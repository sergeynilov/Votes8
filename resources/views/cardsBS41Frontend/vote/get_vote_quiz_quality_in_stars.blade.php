<h4 class="mt-1">All voted : <small>{{ $quiz_quality_result_count }} </small></h4>

<div class="row  m-0">

    @foreach($quizQualityResults as $nextQuizQualityResult)

        <div class="pull-left chart_progress_bar mb-0 pb-0" >
            {{ $nextQuizQualityResult['quiz_quality_title'] }}
            <div class="progress" style="height:10px; margin:2px; padding: 0">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5"
                     style="width: {{ $nextQuizQualityResult['percent'] }}%">
                    <span class="sr-only">{{ $nextQuizQualityResult['count'] }} {{ \Illuminate\Support\Str::plural('vote',  $nextQuizQualityResult['count']) }}</span>
                </div>
            </div>
        </div>
        <div class="pull-right mb-3" style="margin-left:10px;">{{ $nextQuizQualityResult['count'] }} {{ \Illuminate\Support\Str::plural('vote',  $nextQuizQualityResult['count'] ) }}</div>

    @endforeach
</div>

