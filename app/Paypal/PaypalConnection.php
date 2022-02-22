<?php


namespace App\Paypal;

use App\Http\Traits\funcsTrait;
use Auth;


class PaypalConnection
{
    use funcsTrait;

    protected $apiContext;
    private $loggedUser;

    public function __construct($loggedUser)
    {

        if ( !Auth::check() ) {
            return redirect()->route('home-msg', [])->with([
                'text'   => 'You need to login into the system !',
                'type'   => 'danger',
                'action' => 'show-login'
            ]);
        }
        $this->loggedUser= $loggedUser;
//        $this->debToFile(print_r($this->loggedUser->id, true), ' PaypalConnection  -0A $this->loggedUser->id::');


//        $this->debToFile(print_r(config('services.paypal'), true), " PaypalConnection  -0A config('services.paypal.id')::");

        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                config('services.paypal.id'),
                config('services.paypal.secret')
            )
        );

    }
}