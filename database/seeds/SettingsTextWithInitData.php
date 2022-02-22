<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\SettingsText;


class SettingsTextWithInitData extends Seeder
{

    private $settings_text_tb;
    public function __construct()
    {
        $this->settings_text_tb= with(new SettingsText())->getTable();
    }

    public function run()
    {

        \DB::table($this->settings_text_tb)->insert([
            'name' => 'account_register_details_text',
            'text' =>  'When you are registering at our site we need to say : lorem  ipsum dolor sit amet, consectetur<br> adipiscing elit, sed do eiusmod  tempor incididunt ut labore et<br> dolore magna aliqua. Ut enim ad minim ...',
        ]);

        \DB::table($this->settings_text_tb)->insert([
            'name' => 'account_register_avatar_text',
            'text' =>  'Selecting avatar for your account lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et<br> dolore magna aliqua. Ut enim...',
        ]);

        \DB::table($this->settings_text_tb)->insert([
            'name' => 'account_register_subscriptions_text',
            'text' =>  'Selecting news subscriptions for your account lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et<br> dolore magna aliqua. Ut enim ad ...',
        ]);

        \DB::table($this->settings_text_tb)->insert([
            'name' => 'account_register_confirm_text',
            'text' =>  'Creating account lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt <br> dolore magna aliqua. Ut veniam, quis nostrud exercitation <br>ullamco laboris nisi ut aliquip ex ea...',
        ]);

        \DB::table($this->settings_text_tb)->insert([
            'name' => 'account_contacts_us_text',
            'text' =>  'Please, send your message for us and the administration of our site would answer soon. We would be glad to get from you new quiz idea.',
        ]);


        \DB::table($this->settings_text_tb)->insert([
            'name' => 'account_select_payment_package_text',
            'text' =>  'Selecting payment package text for your account lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et<br> dolore magna aliqua. Ut enim ad ...',
        ]);



    }
}
