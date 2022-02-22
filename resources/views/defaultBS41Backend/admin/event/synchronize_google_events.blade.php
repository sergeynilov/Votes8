@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : event index -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_events_wrapper ">

            <form method="POST" action="{{ url('/admin/synchronize_google_events') }}" accept-charset="UTF-8" id="form_synchronize_google_events_edit"
                  enctype="multipart/form-data">
                {!! csrf_field() !!}
                <input type="__hidden" id="form_action" name="form_action" value="">
                <input type="__hidden" id="form_action_items" name="form_action_items" value="">

            <h4 class="card-title ">
                {!! $viewFuncs->showAppIcon('event','transparent_on_white') !!}Synchronize google events ( {{ count($eventItems) }}/{{$new_google_calendar_events_count}} new )
                with application events
            </h4>

            <div class="form-row offset-1">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-primary">
                        <thead>
                        <tr>
                            <th>+</th>
                            <th>Calendar event Id</th>
                            <th>Event name</th>
                            <th>Start/End datetime</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Assigned to google calendar</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($eventItems as $nexEventItem)
                        <tr>
                            <td >
                                <div style="display: flex; flex-direction: column; border: 0px dotted red;" class="p-0 m-3 ml-5">
{{--                                    {{ $nexEventItem['similar_db_event_id'] }}::{{ $nexEventItem['similar_db_event_id'] }}<br>--}}


                                    @if( empty($nexEventItem['similar_db_event_id']) )
                                        <input type="checkbox" class="custom-control-input cbx_calendar_event_selection"
                                               id="next_calendar_event_id_{{ $nexEventItem['calendar_event_id'] }}" value="{{ $nexEventItem['calendar_event_id'] }}">
                                        <label class="custom-control-label" for="next_calendar_event_id_{{ $nexEventItem['calendar_event_id'] }}"></label>
                                    @else
                                        This event name is used in existing event <a  href="{{ url('/admin/events/'.$nexEventItem['similar_db_event_id'].'/edit') }}">Edit</a>
                                    @endif
                                </div>

                            </td>
                            <td>{{ $nexEventItem['calendar_event_id'] }}</td>

                            <td>
                                {{ $nexEventItem['event_name'] }}
                                @if( !empty($nexEventItem['attendeesArray']) and is_array($nexEventItem['attendeesArray']) )
                                    <br><small>Has {{ count($nexEventItem['attendeesArray']) }} attendee(s)</small>
                                @endif
                            </td>
                            <td>
                                {{ $nexEventItem['start_date_formatted'] }} - {{ $nexEventItem['start_date_formatted'] }}
                            </td>
                            <td>
{{--                                {{ $nexEventItem['description'] }}--}}
                                {!! Purifier::clean($viewFuncs->concatStr( (!empty($nexEventItem['description']) ? $nexEventItem['description'] : ''), 40)) !!}
                            </td>
                            <td>
                                {!! Purifier::clean($viewFuncs->concatStr( (!empty($nexEventItem['location']) ? $nexEventItem['location'] : ''), 40)) !!}
{{--                                <p class="card-text">{!!  Purifier::clean($viewFuncs->concatStr($nextActiveQuizVote->description,100))  !!}</p>--}}
                            </td>
                            <td>
                                @if( count($nexEventItem['relatedEvents']) > 0 )
                                    @foreach($nexEventItem['relatedEvents'] as $nexRelatedEvent)
                                        Assigned to {{ $nexRelatedEvent['event_id'] }}-><a href="{{ url( '/admin/events/'.$nexRelatedEvent['event_id']."/edit" ) }}"
                                                                                          target="_blank" class="a_link">{{
                                        $nexRelatedEvent['event_name']  }}</a>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $nexEventItem['created_formatted'] }}</td>
                            <td>{{ $nexEventItem['updated_formatted'] }}</td>

                        </tr>
                        @endforeach

                        </tbody>

                    </table>
                </div>

            </div>

            </form>

        </section>  <!-- class="card-body" -->
        <div class="card-footer  mt-0 pt-0">
            <div class="row float-right mt-0 pt-0">
                <button type="button" class="btn btn-primary btn-default btn-lg"
                    onclick="javascript:backendEvent.importSelectedCalendarEventsIntoDb();  return false; ">
                    {!! $viewFuncs->showAppIcon('event','selected_dashboard') !!}Import Selected
                </button>

                <button type="button" class=" btn btn-inverse ml-5 mr-5 " onclick="javascript:document.location='{{ url('/admin/events') }}'"
                        style="margin-right:50px;"><span class="btn-label"><i class="fa fa-arrows-alt"></i></span> &nbsp;Return to Events
                </button>&nbsp;&nbsp;
            </div>
        </div>


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
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/mustache.min.js') }}"></script>

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/event.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var oTable
        var backendEvent = new backendEvent('synchronize',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendEvent.onBackendPageInit('synchronize')
        });

        /*]]>*/
    </script>


@endsection

