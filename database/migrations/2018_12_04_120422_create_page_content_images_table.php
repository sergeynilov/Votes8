<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\PageContentImage;
use App\PageContent;

class CreatePageContentImagesTable extends Migration
{
    private $page_content_images_tb;
    private $page_contents_tb;
    public function __construct()
    {
        $this->page_content_images_tb= with(new PageContentImage())->getTable();
        $this->page_contents_tb= with(new PageContent)->getTable();
    }

    public function up()
    {
        Schema::create($this->page_content_images_tb, function (Blueprint $table) {
            $table->increments('id');

            $table->integer('page_content_id')->unsigned();
            $table->foreign('page_content_id')->references('id')->on($this->page_contents_tb)->onDelete('CASCADE');

            $table->string('filename', 255);
            $table->boolean('is_main')->default(false);
            $table->boolean('is_video')->default(false);
            $table->smallInteger('video_width')->nullable();
            $table->smallInteger('video_height')->nullable();
            $table->string('info', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['page_content_id', 'filename'], 'page_contents_page_content_id_filename_unique');
            $table->index(['page_content_id', 'is_main'], 'page_contents_page_content_id_is_main');
            $table->index(['page_content_id', 'is_video', 'filename'], 'page_contents_page_content_id_is_video_filename');
            $table->index(['created_at'], 'page_content_message_documents_created_at_index');

        });
        Artisan::call('db:seed', array('--class' => 'PageContentImagesWithInitData')); 

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->page_content_images_tb);
    }
}
