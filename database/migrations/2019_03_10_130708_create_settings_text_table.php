<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SettingsText;


class CreateSettingsTextTable extends Migration
{

    private $settings_text_tb;

    public function __construct()
    {
        $this->settings_text_tb = with(new SettingsText())->getTable();
    }

    public function up()
    {
        Schema::create($this->settings_text_tb, function (Blueprint $table) {

            $table->increments('id')->unsigned();
            $table->string('name', 255)->unique();
            $table->mediumText('text');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->index(['created_at'], 'settings_text_created_at_index');
        });
        Artisan::call('db:seed', array('--class' => 'SettingsTextWithInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->settings_text_tb);
    }
}
