<?php

namespace App\Listeners;

use App\Events\StoreNotificationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreNotificationListener
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
     * @param  StoreNotificationEvent  $event
     * @return void
     */
    public function handle(StoreNotificationEvent $event)
    {
        //
    }
}
