<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomImages extends Model
{
    use HasFactory;
    public function roomType(): BelongsTo{
        return $this->belongsTo(RoomTypes::class);
    }
}
