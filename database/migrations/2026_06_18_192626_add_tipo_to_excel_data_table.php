<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("excel_data", function (Blueprint $table) {
            $table->enum("tipo", ["datos", "lista_negra"])->default("datos")->after("excel_name");
        });
    }

    public function down(): void
    {
        Schema::table("excel_data", function (Blueprint $table) {
            $table->dropColumn("tipo");
        });
    }
};
