<?php
$app_debug=  env('APP_DEBUG', false);


//    'debug' => env('APP_DEBUG', false),
//'debug' => $app_debug,

if ( !empty($_REQUEST['is_debug']) and (int)$_REQUEST['is_debug'] == 9) {
    $app_debug= true;
}
//if ( !$app_debug ) {
//    if ( !empty($_SERVER['APP_URL'])) {
//        $pos = strpos($_SERVER['APP_URL'], 'local-votes.com');
//        if ( ! ($pos === false)) {
//            $app_debug= true;
//        }
//    }
//}
//$app_debug= true;
//echo '<pre>$app_debug::'.print_r($app_debug,true).'</pre>';
//die("-1 XXZ");
return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    'GOOGLE_CALENDAR_ID' => env('GOOGLE_CALENDAR_ID', ''),
    'GOOGLE_CALENDAR_NAME' => env('GOOGLE_CALENDAR_NAME', ''),

    'telescope_enabled' => (bool) env('TELESCOPE_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

//    'debug' => env('APP_DEBUG', false),
    'debug' => $app_debug,

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url'      => env('APP_URL', 'http://local-votes.com'),

    'SHOW_DEMO_ADMIN_ON_LOGIN' => 1,
    'DEMO_ADMIN_EMAIL'         => 'votes_demo@votes.com',
    'DEMO_ADMIN_PASSWORD'      => '654321',

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => env('timezone', 'UTC'),
    'backend_home_url'      => '',
    'frontend_home_url'     => '',
    'empty_img_url'         => '/images/emptyImg.png',
    'medium_slogan_img_url' => '/images/slogans/slogan_2.jpg',
    'fileExtensionsImages'  =>['pdf'=>'pdf.jpg', 'doc'=>'doc.png', 'xls'=>'xls.png', 'ods'=>'xls.png', 'jpg'=>'image.jpg', 'jpeg'=>'image.jpg', 'png'=>'image.jpg', 'html'=> 'html.png', 'htm'=> 'html.png', 'xhtml'=> 'html.png', 'csv'=> 'csv.jpeg'],


    'php_unit_tests_logged_user_id'   => 5,
    'php_unit_tests_vote_category_id' => 5,
    'images_extensions'               => ['png', /*'webp',*/ 'jpg', 'jpeg', 'gif'],
    'uploaded_documents_extensions'   => ['png', 'webp', 'jpg', 'jpeg', 'gif', 'doc', 'xls', 'zip', 'rar'],
    'uploaded_file_max_mib'           => 14,
    'avatar_dimension_limits'         => ['max_width' => 64, 'max_height' => 64],
    'full_photo_dimension_limits'     => ['max_width' => 256, 'max_height' => 256],
    'valid_color_format'              => '~^#(([0-9a-fA-F]{2}){3}|([0-9a-fA-F]){3})$~',
//    'valid_color_format'=> '/^(#[a-f0-9]{6}|black|green|silver|gray|olive|white|yellow|maroon|navy|red|blue|purple|teal|fuchsia|aqua)$/i',

    'valid_nice_name_format'             => '~^[A-Za-z]*$~',
//    'valid_nice_name_format'     => '~^[\p{L}\s\'.-]+$~',
    'ff_valid_phone_format'              => '(01)[0-9]{9}',
    'valid_percent_format'               => '^\d*(\.\d{1,2})?$',
    'valid_geographic_coordinate_format' => '^[\-]?\d*(\.\d{1,7})?$',
    'valid_money_format'                 => '^\d+(\.\d{1,2})?$',
    'valid_shipping_decimal_format'      => '^\d*(\.\d{1,2})?$',
    'valid_phone_format'                 => '(01)[0-9]{9}',
    'currency_sign'                      => '$',
    'currency_sign_alignment_left'       => true,
    'currency_code'                      => 'USD',
    'currency_label'                     => 'USA Dollar',
    'payment_shipping_value'             => 0.2,
    'payment_tax_value'                  => 0.3,

//    $this->shipping_value  = 0.2;
//$this->tax_value       = 0.3;

'reports_demo_period_start' => '2018-08-01',
    'reports_demo_period_end'   => '2019-11-22',

    'all_emails_copy'                      => 'nilov@softreactor.com',

    'cronTasksReceivers'          => [
        [
            'name'   => 'nilov',
            'email'  => 'nilov@softreactor.com',
//            'mobile' => '380959180286',
            'mobile' => '380959180286',
        ],

        [
            'name'   => 'Nilov Sergey',
            'email'  => 'nilovsergey@yahoo.com',
            'mobile' => '',
        ],

    ],
    'chartBackgroundColors'=> ["#005500", "#3e95cd", "#8e5ea2", "#ff063c", "#3cba9f", "#e8c3b9", "#ffff00", "#0000ff", "#fb6bff", "#55ffff", "#c6c7ff", "#aaff7f", "#a5aaff", "#fffda6", "#707070", "#c45850", "#dfdf00" ],

    //    private static $bannerViewTypeLabelValueArray = Array(1 => 'Blue dark', 2 => 'Blue light', 3 => 'Blue medium' ); // blue_dark_1.png  blue_light_1.png  blue_medium_1.png

    'bannersBackgroundImage'=> [
        [
            'view_type'=> 1,
            'background_image'    => 'banners/blue_dark_1.png',
            'text_color'          => '#b8b8b8', // white
            'shadow_text_color'   => '#cecece' //
        ],
        [
            'view_type'=> 2,
            'background_image' => 'banners/blue_light_1.png',
            'text_color'       => '#151515', // dark
            'shadow_text_color'   => '#000000' //
        ],
        [
            'view_type'=> 3,
            'background_image'    => 'banners/blue_medium_1.png',
            'text_color'          => '#ffff00', // dark
            'shadow_text_color'   => '#d3d300' //
//            'text_color'       => '#f50d43', // dark
//            'shadow_text_color'   => '#d90b3b' //
        ],

    ],


    'uploadsImagesDir'     => 'uploads',
    'uploadsTmpImagesDir'  => 'uploads/tmp/',




    'appTitleIconsList'          => [      // https://fontawesome.com/icons
        'dashboard'=> 'fa fa-dashboard',
        'category'=> 'fa fa-book',
        'tag'=> 'fa fa-tag',
        'page-content'=> 'fa fa-clone',
        'vote'=> 'fa fa-file-archive-o',
        'users'=> 'fa fa-users',
        'profile'=> 'fa fa-user',
        'settings'=> 'fa fa-wrench',
        'chat'=> 'fa fa-commenting-o ',
        'banner'=> 'fa fa-bandcamp',
        'report'=> 'fa fa-snapchat-square',
        'external-link'=> 'fa fa-external-link',
        'contact-us'=> 'fa fa-bell-o',
        'subscription'=> 'fa fa-envelope-open-o',
        'logout'=> 'fa fa-sign-out',
        'todo'=> 'fa fa-th-list',    // mapping
        'mapping'=> 'fa fa-map-signs',
        'contacts'=> 'fa fa-book',
        'content'=> 'fa fa-align-justify',
        'bug'=> 'fa fa-bug',
        'payment'=> "fa fa-credit-card",
        'paypal'=> "fa fa-cc-paypal",
        'stripe'=> "fa fa-cc-stripe",
        'toggle_on'=> "fa fa-arrows-alt",
        'toggle_off'=> "fa fa-arrow-up",
//                    <i class="fa fa-arrows-alt"></i>
//            <i class="fa fa-arrow-up"></i>
        'activate'=> "fa fa-toggle-on",
        'deactivate'=> "fa fa-toggle-off",
        'check'=> "fa fa-check",

        'details'   => "fa fa-info-circle",
        'info'      => "fa fa-info",
        'load'      => "fa fa-upload",
        'delete'    => 'fa fa-trash',
        'calendar'  => 'fa fa-calendar',
        'event'     => 'fa fa-calendar',
        'picker'     => 'fa fa-crosshairs',
        'color'     => 'fa fa-address-book',

    ],

    'pdfGenerationOptions'  => [
        'title_font_size' => 24,
        'subtitle_font_size' => 20,
        'content_font_size'  => 16,
        'notes_font_size'    => 14,

        'background_color'   => '#fffbc7',// '#ffec5c',
        'title_font_color'   => '#08a0ff', //'#ff7b3e',
        'subtitle_font_color'=> '#08a0ff', // '#0e76ff',
        'content_font_color' => '#545150', //'#073d84',
        'notes_font_color'   => '#dc0622',

        'outputFileFormatsList'=> [
            'pdf'            => 'Pdf file',
            'png'            => 'Png image',
            'jpg'            => 'Jpg image',
        ],
        'fontsList'          => [ //https://tcpdf.org/docs/fonts/
            'themify'            => 'themify',
            'DejaVu Sans'        => 'DejaVu Sans',
            'Simple line icons'  => 'Simple line icons',
            'Open sans'          => 'Open sans',
            'Source sans pro'    => 'Source sans pro',
            'roboto'             => 'Roboto',

            'arial'              => 'Arial',
            'courier'            => 'Courier',
//            'verdana'            => 'Verdana',  // Is not supported !

//            'impact'             => 'Impact',     // Is not supported !
//            'georgia'            => 'Georgia',       // Is not supported !
            'helvetica'          => 'helvetica',
            'symbol'         => 'Symbol',
            'times'              => 'Times New Roman',
            'zapfdingbats'   => 'Zapf Dingbats',
//            'Trebuchet MS'   => 'Trebuchet MS',
        ],
        'fontSizeItems'          => [
            '6'        => '6',
            '7'        => '7',
            '8'        => '8',
            '9'        => '9',
            '10'       => '10',
            '10.5'     => '10.5',
            '11'       => '11',
            '12'        => '12',
            '13'        => '13',
            '14'        => '14',
            '15'        => '15',
            '16'        => '16',
            '18'        => '18',
            '20'        => '20',
            '22'        => '22',
            '24'        => '24',
            '26'        => '26',
            '32'        => '32',
            '36'        => '36',
            '40'        => '40',
            '44'        => '44',
            '48'        => '48',
            '54'        => '54',
            '60'        => '60',
            '66'        => '66',
            '72'        => '72',
            '80'        => '80',
            '88'        => '88',
            '96'        => '96'
        ],
    ],



    'elasticsearch_url'             => 'http://localhost:9200',
    'elasticsearch_root_index'      => 'select_vote',
    'elasticsearch_allow_fuzziness' => 'AUTO', // leave empty if we do not need fuzziness option

    'spatie_tag_locale' => 'en',
    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        Yajra\Datatables\DatatablesServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        Yajra\DataTables\DataTablesServiceProvider::class,
        Proengsoft\JsValidation\JsValidationServiceProvider::class,


//        'Intervention\Image\ImageServiceProvider',
//        Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider::class,
        Clockwork\Support\Laravel\ClockworkServiceProvider::class,
        Jrean\UserVerification\UserVerificationServiceProvider::class,
        Mews\Captcha\CaptchaServiceProvider::class,
        App\Providers\ComposerServiceProvider::class,
        'Aloha\Twilio\Support\Laravel\ServiceProvider',
        Intervention\Image\ImageServiceProvider::class,
        Arrilot\Widgets\ServiceProvider::class,
//        Dawson\Youtube\YoutubeServiceProvider::class,
//        Laravel\Socialite\SocialiteServiceProvider::class,

//        \SocialiteProviders\Manager\ServiceProvider::class,
        'App\Providers\YouTubeServiceProvider',
        Elasticquent\ElasticquentServiceProvider::class,
//        \App\Providers\InstagramSerivceProvider::class,
        willvincent\Feeds\FeedsServiceProvider::class,
        Alaouy\Youtube\YoutubeServiceProvider::class,
        Sichikawa\LaravelSendgridDriver\SendgridTransportServiceProvider::class,

        Spatie\Newsletter\NewsletterServiceProvider::class,
//        MaddHatter\LaravelFullcalendar\ServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App'          => Illuminate\Support\Facades\App::class,
        'Artisan'      => Illuminate\Support\Facades\Artisan::class,
        'Auth'         => Illuminate\Support\Facades\Auth::class,
        'Blade'        => Illuminate\Support\Facades\Blade::class,
        'Broadcast'    => Illuminate\Support\Facades\Broadcast::class,
        'Bus'          => Illuminate\Support\Facades\Bus::class,
        'Cache'        => Illuminate\Support\Facades\Cache::class,
        'Config'       => Illuminate\Support\Facades\Config::class,
        'Cookie'       => Illuminate\Support\Facades\Cookie::class,
        'Crypt'        => Illuminate\Support\Facades\Crypt::class,
        'DB'           => Illuminate\Support\Facades\DB::class,
        'Eloquent'     => Illuminate\Database\Eloquent\Model::class,
        'Event'        => Illuminate\Support\Facades\Event::class,
        'File'         => Illuminate\Support\Facades\File::class,
        'Gate'         => Illuminate\Support\Facades\Gate::class,
        'Hash'         => Illuminate\Support\Facades\Hash::class,
        'Lang'         => Illuminate\Support\Facades\Lang::class,
        'Log'          => Illuminate\Support\Facades\Log::class,
        'Mail'         => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password'     => Illuminate\Support\Facades\Password::class,
        'Queue'        => Illuminate\Support\Facades\Queue::class,
        'Redirect'     => Illuminate\Support\Facades\Redirect::class,
        'Redis'        => Illuminate\Support\Facades\Redis::class,
        'Request'      => Illuminate\Support\Facades\Request::class,
        'Response'     => Illuminate\Support\Facades\Response::class,
        'Route'        => Illuminate\Support\Facades\Route::class,
        'Schema'       => Illuminate\Support\Facades\Schema::class,
        'Session'      => Illuminate\Support\Facades\Session::class,
        'Storage'      => Illuminate\Support\Facades\Storage::class,
        'URL'          => Illuminate\Support\Facades\URL::class,
        'Validator'    => Illuminate\Support\Facades\Validator::class,
        'View'         => Illuminate\Support\Facades\View::class,

        'DataTables'  => Yajra\DataTables\Facades\DataTables::class,
        'JsValidator' => Proengsoft\JsValidation\Facades\JsValidatorFacade::class,

//        'Image'            => 'Intervention\Image\Facades\Image',
        'Clockwork'        => Clockwork\Support\Laravel\Facade::class,
        'UserVerification' => Jrean\UserVerification\Facades\UserVerification::class,
        'Captcha'          => Mews\Captcha\Facades\Captcha::class,
        'Twilio' => 'Aloha\Twilio\Support\Laravel\Facade',
        'Image' => Intervention\Image\Facades\Image::class,
        'Widget'       => Arrilot\Widgets\Facade::class,
        'AsyncWidget'  => Arrilot\Widgets\AsyncFacade::class,
        'Socialite'    => Laravel\Socialite\Facades\Socialite::class,
//        'Youtube' => Dawson\Youtube\Facades\Youtube::class,
        'Es' => Elasticquent\ElasticquentElasticsearchFacade::class,
        'InstagramApi' => App\Facades\Instagram::class,
        'Feeds'    => willvincent\Feeds\Facades\FeedsFacade::class,
        'Youtube' => Alaouy\Youtube\Facades\Youtube::class,

        'Newsletter' =>     Spatie\Newsletter\NewsletterFacade::class,
//        'Calendar' => MaddHatter\LaravelFullcalendar\Facades\Calendar::class,


    ],

];
