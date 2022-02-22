<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use App\MyAppModel;

class Group extends MyAppModel
{
    use Notifiable;

    protected $table      = 'groups';
    protected $primaryKey = 'id';
    protected $is_checked = false;
    public $timestamps    = false;

    protected $fillable = [
        'name',
        'description'
    ];

    public function getIsCheckedAttribute() : bool
    {
        return $this->is_checked;
    }

    public function setIsCheckedAttribute( bool $is_checked)
    {
        $this->is_checked = $is_checked;
    }

    /* return data array by keys id/name based on filters/ordering... */
    public static function getGroupSelectionList( bool $key_return= true, array $filtersArray = array(), string $order_by = 'name', string $order_direction = 'asc') : array
    {
/*        $usersList = Group::getGroupList(::LISTING, $filtersArray, $order_by, $order_direction);
        $resArray = [];
        foreach ($usersList as $nextGroup) {
            if ($key_return) {
                $resArray[] = array('key' => $nextGroup->id, 'value' => $nextGroup->name . ' (' . $nextGroup->description . ')');
            } else{
                $resArray[ $nextGroup->id ]= $nextGroup->name . ' (' . $nextGroup->description . ')';
            }
        }
        return $resArray;*/
    }




    public static function getRowById( int $id )
    {
        if (empty($id)) return false;
        $groups = Group::find($id);
        if (empty($groups)) return false;
        return $groups;
    } // public function getRowById( int $id)

    /* check if provided name is unique for ion_groups.name field */
    public static function getSimilarGroupByName( string $name, int $id= null, bool $return_count = false )
    {
        $quoteModel = Group::whereRaw( Group::myStrLower('name', false, false) .' = '. Group::myStrLower( Group::myStrLower($name), true,false) );
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

}