<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use App\MyAppModel;
use App\Group;

class UserGroup extends MyAppModel
{
    use Notifiable;

    protected $table      = 'users_groups';
    protected $primaryKey = 'id';
    protected $is_checked = false;
    public $timestamps = false;
    private static $userGroupSelectedLabelValueArray = Array('0' => 'Not selected', '1' => 'Selected');


    protected $fillable = [
        'user_id',
        'group_id'
    ];

    public static function getUserGroupSelectedValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$userGroupSelectedLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }

        return $resArray;
    }

    public static function getUserGroupSelectedLabel(string $user_group_selected): string
    {
        if ( ! empty(self::$userGroupSelectedLabelValueArray[$user_group_selected])) {
            return self::$userGroupSelectedLabelValueArray[$user_group_selected];
        }

        return '';
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function scopeGetByGroup($query, $group_id= null)
    {
        if (!empty($group_id)) {
            $query->where(with(new UserGroup)->getTable().'.group_id', $group_id);
        }
        return $query;
    }


    public function scopeGetByUser($query, $user_id= null)
    {
        if (!empty($user_id)) {
            $query->where(with(new UserGroup)->getTable().'.user_id', $user_id);
        }
        return $query;
    }


    public function getIsCheckedAttribute() : bool
    {
        return $this->is_checked;
    }

    public function setIsCheckedAttribute(bool $is_checked)
    {
        $this->is_checked = $is_checked;
    }


    public static function getRowById( int $id )
    {
        if (empty($id)) return false;
        $userGroup = UserGroup::find($id);

        if (empty($userGroup)) return false;
        return $userGroup;
    } // public function getRowById( int $id, array $additiveParams= [] )


}
