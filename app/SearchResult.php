<?php

namespace App;

use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;
use App\User;


class SearchResult extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'search_results';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = ['text', 'user_id', 'found_results', 'created_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function scopeGetByFoundResults($query, bool $filter_found_results = false)
    {
        if ($filter_found_results) {
            $query->whereRaw(DB::getTablePrefix().with(new SearchResult)->getTable() . '.found_results > 0');
        } else {
            $query->whereRaw(DB::getTablePrefix().zwith(new SearchResult)->getTable() . '.found_results <= 0');
        }
        return $query;
    }


    public function scopeGetByCreatedAt($query, $filter_voted_at_from = null, string $sign = null)
    {
        if ( ! empty($filter_voted_at_from)) {
            if ( ! empty($sign)) {
                $query->whereRaw(DB::getTablePrefix().with(new SearchResult)->getTable() . '.created_at ' . $sign . "'" . $filter_voted_at_from . "' ");
            } else {
                $query->where(with(new SearchResult)->getTable() . '.created_at', $filter_voted_at_from);
            }
        }
        return $query;
    }


    public function scopeGetByUserId($query, $filter_user_id = null)
    {
        if ( ! empty($filter_user_id)) {
            if (is_array($filter_user_id)) {
                $query->whereIn(with(new SearchResult)->getTable() . '.user_id', $filter_user_id);
            } else {
                $query->where(with(new SearchResult)->getTable() . '.user_id', $filter_user_id);
            }
        }
        return $query;
    }


    public static function addDummyData()
    {

        $searchStrings = [
            [
                'text' => 'Tragedy',
                'type' => 2, // Both found and not found
            ],

            [
                'text' => 'comedy',
                'type' => 1, // Only found
            ],

            [
                'text' => 'soccer',
                'type' => 0, // Only not found
            ],

            [
                'text' => 'theatrical',
                'type' => 1, // Only found
            ],

            [
                'text' => 'cartoon dog',
                'type' => 2, // Both found and not found
            ],

            [
                'text' => 'Everest',
                'type' => 1, // Only found
            ],


            [
                'text' => 'stand up',
                'type' => 0, // Only not found
            ],


            [
                'text' => 'movie Jaws',
                'type' => 1, // Only found
            ],


            [
                'text' => 'environment',
                'type' => 0, // Only not found
            ],


            [
                'text' => 'mountain',
                'type' => 2, // Both found and not found
            ],


            [
                'text' => 'hook',
                'type' => 0, // Only not found
            ],

            [
                'text' => 'Batman',
                'type' => 1, // Only found
            ],


        ];

        $usersList = User::all();
        $faker     = \Faker\Factory::create();

        for($index= 0; $index< 5; $index++ ) {
            foreach ($searchStrings as $nextSearchString) {
                $random = mt_rand(1, 10);
                if ( $random <= 3 ) continue;
                $user_random_id = mt_rand(-(count($usersList) / 2), count($usersList));
                if ($user_random_id <= 0) {
                    $user_random_id = null;
                }

                $found_results = 0;
                if ($nextSearchString['type'] == 1) {  // Only found
                    $found_results = mt_rand(1, 5);
                } // Only found

                if ($nextSearchString['type'] == 2) {  // Both found and not found
                    $found_results = mt_rand(-2, 5);
                    if ($found_results < 0) {
                        $found_results = 0;
                    }
                } //  Both found and not found

                DB::table(with(new SearchResult)->getTable())->insert([
                    'user_id'       => $user_random_id,
                    'text'          => $nextSearchString['text'],
                    'found_results' => $found_results,
                    'created_at'    => $faker->dateTimeThisMonth()->format('Y-m-d h:m:s'),
                ]);
            }
        } // for($index= 0; $index< 5; $index++ ) {

    }

}
