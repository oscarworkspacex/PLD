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
        if (! Schema::hasColumn('client_captures', 'anexo_path')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->string('anexo_path')->nullable();
            });
        }

        if (! Schema::hasColumn('client_captures', 'anexo_nombre')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->string('anexo_nombre')->nullable()->after('anexo_path');
            });
        }

        if (! Schema::hasColumn('client_captures', 'cliente_validado')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->string('cliente_validado', 2)->nullable()->after('anexo_nombre');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = ['cliente_validado', 'anexo_nombre', 'anexo_path'];

        foreach ($columns as $column) {
            if (Schema::hasColumn('client_captures', $column)) {
                Schema::table('client_captures', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
