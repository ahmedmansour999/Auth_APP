<?php

namespace App\Listeners;

use App\Events\Registerion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUser;
use Illuminate\Support\Facades\Log;

class SendNotify
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registerion $event): void
    {
        Notification::send($event->users, new NewUser($event->user));
    }
}
