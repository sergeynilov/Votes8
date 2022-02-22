<?php

namespace App;
use App\MyAppModel;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;

class PaymentAgreement extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'payment_agreements';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'user_id', 'payment_type', 'state', 'payment_agreement_id' , 'description' , 'start_date' ];
    private static $paymentAgreementStateLabelValueArray = Array('Pending' => 'Pending');

    protected $casts = [
    ];

    public function scopeGetByUserId($query, $user_id= null)
    {
        if (!empty($user_id)) {
            if ( is_array($user_id) ) {
                $query->whereIn(with(new PaymentAgreement)->getTable().'.user_id', $user_id);
            } else {
                $query->where(with(new PaymentAgreement)->getTable().'.user_id', $user_id);
            }
        }
        return $query;
    }

    public static function getPaymentAgreementStateValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$paymentAgreementStateLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getPaymentAgreementStateLabel(string $state):string
    {
        if (!empty(self::$paymentAgreementStateLabelValueArray[$state])) {
            return self::$paymentAgreementStateLabelValueArray[$state];
        }
        return self::$paymentAgreementStateLabelValueArray[0];
    }
    
    public function scopeGetByCreatedAt($query, $filter_created_at_from= null, string $sign= null)
    {
        if (!empty($filter_created_at_from)) {
            if (!empty($sign)) {
                $query->whereRaw( DB::getTablePrefix().with(new PaymentAgreement)->getTable().'.created_at ' . $sign . "'".$filter_created_at_from."' " );
            } else {
                $query->where(with(new PaymentAgreement)->getTable().'.created_at', $filter_created_at_from);
            }
        }
        return $query;
    }

    public static function getValidationRulesArray($payment_agreement_id): array
    {
    }
    
}