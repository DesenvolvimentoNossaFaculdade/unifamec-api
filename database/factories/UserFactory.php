<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * O nome do atributo de senha para o factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = fake()->name();
        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            // Nossos campos
            'title' => 'Professor(a)', // Default
            'bio' => fake()->paragraph(3),
            'avatar_url' => 'https://via.placeholder.com/400x400.png?text=' . urlencode(substr($name, 0, 1)),
        ];
    }

    
}