<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            
            $userIdColumn = $table->foreignId('user_id');
            $userIdColumn->constrained();
            $userIdColumn->cascadeOnDelete();
            
            $courseIdColumn = $table->foreignId('course_id');
            $courseIdColumn->constrained();
            $courseIdColumn->cascadeOnDelete();
            
            $codeColumn = $table->string('certificate_code');
            $codeColumn->unique();
            
            $table->text('ai_generated_message');
            $table->timestamp('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
