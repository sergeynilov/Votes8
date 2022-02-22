<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;
use Auth;
use DB;
use App\Vote;
use App\VoteItem;

use App\ActivityLog;
use App\MyTag;
use App\TagDetail;
use App\Taggable as MyTaggable;
use App\VoteCategory;
use App\VoteItemUsersResult;
use App\QuizQualityResult;
use App\Http\Traits\funcsTrait;
use App\Policies\VoteItemUsersResultPolicy;
use App\library\CheckValueType;

class VotesController extends MyAppController
{
    use funcsTrait;
    private $votes_tb;
    private $vote_items_tb;
    private $vote_categories_tb;
    private $vote_item_users_results_tb;
    private $quiz_quality_results_tb;

    public function __construct()
    {
        $this->votes_tb= with(new Vote)->getTable();
        $this->vote_items_tb= with(new VoteItem)->getTable();
        $this->vote_categories_tb= with(new VoteCategory())->getTable();
        $this->vote_item_users_results_tb= with(new VoteItemUsersResult())->getTable();
        $this->quiz_quality_results_tb= with(new QuizQualityResult())->getTable();
    }

    public function votes_by_category($vote_category_slug) // votes by category page
    {
        $viewParamsArray                      = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name', 'site_heading', 'site_subheading']);
        $voteCategory                         = VoteCategory
            ::getBySlug($vote_category_slug)
            ->first();
        if ($voteCategory === null) {
            return View($this->getFrontendTemplateName() . '.msg', ['text' => 'Vote with slug # "' . $vote_category_slug . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }

        $home_page_ref_items_per_pagination = Settings
            ::getValue('home_page_ref_items_per_pagination', CheckValueType::cvtInteger, 20);
        $activeVoteCategories = Vote
            ::getByVoteCategory($voteCategory->id)
            ->getByStatus('A')
            ->orderBy('name', 'desc')
            ->paginate($home_page_ref_items_per_pagination)
            ->onEachSide((int)($home_page_ref_items_per_pagination / 2));
        foreach ($activeVoteCategories as $next_key => $nextVote) {
            $voteImageProps = Vote::setVoteImageProps($nextVote->id, $nextVote->image, false);
            if (count($voteImageProps) > 0) {
                $nextVote->setVoteImagePropsAttribute($voteImageProps);
            }
        }

        $viewParamsArray['activeVoteCategories']             = $activeVoteCategories;
        $viewParamsArray['voteCategory']                     = $voteCategory;
        $viewParamsArray['appParamsForJSArray']              = json_encode($appParamsForJSArray);

        return view($this->getFrontendTemplateName() . '.vote.votes_by_category', $viewParamsArray);
    } // public function votes_by_category() // votes by category page


    public function vote($vote_slug)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(false, ['csrf_token', 'empty_img_url', 'site_name', 'site_heading', 'site_subheading']);
        $activeVote      = Vote
            ::getBySlug($vote_slug)
            ->getByStatus('A')
            ->first();

        if ($activeVote === null) {
            return View($this->getFrontendTemplateName() . '.msg', ['text' => 'Vote with slug # "' . $vote_slug . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }

        $voteImageProps = Vote::setVoteImageProps($activeVote->id, $activeVote->image, false);
        if (count($voteImageProps) > 0) {
            $activeVote->setVoteImagePropsAttribute($voteImageProps);
        }

        $viewParamsArray['activeVote']  = $activeVote;
        $appParamsForJSArray['vote_id'] = $activeVote->id;

        $relatedTags                    =  MyTaggable
            ::getByTaggableId($activeVote->id)
            ->get()
            ->map(function ($item) {
                $relatedTag= MyTag::find($item->tag_id);
                if ( $relatedTag!== null ) {

                    return [
                        'id'           => $relatedTag->id,
                        'order_column' => $relatedTag->order_column,
                        'name'         => $this->getSpatieTagLocaledValue($relatedTag->name),
                        'slug'         => $this->getSpatieTagLocaledValue($relatedTag->slug),
                        'created_at'   => $relatedTag->created_at

                    ];
                }
            })
            ->all();

        $viewParamsArray['relatedTags'] = $relatedTags;

        $site_name                                     = Settings::getValue('site_name');


        $viewParamsArray['page_meta_keywords_string' ] = implode( ",", $this->addAppMetaKeywords( is_array($activeVote->meta_keywords) ? $activeVote->meta_keywords : []) );
        $viewParamsArray['page_meta_description']      = !empty($activeVote->meta_description) ? $activeVote->meta_description : ( $site_name . ' : '.$activeVote->name );

        $viewParamsArray['appParamsForJSArray']        = json_encode($appParamsForJSArray);
        return view($this->getFrontendTemplateName() . '.vote.vote', $viewParamsArray);
    } // public function vote($vote_id) // vote by id


    public function load_vote_items(int $vote_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(false, []);
        $activeVote      = Vote::find($vote_id);
        if (empty($activeVote)) {
            return response()->json(['error_code' => 1, 'message' => 'Vote with id # "' . $activeVote->id . '" not found !'], HTTP_RESPONSE_OK);
        }
        if ($activeVote->status != 'A') {
            return response()->json(['error_code' => 1, 'message' => 'Vote with id # "' . $activeVote->id . '" is not active !'], HTTP_RESPONSE_OK);
        }
        $viewParamsArray['activeVote'] = $activeVote;

        $voteItems = VoteItem
            ::getByVote($vote_id)
            ->orderBy('ordering', 'asc')
            ->get();
        foreach ($voteItems as $nextVoteItem) {
            $voteItemImageProps = VoteItem::setVoteItemImageProps($nextVoteItem->id, $nextVoteItem->image, true);
            if (count($voteItemImageProps) > 0) {
                $nextVoteItem->setVoteItemImagePropsAttribute($voteItemImageProps);
            }
        }

        $votes_result_count = VoteItemUsersResult
            ::getByVote($vote_id)
            ->join( $this->vote_items_tb, $this->vote_items_tb.'.id', '=', $this->vote_item_users_results_tb.'.vote_item_id')
            ->join($this->votes_tb, $this->votes_tb.'.id', '=', $this->vote_items_tb.'.vote_id')
            ->count();

        $correct_votes_result_count = VoteItemUsersResult
            ::getByIsCorrect(true)
            ->getByVote($vote_id)
            ->join($this->vote_items_tb, $this->vote_items_tb.'.id', '=', $this->vote_item_users_results_tb.'.vote_item_id')
            ->join($this->votes_tb, $this->votes_tb.'.id', '=', $this->vote_items_tb.'.vote_id')
            ->count();

        $viewParamsArray['voteItems']                  = $voteItems;
        $viewParamsArray['votes_result_count']         = $votes_result_count;
        $viewParamsArray['correct_votes_result_count'] = $correct_votes_result_count;

        $viewParamsArray['quizQualityOptions']         = $this->getQuizQualityOptions(true);
        $html                                          = view($this->getFrontendTemplateName() . '.vote.load_vote_items_listing', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);

    } // public function load_vote_items(int $vote_id)

    public function make_vote_selection(Request $request)
    {

        $requestData = $request->all();
        $vote        = Vote::find($requestData['vote_id']);
        if ($vote === null) {
            return response()->json(['error_code' => 1, 'message' => "Vote # " . $requestData['vote_id'] . " not found !", 'vote_id' => $requestData['vote_id']],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }


        $voteItem = VoteItem::find($requestData['vote_item_id']);
        if ($voteItem === null) {
            return response()->json([
                'error_code'   => 1,
                'message'      => "Vote Item # " . $requestData['vote_item_id'] . " not found !",
                'vote_item_id' => $requestData['vote_item_id']
            ],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        if ( ! Auth::check()) {
            return response()->json([
                'error_code'   => 1,
                'message'      => "To vote you must login to the system !",
                'vote_item_id' =>
                    $requestData['vote_item_id']
            ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
//        $this->authorize('create', auth()->user() );

        $loggedUser = Auth::user();

        $found_count = VoteItemUsersResult
            ::join($this->vote_items_tb, $this->vote_items_tb.'.id', '=', $this->vote_item_users_results_tb.'.vote_item_id')
            ->getByVoteIdAndUserId($requestData['vote_id'], $loggedUser->id)
            ->count();
        if ($found_count > 0) {
            return response()->json([
                'error_code'   => 1,
                'message'      => "You have already voted '" . $vote->name . "' vote # !",
                'vote_item_id' =>
                    $requestData['vote_item_id']
            ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        $newVoteItemUsersResult = new VoteItemUsersResult();
        try {

            $newVoteItemUsersResult->vote_item_id = $requestData['vote_item_id'];
            $newVoteItemUsersResult->user_id      = $loggedUser->id;
            $newVoteItemUsersResult->is_correct   = $voteItem->is_correct;

            DB::beginTransaction();
            $newVoteItemUsersResult->save();

            $newActivityLog              = new ActivityLog();
            $newActivityLog->description = $loggedUser->username . ' voted ' . ($voteItem->is_correct ? 'correctly' : 'not correctly') . " on '" . $vote->name . "' vote ";
            $newActivityLog->subject_id  = $requestData['vote_id'];
            $newActivityLog->causer_id   = $loggedUser->id;
            $newActivityLog->log_name    = $loggedUser->username;
            $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_VOTE_SELECTED;
            $newActivityLog->properties  = $voteItem->is_correct; // Vote was correct
            $newActivityLog->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'voteCategory' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => '', 'id' => $newVoteItemUsersResult->id], HTTP_RESPONSE_OK_RESOURCE_CREATED);
    } //     public function make_vote_selection(Request $request)


    public function make_quiz_quality(Request $request)
    {
        $requestData     = $request->all();
        $quiz_quality_id = ! empty($requestData['quiz_quality_id']) ? $requestData['quiz_quality_id'] : '';
        $vote_id         = ! empty($requestData['vote_id']) ? $requestData['vote_id'] : '';

        if ( ! Auth::check()) {
            return response()->json(['error_code' => 1, 'message' => "To rate you must login to the system !"], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        if (empty($quiz_quality_id)) {
            return response()->json([
                'error_code'      => 1,
                'message'         => "To rate you must select quiz quality !",
                'quiz_quality_id' => $quiz_quality_id
            ], HTTP_RESPONSE_OK);
        }

        $vote = Vote::find($vote_id);
        if ($vote === null) {
            return response()->json(['error_code' => 1, 'message' => "Vote Item # " . $vote_id . " not found !"],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        $loggedUser = Auth::user();

        $found_count = QuizQualityResult
            ::getByVoteIdAndUserId($vote_id, $loggedUser->id)
            ->count();
        if ($found_count > 0) {
            return response()->json(['error_code' => 1, 'message' => "You have already rated '" . $vote->name . "' # vote !", 'vote_id' => $vote_id],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        $newVoteItemUsersResult = new QuizQualityResult();
        try {
            $newVoteItemUsersResult->quiz_quality_id = $quiz_quality_id;
            $newVoteItemUsersResult->vote_id         = $vote_id;
            $newVoteItemUsersResult->user_id         = $loggedUser->id;
            DB::beginTransaction();
            $newVoteItemUsersResult->save();

            $newActivityLog              = new ActivityLog();
            $newActivityLog->description = $loggedUser->username . ' set quiz quality ' . ($quiz_quality_id) . " on '" . $vote->name . "' vote ";
            $newActivityLog->subject_id  = $requestData['vote_id'];
            $newActivityLog->causer_id   = $loggedUser->id;
            $newActivityLog->log_name    = $loggedUser->username;
            $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_SET_QUIZ_QUALITY;
            $newActivityLog->properties  = $quiz_quality_id;
            $newActivityLog->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'voteCategory' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => '', 'id' => $newVoteItemUsersResult->id], HTTP_RESPONSE_OK_RESOURCE_CREATED);
    } //     public function make_quiz_quality(Request $request)


    public function get_vote_results_in_stars(int $vote_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(false, []);
        $activeVote      = Vote
            ::find($vote_id)
            ->getByStatus('A')
            ->first();
        if ($activeVote === null) {
            return View($this->getFrontendTemplateName() . '.msg', ['text' => 'Vote with id # "' . $activeVote->id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }
        $viewParamsArray['activeVote'] = $activeVote;


        $votes_result_count = VoteItemUsersResult
            ::getByVote($vote_id)
            ->join($this->vote_items_tb, $this->vote_items_tb.'.id', '=', $this->vote_item_users_results_tb.'.vote_item_id')
            ->join($this->votes_tb, $this->votes_tb.'.id', '=', $this->vote_items_tb.'.vote_id')
            ->count();

        $correct_votes_result_count = VoteItemUsersResult
            ::getByIsCorrect(true)->
            getByVote($vote_id)->
            join($this->vote_items_tb, $this->vote_items_tb.'.id', '=', $this->vote_item_users_results_tb.'.vote_item_id')->
            join($this->votes_tb, $this->votes_tb.'.id', '=', $this->vote_items_tb.'.vote_id')->
            count();

        $voteItemsResults     = [];
        $voteItemsResultsData = VoteItemUsersResult
            ::getByVote($vote_id)
            ->select(
                $this->vote_items_tb.'.name as vote_item_name',
                $this->vote_items_tb.'.ordering as vote_item_ordering',
                $this->vote_items_tb.'.is_correct as vote_item_is_correct',
                \DB::raw('count(' . DB::getTablePrefix() . $this->vote_item_users_results_tb.'.id) as count')
            )
            ->orderBy('vote_item_ordering', 'asc')
            ->groupBy('vote_item_name')
            ->groupBy('vote_item_ordering')
            ->groupBy('vote_item_is_correct')
            ->join($this->vote_items_tb, $this->vote_items_tb.'.id', '=', $this->vote_item_users_results_tb.'.vote_item_id')
            ->get();
        $biggest_count_value  = 0;
        foreach ($voteItemsResultsData as $next_key => $nextVoteItemsResult) {
            $voteItemsResults[] = [
                'vote_item_name'       => $nextVoteItemsResult->vote_item_name,
                'vote_item_is_correct' => $nextVoteItemsResult->vote_item_is_correct,
                'count'                => $nextVoteItemsResult->count,
                'percent'              => 0
            ];
            if ($biggest_count_value < $nextVoteItemsResult->count) {
                $biggest_count_value = $nextVoteItemsResult->count;
            }
        }

        foreach ($voteItemsResults as $next_key => $nextVoteItemsResult) {
            $voteItemsResults[$next_key]['percent'] = (int)($nextVoteItemsResult['count'] * 100 / $biggest_count_value);
        }
        $viewParamsArray['votes_result_count']         = $votes_result_count;
        $viewParamsArray['correct_votes_result_count'] = $correct_votes_result_count;
        $viewParamsArray['voteItemsResults']           = $voteItemsResults;
        $viewParamsArray['quizQualityOptions']         = $this->getQuizQualityOptions(true);

        $html                                          = view($this->getFrontendTemplateName() . '.vote.get_vote_results_in_stars', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_vote_results_in_stars(int $vote_id)


    public function get_vote_quiz_quality_in_stars(int $vote_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(false, []);
        $activeVote      = Vote
            ::find($vote_id)
            ->getByStatus('A')
            ->first();
        if ($activeVote === null) {
            return View($this->getFrontendTemplateName() . '.msg', ['text' => 'Vote with id # "' . $activeVote->id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }
        $viewParamsArray['activeVote'] = $activeVote;

        $quizQualityOptions     = $this->getQuizQualityOptions(true);
        $quizQualityResults     = [];
        $quizQualityResultsData = QuizQualityResult
            ::getByVoteIdAndUserId($vote_id)
            ->select(
                $this->quiz_quality_results_tb.'.quiz_quality_id',
                \DB::raw( 'count(' . DB::getTablePrefix() . $this->quiz_quality_results_tb.'.quiz_quality_id) as count' )
            )
            ->groupBy('quiz_quality_id')
            ->get();
        $biggest_count_value    = 0;
        foreach ($quizQualityResultsData as $next_key => $nextQuizQualityResult) {
            $quiz_quality_title   = ! empty($quizQualityOptions[$nextQuizQualityResult->quiz_quality_id]) ? $quizQualityOptions[$nextQuizQualityResult->quiz_quality_id] : '';
            $quizQualityResults[] = [
                'quiz_quality_id'    => $nextQuizQualityResult->quiz_quality_id,
                'quiz_quality_title' => $quiz_quality_title,
                'count'              => $nextQuizQualityResult->count,
                'percent'            => 0
            ];
            if ($biggest_count_value < $nextQuizQualityResult->count) {
                $biggest_count_value = $nextQuizQualityResult->count;
            }
        }

        foreach ($quizQualityResults as $next_key => $nextQuizQualityResult) {
            $quizQualityResults[$next_key]['percent'] = (int)($nextQuizQualityResult['count'] * 100 / $biggest_count_value);
        }
        $quiz_quality_result_count = QuizQualityResult
            ::getByVoteIdAndUserId($vote_id)
            ->count();

        $viewParamsArray['quiz_quality_result_count'] = $quiz_quality_result_count;
        $viewParamsArray['quizQualityResults']        = $quizQualityResults;
        $viewParamsArray['quizQualityOptions']        = $this->getQuizQualityOptions(true);
        $html                                         = view($this->getFrontendTemplateName() . '.vote.get_vote_quiz_quality_in_stars', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);

    } // public function get_(int $vote_id)



    public function tag($tag_slug)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(false, ['csrf_token', 'empty_img_url', 'site_name', 'site_heading', 'site_subheading']);
        $activeTag       = MyTag
            ::containingSlug($tag_slug)
            ->first();

        if ($activeTag == null) {
            return View($this->getFrontendTemplateName() . '.msg', ['text' => 'Tag with slug # "' . $tag_slug . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }

        $tagDetail           = TagDetail::getByTagId($activeTag->id)->first();
        $tagDetailImageProps = [];
        if ( ! empty($tagDetail)) {
            $tagDetailImageProps = TagDetail::setTagDetailImageProps($tagDetail->tag_id, $tagDetail->image, false);
            if (count($tagDetailImageProps) > 0) {
                $tagDetail->setTagDetailImagePropsAttribute($tagDetailImageProps);
            }
        }

        $viewParamsArray['tag_name']            = $this->getSpatieTagLocaledValue($activeTag->name);
        $viewParamsArray['tag_id']              = $activeTag->id;
        $viewParamsArray['tag_slug']            = $this->getSpatieTagLocaledValue($activeTag->slug);
        $viewParamsArray['tagDetail']           = $tagDetail;
        $viewParamsArray['tagDetailImageProps'] = $tagDetailImageProps;

        $appParamsForJSArray['tag_name'] = $this->getSpatieTagLocaledValue($activeTag->name);
        $appParamsForJSArray['tag_id']   = $activeTag->id;
        $appParamsForJSArray['tag_slug'] = $this->getSpatieTagLocaledValue($activeTag->slug);
        $appParamsForJSArray['tag_id']   = $activeTag->id;
        $tagRelatedVotes                 = [];



        $tempTagRelatedVotes = MyTaggable
            ::getByTagId($activeTag->id)
            ->getByTaggableType(\App\Vote::class)
            ->get();
        foreach ($tempTagRelatedVotes as $next_key => $nextTempTagRelatedVote) {
            $nextVote = Vote
                ::where(with(new Vote)->getTable() . '.id', $nextTempTagRelatedVote->taggable_id)
                ->getByStatus('A')
                ->leftJoin( $this->vote_categories_tb, $this->vote_categories_tb.'.id', '=', $this->votes_tb.'.vote_category_id' )
                ->select( $this->votes_tb.".*", $this->votes_tb.".image as vote_image", $this->vote_categories_tb.".name as vote_category_name",$this->vote_categories_tb.".slug as vote_category_slug" )
                ->first();
            if (empty($nextVote)) {
                continue;
            }

            $voteImageProps = Vote
                ::setVoteImageProps($nextTempTagRelatedVote->taggable_id, $nextVote->image, false);
            if (count($voteImageProps) > 0) {
                $nextVote->setVoteImagePropsAttribute($voteImageProps);
            }

            $tagRelatedVotes[] = $nextVote;
        }
        $viewParamsArray['tagRelatedVotes']                  = $tagRelatedVotes;
        $viewParamsArray['appParamsForJSArray']              = json_encode($appParamsForJSArray);

        return view($this->getFrontendTemplateName() . '.vote.tag', $viewParamsArray);
    } // public function tag($tag_id) // tag by id


}