@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : vote create -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('vote','transparent_on_white') !!}Create vote</h4>

            <form method="POST" action="{{ url('/admin/votes') }}" accept-charset="UTF-8" id="form_vote_edit" enctype="multipart/form-data">
                {!! csrf_field() !!}
                @include($current_admin_template.'.admin.vote.form')
            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : vote create -->



@endsection


@section('scripts')

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/vote.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        initTinyMCEEditor( "description_container", "description", 460, 360);
    </script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\VoteRequest', '#form_vote_edit'); !!}

    <script>
        /*<![CDATA[*/

        var backendVote = new backendVote('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendVote.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection

