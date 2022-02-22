@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : compare correct votes report -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_compare_correct_votes_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('report','transparent_on_white') !!}Compare correct votes </h4>

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
                    <input type="submit" class="btn btn-primary"  value="Report" onclick="javascript:backendReports.runReportCompareCorrectVotes(); return false;"
                           id="btn_run_search">&nbsp;&nbsp;
                    <input type="submit" class="btn btn-sm"  value="Clear" onclick="javascript:backendReports.clearReportCompareCorrectVotesParameters(); return false;"
                           id="btn_clear">
                </div>


            </div>



            <div class="row" id="div_canvasCompareCorrectVotes" style="display: none">
                <div class="col-md-10 col-md-offset-1">
                        <div class="card-body">
                            <p class=" text-muted small">
                                Any quiz can be rated from 1 till 5.
                            </p>
                            <canvas id="canvasCompareCorrectVotes" width="800" height="650"></canvas></div>
                </div>
            </div>



        </section>  <!-- class="card-body" -->


    </div>
    <!-- /.page-wrapper  Page Content : compare correct votes report   -->

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
            backendReports.onBackendPageInit('compare_correct_votes')
        });

        /*]]>*/
    </script>


@endsection

