<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Finca;
use App\Models\Recepcion;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol'
    ];
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->rol)) {
                $user->rol = 'productor';
            }
        });
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }
    public function isEmpleado() : bool{
        return $this->rol === 'empleado';
    }
    public function isProductor() : bool{
        return $this->rol === 'productor';
    }

     public function fincas() {
        return $this->hasMany(Finca::class, 'productor_id');
    }

    public function recepciones() {
        return $this->hasMany(Recepcion::class, 'empleado_id');
    }

    public function recepcionesRealizadas() {
        return $this->hasMany(Recepcion::class, 'empleado_id');
    }

    public function recepcionesEntregadas() {
        return $this->hasMany(Recepcion::class, 'productor_id');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
