<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Google_Client;
use Google_Service_Calendar;

//use Google_Service_Calendar_Event;
//use Google_Service_Calendar_EventDateTime;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use Illuminate\Validation\Rule;
use Session;
use ImageOptimizer;
use Carbon\Carbon;
use App\library\GoogleCalendarWrapper;

use Illuminate\Support\Facades\Validator;
use App\Event;
use App\EventAttendee;
use App\library\GoogleCalendar;
use App\Http\Controllers\MyAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\EventRequest;

class EventsController extends MyAppController
{
    use funcsTrait;
    protected $client;
    private $events_tb;
    private $event_attendees_tb;
    private $users_tb;

    public function __construct()
    {
        $this->events_tb          = with(new Event)->getTable();
        $this->event_attendees_tb = with(new EventAttendee)->getTable();
        $this->users_tb           = with(new User)->getTable();
        $client                   = new Google_Client();
        $client->setAuthConfig('client_secret.json');
//        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(/*CURLOPT_SSL_VERIFYPEER => false*/)));
        $client->setHttpClient($guzzleClient);  // Only for development mode

        $this->client = $client;

    }

    function __destruct()
    {
//        if ( !empty($this->client) ) {
//            $service = new Google_Service_Calendar($this->client);
//            $calendar = $service->calendars->clear('primary');
//            $this->debToFile(print_r($calendar, true), '  __destruct -321 $calendar ::');
//        }
    }

    // EVENT LISTING/EDITOR BLOCK BEGIN

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request           = request();

        session_start();
        $googleCalendarWrapper = new GoogleCalendarWrapper( config('app.GOOGLE_CALENDAR_ID'), (new Event) );
//        if (!$googleCalendarWrapper->checkHasAccessToken($_SESSION, -1)) {
//        } // if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//        else {
//            return redirect()->route('admin.oauthAdminCallback');
//        }

        $filter_event_name = $request->input('filter_event_name', '');

        $filter_start_date = $request->input('filter_start_date');
        $filter_end_date   = $request->input('filter_end_date');
        if ( ! empty($filter_end_date)) {
            $filter_end_date = $filter_end_date . ' 23:59:59';
        }
        $filter_report_type = $request->input('filter_report_type', '');
        $filter_type        = $request->input('filter_type', '');
        $filter_is_public   = $request->input('filter_is_public', '');
        $filter_is_published= $request->input('filter_is_published', '');
        $filter_status      = $request->input('filter_status', '');
        $viewParamsArray    = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            [
                'filter_event_name'  => $filter_event_name,
                'filter_start_date'  => $filter_start_date,
                'filter_end_date'    => $filter_end_date,
                'filter_report_type' => $filter_report_type,
                'filter_type'        => $filter_type,
                'filter_is_public'   => $filter_is_public,
                'filter_is_published'=> $filter_is_published,
                'filter_status'      => $filter_status,
            ]);
        $viewParamsArray['current_google_calendar'] = config('app.GOOGLE_CALENDAR_NAME');



        $moment_time_label_format       = config('app.moment_time_label_format', 'H:mm A');

        $appParamsForJSArray['moment_time_label_format']= $moment_time_label_format;

        $viewParamsArray['eventTypeValueArray']       = $this->SetArrayHeader(['' => ' -Select Type- '],
            Event::getEventTypeValueArray(false));

        $viewParamsArray['eventIsPublicValueArray']       = $this->SetArrayHeader(['' => ' -Select Is Public- '],
            Event::getEventIsPublicValueArray(false));
        
        $viewParamsArray['eventPublishedValueArray']       = $this->SetArrayHeader(['' => ' -Select Is Published- '],
            Event::getEventPublishedValueArray(false));

        $viewParamsArray['eventStatusValueArray']     = $this->SetArrayHeader(['' => ' -Select Status- '],
            Event::getEventStatusValueArray(false));
        $viewParamsArray['eventReportTypeValueArray'] = $this->SetArrayHeader(['' => ' -Select Report Type- '],
            Event::getEventReportTypeValueArray(false));


        $appParamsForJSArray['start_date_formatted']  = config('app.calendar_events_default_date');
