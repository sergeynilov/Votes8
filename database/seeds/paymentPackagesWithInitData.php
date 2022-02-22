<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\PaymentPackage;
class paymentPackagesWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $payment_packages_tb;
    public function __construct()
    {
        $this->payment_packages_tb= with(new PaymentPackage())->getTable();
    }

    public function run()
    {
        \DB::table($this->payment_packages_tb)->insert([
            'id'                   => 1,
            'name'                 => 'Free',
            'stripe_plan_id'       => 'plan_G2hLjiYeoQ87cu',
            'active'               => true,
            'is_premium'           => false,
            'is_free'              => true,
            'description'          => 'Free description Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'background_color'     => '#cccccc',
            'color'                => '#3e3e3e',
            'subscription_weight'  => 1,
            'price'                => null,
        ]);

        \DB::table($this->payment_packages_tb)->insert([
            'id'                   => 2,
            'name'                 => 'Basic',
            'stripe_plan_id'       => 'plan_G0UlaP5sYaTDQO',
            'active'               => true,
            'is_premium'           => false,
            'is_free'              => false,
            'description'          => 'Basic description Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'background_color'     => '#a8c76f',
            'color'                => '#397c8f',
            'subscription_weight'  => 2,
            'price'                => 2.25,
        ]);


        \DB::table($this->payment_packages_tb)->insert([
            'id'                   => 3,
            'name'                 => 'Silver',
            'stripe_plan_id'       => 'plan_G0687phdnBITXu',
            'active'               => true,
            'is_premium'           => false,
            'is_free'              => false,
            'description'          => 'Silver description Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'background_color'     => '#C0C0C0', // '#24c731',
            'color'                => '#3e8376',
            'subscription_weight'  => 4,
            'price'                => 4.5,
        ]);


        \DB::table($this->payment_packages_tb)->insert([
            'id'                   => 4,
            'name'                 => 'Gold',
            'stripe_plan_id'       => false,
            'active'               => false,
            'is_premium'           => true,
            'is_free'              => false,
            'description'          => 'Gold description Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'background_color'     => 'gold', // '#ffee2c',
            'color'                => '#0fa0c0',
            'subscription_weight'  => 4,
            'price'                => 5.5,
        ]);

        \DB::table($this->payment_packages_tb)->insert([
            'id'                   => 5,
            'name'                 => 'Premium',
            'stripe_plan_id'       => 'plan_G0UnTUF1RxKJ7G',
            'active'               => true,
            'is_premium'           => true,
            'is_free'              => false,
            'description'          => 'Premium description Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'background_color'     => '#c72d12',
            'color'                => '#bcbc84',
            'subscription_weight'  => 5,
            'price'                => 9,
        ]);

    }
}
