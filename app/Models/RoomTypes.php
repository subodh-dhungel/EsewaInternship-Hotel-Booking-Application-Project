<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class RoomTypes extends Model
{

    protected $fillable = [
        'hotel_id',
        'name',
        'description',
        'price',
        'discount_price',
        'capacity',
        'room_size',
        'total_rooms',
        'bed_type',
        'available_rooms',
        'number_of_rooms',
    ];

    use HasFactory;
    public function hotel() :BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImages::class);
    }

    public function bookings(): HasMany 
    {
        return $this->hasMany(Booking::class);
    }

    public function rooms():HasMany
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }
}
