@extends($current_admin_template.'.layouts.backend')

@section('content')
    {{ csrf_field() }}


    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Service Subscription : Service Subscription index -->
    <div id="page-wrapper" class="card">

        @include($current_admin_template.'.layouts.page_header')

        <section class="card-body content_block_admin_ServiceSubscriptions_wrapper ">

            <h4 class="card-title ">{!! $viewFuncs->showAppIcon('service-subscription','transparent_on_white') !!}Paypal plans <small>( {{ count($paypalPlans) }} items)
                </small></h4>

            <div class="form-row offset-1">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-primary" id="paypal-plans-listing-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>State</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Created At</th>
                            <th>Updated At</th>
{{--                            <th></th>--}}
                            <th>Activate</th>
{{--                            <th>Deactivate</th>--}}
                            <th>Delete</th>

                        </tr>
                        </thead>

                        <tbody>

                            @foreach($paypalPlans as $nextPaypalPlan)
                            <tr>

                                <td>
                                    {{ $nextPaypalPlan->id }}
                                    @foreach($serviceSubscriptionsSelectionArray as $nextServiceSubscriptionsSelection)
                                        @if($nextServiceSubscriptionsSelection['service_id'] == $nextPaypalPlan->id)
                                            <p><small>Assigned to <a href="/admin/service-subscriptions/{{$nextServiceSubscriptionsSelection['id']}}/edit" target="_blank">
                                                {!! $nextServiceSubscriptionsSelection['name'] !!}
                                            </a>
                                            </small>
                                            </p>
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $nextPaypalPlan->state }}</td>
                                <td>{{ $nextPaypalPlan->name }}</td>
                                <td>{{ $nextPaypalPlan->description }}</td>
                                <td>{{ $nextPaypalPlan->type }}</td>
                                <td>{{ $nextPaypalPlan->create_time }}</td>
                                <td>{{ $nextPaypalPlan->update_time }}</td>

                                <td>
                                    @if($nextPaypalPlan->state != 'ACTIVE')
                                        <a href="#" onclick="javascript:backendServiceSubscription.activatePaypalPlan( '{{$nextPaypalPlan->id}}','{{$nextPaypalPlan->name}}'); ">
                                            <i class="fa fa-toggle-on a_link"></i>
                                        </a>
                                    @endif
                                </td>
{{--                                <td>--}}
{{--                                    <a href="#" onclick="javascript:backendServiceSubscription.deactivatePaypalPlan( '{{$nextPaypalPlan->id}}','{{$nextPaypalPlan->name}}'); ">--}}
{{--                                        <i class="fa fa-toggle-off a_link"></i>--}}
{{--                                    </a>--}}
{{--                                </td>--}}

                                <td>
                                    <a href="#" onclick="javascript:backendServiceSubscription.deletePaypalPlan( '{{$nextPaypalPlan->id}}','{{$nextPaypalPlan->name}}'); ">
                                        <i class="fa fa-remove a_link"></i>
                                    </a>
                                </td>

                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>


            </div>

            <div class="row mt-2 ml-2">
                <button type="button" onclick="javascript:document.location='{{ route('admin.service-subscriptions.create') }}'" class="btn btn-primary serviceSubscriptions_listing_add">
                    &nbsp;Add
                </button>&nbsp;&nbsp;
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

