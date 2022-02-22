<?php

namespace App\Listeners;

use App\Events\UserChatMessageUpdatingEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserChatMessageUpdatingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserChatMessageUpdatingEvent  $event
     * @return void
     */
    public function handle(UserChatMessageUpdatingEvent $event)
    {
        //
    }
}
