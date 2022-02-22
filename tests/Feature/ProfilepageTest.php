<?php

namespace Tests\Feature;

use Tests\TestCase;
use DB;
use Illuminate\Foundation\Testing\WithFaker;

//use Illuminate\Foundation\Testing\WithoutMiddleware; // Prevent all middleware from being executed for this test class.
//use Illuminate\Foundation\Testing\RefreshDatabase;   // FOR QUICK WORK : TO COMMENT THESE 2 LINES NOT TO REFRESH DB

//use Illuminate\Foundation\Testing\DatabaseMigrations; // TO COMMENT THESE LINE NOT TO REFRESH ALL DB
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Settings;
use App\User;
use App\UserGroup;
use App\Group;
use App\Vote;
use App\Http\Traits\funcsTrait;

class ProfilePageTest extends TestCase  // ./vendor/bin/phpunit   tests/Feature/ProfilepageTest.php
{
    use funcsTrait;

//    use DatabaseMigrations; // TO COMMENT THESE LINE NOT TO REFRESH ALL DB
//    use DatabaseTransactions; // TO COMMENT THESE LINE IF WE NEED TO CHECK CREATED DATA IN BD
//    use WithoutMiddleware;

    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testProfilePage()
    {

        // 1. PREPARE DATA FOR TESTING BLOCK START

//        $this->withoutExceptionHandling();
        

        dd(app()->environment());
        $csrf_token = csrf_token();

        $is_debug        = true;
        $settingsArray   = Settings::getSettingsList(['site_name', 'site_heading', 'site_subheading'], true);
        $site_name       = $settingsArray['site_name'] ? $settingsArray['site_name'] : '';
        $site_heading    = $settingsArray['site_heading'] ? $settingsArray['site_heading'] : '';
        $site_subheading = $settingsArray['site_subheading'] ? $settingsArray['site_subheading'] : '';

//        $testing_user_id        = config('app.php_unit_tests_vote_category_id');
//        $testsUser              = User::find($testing_user_id);
//        $this->assertEquals($testing_user_id, (isset($testsUser->id)?$testsUser->id:-1),'Invalid tests vote category is set !');
//

        // 1. PREPARE DATA FOR TESTING BLOCK END


        if ($is_debug) {
//                $this->d( '<pre>$::' . print_r( $, true ) );
//            $this->d('ProfilePageTest Test1:: $site_heading::' . print_r($site_heading, true));
//            $this->d('ProfilePageTest Test2:: $site_subheading::' . print_r($site_subheading, true));
            dump(  'ProfilePageTest Test4:: $csrf_token::' . print_r($csrf_token, true)  );
        }

        // 1.1 OPEN PROFILE DETAILS VIEW PAGE WITHOUT AUTHORIZATION BLOCK START
        $response = $this->get('profile/view');
        if ($is_debug) {
//            $this->d('/profile/view   Test010:: $newUser->id::' . print_r(-1, true));
        }
        $response->assertSee( htmlspecialchars('Redirecting to',ENT_QUOTES) );

        $response = $this->get('profile/edit-details');
        if ($is_debug) {
//            $this->d('/profile/view   Test011:: $newUser->id::' . print_r(-1, true));
        }
        $response->assertSee( htmlspecialchars('Redirecting to',ENT_QUOTES) );

        // 1.1 OPEN PROFILE DETAILS VIEW PAGE BLOCK END


        // 2. ADD TESTING DATA BLOCK START : CREATING NEW USER as    register/confirm action
        //     Route::post('register/confirm', array(

/*        $newUser             = new User();
        $newUser->username   = 'Testing user on ' . now();
        $newUser->email      = 'Testing_user_on_' . now() . '@site.com';
        $newUser->password   = bcrypt('111111');
        $newUser->status     = 'A';
        $newUser->verified   = true;
        $newUser->first_name = 'first_name_on_' . now();
        $newUser->last_name  = 'last_name_on_' . now();
        $newUser->phone      = 'phone' . now();
        $newUser->website    = 'Testing_user_on_' . now() . '.site.com';
        $newUser->save();
//        $testing_user_id = $newUser->id;
        if ($is_debug) {
//            $this->d('ProfilePageTest Test5:: $newUser->id::' . print_r($newUser->id, true));
        }
        $newUserGroup           = new UserGroup();
        $newUserGroup->group_id = USER_ACCESS_USER;
        $newUserGroup->user_id  = $newUser->id;

        $userGroup= Group::find($newUserGroup->group_id);*/

        $avatar_filename= 'avatar_filename.png';
        $full_photo_filename= '$full_photo_filename.png';
        $new_user_email= 'Testing_user_on_' . now() . '@site.com';

        $response = $this->post('register/confirm', [
            'username'   => 'Testing user on ' . now(),
            'email'      => $new_user_email,
            'password'   => bcrypt('111111'),
            'password_conf'   => bcrypt('111111'),
            'status'     => 'A',
            'verified'     => true,
            'first_name' => 'first_name_on_' . now(),
            'last_name'  => 'last_name_on_' . now(),
            'phone'      => 'phone' . now(),
            'website'    => 'Testing-user-on-' . now() . '.site.com',
            'notes'      => 'Notes Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.' ,
            'sex'        => 'M',
            'avatar_filename'=> $avatar_filename,
            'full_photo_filename'=> $full_photo_filename,
        ]);
//        $response->assertStatus(200);    // to use HTTP_RESPONSE_OK

        $response->assertStatus(200);
//        $response->assertOk();
//        public static function getSimilarUserByEmail( string $email, int $id= null, bool $return_count = false )
        $newUser= User::getSimilarUserByEmail( $new_user_email );

        if ($is_debug) {
//                $this->d( '<pre>$::' . print_r( $, true ) );
//            $this->d('ProfilePageTest Test1:: $site_heading::' . print_r($site_heading, true));
//            $this->d('ProfilePageTest Test2:: $site_subheading::' . print_r($site_subheading, true));
            dump(  'ProfilePageTest Test40 $new_user_email::' . print_r($new_user_email, true)  );
            dump(  'ProfilePageTest Test40 $newUser->id::' . print_r(!empty($newUser->id) ? $newUser->id : 'USER NOT CREATED', true)  );
        }

        $newUserGroup= null;
        foreach ( $newUser->userGroups()->get() as $nextUserGroup ) {
            $newUserGroup= $nextUserGroup;
            if ($is_debug) {
                dump(  'ProfilePageTest Test43 $newUserGroup->id::' . print_r($newUserGroup->id, true)  );
            }
        }



        $newUserSessionData = [
            [
            'loggedUserAccessGroups' => ['group_id' => $newUserGroup->group_id, 'group_name' => !empty($userGroup) ? $userGroup->name : ''],
            'logged_user_ip'         => '0',
            ]
        ];
//        if ($is_debug) {
//            $this->d('ProfilePageTest Test51:: $newUserSessionData::' . print_r($newUserSessionData, true));
//        }
//        $newUserGroup->save();

        // 3. OPEN PROFILE PAGE BLOCK START
        $response = $this->actingAs($newUser)
                         ->withSession($newUserSessionData)
                         ->get('/profile/view');
        // 3. OPEN PROFILE PAGE BLOCK END


        // 4. MAKING CHECKING PROFILE FOR USER CREATED AT 2) BLOCK START

        $response->assertStatus(200);    // to use HTTP_RESPONSE_OK
        $response->assertSee(htmlspecialchars("Profile : " . $newUser->username, ENT_QUOTES));

        // 4. MAKING CHECKING PROFILE FOR USER CREATED AT 2) BLOCK END



        // 5. OPEN PROFILE DETAILS VIEW PAGE BLOCK START
        $response = $this->actingAs($newUser)
                         ->withSession($newUserSessionData)
                         ->get('profile/view');
        if ($is_debug) {
//            $this->d('/profile/view   Test6:: $newUser->id::' . print_r($newUser->id, true));
        }
        $response->assertStatus(200);
        $response->assertSee(htmlspecialchars("Profile : " . $newUser->username, ENT_QUOTES));

        // 5. OPEN PROFILE DETAILS VIEW PAGE BLOCK END






        // 6. OPEN PROFILE DETAILS EDITOR PAGE BLOCK START
        $response = $this->actingAs($newUser)
                         ->withSession($newUserSessionData)
                         ->get('profile/edit-details');   // http://local-votes.com/profile/edit-details
        if ($is_debug) {
//            $this->d('/profile/edit-details   Test7:: $newUser->id::' . print_r($newUser->id, true));
        }
        $response->assertStatus(HTTP_RESPONSE_OK);
        $response->assertSee(htmlspecialchars("Profile : Details"));

        // 6. OPEN PROFILE DETAILS EDITOR PAGE BLOCK END


        // 6.1 OPEN PROFILE DETAILS EDITOR PAGE AND GENERATE PASSWORD BLOCK START
        // http://local-votes.com/profile/generate_password
        //        return response()->json(['message' => 'New password was generated!', 'error_code' => 0], HTTP_RESPONSE_OK);

//        $response = $this->actingAs($newUser, 'api')->json('POST', 'profile/generate-password',[ '_token'     => $csrf_token ]);
//        $response = $this->actingAs($newUser, 'web')->json('POST', 'profile/user/generate-password/' . $newUser->id,[ '_token'     => $csrf_token ]);
        $response = $this->actingAs($newUser, 'web')->json('POST', 'profile/generate-password',[ '_token'     => $csrf_token ]);
//        $response = $this->actingAs($newUser, 'web')->json('POST', 'profile/generate-password',[ 'csrf-token'     => $csrf_token ]);
//        $response = $this->actingAs($newUser, 'web')->json('POST', 'profile/generate-password');

        /* $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
}); */

        //  404 Not Found, 
        /*     Route::post('/user/generate-password/{user_id}', array(
        'as'      => 'user-generate-password',
        'uses'    => 'Admin\UsersController@generate_password'
    ));
 */


        //https://laracasts.com/discuss/channels/testing/i-got-csrf-error-with-http-tests
        $response->assertStatus(200);
        $response->assertJson(['error_code'    => 0]);
        $response->assertJson(['message'       => 'New password was generated !' ]);
        

        // 6.1 OPEN PROFILE DETAILS EDITOR PAGE AND GENERATE PASSWORD BLOCK END
        /*     $response = $this->actingAs($user, 'api')->json('POST', '/api/orders',$data);
            $response->assertStatus(200);
            $response->assertJson(['status'        => true]);
            $response->assertJson(['message'       => "Order Created!"]);
            $response->assertJsonStructure(['data' => [
                                    'id',
                                    'product_id',
                                    'user_id',
                                    'quantity',
                                    'address',
                                    'created_at',
                                    'updated_at'
                            ]]); */



        $skip_profile_updating= true;
        // 7. MODIFY PROFILE DETAILS PAGE BLOCK START

        if ( !$skip_profile_updating ) {
            if ($is_debug) {
//                $this->d('profile/profile-edit-details-post   Test8:: $newUser->id::' . print_r($newUser->id, true));
            }

            $response = $this->actingAs($newUser)
                             ->withSession($newUserSessionData)
//                         ->put('profile/profile-edit-details-put', [
                             ->patch('profile/edit-details-post', [
                    'first_name' => 'Modified : ' . $newUser->first_name,
                    'last_name'  => 'Modified : ' . $newUser->last_name,
                    'phone'      => 'Modified : ' . $newUser->phone,
                    'website'    => 'Modified : ' . $newUser->website,
//                             '_token'     => $csrf_token
                ]);
            $response->assertStatus(205);   // ???
            $response->assertSee( htmlspecialchars('Profile updated successfully') );
//        if ($is_debug) {
//            $this->d('/profile/edit-details   Test81:: $newUser->id::' . print_r($newUser->id, true));
//        }
        } // $skip_profile_updating



        // 7. MODIFY PROFILE DETAILS PAGE BLOCK END


        ////////////////////////
        // 8. OPEN PROFILE DETAILS VIEW PAGE AFTER MODIFICATIONS BLOCK START
        $response = $this->actingAs($newUser)
                         ->withSession($newUserSessionData)
                         ->get('profile/view');
        if ($is_debug) {
//            $this->d('/profile/view   Test9:: $newUser->id::' . print_r($newUser->id, true));
        }
        $response->assertStatus(200);

        // 8. OPEN PROFILE DETAILS VIEW PAGE AFTER MODIFICATIONS BLOCK END



        /*
Route::group(array('prefix' => 'profile', 'middleware' => ['auth', 'isVerified']), function(){
            Route::get('edit-details', array(
//        'before'  => 'guest',
        'as'      => 'profile-edit-details',
        'uses'    => 'ProfileController@edit_details'
    ));


            Route::put('edit-details-put', array(
//        'before'  => 'guest|csrf',
        'as'      => 'profile-edit-details-put',
        'uses'    => 'ProfileController@update_details'
    ));
 */
        // 6. OPEN PROFILE DETAILS EDITOR PAGE BLOCK END


    }
}