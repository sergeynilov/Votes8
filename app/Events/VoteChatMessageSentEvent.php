<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;
use App\ChatMessage;
use App\Exceptions\NotLoggedUserException;
use App\Http\Traits\funcsTrait;
use function PHPSTORM_META\type;



class VoteChatMessageSentEvent  implements ShouldBroadcast
{
    use funcsTrait;

    public $message_text;
    public $user_chat_message_id;
    public $author_id;
    public $chat_id;
    public $is_top;
    public $author_name;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct( $message_text, $user_chat_message_id, $chat_id, $is_top, $author_id, $author_name )
    {
        $this->message_text= $message_text;
        $this->user_chat_message_id= $user_chat_message_id;
        $this->chat_id= $chat_id;
        $this->is_top= $is_top;
        $this->author_id= $author_id;
        $this->author_name= $author_name;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('make_votes_chat');
        return new PresenceChannel('make_votes_chat'); // todo ?
//        return new Channel('make_votes_chat'); // todo ?
    }
}
