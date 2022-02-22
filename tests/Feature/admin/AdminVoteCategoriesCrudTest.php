<?php

namespace Tests\Feature;

use Carbon\Carbon;
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


class AdminVoteCategoriesCrudTest extends TestCase   //   vendor/bin/phpunit tests/Feature/admin/AdminVoteCategoriesCrudTest.php
{ // alias t='clear && vendor/bin/phpunit tests/Feature/admin/AdminVoteCategoriesCrudTest.php'
    use funcsTrait;
//    use DatabaseMigrations;  // TO COMMENT THESE LINE NOT TO REFRESH ALL DB
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testVoteCategoriesCrud()
    {

        $this->withoutExceptionHandling();
        $this->withoutMiddleware();

        // 1. PREPARE DATA FOR TESTING BLOCK START

        $is_debug= true;
        $loggedUser= factory(User::class)->create();
//        $voteCategoriesSelectionArray = VoteCategory::getVoteCategoriesSelectionArray();

        $original_vote_categories_count= VoteCategory::count();
//        if ( $is_debug ) {
//            dump('AdminVoteCategoriesCrudTest Test1:: $loggedUser::' . print_r($loggedUser, true));
//        }

        // 1. PREPARE DATA FOR TESTING BLOCK END



        // 2. OPENING CREATE FORM URL BLOCK START

        $response                     = $this->actingAs($loggedUser)->get('/admin/vote-categories/create');
        $response->assertStatus(200);
        $response->assertSeeInOrder( [ htmlspecialchars('Create vote category',ENT_QUOTES), htmlspecialchars('Meta description',ENT_QUOTES) ] );

        // 2. OPENING CREATE FORM URL BLOCK END



        // 3. ADD NEW ROW BLOCK START

        $newVoteCategoryRow         = factory('App\VoteCategory')->make(); // Create new VoteCategory Data Row
        $newVoteCategoryRow->name   .= ' created on ' . strftime("%Y-%m-%d %H:%M:%S:%U") ;
        $new_vote_category_row_name = $newVoteCategoryRow->name;

        $response = $this->actingAs($loggedUser)->post('/admin/vote-categories', $newVoteCategoryRow->toArray());
//        $response->assertStatus(200);    // to use HTTP_RESPONSE_OK
        $this->assertCount( $original_vote_categories_count+1, VoteCategory::all() );    // to use HTTP_RESPONSE_OK
        $response->assertRedirect('/admin/vote-categories'); // after successful post request redirect to '/admin/vote-categories'

        if ( $is_debug ) {
            dump('AdminVoteCategoriesCrudTest Test21:: $new_vote_category_row_name::' . print_r($new_vote_category_row_name, true));
        }
        // 3. ADD NEW ROW BLOCK END



        // 4. OPENING EDIT FORM FOR ROW CREATED AT 3 BLOCK START
        $checkCreatedVoteCategory     = VoteCategory::getSimilarVoteCategoryByName( $new_vote_category_row_name );
        $this->assertEquals($new_vote_category_row_name, ( !empty($checkCreatedVoteCategory->name) ? $checkCreatedVoteCategory->name : '') );

        $response                     = $this->actingAs($loggedUser)->get('/admin/vote-categories/'.$checkCreatedVoteCategory->id.'/edit');
        $response->assertStatus(200);
        $response->assertSeeInOrder( [ htmlspecialchars('Edit vote category',ENT_QUOTES), htmlspecialchars('Meta description',ENT_QUOTES) ] );


        // 4. OPENING EDIT FORM FOR ROW CREATED AT 3 BLOCK END



        // 5. MODIFY EXISTING ROW(CREATED AT 3) BLOCK START

        $updateVoteCategoryRow = factory('App\VoteCategory')->make(); // Create update VoteCategory Data Row
        $updateVoteCategoryRow->name =  $new_vote_category_row_name . ' => ' . $updateVoteCategoryRow->name . ' updated on '.strftime("%Y-%m-%d %H:%M:%S:%U");

//        if ( $is_debug ) {
//            dump('AdminVoteCategoriesCrudTest Test20:: $updateVoteCategoryRow::' . print_r($updateVoteCategoryRow, true));
//        }

        $response = $this->actingAs($loggedUser)->put( '/admin/vote-categories/'.$checkCreatedVoteCategory->id, $updateVoteCategoryRow->toArray() );

        $this->assertCount( $original_vote_categories_count+1, VoteCategory::all() );    // to use HTTP_RESPONSE_OK
        $response->assertRedirect('/admin/vote-categories'); // after successful put request redirect to '/admin/vote-categories'

        // 5. MODIFY EXISTING ROW(CREATED AT 3) BLOCK END


        // 6. DELETE EXISTING ROW(CREATED AT 3) BLOCK START
//        $response = $this->actingAs($loggedUser)->delete( '/admin/vote-categories', ['id'=>$checkCreatedVoteCategory->id] );

        if ( $is_debug ) {
            dump('??:: $checkCreatedVoteCategory->id::' . print_r($checkCreatedVoteCategory->id, true));
        }
        $this->assertCount( $original_vote_categories_count, VoteCategory::all() );    // to use HTTP_RESPONSE_OK

        // 6. DELETE EXISTING ROW(CREATED AT 3) BLOCK END


    }
}
