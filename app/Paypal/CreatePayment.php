<?php

namespace App\Paypal;

use Auth;
use DB;
use App\Settings;
use App\User;
use App\Http\Traits\funcsTrait;
use App\Download;

use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;

use PayPal\Api\Amount;
use PayPal\Api\Details;
//use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

use PayPal\Api\RedirectUrls;

class CreatePayment extends PaypalConnection
{  // https://www.youtube.com/watch?v=UNSIDciHStQ 1:45
    use funcsTrait;


    private $loggedUser;
    private $payment_description;
    private $site_name;
    private $shipping_value;
    private $tax_value;
    private $subtotal_value;
    private $currency_code;

    public function create($requestData) {
        if ( !Auth::check() ) {
            return redirect()->route('home-msg', [])->with([
                'text'   => 'You need to login into the system !',
                'type'   => 'danger',
                'action' => 'show-login'
            ]);
        }

        $this->loggedUser      = Auth::user();
        $this->payment_description       = !empty($requestData['payment_description']) ? $requestData['payment_description'] : '';
        $this->site_name       = Settings::getValue('site_name');
        $this->shipping_value  = config('app.payment_shipping_value', 0);
        $this->tax_value       = config('app.payment_tax_value', 0);
        $this->subtotal_value  = 0;
        $this->currency_code   = $this->getCurrency('code');

        $itemList              = new ItemList();
        foreach ($requestData as $next_key => $next_value) {
            $a = $this->pregSplit('~cbx_download_~', $next_key);
            if (count($a) == 1 and ((int)$next_value == 1) and is_numeric($a[0])) { // get all checked site_subscriptions
                $download = Download::find($a[0]);
                if (empty($download)) {
                    continue;
                }
                if ( ! $download->active) {
                    continue;
                }

                $this->subtotal_value+= $download->price;  // UNCOMMENT
                $newItem = new Item();
                $newItem->setName($download->title)
                        ->setCurrency($this->currency_code)
                        ->setQuantity(1)
                        ->setSku($download->id)// Similar to `item_number` in Classic API
                        ->setPrice($download->price);
                $itemList->addItem($newItem);

            }
        }
//        die("-1 XXZ");
        $payment = $this->Payment($itemList);

//        $request = clone $payment;


        try {
            $payment->create($this->apiContext);
        } catch (Exception $ex) {

//            ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
//            exit(1);
        }

//        $this->debToFile(print_r($payment, true), ' CreatePayment::Create  -00 $payment::');
        /* invoice_number  5d2d74a05a69f

        "id":"PAYID-LUWXJIQ7J906445PJ6511034
         */


        $approvalUrl = $payment->getApprovalLink();
//        if ($is_debug) {
//            echo '<pre>$approvalUrl::' . print_r($approvalUrl, true) . '</pre>';
//        }

        return redirect($approvalUrl);

    }

    protected function Payer(): Payer
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        return $payer;
    }

    protected function Details(): Details
    {
        $details = new Details();
        $details->setShipping($this->shipping_value)
                ->setTax($this->tax_value)
                ->setSubtotal($this->subtotal_value);

        return $details;
    }

    protected function Amount(): Amount
    {
        $amount = new Amount();
        $amount->setCurrency($this->currency_code)
               ->setTotal($this->shipping_value + $this->tax_value + $this->subtotal_value)
               ->setDetails($this->Details());

        return $amount;
    }

    protected function Transaction(ItemList $itemList): Transaction
    {
        $payment_description= "Payment at " . $this->site_name . ' site by ' . $this->loggedUser->first_name . ' ' . $this->loggedUser->last_name ;

        // Character length and limitations: 127 single-byte alphanumeric characters
        // From comment below -> Also check for line breaks and double spaces Thanks @chim
        if ( !empty($this->payment_description) ) {
            $payment_description= $this->makeStripTags( $this->makeClearDoubledSpaces($this->makeStripslashes
            ($this->payment_description) ) );
        }
        if ( empty($payment_description) ) {
            $payment_description= 'payment description';
        }
        $payment_description= str_replace( [":", ">", "$", "#", "@", "~", "%", "^", "(", ")", "&" , "*" , ";" , ":", "'" , '"' , '|' , '\\' , ',', '.', '/'
        ], '',  $payment_description);

        if ( strlen($payment_description) > 127) {
            $payment_description = substr( $payment_description, 0, 127 );
        }
//        $this->debToFile(print_r($payment_description, true), ' CreatePayment::Transaction  -00 $payment_description::');
        $transaction = new Transaction();
        $transaction->setAmount( $this->Amount() )
                    ->setItemList( $itemList )
                    ->setDescription( $payment_description )
                    ->setInvoiceNumber( uniqid() );

        return $transaction;
    }

    protected function RedirectUrls(): RedirectUrls
    {
        $redirectUrls = new RedirectUrls();
        $app_url      = config('app.url');
        $redirectUrls->setReturnUrl($app_url . '/paypal_payment_execute')
                     ->setCancelUrl($app_url . '/paypal_payment_cancel');

        return $redirectUrls;
    }

    protected function Payment( $itemsList): Payment
    {
        $payment = new Payment();
        $payment->setIntent("sale")
                ->setPayer($this->Payer() )
                ->setRedirectUrls($this->RedirectUrls() )
                ->setTransactions( [ $this->Transaction($itemsList) ] );

        return $payment;
    }

}