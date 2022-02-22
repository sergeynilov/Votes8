<?php

namespace App\Http\Controllers\Auth;

use App\Mail\SendgridMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use DB;
use Auth;
use Session;
use App\Http\Controllers\Controller;

//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;

//use Socialite;

use App\Http\Traits\funcsTrait;
use App\User;
use App\Group;
use App\UserGroup;
use App\Settings;
use App\ActivityLog;
use App\Subscription;
use App\Events\backendFailOnLoginEvent;
use App\Events\backendSuccessOnLoginEvent;
use App\library\BeautymailWrapper;


class LoginController extends Controller // https://laravel.com/api/5.3/Illuminate/Foundation/Auth/AuthenticatesUsers.html
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

//    use AuthenticatesUsers;
    use funcsTrait;
    private $votes_tb;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/';
    protected $redirectTo = '/profile/print-to-pdf'; // For Debugging

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->users_groups_tb= with(new UserGroup)->getTable();
        $this->middleware('guest')->except('logout');
    }



    public function redirectToProvider($provider_name)
    {
        if ( $provider_name == 'facebook' ) {
            return Socialite::driver('facebook')->scopes([
                "publish_actions, manage_pages", "publish_pages"])->redirect();
        }
        return Socialite::driver($provider_name)->redirect();
    }

    public function handleSocialiteProviderCallback($provider_name)
    {
//        echo '<pre>$provider_name::'.print_r($provider_name,true).'</pre>';
//        $this->debToFile(print_r($provider_name,true),'  handleSocialiteProviderCallback  -8 $provider_name::');
        $settingsArray   = Settings::getSettingsList(['site_name']);
        $site_name       = !empty($settingsArray['site_name']) ? $settingsArray['site_name'] : '';
        $site_home_url   = \URL::to('/');

        $provider_name= (string)$provider_name;
        $is_debug= true;
        try {
//            DB::beginTransaction();

            $socialiteUser = Socialite::driver($provider_name)->user();
            /*         $username= User::getUniqueUsername( 'sergeynilov' ); */

/*            if ( $is_debug ) {
                $this->debToFile(print_r($socialiteUser,true),' handleSocialiteProviderCallback  -1 $socialiteUser::');
            }*/

            $loggedUser = User::where('provider_id', $socialiteUser->id)->first();
/*            if ( $is_debug ) {
//                echo '<pre>-2 $loggedUser::'.print_r($loggedUser,true).'</pre>';
                $this->debToFile(print_r($loggedUser,true),' handleSocialiteProviderCallback  -2 $loggedUser->id::');
            }*/

            if ($loggedUser !== null ) { // There is already user is system - just login under it's credentials
//                die("-1 XXZ0000");
                Auth::login($loggedUser, true);
//                \Event::fire(new backendSuccessOnLoginEvent($loggedUser));
                \Event::dispatch(new backendSuccessOnLoginEvent($loggedUser));
//                DB::commit();
//                $this->debToFile(print_r($loggedUser,true),' handleSocialiteProviderCallback  -3 $loggedUser::');
                return redirect()->route('home');
            } // if ($loggedUser) { // There is already user is system - just login under it's credentials


            if ( $provider_name == "github" ) {
                $username = !empty($socialiteUser->name) ? $socialiteUser->name : $socialiteUser->nickname;
            } else {
                $username = $socialiteUser->name;
            }

            if ( !empty($socialiteUser->email) ) {
                $similarUser = User::getSimilarUserByEmail($socialiteUser->email);
            }
            if ( !empty($similarUser) ) {
                $socialiteUser->email= '';
            }
            if ( empty($socialiteUser->email) ) {
                $site_fake_name= str_replace( [' ', '&', '!', '@', '#', '$', '%', '^', ')', '(', '+', '='] , '', $site_name );
                $socialiteUser->email= 'fake-'.$provider_name.time().'@'.$site_fake_name.'.fake';
            }


            $username= User::getUniqueUsername($username);



//            $this->debToFile(print_r($provider_name,true),'  handleSocialiteProviderCallback  -4 $provider_name::');
//            $this->debToFile(print_r($socialiteUser->email,true),'  handleSocialiteProviderCallback  -41 $socialiteUser->email::');

            $loggedUser= User::create([ // There NO user is system yet : create it and login under it's credentials
                'username'      => $username,
                'email'         => $socialiteUser->email,
                'status'        => 'A',
                'verified'      => 1,
                'activated_at'  => Carbon::now(config('app.timezone')),
                'provider_name' => $provider_name,
                'provider_id'   => $socialiteUser->id,
            ]);

            if ( empty($loggedUser->first_name) ) {
                $loggedUser->first_name = 'Demofirstname';
            }
            if ( empty($loggedUser->last_name) ) {
                $loggedUser->last_name = 'Demolastname';
            }


            /*                 'payer_shipping_address' => '111 1st Street',
                'payer_recipient_name' => 'Sergey Nilov',
                'payer_city' => 'Saratoga',
                'payer_state' => 'CA',
                'payer_postal_code' => '95070',
                'payer_country_code' => 'US',
 */
            $loggedUser->phone= 'Demo Phone';
            $loggedUser->website= 'Demowebsite.com';
            $loggedUser->address_line1= 'Demo 1st Street';
            $loggedUser->address_city= 'Saratoga';
            $loggedUser->address_state= 'CA';
            $loggedUser->address_postal_code= '95070';
            $loggedUser->address_country_code= 'US';
            $loggedUser->shipping_address_line1= 'Demo 1st Street';
            $loggedUser->shipping_address_city= 'Saratoga';
            $loggedUser->shipping_address_state= 'CA';
            $loggedUser->shipping_address_postal_code= '95070';
            $loggedUser->shipping_address_country_code= 'US';
            $loggedUser->save();

                DB::table($this->users_groups_tb)->insert([
                'user_id'=> $loggedUser->id,
                'group_id'=> USER_ACCESS_USER
            ]);

            $newActivityLog              = new ActivityLog();
            $request                     = request();
            $newActivityLog->description = 'Successful registration from ip ' . ($request->ip()) . " with '" . $username . "' username, '" . $socialiteUser->email . "' email ";
            $newActivityLog->causer_id   = $loggedUser->id;
            $newActivityLog->log_name    = $username;
            $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_USER_ACTIVATION;
            $newActivityLog->properties  = '';
            $newActivityLog->save();

//            UserVerification::generate($loggedUser);


            $attachFiles= [];
            $subject      = 'You registered at ' . $site_name . ' site ';
            $additiveVars = [
                'to_user_first_name'        => $loggedUser->first_name,
                'to_user_last_name'         => $loggedUser->last_name,
                'provider_name'             => $provider_name,
                'site_home_url'             => $site_home_url,
            ];

//            $to_user_first_name !!} {!! $to_user_last_name
            if ( !empty($socialiteUser->email) ) {      ///_wwwroot/lar/votes/resources/views/emails/user_was_registered_with_socialite.blade.php
                \Mail::to($socialiteUser->email)->send(new SendgridMail('emails/user_was_registered_with_socialite',
                    $socialiteUser->email, '', $subject, $additiveVars, $attachFiles));
            }
            /*            $beautymailWrapper = new BeautymailWrapper();
                        $beautymailWrapper->userWasRegisteredWithSocialite($loggedUser, $provider_name, $loggedUser->verification_token);*/

/*            if ( $is_debug ) {
//                echo '<pre>-4 $loggedUser::'.print_r($loggedUser,true).'</pre>';
                $this->debToFile(print_r($loggedUser->id,true),' handleSocialiteProviderCallback  -4 $loggedUser->id::');
            }*/
            if ( $loggedUser === null ) {
//                echo '<pre>-444 EXIT $loggedUser::'.print_r($loggedUser,true).'</pre>';
                die("-1 XXZ  ERROR");
                return redirect(  "/home#/admin/dashboard/error_creating_new_user/" . urlencode($username) );
            }

//            Auth::login($loggedUser, true);
//            \Event::fire(new backendSuccessOnLoginEvent($loggedUser));

            if ( Auth::check() ) {
//                $this->debToFile(' -9 handleSocialiteProviderCallback CHECKED OK::');

            }   else {
//                $this->debToFile(' -9 handleSocialiteProviderCallback CHECKED NO::');

            }
            /*            $newUserGroup           = new UsersGroups();
                        $newUserGroup->user_id  = $loggedUser->id;
                        $newUserGroup->group_id = $userGroup->id;
                        $newUserGroup->save();*/
//            $this->debToFile(print_r($loggedUser,true),' handleSocialiteProviderCallback  -5 $loggedUser->id::');
//            DB::commit();
//            die("-1 XXZ000");

        } catch (Exception $e) {
//            DB::rollBack();
//            echo '<pre>-20 $e->getMessage(),true::'.print_r($e->getMessage(),true).'</pre>';
//            return redirect($site_hosting . "/home#/admin/dashboard/error_creating_new_user/" . urlencode($e->getMessage()));
//            return redirect("/home#/admin/dashboard/error_creating_new_user/" . urlencode($e->getMessage()));
        }

//        echo '<pre>$loggedUser->username::'.print_r($loggedUser->username,true).'</pre>';
//        echo '<pre>$provider_name::'.print_r($provider_name,true).'</pre>';
//        $this->debToFile(print_r($loggedUser->username,true),' handleSocialiteProviderCallback  -6 $loggedUser->username::');
//        $this->debToFile(print_r($provider_name,true),' handleSocialiteProviderCallback  -7 $provider_name::');
//        $this->debToFile(print_r($loggedUser->username,true),' handleSocialiteProviderCallback  -71 $loggedUser->username::');


        return redirect()->route('home-msg', [])->with([
            'text'   => 'Your account was registered and now you can login into the system !',
            'type'   => 'success',
            'action' => 'show-login'
        ]);

    } //public function handleSocialiteProviderCallback($provider_name)



    public function showLoginForm()
    {

                        $this->debToFile(' -9 showLoginForm 9::');

        $show_demo_admin_on_login = config('app.SHOW_DEMO_ADMIN_ON_LOGIN');

        $demo_admin_email = config('app.DEMO_ADMIN_EMAIL');

        $demo_admin_password = config('app.DEMO_ADMIN_PASSWORD');

        $is_developer_comp= $this->isDeveloperComp();
        if ( !empty( $_REQUEST['is_debug']) ) {
            $is_developer_comp = 1;
        }
        $settingsArray   = Settings::getSettingsList(['site_name']);
        $site_name       = !empty($settingsArray['site_name']) ? $settingsArray['site_name'] : '';


        $viewParamsArray = [
            'frontend_template_name'   => 'cardsBS41Frontend',
            'show_demo_admin_on_login' => $show_demo_admin_on_login,
            'demo_admin_email'         => $demo_admin_email,
            'demo_admin_password'      => $demo_admin_password,
            'is_developer_comp'        => $is_developer_comp,
            'site_name'                => $site_name,
            'allow_facebook_authorization' => Settings::getValue('allow_facebook_authorization'),
            'allow_google_authorization' => Settings::getValue('allow_google_authorization'),
            'allow_github_authorization' => Settings::getValue('allow_github_authorization'),
            'allow_linkedin_authorization' => Settings::getValue('allow_linkedin_authorization'),
            'allow_twitter_authorization' => Settings::getValue('allow_twitter_authorization'),
            'allow_instagram_authorization' => Settings::getValue('allow_instagram_authorization'),
        ];
        $viewParamsArray['csrf_token'] = csrf_token();
        return view('cardsBS41Frontend.auth.login', $viewParamsArray);     //resources/views/cardsBS41Frontend/auth/login.blade.php
    }


    //authenticated(Request $request, mixed $user)
    protected function authenticated(Request $request, $throttles)
    {
        $loggedUser             = Auth::user();
        $loggedUserAccessGroups = [];
        $usersGroups            = User::getUsersGroupsByUserId($loggedUser->id, false);
        foreach ($usersGroups as $next_key => $nextUsersGroup) {
            $loggedUserAccessGroups[] = ['group_id' => $nextUsersGroup->group_id, 'group_name' => $nextUsersGroup->group_name];
            if ( $nextUsersGroup->group_id == USER_ACCESS_ADMIN ) {
                if (!$this->isDeveloperComp()) {
                    $this->redirectTo = "admin/dashboard";
                } else {
                    $this->redirectTo = "/home"; // FOR debugging
//                    $this->redirectTo = "admin/events";
                }
            }
        }

        if ($loggedUser->status != "A") {
            Auth::logout();
            $this->setFlashMessage('Your account is inactive !', 'danger', 'Frontend');
            return redirect('/login');
        }

        $request->session()->put('loggedUserAccessGroups', $loggedUserAccessGroups);


        // t2_service_subscriptions
        $loggedUserSubscription = Subscription
            ::getByUserId($loggedUser->id)
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


//        echo '<pre>$loggedUserSubscription::'.print_r($loggedUserSubscription,true).'</pre>';
//        die("-1 XXZ");
        if ( !empty($loggedUserSubscription) ) {
            $request->session()->put('loggedUserSubscription', $loggedUserSubscription);
        }
        $request->session()->put('logged_user_ip', $request->ip()); // Request::ip();

        $newActivityLog= new ActivityLog();
        $request= request();
//        $request->session()->put('logged_user_ip', $request->ip()); // Request::ip();
        $requestData= $request->all();
//        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
//        die("-1 XXZ sendFailedLoginResponse");
        $newActivityLog->description = 'Successful login from ip ' . ( $request->ip() )." with '" . $requestData['email'] . "' email ";
//        $newActivityLog->subject_id  = ;
        $newActivityLog->causer_id   = $loggedUser->id;
        $newActivityLog->log_name    = $requestData['email'];
        $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_SUCCESSFUL_LOGIN;
        $newActivityLog->properties  = '1';
        $newActivityLog->save();

        $is_debug = false;
        if ($is_debug) {
            echo '<pre><hr></pre>';
            echo '<pre><hr></pre>';
            echo '<pre><hr></pre>';
            echo '<pre><hr></pre>';
        }
        if ($is_debug) {
            $copy_loggedUserAccessGroups = $request->session()->get('loggedUserAccessGroups');
            echo '<pre>$copy_loggedUserAccessGroups::' . print_r($copy_loggedUserAccessGroups, true) . '</pre>';
            $logged_user_ip = $request->session()->get('logged_user_ip');
            echo '<pre>logged_user_ip::' . print_r($logged_user_ip, true) . '</pre>';
            die("-1 XXZ authenticated");
        }
    }

    protected function handleUserWasAuthenticated(Request $request, $throttles)
    {
        die("-1 XXZ handleUserWasAuthenticateds");
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }
        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::guard($this->getGuard())->user());
        }
        if ($request->wantsJson() || $request->ajax()) {
            return; // 200 OK
        } else {
            return redirect()->intended($this->redirectPath());
        }
    }

    protected function sendFailedLoginResponse()
    {
        $newActivityLog= new ActivityLog();
        $request= request();
        $requestData= $request->all();
        $newActivityLog->description = 'Failed login from ip ' . ( $request->ip() )." with '" . $requestData['email'] . "' email ";
        $newActivityLog->log_name    = $requestData['email'];
        $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_FAILED_LOGIN;
        $newActivityLog->properties  = '';
        $newActivityLog->save();
        $this->setFlashMessage('Invalid login', 'danger', 'Frontend');
        return Redirect
            ::back()
            ->withInput([]);
    }

}
