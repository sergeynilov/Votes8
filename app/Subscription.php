<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;

class Subscription extends MyAppModel
{
    use funcsTrait;

    protected $table      = 'subscriptions';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'user_id', 'name', 'stripe_id', 'stripe_status', 'stripe_plan', 'quantity', 'trial_ends_at', 'ends_at', 'source_service_subscription_id' ];

    public function for_user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    
    public function scopeGetByUserId($query, $user_id = null)
    {
        if (empty($user_id)) {
            return $query;
        }
        return $query->where(with(new Subscription)->getTable() . '.user_id', $user_id);
    }

    public function scopeGetByStripeStatus($query, $stripe_status = null)
    {
        if (!isset($stripe_status) ) {
            return $query;
        }
        return $query->where(with(new Subscription)->getTable().'.stripe_status', $stripe_status);
    }

    public static function getValidationRulesArray($subscription_id = null, array $options= []): array
    {
        /*      id	bigint(20) unsigned Auto Increment
    user_id	bigint(20) unsigned
    name	varchar(255)
    stripe_id	varchar(255)
    stripe_status	varchar(255)
   stripe_plan	varchar(255)
    quantity	int(11)
    trial_ends_at	timestamp NULL
ends_at	timestamp NULL
created_at	timestamp NULL
updated_at	timestamp NULL
source_service_subscription_id */

        $validationRulesArray = [
/*            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(with(new Subscription)->getTable())->ignore($subscription_id),
            ],

            'description'  => 'required',
            'completed'    => 'required|in:' . with(new Subscription)->getValueLabelKeys(Subscription::getSubscriptionCompletedValueArray(false)),
            'user_id'   => 'required|integer|exists:' . (with(new User)->getTable()) . ',id',*/
        ];

        return $validationRulesArray;
    }

}