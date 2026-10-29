<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('minister_public_registers', function (Blueprint $table) {
            // Drop the global unique constraint on sequential_number
            $table->dropUnique(['sequential_number']);

            // Add a composite unique constraint: unique per category
            $table->unique(['sequential_number', 'category']);
        });
    }

    public function down(): void
    {
        Schema::table('minister_public_registers', function (Blueprint $table) {
            $table->dropUnique(['sequential_number', 'category']);
            $table->unique(['sequential_number']);
        });
    }
};
