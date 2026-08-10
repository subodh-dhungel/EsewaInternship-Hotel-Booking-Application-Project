<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'district',
        'country',
        'latitude',
        'longitude',
        'star_rating',
        'phone',
        'email',
        'checkin_time',
        'check_out_time',
        'featured_image',
        'is_featured',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function image(): HasMany
    {
        return $this->hasMany(HotelImages::class);
    }

    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomTypes::class);
    }

    public function amenity(): BelongsToMany
    {
        return $this->belongsToMany(
            Amenities::class,
            'amenity_hotels',
            'hotel_id',
            'amenity_id'
        );
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorite(): HasMany
    {
        return $this->hasMany(Favorites::class);
    }

    public function user_favorite(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
