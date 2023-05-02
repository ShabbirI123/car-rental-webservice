<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicles;
use App\Models\Locations;
use App\Models\VehicleTypes;

class VehicleTypesTableSeeder extends Seeder
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

        for ($i = 0; $i < 20; $i++) {
            Vehicles::create([
                'vehicle_type_id' => $vehicleTypes[array_rand($vehicleTypes)],
                'location_id' => $locations[array_rand($locations)],
                'available' => true,
            ]);
        }
    }
}
