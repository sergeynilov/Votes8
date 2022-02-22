<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\ChatMessage;
use App\ChatMessageDocument;
use App\Chat;

class CreateChatMessageDocumentsTable extends Migration
{
    private $users_tb;
    private $chat_messages_tb;
    private $chat_message_documents_tb;

    public function __construct()
    {
        $this->users_tb                  = with(new User)->getTable();
        $this->chat_messages_tb          = with(new ChatMessage)->getTable();
        $this->chat_message_documents_tb = with(new ChatMessageDocument)->getTable();
    }

    public function up()
    {
        Schema::create($this->chat_message_documents_tb, function (Blueprint $table) {
            $table->increments('id');

            $table->integer('chat_message_id')->unsigned();
            $table->foreign('chat_message_id')->references('id')->on($this->chat_messages_tb)->onDelete('CASCADE');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->string('filename', 255);
            $table->string('extension', 10);
            $table->string('info', 255)->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->unique(['chat_message_id', 'filename'], 'user_chat_message_documents_1_unique');
            $table->index(['chat_message_id', 'extension'], 'user_chat_message_documents_2');
            $table->index(['created_at'], 'user_chat_message_documents_created_at_index');
        });
        Artisan::call('db:seed', array('--class' => 'chatMessageDocumentsInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->chat_message_documents_tb);
    }
}
