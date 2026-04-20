<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alerta_anonimas', function (Blueprint $table) {
            if (! Schema::hasColumn('alerta_anonimas', 'is_leida')) {
                $table->boolean('is_leida')->default(false)->after('denuncia');
            }

            if (! Schema::hasColumn('alerta_anonimas', 'leida_at')) {
                $table->timestamp('leida_at')->nullable()->after('is_leida');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerta_anonimas', function (Blueprint $table) {
            if (Schema::hasColumn('alerta_anonimas', 'leida_at')) {
                $table->dropColumn('leida_at');
            }

            if (Schema::hasColumn('alerta_anonimas', 'is_leida')) {
                $table->dropColumn('is_leida');
            }
        });
    }
};
