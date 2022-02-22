<?php

namespace App;
use DB;
use App\MyAppModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;

class PaymentItem extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'payment_items';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'payment_id', 'item_type', 'quantity', 'item_id' , 'price' ];
/*         Schema::create('payment_items', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('payment_id')->unsigned();

            $table->enum('item_type', ['D', 'P'])->default("D")->comment( ' D => Download, P=>Product' );
            $table->smallInteger('quantity')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->decimal('price', 9, 2);

            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at', 'item_type'], 'payment_items_created_at_item_type_index');
            $table->index(['payment_id', 'item_type', 'item_id'], 'payment_items_payment_id_item_type_index');

        });
 */
    private static $paymentItemItemTypeLabelValueArray = Array('D' => 'Download', 'P' => 'Product');

    protected $casts = [
    ];


    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }

    public function scopeGetByPayment($query, $payment_id= null)
    {
        if (!empty($payment_id)) {
            if ( is_array($payment_id) ) {
                $query->whereIn(with(new PaymentItem)->getTable().'.payment_id', $payment_id);
            } else {
                $query->where(with(new PaymentItem)->getTable().'.payment_id', $payment_id);
            }
        }
        return $query;
    }

//    public function paymentItemUsersResults()
//    {
//        return $this->hasMany('App\PaymentItemUsersResult');
//    }


    public static function getPaymentItemMessageTypeValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$paymentItemItemTypeLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getPaymentItemMessageTypeLabel(string $item_type):string
    {
        if (!empty(self::$paymentItemItemTypeLabelValueArray[$item_type])) {
            return self::$paymentItemItemTypeLabelValueArray[$item_type];
        }
        return self::$paymentItemItemTypeLabelValueArray[0];
    }


    public function scopeGetByCreatedAt($query, $filter_created_at_from= null, string $sign= null)
    {
        if (!empty($filter_created_at_from)) {
            if (!empty($sign)) {
                $query->whereRaw( DB::getTablePrefix().with(new PaymentItem)->getTable().'.created_at ' . $sign . "'".$filter_created_at_from."' " );
            } else {
                $query->where(with(new PaymentItem)->getTable().'.created_at', $filter_created_at_from);
            }
        }
        return $query;
    }

    public function scopeGetByItemId($query, $item_id = null, $alias= '')
    {
        if (empty($item_id)) {
            return $query;
        }
        if (empty($alias)) {
            $alias= with(new PaymentItem)->getTable();
        }
        return $query->where( $alias.'.item_id', $item_id );
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
//            ->getByUserId($filterSelectedUsers, 'payments')
    public function scopeGetByUserId($query, $filter_user_id= null, $alias= '')
    {
        if (empty($alias)) {
            $alias= with(new Payment)->getTable();
        }
        if (!empty($filter_user_id)) {
            if ( is_array($filter_user_id) ) {
                $query->whereIn(with(new Payment)->getTable().'.user_id', $filter_user_id);
            } else {
                $query->where(with(new Payment)->getTable().'.user_id', $filter_user_id);
            }
        }
        return $query;
    }


    //         return PaymentItem::getValidationRulesArray( $request->get('payment_id'), $request->get('id') );
    public static function getValidationRulesArray($payment_id, $payment_item_id = null): array
    {
/*        $additional_item_value_validation_rule= 'check_payment_item_unique_by_name:'.$payment_id.','.( !empty($payment_item_id)?$payment_item_id:'');


        $validationRulesArray            = [
            'payment_id'      => 'required|exists:'.( with(new Payment)->getTable() ).',id',
//            'payment_id'             => 'required|max:255|' . $additional_item_value_validation_rule,


            'name'             => 'required|max:255|' . $additional_item_value_validation_rule,
            'is_correct'       => 'required|in:' . with(new PaymentItem)->getValueLabelKeys(PaymentItem::getPaymentItemMessageTypeValueArray(false)),
            'ordering'         => 'required|integer',
        ];
*/
//        return $validationRulesArray;
    }
}
