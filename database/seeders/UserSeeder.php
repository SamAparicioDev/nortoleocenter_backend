<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario admin
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador del Sistema',
                'password' => Hash::make('12345678'),
                'rol' => 'admin',
            ]
        );

        // Usuario empleado
        User::updateOrCreate(
            ['email' => 'empleado@nortoleocenter.com'],
            [
                'name' => 'Empleado Nortoleocenter',
                'password' => Hash::make('12345678'),
                'rol' => 'empleado',
            ]
        );

        // Usuario productor
        User::updateOrCreate(
            ['email' => 'productor@finca.com'],
            [
                'name' => 'Productor Ejemplo',
                'password' => Hash::make('12345678'),
                'rol' => 'productor',
            ]
        );
    }
}
