<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Download;

class CreateDownloadsTable extends Migration
{
    private $users_tb;
    private $downloads_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->downloads_tb= with(new Download)->getTable();
    }

    public function up()
    {
        Schema::create($this->downloads_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->unique();
            $table->string('file', 255)->unique();
            $table->boolean('active')->default(false);

            $table->mediumText('description');

            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->string('preview_image', 255)->nullable();
            $table->decimal('price', 7);
            $table->string('price_info', 255)->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->index(['created_at'], 'downloads_created_at_index');
            $table->index(['creator_id', 'title'], 'downloads_creator_id_title_index');
        });
        Artisan::call('db:seed', array('--class' => 'downloadsWithInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->downloads_tb);
    }
}
