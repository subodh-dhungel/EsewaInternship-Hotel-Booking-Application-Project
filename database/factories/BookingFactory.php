<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Hotel;
use App\Models\RoomTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('now', '+3 months');

        $checkOut = fake()->dateTimeBetween(
            $checkIn,
            '+10 days'
        );

        return [
            'booking_number' => 'BK-' . strtoupper(fake()->unique()->bothify('####??##')),

            'user_id' => User::factory(),

            'hotel_id' => Hotel::factory(),

            'room_type_id' => RoomTypes::factory(),

            'check_in' => $checkIn->format('Y-m-d'),

            'check_out' => $checkOut->format('Y-m-d'),

            'adults' => fake()->numberBetween(1, 4),

            'children' => fake()->numberBetween(0, 3),

            'total_price' => fake()->randomFloat(
                2,
                2000,
                100000
            ),

            'booking_status' => fake()->randomElement([
                'pending',
                'confirmed',
                'completed',
                'cancelled',
            ]),

            'payment_status' => fake()->randomElement([
                'pending',
                'paid',
                'failed',
                'refunded',
            ]),
        ];
    }
}
