<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("alerta_lista_negras", function (Blueprint $table) {
            $table->id();
            $table->string("excel_name_origen");
            $table->text("valor_detectado");
            $table->json("row_data")->nullable();
            $table->string("nombre_lista");
            $table->boolean("is_leida")->default(false);
            $table->timestamp("leida_at")->nullable();
            $table->timestamps();
            $table->index("excel_name_origen");
            $table->index("is_leida");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("alerta_lista_negras");
    }
};
