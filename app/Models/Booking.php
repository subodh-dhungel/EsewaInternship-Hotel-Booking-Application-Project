<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'user_id',
        'hotel_id',
        'room_type_id',
        'check_in',
        'check_out',
        'adults',
        'children',
        'number_of_rooms',
        'total_price',
        'booking_status',
        'payment_status',
        'phone_number',
        'expires_at',
    ];

    protected $casts = [
        'check-in' => 'date',
        'check_out'=> 'date',
        'price'=>'decimal:2',
        'total_amount'=>'decimal:2',
        'expires_at'=>'datetime'
    ];

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
