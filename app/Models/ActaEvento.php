<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActaEvento extends Model
{
    protected $table = 'actas_evento';

    protected $fillable = [
        'evento_id',
        'archivo',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
