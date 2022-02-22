<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\backendFailOnLoginEvent;
use App\User;
use App\Http\Traits\funcsTrait;
//use App\UsersLogins;
use App\ActivityLog;

class backendFailOnLoginListener
{
    use funcsTrait;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct($e = '')
    {
//        $this->debToFile(' backendFailOnLoginListener __construct::');
//        $this->debToFile('  backendFailOnLoginListener __construct:: : ' . print_r($e, true), ' ::');
//
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     *
     * @return void
     */

    public function handle(backendFailOnLoginEvent $event)
    {                          //  https://modzone.ru/blog/2017/05/15/user-events/
//        $this->debToFile('  backendFailOnLoginListener handle $event:: : ' . print_r($event, true), ' ::');
        if ( ! empty($event->loginCreditialsArray['email']) and ! empty($event->loginCreditialsArray['password'])) {
//            $this->debToFile(' !!! backendFailOnLoginListener handle $event->loginCreditialsArray:: : ' . print_r($event->loginCreditialsArray, true), ' ::');

            $newActivityLog= new ActivityLog();
            $request= request();
//        $request->session()->put('logged_user_ip', $request->ip()); // Request::ip();
            $requestData= $request->all();
//        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
//        die("-1 XXZ sendFailedLoginResponse");
            $newActivityLog->description = 'Failed login from ip ' . ( $request->ip() )." with '" . $event->loggedUser->email . "' email ";
//        $newActivityLog->subject_id  = ;
            $newActivityLog->causer_id   = $event->loggedUser->id;
            $newActivityLog->log_name    = $event->loggedUser->email;
            $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_FAILED_LOGIN;
            $newActivityLog->properties  = '';
            $newActivityLog->save();

            /*            UsersLogins::create([
                            'provider_name' => 'EMAIL',
                            'username'      => $event->loginCreditialsArray['email'],
                            'user_id'       => null,
                            'remote_addr'   => ! empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
                            'with_success'  => false,
                        ]);*/

//            return redirect( "/home#/admin/dashboard/failure_login/" . urlencode($event->loginCreditialsArray['email']) );
        }
    }

}
