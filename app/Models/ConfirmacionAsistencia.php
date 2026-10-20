<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmacionAsistencia extends Model
{
    use HasFactory;

    protected $table = 'confirmaciones_asistencia';

    protected $fillable = [
        'evento_id', 'miembro_id', 'estado', 'fecha_confirmacion'
    ];

    protected $casts = [
        'fecha_confirmacion' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function miembro()
    {
        return $this->belongsTo(Miembro::class);
    }
}