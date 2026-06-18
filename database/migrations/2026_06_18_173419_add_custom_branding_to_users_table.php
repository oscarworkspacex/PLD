<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->string("custom_logo")->nullable()->after("status");
            $table->string("custom_company_name")->nullable()->after("custom_logo");
        });
    }

    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn(["custom_logo", "custom_company_name"]);
        });
    }
};
