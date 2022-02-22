<?php

namespace App;

use App\MyAppModel;
use Illuminate\Notifications\Notifiable;
use DB;
use Carbon\Carbon;
use Config;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Elasticsearch\Client;
use App\library\CheckValueType;

class ServiceSubscription extends MyAppModel
{
    use Notifiable;
    use funcsTrait;

    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'service_subscriptions';

//    protected $fillable = [ 'name', 'active', 'is_premium', 'is_free', 'description','color', 'background_color', 'price', 'subscription_weight' ];

    private static $serviceSubscriptionActiveLabelValueArray = Array('1' => 'Is active', '0' => 'Is not active');
    private static $serviceSubscriptionIsPremiumLabelValueArray = Array('1' => 'Is premium', '0' => 'Is not premium');
    private static $serviceSubscriptionIsFreeLabelValueArray = Array('1' => 'Is free', '0' => 'Is not free');

    protected $fillable = [
        /*
        CREATE TABLE `vt2_service_subscriptions` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`active` TINYINT(1) NOT NULL DEFAULT '0',
	`service_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`price_period` ENUM('M','Q','Y') NOT NULL COMMENT ' M => Monthly, Q=>Quarter, A=>Yearly' COLLATE 'utf8mb4_unicode_ci',
	`price` DECIMAL(7,2) NULL DEFAULT NULL,
	`description` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
         */
        'name',
        'active',
        'service_id',
        'price_period',
        'price',
        'description',
    ];

    protected $casts = [
    ];

    private static $serviceSubscriptionPricePeriodLabelValueArray = ['D' => 'Daily', 'W' => 'Weekly', 'M' => 'Monthly', 'Y' => 'Yearly'];
//    private static $serviceSubscriptionActiveLabelValueArray = ['1' => 'Is Active', '0' => 'Is Not Active'];


    public function scopeGetByName($query, $name = null, $partial = false)
    {
        if (empty($name)) {
            return $query;
        }
        return $query->where(with(new ServiceSubscription)->getTable().'.name', (! $partial ? '=' : 'like'), ($partial ? '%' : '') . $name . ($partial ? '%' : ''));
    }


    public function scopeGetByActive($query, $active = null)
    {
        if (!isset($active) or strlen($active) == 0) {
            return $query;
        }
        return $query->where(with(new ServiceSubscription)->getTable().'.active', $active);
    }

    public function scopeGetByPricePeriod($query, $price_period = null)
    {
        if (!isset($price_period) or strlen($price_period) == 0) {
            return $query;
        }
        return $query->where(with(new ServiceSubscription)->getTable().'.price_period', $price_period);
    }




    public static function getServiceSubscriptionPricePeriodValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$serviceSubscriptionPricePeriodLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getServiceSubscriptionPricePeriodLabel(string $price_period): string
    {
        if ( ! empty(self::$serviceSubscriptionPricePeriodLabelValueArray[$price_period])) {
            return self::$serviceSubscriptionPricePeriodLabelValueArray[$price_period];
        }
        return self::$serviceSubscriptionPricePeriodLabelValueArray[0];
    }

    public static function getServiceSubscriptionActiveValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$serviceSubscriptionActiveLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getServiceSubscriptionActiveLabel(string $active): string
    {
        if ( ! empty(self::$serviceSubscriptionActiveLabelValueArray[$active])) {
            return self::$serviceSubscriptionActiveLabelValueArray[$active];
        }
        return self::$serviceSubscriptionActiveLabelValueArray[0];
    }


/*
    public static function getServiceSubscriptionActiveValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$serviceSubscriptionActiveLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getServiceSubscriptionActiveLabel(string $active):string
    {
        if (!empty(self::$serviceSubscriptionActiveLabelValueArray[$active])) {
            return self::$serviceSubscriptionActiveLabelValueArray[$active];
        }
        return '';
    }*/


    public static function getServiceSubscriptionIsPremiumValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$serviceSubscriptionIsPremiumLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getServiceSubscriptionIsPremiumLabel(string $is_premium):string
    {
        if (!empty(self::$serviceSubscriptionIsPremiumLabelValueArray[$is_premium])) {
            return self::$serviceSubscriptionIsPremiumLabelValueArray[$is_premium];
        }
        return '';
    }




    public static function getServiceSubscriptionIsFreeValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$serviceSubscriptionIsFreeLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getServiceSubscriptionIsFreeLabel(string $is_free):string
    {
        if (!empty(self::$serviceSubscriptionIsFreeLabelValueArray[$is_free])) {
            return self::$serviceSubscriptionIsFreeLabelValueArray[$is_free];
        }
        return '';
    }


    
    public static function getValidationRulesArray($service_subscription_id = null): array
    {
        /* CREATE TABLE `vt2_service_subscriptions` (
     	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    	`name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	    `active` TINYINT(1) NOT NULL DEFAULT '0',
	    `service_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
    	`price_period` ENUM('M','Q','Y') NOT NULL COMMENT ' M => Monthly, Q=>Quarter, A=>Yearly' COLLATE 'utf8mb4_unicode_ci',
	`price` DECIMAL(7,2) NULL DEFAULT NULL,
	`description` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NULL DEFAULT NULL, */
        $validationRulesArray = [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique(with(new ServiceSubscription)->getTable())->ignore($service_subscription_id),
            ],
            'active'          => 'required|in:' . with(new ServiceSubscription)->getValueLabelKeys(ServiceSubscription::getServiceSubscriptionActiveValueArray(false)),
            'price_period'    => 'required|in:' . with(new ServiceSubscription)->getValueLabelKeys(ServiceSubscription::getServiceSubscriptionPricePeriodValueArray(false)),
            'price'           => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ];
        return $validationRulesArray;
    }

    public static function getServiceSubscriptionsSelectionArray(string $filter_active=null, $label_field= 'name') :array {
        $serviceSubscriptions = ServiceSubscription::orderBy('name','desc')->getByActive($filter_active)->get();
        $serviceSubscriptionsSelectionArray= [];
        foreach( $serviceSubscriptions as $nextServiceSubscription ) {
            if ( empty($label_field) or $label_field == 'name' ) {
                $serviceSubscriptionsSelectionArray[$nextServiceSubscription->id] = $nextServiceSubscription->name;
            }
            if ( empty($label_field) or $label_field == 'service_id' ) {
                $serviceSubscriptionsSelectionArray[] = [
                    'id'=>$nextServiceSubscription->id,
                    'service_id'=>$nextServiceSubscription->service_id,
                    'name'=>$nextServiceSubscription->name,
                ];
            }
        }
        return $serviceSubscriptionsSelectionArray;
    }


}