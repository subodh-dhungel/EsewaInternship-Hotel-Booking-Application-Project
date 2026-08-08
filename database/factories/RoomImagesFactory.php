<?php

namespace Database\Factories;

use App\Models\RoomImages;
use App\Models\RoomTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomImagesFactory extends Factory
{
    protected $model = RoomImages::class;
    public function definition(): array
    {
        return [
            'room_type_id' => RoomTypes::factory(),
            'image' => 'rooms/' . fake()->uuid() . '.jpg',
        ];
    }
}
