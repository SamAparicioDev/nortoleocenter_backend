<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Departamento;
use App\Models\Finca;

class Ciudad extends Model
{
    use HasFactory;

    protected $table = "ciudades";

    protected $fillable = [
        'nombre',
        'departamento_id',
    ];

    public function departamento() {
    return $this->belongsTo(Departamento::class);
}

    public function fincas() {
        return $this->hasMany(Finca::class);
    }
}
