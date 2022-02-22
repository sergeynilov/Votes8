<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Chat;
class ChatsWithInitData extends Seeder
{

    private $chats_tb;
    public function __construct()
    {
        $this->chats_tb = with(new Chat)->getTable();
    }
    public function run()
    {


        \DB::table($this->chats_tb)->insert([
            'name'              => 'Greeting all employees!',
            'description'       => 'Greeting all employees! and Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'creator_id'        => 1,
            'status'            => 'A',
        ]);

        \DB::table($this->chats_tb)->insert([
            'name'              => 'People, get the first task description',
            'description'       => 'First task description and Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'creator_id'        => 2,
            'status'            => 'A',
        ]);



    }
}
