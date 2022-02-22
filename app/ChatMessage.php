<?php

namespace App;
use DB;
use App\MyAppModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;

class ChatMessage extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'chat_messages';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'user_id', 'chat_id', 'is_top', 'text', 'message_type' ];

    private static $chatMessageMessageTypeLabelValueArray = Array('N' => 'Text added', 'U' => 'Files uploaded');

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
                $query->whereIn(with(new ChatMessage)->getTable().'.chat_id', $chat_id);
            } else {
                $query->where(with(new ChatMessage)->getTable().'.chat_id', $chat_id);
            }
        }
        return $query;
    }

//    public function chatMessageUsersResults()
//    {
//        return $this->hasMany('App\ChatMessageUsersResult');
//    }


    protected static function boot() {
        parent::boot();
        static::deleting(function($chatMessage) {
//            foreach ( $chatMessage->chatMessageUsersResults()->get() as $nextChatMessageUsersResult ) { // chat_message_users_result
//                $nextChatMessageUsersResult->delete();
//            }
//
/*            $chat_message_image_path= ChatMessage::getChatMessageImagePath($chatMessage->id, $chatMessage->image, true);
            ChatMessage::deleteFileByPath($chat_message_image_path, true);*/
        });
    }


    public static function getChatMessageMessageTypeValueArray($key_return= true) : array
    {
        $resArray = [];
        foreach (self::$chatMessageMessageTypeLabelValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = [ 'key' => $key, 'label' => $value ];
            } else {
                $resArray[$key] = $value;
            }
        }
        return $resArray;
    }

    public static function getChatMessageMessageTypeLabel(string $message_type):string
    {
        if (!empty(self::$chatMessageMessageTypeLabelValueArray[$message_type])) {
            return self::$chatMessageMessageTypeLabelValueArray[$message_type];
        }
        return self::$chatMessageMessageTypeLabelValueArray[0];
    }


    public static function getSimilarChatMessageByName( string $name, int $chat_id, int $id= null, $return_count= false )
    {
        $quoteModel = ChatMessage::where( 'name', $name );
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


    //         return ChatMessage::getValidationRulesArray( $request->get('chat_id'), $request->get('id') );
    public static function getValidationRulesArray($chat_id, $chat_message_id = null): array
    {
        $additional_item_value_validation_rule= 'check_chat_message_unique_by_name:'.$chat_id.','.( !empty($chat_message_id)?$chat_message_id:'');


        $validationRulesArray            = [
            'chat_id'      => 'required|exists:'.( with(new Chat)->getTable() ).',id',
//            'chat_id'             => 'required|max:255|' . $additional_item_value_validation_rule,


            'name'             => 'required|max:255|' . $additional_item_value_validation_rule,
            'is_correct'       => 'required|in:' . with(new ChatMessage)->getValueLabelKeys(ChatMessage::getChatMessageMessageTypeValueArray(false)),
            'ordering'         => 'required|integer',
        ];

        return $validationRulesArray;
    }
    /* CREATE TABLE "chat_messages" ("id" integer not null primary key autoincrement,
            "chat_id" integer null,
         "name" varchar not null,
             "ordering" integer not null,
            "is_correct" tinyint(1) not null default '0',
    "image" varchar null,
    "created_at" datetime default CURRENT_TIMESTAMP not null, "updated_at" datetime null, foreign key("chat_id") references "chats"("id") on delete RESTRICT)
     */

}
