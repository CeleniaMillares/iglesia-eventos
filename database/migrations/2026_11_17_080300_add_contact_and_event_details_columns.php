<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('direccion')->nullable();
            $table->timestamp('ultimo_acceso')->nullable();
            $table->string('foto')->nullable();
        });

        Schema::table('miembros', function (Blueprint $table): void {
            $table->string('tipo_sangre')->nullable();
            $table->string('numero_referencia')->nullable()->unique();
            $table->string('foto')->nullable();
            $table->string('contacto_emergencia')->nullable();
            $table->string('telefono_emergencia')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('conyuge')->nullable();
            $table->integer('hijos')->nullable();
            $table->string('profesion')->nullable();
        });

        Schema::table('eventos', function (Blueprint $table): void {
            $table->integer('duracion_dias')->nullable();
            $table->string('tipo_evento')->nullable();
            $table->foreignId('responsable_principal_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table): void {
            $table->dropForeign(['responsable_principal_id']);
            $table->dropColumn(['duracion_dias', 'tipo_evento', 'responsable_principal_id']);
        });

        Schema::table('miembros', function (Blueprint $table): void {
            $table->dropUnique(['numero_referencia']);
            $table->dropColumn([
                'tipo_sangre',
                'numero_referencia',
                'foto',
                'contacto_emergencia',
                'telefono_emergencia',
                'estado_civil',
                'conyuge',
                'hijos',
                'profesion',
            ]);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['telefono', 'celular', 'direccion', 'ultimo_acceso', 'foto']);
        });
    }
};
