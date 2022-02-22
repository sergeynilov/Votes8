<?php


namespace App\Paypal;
use Auth;


use App\Http\Traits\funcsTrait;
//use PayPal\Api\Amount;
//use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
//use PayPal\Api\Transaction;

class ExecutePayment extends PaypalConnection
{

    use funcsTrait;

    private $payment;
    public function create() {


        $is_debug= false;
        if ($is_debug) {
            echo '<pre>paymentId::' . print_r(request('paymentId'), true) . '</pre>';
        }
        // After Step 1


        $loggedUser= Auth::user();
//        $this->debToFile(print_r( ( !empty($loggedUser->id) ? $loggedUser->id : '') , true), ' GetThePayment  -0SA $loggedUser->id::');

        $this->payment = $this->GetThePayment();
//
//        $this->debToFile(print_r($this->payment, true), ' ExecutePayment::Create  -00 $this->payment::');
//        echo '<pre>$this->payment->transactions::'.print_r($this->payment->transactions,true).'</pre>';
//        echo '<pre>$this->payment->transactions[0]->amount::'.print_r($this->payment->transactions[0]->amount,true).'</pre>';
//        echo '<pre>$this->payment->transactions[0]->amount->total::'.print_r($this->payment->transactions[0]->amount->total,true).'</pre>';
//        echo '<pre>$this->payment->transactions[0]->amount->currency::'.print_r($this->payment->transactions[0]->amount->currency,true).'</pre>';
//        echo '<pre>$this->payment->transactions[0]->amount->details::'.print_r($this->payment->transactions[0]->amount->details,true).'</pre>';
//        echo '<pre>$this->payment->transactions[0]->amount->details->subtotal::'.print_r($this->payment->transactions[0]->amount->details->subtotal,true).'</pre>';
//        echo '<pre>$this->payment->transactions[0]->amount->details->tax::'.print_r($this->payment->transactions[0]->amount->details->tax,true).'</pre>';
//        echo '<pre>$this->payment->transactions[0]->amount->details->shipping::'.print_r($this->payment->transactions[0]->amount->details->shipping,true).'</pre>';
//
//        [subtotal] => 1.20
//            [tax] => 0.00
//            [shipping] => 0.20
//        die("-1 XXZ");

        $PayerID = request('PayerID');
        if ($is_debug) {
            echo '<pre>$PayerID::' . print_r($PayerID, true) . '</pre>';
        }

        $execution = $this->CreateExecution($is_debug, $PayerID);


//        $execution->addTransaction( [$this->transaction()] );  // I found that here must be an array?

        try {
            $result = $this->payment->execute($execution, $this->apiContext);
//        } catch (PayPal\Exception\PPConnectionException $pce) {
        } catch (PayPal\Exception\PayPalConnectionException $pce) {
//             Don't spit out errors or use "exit" like this in production code
            echo '<pre>::'.print_r( json_decode($pce->getData()) );
            die("-1 XXZ  app/Http/Controllers/PaymentController.php");
        }
//        echo '<pre>$result::'.print_r($result,true).'</pre>';
//        die("-1 XXZ");

        return $result;
    }

    protected function GetThePayment(): Payment
    {
        $this->paymentId = request('paymentId');

        $loggedUser= Auth::user();
//        $this->debToFile(print_r(  ( !empty($loggedUser->id) ? $loggedUser->id : '') , true), ' GetThePayment  -0SB $loggedUser->id::');

//        $this->debToFile(print_r($this->apiContext, true), ' GetThePayment  -00 $this->apiContext::');
        $this->payment   = Payment::get($this->paymentId, $this->apiContext);
//        $this->debToFile(print_r($this->payment, true), ' GetThePayment  -01 $this->payment::');


        $loggedUser= Auth::user();
//        $this->debToFile(print_r(  ( !empty($loggedUser->id) ? $loggedUser->id : '') , true), ' GetThePayment  -0SC $loggedUser->id::');
        return $this->payment;
    }

    protected function CreateExecution(bool $is_debug, $PayerID): PaymentExecution
    {
        $execution = new PaymentExecution();
        if ($is_debug) {
            echo '<pre>$execution::' . print_r($execution, true) . '</pre>';
        }
        $execution->setPayerId($PayerID);

        return $execution;
    }


}