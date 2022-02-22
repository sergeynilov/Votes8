@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : event create -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('event','transparent_on_white') !!}Create event</h4>

            <form method="POST" action="{{ url('/admin/events') }}" accept-charset="UTF-8" id="form_event_edit" enctype="multipart/form-data">
                {!! csrf_field() !!}
                @include($current_admin_template.'.admin.event.form')
            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper  Page Content : event create  -->


@endsection


@section('scripts')

    <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/daterangepicker.min.js') }}"></script>

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/event.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        initTinyMCEEditor( "description_container", "description", 460, 360);
    </script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\EventRequest', '#form_event_edit'); !!}

    <script>
        /*<![CDATA[*/

        var backendEvent = new backendEvent('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendEvent.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection