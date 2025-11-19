<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('envios', function (Blueprint $table) {

            // Ejemplo: agregar o modificar columnas
            if (!Schema::hasColumn('envios', 'fecha_envio')) {
                $table->datetime('fecha_envio')->change();
            }

            // Agrega aquí los cambios que realmente necesites
            // ejemplo: $table->float('nuevo_campo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {

            // Revertir los cambios hechos en up()
            // ejemplo si agregaste una columna: $table->dropColumn('nuevo_campo');

        });
    }
};
