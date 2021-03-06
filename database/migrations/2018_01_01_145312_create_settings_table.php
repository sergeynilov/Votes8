<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Settings;

class CreateSettingsTable extends Migration
{
    private $settings_tb;
    public function __construct()
    {
        $this->settings_tb= with(new Settings())->getTable();
    }
    public function up()
    {
        Schema::create($this->settings_tb, function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name', 255)->unique();
            $table->string('value', 255);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->index(['created_at'], 'settings_created_at_index');
        });
        Artisan::call('db:seed', array('--class' => 'SettingsWithInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->settings_tb);
    }
}
