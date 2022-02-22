<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Chat;

class CreateChatsTable extends Migration
{
    private $users_tb;
    private $chats_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->chats_tb = with(new Chat)->getTable();
    }

    public function up()
    {
        Schema::create($this->chats_tb, function (Blueprint $table) {

            $table->increments('id');
            $table->string('name', 255);
            $table->mediumText('description');

            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');


            $table->enum('status', ['A', 'C'])->comment(' A=>Active, C=>Closed');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->index(['created_at'], 'chats_created_at_index');
            $table->index(['creator_id', 'status', 'name'], 'chats_creator_id_status_name_index');
        });
        Artisan::call('db:seed', array('--class' => 'ChatsWithInitData'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->chats_tb);
    }
}
