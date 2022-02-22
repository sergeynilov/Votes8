@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : votes by vote names -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_votes_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('report','transparent_on_white') !!}Votes by vote names</h4>

            <div class="form-row">
                
                <div class="col-12 col-sm-6">
                    <label class="col-12 col-sm-12 col-form-label pb-0 mb-0 mt-3" for="filter_voted_at_from_till">
                        Voted at range
                        (<small>You can pick one date and then scroll to the second month and select a date. The range will be selected but not all visible. By default dates
                            with demo data are selected.</small>)
                    </label>
                    <div class="col-12 col-sm-12">
                        {!! $viewFuncs->text('filter_voted_at_from_till', '', "form-control", [ "readonly"=>"readonly" ] ) !!}
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <label class="col-12 col-form-label pb-0 mb-0  mt-3" for="filter_user_id"> Select user(s)
                    </label>
                    <div class="col-12">
                        {!! $viewFuncs->select('filter_user_id', $usersSelectionArray, '', "form-control editable_field chosen_select_box", [ 'multiple'=>'multiple' ] ) !!}
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <label class="col-12 col-form-label  pb-0 mb-0 mt-3" for="filter_vote_category_id"> Select category(ies)
                    </label>
                    <div class="col-12">
                        {!! $viewFuncs->select('filter_vote_category_id', $voteCategoriesSelectionArray, '', "form-control editable_field chosen_select_box", [ 'multiple'=>'multiple' ] ) !!}
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <label class="col-12 col-form-label  pb-0 mb-0 mt-3" for="filter_vote_id"> Select vote(s)
                    </label>
                    <div class="col-12">
                        {!! $viewFuncs->select('filter_vote_id', $votesSelectionArray, '', "form-control editable_field chosen_select_box", [ 'multiple'=>'multiple' ] ) !!}
                    </div>

                </div>

                <div class="col-12 col-sm-6 mt-2 mb-2">
                    <input type="submit" class="btn btn-primary"  value="Report" onclick="javascript:backendReports.runVotesReportByVoteNames(); return false;" id="btn_run_search">&nbsp;&nbsp;
                    <input type="submit" class="btn btn-sm"  value="Clear" onclick="javascript:backendReports.clearVotesReportByVoteNamesParameters(); return false;"
                           id="btn_clear">
                </div>


            </div>



            <div class="row" id="div_canvasVoteNames" style="display: none;">
                <div class="col-md-10 col-md-offset-1">
                        <div class="card-body">
                            <p class=" text-muted small">
                                Clicking on legend items you can hide/show them. Clicking on bar area you would see opened details by selected vote.
                            </p>

                            <canvas id="canvasVoteNames" height="560" width="800"></canvas>
                        </div>
                </div>
            </div>



        </section>  <!-- class="card-body" -->


    </div>
    <!-- /.page-wrapper Page Content : votes by vote names -->


    <div class="modal fade" tabindex="-1" role="dialog" id="div_vote_names_report_details_modal"  aria-labelledby="vote-names-report-details-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vote-names-report-details-modal-label">Details on <span id="span_vote_names_report_details_content_title"> vote</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body " >
                    <div id="div_vote_names_report_details_content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



@endsection


@section('scripts')

    <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet"  type="text/css">
    <script src="{{ asset('/js/Chart.bundle.js') }}"></script>
    <script src="{{ asset('js/daterangepicker.min.js') }}"></script>

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/reports.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var backendReports = new backendReports('report',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendReports.onBackendPageInit('votes_by_vote_names')
        });

        /*]]>*/
    </script>


@endsection

