<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recepciones', function (Blueprint $table) {
            $table->foreignId('envio_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('peso_recibido_kg')->nullable();
            $table->dropForeign(['lote_id']);
            $table->dropForeign(['productor_id']);
            $table->dropColumn(['peso_bruto', 'peso_neto', 'productor_id', 'lote_id']);

        });
    }

    public function down(): void
    {
        Schema::table('recepciones', function (Blueprint $table) {
            $table->dropConstrainedForeignId('envio_id');
        });
    }
};
