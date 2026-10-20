<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('miembros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('estado_miembro', ['activo', 'inactivo', 'pendiente'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('miembros');
    }
};