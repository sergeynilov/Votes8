<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Vote;
use App\VoteItem;

class CreateVoteItemsTable extends Migration
{
    private $votes_tb;
    private $vote_items_tb;
    public function __construct()
    {
        $this->votes_tb= with(new Vote)->getTable();
        $this->vote_items_tb= with(new VoteItem)->getTable();
    }
    public function up()
    {
        Schema::create($this->vote_items_tb, function (Blueprint $table) {
            $table->increments('id');

            $table->integer('vote_id')->unsigned()->nullable();
            $table->foreign('vote_id')->references('id')->on($this->votes_tb)->onDelete('CASCADE');

            $table->string('name', 255);
            $table->integer('ordering');
            $table->boolean('is_correct')->default(false);
            $table->string('image', 100)->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['vote_id', 'name'], 'vote_items_vote_id_name_unique');
            $table->index(['vote_id', 'ordering'], 'vote_items_vote_id_ordering_index');
            $table->index(['vote_id', 'is_correct'], 'vote_items_vote_id_is_correct_index');
            $table->index(['created_at'], 'vote_items_created_at_index');

        });
        Artisan::call('db:seed', array('--class' => 'voteItemsInitData'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->vote_items_tb);
    }
}
