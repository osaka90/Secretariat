<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minister_public_register_correspondences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('minister_public_register_id')
                  ->constrained('minister_public_registers')
                  ->cascadeOnDelete();
                  
            $table->string('type')->comment('outgoing or incoming');
            $table->date('date')->nullable()->comment('التاريخ');
            $table->string('correspondent')->nullable()->comment('المرسل إليه أو المصدر');
            $table->text('subject_or_reply')->nullable()->comment('الموضوع أو الجواب');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minister_public_register_correspondences');
    }
};
