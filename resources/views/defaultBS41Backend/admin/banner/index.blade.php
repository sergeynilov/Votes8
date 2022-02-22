@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : banner index -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_banners_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('banner','transparent_on_white') !!}Banners Listing</h4>

            <div class="form-row offset-1">
                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->text('filter_text', '', "form-control editable_field", [ "autocomplete"=>"off", 'placeholder'=> 'Enter search string for text' ] ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->select('filter_active', $bannerActiveValueArray, '', "form-control editable_field chosen_select_box", ['data-placeholder'=>" -Select
                    Active- "] ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->select('filter_view_type', $bannerViewTypeValueArray, '', "form-control editable_field chosen_select_box", ['data-placeholder'=>" -Select
                    View Type- "] ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3 mt-1 pl-2">
                    <input type="submit" class="btn btn-primary" value="Search" onclick="javascript:backendBanner.runSearch(oTable); return false;" id="btn_run_search">
                    <a onclick="javascript:document.location='{{ route('admin.banners.create') }}'" class="a_link">
                        &nbsp;<small>&nbsp;&nbsp;&nbsp;&nbsp;( Add )</small>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-primary" id="get-banner-dt-listing-table">
                        <thead>
                        <tr>
                            {{--<th>+</th>--}}
                            <th>Id</th>
                            <th>Text</th>
                            <th>Active</th>
                            <th>View type</th>
                            <th>Created At</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>

            </div>

            <div class="row mt-2 ml-2">
                <button type="button" onclick="javascript:document.location='{{ route('admin.banners.create') }}'" class="btn btn-primary banners_listing_add">
                    &nbsp;Add
                </button>&nbsp;&nbsp;
            </div>

        </section>  <!-- class="card-body" -->


    </div>
    <!-- /.page-wrapper Banner : banner index -->


    <script id="banner_details_info_template" type="mustache/x-tmpl">
        <div id="div_banner_details_info_<%id%>"></div>

    </script>

    <!-- DataTables -->

@endsection


@section('scripts')

    <link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}" type="text/css">
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/mustache.min.js') }}"></script>

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/banner.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var oTable
        var backendBanner = new backendBanner('list',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendBanner.onBackendPageInit('list')
        });

        /*]]>*/
    </script>


@endsection

