<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear clientes de prueba
        Client::create([
            'codigo' => 'CLI001',
            'razon_social' => 'Empresa ABC S.A.',
            'email' => 'contacto@empresaabc.com',
            'direccion' => 'Av. Corrientes 1234, CABA',
            'latitud' => -34.6037,
            'longitud' => -58.3816
        ]);

        Client::create([
            'codigo' => 'CLI002',
            'razon_social' => 'Comercio XYZ Ltda.',
            'email' => 'info@comercioxyz.com',
            'direccion' => 'Calle Florida 567, CABA',
            'latitud' => -34.6084,
            'longitud' => -58.3781
        ]);

        Client::create([
            'codigo' => 'CLI003',
            'razon_social' => 'Distribuidora 123',
            'email' => 'ventas@distribuidora123.com',
            'direccion' => 'Av. Santa Fe 890, CABA',
            'latitud' => -34.5890,
            'longitud' => -58.3920
        ]);

    }
}
