<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id'=>User::factory(),
            'name'=>fake()->company() . ' Hotel',
            'slug'=>fake()->unique()->slug(),
            'description'=>fake()->paragraph(3),
            'address'=>fake()->streetAddress(),
            'city'=>fake()->city(),
            'district'=>fake()->randomElement([
                'Kathmandu',
                'Lalitpur',
                'Bhaktapur',
                'Chitwan',
                'Pokhara',
                'Kaski',
                'Jhapa',
                'Morang',
                'Sunsari',
                'Rupandehi'
            ]),
            'country'=>'Nepal',
            'latitude' => fake()->latitude(26.5, 29.5),
            'longitude' => fake()->longitude(80.0, 88.2),
            'star_rating' => fake()->randomFloat(1, 1, 5),
            'phone' => fake()->numerify('98########'),
            'Email' => fake()->unique()->safeEmail(),
            'checkin_time' => fake()->randomElement([ '12:00:00', '13:00:00', '14:00:00', ]),
            'check_out_time' => fake()->randomElement([ '10:00:00', '11:00:00', '12:00:00', ]),
            'featured_image' => 'hotels/default.jpg',
            'status' => fake()->randomElement([ 'pending', 'approved', 'rejected']),
            'is_featured' => fake()->boolean(20)          
        ];
    }
}
