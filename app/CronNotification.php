<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;


class CronNotification extends MyAppModel
{
    use funcsTrait;

    protected $table      = 'cron_notifications';
    protected $primaryKey = 'id';
    public $timestamps    = false;
    const NEW_CONTACT_US  = 'new_contact_us';
    const NEW_USER        = 'new_user';

    protected $fillable = [	'cron_type', 'cron_object_id'];

    public function scopeGetByCronType($query, $cron_type = null)
    {
        if (empty($cron_type)) {
            return $query;
        }
        return $query->where(with(new CronNotification)->getTable() . '.cron_type', $cron_type);
    }


    public function scopeGetByCronTypeAndCronObjectId($query, $cron_type= null, $cron_object_id= null)
    {
        if (!empty($cron_type)) {
            $query->where( 'cron_type', $cron_type);
        }
        if (!empty($cron_object_id)) {
            $query->where('cron_object_id', $cron_object_id);
        }
        return $query;
    }




    public static function getValidationRulesArray(): array
    {
        $validationRulesArray = [
            'cron_type'       => 'required|string|max:50',
            'cron_object_id'  => 'required|integer',
        ];

        return $validationRulesArray;
    }

}
