<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\VoteCategory;

class CreateVoteCategoriesTable extends Migration
{
    private $vote_categories_tb;
    public function __construct()
    {
        $this->vote_categories_tb= with(new VoteCategory)->getTable();
    }
    public function up()
    {
        Schema::create($this->vote_categories_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->unique();
            $table->string('slug', 260)->unique();
            $table->boolean('active')->default(false);
            $table->boolean('in_subscriptions')->default(false);

            $table->string('meta_description', 255)->nullable();
            $table->json('meta_keywords')->nullable();


            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->index(['created_at'], 'vote_categories_created_at_index');
            $table->index(['active', 'name'], 'vote_categories_active_name_index');
        });
        Artisan::call('db:seed', array('--class' => 'voteCategoriesInitData'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->vote_categories_tb);
    }
}
