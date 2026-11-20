<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    public const ESTADOS_CONFIRMABLES = ['aprobado', 'planificado'];

    public const ESTADOS_ARCHIVABLES = ['finalizado', 'aprobado'];

    public const ESTADO_ARCHIVADO = 'archivado';

    protected $fillable = [
        'nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'hora', 
        'lugar', 'objetivos', 'estado', 'motivo_rechazo', 
        'capacidad_maxima', 'user_creador_id', 'user_aprobador_id',
        'duracion_dias', 'tipo_evento', 'responsable_principal_id', 'cronograma'
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

    public function responsablePrincipal()
    {
        return $this->belongsTo(User::class, 'responsable_principal_id');
    }

    public function confirmaciones()
    {
        return $this->hasMany(ConfirmacionAsistencia::class);
    }

    public function convocatorias()
    {
        return $this->hasMany(Convocatoria::class);
    }

    public function actas()
    {
        return $this->hasMany(ActaEvento::class);
    }

    public function tieneCupo(): bool
    {
        if ($this->capacidad_maxima === 0) return true;
        return $this->confirmaciones()->where('estado', 'confirmada')->count() < $this->capacidad_maxima;
    }

    public function estaConfirmadoPor(User $user): bool
    {
        $miembro = $user->miembro;

        if (!$miembro) {
            return false;
        }

        return $this->confirmaciones()
            ->where('miembro_id', $miembro->id)
            ->where('estado', 'confirmada')
            ->exists();
    }
}