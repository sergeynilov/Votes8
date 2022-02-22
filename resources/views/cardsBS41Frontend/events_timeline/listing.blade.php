@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')


    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
        <br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
            <small>{{ $site_subheading }}</small>@endif
    </h1>

    @include($frontend_template_name.'.layouts.logged_user')

    <div class="row ml-2 mb-3">
        {{ Breadcrumbs::render('event', 'Events') }}
    </div>


    <div class="row ml-1 mr-1">
        <div class="col-sm-8 ">

            @if( !empty($eventsTimelineList) and count($eventsTimelineList) > 0 )
                <div class="row">

                    <h4 class="pl-2">{{ $all_events_timelines_count }} Events</h4>
                    <ul class="timeline m-0 p-2 pl-4">
                        @foreach($eventsTimelineList as $nexEventTimeline)

                        <li class="mb-5">
                            <a href="{{ route('event', $nexEventTimeline->slug ) }}" class="a_link">
                                {{ $nexEventTimeline->event_name }}
                            </a>

                            @if($nexEventTimeline->is_same_day)
                                <span href="#" class="float-right">
                                    {{ $viewFuncs->getFormattedDateTime($nexEventTimeline->start_date) }}&nbsp;-&nbsp;{{
                                $viewFuncs->getFormattedDateTime($nexEventTimeline->end_date, 'mysql', 'only_time') }}
                                </span>
                            @else
                                <span href="#" class="float-right">
                                    {{ $viewFuncs->getFormattedDateTime($nexEventTimeline->start_date) }}&nbsp;-&nbsp;{{
                                $viewFuncs->getFormattedDateTime($nexEventTimeline->end_date) }}
                                </span>
                            @endif

                            <h5 class="m-3">
                                {!! $viewFuncs->showAppIcon('event','transparent_on_white', 'Event\'s type') !!}&nbsp;
                                <span style="background-color: {{ $nexEventTimeline->event_type_color }}; color: white;" class="p-1">
                                {{ $nexEventTimeline->event_type_label }}
                            </span>
                            </h5>

                            <p>
                                {!!  Purifier::clean($viewFuncs->concatStr($nexEventTimeline->description,255))  !!}
                            </p>
                            <p>
                                {!! $viewFuncs->showAppIcon('location','transparent_on_white', 'Event\'s location') !!}&nbsp;
                                {!! $nexEventTimeline->location  !!}
                            </p>
                        </li>
                        @endforeach
                        
                    </ul>

                </div>


                <div id="div_map_wrapper" style="display: block" class=" p-2">
                    <div id="div_map" ></div>
                </div>

                <div class="row m-3">
                    {{ $eventsTimelineList->appends([])->links() }}
                </div>

            @else
                <div class="alert alert-warning small" role="alert">
                    There are no events yet
                </div>
            @endif

        </div>


        @include($frontend_template_name.'.layouts.right_menu_block' , ['show_questions_block' => false, 'show_most_rated_quizzes_block' => true,
'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )

    </div>

@endsection



@section('scripts')
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCe9fHKwiILLtHlA9h09H13KShox4cn0Dg"></script>

    <script src="{{ asset('js/'.$frontend_template_name.'/timeline.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var frontendTimeline = new frontendTimeline('events_listing',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            frontendTimeline.onFrontendPageInit('events_listing')
        });

        /*]]>*/
    </script>

@endsection


@section('styles')

    @media only screen and (min-width: 1280px )  {
        #div_map {
            height: 820px;
            width: 820px;
            border: 1px dotted grey;
        }
    }

    @media only screen and (min-width: 1024px ) and (max-width: 1279px) {
        #div_map {
            height: 650px;
            width: 650px;
            border: 1px dotted grey;
        }
    }

    @media only screen and (min-width: 768px ) and (max-width: 1023px) {
        #div_map {
            height: 480px;
            width: 480px;
            border: 1px dotted grey;
        }
    }

    @media only screen and (min-width: 320px ) and (max-width: 479px) {
        #div_map {
            height: 280px;
            width: 280px;
            border: 1px dotted grey;
        }
    }

    @media only screen and (min-width: 480px ) and (max-width: 599px) {
        #div_map {
            height: 440px;
            width: 440px;
            border: 1px dotted grey;
        }
    }

    @media only screen and (min-width: 600px ) and (max-width: 767px) {
        #div_map {
            height: 380px;
            width: 380px;
            border: 1px dotted grey;
        }
    }

@stop