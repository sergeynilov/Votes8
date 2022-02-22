<div class="col-sm-4 m-0">


    
    @if(!empty($activeNonQuizVotes) and count($activeNonQuizVotes) > 0 and $show_questions_block )
        <h3 class="text-center">
            Questions
        </h3>

        <div class="row bordered" style="border: 0px dotted green;">
            @foreach($activeNonQuizVotes as $nextActiveNonQuizVote)

            <!-- Active Non Quiz Vote : {{ $nextActiveNonQuizVote->name }} -->
                <div class="col-sm-12 m-0 p-2 mb-4 vote-item-wrapper">
                    <div class="card">
                        <span class="w-80 mx-auto px-4 py-1 rounded-bottom category-link border-info text-white primary">
                            @if(!empty($nextActiveNonQuizVote->vote_category_name) )
                                <a href="{{ route('votes-by-category', $nextActiveNonQuizVote->vote_category_slug ) }}"><strong>{{
                                    $nextActiveNonQuizVote->vote_category_name }}</strong>
                                </a>
                            @endif
                        </span>

                        <div class="img-preview-wrapper">
                            <?php $voteImagePropsAttribute = $nextActiveNonQuizVote->getVoteImagePropsAttribute();?>
                            @if( isset($voteImagePropsAttribute['image_url']) )
                                <a href="{{ route('vote_by_slug', $nextActiveNonQuizVote->slug ) }}">
                                    <img class="image_in_3_columns_list" src="{{ $voteImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}" alt="{{ $nextActiveNonQuizVote->name }}">
                                </a>
                            @endif
                        </div>

                        <div class="card-body pt-0">
                            <h5 class="card-title">
                                <a href="{{ route('vote_by_slug', $nextActiveNonQuizVote->slug ) }}">
                                    {{ $nextActiveNonQuizVote->name }}
                                </a>
                            </h5>
                            <p class="card-text">{!!  Purifier::clean($viewFuncs->concatStr($nextActiveNonQuizVote->description,100))  !!}</p>
                            <a href="{{ route('vote_by_slug', $nextActiveNonQuizVote->slug ) }}">
                                Go to Question
                            </a>
                        </div>

                    </div>

                </div>

            @endforeach
            {{--@foreach($activeNonQuizVotes as $nextActiveNonQuizVote)--}}
        </div>{{--<div class="row bordered">--}}


    @endif
    {{--@if(count($activeNonQuizVotes) > 0)--}}



    @if($show_most_rated_quizzes_block )
        {{ Widget::run('MostRatedQuizzes', ['items_per_block' => 8, 'order_by_field_name'=> 'quiz_quality_avg', 'order_by_field_ordering'=>'desc']) }}
    @endif


    @if($show_most_taggable_votes_block)
        {{ Widget::run('MostPopularTags', ['items_per_block' => 10, 'order_by_field_name'=> 'taggables_count', 'order_by_field_ordering'=>'desc']) }}
    @endif

    @if($show_vote_categories_block)
        {{ Widget::run('VoteCategories', ['order_by_field_name'=> 'votes_count', 'order_by_field_ordering'=>'desc']) }}
    @endif


    {{ Widget::run('FrontendBanners', ['order_by_field_name'=> 'ordering', 'order_by_field_ordering'=>'desc']) }}

</div>{{--<div class="col-sm-4" >--}}
