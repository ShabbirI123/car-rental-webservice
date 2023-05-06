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
                'name' => 'Saucy Sedans',
                'address' => 'Favoritenstraße 226',
                'postal_code' => '1100',
                'city' => 'Vienna',
                'country' => 'Austria'
            ],
        ];

        foreach ($locations as $location) {
            Locations::create($location);
        }
    }
}
