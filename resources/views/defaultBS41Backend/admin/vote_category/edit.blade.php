@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')
    {{ csrf_field() }}

    <!-- Page Content : vote category edit -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('category','transparent_on_white') !!}Edit vote category</h4>

            <form  method="POST" action="{{ url('/admin/vote-categories/' . $voteCategory->id) }}" accept-charset="UTF-8" id="form_vote_category_edit"
                   enctype="multipart/form-data">
                @method('PUT')
                {!! csrf_field() !!}
                @include($current_admin_template . '.admin.vote_category.form')
            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper page Content : vote category edit -->



@endsection


@section('scripts')

    <script src="{{ asset('js/'.$current_admin_template.'/admin/vote_category.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\VoteCategoryRequest', '#form_vote_category_edit'); !!}

    <script>
        /*<![CDATA[*/

        var backendVoteCategory = new backendVoteCategory('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendVoteCategory.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection

