@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : tag create -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('tag','transparent_on_white') !!}Create tag</h4>

            <form method="POST" action="{{ url('/admin/tags') }}" accept-charset="UTF-8" id="form_tag_edit" enctype="multipart/form-data">
                {!! csrf_field() !!}
                @include($current_admin_template.'.admin.tag.form')
            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : tag create -->



@endsection


@section('scripts')

    <script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        initTinyMCEEditor( "description_container", "description", 460, 260);
    </script>

    <script src="{{ asset('js/'.$current_admin_template.'/admin/tag.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\TagRequest', '#form_tag_edit'); !!}

    <script>
        /*<![CDATA[*/

        var backendTag = new backendTag('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendTag.onBackendPageInit('edit');
        });

        /*]]>*/
    </script>


@endsection

