<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Chat;
use App\ChatsLastVisited;

class CreateChatsLastVisitedTable extends Migration
{
    private $users_tb;
    private $chats_tb;
    private $chats_last_visited_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->chats_tb = with(new Chat)->getTable();
        $this->chats_last_visited_tb = with(new ChatsLastVisited)->getTable();
    }

    public function up()
    {
        Schema::create($this->chats_last_visited_tb, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->integer('chat_id')->unsigned();
            $table->foreign('chat_id')->references('id')->on($this->chats_tb)->onDelete('CASCADE');
            $table->timestamp('visited_at');

            $table->unique(['user_id', 'chat_id'], 'chats_last_visited_user_id_chat_id_unique');
            $table->index(['visited_at'], 'chats_last_visited_visited_at_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->chats_last_visited_tb);
    }
}
