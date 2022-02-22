<?php

namespace App\Http\Controllers;

use App\Download;
use Auth;
use Session;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Settings;
use App\User;
use App\Http\Traits\funcsTrait;
use PayPal\Api\Item;
use Spipu\Html2Pdf\Html2Pdf;
use App\library\CheckValueType;
use App\Paypal\CreatePayment;
use App\Paypal\ExecutePayment;
use App\Paypal\SubscriptionPlan;
use App\Payment;
use App\PaymentItem;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;



class CheckoutController extends MyAppController
{
    use funcsTrait;
    private $stripe_payment_key = 'stripe_payment';
    private $session_id = '';

    public function __construct()
    {
    }



    public function subscribe(Request $request)
    {

        $currency_stripe_code  = $this->getCurrency('stripe_code');
        $stripeConfig = config('services.stripe');
        echo '<pre>$stripeConfig[\'secret\']::'.print_r($stripeConfig['secret'],true).'</pre>';
//        die("-1 XXZ");
//        Stripe::setApiKey( $stripeConfig['secret'] );

        $viewParams = $appParamsForJSArray    = $this->getAppParameters(false, ['site_name']);
        $viewParams['currency_stripe_code']   = $currency_stripe_code;
        $viewParams['stripe_public_key']      = $stripeConfig['key'];
//        $viewParams['payment_description']       = !empty($requestData['payment_description']) ? $requestData['payment_description'] : '';

        $request->session()->put($this->stripe_payment_key, [
//            'subtotal_value'        => $subtotal_value,
//            'all_quantity'          => $all_quantity,
//            'selected_items_title'  => $selectedItems_title,
//            'selectedItems'         => $selectedItems,
            'currency_stripe_code'  => $currency_stripe_code,
//            'payment_description'   => !empty($requestData['payment_description']) ? $requestData['payment_description'] : '',
        ]);

        return view('subscribe', $viewParams);
//        return view($this->getFrontendTemplateName() . '.payment.stripe_checkout', $viewParams);

        /*        try {
                    Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

                    $user = User::find(1);
                    $user->newSubscription('main', 'bronze')->create($request->stripeToken);

                    return 'Subscription successful, you get the course!';
                } catch (\Exception $ex) {
                    return $ex->getMessage();
                }*/

    }


    public function subscribe_process(Request $request)
    {
        $requestData= $request->all();
        echo '<pre>$requestData::'.print_r($requestData,true).'</pre>';
        /* $requestData::Array
(
    [_token] => Eyxy69anGQumiGPLlXNtu8DkFuU1qEDgRZleB05E
    [stripeToken] => tok_1FQxLBI5xQuRgDRP0WGUFheb
    [stripeTokenType] => card
    [stripeEmail] => nilovsergey@meta.ua
) */
//die("-1 XXZ");
//        try {

            $stripeConfig = config('services.stripe');
            Stripe::setApiKey( $stripeConfig['secret'] );
//            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $user = User::find(1);
            $user->newSubscription('Product 1', 'plan name')->create($request->stripeToken);
//            $user->newSubscription('main', 'bronze')->create($request->stripeToken);

//            return 'Subscription successful, you get the course!';
//        } catch (\Exception $ex) {
//            return $ex->getMessage();
//        }

    }


}
