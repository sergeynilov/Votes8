<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ChatParticipant;

class ChatParticipantsWithInitData extends Seeder
{
    private $chat_participants_tb;
    public function __construct()
    {
        $this->chat_participants_tb= with(new ChatParticipant())->getTable();
    }
    public function run()
    {
        try {
            \DB::table($this->chat_participants_tb)->insert([
                'user_id'           => 1,
                'chat_id'           => 1,
                'status'       => 'M',
            ]);


            \DB::table($this->chat_participants_tb)->insert([
                'user_id'           => 2,
                'chat_id'           => 1,
                'status'       => 'W',
            ]);


        } catch (Exception $e) {

            throw $e;
        }

    }
}
