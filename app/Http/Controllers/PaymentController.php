<?php

namespace App\Http\Controllers;

use App\Download;
use Auth;
use Carbon\Carbon;
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



class PaymentController extends MyAppController
{
    use funcsTrait;
    private $stripe_payment_key = 'stripe_payment';
    private $session_id = '';

    public function __construct()
    {
        \Log::info( '<pre>PaymentController stripe_callback_event -1::'.print_r(-1,true).'</pre>' );
    }


    ///  STRIPE BLOCK START

    public function stripe_callback_event( $payload= '' )   // https://www.votes.my-demo-apps.tk/stripe_callback_event
    {
        \Log::info( '<pre>-20 PaymentController stripe_callback_event $payload::'.print_r(-2,true).'</pre>' );
        \Log::info( '<pre>-21 $payload::'.print_r($payload,true).'</pre>' );

    } // public function stripe_callback_event()   // https://www.votes.my-demo-apps.tk/stripe_callback_event
    

    public function stripe_express_payment_callback()   //http://local-votes.com/stripe_express_payment_callback
    {
        // http://local-votes.com/stripe_express_payment_callback?stripeToken=tok_1FO047I5xQuRgDRP9hvvyUJW&stripeTokenType=card&stripeEmail=mstdmstd
        //%40rambler.ru
        $request     = request();
//        dd($request->all());
        /* array:3 [▼
  "stripeToken" => "tok_1FO0bsI5xQuRgDRPy0S8XnB3"
  "stripeTokenType" => "card"
  "stripeEmail" => "mstdmstd@rambler.ru" */
//        echo '<pre>stripe_express_payment_callback::'.print_r(-88,true).'</pre>';

        if (!Session::has($this->stripe_payment_key)) {
            return redirect()->route('home-msg', [])->with(['text' => 'Invalid Session', 'type' => 'danger', 'action' => '']);
        }
        $stripePaymentData               = Session::get($this->stripe_payment_key);
//        echo '<pre>$stripePaymentData::'.print_r($stripePaymentData,true).'</pre>';
        $selectedItems= $stripePaymentData['selectedItems'];
        $stripeConfig = config('services.stripe');
        Stripe::setApiKey( $stripeConfig['secret'] );

        $customer = Customer::create(array(
            'email' => $request->stripeEmail,
            'source'  => $request->stripeToken
        ));

//        echo '<pre>$customer::'.print_r($customer,true).'</pre>';
        $charge = Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $stripePaymentData['subtotal_value']*100,
            'currency' => $stripePaymentData['currency_stripe_code'],
            'description' => $stripePaymentData['payment_description']
        ));

//        echo '<pre>$charge::'.print_r($charge,true).'</pre>';
//        die("-1 XXZ RESULTS");
        $loggedUser = Auth::user();


/*        if ( $newPaymentResult->state != 'approved' ) { // payment was successful
            return redirect()->route('home-msg', [])->with([
                'text'   => 'Your payment was successfully completed !',
                'type'   => 'danger',
                'action' => ''
            ]);
        }*/

        $newStripePayment= new \App\Payment();
        $newStripePayment->user_id= $loggedUser->id;
        $newStripePayment->status= 'C';
        $newStripePayment->payment_type= 'SC'; // SC => Stripe Checkout
        $newStripePayment->payment_status= $charge->status;
        $newStripePayment->payment_description= !empty($charge->description) ? substr($charge->description,0,255) : '';
        $newStripePayment->invoice_number= !empty($charge->invoice) ? $charge->invoice : '';   // Empty ??

        $newStripePayment->payer_id= $customer->id;
