<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Ciudad;


class Departamento extends Model
{
    use HasFactory;


    protected $fillable = [
        'nombre',
    ];

    public function ciudades() {
    return $this->hasMany(Ciudad::class);
}
}
