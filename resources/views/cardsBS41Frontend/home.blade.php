@extends($frontend_template_name.'.layouts.frontend')

@section('content')

    @inject('viewFuncs', 'App\library\viewFuncs')


    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span>
        <br><i class="fa fa-heart"></i>&nbsp;@if(isset($site_subheading))
            <small>{{ $site_subheading }}</small>@endif
    </h1>
    @include($frontend_template_name.'.layouts.logged_user')


    <div class="row ml-1 mb-3">
        {{ Breadcrumbs::render('home') }}
    </div>


    <div class="row bordered ml-1 mr-1">
        <div class="col-sm-8">
            @if( count($activeQuizVotes) > 0 )
                <h3 class="text-center">
                    Quizzes
                </h3>


                <div class="row bordered">
                    <?php $odd = true ?>
                    @foreach($activeQuizVotes as $nextActiveQuizVote)

                        <div class="col-12 col-sm-6 m-0 p-2 mb-0 vote-item-wrapper">
                            <div class="card">
                            <span class="w-80 mx-auto px-4 py-1 rounded-bottom category-link primary border-info text-white">
                                @if(!empty($nextActiveQuizVote->vote_category_name) )
                                    <a href="{{ route('votes-by-category', $nextActiveQuizVote->vote_category_slug ) }}"><strong>{{ $nextActiveQuizVote->vote_category_name
                                }} </strong></a>
                                @endif
                            </span>

                                <div class="img-preview-wrapper">
                                    <?php $voteImagePropsAttribute = $nextActiveQuizVote->getVoteImagePropsAttribute();?>
                                    @if(isset($voteImagePropsAttribute['image_url']) )
                                        <a href="{{ route('vote_by_slug', $nextActiveQuizVote->slug ) }}">
                                            <img class="image_in_3_columns_list" src="{{ $voteImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}"
                                                 alt="{{ $nextActiveQuizVote->name }}">
                                        </a>
                                    @endif
                                </div>
                                <div class="card-body pt-0">
                                    <h5 class="card-title">
                                        <a href="{{ route('vote_by_slug', $nextActiveQuizVote->slug ) }}">
                                            {{ $nextActiveQuizVote->name }}
                                        </a>
                                    </h5>
                                    <p class="card-text">{!!  Purifier::clean($viewFuncs->concatStr($nextActiveQuizVote->description,100))  !!}</p>
                                    <a href="{{ route('vote_by_slug', $nextActiveQuizVote->slug ) }}">
                                        Go to Quiz
                                    </a>
                                </div>
                            </div>

                        </div>


                        @if($odd)
                            <div class="clearfix "></div>
                        @endif

                        <?php $odd = ! $odd; ?>
                    @endforeach

                </div>

                <div class="row">
                    {{ $activeQuizVotes->appends([])->links() }}
                </div>

            @endif

            <div class="row bordered">
                {{ Widget::run('LatestNews', [
                    'items_per_block' => 4,
                    'filter_published'=> 1,
                    'filter_is_homepage'=>1
                ]) }}
            </div>

            <div class="row bordered">
                {{ Widget::run('LatestExternalNews', [
                    'items_per_block' => 10,
                    'filter_published'=> 1,
                    'filter_is_homepage'=>1
                ]) }}
            </div>

        </div>


        @include($frontend_template_name.'.layouts.right_menu_block' , [ 'show_questions_block' => true, 'show_most_rated_quizzes_block' => true,
            'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )


    </div>{{--<div class="row bordered">--}}






    {{--<div class="row bordered">--}}
    {{--<div class="col-sm-4 ">col-sm-4</div>--}}
    {{--<div class="col-sm-4 ">col-sm-4</div>--}}
    {{--<div class="col-sm-4 ">col-sm-4</div>--}}
    {{--</div>--}}
    {{--<div class="row bordered">--}}
    {{--<div class="col-sm-4 ">col-sm-4</div>--}}
    {{--<div class="col-sm-4 ">col-sm-4</div>--}}
    {{--<div class="col-sm-4 ">col-sm-4</div>--}}
    {{--</div>--}}



@endsection
