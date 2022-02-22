@extends($current_admin_template.'.layouts.backend')

@section('content')

    <link rel="stylesheet" href="{{ url('css/jquery.fileupload.css') }} "/>

    <script src="{{ url('js/fileupload/jquery.ui.widget.js') }}"></script>

    <script src="{{ url('js/fileupload/jquery.fileupload.js') }}"></script>
    <script src="{{ url('js/fileupload/jquery.fileupload-process.js') }}"></script>
    <script src="{{ url('js/fileupload/jquery.iframe-transport.js') }}"></script>

    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : page content edit -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('page-content','transparent_on_white') !!}Edit page</h4>



            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    {{--active--}}
                    <a class="nav-link active" id="page-content-details-tab" data-toggle="pill" href="#page-content-details" role="tab" aria-controls="page-content-details"
                       aria-selected="true">Details
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="pills-page-content-images-tab" data-toggle="pill" href="#pills-page-content-images" role="tab" aria-controls="pills-page-content-images"
                       aria-selected="false">Images / Videos
                    </a>
                </li>

                {{--<li class="nav-item">--}}
                    {{--<a class="nav-link " id="related-page-content-additive-data-tab" data-toggle="pill" href="#pills-page-content-additive-data" role="tab" aria-controls="page-content-additive-data"--}}
                       {{--aria-selected="true">additive data--}}
                    {{--</a>--}}
                {{--</li>--}}
            </ul>

            

            <div class="tab-content " id="pills-tabContent">
                {{----}}
                <div class="tab-pane active " id="page-content-details" role="tabpanel" aria-labelledby="page-content-details-tab">

                    <form method="POST" action="{{ url('/admin/page-contents/'.$pageContent->id) }}" accept-charset="UTF-8" id="form_page_content_edit" enctype="multipart/form-data">
                        @method('PUT')
                        {!! csrf_field() !!}
                        @include($current_admin_template . '.admin.page_content.form')
                    </form>

                </div>

                <div class="tab-pane fade " id="pills-page-content-images" role="tabpanel" aria-labelledby="pills-page-content-images-tab">
                    @include($current_admin_template . '.admin.page_content.tp_images')
                </div>


                {{--<div class="tab-pane" id="pills-page-content-additive-data" role="tabpanel" aria-labelledby="pills-page-content-additive-data-tab">--}}
                    {{--<div id="div_page-content-additive-data">page-content-additive-data</div>--}}
                {{--</div>--}}

            </div>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : page content edit -->



@endsection


@section('scripts')

    <script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        initTinyMCEEditor( "content_container", "content", 460, 360, [ "/page-content-images/-page-content-image-{{ $pageContent->id }}" ]);
    </script>

    <script src="{{ asset('js/video.js') }}"></script>
    <link href="{{ asset('css/video-js.css') }}" rel="stylesheet" type="text/css">

    <script src="{{ asset('js/'.$current_admin_template.'/admin/page_content.js') }}{{  "?dt=".time()  }}"></script>

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

