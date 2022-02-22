<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\PageContent;

class CreatePageContentsTable extends Migration
{
    private $users_tb;
    private $page_contents_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->page_contents_tb= with(new PageContent)->getTable();
    }

    public function up()
    {
        Schema::create($this->page_contents_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slug', 260)->unique();

            $table->mediumText('content');
            $table->string('content_shortly', 255)->nullable();

            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on($this->users_tb);


            $table->boolean('is_featured')->default(false);
            $table->boolean('is_homepage')->default(false);
            $table->boolean('published')->default(false);
            $table->enum('page_type', ['N', 'E', 'P', 'B'])->comment(' N=>Our News, E=>External News,  P=>Page, B=>Blog');
            $table->string('image', 100)->nullable();

            $table->string('source_type', 20)->nullable();
            $table->string('source_url', 255)->nullable();

            $table->string('meta_description', 255)->nullable();
            $table->json('meta_keywords')->nullable();

            $table->timestamp('created_at');//->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->index(['created_at'], 'page_contents_created_at_index');

            $table->index(['is_homepage', 'published'], 'page_contents_is_homepage_published_index');

            $table->index(['creator_id', 'published', 'title'], 'page_contents_creator_id_published_title_index');
            $table->index(['page_type', 'published', 'title'], 'page_contents_page_type_published_title_index');
            $table->index(['source_type', 'published'], 'page_contents_source_type_published_index');

        });
        Artisan::call('db:seed', array('--class' => 'PageContentsInitData'));
    }

    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->page_contents_tb);
    }
}
