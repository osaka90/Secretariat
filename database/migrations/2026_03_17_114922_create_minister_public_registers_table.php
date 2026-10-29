<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minister_public_registers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sequential_number')->unique()->comment('الرقم الترتيبي');
            $table->text('result_or_notes')->nullable()->comment('النتيجة أو الملاحظات');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minister_public_registers');
    }
};
