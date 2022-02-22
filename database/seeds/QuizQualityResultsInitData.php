<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\QuizQualityResult;

class QuizQualityResultsInitData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuizQualityResult::addDummyData();
    }
}
