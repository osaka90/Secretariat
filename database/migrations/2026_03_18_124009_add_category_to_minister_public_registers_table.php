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
        Schema::table('minister_public_registers', function (Blueprint $table) {
            $table->string('category')->default('presidency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('minister_public_registers', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
