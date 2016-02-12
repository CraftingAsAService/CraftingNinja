<?php

namespace App\Listeners;

use App\Http\Requests;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEventListener
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
     * @param  Login  $event
     * @return void
     */
    public function login(Login $event)
    {
        // Force a refresh on if they're advanced or not
        $event->user->is_advanced_crafter(true);

        // Load in their prefered language
        if ($event->user->language_id)
        {
            $language = \App\Models\Language::find($event->user->language_id);
            if (isset($language->exists))
                \Request::session()->set('applocale', $language->code);
        }
    }

}
