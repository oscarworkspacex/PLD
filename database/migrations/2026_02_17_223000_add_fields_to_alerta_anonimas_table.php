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
            if (! Schema::hasColumn('alerta_anonimas', 'destinatario')) {
                $table->string('destinatario')->nullable()->after('id');
            }

            if (! Schema::hasColumn('alerta_anonimas', 'asunto')) {
                $table->string('asunto')->nullable()->after('destinatario');
            }

            if (! Schema::hasColumn('alerta_anonimas', 'empleado_reportado')) {
                $table->string('empleado_reportado')->nullable()->after('asunto');
            }

            if (! Schema::hasColumn('alerta_anonimas', 'motivo')) {
                $table->string('motivo')->nullable()->after('empleado_reportado');
            }

            if (! Schema::hasColumn('alerta_anonimas', 'detalle')) {
                $table->text('detalle')->nullable()->after('motivo');
            }

            if (! Schema::hasColumn('alerta_anonimas', 'denuncia')) {
                $table->text('denuncia')->nullable()->after('detalle');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerta_anonimas', function (Blueprint $table) {
            $columns = ['destinatario', 'asunto', 'empleado_reportado', 'motivo', 'detalle', 'denuncia'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('alerta_anonimas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
