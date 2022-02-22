@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : search results -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_search_results_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('report','transparent_on_white') !!}Search results </h4>

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


                <div class="col-12 col-sm-6 mt-2 mb-2">
                    <input type="submit" class="btn btn-primary"  value="Report" onclick="javascript:backendReports.runReportSearchResults(); return false;"
                           id="btn_run_search">&nbsp;&nbsp;
                    <input type="submit" class="btn btn-sm"  value="Clear" onclick="javascript:backendReports.clearReportSearchResultsParameters(); return false;"
                           id="btn_clear">
                </div>


            </div>



            <div class="row" id="div_SearchResults" style="display: none;">
                <div class="col-md-10 col-md-offset-1">
                        <div class="card-body">
                            <p class=" text-muted small">
                                Clicking on legend items you can hide/show them.
                            </p>

                            <canvas id="SearchResults" height="560" width="800"></canvas>
                        </div>
                </div>
            </div>


        </section>  <!-- class="card-body" -->


    </div>
    <!-- /.page-wrapper Page Content : search results -->

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
            backendReports.onBackendPageInit('search_results')
        });

        /*]]>*/
    </script>


@endsection

