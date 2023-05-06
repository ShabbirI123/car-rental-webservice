<?php

namespace Database\Seeders;

use App\Models\Locations;
use App\Models\Vehicles;
use Illuminate\Database\Seeder;
use App\Models\VehicleTypes;

class VehiclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicleTypes = VehicleTypes::all()->pluck('vehicle_type_id')->toArray();
        $locations = Locations::all()->pluck('location_id')->toArray();
        $location = $locations[0];

        for ($i = 0; $i < 10; $i++) {
            $vehicleType = $vehicleTypes[$i % count($vehicleTypes)];
            Vehicles::create([
                'vehicle_type_id' => $vehicleType,
                'location_id' => $location,
                'available' => true,
            ]);
        }
    }
}
