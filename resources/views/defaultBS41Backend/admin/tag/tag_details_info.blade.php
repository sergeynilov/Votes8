@inject('viewFuncs', 'App\library\viewFuncs')

<?php $tagDetailImagePropsAttribute = $tagDetail->getTagDetailImagePropsAttribute();?>

<table style="border: 1px dotted">

    @if(!empty($tagDetail->description))
        <tr>
            <td>Description:</td>
            <td>{!! $viewFuncs->concatStr($tagDetail->description,400) !!}</td>
        </tr>
    @endif

    @if(isset($tagDetailImagePropsAttribute['image_url']) )
        <tr>
            <td>Image</td>
            <td>
                <a class="a_link" target="_blank" href="{{ $tagDetailImagePropsAttribute['image_url'] }}">
                    <img class="image_preview" src="{{ $tagDetailImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}" >
                </a>
            </td>
        </tr>
    @endif

</table>


@if( count($tagRelatedVotes) == 0)

    <p class="card-subtitle mt-2">Has no related votes</p>
    <div>
        <button type="button" onclick="javascript:document.location='/admin/tag_item/create/{{$tag_id}}'" class="btn btn-primary">&nbsp;Add</button>&nbsp;&nbsp;
    </div>
@else
    <p class="card-subtitle">Has {{ count($tagRelatedVotes) }} related {{ \Illuminate\Support\Str::plural('vote', count($tagRelatedVotes)) }}</p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-primary">
            @foreach($tagRelatedVotes as $next_key => $nextTagRelatedVote)
                <tr>
                    @if($next_key == 0)
                        <td rowspan="{{ count($tagRelatedVotes) }}">
                            @endif

                            @if($next_key == 0)
                        </td>

                    @endif

                    <td>
                        {{ $nextTagRelatedVote->name }}
                        '<a href="{{ url("/admin/vote/edit/" . $nextTagRelatedVote['id']) }}" class="a_link" target="_blank"><i class=" fa fa-edit"></i></a>
                    </td>

                </tr>
            @endforeach

        </table>
    </div>

@endif
