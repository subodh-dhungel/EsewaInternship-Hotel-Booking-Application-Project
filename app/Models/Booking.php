<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function hotel(): BelongsTo {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType(): BelongsTo {
        return $this->belongsTo(RoomTypes::class);
    }

    public function payment(): HasOne {
        return $this->hasOne(Payment::class);
    }

    public function review(): HasOne {
        return $this->hasOne(Review::class);
    }

    public function coupon(): BelongsToMany {
        return $this->belongsToMany(Coupons::class);
    }
}
