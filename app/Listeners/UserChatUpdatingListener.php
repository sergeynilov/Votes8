<?php

namespace App\Listeners;

use App\Events\UserChatUpdatingEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserChatUpdatingListener
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
     * @param  UserChatUpdatingEvent  $event
     * @return void
     */
    public function handle(UserChatUpdatingEvent $event)
    {
        //
    }
}
