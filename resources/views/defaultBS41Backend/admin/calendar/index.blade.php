@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : calendar index -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_calendar_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('calendar','transparent_on_white') !!}Calendar</h4>


            {{--            <div class="form-row offset-1">--}}
            {{--                <div class="col-12 col-sm-6 mb-3">--}}
            {{--                    {!! $viewFuncs->text('filter_name', '', "form-control editable_field", [ "autocomplete"=>"off", 'placeholder'=> 'Enter search string for author name/email' ]--}}
            {{--                     ) !!}--}}
            {{--                </div>--}}

            {{--                <div class="col-12 col-sm-6 mb-3">--}}
            {{--                    <label> {!! $viewFuncs->select('filter_accepted', $calendarStatusValueArray, '', "form-control editable_field chosen_select_box chosen_filter_accepted", ['data-placeholder'=>"--}}
            {{--                    -Select Accepted- "] ) !!}</label>--}}
            {{--                </div>--}}

            {{--                <div class="col-12 col-sm-6 mb-3 mt-1 pl-2">--}}
            {{--                    <input type="submit" class="btn btn-primary" value="Search" onclick="javascript:backendCalendar.runSearch(oTable); return false;" id="btn_run_search">--}}
            {{--                </div>--}}

            {{--                <div class="table-responsive">--}}
            {{--                    <table class="table table-bordered table-striped text-primary " id="get-calendar-dt-listing-table">--}}
            {{--                        <thead>--}}
            {{--                        <tr>--}}

            {{--                            <th>Id</th>--}}
            {{--                            <th>Author name</th>--}}
            {{--                            <th>Author email</th>--}}
            {{--                            <th>Message</th>--}}
            {{--                            <th>Accepted</th>--}}
            {{--                            <th>Accepted At</th>--}}
            {{--                            <th>Created At</th>--}}
            {{--                            <th></th>--}}
            {{--                            <th></th>--}}

            {{--                        </tr>--}}
            {{--                        </thead>--}}
            {{--                    </table>--}}
            {{--                </div>--}}
            {{--            </div>--}}


        </section>  <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper  Page Content : calendar index -->


    <div class="modal fade" tabindex="-1" role="dialog" id="div_show_calendar_add_event_modal" aria-labelledby="show-calendar-add-event-modal-modal-label"
         aria-hidden="true">
        <form class="modal-dialog" role="document" method="POST" action="{{ url('/admin/calendar_add_event') }}" accept-charset="UTF-8" id="form_save_calendar_add_event"
              enctype="multipart/form-data">

            {!! csrf_field() !!}


            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="show-calendar-add-event-modal-modal-label">Entere Event details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">

                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label" for="new_event_title">Title</label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('new_event_title', isset($defaultEvent['title']) ? $defaultEvent['title'] : '', "form-control editable_field", ["maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
                        </div>
                    </div>

                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label" for="new_event_description">Description</label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('new_event_description', isset($defaultEvent['description']) ? $defaultEvent['description'] : '', "form-control editable_field", ["maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
                        </div>
                    </div>


                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label" for="new_event_start_date">Start date</label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('new_event_start_date', isset($defaultEvent['start_date']) ? $defaultEvent['start_date'] : '',"form-control editable_field", ["maxlength"=>"255","autocomplete"=>"off" ] ) !!}
                        </div>
                    </div>

                    <div class="form-row mb-3">
                        <label class="col-12 col-sm-4 col-form-label" for="new_event_end_date">End date</label>
                        <div class="col-12 col-sm-8">
                            {!! $viewFuncs->text('new_event_end_date', isset($defaultEvent['end_date']) ? $defaultEvent['end_date'] : '', "form-control editable_field", 
                            ["maxlength"=>"255", "autocomplete"=>"off" ] ) !!}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="javascript:backendCalendar.saveCalendarAddEvent(); return;">
                        <span class="btn-label"><i class="fa fa-save"></i></span> &nbsp;Save
                    </button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </form>


    </div>
    </div>


    <div class="row">
        <div id="div_show_quiz_quality_results" class="m-3">
            <button onclick="javascript:backendCalendar.showCalendarAddEvent()" class="a_link small">Add Event Manually</button>
        </div>

        {{--        <div id="div_hide_quiz_quality_results" style="display:none;" class="m-3">--}}
        {{--            <button onclick="javascript:frontendVote.hideQuizQualityResults()" class="a_link small">Hide results</button>--}}
        {{--            <div id="div_current_vote_quiz_quality_in_stars"></div>--}}
        {{--        </div>--}}
    </div>



@endsection


@section('scripts')

    <link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}" type="text/css">
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/mustache.min.js') }}"></script>

    <script src="{{ asset('js/'.$current_admin_template.'/admin/calendar.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var oTable
        var backendCalendar = new backendCalendar('view',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendCalendar.onBackendPageInit('view')
        });

        /*]]>*/
    </script>


@endsection

