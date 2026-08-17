<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\User;


class HotelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Hotel $hotel): bool
    {
        return $user->hasPermission('view_hotel')
            &&(
                $user->id === $hotel->owner_id
                || $user->hasRole('super_admin')    
                || $user->hasRole('admin')
            )
        ;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Hotel $hotel): bool
    {
        return $user->hasPermission('update_hotel') 
        &&(
            $user->id === $hotel->owner_id
            || $user->hasRole('super_admin')
            || $user->hasRole('admin')
        );
    }

    public function activate(User $user, Hotel $hotel):bool{
        return $user->hasPermission('activate_hotel')
        &&(
            $user->id === $hotel->owner_id
            || $user->hasRole('admin')
            || $user->hasRole('super_admin')
        );
    }

    public function deactivate(User $user, Hotel $hotel):bool{
        return $user->hasPermission('deactivate_hotel')
        &&(
            $user->id === $hotel->owner_id
            || $user->hasRole('admin')
            || $user->hasRole('super_admin')
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Hotel $hotel): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Hotel $hotel): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Hotel $hotel): bool
    {
        return false;
    }
}
