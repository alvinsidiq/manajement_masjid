<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'dark_mode' => fake()->boolean(30),
            'preferred_landing' => fake()->randomElement(['dashboard','home']),
        ];
    }
}

