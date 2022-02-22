<?php

namespace Tests\Feature;

use Tests\TestCase;
use DB;
use Illuminate\Foundation\Testing\WithFaker;

//use Illuminate\Foundation\Testing\RefreshDatabase;   // FOR QUICK WORK : TO COMMENT THESE 2 LINES NOT TO REFRESH DB
//use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Settings;
use App\User;
use App\Vote;
use App\VoteCategory;
use App\Http\Traits\funcsTrait;

class VotesByCategoryPageTest extends TestCase  // vendor/bin/phpunit   tests/Feature/VotesByCategorypageTest.php
{
    use funcsTrait;


    
//    use DatabaseMigrations; // TO COMMENT THESE LINE NOT TO REFRESH ALL DB
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testVotesByCategoryPage()
    {

        // 1. PREPARE DATA FOR TESTING BLOCK START

        $is_debug= true;
        $settingsArray                  = Settings::getSettingsList( ['site_name', 'site_heading', 'site_subheading'], true);
        $site_name                      = $settingsArray['site_name' ] ? $settingsArray['site_name' ] : '';
        $site_heading                   = $settingsArray['site_heading' ] ? $settingsArray['site_heading' ] : '';
        $site_subheading                = $settingsArray['site_subheading' ] ? $settingsArray['site_subheading' ] : '';
        $logged_user_id                 = config('app.php_unit_tests_logged_user_id');
        $loggedUser                     = User::find($logged_user_id);

        $this->assertEquals($logged_user_id, (isset($loggedUser->id)?$loggedUser->id:-1),'Invalid tests logged user is set !');
        $max_ordering= DB::table(with(new Vote)->getTable())->max('ordering');
        // 1. PREPARE DATA FOR TESTING BLOCK END


        if ( $is_debug ) {
            dump('VotesByCategoryPageTest Test0:: $site_heading::' . print_r($site_heading, true));
            dump('VotesByCategoryPageTest Test1:: $site_subheading::' . print_r($site_subheading, true));
            dump('VotesByCategoryPageTest Test2:: $loggedUser->email::' . print_r($loggedUser->email, true));
            dump('VotesByCategoryPageTest Test3:: $max_ordering::' . print_r($max_ordering, true));
        }


        // 2. ADD TESTING DATA BLOCK START : CREATING NEW QUIZ VOTE

        $newVoteCategory                    = new VoteCategory();
        $newVoteCategory->name              = 'Testing Vote Category on '.now();
        $newVoteCategory->active            = true;
        $newVoteCategory->in_subscriptions  = false;
        $newVoteCategory->save();
        $testing_vote_category_id           = $newVoteCategory->id;

        $newQuizVote                    = new Vote();
        $newQuizVote->name              = 'Testing Vote (Vote Category) on '.now();
        $newQuizVote->description       = 'Testing Vote (Vote Category) on '.now() . ' description Sed  ut perspiciatis unde omnis iste natus error sit voluptatem accusantium  doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo  inventore veritatis et quasi architecto beatae vitae dicta sunt  explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut  odit aut fugit, sed quia consequuntur magni dolores eos qui ratione  voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum  quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam  eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat  voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam  corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?  Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse  quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo  voluptas nulla pariatur?';
        $newQuizVote->creator_id        = $logged_user_id;
        $newQuizVote->vote_category_id  = $testing_vote_category_id;
        $newQuizVote->is_quiz           = true;     // that iz Quiz
        $newQuizVote->is_homepage       = true;     // it is shown on home page
//        $newQuizVote->image             = $newQuizVote_image;
        $newQuizVote->status            = 'A';
        $newQuizVote->ordering          = !empty($max_ordering) ? $max_ordering + 1 : 1;
        $newQuizVote->save();

        if ( $is_debug ) {
            dump('VotesByCategoryPageTest Test5:: $newQuizVote->id::' . print_r($newQuizVote->id, true));
            dump('VotesByCategoryPageTest Test6:: $newQuizVote->name::' . print_r($newQuizVote->name, true));
            dump('VotesByCategoryPageTest Test7:: $newQuizVote->slug::' . print_r($newQuizVote->slug, true));
        }


        // 2.1. ADD TESTING DATA BLOCK START : CREATING NEW QUESTION VOTE
        $newQuestionVote                    = new Vote();
        $newQuestionVote->name              = 'Testing Question on '.now();
        $newQuestionVote->description       = 'Testing Question on '.now() . ' description Sed  ut perspiciatis unde omnis iste natus error sit voluptatem accusantium  doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo  inventore veritatis et quasi architecto beatae vitae dicta sunt  explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut  odit aut fugit, sed quia consequuntur magni dolores eos qui ratione  voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum  quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam  eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat  voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam  corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?  Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse  quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo  voluptas nulla pariatur?';
        $newQuestionVote->creator_id        = $logged_user_id;
        $newQuestionVote->vote_category_id  = $testing_vote_category_id;
        $newQuestionVote->is_quiz           = false;     // that iz Question
        $newQuestionVote->is_homepage       = true;     // it is shown on home page
//        $newQuestionVote->image             = $newQuestionVote_image;
        $newQuestionVote->status            = 'A';
        $newQuestionVote->ordering          = !empty($max_ordering) ? $max_ordering + 1 : 1;
        $newQuestionVote->save();

        // 2. ADD TESTING DATA BLOCK END


        if ( $is_debug ) {
            dump('VotesByCategoryPageTest Test8:: $newQuestionVote->id::' . print_r($newQuestionVote->id, true));
            dump('VotesByCategoryPageTest Test9:: $newQuestionVote->name::' . print_r($newQuestionVote->name, true));
            dump('VotesByCategoryPageTest Test10:: $newQuestionVote->slug::' . print_r($newQuestionVote->slug, true), '');
        }



        // 3. OPEN TESTING PAGE(HOMEPAGE) BLOCK START
        $response                     = $this->actingAs($loggedUser)->get('/home');

        // 3. OPEN TESTING PAGE(HOMEPAGE) BLOCK END



        // 4. MAKING CHECKING ASSERTION BLOCK START

        $response->assertStatus(200);
        $response->assertSee(htmlspecialchars($site_heading,ENT_QUOTES));
        $response->assertSee(htmlspecialchars($site_subheading,ENT_QUOTES));

        // 4. MAKING CHECKING ASSERTION BLOCK END



        // 5.1. OPEN QUESTION VOTE CREATED at 2.1 BLOCK START

        $response                     = $this->actingAs($loggedUser)->get('/vote/' . $newQuizVote->slug);
        $response->assertStatus(200);
        $response->assertSeeTextInOrder( [ htmlspecialchars($site_heading,ENT_QUOTES), htmlspecialchars($newQuizVote->name,ENT_QUOTES) ] );

        // 5.1. OPEN QUESTION VOTE CREATED at 2.1 BLOCK END


        // 5.2. OPEN QUESTION VOTE CREATED at 2.1 BLOCK START

        $response                     = $this->actingAs($loggedUser)->get('/vote/' . $newQuestionVote->slug);
        $response->assertStatus(200);
        $response->assertSeeTextInOrder( [ htmlspecialchars($site_heading,ENT_QUOTES), htmlspecialchars($newQuestionVote->name,ENT_QUOTES) ] );
//        $response->assertSee(htmlspecialchars($site_heading,ENT_QUOTES));
//        $response->assertSee(htmlspecialchars($newQuestionVote->name,ENT_QUOTES));

        // 5.2. OPEN QUESTION VOTE CREATED at 2.1 BLOCK END


        // 6. OPEN VOTES BY CATEGORY PAGE BLOCK START

        $response                     = $this->actingAs($loggedUser)->get('/votes-by-category/' . $newVoteCategory->slug);
        $response->assertStatus(200);
        $response->assertSeeTextInOrder( [htmlspecialchars($site_name,ENT_QUOTES), htmlspecialchars($newVoteCategory->name,ENT_QUOTES) ] );

        // 6. OPEN VOTES BY CATEGORY PAGE BLOCK END

    }
}
