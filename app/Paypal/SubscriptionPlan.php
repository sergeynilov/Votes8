<?php

namespace App\Paypal;

use App\Http\Traits\funcsTrait;
use Auth;
use DB;

use App\Settings;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;

use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;

class SubscriptionPlan extends PaypalConnection
{
    use funcsTrait;
    private $loggedUser;
    private $site_name;
    private $planDataArray;
    private $currency_code;
    /* https://www.youtube.com/watch?v=nnmt8-9jUTI
    http://paypal.github.io/PayPal-PHP-SDK/sample/doc/billing/CreatePlan.html
    https://developer.paypal.com/docs/subscriptions/integrate/#3-create-a-plan
     */

    public function Create($planDataArray)
    {

//        echo '<pre>-z1</pre>';
        if ( ! Auth::check()) {
            return redirect()->route('home-msg', [])->with([
                'text'   => 'You need to login into the system !',
                'type'   => 'danger',
                'action' => 'show-login'
            ]);
        }

        $this->currency_code = $this->getCurrency('code');
        $this->planDataArray = $planDataArray;
        $this->loggedUser    = Auth::user();
        $this->site_name     = Settings::getValue('site_name');
//        echo '<pre>-z2</pre>';

        $plan = $this->Plan();

        $paymentDefinition = $this->PaymentDefinition();

        $chargeModel = $this->ChargeModel();
//        echo '<pre>-z3</pre>';


        $paymentDefinition->setChargeModels(array($chargeModel));

        $merchantPreferences = new MerchantPreferences();
//        $baseUrl             = getBaseUrl();
//        echo '<pre>-z4</pre>';


        $this->MerchantPreferences($merchantPreferences);


        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);
//        For Sample Purposes Only.
//        $request = clone $plan;


//        try {
        $output = $plan->create($this->apiContext);
//        $this->debToFile(print_r($output, true), '  SubscriptionPlan::Create  - $output::');

//        dd($output);
//        } catch (Exception $ex) {
////            NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//
//            ResultPrinter::printError("Created Plan", "Plan", null, $request, $ex);
//            exit(1);
//        }


//        ResultPrinter::printResult("Created Plan", "Plan", $output->getId(), $request, $output);

