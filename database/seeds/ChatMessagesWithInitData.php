<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ChatMessage;

class ChatMessagesWithInitData extends Seeder
{
    private $chat_messages_tb;
    public function __construct()
    {
        $this->chat_messages_tb= with(new ChatMessage())->getTable();
    }
    public function run()
    {

        try {
            \DB::table($this->chat_messages_tb)->insert([
                'id'                 => 1,
                'user_id'            => 1,
                'chat_id'            => 1,
                'is_top'             => true,
                'text'       => ' That is first/top message on "Greeting all employees!" chan and Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            ]);
            \DB::table($this->chat_messages_tb)->insert([
                'id'                 => 2,
                'user_id'            => 3,
                'chat_id'            => 1,
                'is_top'             => false,
                'text'               => 'That is next message on "Greeting all employees!" ',
            ]);
            \DB::table($this->chat_messages_tb)->insert([
                'id'                 => 3,
                'user_id'            => 5,
                'chat_id'            => 1,
                'is_top'             => false,
                'text'               => 'That is third message on "Greeting all employees!" ',
            ]);
            \DB::table($this->chat_messages_tb)->insert([
                'id'                 => 4,
                'user_id'            => 2,
                'chat_id'            => 1,
                'is_top'             => false,
                'text'               => 'One message on "Greeting all employees!" ',
            ]);

            \DB::table($this->chat_messages_tb)->insert([
                'id'                 => 5,
                'user_id'            => 1,
                'chat_id'            => 2,
                'is_top'             => true,
                'text'       => ' That is first/top message on "People, get the first task description" chat and Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            ]);

            \DB::table($this->chat_messages_tb)->insert([
                'id'                 => 6,
                'user_id'            => 5,
                'chat_id'            => 1,
                'is_top'             => true,
                'text'               => ' One more top message" ',
            ]);


            \DB::table($this->chat_messages_tb)->insert([
                'id'                 => 7,
                'user_id'            => 4,
                'chat_id'            => 2,
                'is_top'             => false,
                'text'               => 'That is next message on "People, get the first task description".',
            ]);
            \DB::table($this->chat_messages_tb)->insert([
                'id'                 => 8,
                'user_id'            => 5,
                'chat_id'            => 2,
                'is_top'             => false,
                'text'               => 'One more message" ',
            ]);
            \DB::table($this->chat_messages_tb)->insert([
                'id'                 => 9,
                'user_id'            => 2,
                'chat_id'            => 2,
                'is_top'             => false,
                'text'               => 'and again some message message Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. ',
            ]);
            \DB::table($this->chat_messages_tb)->insert([
                'id'                 => 10,
                'user_id'            => 5,
                'chat_id'            => 2,
                'is_top'             => true,
                'text'               => ' One more top message in "People, get the first task description" chat" ',
            ]);


        } catch (Exception $e) {

            throw $e;
        }


    }
}
