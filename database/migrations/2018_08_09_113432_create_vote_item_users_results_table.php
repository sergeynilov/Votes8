<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\VoteItemUsersResult;
use App\VoteItem;

class CreateVoteItemUsersResultsTable extends Migration
{
    private $users_tb;
    private $vote_item_users_results_tb;
    private $vote_items_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->vote_item_users_results_tb= with(new VoteItemUsersResult)->getTable();
        $this->vote_items_tb= with(new VoteItem)->getTable();
    }

    public function up()
    {
        Schema::create($this->vote_item_users_results_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vote_item_id')->unsigned();
            $table->foreign('vote_item_id')->references('id')->on($this->vote_items_tb)->onDelete('CASCADE');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->boolean('is_correct')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['vote_item_id', 'user_id'], 'vote_item_users_result_vote_item_id_user_id_index');
            $table->index(['vote_item_id', 'is_correct', 'user_id'], 'vote_item_users_result_vote_item_id_is_correct_user_id_index');
        });
        Artisan::call('db:seed', array('--class' => 'VoteItemUsersResultsInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->vote_item_users_results_tb);
    }
}
