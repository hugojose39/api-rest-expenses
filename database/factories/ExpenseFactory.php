<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => $this->faker->text(50),
            'date' => now()->subMinutes(10),
            'value' => random_int(10, 1000),
        ];
    }
}
