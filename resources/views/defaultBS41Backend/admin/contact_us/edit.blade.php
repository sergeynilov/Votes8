@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : admin contact_us edit -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('contact-us','transparent_on_white') !!}Edit contact us</h4>
            @include($current_admin_template . '.admin.contact_us.form')

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : admin contact_us edit -->



@endsection


@section('scripts')

    <script src="{{ asset('js/'.$current_admin_template.'/admin/contact_us.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\ContactUsRequest', '#form_contact_us_edit'); !!}


    <script>
        /*<![CDATA[*/

        var backendContactUs = new backendContactUs('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendContactUs.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection

