<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//F14 - Volunteer Matching System
return new class extends Migration
{
    public function up()
    {
        Schema::create('volunteer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique();
            $table->text('bio')->nullable();
            $table->json('skills')->nullable(); // e.g., ['transportation', 'companionship', 'errands', 'technical_support']
            $table->json('availability')->nullable(); // e.g., ['monday', 'wednesday', 'friday']
            $table->string('location')->nullable();
            $table->integer('max_distance_km')->default(10); // Maximum distance willing to travel
            $table->boolean('available_for_emergency')->default(false);
            $table->text('specializations')->nullable(); // e.g., "Works with visually impaired, wheelchair users"
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('volunteer_profiles');
    }
};

