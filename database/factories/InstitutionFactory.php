<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Institution>
 */
class InstitutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('12345678'), 
            'logo' => 'logo.png',
            'website' => fake()->url(),
            'phone_number' => fake()->phoneNumber(),
            'description' => fake()->paragraph(),
        ];
    }
}
