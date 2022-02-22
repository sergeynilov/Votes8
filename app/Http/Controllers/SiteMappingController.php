<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Input;
use App;
use Url;
use App\Settings;

use App\Http\Traits\funcsTrait;
use App\Vote;
use App\Tag;
use App\MyTag;
use App\VoteCategory;
use App\PageContent;
use Carbon\Carbon;


// http://local-votes.com/sitemapping
class SiteMappingController extends MyAppController // https://gitlab.com/Laravelium/Sitemap/wikis/Dynamic-sitemap
{ // https://stackoverflow.com/questions/47648300/get-all-links-for-my-laravel-website-for-sitemap

    use funcsTrait;
    private $published_page_contents_priority = 0.2;
    private $published_page_contents_changefreq = "monthly";
    private $published_page_contents_count = 0;

    private $published_votes_priority = 0.6;
    private $published_votes_changefreq = "weekly";
    private $published_votes_count = 0;

    private $published_vote_categories_priority = 0.4;
    private $published_vote_categories_changefreq = "monthly";
    private $published_vote_categories_count = 0;

    private $published_tags_priority = 0.8;
    private $published_tags_changefreq = "weekly";
    private $published_tags_count = 0;

    private $sitemap_filename = 'sitemap';
    private $sitemaps_list_array = [];


    public function generate()
    {
        $request = request();
        $sitemap = App::make("sitemap");

        $this->sitemaps_list_array[] = [ // home page
            'slug'        => \URL::to('/home'),
            'modified_at' => Carbon::now(config('app.timezone')),
            'priority'    => 1.0,
            'changefreq'  => 'daily'
        ];

        $publishedPageContents = PageContent// all published content pages
        ::getByPageType('P')
        ->getByPublished(true)
        ->orderBy('created_at', 'desc')
        ->get();
        foreach ($publishedPageContents as $nextPublishedPageContent) {
            $modified_at                 = ! empty($nextPublishedPageContent->updated_at) ? $nextPublishedPageContent->updated_at : $nextPublishedPageContent->created_at;
            $this->sitemaps_list_array[] = [
                'slug'        => \URL::to($nextPublishedPageContent->slug),
                'modified_at' => $modified_at,
                'priority'    => $this->published_page_contents_priority,
                'changefreq'  => $this->published_page_contents_changefreq
            ];
            $this->published_page_contents_count++;
        }


        $activeVotes = Vote// all active vote pages
        ::getByStatus('A')
        ->orderBy('ordering', 'desc')
        ->get();
        foreach ($activeVotes as $nextActiveVote) {
            $modified_at                 = ! empty($nextActiveVote->updated_at) ? $nextActiveVote->updated_at : $nextActiveVote->created_at;
            $this->sitemaps_list_array[] = [
                'slug'        => \URL::to($nextActiveVote->slug),
                'modified_at' => $modified_at,
                'priority'    => $this->published_votes_priority,
                'changefreq'  => $this->published_votes_changefreq
            ];
            $this->published_votes_count++;
        }


        $Tags = MyTag// all tag pages
        ::orderBy('order_column', 'desc')
        ->get();
        foreach ($Tags as $nextTag) {
            $modified_at = ! empty($nextTag->updated_at) ? $nextTag->updated_at : $nextTag->created_at;

            $this->sitemaps_list_array[] = [
                'slug'        => \URL::to('tag/' . $this->getSpatieTagLocaledValue($nextTag->slug)),
                'modified_at' => $modified_at,
                'priority'    => $this->published_tags_priority,
                'changefreq'  => $this->published_tags_changefreq
            ];
            $this->published_tags_count++;
        }

        $activeVoteCategories = VoteCategory// all active vote categories pages
        ::getByActive(true)
        ->orderBy('created_at', 'desc')
        ->get();
        foreach ($activeVoteCategories as $nextActiveVoteCategory) {
            $modified_at                 = ! empty($nextActiveVoteCategory->updated_at) ? $nextActiveVoteCategory->updated_at : $nextActiveVoteCategory->created_at;
            $this->sitemaps_list_array[] = [
                'slug'        => \URL::to($nextActiveVoteCategory->slug),
                'modified_at' => $modified_at,
                'priority'    => $this->published_vote_categories_priority,
                'changefreq'  => $this->published_vote_categories_changefreq
            ];
            $this->published_vote_categories_count++;
        }


        uasort($this->sitemaps_list_array, array($this, 'cmpSiteMapsListArrayData'));

        foreach ($this->sitemaps_list_array as $nextSitemap) { // show all pages for sitemap ordering by priority
            $sitemap->add($nextSitemap['slug'], $nextSitemap['modified_at'], $nextSitemap['priority'], $nextSitemap['changefreq']);
        }

        $sitemap->store('xml', $this->sitemap_filename);
        $msg = $this->sitemap_filename . ' was successfully generated : 1 home page, ' . $this->published_page_contents_count . ' published page contents, ' .
               $this->published_votes_count . ' votes, ' .
               $this->published_vote_categories_count . ' vote categories, ' .
               $this->published_tags_count . ' tags.';


        $settings = Settings::getByName('last_sitemapping_results')->first();
        if ($settings === null) {
            $settings       = new Settings();
            $settings->name = 'last_sitemapping_results';
        }
        $settings->value      = $msg;
        $settings->updated_at = Carbon::now(config('app.timezone'));
        $settings->save();

//        echo '<pre>$msg::'.print_r($msg,true).'</pre>';

        if($request->ajax()){
            return response()->json( ['error_code' => 0, 'message' => '', 'msg' => $msg ], HTTP_RESPONSE_OK );
        }
        return redirect()->route('home-msg', [])->with(['text' => $msg, 'type' => 'success', 'action' => '']);

    } // public function generate()

    public function cmpSiteMapsListArrayData($a, $b)
    {
        if ($a['priority'] == $b['priority']) {
            if ($a['modified_at'] == $b['modified_at']) { // if same priority then modified_at must be first
                return 0;
            }

            return ($a['modified_at']) < $b['modified_at'] ? 1 : -1;
        }

        return ($a['priority']) < $b['priority'] ? 1 : -1;
    }


}