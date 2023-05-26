<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Scale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * @return string
     */
    public function getRandomCpf()
    {
        $number = '';
        for ($i = 0; $i < 11; $i++) {
            $number .= rand(0, 9);
        }
        return $number;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nickname' => fake()->unique()->userName,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'cpf' => $this->getRandomCpf(),
            'register' => Str::random(5),
            'role_id' => rand(Role::first()->id, Role::latest()->first()->id),
            'scale_id' => rand(Scale::first()->id, Scale::latest()->first()->id)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
