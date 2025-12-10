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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('learning_objectives')->nullable();
            $table->string('category')->nullable();
            $table->string('difficulty_level')->default('beginner'); // beginner, intermediate, advanced
            $table->integer('estimated_duration_minutes')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->json('accessibility_features')->nullable(); // ["subtitles", "audio_description", "transcript", "keyboard_navigation"]
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['is_active', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};





