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
         Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('finca_id')->constrained()->onDelete('cascade');
            $table->foreignId('lote_id')->nullable()->constrained()->onDelete('set null');
            $table->string('codigo_envio')->unique();
            $table->date('fecha_envio');
            $table->enum('estado',['pendiente','enviado','recibido'])->default('pendiente');
            $table->decimal('peso_kg');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            //
        });
    }
};
