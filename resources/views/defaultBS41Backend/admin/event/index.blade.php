@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : event index -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_events_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('event','transparent_on_white') !!}Events Listing</h4>

            @if(!empty($current_google_calendar))
                <h4 class="text-center p-3">All events can be synchronized to "{{ $current_google_calendar }}" Google Calendar.</h4>
            @endif

            {{--            private static $eventReportTypeValueArray = Array( 'C' => 'Calendar', 'L' => 'Listing' );--}}

            <div class="form-row offset-1">
                <div class="col-12 mb-3">
                    {!! $viewFuncs->text('filter_event_name', '', "form-control editable_field", [ "autocomplete"=>"off", 'placeholder'=> 'Enter search string for event name' ] ) !!}
                </div>
{{--                <div class="col-12 col-sm-6 mb-3">--}}
{{--                    {!! $viewFuncs->select('filter_report_type', $eventReportTypeValueArray, 'C', "form-control--}}
{{--                    editable_field chosen_select_box", ['data-placeholder'=>" -Select Report Type- "] ) !!}--}}
{{--                </div>--}}

                <div class="col-12 col-sm-12 mb-3">
                    <input type="hidden" id="filter_start_date" name="filter_start_date" value="{{ isset($event->start_date) ? $event->start_date : '' }}">
                    <input type="hidden" id="filter_end_date" name="filter_end_date" value="{{ isset($event->end_date) ? $event->end_date : '' }}">
                    <label class="col-12 col-sm-4 col-form-label" for="filter_start_date_end_date_picker">
                        Start date / End date
                        <span class="required"> * </span>
                    </label>
                    <div class="col-12 col-sm-8" style="padding-bottom: 30px;">
                        {!! $viewFuncs->text('filter_start_date_end_date_picker', '', "form-control", [ "readonly"=>"readonly" ] ) !!}
                        <p class="m-2">
                            <small>
                                You can pick one date and then the second date(it must be bigger the first date). After that select time. The range will be selected by clicking on
                                “Apply” button.
                            </small>
                        </p>
                    </div>

                </div>

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->select('filter_status', $eventStatusValueArray, '', "form-control editable_field chosen_select_box", ['data-placeholder'=>" -Select Status- "] ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->select('filter_type', $eventTypeValueArray, '', "form-control editable_field chosen_select_box", ['data-placeholder'=>" -Select Type- "] ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3 mt-1 pl-2">
                    <input type="submit" class="btn btn-primary" value="Search" onclick="javascript:backendEvent.runSearch(oTable); return false;" id="btn_run_search">
                    <a onclick="javascript:document.location='{{ route('admin.events.create') }}'" class="a_link">
                        &nbsp;<small>&nbsp;&nbsp;&nbsp;&nbsp;( Add )</small>
                    </a>
                </div>

                <div class="col-12 m-3 p-2" id="div_events_calendar_wrapper" style="display: none">
                    <p class="m-2">
                        <small>
                            For this app some <strong>demo data</strong> are opened by default.
                        </small>
                    </p>

                    <div id='events_calendar'></div>
                </div>

                <div class="table-responsive" id="table_events_listing" style="display: none">
                    <table class="table table-bordered table-striped text-primary" id="get-event-dt-listing-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Event name</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Assigned to google calendar</th>
                            <th>Event Type</th>
                            <th>Created At</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>

            </div>

            <div class="row mt-2 ml-2">
                <button type="button" onclick="javascript:document.location='{{ route('admin.events.create') }}'" class="btn btn-primary events_listing_add">
                    &nbsp;Add
                </button>&nbsp;&nbsp;
            </div>

            <div class="row mt-4 ml-2">
                <button type="button" onclick="javascript:document.location='{{ route('admin.add_demo_events') }}'" class="btn btn-primary events_listing_add">
                    &nbsp;Add Demo Events for current week
                </button>&nbsp;&nbsp;
            </div>

            <div class="row mt-4 ml-2">
                <button type="button" onclick="javascript:document.location='{{ route('admin.synchronize_google_events') }}'" class="btn btn-primary events_listing_add">
                    &nbsp;Synchronize with Google Events
                </button>&nbsp;&nbsp;
            </div>

        </section>  <!-- class="card-body" -->


    </div>
    <!-- /.page-wrapper Event : event index -->


    <script id="event_details_info_template" type="mustache/x-tmpl">
        <div id="div_event_details_info_<%id%>"></div>

    </script>

    <!-- DataTables -->

@endsection


@section('scripts')

    <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/daterangepicker.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}" type="text/css">

    <link rel="stylesheet" href="{{ asset('/css/fullcalendar/core/main.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/css/fullcalendar/daygrid/main.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('/css/fullcalendar/timegrid/main.css') }}" type="text/css">

{{--    file:///_wwwroot/lar/votes/public/css/fullcalendar/timegrid/main.css--}}

    <script src="{{ asset('js/fullcalendar/core/main.js') }}"></script>

    <script src="{{ asset('js/fullcalendar/daygrid/main.js') }}"></script>

{{--    file:///_wwwroot/lar/votes/public/js/fullcalendar/timegrid/main.js--}}
    <script src="{{ asset('js/fullcalendar/timegrid/main.js') }}"></script>



    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/mustache.min.js') }}"></script>

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/event.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var oTable
        var backendEvent = new backendEvent('list',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendEvent.onBackendPageInit('list')
        });

        /*]]>*/
    </script>


@endsection