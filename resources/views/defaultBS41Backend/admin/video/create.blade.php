@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')
    {{ csrf_field() }}

    <!-- Page Content : video create -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">Create video</h4>

        {!! Form::open(['url' => $backend_home_url.'/admin/video/store', 'id'=>"form_video_edit", "enctype"=>"multipart/form-data" ]) !!}
        {{ method_field('POST') }}
        @include($current_admin_template.'.admin.video.form')
        {!! Form::close() !!} <!-- form_video_edit -->

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : video create -->



@endsection


@section('scripts')

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/video.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        initTinyMCEEditor( "description_container", "description", 460, 360);
    </script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\VideoRequest', '#form_video_edit'); !!}

    <script>
        /*<![CDATA[*/

        var backendVideo = new backendVideo('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendVideo.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection

