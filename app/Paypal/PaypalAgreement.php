<?php

namespace App\Paypal;

use Auth;
use Carbon\Carbon;
use App\Http\Traits\funcsTrait;
use App\Settings;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;

// http://votes.my-demo-apps.tk/admin/paypal_create_agreement/P-527143190V596391WQQQR52I
// http://local-votes.com/admin/paypal_create_agreement/P-6PN27771HX042073KG5XOOWQ

class PaypalAgreement extends PaypalConnection
{
    use funcsTrait;
    private $loggedUser;
    private $site_name;


    public function Create($plan_id)
    {
        echo '<pre>-z1</pre>';
        if ( !Auth::check() ) {
            return redirect()->route('home-msg', [])->with([
                'text'   => 'You need to login into the system !',
                'type'   => 'danger',
                'action' => 'show-login'
            ]);
        }

        $this->loggedUser      = Auth::user();

        if ( empty($this->loggedUser->shipping_address_line1) or empty($this->loggedUser->shipping_address_city) or
             empty($this->loggedUser->shipping_address_state) or empty($this->loggedUser->shipping_address_postal_code) or
             empty($this->loggedUser->shipping_address_country_code) ) {
            return redirect()->route('home-msg', [])->with([
                'text'   => 'To subscribe paypal agreement you need to fill all your shipping fields !',
                'type'   => 'danger',
                'action' => 'show-profile'
            ]);
        }

        $this->site_name       = Settings::getValue('site_name');

        return redirect($this->Agreement($plan_id));
    }

    /**
     * @return Agreement
     */
    protected function Agreement($plan_id) : string
    {
        $date = Carbon::now(config('app.timezone'))->addHour();
//        $this->debToFile(print_r($date->format('Y-m-d\TH:i:s.uP T'), true), '-1:');
        $this->debToFile(print_r($date->format('Y-m-d\\TH:i:s\\Z'), true), '-2:');
        $formatted_start_date= $date->format('Y-m-d\\TH:i:s\\Z');
        $this->debToFile(print_r($formatted_start_date, true), '- $formatted_start_date -3 :');

//        die("-1 XXZ");
        // echo Carbon::createFromTimestampMs(1)->format('Y-m-d\TH:i:s.uP T');

//        $startDate = date('Y-m-d\\TH:i:s\\Z', $time);
        $agreement = new Agreement();
        /* //        $this->loggedUser      = Auth::user();
//        $this->site_name       = Settings::getValue('site_name');  */
        $agreement->setName('Base Agreement on ' . $this->site_name)
                  ->setDescription('Base Agreement on ' . $this->site_name . ' lorem ipsum dolor sit amet, consectetur adipiscing elit')
                  ->setStartDate($formatted_start_date);

        
        $plan = new Plan();
        $plan->setId($plan_id);
        $agreement->setPlan($plan);


        // Add Payer
        $payer = new Payer();  // Who is Payer here ?
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        $shippingAddress = new ShippingAddress();
        $shippingAddress->setLine1($this->loggedUser->shipping_address_line1)
                        ->setCity($this->loggedUser->shipping_address_city)
                        ->setState($this->loggedUser->shipping_address_state)
                        ->setPostalCode($this->loggedUser->shipping_address_postal_code)
                        ->setCountryCode($this->loggedUser->shipping_address_country_code);
        $agreement->setShippingAddress($shippingAddress);
        $agreement = $agreement->create($this->apiContext);
//        Get redirect url
//The API response provides the url that you must redirect the buyer to. Retrieve the url from the $agreement->getApprovalLink() method

//        $agreement = $agreement->create($this->apiContext);
//        Get redirect url
//The API response provides the url that you must redirect the buyer to. Retrieve the url from the $agreement->getApprovalLink() method

//        $approvalUrl = $agreement->getApprovalLink();

        $approvalUrl = $agreement->getApprovalLink();
        return $approvalUrl;
//        return redirect($approvalUrl);

//        return $agreement;
    }

