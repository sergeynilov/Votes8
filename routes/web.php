<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();
Route::feeds(); //  https://github.com/spatie/laravel-feed



Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('cancel_payment', 'PaymentController@cancel_payment')->name('cancel_payment');
Route::post('run_payment', 'PaymentController@run_payment')->name('run_payment');

///  STRIPE BLOCK START
Route::get('stripe_callback_event', 'PaymentController@stripe_callback_event')->name('stripe_callback_event');
Route::get('stripe_express_payment_callback', 'PaymentController@stripe_express_payment_callback')->name('stripe_express_payment_callback');

//Route::get('/subscribe', function () {
//    return view('subscribe');
//});
Route::get('/subscribe', 'CheckoutController@subscribe');
Route::post('/subscribe_process', 'CheckoutController@subscribe_process');

///  STRIPE BLOCK END


///  PAYPAL PLAN BLOCK START
Route::get('paypal_payment_execute', 'PaymentController@paypal_payment_execute')->name('paypal_payment_execute');
Route::get('paypal_payment_cancel', 'PaymentController@paypal_payment_cancel')->name('paypal_payment_cancel');



Route::get('paypal_list_plan', 'PaymentController@paypal_list_plan')->name('paypal_list_plan');
Route::get('paypal_plan_detail/{plan_id}', 'PaymentController@paypal_plan_detail')->name('paypal_plan_detail');
//    public function planDetail($plan_id) // http://local-votes.com/paypal_plan
//    public function paypal_activate_plan($plan_id) // http://local-votes.com/paypal_activate_plan/P-8DC818364R7167805ANXTMPY
Route::get('paypal_activate_plan/{plan_id}', 'PaymentController@paypal_activate_plan')->name('paypal_activate_plan');

Route::get('select-service-subscription', array(
    'as'      => 'select-service-subscription',
    'uses'    => 'SelectServiceSubscriptionController@listing'
));

//    public function planDetail($plan_id) // http://local-votes.com/paypal_plan

///  PAYPAL PLAN BLOCK END




Route::get('sitemapping', 'SiteMappingController@generate')->name('sitemapping');



Route::get('/', 'HomeController@index')->name('root');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/msg', 'HomeController@msg')->name('home-msg');
Route::get('format_currency/{val}', 'HomeController@format_currency')->name('format_currency');

Route::post('logged-user', [
        'as'   => 'logged-user',
        'uses' => 'HomeController@get_logged_user']
);


Route::group(['middleware' => [ 'auth']], function(){
    Route::get('/user', 'GraphController@retrieveUserProfile');
    Route::post('/user', 'GraphController@publishToProfile');
    Route::post('/page', 'GraphController@publishToPage');
});

