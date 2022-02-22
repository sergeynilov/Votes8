@if(count($tagsList) == 0)
    <h4 class="card-subtitle pl-2 pb-2">Has no related tags</h4>
    <button type="button" onclick="javascript:document.location='/admin/vote-item/create/{{$vote->id}}'" class="btn btn-primary">
        &nbsp;Add
    </button>&nbsp;&nbsp;
@else
    <h4 class="card-subtitle pl-2 pb-2">Has {{ count($tagsList) }} tags, {{ $selected_tags_count }} selected for this vote. </h4>

    <div class="table-responsive" style="max-height: 600px; overflow-y:auto;">
        <table class="table table-bordered table-striped text-primary ">
            @foreach($tagsList as $next_key => $nextRelatedTag)
                <tr>
                    <td>
                        {{ $nextRelatedTag['name'] }}
                    </td>
                    <td>

                        @if(!empty($nextRelatedTag['selected']))
                            <a href="#" onclick="javascript:backendVote.clearTagToVote({{$nextRelatedTag['id']}},'{{$nextRelatedTag['name']}}')">
                                <i class="fa fa-remove"></i>
                            </a>
                            <b>Selected</b>&nbsp;
                        @else
                            <a href="#" onclick="javascript:backendVote.attachTagToVote({{$nextRelatedTag['id']}},'{{$nextRelatedTag['name']}}')">
                                <i class="fa fa-paperclip"></i>
                            </a>
                        @endif

                    </td>

                </tr>
            @endforeach

        </table>
    </div>

{{--    <div>--}}
{{--        <button type="button" onclick="javascript:document.location='/admin/vote-item/create/{{$vote->id}}'" class="btn btn-primary">--}}
{{--          &nbsp;Add--}}
{{--        </button>&nbsp;&nbsp;--}}
{{--    </div>--}}

@endif
