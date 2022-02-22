<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Settings;

class SettingsTableSwitchElasticAutomationOn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        \DB::table('settings')->insert([
//            'name' => 'elastic_automation',
//            'value' =>  'Y',
//        ]);
        Settings::where('name', 'elastic_automation')->update(['value' => 'Y']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        \DB::table('settings')->insert([
//            'name' => 'elastic_automation',
//            'value' =>  'N',
//        ]);
        Settings::where('name', 'elastic_automation')->update(['value' => 'N']);

    }
}
