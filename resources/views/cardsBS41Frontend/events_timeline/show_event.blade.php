@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')
    <?php $errorFieldsArray = array_values( !empty($errors) ? $errors->keys() : [] ); ?>

    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
        <br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
            <small>{{ $site_subheading }}</small>@endif
    </h1>

    @include($frontend_template_name.'.layouts.logged_user')

    <div class="row ml-1 mb-2">
        {{ Breadcrumbs::render('event', $event->event_name) }}
    </div>

    <div class="row ml-1 mr-1">
        <div class="col-sm-8">

            <div class="row">


                
                <div class="card">

                    <div class="col-12">

                        <h4 class="card-title m-1 p-0">
                            @if($is_same_day)
                               {{ Purifier::clean($event->event_name) }} : {{ $viewFuncs->getFormattedDateTime($event->start_date) }}&nbsp;-&nbsp;{{
                                $viewFuncs->getFormattedDateTime($event->end_date, 'mysql', 'only_time') }}
                            @else
                                {{ Purifier::clean($event->event_name) }} : {{ $viewFuncs->getFormattedDateTime($event->start_date) }}&nbsp;-&nbsp;{{
                                $viewFuncs->getFormattedDateTime($event->end_date) }}
                            @endif
                        </h4>

                        <h5 class="card-subtitle m-1 p-0">
                            {!! $viewFuncs->showAppIcon('event','transparent_on_white', 'Event\'s type') !!}&nbsp;
                            <span style="background-color: {{ $event_type_color }}; color: white;" class="p-1">
                                {{ $event_type_label }}
                            </span>
                        </h5>

                        @if(!empty($event->description))
                            <div class="card-text m-1 p-0">
                                {!! Purifier::clean($event->description) !!}
                            </div>
                        @endif

                        <div class="card-footer m-1 p-0">
                            {!! $viewFuncs->showAppIcon('location','transparent_on_white', 'Event\'s location') !!}&nbsp;
                            {!! $event->location  !!}

                            <div id="div_map_wrapper" style="display: none" class=" p-2">
                                <div id="div_map" ></div>
                            </div>
                        </div>



                    </div>


                </div>


            </div> {{--<div class="col-sm-8--}}


        </div>{{--<div class="row">--}}


            @include($frontend_template_name.'.layouts.right_menu_block' , ['show_questions_block' => false, 'show_most_rated_quizzes_block' => true,
        'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )

    </div>

@endsection

@section('scripts')

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCe9fHKwiILLtHlA9h09H13KShox4cn0Dg"></script>

<script src="{{ asset('js/'.$frontend_template_name.'/timeline.js') }}{{  "?dt=".time()  }}"></script>

<script>
    /*<![CDATA[*/

    var frontendTimeline = new frontendTimeline('single_event',  // must be called before jQuery(document).ready(function ($) {
        <?php echo $appParamsForJSArray ?>
    );
    jQuery(document).ready(function ($) {
        frontendTimeline.onFrontendPageInit('single_event')
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