//        echo '<pre>$appParamsForJSArray[\'start_date_formatted\']::'.print_r($appParamsForJSArray['start_date_formatted'],true).'</pre>';
        $viewParamsArray['appParamsForJSArray']       = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.event.index', $viewParamsArray);
    }

    public function get_events_fc_listing()
    {
        $request     = request();
        $requestData = $request->all();
        $this->debToFile(print_r($requestData, true), '  get_events_fc_listing -21 $requestData ::');

        $filter_event_name = $request->input('filter_event_name', '');
        $filter_start_date = $request->input('filter_start_date');
        $filter_end_date   = $request->input('filter_end_date');
        if ( ! empty($filter_end_date)) {
            $filter_end_date = $filter_end_date . ' 23:59:59';
        }
        $filter_type        = $request->input('filter_type', '');
        $filter_is_public   = $request->input('filter_is_public', '');
        $filter_is_published = $request->input('filter_is_published', '');
        $filter_status      = $request->input('filter_status', '');

        $events     = [];
        $tempEvents = Event
            ::getByEventName($filter_event_name, true)
            ->getByStartDate($filter_start_date, ' > ')
            ->getByEndDate($filter_end_date, ' <= ')
            ->getByStatus($filter_status, true)
            ->getByType($filter_type)
            ->getByIsPublic($filter_is_public)
            ->getByPublished($filter_is_published)
            ->get();


        $mysql_to_carbon_datetime_format       = config('app.mysql_to_carbon_datetime_format', 'Y-m-d H:i:s');

        foreach ($tempEvents as $nextTempEvent) {
            $type_color = Event::getEventTypeColorLabel($nextTempEvent->type);

            $this->debToFile(print_r($requestData, true), '  get_events_fc_listing -21 $requestData ::');
            $this->debToFile(print_r($nextTempEvent->start_date, true), '  get_events_fc_listing -210 $nextTempEvent->start_date ::');

            $startDate= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $nextTempEvent->start_date);
//            $this->debToFile(print_r($startDate, true), '  get_events_fc_listing -20 $startDate ::');

            $endDate= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $nextTempEvent->end_date);
