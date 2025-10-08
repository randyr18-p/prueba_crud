<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar claves foráneas para truncar sin errores
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = [
            [
                'nombres'   => 'Juan Carlos',
                'apellidos' => 'Pérez García',
                'email'     => 'juan.perez@example.com',
                'telefono'  => '+57 300 123 4567',
                'estado'    => 'activo',
            ],
            [
                'nombres'   => 'María Fernanda',
                'apellidos' => 'González López',
                'email'     => 'maria.gonzalez@example.com',
                'telefono'  => '+57 301 234 5678',
                'estado'    => 'activo',
            ],
            [
                'nombres'   => 'Pedro Antonio',
                'apellidos' => 'Martínez Rodríguez',
                'email'     => 'pedro.martinez@example.com',
                'telefono'  => '+57 302 345 6789',
                'estado'    => 'activo',
            ],
            [
                'nombres'   => 'Ana Lucía',
                'apellidos' => 'Hernández Silva',
                'email'     => 'ana.hernandez@example.com',
                'telefono'  => '+57 303 456 7890',
                'estado'    => 'activo',
            ],
            [
                'nombres'   => 'Carlos Eduardo',
                'apellidos' => 'Ramírez Torres',
                'email'     => 'carlos.ramirez@example.com',
                'telefono'  => '+57 304 567 8901',
                'estado'    => 'activo',
            ],
        ];

        // Inserción masiva para mejor rendimiento
        User::insert($users);
    }
}
