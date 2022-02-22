@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')


    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
        <br> {{ $all_news_count }} News
    </h1>

    @include($frontend_template_name.'.layouts.logged_user')

    <div class="row ml-2 mb-3">
        {{ Breadcrumbs::render('all-news', 'News') }}
    </div>


    <div class="row ml-1 mr-1">
        <div class="col-sm-8 ">

            <div class="row">


                @if( !empty($newsList) and count($newsList) > 0 )
                    <div class="latest-news-block mt-0 mb-0 mt-3">


                        @foreach($newsList as $nextNews)

                            <div class="card">
                                <div class="card-body pt-4">

                                    <h5 class="card-title mb-0 pb-0">
                                        <a href="{{ route('news', $nextNews['slug'] ) }}">
                                            {{ Purifier::clean($nextNews['title']) }}
                                        </a>
                                        @if( $nextNews['is_featured'] )
                                            <span class="float-right mt-0 pt-0 badge badge-pill badge-primary">Featured</span>
                                        @endif
                                    </h5>

                                    @if( !empty($nextNews['content_shortly']) )
                                        <div class="card-footer mt-0 pt-0 mb-0 pb-0">
                                            <small>{!! Purifier::clean($nextNews['content_shortly']) !!}</small>
                                        </div>
                                    @endif

                                    <div class="card-footer  mt-0 pt-0">
                                        <div class="row float-right mt-0 pt-0">
                                            Published at {{ $viewFuncs->getFormattedDateTime($nextNews['created_at'], 'mysql', 'ago_format') }} by {{
                                            $nextNews['username'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach


                        <div class="row m-3">
                            {{ $newsList->appends([])->links() }}
                        </div>


                    </div>

                @else
                    <div class="alert alert-warning small" role="alert">
                        There are no news yet
                    </div>
                @endif

            </div>
        </div>


        @include($frontend_template_name.'.layouts.right_menu_block' , ['show_questions_block' => false, 'show_most_rated_quizzes_block' => true,
'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )

    </div>

@endsection
