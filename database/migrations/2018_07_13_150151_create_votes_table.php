<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Vote;
use App\Taggable as MyTaggable;
//use App\Tag as MyTag;
use App\Tag;
use App\TagDetail;
use App\VoteCategory;

class CreateVotesTable extends Migration
{
    private $users_tb;
    private $votes_tb;
    private $tags_tb;
    private $taggables_tb;
    private $tag_details_tb;
    private $vote_categories_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->votes_tb= with(new Vote)->getTable();
        $this->tags_tb= with(new Tag)->getTable();
        $this->taggables_tb= with(new MyTaggable)->getTable();
        $this->tag_details_tb= with(new TagDetail)->getTable();
        $this->vote_categories_tb= with(new VoteCategory)->getTable();
    }
    public function up()
    {


        Schema::create($this->votes_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->unique();
            $table->string('slug', 260)->unique();
            $table->mediumText('description');

            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->integer('vote_category_id')->unsigned();
            $table->foreign('vote_category_id')->references('id')->on($this->vote_categories_tb);//->onDelete('RESTRICT');


            $table->boolean('is_quiz')->default(false);
            $table->boolean('is_homepage')->default(false);
            $table->enum('status', ['N', 'A', 'I'])->comment(' N=>New,  A=>Active, I=>Inactive');
            $table->integer('ordering')->unsigned();
            $table->string('image', 100)->nullable();

            $table->string('meta_description', 255)->nullable();
            $table->json('meta_keywords')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->index(['created_at'], 'votes_created_at_index');
            $table->index(['is_quiz', 'status'], 'votes_is_quiz_status_index');
            $table->index(['ordering', 'status'], 'votes_ordering_status_index');
            $table->index(['is_homepage', 'status'], 'votes_is_homepage_status_index');

            $table->index(['creator_id', 'status', 'name'], 'votes_creator_id_status_name_index');
            $table->index(['vote_category_id', 'status', 'name'], 'votes_vote_category_id_status_name_index');

        });

        Artisan::call('db:seed', array('--class' => 'votesInitData'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->votes_tb);
    }
}
