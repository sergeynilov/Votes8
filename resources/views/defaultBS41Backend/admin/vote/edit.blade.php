@extends($current_admin_template.'.layouts.backend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <!-- Page Content : vote edit -->
    <div id="page-wrapper" class="card">

        <section class="card-body">
            <h4 class="card-title">{!! $viewFuncs->showAppIcon('vote','transparent_on_white') !!}Edit vote</h4>

            <form method="POST" action="{{ url('/admin/votes/'.$vote->id) }}" accept-charset="UTF-8" id="form_vote_edit" enctype="multipart/form-data">
                @method('PUT')
                {!! csrf_field() !!}
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="vote-details-tab" data-toggle="pill" href="#vote-details" role="tab" aria-controls="vote-details"
                           aria-selected="true">Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="pills-vote-items-tab" data-toggle="pill" href="#pills-vote-items" role="tab" aria-controls="pills-vote-items"
                           aria-selected="false">Vote Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="related-tags-tab" data-toggle="pill" href="#pills-related-tags" role="tab" aria-controls="pills-related-tags"
                           aria-selected="true">Tags
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="related-meta-tab" data-toggle="pill" href="#pills-related-meta" role="tab" aria-controls="pills-related-meta"
                           aria-selected="true">Meta
                        </a>
                    </li>
                </ul>

                <div class="tab-content " id="pills-tabContent">
                    <div class="tab-pane active" id="vote-details" role="tabpanel" aria-labelledby="vote-details-tab">
                        @include($current_admin_template.'.admin.vote.form')
                    </div>

                    <div class="tab-pane fade" id="pills-vote-items" role="tabpanel" aria-labelledby="pills-vote-items-tab">
                        @if($voteItems->count() == 0)
                            <p class="card-subtitle">Has no related vote items</p>
                            <button type="button" onclick="javascript:document.location='/admin/vote-item/create/{{$vote->id}}'" class="btn btn-primary">
                                &nbsp;Add
                            </button>&nbsp;&nbsp;
                        @else
                            <p class="card-subtitle pl-2">Has {{ $voteItems->count() }} related vote {{ \Illuminate\Support\Str::plural('item', $voteItems->count()) }}</p>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-primary ">
                                    @foreach($voteItems as $next_key => $nextVoteItem)
                                        <tr>

                                            <td>
                                                {{ $nextVoteItem->name }}
                                            </td>
                                            <td>
                                                {{ $viewFuncs->wrpGetVoteItemIsCorrectLabel($nextVoteItem->is_correct) }}
                                            </td>
                                            <td>
                                                {{ $nextVoteItem->ordering }}
                                            </td>
                                            <td>
                                                {{ $viewFuncs->getFormattedDateTime($nextVoteItem->created_at) }}
                                            </td>
                                            <td>
                                                <a href="/admin/vote-item/{{$nextVoteItem->id}}/edit" class="a_link"><i class=" fa fa-edit"></i></a>
                                            </td>
                                            <td>
                                                <a href="#"
                                                   onclick="javascript:backendVote.deleteVoteItem({{$nextVoteItem->id}}, {{$nextVoteItem->vote_id}}, '{{$nextVoteItem->name}}')"
                                                   class="a_link"><i class=" fa fa-remove"></i></a>
                                            </td>

                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6">
                                            <button type="button" onclick="javascript:document.location='/admin/vote-item/create/{{$vote->id}}'" class="btn btn-primary">
                                                &nbsp;Add
                                            </button>&nbsp;&nbsp;
                                        </td>
                                    </tr>

                                </table>
                            </div>

                        @endif
                    </div>


                    <div class="tab-pane" id="pills-related-tags" role="tabpanel" aria-labelledby="pills-related-tags">
                        <div id="div_related_tags"></div>
                    </div>

                    <div class="tab-pane" id="pills-related-meta" role="tabpanel" aria-labelledby="pills-related-meta">
                        <div id="div_meta_keywords"></div>
                    </div>

                </div>

            </form>

        </section> <!-- class="card-body" -->

    </div>
    <!-- /.page-wrapper Page Content : vote edit End -->



@endsection


@section('scripts')

    <script src="{{ asset('js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        initTinyMCEEditor("description_container", "description", 460, 360);
    </script>

    <script src="{{ asset('/js/'.$current_admin_template.'/admin/vote.js') }}{{  "?dt=".time()  }}"></script>

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

