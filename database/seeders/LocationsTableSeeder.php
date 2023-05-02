<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Locations;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            [
                'name' => 'Location A',
                'address' => '123 Main St',
                'postal_code' => '12345',
                'city' => 'City A',
                'country' => 'Country A'
            ],
            // ... (Add more sample locations)
        ];

        foreach ($locations as $location) {
            Locations::create($location);
        }
    }
}
