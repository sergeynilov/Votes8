<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

use DB;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;

class Chat extends MyAppModel
{
    use funcsTrait;

    protected $table      = 'chats';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = ['name', 'description', 'creator_id', 'status'];

    private static $chatStatusValueArray = Array('A' => 'Active', 'C' => 'Not active');

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function chatMessages()
    {
        return $this->hasMany('App\ChatMessage');
    }

    public function chatParticipants()
    {
        return $this->hasMany('App\ChatParticipant');
    }

    /**
     * Get the user's history.
     */
    public function chatMessageDocuments()
    {
        return $this->hasOneThrough('App\ChatMessageDocument', 'App\ChatMessage');
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($user) {
            foreach ( $user->chatMessages()->get() as $nextChatMessage ) {
                $nextChatMessage->delete();
            }
            foreach ( $user->chatParticipants()->get() as $nextChatParticipant ) {
                $nextChatParticipant->delete();
            }

        });
    }

    public static function getChatStatusValueArray($key_return = true): array
    {
        $resArray = [];
        foreach (self::$chatStatusValueArray as $key => $value) {
            if ($key_return) {
                $resArray[] = ['key' => $key, 'label' => $value];
            } else {
                $resArray[$key] = $value;
            }
        }

        return $resArray;
    }


    public static function getChatStatusLabel(string $status): string
    {
        if ( ! empty(self::$chatStatusValueArray[$status])) {
            return self::$chatStatusValueArray[$status];
        }
        return '';
    }


    public function scopeGetByCreatorId($query, $creator_id = null)
    {
        if (empty($creator_id)) {
            return $query;
        }
        return $query->where(with(new Chat)->getTable() . '.creator_id', $creator_id);
    }


    public function scopeGetByStatus($query, $status = null)
    {
        if ( !isset($status) or strlen($status) == 0 ) {
            return $query;
        }
        return $query->where(with(new Chat)->getTable() . '.status', $status);
    }


    public function scopeGetByName($query, $name = null, $partial = false)
    {
        if ( ! isset($name)) {
            return $query;
        }
        return $query->where(with(new Chat)->getTable().'.name', (! $partial ? '=' : 'like'), ($partial ? '%' : '') . $name . ($partial ? '%' : ''));
    }

    public static function getValidationRulesArray($chat_id = null, array $options= []): array
    {
        $validationRulesArray = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(with(new Chat)->getTable())->ignore($chat_id),
            ],

            'description'  => 'required',
            'status'       => 'required|in:' . with(new Chat)->getValueLabelKeys(Chat::getChatStatusValueArray(false)),
            'creator_id'   => 'required|integer|exists:' . (with(new User)->getTable()) . ',id',
        ];

//        with(new Chat)->debToFile(print_r( $options,true),'  app/Chat.php  $options -1::');
        if (!empty($options) and in_array('skip_creator_id', $options)) {
            unset($validationRulesArray['creator_id']);
//            with(new Chat)->debToFile(print_r( $validationRulesArray,true),'  app/Chat.php  $validationRulesArray -2::');
        }
        if (!empty($options) and in_array('skip_status', $options)) {
            unset($validationRulesArray['status']);
        }

        return $validationRulesArray;
    }

}