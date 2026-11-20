<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table): void {
            $table->id();
            $table->string('clave')->unique();
            $table->text('valor');
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });

        $ahora = now();

        DB::table('configuraciones')->insert([
            [
                'clave' => 'nombre_iglesia',
                'valor' => 'Iglesia Asamblea de Dios Boliviana',
                'descripcion' => 'Nombre oficial de la iglesia',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'clave' => 'correo_contacto',
                'valor' => 'contacto@iglesia.com',
                'descripcion' => 'Correo de contacto de la iglesia',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'clave' => 'limite_eventos_por_pagina',
                'valor' => '15',
                'descripcion' => 'Límite de eventos por página',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
