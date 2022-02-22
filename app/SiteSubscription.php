<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;


class SiteSubscription extends MyAppModel
{
    use funcsTrait;

    protected $table      = 'site_subscriptions';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'name', 'active', 'vote_category_id' ];

    private static $siteSubscriptionActiveLabelValueArray = Array('1' => 'Is active', '0' => 'Is not active');

    public function voteCategory()
    {
        return $this->belongsTo('App\VoteCategory');
    }

    public function usersSiteSubscriptions()
    {
        return $this->hasMany('App\UsersSiteSubscription');
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($siteSubscription) {
            $siteSubscription->usersSiteSubscriptions()->delete();
        });

    }


    public static function getSiteSubscriptionActiveValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$siteSubscriptionActiveLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getSiteSubscriptionActiveLabel(string $active):string
    {
        if (!empty(self::$siteSubscriptionActiveLabelValueArray[$active])) {
            return self::$siteSubscriptionActiveLabelValueArray[$active];
        }
        return '';
    }


    public function scopeGetByName($query, $name= null, $partial= false)
    {
        if (empty($name)) return $query;
        return $query->where('.name', (!$partial?'=':'like'), ($partial?'%':''). $name .($partial?'%':'') );
    }

    
    public function scopeGetByActive($query, $active= null)
    {
        if ( !isset($active)  or strlen($active) == 0 ) return $query;
        return $query->where( with(new SiteSubscription)->getTable().'.active', $active );
    }

    /* check if provided name is unique for site_subscriptions.name field */
    public static function getSimilarSiteSubscriptionByName( string $name, int $id= null, bool $return_count = false )
    {
        $quoteModel = SiteSubscription::where( 'name', $name );
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

    public static function getValidationRulesArray($site_subscription_id= null) : array
    {
        $validationRulesArray = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(with(new SiteSubscription)->getTable())->ignore($site_subscription_id),
            ],
            'active'     =>   'required|in:'.with( new SiteSubscription)->getValueLabelKeys( SiteSubscription::getSiteSubscriptionActiveValueArray(false) ),
        ];
        return $validationRulesArray;
    }

    public static function getSiteSubscriptionsSelectionArray(int $filter_active=null) :array {
        $siteSubscriptions = SiteSubscription::orderBy('name','desc')->getByActive($filter_active)->get();
        $siteSubscriptionsSelectionArray= [];
        foreach( $siteSubscriptions as $nextSiteSubscription ) {
            $siteSubscriptionsSelectionArray[$nextSiteSubscription->id]= $nextSiteSubscription->name;
        }
        return $siteSubscriptionsSelectionArray;
    }

}
