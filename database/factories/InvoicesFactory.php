<?php

namespace Database\Factories;

use App\Models\Customers;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoices>
 */
class InvoicesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $paymentStatus = ['Paid', 'Pending'];

        return [
            'customer_id' => Customers::factory(),
            'total_amount' => $this->faker->randomFloat(2, 1, 1000),
            'original_currency' => 'USD',
            'total_amount_selected_currency' => $this->faker->randomFloat(2, 1, 1000),
            'selected_currency' => $this->faker->currencyCode,
            'invoice_date' => $this->faker->date(),
            'payment_status' => $paymentStatus[array_rand($paymentStatus)],
        ];
    }
}
