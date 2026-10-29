<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minister_public_register_incomings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('minister_public_register_id')
                  ->constrained('minister_public_registers')
                  ->cascadeOnDelete();
                  
            $table->date('letter_date')->nullable()->comment('تاريخ رسالة الوارد');
            $table->string('letter_number')->nullable()->comment('رقم رسالة الوارد');
            $table->date('arrival_date')->nullable()->comment('تاريخ الوصول');
            $table->string('sender')->nullable()->comment('اسم وموطن المرسل إليه');
            $table->text('subject')->nullable()->comment('الموضوع');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minister_public_register_incomings');
    }
};
