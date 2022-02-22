<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\SiteSubscription;

class SiteSubscriptionsInitData extends Seeder
{
    private $site_subscriptions_tb;


    public function __construct()
    {
        $this->site_subscriptions_tb= with(new SiteSubscription)->getTable();
    }

    public function run()
    {
        \DB::table($this->site_subscriptions_tb)->insert([
            'id'                  =>     1,
            'name'                =>     "News of site",
            'active'              =>     true,
            'mailchimp_list_name' =>     'Votes Newsletters : News of site',
            'mailchimp_list_id'   =>     'f33474b1c9',
        ]);
        /*             $table->string('mailchimp_list_name', 255)->nullable();
            $table->string('mailchimp_list_id', 20)->nullable();
 */

        \DB::table($this->site_subscriptions_tb)->insert([
            'id'                  =>     2,
            'name'                =>     "Classic literature",
            'active'              =>     true,
            'vote_category_id'    =>     1,
            'mailchimp_list_name' =>     'Votes Newsletters : Classic Literature',
            'mailchimp_list_id'   =>     'a6cd2a60e6',
        ]);

        \DB::table($this->site_subscriptions_tb)->insert([
            'id'                 =>     3,
            'name'               =>     "Movie&Cartoons",
            'active'             =>     true,
            'vote_category_id'   =>     2,
            'mailchimp_list_name' =>    'Votes Newsletters : Movie&Cartoons',
            'mailchimp_list_id'   =>    '087acbf04b',
        ]);


        \DB::table($this->site_subscriptions_tb)->insert([
            'id'                 =>     4,
            'name'               =>     "Earth&World",
            'active'             =>     true,
            'mailchimp_list_name' =>    'Votes Newsletters : Earth&World',
            'mailchimp_list_id'   =>    '42dde41bd4',
            'vote_category_id'   =>     3,
        ]);


        \DB::table($this->site_subscriptions_tb)->insert([
            'id'                 =>     5,
            'name'               =>     "History",
            'active'             =>     true,
            'mailchimp_list_name' =>    'Votes Newsletters : History',
            'mailchimp_list_id'   =>    'ac63b59026',
            'vote_category_id'   =>     4,
        ]);


        \DB::table($this->site_subscriptions_tb)->insert([
            'id'                 =>     6,
            'name'               =>     "Dummy subscriptions",
            'active'             =>     false,
            'vote_category_id'   =>     5,
        ]);

        \DB::table($this->site_subscriptions_tb)->insert([
            'id'                 =>     7,
            'name'               =>     "Miscellaneous",
            'active'             =>     false,
            'vote_category_id'   =>     6,
        ]);

    }
}
