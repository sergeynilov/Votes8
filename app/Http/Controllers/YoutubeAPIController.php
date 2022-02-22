<?php

namespace App\Http\Controllers;

// app/Http/Controllers/YouTubeAPIController.php

use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Settings;
use App\Vote;
use App\User;
use App\VoteItem;
use App\QuizQualityResult;
use App\Http\Traits\funcsTrait;
//use App\Http\Traits\funcsTrait;


//class YouTubeAPIController extends MyAppController
class YoutubeAPIController extends Controller
{
    use funcsTrait;

    /* Route::any('/videos', ['middleware' => 'google_login', 'uses' => 'YoutubeAPIController@videos', 'as' => 'videos']);
    Route::get('/video/{id}', ['middleware' => 'google_login', 'uses' => 'YoutubeAPIController@video', 'as' => 'video']);
    Route::any('/search', ['middleware' => 'google_login', 'as' => 'search', 'uses' => 'YoutubeAPIController@search']);
     */
//    use funcsTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
//        Vote::bulkVotesToElastic();
//        Vote::clearVotesInElastic();
    }

    /**                                                              $this->debToFile(print_r( $this->client->getRefreshToken(),true),'  GoogleLogin  -3 $this->client->getRefreshToken()::');

     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function videos()
    {
        die("-1 XXZ app/Http/Controllers/YoutubeAPIController.php");
        echo '<pre>videos ::'.print_r(-1,true).'</pre>';
        \Debugbar::info('-1');
        $youtube = \App::make('youtube');

        \Debugbar::info('-2');
        die("-1 XXZ");
        $videos  = $youtube->videos->listVideos('snippet', ['chart' => 'mostPopular']);

        \Debugbar::info('-3');
        \Debugbar::info($videos);
//        dump($videos);



        $options = ['chart' => 'mostPopular', 'maxResults' => 16];
        if (\Input::has('page')) {
            $options['pageToken'] = \Input::get('page');
        }

        $youtube = \App::make('youtube');
        $videos = $youtube->videos->listVideos('id, snippet', $options);

        return view('videos', ['videos' => $videos]);
    }

    protected function youtube_callback()
    {
        die("-1 XXZ  youtube_callback");
    }

}
