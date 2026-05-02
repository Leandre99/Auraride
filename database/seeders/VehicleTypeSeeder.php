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
            ['name' => 'Atlas Volt'],
            [
                'base_fare' => 5.00,
                'per_km_rate' => 1.50,
                'capacity' => 4,
            ]
        );

        VehicleType::updateOrCreate(
            ['name' => 'Atlas Black'],
            [
                'base_fare' => 10.00,
                'per_km_rate' => 3.00,
                'capacity' => 4,
            ]
        );
    }
}
