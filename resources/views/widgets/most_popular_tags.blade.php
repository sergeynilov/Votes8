@if( !empty($mostVotesTaggableData) and count($mostVotesTaggableData) > 0 )
    <div class="taggable-votes-block">
        <h4>Most Popular Tags</h4>
        @foreach($mostVotesTaggableData as $nextMostVoteTaggable)
            <h5 class="mt-1 mb-2">
                <a href="{{ route('tag_by_slug', $nextMostVoteTaggable['tag_slug'] ) }}" class="a_link">
                    {{ $nextMostVoteTaggable['tag_name'] }}
                    <small>{{ $nextMostVoteTaggable['taggables_count'] }} {{ \Illuminate\Support\Str::plural('vote', $nextMostVoteTaggable['taggables_count']) }} </small>
                </a>
            </h5>
        @endforeach
        
    </div>
@else
    @if( !empty($config['show_no_items_label']) )
        <div class="alert alert-warning small" role="alert">
            There are no tags yet
        </div>
    @endif
@endif
