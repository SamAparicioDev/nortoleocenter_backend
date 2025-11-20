<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

            // Calcular total de dinero
            $precio = floatval($recepcion->precio_kg);
            $pesoRecibido = floatval($recepcion->peso_recibido_kg);

            if ($precio > 0 && $pesoRecibido > 0) {
                $recepcion->total = $precio * $pesoRecibido;
            }

            // Calcular kg perdidos
            if ($recepcion->envio) {
                // EL FIX AQUÍ ↓
                $pesoOriginal = floatval($recepcion->envio->peso_kg);

                $recepcion->total_kg_perdidos = max($pesoOriginal - $pesoRecibido, 0);
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
