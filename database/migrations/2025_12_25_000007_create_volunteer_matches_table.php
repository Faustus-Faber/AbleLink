<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//F14 - Volunteer Matching System
return new class extends Migration
{
    public function up()
    {
        Schema::create('volunteer_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assistance_request_id')->constrained('assistance_requests')->onDelete('cascade');
            $table->foreignId('volunteer_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'declined', 'completed', 'cancelled'])->default('pending');
            $table->text('volunteer_notes')->nullable();
            $table->text('user_feedback')->nullable();
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->timestamp('matched_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Prevent duplicate matches for same request
            $table->unique(['assistance_request_id', 'volunteer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('volunteer_matches');
    }
};

