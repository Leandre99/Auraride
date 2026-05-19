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

    public function run(): void
    {
        // Seed Vehicle Types first
        $this->call(VehicleTypeSeeder::class);

        // Create Client
        User::updateOrCreate(
            ['email' => 'client@atlasandco.com'],
            [
                'name' => 'John Client',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sophie@atlasandco.com'],
            [
                'name' => 'Sophie Client',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        // Create Driver 1
        $driver1 = User::updateOrCreate(
            ['email' => 'driver@atlasandco.com'],
            [
                'name' => 'Michael Driver',
                'password' => Hash::make('password'),
                'role' => 'driver',
                'phone_number' => '+229 90 00 00 01',
                'is_approved' => true,
                'is_active' => true,
            ]
        );

        Vehicle::updateOrCreate(
            ['user_id' => $driver1->id],
            [
                'vehicle_type_id' => 1,
                'model' => 'Tesla Model S',
                'plate_number' => 'ATLAS 1',
                'color' => 'Noir Profond',
            ]
        );

        // Create Driver 2
        $driver2 = User::updateOrCreate(
            ['email' => 'pierre@atlasandco.com'],
            [
                'name' => 'Pierre Chauffeur',
                'password' => Hash::make('password'),
                'role' => 'driver',
                'phone_number' => '+229 90 00 00 02',
                'is_approved' => true,
                'is_active' => true,
            ]
        );

        Vehicle::updateOrCreate(
            ['user_id' => $driver2->id],
            [
                'vehicle_type_id' => 2, // Van
                'model' => 'Mercedes Classe V',
                'plate_number' => 'ATLAS 2',
                'color' => 'Gris Sidéral',
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@atlasandco.com'],
            [
                'name' => 'Atlas Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone_number' => '0149877981',
            ]
        );
    }
}
