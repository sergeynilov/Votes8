<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [



       'App\Events\VoteChatMessageSentEvent' => [
            'App\Listeners\VoteChatMessageSentEventListener',
        ],
       'App\Events\NewMessage' => [
            'App\Listeners\NewMessageListener',
        ],

        'App\Events\backendFailOnLoginEvent' => [
            'App\Listeners\backendFailOnLoginListener',
        ],

        'App\Events\backendSuccessOnLoginEvent' => [
            'App\Listeners\backendSuccessOnLoginListener',
        ],

        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\\Instagram\\InstagramExtendSocialite@handle',
        ],


        'App\Events\ChatEvent' => [
            'App\Listeners\ChatEventListener',
        ],

        'App\Events\UserChatUpdatingEvent' => [
            'App\Listeners\UserChatUpdatingListener',
        ],

        'App\Events\UserChatMessageUpdatingEvent' => [
            'App\Listeners\UserChatMessageUpdatingListener',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
