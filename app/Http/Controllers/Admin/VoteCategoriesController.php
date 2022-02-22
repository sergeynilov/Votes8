<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Controllers\MyAppController;
use App\VoteCategory;
use DB;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\VoteCategoryRequest;

class VoteCategoriesController extends MyAppController
{
    use funcsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter_type = '', $filter_value = '')
    {
        $viewParamsArray                                 = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['voteCategoryStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Vote Category- '], VoteCategory::getVoteCategoryActiveValueArray(false));
        $viewParamsArray['appParamsForJSArray']          = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.vote_category.index', $viewParamsArray);
    }

    public function get_vote_categories_dt_listing()
    {
        $request = request();

        $filter_name   = $request->input('filter_name', '');
        $filter_active = $request->input('filter_active', '');

        $voteCategoriesCollection = VoteCategory
            ::getByName($filter_name, true)
            ->getByActive($filter_active, true)
            ->get();
        foreach( $voteCategoriesCollection as $next_key=> $nextVoteCategory ) {
            $voteCategoriesCollection[$next_key]->slashed_name= addslashes($nextVoteCategory->name);
        }

        return Datatables
            ::of($voteCategoriesCollection)
            ->editColumn('active', function ($voteCategory) {
                if ( ! isset($voteCategory->active)) {
                    return '';
                }
                return VoteCategory::getVoteCategoryActiveLabel($voteCategory->active);
            })
            ->setRowClass(function ($voteCategory) {
                return ! $voteCategory->active ? 'row_inactive_status' : '';
            })
            ->editColumn('in_subscriptions', function ($voteCategory) {
                if ( ! isset($voteCategory->in_subscriptions)) {
                    return '';
                }
                return VoteCategory::getVoteCategoryInSubscriptionsLabel($voteCategory->in_subscriptions);
            })
            ->editColumn('created_at', function ($voteCategory) {
                if (empty($voteCategory->created_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($voteCategory->created_at);
            })
            ->editColumn('updated_at', function ($voteCategory) {
                if (empty($voteCategory->updated_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($voteCategory->updated_at);
            })
            ->editColumn('action', '<a href="/admin/vote-categories/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendVoteCategory.deleteVoteCategory({{$id}},\'{{$slashed_name}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        $viewParamsArray['voteCategoryStatusValueArray']         = $this->SetArrayHeader(['' => ' -Select Active- '], VoteCategory::getVoteCategoryActiveValueArray(false));
        $viewParamsArray['voteCategoryInSubscriptionValueArray'] = $this->SetArrayHeader(['' => ' -Select In Subscription- '],
            VoteCategory::getVoteCategoryInSubscriptionLabelValueArray
            (false));
        $viewParamsArray['appParamsForJSArray']                  = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.vote_category.create', $viewParamsArray);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(VoteCategoryRequest $request)
    {
        $voteCategory                   = new VoteCategory();
        $voteCategory->name             = $request->get('name');
        $voteCategory->active           = $request->get('active');
        $voteCategory->in_subscriptions = $request->get('in_subscriptions');
        $voteCategory->meta_description = $request->get('meta_description');
        $voteCategory->meta_keywords = $request->get('meta_keywords');

        DB::beginTransaction();
        try {
            $voteCategory->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['name' => $request->get('name'), 'active' => $request->get('active'), 'in_subscriptions' => $request->get('in_subscriptions')]);
        }
        $this->setFlashMessage('Vote category created successfully !', 'success', 'Backend');

        return redirect()->route('admin.vote-categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VoteCategory $voteCategory
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($vote_category_id)
    {
        $voteCategoryStatusValueArray         = $this->SetArrayHeader(['' => ' -Select Active- '], VoteCategory::getVoteCategoryActiveValueArray(false));
        $voteCategoryInSubscriptionValueArray = $this->SetArrayHeader(['' => ' -Select Active- '], VoteCategory::getVoteCategoryInSubscriptionLabelValueArray(false));
        $voteCategory                         = VoteCategory::find($vote_category_id);
        $viewParamsArray                      = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        if ($voteCategory === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg',
                ['text' => 'Vote Category with id # "' . $vote_category_id . '" not found !', 'type' => 'danger', 'action' => ''],
                $viewParamsArray);
        }

        $viewParamsArray['voteCategory']                         = $voteCategory;
        $appParamsForJSArray['id']                               = $vote_category_id;
        $viewParamsArray['voteCategoryStatusValueArray']         = $voteCategoryStatusValueArray;
        $viewParamsArray['voteCategoryInSubscriptionValueArray'] = $voteCategoryInSubscriptionValueArray;
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.vote_category.edit', $viewParamsArray);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\VoteCategory $voteCategory
     *
     * @return \Illuminate\Http\Response
     */
    public function update(VoteCategoryRequest $request, int $vote_category_id)
    {
        $viewParamsArray  = $this->getAppParameters(true, ['csrf_token']);
        $voteCategory     = VoteCategory::find($vote_category_id);
        if ($voteCategory === null) {
            $viewParamsArray['text'] = 'Vote Category with id # "' . $vote_category_id . '" not found !';
            $viewParamsArray['type'] = 'danger';
            return View( $this->getBackendTemplateName() . '.admin.dashboard.msg', $viewParamsArray );
        }
        $voteCategory->name       = $request->get('name');
        $voteCategory->active     = $request->get('active');
        $voteCategory->updated_at = Carbon::now(config('app.timezone'));
        DB::beginTransaction();
        try {
            $voteCategory->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('Vote category updated successfully !', 'success', 'Backend');

        return redirect()->route('admin.vote-categories.index');
    }


    /* delete item with related vote categories */
    public function destroy(Request $request)
    {
        $id = $request->get('id');
        $voteCategory = VoteCategory::find($id);
        if ($voteCategory === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote Category # "' . $id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $voteCategory->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)


    public function get_vote_category_details_info(int $vote_category_id)
    {
        $votes                               = Vote
            ::getByVoteCategory($vote_category_id)
            ->orderBy('ordering', 'desc')
            ->get();
        $viewParamsArray                     = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['vote_category_id'] = $vote_category_id;
        $viewParamsArray['votes']            = $votes;
        $html                                = view($this->getBackendTemplateName() . '.admin.vote_category.vote_category_details_info', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_vote_category_details_info(Request $request)



    // VOTE'S CATEGORY META KEYWORD BLOCK BEGIN
    public function get_vote_category_meta_keywords(int $vote_category_id)
    {
        $voteCategory                 = VoteCategory::find($vote_category_id);
        $metaKeywords                 = !empty($voteCategory->meta_keywords) ? $voteCategory->meta_keywords : [];
        $viewParamsArray              = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['vote_id']   = $vote_category_id;
        $viewParamsArray['voteCategory']      = $voteCategory;
        $viewParamsArray['metaKeywords']  = $metaKeywords;
        $html                         = view($this->getBackendTemplateName() . '.admin.vote_category.meta_keywords', $viewParamsArray)->render();
        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function META KEYWORD(Request $request)

    public function attach_meta_keyword($vote_category_id, $meta_keyword)
    {
        $voteCategory = VoteCategory::find($vote_category_id);
        if ($voteCategory === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote Category # "' . $vote_category_id . '" not found!'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        DB::beginTransaction();
        try {
            $meta_keywords_array= !empty($voteCategory->meta_keywords) ? $voteCategory->meta_keywords : [];
            if ( in_array($meta_keyword, $meta_keywords_array) ) {
                return response()->json(['error_code' => 11, 'message' => 'This vote already has "' . $meta_keyword . '" !'],
                    HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }
            $meta_keywords_array[]= $meta_keyword;
            $voteCategory->meta_keywords= $meta_keywords_array;
            $voteCategory->updated_at= Carbon::now(config('app.timezone'));
            $voteCategory->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'vote' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function attach_meta_keyword($vote_category_id, $tag_id)

    public function clear_meta_keyword($vote_category_id, $meta_keyword)
    {
        $voteCategory = VoteCategory::find($vote_category_id);
        if ($voteCategory === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote Category # "' . $vote_category_id . '" not found!' ],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {

            $meta_keywords_array= $voteCategory->meta_keywords;
            foreach( $meta_keywords_array as $next_key=>$next_value ) {
                if ( $next_value == $meta_keyword ) {
                    unset($meta_keywords_array[$next_key]);
                }
            }
            $voteCategory->meta_keywords= $meta_keywords_array;
            $voteCategory->updated_at= Carbon::now(config('app.timezone'));
            $voteCategory->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json( ['error_code' => 1, 'message' => $e->getMessage() ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function clear_meta_keyword($vote_category_id, $meta_keyword)  */


    public function update_vote_meta_description($vote_category_id, $meta_description)
    {
        $voteCategory = VoteCategory::find($vote_category_id);
        if ($voteCategory === null) {
            return response()->json(['error_code' => 11, 'message' => 'Vote Category # "' . $vote_category_id . '" not found!' ],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {

            if( trim($meta_description) == '-' ) $meta_description= '';
            $voteCategory->meta_description= $meta_description;
            $voteCategory->updated_at= Carbon::now(config('app.timezone'));
            $voteCategory->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json( ['error_code' => 1, 'message' => $e->getMessage() ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function update_vote_meta_description($vote_category_id, $meta_description)  */



    // VOTE'S CATEGORY META KEYWORD BLOCK END


}
