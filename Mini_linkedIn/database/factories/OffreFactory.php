<?php

namespace Database\Factories;

use App\Models\Offre;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<Offre>
 */
class OffreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titre' => fake()->jobTitle(),
            'description' => fake()->paragraph(),
            'localisation' => fake()->city(),
            'type' => fake()->randomElement(['CDI','CDD','stage']),
            'actif' => fake()->boolean(),
        ];
    }
}
