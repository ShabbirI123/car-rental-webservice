<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\VehicleTypes;
use App\Models\Vehicles;
use App\Http\Controllers\Api\CarRentalCarController;

class CarRentalCarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_car_details()
    {
        $vehicleType = VehicleTypes::factory()->create();
        $vehicle = Vehicles::factory()->create([
            'vehicle_type_id' => $vehicleType->vehicle_type_id,
        ]);

        $controller = new CarRentalCarController();
        $response = $controller->getCarDetails($vehicle->vehicle_id);

        $expectedJson = [
            'vehicle-id' => $vehicle->vehicle_id,
            'vehicle-name' => $vehicleType->name,
            'transmission' => $vehicleType->transmission,
            'daily-rate' => (string) $vehicleType->daily_rate,
            'seats' => $vehicleType->seats,
            'price' => $vehicleType->price,
            'image' => $vehicleType->image,
            'available' => $vehicle->available,
            'created_at' => $vehicle->created_at->format('Y-m-d\TH:i:s.u\Z'),
        ];

        $this->assertEquals(json_encode($expectedJson), $response->getContent(), true);
    }

    public function test_get_all_cars()
    {
        $vehicleType = VehicleTypes::factory()->create();
        $vehicles = Vehicles::factory(3)->create([
            'vehicle_type_id' => $vehicleType->vehicle_type_id,
        ]);

        $controller = new CarRentalCarController();
        $response = $controller->getAllCars();

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertCount(3, $data);
    }
}
