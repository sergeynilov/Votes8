<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class backendFailOnLoginEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $loginCreditialsArray;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $loginCreditialsArray)
    {
        $this->loginCreditialsArray = $loginCreditialsArray;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
