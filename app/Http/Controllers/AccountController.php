<?php

namespace App\Http\Controllers;

use App\MyAppModel;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Input;
use ImageOptimizer;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\ActivityLog;
use App\Mail\SendgridMail;

//use App\Exceptions\UserNotVerifiedException;
// app/Exceptions/UserNotVerifiedException.php
use App\Settings;
use App\User;
use App\UserGroup;
use App\Vote;
use App\SiteSubscription;
use App\UsersSiteSubscription;

//use App\VoteCategory;
//use App\UsersSiteSubscription;
use App\Http\Requests\AccountUserRegisterDetailsRequest;
use App\Http\Traits\funcsTrait;

//use App\library\BeautymailWrapper;

class AccountController extends MyAppController
{    //  http://local-votes.com/account/register/details

    use funcsTrait;

    use RegistersUsers;
    use VerifiesUsers;

    private $register_session_key = 'votes_register';
    private $session_id = '';


    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getVerification', 'getVerificationError']]);
    }

    /*** 1st STEP - USER DETAILS BLOCK START  ***/
    public function getDetails()
    {
        if (empty($this->session_id)) {
            $this->session_id = Session::getId();
        }


        $viewParams = $appParamsForJSArray = $this->getAppParameters(false, /*[],*/
            ['site_name', 'account_register_details_text']);
//        echo '<pre>$viewParams::'.print_r($viewParams,true).'</pre>';
//        die("-1 XXZ");
        $viewParams['appParamsForJSArray']    = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url']  = config('app.medium_slogan_img_url');
        $viewParams['userSexLabelValueArray'] = $this->SetArrayHeader(['' => ' -Select Sex- '], User::getUserSexValueArray(false));
        if ($this->isDeveloperComp()) {
            $defaultValues = [
                'username'   => 'JackParrot',
                'email'      => 'JackParrot@mail.com',
                'first_name' => 'Jack',
                'last_name'  => 'Parrot',
                'phone'      => '098-765-4321',
                'website'    => 'jackparrot.vote_site.com',
                'notes'      => 'some notes about <strong>Jack Parrot</strong> lorem <i>ipsum dolor</i> sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
                'sex'        => '',
            ];
        }

        if (Session::has($this->register_session_key)) {
            $defaultValues               = Session::get($this->register_session_key);
            $viewParams['defaultValues'] = $defaultValues;

            return view($this->getFrontendTemplateName() . '.account.details', $viewParams)->with('input', $defaultValues);
        }

        $viewParams['defaultValues'] = ! empty($defaultValues) ? $defaultValues : [];

        return view($this->getFrontendTemplateName() . '.account.details', $viewParams);
    }

    public function postDetails(AccountUserRegisterDetailsRequest $request)
    {
        $requestData               = $request->all();
        $accountData               = $request->session()->get($this->register_session_key);
        $accountData['username']   = $requestData['username'];
        $accountData['email']      = $requestData['email'];
        $accountData['password']   = $requestData['password'];
        $accountData['first_name'] = $requestData['first_name'];
        $accountData['last_name']  = $requestData['last_name'];
        $accountData['phone']      = $requestData['phone'];
        $accountData['website']    = $requestData['website'];
        $accountData['notes']      = $requestData['notes'];
        $accountData['sex']        = $requestData['sex'];
        $request->session()->put($this->register_session_key, $accountData);

        return Redirect::route('account-register-avatar');
    }
    /*** 1st STEP - USER DETAILS BLOCK END  ***/


    /*** 2nd STEP - USER AVATAR BLOCK START  ***/

    public function getAvatar()
    {
        $viewParams                          = $appParamsForJSArray = $this->getAppParameters(false, ['site_name', 'account_register_avatar_text']);
        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');

        $viewParams['avatar_dimension_limits']     = \Config::get('app.avatar_dimension_limits', ['max_width' => 32, 'max_height' => 32]);
        $viewParams['full_photo_dimension_limits'] = \Config::get('app.full_photo_dimension_limits', ['max_width' => 256, 'max_height' => 256]);
        $accountData                               = Session::get($this->register_session_key);

        $avatar_filename      = ! empty($accountData['avatar_filename']) ? $accountData['avatar_filename'] : '';
        $avatar_filename_path = ! empty($accountData['avatar_filename_path']) ? $accountData['avatar_filename_path'] : '';
        $avatar_filename_url  = ! empty($accountData['avatar_filename_url']) ? $accountData['avatar_filename_url'] : '';
        if ( ! empty($avatar_filename) and ! empty($avatar_filename_url)) {
            $viewParams['avatar_filename']      = $avatar_filename;
            $viewParams['avatar_filename_path'] = $avatar_filename_path;
            $viewParams['avatar_filename_url']  = $avatar_filename_url;
        }

        $full_photo_filename      = ! empty($accountData['full_photo_filename']) ? $accountData['full_photo_filename'] : '';
        $full_photo_filename_path = ! empty($accountData['full_photo_filename_path']) ? $accountData['full_photo_filename_path'] : '';
        $full_photo_filename_url  = ! empty($accountData['full_photo_filename_url']) ? $accountData['full_photo_filename_url'] : '';
        if ( ! empty($full_photo_filename) and ! empty($full_photo_filename_url)) {
            $viewParams['full_photo_filename']      = $full_photo_filename;
            $viewParams['full_photo_filename_path'] = $full_photo_filename_path;
            $viewParams['full_photo_filename_url']  = $full_photo_filename_url;
        }

        return view($this->getFrontendTemplateName() . '.account.avatar', $viewParams);
    }

    public function postAvatar(Request $request)
    {
        $userUploadFile          = $request->file('avatar');
        $userUploadFullPhotoFile = $request->file('full_photo');

        $uploaded_file_max_mib = (int)\Config::get('app.uploaded_file_max_mib', 1);
        $max_size              = 1024 * $uploaded_file_max_mib;
        $rules                 = array(
            'avatar'     => 'max:' . $max_size,
            'full_photo' => 'max:' . $max_size,
        );
        $validator             = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ( ! empty($userUploadFile)) {
            $avatar_filename  = with(new Vote)->checkValidImgName($userUploadFile->getClientOriginalName(), with(new User)->getAvatarFilenameMaxLength(), true);
            $user_avatar_path = $userUploadFile->getPathName();

            if ( ! empty($avatar_filename)) {
                if (empty($this->session_id)) {
                    $this->session_id = Session::getId();
                }
                $avatar_filename_path = 'public/' . User::getUserAvatarTempDir($this->session_id) . $avatar_filename;
                $avatar_filename_url  = '/storage/' . User::getUserAvatarTempPath($this->session_id, $avatar_filename);

                Storage::disk('local')->put($avatar_filename_path, File::get($user_avatar_path));
                ImageOptimizer::optimize(storage_path() . '/app/' . $avatar_filename_path, null);
                $accountData                         = $request->session()->get($this->register_session_key);
                $accountData['avatar_filename']      = $avatar_filename;
                $accountData['avatar_filename_path'] = $avatar_filename_path;
                $accountData['avatar_filename_url']  = $avatar_filename_url;
                $request->session()->put($this->register_session_key, $accountData);
            } // if ( !empty($avatar_filename) ) {

        }

        if ( ! empty($userUploadFullPhotoFile)) {
            $full_photo_filename  = with(new Vote)->checkValidImgName($userUploadFullPhotoFile->getClientOriginalName(), with(new User)->getAvatarFilenameMaxLength(), true);
            $user_full_photo_path = $userUploadFullPhotoFile->getPathName();

            if ( ! empty($full_photo_filename)) {
                if (empty($this->session_id)) {
                    $this->session_id = Session::getId();
                }
                $full_photo_filename_path = 'public/' . User::getUserAvatarTempDir($this->session_id) . $full_photo_filename;
                $full_photo_filename_url  = '/storage/' . User::getUserAvatarTempPath($this->session_id, $full_photo_filename);

                Storage::disk('local')->put($full_photo_filename_path, File::get($user_full_photo_path));
                ImageOptimizer::optimize(storage_path() . '/app/' . $avatar_filename_path, null);
                $accountData                             = $request->session()->get($this->register_session_key);
                $accountData['full_photo_filename']      = $full_photo_filename;
                $accountData['full_photo_filename_path'] = $full_photo_filename_path;
                $accountData['full_photo_filename_url']  = $full_photo_filename_url;
                $request->session()->put($this->register_session_key, $accountData);
            } // if ( !empty($full_photo_filename) ) {

        }

        return Redirect::route('account-register-subscriptions');
    }

    /*** 2nd STEP - USER AVATAR BLOCK END  ***/


    /*** 3rd STEP - USER SUBSCRIPTIONS BLOCK START  ***/
    public function getSubscriptions()
    {
        $viewParams                          = $appParamsForJSArray = $this->getAppParameters(false, /*[],*/
            ['site_name', 'account_register_subscriptions_text']);
        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');

        $accountData           = Session::get($this->register_session_key);
        $selectedSubscriptions = ! empty($accountData['selectedSubscriptions']) ? $accountData['selectedSubscriptions'] : [];
        $siteSubscriptionsList = SiteSubscription
            ::getByActive(true)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($item, $key) use ($selectedSubscriptions) {
                $is_found = collect($selectedSubscriptions)->contains($item->id);

                return (object)['name' => $item->name, 'id' => $item->id, 'checked' => $is_found];

            })
            ->all();

        $viewParams['siteSubscriptionsList'] = $siteSubscriptionsList;
        if (Session::has($this->register_session_key)) {
            return view($this->getFrontendTemplateName() . '.account.subscriptions', $viewParams)->with('input', [] /*$defaultValues*/);
        }

        return view($this->getFrontendTemplateName() . '.account.subscriptions', $viewParams);
        //         return view($this->getBackendTemplateName() . '.admin.vote.index', $viewParams);

    }

    public function postSubscriptions(/*AccountUserRegisterDetailsRequest*/
        Request $request
    ) {
        $dataArray             = $request->all();
        $selectedSubscriptions = collect($dataArray)->keys()->map(function ($item) {
            $a = $this->pregSplit('/_subscription_/', $item);
            if (count($a) == 2) {
                return $a[1];
            }
        });

//        echo '<pre>$selectedSubscriptions::'.print_r($selectedSubscriptions,true).'</pre>';
//        die("-1 XXZ");
        $accountData                          = $request->session()->get($this->register_session_key);
        $accountData['selectedSubscriptions'] = $selectedSubscriptions;
        $request->session()->put($this->register_session_key, $accountData);

        return Redirect::route('account-register-confirm');
    }
    /*** 3rd STEP - USER SUBSCRIPTIONS BLOCK END  ***/


    /*** 4rt STEP - CREATE NEW USER WITH ALL BLOCK START  ***/
    public function getConfirm()
    {
        $viewParams = $appParamsForJSArray = $this->getAppParameters(false, /*[],*/
            ['site_name', 'account_register_confirm_text']);

        // prevent access without filling out step1
        if ( ! Session::has($this->register_session_key)) {
            return Redirect::route('account-register-details');
        }

        // get forms session data
        $accountData = Session::get($this->register_session_key);

        $tempSelectedSubscriptions = ! empty($accountData['selectedSubscriptions']) ? $accountData['selectedSubscriptions'] : [];


        /* I assume tempSelectedSubscriptions is an array of SiteSubscription ids, so can you not just use SiteSubscription::findMany($tempSelectedSubscriptions); to get a collection of all SiteSubscriptions in $tempSelectedSubscriptions?  */
//        $selectedSubscriptions = collect($tempSelectedSubscriptions)->filter(function ($item) {
//            echo '<pre>$item::' . print_r($item, true) . '</pre>';
//            $nextTempSelectedSubscription = SiteSubscription::find($item);
////            echo '<pre>$nextTempSelectedSubscription->id::'.print_r($nextTempSelectedSubscription,true).'</pre>';
//            if ($nextTempSelectedSubscription !== null) {
//                echo '<pre>INSIDE $item::' . print_r($item, true) . '</pre>';
//
//                return (object)['id' => $nextTempSelectedSubscription->id, 'name' => $nextTempSelectedSubscription->name];
//            }
//        });
        $selectedSubscriptions= SiteSubscription::findMany($tempSelectedSubscriptions);
//        dump($selectedSubscriptions);
//        echo '<pre>$selectedSubscriptions::' . print_r($selectedSubscriptions, true) . '</pre>';
//        die("-1 XXZ");

        // return the confirm view  session data as input
        $viewParams['appParamsForJSArray']   = json_encode($appParamsForJSArray);
        $viewParams['medium_slogan_img_url'] = config('app.medium_slogan_img_url');
        $viewParams['selectedSubscriptions'] = $selectedSubscriptions;

        $avatar_filename      = ! empty($accountData['avatar_filename']) ? $accountData['avatar_filename'] : '';
        $avatar_filename_path = ! empty($accountData['avatar_filename_path']) ? $accountData['avatar_filename_path'] : '';
        $avatar_filename_url  = ! empty($accountData['avatar_filename_url']) ? $accountData['avatar_filename_url'] : '';
        if ( ! empty($avatar_filename) and ! empty($avatar_filename_url)) {
            $viewParams['avatar_filename']      = $avatar_filename;
            $viewParams['avatar_filename_path'] = $avatar_filename_path;
            $viewParams['avatar_filename_url']  = $avatar_filename_url;
        }


        $full_photo_filename      = ! empty($accountData['full_photo_filename']) ? $accountData['full_photo_filename'] : '';
        $full_photo_filename_path = ! empty($accountData['full_photo_filename_path']) ? $accountData['full_photo_filename_path'] : '';
        $full_photo_filename_url  = ! empty($accountData['full_photo_filename_url']) ? $accountData['full_photo_filename_url'] : '';
        if ( ! empty($full_photo_filename) and ! empty($full_photo_filename_url)) {
            $viewParams['full_photo_filename']      = $full_photo_filename;
            $viewParams['full_photo_filename_path'] = $full_photo_filename_path;
            $viewParams['full_photo_filename_url']  = $full_photo_filename_url;
        }


        return view($this->getFrontendTemplateName() . '.account.confirm', $viewParams)->with('accountData', $accountData);
    }

    public function postConfirm() // create new user with all related data
    {
        $fileOnRegistrations         = [];
        $user_registration_files_str = Settings::getValue('userRegistrationFiles');
        $tempUserRegistrationFiles   = array_unique($this->pregSplit('/;/', $user_registration_files_str));
        foreach ($tempUserRegistrationFiles as $next_key => $next_file_on_registration) {
            $next_file_on_registration = trim($next_file_on_registration);
            if (empty($next_file_on_registration)) {
                continue;
            }
            $fileOnRegistrationProps = Settings::setSettingsFileOnRegistrationProps($next_file_on_registration, false);
            $fileOnRegistrations[]   = storage_path($fileOnRegistrationProps['file_on_registration_url']);
        }

        $request     = Request();
        $requestData = $request->all();
        $rules       = ['captcha' => 'required|captcha'];
        $site_name   = Settings::getValue('site_name');

        $validator = Validator::make($requestData, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $newAccountData = Session::get($this->register_session_key);

            $avatar_filename      = ! empty($newAccountData['avatar_filename']) ? $newAccountData['avatar_filename'] : '';
            $avatar_filename_path = ! empty($newAccountData['avatar_filename_path']) ? $newAccountData['avatar_filename_path'] : '';

            $full_photo_filename      = ! empty($newAccountData['full_photo_filename']) ? $newAccountData['full_photo_filename'] : '';
            $full_photo_filename_path = ! empty($newAccountData['full_photo_filename_path']) ? $newAccountData['full_photo_filename_path'] : '';

            $newUser             = new User();
            $newUser->username   = $newAccountData['username'];
            $newUser->email      = $newAccountData['email'];
            $newUser->password   = bcrypt($newAccountData['password']);
            $newUser->first_name = $newAccountData['first_name'];
            $newUser->last_name  = $newAccountData['last_name'];
            $newUser->phone      = $newAccountData['phone'];
            $newUser->website    = $newAccountData['website'];
            $newUser->notes      = $newAccountData['notes'];
            $newUser->sex        = $newAccountData['sex'];
            $newUser->status     = 'N';
            if ( ! empty($avatar_filename)) {
                $newUser->avatar = $newAccountData['avatar_filename'];
            }
            if ( ! empty($full_photo_filename)) {
                $newUser->full_photo = $newAccountData['full_photo_filename'];
            }
            $newUser->save();

            $newActivityLog              = new ActivityLog();
            $request                     = request();
            $newActivityLog->description = 'Successful registration from ip ' . ($request->ip()) . " with '" . $newAccountData['username'] . "' username, '" . $newAccountData['email'] .
                                           "' email ";
            $newActivityLog->causer_id   = $newUser->id;
            $newActivityLog->log_name    = $newAccountData['username'];
            $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_USER_ACTIVATION;
            $newActivityLog->properties  = '';
            $newActivityLog->save();

            UserVerification::generate($newUser);

            $selectedSubscriptions = ! empty($newAccountData['selectedSubscriptions']) ? $newAccountData['selectedSubscriptions'] : [];
            foreach ($selectedSubscriptions as $next_selected_subscription) {
                UsersSiteSubscription::create([
                    'site_subscription_id' => $next_selected_subscription,
                    'user_id'              => $newUser->id,
                ]);
            }

            $usersSiteSubscriptionsList = UsersSiteSubscription
                ::getByUserId($newUser->id)
                ->where('site_subscriptions.active', true)
                ->orderBy('site_subscriptions.name', 'desc')
                ->leftJoin('site_subscriptions', 'site_subscriptions.id', '=', 'users_site_subscriptions.site_subscription_id')
                ->get();

            $additiveVars = [
                'to_user_name'               => $newUser->username,
                'to_user_first_name'         => $newUser->first_name,
                'to_user_last_name'          => $newUser->last_name,
                'to_user_email'              => $newUser->email,
                'to_user_password'           => $newAccountData['password'],
                'verification_token'         => $newUser->verification_token,
                'usersSiteSubscriptionsList' => $usersSiteSubscriptionsList,
            ];

            $subject = 'You were registered at ' . $site_name . ' site ';
            \Mail::to($newUser->email)->send(new SendgridMail('emails/user_was_registered', $newUser->email, '', $subject, $additiveVars, $fileOnRegistrations));

            if ( ! empty($avatar_filename_path)) {
                $dest_avatar_filename_path = 'public/' . User::getUserAvatarPath($newUser->id, $avatar_filename);
                Storage::move($avatar_filename_path, $dest_avatar_filename_path);
            }

            if ( ! empty($full_photo_filename_path)) {
                $dest_full_photo_filename_path = 'public/' . User::getUserFullPhotoPath($newUser->id, $full_photo_filename);
                Storage::move($full_photo_filename_path, $dest_full_photo_filename_path);
            }

            Session::forget($this->register_session_key);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }

        return redirect()->route('home-msg', [])->with([
            'text'   => 'Your account was registered and activation code was sent at your email !',
            'type'   => 'success',
            'action' => 'show-login'
        ]);

    } // public function postConfirm() // create new user with all related data

    /*** 4rt STEP - CREATE NEW USER WITH ALL BLOCK END  ***/


    public function cancelAccountRegistration()
    {
        Session::forget($this->register_session_key);

        return Redirect::route('home');
    }

    public function activation($activation_key)
    {
        $site_name     = Settings::getValue('site_name');
        $activatedUser = User::getSimilarUserByVerificationToken($activation_key);
        if (empty($activatedUser->id)) {
            return redirect()->route('home-msg', [])->with(['text' => 'Invalid activation key', 'type' => 'danger', 'action' => '']);
        }

        if ($activatedUser->verified) {
            return redirect()->route('home-msg', [])->with(['text' => 'This Activation is already activated', 'type' => 'danger', 'action' => '']);
        }

        DB::beginTransaction();
        try {
            $activatedUser->status             = "A";
            $activatedUser->verified           = true;
            $activatedUser->verification_token = null;
            $activatedUser->activated_at       = now();
            $activatedUser->save();

            $userGroup = UserGroup
                ::getByGroup(USER_ACCESS_USER)
                ->getByUser($activatedUser->id)
                ->first();
            if (empty($userGroup)) {
                $userGroup           = new UserGroup();
                $userGroup->user_id  = $activatedUser->id;
                $userGroup->group_id = USER_ACCESS_USER;
                $userGroup->save();
            }

            $newActivityLog = new ActivityLog();
            $request        = request();

            $newActivityLog->description = 'Successful activation from ip ' . ($request->ip()) . " with '" . $activatedUser->username . "' username, '" . $activatedUser->email . "' email ";
            $newActivityLog->causer_id   = $activatedUser->id;
            $newActivityLog->log_name    = $activatedUser->username;
            $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_USER_ACTIVATION;
            $newActivityLog->properties  = '';
            $newActivityLog->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
                ->withInput([]);
        }

        $additiveVars = [
            'to_user_name'       => $activatedUser->username,
            'to_user_first_name' => $activatedUser->first_name,
            'to_user_last_name'  => $activatedUser->last_name,
            'to_user_email'      => $activatedUser->email,
            'verification_token' => $activatedUser->verification_token,
        ];

        $subject = 'You were registered at ' . $site_name . ' site ';
        \Mail::to($activatedUser->email)->send(new SendgridMail('emails/user_was_activated', $activatedUser->email, '', $subject, $additiveVars));

//        $beautymailWrapper = new BeautymailWrapper();
//        $beautymailWrapper->userWasActivated($activatedUser);

        return redirect()->route('home-msg', [])->with([
            'text'   => 'Activation was successful ! Now you can login into the system with credentials you entered during registration.',
            'type'   => 'success',
            'action' => 'show-login'
        ]);
    }


}