<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\ChatMessage;
use App\Chat;

class CreateChatMessagesTable extends Migration
{
    private $users_tb;
    private $chats_tb;
    private $chat_messages_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->chats_tb = with(new Chat)->getTable();
        $this->chat_messages_tb= with(new ChatMessage)->getTable();
    }

    public function up()
    {
        Schema::create($this->chat_messages_tb, function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->integer('chat_id')->unsigned();
            $table->foreign('chat_id')->references('id')->on($this->chats_tb)->onDelete('CASCADE');


            $table->boolean('is_top')->default(false);
            $table->mediumText('text');

            $table->enum('message_type', ['T', 'U'])->comment(' N=>Text added , U=>Files uploaded')->default('T');
            $table->index(['message_type', 'user_id'], 'chat_messages_chat_id_message_type_index');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_at_by_user_id')->unsigned()->nullable();
            $table->foreign('updated_at_by_user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->index(['user_id', 'chat_id'], 'chat_messages_user_id_chat_id_index');
            $table->index(['chat_id', 'is_top'], 'chat_messages_chat_id_is_top_index');
            $table->index(['created_at'], 'chat_messages_created_at_index');
        });
        Artisan::call('db:seed', array('--class' => 'ChatMessagesWithInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->chat_messages_tb);
    }
}
