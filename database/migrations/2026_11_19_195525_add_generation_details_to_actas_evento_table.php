<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actas_evento', function (Blueprint $table): void {
            $table->foreignId('user_id')
                ->nullable()
                ->after('evento_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->string('archivo_pdf')->nullable()->after('archivo');
            $table->timestamp('fecha_generacion')->useCurrent()->after('archivo_pdf');
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::table('actas_evento', function (Blueprint $table) use ($driver): void {
            $columnas = ['archivo_pdf', 'fecha_generacion'];

            if ($driver === 'sqlite') {
                $columnas[] = 'user_id';
            } else {
                $table->dropConstrainedForeignId('user_id');
            }

            $table->dropColumn($columnas);
        });
    }
};
