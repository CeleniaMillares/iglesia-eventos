<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerarBackup extends Command
{
    protected $signature = 'backup:generar {--nombre= : Nombre base del archivo de backup}';

    protected $description = 'Genera un backup diario de la base de datos en storage/app/backups';

    protected $aliases = ['app:generar-backup'];

    public function handle(): int
    {
        $nombreBase = $this->option('nombre') ?: 'backup_'.now()->format('Ymd_His');
        $nombreSeguro = Str::slug((string) $nombreBase) ?: 'backup_'.now()->format('Ymd_His');
        $ruta = $nombreSeguro.'.sql';

        $conexion = (string) config('database.default');

        if ($conexion === 'pgsql') {
            $config = config('database.connections.pgsql');
            $comando = sprintf(
                'pg_dump --host=%s --port=%s --username=%s --no-owner --no-privileges --format=plain --file=%s %s',
                escapeshellarg((string) ($config['host'] ?? '127.0.0.1')),
                escapeshellarg((string) ($config['port'] ?? '5432')),
                escapeshellarg((string) ($config['username'] ?? 'postgres')),
                escapeshellarg(Storage::disk('backups')->path($ruta)),
                escapeshellarg((string) ($config['database'] ?? 'laravel'))
            );

            $resultado = Process::timeout(300)
                ->env(['PGPASSWORD' => (string) ($config['password'] ?? '')])
                ->run($comando);

            if ($resultado->successful()) {
                $this->info('Backup generado correctamente: storage/app/backups/'.$ruta);

                return self::SUCCESS;
            }

            $this->warn('pg_dump no está disponible; se generará el backup con el exportador nativo de Laravel.');
        }

        Storage::disk('backups')->put($ruta, $this->volcarBaseDatos($conexion));

        $this->info('Backup generado correctamente: storage/app/backups/'.$ruta);

        return self::SUCCESS;
    }

    private function volcarBaseDatos(string $conexion): string
    {
        $sql = '-- Backup generado por Laravel'.PHP_EOL.'-- Fecha: '.now()->toDateTimeString().PHP_EOL.PHP_EOL;

        foreach ($this->tablas($conexion) as $tabla) {
            $sql .= $this->estructuraTabla($conexion, $tabla);

            foreach (DB::connection($conexion)->table($tabla)->get() as $fila) {
                $columnas = [];
                $valores = [];

                foreach ((array) $fila as $columna => $valor) {
                    $columnas[] = '"'.str_replace('"', '""', (string) $columna).'"';
                    $valores[] = $this->valorSql($conexion, $valor);
                }

                $sql .= 'INSERT INTO "'.str_replace('"', '""', $tabla).'" ('
                    .implode(', ', $columnas).') VALUES ('.implode(', ', $valores).');'.PHP_EOL;
            }

            $sql .= PHP_EOL;
        }

        return $sql;
    }

    private function tablas(string $conexion): array
    {
        $conexionDb = DB::connection($conexion);

        if ($conexion === 'sqlite') {
            return collect($conexionDb->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name"))
                ->pluck('name')
                ->filter()
                ->all();
        }

        if ($conexion === 'pgsql') {
            return collect($conexionDb->select(
                "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE' ORDER BY table_name"
            ))
                ->pluck('table_name')
                ->filter()
                ->all();
        }

        return collect($conexionDb->select('SHOW TABLES'))
            ->map(fn (object $fila): string => (string) (array_values((array) $fila)[0] ?? ''))
            ->filter()
            ->all();
    }

    private function estructuraTabla(string $conexion, string $tabla): string
    {
        if ($conexion !== 'sqlite') {
            return '';
        }

        $resultado = DB::connection($conexion)
            ->select("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ?", [$tabla]);

        $sql = $resultado[0]->sql ?? null;

        return $sql ? $sql.';'.PHP_EOL.PHP_EOL : '';
    }

    private function valorSql(string $conexion, mixed $valor): string
    {
        if ($valor === null) {
            return 'NULL';
        }

        if (is_bool($valor)) {
            return $valor ? '1' : '0';
        }

        if (is_int($valor) || is_float($valor)) {
            return (string) $valor;
        }

        return DB::connection($conexion)->getPdo()->quote((string) $valor);
    }
}
