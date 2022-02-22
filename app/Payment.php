<?php

namespace App;

use App\MyAppModel;
use DB;
use Carbon\Carbon;
use Config;

use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Elasticsearch\Client;
use App\library\CheckValueType;

class Payment extends MyAppModel
{
    use funcsTrait;

    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'payments';

    protected $fillable = [
    ];

    protected $casts = [
        'meta_keywords' => 'array'
    ];

    private static $statusLabelValueArray = Array('D' => 'Draft', 'C' => 'Completed', 'A' => 'Cancelled');
    private static $paymentTypeLabelValueArray = Array('PC' => 'Paypal Checkout', 'PS' => 'Paypal Subscription', 'SC' => 'Stripe Checkout', 'SS' => 'Stripe Subscription');
    // $table->enum('payment_type', ['PC', 'PS', 'SC', 'SS'])->comment( ' PC => Paypal Checkout, PS => Paypal Subscription, SC => Stripe Checkout, SS => Stripe Subscription' );

    protected static function boot() {
        parent::boot();
        static::deleting(function($payment) {
            foreach ( $payment->paymentItems()->get() as $nextPaymentItem ) {
                $nextPaymentItem->delete();
            }
        });
    }

    public function pageContentItems()
    {
        return $this->hasMany('App\PageContentItems');
    }



    public function scopeGetByUserId($query, $user_id= null)
    {
        if (!empty($user_id)) {
            if ( is_array($user_id) ) {
                $query->whereIn( with(new Payment)->getTable().'.user_id', $user_id );
            } else {
                $query->where( with(new Payment)->getTable() . '.user_id', $user_id );
            }
        }
        return $query;
    }

    public function scopeGetByStatus($query, $status = null, $alias= '')
    {
        if (empty($status)) {
            return $query;
        }
        if (empty($alias)) {
            $alias= with(new Payment)->getTable();
        }
        return $query->where( $alias.'.status', $status );
    }
    /*     public function scopeGetByActive($query, $active = null, $alias= '')
    {
        if (empty($active)) {
            return $query;
        }
        if (empty($alias)) {
            $alias= with(new SiteSubscription)->getTable();
        }
        return $query->where($alias.'.active', $active);
    }
 */


    public static function getPaymentStatusValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$statusLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getPaymentStatusLabel(string $status): string
    {
        if ( ! empty(self::$statusLabelValueArray[$status])) {
            return self::$statusLabelValueArray[$status];
        }
        return self::$statusLabelValueArray[0];
    }


    public static function getPaymentTypeValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$paymentTypeLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getPaymentTypeLabel(string $payment_type): string
    {
        if ( ! empty(self::$paymentTypeLabelValueArray[$payment_type])) {
            return self::$paymentTypeLabelValueArray[$payment_type];
        }
        return $payment_type;
    }

    public static function getValidationRulesArray($payment_id = null): array
    {
        $validationRulesArray = [
        ];
        return $validationRulesArray;
    }
    
}