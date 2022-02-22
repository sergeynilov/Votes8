<?php

namespace App;
use DB;
use App\MyAppModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;

class ChatsLastVisited extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'chats_last_visited';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'user_id', 'chat_id', 'visited_at' ];

    protected $casts = [
    ];



    public function chat()
    {
        return $this->belongsTo('App\Chat');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function scopeGetByChat($query, $chat_id= null)
    {
        if (!empty($chat_id)) {
            if ( is_array($chat_id) ) {
                $query->whereIn(with(new ChatsLastVisited)->getTable().'.chat_id', $chat_id);
            } else {
                $query->where(with(new ChatsLastVisited)->getTable().'.chat_id', $chat_id);
            }
        }
        return $query;
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($chatsLastVisited) {
        });
    }

}