    public function execute($token)
    {

//        $token     = $_GET['token'];
//        $agreement = new \PayPal\Api\Agreement();
        $agreement = new Agreement();
        $ret= $agreement->execute($token, $this->apiContext);
//        echo '<pre>$ret::'.print_r($ret,true).'</pre>';
        return $ret;
        /* $ret::PayPal\Api\Agreement Object
(
    [_propMap:PayPal\Common\PayPalModel:private] => Array
        (
            [id] => I-R95NCPNYM03N
            [state] => Pending
            [description] => Base Agreement on Select & Vote lorem ipsum dolor sit amet, consectetur adipiscing elit
            [start_date] => 2019-08-17T07:00:00Z
            [payer] => PayPal\Api\Payer Object
                (
                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                        (
                            [payment_method] => paypal
                            [status] => unverified
                            [payer_info] => PayPal\Api\PayerInfo Object
                                (
                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                        (
                                            [email] => mstdtestpp@gmail.com
                                            [first_name] => Sergey
                                            [last_name] => Nilov
                                            [payer_id] => Z9U8EWL3EEAAC
                                            [shipping_address] => PayPal\Api\ShippingAddress Object
                                                (
                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                        (
                                                            [recipient_name] => Sergey Nilov
                                                            [line1] => First Street, ap 12b
                                                            [city] => Saratoga
                                                            [state] => CA
                                                            [postal_code] => 95070
                                                            [country_code] => US
                                                        )

                                                )

                                        )

                                )

                        )

                )

            [shipping_address] => PayPal\Api\Address Object
                (
                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                        (
                            [recipient_name] => Sergey Nilov
                            [line1] => First Street, ap 12b
                            [city] => Saratoga
                            [state] => CA
                            [postal_code] => 95070
                            [country_code] => US
                        )

                )

            [plan] => PayPal\Api\Plan Object
                (
                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                        (
                            [payment_definitions] => Array
                                (
                                    [0] => PayPal\Api\PaymentDefinition Object
                                        (
                                            [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                (
                                                    [type] => REGULAR
                                                    [frequency] => Month
                                                    [amount] => PayPal\Api\Currency Object
                                                        (
                                                            [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                                (
                                                                    [value] => 23.00
                                                                )

                                                        )

                                                    [cycles] => 12
                                                    [charge_models] => Array
                                                        (
                                                            [0] => PayPal\Api\ChargeModel Object
                                                                (
                                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                                        (
                                                                            [type] => TAX
                                                                            [amount] => PayPal\Api\Currency Object
                                                                                (
                                                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                                                        (
                                                                                            [value] => 0.00
                                                                                        )

                                                                                )

                                                                        )

                                                                )

                                                            [1] => PayPal\Api\ChargeModel Object
                                                                (
                                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                                        (
                                                                            [type] => SHIPPING
                                                                            [amount] => PayPal\Api\Currency Object
                                                                                (
                                                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                                                        (
                                                                                            [value] => 23.00
                                                                                        )

                                                                                )

                                                                        )

                                                                )

                                                        )

                                                    [frequency_interval] => 1
                                                )

                                        )

                                )

                            [merchant_preferences] => PayPal\Api\MerchantPreferences Object
                                (
                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                        (
                                            [setup_fee] => PayPal\Api\Currency Object
                                                (
                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                        (
                                                            [value] => 1.00
                                                        )

                                                )

                                            [max_fail_attempts] => 0
                                            [auto_bill_amount] => YES
                                        )

                                )

                            [currency_code] => USD
                        )

                )

            [links] => Array
                (
                    [0] => PayPal\Api\Links Object
                        (
                            [_propMap:PayPal\Common\PayPalModel:private] => Array
                                (
                                    [href] => https://api.sandbox.paypal.com/v1/payments/billing-agreements/I-R95NCPNYM03N
                                    [rel] => self
                                    [method] => GET
                                )

                        )

                )

            [agreement_details] => PayPal\Api\AgreementDetails Object
                (
                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                        (
                            [outstanding_balance] => PayPal\Api\Currency Object
                                (
                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                        (
                                            [value] => 0.00
                                        )

                                )

                            [cycles_remaining] => 12
                            [cycles_completed] => 0
                            [final_payment_date] => 2020-07-17T10:00:00Z
                            [failed_payment_count] => 0
                        )

                )

        ) */
    } // public function execute($token)