// it also works for /login/facebook
Route::get('/auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('/auth/{provider}/callback', 'Auth\LoginController@handleSocialiteProviderCallback');



/*Route::any ( 'sendemail', function () {
    if (Request::get ( 'message' ) != null)
        $data = array (
            'bodyMessage' => Request::get ( 'message' )
        );
    else
        $data [] = '';
    Mail::send ( 'email', $data, function ($message) {

        $message->from ( 'donotreply@demo.com', 'Just Laravel' );

        $message->to ( Request::get ( 'toEmail' ) )->subject ( 'Just Laravel demo email using SendGrid' );
    } );
    return Redirect::back ()->withErrors ( [
        'Your email has been sent successfully'
    ] );
} );*/

//Route::get('/image/{image_id}', ['as' => 'site.viewImage', 'uses' => 'ImageController@viewImage']);
//Route::get('/show_banner_image/{banner_id}', ['as' => 'site.viewImage', 'uses' => 'HomeController@test2']); //app/Http/Controllers/HomeController.php
//                 <img src="/show_banner_image/{{ $nextActiveBanners['id'] }}/{{ $nextActiveBanners['test'] }}/{{ $nextActiveBanners['url'] }}/{{ $nextActiveBanners['view_type']
Route::get('/show_banner_image/{banner_id}/{banner_text}/{banner_logo}/{banner_short_descr}/{banner_view_type}', ['as' => 'site.viewImage', 'uses' => 'HomeController@generate_banner_image']);
//app/Http/Controllers/HomeController.php

Route::any('/youtube-callback', ['middleware' => 'google_login', 'uses' => 'YoutubeAPIController@youtube_callback', 'as' => 'youtube-callback']);

Route::any('/videos', ['middleware' => 'google_login', 'uses' => 'YoutubeAPIController@videos', 'as' => 'videos']);
Route::get('/video/{id}', ['middleware' => 'google_login', 'uses' => 'YoutubeAPIController@video', 'as' => 'video']);
Route::any('/search', ['middleware' => 'google_login', 'as' => 'search', 'uses' => 'YoutubeAPIController@search']);

Route::get('/loginCallback', ['uses' => 'GoogleLoginController@store', 'as' => 'loginCallback']);



Route::get('/search-results/{text}/{vote_categories}/{search_in_blocks}', 'SearchController@results')->name('search-results');
Route::get('/get-most-used-search-results', 'SearchController@get_most_used_search_results')->name('get-most-used-search-results');

Route::get('logout', [
        'as'   => 'logout',
        'uses' => 'HomeController@logout']
);


$router->get('forgot-password', 'Auth\ForgotPasswordController@index')->name('forgot-password');
$router->post('password/reset/{token}', 'Auth\ForgotPasswordController@reset'); // password.request

Route::get('test', array(
    'as'      => 'test',
    'uses'    => 'HomeController@test'
));

Route::post('test', array(
    'as'      => 'test_post',
    'uses'    => 'HomeController@test'
));

Route::get('test2', array(
    'as'      => 'test2',
    'uses'    => 'HomeController@test2'
));

Route::post('sendEmail', array(
    'as'      => 'sendEmail',
    'uses'    => 'HomeController@sendEmail'
));

//Route::post('test-generate-pdf-by-content-test', array(
//    'as'      => 'test-generate-pdf-by-content-test',
//    'uses'    => 'HomeController@test_generate_pdf_by_content_test'
//));


///  PAGE CONTENT BLOCK START
Route::get('file-download/{download_id}', array(
    'as'      => 'file-download',
    'uses'    => 'PageController@file_download'
));


Route::post('contact-us-post', 'PageController@contacts_us_post')->name('contact-us-post');
Route::get('contact-us', array(
    'as'      => 'page-contact-us',
    'uses'    => 'PageController@page_contact_us'
));

Route::get('about', array(
    'as'      => 'page-about',
    'uses'    => 'PageController@page_about'
))->name('page-about');

Route::get('/page-content/about', 'PageController@page_about')->name('page-content-by-slug');

Route::get('security-privacy', array(
    'as'      => 'page-security-privacy',
    'uses'    => 'PageController@page_security_privacy'
));
//Route::get('/page-content/{slug}', 'PageController@page_security_privacy')->name('page-content-by-slug');
Route::get('/page-content/security-privacy', 'PageController@page_security_privacy');//->name('page-content-by-slug');

Route::get('warranty-and-service', array(
    'as'      => 'page-warranty-and-service',
    'uses'    => 'PageController@page_warranty_and_service'
));
Route::get('/page-content/warranty-and-service', 'PageController@page_warranty_and_service')->name('page-content-by-slug');

Route::get('terms-of-service', array(
    'as'      => 'page-terms-of-service',
    'uses'    => 'PageController@page_terms_of_service'
));

///  PAGE CONTENT BLOCK END



///  NEWS BLOCK START
Route::get('news', array(
    'as'      => 'news',
    'uses'    => 'PageController@news'
));
Route::get('/news/{news_slug}', 'PageController@page_news')->name('news');

Route::get('all-news/{page_number?}', array(
    'as'      => 'all-news',
    'uses'    => 'PageController@all_news'
));

Route::get('all-external-news', array(
    'as'      => 'all-external-news',
    'uses'    => 'PageController@all_external_news'
));
Route::get( 'get-all-external-news-listing/{page}', [ 'uses' => 'PageController@get_all_external_news_listing' ] );
///  NEWS BLOCK END


///  EVENTS_TIMELINE BLOCK START
Route::get('events-timeline', array(
    'as'      => 'events-timeline',
    'uses'    => 'EventsTimelineController@listing'
));
Route::get('/event/{event_slug}', 'EventsTimelineController@event')->name('event');
///  EVENTS_TIMELINE BLOCK END



///  VOTES BLOCK START

Route::get('/votes-by-category/{vote_category_slug}', 'VotesController@votes_by_category')->name('votes-by-category');

Route::get('/tag/{tag_slug}', 'VotesController@tag')->name('tag_by_slug');
Route::get('/vote/{vote_slug}', 'VotesController@vote')->name('vote_by_slug');
Route::post('/make-vote-selection', 'VotesController@make_vote_selection')->name('make_vote_selection');
Route::post('/make-quiz-quality', 'VotesController@make_quiz_quality')->name('make_quiz_quality');
Route::get('/load-vote-items/{vote_id}', 'VotesController@load_vote_items')->name('load_vote_items');

Route::get('/get-vote-results-in-stars/{vote_id}', 'VotesController@get_vote_results_in_stars')->name('get-vote-quiz-quality-in-stars');
Route::get('/get-vote-quiz-quality-in-stars/{vote_id}', 'VotesController@get_vote_quiz_quality_in_stars')->name('get-vote-quiz-quality-in-stars');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index'); //Go to http://myapp/logs or some other route

///  VOTES BLOCK END


///  PROFILE BLOCK START

Route::get('public-profile-view/{user_id}', 'HomeController@public_profile_view')->name('public-profile-view');

Route::group(array('prefix' => 'profile', 'middleware' => ['auth', 'isVerified', 'CheckUserStatus']), function(){
    Route::get('view', array(
        'as'      => 'profile-view',
        'uses'    => 'ProfileController@index'
    ));

    Route::post('generate-pdf-by-content', array(
        'as'      => 'generate-pdf-by-content',
        'uses'    => 'ProfileController@generate_pdf_by_content'
    ));

    Route::get('user-chat-container', array(
        'as'      => 'user-chat-container',
        'uses'    => 'UserChatController@user_chat_container'
    ));



    Route::get('load-user-chat-content/{chat_id}', array(
        'as'      => 'profile-load-user-chat-content',
        'uses'    => 'UserChatController@load_user_chat_content'
    ));

    Route::get('show-chat-members/{chat_id}/{activeUserIds}', array(
        'as'      => 'profile-show-chat-members',
        'uses'    => 'UserChatController@show_chat_members'
    ));

    Route::get('attach-user-to-chat/{chat_id}/{user_id}/{status}', array(
        'as'      => 'profile-attach-user-to-chat',
        'uses'    => 'UserChatController@attach_user_to_chat'
    ));

    Route::get('clear-user-in-chat/{chat_id}/{user_id}', array(
        'as'      => 'profile-clear-user-in-chat',
        'uses'    => 'UserChatController@clear_user_in_chat'
    ));

    //                     url: this_backend_home_url+"/profile/upload-chat-message-document-to-tmp/"+this_chat_id,
    Route::post('upload-chat-message-document-to-tmp/{chat_id}', [
            'as'   => 'upload-chat-message-document-to-tmp',
            'uses' => 'UserChatController@upload_chat_message_document_to_tmp']
    );
    //http://local-votes.com/profile/upload-image-to-chat-message-document
    Route::post('upload-image-to-chat-message-document/{chat_id}', [
            'as'   => 'upload-image-to-chat-message-document',
            'uses' => 'UserChatController@upload_image_to_chat_message_document']
    );
    //    Route::post('/upload-image-to-page-content', 'Admin\PageContentsController@upload_image_to_page_content');
    Route::delete('delete-chat-message-document', 'Admin\SettingsController@delete_settings_file_on_registration_destroy');

    //    var href = this_backend_home_url+"/profile/get-temp-uploading-chat-message-documents/"+this_chat_id
    Route::get('get-temp-uploading-chat-message-documents/{chat_id}', array(
        'as'      => 'get-temp-uploading-chat-message-documents',
        'uses'    => 'UserChatController@get_temp_uploading_chat_message_documents'
    ));




    Route::post('chat-message-sent', array(
        'as'      => 'chat-message-sent',
        'uses'    => 'UserChatController@chat_message_sent'
    ));

    Route::get('print-to-pdf', array(
        'as'      => 'profile-print-to-pdf',
        'uses'    => 'ProfileController@print_to_pdf'
    ));

    Route::post('print-to-pdf', array(
        'as'      => 'profile-print-to-pdf',
        'uses'    => 'ProfileController@print_to_pdf'
    ));

    Route::get('print-to-pdf-options', array(
        'as'      => 'profile-print-to-pdf-options',
        'uses'    => 'ProfileController@print_to_pdf_options'
    ));

    Route::get('edit-details', array(
        'as'      => 'profile-edit-details',
        'uses'    => 'ProfileController@edit_details'
    ));
    Route::put('edit-details-post', array(
        'as'      => 'profile-edit-details-post',
        'uses'    => 'ProfileController@update_details'
    ))->middleware('WorkTextString:notes');


    Route::get('profile-payments', array(
        'as'      => 'profile-payments',
        'uses'    => 'ProfileController@profile_payments'
    ));

    Route::get('edit-avatar', array(
        'as'      => 'profile-edit-avatar',
        'uses'    => 'ProfileController@edit_avatar'
    ));

    Route::put('edit-avatar-put', array(
        'as'      => 'profile-edit-avatar-put',
        'uses'    => 'ProfileController@update_avatar'
    ));

    Route::get('edit-subscriptions', array(
        'as'      => 'profile-edit-subscriptions',
        'uses'    => 'ProfileController@edit_subscriptions'
    ));

    Route::get('get-user-mail-chimp-info', array(
        'as'      => 'profile-get-user-mail-chimp-info',
        'uses'    => 'ProfileController@get_user_mail_chimp_info'
    ));

    Route::get( 'get-related-users-site-subscriptions', [ 'uses' => 'ProfileController@get_related_users_site_subscriptions' ] );
    Route::get( 'subscribe-users-site-subscription/{site_subscription_id}', [ 'uses' => 'ProfileController@subscribe_users_site_subscription' ] );
    Route::get( 'unsubscribe-users-site-subscription/{site_subscription_id}', [ 'uses' => 'ProfileController@unsubscribe_users_site_subscription' ] );


    Route::get( 'load_payment_agreements', [ 'uses' => 'ProfileController@load_payment_agreements' ] );
    Route::get( 'load_payment_agreement_details/{payment_agreement_id}', [ 'uses' => 'ProfileController@load_payment_agreement_details' ] );

    Route::post('get-profile-payment-items-rows', [
            'as'   => 'get-profile-payment-items-rows',
            'uses' => 'ProfileController@get_payment_items_rows']
    );


    Route::post('generate-password', array(
        'as'      => 'profile-generate-password',
        'uses'    => 'ProfileController@generate_password'
    ));


    Route::get( 'profile_select_service_subscription', [
        'as'   => 'profile_select_service_subscription',
        'uses' => 'ProfileController@profile_select_service_subscription' ] );


    Route::get( 'profile_confirm_subscribe_to_service_subscription/{package_id}', [
        'as'   => 'profile_confirm_subscribe_to_service_subscription',
        'uses' => 'ProfileController@profile_confirm_subscribe_to_service_subscription' ] );

    Route::post( 'profile_subscribe_to_service_subscription', [
        'as'   => 'profile_subscribe_to_service_subscription',
        'uses' => 'ProfileController@profile_subscribe_to_service_subscription' ] );

    Route::post( 'profile_subscribe_to_service_subscription_paypal', [
        'as'   => 'profile_subscribe_to_service_subscription_paypal',
        'uses' => 'ProfileController@profile_subscribe_to_service_subscription_paypal' ] );

    Route::post( 'profile_cancel_service_subscription_subscription', [
        'as'   => 'profile_cancel_service_subscription_subscription',
        'uses' => 'ProfileController@profile_cancel_service_subscription_subscription' ] );

}); // Route::group(array('prefix' => 'profile'), function(){

///  PROFILE BLOCK END


///  REGISTER BLOCK START

Route::get('register', array(
    'before'  => 'guest',
    'as'      => 'account-register-details',
    'uses'    => 'AccountController@getDetails'
));

Route::group(array('prefix' => 'account'), function(){
    Route::get('register', array(
        'before'  => 'guest',
        'as'      => 'account-register-details',
        'uses'    => 'AccountController@getDetails'
    ));

    Route::get('register/details', array(
        'before'  => 'guest',
        'as'      => 'account-register-details',
        'uses'    => 'AccountController@getDetails'
    ));

    Route::post('register/details', array(
        'before'  => 'guest|csrf',
        'as'      => 'account-register-details-post',
        'uses'    => 'AccountController@postDetails'
    ));

    Route::get('register/avatar', array(
        'before'  => 'guest',
        'as'      => 'account-register-avatar',
        'uses'    => 'AccountController@getAvatar'
    ));

    Route::post('register/avatar', array(
        'before'  => 'guest|csrf',
        'as'      => 'account-register-avatar-post',
        'uses'    => 'AccountController@postAvatar'
    ));

    Route::get('register/subscriptions', array(
        'before'  => 'guest',
        'as'      => 'account-register-subscriptions',
        'uses'    => 'AccountController@getSubscriptions'
    ));

    Route::post('register/subscriptions', array(
        'before'  => 'guest|csrf',
        'as'      => 'account-register-subscriptions-post',
        'uses'    => 'AccountController@postSubscriptions'
    ));

    Route::get('register/details', array(
        'before'  => 'guest',
        'as'      => 'account-register-details',
        'uses'    => 'AccountController@getDetails'
    ));

    Route::post('register/details', array(
        'before'  => 'guest|csrf',
        'as'      => 'account-register-details-post',
        'uses'    => 'AccountController@postDetails'
    ));

    Route::get('register/confirm', array(
        'before'  => 'guest',
        'as'      => 'account-register-confirm',
        'uses'    => 'AccountController@getConfirm'
    ));

    Route::post('register/confirm', array(
        'before'  => 'guest|csrf',
        'as'      => 'account-register-confirm-post',
        'uses'    => 'AccountController@postConfirm'
    ));

    Route::get('register/cancel', array( //     public function cancelAccountRegistration()

        'before'  => 'guest',
        'as'      => 'account-register-cancel',
        'uses'    => 'AccountController@cancelAccountRegistration'
    ));

    Route::get('activation/{activation_key}', array( // http://local-votes
        //.com/account/activation/63b6b78ede2178787c17b455fe92aa24d338b92ceffd5697b3d8b0657176cb36
        'before'  => 'guest',
        'as'      => 'account-activation',
        'uses'    => 'AccountController@activation'
    ));


}); // Route::group(array('prefix' => 'account'), function(){

///  REGISTER BLOCK END


///  BACKEND BLOCK START


Route::group(['middleware' => ['auth', 'isVerified', 'CheckUserStatus'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('oauthAdminCallback', [ 'as' => 'oauthAdminCallback', 'uses' =>'Admin\EventsController@oauthAdminCallback']); //->name('admin.oauthAdminCallback');



    Route::post('/video/store', 'Admin\VideoController@store');

    ///  DASHBOARD BLOCK START
    Route::get('msg',  array( // msg parameters are defined in session
            'as'      => 'msg',
            'uses'    => 'Admin\DashboardController@msg')
    );

    Route::get('/', 'Admin\DashboardController@index');

    Route::get('dashboard', array(
        'as'      => 'dashboard',
        'uses'    => 'Admin\DashboardController@index'
    ));

    Route::get('test', array(
        'as'      => 'dashboard-test',
        'uses'    => 'Admin\DashboardController@test'
    ));

//    var href = this_backend_home_url + "/admin/publish-to-profile";
    Route::post('publish-to-profile', 'GraphController@publish_to_profile');
    // retrieveUserProfile

    Route::get('show-logged-user-info', [
        'as'   => 'show-logged-user-info',
        'uses' => 'Admin\DashboardController@show_logged_user_info']
    );

    Route::get('get-system-info', [
        'as'   => 'get-system-info',
        'uses' => 'Admin\DashboardController@get_system_info']
    );

    Route::get('refresh-demo-data', [ // /dashboard/refresh_demo_data
        'as'   => 'refresh-demo-data',
        'uses' => 'Admin\DashboardController@refresh_demo_data']
    );

    Route::post('get-payment-items-rows', [
        'as'   => 'get-payment-items-rows',
        'uses' => 'Admin\DashboardController@get_payment_items_rows']
    );

    Route::get('get-activity-log-rows/{page}', [
        'as'   => 'get-activity-log-rows',
        'uses' => 'Admin\DashboardController@get_activity_log_rows']
    );

    Route::get('clear-activity-log-rows', [
        'as'   => 'clear-activity-log-rows',
        'uses' => 'Admin\DashboardController@clear_activity_log_rows']
    );
    ///  DASHBOARD BLOCK END



    ///  TODO BLOCK START
    Route::get('todo-container-page', [
            'as'   => 'todo-container-page',
            'uses' => 'Admin\DashboardController@todo_container_page']
    );
    Route::get('show-todo-page', [
            'as'   => 'show-todo-page',
            'uses' => 'Admin\DashboardController@show_todo_page']
    );
    Route::post('save-todo-page', array(
        'as'      => 'save-todo-page',
        'uses'    => 'Admin\DashboardController@save_todo_page'
    ));
    Route::delete('delete-todo', array(
        'as'      => 'delete-todo',
        'uses'    => 'Admin\DashboardController@delete_todo'
    ));
//    Route::delete('/vote-item/{vote_item_id}/destroy', 'Admin\VotesController@vote_item_destroy');


    ///  TODO BLOCK END


    ///  SITE SUBSCRIPTIONS BLOCK START
    Route::get('site-subscriptions/filter/{filter_type}/{filter_value}', 'Admin\SiteSubscriptionsController@index')->name('SubscriptionsFilter');
    Route::resource('site-subscriptions', 'Admin\SiteSubscriptionsController', [ 'except' => [/*'edit' */ ] ] )->middleware('WorkTextString');

    Route::get( 'get-site-subscriptions-dt-listing', [ 'uses' => 'Admin\SiteSubscriptionsController@get_site_subscriptions_dt_listing' ] );
    Route::get('/get-site-subscription-details-info/{site_subscription_id}', 'Admin\SiteSubscriptionsController@get_site_subscription_details_info');
    ///  SITE SUBSCRIPTIONS BLOCK END





    ///  SERVICE SUBSCRIPTIONS BLOCK START
    Route::get('paypal_plans', 'Admin\ServiceSubscriptionsController@paypal_plans')->name('paypal_plans');
    Route::get('load_paypal_plans', 'Admin\ServiceSubscriptionsController@load_paypal_plans')->name('load_paypal_plans');
    Route::delete('paypal-plan/destroy', 'Admin\ServiceSubscriptionsController@paypal_plan_destroy')->name('paypal-plan-destroy');
    // /admin/paypal-plan/deactivate
    Route::POST('paypal-plan/activate', 'Admin\ServiceSubscriptionsController@paypal_plan_activate')->name('paypal-plan-activate');
    Route::POST('paypal-plan/deactivate', 'Admin\ServiceSubscriptionsController@paypal_plan_deactivate')->name('paypal-plan-deactivate');

    // http://votes.my-demo-apps.tk//admin/paypal_plan_success?token=EC-804610684X9649344
    Route::get('paypal_plan_success', 'Admin\ServiceSubscriptionsController@paypal_plan_success')->name('paypal_plan_success');
    Route::get('paypal_plan_failure', 'Admin\ServiceSubscriptionsController@paypal_plan_failure')->name('paypal_plan_failure');
//    Route::get('execute-agreement/{success}', 'Admin\ServiceSubscriptionsController@executeAgreement')->name('executeAgreement');

    Route::get('service-subscriptions/filter/{filter_type}/{filter_value}', 'Admin\ServiceSubscriptionsController@index')->name('ServiceSubscriptionsFilter');
    Route::resource('service-subscriptions', 'Admin\ServiceSubscriptionsController', [ 'except' => [/*'edit' */ ] ] )->middleware('WorkTextString');

    Route::get( 'get-service-subscriptions-dt-listing', [ 'uses' => 'Admin\ServiceSubscriptionsController@get_service_subscriptions_dt_listing' ] );
    Route::get('/get-service-subscription-details-info/{service_subscription_id}', 'Admin\ServiceSubscriptionsController@get_site_subscription_details_info');


    Route::get('paypal_create_agreement/{plan_id}', 'Admin\ServiceSubscriptionsController@paypal_create_agreement')->name('paypal_create_agreement');
//

    Route::post('/paypal_create_plan', 'Admin\ServiceSubscriptionsController@paypal_create_plan')->name('paypal_create_plan');
    Route::post('/clear_paypal_plan', 'Admin\ServiceSubscriptionsController@clear_paypal_plan')->name('clear_paypal_plan');

    Route::post('paypal_activate_plan', 'Admin\ServiceSubscriptionsController@paypal_activate_plan');
    Route::post('paypal_get_plans_list', 'Admin\ServiceSubscriptionsController@paypal_get_plans_list');
    /*             var href = this_backend_home_url + "/admin/service-subscriptions/paypal_activate_subscription";
            $.ajax( {
                type: "POST",
                dataType: "json",
                url: href,
                data: {"id": id, "name": name, "_token": this_csrf_token},
 */
///  SERVICE SUBSCRIPTIONS BLOCK END



    ///  CONTACT US BLOCK START
    Route::get('contact-us/filter/{filter_type}/{filter_value}', 'Admin\ContactUsController@index')->name('ContactUsFilter');
    Route::resource('contact-us', 'Admin\ContactUsController', ['except' => []])->middleware('WorkTextString:message');
    Route::get( 'get-contact-us-dt-listing', [ 'uses' => 'Admin\ContactUsController@get_contact_us_dt_listing' ] );
    Route::get('/contact-us-accept/{contact_us_id}', 'Admin\ContactUsController@contact_us_accept');
    ///  CONTACT US BLOCK END



    ///  USERS BLOCK START
    Route::get('users/filter/{filter_type}/{filter_value}', 'Admin\UsersController@index')->name('UsersFilter');
    Route::resource('users', 'Admin\UsersController', ['except' => []])->middleware('WorkTextString:notes');

    Route::get('/get-user-access-groups-info/{user_id}', 'Admin\UsersController@get_user_access_groups_info');
    Route::post('/update-user-access', 'Admin\UsersController@update_user_access');

    Route::get('/get-user-related-site-subscriptions/{user_id}', 'Admin\UsersController@get_user_related_site_subscriptions')->name('admin-get-user-related-site-subscriptions');

    Route::get('/users/attach-related-site-subscription/{user_id}/{site_subscription_id}', 'Admin\UsersController@attach_related_site_subscription');
    Route::get('/users/clear-related-site-subscription/{user_id}/{site_subscription_id}', 'Admin\UsersController@clear_related_site_subscription');

    Route::get('/get-user-related-chats/{user_id}', 'Admin\UsersController@get_user_related_chats')->name('admin-get-user-related-chats');
    Route::post('/users/attach-chat-participant-to-user', 'Admin\UsersController@attach_chat_participant_to_user');
    Route::post('/users/clear-related-chat', 'Admin\UsersController@clear_related_chat');

    Route::get( 'get-users-dt-listing', [ 'uses' => 'Admin\UsersController@get_users_dt_listing' ] );
    Route::post('/user/generate-password/{user_id}', array(
        'as'      => 'user-generate-password',
        'uses'    => 'Admin\UsersController@generate_password'
    ));
    ///  USERS BLOCK END



    ///  CHATS BLOCK START
    Route::get('chats/filter/{filter_type}/{filter_value}', 'Admin\ChatsController@index')->name('ChatsFilter');
    Route::resource('chats', 'Admin\ChatsController', ['except' => []])->middleware('WorkTextString:description');
    Route::get( 'get-chats-dt-listing', [ 'uses' => 'Admin\ChatsController@get_chats_dt_listing' ] );
    Route::get('/get-chat-details-info/{chat_id}', 'Admin\ChatsController@get_chat_details_info');
    Route::get('/get-chat-related-chat-participants/{chat_id}', 'Admin\ChatsController@get_chat_related_chat_participants')->name('admin-get-chat-related-chat-participants');
    ///  CHATS BLOCK END



    ///  VOTE CATEGORIES BLOCK START
    Route::get('vote-categories/filter/{filter_type}/{filter_value}', 'Admin\VoteCategoriesController@index')->name('VoteCategoriesFilter');

    Route::resource('vote-categories', 'Admin\VoteCategoriesController', ['except' => []])->middleware('WorkTextString');
    Route::delete('vote-categories', 'Admin\VoteCategoriesController@destroy');

    Route::get( 'get-vote-categories-dt-listing', [ 'uses' => 'Admin\VoteCategoriesController@get_vote_categories_dt_listing' ] );
    Route::get('/get-vote-category-details-info/{vote_category_id}', 'Admin\VoteCategoriesController@get_vote_category_details_info');
    ///  VOTE CATEGORIES BLOCK END
    ///
    /// VOTE CATEGORIES META KEYWORD EDITOR BLOCK START
    Route::get( '/get-vote-category-meta-keywords/{vote_category_id}', [ 'uses' => 'Admin\VoteCategoriesController@get_vote_category_meta_keywords' ] );
    Route::get('/votes/attach-vote-category-meta-keyword/{vote_category_id}/{meta_keyword}', 'Admin\VoteCategoriesController@attach_meta_keyword');
    Route::get('/votes/clear-vote-category-meta-keyword/{vote_category_id}/{meta_keyword}', 'Admin\VoteCategoriesController@clear_meta_keyword');
    /// VOTE CATEGORIES META KEYWORD EDITOR BLOCK END


    ///  TAGS BLOCK START
    Route::resource('tags', 'Admin\TagsController', ['except' => []])->middleware('WorkTextString:description');
    Route::get( 'get-tags-dt-listing', [ 'uses' => 'Admin\TagsController@get_tags_dt_listing' ] );
    Route::get('/get-tag-details-info/{tag_id}', 'Admin\TagsController@get_tag_details_info');
    ///  TAGS BLOCK END

    /// TAGS META KEYWORD EDITOR BLOCK START
    Route::get( '/get-tag-meta-keywords/{tag_id}', [ 'uses' => 'Admin\TagsController@get_tag_meta_keywords' ] );
    Route::get('/votes/attach-tag-meta-keyword/{tag_id}/{meta_keyword}', 'Admin\TagsController@attach_meta_keyword');
    Route::get('/votes/clear-tag-meta-keyword/{tag_id}/{meta_keyword}', 'Admin\TagsController@clear_meta_keyword');
    /// TAGS META KEYWORD EDITOR BLOCK END


    ///  PAGE CONTENTS BLOCK START
    Route::resource('page-contents', 'Admin\PageContentsController', ['except' => []])->middleware('WorkTextString:content');
    Route::get( 'get-page-content-dt-listing', [ 'uses' => 'Admin\PageContentsController@get_page_content_dt_listing' ] );
    Route::get('/get-page-content-details-info/{page_content_id}', 'Admin\PageContentsController@get_page_content_details_info');

    Route::get('/get-related-page-content-images/{page_content_id}', 'Admin\PageContentsController@get_related_page_content_images');
    Route::delete('/delete-page-content-image', 'Admin\PageContentsController@delete_page_content_image');
    Route::post('/upload-page-content-image-to-tmp-page-content', 'Admin\PageContentsController@upload_page_content_image_to_tmp_page_content');
    Route::post('/upload-image-to-page-content', 'Admin\PageContentsController@upload_image_to_page_content');
    ///  PAGE CONTENTS BLOCK END

    ///  BANNERS BLOCK START
    Route::resource('banners', 'Admin\BannersController', ['except' => []])->middleware('WorkTextString');
    Route::get( 'get-banner-dt-listing', [ 'uses' => 'Admin\BannersController@get_banner_dt_listing' ] );
    Route::get('/get-banner-details-info/{banner_id}', 'Admin\BannersController@get_banner_details_info');
    Route::delete('banners-destroy', 'Admin\BannersController@destroy');
    ///  BANNERS BLOCK END


    ///  EVENTS BLOCK START
    Route::resource('events', 'Admin\EventsController', ['except' => []])->middleware('WorkTextString:description');
    Route::get( 'get-event-dt-listing', [ 'uses' => 'Admin\EventsController@get_event_dt_listing' ] );
    Route::post( 'get_events_fc_listing', [ 'uses' => 'Admin\EventsController@get_events_fc_listing' ] );
    Route::get('/get-event-details-info/{event_id}', 'Admin\EventsController@get_event_details_info');
    Route::delete('events', 'Admin\EventsController@destroy');

    Route::post('events_calendar_action_insert', 'Admin\EventsController@events_calendar_action_insert');
    Route::get('add_demo_events', 'Admin\EventsController@add_demo_events')->name('add_demo_events');

    Route::get('synchronize_google_events', 'Admin\EventsController@synchronize_google_events')->name('synchronize_google_events');
    Route::post('synchronize_google_events', 'Admin\EventsController@synchronize_google_events')->name('synchronize_google_events');


    Route::get( 'load_event_attendees/{event_id}', [ 'uses' => 'Admin\EventsController@load_event_attendees' ] );
    Route::delete( 'clear_event_attendee', [ 'uses' => 'Admin\EventsController@clear_event_attendee' ] );
    Route::post( 'add_active_users_to_event', [ 'uses' => 'Admin\EventsController@add_active_users_to_event' ] );
    Route::post( 'add_external_user_event', [ 'uses' => 'Admin\EventsController@add_external_user_event' ] );



    ///  EVENTS BLOCK END


    ///  EXTERNAL NEWS IMPORTING BLOCK START
    Route::resource('external-news-importing', 'Admin\ExternalNewsImportingController', ['except' => []])->middleware('WorkTextString');
    Route::get( 'get-external-news-importing-dt-listing', [ 'uses' => 'Admin\ExternalNewsImportingController@get_external_news_importing_dt_listing' ] );
    ///  EXTERNAL NEWS IMPORTING BLOCK END


    /// VOTES REPORTS BLOCK START
    Route::get('report-votes-by-days', 'Admin\ReportsController@votes_by_days')->name('report_votes_by_days');
    Route::post('report-votes-by-days-retrieve', 'Admin\ReportsController@votes_by_days_retrieve')->name('report_votes_by_days_retrieve');

    Route::get('report-votes-by-vote-names', 'Admin\ReportsController@votes_by_vote_names')->name('report_votes_by_vote_names');
    Route::post('report-votes-by-vote-names-retrieve', 'Admin\ReportsController@votes_by_vote_names_retrieve')->name('report_votes_by_vote_names_retrieve');
    Route::post('report-votes-by-vote-names-retrieve-by-vote-id', 'Admin\ReportsController@votes_by_vote_names_retrieve_by_vote_id')->name('report_votes_by_vote_names_retrieve_by_vote_id');
    Route::post('report-votes-by-vote-names-retrieve-by-selected-day', 'Admin\ReportsController@votes_by_vote_names_retrieve_by_selected_day')->name('report_votes_by_vote_names_retrieve_by_selected_day');

    Route::get('report-quizzes-rating', 'Admin\ReportsController@quizzes_rating')->name('report_quizzes_rating');
    Route::post('report-quizzes-rating-retrieve', 'Admin\ReportsController@quizzes_rating_retrieve')->name('report_quizzes_rating_retrieve');

    Route::get('report-compare-correct-votes', 'Admin\ReportsController@compare_correct_votes')->name('report_compare_correct_votes');
    Route::post('report-compare-correct-votes-retrieve', 'Admin\ReportsController@compare_correct_votes_retrieve')->name('report_compare_correct_votes_retrieve');

    Route::get('report-search-results', 'Admin\ReportsController@search_results')->name('report_search_results');
    Route::post('report-search-results-retrieve', 'Admin\ReportsController@search_results_retrieve')->name('report_search_results_retrieve');

    Route::post('report-save-to-excel', 'Admin\ReportsController@report_save_to_excel')->name('report-save-to-excel');


//    admin/report-payments
    Route::get('report-payments', 'Admin\ReportsController@report_payments')->name('report_payments');
    Route::post('report_payments_retrieve', 'Admin\ReportsController@report_payments_retrieve')->name('report_payments_retrieve');
    Route::post('report_open_items_count_details', 'Admin\ReportsController@report_open_items_count_details')->name('report_open_items_count_details');
    Route::post('report_payments_by_days_count_details', 'Admin\ReportsController@report_payments_by_days_count_details')->name('report_payments_by_days_count_details');

    /// VOTES REPORTS BLOCK END



    /// VOTE EDITOR BLOCK START
    Route::get('votes/filter/{filter_type}/{filter_value}', 'Admin\VotesController@index')->name('VotesFilter');
    Route::resource( 'votes', 'Admin\VotesController', ['except' => []] ) ;
    Route::get( 'get-votes-dt-listing', [ 'uses' => 'Admin\VotesController@get_votes_dt_listing' ] );

    Route::get('/get-vote-details-info/{vote_id}', 'Admin\VotesController@get_vote_details_info');
    /// VOTE EDITOR BLOCK END


    /// VOTE TAG EDITOR BLOCK START
    Route::get( 'get-vote-related-tags/{vote_id}', [ 'uses' => 'Admin\VotesController@get_vote_related_tags' ] );
    Route::get('/votes/attach-related-tag/{vote_id}/{tag_id}', 'Admin\VotesController@attach_related_tag');
    Route::get('/votes/clear-related-tag/{vote_id}/{tag_id}', 'Admin\VotesController@clear_related_tag');
    /// VOTE TAG EDITOR BLOCK END

    /// VOTE META KEYWORD EDITOR BLOCK START
    Route::get( '/get-vote-meta-keywords/{vote_id}', [ 'uses' => 'Admin\VotesController@get_vote_meta_keywords' ] );
    Route::get('/votes/attach-vote-meta-keyword/{vote_id}/{meta_keyword}', 'Admin\VotesController@attach_meta_keyword');
    Route::get('/votes/clear-vote-meta-keyword/{vote_id}/{meta_keyword}', 'Admin\VotesController@clear_meta_keyword');
    /// VOTE META KEYWORD EDITOR BLOCK END


    /// VOTE ITEM BLOCK START
    Route::get('/get-vote-item-info/{vote_id}', 'Admin\VotesController@get_vote_item_info');
    Route::get('/vote-item/{vote_item_id}/edit', 'Admin\VotesController@vote_item_edit');
    Route::get('/vote-item/create/{vote_id}', 'Admin\VotesController@vote_item_create');
    Route::put('/vote-item/{vote_item_id}/update', 'Admin\VotesController@vote_item_update');
    Route::post('/vote-item/{vote_id}/store', 'Admin\VotesController@vote_item_store');
    Route::delete('/vote-item/{vote_item_id}/destroy', 'Admin\VotesController@vote_item_destroy');


    /// VOTE ITEM BLOCK END

    // VOTE'S ELASTICSEARCH  BLOCK BEGIN
    //    The route for select_vote/votes/_mapping needs a delete method
    //Route::delete('select_vote/votes/_mapping', 'Controller@method');
//    Route::delete('select_vote/votes/_mapping', 'Controller@method');
    /// VOTE'S ELASTICSEARCH  BLOCK END


    /// SETTINGS BLOCK START
    ///
    /// 'settings'
//    Route::get('settings', 'Admin\SettingsController@index')->name('settings');
    //        return redirect()->route('admin.settings.index');

    Route::get('settings', array(
        'as'      => 'settings.index',
        'uses'    => 'Admin\SettingsController@index'
    ));

    Route::put('settings', array(
        'as'      => 'settings-put',
        'uses'    => 'Admin\SettingsController@update'
    ))->middleware('WorkTextString:common_settings_support_signature site_content_account_register_details_text site_content_account_register_avatar_text site_content_account_register_subscriptions_text site_content_account_register_confirm_text site_content_account_contacts_us_text');


    Route::get('get-settings-show-quiz-quality-options-listing', 'Admin\SettingsController@get_settings_show_quiz_quality_options_listing')->name('get-settings-show-quiz-quality-options-listing');
    Route::delete('settings-show-quiz-quality-option-destroy', 'Admin\SettingsController@settings_show_quiz_quality_option_destroy');
    Route::post('add-settings-show-quiz-quality', 'Admin\SettingsController@add_settings_show_quiz_quality');     // common_settings_support_signature


    Route::get('get-settings-related-file-on-registrations', 'Admin\SettingsController@get_settings_related_file_on_registrations')->name('get-settings-related-file-on-registrations');


    Route::post('upload-settings-file-on-registration-to-tmp', [
            'as'   => 'upload-settings-file-on-registration-to-tmp',
            'uses' => 'Admin\SettingsController@upload_settings_file_on_registration_to_tmp_file_on_registration']
    );
    Route::post('upload-settings-image-to-file-on-registration', [
            'as'   => 'upload-settings-image-to-file-on-registration',
            'uses' => 'Admin\SettingsController@upload_settings_image_to_file_on_registration']
    );
    Route::delete('delete-settings-file-on-registration', 'Admin\SettingsController@delete_settings_file_on_registration_destroy');

    Route::post('set-developers-mode-on', 'Admin\SettingsController@set_developers_mode_on')->name('set-developers-mode-on');
    Route::post('set-developers-mode-off', 'Admin\SettingsController@set_developers_mode_off')->name('set-developers-mode-off');

    Route::get('view_laravel_log', 'Admin\SettingsController@view_laravel_log');
    Route::get('delete_laravel_log', 'Admin\SettingsController@delete_laravel_log');

    Route::get('view_paypal_log', 'Admin\SettingsController@view_paypal_log');
    Route::get('delete_paypal_log', 'Admin\SettingsController@delete_paypal_log');

    Route::get('view_logging_deb', 'Admin\SettingsController@view_logging_deb');
    Route::get('delete_logging_deb', 'Admin\SettingsController@delete_logging_deb');
    // view_logging_deb

    Route::get('view_sql_tracing_log', 'Admin\SettingsController@view_sql_tracing_log');
    Route::get('delete_sql_tracing_log', 'Admin\SettingsController@delete_sql_tracing_log');
    //             var href = this_backend_home_url+"/admin/delete-settings-file-on-registration"



    //    Route::post('/upload-page-content-image-to-tmp-page-content', 'Admin\PageContentsController@upload_page_content_image_to_tmp_page_content');
//    Route::post('/upload-image-to-page-content', 'Admin\PageContentsController@upload_image_to_page_content');


    //         url: this_backend_home_url+'/admin/upload-settings-image-to-file-on-registration',
    //            var href = this_backend_home_url+"/admin/delete-settings-file-on-registration"

    /// SETTINGS BLOCK END

}); /// Route::group(array('prefix' => 'admin'), function(){
///
///  BACKEND BLOCK END
