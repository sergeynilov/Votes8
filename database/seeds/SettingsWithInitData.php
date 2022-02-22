<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Settings;

class SettingsWithInitData extends Seeder
{
    private $settings_tb;
    public function __construct()
    {
        $this->settings_tb= with(new Settings())->getTable();
    }
    public function run()
    {
        \DB::table($this->settings_tb)->insert([
            'name' => 'site_name',
            'value' =>  'Select & Vote',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'copyright_text',
            'value' =>  '© 2018 - 2019 All rights reserved',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'elastic_automation',
            'value' =>  'N',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'site_heading',
            'value' =>  'Make your choice!',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'site_subheading',
            'value' =>  'Vote\'em all !',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'contact_us_email',
            'value' =>  'vote@vote.com',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'subscriptions_notification_email',
            'value' =>  'nilov@softreactor.com',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'contact_us_phone',
            'value' =>  '(321)-987-654-0321',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'home_page_ref_items_per_pagination',
            'value' =>  8,
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'backend_per_page',
            'value' =>  20,
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'news_per_page',
            'value' =>  20,
        ]);


        \DB::table($this->settings_tb)->insert([
            'name' => 'noreply_email',
            'value' =>  'noreply@make_votes.com',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'support_signature',
            'value' =>  'Best Regards,<br>    Support of Select & Vote Team',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'userRegistrationFiles',
            'value' =>  'slogan_1.jpg;our-services.doc;rules-of-our-site.pdf;our_prices.ods;terms.doc;',
        ]);
        \DB::table($this->settings_tb)->insert([
            'name' => 'showQuizQualityOptions',
            'value' =>  '1=Poor;2=Not good;3=So-so;4=Good;5=Excellent',
        ]);

/*        \DB::table($this->settings_tb)->insert([
            'name' => 'account_register_details_text',
            'value' =>  'When you are registering at our site we need to say : lorem  ipsum dolor sit amet, consectetur<br> adipiscing elit, sed do eiusmod  tempor incididunt ut labore et<br> dolore magna aliqua. Ut enim ad minim ...',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'account_register_avatar_text',
            'value' =>  'Selecting avatar for your account lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et<br> dolore magna aliqua. Ut enim...',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'account_register_subscriptions_text',
            'value' =>  'Selecting news subscriptions for your account lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et<br> dolore magna aliqua. Ut enim ad ...',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'account_register_confirm_text',
            'value' =>  'Creating account lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt <br> dolore magna aliqua. Ut veniam, quis nostrud exercitation <br>ullamco laboris nisi ut aliquip ex ea...',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'account_contacts_us_text',
            'value' =>  'Please, send your message for us and the administration of our site would answer soon. We would be glad to get from you new quiz idea.',
        ]);*/

//        \DB::table($this->settings_tb)->insert([
//            'name' => 'account_contacts_us_enter_vote_text',
//            'value' =>  'That is name of a vote you propose.',
//        ]);
//

        \DB::table($this->settings_tb)->insert([
            'name' => 'allow_facebook_authorization',
            'value' =>  'Y',
        ]);
        \DB::table($this->settings_tb)->insert([
            'name' => 'allow_google_authorization',
            'value' =>  'Y',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'allow_linkedin_authorization',
            'value' =>  'Y',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'allow_github_authorization',
            'value' =>  'Y',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'allow_twitter_authorization',
            'value' =>  'Y',
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'allow_instagram_authorization',
            'value' =>  'Y',
        ]);




        \DB::table($this->settings_tb)->insert([
            'name' => 'latest_news_on_homepage',
            'value' =>  10,
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'infinite_scroll_rows_per_scroll_step',
            'value' =>  12,
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'similar_news_on_limit',
            'value' =>  15,
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'other_news_on_limit',
            'value' =>  20,
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'feed_items_on_limit',
            'value' =>  12,
        ]);

        \DB::table($this->settings_tb)->insert([
            'name' => 'feed_import_creator_id',
            'value' =>  2,
        ]);
        \DB::table($this->settings_tb)->insert([
            'name' => 'most_rating_quiz_quality_on_homepage',
            'value' =>  6,
        ]);
        \DB::table($this->settings_tb)->insert([
            'name' => 'most_votes_taggable_on_homepage',
            'value' =>  8,
        ]);


    }
}
