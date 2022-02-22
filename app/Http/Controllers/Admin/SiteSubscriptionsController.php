<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MyAppController;
use App\SiteSubscription;
use DB;
use App\User;
use App\Vote;
use App\VoteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\SiteSubscriptionRequest;

class SiteSubscriptionsController extends MyAppController
{
    use funcsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter_type = '', $filter_value = '')
    {
        $viewParamsArray                                     = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['siteSubscriptionStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Active- '],
            SiteSubscription::getSiteSubscriptionActiveValueArray(false));
        $viewParamsArray['appParamsForJSArray']              = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.site_subscription.index', $viewParamsArray);
    }

    public function get_site_subscriptions_dt_listing()
    {
        $request = request();

        $filter_name   = $request->input('filter_name', '');
        $filter_active = $request->input('filter_active', '');

        $siteSubscriptionsCollection = SiteSubscription
            ::getByName($filter_name, true)
            ->getByActive($filter_active, true)
            ->get();

	    foreach( $siteSubscriptionsCollection as $next_key=> $nextSiteSubscription ) {
            $siteSubscriptionsCollection[$next_key]->slashed_name= addslashes($nextSiteSubscription->name);
        }

        return Datatables
            ::of($siteSubscriptionsCollection)
            ->editColumn('active', function ($siteSubscription) {
                if ( ! isset($siteSubscription->active)) {
                    return '';
                }
                return SiteSubscription::getSiteSubscriptionActiveLabel($siteSubscription->active);
            })
            ->setRowClass(function ($siteSubscription) {
                return ! $siteSubscription->active ? 'row_inactive_status' : '';
            })
            ->editColumn('vote_category_id', function ($siteSubscription) {
                if ( ! isset($siteSubscription->vote_category_id)) {
                    return '';
                }
                $voteCategory = VoteCategory::find($siteSubscription->vote_category_id);
                return ( ! empty($voteCategory->name)) ? $voteCategory->name : '';
            })
            ->editColumn('created_at', function ($siteSubscription) {
                if (empty($siteSubscription->created_at)) {
                    return '';
                }
                return $this->getCFFormattedDateTime($siteSubscription->created_at);
            })
            ->editColumn('action', '<a href="/admin/site-subscriptions/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendSiteSubscription.deleteSiteSubscription({{$id}},\'{{$slashed_name}}\')"><i class="fa fa-remove a_link"></i></a>')
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
        $viewParamsArray                                     = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['siteSubscriptionStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Active- '], SiteSubscription::getSiteSubscriptionActiveValueArray(false));

        $voteCategoryValueArray   = [];
        $voteCategoriesCollection = VoteCategory::orderBy('name', 'desc')->get();
        foreach ($voteCategoriesCollection as $next_key => $nextVoteCategory) {
            $voteCategoryValueArray[$nextVoteCategory->id] = $nextVoteCategory->name;
        }
        $viewParamsArray['voteCategoryValueArray'] = $this->SetArrayHeader(['' => ' -Select Vote Category- '], $voteCategoryValueArray);
        $viewParamsArray['appParamsForJSArray']    = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.site_subscription.create', $viewParamsArray);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SiteSubscriptionRequest $request)
    {
//        echo '<pre>store ::' . print_r(-1, true) . '</pre>';


        $siteSubscription                   = new SiteSubscription();
        $siteSubscription->name             = $request->get('name');
        $siteSubscription->active           = $request->get('active');
        $siteSubscription->vote_category_id = $request->get('vote_category_id');
        DB::beginTransaction();
        try {
            $siteSubscription->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['name' => $request->get('name'), 'active' => $request->get('active'), 'vote_category_id' => $request->get('vote_category_id')]);
        }
        $this->setFlashMessage('Site subscription created successfully !', 'success', 'Backend');

        return redirect()->route('admin.site-subscriptions.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SiteSubscription $siteSubscription
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($site_subscription_id)
    {
        $siteSubscriptionStatusValueArray = $this->SetArrayHeader(['' => ' -Select Active- '], SiteSubscription::getSiteSubscriptionActiveValueArray(false));
        $siteSubscription                 = SiteSubscription::find($site_subscription_id);
        $viewParamsArray                  = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        if ($siteSubscription === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'Site Subscription with id # "' . $site_subscription_id . '" not found !',
                'type'   =>
                    'danger',
                'action' => ''
            ], $viewParamsArray);
        }

        $voteCategoryValueArray   = [];
        $voteCategoriesCollection = VoteCategory::orderBy('name', 'desc')->get();
        foreach ($voteCategoriesCollection as $next_key => $nextVoteCategory) {
            $voteCategoryValueArray[$nextVoteCategory->id] = $nextVoteCategory->name;
        }

        $viewParamsArray['voteCategoryValueArray']           = $this->SetArrayHeader(['' => ' -Select Vote Category- '], $voteCategoryValueArray);
        $viewParamsArray['siteSubscription']                 = $siteSubscription;
        $appParamsForJSArray['id']                           = $site_subscription_id;
        $viewParamsArray['siteSubscriptionStatusValueArray'] = $siteSubscriptionStatusValueArray;
        // //            'siteSubscription'=> $siteSubscription,

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.site_subscription.edit', $viewParamsArray);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\SiteSubscription $siteSubscription
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SiteSubscriptionRequest $request, int $site_subscription_id)
    {
		$viewParamsArray  = $this->getAppParameters(true, ['csrf_token']);
        $siteSubscription = SiteSubscription::find($site_subscription_id);
        if ($siteSubscription === null) {
            $viewParamsArray['text'] = 'Site Subscription with id # "' . $site_subscription_id . '" not found !';
            $viewParamsArray['type'] = 'danger';
            return View( $this->getBackendTemplateName() . '.admin.dashboard.msg', $viewParamsArray );
        }
        $siteSubscription->name             = $request->get('name');
        $siteSubscription->active           = $request->get('active');
        $siteSubscription->vote_category_id = $request->get('vote_category_id');
        DB::beginTransaction();
        try {
            $siteSubscription->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('Site subscription updated successfully !', 'success', 'Backend');

        return redirect()->route('admin.site-subscriptions.index');
    }


    /* delete item with related site subscriptions */
    public function destroy(Request $request)
    {
        $id = $request->get('id');

        $siteSubscription = SiteSubscription::find($id);

        if ($siteSubscription === null) {
            return response()->json(['error_code' => 11, 'message' => 'Site Subscription # "' . $id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }


        DB::beginTransaction();
        try {
            $siteSubscription->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)

}
