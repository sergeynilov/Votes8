<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\MyAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Requests\ForgotPasswordRequest;

use App\Http\Traits\funcsTrait;
use App\User;
use App\ActivityLog;
use App\Settings;
//use App\library\BeautymailWrapper;
use App\Mail\SendgridMail;

class ForgotPasswordController extends MyAppController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

//    use SendsPasswordResetEmails;
    use funcsTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index() // Forgot password page
    {

        $viewParamsArray          = $appParamsForJSArray = $this->getAppParameters(false, ['csrf_token', 'empty_img_url', 'site_name', 'site_heading', 'site_subheading']);
        $viewParamsArray['appParamsForJSArray']          = json_encode($appParamsForJSArray);
        $viewParamsArray['csrf_token']                   = $appParamsForJSArray['csrf_token'];
        return view($this->getFrontendTemplateName() . '.auth.forgot_password', $viewParamsArray);
    } // public function index() // Forgot password page

    public function reset( ForgotPasswordRequest $request )
    {
        $requestData= $request->all();
        $similarUser= User::getSimilarUserByEmail( $requestData['email'] );
        if (empty($similarUser->id)) {
            $this->setFlashMessage('Invalid email !', 'danger', 'Frontend');
            return Redirect
                ::back()
                ->withInput([]);
        }

        if ($similarUser->status!= "A") {
            $this->setFlashMessage('Your account is inactive !', 'danger', 'Frontend');
            return Redirect
                ::back()
                ->withInput([]);
        }

/*        UserVerification::generate($similarUser);

        $similarUser->status= 'I';
        $similarUser->save();*/


        $generated_password = User::generatePassword();
        $hashed_password    = bcrypt($generated_password);


        $similarUser->password = $hashed_password;
        $similarUser->save();
        $site_name          = Settings::getValue('site_name');


//        $beautymailWrapper = new BeautymailWrapper();
//        $beautymailWrapper->passwordWasReset($similarUser, $generated_password);

        $additiveVars= [
            'to_user_email'        => $similarUser->email,
            'generated_password'   => $generated_password,
            'to_user_name'         => $similarUser->username,
            'to_user_first_name'   => $similarUser->first_name,
            'to_user_last_name'    => $similarUser->last_name,

        ];
        $subject= 'Your password was reset at ' . $site_name . ' site ';
        \Mail::to($similarUser->email)->send( new SendgridMail( 'emails/password_was_reset',$similarUser->email,  '', $subject , $additiveVars ) );

        $newActivityLog= new ActivityLog();
        $request= request();
        $requestData= $request->all();
        $newActivityLog->description = 'Password reset successfully from ip ' . ( $request->ip() )." with '" . $requestData['email'] . "' email ";
        $newActivityLog->causer_id   = $similarUser->id;
        $newActivityLog->log_name    = $similarUser->username;
        $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_PASSWORD_RESET;
        $newActivityLog->save();
        $this->setFlashMessage("Password reset successfully and email with activation code was sent at provided email !", 'success');
        return redirect()->route('home');
    }

}
