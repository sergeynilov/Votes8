<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\VoteItemUsersResult;


class VoteItemUsersResultsInitData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VoteItemUsersResult::addDummyData();
    }
}
