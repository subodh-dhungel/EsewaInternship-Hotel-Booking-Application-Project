<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\HotelImages;
use App\Models\User;

class HotelImagePolicy
{
    /**
     * Create a new policy instance.
     */

    public function create(User $user, Hotel $hotel):bool{
        return $hotel->owner_id === $user->id;
    }

    // Make the owner of the hotel to delete his own hotel
    public function delete(User $user, HotelImages $image):bool{
        return $user->id === $image->hotel->owner_id;
    }
}
