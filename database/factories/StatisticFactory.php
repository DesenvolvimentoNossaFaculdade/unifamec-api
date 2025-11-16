<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StatisticFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'value' => fake()->numberBetween(100, 10000),
            'prefix' => null,
            'suffix' => null,
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}