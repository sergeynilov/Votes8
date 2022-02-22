@extends($frontend_template_name.'.layouts.frontend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span><br>
        @if(isset($voteCategory->name))<span>{{ $voteCategory->name }}</span> @endif
        <small>( {{ count($activeVoteCategories) }} {{ \Illuminate\Support\Str::plural('vote', count($activeVoteCategories)) }} )</small>
    </h1>

    @include($frontend_template_name.'.layouts.logged_user')

    <div class="row ml-2 mb-3">
        {{ Breadcrumbs::render('votes-by-category', $voteCategory) }}
    </div>

    <div class="row bordered  ml-1 mr-1">

        <div class="col-sm-8 mt-0 pt-0">
            <?php $odd = true ?>


            <div class="row bordered  mt-0 pt-0">
                @foreach($activeVoteCategories as $nextActiveVote)

                    <div class="col-sm-6 mt-4 pt-0 vote-item-wrapper">
                        <div class="card">
                            <div class="img-preview-wrapper">
                                <?php $voteImagePropsAttribute = $nextActiveVote->getVoteImagePropsAttribute();?>
                                @if(isset($voteImagePropsAttribute['image_url']))
                                    <a href="{{ route('vote_by_slug', $nextActiveVote->slug ) }}">
                                        <img class="image_in_3_columns_list" src="{{ $voteImagePropsAttribute['image_url'] }}{{  "?dt=".time()  }}"
                                             alt="{{ $nextActiveVote->name }}">
                                    </a>
                                @endif
                            </div>
                            <div class="card-body pt-0">
                                <h5 class="card-title">
                                    <a href="{{ route('vote_by_slug', $nextActiveVote->slug ) }}">
                                        {{ $nextActiveVote->name }}
                                    </a>
                                </h5>


                                <p class="card-text">{!!  Purifier::clean($viewFuncs->concatStr($nextActiveVote->description,100))  !!}</p>
                                <a href="{{ route('vote_by_slug', $nextActiveVote->slug ) }}">
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
            </div> {{--<div class="row">--}}

        </div> {{--<div class="col-sm-8--}}

        @include($frontend_template_name.'.layouts.right_menu_block' , ['show_questions_block' => false, 'show_most_rated_quizzes_block' => true,
    'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )


    </div>{{--<div class="row">--}}

    <div class="row">
        {{ $activeVoteCategories->appends([])->links() }}
    </div>


@endsection
