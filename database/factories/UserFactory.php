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
            'password' => static::$password ??= Hash::make('password'), // Senha padrão 'password'
            'remember_token' => Str::random(10),

            // Nossos campos
            'title' => 'Professor(a)', // Default
            'bio' => fake()->paragraph(3),
            'avatar_url' => 'https://via.placeholder.com/400x400.png?text=' . urlencode(substr($name, 0, 1)),
            'role' => 'user', // Default
        ];
    }

    /**
     * State para definir um usuário como Coordenador.
     */
    public function coordinator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'coordinator',
            'title' => 'Coordenador(a) de ' . fake()->randomElement(['TI', 'Direito', 'Saúde']),
        ]);
    }

    /**
     * State para definir um usuário como Admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'title' => 'Administrador do Sistema',
        ]);
    }
}