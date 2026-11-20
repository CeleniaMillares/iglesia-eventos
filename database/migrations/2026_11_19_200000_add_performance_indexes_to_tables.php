<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->ensureIndex('eventos', 'estado');
        $this->ensureIndex('eventos', 'fecha_inicio');
        $this->ensureIndex('eventos', 'user_creador_id');
        $this->ensureIndex('confirmaciones_asistencia', 'miembro_id');
        $this->ensureIndex('confirmaciones_asistencia', 'evento_id');
        $this->ensureIndex('miembros', 'user_id');
    }

    public function down(): void
    {
        $this->dropIndex('eventos', 'estado');
        $this->dropIndex('eventos', 'fecha_inicio');
        $this->dropIndex('eventos', 'user_creador_id');
        $this->dropIndex('confirmaciones_asistencia', 'miembro_id');
        $this->dropIndex('confirmaciones_asistencia', 'evento_id');
        $this->dropIndex('miembros', 'user_id');
    }

    private function ensureIndex(string $table, string $column): void
    {
        $index = $this->indexName($table, $column);

        if (Schema::hasIndex($table, [$column]) || Schema::hasIndex($table, $index)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($column, $index): void {
            $table->index($column, $index);
        });
    }

    private function dropIndex(string $table, string $column): void
    {
        $index = $this->indexName($table, $column);

        if (! Schema::hasIndex($table, $index)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($index): void {
            $table->dropIndex($index);
        });
    }

    private function indexName(string $table, string $column): string
    {
        return $table.'_'.$column.'_index';
    }
};
