<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('minister_public_registers', function (Blueprint $table) {
            $table->unsignedSmallInteger('registration_year')->nullable()->after('registration_date');
        });

        // Backfill existing records
        DB::table('minister_public_registers')->get()->each(function ($record) {
            $year = $record->registration_date 
                ? (int) Carbon::parse($record->registration_date)->format('Y')
                : ($record->created_at ? (int) Carbon::parse($record->created_at)->format('Y') : (int) now()->format('Y'));
            DB::table('minister_public_registers')->where('id', $record->id)->update(['registration_year' => $year]);
        });

        Schema::table('minister_public_registers', function (Blueprint $table) {
            $table->dropUnique(['sequential_number', 'category']);
            $table->unique(['sequential_number', 'category', 'registration_year']);
        });
    }

    public function down(): void
    {
        Schema::table('minister_public_registers', function (Blueprint $table) {
            $table->dropUnique(['sequential_number', 'category', 'registration_year']);
            $table->unique(['sequential_number', 'category']);
            $table->dropColumn('registration_year');
        });
    }
};

