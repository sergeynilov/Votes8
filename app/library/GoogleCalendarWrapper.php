<?php namespace App\library {

    use App\Event;
    use App\EventAttendee;
    use Auth;
    use Carbon\Carbon;
    use DB;

    use Google_Service_Calendar;
    use Google_Service_Calendar_Event;
    use Google_Service_Calendar_EventDateTime;

    use App\User;
    use App\Settings;
    use App\Http\Traits\funcsTrait;
    use Illuminate\Support\Facades\Redirect;


    class GoogleCalendarWrapper
    {
        use funcsTrait;
        private $calendarId;
        private $event;

        private $events_tb;
        private $event_attendees_tb;
        private $users_tb;

        //        private $channel_id='UCNd4Tz3KdWmLFsPvwk4HzMg';
//        private $video_id='teOx47wgln8';

        public function __construct($calendarId, $event= null)
        {
            $this->events_tb          = with(new Event)->getTable();
            $this->event_attendees_tb = with(new EventAttendee)->getTable();
            $this->users_tb           = with(new User)->getTable();
            $this->calendarId         = $calendarId;
            if ( !empty($event) ) {
                $this->event = $event;
            }
        }

/*        public function makeOauthAdminCallback($sessionArray, $source='')
        {
            $this->debToFile(print_r($_SESSION, true), $source.'  checkHasAccessToken?? $_SESSION ::');
            $this->debToFile(print_r($sessionArray, true), $source.'  checkHasAccessToken?? $sessionArray ::');

            if (isset($sessionArray['access_token']) && $sessionArray['access_token']) {
                $this->debToFile(print_r('true', true), $source.'  checkHasAccessToken -1 true ::');
                return true;
            }

            $this->debToFile(print_r('false', true), $source.'  checkHasAccessToken -1 false ::');
            return false;
        } // public function makeOauthAdminCallback($sessionArray, $source='')
        //                $googleCalendarWrapper->makeOauthAdminCallback($form_action);*/


        public function checkHasAccessToken($sessionArray, $source='')
        {
            $this->debToFile(print_r($_SESSION, true), $source.'  checkHasAccessToken?? $_SESSION ::');
            $this->debToFile(print_r($sessionArray, true), $source.'  checkHasAccessToken?? $sessionArray ::');

            if (isset($sessionArray['access_token']) && $sessionArray['access_token']) {
                $this->debToFile(print_r('true', true), $source.'  checkHasAccessToken -1 true ::');
                return true;
            }

            $this->debToFile(print_r('false', true), $source.'  checkHasAccessToken -1 false ::');
            return false;
        } // public function checkHasAccessToken($sessionArray, $source='')


        public function insertCalendarEvent($client, $sessionArray, $request, $source='')
        {
            $startDateTime = Carbon::parse($request->get('start_date'))->toRfc3339String();
            $endDateTime   = Carbon::parse($request->get('start_date'))->toRfc3339String();
            $eventAttendees= [];
            $tempEventAttendees = EventAttendee
                ::getByEvent($this->event->id)
                ->leftJoin($this->users_tb, $this->users_tb . '.id', '=', $this->event_attendees_tb . '.attendee_user_id')
                ->select(
                    $this->event_attendees_tb . '.*',
                    $this->users_tb . '.id as event_attendees_user_id',
                    $this->users_tb . '.first_name as event_attendees_first_name',
                    $this->users_tb . '.last_name as event_attendees_last_name',
                    $this->users_tb . '.email as event_attendees_email'
                )
                ->orderBy($this->event_attendees_tb . '.created_at', 'asc')
                ->get();
            foreach( $tempEventAttendees as $nextTempEventAttendee ) {
                if ( !empty($nextTempEventAttendee->event_attendees_user_id) ) {
                    $eventAttendees[]= [
                        "email" => $nextTempEventAttendee->event_attendees_email,
                        "displayName" => $nextTempEventAttendee->event_attendees_first_name . ' ' . $nextTempEventAttendee->event_attendees_last_name];
                } else {
                    $eventAttendees[]= ["email" => $nextTempEventAttendee->attendee_user_email, "displayName" => $nextTempEventAttendee->attendee_user_display_name ];
                }
            }

            $color_id= Event::getEventTypeGoogleCalendarColorLabel($this->event->type);
            if (empty($color_id)) $color_id= 8; // Dark Gray

            $newEvent = [
                'summary'     => $request->get('event_name'),
                'description' => $request->get('description'),
                'location'    => $request->get('location'),
                'start'       => ['dateTime' => $startDateTime],
                'end'         => ['dateTime' => $endDateTime],
                'reminders'   => ['useDefault' => true],
                'attendees'   => $eventAttendees,
                'colorId'     => $color_id,
            ];

            $client->setAccessToken($sessionArray['access_token']);
            $googleCalendarService = new Google_Service_Calendar($client);



            $this->debToFile(print_r($this->calendarId, true), '  calendar_add_event -20 $this->calendarId ::');
            $this->debToFile(print_r($newEvent, true), '  calendar_add_event -2 $newEvent ::');
//                $this->debToFile(print_r($eventAttendees, true), '  calendar_add_event -3 $eventAttendees ::');
            $serviceCalendarEvent = new Google_Service_Calendar_Event($newEvent);
            $results              = $googleCalendarService->events->insert($this->calendarId, $serviceCalendarEvent);
            $this->debToFile(print_r($results, true), '  calendar_add_event  -3 $results ::');
            if ( ! $results) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }


            DB::beginTransaction();
            try {
                $this->event->calendar_event_id        = $results->id;
                $this->event->calendar_event_html_Link = $results->htmlLink;
                $this->event->updated_at               = Carbon::now(config('app.timezone'));
                $this->event->status                   = "U";
                $this->event->save();
                DB::commit();
//                    die("-1 XXZ");
            } catch (\Exception $e) {
                DB::rollback();
                $this->setFlashMessage($e->getMessage(), 'danger');

                return Redirect
                    ::back()
                    ->withErrors([$e->getMessage()])
                    ->withInput([]);
            }

            return true;
        } // public function insertCalendarEvent()


        public function updateCalendarEvent( $client, $sessionArray, $request, $source='' ) {

            $client->setAccessToken($_SESSION['access_token']);
            $googleCalendarService = new Google_Service_Calendar($client);
            $startDateTime         = Carbon::parse($request->get('start_date'))->toRfc3339String();
            $endDateTime           = Carbon::parse($request->get('end_date'))->toRfc3339String();

//                echo '<pre>$event->calendar_event_id::'.print_r($event->calendar_event_id,true).'</pre>';
//                die("-1 XXZ");
            $googleCalendarEvent = $googleCalendarService->events->get($this->calendarId, $this->event->calendar_event_id);
            $googleCalendarEvent->setSummary($request->get('event_name'));
            $googleCalendarEvent->setDescription($request->get('description'));
            $googleCalendarEvent->setLocation($request->get('location'));

            $startDateTimeCalendar = new Google_Service_Calendar_EventDateTime();
            $startDateTimeCalendar->setDateTime($startDateTime);
            $googleCalendarEvent->setStart($startDateTimeCalendar);

            $endDateTimeCalendar = new Google_Service_Calendar_EventDateTime();
            $endDateTimeCalendar->setDateTime($endDateTime);
            $googleCalendarEvent->setEnd($endDateTimeCalendar);

            $color_id= Event::getEventTypeGoogleCalendarColorLabel($request->get('type'));
            if (empty($color_id)) $color_id= 8; // Dark Gray

            $this->debToFile(print_r($color_id, true), '  updateCalendarEvent -33 $color_id ::');
//                'colorId'     => $color_id,
//            $googleCalendarEvent->setEventId($color_id); //eventId
            $googleCalendarEvent->setColorId($color_id);

            $this->debToFile(print_r($googleCalendarEvent, true), '  updateCalendarEvent -34 $googleCalendarEvent ::');

            $updateCalendarEvent = $googleCalendarService->events->update($this->calendarId, $this->event->calendar_event_id, $googleCalendarEvent);
            if ($updateCalendarEvent) {

                DB::beginTransaction();
                try {
                    $this->event->updated_at = Carbon::now(config('app.timezone'));
                    $this->event->status     = "U";
                    $this->event->save();
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    $this->setFlashMessage($e->getMessage(), 'danger');

                    return Redirect
                        ::back()
                        ->withErrors([$e->getMessage()])
                        ->withInput([]);
                }

                return redirect()->route('admin.events.index')->with([
                    'text'   => 'The event was successfully updated !',
                    'type'   => 'success',
                    'action' => ''
                ]);
            } else {
                die("-1 XXZ ERROR");
            }

        } //public function updateCalendarEvent() {


        public function deleteCalendarEvent( $client, $sessionArray, $request, $source='' ) {

            $client->setAccessToken($_SESSION['access_token']);
            $googleCalendarService = new Google_Service_Calendar($client);
            $googleCalendarService->events->delete($this->calendarId, $this->event->calendar_event_id);
            DB::beginTransaction();
            try {
                $this->event->calendar_event_id        = null;
                $this->event->calendar_event_html_Link = null;
                $this->event->updated_at               = Carbon::now(config('app.timezone'));
                $this->event->status                   = "N";
                $this->event->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $this->setFlashMessage($e->getMessage(), 'danger');

                return Redirect
                    ::back()
                    ->withErrors([$e->getMessage()])
                    ->withInput([]);
            }

        } //public function deleteCalendarEvent() {


    }



}