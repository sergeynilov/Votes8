<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Todo;


class CreateTodosTable extends Migration
{
    private $users_tb;
    private $todos_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->todos_tb = with(new Todo)->getTable();
    }

    public function up()
    {
        Schema::create($this->todos_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('for_user_id')->unsigned()->default(1);
            $table->foreign('for_user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');
            $table->string('text', 255);

            $table->enum('priority', ['1', '2', '3', '4', '5', '6'])->default("1")->comment('  1-No, 2-Low, 3-Normal, 4-High, 5-Urgent, 6-Immediate  ');

            $table->boolean('completed')->default(false);

            $table->timestamp('created_at')->useCurrent();

            $table->unique(['for_user_id', 'text'], 'user_todos_for_user_id_text_unique');
            $table->index(['for_user_id', 'completed'], 'user_todos_for_user_id_completed');
            $table->index(['created_at'], 'user_todos_created_at_index');
        });
        Artisan::call('db:seed', array('--class' => 'TodosWithInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->todos_tb);
    }
}
