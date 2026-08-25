<?php

namespace App\Policies;

use App\Models\User;

class RoomPolicy
{

    public function viewAny()
    {
        return true;
    }


    public function view(User $user)
    {
        return $user->hasPermission('view_rooms')
            && (
                $user->hasRole('customer')
                || $user->hasRole('hotel_owner')
                || $user->hasRole('admin')
                || $user->hasRole('super_admin')
            );
    }

    public function create(User $user)
    {
        return $user->hasPermission('create_rooms')
            && (
                $user->hasRole('hotel_owner')
                || $user->hasRole('admin')
                || $user->hasRole('super_admin')
            );
    }

    public function update() {}

    public function delete() {}
}
