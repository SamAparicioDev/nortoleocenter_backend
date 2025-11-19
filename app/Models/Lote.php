<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Finca;
use App\Models\Recepcion;

class Lote extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'area_m2',
        'finca_id',
    ];

    public function finca(){
        return $this->belongsTo(Finca::class);
    }

    public function recepciones(){
        return $this->hasMany(Recepcion::class);
    }
}
