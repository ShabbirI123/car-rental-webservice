<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicles>
 */
class VehiclesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'vehicle_type_id' => \App\Models\VehicleTypes::factory(),
            'location_id' => \App\Models\Locations::factory(),
            'available' => $this->faker->boolean,
        ];
    }
}
