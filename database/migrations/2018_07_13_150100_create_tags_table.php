<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Taggable as MyTaggable;
//use App\Tag as MyTag;
use App\Tag;
use App\TagDetail;

class CreateTagsTable extends Migration
{
    private $tags_tb;
    private $taggables_tb;
    private $tag_details_tb;

    public function __construct()
    {
        $this->tags_tb        = with(new Tag)->getTable();
        $this->taggables_tb   = with(new MyTaggable)->getTable();
        $this->tag_details_tb = with(new TagDetail)->getTable();
    }

    public function up()   // database/migrations/2018_07_13_150100_create_votes_table.php
    {
        Schema::create($this->tags_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->json('name');
            $table->json('slug');
            $table->string('type')->nullable();
            $table->integer('order_column')->nullable();
            $table->timestamps();
        });

        Schema::create($this->taggables_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tag_id')->unsigned();
            $table->integer('taggable_id')->unsigned();
            $table->string('taggable_type');

            $table->foreign('tag_id')->references('id')->on($this->tags_tb)->onDelete('cascade');

        });

        Schema::create($this->tag_details_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tag_id')->unsigned()->unique();
            $table->foreign('tag_id')->references('id')->on($this->tags_tb)->onDelete('cascade');
            $table->string('image', 100)->nullable();

            $table->text('description')->nullable();

            $table->string('meta_description', 255)->nullable();
            $table->json('meta_keywords')->nullable();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->taggables_tb);
        Schema::dropIfExists($this->tag_details_tb);
        Schema::dropIfExists($this->tags_tb);
    }
}
