<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'client',
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'adresse' => fake()->streetAddress(),
            'code_postal' => fake()->postcode(),
            'date_de_naissance' => fake()->date('Y-m-d', '-18 years'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Donne le rôle administrateur à l'utilisateur.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
