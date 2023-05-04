<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rentals>
 */
class RentalsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+2 days');
        $endDate = $this->faker->dateTimeBetween('+3 days', '+1 month');

        return [
            'customer_id' => $this->faker->numberBetween(1, 100),
            'vehicle_id' => $this->faker->numberBetween(1, 100),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $endDate->diff($startDate)->days,
            'invoice_id' => $this->faker->optional(0.5)->numberBetween(1, 100),
        ];
    }
}
