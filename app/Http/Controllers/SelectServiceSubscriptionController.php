<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Settings;
use App\ServiceSubscription;
use App\Http\Traits\funcsTrait;
use App\Http\Requests\ContactUsRequest;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use App\ActivityLog;
use App\User;
use App\Download;
use App\UserGroup;
use App\ContactUs;
use App\Mail\SendgridMail;
use Response;
use App\library\CheckValueType;


class SelectServiceSubscriptionController extends MyAppController
{                                                      // https://bootsnipp.com/snippets/VvP8d
    use funcsTrait;
    private $users_tb;
    private $service_subscriptions_tb;

    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->service_subscriptions_tb= with(new ServiceSubscription)->getTable();
    }



    public function listing()    // http://local-votes.com/select-service-subscription
    {
        $request= request();
        $viewParamsArray          = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name', 'site_heading', 'site_subheading']);

        $mysql_to_carbon_datetime_format       = config('app.mysql_to_carbon_datetime_format', 'Y-m-d H:i:s');

        $leftSelectServiceSubscriptionList= [];
        $rightSelectServiceSubscriptionList= [];
        $selectServiceSubscriptionList = ServiceSubscription
            ::getByActive( true )
            ->orderBy('price', 'desc')
            ->get();



        $select_service_subscriptionList_count_per_column= floor( count($selectServiceSubscriptionList)/2 );
        echo '<pre>$select_service_subscriptionList_count_per_column::'.print_r($select_service_subscriptionList_count_per_column,true).'</pre>';

        $module= count($selectServiceSubscriptionList) % 2;
        echo '<pre>$module::'.print_r($module,true).'</pre>';
        for( $i=0; $i< count($selectServiceSubscriptionList); $i++ ) {
            if ( $i< ($select_service_subscriptionList_count_per_column + $module) ) {
                $leftSelectServiceSubscriptionList[]= $selectServiceSubscriptionList[$i];
            } else{
                $rightSelectServiceSubscriptionList[]= $selectServiceSubscriptionList[$i];
            }
        }
//        die("-1 XXZ");


        /*        foreach( $selectServiceSubscriptionList as $next_key=>$nextTempSelectServiceSubscription ) {
                    $startDate= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $nextTempSelectServiceSubscription->start_date);
                    $endDate= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $nextTempSelectServiceSubscription->end_date);

                    $selectServiceSubscriptionList[$next_key]->event_type_label= ServiceSubscription::getServiceSubscriptionTypeLabel($nextTempSelectServiceSubscription->type);
                    $selectServiceSubscriptionList[$next_key]->event_type_color= ServiceSubscription::getServiceSubscriptionTypeColorLabel($nextTempSelectServiceSubscription->type);
                    $selectServiceSubscriptionList[$next_key]->is_same_day= $startDate->isSameDay( $endDate );
                }*/
        $viewParamsArray['appParamsForJSArray']             = json_encode($appParamsForJSArray);
        $viewParamsArray['leftSelectServiceSubscriptionList']   = $leftSelectServiceSubscriptionList;
        $viewParamsArray['rightSelectServiceSubscriptionList']   = $rightSelectServiceSubscriptionList;


        return view($this->getFrontendTemplateName() . '.select_service_subscription.listing', $viewParamsArray);
    } // private function listing ()

}
