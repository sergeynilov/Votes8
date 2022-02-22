@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : vote items edit -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">Edit vote item of <a href="{{ route('admin.votes.edit', $voteItem->vote_id ) }}"><i class=" fa fa-edit">"{{ $parentVote->name }}"</i></a> vote
            </h4>

            <form method="POST" action="{{ url('/admin/vote-item/'.$voteItem->id) }}/update" accept-charset="UTF-8" id="form_vote_item_edit"
                  enctype="multipart/form-data">
                @method('PUT')
            {!! csrf_field() !!}

            @include($current_admin_template.'.admin.vote_item.form')
            </form>

        </section> <!-- class="card-body" -->

    </div>

    <!-- /.page-wrapper  Page Content : vote items edit  -->

@endsection


@section('scripts')

    <script src="{{ asset('js/'.$current_admin_template.'/admin/vote_item.js') }}{{  "?dt=".time()  }}"></script>

    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\VoteItemRequest', '#form_vote_item_edit'); !!}


    <script>
        /*<![CDATA[*/

        var backendVoteItem = new backendVoteItem('edit',  // must be called before jQuery(document).ready(function ($) {
            <?php echo $appParamsForJSArray ?>
        );
        jQuery(document).ready(function ($) {
            backendVoteItem.onBackendPageInit('edit')
        });

        /*]]>*/
    </script>


@endsection

