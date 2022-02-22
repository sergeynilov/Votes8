<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Banner;

class CreateBannersTable extends Migration
{
    private $banners_tb;
    public function __construct()
    {
        $this->banners_tb= with(new Banner)->getTable();
    }
    public function up()
    {
        Schema::create($this->banners_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->string('text', 20);
            $table->string('logo', 50)->nullable();
            $table->string('short_descr', 50)->nullable();
            $table->string('url', 255);
            $table->boolean('active')->default(false);

            $table->integer('ordering')->unsigned();
            $table->integer('view_type')->unsigned();

            $table->timestamp('created_at')->useCurrent();

            $table->index(['ordering', 'active'], 'banners_ordering_active_index');
            $table->index(['created_at'], 'banners_created_at_index');
        });
        Artisan::call('db:seed', array('--class' => 'BannersInitData'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->banners_tb);
    }
}
