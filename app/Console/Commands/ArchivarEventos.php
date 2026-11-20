<?php

namespace App\Console\Commands;

use App\Models\Evento;
use Illuminate\Console\Command;

class ArchivarEventos extends Command
{
    protected $signature = 'eventos:archivar';

    protected $description = 'Archiva automáticamente los eventos finalizados o aprobados cuya fecha ya pasó';

    protected $aliases = ['app:archivar-eventos'];

    public function handle(): int
    {
        $actualizados = Evento::query()
            ->whereIn('estado', Evento::ESTADOS_ARCHIVABLES)
            ->where(function ($query): void {
                $query->whereDate('fecha_inicio', '<', today())
                    ->orWhereDate('fecha_fin', '<', today());
            })
            ->update(['estado' => 'archivado']);

        $this->info('Eventos archivados: '.$actualizados);

        return self::SUCCESS;
    }
}
