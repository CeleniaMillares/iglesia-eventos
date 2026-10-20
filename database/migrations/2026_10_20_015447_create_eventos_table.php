<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->time('hora')->nullable();
            $table->string('lugar', 100);
            $table->text('objetivos')->nullable();
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'planificado', 'finalizado'])->default('pendiente');
            $table->text('motivo_rechazo')->nullable();
            $table->integer('capacidad_maxima')->default(0);
            $table->foreignId('user_creador_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_aprobador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};