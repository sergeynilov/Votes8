@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Service Subscription : Service Subscription index -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_ServiceSubscriptions_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('service-subscription','transparent_on_white') !!}Service Subscriptions Listing</h4>

            <div class="form-row offset-1">
                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->text('filter_name', '', "form-control editable_field", [ "autocomplete"=>"off", 'placeholder'=> 'Enter search string for name' ]         ) !!}
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    {!! $viewFuncs->select('filter_price_period', $serviceSubscriptionPricePeriodValueArray, '', "form-control editable_field chosen_select_box",
                    ['data-placeholder'=>" -Select Price Period- "] ) !!}
                </div>
                <div class="col-12 col-sm-6 mb-3 mt-1 pl-2">
                    <input type="submit" class="btn btn-primary" value="Search" onclick="javascript:backendServiceSubscription.runSearch(oTable); return false;" id="btn_run_search">
                    <a onclick="javascript:document.location='{{ route('admin.service-subscriptions.create') }}'" class="a_link">
                        &nbsp;<small>&nbsp;&nbsp;&nbsp;&nbsp;( Add )</small>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-primary" id="get-service-subscriptions-dt-listing-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Active</th>
                            <th>Price Period</th>
                            <th>Price</th>
                            <th>Service ID (Paypal) </th>
                            <th>Created At</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>


            </div>

            <div class="row mt-2 ml-2">
                <button type="button" onclick="javascript:document.location='{{ route('admin.service-subscriptions.create') }}'" class="btn btn-primary serviceSubscriptions_listing_add">
                    &nbsp;Add
                </button>&nbsp;&nbsp;
            </div>

            <div class="row mt-2 ml-2">
                <a class="a_link " href="{{ url('admin/paypal_plans') }}" target="_blank">
                    {!! $viewFuncs->showAppIcon('paypal','transparent_on_white') !!}Paypal plans
                </a>
            </div>


        </section>  <!-- class="card-body" -->


    </div>
    <!-- /.page-wrapper Service Subscription : service subscription index -->


    <script id="ServiceSubscription_details_info_template" type="mustache/x-tmpl">
        <div id="div_ServiceSubscription_details_info_<%id%>"></div>

    </script>

    <!-- DataTables -->

@endsection


@section('scripts')

    <link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}" type="text/css">
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/mustache.min.js') }}"></script>

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/service_subscription.js') }}{{  "?dt=".time()  }}"></script>

    <script>
        /*<![CDATA[*/

        var oTable
        var backendServiceSubscription = new backendServiceSubscription('list',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendServiceSubscription.onBackendPageInit('list')
        });

        /*]]>*/
    </script>


@endsection

