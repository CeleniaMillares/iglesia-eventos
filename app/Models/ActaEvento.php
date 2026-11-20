<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActaEvento extends Model
{
    protected $table = 'actas_evento';

    protected $fillable = [
        'evento_id',
        'user_id',
        'archivo',
        'archivo_pdf',
        'fecha_generacion',
    ];

    protected $casts = [
        'fecha_generacion' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function generadoPor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->generadoPor();
    }
}
