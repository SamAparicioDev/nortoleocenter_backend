<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Envio;
use Illuminate\Support\Facades\Log;

class Recepcion extends Model
{
    use HasFactory;

    protected $table = 'recepciones';

    protected $fillable = [
        'envio_id',
        'precio_kg',
        'total',
        'fecha_recepcion',
        'empleado_id',
        'peso_recibido_kg',
        'total_kg_perdidos',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($recepcion) {

            // Cast seguros
            $precio = floatval($recepcion->precio_kg);
            $pesoRecibido = floatval($recepcion->peso_recibido_kg);

            // Calcular total monetario si hay datos válidos
            if ($precio > 0 && $pesoRecibido > 0) {
                $recepcion->total = $precio * $pesoRecibido;
            }

            // Intentar obtener el envío de forma segura usando el envio_id
            $pesoOriginal = 0;

            if (!empty($recepcion->envio_id)) {
                // Buscamos el modelo Envio directamente (esto funciona aunque la relación no esté cargada)
                $envio = Envio::find($recepcion->envio_id);

                if ($envio) {
                    // Asegúrate que el campo real del peso es peso_kg (según tu Envio model)
                    $pesoOriginal = floatval($envio->peso_kg);
                } else {
                    // Log para debugging si no existe el envio
                    Log::warning("Recepcion: envio_id {$recepcion->envio_id} no encontrado al salvar Recepcion.");
                }
            } else {
                Log::warning('Recepcion: envio_id vacío al salvar Recepcion.', ['recepcion' => $recepcion->toArray()]);
            }

            // Calcular kg perdidos (no negativos)
            if ($pesoOriginal > 0) {
                $recepcion->total_kg_perdidos = max($pesoOriginal - $pesoRecibido, 0);
            } else {
                // Si no hay peso original conocido, dejar en null o 0 según prefieras.
                $recepcion->total_kg_perdidos = 0;
            }

            // Fecha recepción por defecto
            if (empty($recepcion->fecha_recepcion)) {
                $recepcion->fecha_recepcion = now();
            }
        });
    }

    public function empleado()
    {
        return $this->belongsTo(User::class, 'empleado_id');
    }

    public function envio()
    {
        return $this->belongsTo(Envio::class, 'envio_id');
    }
}
