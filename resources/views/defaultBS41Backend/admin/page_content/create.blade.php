@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : page content create -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('page-content','transparent_on_white') !!}Create page</h4>

            <form method="POST" action="{{ url('/admin/page-contents') }}" accept-charset="UTF-8" id="form_page_content_edit" enctype="multipart/form-data">
                {!! csrf_field() !!}
                @include($current_admin_template.'.admin.page_content.form')
            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper  Page Content : page content create  -->


@endsection


@section('scripts')

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/page_content.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        initTinyMCEEditor( "content_container", "content", 460, 360);
    </script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\PageContentRequest', '#form_page_content_edit'); !!}

    <script>
        /*<![CDATA[*/

        var backendPageContent = new backendPageContent('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendPageContent.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection