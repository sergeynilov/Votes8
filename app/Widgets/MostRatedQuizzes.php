<?php

namespace App\Widgets;

use DB;
use Arrilot\Widgets\AbstractWidget;
use App\QuizQualityResult;
use App\Http\Traits\funcsTrait;
use App\VoteCategory;
use App\Vote;
use App\Settings;
use App\library\CheckValueType;

class MostRatedQuizzes extends AbstractWidget
{
    use funcsTrait;
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'items_per_block'         => 5,
        'order_by_field_name'     => 'quiz_quality_avg',
        'order_by_field_ordering' => 'desc',
        'show_no_items_label'     => false,
    ];

    public function __construct(array $config = [])
    {
        $this->config['items_per_block']= Settings::getValue('most_rating_quiz_quality_on_homepage', CheckValueType::cvtInteger, 8);
        $this->config['items_per_block']= isset( $config['items_per_block'] ) ? $config['items_per_block'] : $this->config['items_per_block'];
        parent::__construct($config);
    }

    public function run()
    {
        $quizQualityOptions                   = with(new QuizQualityResult)->getQuizQualityOptions();

        $votes_tb= with(new Vote)->getTable();
        $quiz_quality_results_tb= with(new QuizQualityResult())->getTable();

        $mostRatingQuizQualityResultsData     = [];
        $mostRatingQuizQualityResultsDataTemp = QuizQualityResult
            ::select( $votes_tb.'.name as vote_name', $votes_tb.'.slug as vote_slug', $quiz_quality_results_tb.'.vote_id', DB::raw('count('.DB::getTablePrefix(). $quiz_quality_results_tb.'.quiz_quality_id) as quiz_quality_count'), DB::raw('avg('.DB::getTablePrefix().$quiz_quality_results_tb . '.quiz_quality_id) as quiz_quality_avg') )
            ->getByVoteStatus('A')
            ->orderBy($this->config['order_by_field_name'], $this->config['order_by_field_ordering'])
            ->groupBy('vote_id')
            ->groupBy('vote_slug')
            ->groupBy('vote_name')
            ->join( $votes_tb, $votes_tb.'.id', '=', $quiz_quality_results_tb.'.vote_id' )
            ->limit ($this->config['items_per_block'] )
            ->get();

        foreach ($mostRatingQuizQualityResultsDataTemp as $next_key => $mostRatingQuizQualityResultsDataTemp) {
            $cealing_quiz_quality_id            = ceil($mostRatingQuizQualityResultsDataTemp->quiz_quality_avg);
            $mostRatingQuizQualityResultsData[] = [
                'vote_name'         => $mostRatingQuizQualityResultsDataTemp->vote_name,
                'vote_slug'         => $mostRatingQuizQualityResultsDataTemp->vote_slug,
                'quiz_quality_id'   => $mostRatingQuizQualityResultsDataTemp->quiz_quality_id,
                'quiz_quality_avg'  => round($mostRatingQuizQualityResultsDataTemp->quiz_quality_avg, 1),
                'quiz_quality_name' => ! empty($quizQualityOptions[$cealing_quiz_quality_id]) ?
                    $quizQualityOptions[$cealing_quiz_quality_id] : '',
            ];
        }

        $viewParamsArray['mostRatingQuizQualityResultsData'] = $mostRatingQuizQualityResultsData;
        return view('widgets.most_rated_quizzes', [
            'config'                            => $this->config,
            'mostRatingQuizQualityResultsData'  => $mostRatingQuizQualityResultsData,
        ]);
    }
}