//        $newStripePayment->payer_shipping_address= $newPaymentResult->payer->payer_info->shipping_address;
        $newStripePayment->payer_recipient_name= $charge->source->name;
        

        $newStripePayment->payer_email= $customer->email;
        $newStripePayment->payer_first_name= '?';
        $newStripePayment->payer_last_name=  '?';
        $newStripePayment->payer_middle_name=  '?';

        $newStripePayment->currency= $charge->currency;

        $newStripePayment->total= $charge->amount;
        $newStripePayment->subtotal= $charge->amount; // Empty ??
        $newStripePayment->tax= 0;
        $newStripePayment->shipping= !empty($charge->shipping) ? $charge->shipping : 0;


        $newStripePayment->payer_shipping_address= '??';
        $newStripePayment->payer_recipient_name= '??';
        $newStripePayment->payer_city= '??';
        $newStripePayment->payer_state= '??';
        $newStripePayment->payer_postal_code= '??';
        $newStripePayment->payer_country_code= '??';
        $newStripePayment->payer_business_name= '??';

        DB::beginTransaction();
        try {
            $newStripePayment->save();

            //                     $selectedItems[]      = [ 'id'=>$download->id, 'price'=>$download->price ];
            foreach( $selectedItems as $next_key=>$nextSelectedItem ) {
//                echo '<pre>$nextSelectedItem->sku::'.print_r($nextSelectedItem->sku,true).'</pre>';
                $newPaymentItem= new PaymentItem();
                $newPaymentItem->payment_id= $newStripePayment->id;
                $newPaymentItem->item_type= 'D';
                $newPaymentItem->quantity= $nextSelectedItem['quantity'];
                $newPaymentItem->item_id= $nextSelectedItem['sku'];
                $newPaymentItem->price= $nextSelectedItem['price'];
                $newPaymentItem->save();
            }
            $loggedUser->stripe_id  = $customer->id;
            $loggedUser->updated_at = Carbon::now(config('app.timezone'));
            $loggedUser->save();
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            echo '<pre>$e->getMessage()::'.print_r($e->getMessage(),true).'</pre>';
            die("-1 EOOR");
            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
//                ->withInput(['name' => $request->get('name'), 'status' => $request->get('status'), 'description' => $request->get('description')])
                ;
        }
        return redirect()->route('home-msg', [])->with([
            'text'   => 'Your payment was successfully completed !',
            'type'   => 'success',
            'action' => ''
        ]);


    }

    ///  STRIPE BLOCK END

    public function cancel_payment() {
        Session::forget($this->stripe_payment_key);
        return redirect()->route('about');
    } // cancel_payment
    
    public function run_payment()
    {
        if (empty($this->session_id)) {
            $this->session_id = Session::getId();
        }

        $request     = request();
        $requestData = $request->all();

        $is_debug = false;
        if ($is_debug) {
            echo '<pre>$requestData::' . print_r($requestData, true) . '</pre>';
        }
        $loggedUser = Auth::user();
        if ( empty($loggedUser) ) {
            return redirect()->route('home-msg', [])->with([
                'text'   => 'You need login into the system !',
                'type'   => 'danger',
                'action' => 'show-login'
            ]);
        }

        if ($is_debug) {
            echo '<pre>$loggedUser::' . print_r($loggedUser, true) . '</pre>';
        }
        if ( empty($loggedUser->first_name) or
             empty($loggedUser->last_name or
             empty($loggedUser->address_line1) or
             empty($loggedUser->address_city) or
             empty($loggedUser->address_state) or
             empty($loggedUser->address_postal_code) or
             empty($loggedUser->address_country_code) or
             empty($loggedUser->shipping_address_line1) or
             empty($loggedUser->shipping_address_city) or
             empty($loggedUser->shipping_address_state) or
             empty($loggedUser->shipping_address_postal_code) or
             empty($loggedUser->shipping_address_country_code)
             ) ) {

            return redirect()->route('home-msg', [])->with([
                'text'   => 'To make payment fill your profile, including First name, Last name, Address, Shipping address !',
                'type'   => 'danger',
                'action' => 'show-profile'
            ]);
        }
        if ( $requestData['payment_type'] == 'stripe' ) {

            $viewParams = $appParamsForJSArray    = $this->getAppParameters(false, ['site_name', 'subscriptions_notification_email']);
            $viewParams['appParamsForJSArray']    = json_encode($appParamsForJSArray);
            $viewParams['medium_slogan_img_url']  = config('app.medium_slogan_img_url');

            $stripeConfig = config('services.stripe');
            if( empty($stripeConfig) ) {
                return redirect()->route('home-msg', [])->with([
                    'text'   => 'Stripe configuration is not filled !',
                    'type'   => 'danger',
                    'action' => ''
                ]);
            }
            $currency_stripe_code  = $this->getCurrency('stripe_code');
            $selectedItems_title  = '';
            $selectedItems        = [];
            $subtotal_value        = 0;
            $all_quantity          = 0;
            foreach ($requestData as $next_key => $next_value) {
                $a = $this->pregSplit('~cbx_download_~', $next_key);
                if (count($a) == 1 and ((int)$next_value == 1) and is_numeric($a[0])) { // get all checked services
                    $download = Download::find($a[0]);
                    if (empty($download)) {
                        continue;
                    }
                    if ( ! $download->active) {
                        continue;
                    }

                    $selectedItems_title .= $download->title.', ';
                    $selectedItems[]      = [
                        'id'        => $download->id,
                        'quantity'  => 1,
                        'sku'       => $download->id,
                        'title'     => $download->title,
                        'price'     => $download->price
                    ];
                    $subtotal_value       += $download->price;
                    $all_quantity++;
                }
            }
//            echo '<pre>$viewParams::'.print_r($viewParams,true).'</pre>';
//            die("-1 XXZ");
            $viewParams['selectedItems']             = $selectedItems;
            $viewParams['subtotal_value']            = $subtotal_value;
            $viewParams['all_quantity']              = $all_quantity;
            $viewParams['selected_items_title']      = $selectedItems_title;
            $viewParams['currency_stripe_code']      = $currency_stripe_code;
            $viewParams['stripe_public_key']         = $stripeConfig['key'];
            $viewParams['payment_description']       = !empty($requestData['payment_description']) ? $requestData['payment_description'] : '';

            $request->session()->put($this->stripe_payment_key, [
                'subtotal_value'        => $subtotal_value,
                'all_quantity'          => $all_quantity,
                'selected_items_title'  => $selectedItems_title,
                'selectedItems'         => $selectedItems,
                'currency_stripe_code'  => $currency_stripe_code,
                'payment_description'   => !empty($requestData['payment_description']) ? $requestData['payment_description'] : '',
            ]);

            return view($this->getFrontendTemplateName() . '.payment.stripe_checkout', $viewParams);
        }

        if ( $requestData['payment_type'] == 'paypal' ) {
            $payment = new CreatePayment($loggedUser);
            return $payment->Create($requestData);
        } // if ( $requestData['payment_type'] == 'paypal' ) {

    } // public function run_payment()


    public function paypal_payment_execute()
    {
        echo '<pre>paypal_payment_execute::' . print_r(-1, true) . '</pre>';
        $loggedUser = Auth::user();

//        $this->debToFile(print_r( ( !empty($loggedUser->id) ? $loggedUser->id : '' ), true) , ' paypal_payment_execute  -000 $loggedUser->id::');

        $newPayment= new ExecutePayment($loggedUser);
        try {
        $newPaymentResult= $newPayment->create();
//            exit(1);
        } catch (PayPal\Exception\PPConnectionException $pce) {
            echo '<pre>Error Block # 1</pre>';
            var_dump("-1:" . json_decode($pce->getData()));
            exit(1);
        } catch (PayPal\Exception\PayPalConnectionException $pce) {
            echo '<pre>Error Block # 2</pre>';
            var_dump("-2:" . json_decode($pce->getData()));
            exit(1);
        } catch (Exception $pce) {
//             Don't spit out errors or use "exit" like this in production code
            echo '<pre>Error Block # 3</pre>';
            var_dump("-3:" . json_decode($pce->getData()));
            exit(1);

        }

        if ( $newPaymentResult->state != 'approved' ) { // payment was successful
            return redirect()->route('home-msg', [])->with([
                'text'   => 'Your payment was successfully completed !',
                'type'   => 'danger',
                'action' => ''
            ]);
        }

        $newPaypalPayment= new \App\Payment();
        $newPaypalPayment->user_id= $loggedUser->id;
        $newPaypalPayment->status= 'C';
        $newPaypalPayment->payment_type= 'PC'; // PC => Paypal Checkout
        $newPaypalPayment->payment_status= $newPaymentResult->state;
        $newPaypalPayment->payment_description= substr($newPaymentResult->transactions[0]->description,0,255);
        $newPaypalPayment->invoice_number= $newPaymentResult->transactions[0]->invoice_number;

        $newPaypalPayment->payer_id= $newPaymentResult->payer->payer_info->payer_id;
//        $newPaypalPayment->payer_shipping_address= $newPaymentResult->payer->payer_info->shipping_address;
        $newPaypalPayment->payer_recipient_name= $newPaymentResult->payer->payer_info->recipient_name;

//        echo '<pre>$newPaymentResult->payer->payer_info->email::'.print_r($newPaymentResult->payer->payer_info->email,true).'</pre>';
        $newPaypalPayment->payer_email= $newPaymentResult->payer->payer_info->email;
        $newPaypalPayment->payer_first_name= $newPaymentResult->payer->payer_info->first_name;
        $newPaypalPayment->payer_last_name= $newPaymentResult->payer->payer_info->last_name;
        $newPaypalPayment->payer_middle_name= $newPaymentResult->payer->payer_info->middle_name;

        $newPaypalPayment->currency= $newPaymentResult->transactions[0]->amount->currency;
        $newPaypalPayment->total= $newPaymentResult->transactions[0]->amount->total;
        $newPaypalPayment->subtotal= $newPaymentResult->transactions[0]->amount->details->subtotal;
        $newPaypalPayment->tax= $newPaymentResult->transactions[0]->amount->details->tax;
        $newPaypalPayment->shipping= $newPaymentResult->transactions[0]->amount->details->shipping;


        $newPaypalPayment->payer_shipping_address= $newPaymentResult->payer->payer_info->shipping_address->line1;
        $newPaypalPayment->payer_recipient_name= $newPaymentResult->payer->payer_info->shipping_address->recipient_name;
        $newPaypalPayment->payer_city= $newPaymentResult->payer->payer_info->shipping_address->city;
        $newPaypalPayment->payer_state= $newPaymentResult->payer->payer_info->shipping_address->state;
        $newPaypalPayment->payer_postal_code= $newPaymentResult->payer->payer_info->shipping_address->postal_code;
        $newPaypalPayment->payer_country_code= $newPaymentResult->payer->payer_info->shipping_address->country_code;
        $newPaypalPayment->payer_business_name= $newPaymentResult->payer->payer_info->business_name;

        $itemsList= $newPaymentResult->transactions[0]->item_list;
//        dump($itemsList);

        DB::beginTransaction();
        try {
            $newPaypalPayment->save();

            foreach( $itemsList->items as $next_key=>$nextItem ) {
//                echo '<pre>$nextItem->sku::'.print_r($nextItem->sku,true).'</pre>';
                $newPaymentItem= new PaymentItem();
                $newPaymentItem->payment_id= $newPaypalPayment->id;
                $newPaymentItem->item_type= 'D';
                $newPaymentItem->quantity= $nextItem->quantity;
                $newPaymentItem->item_id= $nextItem->sku;
                $newPaymentItem->price= $nextItem->price;
                $newPaymentItem->save();
        }

            DB::commit();
//            die("-1 XXZ");
        } catch (\Exception $e) {
            DB::rollback();
            $this->setFlashMessage($e->getMessage(), 'danger');

            echo '<pre>$e->getMessage()::'.print_r($e->getMessage(),true).'</pre>';
            die("-1 EOOR");
            return Redirect
                ::back()
                ->withErrors([$e->getMessage()])
//                ->withInput(['name' => $request->get('name'), 'status' => $request->get('status'), 'description' => $request->get('description')])
                ;
        }
        return redirect()->route('home-msg', [])->with([
            'text'   => 'Your payment was successfully completed !',
            'type'   => 'success',
            'action' => ''
        ]);

//
//        $this->setFlashMessage('Your payment was successfully completed !', 'success');
//        die("-1 SUCCESS");
        //return redirect()->route('home');

    } // public function paypal_payment_execute()

    public function paypal_payment_cancel()
    {
        echo '<pre>paypal_payment_cancel::' . print_r(-1, true) . '</pre>';
        die("-1 XXZ paypal_payment_cancel app/Http/Controllers/PaymentController.php");
    }


    public function paypal_list_plan() // http://local-votes.com/paypal_list_plan
    {
        echo '<pre>list_plan' . print_r(-543, true) . '</pre>';
        $loggedUser = Auth::user();
        
        $plan = new SubscriptionPlan($loggedUser);
        $listPlan= $plan->listPlan();
        $paypalPlansList= $listPlan->plans;
        dd($paypalPlansList);
//        $plan->create();
    }


    public function paypal_plan_detail($plan_id) // http://local-votes.com/paypal_plan_detail/P-8DC818364R7167805ANXTMPY
    {
        echo '<pre>list_plan' . print_r(-543, true) . '</pre>';
        $loggedUser = Auth::user();

        $plan = new SubscriptionPlan($loggedUser);
        return $plan->planDetail($plan_id);
//        $plan->create();
    }


    public function paypal_activate_plan($plan_id) // http://local-votes.com/paypal_activate_plan/P-8DC818364R7167805ANXTMPY
    {
        echo '<pre>list_plan' . print_r(-543, true) . '</pre>';
        $loggedUser = Auth::user();

        $plan = new SubscriptionPlan($loggedUser);
        return $plan->activate($plan_id);
//        $plan->create();
    }


}
