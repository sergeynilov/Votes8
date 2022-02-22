
<ul class="list-unstyled video-list-thumbs row">
    @foreach($videos as $video)
        <li class="col-lg-3 col-sm-4 col-6">
            <a href="http://youtube.com/watch?v={{ $video->getId() }}" title="{{ Purifier::clean($video['snippet']['title']) }}">
                <img src="{{ $video['snippet']['thumbnails']['medium']['url'] }}" alt="{{ Purifier::clean($video['snippet']['title']) }}" />
                <h2 class="truncate">{{ Purifier::clean($video['snippet']['title']) }}</h2>
            </a>
        </li>
    @endforeach
</ul>

<ul class="pagination pagination-lg">
    <li @if($videos->getPrevPageToken() == null) class="disabled" @endif>
        <a href="/videos?page={{$videos->getPrevPageToken()}}" aria-label="Previous">
            <span aria-hidden="true">Previous &laquo;</span>
        </a>
    </li>
    <li @if($videos->getNextPageToken() == null) class="disabled" @endif>
        <a href="/videos?page={{$videos->getNextPageToken()}}" aria-label="Next">
            <span aria-hidden="true">Next &raquo;</span>
        </a>
    </li>
</ul>
