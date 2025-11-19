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
        Schema::create('recepciones', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('lote_id')->constrained()->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('productor_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_recepcion');
            $table->float('peso_bruto');
            $table->float('peso_neto');
            $table->float('precio_kg');
            $table->float('total');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('recepciones');
    }
};
