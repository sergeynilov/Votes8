<?php

namespace App\Http\Controllers;

use App\Download;
use App\MyAppModel;
use App\Payment;
use App\PaymentItem;
use Doctrine\DBAL\Types\DateImmutableType;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ImageOptimizer;

use App\User;
use App\UserGroup;
use App\SiteSubscription;
use App\PaymentAgreement;
use App\Settings;
use App\VoteCategory;
use App\Subscription;
use App\ServiceSubscription;
use App\UsersSiteSubscription;
use App\Http\Requests\ProfileUserDetailsRequest;
use App\Http\Traits\funcsTrait;
use App\Mail\SendgridMail;
use App\Paypal\PaypalAgreement;
use Newsletter;
use Spatie\Browsershot\Browsershot;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class ProfileController extends MyAppController
{

    use funcsTrait;

    private $users_tb;
    private $vote_categories_tb;
    private $site_subscriptions_tb;
    private $users_site_subscriptions_tb;

    private $payments_tb;
    private $payment_items_tb;
    private $downloads_tb;

    public function __construct()
    {
        $this->payments_tb      = with(new Payment)->getTable();
        $this->payment_items_tb = with(new PaymentItem)->getTable();
        $this->downloads_tb     = with(new Download)->getTable();

        $this->users_tb                    = with(new User)->getTable();
        $this->vote_categories_tb          = with(new VoteCategory)->getTable();
        $this->site_subscriptions_tb       = with(new SiteSubscription)->getTable();
        $this->users_site_subscriptions_tb = with(new UsersSiteSubscription)->getTable();
    }

    /*** PROFILE VIEW BLOCK START  ***/

    public function index()
    {
        $mysql_to_carbon_datetime_format       = config('app.mysql_to_carbon_datetime_format', 'Y-m-d H:i:s');
        $subscription_expire_warning_count     = config('app.subscription_expire_warning_count', 3);
        $now = Carbon::now();
        $viewParams                          = $appParamsForJSArray = $this->getAppParameters(false,
            ['csrf_token', 'site_name', 'site_heading', 'site_subheading', 'account_register_details_text']);
        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');
        $userProfile                         = Auth::user();

        $viewParams['userProfile'] = $userProfile;


        $profileUsersSiteSubscriptions = UsersSiteSubscription
            ::getByUserId($userProfile->id)
            ->select($this->users_site_subscriptions_tb . ".*", $this->site_subscriptions_tb . ".name as site_subscription_name",
                $this->site_subscriptions_tb . ".vote_category_id", $this->vote_categories_tb . ".name as vote_category_name")
            ->leftJoin($this->site_subscriptions_tb, $this->site_subscriptions_tb . '.id', '=', $this->users_site_subscriptions_tb . '.site_subscription_id')
            ->leftJoin($this->vote_categories_tb, $this->vote_categories_tb . '.id', '=', $this->site_subscriptions_tb . '.vote_category_id')
            ->orderBy('site_subscription_name', 'asc')
            ->get();

        $profileSubscription = Subscription
            ::getByUserId($userProfile->id)
            ->getByStripeStatus('active')
            ->select("subscriptions.*",
                "service_subscriptions.name as service_subscription_name",
                "service_subscriptions.active as service_subscription_active",
                "service_subscriptions.is_premium as service_subscription_is_premium",
                "service_subscriptions.is_free as service_subscription_is_free",
                "service_subscriptions.description as service_subscription_description",
                "service_subscriptions.color as service_subscription_color",
                "service_subscriptions.background_color as service_subscription_background_color",
                "service_subscriptions.price as service_subscription_price"
            )
            ->leftJoin("service_subscriptions", "service_subscriptions" . '.id', '=', 'subscriptions.source_service_subscription_id')
            ->first();

        $avatarData    = User::setUserAvatarProps($userProfile->id, $userProfile->avatar, true);
        $fullPhotoData = User::setUserFullPhotoProps($userProfile->id, $userProfile->full_photo, true);

        $viewParams['userProfile']         = $userProfile;
        $viewParams['profileSubscription'] = $profileSubscription;
        echo '<pre>$subscription_expire_warning_count::'.print_r($subscription_expire_warning_count,true).'</pre>';
        $current_subscription_icon_type = 'transparent_on_white';
        $current_subscription_text      = '';

        if ( !empty($profileSubscription) ) {  // $user has some Subscription
            if ($profileSubscription->is_free) {

                $trialEndsAt = Carbon::createFromFormat($mysql_to_carbon_datetime_format, $profileSubscription->trial_ends_at);
                if ($trialEndsAt->isPast()) {
                    $current_subscription_text      = ' is expired';
                    $current_subscription_icon_type = 'warning';
                } else {
//                    $date = Carbon::parse('2016-09-17 11:00:00');

                    $diff = $trialEndsAt->diffInDays($now);
                    echo '<pre>$diff::' . print_r($diff, true) . '</pre>';
//                    die("-1 XXZ");
                    if ($subscription_expire_warning_count >= $diff) {
                        $current_subscription_text      = 'active till ' . $this->getCFFormattedDate($profileSubscription->trial_ends_at) . '. ' . $diff . ' day(s) left';
                        $current_subscription_icon_type = 'warning';
                    } else {
                        $current_subscription_text = 'active till ' . $this->getCFFormattedDate($profileSubscription->trial_ends_at);
                    }
                }

            } else {

                $endsAt= Carbon::createFromFormat($mysql_to_carbon_datetime_format, $profileSubscription->ends_at);
                if ( $endsAt->isPast() ) {
                    $current_subscription_text = $userProfile->service_subscription_name . ' is expired';
                    $icon_type= 'warning';
                } else {
                    $current_subscription_text = $userProfile->service_subscription_name . ', active till ' . $this->getCFFormattedDate($userProfile->ends_at);
                }

//                $current_subscription_text = $this->getCFFormattedDate($profileSubscription->ends_at);

            }
        } // if ( !empty($profileSubscription) ) {  // $user has some Subscription


        $viewParams['current_subscription_icon_type']= $current_subscription_icon_type;
        $viewParams['current_subscription_text']     = $current_subscription_text;
        $viewParams['profileUsersSiteSubscriptions'] = $profileUsersSiteSubscriptions;
        $viewParams['avatar_dimension_limits']       = \Config::get('app.avatar_dimension_limits', ['max_width' => 32, 'max_height' => 32]);
        $viewParams['full_photo_dimension_limits']   = \Config::get('app.full_photo_dimension_limits', ['max_width' => 256, 'max_height' => 256]);
        $viewParams['avatarData']                    = $avatarData;
        $viewParams['fullPhotoData']                 = $fullPhotoData;

        return view($this->getFrontendTemplateName() . '.profile.view', $viewParams);
    } // public function index()


    public function edit_details()
    {
        $viewParams                           = $appParamsForJSArray = $this->getAppParameters(false,
            ['csrf_token', 'site_name', 'site_heading', 'site_subheading', 'account_register_details_text']);
        $viewParams['appParamsForJSArray']    = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url']  = config('app.medium_slogan_img_url');
        $viewParams['userSexLabelValueArray'] = $this->SetArrayHeader(['' => ' -Select Sex- '], User::getUserSexValueArray(false));
        $userProfile                          = Auth::user();

        $viewParams['userProfile'] = $userProfile;
        $viewParams['userProfile'] = $userProfile;

        return view($this->getFrontendTemplateName() . '.profile.edit', $viewParams);
    } // public function edit_details()


    public function update_details(
        ProfileUserDetailsRequest /* Request */
        $request
    ) {
        $userProfile             = Auth::user();
        $requestData             = $request->all();
        $userProfile->first_name = $requestData['first_name'];
        $userProfile->last_name  = $requestData['last_name'];
        $userProfile->phone      = $requestData['phone'];
        $userProfile->website    = $requestData['website'];
        $userProfile->notes      = $requestData['notes'];
        $userProfile->sex        = $requestData['sex'];


//        {{--                $table->string('address_line1',255)->nullable();--}}
//        {{--                $table->string('address_city',255)->nullable();--}}
//        {{--                $table->string('address_state',5)->nullable();--}}
//        {{--                $table->string('address_postal_code',5)->nullable();--}}
//        {{--                $table->string('address_country_code',2)->nullable();--}}
//
//        {{--                $table->string('shipping_address_line1',255)->nullable();--}}
//        {{--                $table->string('shipping_address_city',255)->nullable();--}}
//        {{--                $table->string('shipping_address_state',5)->nullable();--}}
//        {{--                $table->string('shipping_address_postal_code',5)->nullable();--}}
//        {{--                $table->string('shipping_address_country_code',2)->nullable();--}}
//

        $userProfile->address_line1                 = $requestData['address_line1'];
        $userProfile->address_city                  = $requestData['address_city'];
        $userProfile->address_state                 = $requestData['address_state'];
        $userProfile->address_postal_code           = $requestData['address_postal_code'];
        $userProfile->address_country_code          = $requestData['address_country_code'];
        $userProfile->shipping_address_line1        = $requestData['shipping_address_line1'];
        $userProfile->shipping_address_city         = $requestData['shipping_address_city'];
        $userProfile->shipping_address_state        = $requestData['shipping_address_state'];
        $userProfile->shipping_address_postal_code  = $requestData['shipping_address_postal_code'];
        $userProfile->shipping_address_country_code = $requestData['shipping_address_country_code'];
        $userProfile->updated_at                    = Carbon::now(config('app.timezone'));
        $userProfile->save();
        $this->setFlashMessage('Profile updated successfully !', 'success', 'Profile');

        return Redirect::route('profile-view');
    } // public function update_details(ProfileUserDetailsRequest $request)


    public function edit_avatar()
    {
        $viewParams                          = $appParamsForJSArray = $this->getAppParameters(false,
            ['csrf_token', 'site_name', 'site_heading', 'site_subheading', 'account_register_avatar_text']);
        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');

        $userProfile                               = Auth::user();
        $viewParams['userProfile']                 = $userProfile;
        $viewParams['avatar_dimension_limits']     = \Config::get('app.avatar_dimension_limits', ['max_width' => 32, 'max_height' => 32]);
        $viewParams['full_photo_dimension_limits'] = \Config::get('app.full_photo_dimension_limits', ['max_width' => 256, 'max_height' => 256]);
//        $viewParams['userProfile']                 = $userProfile;

        $fullPhotoData = User::setUserFullPhotoProps($userProfile->id, $userProfile->full_photo, true);
//        echo '<pre>$fullPhotoData::'.print_r($fullPhotoData,true).'</pre>';

        $viewParams['avatar_filename']     = $userProfile->avatar;
        $viewParams['full_photo_filename'] = $userProfile->full_photo;

        return view($this->getFrontendTemplateName() . '.profile.avatar', $viewParams);
    } // public function edit_avatar()


    public function update_avatar(Request $request)
    {
        $loggedUserUploadFile          = $request->file('avatar');
        $loggedUserFullPhotoUploadFile = $request->file('full_photo');
        $uploaded_file_max_mib         = (int)\Config::get('app.uploaded_file_max_mib', 1);
        $max_size                      = 1024 * $uploaded_file_max_mib;

        $rules     = array(
            'avatar'     => 'required|max:' . $max_size,
            'full_photo' => 'required|max:' . $max_size,
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $loggedUser = Auth::user();

        if ( ! empty($loggedUserUploadFile)) {
            $logged_user_avatar     = UserGroup::checkValidImgName($loggedUserUploadFile->getClientOriginalName(), with(new User)->getAvatarFilenameMaxLength(),
                true);
            $logged_user_file_path  = $loggedUserUploadFile->getPathName();
            $loggedUser->updated_at = Carbon::now(config('app.timezone'));
            $loggedUser->avatar     = $logged_user_avatar;
        } // if (!empty($loggedUserUploadFile)) {


        if ( ! empty($loggedUserFullPhotoUploadFile)) {
            $logged_user_full_photo           = UserGroup::checkValidImgName($loggedUserFullPhotoUploadFile->getClientOriginalName(),
                with(new User)->getFullPhotoFilenameMaxLength(), true);
            $logged_user_full_photo_file_path = $loggedUserFullPhotoUploadFile->getPathName();
            $loggedUser->updated_at           = Carbon::now(config('app.timezone'));
            $loggedUser->full_photo           = $logged_user_full_photo;
        } // if (!empty($loggedUserFullPhotoUploadFile)) {

        if ( ! empty($logged_user_avatar)) {
            $dest_avatar = 'public/' . User::getUserAvatarPath($loggedUser->id, $logged_user_avatar);
            Storage::disk('local')->put($dest_avatar, File::get($logged_user_file_path));
            ImageOptimizer::optimize(storage_path() . '/app/' . $dest_avatar, null);
        } //             if ( !empty($logged_user_image) ) {
        if ( ! empty($logged_user_full_photo)) {
            $dest_full_photo = 'public/' . User::getUserFullPhotoPath($loggedUser->id, $logged_user_full_photo);
//            echo '<pre>$dest_full_photo::' . print_r($dest_full_photo, true) . '</pre>';
//            echo '<pre>$logged_user_full_photo_file_path::' . print_r($logged_user_full_photo_file_path, true) . '</pre>';
            Storage::disk('local')->put($dest_full_photo, File::get($logged_user_full_photo_file_path));
            ImageOptimizer::optimize(storage_path() . '/app/' . $dest_full_photo, null);
        } //             if ( !empty($logged_user_image) ) {


        DB::beginTransaction();
        try {
            $loggedUser->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }
//        die("-1 XXZ");
        $this->setFlashMessage('Profile\'s avatar/full photo updated successfully !', 'success', 'Profile');

        return Redirect::route('profile-view');
    } // public function update_avatar($request)


    public function edit_subscriptions()
    {
        $viewParams                          = $appParamsForJSArray = $this->getAppParameters(false,
            ['csrf_token', 'site_name', 'site_heading', 'site_subheading', 'account_register_subscriptions_text']);
        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');

        $userProfile               = Auth::user();
        $viewParams['userProfile'] = $userProfile;
        /*        $siteSubscriptionsList = [];
                $tempSiteSubscriptions = SiteSubscription
                    ::orderBy('name', 'asc')
                    ->getByActive(true)
                    ->get();

                $profileUsersSiteSubscriptions = UsersSiteSubscription
                    ::getByUserId($userProfile->id)
                    ->select(\DB::raw($this->users_site_subscriptions_tb.".*, ".$this->site_subscriptions_tb.".name as site_subscription_name, ".$this->site_subscriptions_tb.".vote_category_id, ".$this->vote_categories_tb.".name as vote_category_name"))
                    ->leftJoin($this->site_subscriptions_tb, $this->site_subscriptions_tb.'.id', '=', $this->users_site_subscriptions_tb.'.site_subscription_id')
                    ->leftJoin($this->vote_categories_tb, $this->vote_categories_tb.'.id', '=', $this->site_subscriptions_tb.'.vote_category_id')
                    ->orderBy('site_subscription_name', 'asc')
                    ->get();

                foreach ($tempSiteSubscriptions as $nextTempSiteSubscription) {
                    $is_checked              = false;
                    $next_vote_category_name = '';
                    foreach ($profileUsersSiteSubscriptions as $nextProfileUsersSiteSubscription) {
                        if ($nextTempSiteSubscription->id == $nextProfileUsersSiteSubscription->site_subscription_id) {
                            $is_checked = true;
                            break;
                        }
                    }
                    $siteSubscriptionsList[] = ['id'                 => $nextTempSiteSubscription->id,
                                                'name'               => $nextTempSiteSubscription->name,
                                                'vote_category_name' => $next_vote_category_name,
                                                'is_checked'         => $is_checked
                    ];
                }*/

        $viewParams['siteSubscriptionStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Site Subscription- '],
            SiteSubscription::getSiteSubscriptionActiveValueArray
            (false));

        $viewParams['userProfile'] = $userProfile;

//        $viewParams['siteSubscriptionsList']            = $siteSubscriptionsList;
        return view($this->getFrontendTemplateName() . '.profile.edit_subscriptions', $viewParams);
    } // public function edit_subscriptions()


    public function update_subscriptions(Request $request)
    {
        $userProfile = Auth::user();
        $requestData = $request->all();
        echo '<pre>$requestData::' . print_r($requestData, true) . '</pre>';

        $userSiteSubscriptionsDone = [];
        foreach ($requestData as $next_key => $next_value) { // get all parameters in request
            $a = $this->pregSplit('~checked_site_subscription_~', $next_key);
            if (count($a) == 1 and ((int)$next_value == 1) and is_numeric($a[0])) { // get all checked site_subscriptions
                echo '<pre>$a[0]::' . print_r($a[0], true) . '</pre>';
                $siteSubscription = SiteSubscription::find($a[0]);
                if ($siteSubscription !== null) {
                    echo '<pre>$siteSubscription->name::' . print_r($siteSubscription->name, true) . '</pre>';
//                    $ret= Newsletter::subscribeOrUpdate( 'fdewa@site.com', ['FNAME' => 'John', 'LNAME' => 'black'], 'subscribers', ['interests'=>['interestId'=>true, 'interestId'=>true]] );
                    //Newsletter::subscribeOrUpdate('rincewind@dscworld.com', ['firstName'=>'Rince','lastName'=>'Wind'], 'subscribers', ['interests'=>['interestId'=>true, 'interestId'=>true]])

//                    if ( !Newsletter::isSubscribed($userProfile->email) ) { // user was subscribed successfully
                    $retData = Newsletter::subscribe($userProfile->email, ['FNAME' => $userProfile->first_name, 'LNAME' => $userProfile->last_name],
                        $siteSubscription->name);
                    echo '<pre>$retData::' . print_r($retData, true) . '</pre>';
                    if ( ! empty($retData['id'])) {
                        $userSiteSubscriptionsDone[] = $a[0];
                        UsersSiteSubscription::create([
                            'site_subscription_id'      => $a[0],
                            'user_id'                   => $userProfile->id,
                            'mailchimp_subscription_id' => $retData['id'],
                        ]);
                        echo '<pre>INSIDE -4</pre>';
                    }
                } //if ( !Newsletter::isSubscribed($userProfile->email) ) { // user was subscribed successfully
//                }
            } //if (count($a) == 1 and ((int)$next_value == 1) and is_numeric($a[0])) { // get all checked site_subscriptions

        } // foreach ($requestData as $next_key => $next_value) { // get all parameters in request

        echo '<pre>$userSiteSubscriptionsDone::' . print_r($userSiteSubscriptionsDone, true) . '</pre>';
        foreach ($userProfile->usersSiteSubscriptions()->get() as $nextUsersSiteSubscription) {
            echo '<pre>$nextUsersSiteSubscription->site_subscription_id::' . print_r($nextUsersSiteSubscription->site_subscription_id, true) . '</pre>';
            if ( ! in_array($nextUsersSiteSubscription->site_subscription_id, $userSiteSubscriptionsDone)) { // this group must be unsubsribed
                $deletedSiteSubscription = SiteSubscription::find($nextUsersSiteSubscription->site_subscription_id);

                echo '<pre>$nextUsersSiteSubscription->site_subscription_id::' . print_r($nextUsersSiteSubscription->site_subscription_id, true) . '</pre>';
                if ($deletedSiteSubscription != null) {
                    Newsletter::delete($userProfile->email, $deletedSiteSubscription->name);
                    $usersSiteSubscriptionToDelete = UsersSiteSubscription
                        ::getByUserId($userProfile->id)
                        ->getBySiteSubscriptionId($nextUsersSiteSubscription->site_subscription_id)
                        ->first();
                    if ($usersSiteSubscriptionToDelete !== null) {
                        $usersSiteSubscriptionToDelete->delete();
                    }
                }
                //Newsletter::unsubscribe('rincewind@discworld.com', 'subscribers');

            }
//            $nextUsersSiteSubscription->delete();
        }
//        die("-1 XXZ00000");

        $this->setFlashMessage('Profile\'s subscriptions successfully updated !', 'success', 'Profile');

        return Redirect::route('profile-view');
    } // public function update_subscriptions(ProfileUserAvatarRequest $request)


    // USER'S RELATED SITE SUBSCRPTIONS BLOCK BEGIN
    public function get_related_users_site_subscriptions()
    {
        /*     Route::get( 'get-related-users-site-subscriptions', [ 'uses' => 'ProfileController@get_related_users_site_subscriptions' ] );
    Route::get( 'subscribe-users-site-subscription/{site_subscription_id}', [ 'uses' => 'ProfileController@subscribe_users_site_subscription' ] );
    Route::get( 'unsubscribe-users-site-subscription/{site_subscription_id}', [ 'uses' => 'ProfileController@unsubscribe_users_site_subscription' ] );
 */
        $userProfile = Auth::user();

        $siteSubscriptionsList = [];
        $tempSiteSubscriptions = SiteSubscription
            ::orderBy('name', 'asc')
            ->getByActive(true)
            ->get();

        $profileUsersSiteSubscriptions = UsersSiteSubscription
            ::getByUserId($userProfile->id)
            ->select($this->users_site_subscriptions_tb . ".*", $this->site_subscriptions_tb . ".name as site_subscription_name",
                $this->site_subscriptions_tb . ".vote_category_id", $this->vote_categories_tb . ".name as vote_category_name")
            ->leftJoin($this->site_subscriptions_tb, $this->site_subscriptions_tb . '.id', '=', $this->users_site_subscriptions_tb . '.site_subscription_id')
            ->leftJoin($this->vote_categories_tb, $this->vote_categories_tb . '.id', '=', $this->site_subscriptions_tb . '.vote_category_id')
            ->orderBy('site_subscription_name', 'asc')
            ->get();

        $subscribed_site_subscriptions_count = 0;
        foreach ($tempSiteSubscriptions as $nextTempSiteSubscription) {
            $is_checked              = false;
            $next_vote_category_name = '';
            foreach ($profileUsersSiteSubscriptions as $nextProfileUsersSiteSubscription) {
                if ($nextTempSiteSubscription->id == $nextProfileUsersSiteSubscription->site_subscription_id) {
                    $is_checked = true;
                    $subscribed_site_subscriptions_count++;
                    break;
                }
            }
            $siteSubscriptionsList[] = [
                'id'                 => $nextTempSiteSubscription->id,
                'name'               => $nextTempSiteSubscription->name,
                'vote_category_name' => $next_vote_category_name,
                'is_checked'         => $is_checked
            ];
        }

//        echo '<pre>$siteSubscriptionsList::'.print_r($siteSubscriptionsList,true).'</pre>';
//        die("-1 XXZ");

        $viewParamsArray                                        = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['userProfile']                         = $userProfile;
        $viewParamsArray['siteSubscriptionsList']               = $siteSubscriptionsList;
        $viewParamsArray['subscribed_site_subscriptions_count'] = $subscribed_site_subscriptions_count;

        $html = view($this->getFrontendTemplateName() . '.profile.related_users_site_subscriptions', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_related_users_site_subscriptions()

    public function subscribe_users_site_subscription($site_subscription_id)
    {
        $userProfile = Auth::user();

        $siteSubscription = SiteSubscription::find($site_subscription_id);
        if ($siteSubscription === null) {
            return response()->json(['error_code' => 11, 'message' => 'Site subscription # "' . $site_subscription_id . '" not found!', 'tag' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            $this->debToFile(print_r($siteSubscription->name, true), '  TEXT  -1 $siteSubscription->name::');
//            echo '<pre>$siteSubscription->name::' . print_r($siteSubscription->name, true) . '</pre>';
            $retData = Newsletter::subscribe($userProfile->email, ['FNAME' => $userProfile->first_name, 'LNAME' => $userProfile->last_name],
                $siteSubscription->name . '');
//            echo '<pre>$retData::' . print_r($retData, true) . '</pre>';
            $this->debToFile(print_r($retData, true), '  TEXT  -2 $retData::');
//            if ( ! empty($retData['id'])) {     // UNCOMMENT
            UsersSiteSubscription::create([
                'site_subscription_id'      => $site_subscription_id,
                'user_id'                   => $userProfile->id,
                'mailchimp_subscription_id' => $retData['id'],
            ]);
            $this->debToFile(print_r(4, true), '  INSIDE -4');
//            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'vote' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function subscribe_users_site_subscription( $site_subscription_id)


    public function unsubscribe_users_site_subscription($site_subscription_id)
    {
        $userProfile      = Auth::user();
        $siteSubscription = SiteSubscription::find($site_subscription_id);
        if ($siteSubscription === null) {
            return response()->json(['error_code' => 11, 'message' => 'Site subscription # "' . $site_subscription_id . '" not found!', 'tag' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        DB::beginTransaction();
        try {
            Newsletter::delete($userProfile->email, $siteSubscription->name);
            $usersSiteSubscriptionToDelete = UsersSiteSubscription
                ::getByUserId($userProfile->id)
                ->getBySiteSubscriptionId($site_subscription_id)
                ->first();
            if ($usersSiteSubscriptionToDelete !== null) {
                $usersSiteSubscriptionToDelete->delete();
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'vote' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
    } //     public function unsubscribe_users_site_subscription($userProfile->id, $site_subscription)

    // USER'S RELATED SITE SUBSCRPTIONS BLOCK END


    public function generate_password()
    {
        $userProfile = Auth::user();
        $site_name   = Settings::getValue('site_name');

        $generated_password = User::generatePassword();
        $hashed_password    = bcrypt($generated_password);

        DB::beginTransaction();
        try {
            $userProfile->password   = $hashed_password;
            $userProfile->updated_at = Carbon::now(config('app.timezone'));
            $ret                     = $userProfile->save();
            DB::commit();

            $additiveVars = [
                'to_user_email'          => $userProfile->email,
                'new_generated_password' => $generated_password,
                'to_user_name'           => $userProfile->username,
                'to_user_first_name'     => $userProfile->first_name,
                'to_user_last_name'      => $userProfile->last_name,

            ];
            $subject      = 'New password was generated at ' . $site_name . ' site ';
            \Mail::to($userProfile->email)->send(new SendgridMail('emails/password_was_generated', $userProfile->email, '', $subject, $additiveVars));

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error_code' => 1, 'message' => $e->getMessage(), 'user_id' => $userProfile->id], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['message' => 'New password was generated !', 'error_code' => 0], HTTP_RESPONSE_OK);
    } // public function generate_password()

    /*** PROFILE TO PDF BLOCK END  ***/


    /*** PROFILE TO PDF BLOCK START  ***/

    public function print_to_pdf()
    {
        $viewParams = $appParamsForJSArray = $this->getAppParameters(false, ['csrf_token', 'site_name', 'site_heading', 'site_subheading']);

        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');
        $userProfile                         = Auth::user();
        $request                             = Request();
        $pdfGenerationOptions                = config('app.pdfGenerationOptions');

        $background_color = ! empty($pdfGenerationOptions['background_color']) ? $pdfGenerationOptions['background_color'] : '#c7cc8c';

        $title_font_size    = ! empty($pdfGenerationOptions['title_font_size']) ? $pdfGenerationOptions['title_font_size'] : 32;
        $subtitle_font_size = ! empty($pdfGenerationOptions['subtitle_font_size']) ? $pdfGenerationOptions['subtitle_font_size'] : 24;
        $content_font_size  = ! empty($pdfGenerationOptions['content_font_size']) ? $pdfGenerationOptions['content_font_size'] : 18;
        $notes_font_size    = ! empty($pdfGenerationOptions['notes_font_size']) ? $pdfGenerationOptions['notes_font_size'] : 16;

        $title_font_color    = ! empty($pdfGenerationOptions['title_font_color']) ? $pdfGenerationOptions['title_font_color'] : '#1120eb';
        $subtitle_font_color = ! empty($pdfGenerationOptions['subtitle_font_color']) ? $pdfGenerationOptions['subtitle_font_color'] : '#644544';
        $content_font_color  = ! empty($pdfGenerationOptions['content_font_color']) ? $pdfGenerationOptions['content_font_color'] : '#8061be';
        $notes_font_color    = ! empty($pdfGenerationOptions['notes_font_color']) ? $pdfGenerationOptions['notes_font_color'] : '#8d8d6a';

        $title_font_name    = ! empty($pdfGenerationOptions['title_font_name']) ? $pdfGenerationOptions['title_font_name'] : 'courier';
        $subtitle_font_name = ! empty($pdfGenerationOptions['subtitle_font_name']) ? $pdfGenerationOptions['subtitle_font_name'] : 'helvetica';
        $content_font_name  = ! empty($pdfGenerationOptions['content_font_name']) ? $pdfGenerationOptions['content_font_name'] : 'symbol';
        $notes_font_name    = ! empty($pdfGenerationOptions['notes_font_name']) ? $pdfGenerationOptions['notes_font_name'] : 'times';
        /*             'courier'        => 'Courier',
            'courierB'       => 'Courier Bold',
            'courierBI'      => 'courierBI',
            'courierI'       => 'courierI',
            'helvetica'      => 'helvetica',
            'symbol'         => 'Symbol',
            'times'          => 'Times New Roman',
            'zapfdingbats'   => 'Zapf Dingbats',
 */

        $fontsList         = ! empty($pdfGenerationOptions['fontsList']) ? $this->SetArrayHeader(['' => ' -Select Font- '],
            $pdfGenerationOptions['fontsList']) : [];
        $fontSizeItemsList = ! empty($pdfGenerationOptions['fontSizeItems']) ? $this->SetArrayHeader(['' => ' -Select Font Size- '],
            $pdfGenerationOptions['fontSizeItems']) : [];

        if ($request->isMethod('post')) {
            $requestData = $request->all();
//            echo '<pre>$requestData::' . print_r($requestData, true) . '</pre>';

            $background_color   = ! empty($requestData['background_color']) ? $requestData['background_color'] : $background_color;
            $title_font_size    = ! empty($requestData['title_font_size']) ? $requestData['title_font_size'] : $title_font_size;
            $subtitle_font_size = ! empty($requestData['subtitle_font_size']) ? $requestData['subtitle_font_size'] : $subtitle_font_size;
            $content_font_size  = ! empty($requestData['content_font_size']) ? $requestData['content_font_size'] : $content_font_size;
            $notes_font_size    = ! empty($requestData['notes_font_size']) ? $requestData['notes_font_size'] : $notes_font_size;

            $title_font_color    = ! empty($requestData['title_font_color']) ? $requestData['title_font_color'] : $title_font_color;
            $subtitle_font_color = ! empty($requestData['subtitle_font_color']) ? $requestData['subtitle_font_color'] : $subtitle_font_color;
            $content_font_color  = ! empty($requestData['content_font_color']) ? $requestData['content_font_color'] : $content_font_color;
            $notes_font_color    = ! empty($requestData['notes_font_color']) ? $requestData['notes_font_color'] : $notes_font_color;

            $title_font_name    = ! empty($requestData['title_font_name']) ? $requestData['title_font_name'] : $title_font_name;
            $subtitle_font_name = ! empty($requestData['subtitle_font_name']) ? $requestData['subtitle_font_name'] : $subtitle_font_name;
            $content_font_name  = ! empty($requestData['content_font_name']) ? $requestData['content_font_name'] : $content_font_name;
            $notes_font_name    = ! empty($requestData['notes_font_name']) ? $requestData['notes_font_name'] : $notes_font_name;

            /*            $("#background_color").val( $("#option_background_color").val().toHexString() )

    $("#title_font_name").val( $("#title_font_name").val() )
    $("#title_font_size").val( $("#option_title_font_size").val() )
    $("#title_font_color").val( $("#option_title_font_color").val() )

    $("#subtitle_font_name").val( $("#option_subtitle_font_name").val() )
    $("#subtitle_font_size").val( $("#option_subtitle_font_size").val() )
    $("#subtitle_font_color").val( $("#option_subtitle_font_color").val() )

    $("#content_font_name").val( $("#option_content_font_name").val() )
    $("#content_font_size").val( $("#option_content_font_size").val() )
    $("#content_font_color").val( $("#option_content_font_color").val() )

    $("#notes_font_name").val( $("#option_notes_font_name").val() )
    $("#notes_font_size").val( $("#option_notes_font_size").val() )
    $("#notes_font_color").val( $("#option_notes_font_color").val() )
 */
//            $title_font_size    = 24;
//            $subtitle_font_size = 20;
//            $content_font_size  = 16;
//            $notes_font_size    = 14;
//            echo '<pre>++$_POST::'.print_r($_POST,true).'</pre>';
//            echo '<pre>++$_REQUEST::'.print_r($_REQUEST,true).'</pre>';

//            die("-1 XXZ");
        } else {
            $title_font_size    = 24;
            $subtitle_font_size = 20;
            $content_font_size  = 16;
            $notes_font_size    = 14;

        }

        $viewParams['userProfile']     = $userProfile;
        $profileUsersSiteSubscriptions = UsersSiteSubscription
            ::getByUserId($userProfile->id)
            ->select($this->users_site_subscriptions_tb . ".*", $this->site_subscriptions_tb . ".name as site_subscription_name",
                $this->site_subscriptions_tb . ".vote_category_id", $this->vote_categories_tb . ".name as vote_category_name")
            ->leftJoin($this->site_subscriptions_tb, $this->site_subscriptions_tb . '.id', '=', $this->users_site_subscriptions_tb . '.site_subscription_id')
            ->leftJoin($this->vote_categories_tb, $this->vote_categories_tb . '.id', '=', $this->site_subscriptions_tb . '.vote_category_id')
            ->orderBy('site_subscription_name', 'asc')
            ->get();
        $avatarData                    = User::setUserAvatarProps($userProfile->id, $userProfile->avatar, true);
        $fullPhotoData                 = User::setUserFullPhotoProps($userProfile->id, $userProfile->full_photo, true);

        // 'storage/app/' . $dir_path . '/' . $full_photo;
        $attachedImages     = [];
        $tempAttachedImages = [
            [
                'attached_image_url' => '/storage/attached-images/about.jpg',
                'text'               => '<p>1) But I must  <strong>explain</strong> to you how all this mistaken idea of denouncing  pleasure and praising pain was born and I will give you a complete  account of the system, and expound the actual teachings of the great  explorer of the truth, the master-builder of human happiness. No one  rejects, dislikes, or avoids pleasure itself, because it is pleasure,  but because those who do not know how to pursue pleasure rationally  encounter consequences that are extremely painful. Nor again is there  anyone who loves or pursues or desires to obtain pain of itself, because  it is pain, but because occasionally circumstances occur in which toil  and pain can procure him some great pleasure. To take a trivial example,  which of us ever undertakes laborious physical exercise, except to  obtain some advantage from it? But who has any right to find fault with a  man who chooses to enjoy a pleasure that has no annoying consequences,  or one who avoids a pain that produces no resultant pleasure?</p><p>2) But I must <strong>explain</strong> to you how all this mistaken idea of denouncing  pleasure and praising pain was born and I will give you a complete  account of the system, and expound the actual teachings of the great  explorer of the truth, the master-builder of human happiness. No one  rejects, dislikes, or avoids pleasure itself, because it is pleasure,  but because those who do not know how to pursue pleasure rationally  encounter consequences that are extremely painful. Nor again is there  anyone who loves or pursues or desires to obtain pain of itself, because  it is pain, but because occasionally circumstances occur in which toil  and pain can procure him some great pleasure. To take a trivial example,  which of us ever undertakes laborious physical exercise, except to  obtain some advantage from it? But who has any right to find fault with a  man who chooses to enjoy a pleasure that has no annoying consequences,  or one who avoids a pain that produces no resultant pleasure?</p><p>3) But I must <strong>explain</strong> to you how all this mistaken idea of denouncing  pleasure and praising pain was born and I will give you a complete  account of the system, and expound the actual teachings of the great  explorer of the truth, the master-builder of human happiness. No one  rejects, dislikes, or avoids pleasure itself, because it is pleasure,  but because those who do not know how to pursue pleasure rationally  encounter consequences that are extremely painful. Nor again is there  anyone who loves or pursues or desires to obtain pain of itself, because  it is pain, but because occasionally circumstances occur in which toil  and pain can procure him some great pleasure. To take a trivial example,  which of us ever undertakes laborious physical exercise, except to  obtain some advantage from it? But who has any right to find fault with a  man who chooses to enjoy a pleasure that has no annoying consequences,  or one who avoids a pain that produces no resultant pleasure?</p>'
            ],
            [
                'attached_image_url' => '/storage/attached-images/slogan_1.jpg',
                'text'               => '<p>4) But I must  <strong>explain</strong> to you how all this mistaken idea of denouncing  pleasure and praising pain was born and I will give you a complete  account of the system, and expound the actual teachings of the great  explorer of the truth, the master-builder of human happiness. No one  rejects, dislikes, or avoids pleasure itself, because it is pleasure,  but because those who do not know how to pursue pleasure rationally  encounter consequences that are extremely painful. Nor again is there  anyone who loves or pursues or desires to obtain pain of itself, because  it is pain, but because occasionally circumstances occur in which toil  and pain can procure him some great pleasure. To take a trivial example,  which of us ever undertakes laborious physical exercise, except to  obtain some advantage from it? But who has any right to find fault with a  man who chooses to enjoy a pleasure that has no annoying consequences,  or one who avoids a pain that produces no resultant pleasure?</p><p>5)But I must <strong>explain</strong> to you how all this mistaken idea of denouncing  pleasure and praising pain was born and I will give you a complete  account of the system, and expound the actual teachings of the great  explorer of the truth, the master-builder of human happiness. No one  rejects, dislikes, or avoids pleasure itself, because it is pleasure,  but because those who do not know how to pursue pleasure rationally  encounter consequences that are extremely painful. Nor again is there  anyone who loves or pursues or desires to obtain pain of itself, because  it is pain, but because occasionally circumstances occur in which toil  and pain can procure him some great pleasure. To take a trivial example,  which of us ever undertakes laborious physical exercise, except to  obtain some advantage from it? But who has any right to find fault with a  man who chooses to enjoy a pleasure that has no annoying consequences,  or one who avoids a pain that produces no resultant pleasure?</p>'
            ],
            [
                'attached_image_url' => '/storage/attached-images/palyfull.jpeg',
                'text'               => '<p>6)But I must  <strong>explain</strong> to you how all this mistaken idea of denouncing  pleasure and praising pain was born and I will give you a complete  account of the system, and expound the actual teachings of the great  explorer of the truth, the master-builder of human happiness. No one  rejects, dislikes, or avoids pleasure itself, because it is pleasure,  but because those who do not know how to pursue pleasure rationally  encounter consequences that are extremely painful. Nor again is there  anyone who loves or pursues or desires to obtain pain of itself, because  it is pain, but because occasionally circumstances occur in which toil  and pain can procure him some great pleasure. To take a trivial example,  which of us ever undertakes laborious physical exercise, except to  obtain some advantage from it? But who has any right to find fault with a  man who chooses to enjoy a pleasure that has no annoying consequences,  or one who avoids a pain that produces no resultant pleasure?</p><p>7)But I must <strong>explain</strong> to you how all this mistaken idea of denouncing  pleasure and praising pain was born and I will give you a complete  account of the system, and expound the actual teachings of the great  explorer of the truth, the master-builder of human happiness. No one  rejects, dislikes, or avoids pleasure itself, because it is pleasure,  but because those who do not know how to pursue pleasure rationally  encounter consequences that are extremely painful. Nor again is there  anyone who loves or pursues or desires to obtain pain of itself, because  it is pain, but because occasionally circumstances occur in which toil  and pain can procure him some great pleasure. To take a trivial example,  which of us ever undertakes laborious physical exercise, except to  obtain some advantage from it? But who has any right to find fault with a  man who chooses to enjoy a pleasure that has no annoying consequences,  or one who avoids a pain that produces no resultant pleasure?</p><p>8) But I must <strong>explain</strong>  to you how all this mistaken idea of denouncing  pleasure and praising pain was born and I will give you a complete  account of the system, and expound the actual teachings of the great  explorer of the truth, the master-builder of human happiness. No one  rejects, dislikes, or avoids pleasure itself, because it is pleasure,  but because those who do not know how to pursue pleasure rationally  encounter consequences that are extremely painful. Nor again is there  anyone who loves or pursues or desires to obtain pain of itself, because  it is pain, but because occasionally circumstances occur in which toil  and pain can procure him some great pleasure. To take a trivial example,  which of us ever undertakes laborious physical exercise, except to  obtain some advantage from it? But who has any right to find fault with a  man who chooses to enjoy a pleasure that has no annoying consequences,  or one who avoids a pain that produces no resultant pleasure?</p>'
            ]
        ];
//        $tempAttachedImages= [ 'storage/app/attached-images/about.jpg', 'storage/app/attached-images/slogan_1.jpg', 'storage/app/attached-images/palyfull.jpeg' ];
        foreach ($tempAttachedImages as $nextTempAttachedImage) {
//            echo '<pre>$nextTempAttachedImage::'.print_r($nextTempAttachedImage,true).'</pre>';
//            die("-1 XXZ");
            $attachedImages[] = $nextTempAttachedImage;
        }

        $viewParams['title_font_size']    = $title_font_size;
        $viewParams['subtitle_font_size'] = $subtitle_font_size;
        $viewParams['content_font_size']  = $content_font_size;
        $viewParams['notes_font_size']    = $notes_font_size;

        $viewParams['background_color']    = $background_color;
        $viewParams['title_font_color']    = $title_font_color;
        $viewParams['subtitle_font_color'] = $subtitle_font_color;
        $viewParams['content_font_color']  = $content_font_color;
        $viewParams['notes_font_color']    = $notes_font_color;


        $viewParams['title_font_name']               = $title_font_name;
        $viewParams['subtitle_font_name']            = $subtitle_font_name;
        $viewParams['content_font_name']             = $content_font_name;
        $viewParams['notes_font_name']               = $notes_font_name;
        $viewParams['userProfile']                   = $userProfile;
        $viewParams['profileUsersSiteSubscriptions'] = $profileUsersSiteSubscriptions;
        $viewParams['avatar_dimension_limits']       = \Config::get('app.avatar_dimension_limits', ['max_width' => 32, 'max_height' => 32]);
        $viewParams['full_photo_dimension_limits']   = \Config::get('app.full_photo_dimension_limits', ['max_width' => 256, 'max_height' => 256]);
        $viewParams['avatarData']                    = $avatarData;
        $viewParams['fullPhotoData']                 = $fullPhotoData;
        $viewParams['attachedImages']                = $attachedImages;
//        echo '<pre>$::'.print_r($appParamsForJSArray,true).'</pre>';
//        die("-1 XXZ");
        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['outputFileFormatsList'] = ! empty($pdfGenerationOptions['outputFileFormatsList']) ? $this->SetArrayHeader(['' => ' -Select File Format- '],
            $pdfGenerationOptions['outputFileFormatsList']) : [];

//        echo '<pre>$viewParams::'.print_r($viewParams,true).'</pre>';
//      die("-1 XXZ");


        return view($this->getFrontendTemplateName() . '.profile.print_to_pdf', $viewParams);
    } // public function print_to_pdf()

    public function print_to_pdf_options()
    {
        $viewParams           = $appParamsForJSArray = $this->getAppParameters(false, ['site_name', 'site_heading', 'site_subheading']);
        $pdfGenerationOptions = config('app.pdfGenerationOptions');
        $page_margin_left     = 1;
        $page_margin_top      = 1;
        $page_margin_right    = 1;
        $page_margin_bottom   = 1;

        $viewParams['title_font_size']    = ! empty($pdfGenerationOptions['title_font_size']) ? $pdfGenerationOptions['title_font_size'] : 32;
        $viewParams['subtitle_font_size'] = ! empty($pdfGenerationOptions['subtitle_font_size']) ? $pdfGenerationOptions['subtitle_font_size'] : 24;
        $viewParams['content_font_size']  = ! empty($pdfGenerationOptions['content_font_size']) ? $pdfGenerationOptions['content_font_size'] : 18;
        $viewParams['notes_font_size']    = ! empty($pdfGenerationOptions['notes_font_size']) ? $pdfGenerationOptions['notes_font_size'] : 16;

        $viewParams['background_color']    = ! empty($pdfGenerationOptions['background_color']) ? $pdfGenerationOptions['background_color'] : '#c7cc8c';
        $viewParams['title_font_color']    = ! empty($pdfGenerationOptions['title_font_color']) ? $pdfGenerationOptions['title_font_color'] : '#1120eb';
        $viewParams['subtitle_font_color'] = ! empty($pdfGenerationOptions['subtitle_font_color']) ? $pdfGenerationOptions['subtitle_font_color'] : '#644544';
        $viewParams['content_font_color']  = ! empty($pdfGenerationOptions['content_font_color']) ? $pdfGenerationOptions['content_font_color'] : '#8061be';
        $viewParams['notes_font_color']    = ! empty($pdfGenerationOptions['notes_font_color']) ? $pdfGenerationOptions['notes_font_color'] : '#8d8d6a';


        $viewParams['title_font_name']    = ! empty($pdfGenerationOptions['title_font_name']) ? $pdfGenerationOptions['title_font_name'] : 'courier';
        $viewParams['subtitle_font_name'] = ! empty($pdfGenerationOptions['subtitle_font_name']) ? $pdfGenerationOptions['subtitle_font_name'] : 'helvetica';
        $viewParams['content_font_name']  = ! empty($pdfGenerationOptions['content_font_name']) ? $pdfGenerationOptions['content_font_name'] : 'symbol';
        $viewParams['notes_font_name']    = ! empty($pdfGenerationOptions['notes_font_name']) ? $pdfGenerationOptions['notes_font_name'] : 'times';

        $viewParams['fontsList']         = ! empty($pdfGenerationOptions['fontsList']) ? $this->SetArrayHeader(['' => ' -Select Font- '],
            $pdfGenerationOptions['fontsList']) : [];
        $viewParams['fontSizeItemsList'] = ! empty($pdfGenerationOptions['fontSizeItems']) ? $this->SetArrayHeader(['' => ' -Select Font Size- '],
            $pdfGenerationOptions['fontSizeItems']) : [];

        $viewParams['page_margin_left']   = ! empty($pdfGenerationOptions['page_margin_left']) ? $pdfGenerationOptions['page_margin_left'] : $page_margin_left;
        $viewParams['page_margin_top']    = ! empty($pdfGenerationOptions['page_margin_top']) ? $pdfGenerationOptions['page_margin_top'] : $page_margin_top;
        $viewParams['page_margin_right']  = ! empty($pdfGenerationOptions['page_margin_right']) ? $pdfGenerationOptions['page_margin_right'] : $page_margin_right;
        $viewParams['page_margin_bottom'] = ! empty($pdfGenerationOptions['page_margin_bottom']) ? $pdfGenerationOptions['page_margin_bottom'] : $page_margin_bottom;


        $html = view($this->getFrontendTemplateName() . '.profile.print_to_pdf_options', $viewParams)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);

    } // public function print_to_pdf_options()


    public function generate_pdf_by_content()
    {
        $request = request();

        $requestData               = $request->all();
        $pdf_content               = ! empty($requestData['pdf_content']) ? $requestData['pdf_content'] : '';
        $option_output_filename    = ! empty($requestData['option_output_filename']) ? $requestData['option_output_filename'] : '';
        $option_output_file_format = ! empty($requestData['option_output_file_format']) ? $requestData['option_output_file_format'] : '';
        $a                         = $this->pregSplit('/\./', $option_output_filename);
        if (count($a) == 2) {
            $option_output_filename = $a[0];
        }

        if (empty($option_output_filename)) {
            $loggedUser             = Auth::user();
            $option_output_filename = $loggedUser->username;
        }
        $option_output_filename = $this->safeFilename($option_output_filename);

        $filename_to_save = $option_output_filename . '.' . $option_output_file_format;
        $save_to_file     = 'generate_profile_card_' . Session::getId() . '_' . $filename_to_save;

        if (strtolower($option_output_file_format) == 'pdf') {
            Browsershot::html(htmlspecialchars_decode($pdf_content))
                       ->showBackground()
                       ->save($save_to_file);
            \Response::download($save_to_file, $save_to_file, array('Content-Type: application/octet-stream', 'Content-Length: ' . $option_output_file_format));

            return response()->download($save_to_file, $filename_to_save)->deleteFileAfterSend(true);
        } else {
            Browsershot::html(htmlspecialchars_decode($pdf_content))
                       ->fullPage()
                       ->save($save_to_file);
            \Response::download($save_to_file, $save_to_file, array('Content-Type: application/octet-stream', 'Content-Length: pdf'));

            return response()->download($save_to_file, $filename_to_save)->deleteFileAfterSend(true);
        }
    }


    public function get_user_mail_chimp_info()
    {
        $loggedUser             = Auth::user();
        $userMailChimpInfoArray = Newsletter::getMember($loggedUser->email);

        return response()->json(['error_code' => 0, 'message' => '', 'userMailChimpInfo' => $userMailChimpInfoArray], HTTP_RESPONSE_OK);
    }


    // USER'S PAYMENTS BLOCK BEGIN
    public function profile_payments()
    {
//        die("-1 XXZ  profile_payments");
        $viewParams = $appParamsForJSArray = $this->getAppParameters(false,
            ['csrf_token', 'site_name', 'site_heading', 'site_subheading', 'account_register_avatar_text']);

        $appParamsForJSArray['start_date_formatted'] = config('app.calendar_events_default_date');
//        echo '<pre>$appParamsForJSArray[\'start_date_formatted\']::' . print_r($appParamsForJSArray['start_date_formatted'], true) . '</pre>';

        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');

        $userProfile               = Auth::user();
        $viewParams['userProfile'] = $userProfile;

        return view($this->getFrontendTemplateName() . '.profile.payments', $viewParams);
    } // public function profile_payments()


    public function load_payment_agreements()
    {
        $userProfile                          = Auth::user();
        $paymentAgreements                    = PaymentAgreement
            ::getByUserId($userProfile->id)
            ->orderBy('created_at', 'asc')
            ->get();
        $viewParamsArray                      = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['paymentAgreements'] = $paymentAgreements;

        $html = view($this->getFrontendTemplateName() . '.profile.load_payment_agreements', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function load_payment_agreements()

//     Route::get( 'load_payment_agreement_details/{payment_agreement_id}', [ 'uses' => 'ProfileController@load_payment_agreement_details' ] );
// http://local-votes.com/profile/load_payment_agreement_details/1
    public function load_payment_agreement_details($payment_agreement_id)
    {
        /* PaypalAgreement
         public function agreementDetail($agreement_id) { // http://paypal.github.io/PayPal-PHP-SDK/sample/doc/billing/GetBillingAgreement.html
 */
        $userProfile             = Auth::user();
        $plan                    = new PaypalAgreement($userProfile);
        $paymentAgreementDetails = $plan->agreementDetail($payment_agreement_id);

        $viewParamsArray                            = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['userProfile']             = $userProfile;
        $viewParamsArray['paymentAgreementDetails'] = $paymentAgreementDetails;


        $html = view($this->getFrontendTemplateName() . '.profile.load_payment_agreement_details', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);

        //        echo '<pre>$paymentAgreementDetails::'.print_r($paymentAgreementDetails,true).'</pre>';
//        $paypalPlans      = $listPlan->plans;
//        $this->debToFile(print_r($paypalPlans, true), '  SubscriptionPlan::delete  - $delete_plan_result::');

//        return response()->json( ['error_code' => 0, 'message' => '', 'paymentAgreementDetails' => $paymentAgreementDetails ], HTTP_RESPONSE_OK );
    } // public function load_payment_agreement_details($payment_agreement_id)


    public function get_payment_items_rows()
    {
        $ref_items_per_pagination = 10;

        $request            = request();
        $page               = $request->input('page');
        $filter_download_id = $request->input('filter_download_id');
        $userProfile        = Auth::user();

        $total_rows = PaymentItem
            ::getByUserId($userProfile->id)
            ->join($this->payments_tb, $this->payments_tb . '.id', '=', $this->payment_items_tb . '.payment_id')
            ->getByItemId($filter_download_id)
            ->count();

        $paymentItems = PaymentItem
            ::getByStatus('C', 'payments')
            ->getByItemId($filter_download_id)
            ->getByUserId($userProfile->id)
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
            ->orderBy('payed_item_title', 'asc')
            ->orderBy($this->payment_items_tb . '.created_at', 'desc')
            ->join($this->payments_tb, $this->payments_tb . '.id', '=', $this->payment_items_tb . '.payment_id')
            ->join($this->users_tb, $this->users_tb . '.id', '=', $this->payments_tb . '.user_id')
            ->join($this->downloads_tb, $this->downloads_tb . '.id', '=', $this->payment_items_tb . '.item_id')
            ->paginate($ref_items_per_pagination, null, null, $page)
            ->onEachSide((int)($ref_items_per_pagination / 2));

        $viewParamsArray                        = $appParamsForJSArray = $this->getAppParameters(true, ['csrf_token']);
        $viewParamsArray['paymentItems']        = $paymentItems;
        $viewParamsArray['total_rows']          = $total_rows;
        $viewParamsArray['filter_download_id']  = $filter_download_id;
        $viewParamsArray['downloadsValueArray'] = $this->SetArrayHeader(['' => ' -All Items- '], Download::getDownloadsSelectionArray());
        $html                                   = view($this->getFrontendTemplateName() . '.profile.payments_rows', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_payment_items_rows()


    // USER'S PAYMENTS BLOCK END


    // USER'S PAYMENTS PACKAGE BLOCK START
    public function profile_select_service_subscription()
    {
        $viewParams                          = $appParamsForJSArray = $this->getAppParameters(false,
            ['csrf_token', 'site_name', 'site_heading', 'site_subheading', 'account_select_service_subscription_text']);
        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');

        $userProfile = Auth::user();


//        echo '<pre>$userProfile->subscriptions::'.print_r($userProfile->subscriptions,true).'</pre>';
        $viewParams['userProfile'] = $userProfile;
        $serviceSubscriptionsList       = ServiceSubscription
            ::orderBy('subscription_weight', 'asc')
            ->getByActive(true)
            ->get()
            ->map(function ($item) use ($userProfile) {
                foreach ($userProfile->subscriptions as $nextUserProfileSubscription) {
                    if ($nextUserProfileSubscription->source_service_subscription_id == $item->id and $nextUserProfileSubscription->stripe_status == 'active') {
                        $item->is_subscribed = true;
                        break;
                    }
                }

                return $item;
            })
            ->all();

        $viewParams['serviceSubscriptionsList']              = $serviceSubscriptionsList;
        $viewParams['siteSubscriptionStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Site Subscription- '],
            SiteSubscription::getSiteSubscriptionActiveValueArray
            (false));

        $viewParams['userProfile']   = $userProfile;
        $viewParams['currency_sign'] = $this->getCurrency('sign');

        return view($this->getFrontendTemplateName() . '.profile.profile_select_service_subscription', $viewParams);
    } // public function profile_select_service_subscription()

    public function profile_confirm_subscribe_to_service_subscription($service_subscription_id)
    {
        $viewParams   = $appParamsForJSArray = $this->getAppParameters(false,
            ['csrf_token', 'site_name', 'site_heading', 'site_subheading', 'account_select_service_subscription_text']);
        $stripeConfig = config('services.stripe');
        $userProfile  = Auth::user();

        $appParamsForJSArray['stripe_public_key'] = $stripeConfig['key'];

        $appParamsForJSArray['userProfile']              = $userProfile;
        $appParamsForJSArray['userProfile']['full_name'] = $userProfile->full_name;
        $appParamsForJSArray['service_subscription_id']       = $service_subscription_id;

        if (empty($appParamsForJSArray['stripe_public_key'])) {
            return redirect()->route('home-msg', [])->with([
                'text'   => 'Stripe is not properly configured !',
                'type'   => 'danger',
                'action' => ''
            ]);
        }
        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');

        $viewParams['userProfile'] = $userProfile;

        $viewParams['selectedServiceSubscription'] = ServiceSubscription::find($service_subscription_id);
        if (empty($viewParams['selectedServiceSubscription'])) {
            return redirect()->route('home-msg', [])->with([
                'text'   => 'Payment package not found !',
                'type'   => 'danger',
                'action' => ''
            ]);
        }


        $viewParams['siteSubscriptionStatusValueArray'] = $this->SetArrayHeader(['' => ' -Select Site Subscription- '],
            SiteSubscription::getSiteSubscriptionActiveValueArray
            (false));

        $viewParams['userProfile']        = $userProfile;
        $viewParams['service_subscription_id'] = $service_subscription_id;
        $viewParams['currency_sign']      = $this->getCurrency('sign');

//        $viewParams['siteSubscriptionsList']            = $siteSubscriptionsList;
        return view($this->getFrontendTemplateName() . '.profile.profile_confirm_subscribe_to_service_subscription', $viewParams);
    } // public function profile_confirm_subscribe_to_service_subscription()


    public function profile_subscribe_to_service_subscription_paypal(Request $request)
    {
        $userProfile = Auth::user();
        $requestData = $request->all();
        echo '<pre>profile_subscribe_to_service_subscription_paypal $requestData::'.print_r($requestData,true).'</pre>';
        $plan_id= 'P-0U478222LP387231WHIR4RWQ';
        $agreement= new PaypalAgreement($plan_id);
        return $agreement->create($plan_id);

    }
    public function profile_subscribe_to_service_subscription(Request $request)
    {

        $userProfile = Auth::user();
        $requestData = $request->all();
        $this->debToFile(print_r($requestData, true), '  profile_subscribe_to_service_subscription  -33 $requestData::');

        $source_service_subscription_id = ! empty($requestData['purchaseDetails']['source_service_subscription_id']) ? $requestData['purchaseDetails']['source_service_subscription_id'] : '';
        $sourceServiceSubscription      = ServiceSubscription::find($source_service_subscription_id);
        if (empty($sourceServiceSubscription)) {
            return response()->json([
                'error_code'                => 11,
                'message'                   => 'Payment package # "' . $source_service_subscription_id . '" not found!',
                'source_service_subscription_id' => $source_service_subscription_id
            ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        $stripeConfig = config('services.stripe');
        $this->debToFile(print_r($stripeConfig['secret'], true), '  profile_subscribe_to_service_subscription  -34 $stripeConfig[\'secret\']::');

        $profileSubscription = Subscription
            ::getByUserId($userProfile->id)
            ->getByStripeStatus('active')
            ->first();
        if ( ! empty($profileSubscription)) {
            return response()->json([
                'error_code'                => 11,
                'message'                   => 'You are already subscribed to "' . $profileSubscription->name . '" service ! Cancel it firstly to make new subscription !',
                'source_service_subscription_id' => $source_service_subscription_id
            ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        $subscription_days_count       = config('app.subscription_days_count', 31);
        $free_subscription_days_count  = config('app.free_subscription_days_count', 31);
        $subscription_product_name     = config('app.subscription_product_name');

        DB::beginTransaction();
        try {
            Stripe::setApiKey($stripeConfig['secret']);
            $user = User::find($userProfile->id);


            $newSubscription = $user->newSubscription($subscription_product_name,
                $sourceServiceSubscription->stripe_plan_id)->create($requestData['purchaseDetails']['payment_token']);


            $this->debToFile(print_r($newSubscription, true), '  profile_subscribe_to_service_subscription  -37 $newSubscription::');

            $newSubscription->source_service_subscription_id = $requestData['purchaseDetails']['source_service_subscription_id'];
            $newSubscription->is_free                   = $sourceServiceSubscription->is_free;
            if ( $sourceServiceSubscription->is_free ) {
                $newSubscription->trial_ends_at = Carbon::now()->addDays($free_subscription_days_count);
            } else {
                $newSubscription->ends_at = Carbon::now()->addDays($subscription_days_count);
            }
            $newSubscription->save();
            $this->debToFile(' Subscription successful, you get the course!::');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();

            return response()->json(['error_code' => 11, 'message' => 'Error subscribing payment package : "' . $ex->getMessage()],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => '', 'requestData' => $requestData], HTTP_RESPONSE_OK);

    }   // profile_subscribe_to_service_subscription

    public function profile_cancel_service_subscription_subscription(Request $request)
    {

        $userProfile = Auth::user();
        $requestData = $request->all();
//        $this->debToFile(print_r($requestData, true), '  profile_cancel_service_subscription_subscription  -33 $requestData::');

        $source_service_subscription_id = ! empty($requestData['source_service_subscription_id']) ? $requestData['source_service_subscription_id'] : '';
        $sourceServiceSubscription      = ServiceSubscription::find($source_service_subscription_id);
        if (empty($sourceServiceSubscription)) {
            return response()->json([
                'error_code'                => 11,
                'message'                   => 'Payment package # "' . $source_service_subscription_id . '" not found!',
                'source_service_subscription_id' => $source_service_subscription_id
            ], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }


        $stripeConfig = config('services.stripe');
        $this->debToFile(print_r($sourceServiceSubscription, true), '  profile_cancel_service_subscription_subscription  -34 $sourceServiceSubscription::');
        $subscription_product_name  = config('app.subscription_product_name');

        DB::beginTransaction();
        try {
            Stripe::setApiKey($stripeConfig['secret']);


//            $ret= $userProfile->subscription($sourceServiceSubscription->stripe_plan_id)->cancelNow();
            $ret = $userProfile->subscription($subscription_product_name)->cancelNow();
            $this->debToFile(print_r($ret, true), '  profile_cancel_service_subscription_subscription  -30 $ret::');

            DB::commit();
//            return 'Subscription successful, you get the course!';
        } catch (\Exception $ex) {
            DB::rollback();

            return response()->json(['error_code' => 11, 'message' => 'Error subscribing payment package : "' . $ex->getMessage()],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['error_code' => 0, 'message' => '', 'requestData' => $requestData], HTTP_RESPONSE_OK);

    }   // profile_cancel_service_subscription_subscription

    /**
     * Cancel the subscription immediately.
     *
     * @return $this
     */
/*    public function cancelNow() // https://stackoverflow.com/questions/48213230/how-does-laravel-handle-cancelling-stripe-subscriptions
    {
        $subscription = $this->asStripeSubscription();

        $subscription->cancel();

        $this->markAsCancelled();

        return $this;
    }*/

    // USER'S PAYMENTS PACKAGE BLOCK END

}