//            $this->debToFile(print_r($endDate, true), '  get_events_fc_listing -20 $endDate ::');

            $attendees_count = EventAttendee
                ::getByEvent($nextTempEvent->id)
                ->count();
            $events[]   = [
                'event_id'    => $nextTempEvent->id,
                'title'       => $nextTempEvent->event_name,
                'start'       => $nextTempEvent->start_date,
                'end'         => $nextTempEvent->end_date,
                'description' => substr(strip_tags($nextTempEvent->description), 0, 200),
                'url'         => ! empty($nextTempEvent->calendar_event_html_Link) ? $nextTempEvent->calendar_event_html_Link : '',
                'color'       => $type_color,
                'textColor'   => 'yellow',
                'attendees_count' => $attendees_count,
                'is_same_day' => $startDate->isSameDay( $endDate ),
            ];
        }

        $calendar_events_default_date = config('app.calendar_events_default_date');
        if (!empty($filter_start_date)) {
            $calendar_events_default_date= $filter_start_date;
        }
        return response()->json(['error_code' => 0, 'message' => '', 'events' => $events, 'calendar_events_default_date'=> $calendar_events_default_date], HTTP_RESPONSE_OK);
    } // public function get_events_fc_listing()

    public function load_event_attendees($event_id)
    {
        $eventAttendees = EventAttendee
            ::getByEvent($event_id)
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

        $activeUsers     = [];
        $tempActiveUsers = User::getUsersSelectionArray('A');
        foreach ($tempActiveUsers as $nextTempActiveUser_id => $nextTempActiveUser_name) {
            $is_found = false;
            foreach ($eventAttendees as $nextEventAttendee_id => $nextEventAttendee_name) {
                if ($nextEventAttendee_id == $nextTempActiveUser_id) {
                    $is_found = true;
                    break;
                }
            }
            if ( ! $is_found) {
                $activeUsers[$nextTempActiveUser_id] = $nextTempActiveUser_name;
            }
        }


        $viewParamsArray['eventAttendees'] = $eventAttendees;
        $viewParamsArray['activeUsers']    = $activeUsers;
        $html                              = view($this->getBackendTemplateName() . '.admin.event.load_event_attendees',
            $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function load_event_attendees()

    public function clear_event_attendee(Request $request)
    {
        $event_attendee_id = $request->get('event_attendee_id');
        $eventAttendee     = EventAttendee::find($event_attendee_id);

        if ($eventAttendee === null) {
            return response()->json([
                'error_code' => 11,
                'message'    => 'Event Attendee # "' . $event_attendee_id . '" not found !'
            ],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $eventAttendee->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } // public function clear_event_attendee()

    public function add_active_users_to_event(Request $request)
    {
        $event_id = $request->get('event_id');
        $event    = Event::find($event_id);

        if ($event === null) {
            return response()->json(['error_code' => 11, 'message' => 'Event # "' . $event_id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        $activeUsersList = $request->get('activeUsersList');

        if ( ! empty($activeUsersList) and is_array($activeUsersList)) {
            DB::beginTransaction();
            try {
                foreach ($activeUsersList as $next_active_user_id) {
                    $newEventAttendee                   = new EventAttendee();
                    $newEventAttendee->event_id         = $event_id;
                    $newEventAttendee->attendee_user_id = $next_active_user_id;
                    $newEventAttendee->save();
                }
                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();

                return response()->json(['error_code' => 1, 'message' => $e->getMessage()],
                    HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } // public function add_active_users_to_event()

    public function add_external_user_event(Request $request)
    {
        $event_id = $request->get('event_id');
        $event    = Event::find($event_id);

        if ($event === null) {
            return response()->json(['error_code' => 11, 'message' => 'Event # "' . $event_id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        $new_attendee_user_email        = $request->get('new_attendee_user_email');
        $new_attendee_user_display_name = $request->get('new_attendee_user_display_name');

        DB::beginTransaction();
        try {
            $newEventAttendee                             = new EventAttendee();
            $newEventAttendee->event_id                   = $event_id;
            $newEventAttendee->attendee_user_email        = $new_attendee_user_email;
            $newEventAttendee->attendee_user_display_name = $new_attendee_user_display_name;
            $newEventAttendee->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_CREATED);
    } // public function add_external_user_event()

    public function get_event_dt_listing()
    {
        $request     = request();
        $requestData = $request->all();
        $this->debToFile(print_r($requestData, true), '  get_event_dt_listing -21 $requestData ::');

        $filter_event_name = $request->input('filter_event_name', '');
        $filter_start_date = $request->input('filter_start_date');
        $filter_end_date   = $request->input('filter_end_date');
        if ( ! empty($filter_end_date)) {
            $filter_end_date = $filter_end_date . ' 23:59:59';
        }
        $filter_type        = $request->input('filter_type', '');
        $filter_is_public   = $request->input('filter_is_public', '');
        $filter_is_published= $request->input('filter_is_published', '');
        $filter_status      = $request->input('filter_status', '');

        $eventsCollection = Event
            ::getByEventName($filter_event_name, true)
            ->getByStartDate($filter_start_date, ' > ')
            ->getByEndDate($filter_end_date, ' <= ')
            ->getByStatus($filter_status, true)
            ->getByType($filter_type)
            ->getByIsPublic($filter_is_public)
            ->getByPublished($filter_is_published)
            ->get();

        foreach ($eventsCollection as $next_key => $nextEvent) {
            $eventsCollection[$next_key]->slashed_text = addslashes($nextEvent->event_name);
        }

        return Datatables
            ::of($eventsCollection)
            ->editColumn('start_date', function ($event) {
                if (empty($event->start_date)) {
                    return '';
                }

                return $this->getCFFormattedDateTime($event->start_date);
            })
            ->editColumn('end_date', function ($event) {
                if (empty($event->end_date)) {
                    return '';
                }

                return $this->getCFFormattedDateTime($event->end_date);
            })
            ->editColumn('assigned_to_google_calendar', function ($event) {
                if ( ! empty($event->calendar_event_id) and ! empty($event->calendar_event_html_Link)) {
                    return '<a href="' . $event->calendar_event_html_Link . '" target="_blank"><i class="fa fa-calendar" title="Assigned to google calendar"></i></a>';
//                    return 'Assigned to google calendar ' . '<a href="' . $event->calendar_event_html_Link . '" target="_blank"><i class="fa fa-calendar"></i></a>';
                }

                return '';
            })
            ->editColumn('created_at', function ($event) {
                if (empty($event->created_at)) {
                    return '';
                }

                return $this->getCFFormattedDateTime($event->created_at);
            })
            ->editColumn('type', function ($event) {
                if (empty($event->type)) {
                    return '';
                }

                return Event::getEventTypeLabel($event->type);
            })
            ->editColumn('is_public', function ($event) {
                if (empty($event->is_public)) {
                    return '';
                }

                return Event::getEventIsPublicLabel($event->is_public);
            })
            ->editColumn('action', '<a href="/admin/events/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendEvent.deleteEvent({{$id}},\'{{$slashed_text}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['assigned_to_google_calendar', 'action', 'action_delete'])
            ->make(true);
    }

    public function create()
    {

        $viewParamsArray                                           = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['current_google_calendar']                = config('app.GOOGLE_CALENDAR_NAME');
        $viewParamsArray['eventTypeValueArray']                    = $this->SetArrayHeader(['' => ' -Select Type- '],
            Event::getEventTypeValueArray(false));
        $viewParamsArray['eventIsPublicValueArray']                = $this->SetArrayHeader(['' => ' -Select Is Public- '],
            Event::getEventIsPublicValueArray(false));

        $viewParamsArray['eventPublishedValueArray']               = $this->SetArrayHeader(['' => ' -Select Is Published- '],
            Event::getEventPublishedValueArray(false));

        $viewParamsArray['eventTypeColorValueArray']               = $this->SetArrayHeader(['' => ' -Select Type Color- '],
            Event::getEventTypeColorValueArray(false));
        $viewParamsArray['eventTypeGoogleCalendarColorValueArray'] = $this->SetArrayHeader(['' => ' -Select Type- '],
            Event::getEventTypeGoogleCalendarColorValueArray(false));
        $viewParamsArray['event']                                  = new Event();
        $viewParamsArray['event']->start_date                      = Carbon::now()->addDay(1)->format('Y-m-d');
        $viewParamsArray['event']->end_date                        = Carbon::now()->addDay(2)->format('Y-m-d');
        $viewParamsArray['event']->published                       = true;
        $viewParamsArray['event']->is_public                       = true;
//        $viewParamsArray['event']->description                     = 'description ' . now();
        $appParamsForJSArray                                       = $viewParamsArray;
        $viewParamsArray['appParamsForJSArray']                    = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.event.create', $viewParamsArray);
    }

    public function store(EventRequest $request)
    {

        $loggedUser            = Auth::user();
        $newEvent              = new Event();
        $newEvent->event_name  = $request->get('event_name');
        $newEvent->start_date  = $request->get('start_date');
        $newEvent->end_date    = $request->get('end_date');
        $newEvent->type        = $request->get('type');
        $newEvent->is_public   = $request->get('is_public');
        $newEvent->location    = $request->get('location');
        $newEvent->description = $request->get('description');
        $newEvent->creator_id  = $loggedUser->id;

        DB::beginTransaction();
        try {
            $newEvent->save();//

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['text' => $request->get('text')]);
        }
        $this->setFlashMessage('Event created successfully !', 'success', 'Backend');

        return redirect('admin/events/' . $newEvent->id . '/edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Event $event
     *
     * @return \Illuminate\Http\Response
     */

    public function edit($event_id)
    {
        $event           = Event::find($event_id);
        $viewParamsArray = $this->getAppParameters(true, ['csrf_token']);
        if ($event === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'Event with id # "' . $event_id . '" not found !',
                'type'   => 'danger',
                'action' => ''
            ],
                $viewParamsArray);
        }

        $event->status_label                                       = Event::getEventStatusLabel($event->status);
        $viewParamsArray['eventTypeValueArray']                    = $this->SetArrayHeader(['' => ' -Select Type- '],
            Event::getEventTypeValueArray(false));
        $viewParamsArray['eventIsPublicValueArray']                = $this->SetArrayHeader(['' => ' -Select Is Public- '],
            Event::getEventIsPublicValueArray(false));

        $viewParamsArray['eventPublishedValueArray']               = $this->SetArrayHeader(['' => ' -Select Is Published- '],
            Event::getEventPublishedValueArray(false));

        $viewParamsArray['eventTypeColorValueArray']               = $this->SetArrayHeader(['' => ' -Select Color- '],
            Event::getEventTypeColorValueArray(false));
        $viewParamsArray['eventTypeGoogleCalendarColorValueArray'] = $this->SetArrayHeader(['' => ' -Select Type- '],
            Event::getEventTypeGoogleCalendarColorValueArray(false));

//            echo '<pre>$viewParamsArray::'.print_r($viewParamsArray,true).'</pre>';
//        die("-1 XXZ");
        $viewParamsArray['event'] = $event;
        $viewParamsArray['id']    = $event_id;

        $viewParamsArray['current_google_calendar'] = config('app.GOOGLE_CALENDAR_NAME');
        $appParamsForJSArray                        = $viewParamsArray;
        $viewParamsArray['appParamsForJSArray']     = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.event.edit', $viewParamsArray);
    }


    public function update(EventRequest $request, int $event_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        $event = Event::find($event_id);
        if ($event === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'Event with id # "' . $event_id . '" not found !',
                'type'   => 'danger',
                'action' => ''
            ],
                $viewParamsArray);
        }
        $form_action = $request->get('form_action');

        $event->event_name  = $request->get('event_name');
        $event->start_date  = $request->get('start_date');
        $event->end_date    = $request->get('end_date');
        $event->type        = $request->get('type');
        $event->is_public   = $request->get('is_public');
        $event->location    = $request->get('location');
        $event->description = $request->get('description');
        $event->updated_at  = Carbon::now(config('app.timezone'));
        if ( !empty($form_action) ) {
            $event->status = "M"; // 'N' => 'New', 'M' => 'Modified', 'U' => 'Unmodified' );
        }

        DB::beginTransaction();
        try {
            $event->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('Event updated successfully !', 'success', 'Backend');

        if ($form_action == 'calendarActionUpdate') { // current event must be updated at Google Calendar
            // id, event_name, start_date, end_date, status, type, location, description,
            // calendar_event_id, calendar_event_html_Link, creator_id, created_at, updated_at
            if ( ! empty($event->calendar_event_id)) {
                session_start();
                $googleCalendarWrapper = new GoogleCalendarWrapper(config('app.GOOGLE_CALENDAR_ID'), $event);
                if ($googleCalendarWrapper->checkHasAccessToken($_SESSION, -1)) {
                    $googleCalendarWrapper->updateCalendarEvent($this->client, $_SESSION, $request);
                    $this->setFlashMessage('New event was successfully updated at Google calendar!', 'success',
                        'Backend');

                    return redirect()->route('admin.events.index');
                } // if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                else {
//                $this->debToFile(print_r('REDIRECT', true), '  calendarActionUpdate -23 REDIRECT ::');
                    return redirect()->route('admin.oauthAdminCallback');
                }
            } // if ( !empty($event->calendar_event_id) ) {

            else { // this event must be inserted
                $form_action = 'CalendarActionInsert';
//                $this->debToFile(print_r($form_action, true), '  SEETTING CalendarActionInsert $form_action::');

            }

        } //if ( $form_action == 'calendarActionUpdate' ) { // current event must be updated at Google Calendar

        if ($form_action == 'CalendarActionInsert') {
            session_start();

            $googleCalendarWrapper = new GoogleCalendarWrapper(config('app.GOOGLE_CALENDAR_ID'), $event);
            if ($googleCalendarWrapper->checkHasAccessToken($_SESSION, -3)) {
                $googleCalendarWrapper->insertCalendarEvent($this->client, $_SESSION, $request);
                $this->setFlashMessage('New event was successfully added at Google calendar !', 'success', 'Backend');

                return redirect()->route('admin.events.index');

//                return response()->json(['status' => 'success', 'message' => 'Event Created', 'id' => $results->id, 'htmlLink' => $results->htmlLink]);
            }  // if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            else {
                return redirect()->route('admin.oauthAdminCallback');
            }

        } // if ( $form_action == 'CalendarActionInsert' ) {


        if ($form_action == 'calendarActionDelete') {
            session_start();
            echo '<pre>$event->calendar_event_id::' . print_r($event->calendar_event_id, true) . '</pre>';

//            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $googleCalendarWrapper = new GoogleCalendarWrapper(config('app.GOOGLE_CALENDAR_ID'), $event);
            if ($googleCalendarWrapper->checkHasAccessToken($_SESSION, -2)) {
                $googleCalendarWrapper = new GoogleCalendarWrapper(config('app.GOOGLE_CALENDAR_ID'), $event);
                $googleCalendarWrapper->deleteCalendarEvent($this->client, $_SESSION, $request);
                $this->setFlashMessage('New event was successfully deleted at Google calendar !', 'success', 'Backend');

                return redirect()->route('admin.events.index');
            } // if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            else {
                return redirect()->route('admin.oauthAdminCallback');
            }

        } //if ( $form_action == 'calendarActionDelete' ) {

        return redirect('admin/events/' . $event_id . '/edit'); // TO COMMENT
    }


    /* delete event with related */
    public function destroy(Request $request)
    {
        $id    = $request->get('id');
        $event = Event::find($id);

        if ($event === null) {
            return response()->json(['error_code' => 11, 'message' => 'Event # "' . $id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $event->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)


    // EVENT LISTING/EDITOR BLOCK END

    //oauthAdminCallback      // admin/oauthAdminCallback
    public function oauthAdminCallback()
    {
        session_start();
        $this->debToFile(print_r(-1, true), '  oauthAdminCallback  -1 ::');

        $rurl = action('Admin\EventsController@oauthAdminCallback'); // ???
        $this->client->setRedirectUri($rurl);
        if ( ! isset($_GET['code'])) {
            $this->debToFile(print_r(-2, true), '  oauthAdminCallback  -2 ::');
            $auth_url     = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);

            return redirect($filtered_url);
        } else {
            $this->debToFile(print_r(-3, true), '  oauthAdminCallback  -3 ::');
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();

            $this->setFlashMessage('Successfully connected at Google calendar !', 'success', 'Backend');

            return redirect()->route('admin.events.index');
        }
    }


    public function synchronize_google_events($filter_type = '', $filter_value = '')
    {
        session_start();
        $request     = request();
        $loggedUser  = Auth::user();
        $form_action = $request->get('form_action');
//        echo '<pre>$form_action::' . print_r($form_action, true) . '</pre>';
        $googleCalendarWrapper = new GoogleCalendarWrapper(config('app.GOOGLE_CALENDAR_ID'));
//        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        if ($googleCalendarWrapper->checkHasAccessToken($_SESSION, -1)) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId                       = config('app.GOOGLE_CALENDAR_ID');
            $results                          = $service->events->listEvents($calendarId);
            $eventItems                       = [];
            $new_google_calendar_events_count = 0;
            $tempEventItems                   = $results->getItems();
//            echo '<pre>$tempEventItems::'.print_r($tempEventItems,true).'</pre>';
//            die("-1 XXZ");
            //     public static function getSimilarUserByEmail( string $email, int $id= null, bool $return_count = false )
            $this->loggedUser = Auth::user();
            foreach ($tempEventItems as $next_key => $nextTempEventItem) {
//                if ( $nextTempEventItem->summary != 'Ktulu selebration' ) continue;

                $creator_id  = $this->loggedUser->id;
                $similarUser = User::getSimilarUserByEmail($nextTempEventItem->creator->email);
                if ( ! empty($similarUser->id)) {
                    $creator_id = $similarUser->id;
                }
                $start_date  = Carbon::parse($nextTempEventItem->start->date);
                $end_date    = Carbon::parse($nextTempEventItem->start->date);
                $created     = Carbon::parse($nextTempEventItem->created);
                $updated     = Carbon::parse($nextTempEventItem->updated);
                $description = $nextTempEventItem->description;
                $location    = $nextTempEventItem->location;

                $start_date_formatted = $this->getCFFormattedDateTime($start_date->timestamp);
                $end_date_formatted   = $this->getCFFormattedDateTime($end_date->timestamp);
                $created_formatted    = $this->getCFFormattedDateTime($created->timestamp);
                $updated_formatted    = $this->getCFFormattedDateTime($updated->timestamp);
//                return $this->getCFFormattedDateTime($event->created_at);

                $relatedEvents     = [];
                $tempRelatedEvents = Event
                    ::getByCalendarEventId($nextTempEventItem->id)
                    ->get();

                //    protected $fillable = [ 'event_name', 'start_date', 'end_date', 'description', 'creator_id' ];
                foreach ($tempRelatedEvents as $nextTempRelatedEvent) {
                    $relatedEvents[] = [
                        'event_id'    => $nextTempRelatedEvent->id,
                        'event_name'  => $nextTempRelatedEvent->event_name,
                        'start_date'  => $nextTempRelatedEvent->start_date,
                        'end_date'    => $nextTempRelatedEvent->end_date,
                        'description' => $nextTempRelatedEvent->description,
                        'location'    => $nextTempRelatedEvent->location,
                    ];
                }


                //     public static function getSimilarEventByEventName( string $event_name, int $id= null, bool $return_count = false )
                $similar_db_event_id = null;
                $similarEvent        = Event::getSimilarEventByEventName($nextTempEventItem->summary);
                if ( ! empty($similarEvent->id)) {
                    $similar_db_event_id = $similarEvent->id;
                } else {
                    $new_google_calendar_events_count++;
                }

                $eventTypeGoogleCalendarColorValueArray = Event::getEventTypeGoogleCalendarColorValueArray(false);
//                echo '<pre>$eventTypeGoogleCalendarColorValueArray::'.print_r($eventTypeGoogleCalendarColorValueArray,true).'</pre>';
//                echo '<pre>$nextTempEventItem::'.print_r($nextTempEventItem,true).'</pre>';
//                echo '<pre>$nextTempEventItem->summary::'.print_r($nextTempEventItem->summary,true).'</pre>';

                $colorId = $nextTempEventItem->colorId;
//                echo '<pre>$colorId::'.print_r($colorId,true).'</pre>';

                $event_type = 'O';
                foreach ($eventTypeGoogleCalendarColorValueArray as $next_type => $next_color_id) {
                    if ((int)$next_color_id == (int)$colorId) {
                        $event_type = $next_type;
                    }
                }
//                echo '<pre>$event_type::'.print_r($event_type,true).'</pre>';
//                die("-1 XXZ");
                $attendeesArray     = [];
                $tempAttendeesArray = $nextTempEventItem->attendees;
                foreach ($tempAttendeesArray as $nextTempAttendee) {
                    $attendeesArray[] = [
                        'email'       => $nextTempAttendee->email,
                        'displayName' => $nextTempAttendee->displayName
                    ];
                }
//                echo '<pre>$attendeesArray::'.print_r($attendeesArray,true).'</pre>';
                $eventItems[] = [
                    'similar_db_event_id'      => $similar_db_event_id,
                    'calendar_event_id'        => $nextTempEventItem->id,
                    'event_name'               => $nextTempEventItem->summary,
                    'attendeesArray'           => $attendeesArray,
                    'type'                     => $event_type,
                    'description'              => $nextTempEventItem->description,
                    'location'                 => $nextTempEventItem->location,
                    'calendar_event_html_Link' => $nextTempEventItem->htmlLink,
                    'creator_username'         => $nextTempEventItem->creator->displayName,
                    'creator_email'            => $nextTempEventItem->creator->email,
                    'start_date_formatted'     => $start_date_formatted,
                    'start_date'               => $start_date,
                    'end_date_formatted'       => $end_date_formatted,
                    'end_date'                 => $end_date,
                    'created_formatted'        => $created_formatted,
                    'updated_formatted'        => $updated_formatted,
                    'creator_id'               => $creator_id,
                    'relatedEvents'            => $relatedEvents,
                ];
//                echo '<pre>$eventItems::'.print_r($eventItems,true).'</pre>';
//                die("-1 XXZ");
            }

            if ($form_action == "import_calendar_event_into_db") {

                $calendar_events_added_count       = 0;
                $checked_calendar_events_selection = $request->get('form_action_items');
//                $requestData= $request->all();
//                echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
                $checkedCalendarEventsSelectionIds = $this->pregSplit('~,~', $checked_calendar_events_selection);

                DB::beginTransaction();
                try {
                    foreach ($checkedCalendarEventsSelectionIds as $nextCheckedCalendarEventsSelectionId) {
//                        echo '<pre>$nextCheckedCalendarEventsSelectionId::' . print_r($nextCheckedCalendarEventsSelectionId, true) . '</pre>';

                        foreach ($eventItems as $nextEventItem) {
                            if ($nextEventItem['calendar_event_id'] == $nextCheckedCalendarEventsSelectionId) {
                                $newEvent                           = new Event();
                                $newEvent->event_name               = $nextEventItem['event_name'];
                                $newEvent->calendar_event_id        = $nextEventItem['calendar_event_id'];
                                $newEvent->calendar_event_html_Link = $nextEventItem['calendar_event_html_Link'];
                                $newEvent->description              = $nextEventItem['description'];
                                $newEvent->status                   = 'U';
                                $newEvent->type                     = $nextEventItem['type'];
                                $newEvent->location                 = $nextEventItem['location'];
                                $newEvent->start_date               = $nextEventItem['start_date'];
                                $newEvent->end_date                 = $nextEventItem['end_date'];
                                $newEvent->creator_id               = $loggedUser->id;
                                $newEvent->save();
                                $calendar_events_added_count++;

//                                $this->debToFile(print_r($nextEventItem, true), ' -99 $nextEventItem ::');

//                                echo '<pre>-99 $nextEventItem::'.print_r($nextEventItem,true).'</pre>';
                                if ( ! empty($nextEventItem['attendeesArray']) and is_array($nextEventItem['attendeesArray'])) {
                                    echo '<pre>INSIDE</pre>';
                                    foreach ($nextEventItem['attendeesArray'] as $nextNextEventItemAttendee) {
                                        echo '<pre>$nextNextEventItemAttendee[email]::' . print_r($nextNextEventItemAttendee['email'],
                                                true) . '</pre>';
                                        //                        $eventAttendees[]= ["email" => $nextTempEventAttendee->attendee_user_email, "displayName" => $nextTempEventAttendee->attendee_user_display_name ];
                                        $similarUser = User::getSimilarUserByEmail($nextNextEventItemAttendee['email']);
//                                        echo '<pre>$similarUser::'.print_r($similarUser,true).'</pre>';
                                        if (empty($similarUser->id)) {
                                            $newEventAttendee                             = new EventAttendee();
                                            $newEventAttendee->event_id                   = $newEvent->id;
                                            $newEventAttendee->attendee_user_email        = $nextNextEventItemAttendee['email'];
                                            $newEventAttendee->attendee_user_display_name = $nextNextEventItemAttendee['displayName'];
                                            $newEventAttendee->save();
                                        } else {
                                            $newEventAttendee                   = new EventAttendee();
                                            $newEventAttendee->event_id         = $newEvent->id;
                                            $newEventAttendee->attendee_user_id = $similarUser->id;
                                            $newEventAttendee->save();
                                        }
                                    }
                                }
                            }
                        }
                    } // foreach( $checkedCalendarEventsSelectionIds as $nextCheckedCalendarEventsSelectionId ) {

                    DB::commit();


//                    die("-1 XXZ");
                } catch (\Exception $e) {
                    DB::rollback();
                    $this->setFlashMessage($e->getMessage(), 'danger');

                    return Redirect
                        ::back()
                        ->withErrors([$e->getMessage()])
                        ->withInput(['text' => $request->get('text')]);
                }
                $this->setFlashMessage($calendar_events_added_count . ' Google Calendar events successfully added !',
                    'success', 'Backend');

                return redirect()->route('admin.events.index');
            }

            reset($eventItems);
            $viewParamsArray                                     = $appParamsForJSArray = $this->getAppParameters(true,
                ['csrf_token'],
                ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
            $viewParamsArray['filter_type']                      = $filter_type;
            $viewParamsArray['filter_value']                     = $filter_value;
            $viewParamsArray['eventItems']                       = $eventItems;
            $viewParamsArray['new_google_calendar_events_count'] = $new_google_calendar_events_count;
            $viewParamsArray['appParamsForJSArray']              = json_encode($appParamsForJSArray);

            return view($this->getBackendTemplateName() . '.admin.event.synchronize_google_events', $viewParamsArray);

        } else {
//            return redirect()->route('oauthCallback');
            return redirect()->route('admin.oauthAdminCallback');
        }

    } // public function synchronize_google_events()

    public function add_demo_events()
    {
        $eventTypeValueArray = array_keys(Event::getEventTypeValueArray(false));
        $users               = User::orderBy('username', 'desc')->getByStatus('A')->get();

        DB::beginTransaction();
        try {
            for ($i = 0; $i < 10; $i++) {
                $d      = rand(0, 6);
                $h      = rand(0, 23);
                $length = rand(0, 10) * 10;
                $type   = rand(0, count($eventTypeValueArray) - 1);

                $now_label = Carbon::now()->format('Y-m-d hh:mm:ss');

                $now        = Carbon::now();
                $start_date = $now->startOfWeek()->addDay($d)->addHour($h);//->format('Y-m-d hh:mm:ss');
                $end_date   = $start_date->addMinute($length);

                $loggedUser            = Auth::user();
                $newEvent              = new Event();
                $newEvent->event_name  = 'event_name _' . $now_label . ' _ ' . $i;
                $newEvent->start_date  = $start_date;
                $newEvent->end_date    = $end_date;
                $newEvent->type        = $eventTypeValueArray[$type];
                $newEvent->is_public   = true;
                $newEvent->location    = 'location _' . $now_label . ' _ ' . $i;
                $newEvent->description = 'description _' . $now_label . ' _ ' . $i;
                $newEvent->creator_id  = $loggedUser->id;
                $newEvent->save();

                $j = 0;
                foreach ($users as $nextUser) {
                    $r= rand(1,10);
                    if ($r < 3) {
                        $newEventAttendee                   = new EventAttendee();
                        $newEventAttendee->event_id         = $newEvent->id;
                        $newEventAttendee->attendee_user_id = $nextUser->id;
                        $newEventAttendee->save();
                        $j++;
                    }
                }

            }

            DB::commit();

            $this->setFlashMessage('Demo events created successfully !', 'success', 'Backend');
            return redirect('admin/events');

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }


    } // public function add_demo_events() {


}
