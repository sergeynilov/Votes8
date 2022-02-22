@if( !empty($activeVoteCategories) and count($activeVoteCategories) > 0 )
    <div class="active-vote-categories-block">
        <h4>Vote Categories</h4>
        @foreach($activeVoteCategories as $nextActiveVoteCategory)
            <h5 class="mt-1 mb-2">
                <a href="{{ route('votes-by-category', $nextActiveVoteCategory['slug'] ) }}" class="a_link">
                    {{ $nextActiveVoteCategory['name'] }}
                    <small>{{ $nextActiveVoteCategory['votes_count'] }} {{ \Illuminate\Support\Str::plural('vote', $nextActiveVoteCategory['votes_count'] ) }} </small>
                </a>
            </h5>
        @endforeach

    </div>
@else
    @if( !empty($config['show_no_items_label']) )
        <div class="alert alert-warning small" role="alert">
            There are no vote categories yet
        </div>
    @endif
@endif

