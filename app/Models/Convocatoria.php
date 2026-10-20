<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    protected $fillable = ['evento_id', 'user_id', 'asunto', 'mensaje', 'fecha_envio'];

    // AGREGA ESTO:
    protected $casts = [
        'fecha_envio' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}