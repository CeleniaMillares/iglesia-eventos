<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Miembro extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'telefono',
        'direccion',
        'fecha_nacimiento',
        'estado_miembro',
        'tipo_sangre',
        'numero_referencia',
        'foto',
        'contacto_emergencia',
        'telefono_emergencia',
        'estado_civil',
        'conyuge',
        'hijos',
        'profesion',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function confirmaciones()
    {
        return $this->hasMany(ConfirmacionAsistencia::class);
    }
}