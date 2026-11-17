<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table): void {
            $table->text('cronograma')->nullable();
        });

        Schema::table('miembros', function (Blueprint $table): void {
            $table->integer('hijos')->nullable()->default(0)->change();
        });

        Schema::create('actas_evento', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos');
            $table->string('archivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actas_evento');

        Schema::table('miembros', function (Blueprint $table): void {
            $table->integer('hijos')->nullable()->default(null)->change();
        });

        Schema::table('eventos', function (Blueprint $table): void {
            $table->dropColumn('cronograma');
        });
    }
};
