@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')


    <!-- Page Content : user index -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')


        <section class="card-body content_block_admin_users_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('users','transparent_on_white') !!}Users Listing</h4>

            <div class="form-row offset-1">

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->text('filter_username', '', "form-control editable_field", [ "autocomplete"=>"off", 'placeholder'=> 'Enter search string for username' ] ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->select('filter_status', $userStatusValueArray, '', "form-control editable_field chosen_select_box", ['data-placeholder'=>" -Select Status- "] ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3 mt-1 pl-2">
                    <input type="submit" class="btn btn-primary" value="Search" onclick="javascript:backendUser.runSearch(oTable); return false;" id="btn_run_search">
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-primary" id="get-user-dt-listing-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Phone</th>
                            <th>Created At</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>

            </div>

        </section>  <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : user index -->


    <script id="user_details_info_template" type="mustache/x-tmpl">
        <div id="div_user_details_info_<%id%>"></div>

    </script>

    <!-- DataTables -->


@endsection


@section('scripts')

    <link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}" type="text/css">
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/mustache.min.js') }}"></script>

    <script src="{{ asset('js/'.$current_admin_template.'/admin/user.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var oTable
        var backendUser = new backendUser('list',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendUser.onBackendPageInit('list')
        });

        /*]]>*/
    </script>


@endsection

