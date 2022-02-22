<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\SearchResult;

class CreateSearchResultsTable extends Migration
{
    private $users_tb;
    private $search_results_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->search_results_tb= with(new SearchResult)->getTable();
    }

    public function up()
    {
        Schema::create($this->search_results_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');
            $table->string('text', 255);

            $table->integer('found_results')->unsigned();

            $table->timestamp('created_at')->useCurrent();
            $table->index(['text', 'user_id'], 'search_results_text_user_id_index');
            $table->index(['text', 'found_results'], 'search_results_text_found_results_index');

        });
        Artisan::call('db:seed', array('--class' => 'SearchResultsInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->search_results_tb);
    }
}
