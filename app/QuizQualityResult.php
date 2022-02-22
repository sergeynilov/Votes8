<?php

namespace App;
use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;
use App\User;


class QuizQualityResult extends MyAppModel
{
    use funcsTrait;
    protected $table       = 'quiz_quality_results';
    protected $primaryKey  = 'id';
    public $timestamps     = false;

    protected $fillable = [ 'quiz_quality_id', 'user_id', 'quality', 'created_at' ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }



    public function scopeGetByCreatedAt($query, $filter_voted_at_from= null, string $sign= null)
    {
        if (!empty($filter_voted_at_from)) {
            if (!empty($sign)) {
                $query->whereRaw(DB::getTablePrefix().with(new QuizQualityResult)->getTable().'.created_at ' . $sign . "'".$filter_voted_at_from."' ");
            } else {
                $query->where(with(new QuizQualityResult)->getTable().'.created_at', $filter_voted_at_from);
            }
        }
        return $query;
    }



    public function scopeGetByUserId($query, $filter_user_id= null)
    {
        if (!empty($filter_user_id)) {
            if ( is_array($filter_user_id) ) {
                $query->whereIn(with(new QuizQualityResult)->getTable().'.user_id', $filter_user_id);
            } else {
                $query->where(with(new QuizQualityResult)->getTable().'.user_id', $filter_user_id);
            }
        }
        return $query;
    }

    // getByVoteId
    public function scopeGetByVoteId($query, $filter_vote_id= null)
    {
        if (!empty($filter_vote_id)) {
            if ( is_array($filter_vote_id) ) {
                $query->whereIn(with(new QuizQualityResult)->getTable().'.vote_id', $filter_vote_id);
            } else {
                $query->where(with(new QuizQualityResult)->getTable().'.vote_id', $filter_vote_id);
            }
        }
        return $query;
    }

    public function scopeGetByIsCorrect($query, $filter_quality= null)
    {
        if (isset($filter_quality)) {
            $query->where(with(new QuizQualityResult)->getTable().'.quality', $filter_quality);
        }
        return $query;
    }

    public function scopeGetByVoteIdAndUserId($query, $vote_id, $user_id= null)
    {
        if (!empty($vote_id)) {
            $query->where(with(new QuizQualityResult)->getTable().'.vote_id', $vote_id);
        }
        if (!empty($user_id)) {
            $query->where(with(new QuizQualityResult)->getTable().'.user_id', $user_id);
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
    

    public static function addDummyData()
    {
        $usersList             = User::all();
        $votesList             = Vote::all();

        $quizQualityOptions   = with(new QuizQualityResult)->getQuizQualityOptions();
        $faker = \Faker\Factory::create();

        foreach ($usersList as $nextUser) {
            foreach ($votesList as $nextVote) {
                $random_index = mt_rand(1, count($quizQualityOptions) );
                if ( ! empty($quizQualityOptions[$random_index])) {

                    if ( $nextVote->id <= 2 and $random_index <= 3 ) { // Votes with id = 1, 2 - has bigger rating
                        $random_index= count($quizQualityOptions);
                    }

                    if ( in_array( $nextVote->id,[13,14,15] ) and $random_index >= 3 ) { // Votes with id = 13, 14, 15 : has smallest rating
                        $random_index= 1;
                    }


                    if ( in_array( $nextVote->id,[5] )  ) { // Votes with id = 5 : has biggest rating
                        $random_index= count($quizQualityOptions);
                    }

                    DB::table('quiz_quality_results')->insert([
                        'quiz_quality_id' => $random_index,
                        'user_id'         => $nextUser->id,
                        'vote_id'         => $nextVote->id,
                        'created_at'      => $faker->dateTimeThisMonth()->format('Y-m-d h:m:s'),
                    ]);
                }
            } //             foreach ($votesList as $nextVote) {
        } // foreach ($usersList as $nextUser) {
//        reset($usersList);

    }

}
