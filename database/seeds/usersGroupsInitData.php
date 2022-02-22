<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class usersGroupsInitData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users_groups')->insert([
            'user_id'=> 1,
            'group_id'=> USER_ACCESS_ADMIN
        ]);
        \DB::table('users_groups')->insert([
            'user_id'=> 1,
            'group_id'=> USER_ACCESS_MANAGER
        ]);


//        \DB::table('users_groups')->insert([
//            'user_id'=> 2,
//            'group_id'=> USER_ACCESS_ADMIN
//        ]);
        \DB::table('users_groups')->insert([
            'user_id'=> 2,
            'group_id'=> USER_ACCESS_MANAGER
        ]);


        \DB::table('users_groups')->insert([
            'user_id'=> 3,
            'group_id'=> USER_ACCESS_ADMIN
        ]);
        \DB::table('users_groups')->insert([
            'user_id'=> 3,
            'group_id'=> USER_ACCESS_MANAGER
        ]);


        \DB::table('users_groups')->insert([
            'user_id'=> 4,
            'group_id'=> USER_ACCESS_ADMIN
        ]);
        \DB::table('users_groups')->insert([
            'user_id'=> 4,
            'group_id'=> USER_ACCESS_MANAGER
        ]);

        \DB::table('users_groups')->insert([
            'user_id'=> 5,
            'group_id'=> USER_ACCESS_ADMIN
        ]);
        \DB::table('users_groups')->insert([
            'user_id'=> 5,
            'group_id'=> USER_ACCESS_MANAGER
        ]);

        \DB::table('users_groups')->insert([
            'user_id'=> 6,
            'group_id'=> USER_ACCESS_ADMIN
        ]);
        \DB::table('users_groups')->insert([
            'user_id'=> 6,
            'group_id'=> USER_ACCESS_MANAGER
        ]);

//        Artisan::call('db:seed', array('--class' => 'usersGroupsInitData'));

    }
}
