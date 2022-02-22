<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ServiceSubscription;

class ServiceSubscriptionsWithInitData extends Seeder
{

    private $service_subscriptions_tb;

    public function __construct()
    {
        $this->service_subscriptions_tb = with(new ServiceSubscription)->getTable();
    }

    public function run()
    {
        \DB::table($this->service_subscriptions_tb)->insert([
            'id'                         => 1,
            'name'                       => 'Free',
            'active'                     => true,
            'paypal_service_id'          => 'P-38K215479K725494LHITER6I',
            'stripe_plan_id'             => 'plan_G2hLjiYeoQ87cu',
            'is_premium'                 => false,
            'is_free'                    => true,
            'price_period'               => 'M',
            'price'                      => 0,
            'description'                => 'Free description Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod',
            'color'                      => '#3e3e3e',
            'background_color'           => '#cccccc',
            'subscription_weight'        => 1
        ]);

        \DB::table($this->service_subscriptions_tb)->insert([
            'id'                         => 2,
            'name'                       => 'Basic',
            'active'                     => true,
            'paypal_service_id'          => 'P-0U478222LP387231WHIR4RWQ',
            'stripe_plan_id'             => 'plan_G0UlaP5sYaTDQO',
            'is_premium'                 => false,
            'is_free'                    => false,
            'price_period'               => 'M',
            'price'                      => 2.25,
            'description'                => 'Basic description Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit',
            'color'                      => '#397c8f',
            'background_color'           => '#a8c76f',
            'subscription_weight'        => 2
        ]);

/*        \DB::table($this->service_subscriptions_tb)->insert([
            'id'                         => 3,
            'name'                       => 'Receive news on new services available',
            'active'                     => true,
            'paypal_service_id'                 => null,
            'stripe_plan_id'             => null,
            'price_period'               => 'M',
            'price'                      => 0.45,
            'description'                => 'Receive news on new services available Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod',
        ]);

        \DB::table($this->service_subscriptions_tb)->insert([
            'id'                         => 4,
            'name'                       => 'Free digest of the main news of the site',
            'active'                     => true,
            'paypal_service_id'          => null,
            'stripe_plan_id'             => null,
            'price_period'               => 'Y',
            'price'                      => 0,
            'description'                => 'Free digest of the main news of the site Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod',
        ]);*/

    }
}
