<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_capture_anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_capture_id')->constrained('client_captures')->cascadeOnDelete();
            $table->string('path');
            $table->string('nombre_original');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->timestamps();
        });

        // Migra anexos históricos del esquema anterior (1 archivo por cliente).
        if (Schema::hasTable('client_captures') && Schema::hasColumn('client_captures', 'anexo_path')) {
            $clientsWithAnexo = DB::table('client_captures')
                ->select(['id', 'anexo_path', 'anexo_nombre', 'created_at', 'updated_at'])
                ->whereNotNull('anexo_path')
                ->get();

            foreach ($clientsWithAnexo as $client) {
                DB::table('client_capture_anexos')->insert([
                    'client_capture_id' => $client->id,
                    'path' => (string) $client->anexo_path,
                    'nombre_original' => (string) ($client->anexo_nombre ?: basename((string) $client->anexo_path)),
                    'mime_type' => null,
                    'size_bytes' => null,
                    'created_at' => $client->created_at ?? now(),
                    'updated_at' => $client->updated_at ?? now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_capture_anexos');
    }
};
