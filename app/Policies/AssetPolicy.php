<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    /**
     * Determine if the user can view any assets.
     */
    public function viewAny(User $user)
    {
        // Allow if the user has the administrator role or has specific permission
        return $user->hasRole('administrator') || $user->hasPermissionTo('view assets');
    }

    /**
     * Determine if the user can view a specific asset.
     */
    public function view(User $user, Asset $asset)
    {
        // Allow if the user is the owner or has the administrator role
        return $user->id === $asset->user_id || $user->hasRole('administrator');
    }

    /**
     * Determine if the user can create an asset.
     */
    public function create(User $user)
    {
        // Allow if the user has the administrator role or has specific permission
        return $user->hasRole('administrator') || $user->hasPermissionTo('create assets');
    }

    /**
     * Determine if the user can update an asset.
     */
    public function update(User $user, Asset $asset)
    {
        // Allow if the user is the owner or has the administrator role
        return $user->id === $asset->user_id || $user->hasRole('administrator');
    }

    /**
     * Determine if the user can delete an asset.
     */
    public function delete(User $user, Asset $asset)
    {
        // Allow if the user is the owner or has the administrator role
        return $user->id === $asset->user_id || $user->hasRole('administrator');
    }

    /**
     * Determine if the user can restore an asset.
     */
    public function restore(User $user, Asset $asset)
    {
        // Allow if the user is the owner or has the administrator role
        return $user->id === $asset->user_id || $user->hasRole('administrator');
    }

    /**
     * Determine if the user can permanently delete an asset.
     */
    public function forceDelete(User $user, Asset $asset)
    {
        // Allow if the user has the administrator role
        return $user->hasRole('administrator');
    }
}
