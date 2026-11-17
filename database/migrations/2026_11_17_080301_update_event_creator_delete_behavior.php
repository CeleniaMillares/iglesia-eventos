<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table): void {
            $table->dropForeign(['user_creador_id']);
            $table->unsignedBigInteger('user_creador_id')->nullable()->change();
            $table->foreign('user_creador_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::table('eventos')->whereNull('user_creador_id')->exists()) {
            throw new \RuntimeException(
                'Cannot restore the non-null event creator foreign key while events have no creator.'
            );
        }

        Schema::table('eventos', function (Blueprint $table): void {
            $table->dropForeign(['user_creador_id']);
            $table->unsignedBigInteger('user_creador_id')->nullable(false)->change();
            $table->foreign('user_creador_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
