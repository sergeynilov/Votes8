<?php

namespace App\Http\Controllers\Admin;

use App\VoteCategory;
use DB;
use Auth;
use Carbon\Carbon;

use App\User;
use App\Vote;
use App\VoteItem;
use App\QuizQualityResult;
use App\VoteItemUsersResult;
use App\Payment;
use App\PaymentItem;
use App\Download;
use App\SearchResult;
use App\Http\Controllers\MyAppController;
use Illuminate\Http\Request;
use App\Http\Traits\funcsTrait;

//use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends MyAppController
{
    use funcsTrait;
    private $users_tb;
    private $votes_tb;
    private $vote_items_tb;
    private $vote_categories_tb;
    private $vote_item_users_results_tb;
    private $quiz_quality_results_tb;
    private $search_results_tb;
    private $payments_tb;
    private $payment_items_tb;
    private $downloads_tb;

    public function __construct()
    {
        $this->users_tb                   = with(new User)->getTable();
        $this->votes_tb                   = with(new Vote)->getTable();
        $this->vote_items_tb              = with(new VoteItem)->getTable();
        $this->vote_categories_tb         = with(new VoteCategory())->getTable();
        $this->vote_item_users_results_tb = with(new VoteItemUsersResult())->getTable();
        $this->quiz_quality_results_tb    = with(new QuizQualityResult())->getTable();
        $this->search_results_tb          = with(new SearchResult())->getTable();
        $this->payments_tb                = with(new Payment)->getTable();
        $this->payment_items_tb           = with(new PaymentItem)->getTable();
        $this->downloads_tb               = with(new Download)->getTable();
    }


    // VOTES BY DAYS BLOCK BEGIN
    public function votes_by_days()
    {
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'], []);
        $voteCategoriesSelectionArray = VoteCategory::getVoteCategoriesSelectionArray();
        $votesSelectionArray          = Vote::getVotesSelectionArray();
        $usersSelectionArray          = User::getUsersSelectionArray();

        $viewParamsArray['voteCategoriesSelectionArray'] = $voteCategoriesSelectionArray;
        $viewParamsArray['votesSelectionArray']          = $votesSelectionArray;
        $viewParamsArray['usersSelectionArray']          = $usersSelectionArray;

        $appParamsForJSArray['reports_demo_period_start'] = config('app.reports_demo_period_start');
        $appParamsForJSArray['reports_demo_period_end']   = config('app.reports_demo_period_end');
        $appParamsForJSArray['chartBackgroundColors']     = config('app.chartBackgroundColors');

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.reports.votes_by_days',
            $viewParamsArray);   //resources/views/defaultBS41Backend/admin/reports/votes_by_days.blade.php
    } // public function votes_by_days()


    public function votes_by_days_retrieve(Request $request)
    {
        $filter_voted_at_from = $request->input('filter_voted_at_from');
        $filter_voted_at_till = $request->input('filter_voted_at_till');
        if ( ! empty($filter_voted_at_till)) {
            $filter_voted_at_till = $filter_voted_at_till . ' 23:59:59';
        }

        $filterSelectedUsers          = $this->clearEmptyArrayItems($request->input('selectedUsers', []));
        $filterSelectedVotes          = $this->clearEmptyArrayItems($request->input('selectedVotes', []));
        $filterSelectedVoteCategories = $this->clearEmptyArrayItems($request->input('selectedVoteCategories', []));

        $voteItemUsersResultsCorrect     = [];
        $voteItemUsersResultsNoneCorrect = [];


        $tempVoteItemUsersResultsCorrect = VoteItemUsersResult
            ::getByIsCorrect(true)
            ->getByCreatedAt($filter_voted_at_from, ' > ')
            ->getByCreatedAt($filter_voted_at_till, '<= ')
            ->getByUserId($filterSelectedUsers)
            ->getByVote($filterSelectedVotes)// VoteItem table
            ->getByVoteCategories($filterSelectedVoteCategories)
            ->getByVoteIsQuiz(true)
            ->getByVoteStatus('A')//   // Vote table

            ->select(\DB::raw(' DATE_FORMAT(' . DB::getTablePrefix() . $this->vote_item_users_results_tb . '.created_at, "%Y-%m-%d") as formatted_created_at',
                \DB::raw('count(' . DB::getTablePrefix() . $this->vote_item_users_results_tb . '.id) as count')))
            ->orderBy('formatted_created_at', 'asc')
            ->groupBy('formatted_created_at')
            ->join($this->vote_items_tb, $this->vote_items_tb . '.id', '=', $this->vote_item_users_results_tb . '.vote_item_id')
            ->join($this->votes_tb, $this->votes_tb . '.id', '=', $this->vote_items_tb . '.vote_id')
            ->get();

        if (count($tempVoteItemUsersResultsCorrect) == 0) {
            return response()->json([
                'error_code'                      => 0,
                'message'                         => '',
                'voteItemUsersResultsCorrect'     => [],
                'voteItemUsersResultsNoneCorrect' => []
            ], HTTP_RESPONSE_OK);
        }
        $first_key_is_correct = $tempVoteItemUsersResultsCorrect->keys()->first();
        $last_key_is_correct  = $tempVoteItemUsersResultsCorrect->keys()->last();

        $report_first_date_is_correct    = $tempVoteItemUsersResultsCorrect[$first_key_is_correct]->formatted_created_at;
        $report_first_date_is_correct_dt = Carbon::createFromFormat('Y-m-d', $report_first_date_is_correct)->setTime(0, 0, 0); // !! $report_first_date_is_correct_dt

        $report_last_date_is_correct    = $tempVoteItemUsersResultsCorrect[$last_key_is_correct]->formatted_created_at;
        $report_last_date_is_correct_dt = Carbon::createFromFormat('Y-m-d', $report_last_date_is_correct)->setTime(0, 0, 0); // !! $report_last_date_is_correct_dt

        $tempVoteItemUsersResultsNoneCorrect = VoteItemUsersResult
            ::getByIsCorrect('0')
            ->getByCreatedAt($filter_voted_at_from, ' > ')
            ->getByCreatedAt($filter_voted_at_till, '<= ')
            ->getByUserId($filterSelectedUsers)// VoteItem table
            ->getByVote($filterSelectedVotes)
            ->getByVoteCategories($filterSelectedVoteCategories)
            ->getByVoteIsQuiz(true)// Vote table
            ->getByVoteStatus('A')
            ->select(\DB::raw(' DATE_FORMAT(' . DB::getTablePrefix() . $this->vote_item_users_results_tb . '.created_at, "%Y-%m-%d") as formatted_created_at'),
                \DB::raw('count(' . DB::getTablePrefix() . $this->vote_item_users_results_tb . '.id) as count'))
            ->orderBy('formatted_created_at', 'asc')
            ->groupBy('formatted_created_at')
            ->join($this->vote_items_tb, $this->vote_items_tb . '.id', '=', $this->vote_item_users_results_tb . '.vote_item_id')
            ->join($this->votes_tb, $this->votes_tb . '.id', '=', $this->vote_items_tb . '.vote_id')
            ->get();

        $first_key_is_none_correct            = $tempVoteItemUsersResultsNoneCorrect->keys()->first();
        $last_key_is_none_correct             = $tempVoteItemUsersResultsNoneCorrect->keys()->last();
        $report_first_date_is_none_correct    = $tempVoteItemUsersResultsNoneCorrect[$first_key_is_none_correct]->formatted_created_at;
        $report_first_date_is_none_correct_dt = Carbon::createFromFormat('Y-m-d', $report_first_date_is_none_correct)->setTime(0, 0, 0); // !! $report_first_date_is_none_correct_dt

        $report_last_date_is_none_correct    = $tempVoteItemUsersResultsNoneCorrect[$last_key_is_none_correct]->formatted_created_at;
        $report_last_date_is_none_correct_dt = Carbon::createFromFormat('Y-m-d', $report_last_date_is_none_correct)->setTime(0, 0, 0); // $report_last_date_is_none_correct_dt

        $first_date = $report_first_date_is_correct_dt;
        if ($first_date->lessThan($report_first_date_is_none_correct_dt)) {
            $first_date = $report_first_date_is_none_correct_dt;
        }


        $last_date = $report_last_date_is_correct_dt;
        if ($last_date->lessThan($report_last_date_is_none_correct_dt)) {
            $last_date = $report_last_date_is_none_correct_dt;
        }

        $next_date = $first_date;
        while ($next_date->lessThan($last_date)) {
            $correct_count_value      = 0;
            $none_correct_count_value = 0;
            foreach ($tempVoteItemUsersResultsCorrect as $nextTempVoteItemUsersResultsCorrect) {
                if ($nextTempVoteItemUsersResultsCorrect->formatted_created_at == $next_date->format('Y-m-d')) {
                    $correct_count_value = $nextTempVoteItemUsersResultsCorrect->count;
                    break;
                }
            }
            $voteItemUsersResultsCorrect[] = ['formatted_created_at' => $next_date->format('Y-m-d'), 'count' => $correct_count_value];


            foreach ($tempVoteItemUsersResultsNoneCorrect as $nextTempVoteItemUsersResultsNoneCorrect) {
                if ($nextTempVoteItemUsersResultsNoneCorrect->formatted_created_at == $next_date->format('Y-m-d')) {
                    $none_correct_count_value = $nextTempVoteItemUsersResultsNoneCorrect->count;
                    break;
                }
            }
            $voteItemUsersResultsNoneCorrect[] = ['formatted_created_at' => $next_date->format('Y-m-d'), 'count' => $none_correct_count_value];

            $next_date->addDay();
        }

        return response()->json([
            'error_code'                      => 0,
            'message'                         => '',
            'voteItemUsersResultsCorrect'     => $voteItemUsersResultsCorrect,
            'voteItemUsersResultsNoneCorrect' => $voteItemUsersResultsNoneCorrect
        ], HTTP_RESPONSE_OK);
    } // public function votes_by_days_retrieve()


    public function votes_by_vote_names_retrieve_by_selected_day(Request $request)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        $filter_voted_at_from = $request->input('filter_selected_day');
        $filter_voted_at_till = $request->input('filter_selected_day');
        if ( ! empty($filter_voted_at_till)) {
            $filter_voted_at_till = $filter_voted_at_till . ' 23:59:59';
        }

        $filterSelectedUsers          = $this->clearEmptyArrayItems($request->input('selectedUsers'/*, [1,3,5]*/));
        $filterSelectedVoteCategories = $this->clearEmptyArrayItems($request->input('selectedVoteCategories'/*, [4,3,2,5]*/));


        $detailedVoteItemUsersResults     = [];
        $subResults                       = [];
        $tempDetailedVoteItemUsersResults = VoteItemUsersResult// Grouped by vote name
        ::getByCreatedAt($filter_voted_at_from, ' > ')
        ->getByCreatedAt($filter_voted_at_till, ' <= ')
        ->getByUserId($filterSelectedUsers)
        ->getByVoteCategories($filterSelectedVoteCategories)
        ->getByVoteIsQuiz(true)
        ->getByVoteStatus('A')
        ->select($this->vote_item_users_results_tb . '.is_correct',
            $this->vote_item_users_results_tb . '.created_at as voted_at',
            $this->users_tb . '.id as user_id',
            $this->users_tb . '.first_name',
            $this->users_tb . '.last_name',
            $this->users_tb . '.username',
            $this->votes_tb . '.id as vote_id',
            $this->votes_tb . '.name as vote_name')
        ->orderBy('vote_name', 'desc')
        ->orderBy('voted_at', 'asc')
        ->join($this->vote_items_tb, $this->vote_items_tb . '.id', '=', $this->vote_item_users_results_tb . '.vote_item_id')
        ->join($this->users_tb, $this->users_tb . '.id', '=', $this->vote_item_users_results_tb . '.user_id')
        ->join($this->votes_tb, $this->votes_tb . '.id', '=', $this->vote_items_tb . '.vote_id')
        ->get();


        $correct_count     = 0;
        $not_correct_count = 0;
        $current_vote_name = '';
        $current_vote_id   = '';
        $is_first_row      = true;
        $row_number        = 0;
        foreach ($tempDetailedVoteItemUsersResults as $nextTempDetailedVoteItemUsersResult) { // all rows in result set
            $correct_count     += $nextTempDetailedVoteItemUsersResult->is_correct ? 1 : 0;
            $not_correct_count += ! $nextTempDetailedVoteItemUsersResult->is_correct ? 1 : 0;
            if ($is_first_row or $current_vote_name != $nextTempDetailedVoteItemUsersResult->vote_name) {

                if ($is_first_row) {   // get first row
                    $subResults = [
                        [
                            'is_correct'       => $nextTempDetailedVoteItemUsersResult->is_correct,
                            'is_correct_label' => VoteItemUsersResult::getVoteItemUsersResultIsCorrectLabel($nextTempDetailedVoteItemUsersResult->is_correct),
                            'voted_at'         => $nextTempDetailedVoteItemUsersResult->voted_at,
                            'user_id'          => $nextTempDetailedVoteItemUsersResult->user_id,
                            'username'         => $nextTempDetailedVoteItemUsersResult->first_name . ' ' . $nextTempDetailedVoteItemUsersResult->last_name .
                                                  ' ( ' . $nextTempDetailedVoteItemUsersResult->username . ' )'
                        ]
                    ];
                }

                if ( ! $is_first_row) {
                    $detailedVoteItemUsersResults[] = ['vote_name' => $current_vote_name, 'vote_id' => $current_vote_id, 'subResults' => $subResults];
                    $subResults                     = [ // save current vote name with sub Results and clear $subResults for new vote name
                        [
                            'is_correct'       => $nextTempDetailedVoteItemUsersResult->is_correct,
                            'is_correct_label' => VoteItemUsersResult::getVoteItemUsersResultIsCorrectLabel($nextTempDetailedVoteItemUsersResult->is_correct),
                            'voted_at'         => $nextTempDetailedVoteItemUsersResult->voted_at,
                            'user_id'          => $nextTempDetailedVoteItemUsersResult->user_id,
                            'username'         => $nextTempDetailedVoteItemUsersResult->first_name . ' ' . $nextTempDetailedVoteItemUsersResult->last_name .
                                                  ' ( ' . $nextTempDetailedVoteItemUsersResult->username . ' )',
                        ]
                    ];
                }
                $current_vote_name = $nextTempDetailedVoteItemUsersResult->vote_name;
                $current_vote_id   = $nextTempDetailedVoteItemUsersResult->vote_id;
                $is_first_row      = false;
            } else {

                $subResults[] = [
                    'is_correct'       => $nextTempDetailedVoteItemUsersResult->is_correct,
                    'is_correct_label' => VoteItemUsersResult::getVoteItemUsersResultIsCorrectLabel($nextTempDetailedVoteItemUsersResult->is_correct),
                    'voted_at'         => $nextTempDetailedVoteItemUsersResult->voted_at,
                    'user_id'          => $nextTempDetailedVoteItemUsersResult->user_id,
                    'username'         => $nextTempDetailedVoteItemUsersResult->first_name . ' ' . $nextTempDetailedVoteItemUsersResult->last_name .
                                          ' ( ' . $nextTempDetailedVoteItemUsersResult->username . ' )',
                ];

            }
            $row_number++;
        } // foreach( $tempDetailedVoteItemUsersResults as $nextTempDetailedVoteItemUsersResult ) { // all rows in result set

        $detailedVoteItemUsersResults[] = ['vote_name' => $current_vote_name, 'vote_id' => $current_vote_id, 'subResults' => $subResults];

        $viewParamsArray['detailedVoteItemUsersResults'] = $detailedVoteItemUsersResults;
        $viewParamsArray['correct_count']                = $correct_count;
        $viewParamsArray['not_correct_count']            = $not_correct_count;
        $html                                            = view($this->getBackendTemplateName() . '.admin.reports.votes_by_vote_by_selected_day_details',
            $viewParamsArray)->render();

        return response()->json([
            'error_code'             => 0,
            'message'                => '',
            'html'                   => $html,
            'correct_count'          => $correct_count,
            'not_correct_count'      => $not_correct_count,
            'selected_day_formatted' => $this->getCFFormattedDate(Carbon::createFromFormat('Y-m-d', $filter_voted_at_from))
        ],
            HTTP_RESPONSE_OK);
    } // public function votes_by_vote_names_retrieve_by_selected_day()

    // VOTES BY DAYS BLOCK END


    // VOTES BY VOTE NAMES BLOCK START
    public function votes_by_vote_names()
    {
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'], []);
        $voteCategoriesSelectionArray = VoteCategory::getVoteCategoriesSelectionArray();
        $votesSelectionArray          = Vote::getVotesSelectionArray();
        $usersSelectionArray          = User::getUsersSelectionArray();

        $viewParamsArray['voteCategoriesSelectionArray'] = $voteCategoriesSelectionArray;
        $viewParamsArray['votesSelectionArray']          = $votesSelectionArray;
        $viewParamsArray['usersSelectionArray']          = $usersSelectionArray;

        $appParamsForJSArray['reports_demo_period_start'] = config('app.reports_demo_period_start');
        $appParamsForJSArray['reports_demo_period_end']   = config('app.reports_demo_period_end');
        $appParamsForJSArray['chartBackgroundColors']     = config('app.chartBackgroundColors');
        $viewParamsArray['appParamsForJSArray']           = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.reports.votes_by_vote_names',
            $viewParamsArray);   //resources/views/defaultBS41Backend/admin/reports/votes_by_vote_names.blade.php
    } // public function votes_by_vote_names()


    public function votes_by_vote_names_retrieve(Request $request)
    {
        $filter_voted_at_from = $request->input('filter_voted_at_from');
        $filter_voted_at_till = $request->input('filter_voted_at_till');

        if ( ! empty($filter_voted_at_till)) {
            $filter_voted_at_till = $filter_voted_at_till . ' 23:59:59';
        }

        $filterSelectedUsers          = $this->clearEmptyArrayItems($request->input('selectedUsers'));
        $filterSelectedVoteCategories = $this->clearEmptyArrayItems($request->input('selectedVoteCategories'));
        $filterSelectedVotes          = $this->clearEmptyArrayItems($request->input('selectedVotes'));
//        $filterSelectedVotes= [2,3,4,5];

        $voteItemUCodesList              = [];
        $voteItemUsersResultsCorrect     = [];
        $voteItemUsersResultsNoneCorrect = [];

        $tempVoteItemUsersResultsCorrect = VoteItemUsersResult// Grouped by vote name
        ::getByIsCorrect(true)
        ->getByCreatedAt($filter_voted_at_from, ' > ')
        ->getByCreatedAt($filter_voted_at_till, ' <= ')
        ->getByUserId($filterSelectedUsers)
        ->getByVote($filterSelectedVotes)
        ->getByVoteCategories($filterSelectedVoteCategories)
        ->getByVoteIsQuiz(true)
        ->getByVoteStatus('A')
        ->select(
            \DB::raw('count(' . DB::getTablePrefix() . $this->vote_item_users_results_tb . '.id) as votes_count'),
            $this->votes_tb . '.id',
            $this->votes_tb . '.name as vote_name'
        )
        ->orderBy('vote_name', 'asc')
        ->groupBy($this->votes_tb . '.id')
        ->groupBy('vote_name')
        ->join($this->vote_items_tb, $this->vote_items_tb . '.id', '=', $this->vote_item_users_results_tb . '.vote_item_id')
        ->join($this->votes_tb, $this->votes_tb . '.id', '=', $this->vote_items_tb . '.vote_id')
        ->get()->toArray();

        $tempVoteItemUsersResultsNoneCorrect = VoteItemUsersResult//Grouped by vote name
        ::getByIsCorrect('0')
        ->getByCreatedAt($filter_voted_at_from, ' > ')
        ->getByCreatedAt($filter_voted_at_till, ' <= ')
        ->getByUserId($filterSelectedUsers)
        ->getByVote($filterSelectedVotes)
        ->getByVoteCategories($filterSelectedVoteCategories)
        ->getByVoteIsQuiz(true)
        ->getByVoteStatus('A')
        ->select(
            \DB::raw('count(' . DB::getTablePrefix() . $this->vote_item_users_results_tb . '.id) as votes_count'),
            $this->votes_tb . '.id',
            $this->votes_tb . '.name as vote_name'
        )
        ->orderBy('vote_name', 'asc')
        ->groupBy($this->votes_tb . '.id')
        ->groupBy('vote_name')
        ->join($this->vote_items_tb, $this->vote_items_tb . '.id', '=', $this->vote_item_users_results_tb . '.vote_item_id')
        ->join($this->votes_tb, $this->votes_tb . '.id', '=', $this->vote_items_tb . '.vote_id')
        ->get()->toArray();

        $skippedResultNoneCorrect = [];
        $skippedResultCorrect     = [];
        foreach ($tempVoteItemUsersResultsCorrect as $next_key => $nextTempVoteItemUsersResultCorrect) {
            $voteItemUCodesList[] = ['id' => $nextTempVoteItemUsersResultCorrect['id'], 'vote_name' => $nextTempVoteItemUsersResultCorrect['vote_name']];
            $is_found             = false;
            foreach ($tempVoteItemUsersResultsNoneCorrect as $nexTempVoteItemUsersResultsNoneCorrect) {
                if ($nextTempVoteItemUsersResultCorrect['id'] == $nexTempVoteItemUsersResultsNoneCorrect['id']) {
                    $is_found = true;
                    break;
                }
            }
            if ( ! $is_found) {
                $skippedResultNoneCorrect[] = [
                    'votes_count' => 0,
                    'id'          => $nextTempVoteItemUsersResultCorrect['id'],
                    'vote_name'   => $nextTempVoteItemUsersResultCorrect['vote_name']
                ];
            }
            $voteItemUsersResultsCorrect[ /*count($nextTempVoteItemUsersResultCorrect)*/] = $nextTempVoteItemUsersResultCorrect;
        }


        foreach ($tempVoteItemUsersResultsNoneCorrect as $next_key => $nextTempVoteItemUsersResultNoneCorrect) {
            $voteItemUCodesList[] = ['id' => $nextTempVoteItemUsersResultNoneCorrect['id'], 'vote_name' => $nextTempVoteItemUsersResultNoneCorrect['vote_name']];
            $is_found             = false;

            foreach ($tempVoteItemUsersResultsCorrect as $nexTempVoteItemUsersResultsCorrect) {
                if ($nextTempVoteItemUsersResultNoneCorrect['id'] == $nexTempVoteItemUsersResultsCorrect['id']) {
                    $is_found = true;
                    break;
                }
            }

            if ( ! $is_found) {
                $skippedResultCorrect[] = [
                    'votes_count' => 0,
                    'id'          => $nextTempVoteItemUsersResultNoneCorrect['id'],
                    'vote_name'   => $nextTempVoteItemUsersResultNoneCorrect['vote_name']
                ];
            }
            $voteItemUsersResultsNoneCorrect[ /*count($voteItemUsersResultsNoneCorrect)*/] = $nextTempVoteItemUsersResultNoneCorrect;
        }

        $voteItemUsersResultsNoneCorrect = array_merge($voteItemUsersResultsNoneCorrect, $skippedResultNoneCorrect);
        $voteItemUsersResultsCorrect     = array_merge($voteItemUsersResultsCorrect, $skippedResultCorrect);

        uasort($voteItemUsersResultsCorrect, array($this, 'cmp'));
        uasort($voteItemUsersResultsNoneCorrect, array($this, 'cmp'));
        $voteNamesXCoordItems  = [];
        $voteNamelabels        = [];
        $voteValuesCorrect     = [];
        $voteValuesNoneCorrect = [];

        foreach ($voteItemUsersResultsCorrect as $nextVoteItemUsersResultsCorrect) {
            $voteNamesXCoordItems[] = $nextVoteItemUsersResultsCorrect['vote_name'];
            $voteNamelabels[]       = $nextVoteItemUsersResultsCorrect['vote_name'];
            $voteValuesCorrect[]    = $nextVoteItemUsersResultsCorrect['votes_count'];


        }

        foreach ($voteItemUsersResultsNoneCorrect as $nextVoteItemUsersResultsNoneCorrect) {
            $voteNamelabels[]        = $nextVoteItemUsersResultsNoneCorrect['vote_name'];
            $voteValuesNoneCorrect[] = $nextVoteItemUsersResultsNoneCorrect['votes_count'];
        }

        return response()->json([
            'error_code'            => 0,
            'message'               => '',
            'voteNamesXCoordItems'  => $voteNamesXCoordItems,
            'voteNamelabels'        => $voteNamelabels,
            'voteValuesCorrect'     => $voteValuesCorrect,
            'voteValuesNoneCorrect' => $voteValuesNoneCorrect,
            'voteItemUCodesList'    => $voteItemUCodesList,
            //                         backendReports.showVoteNamesReportDetailsByVoteId(data.id, data.vote_name)

        ], HTTP_RESPONSE_OK);
    } // public function votes_by_vote_names_retrieve()

    public function cmp($a, $b)
    {
        if (strtolower($a['vote_name']) == strtolower($b['vote_name'])) {
            return 0;
        }

        return (strtolower($a['vote_name']) < strtolower($b['vote_name'])) ? -1 : 1;
    }


    public function votes_by_vote_names_retrieve_by_vote_id(Request $request)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        $filter_voted_at_from = $request->input('filter_voted_at_from');
        $filter_voted_at_till = $request->input('filter_voted_at_till');
        if ( ! empty($filter_voted_at_till)) {
            $filter_voted_at_till = $filter_voted_at_till . ' 23:59:59';
        }
        $filterSelectedUsers          = $this->clearEmptyArrayItems($request->input('selectedUsers'));
        $filterSelectedVoteCategories = $this->clearEmptyArrayItems($request->input('selectedVoteCategories'));
        $filter_vote_id               = $request->input('filter_vote_id');

        $detailedVoteItemUsersResults = VoteItemUsersResult// Grouped by vote name
        ::getByCreatedAt($filter_voted_at_from, ' > ')
        ->getByCreatedAt($filter_voted_at_till, ' <= ')
        ->getByUserId($filterSelectedUsers)
        ->getByVote($filter_vote_id)
        ->getByVoteCategories($filterSelectedVoteCategories)
        ->getByVoteIsQuiz(true)
        ->getByVoteStatus('A')
        ->select(
            $this->vote_item_users_results_tb . '.is_correct',
            $this->vote_item_users_results_tb . '.created_at as voted_at',
            $this->users_tb . '.id as user_id',
            $this->users_tb . '.first_name',
            $this->users_tb . '.last_name',
            $this->users_tb . '.username',
            $this->votes_tb . '.id as vote_id'
        )
        ->orderBy('username', 'desc')
        ->orderBy('voted_at', 'asc')
        ->join($this->vote_items_tb, $this->vote_items_tb . '.id', '=', $this->vote_item_users_results_tb . '.vote_item_id')
        ->join($this->users_tb, $this->users_tb . '.id', '=', $this->vote_item_users_results_tb . '.user_id')
        ->join($this->votes_tb, $this->votes_tb . '.id', '=', $this->vote_items_tb . '.vote_id')
        ->get();

        $correct_count     = 0;
        $not_correct_count = 0;
        foreach ($detailedVoteItemUsersResults as $nextDetailedVoteItemUsersResult) {
            $correct_count     += $nextDetailedVoteItemUsersResult->is_correct ? 1 : 0;
            $not_correct_count += ! $nextDetailedVoteItemUsersResult->is_correct ? 1 : 0;
        }

        $viewParamsArray['detailedVoteItemUsersResults'] = $detailedVoteItemUsersResults;
        $viewParamsArray['correct_count']                = $correct_count;
        $viewParamsArray['not_correct_count']            = $not_correct_count;
        $html                                            = view($this->getBackendTemplateName() . '.admin.reports.votes_by_vote_names_details', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function votes_by_vote_names_retrieve_by_vote_id()

    // VOTES BY VOTE NAMES BLOCK END

    // QUIZZES RATING BLOCK START
    public function quizzes_rating()
    {
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'], []);
        $voteCategoriesSelectionArray = VoteCategory::getVoteCategoriesSelectionArray();
        $votesSelectionArray          = Vote::getVotesSelectionArray();
        $usersSelectionArray          = User::getUsersSelectionArray();

        $viewParamsArray['voteCategoriesSelectionArray'] = $voteCategoriesSelectionArray;
        $viewParamsArray['votesSelectionArray']          = $votesSelectionArray;
        $viewParamsArray['usersSelectionArray']          = $usersSelectionArray;

        $appParamsForJSArray['reports_demo_period_start'] = config('app.reports_demo_period_start');
        $appParamsForJSArray['reports_demo_period_end']   = config('app.reports_demo_period_end');
        $appParamsForJSArray['chartBackgroundColors']     = config('app.chartBackgroundColors');
        $viewParamsArray['appParamsForJSArray']           = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.reports.quizzes_rating', $viewParamsArray);
    } // public function quizzes_rating()

    public function quizzes_rating_retrieve(Request $request)
    {
        $filter_voted_at_from = $request->input('filter_voted_at_from');
        $filter_voted_at_till = $request->input('filter_voted_at_till');
        if ( ! empty($filter_voted_at_till)) {
            $filter_voted_at_till = $filter_voted_at_till . ' 23:59:59';
        }
        $filterSelectedUsers          = $this->clearEmptyArrayItems($request->input('selectedUsers', []));
        $filterSelectedVotes          = $this->clearEmptyArrayItems($request->input('selectedVotes', []));
        $filterSelectedVoteCategories = $this->clearEmptyArrayItems($request->input('selectedVoteCategories', []));

        $quizQualityOptions               = with(new QuizQualityResult)->getQuizQualityOptions();
        $quizQualityResultsRatingData     = [];
        $quizQualityResultsRatingDataTemp = QuizQualityResult
            ::select(
                $this->votes_tb . '.name as vote_name',
                $this->votes_tb . '.slug as vote_slug',
                $this->quiz_quality_results_tb . '.vote_id',
                \DB::raw('count(' . DB::getTablePrefix() . $this->quiz_quality_results_tb . '.quiz_quality_id) as quiz_quality_count'),
                \DB::raw('avg(' . DB::getTablePrefix() . $this->quiz_quality_results_tb . '.quiz_quality_id) as quiz_quality_avg')
            )
            ->getByVoteStatus('A')
            ->getByCreatedAt($filter_voted_at_from, ' > ')
            ->getByCreatedAt($filter_voted_at_till, '<= ')
            ->getByUserId($filterSelectedUsers)
            ->getByVoteId($filterSelectedVotes)
            ->getByVoteCategories($filterSelectedVoteCategories)
            ->orderBy('quiz_quality_avg', 'desc')
            ->groupBy('vote_id')
            ->groupBy('vote_slug')
            ->groupBy('vote_name')
            ->join($this->votes_tb, $this->votes_tb . '.id', '=', $this->quiz_quality_results_tb . '.vote_id')
            ->get();

        foreach ($quizQualityResultsRatingDataTemp as $next_key => $quizQualityResultsRatingDataTemp) {
            $cealing_quiz_quality_id        = ceil($quizQualityResultsRatingDataTemp->quiz_quality_avg);
            $quizQualityResultsRatingData[] = [
                'vote_name'         => $quizQualityResultsRatingDataTemp->vote_name,
                'vote_slug'         => $quizQualityResultsRatingDataTemp->vote_slug,
                'quiz_quality_avg'  => round($quizQualityResultsRatingDataTemp->quiz_quality_avg, 1),
                'quiz_quality_name' => ! empty($quizQualityOptions[$cealing_quiz_quality_id]) ?
                    $quizQualityOptions[$cealing_quiz_quality_id] : '',
            ];
        }
        $viewParamsArray['mostRatingQuizQualityResultsData'] = $quizQualityResultsRatingData;

        return response()->json(['error_code' => 0, 'message' => '', 'quizQualityResultsRatingData' => $quizQualityResultsRatingData], HTTP_RESPONSE_OK);
    } // public function quizzes_rating_retrieve()

    // QUIZZES RATING BLOCK END


    // COMPARE CORRECT VOTES BLOCK START
    public function compare_correct_votes()
    {
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'], []);
        $voteCategoriesSelectionArray = VoteCategory::getVoteCategoriesSelectionArray();
        $votesSelectionArray          = Vote::getVotesSelectionArray();
        $usersSelectionArray          = User::getUsersSelectionArray();

        $viewParamsArray['voteCategoriesSelectionArray'] = $voteCategoriesSelectionArray;
        $viewParamsArray['votesSelectionArray']          = $votesSelectionArray;
        $viewParamsArray['usersSelectionArray']          = $usersSelectionArray;

        $appParamsForJSArray['reports_demo_period_start'] = config('app.reports_demo_period_start');
        $appParamsForJSArray['reports_demo_period_end']   = config('app.reports_demo_period_end');
        $appParamsForJSArray['chartBackgroundColors']     = config('app.chartBackgroundColors');
        $viewParamsArray['appParamsForJSArray']           = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.reports.compare_correct_votes', $viewParamsArray);
    } // public function compare_correct_votes()


    public function compare_correct_votes_retrieve(Request $request)
    {
        $filter_voted_at_from = $request->input('filter_voted_at_from');
        $filter_voted_at_till = $request->input('filter_voted_at_till');
        if ( ! empty($filter_voted_at_till)) {
            $filter_voted_at_till = $filter_voted_at_till . ' 23:59:59';
        }
        $filterSelectedUsers          = $this->clearEmptyArrayItems($request->input('selectedUsers', []));
        $filterSelectedVotes          = $this->clearEmptyArrayItems($request->input('selectedVotes', []));
        $filterSelectedVoteCategories = $this->clearEmptyArrayItems($request->input('selectedVoteCategories', []));

        $voteItemUsersResultCorrectPercents = [];

        $tempVoteItemUsersResultsCorrect = VoteItemUsersResult
            ::getByIsCorrect(true)
            ->getByCreatedAt($filter_voted_at_from, ' > ')
            ->getByCreatedAt($filter_voted_at_till, ' <= ')
            ->getByUserId($filterSelectedUsers)
            ->getByVote($filterSelectedVotes)
            ->getByVoteCategories($filterSelectedVoteCategories)
            ->getByVoteIsQuiz(true)
            ->getByVoteStatus('A')
            ->select(
                $this->votes_tb . '.id as vote_id',
                $this->votes_tb . '.name as vote_name',
                DB::raw('COUNT(*) AS correct_votes_count')
            )
            ->orderBy('vote_name', 'asc')
            ->groupBy($this->votes_tb . '.id')
            ->groupBy('vote_name')
            ->join($this->vote_items_tb, $this->vote_items_tb . '.id', '=', $this->vote_item_users_results_tb . '.vote_item_id')
            ->join($this->votes_tb, $this->votes_tb . '.id', '=', $this->vote_items_tb . '.vote_id')
            ->get();

        $correct_votes_sum = 0;
        foreach ($tempVoteItemUsersResultsCorrect as $next_key => $nextTempRow) {
            $correct_votes_sum += $nextTempRow->correct_votes_count;
        }

        foreach ($tempVoteItemUsersResultsCorrect as $next_key => $nextTempRow) {
            $voteItemUsersResultCorrectPercents[] = [
                'vote_id'             => $nextTempRow->vote_id,
                'vote_name'           => $nextTempRow->vote_name,
                'correct_votes_count' => $nextTempRow->correct_votes_count,
                'percent'             => round($nextTempRow->correct_votes_count / $correct_votes_sum * 100, 1)
            ];
        }

        $viewParamsArray['voteItemUsersResultCorrectPercents'] = $voteItemUsersResultCorrectPercents;

        return response()->json(['error_code' => 0, 'message' => '', 'voteItemUsersResultCorrectPercents' => $voteItemUsersResultCorrectPercents], HTTP_RESPONSE_OK);
    } // public function compare_correct_votes_retrieve()

    // COMPARE CORRECT VOTES BLOCK END


    // SEARCH RESULTS BLOCK START
    public function search_results()
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'], []);
//        $voteCategoriesSelectionArray = VoteCategory::getVoteCategoriesSelectionArray();
//        $votesSelectionArray          = Vote::getVotesSelectionArray();
        $usersSelectionArray = User::getUsersSelectionArray();
//
//        $viewParamsArray['voteCategoriesSelectionArray'] = $voteCategoriesSelectionArray;
//        $viewParamsArray['votesSelectionArray']          = $votesSelectionArray;
        $viewParamsArray['usersSelectionArray'] = $usersSelectionArray;

        $appParamsForJSArray['reports_demo_period_start'] = config('app.reports_demo_period_start');
        $appParamsForJSArray['reports_demo_period_end']   = config('app.reports_demo_period_end');
        $appParamsForJSArray['chartBackgroundColors']     = config('app.chartBackgroundColors');
        $viewParamsArray['appParamsForJSArray']           = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.reports.search_results', $viewParamsArray);
    } // public function search_results()


    public function search_results_retrieve(Request $request)
    {
        $filter_voted_at_from = $request->input('filter_voted_at_from');
        $filter_voted_at_till = $request->input('filter_voted_at_till');
        if ( ! empty($filter_voted_at_till)) {
            $filter_voted_at_till = $filter_voted_at_till . ' 23:59:59';
        }
        $filterSelectedUsers = $this->clearEmptyArrayItems($request->input('selectedUsers', []));


        $searchResultFound    = [];
        $searchResultNotFound = [];

        $tempSearchResultFound                = SearchResult
            ::getByFoundResults(true)
            ->getByCreatedAt($filter_voted_at_from, ' > ')
            ->getByCreatedAt($filter_voted_at_till, ' <= ')
            ->getByUserId($filterSelectedUsers)
//            ->whereRaw(with(new SearchResult)->getTable() . '.id <= 40')
            ->select(
                $this->search_results_tb . '.text',
                DB::raw('COUNT(*) AS found_search_results_count')
            )
            ->orderBy('text', 'asc')
            ->groupBy('text')
            ->get()->toArray();
        $viewParamsArray['searchResultFound'] = $searchResultFound;

        $tempSearchResultNotFound = SearchResult
            ::getByFoundResults(false)
            ->getByCreatedAt($filter_voted_at_from, ' > ')
            ->getByCreatedAt($filter_voted_at_till, ' <= ')
            ->getByUserId($filterSelectedUsers)
//            ->whereRaw(with(new SearchResult)->getTable() . '.id <= 40')
            ->select(
                $this->search_results_tb . '.text',
                DB::raw('COUNT(*) AS found_search_results_count')
            )
            ->orderBy('text', 'asc')
            ->groupBy('text')
            ->get()->toArray();


        $skippedSearchResultNotFound = [];
        $skippedSearchResultFound    = [];
        foreach ($tempSearchResultFound as $next_key => $nextTempSearchResultFound) {
            $is_found = false;
            foreach ($tempSearchResultNotFound as $nextTempSearchResultNotFound) {
                if ($nextTempSearchResultFound['text'] == $nextTempSearchResultNotFound['text']) {
                    $is_found = true;
                    break;
                }
            }
            if ( ! $is_found) {
                $skippedSearchResultNotFound[] = ['found_search_results_count' => 0, 'text' => $nextTempSearchResultFound['text']];
            }
            $searchResultFound[ /*count($nextTempSearchResultFound)*/] = $nextTempSearchResultFound;
        }

        foreach ($tempSearchResultNotFound as $next_key => $nextTempSearchResultNotFound) {
            $is_found = false;

            foreach ($tempSearchResultFound as $nexTempSearchResultFound) {
                if ($nextTempSearchResultNotFound['text'] == $nexTempSearchResultFound['text']) {
                    $is_found = true;
                    break;
                }
            }

            if ( ! $is_found) {
                $skippedSearchResultFound[] = ['found_search_results_count' => 0, 'text' => $nextTempSearchResultNotFound['text']];
            }
            $searchResultNotFound[ /*count($searchResultNotFound)*/] = $nextTempSearchResultNotFound;
        }

        $searchResultNotFound = array_merge($searchResultNotFound, $skippedSearchResultNotFound);
        $searchResultFound    = array_merge($searchResultFound, $skippedSearchResultFound);

        //        $result = array_merge($array1, $array2);


        uasort($searchResultFound, array($this, 'cmpSearchResultFoundData'));
        uasort($searchResultNotFound, array($this, 'cmpSearchResultFoundData'));

//        echo '<pre>+++$searchResultFound::'.print_r($searchResultFound,true).'</pre>';
//        echo '<pre>+++$searchResultNotFound::'.print_r($searchResultNotFound,true).'</pre>';
//        die("-1 XXZ========");
//

        $searchTextXCoordItems     = [];
        $searchResultslabels       = [];
        $searchResultFoundArray    = [];
        $searchResultNotFoundArray = [];

        foreach ($searchResultFound as $nextSearchResultFound) {
            $searchTextXCoordItems[]  = $nextSearchResultFound['text'];
            $searchResultslabels[]    = $nextSearchResultFound['text'];
            $searchResultFoundArray[] = $nextSearchResultFound['found_search_results_count'];
        }

        foreach ($searchResultNotFound as $nextSearchResultNotFound) {
            $searchResultslabels[]       = $nextSearchResultNotFound['text'];
            $searchResultNotFoundArray[] = $nextSearchResultNotFound['found_search_results_count'];
        }

        $viewParamsArray['searchResultFound']    = $searchResultFound;
        $viewParamsArray['searchResultNotFound'] = $searchResultNotFound;

        return response()->json([
            'error_code'                => 0,
            'message'                   => '',
            'searchTextXCoordItems'     => $searchTextXCoordItems,
            'searchResultslabels'       => $searchResultslabels,
            'searchResultFoundArray'    => $searchResultFoundArray,
            'searchResultNotFoundArray' => $searchResultNotFoundArray,
//            'searchResultFound' => $searchResultFound, 'searchResultNotFound' => $searchResultNotFound
        ], HTTP_RESPONSE_OK);
//        return response()->json(['error_code' => 0, 'message' => '', 'searchResultFound' => $searchResultFound, 'searchResultNotFound' => $searchResultNotFound], HTTP_RESPONSE_OK);
    } // public function search_results_retrieve()

    public function cmpSearchResultFoundData($a, $b)
    {
        if (strtolower($a['text']) == strtolower($b['text'])) {
            return 0;
        }

        return (strtolower($a['text']) < strtolower($b['text'])) ? -1 : 1;
    }



    // SEARCH RESULTS BLOCK END


    // RESULTS EXPORT BLOCK START
    public function report_save_to_excel(Request $request)
    {
        /*         "report_name": report_name,
        "voteItemUsersResultsCorrect": this.this_voteItemUsersResultsCorrect,
        "voteItemUsersResultsNoneCorrect": this.this_voteItemUsersResultsNoneCorrect
 */
        $report_name           = $request->input('report_name');
        $monthsXCoordItems     = $request->input('monthsXCoordItems');
        $voteValuesCorrect     = $request->input('voteValuesCorrect');
        $voteValuesNoneCorrect = $request->input('voteValuesNoneCorrect');
        $notes                 = $request->input('notes');


        $report_full_name = $report_name . '.xlsx';
        $this->debToFile(print_r($report_full_name, true), ' ReportsExport  $report_full_name ::');


        /* report_name: votes_by_days
monthsXCoordItems: 2018-12-25,2018-12-26,2018-12-27,2018-12-28,2018-12-29,2018-12-30,2018-12-31,2019-01-01,2019-01-02,2019-01-03,2019-01-04,2019-01-05,2019-01-06,2019-01-07,2019-01-08,2019-01-09,2019-01-10,2019-01-11,2019-01-12,2019-01-13,2019-01-14,2019-01-15,2019-01-16,2019-01-17,2019-01-18,2019-01-19,2019-01-20,2019-01-21,2019-01-22,2019-01-23
voteValuesCorrect: 6,5,0,5,7,1,1,2,3,5,3,6,5,3,5,4,3,1,1,5,4,2,6,3,5,1,2,3,3,0
voteValuesNoneCorrect: 9,5,9,12,7,6,9,8,8,7,10,13,9,10,12,7,9,9,9,8,7,7,10,11,12,11,6,9,12,10
notes: 1234 */

        return Excel::download(new ReportsExport ($monthsXCoordItems, $voteValuesCorrect, $voteValuesNoneCorrect, $notes), $report_full_name);
        // app/Exports/ReportsExport.php
    } // public function report_save_to_excel(Request $request)
    // RESULTS EXPORT BLOCK END


    // PAYMENTS BLOCK BEGIN
    public function report_payments()
    {
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'], []);
        $voteCategoriesSelectionArray = VoteCategory::getVoteCategoriesSelectionArray();
        $votesSelectionArray          = Vote::getVotesSelectionArray();
        $usersSelectionArray          = User::getUsersSelectionArray();
//        echo '<pre>$usersSelectionArray::'.print_r($usersSelectionArray,true).'</pre>';

        $reportTypesArray = [
            'items_by_days' => 'Payed items grouped by days',
            'items_count'   => 'Payed items grouped by items count',
        ];
//        echo '<pre>$reportTypesArray::' . print_r($reportTypesArray, true) . '</pre>';

//        die("-1 XXZ===");

        $viewParamsArray['voteCategoriesSelectionArray'] = $voteCategoriesSelectionArray;
        $viewParamsArray['votesSelectionArray']          = $votesSelectionArray;
        $viewParamsArray['usersSelectionArray']          = $usersSelectionArray;
        $viewParamsArray['reportTypesArray']             = $reportTypesArray;

        $appParamsForJSArray['reports_demo_period_start'] = config('app.reports_demo_period_start');
        $appParamsForJSArray['reports_demo_period_end']   = config('app.reports_demo_period_end');
        $appParamsForJSArray['chartBackgroundColors']     = config('app.chartBackgroundColors');

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.reports.report_payments', $viewParamsArray);
    } // public function report_payments()


    public function report_payments_retrieve(Request $request)
    {
        $this->debToFile(print_r($request->all(), true), ' report_payments_retrieve  $request->all() ::');
        $filter_voted_at_from = $request->input('filter_voted_at_from', '2019-07-20');
        $filter_voted_at_till = $request->input('filter_voted_at_till', '2019-09-20');
        if ( ! empty($filter_voted_at_till)) {
            $filter_voted_at_till = $filter_voted_at_till . ' 23:59:59';
        }

        $filterSelectedUsers = $this->clearEmptyArrayItems($request->input('selectedUsers', []));
        $filter_report_type  = $request->input('filter_report_type', 'items_by_days');

        if (empty($filter_report_type) or $filter_report_type == 'items_by_days') {
            $paymentItems = PaymentItem
                ::getByStatus('C', 'payments')
                ->getByCreatedAt($filter_voted_at_from, ' > ')
                ->getByCreatedAt($filter_voted_at_till, '<= ')
                ->getByUserId($filterSelectedUsers, 'payments')
//                ->select(\DB::raw(' ' . DB::getTablePrefix() . $this->downloads_tb . '.id as payment_item_id, ' . DB::getTablePrefix() . $this->downloads_tb . '.title as item_title'),
//                    \DB::raw('count(' . DB::getTablePrefix() . $this->payment_items_tb . '.quantity) as quantity_count'))
                ->select(\DB::raw(' DATE_FORMAT(' . DB::getTablePrefix() . $this->payments_tb . '.created_at, "%Y-%m-%d") as formatted_created_at'),
                    \DB::raw('count(' . DB::getTablePrefix() . $this->payment_items_tb . '.id) as quantity_count'))
                ->orderBy('formatted_created_at', 'asc')
                ->groupBy('formatted_created_at')
                ->join($this->payments_tb, $this->payments_tb . '.id', '=', $this->payment_items_tb . '.payment_id')
                ->join($this->downloads_tb, $this->downloads_tb . '.id', '=', $this->payment_items_tb . '.item_id')
                ->get();
            $html         = view($this->getBackendTemplateName() . '.admin.reports.report_payments_downloaded_by_days', [
                'paymentItems'         => $paymentItems,
                'filter_voted_at_from' => $filter_voted_at_from,
                'filter_voted_at_till' => $filter_voted_at_till,
                'filterSelectedUsers'  => $this->concatArray($filterSelectedUsers),
                'filter_report_type'   => $filter_report_type
            ])->render();
        } // if ( empty($filter_report_type) or $filter_report_type == 'items_by_days' ) {

        if (empty($filter_report_type) or $filter_report_type == 'items_count') {
            $paymentItems = PaymentItem
                ::getByStatus('C', 'payments')
                ->getByCreatedAt($filter_voted_at_from, ' > ')
                ->getByCreatedAt($filter_voted_at_till, '<= ')
                ->getByUserId($filterSelectedUsers, 'payments')
                ->select(\DB::raw(' ' . DB::getTablePrefix() . $this->downloads_tb . '.id as payment_item_id, ' . DB::getTablePrefix() . $this->downloads_tb . '.title as item_title'),
                    \DB::raw('count(' . DB::getTablePrefix() . $this->payment_items_tb . '.quantity) as quantity_count'))
//            ->orderBy('item_id', 'asc')
                ->groupBy('payment_item_id')
                ->join($this->payments_tb, $this->payments_tb . '.id', '=', $this->payment_items_tb . '.payment_id')
                ->join($this->downloads_tb, $this->downloads_tb . '.id', '=', $this->payment_items_tb . '.item_id')

                ->get();

            $html = view($this->getBackendTemplateName() . '.admin.reports.report_payments_downloaded_by_quantity_count', [
                'paymentItems'         => $paymentItems,
                'filter_voted_at_from' => $filter_voted_at_from,
                'filter_voted_at_till' => $filter_voted_at_till,
                'filterSelectedUsers'  => $this->concatArray($filterSelectedUsers),
                'filter_report_type'   => $filter_report_type
            ])->render();
        } // if ( empty($filter_report_type) or $filter_report_type == 'items_count' ) {

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function report_payments_retrieve()


    public function report_open_items_count_details(Request $request)
    {
        $this->debToFile(print_r($request->all(), true), ' report_open_items_count_details  $request->all() ::');
        $filter_downloaded_item_id = $request->input('downloaded_item_id', 1);

        $filter_voted_at_from = $request->input('filter_voted_at_from');
        $filter_voted_at_till = $request->input('filter_voted_at_till');
        if ( ! empty($filter_voted_at_till)) {
            $filter_voted_at_till = $filter_voted_at_till . ' 23:59:59';
        }

        //     public function scopeGetByItemId($query, $item_id = null, $alias= '')
        $filterSelectedUsers = $this->clearEmptyArrayItems($request->input('selectedUsers', [2]));
        $paymentItems        = PaymentItem
            ::getByStatus('C', 'payments')
            ->getByItemId($filter_downloaded_item_id)
            ->getByCreatedAt($filter_voted_at_from, ' > ')
            ->getByCreatedAt($filter_voted_at_till, '<= ')
            ->getByUserId($filterSelectedUsers, 'payments')
            ->select(
                $this->payment_items_tb . '.*',
                $this->downloads_tb . '.title as payed_item_title',
                $this->users_tb . '.username as payment_username',
                $this->payments_tb . '.payment_type',
                $this->payments_tb . '.invoice_number',
                $this->payments_tb . '.payment_description',
                $this->payments_tb . '.payer_email',
                $this->payments_tb . '.payer_first_name',
                $this->payments_tb . '.payer_last_name',
                $this->payments_tb . '.shipping',
                $this->payments_tb . '.tax',
                $this->payments_tb . '.payer_shipping_address'
            )
            ->orderBy($this->payment_items_tb . '.created_at', 'desc')
//            ->groupBy('formatted_created_at')

            ->join($this->payments_tb, $this->payments_tb . '.id', '=', $this->payment_items_tb . '.payment_id')
            ->join($this->users_tb, $this->users_tb . '.id', '=', $this->payments_tb . '.user_id')
            ->join($this->downloads_tb, $this->downloads_tb . '.id', '=', $this->payment_items_tb . '.item_id')

            ->get()
            ->map(function ($item) {

                return [
                    'id'                   => $item->id,
                    'payment_username'     => $item->payment_username,
                    'price'                => $item->price,
                    'quantity'             => $item->quantity,
                    'payed_item_title'     => $item->payed_item_title,
                    'created_at'           => $item->created_at,
                    'total'                => $item->price * $item->quantity,

                    'payment_type_label'   => Payment::getPaymentTypeLabel($item->payment_type),
                    'invoice_number'       => $item->invoice_number,
                    'payment_description'  => $item->payment_description,
                    'payer_email'          => $item->payer_email,
                    'payer_first_name'     => $item->payer_first_name,
                    'payer_last_name'      => $item->payer_last_name,
                    'shipping'             => $item->shipping,
                    'tax'                  => $item->tax,
                    'payer_shipping_address' => $item->payer_shipping_address,
                ];
                /* CREATE TABLE `vt2_payments` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) UNSIGNED NULL DEFAULT NULL,
	`status` ENUM('D','C','A') NOT NULL DEFAULT 'D' COMMENT ' D => Draft, C=>Completed, A=>Cancelled' COLLATE 'utf8mb4_unicode_ci',
	`payment_type` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payment_status` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payment_description` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`invoice_number` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_id` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_first_name` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_last_name` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_middle_name` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`currency` VARCHAR(5) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`total` DECIMAL(9,2) NOT NULL,
	`subtotal` DECIMAL(9,2) NOT NULL,
	`tax` DECIMAL(9,2) NOT NULL,
	`shipping` DECIMAL(9,2) NOT NULL,
	`payer_shipping_address` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_recipient_name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_city` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_state` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_postal_code` VARCHAR(10) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_country_code` VARCHAR(2) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_business_name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `payments_user_id_foreign` (`user_id`),
	INDEX `payments_created_at_payment_type_index` (`created_at`, `payment_type`),
	INDEX `payments_payment_type_user_status_id_index` (`payment_type`, `user_id`, `status`),
	CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vt2_users` (`id`) ON DELETE CASCADE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=23
;
 */
            });

        $html = view($this->getBackendTemplateName() . '.admin.reports.report_payments_items_count_details', [
            'paymentItems' => $paymentItems,
        ])->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function report_open_items_count_details()


    public function report_payments_by_days_count_details(Request $request)
    {
        $this->debToFile(print_r($request->all(), true), ' report_payments_by_days_count_details  $request->all() ::');
        $filter_downloaded_item_id = $request->input('downloaded_item_id', 1);

        $filter_voted_at_from = $request->input('filter_voted_at_from');
        $filter_voted_at_till = $request->input('filter_voted_at_till');
        if ( ! empty($filter_voted_at_till)) {
            $filter_voted_at_till = $filter_voted_at_till . ' 23:59:59';
        }

        //     public function scopeGetByItemId($query, $item_id = null, $alias= '')
        $filterSelectedUsers = $this->clearEmptyArrayItems($request->input('selectedUsers', [2]));
        $paymentItems        = PaymentItem
            ::getByStatus('C', 'payments')
            ->getByItemId($filter_downloaded_item_id)
            ->getByCreatedAt($filter_voted_at_from, ' > ')
            ->getByCreatedAt($filter_voted_at_till, '<= ')
            ->getByUserId($filterSelectedUsers, 'payments')
            ->select(
                $this->payment_items_tb . '.*',
                $this->downloads_tb . '.title as payed_item_title',
                $this->users_tb . '.username as payment_username',
                $this->payments_tb . '.payment_type',
                $this->payments_tb . '.invoice_number',
                $this->payments_tb . '.payment_description',
                $this->payments_tb . '.payer_email',
                $this->payments_tb . '.payer_first_name',
                $this->payments_tb . '.payer_last_name',
                $this->payments_tb . '.shipping',
                $this->payments_tb . '.tax',
                $this->payments_tb . '.payer_shipping_address'
            )
            ->orderBy($this->payment_items_tb . '.created_at', 'desc')
//            ->groupBy('formatted_created_at')

            ->join($this->payments_tb, $this->payments_tb . '.id', '=', $this->payment_items_tb . '.payment_id')
            ->join($this->users_tb, $this->users_tb . '.id', '=', $this->payments_tb . '.user_id')
            ->join($this->downloads_tb, $this->downloads_tb . '.id', '=', $this->payment_items_tb . '.item_id')

            ->get()
            ->map(function ($item) {

                return [
                    'id'                   => $item->id,
                    'payment_username'     => $item->payment_username,
                    'price'                => $item->price,
                    'quantity'             => $item->quantity,
                    'payed_item_title'     => $item->payed_item_title,
                    'created_at'           => $item->created_at,
                    'total'                => $item->price * $item->quantity,

                    'payment_type_label'   => Payment::getPaymentTypeLabel($item->payment_type),
                    'invoice_number'       => $item->invoice_number,
                    'payment_description'  => $item->payment_description,
                    'payer_email'          => $item->payer_email,
                    'payer_first_name'     => $item->payer_first_name,
                    'payer_last_name'      => $item->payer_last_name,
                    'shipping'             => $item->shipping,
                    'tax'                  => $item->tax,
                    'payer_shipping_address' => $item->payer_shipping_address,
                ];
                /* CREATE TABLE `vt2_payments` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) UNSIGNED NULL DEFAULT NULL,
	`status` ENUM('D','C','A') NOT NULL DEFAULT 'D' COMMENT ' D => Draft, C=>Completed, A=>Cancelled' COLLATE 'utf8mb4_unicode_ci',
	`payment_type` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payment_status` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payment_description` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`invoice_number` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_id` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_first_name` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_last_name` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_middle_name` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`currency` VARCHAR(5) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`total` DECIMAL(9,2) NOT NULL,
	`subtotal` DECIMAL(9,2) NOT NULL,
	`tax` DECIMAL(9,2) NOT NULL,
	`shipping` DECIMAL(9,2) NOT NULL,
	`payer_shipping_address` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_recipient_name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_city` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_state` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_postal_code` VARCHAR(10) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_country_code` VARCHAR(2) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`payer_business_name` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `payments_user_id_foreign` (`user_id`),
	INDEX `payments_created_at_payment_type_index` (`created_at`, `payment_type`),
	INDEX `payments_payment_type_user_status_id_index` (`payment_type`, `user_id`, `status`),
	CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `vt2_users` (`id`) ON DELETE CASCADE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=23
;
 */
            });

        $html = view($this->getBackendTemplateName() . '.admin.reports.report_payments_items_count_details', [
            'paymentItems' => $paymentItems,
        ])->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function report_payments_by_days_count_details()

    // PAYMENTS BLOCK END

}
