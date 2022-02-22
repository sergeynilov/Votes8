@extends($current_admin_template.'.layouts.backend')

@section('content')

    <link rel="stylesheet" href="{{ url('css/jquery.fileupload.css') }} "/>

    <script src="{{ url('js/fileupload/jquery.ui.widget.js') }}"></script>

    <script src="{{ url('js/fileupload/jquery.fileupload.js') }}"></script>
    <script src="{{ url('js/fileupload/jquery.fileupload-process.js') }}"></script>
    <script src="{{ url('js/fileupload/jquery.iframe-transport.js') }}"></script>

    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Service Subscription : service subscription edit -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('service','transparent_on_white') !!}Edit service subscription</h4>

            <form method="POST" action="{{ url('/admin/service-subscriptions/'.$serviceSubscription->id) }}" accept-charset="UTF-8" id="form_service_subscription_edit"
                  enctype="multipart/form-data">
                @method('PUT')
                {!! csrf_field() !!}
                @include($current_admin_template . '.admin.service_subscription.form')
            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Service Subscription : service subscription edit -->



@endsection


@section('scripts')


    <script src="{{ asset('js/video.js') }}"></script>
    <link href="{{ asset('css/video-js.css') }}" rel="stylesheet" type="text/css">

    <script src="{{ asset('js/'.$current_admin_template.'/admin/service_subscription.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\ServiceSubscriptionRequest', '#form_service_subscription_edit'); !!}


    <script>
        /*<![CDATA[*/

        var backendServiceSubscription = new backendServiceSubscription('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendServiceSubscription.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection

