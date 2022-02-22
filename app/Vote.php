<?php

namespace App;

use DB;
use App\MyAppModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Validation\Rule;
//use App\MyTag;
//use Spatie\Tags\Tag as SpatieTag;
//use Spatie\Tags\HasTags;
use App\HasTags;
use App\Taggable as MyTaggable;
use Elasticquent\ElasticquentTrait;

class Vote extends MyAppModel
{
    use ElasticquentTrait;
    use funcsTrait;
    use Sluggable;
    use HasTags;

    protected $table = 'votes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $voteImagePropsArray = [];
    protected $img_filename_max_length = 255;

    protected $casts = [
        'meta_keywords' => 'array'
    ];

    protected static $uploads_votes_dir = 'votes/-vote-';

    protected $fillable = ['name', 'slug', 'description', 'creator_id', 'vote_category_id', 'is_quiz', 'status', 'image'];
    protected static $logAttributes = ['*'];

    private static $voteIsQuizLabelValueArray = Array(1 => 'Is Quiz', 0 => 'Is Not Quiz');
    private static $voteStatusLabelValueArray = Array('A' => 'Active', 'I' => 'Inactive', 'N' => 'New (Draft)');
    private static $voteIsHomepageLabelValueArray = Array(1 => 'Is Homepage', 0 => 'Is Not Homepage');


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
//    protected $mapping = [ "include_type_name" => true];
    protected $mappingProperties = array(
        'name' => [
            'type' => 'string',
            'analyzer' => 'standard'
        ],

        'description' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],

/*        'tags' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],*/
    );

    public static function makeElasticSearch($str)
    {
        echo '<pre>makeElasticSearch $str::'.print_r($str,true).'</pre>';
            return Vote::searchByQuery([
                'match' => ['name'=> $str]

            ]);
        }

    public static function setVotesElasticMapping()
    {
//        return;

//        if (   Vote::mappingExists() ) {
//        Vote::deleteMapping();
//        }
        $ret= Vote::createIndex(/*$shards = null, $replicas = null*/);
        echo '<pre>-1 $ret::'.print_r($ret,true).'</pre>';

        $ret= Vote::putMapping(/*$ignoreConflicts = true*/);
        echo '<pre>-2 $ret::'.print_r($ret,true).'</pre>';

        $ret= Vote::addAllToIndex();
        echo '<pre>-3 $ret::'.print_r($ret,true).'</pre>';
        return $ret;

//        $elasticaClient = new \Elasticsearch\Client();
        $elasticaClient = \Elasticsearch\ClientBuilder::create()->build();


//        echo '<pre>$elasticaClient::'.print_r($elasticaClient,true).'</pre>';
        /* $elasticaClient::Elasticsearch\Client Object
(
    [transport] => Elasticsearch\Transport Object
        elasticsearch_type
        */

        //// WORKING CODE ///
        /*        $name= 'Default Value';
                $age= 45;
                $params = array();
                $params['body']  = array(
                    'name' => $name, 											//preparing structred data
                    'age' =>$age
                );
                $params['index'] = 'select_vote';
                $params['type']  = 'vote';
                $result = $elasticaClient->index($params);							//using Index() function to inject the data
                var_dump($result);

                die("-1 XXZ");*/
        //// WORKING CODE END ///
        $elasticaIndex = $elasticaClient->getIndex('select_vote');
        echo '<pre>$elasticaIndex::'.print_r($elasticaIndex,true).'</pre>';

        $elasticaType = $elasticaIndex->getType('vote');
        echo '<pre>$elasticaType::'.print_r($elasticaType,true).'</pre>';

// Define mapping
//        $mapping = new \Elastica\Type\Mapping();
//        $mapping->setType($elasticaType);

// Set mapping
        $mapping->setProperties(array(
            'id'      => array('type' => 'integer', 'include_in_all' => FALSE),
//            'user'    => array(
//                'type' => 'object',
//                'properties' => array(
//                    'name'      => array('type' => 'string', 'include_in_all' => TRUE),
//                    'fullName'  => array('type' => 'string', 'include_in_all' => TRUE, 'boost' => 2)
//                ),
//            ),
            'name'             => array('type' => 'string', 'include_in_all' => TRUE),
            'slug'             => array('type' => 'string', 'include_in_all' => TRUE),
            'description'      => array('type' => 'string', 'include_in_all' => FALSE),
            'created_at'       => array('type' => 'date', 'include_in_all' => FALSE),
        ));
        /*                 $elastic->index([
                            'index' => $elasticsearch_root_index,
                            'type'  => $elasticsearch_type,
                            'id'    => $nextVote->id,
                            'body'  => [
                                'id'          => $nextVote->id,
                                    'slug'        => $nextVote->slug,
                                    'name'        => $nextVote->name,
                                    'description' => $nextVote->description,
                                    'created_at'  => $nextVote->created_at,
                                'vote_items'  => $relatedVoteItemsList,
                                'category_id' => $voteCategory->id,
                                'category'    => [
                                    'name'         => $voteCategory->name,
                                    'slug'         => $voteCategory->slug,
                                    'created_at'   => $voteCategory->created_at,
                                ],
                            ]
                        ]);
         */
// Send mapping to type
        $mapping->send();
        /*$elasticaType = $elasticaIndex->getType('tweet');

// Define mapping
$mapping = new \Elastica\Type\Mapping();
$mapping->setType($elasticaType);

// Set mapping
$mapping->setProperties(array(
    'id'      => array('type' => 'integer', 'include_in_all' => FALSE),
    'user'    => array(
        'type' => 'object',
        'properties' => array(
            'name'      => array('type' => 'string', 'include_in_all' => TRUE),
            'fullName'  => array('type' => 'string', 'include_in_all' => TRUE, 'boost' => 2)
        ),
    ),
    'msg'     => array('type' => 'string', 'include_in_all' => TRUE),
    'tstamp'  => array('type' => 'date', 'include_in_all' => FALSE),
    'location'=> array('type' => 'geo_point', 'include_in_all' => FALSE)
));

// Send mapping to type
$mapping->send();*/
    } // public static function setVotesElasticMapping()

