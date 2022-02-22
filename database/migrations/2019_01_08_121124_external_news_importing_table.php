<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ExternalNewsImporting;

class ExternalNewsImportingTable extends Migration
{
    private $external_news_importings_tb;
    public function __construct()
    {
        $this->external_news_importings_tb= with(new ExternalNewsImporting())->getTable();
    }
    public function up()
    {
        Schema::create($this->external_news_importings_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',100)->unique();
            $table->string('url', 100)->unique();
            $table->boolean('status')->default(false);
            $table->index(['status'], 'external_news_importing_status_index'  );
            $table->boolean('import_image')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->index(['created_at'], 'external_news_importing_created_at_index');
        });
        Artisan::call('db:seed', array('--class' => 'ExternalNewsImportingWithInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->external_news_importings_tb);
    }
}
