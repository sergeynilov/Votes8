@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : site subscriptions create -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('subscription','transparent_on_white') !!}Create site subscription</h4>

            <form method="POST" action="{{ url('/admin/site-subscriptions') }}" accept-charset="UTF-8" id="form_site_subscription_edit" enctype="multipart/form-data">
                {!! csrf_field() !!}
                @include($current_admin_template.'.admin.site_subscription.form')
            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : site subscriptions create End -->



@endsection


@section('scripts')

    <script src="{{ asset('js/'.$current_admin_template.'/admin/site_subscription.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\SiteSubscriptionRequest', '#form_site_subscription_edit'); !!}

    <script>
        /*<![CDATA[*/

        var backendSiteSubscription = new backendSiteSubscription('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendSiteSubscription.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection

