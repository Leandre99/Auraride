<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        User::create([
            'name' => 'John Client',
            'email' => 'client@auraride.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'client',
        ]);

        // Create Driver
        $driver = User::create([
            'name' => 'Michael Driver',
            'email' => 'driver@auraride.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'driver',
        ]);

        // Create Vehicle for the Driver
        \App\Models\Vehicle::create([
            'user_id' => $driver->id,
            'vehicle_type_id' => 1, // Aura Volt
            'model' => 'Tesla Model S',
            'plate_number' => 'NXT GEN',
            'color' => 'Neon Cyan',
        ]);

        // Create Admin
        User::create([
            'name' => 'Aura Admin',
            'email' => 'admin@auraride.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
