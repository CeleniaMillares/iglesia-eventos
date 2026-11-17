<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('eventos')->whereNull('objetivos')->exists()) {
            throw new RuntimeException(
                'Cannot make eventos.objetivos non-nullable while null values exist.'
            );
        }

        Schema::table('eventos', function (Blueprint $table) {
            $table->text('objetivos')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->text('objetivos')->nullable()->change();
        });
    }
};
