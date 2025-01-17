<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;

class AssignDefaultRole
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;

        // Assign a default role to the user
        $defaultRole = 'subscriber'; // Replace 'user' with your default role name
        if (Role::where('name', $defaultRole)->exists()) {
            $user->assignRole($defaultRole);
        }
    }
}
