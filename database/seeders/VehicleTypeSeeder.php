<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleTypes = [
            [
                'name'        => 'Limousine',
                'icon'        => 'icons/limousine.svg',
                'base_fare'   => 50.00,
                'seats'       => 6,
            ],
            [
                'name'        => 'Luxury',
                'icon'        => 'icons/luxury-car.svg',
                'base_fare'   => 35.00,
                'seats'       => 4,
            ],
            [
                'name'        => 'ElectricCar',
                'icon'        => 'icons/ev-car.svg',
                'base_fare'   => 18.00,
                'seats'       => 4,
            ],
            [
                'name'        => 'Bike',
                'icon'        => 'icons/motorcycle.svg',
                'base_fare'   => 8.00,
                'seats'       => 1,
            ],
            [
                'name'        => 'Taxi 4 seat',
                'icon'        => 'icons/taxi-4.svg',
                'base_fare'   => 12.00,
                'seats'       => 4,
            ],
            [
                'name'        => 'Taxi 7 seat',
                'icon'        => 'icons/taxi-7.svg',
                'base_fare'   => 18.00,
                'seats'       => 7,
            ],
        ];

        foreach ($vehicleTypes as $vehicleType) {
            VehicleType::create($vehicleType);
        }
    }
}
