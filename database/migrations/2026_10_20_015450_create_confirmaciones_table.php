<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('confirmaciones_asistencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained()->cascadeOnDelete();
            $table->foreignId('miembro_id')->constrained()->cascadeOnDelete();
            $table->timestamp('fecha_confirmacion')->useCurrent();
            $table->enum('estado', ['confirmada', 'cancelada'])->default('confirmada');
            $table->timestamps();
            $table->unique(['evento_id', 'miembro_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('confirmaciones_asistencia');
    }
};