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
        if (! Schema::hasColumn('client_captures', 'estatus_prestamo')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->string('estatus_prestamo', 20)
                    ->default('PENDIENTE')
                    ->after('cliente_validado');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('client_captures', 'estatus_prestamo')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->dropColumn('estatus_prestamo');
            });
        }
    }
};
