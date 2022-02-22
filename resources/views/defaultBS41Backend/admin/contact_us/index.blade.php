@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : contact_us index -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_contact_us_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('contact-us','transparent_on_white') !!}Contact Us Listing</h4>

            
            <div class="form-row offset-1">
                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->text('filter_name', '', "form-control editable_field", [ "autocomplete"=>"off", 'placeholder'=> 'Enter search string for author name/email' ]
                     ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    <label> {!! $viewFuncs->select('filter_accepted', $contactUsStatusValueArray, '', "form-control editable_field chosen_select_box chosen_filter_accepted", ['data-placeholder'=>"
                    -Select Accepted- "] ) !!}</label>
                </div>

                <div class="col-12 col-sm-6 mb-3 mt-1 pl-2">
                    <input type="submit" class="btn btn-primary" value="Search" onclick="javascript:backendContactUs.runSearch(oTable); return false;" id="btn_run_search">
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-primary " id="get-contact-us-dt-listing-table">
                        <thead>
                        <tr>

                            <th>Id</th>
                            <th>Author name</th>
                            <th>Author email</th>
                            <th>Message</th>
                            <th>Accepted</th>
                            <th>Accepted At</th>
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
    <!-- /.page-wrapper  Page Content : contact_us index -->


    <script id="contact_us_details_info_template" type="mustache/x-tmpl">
        <div id="div_contact_us_details_info_<%id%>"></div>

    </script>

    <!-- DataTables -->


@endsection


@section('scripts')

    <link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}" type="text/css">
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/mustache.min.js') }}"></script>

    <script src="{{ asset('js/'.$current_admin_template.'/admin/contact_us.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var oTable
        var backendContactUs = new backendContactUs('list',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendContactUs.onBackendPageInit('list')
        });

        /*]]>*/
    </script>


@endsection

