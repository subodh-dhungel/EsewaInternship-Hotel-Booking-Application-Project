<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\RoomTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomTypesFactory extends Factory
{
    protected $model = RoomTypes::class;
    public function definition(): array
    {
        $totalRooms = fake()->numberBetween(5, 30);
        return [
            'hotel_id' => Hotel::factory(),
            'name' => fake()->randomElement(['Standard Room', 'Deluxe Room', 'Executive Room', 'Family Room', 'Suite', 'Presidential Suite',]),
            'description' => fake()->paragraph(2),
            'capacity' => fake()->numberBetween(1, 6),
            'bed_type' => fake()->randomElement(['Single', 'Double', 'Queen', 'King', 'Twin',]),
            'room_size' => fake()->numberBetween(150, 1000),
            'price' => fake()->randomFloat(2, 2000, 30000),
            'discount_price' => fake()->randomFloat(2, 1500, 25000),
            'total_rooms' => $totalRooms,
            'available_rooms' => fake()->numberBetween(0, $totalRooms),
        ];
    }
}
