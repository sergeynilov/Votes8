<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Todo;

class TodosWithInitData extends Seeder
{
    private $todos_tb;
    public function __construct()
    {
        $this->todos_tb = with(new Todo)->getTable();
    }

    public function run()
    {
        \DB::table($this->todos_tb)->insert([
            'for_user_id'  => 5,
            'text'     => 'Write user\'s on using frontend application part lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut.',
            'priority' => 4,
            'completed'=> false,
        ]);

        \DB::table($this->todos_tb)->insert([
            'for_user_id'  => 5,
            'text'     => 'To do line 2...',
            'priority' => 3,
            'completed'=> true,
        ]);


        \DB::table($this->todos_tb)->insert([
            'for_user_id'  => 4,
            'text'     => 'Prepare list of user\'s having access to backend part lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut...',
            'priority' => 2,
            'completed'=> false,
        ]);

    }
}
