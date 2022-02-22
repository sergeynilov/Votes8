<?php

namespace App;
use DB;
use App\MyAppModel;
use App\User;
use App\Http\Traits\funcsTrait;


class ChatParticipant extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'chat_participants';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $img_filename_max_length = 255;

    protected $fillable = [ 'user_id', 'chat_id', 'status'];
    private static $chatParticipantParticipantTypeLabelValueArray = Array('M'=>'Manage this chat', 'W' => 'Can write messages', 'R' => 'Can only read messages');

    protected $casts = [
    ];

    public function getImgFilenameMaxLength(): int
    {
        return $this->img_filename_max_length;
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function chat()
    {
        return $this->belongsTo('App\Chat');
    }



    public function scopeGetByChat($query, $chat_id= null)
    {
        if (!empty($chat_id)) {
            if ( is_array($chat_id) ) {
                $query->whereIn(with(new ChatParticipant)->getTable().'.chat_id', $chat_id);
            } else {
                $query->where(with(new ChatParticipant)->getTable().'.chat_id', $chat_id);
            }
        }
        return $query;
    }

    public function scopeGetByUser($query, $user_id= null)
    {
        if (!empty($user_id)) {
            if ( is_array($user_id) ) {
                $query->whereIn(with(new ChatParticipant)->getTable().'.user_id', $user_id);
            } else {
                $query->where(with(new ChatParticipant)->getTable().'.user_id', $user_id);
            }
        }
        return $query;
    }

    public function chatParticipantUsersResults()
    {
        return $this->hasMany('App\ChatParticipantUsersResult');
    }


/*    protected static function boot() {
        parent::boot();
        static::deleting(function($chatParticipant) {
        });
    }*/


    public static function getChatParticipantParticipantTypeValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$chatParticipantParticipantTypeLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getChatParticipantParticipantTypeLabel(string $participant_type):string
    {
        if (!empty(self::$chatParticipantParticipantTypeLabelValueArray[$participant_type])) {
            return self::$chatParticipantParticipantTypeLabelValueArray[$participant_type];
        }
        return self::$chatParticipantParticipantTypeLabelValueArray[0];
    }


    public static function getSimilarChatParticipantByName( string $name, int $chat_id, int $id= null, $return_count= false )
    {
        $quoteModel = ChatParticipant::where( 'name', $name );
        $quoteModel = $quoteModel->where( 'chat_id', '=' , $chat_id );
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


    //         return ChatParticipant::getValidationRulesArray( $request->get('chat_id'), $request->get('id') );
    public static function getValidationRulesArray($chat_id, $chat_participant_id = null): array
    {
        $additional_item_value_validation_rule= 'check_chat_participant_unique_by_name:'.$chat_id.','.( !empty($chat_participant_id)?$chat_participant_id:'');
        $validationRulesArray            = [
            'chat_id'      => 'required|exists:' . ( with(new Chat)->getTable() ).',id',
            'user_id'      => 'required|exists:' . ( with(new User)->getTable() ).',id',
            'status'       => 'required|in:'     . with(new ChatParticipant)->getValueLabelKeys(ChatParticipant::$chatParticipantParticipantTypeLabelValueArray(false)),
        ];

        return $validationRulesArray;
    }

}
