<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("excel_data", function (Blueprint $table) {
            $table->json("row_data")->nullable()->after("value");
            $table->index("excel_name");
        });
    }

    public function down(): void
    {
        Schema::table("excel_data", function (Blueprint $table) {
            $table->dropColumn("row_data");
            $table->dropIndex(["excel_name"]);
        });
    }
};