    // http://paypal.github.io/PayPal-PHP-SDK/sample/doc/billing/GetBillingAgreement.html

/*$createdAgreement = "your billing agreement id";

    use PayPal\Api\Agreement;

try {
$agreement = Agreement::get($createdAgreement->getId(), $apiContext);
} catch (Exception $ex) {

    ResultPrinter::printError("Retrieved an Agreement", "Agreement", $agreement->getId(), $createdAgreement->getId(), $ex);
    exit(1);
}

ResultPrinter::printResult("Retrieved an Agreement", "Agreement", $agreement->getId(), $createdAgreement->getId(), $agreement);

return $agreement;*/

    public function agreementDetail($agreement_id) { // http://paypal.github.io/PayPal-PHP-SDK/sample/doc/billing/GetBillingAgreement.html

//        echo '<pre>planDetail  $agreement_id::' . print_r($agreement_id, true) . '</pre>';
        $agreementDetailsArray= [];
        $agreementDetails = Agreement::get($agreement_id, $this->apiContext);

        $payer= $agreementDetails->payer;
//        echo '<pre>$payer::'.print_r($payer,true).'</pre>';

        $shipping_address= $agreementDetails->shipping_address;
//        echo '<pre>$shipping_address::'.print_r($shipping_address,true).'</pre>';

        $plan= $agreementDetails->plan;
//        echo '<pre>$plan::'.print_r($plan,true).'</pre>';

        $agreement_details= $agreementDetails->agreement_details;
//        echo '<pre>$agreement_details::'.print_r($agreement_details,true).'</pre>';

//        $agreement_details= $agreementDetails->agreement_details;
//        echo '<pre>$agreement_details::'.print_r($agreement_details,true).'</pre>';
        $agreementDetailsArray= [
            'agreement_id'  => $agreementDetails->id,
            'state'         => $agreementDetails->state,
            'description'   => $agreementDetails->description,
            'start_date'    => $agreementDetails->start_date,
            'payer_recipient_name'=> !empty($payer->payer_info->shipping_address->recipient_name),
            'payer_line1'=> !empty($payer->payer_info->shipping_address->line1) ? $payer->payer_info->shipping_address->line1 : '',
            'payer_city'=> !empty($payer->payer_info->shipping_address->city) ? $payer->payer_info->shipping_address->city : '',
            'payer_state'=> !empty($payer->payer_info->shipping_address->state) ? $payer->payer_info->shipping_address->state : '',
            'payer_postal_code'=> !empty($payer->payer_info->shipping_address->postal_code) ?
                $payer->payer_info->shipping_address->postal_code : '',
            'payer_country_code'=> !empty($payer->payer_info->shipping_address->country_code) ?
                $payer->payer_info->shipping_address->country_code : '',


            'shipping_address_recipient_name'=> !empty($shipping_address->recipient_name) ? $shipping_address->recipient_name : '',
            'shipping_address_line1'=> !empty($shipping_address->line1) ? $shipping_address->line1 : '',
            'shipping_address_city'=> !empty($shipping_address->city) ? $shipping_address->city : '',
            'shipping_address_state'=> !empty($shipping_address->state) ? $shipping_address->state : '',
            'shipping_address_postal_code'=> !empty($shipping_address->postal_code) ? $shipping_address->postal_code : '',
            'shipping_address_country_code'=> !empty($shipping_address->country_code) ? $shipping_address->country_code : '',


            'plan_payment_definitions_type'=> !empty($plan->payment_definitions[0]->type) ? $plan->payment_definitions[0]->type : '',
            'plan_payment_definitions_frequency'=> !empty($plan->payment_definitions[0]->frequency) ? $plan->payment_definitions[0]->frequency : '',
            'plan_payment_definitions_amount_currency'=> !empty($plan->payment_definitions[0]->amount->currency) ? $plan->payment_definitions[0]->amount->currency : '',
            'plan_payment_definitions_amount_value'=> !empty($plan->payment_definitions[0]->amount->value) ? $plan->payment_definitions[0]->amount->value : '',

            'agreement_details_outstanding_balance_currency'=> !empty($agreement_details->outstanding_balance->currency) ? $agreement_details->outstanding_balance->currency : '',
            'agreement_details_outstanding_balance_value'=> !empty($agreement_details->outstanding_balance->value) ? $agreement_details->outstanding_balance->value : '',

            'agreement_details_cycles_remaining'=> !empty($agreement_details->cycles_remaining) ? $agreement_details->cycles_remaining : '',
            'agreement_details_cycles_completed'=> !empty($agreement_details->cycles_completed) ? $agreement_details->cycles_completed : '',
            'agreement_details_next_billing_date'=> !empty($agreement_details->next_billing_date) ? $agreement_details->next_billing_date : '',
            'agreement_details_last_payment_date'=> !empty($agreement_details->last_payment_date) ? $agreement_details->last_payment_date : '',
            'agreement_details_last_payment_amount_currency'=> !empty($agreement_details->last_payment_amount->currency) ? $agreement_details->last_payment_amount->currency : '',
            'agreement_details_last_payment_amount_value'=> !empty($agreement_details->last_payment_amount->value) ? $agreement_details->last_payment_amount->value : '',

            'agreement_details_final_payment_date'=> !empty($agreement_details->final_payment_date) ? $agreement_details->final_payment_date : '',
            'agreement_details_failed_payment_count'=> !empty($agreement_details->failed_payment_count) ? $agreement_details->failed_payment_count : '0',

            /*  )
</pre><pre>$agreement_details::PayPal\Api\AgreementDetails Object
(
    [_propMap:PayPal\Common\PayPalModel:private] => Array
        (
            [outstanding_balance] => PayPal\Api\Currency Object
                (
                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                        (
                            [currency] => USD
                            [value] => 0.00
                        )

                )

            [cycles_remaining] => 11
            [cycles_completed] => 1
            [next_billing_date] => 2019-09-17T10:00:00Z
            [last_payment_date] => 2019-08-17T14:57:30Z
            [last_payment_amount] => PayPal\Api\Currency Object
                (
                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                        (
                            [currency] => USD
                            [value] => 46.00
                        )

                )

            [final_payment_date] => 2020-07-17T10:00:00Z
            [failed_payment_count] => 0
        )

)   */
        ];
//        echo '<pre>$agreementDetailsArray::'.print_r($agreementDetailsArray,true).'</pre>';
        return $agreementDetailsArray;
        /* <pre>planDetail  $agreement_id::I-AF0P6DF9Y3BE</pre><pre>$paymentAgreementDetails::PayPal\Api\Agreement Object
(
    [_propMap:PayPal\Common\PayPalModel:private] => Array
        (
            [id] => I-AF0P6DF9Y3BE
            [state] => Active
            [description] => Base Agreement on Select & Vote lorem ipsum dolor sit amet, consectetur adipiscing elit
            [start_date] => 2019-08-17T07:00:00Z
            [payer] => PayPal\Api\Payer Object
                (
                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                        (
                            [payment_method] => paypal
                            [status] => unverified
                            [payer_info] => PayPal\Api\PayerInfo Object
                                (
                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                        (
                                            [email] => mstdtestpp@gmail.com
                                            [first_name] => Sergey
                                            [last_name] => Nilov
                                            [payer_id] => Z9U8EWL3EEAAC
                                            [shipping_address] => PayPal\Api\ShippingAddress Object
                                                (
                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                        (
                                                            [recipient_name] => Sergey Nilov
                                                            [line1] => First Street, ap 12b
                                                            [city] => Saratoga
                                                            [state] => CA
                                                            [postal_code] => 95070
                                                            [country_code] => US
                                                        )

                                                )

                                        )

                                )

                        )

                )

            [shipping_address] => PayPal\Api\Address Object
                (
                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                        (
                            [recipient_name] => Sergey Nilov
                            [line1] => First Street, ap 12b
                            [city] => Saratoga
                            [state] => CA
                            [postal_code] => 95070
                            [country_code] => US
                        )

                )

            [plan] => PayPal\Api\Plan Object
                (
                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                        (
                            [payment_definitions] => Array
                                (
                                    [0] => PayPal\Api\PaymentDefinition Object
                                        (
                                            [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                (
                                                    [type] => REGULAR
                                                    [frequency] => Month
                                                    [amount] => PayPal\Api\Currency Object
                                                        (
                                                            [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                                (
                                                                    [currency] => USD
                                                                    [value] => 23.00
                                                                )

                                                        )

                                                    [cycles] => 12
                                                    [charge_models] => Array
                                                        (
                                                            [0] => PayPal\Api\ChargeModel Object
                                                                (
                                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                                        (
                                                                            [type] => TAX
                                                                            [amount] => PayPal\Api\Currency Object
                                                                                (
                                                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                                                        (
                                                                                            [currency] => USD
                                                                                            [value] => 0.00
                                                                                        )

                                                                                )

                                                                        )

                                                                )

                                                            [1] => PayPal\Api\ChargeModel Object
                                                                (
                                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                                        (
                                                                            [type] => SHIPPING
                                                                            [amount] => PayPal\Api\Currency Object
                                                                                (
                                                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                                                        (
                                                                                            [currency] => USD
                                                                                            [value] => 23.00
                                                                                        )

                                                                                )

                                                                        )

                                                                )

                                                        )

                                                    [frequency_interval] => 1
                                                )

                                        )

                                )

                            [merchant_preferences] => PayPal\Api\MerchantPreferences Object
                                (
                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                        (
                                            [setup_fee] => PayPal\Api\Currency Object
                                                (
                                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                                        (
                                                            [currency] => USD
                                                            [value] => 1.00
                                                        )

                                                )

                                            [max_fail_attempts] => 0
                                            [auto_bill_amount] => YES
                                        )

                                )

                        )

                )

            [links] => Array
                (
                    [0] => PayPal\Api\Links Object
                        (
                            [_propMap:PayPal\Common\PayPalModel:private] => Array
                                (
                                    [href] => https://api.sandbox.paypal.com/v1/payments/billing-agreements/I-AF0P6DF9Y3BE/suspend
                                    [rel] => suspend
                                    [method] => POST
                                )

                        )

                    [1] => PayPal\Api\Links Object
                        (
                            [_propMap:PayPal\Common\PayPalModel:private] => Array
                                (
                                    [href] => https://api.sandbox.paypal.com/v1/payments/billing-agreements/I-AF0P6DF9Y3BE/re-activate
                                    [rel] => re_activate
                                    [method] => POST
                                )

                        )

                    [2] => PayPal\Api\Links Object
                        (
                            [_propMap:PayPal\Common\PayPalModel:private] => Array
                                (
                                    [href] => https://api.sandbox.paypal.com/v1/payments/billing-agreements/I-AF0P6DF9Y3BE/cancel
                                    [rel] => cancel
                                    [method] => POST
                                )

                        )

                    [3] => PayPal\Api\Links Object
                        (
                            [_propMap:PayPal\Common\PayPalModel:private] => Array
                                (
                                    [href] => https://api.sandbox.paypal.com/v1/payments/billing-agreements/I-AF0P6DF9Y3BE/bill-balance
                                    [rel] => self
                                    [method] => POST
                                )

                        )

                    [4] => PayPal\Api\Links Object
                        (
                            [_propMap:PayPal\Common\PayPalModel:private] => Array
                                (
                                    [href] => https://api.sandbox.paypal.com/v1/payments/billing-agreements/I-AF0P6DF9Y3BE/set-balance
                                    [rel] => self
                                    [method] => POST
                                )

                        )

                )

            [agreement_details] => PayPal\Api\AgreementDetails Object
                (
                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                        (
                            [outstanding_balance] => PayPal\Api\Currency Object
                                (
                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                        (
                                            [currency] => USD
                                            [value] => 0.00
                                        )

                                )

                            [cycles_remaining] => 11
                            [cycles_completed] => 1
                            [next_billing_date] => 2019-09-17T10:00:00Z
                            [last_payment_date] => 2019-08-17T14:57:30Z
                            [last_payment_amount] => PayPal\Api\Currency Object
                                (
                                    [_propMap:PayPal\Common\PayPalModel:private] => Array
                                        (
                                            [currency] => USD
                                            [value] => 46.00
                                        )

                                )

                            [final_payment_date] => 2020-07-17T10:00:00Z
                            [failed_payment_count] => 0
                        )

                )

        )

)
</pre>{"error_code":0,"message":"","paymentAgreementDetails":{}} */
    } // public function agreementDetail($agreement_id) {

}