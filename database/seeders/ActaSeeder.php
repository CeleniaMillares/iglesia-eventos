<?php

namespace Database\Seeders;

use App\Models\ActaEvento;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class ActaSeeder extends Seeder
{
    public function run(): void
    {
        $secretaria = User::whereHas('roles', fn ($query) => $query->where('nombre', 'Secretaría'))->first()
            ?? User::where('email', 'secretaria@gmail.com')->first();

        if (! $secretaria instanceof User) {
            throw new RuntimeException('No se encontró el usuario Secretaría (secretaria@gmail.com).');
        }

        foreach (Evento::where('estado', 'finalizado')->orderBy('fecha_inicio')->get() as $evento) {
            ActaEvento::updateOrCreate(
                ['evento_id' => $evento->id],
                [
                    'user_id' => $secretaria->id,
                    'archivo' => 'actas/acta-'.$evento->id.'.docx',
                    'archivo_pdf' => 'actas/acta-'.$evento->id.'.pdf',
                    'fecha_generacion' => $evento->fecha_inicio
                        ->copy()
                        ->addDay()
                        ->setTime(18, 0),
                ]
            );
        }
    }
}
