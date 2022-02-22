@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : banners edit -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('banner','transparent_on_white') !!}Edit banner</h4>

            <form method="POST" action="{{ url('/admin/banners/'.$banner->id) }}" accept-charset="UTF-8" id="form_banner_edit"
                  enctype="multipart/form-data">
                @method('PUT')
                {!! csrf_field() !!}
                @include($current_admin_template . '.admin.banner.form')
            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : banners edit End -->


@endsection


@section('scripts')

    <script src="{{ asset('js/'.$current_admin_template.'/admin/banner.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\BannerRequest', '#form_banner_edit'); !!}


    <script>
        /*<![CDATA[*/

        var backendBanner = new backendBanner('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendBanner.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection

