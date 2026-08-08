<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupons extends Model
{
    use HasFactory;
    public function booking(): BelongsToMany {
        return $this->belongsToMany(Booking::class);
    }
}
