<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'hora', 
        'lugar', 'objetivos', 'estado', 'motivo_rechazo', 
        'capacidad_maxima', 'user_creador_id', 'user_aprobador_id'
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'hora' => 'datetime:H:i',
        ];
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'user_creador_id');
    }

    public function confirmaciones()
    {
        return $this->hasMany(ConfirmacionAsistencia::class);
    }

    public function tieneCupo(): bool
    {
        if ($this->capacidad_maxima === 0) return true;
        return $this->confirmaciones()->where('estado', 'confirmada')->count() < $this->capacidad_maxima;
    }
}