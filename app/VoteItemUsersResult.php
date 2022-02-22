<?php

namespace App;
use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;
use App\VoteItem;
use App\Vote;
use App\User;


class VoteItemUsersResult extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'vote_item_users_result';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable   = [ 'vote_item_id', 'user_id', 'is_correct', 'created_at' ];

    private static $voteItemIsCorrectLabelValueArray = Array('1' => 'Is Correct', '0' => 'Is Not Correct');

    protected $casts = [
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function voteItem()
    {
        return $this->belongsTo('App\VoteItem');
    }


    public function scopeGetByCreatedAt($query, $filter_voted_at_from= null, string $sign= null)
    {
        if (!empty($filter_voted_at_from)) {
            if (!empty($sign)) {
                $query->whereRaw( DB::getTablePrefix().with(new VoteItemUsersResult)->getTable().'.created_at ' . $sign . "'".$filter_voted_at_from."' " );
            } else {
                $query->where(with(new VoteItemUsersResult)->getTable().'.created_at', $filter_voted_at_from);
            }
        }
        return $query;
    }

    public function scopeGetByUserId($query, $filter_user_id= null)
    {
        if (!empty($filter_user_id)) {
            if ( is_array($filter_user_id) ) {
                $query->whereIn( DB::getTablePrefix().with(new VoteItemUsersResult)->getTable().'.user_id', $filter_user_id );
            } else {
                $query->where(with(new VoteItemUsersResult)->getTable().'.user_id', $filter_user_id);
            }
        }
        return $query;
    }

    public function scopeGetByIsCorrect($query, $filter_is_correct= null)
    {
        if (isset($filter_is_correct)) {
            $query->where(with(new VoteItemUsersResult)->getTable().'.is_correct', $filter_is_correct);
        }
        return $query;
    }

    public function scopeGetByVoteIdAndUserId($query, $vote_id, $user_id= null)
    {
        $vote_items_tb= with(new VoteItem)->getTable();
        if (!empty($vote_id)) {
            $query->where( $vote_items_tb.'.vote_id', $vote_id);
        }
        if (!empty($user_id)) {
            $query->where(with(new VoteItemUsersResult)->getTable().'.user_id', $user_id);
        }
        return $query;
    }



    public function scopeGetByVote($query, $vote_id= null)
    {
        if (!empty($vote_id)) {
            if ( is_array($vote_id) ) {
                $query->whereIn( with(new VoteItem)->getTable().'.vote_id', $vote_id );
            } else {
                $query->where( with(new VoteItem)->getTable() . '.vote_id', $vote_id );
            }
        }
        return $query;
    }

    public function scopeGetByVoteCategories($query, $vote_category_id= null)
    {
        if (!empty($vote_category_id)) {
            if ( is_array($vote_category_id) ) {
                $query->whereIn( with(new Vote)->getTable().'.vote_category_id', $vote_category_id);
            } else {
                $query->where( with(new Vote)->getTable().'.vote_category_id', $vote_category_id);
            }
        }
        return $query;
    }

    public function scopeGetByVoteIsQuiz($query, $is_quiz= null)
    {
        if (isset($is_quiz)) {
            $query->where( with(new Vote)->getTable().'.is_quiz', $is_quiz );
        }
        return $query;
    }

    public function scopeGetByVoteStatus($query, $status= null)
    {
        if (isset($status)) {
            $query->where( with(new Vote)->getTable().'.status', $status );
        }
        return $query;
    }

    public static function getVoteItemUsersResultIsCorrectValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$voteItemIsCorrectLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getVoteItemUsersResultIsCorrectLabel(string $is_correct):string
    {
        if (!empty(self::$voteItemIsCorrectLabelValueArray[$is_correct])) {
            return self::$voteItemIsCorrectLabelValueArray[$is_correct];
        }
        return self::$voteItemIsCorrectLabelValueArray[0];
    }



    public static function addDummyData()
    {
        $usersList             = User::all();
        $votesList             = Vote::all();
        $faker = \Faker\Factory::create();

        foreach ($usersList as $nextUser) {

            foreach ($votesList as $nextVote) {
                $voteItemsList = VoteItem::getByVote($nextVote->id)->orderBy('ordering', 'asc')->get();
                if ( count($voteItemsList) > 0 ) {
                    $random_index = mt_rand(0, count($voteItemsList) - 1);
                    if ( ! empty($voteItemsList[$random_index]->id)) {

                        if ( $voteItemsList[$random_index]->is_correct and in_array($nextVote->id,[1,2]) ) continue;
                        if ( !$voteItemsList[$random_index]->is_correct and in_array($nextVote->id,[3,4]) ) continue;


                        DB::table('vote_item_users_result')->insert([
                            'user_id'      => $nextUser->id,
                            'vote_item_id' => $voteItemsList[$random_index]->id,
                            'is_correct'   => $voteItemsList[$random_index]->is_correct,
                            'created_at'   => $faker->dateTimeThisMonth()->format('Y-m-d h:m:s'),
                        ]);
                    }
                }
            } // foreach ($votesList as $nextVote) {

        } // foreach ($usersList as $nextUser) {

    }

}
