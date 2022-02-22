@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : page content index -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_PageContents_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('page-content','transparent_on_white') !!}Pages Listing</h4>
            
            <div class="form-row offset-1">
                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->text('filter_title', '', "form-control editable_field", [ "autocomplete"=>"off", 'placeholder'=> 'Enter search string for title' ]         ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->select('filter_is_featured', $pageContentIsFeaturedValueArray, '', "form-control editable_field chosen_select_box", ['data-placeholder'=>" -Select Is Featured- "] ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->select('filter_page_type', $pageContentPageTypeValueArray, '', "form-control editable_field chosen_select_box", ['data-placeholder'=>" -Select Page Type- "] ) !!}
                </div>
                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->select('filter_is_homepage', $pageContentIsHomepageValueArray, '', "form-control editable_field chosen_select_box", ['data-placeholder'=>" -Select Is Homepage- "] ) !!}
                </div>
                <div class="col-12 col-sm-6 mb-3 mt-1 pl-2">
                    <input type="submit" class="btn btn-primary" value="Search" onclick="javascript:backendPageContent.runSearch(oTable); return false;" id="btn_run_search">
                    <a onclick="javascript:document.location='{{ route('admin.page-contents.create') }}'" class="a_link">
                        &nbsp;<small>&nbsp;&nbsp;&nbsp;&nbsp;( Add )</small>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-primary" id="get-page-content-dt-listing-table">
                        <thead>
                        <tr>
                            {{--<th>+</th>--}}
                            <th>Id</th>
                            <th>Title</th>
                            <th>Creator</th>
                            <th>Is Featured</th>
                            <th>Published</th>
                            <th>Page type</th>
                            <th>Created At</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>


            </div>

            <div class="row mt-2 ml-2">
                <button type="button" onclick="javascript:document.location='{{ route('admin.page-contents.create') }}'" class="btn btn-primary pageContents_listing_add">
                    &nbsp;Add
                </button>&nbsp;&nbsp;
            </div>


        </section>  <!-- class="card-body" -->


    </div>
    <!-- /.page-wrapper Page Content : page content index -->


    <script id="PageContent_details_info_template" type="mustache/x-tmpl">
        <div id="div_PageContent_details_info_<%id%>"></div>

    </script>

    <!-- DataTables -->

@endsection


@section('scripts')

    <link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}" type="text/css">
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/mustache.min.js') }}"></script>

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/page_content.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var oTable
        var backendPageContent = new backendPageContent('list',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendPageContent.onBackendPageInit('list')
        });

        /*]]>*/
    </script>


@endsection

