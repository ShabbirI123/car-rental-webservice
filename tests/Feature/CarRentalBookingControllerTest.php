<?php

namespace Tests\Feature;

use App\Models\Customers;
use App\Models\Vehicles;
use App\Models\Rentals;
use App\Models\Invoices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarRentalBookingControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_create_booking_success()
    {
        $customer = Customers::factory()->create();
        $vehicle = Vehicles::factory()->create(['available' => true]);

        $payload = [
            'user_id' => $customer->customer_id,
            'vehicle_id' => $vehicle->vehicle_id,
            'currency' => 'Euro',
            'amount' => 40.5,
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-05',
        ];

        $response = $this->postJson('/car-rental/api/v1/bookings', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'booking_id',
                'vehicle_id',
                'user_id',
                'start_date',
                'end_date',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_create_booking_validation_errors()
    {
        $response = $this->postJson('/car-rental/api/v1/bookings', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'user_id',
                'vehicle_id',
                'currency',
                'amount',
                'start_date',
                'end_date',
            ]);
    }

    public function test_create_booking_vehicle_not_found()
    {
        $customer = Customers::factory()->create();

        $payload = [
            'user_id' => $customer->customer_id,
            'vehicle_id' => 9999,
            'currency' => 'Euro',
            'amount' => 40.5,
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-05',
        ];

        $response = $this->postJson('/car-rental/api/v1/bookings', $payload);

        $response->assertStatus(422);
    }

    public function test_create_booking_customer_not_found()
    {
        $vehicle = Vehicles::factory()->create(['available' => true]);

        $payload = [
            'user_id' => 9999,
            'vehicle_id' => $vehicle->vehicle_id,
            'currency' => 'Euro',
            'amount' => 40.5,
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-05',
        ];

        $response = $this->postJson('/car-rental/api/v1/bookings', $payload);

        $response->assertStatus(422);
    }

    public function test_get_booking_success()
    {
        $customer = Customers::factory()->create();
        $vehicle = Vehicles::factory()->create(['available' => true]);
        $invoice = Invoices::factory()->create(['customer_id' => $customer->customer_id]);
        $booking = Rentals::factory()->create([
            'customer_id' => $customer->customer_id,
            'vehicle_id' => $vehicle->vehicle_id,
            'invoice_id' => $invoice->invoice_id,
        ]);

        $response = $this->getJson('/car-rental/api/v1/bookings/' . $booking->rental_id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'booking' => [
                    'rental_id',
                    'customer_id',
                    'vehicle_id',
                    'start_date',
                    'end_date',
                    'total_days',
                    'invoice_id',
                    'created_at',
                    'updated_at',
                ],
                'invoice' => [
                    'invoice_id',
                    'customer_id',
                    'total_amount',
                    'original_currency',
                    'total_amount_selected_currency',
                    'selected_currency',
                    'invoice_date',
                    'payment_status',
                    'created_at',
                    'updated_at',
                ],
                'vehicle' => [
                    'vehicle_id',
                    'vehicle_type_id',
                    'location_id',
                    'available',
                    'updated_at',
                ],
            ]);
    }

    public function test_get_booking_not_found()
    {
        $response = $this->getJson('/car-rental/api/v1/bookings/9999');

        $response->assertStatus(404);
    }
}
