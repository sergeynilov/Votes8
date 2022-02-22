<h4 class="mt-1">All voted : <small>{{ $votes_result_count }} / {{ $correct_votes_result_count }} correct</small></h4>

<div class="row m-0">

    @foreach($voteItemsResults as $nextVoteItemsResult)

        <div class="pull-left chart_progress_bar mb-0 pb-0">
            {{ $nextVoteItemsResult['vote_item_name'] }}
            @if($nextVoteItemsResult['vote_item_is_correct'])
                <span class="fa fa-star" style="color: gold;" ></span>
                <span class="fa fa-star" style="color: gold;"></span>
                <span class="fa fa-star" style="color: gold;"></span>
            @endif
            <div class="progress" style="height:10px; margin:2px; padding: 0 ">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5"
                     style="width: {{ $nextVoteItemsResult['percent'] }}%">
                    <span class="sr-only">{{ $nextVoteItemsResult['count'] }} {{ \Illuminate\Support\Str::plural('vote',  $nextVoteItemsResult['count']) }}</span>
                </div>
            </div>
        </div>
        <div class="pull-right  mb-3" style="margin-left:10px;">{{ $nextVoteItemsResult['count'] }} {{ \Illuminate\Support\Str::plural('vote',  $nextVoteItemsResult['count']) }}</div>

    @endforeach
</div>

