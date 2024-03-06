<?php

namespace App\Listeners;

use App\Events\NewUserInviteCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Invite;
use App\Notifications\NewUserInvite;

class SendUserInviteNotifications 
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
     * @param  \App\Events\NewUserInviteCreated  $event
     * @return void
     */
    public function handle(NewUserInviteCreated $event)
    {
        foreach (Invite::where('invite_id', $event->invite->invite_id)->cursor() as $invite) {
            $invite->notify(new NewUserInvite($event->invite));
        }
    }
}
