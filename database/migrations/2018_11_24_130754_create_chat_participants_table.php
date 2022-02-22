<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\ChatParticipant;
use App\Chat;

class CreateChatParticipantsTable extends Migration
{
    private $users_tb;
    private $chats_tb;
    private $chat_participants_tb;

    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->chats_tb = with(new Chat)->getTable();
        $this->chat_participants_tb= with(new ChatParticipant())->getTable();
    }

    public function up()
    {
        Schema::create($this->chat_participants_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');
            $table->enum('status', ['M', 'W', 'R'])->default('R')->comment(" 'M'=>'Manage this chat', 'W' => 'Can write messages', 'R' => 'Can only read' ");

            $table->integer('chat_id')->unsigned();
            $table->foreign('chat_id')->references('id')->on($this->chats_tb)->onDelete('CASCADE');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'chat_id'], 'chat_participants_user_id_chat_id_unique');
            $table->index(['status', 'user_id'], 'chat_participants_status_user_index');
            $table->index(['created_at'], 'chat_participants_created_at_index');
            $table->index(['chat_id', 'status', 'user_id'], 'chat_participants_chat_id_status_user_id_index');
        });
        Artisan::call('db:seed', array('--class' => 'UserChatParticipantsWithInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->chat_participants_tb);
    }
}