<?php

namespace Database\Factories;

use App\Models\Notifications;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationsFactory extends Factory
{
    protected $model = Notifications::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'title' => fake()->sentence(4),

            'message' => fake()->paragraph(),

            'read_at' => fake()->boolean(30)
                ? fake()->dateTimeBetween('-1 month', 'now')
                : null,
        ];
    }
}
