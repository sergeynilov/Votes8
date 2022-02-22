@if( !empty($mostRatingQuizQualityResultsData) and count($mostRatingQuizQualityResultsData) > 0 )
    <div class="pb-4 most-rating-quiz-quality-block">
        {{--block-selection--}}
        <h4>Most Rated Quizzes</h4>

        @foreach($mostRatingQuizQualityResultsData as $nextMostRatingQuizQualityResult)
            <h5 class="mb-1">

                <a href="{{ route('vote_by_slug', $nextMostRatingQuizQualityResult['vote_slug'] ) }}" target="_blank" class="a_link">
                    {{  $nextMostRatingQuizQualityResult['vote_name']  }}
                </a>
            </h5>

            @if( $nextMostRatingQuizQualityResult['quiz_quality_avg']>= 1 )
                <button type="button" class="btn btn-warning btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            @else
                <button type="button" class="btn btn-default btn-grey btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            @endif

            @if( $nextMostRatingQuizQualityResult['quiz_quality_avg']>= 2 )
                <button type="button" class="btn btn-warning btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            @else
                <button type="button" class="btn btn-default btn-grey btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            @endif

            @if( $nextMostRatingQuizQualityResult['quiz_quality_avg']>= 3 )
                <button type="button" class="btn btn-warning btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            @else
                <button type="button" class="btn btn-default btn-grey btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            @endif

            @if( $nextMostRatingQuizQualityResult['quiz_quality_avg']>= 4 )
                <button type="button" class="btn btn-warning btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            @else
                <button type="button" class="btn btn-default btn-grey btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            @endif

            @if( $nextMostRatingQuizQualityResult['quiz_quality_avg']>= 5 )
                <button type="button" class="btn btn-warning btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            @else
                <button type="button" class="btn btn-default btn-grey btn-sm" aria-label="Left Align">
                    <span class="fa fa-star" aria-hidden="true"></span>
                </button>
            @endif
            
        @endforeach
    </div>
@else
    @if( !empty($config['show_no_items_label']) )
        <div class="alert alert-warning small" role="alert">
            There are no rated quizzes yet
        </div>
    @endif
@endif