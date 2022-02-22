@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')


    <!-- Page Content : vote categories index -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')


        <section class="card-body content_block_admin_vote_categories_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('category','transparent_on_white') !!}Vote Categories Listing</h4>

            <div class="form-row offset-1">

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->text('filter_name', '', "form-control editable_field", [ "autocomplete"=>"off", 'placeholder'=> 'Enter search string for name' ] ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->select('filter_active', $voteCategoryStatusValueArray, '', "form-control editable_field chosen_select_box", ['data-placeholder'=>" -Select Active- "] ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3 mt-1 pl-2">
                    <input type="submit" class="btn btn-primary" value="Search" onclick="javascript:backendVoteCategory.runSearch(oTable); return false;" id="btn_run_search">
                    <a onclick="javascript:document.location='{{ route('admin.vote-categories.create') }}'" class="a_link">
                        &nbsp;<small>&nbsp;&nbsp;&nbsp;&nbsp;( Add )</small>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-primary" id="get-vote-category-dt-listing-table">
                        <thead>
                        <tr>
                            <th>+</th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Active</th>
                            <th>In Subscriptions</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>

            </div>

            <div class="row mt-2 ml-2">
                <button type="button" onclick="javascript:document.location='/admin/vote-categories/create'" class="btn btn-primary">
                    &nbsp;Add
                </button>&nbsp;&nbsp;
            </div>


        </section>  <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : vote categories index -->


    <script id="vote_category_details_info_template" type="mustache/x-tmpl">
<div id="div_vote_category_details_info_<%id%>"></div>


    </script>

    <!-- DataTables -->


@endsection


@section('scripts')

    <link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}" type="text/css">
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/mustache.min.js') }}"></script>

    <script src="{{ asset('js/'.$current_admin_template.'/admin/vote_category.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var oTable
        var backendVoteCategory = new backendVoteCategory('list',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendVoteCategory.onBackendPageInit('list')
        });

        /*]]>*/
    </script>


@endsection

