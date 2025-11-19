<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Ciudad;
use App\Models\User;
use App\Models\Lote;

class Finca extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad_id',
        'productor_id'
    ];

    public function ciudad(){
        return $this->belongsTo(Ciudad::class);
    }

    public function productor(){
        return $this->belongsTo(User::class, 'productor_id');
    }

    public function lotes(){
        return $this->hasMany(Lote::class);
    }
}
