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
                'base_fare' => 3.00,      // ← modifié
                'per_km_rate' => 2.50,    // ← modifié
                'capacity' => 4,
            ]
        );

        VehicleType::updateOrCreate(
            ['name' => 'Van Luxe'],
            [
                'base_fare' => 5.00,      // ← modifié
                'per_km_rate' => 3.50,    // ← modifié
                'capacity' => 8,
            ]
        );

        VehicleType::updateOrCreate(
            ['name' => 'Sprinter Mercedes'],
            [
                'base_fare' => 7.00,      // ← modifié
                'per_km_rate' => 4.50,    // ← modifié
                'capacity' => 9,
            ]
        );
    }
}
