<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\VehicleType::create([
            'name' => 'Aura Volt',
            'base_fare' => 5.00,
            'per_km_rate' => 1.50,
            'capacity' => 4,
        ]);

        \App\Models\VehicleType::create([
            'name' => 'Aura Black',
            'base_fare' => 10.00,
            'per_km_rate' => 3.00,
            'capacity' => 4,
        ]);
    }
}
