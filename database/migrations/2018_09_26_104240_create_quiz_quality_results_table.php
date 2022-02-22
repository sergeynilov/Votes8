<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Vote;
use App\QuizQualityResult;

class CreateQuizQualityResultsTable extends Migration
{
    private $users_tb;
    private $votes_tb;
    private $quiz_quality_results_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->votes_tb= with(new Vote)->getTable();
        $this->quiz_quality_results_tb= with(new QuizQualityResult)->getTable();
    }

    public function up()
    {
        Schema::create($this->quiz_quality_results_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vote_id')->unsigned();
            $table->foreign('vote_id')->references('id')->on($this->votes_tb)->onDelete('CASCADE');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->integer('quiz_quality_id')->unsigned();

            $table->timestamp('created_at')->useCurrent();
            $table->unique(['vote_id', 'user_id'], 'quiz_quality_results_vote_id_id_user_id_index');
            $table->index(['quiz_quality_id', 'vote_id', 'user_id'], 'quiz_quality_results_quiz_quality_id_vote_id_user_id_index');

        });
        Artisan::call('db:seed', array('--class' => 'QuizQualityResultsInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->quiz_quality_results_tb);
    }
}