<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActivityLogTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('activity_log')->delete();

        \DB::table('activity_log')->insert(array (
            0 =>
            array (
                'id' => 1,
                'log_name' => 'admin@mail.com',
                'description' => 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ',
                'subject_id' => NULL,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'successful_login',
                'properties' => '1',
                'created_at' => '2018-10-25 13:08:39',
            ),
            1 =>
            array (
                'id' => 2,
                'log_name' => 'admin@mail.com',
                'description' => 'Failed login from ip 127.0.0.1 with \'admin@mail.com\' email ',
                'subject_id' => NULL,
                'subject_type' => NULL,
                'causer_id' => NULL,
                'causer_type' => 'failed_login',
                'properties' => '',
                'created_at' => '2018-10-25 14:13:43',
            ),
            2 =>
            array (
                'id' => 3,
                'log_name' => 'admin@mail.com',
                'description' => 'Failed login from ip 127.0.0.1 with \'admin@mail.com\' email ',
                'subject_id' => NULL,
                'subject_type' => NULL,
                'causer_id' => NULL,
                'causer_type' => 'failed_login',
                'properties' => '',
                'created_at' => '2018-10-25 14:13:47',
            ),
            3 =>
            array (
                'id' => 4,
                'log_name' => 'admin@mail.com',
                'description' => 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ',
                'subject_id' => NULL,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'successful_login',
                'properties' => '1',
                'created_at' => '2018-10-25 14:13:49',
            ),
            4 =>
            array (
                'id' => 5,
                'log_name' => 'test',
                'description' => 'Testing email was sent ret = ',
                'subject_id' => -1,
                'subject_type' => NULL,
                'causer_id' => -1,
                'causer_type' => 'Email Testing',
                'properties' => '0',
                'created_at' => '2018-10-25 14:13:50',
            ),
            5 =>
            array (
                'id' => 6,
                'log_name' => 'JackParrot',
                'description' => 'Successful registration from ip 127.0.0.1 with \'JackParrot\' username, \'admin@mail.commm\' email ',
                'subject_id' => NULL,
                'subject_type' => NULL,
                'causer_id' => 45,
                'causer_type' => 'successful_user_registration',
                'properties' => '',
                'created_at' => '2018-10-25 14:22:21',
            ),
            6 =>
            array (
                'id' => 7,
                'log_name' => 'Admin',
                'description' => 'Admin voted not correctly on \'Who Framed Roger Rabbit ?\' vote ',
                'subject_id' => 2,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'vote_selected',
                'properties' => '0',
                'created_at' => '2018-10-25 14:24:05',
            ),
            7 =>
            array (
                'id' => 8,
                'log_name' => 'Admin',
                'description' => 'Admin set quiz quality 5 on \'Who Framed Roger Rabbit ?\' vote ',
                'subject_id' => 2,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'set_quiz_quality',
                'properties' => '5',
                'created_at' => '2018-10-25 14:24:12',
            ),
            8 =>
            array (
                'id' => 9,
                'log_name' => 'Admin',
                'description' => 'Admin voted correctly on \'Who Framed Roger Rabbit ?\' vote ',
                'subject_id' => 2,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'vote_selected',
                'properties' => '1',
                'created_at' => '2018-10-25 14:24:18',
            ),
            9 =>
            array (
                'id' => 10,
                'log_name' => 'Admin',
                'description' => 'Admin voted not correctly on \'Which fictional city is the home of Batman ?\' vote ',
                'subject_id' => 4,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'vote_selected',
                'properties' => '0',
                'created_at' => '2018-10-25 14:24:45',
            ),
            10 =>
            array (
                'id' => 11,
                'log_name' => 'Admin',
                'description' => 'Admin set quiz quality 3 on \'Which fictional city is the home of Batman ?\' vote ',
                'subject_id' => 4,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'set_quiz_quality',
                'properties' => '3',
                'created_at' => '2018-10-25 14:24:48',
            ),
            11 =>
            array (
                'id' => 12,
                'log_name' => 'Admin',
                'description' => 'Admin voted correctly on \'Which fictional city is the home of Batman ?\' vote ',
                'subject_id' => 4,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'vote_selected',
                'properties' => '1',
                'created_at' => '2018-10-25 14:25:01',
            ),
            12 =>
            array (
                'id' => 13,
                'log_name' => 'JackParrot',
                'description' => 'Successful activation from ip 127.0.0.1 with \'JackParrot\' username, \'admin@mail.commm\' email ',
                'subject_id' => NULL,
                'subject_type' => NULL,
                'causer_id' => 45,
                'causer_type' => 'successful_user_activation',
                'properties' => '',
                'created_at' => '2018-10-25 14:30:03',
            ),
            13 =>
            array (
                'id' => 14,
                'log_name' => 'JackParroda',
                'description' => 'Successful registration from ip 127.0.0.1 with \'JackParroda\' username, \'JackParroda@mail.com\' email ',
                'subject_id' => NULL,
                'subject_type' => NULL,
                'causer_id' => 46,
                'causer_type' => 'successful_user_activation',
                'properties' => '',
                'created_at' => '2018-10-25 14:32:37',
            ),
            14 =>
            array (
                'id' => 15,
                'log_name' => 'JackParroda',
                'description' => 'Successful activation from ip 127.0.0.1 with \'JackParroda\' username, \'JackParroda@mail.com\' email ',
                'subject_id' => NULL,
                'subject_type' => NULL,
                'causer_id' => 46,
                'causer_type' => 'successful_user_registration',
                'properties' => '',
                'created_at' => '2018-10-25 14:33:10',
            ),
            15 =>
            array (
                'id' => 16,
                'log_name' => 'Admiuterneferzer',
                'description' => 'Contact Us was sent from ip 127.0.0.1 with \'Admiuterneferzer\' username, \'Admiuterneferzer@mail.com\' email ',
                'subject_id' => 13,
                'subject_type' => NULL,
                'causer_id' => NULL,
                'causer_type' => 'successful_user_contact_us_sent',
                'properties' => '',
                'created_at' => '2018-10-25 14:39:31',
            ),
            16 =>
            array (
                'id' => 17,
                'log_name' => 'Admin',
                'description' => 'Admin voted correctly on \'Which is the tallest mammal?\' vote ',
                'subject_id' => 14,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'vote_selected',
                'properties' => '1',
                'created_at' => '2018-10-25 14:40:15',
            ),
            17 =>
            array (
                'id' => 18,
                'log_name' => 'Admin',
                'description' => 'Admin set quiz quality 3 on \'Which is the tallest mammal?\' vote ',
                'subject_id' => 14,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'set_quiz_quality',
                'properties' => '3',
                'created_at' => '2018-10-25 14:40:17',
            ),
            18 =>
            array (
                'id' => 19,
                'log_name' => 'Admin',
                'description' => 'Admin voted not correctly on \'Which is the tallest mammal?\' vote ',
                'subject_id' => 14,
                'subject_type' => NULL,
                'causer_id' => 5,
                'causer_type' => 'vote_selected',
                'properties' => '0',
                'created_at' => '2018-10-25 14:40:24',
            ),
            19 =>
            array (
                'id' => 20,
                'log_name' => 'admin@mail.com',
                'description' => 'Failed login from ip 127.0.0.1 with \'admin@mail.com\' email ',
                'subject_id' => NULL,
                'subject_type' => NULL,
                'causer_id' => NULL,
                'causer_type' => 'failed_login',
                'properties' => '',
                'created_at' => '2018-10-25 14:40:50',
            ),
            20 =>
            array (
                'id' => 21,
                'log_name' => 'adggmin@mail.comgg',
                'description' => 'Failed login from ip 127.0.0.1 with \'adggmin@mail.comgg\' email ',
                'subject_id' => NULL,
                'subject_type' => NULL,
                'causer_id' => NULL,
                'causer_type' => 'failed_login',
                'properties' => '',
                'created_at' => '2018-10-25 14:40:57',
            ),
        ));


    }
}
