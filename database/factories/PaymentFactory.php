<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $status = fake()->randomElement([
            'pending',
            'success',
            'failed',
        ]);

        return [
            'booking_id' => Booking::factory(),

            'transaction_id' => fake()->unique()->bothify('TXN-########'),

            'payment_method' => fake()->randomElement([
                'eSewa',
                'Khalti',
                'Card',
                'Cash',
            ]),

            'amount' => fake()->randomFloat(
                2,
                1000,
                100000
            ),

            'status' => $status,

            'paid_at' => $status === 'success'
                ? fake()->dateTimeBetween('-6 months', 'now')
                : null,
        ];
    }
}
