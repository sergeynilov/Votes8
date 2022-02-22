<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;
//use Illuminate\Foundation\Testing\RefreshDatabase;

//use Illuminate\Foundation\Testing\DatabaseMigrations; // TO COMMENT THESE LINE NOT TO REFRESH ALL DB

use App\Settings;
use App\User;
use App\VoteCategory;
use App\Http\Traits\funcsTrait;


class VotesAdminCrudTest extends TestCase   //   vendor/bin/phpunit tests/Feature/VotesAdminCrudTest.php
{
    use funcsTrait;
//    use DatabaseMigrations;  // TO COMMENT THESE LINE NOT TO REFRESH ALL DB
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testVotesListing()
    {
        // 1. PREPARE DATA FOR TESTING BLOCK START

        $is_debug= false;
        $settingsArray                = Settings::getSettingsList( ['site_name'/*, 'site_heading', 'site_subheading'*/ ], true);
        $site_name= $settingsArray['site_name' ] ? $settingsArray['site_name' ] : '';
        $logged_user_id               = config('app.php_unit_tests_logged_user_id');
        $loggedUser= User::find($logged_user_id);
        $voteCategoriesSelectionArray = VoteCategory::getVoteCategoriesSelectionArray();
        $vote_category_id= !empty($voteCategoriesSelectionArray[0]) ? $voteCategoriesSelectionArray[0] : 0;
        if ( $is_debug ) {
            dump('VotesAdminCrudTest Test1:: $site_name::' . print_r($site_name, true));
            dump('VotesAdminCrudTest Test2:: $loggedUser::' . print_r($loggedUser, true) );
            dump('VotesAdminCrudTest Test3:: $voteCategoriesSelectionArray::' . print_r($voteCategoriesSelectionArray, true));
            dump('VotesAdminCrudTest Test4:: $vote_category_id::' . print_r($vote_category_id, true));
        }
        $this->assertEquals($logged_user_id, (isset($loggedUser->id)?$loggedUser->id:-1),'Invalid logged user is set !');

        // 1. PREPARE DATA FOR TESTING BLOCK END



        // 2. RUN TESTING URL BLOCK START

        $this->withoutMiddleware();
        $response                     = $this->actingAs($loggedUser)->get('/admin/votes');

        // 2. RUN TESTING URL BLOCK END



        // 3. MAKING CHECKING ASSERTION BLOCK START

        $response->assertStatus(200);
//        $response->assertTitle( htmlspecialchars($site_name,ENT_QUOTES));   // Title of Document Categories listing page - must be opened after inserting

        $response->assertSee( htmlspecialchars('Votes Listing',ENT_QUOTES));


//        $this->visit('/')
//             ->click('О нас')
//             ->seePageIs('/about-us')

//        $response                     = $this->actingAs($loggedUser)->get('/admin/votes');

        $response                     = $this->actingAs($loggedUser)->get('/admin/vote/create')
             ->type('Taylor', 'name')
             ->select($vote_category_id, 'vote_category_id')
             ->select(true, 'is_quiz')
             ->select(true, 'is_homepage')
             ->select('A', 'status')
             ->type('description text ...', 'description')
             ->type('99', 'ordering')

             ->press('Save')
             ->seePageIs('/admin/votes');
//        $this->actingAs($loggedUser)->get('/admin/votes')->
//            click('.votes_listing_add')
//             ->seePageIs('/admin/vote/create')
//             ->assertSee( 'Create vote');


/*        $this->visit('/admin/votes')
            ->click('.votes_listing_add')
            ->seePageIs('/admin/vote/create')
            ->assertSee( 'Create vote');*/

//        $this->visit('.votes_listing_add')
//             ->type('Taylor', 'name')
//             ->check('terms')
//             ->press('Register');

//        $response->assertSee( htmlspecialchars('Non existing text',ENT_QUOTES));

        //         $response->assertSee(htmlspecialchars($site_subheading,ENT_QUOTES));

        // 3. MAKING CHECKING ASSERTION BLOCK END
        
    }
}