//    function getTypeName()
//    {
//        return 'votes_type_name';
//    }

    public function scopeGetByVote($query, $vote_id= null)
    {
        if (!empty($vote_id)) {
            if ( is_array($vote_id) ) {
                $query->whereIn( with(new VoteItem)->getTable().'.vote_id', $vote_id );
            } else {
                $query->where( with(new VoteItem)->getTable() . '.vote_id', $vote_id );
            }
        }
        return $query;
    }


    public function getImgFilenameMaxLength(): int
    {
        return $this->img_filename_max_length;
    }

    public function voteCategory()
    {
        return $this->belongsTo('App\VoteCategory');
    }

    public function voteItems()
    {
        return $this->hasMany('App\VoteItem');
    }

    public function quizQualityResults()
    {
        return $this->hasMany('App\QuizQualityResult');
    }


    protected static function boot() {
        parent::boot();
        static::deleting(function($vote) {
            foreach ( $vote->quizQualityResults()->get() as $nextQuizQualityResults ) {
                $nextQuizQualityResults->delete();
            }
            foreach ( $vote->voteItems()->get() as $nextVoteItem ) {
                $nextVoteItem->delete();
            }

            foreach ( MyTaggable::getByTaggableType('App\Vote')->getByTaggableId($vote->id)->get() as $nextVoteItem ) {
                $nextVoteItem->delete();
            }

            $vote_image_path= Vote::getVoteImagePath($vote->id, $vote->image, true);
            Vote::deleteFileByPath($vote_image_path, true);

        });


        static::saved(function ($vote) {
//            $elastic_automation  = config('app.elastic_automation');
//            $elastic_automation = \Config::get('app.elastic_automation';
            return;

            $elastic_automation= Settings::getValue('elastic_automation');

            if ( $elastic_automation !='Y' ) return;


            $elastic = app(\App\Elastic\Elastic::class);
            $elasticsearch_root_index  = config('app.elasticsearch_root_index');
            $elasticsearch_type        = with(new Vote)->getElasticsearchType();

            $elastic->delete([
                'index' => $elasticsearch_root_index,
                'type'  => $elasticsearch_type,
                'id'    => $vote->id,
            ]);

            if ($vote->status == 'A') { // only active votes must be saved in elasticsearch

                $voteCategory= $vote->voteCategory;
                if (!empty($voteCategory)) {     // only votes with valid category must be saved in elasticsearch
                    if ( $voteCategory->active) { // only votes with active category must be saved in elasticsearch

                        $voteItems = VoteItem
                            ::getByVote($vote->id)
                            ->orderBy('ordering', 'asc')
                            ->get();
                        $relatedVoteItemsList= [];
                        foreach ($voteItems as $nextVoteItem) {
//                            $relatedVoteItemsList[]= $nextVoteItem->name;
                            $relatedVoteItemsList[]= [ 'vote_item_name' => $nextVoteItem->name ];
                        }

                        $elastic->index([
                            'index' => $elasticsearch_root_index,
                            'type'  => $elasticsearch_type,
                            'id'    => $vote->id,
                            'body'  => [
                                'id'          => $vote->id,
                                'slug'        => $vote->slug,
                                'name'        => $vote->name,
                                'description' => $vote->description,
                                'created_at'  => $vote->created_at,
                                'vote_items'  => $relatedVoteItemsList,
                                'category_id' => $voteCategory->id,
                                'category'    => [
                                    'name'       => $voteCategory->name,
                                    'slug'       => $voteCategory->slug,
                                    'created_at' => $voteCategory->created_at,
                                ],
                            ]
                        ]);
                    } // if ( $voteCategory->active) { // only votes with active category must be saved in elasticsearch
                } // if (empty($voteCategory)) {     // only votes with valid category must be saved in elasticsearch
            } // if ($vote->status == 'A') { // only active votes must be saved in elasticsearch

        });
        static::deleted(function ($vote ) {
            return;

            $elastic_automation= Settings::getValue('elastic_automation');
            if ( $elastic_automation != 'Y' ) return;
            $elastic = app(\App\Elastic\Elastic::class);
            $elasticsearch_root_index  = config('app.elasticsearch_root_index');
            $elasticsearch_type        = with(new Vote)->getElasticsearchType();
            $elastic->delete([
                'index' => $elasticsearch_root_index,
                'type' => $elasticsearch_type,
                'id' => $vote->id,
            ]);
        });
    }


    public function scopeGetByVoteCategory($query, $vote_id = null)
    {
        if (empty($vote_id)) {
            return $query;
        }
        return $query->where(with(new Vote)->getTable().'.vote_category_id', $vote_id);
    }

    public function scopeGetByStatus($query, $status = null)
    {
        if (empty($status)) {
            return $query;
        }
        return $query->where(with(new Vote)->getTable().'.status', $status);
    }

    public function scopeGetByIsQuiz($query, $is_quiz = null)
    {
        if ( !isset($is_quiz) ) {
            return $query;
        }
        return $query->where(with(new Vote)->getTable().'.is_quiz', $is_quiz);
    }

    public function scopeGetByIsHomepage($query, $is_homepage = null)
    {
        if ( ! isset($is_homepage) ) {
            return $query;
        }
        return $query->where(with(new Vote)->getTable().'.is_homepage', $is_homepage);
    }

    public function scopeGetByName($query, $name = null, $partial = false)
    {
        if (empty($name)) {
            return $query;
        }
        return $query->where(with(new Vote)->getTable().'.name', (! $partial ? '=' : 'like'), ($partial ? '%' : '') . $name . ($partial ? '%' : ''));
    }

    public function scopeGetBySlug($query, $slug = null)
    {
        if (empty($slug)) {
            return $query;
        }
        return $query->where(with(new Vote)->getTable().'.slug', $slug);
    }


    public static function getVoteIsQuizValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$voteIsQuizLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }

        return $resArray;
    }

    public static function getVoteIsQuizLabel(string $is_quiz): string
    {
        if ( ! empty(self::$voteIsQuizLabelValueArray[$is_quiz])) {
            return self::$voteIsQuizLabelValueArray[$is_quiz];
        }

        return self::$voteIsQuizLabelValueArray[0];
    }


    public static function getVoteStatusValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$voteStatusLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }

        return $resArray;
    }

    public static function getVoteStatusLabel(string $status): string
    {
        if ( ! empty(self::$voteStatusLabelValueArray[$status])) {
            return self::$voteStatusLabelValueArray[$status];
        }

        return '';
    }


    public static function getVoteIsHomepageValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$voteIsHomepageLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }

        return $resArray;
    }

    public static function getVoteIsHomepageLabel(string $is_homepage): string
    {
        if ( ! empty(self::$voteIsHomepageLabelValueArray[$is_homepage])) {
            return self::$voteIsHomepageLabelValueArray[$is_homepage];
        }

        return self::$voteIsHomepageLabelValueArray[0];
    }

    public static function getVoteDir(int $vote_id): string
    {
        return self::$uploads_votes_dir . $vote_id . '/';
    }

    public static function getVoteImagePath(int $vote_id, $image): string
    {
        if (empty($image)) {
            return '';
        }
        return self::$uploads_votes_dir . $vote_id . '/' . $image;
    }


    public static function setVoteImageProps(int $page_content_id, string $image = null, bool $skip_non_existing_file = false): array
    {
        if (empty($image) and $skip_non_existing_file) {
            return [];
        }

        $dir_path = self::$uploads_votes_dir . '' . $page_content_id . '';
        $file_full_path = $dir_path . '/' . $image;
        $file_exists    = ( !empty($image) and Storage::disk('local')->exists( 'public/'.$file_full_path) );

        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_full_path = config('app.empty_img_url');
            $image          = with(new Vote)->getFilenameBasename($file_full_path);
        }

        $image_path = $file_full_path;
        if ($file_exists) {
            $imagePropsArray = ['image' => $image, 'image_path' => $image_path, 'image_url' => Storage::url( $file_full_path ) ];
            $image_full_path = ( $image_path);
            $VoteImgProps = with(new Vote)->getCFImageProps(base_path() . '/storage/app/public/'.$image_full_path, $imagePropsArray);
        } else {
            $VoteImgProps = ['image' => $image, 'image_path' => $image_path, 'image_url' => ($file_full_path) ];
        }

        return $VoteImgProps;
    }


    public static function getValidationRulesArray($vote_id = null): array
    {
        $validationRulesArray = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(with(new Vote)->getTable())->ignore($vote_id),
            ],

            'description'      => 'required',
            'vote_category_id' => 'required|integer|exists:' . (with(new VoteCategory)->getTable()) . ',id',

            'is_quiz'     => 'required|in:' . with(new Vote)->getValueLabelKeys(Vote::getVoteIsQuizValueArray(false)),
            'is_homepage' => 'required|in:' . with(new Vote)->getValueLabelKeys(Vote::getVoteIsHomepageValueArray(false)),
            'status'      => 'required|in:' . with(new Vote)->getValueLabelKeys(Vote::getVoteStatusValueArray(false)),

            'ordering' => 'required|integer',
        ];

        return $validationRulesArray;
    }

    /* get additional properties of cms_item image : path, url, size etc... */
    public function getVoteImagePropsAttribute(): array
    {
        return $this->voteImagePropsArray;
    }

    /* set additional properties of cms_item image : path, url, size etc... */
    public function setVoteImagePropsAttribute(array $voteImagePropsArray)
    {
        $this->voteImagePropsArray = $voteImagePropsArray;
    }


    public static function getVotesSelectionArray(string $filter_status=null) :array {
        $votes = Vote::orderBy('name','desc')->getByStatus($filter_status)->get();
        $votesSelectionArray= [];
        foreach( $votes as $nextVote ) {
            $votesSelectionArray[$nextVote->id]= $nextVote->name;
        }
        return $votesSelectionArray;
    }

    /*     public function delete(array $parameters)
 */
    public static function clearVotesInElastic()
    {
        return;
        $elastic = app(\App\Elastic\Elastic::class);

        $elasticsearch_root_index = config('app.elasticsearch_root_index');
        $elasticsearch_type       = with(new Vote)->getElasticsearchType();

        $parameters= [
            'index' => $elasticsearch_root_index,
            'type'  => $elasticsearch_type,
        ];
        echo '<pre>$parameters::'.print_r($parameters,true).'</pre>';
//        die("-1 XXZ");
        $elastic->delete( $parameters); // TODO

    }



    public static function bulkVotesToElastic()
    {
        $elastic = app(\App\Elastic\Elastic::class);

        $elasticsearch_root_index  = config('app.elasticsearch_root_index');
        $elasticsearch_type        = with(new Vote)->getElasticsearchType();
//
        echo '<pre>$elasticsearch_root_index::'.print_r($elasticsearch_root_index,true).'</pre>';
        echo '<pre>$elasticsearch_type::'.print_r($elasticsearch_type,true).'</pre>';
        die("-1 XXZ");
//        $elastic->delete([
//            'index' => $elasticsearch_root_index,
//            'type' => $elasticsearch_type,
//            'id' => $vote->id,
//        ]);

        Vote::chunk(100, function ($Votes) use ($elastic, $elasticsearch_root_index, $elasticsearch_type) {
            foreach ($Votes as $nextVote) {

                if ($nextVote->status!= 'A') continue;   // only active votes must be saved in elasticsearch


                $voteCategory= $nextVote->voteCategory;
                if (empty($voteCategory)) continue;     // only votes with valid category must be saved in elasticsearch
                if ( !$voteCategory->active ) continue; // only votes with active category must be saved in elasticsearch

                $voteItems = VoteItem
                    ::getByVote($nextVote->id)
                    ->orderBy('ordering', 'asc')
                    ->get();
                $relatedVoteItemsList= [];
                foreach ($voteItems as $nextVoteItem) {
//                    $relatedVoteItemsList[]= $nextVoteItem->name;
//                    $relatedVoteItemsList[]= [ 'vote_items' => $nextVoteItem->name ];
                    $relatedVoteItemsList[]= [ 'vote_item_name' => $nextVoteItem->name ];
                }


                $elastic->index([
                    'index' => $elasticsearch_root_index,
                    'type'  => $elasticsearch_type,
                    'id'    => $nextVote->id,
                    'body'  => [
                        'id'          => $nextVote->id,
                        'slug'        => $nextVote->slug,
                        'name'        => $nextVote->name,
                        'description' => $nextVote->description,
                        'created_at'  => $nextVote->created_at,
                        'vote_items'  => $relatedVoteItemsList,
                        'category_id' => $voteCategory->id,
                        'category'    => [
                            'name'         => $voteCategory->name,
                            'slug'         => $voteCategory->slug,
                            'created_at'   => $voteCategory->created_at,
                        ],
                    ]
                ]);
            }
        });
    }

    public function downloads()
    {
        return $this->morphToMany(Download::class, 'downloadable');
    }


}
