<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;

use Carbon\Carbon;

use App\User;
use App\PaymentAgreement;
use App\ServiceSubscription;
use App\Http\Controllers\MyAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\ServiceSubscriptionRequest;
use App\Paypal\SubscriptionPlan;        //app/Paypal/SubscriptionPlan.php
use App\Paypal\PaypalAgreement;        //app/Paypal/SubscriptionPlan.php

class ServiceSubscriptionsController extends MyAppController
{
    use funcsTrait;
    private $users_tb;
    private $service_subscriptions_tb;

    public function __construct()
    {
        $this->users_tb                 = with(new User)->getTable();
        $this->service_subscriptions_tb = with(new ServiceSubscription)->getTable();
    }

    // SERVICE SUBSCRIPTION LISTING/EDITOR BLOCK BEGIN

    public function index($filter_type = '', $filter_value = '')
    {
        $viewParamsArray                                             = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token'],
            ['filter_type' => $filter_type, 'filter_value' => $filter_value]);
        $viewParamsArray['serviceSubscriptionPricePeriodValueArray'] = $this->SetArrayHeader(['' => ' -Select Price Period- '],
            ServiceSubscription::getServiceSubscriptionPricePeriodValueArray(false));
        $viewParamsArray['filter_type']                              = $filter_type;
        $viewParamsArray['filter_value']                             = $filter_value;
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.service_subscription.index', $viewParamsArray);
    }

    public function get_service_subscriptions_dt_listing()
    {
        $request = request();

        $filter_name         = $request->input('filter_name', '');
        $filter_active       = $request->input('filter_active', '');
        $filter_price_period = $request->input('filter_price_period', '');

        $serviceSubscriptionsCollection = ServiceSubscription
            ::getByName($filter_name, true)
            ->getByActive($filter_active, true)
            ->getByPricePeriod($filter_price_period)
//            ->select( $this->service_subscriptions_tb.'.*' )
            ->get();

        foreach ($serviceSubscriptionsCollection as $next_key => $nextServiceSubscription) {
            $serviceSubscriptionsCollection[$next_key]->slashed_name = addslashes($nextServiceSubscription->name);
        }

        return Datatables
            ::of($serviceSubscriptionsCollection)
            ->editColumn('price_period', function ($service_subscription) {
                if ( ! isset($service_subscription->price_period)) {
                    return '::' . $service_subscription->price_period;
                }

                return ServiceSubscription::getServiceSubscriptionPricePeriodLabel($service_subscription->price_period);
            })

            //     public function getFormattedCurrency($val) : string
            ->editColumn('price', function ($service_subscription) {
                if ( ! isset($service_subscription->price)) {
                    return '';
                }

                return $this->getFormattedCurrency($service_subscription->price);
            })
            
            ->setRowClass(function ($service_subscription) {
                return (! $service_subscription->active ? 'row_inactive_status' : '');
            })
            ->editColumn('active', function ($service_subscription) {
                if ( ! isset($service_subscription->active)) {
                    return '::' . $service_subscription->active;
                }

                return ServiceSubscription::getServiceSubscriptionActiveLabel($service_subscription->active);
            })
            ->editColumn('created_at', function ($service_subscription) {
                if (empty($service_subscription->created_at)) {
                    return '';
                }

                return $this->getCFFormattedDateTime($service_subscription->created_at);
            })
            ->editColumn('updated_at', function ($service_subscription) {
                if (empty($service_subscription->updated_at)) {
                    return '';
                }

                return $this->getCFFormattedDateTime($service_subscription->updated_at);
            })
            ->editColumn('action', '<a href="/admin/service-subscriptions/{{$id}}/edit"><i class=" fa fa-edit"></i></a>')
            ->editColumn('action_delete',
                '<a href="#" onclick="javascript:backendServiceSubscription.deleteServiceSubscription({{$id}},\'{{$slashed_name}}\')"><i class="fa fa-remove a_link"></i></a>')
            ->rawColumns(['action', 'action_delete'])
            ->make(true);
    }

    public function create()
    {

        $viewParamsArray                                             = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['serviceSubscription']                      = new ServiceSubscription();
        $viewParamsArray['serviceSubscription']->price_period        = 'N';
        $viewParamsArray['serviceSubscription']->active              = '1';
        $viewParamsArray['serviceSubscriptionPricePeriodValueArray'] = $this->SetArrayHeader(['' => ' -Select Price Period- '],
            ServiceSubscription::getServiceSubscriptionPricePeriodValueArray(false));
        $viewParamsArray['serviceSubscriptionActiveValueArray']      = $this->SetArrayHeader(['' => ' -Select Is Active- '],
            ServiceSubscription::getServiceSubscriptionActiveValueArray(false));
        $viewParamsArray['appParamsForJSArray']                      = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.service_subscription.create', $viewParamsArray);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceSubscriptionRequest $request)
    {
//        echo '<pre>store ::' . print_r(-1, true) . '</pre>';

        $newServiceSubscription = new ServiceSubscription();

        $newServiceSubscription->name         = $request->get('name');
        $newServiceSubscription->active       = $request->get('active');
        $newServiceSubscription->price_period = $request->get('price_period');
        $newServiceSubscription->price        = $request->get('price');
        $newServiceSubscription->service_id   = $request->get('service_id');
        $newServiceSubscription->description  = $request->get('description');


        DB::beginTransaction();
        try {
            $newServiceSubscription->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput(['name' => $request->get('name'), 'status' => $request->get('status')]);
        }
        $this->setFlashMessage('Page created successfully !', 'success', 'Backend');

        return redirect('admin/service-subscriptions/' . $newServiceSubscription->id . '/edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\ServiceSubscription $service_subscription
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($service_subscription_id)
    {

//        $service_subscriptionPricePeriodValueArray = $this->SetArrayHeader(['' => ' -Select Status- '], ServiceSubscription::getServiceSubscriptionPricePeriodValueArray(false));
        $serviceSubscription = ServiceSubscription::find($service_subscription_id);

        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);


//        echo '<pre>$appParamsForJSArray::'.print_r($appParamsForJSArray,true).'</pre>';

        if ($serviceSubscription === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'service_subscription with id # "' . $service_subscription_id . '" not found !',
                'type'   => 'danger',
                'action' => ''
            ],
                $viewParamsArray);
        }

        $viewParamsArray['serviceSubscription']                      = $serviceSubscription;
        $appParamsForJSArray['id']                                   = $service_subscription_id;
        $appParamsForJSArray['service_id']                           = $serviceSubscription->service_id;
        $viewParamsArray['serviceSubscriptionPricePeriodValueArray'] = $this->SetArrayHeader(['' => ' -Select Price Period- '],
            ServiceSubscription::getServiceSubscriptionPricePeriodValueArray(false));
        $viewParamsArray['serviceSubscriptionActiveValueArray']      = $this->SetArrayHeader(['' => ' -Select Is Active- '],
            ServiceSubscription::getServiceSubscriptionActiveValueArray(false));

        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        return view($this->getBackendTemplateName() . '.admin.service_subscription.edit', $viewParamsArray);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ServiceSubscription $serviceSubscription
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceSubscriptionRequest $request, int $service_subscription_id)
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);

        $serviceSubscription = ServiceSubscription::find($service_subscription_id);
        if ($serviceSubscription === null) {
            return View($this->getBackendTemplateName() . '.admin.dashboard.msg', [
                'text'   => 'Service subscription with id # "' . $service_subscription_id . '" not found !',
                'type'   => 'danger',
                'action' => ''
            ],
                $viewParamsArray);
        }

        $serviceSubscription->name         = $request->get('name');
        $serviceSubscription->active       = $request->get('active');
        $serviceSubscription->service_id   = $request->get('service_id');
        $serviceSubscription->price_period = $request->get('price_period');
        $serviceSubscription->price        = $request->get('price');
        $serviceSubscription->description  = $request->get('description');
        $serviceSubscription->updated_at   = Carbon::now(config('app.timezone'));

        DB::beginTransaction();
        try {
            $serviceSubscription->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
        $this->setFlashMessage('Page updated successfully !', 'success', 'Backend');

        //resources/views/defaultBS41Backend/admin/service_subscription/index.blade.php
        return redirect()->route('admin.service-subscriptions.index');
    }


    /* delete service_subscription with related */
    public function destroy(Request $request)
    {
        $id                  = $request->get('id');
        $serviceSubscription = ServiceSubscription::find($id);

        if ($serviceSubscription === null) {
            return response()->json(['error_code' => 11, 'message' => 'Service subscription # "' . $id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $serviceSubscription->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function destroy(Request $request)

    // SERVICE SUBSCRIPTION LISTING/EDITOR BLOCK END


    // PAYPAL SUBSCRIPTION BLOCK START
    public function paypal_create_plan(Request $request) // http://local-votes.com/paypal_create_plan
    {
        $id          = $request->get('id');
        $requestData = $request->all();

//        echo '<pre>$requestData::' . print_r($requestData, true) . '</pre>';
        $this->debToFile(print_r($requestData, true), '  SubscriptionPlan::paypal_create_plan  - $requestData::');
        $serviceSubscription = ServiceSubscription::find($id);
        if ($serviceSubscription === null) {
            return response()->json(['error_code' => 11, 'message' => 'Service subscription # "' . $id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        try {
            $plan              = new SubscriptionPlan(Auth::user());
            $result            = $plan->create([
                'name'         => $requestData['name'],
                'price_period' => $requestData['price_period'],
                'price'        => $requestData['price'],
                'description'  => $requestData['description'],
            ]);

//            echo '<pre>$result::' . print_r($result, true) . '</pre>';
//            echo '<pre>$result->state::' . print_r($result->state, true) . '</pre>';
//        "state":"CREATED",
            if ($result->state != "CREATED") {
                return response()->json(['error_code' => 1, 'message' => 'Internal error'], HTTP_RESPONSE_BAD_REQUEST);
            }

            DB::beginTransaction();
            $serviceSubscription->service_id = $result->id;
            $serviceSubscription->updated_at = Carbon::now(config('app.timezone'));
            $serviceSubscription->save();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => '', 'service_id'=> $serviceSubscription->service_id ], HTTP_RESPONSE_OK_RESOURCE_CREATED);
    } //     public function paypal_create_plan(Request $request)


    public function clear_paypal_plan(Request $request) // http://local-votes.com/clear_paypal_plan
    {
        $id          = $request->get('id');
        $serviceSubscription = ServiceSubscription::find($id);
        if ($serviceSubscription === null) {
            return response()->json(['error_code' => 11, 'message' => 'Service subscription # "' . $id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        try {

            DB::beginTransaction();
            $serviceSubscription->service_id = null;
            $serviceSubscription->updated_at = Carbon::now(config('app.timezone'));
            $serviceSubscription->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => '', 'service_id'=> $serviceSubscription->service_id ], HTTP_RESPONSE_OK_RESOURCE_CREATED);
    } //     public function clear_paypal_plan(Request $request)


    public function paypal_activate_plan(Request $request)
    {
        $id                  = $request->get('id');
        $serviceSubscription = ServiceSubscription::find($id);

        if ($serviceSubscription === null) {
            return response()->json(['error_code' => 11, 'message' => 'Service subscription # "' . $id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
//            $serviceSubscription->delete();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_CREATED);
    } //     public function paypal_activate_plan(Request $request)


    public function paypal_plans()
    {
        $viewParamsArray                                             = $appParamsForJSArray = $this->getAppParameters( true, ['csrf_token'],
            [/*'filter_type' => $filter_type, 'filter_value' => $filter_value*/ ] );
        $viewParamsArray['serviceSubscriptionPricePeriodValueArray'] = $this->SetArrayHeader(['' => ' -Select Price Period- '],
            ServiceSubscription::getServiceSubscriptionPricePeriodValueArray(false));
//        $viewParamsArray['filter_type']                              = $filter_type;
//        $viewParamsArray['filter_value']                             = $filter_value;
//        echo '<pre>$viewParamsArray::'.print_r($viewParamsArray,true).'</pre>';
        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        $serviceSubscriptionsSelectionArray= ServiceSubscription::getServiceSubscriptionsSelectionArray(null, 'service_id');

        $plan             = new SubscriptionPlan(Auth::user());
        $listPlan         = $plan->listPlan();
        $paypalPlans      = $listPlan->plans;
//        dd($paypalPlans);
        $viewParamsArray['paypalPlans'] = ( !empty($paypalPlans) and is_array($paypalPlans) ) ? $paypalPlans : [];
        $viewParamsArray['serviceSubscriptionsSelectionArray'] = ( !empty($serviceSubscriptionsSelectionArray) and is_array($serviceSubscriptionsSelectionArray) ) ? $serviceSubscriptionsSelectionArray : [];

        return view($this->getBackendTemplateName() . '.admin.service_subscription.paypal_plans', $viewParamsArray);
    } // public function paypal_plans()

    public function load_paypal_plans()
    {
//            ServiceSubscription::getServiceSubscriptionPricePeriodValueArray(false));
//        $viewParamsArray['filter_type']                              = $filter_type;
//        $viewParamsArray['filter_value']                             = $filter_value;
//        echo '<pre>$viewParamsArray::'.print_r($viewParamsArray,true).'</pre>';
//        $viewParamsArray['appParamsForJSArray'] = json_encode($appParamsForJSArray);

        $plan             = new SubscriptionPlan(Auth::user());
        $listPlan         = $plan->listPlan();
        $paypalPlans      = $listPlan->plans;
        $this->debToFile(print_r($paypalPlans, true), '  SubscriptionPlan::delete  - $delete_plan_result::');

        return response()->json( ['error_code' => 0, 'message' => '', 'paypalPlans' => $paypalPlans ], HTTP_RESPONSE_OK );
    } // public function load_paypal_plans()

    public function paypal_plan_success()
    {
        $loggedUser = Auth::user();
        $agreement= new PaypalAgreement($loggedUser);
        $resultPaypalAgreement= $agreement->execute( request('token') );
        $newPaymentAgreement= new PaymentAgreement();
        $newPaymentAgreement->user_id= $loggedUser->id;
        $newPaymentAgreement->payment_type= "PS"; // Paypal Subscription
        $newPaymentAgreement->state= $resultPaypalAgreement->state;
        $newPaymentAgreement->payment_agreement_id= $resultPaypalAgreement->id;
        $newPaymentAgreement->description= $resultPaypalAgreement->description;
        $newPaymentAgreement->start_date= $resultPaypalAgreement->start_date;
        $newPaymentAgreement->save();
        return redirect()->route('home-msg', [])->with(['text' => 'You were successfully subscribed with your payment account !', 'type' => 'success', 'action' => '']);
//        return redirect()->route('home-msg', [])->with(['text' => 'You successfully Signed Plan Agreement !', 'type' => 'success', 'action' => '']);
    } // public function paypal_plan_success($status)

    public function paypal_plan_failure()
    {
        return redirect()->route('home-msg', [])->with(['text' => 'You cancelled Plan Agreement !', 'type' => 'danger', 'action' => '']);
    } // public function paypal_plan_failure()



    public function paypal_plan_destroy(Request $request)
    {
        $plan_id                  = $request->get('plan_id');
/*        $serviceSubscription = ServiceSubscription::find($plan_id);
        if ($serviceSubscription === null) {
            return response()->json(['error_code' => 11, 'message' => 'Service subscription # "' . $plan_id . '" not found !'],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }*/

        $plan                = new SubscriptionPlan(Auth::user());
        $delete_plan_result  = $plan->delete($plan_id);
        $this->debToFile(print_r($delete_plan_result, true), '  SubscriptionPlan::delete  - $delete_plan_result::');

        if ( !$delete_plan_result ) {
            return response()->json(['error_code' => 1, 'message' => "Can not delete Paypal plan"], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        if ( $delete_plan_result ) {
            DB::beginTransaction();
            try {
//            $serviceSubscription->delete();
                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();

                return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }
        } // if ( $delete_plan_result ) {

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK_RESOURCE_DELETED);
    } //     public function paypal_plan_destroy(Request $request)

    /*     Route::POST('paypal-plan/activate', 'Admin\ServiceSubscriptionsController@paypal_plan_activate')->name('paypal-plan-activate');
    Route::POST('paypal-plan/deactivate', 'Admin\ServiceSubscriptionsController@paypal_plan_deactivate')->name('paypal-plan-deactivate');
 */

    public function paypal_plan_activate(Request $request)
    {
        $plan_id                  = $request->get('plan_id');
        /*        $serviceSubscription = ServiceSubscription::find($plan_id);
                if ($serviceSubscription === null) {
                    return response()->json(['error_code' => 11, 'message' => 'Service subscription # "' . $plan_id . '" not found !'],
                        HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
                }*/

        $plan                = new SubscriptionPlan(Auth::user());
        $activatePlanResult  = $plan->activate($plan_id);

        $this->debToFile(print_r($activatePlanResult, true), '  SubscriptionPlan::activate  - $activatePlanResult::');

        if ( empty($activatePlanResult->state) or $activatePlanResult->state!= 'ACTIVE' ) {
            return response()->json(['error_code' => 1, 'message' => "Can not activate Paypal plan"], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        if ( $activatePlanResult ) {
            DB::beginTransaction();
            try {
//            $serviceSubscription->activate();
                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();

                return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }
        } // if ( $activatePlanResult ) {

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function paypal_plan_activate(Request $request)


    public function paypal_create_agreement($plan_id) {
        $agreement= new PaypalAgreement($plan_id);
        return $agreement->create($plan_id);
    } //


    public function paypal_plan_deactivate(Request $request)
    {
        $plan_id                  = $request->get('plan_id');
        /*        $serviceSubscription = ServiceSubscription::find($plan_id);
                if ($serviceSubscription === null) {
                    return response()->json(['error_code' => 11, 'message' => 'Service subscription # "' . $plan_id . '" not found !'],
                        HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
                }*/

        $plan                = new SubscriptionPlan(Auth::user());
        $deactivate_plan_result  = $plan->deactivate($plan_id);
        $this->debToFile(print_r($deactivate_plan_result, true), '  SubscriptionPlan::deactivate  - $deactivate_plan_result::');

        if ( !$deactivate_plan_result ) {
            return response()->json(['error_code' => 1, 'message' => "Can not deactivate Paypal plan"], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        if ( $deactivate_plan_result ) {
            DB::beginTransaction();
            try {
//            $serviceSubscription->deactivate();
                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();

                return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }
        } // if ( $deactivate_plan_result ) {

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function paypal_plan_deactivate(Request $request)


    // PAYPAL SUBSCRIPTION BLOCK END

}
