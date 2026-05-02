<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleType::updateOrCreate(
            ['name' => 'Berline Standard'],
            [
                'base_fare' => 0.00,
                'per_km_rate' => 1.50,
                'capacity' => 4,
            ]
        );

        VehicleType::updateOrCreate(
            ['name' => 'Van Luxe'],
            [
                'base_fare' => 0.00,
                'per_km_rate' => 2.50,
                'capacity' => 8,
            ]
        );

        VehicleType::updateOrCreate(
            ['name' => 'Sprinter Mercedes'],
            [
                'base_fare' => 0.00,
                'per_km_rate' => 4.00,
                'capacity' => 9,
            ]
        );
    }
}
