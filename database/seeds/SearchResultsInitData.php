<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\SearchResult;

class SearchResultsInitData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SearchResult::addDummyData();
    }
}
