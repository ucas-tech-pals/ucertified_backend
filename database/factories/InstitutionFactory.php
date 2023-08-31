<?php

namespace Database\Factories;

use App\PDFCryptoSigner\CryptoManager;
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
        $keys = CryptoManager::generateKeyPair();

        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
//            'logo' => 'logo.png',
//            'website' => fake()->url(),
//            'phone_number' => fake()->phoneNumber(),
//            'description' => fake()->paragraph(),
            'private_key' => $keys['private_key'],
            'public_key' => $keys['public_key']
        ];
    }
}
