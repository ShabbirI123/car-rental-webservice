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
        $vehicleTypes = [
            [
                'name' => 'Toyota Prius Hybrid',
                'seats' => 5,
                'price' => 24.5,
                'transmission' => 'Automatic',
                'daily_rate' => 45.0,
                'image' => 'https://saucy-sedans-images.s3.eu-central-1.amazonaws.com/Toyota+Prius+Hybrid.jpg'
            ],
            [
                'name' => 'Volkswagen Golf',
                'seats' => 5,
                'price' => 20.5,
                'transmission' => 'Manual',
                'daily_rate' => 35.0,
                'image' => 'https://saucy-sedans-images.s3.eu-central-1.amazonaws.com/Volkswagen+Golf.jpg'
            ],
            [
                'name' => 'BMW 3 Series',
                'seats' => 5,
                'price' => 32.0,
                'transmission' => 'Automatic',
                'daily_rate' => 60.0,
                'image' => 'https://saucy-sedans-images.s3.eu-central-1.amazonaws.com/BMW+3+Series.jpg'
            ],
            [
                'name' => 'Mercedes-Benz C-Class',
                'seats' => 5,
                'price' => 35.0,
                'transmission' => 'Automatic',
                'daily_rate' => 65.0,
                'image' => 'https://saucy-sedans-images.s3.eu-central-1.amazonaws.com/Mercedes-Benz+C-Class.jpg'
            ],
            [
                'name' => 'Audi A4',
                'seats' => 5,
                'price' => 30.5,
                'transmission' => 'Manual',
                'daily_rate' => 55.0,
                'image' => 'https://saucy-sedans-images.s3.eu-central-1.amazonaws.com/Audi+A4.jpg'
            ],
            [
                'name' => 'Ford Focus',
                'seats' => 5,
                'price' => 18.0,
                'transmission' => 'Manual',
                'daily_rate' => 32.0,
                'image' => 'https://saucy-sedans-images.s3.eu-central-1.amazonaws.com/Ford+Focus.jpg'
            ],
            [
                'name' => 'Honda Civic',
                'seats' => 5,
                'price' => 20.0,
                'transmission' => 'Manual',
                'daily_rate' => 35.0,
                'image' => 'https://saucy-sedans-images.s3.eu-central-1.amazonaws.com/honda+civic.jpg'
            ],
            [
                'name' => 'Nissan Leaf',
                'seats' => 5,
                'price' => 23.5,
                'transmission' => 'Automatic',
                'daily_rate' => 40.0,
                'image' => 'https://saucy-sedans-images.s3.eu-central-1.amazonaws.com/Nissan+Leaf.jpg'
            ],
            [
                'name' => 'Mazda 3',
                'seats' => 5,
                'price' => 20.5,
                'transmission' => 'Manual',
                'daily_rate' => 35.0,
                'image' => 'https://saucy-sedans-images.s3.eu-central-1.amazonaws.com/Mazda+3.jpg'
            ],
            [
                'name' => 'Chevrolet Cruze',
                'seats' => 5,
                'price' => 18.5,
                'transmission' => 'Manual',
                'daily_rate' => 32.0,
                'image' => 'https://saucy-sedans-images.s3.eu-central-1.amazonaws.com/Chevrolet+Cruze.jpg'
            ],
        ];

        foreach ($vehicleTypes as $vehicleType) {
            VehicleTypes::create($vehicleType);
        }
    }
}
