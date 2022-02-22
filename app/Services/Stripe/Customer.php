<?php

namespace App\Services\Stripe;

use Stripe\Stripe;

class Customer
{
    public  static  function save(User $user, array $card) {
        Stripe::setApiKey( config('services.stripe.secret') );

        $token = Token::create($card);
        $customer= \Stripe\Customer::create([
            'source' =>$token->id,
            'email'  => $user->email
        ]);

        $user->update([
            'stripe_customer_id'=>$customer->id
        ]);
    }


}