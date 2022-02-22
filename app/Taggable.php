<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use DB;
use App\MyAppModel;

class Taggable extends MyAppModel
{
    use Notifiable;

    protected $table      = 'taggables';
    protected $primaryKey = 'id';
    public $incrementing  = false;
    public $timestamps    = false;

    protected $fillable = [
        'tag_id',
        'taggable_id',
        'taggable_type',
    ];


//    public function getTable()
//    {
//        return \DB::getTablePrefix() . $this->table;
//    }

    public function Taggable()
    {
        return $this->belongsTo('App\MyTag');
    }


    public function scopeGetByTaggableType($query, $taggable_type= null)
    {
        if (!empty($taggable_type)) {
//             $query->whereRaw( with(new Taggable)->getTable() . ".taggable_type = '" . self::mysqlEscape($taggable_type) ."' " );
//             $query->whereRaw( \DB::getTablePrefix() . with(new Taggable)->getTable() . ".taggable_type = '" . self::mysqlEscape($taggable_type) ."' ");
//             $query->where( with(new Taggable)->getTable() . ".taggable_type = '" . self::mysqlEscape($taggable_type) ."' ");
             $query->where( with(new Taggable)->getTable() . ".taggable_type", $taggable_type );
//             $query->where(  "taggable_type", $taggable_type );
        }
        return $query;
    }

    public function scopeGetByTaggableId($query, $taggable_id= null)
    {
        if (!empty($taggable_id)) {
            if ( is_array($taggable_id) ) {
                $query->whereIn( with(new Taggable)->getTable().'.taggable_id', $taggable_id );
            } else {
                $query->where( with(new Taggable)->getTable() . '.taggable_id', $taggable_id );
            }
        }
        return $query;
    }

    public function scopeGetByTagId($query, $tag_id= null)
    {
        if (!empty($tag_id)) {
            if ( is_array($tag_id) ) {
                $query->whereIn( with(new Taggable)->getTable().'.tag_id', $tag_id );
            } else {
                $query->where( with(new Taggable)->getTable() . '.tag_id', $tag_id );
            }
        }
        return $query;
    }

}
