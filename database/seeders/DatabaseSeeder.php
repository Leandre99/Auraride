<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Vehicle Types first
        $this->call(VehicleTypeSeeder::class);

        // Create Client
        User::updateOrCreate(
            ['email' => 'client@auraride.com'],
            [
                'name' => 'John Client',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        // Create Driver
        $driver = User::updateOrCreate(
            ['email' => 'driver@auraride.com'],
            [
                'name' => 'Michael Driver',
                'password' => Hash::make('password'),
                'role' => 'driver',
            ]
        );

        // Create Vehicle for the Driver
        Vehicle::updateOrCreate(
            ['user_id' => $driver->id],
            [
                'vehicle_type_id' => 1, // Aura Volt
                'model' => 'Tesla Model S',
                'plate_number' => 'NXT GEN',
                'color' => 'Neon Cyan',
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@auraride.com'],
            [
                'name' => 'Aura Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
