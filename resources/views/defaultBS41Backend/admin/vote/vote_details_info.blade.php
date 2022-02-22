@inject('viewFuncs', 'App\library\viewFuncs')

<?php $voteImagePropsAttribute = $vote->getVoteImagePropsAttribute();?>


<table style="border: 1px dotted">
    <tr>
        <td>Slug:</td>
        <td>{{ $vote->slug }}</td>
    </tr>
    <tr>
        <td>Homepage:</td>
        <td>{{ $viewFuncs->wrpGetVoteIsHomepageLabel($vote->is_homepage) }}</td>
    </tr>

    @if(!empty($vote->updated_at))
        <tr>
            <td>Updated at:</td>
            <td>{{ $viewFuncs->getFormattedDateTime($vote->updated_at) }}</td>
        </tr>
    @endif

    @if(isset($voteImagePropsAttribute['image_url']) )
    <tr>
        <td>Image</td>
        <td>
            <a class="a_link" target="_blank" href="{{ $voteImagePropsAttribute['image_url'] }}">
                <img class="image_preview" src="{{ $voteImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}" alt="{{ $vote->name }}">
            </a>
        </td>
    </tr>
    @endif

</table>


@if($voteItems->count() == 0)

    <p class="card-subtitle mt-2">Has no related vote items</p>
    <div>
        <button type="button" onclick="javascript:document.location='/admin/vote-item/create/{{$vote_id}}'" class="btn btn-primary">&nbsp;Add</button>&nbsp;&nbsp;
    </div>



@else
    <p class="card-subtitle">Has {{ $voteItems->count() }} related vote {{ \Illuminate\Support\Str::plural('item', $voteItems->count()) }}</p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-primary">


            @foreach($voteItems as $next_key => $nextVoteItem)
                <tr>
                    <td>
                        {{ $nextVoteItem->name }}
                        '<a href="{{ url("/admin/vote-item/" . $nextVoteItem->id) }}/edit" class="a_link"><i class=" fa fa-edit"></i></a>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td>
                    <button type="button" onclick="javascript:document.location='/admin/vote-item/create/{{$vote_id}}'" class="btn btn-primary">
                        &nbsp;Add
                    </button>&nbsp;&nbsp;
                </td>
            </tr>

        </table>
    </div>

@endif

@if( count($relatedTags) > 0)
<div class="table-responsive">
    <table class="table text-primary " id="get-contact-us-dt-listing-table">
    @foreach($relatedTags as $relatedTag)
        <tr>
            <td>
                <a href="{{ route('admin.tags.edit', [$relatedTag['id']] ) }}" target="_blank">
                    <span class="badge">{{ $relatedTag->name  }}</span></a>&nbsp;&nbsp;
            </td>
        </tr>
    @endforeach
    </table>
</div>
@endif
