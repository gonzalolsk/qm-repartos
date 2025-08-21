<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    // Crear vehículos de prueba
    Vehicle::create([
        'patente' => 'ABC123',
        'modelo' => 'Ford Transit'
    ]);

    Vehicle::create([
        'patente' => 'XYZ789',
        'modelo' => 'Mercedes Sprinter'
    ]);

    Vehicle::create([
        'patente' => 'DEF456',
        'modelo' => 'Iveco Daily'
    ]);
    }
}
