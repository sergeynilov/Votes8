<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\SiteSubscription;
use App\User;
use App\UsersSiteSubscription;

class UsersSiteSubscriptionsInitData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        return;
        $usersList             = User::orderBy('id', 'asc')->get();
        $siteSubscriptionsList = SiteSubscription::orderBy('id', 'asc')->get();

        $users_site_subscriptions_tb= with(new UsersSiteSubscription)->getTable();

        foreach ($usersList as $nextUser) {
            foreach ($siteSubscriptionsList as $nextSiteSubscription) {
                \DB::table($users_site_subscriptions_tb)->insert([
                    'user_id'              => $nextUser->id,
                    'site_subscription_id' => $nextSiteSubscription->id,
                ]);
            }

        }
    }
}
