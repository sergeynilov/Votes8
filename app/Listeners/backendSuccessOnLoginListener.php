<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
//use App\UsersLogins;
use App\Events\backendSuccessOnLoginEvent;
use App\Http\Traits\funcsTrait;
use App\ActivityLog;

class backendSuccessOnLoginListener
{
    use funcsTrait;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->debToFile(' backendSuccessOnLoginListener __construct::');
//        $this->debToFile('  backendSuccessOnLoginListener handle __construct:: : '.print_r($e,true),' ::');
//        die("00 XXZ handle ");
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(backendSuccessOnLoginEvent $event)
    {

//        die("-1 XXZ handle ");
//        $this->debToFile(' backendSuccessOnLoginListener handle::');
//        $this->debToFile('  backendSuccessOnLoginListener handle $event:: : '.print_r($event,true),' ::');
//
        $username= !empty($event->loggedUser->email) ? $event->loggedUser->email : '';
        if ( empty($username) and !empty($event->loggedUser->username) ) { // get name of logged user
            $username= $event->loggedUser->username;
        }
        if ( !empty($event->loggedUser)  ) { // save in history  name/id of logged user
            $newActivityLog= new ActivityLog();
            $request= request();
//        $request->session()->put('logged_user_ip', $request->ip()); // Request::ip();
            $requestData= $request->all();
//        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
//        die("-1 XXZ sendFailedLoginResponse");
            $newActivityLog->description = 'Successful login from ip ' . ( $request->ip() )." with '" . $event->loggedUser->email . "' email ";
//        $newActivityLog->subject_id  = ;
            $newActivityLog->causer_id   = $event->loggedUser->id;
            $newActivityLog->log_name    = $event->loggedUser->email;
            $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_SUCCESSFUL_LOGIN;
            $newActivityLog->properties  = '1';
            $newActivityLog->save();

            /*             UsersLogins::create([
                            'provider_name'      => $event->loggedUser->provider_name,
                            'username'           => $username,
                            'user_id'            => $event->loggedUser->id,
                            'remote_addr'        => !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
                            'with_success'       => true,
                        ]);*/
        } // if ( !empty($event->loggedUser)  ) { // save in history  name/id of logged user

    }
}
