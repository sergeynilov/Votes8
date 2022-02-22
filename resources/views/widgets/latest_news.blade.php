@inject('viewFuncs', 'App\library\viewFuncs')
@if( !empty($latestNewsData) and count($latestNewsData) > 0 )
    <div class="latest-news-block mt-0 mb-0 mt-3">

        <h3 class="text-center">News</h3>
        @foreach($latestNewsData as $nextLatestNew)

            <div class="card">
                <div class="card-body pt-0">


                    <h5 class="card-title mb-0 pb-0">
                        <a href="{{ route('news', $nextLatestNew['slug'] ) }}">
                            {!! Purifier::clean($nextLatestNew['title']) !!}
                        </a>
                        @if( $nextLatestNew['is_featured'] )
                            <span class="float-right mt-0 pt-0 badge badge-pill badge-primary">Featured</span>
                        @endif
                    </h5>

                    @if( !empty($nextLatestNew['content_shortly']) )
                        <div class="card-footer mt-0 pt-0 mb-0 pb-0">
                            <small>{!! Purifier::clean($nextLatestNew['content_shortly']) !!}</small>
                        </div>
                    @endif

                    <div class="card-footer  mt-0 pt-0">
                        <div class="row float-right mt-0 pt-0 published_by_author">
                            Published at {{ $viewFuncs->getFormattedDateTime($nextLatestNew['created_at'], 'mysql', 'ago_format') }} by {{ $nextLatestNew['username'] }}
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

        @if( $all_news_count > count($latestNewsData) )
            <div class="card">
                <div class="card-body pt-0">
                    <a href="{{ route('all-news' ) }}">
                        All News
                    </a>
                </div>
            </div>
        @endif

    </div>
@else
    @if( !empty($config['show_no_items_label']) )
        <div class="alert alert-warning small" role="alert">
            There are no news yet
        </div>
    @endif
@endif