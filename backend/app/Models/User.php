<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    public const CREATED_AT = 'fecha_registro';
    public const UPDATED_AT = 'fecha_ultima_modificacion';


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'estado'
    ];

   
      protected $casts = [
        'fecha_registro' => 'datetime',
        'fecha_ultima_modificacion' => 'datetime',
    ];

    // Scope para usuarios activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }
    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }

   
    
}
