<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Finca;
use App\Models\Lote;
use App\Models\Recepcion;
use Illuminate\Support\Str;

class Envio extends Model
{
    use HasFactory;

    protected $table = 'envios';

    protected $fillable = [
        'codigo_envio',
        'productor_id',
        'finca_id',
        'lote_id',
        'peso_kg',
        'observaciones',
        'fecha_envio',
        'estado',
    ];

    protected static function booted()
    {
        static::creating(function ($envio) {
            // Generar código si no fue asignado por el controlador
            if (empty($envio->codigo_envio)) {
                $envio->codigo_envio = 'ENV-'.strtoupper(Str::random(8));
            }

            // Estado por defecto
            if (empty($envio->estado)) {
                $envio->estado = 'pendiente';
            }

            // Fecha por defecto
            if (empty($envio->fecha_envio)) {
                $envio->fecha_envio = now();
            }
        });
    }

    public function productor()
    {
        return $this->belongsTo(User::class, 'productor_id');
    }

    public function recepcion()
    {
        return $this->hasOne(Recepcion::class);
    }

    public function finca()
    {
        return $this->belongsTo(Finca::class, 'finca_id');
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }

    protected $casts = [
        'fecha_envio' => 'datetime:Y-m-d',
    ];
}
