<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleTypes>
 */
class VehicleTypesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'transmission' => $this->faker->randomElement(['Automatic', 'Manual']),
            'daily_rate' => $this->faker->randomFloat(2, 1, 100),
            'seats' => $this->faker->numberBetween(2, 7),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
