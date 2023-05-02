<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $vehicleTypes = [
            [
                'name' => 'Compact',
                'seats' => 4,
                'price' => 15.5,
                'transmission' => 'Manual',
                'daily_rate' => 30.0,
                'image' => 'compact.jpg'
            ],
        ];

        foreach ($vehicleTypes as $vehicleType) {
            VehicleTypes::create($vehicleType);
        }
    }
}
