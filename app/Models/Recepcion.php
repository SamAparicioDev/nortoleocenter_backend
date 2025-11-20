<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Envio;

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

        // Calcular total (ya lo tenías)
        $precio = floatval($recepcion->precio_kg);
        $peso = floatval($recepcion->peso_recibido_kg);

        if ($precio > 0 && $peso > 0) {
            $recepcion->total = $precio * $peso;
        }

        // Calcular total_kg_perdidos automáticamente
        if (!empty($recepcion->envio_id) && !empty($recepcion->peso_recibido_kg)) {
            $envio = Envio::find($recepcion->envio_id);

            if ($envio) {
                $recepcion->total_kg_perdidos = $envio->peso - $recepcion->peso_recibido_kg;
            }
        }

        // Fecha por defecto
        if (empty($recepcion->fecha_recepcion)) {
            $recepcion->fecha_recepcion = Carbon::now();
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
