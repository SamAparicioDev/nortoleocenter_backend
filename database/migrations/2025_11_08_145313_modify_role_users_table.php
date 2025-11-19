<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones para modificar la columna 'rol'.
     */
    public function up(): void
    {
        // ⚠️ IMPORTANTE: Necesitas instalar 'doctrine/dbal' si no lo tienes: composer require doctrine/dbal
        Schema::table('users', function (Blueprint $table) {
            // Modifica la columna 'rol' para asegurar que el ENUM es ['admin', 'empleado', 'productor']
            $table->enum('rol', ['admin', 'empleado', 'productor'])
                  ->default('productor')
                  ->change();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revierte el ENUM a una versión anterior (ajusta los valores si eran diferentes)
            $table->enum('rol', ['admin', 'empleado'])
                  ->default('empleado') // Valor por defecto anterior
                  ->change();
        });
    }
};
