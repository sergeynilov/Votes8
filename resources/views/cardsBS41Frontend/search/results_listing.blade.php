@extends($frontend_template_name.'.layouts.frontend')

@section('content')
    @inject('viewFuncs', 'App\library\viewFuncs')

    <h1 class="text-center">
        @if(isset($site_heading))<span>{{ $site_heading }}@endif</span><br>
        Search by "@if(isset($search_text))<span>{{ $search_text }}</span>@endif"
        <small>( {{ count($foundVotesRows) }} {{ \Illuminate\Support\Str::plural('vote', count($foundVotesRows)) }} found)</small>
    </h1>

    @include($frontend_template_name.'.layouts.logged_user')

    <div class="row ml-2 mb-3">
        {{--            {{ Breadcrumbs::render('search-results', 'Search : '.$search_text) }}--}}
    </div>

    <div class="row ml-2 mb-3">
        <label class="col-12 col-form-label" for="listing_input_search">Repeat search : </label>
        <div class="col-12">
            <input id="listing_input_search" value="{{ $search_text }}" size="255" class="form-control editable_field">
            <input id="hidden_vote_categories" value="{{ $vote_categories }}" type="hidden">

        </div>
        <div class="col-12 mt-2">
            <button type="button" class="btn btn-primary" onclick="javascript:runSearchListing(); return;"
            ><span class="btn-label"></span> &nbsp;Search
            </button>
            @if( count($voteCategories) == 1 )
                &nbsp;{{ count($voteCategories) }} category selected
            @endif
            @if( count($voteCategories) > 1 )
                &nbsp;{{ count($voteCategories) }} categories selected
            @endif
        </div>

    </div>

    @if( count($foundVotesRows) > 0 )
        <div class="row bordered">

            <div class="col-sm-8 mt-0 pt-0">

                <div class="row bordered  mt-0 pt-0">
                    @foreach($foundVotesRows as $nextFoundVote)
                        <div class="col-sm-12 mt-4 pt-0 vote-item-wrapper">
                            <div class="card">
                                <div class="card-body pt-0">


                                    <h5 class="card-title">
                                        <a href="{{ route('vote_by_slug', $nextFoundVote['_source']['slug'] ) }}">
                                            {!! $nextFoundVote['_source']['name'] !!}
                                        </a>
                                    </h5>
                                    <p class="card-text">{!!  Purifier::clean($nextFoundVote['_source']['description'])  !!}</p>
                                    {{--                                        <p class="card-text">{!!  $viewFuncs->concatStr($nextFoundVote['_source']['description'],3000)  !!}</p>--}}

                                    <div class="card-footer">
                                        <a href="{{ route('votes-by-category', $nextFoundVote['_source']['category_slug'] ) }}" class="a_link">
                                            category_id::{{ $nextFoundVote['_source']['category_id'] }}:: {{ $nextFoundVote['_source']['category_name'] }}
                                        </a>
                                        <div class="row float-right">
                                            Published at {{ $viewFuncs->getFormattedDateTime($nextFoundVote['_source']['created_at']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{--[id] => 16--}}
                        {{--[slug] => in-the-film-babe-what-type-of-animal-was-babe--}}
                        {{--[name] => In the film Babe, what type of animal was Babe?--}}
                        {{--[description] => Babe is a 1995 Australian-American comedy-drama film directed by Chris Noonan, produced by George Miller, and written by both. It is an adaptation of Dick King-Smith's 1983 novel The Sheep-Pig, also known as Babe: The Gallant Pig in the US, which tells the story of a pig raised as livestock who want ...--}}
                        {{--[created_at] => 2018-11-10 09:14:15--}}
                        {{--[category_name] => Movie&Cartoons--}}
                        {{--[category_slug] => movie-cartoons--}}

                    @endforeach
                </div> {{--<div class="row">--}}

            </div> {{--<div class="col-sm-8--}}
            @include($frontend_template_name.'.layouts.right_menu_block' , [ 'show_most_rated_quizzes_block' => true,
'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )

        </div>{{--<div class="row">--}}
    @else
        <div class="row bordered">

            <div class="col-sm-8 mt-0 pt-0">
                <div class="alert alert-warning" role="alert">
                    <p>Nothing found ! Try to change search criteria. </p>
                </div>
            </div>
            @include($frontend_template_name.'.layouts.right_menu_block' , [ 'show_most_rated_quizzes_block' => true,
        'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )

        </div>

    @endif

    {{--@include($frontend_template_name.'.layouts.right_menu_block' , ['show_questions_block' => true, 'show_most_rated_quizzes_block' => true,--}}
    {{--'show_most_taggable_votes_block' => true, 'show_vote_categories_block'=> true ] )--}}


@endsection
