<?php namespace App\library {

    use Auth;
    use DB;
    use Carbon\Carbon;

    use App\User;
    use App\ContactUs;
    use App\Settings;
    use App\UsersSiteSubscription;
    use App\Http\Traits\funcsTrait;

    class BeautymailWrapper
    {
        use funcsTrait;

        private $send_sms_message= true;


        public function newContactUsNotification($newContactUsList, $cronTasksReceiversArray)
        {
            $site_home_url     = \URL::to('/');
            $site_name         = Settings::getValue('site_name');
            $noreply_email     = Settings::getValue('noreply_email');
            $support_signature = Settings::getValue('support_signature');
            try {

                $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);

                foreach ($cronTasksReceiversArray as $nextCronTasksReceiver) { // all admins of the site who would receive email notifications
                    $next_cron_tasks_receiver_email= $nextCronTasksReceiver['email'];
                    $next_cron_tasks_receiver_name= $nextCronTasksReceiver['name'];


                    $beautymail->send('emails.cron.new_contact_us_notification', [
                        'receiver_username' => $next_cron_tasks_receiver_name,
                        'site_home_url'     => $site_home_url,
                        'site_name'         => $site_name,
                        'newContactUsList'  => $newContactUsList,
                        'noreply_email'     => $noreply_email,
                        'support_signature' => $support_signature,
                        'color'             => '#FF00FF'
                    ], function ($messageInstance) use ($next_cron_tasks_receiver_email, $next_cron_tasks_receiver_name, $site_home_url, $site_name, $noreply_email, $support_signature) {
                        $messageInstance
                            ->from($noreply_email)
                            ->to($next_cron_tasks_receiver_email, $next_cron_tasks_receiver_name)
                            ->subject('New contact us notification was sent at ' . $site_name . ' site ');
                    });

                    if ( !empty($nextCronTasksReceiver['mobile']) and $this->send_sms_message ) { // send SMS to Tasks Receiver as he has mobile in his options
                        $sms_text= 'There are ' . count($newContactUsList) . ' new contact us at '.$site_name.' site !';
                        $this->sendSMSMessageByTwilio($sms_text  );
                    }
                    
                } // foreach( $cronTasksReceiversArray as $nextCronTasksReceiversArray ) { // all admins of the site who would receive email notifications

            } catch (Exception $e) {
                return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }

            return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
        } // public function contactUsWasSent()


        public function userWasActivated(User $activatedUser)
        {
            $site_home_url     = \URL::to('/');
            $site_name         = Settings::getValue('site_name');
            $noreply_email     = Settings::getValue('noreply_email');
            $support_signature = Settings::getValue('support_signature');
            $user_first_name   = $activatedUser->first_name;
            $user_last_name    = $activatedUser->last_name;
            $user_name         = $activatedUser->username;
            $user_email        = $activatedUser->email;

            try {


                $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
                $beautymail->send('emails.user_was_activated', [
                    'to_user_name'       => $user_name,
                    'to_user_first_name' => $user_first_name,
                    'to_user_last_name'  => $user_last_name,
                    'to_user_email'      => $user_email,
                    'site_home_url'      => $site_home_url,
                    'site_name'          => $site_name,
                    'noreply_email'      => $noreply_email,
                    'support_signature'  => $support_signature,
                    'color'              => '#FF00FF'
                ], function ($messageInstance) use ($user_name, $user_email, $site_home_url, $site_name, $noreply_email, $support_signature) {
                    $messageInstance
                        ->from($noreply_email)
                        ->to($user_email, $user_name)
                        ->subject('User was activated ' . $site_name . ' site ');
                });


            } catch (Exception $e) {
                return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }
        } // public function userWasActivated()




        public function userWasRegisteredWithSocialite_OLD(User $forUser, $provider_name, $generated_verification_token)
        {
            $site_home_url     = \URL::to('/');
            $site_name         = Settings::getValue('site_name');
            $noreply_email     = Settings::getValue('noreply_email');
            $support_signature = Settings::getValue('support_signature');
            $user_first_name = $forUser->first_name;
            $user_last_name  = $forUser->last_name;
            $user_name       = $forUser->username;
            $user_email      = $forUser->email;

            $usersSiteSubscriptionsList = UsersSiteSubscription
                ::getByUserId($forUser->id)
                ->where('site_subscriptions.active', true)
                ->orderBy('site_subscriptions.name', 'desc')
                ->leftJoin( 'site_subscriptions', 'site_subscriptions.id', '=', 'users_site_subscriptions.site_subscription_id' )
                ->get();

            try {


                $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
                $beautymail->send('emails.user_was_registered_with_socialite', [
                    'to_user_name'               => $user_name,
                    'to_user_first_name'         => $user_first_name,
                    'to_user_last_name'          => $user_last_name,
                    'to_user_email'              => $user_email,
                    'provider_name'              => $provider_name,
                    'verification_token'         => $generated_verification_token,
                    'site_home_url'              => $site_home_url,
                    'site_name'                  => $site_name,
                    'noreply_email'              => $noreply_email,
                    'support_signature'          => $support_signature,
                    'usersSiteSubscriptionsList' => $usersSiteSubscriptionsList,
                    'color'                      => '#FF00FF'
                ], function ($messageInstance) use ($user_name, $user_email, $site_home_url, $site_name, $noreply_email, $support_signature) {
                    $messageInstance
                        ->from($noreply_email)
                        ->to($user_email, $user_name)
                        ->subject('You were registered at ' . $site_name . ' site ');
                });


            } catch (Exception $e) {
                return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }

            return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
        } // public function userWasRegisteredWithSocialite_OLD()




        public function subscriptionsListOfUser(User $forUser)
        {
            $site_home_url     = \URL::to('/');
            $site_name         = Settings::getValue('site_name');
            $noreply_email     = Settings::getValue('noreply_email');
            $support_signature = Settings::getValue('support_signature');
            $user_name  = $forUser->username;
            $user_email = $forUser->email;

            $usersSiteSubscriptionsList = UsersSiteSubscription
                ::getByUserId($forUser->id)
                ->where('site_subscriptions.active', true)
                ->orderBy('site_subscriptions.name', 'desc')
                ->leftJoin( 'site_subscriptions',  'site_subscriptions.id', '=', 'users_site_subscriptions.site_subscription_id' )
                ->get();

            try {

                $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
                $beautymail->send('emails.user_has_subscriptions', [
                    'to_user_name'               => $user_name,
                    'to_user_email'              => $user_email,
                    'site_home_url'              => $site_home_url,
                    'site_name'                  => $site_name,
                    'noreply_email'              => $noreply_email,
                    'support_signature'          => $support_signature,
                    'usersSiteSubscriptionsList' => $usersSiteSubscriptionsList,
                    'color'                      => '#FF00FF'
                ], function ($messageInstance) use ($user_name, $user_email, $site_home_url, $site_name, $noreply_email, $support_signature) {
                    $messageInstance
                        ->from($noreply_email)
                        ->to($user_email, $user_name)
                        ->subject('Your subscriptions at ' . $site_name . ' site ');
                });


            } catch (Exception $e) {
                return response()->json(['error_code' => 1, 'message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
            }

            return response()->json(['error_code' => 0, 'message' => ''], HTTP_RESPONSE_OK);
        } // public function subscriptionsListOfUser()


    }
}