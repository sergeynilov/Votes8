<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Traits\funcsTrait;

class YouTubeServiceProvider extends ServiceProvider
{
    use funcsTrait;
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */

    // app/Providers/YouTubeServiceProvider.php

    public function register()
    {
//        $this->debToFile(print_r( -21,true),'  YouTubeServiceProvider  -21 ::');
        $app = $this->app;

        $this->app->bind('GoogleClient', function () {
            $googleClient = new \Google_Client();
//            $this->debToFile(print_r( -22,true),'  \Session::get("token")  -22 ::');

            
            $googleClient->setAccessToken(\Session::get("token"));

            return $googleClient;
        });

//        $this->debToFile(print_r( -23,true),'  YouTubeServiceProvider  -23 ::');
        $this->app->bind('youtube', function () use ($app) {
            $this->debToFile(print_r( -24,true),'  YouTubeServiceProvider  -24 ::');
            $googleClient = \App::make('GoogleClient');
            $youtube = new \Google_Service_YouTube($googleClient);
            return $youtube;
        });
    }



}
