<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Permission;

class AssignCreateAssetsPermission
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event)
    {
        // Access the newly registered user
        $user = $event->user;

        // Ensure the 'create assets' permission exists
        $permission = Permission::firstOrCreate(['name' => 'create assets', 'guard_name' => 'web']);

        // Assign the permission to the user
        $user->givePermissionTo($permission);
    }
}
