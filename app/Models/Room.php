<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'room_number',
        'name',
        'status'
    ];
    
    public function hotel(): BelongsTo{
        return $this->belongsTo(Hotel::class);
    }

    public function roomType(): BelongsTo{
        return $this->belongsTo(RoomTypes::class);
    }

    
}