        return $output;

    }

    /**
     * @return Plan
     */
    protected function Plan(): Plan
    {
        $plan = new Plan();
//        $plan->setName('T-Shirt of the Month Club Plan')
        $plan->setName($this->planDataArray['name'])
             ->setDescription($this->planDataArray['description'])
//             ->setDescription('Template creation.')
             ->setType('fixed');   // TODO

        return $plan;
    }

    /**
     * @return PaymentDefinition
     */
    protected function PaymentDefinition(): PaymentDefinition
    {
        $paymentDefinition = new PaymentDefinition();

        //  $paymentDefinition->setName('Regular Payments')
//        echo '<pre>$this->planDataArray[\'price_period\']::'.print_r($this->planDataArray['price_period'],true).'</pre>';
        $frequency_label = $this->setFrequencyLabel($this->planDataArray['price_period']);
//        echo '<pre>$frequency_label::'.print_r($frequency_label,true).'</pre>';
        $paymentDefinition->setName($this->planDataArray['name'])// 1 year plan monthly
                          ->setType('REGULAR')
//                          ->setFrequency('Month')
                          ->setFrequency($frequency_label)
                          ->setFrequencyInterval("1")
                          ->setCycles("12")
                          ->setAmount(new Currency(array('value' => $this->planDataArray['price'], 'currency' => $this->currency_code)));

        return $paymentDefinition;
    }

    /**
     * @return ChargeModel
     */
    protected function ChargeModel(): ChargeModel
    {
        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')
                    ->setAmount(new Currency(array('value' => $this->planDataArray['price'], 'currency' => $this->currency_code)));

        return $chargeModel;
    }

    /**
     * @param MerchantPreferences $merchantPreferences
     */
    protected function MerchantPreferences(MerchantPreferences $merchantPreferences): void
    {
        $app_url = config('app.url');
        //http://votes.my-demo-apps.tk//admin/paypal_plan_success?token=EC-804610684X9649344
//        $merchantPreferences->setReturnUrl($app_url . '/admin/execute-agreement/true')
        $merchantPreferences->setReturnUrl($app_url . '/admin/paypal_plan_success')
                            ->setCancelUrl($app_url . '/admin/paypal_plan_failure')
                            ->setAutoBillAmount("yes")
                            ->setInitialFailAmountAction("CONTINUE")
                            ->setMaxFailAttempts("0")// no retry in case of error
                            ->setSetupFee(new Currency(array('value' => 1, 'currency' => $this->currency_code)));
    }

    public function listPlan() // http://local-votes.com/admin/paypal_plan
    {   // http://paypal.github.io/PayPal-PHP-SDK/sample/doc/billing/ListPlans.html
        $params   = array('page_size' => '20', 'status'=>'ALL');  // get Plans list with all status  
        $planList = Plan::all($params, $this->apiContext);
        return $planList;
    }


    public function planDetail($plan_id)
    { // http://paypal.github.io/PayPal-PHP-SDK/sample/doc/billing/GetPlan.html

//        echo '<pre>planDetail  $plan_id::' . print_r($plan_id, true) . '</pre>';
        $plan = Plan::get($plan_id, $this->apiContext);

        return $plan;
    }

    public function activate($plan_id) // http://paypal.github.io/PayPal-PHP-SDK/sample/doc/billing/UpdatePlan.html
    {
        $patch = new Patch();
        $value = new PayPalModel('{
	       "state":"ACTIVE"
	     }');

        $createdPlan = $this->planDetail($plan_id);
        $patch->setOp('replace')
              ->setPath('/')
              ->setValue($value);
        $patchRequest = new PatchRequest();
        $patchRequest->addPatch($patch);

        try {
            $createdPlan->update($patchRequest, $this->apiContext);
            $plan = Plan::get($createdPlan->getId(), $this->apiContext);
        } catch (Exception $ex) {
            return false;
        }

        return $plan;
    } // public function activate($plan_id)

    public function deactivate($plan_id) // http://paypal.github.io/PayPal-PHP-SDK/sample/doc/billing/UpdatePlan.html
    {
        $patch = new Patch();
        $value = new PayPalModel('{
	       "state":"INACTIVE"
	     }');

        $createdPlan = $this->planDetail($plan_id);
        $patch->setOp('replace')
              ->setPath('/')
              ->setValue($value);
        $patchRequest = new PatchRequest();
        $patchRequest->addPatch($patch);

        try {
            $createdPlan->update($patchRequest, $this->apiContext);
            $plan = Plan::get($createdPlan->getId(), $this->apiContext);
        } catch (Exception $ex) {
            return false;
        }

        return $plan;
    } // public function deactivate($plan_id)

    public function delete($plan_id)   // http://paypal.github.io/PayPal-PHP-SDK/sample/doc/billing/DeletePlan.html
    {
        $existingPlan = $this->planDetail($plan_id);
//        $this->debToFile(print_r($existingPlan, true), '  SubscriptionPlan::?? Delete  - $existingPlan::');

        try {
            $result = $existingPlan->delete($this->apiContext);
        } catch (Exception $ex) {
            return false;
        }

        return $result;
    } // public function delete($plan_id)


    protected function setFrequencyLabel($frequency)
    {
        if (strtoupper($frequency) == 'D') { // TODO   // DAY, WEEK, MONTH or YEAR.
            return 'DAY';
        }

        if (strtoupper($frequency) == 'W') { // TODO   // DAY, WEEK, MONTH or YEAR.
            return 'WEEK';
        }

        if (strtoupper($frequency) == 'M') { // TODO   // DAY, WEEK, MONTH or YEAR.
            return 'MONTH';
        }

        if (strtoupper($frequency) == 'Y') { // TODO   // DAY, WEEK, MONTH or YEAR.
            return 'YEAR';
        }

        return $frequency;
    }

    //http://paypal.github.io/PayPal-PHP-SDK/sample/doc/billing/CreateBillingAgreementWithPayPal.html
}