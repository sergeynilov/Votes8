<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
//use Illuminate\Notifications\Notifiable;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Validation\Rule;


class VoteCategory extends MyAppModel
{
    use funcsTrait;
    use Sluggable;

    protected $table      = 'vote_categories';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'name', 'slug', 'active', 'in_subscriptions', 'meta_description', 'meta_keywords' ];
    //            'meta_description'   => 'vote category meta_description on ' . now(),
    //            'meta_keywords'   => [ 'vote category meta_description on ' . now(), 'meta_keywords' ],


    private static $voteCategoryActiveLabelValueArray = Array('1' => 'Active', '0' => 'Inactive');
    private static $voteCategoryInSubscriptionsLabelValueArray = Array('1' => 'In subscriptions', '0' => 'Not in subscriptions');

    protected $casts = [
        'meta_keywords' => 'array'
    ];

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }


    public function siteSubscriptions()
    {
        return $this->hasMany('App\SiteSubscription');
    }


    protected static function boot() {
        parent::boot();
        static::deleting(function($voteCategory) {
            foreach ( $voteCategory->siteSubscriptions()->get() as $nextSiteSubscription ) {
                $nextSiteSubscription->delete();
            }

            foreach ( $voteCategory->votes()->get() as $nextVote ) {
                $nextVote->delete();
            }
        });

    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    public static function getVoteCategoryActiveValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$voteCategoryActiveLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getVoteCategoryActiveLabel(string $active):string
    {
        if (!empty(self::$voteCategoryActiveLabelValueArray[$active])) {
            return self::$voteCategoryActiveLabelValueArray[$active];
        }
        return self::$voteCategoryActiveLabelValueArray[0];
    }


    public static function getVoteCategoryInSubscriptionLabelValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$voteCategoryInSubscriptionsLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getVoteCategoryInSubscriptionsLabel(string $in_subscriptions):string
    {
        if (!empty(self::$voteCategoryInSubscriptionsLabelValueArray[$in_subscriptions])) {
            return self::$voteCategoryInSubscriptionsLabelValueArray[$in_subscriptions];
        }
        return self::$voteCategoryInSubscriptionsLabelValueArray[0];
    }


    public function scopeGetByName($query, $name= null, $partial= false)
    {
        if (empty($name)) return $query;
        return $query->where(with(new VoteCategory)->getTable().'.name', (!$partial?'=':'like'), ($partial?'%':''). $name .($partial?'%':'') );
    }

    public function scopeGetBySlug($query, $slug= null)
    {
        if (empty($slug)) return $query;
        return $query->where(with(new VoteCategory)->getTable().'.slug', $slug );
    }


    public function scopeGetByActive($query, $active= null)
    {
        if ( !isset($active) or strlen($active) == 0 ) return $query;
        return $query->where( with(new VoteCategory)->getTable().'.active', $active );
    }

    /* check if provided name is unique for vote_categories.name field */
    public static function getSimilarVoteCategoryByName( string $name, int $id= null, bool $return_count = false )
    {
        $quoteModel = VoteCategory::where( 'name', $name );
        if ( !empty( $id ) ) {
            $quoteModel = $quoteModel->where( 'id', '!=' , $id );
        }
        if ( $return_count ) {
            return $quoteModel->get()->count();
        }
        $retRow= $quoteModel->get();
        if ( empty($retRow[0]) ) return false;
        return $retRow[0];
    }

    public static function getValidationRulesArray($vote_category_id= null) : array
    {
        $validationRulesArray = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(with(new VoteCategory)->getTable())->ignore($vote_category_id),
            ],
            'meta_description'     =>   'nullable|max:255',
            'active'               =>   'required|in:'.with( new VoteCategory)->getValueLabelKeys( VoteCategory::getVoteCategoryActiveValueArray(false) ),
            'in_subscriptions'     =>   'required|in:'.with( new VoteCategory)->getValueLabelKeys( VoteCategory::getVoteCategoryInSubscriptionLabelValueArray(false) ),
        ];
        return $validationRulesArray;
    }

    public static function getVoteCategoriesSelectionArray(int $filter_active=null) :array {
        $voteCategories = VoteCategory::orderBy('name','desc')->getByActive($filter_active)->get();
        $voteCategoriesSelectionArray= [];
        foreach( $voteCategories as $nextVoteCategory ) {
            $voteCategoriesSelectionArray[$nextVoteCategory->id]= $nextVoteCategory->name;
        }
        return $voteCategoriesSelectionArray;
    }

}
