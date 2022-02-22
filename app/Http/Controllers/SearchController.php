<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Settings;
use App\SearchResult;
use App\User;
use App\Vote;
use App\VoteItem;
use App\VoteCategory;

//use App\VoteItem;
//use App\QuizQualityResult;
use App\Http\Traits\funcsTrait;
use Elasticquent\ElasticquentTrait;

/*
1) What is Elasticsearch and why should I use it?

https://michaelstivala.com/learning-elasticsearch-with-laravel/
 */

// /search-results/{text}
//  http://local-votes.com/search-results/holly
class SearchController extends MyAppController
{ // https://michaelstivala.com/learning-elasticsearch-with-laravel/
    use funcsTrait;
    use ElasticquentTrait;

    public function __construct()
    {
//        Vote::bulkVotesToElastic();
//        VoteItem::bulkVoteItemsToElastic();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    //      document.location='/search-results/'+encodeURIComponent(input_search)+"/"+voteCategories+"/"+search_in_blocks

    //Route::get('/search-results/{text}/{vote_categories}/{search_in_blocks}', 'SearchController@results')->name('search-results');

    public function results($text, $vote_categories,$search_in_blocks)
    {


        /*        $elasticaClient = \Elasticsearch\ClientBuilder::create()->build();
                 $params['index'] = 'select_vote';
        $params['type']  = 'vote';

        */
        $age= 45;

        $client = \Elasticsearch\ClientBuilder::create()->build();		//connect to the client
        $params['index'] = 'select_vote';						// Preparing Indexed Data
        $params['type'] = 'vote';
        $params['body']['query']['match']['age'] = $age;			//Find data in which age matches given input
        $result = $client->search($params);					//Using Search function
        echo '<pre>$result::'.print_r($result,true).'</pre>';

        die("-1 XXZ");


        $is_debug= true;
        $text= $this->workTextString($text);
        if ( $vote_categories == '-' ) $vote_categories = '';
        if ( $is_debug ) {
            echo '<pre>$text::' . print_r($text, true) . '::</pre>';
            echo '<pre>$vote_categories::' . print_r($vote_categories, true) . '::</pre>';
            echo '<pre>$search_in_blocks::' . print_r($search_in_blocks, true) . '::</pre>';
        }
        $voteCategories = $this->pregSplit('/,/',$vote_categories);
        if ( $is_debug ) {
            echo '<pre>results $voteCategories::' . print_r($voteCategories, true) . '</pre>';
        }
//        die("-1 XXZ");
        $elastic = app(\App\Elastic\Elastic::class);
//        $elasticsearch_allow_fuzziness = config('app.elasticsearch_allow_fuzziness');
        $elasticQuery = [
            "bool" => [
                'must' => [
                    [
                        "multi_match" => [
                            "query"  => $text,
                            "type"   => "cross_fields",
                            "fields" => [
                                "name^4",
                                "description",
                                "vote_items^2"
                            ]
                        ],
                    ],
                ],


//                "nested"=> [
//                    "path"=> "vote_items",
//                    "query"=> [
//                        "bool"=> [
//                            "must"=> [
//                                "match"=> [
//                                    "vote_items.vote_item_name"=> $text
//                                ]
//                            ]
//                        ]
//                    ]
//                ]
            ]
        ];

        $elasticQuery = [
            "query"=> [
                "nested"=> [
                    "path"=> "vote_items",
                    "query"=> [
                        "bool"=> [
                            "must"=> [
                                "match"=> [
                                    "vote_items.vote_item_name"=> $text
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if ( !empty($voteCategories) and is_array($voteCategories) and count($voteCategories) > 0) {
            $elasticQuery["bool"]['filter']= [
                'terms' => [
                    'category_id' => $voteCategories
                ]
            ];
        }
        
        $elasticsearch_root_index      = config('app.elasticsearch_root_index');
        $elasticsearch_type            = with(new Vote)->getElasticsearchType();
//        if ( ! empty($elasticsearch_allow_fuzziness)) {
//            $elasticQuery['multi_match']['fuzziness'] = $elasticsearch_allow_fuzziness;
//        }

        if ( $is_debug ) {
            echo '<pre>$elasticQuery::' . print_r($elasticQuery, true) . '</pre>';
        }
//        die("-1 XXZ");
        $elasticResponse = $elastic->search([
            'index' => $elasticsearch_root_index,
            'type'  => $elasticsearch_type,
            'body'  => [
                'query' => $elasticQuery
            ]
        ]);
//        echo '<pre>$elasticResponse::' . print_r($elasticResponse, true) . '</pre>';
        $foundVotesRows = ! empty($elasticResponse['hits']['hits']) ? $elasticResponse['hits']['hits'] : [];
        foreach ($foundVotesRows as $next_key => $nextFoundVotesRow) {
//            echo '<pre>$nextFoundVotesRow::'.print_r($nextFoundVotesRow,true).'</pre>';
            $source_description                                    = $this->concatStr($foundVotesRows[$next_key]['_source']['description'], 300);
//            $source_description                                    = $foundVotesRows[$next_key]['_source']['description'];
            $foundVotesRows[$next_key]['_source']['description']   = str_ireplace($text, '<span class="found_text">' . $text . '</span>', $source_description);
            $foundVotesRows[$next_key]['_source']['name']          = str_ireplace($text, '<span class="found_text">' . $text . '</span>',
                $foundVotesRows[$next_key]['_source']['name']);
            $foundVotesRows[$next_key]['_source']['category_id'] = ! empty($foundVotesRows[$next_key]['_source']['category_id']) ? $foundVotesRows[$next_key]['_source']['category_id'] : '';
            $foundVotesRows[$next_key]['_source']['category_name'] = ! empty($foundVotesRows[$next_key]['_source']['category']['name']) ? $foundVotesRows[$next_key]['_source']['category']['name'] : '';
            $foundVotesRows[$next_key]['_source']['category_slug'] = ! empty($foundVotesRows[$next_key]['_source']['category']['slug']) ? $foundVotesRows[$next_key]['_source']['category']['slug'] : '';
            if ( ! empty($foundVotesRows[$next_key]['_source']['category'])) {
                unset($foundVotesRows[$next_key]['_source']['category']);
            }
        }
        echo '<pre>$foundVotesRows::' . print_r($foundVotesRows, true) . '</pre>';
        die("-1 XXZ");
        $viewParamsArray                   = $appParamsForJSArray = $this->getAppParameters(false, ['empty_img_url', 'site_name', 'site_heading', 'site_subheading']);
        $viewParamsArray['search_text']    = $text;
        $viewParamsArray['foundVotesRows'] = $foundVotesRows;
        $viewParamsArray['voteCategories'] = $voteCategories;
        $viewParamsArray['vote_categories'] = $vote_categories;

        $loggedUser                        = Auth::user();
        $found_results                     = count($foundVotesRows);
        $newSearchResult                   = new SearchResult();
        $newSearchResult->user_id          = (! empty($loggedUser->id) ? $loggedUser->id : null);
        $newSearchResult->text             = $text;
        $newSearchResult->found_results    = $found_results;
        $newSearchResult->save();

        return view($this->getFrontendTemplateName() . '.search.results_listing', $viewParamsArray);
    } 

    public function get_most_used_search_results()
    {
        $viewParamsArray = $appParamsForJSArray = $this->getAppParameters(false, []);

        /*         $quizQualityResultsData = QuizQualityResult
            ::getByVoteIdAndUserId($vote_id)
            ->select(
                $this->quiz_quality_results_tb.'.quiz_quality_id',
                \DB::raw( 'count(' . DB::getTablePrefix() . $this->quiz_quality_results_tb.'.quiz_quality_id) as count' )
            )
            ->groupBy('quiz_quality_id')
            ->get();
 */
        $mostUsedSearchResults = SearchResult
            ::select(DB::raw( DB::getTablePrefix() . 'search_results.text, COUNT(*) AS found_search_results_count '))
            ->orderBy('found_search_results_count', 'asc')
            ->groupBy('text')
            ->limit(20)
            ->get();
        $viewParamsArray['voteCategoriesSelectionArray'] = VoteCategory::getVoteCategoriesSelectionArray();
        $viewParamsArray['mostUsedSearchResults'] = $mostUsedSearchResults;
        $html                                     = view($this->getFrontendTemplateName() . '.search.most_used_search_results', $viewParamsArray)->render();

        return response()->json(['error_code' => 0, 'message' => '', 'html' => $html], HTTP_RESPONSE_OK);
    } // public function get_most_used_search_results()


}
