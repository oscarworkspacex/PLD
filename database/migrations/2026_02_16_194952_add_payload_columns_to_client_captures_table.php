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
        if (! Schema::hasColumn('client_captures', 'tipo_solicitud')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->string('tipo_solicitud')->nullable()->after('id');
            });
        }

        if (! Schema::hasColumn('client_captures', 'datos_identificacion')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->json('datos_identificacion')->nullable();
            });
        }

        if (! Schema::hasColumn('client_captures', 'datos_laborales')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->json('datos_laborales')->nullable()->after('datos_identificacion');
            });
        }

        if (! Schema::hasColumn('client_captures', 'solicitud_operacion')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->json('solicitud_operacion')->nullable()->after('datos_laborales');
            });
        }

        if (! Schema::hasColumn('client_captures', 'datos_contacto')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->json('datos_contacto')->nullable()->after('solicitud_operacion');
            });
        }

        if (! Schema::hasColumn('client_captures', 'pld')) {
            Schema::table('client_captures', function (Blueprint $table) {
                $table->json('pld')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = [
            'tipo_solicitud',
            'datos_identificacion',
            'datos_laborales',
            'solicitud_operacion',
            'datos_contacto',
            'pld',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('client_captures', $column)) {
                Schema::table('client_captures', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
