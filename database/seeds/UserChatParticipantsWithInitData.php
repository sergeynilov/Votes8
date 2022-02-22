<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ChatParticipant;

class UserChatParticipantsWithInitData extends Seeder
{
    private $chat_participants_tb;

    public function __construct()
    {
        $this->chat_participants_tb= with(new ChatParticipant())->getTable();
    }
    public function run()
    {
        \DB::table($this->chat_participants_tb)->insert([
            'user_id'           => 1,
            'chat_id'      => 1,
            'status'            => 'R',
        ]);

        \DB::table($this->chat_participants_tb)->insert([
            'user_id'           => 5,
            'chat_id'      => 1,
            'status'            => 'M',
        ]);


        \DB::table($this->chat_participants_tb)->insert([
            'user_id'           => 2,
            'chat_id'      => 1,
            'status'            => 'W',
        ]);
        \DB::table($this->chat_participants_tb)->insert([
            'user_id'           => 6,
            'chat_id'      => 1,
            'status'            => 'W',
        ]);



        \DB::table($this->chat_participants_tb)->insert([
            'user_id'           => 3,
            'chat_id'      => 2,
            'status'            => 'R',
        ]);

        \DB::table($this->chat_participants_tb)->insert([
            'user_id'           => 5,
            'chat_id'      => 2,
            'status'            => 'M',
        ]);


        \DB::table($this->chat_participants_tb)->insert([
            'user_id'           => 1,
            'chat_id'      => 2,
            'status'            => 'W',
        ]);
        \DB::table($this->chat_participants_tb)->insert([
            'user_id'           => 6,
            'chat_id'      => 2,
            'status'            => 'W',
        ]);

        \DB::table($this->chat_participants_tb)->insert([
            'user_id'           => 4,
            'chat_id'      => 2,
            'status'            => 'R',
        ]);

    }
}
