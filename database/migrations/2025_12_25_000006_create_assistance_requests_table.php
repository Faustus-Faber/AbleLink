<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//F14 - Volunteer Matching System
return new class extends Migration
{
    public function up()
    {
        Schema::create('assistance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['transportation', 'companionship', 'errands', 'technical_support', 'medical_assistance', 'other'])->default('other');
            $table->enum('urgency', ['low', 'medium', 'high', 'emergency'])->default('medium');
            $table->string('location')->nullable();
            $table->dateTime('preferred_date_time')->nullable();
            $table->enum('status', ['pending', 'matched', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('special_requirements')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assistance_requests');
    }
};

