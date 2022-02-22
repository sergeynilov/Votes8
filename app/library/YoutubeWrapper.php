<?php namespace App\library {

    use Auth;
    use DB;
    use App\User;
    use App\Settings;
    use App\Http\Traits\funcsTrait;


    class YoutubeWrapper
    {
        use funcsTrait;
        private $channel_id='UCNd4Tz3KdWmLFsPvwk4HzMg';
        private $video_id='teOx47wgln8';
             // https://www.youtube.com/channel/UCNd4Tz3KdWmLFsPvwk4HzMg
//    https://www.youtube.com/watch?v=teOx47wgln8
        //  https://github.com/alaouy/Youtube

        public function getVideosList()
        {
            // List videos in a given channel, return an array of PHP objects
            $videoList = \Youtube::listChannelVideos($this->channel_id, 40);
            echo '<pre>$videoList::'.print_r($videoList,true).'</pre>';
            return $videoList;
        } // public function archiveUserRegistrationFiles()

        public function getVideoInfo()
        {
// Return an STD PHP object
            $videoInfo = \Youtube::getVideoInfo($this->video_id);
            echo '<pre>$videoInfo::'.print_r($videoInfo,true).'</pre>';
            return $videoInfo;
        } // public function archiveUserRegistrationFiles()

    }

}