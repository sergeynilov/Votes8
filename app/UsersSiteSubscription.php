<?php

namespace App;
use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;

class UsersSiteSubscription extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'users_site_subscriptions';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'site_subscription_id', 'user_id', 'mailchimp_subscription_id' ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function siteSubscription()
    {
        return $this->belongsTo('App\SiteSubscription');
    }

    public function scopeGetByUserId($query, $user_id = null)
    {
        if (empty($user_id)) {
            return $query;
        }
        return $query->where(with(new UsersSiteSubscription)->getTable().'.user_id', $user_id);
    }


    public function scopeGetByActive($query, $active = null, $alias= '')
    {
        if (empty($active)) {
            return $query;
        }
        if (empty($alias)) {
            $alias= with(new SiteSubscription)->getTable();
        }
        return $query->where($alias.'.active', $active);
    }

    public function scopeGetBySiteSubscriptionId($query, $site_subscription_id = null)
    {
        if (empty($site_subscription_id)) {
            return $query;
        }
        return $query->where(with(new UsersSiteSubscription)->getTable().'.site_subscription_id', $site_subscription_id);
    }

}