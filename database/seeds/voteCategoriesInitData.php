<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\VoteCategory;

class voteCategoriesInitData extends Seeder
{
    private $vote_categories_tb;
    public function __construct()
    {
        $this->vote_categories_tb= with(new VoteCategory)->getTable();
    }

    public function run()
    {
        \DB::table($this->vote_categories_tb)->insert([
            'id'                 => 1,
            'name'               => 'Classic literature',
            'slug'               => 'classic-literature',
            'active'             => true,
            'in_subscriptions'   => true,
            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Classic literature']),
        ]);

        \DB::table($this->vote_categories_tb)->insert([
            'id'                 => 2,
            'name'               => 'Movie&Cartoons',
            'slug'               => 'movie-cartoons',
            'active'             => true,
            'in_subscriptions'   => true,
            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Movie&Cartoons']),
        ]);

        \DB::table($this->vote_categories_tb)->insert([
            'id'                 => 3,
            'name'               => 'Earth&World',
            'slug'               => 'earth-world',
            'active'             => true,
            'in_subscriptions'   => true,
            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Earth&World']),
        ]);
        \DB::table($this->vote_categories_tb)->insert([
            'id'                 => 4,
            'name'               => 'History',
            'slug'               => 'history',
            'active'             => true,
            'in_subscriptions'   => false,
            'meta_description'   => '',
            'meta_keywords'      => json_encode(['History']),
        ]);
        \DB::table($this->vote_categories_tb)->insert([
            'id'                 => 5,
            'name'               => 'PHP unit tests',
            'slug'               => 'php-unit-tests',
            'active'             => true,
            'in_subscriptions'   => true,
            'meta_description'   => '',
            'meta_keywords'      => json_encode(['PHP unit tests']),
        ]);


        \DB::table($this->vote_categories_tb)->insert([
            'id'                 => 6,
            'name'               => 'Miscellaneous',
            'slug'               => 'miscellaneous',
            'active'             => false,
            'meta_description'   => '',
            'meta_keywords'      => json_encode(['Miscellaneous']),
        ]);

    }
}
