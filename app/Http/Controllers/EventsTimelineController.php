<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Redirect;

use App\Settings;
use App\Event;
use App\Http\Traits\funcsTrait;
use App\User;
use Response;
use App\library\CheckValueType;


class EventsTimelineController extends MyAppController
{
    use funcsTrait;
    private $users_tb;
    private $events_tb;

    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->events_tb= with(new Event)->getTable();
    }


    // 2019-08-21 19:50
    public function event($event_slug)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name', 'site_heading', 'site_subheading']);
        $event     = Event
            ::select( $this->events_tb.'.*', 'username as creator_username')
            ->getBySlug($event_slug)
            ->join($this->users_tb, $this->users_tb.'.id', '=', $this->events_tb.'.creator_id')
            ->first();

        if ($event === null or ! $event->published) {
            return View($this->getFrontendTemplateName() . '.msg', ['text' => 'Page "' . $event_slug . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }

        if ($event->page_type == 'E' and ! empty($event->source_type) and ! empty($event->source_url)) {
            return Redirect::to($event->source_url);
        }
        $mysql_to_carbon_datetime_format       = config('app.mysql_to_carbon_datetime_format', 'Y-m-d H:i:s');

        $startDate= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $event->start_date);

        $endDate= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $event->end_date);

        $viewParamsArray['page_slug']             = $event_slug;
        $viewParamsArray['medium_slogan_img_url'] = config('app.medium_slogan_img_url');
        $viewParamsArray['event']           = $event;

        $viewParamsArray['event_type_label']      = Event::getEventTypeLabel($event->type);
        $viewParamsArray['event_type_color']      = Event::getEventTypeColorLabel($event->type);
        $viewParamsArray['is_same_day']           = $startDate->isSameDay( $endDate ); //        'is_same_day' => $startDate->isSameDay(

//        ,"latitude":"51.65227700","longitude":"-0.32770000"
        $appParamsForJSArray['event_name']        = $event->event_name;
        $appParamsForJSArray['event_latitude']    = $event->latitude;
        $appParamsForJSArray['event_longitude']   = $event->longitude;
        $appParamsForJSArray['event_start_date']  = $this->getCFFormattedDate($event->start_date);
        $appParamsForJSArray['event_end_date']    = $this->getCFFormattedDate($event->end_date);
        $viewParamsArray['appParamsForJSArray']   = json_encode($appParamsForJSArray);

        return view($this->getFrontendTemplateName() . '.events_timeline.show_event', $viewParamsArray);
    } // private function event($event_slug)


    public function listing()    // http://local-votes.com/events-timeline
    {
        $request= request();
        $page = $request->input('page');
        if (empty($page)) $page   = 1;
        $viewParamsArray          = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name', 'site_heading', 'site_subheading']);

        $events_timeline_per_page                      = Settings::getValue('events_timeline_per_page', CheckValueType::cvtInteger, 5);
        $home_page_ref_items_per_pagination            = Settings::getValue('home_page_ref_items_per_pagination', CheckValueType::cvtInteger, 5);
        $mysql_to_carbon_datetime_format       = config('app.mysql_to_carbon_datetime_format', 'Y-m-d H:i:s');

        $eventsTimelineList = Event
            ::select( $this->events_tb.'.*', $this->users_tb.'.username')
            ->getByIsPublic( true )
            ->orderBy('start_date', 'desc')
            ->join($this->users_tb, $this->users_tb.'.id', '=', $this->events_tb.'.creator_id')
            ->paginate( $events_timeline_per_page , array('*'), 'page', $page)
            ->onEachSide((int)($home_page_ref_items_per_pagination / 2));


        $mapEvents         = [];
        $default_latitude  = '';
        $default_longitude = '';
        foreach( $eventsTimelineList as $next_key=>$nextTempEventsTimeline ) {
            if ( empty($nextTempEventsTimeline->start_date) or empty($nextTempEventsTimeline->latitude) or empty($nextTempEventsTimeline->end_date) or empty($nextTempEventsTimeline->longitude))
                continue;

            if ( empty($default_latitude) or empty($default_longitude) ) {
                $default_latitude= $nextTempEventsTimeline->latitude;
                $default_longitude= $nextTempEventsTimeline->longitude;
            }
            $startDate= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $nextTempEventsTimeline->start_date);
            $endDate= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $nextTempEventsTimeline->end_date);

            $eventsTimelineList[$next_key]->event_type_label= Event::getEventTypeLabel($nextTempEventsTimeline->type);
            $eventsTimelineList[$next_key]->event_type_color= Event::getEventTypeColorLabel($nextTempEventsTimeline->type);
            $eventsTimelineList[$next_key]->is_same_day= $startDate->isSameDay( $endDate );

            $mapEvents[] = [
                'event_name' => $nextTempEventsTimeline->event_name,
                'latitude' => $nextTempEventsTimeline->latitude,
                'longitude' => $nextTempEventsTimeline->longitude,
                'event_start_date' => $this->getCFFormattedDate($nextTempEventsTimeline->start_date),
                'event_end_date' => $this->getCFFormattedDate($nextTempEventsTimeline->end_date),
                'event_location' => $this->getCFFormattedDate($nextTempEventsTimeline->location),
            ];
        }
        $all_events_timelines_count = Event
            ::getByIsPublic( true )
            ->join($this->users_tb, $this->users_tb.'.id', '=', $this->events_tb.'.creator_id')
            ->count();


//        echo '<pre>$appParamsForJSArray[\'mapEvents\']::'.print_r($appParamsForJSArray['mapEvents'],true).'</pre>';
//        die("-1 XXZ");
        $appParamsForJSArray['mapEvents']                   = $mapEvents;
        $appParamsForJSArray['default_latitude']            = $default_latitude;
        $appParamsForJSArray['default_longitude']           = $default_longitude;

        $viewParamsArray['appParamsForJSArray']             = json_encode($appParamsForJSArray);
        $viewParamsArray['eventsTimelineList']              = $eventsTimelineList;
        $viewParamsArray['all_events_timelines_count']      = $all_events_timelines_count;


        return view($this->getFrontendTemplateName() . '.events_timeline.listing', $viewParamsArray);
    } // private function listing ()

}
