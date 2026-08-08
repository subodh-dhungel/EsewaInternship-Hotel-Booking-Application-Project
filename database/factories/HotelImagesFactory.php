<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\HotelImages;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelImagesFactory extends Factory
{
    protected $model = HotelImages::class;
    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),
            'image' => 'hotels/' . fake()->uuid() . '.jpg',
            'caption' => fake()->sentence(),
        ];
    }
}
