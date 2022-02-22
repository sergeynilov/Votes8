<?php namespace App\library {


//use Illuminate\Http\Request;

    use App\Http\Traits\funcsTrait;

    class GoogleCalendar
    {
        use funcsTrait;

        public function insertCalendarEvents(array $newEventArray)
        {
            $this->debToFile(print_r($newEventArray, true), '  calendar_add_event -1 $newEventArray ::');

            session_start();
            $startDateTime = $newEventArray['start_date'];
            $endDateTime   = $newEventArray['end_date'];

            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                $calendarId = 'primary';
                $calendarId = 'softreactor.com_lm318l77fa39mod5pgfj58ela0@group.calendar.google.com';
                $newEvent   = [
                    'summary'               => $newEventArray['title'],
                    'description'           => $newEventArray['description'],
                    'start'                 => ['dateTime' => $startDateTime],
                    'end'                   => ['dateTime' => $endDateTime],
                    'reminders'             => ['useDefault' => true],
                ];

                $this->debToFile(print_r($newEvent, true), '  calendar_add_event -2 $newEvent ::');
                $event   = new Google_Service_Calendar_Event($newEvent);
                $results = $service->events->insert($calendarId, $event);
                $this->debToFile(print_r($results, true), '  calendar_add_event  -3 $results ::');
                if ( ! $results) {
                    return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
                }

                return response()->json(['status' => 'success', 'message' => 'Event Created', 'id' => $results->id, 'htmlLink' => $results->htmlLink]);
            } else {
                return redirect()->route('oauthCallback');
            }
        } // public function insertCalendarEvents(array $newEventArray)


    }
}