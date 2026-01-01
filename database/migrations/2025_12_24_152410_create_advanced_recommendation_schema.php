<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->json('skills')->nullable();
            $table->json('interests')->nullable();
            $table->string('learning_style')->nullable();
        });

        Schema::table('employer_jobs', function (Blueprint $table) {
            $table->json('skills_required')->nullable();
            $table->json('embedding_vector')->nullable();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
        });

        Schema::create('recommendation_feedback', function (Blueprint $table) {
            $table->id();
            
            $userIdColumn = $table->foreignId('user_id');
            $userIdColumn->constrained();
            $deleteAction = $userIdColumn->onDelete('cascade');
            
            $table->unsignedBigInteger('item_id');
            $table->string('item_type');
            $table->string('action');
            
            $weightColumn = $table->float('weight');
            $weightColumn->default(1.0);
            
            $table->timestamps();

            $table->index(['user_id', 'item_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_feedback');

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['category', 'tags']);
        });

        Schema::table('employer_jobs', function (Blueprint $table) {
            $table->dropColumn(['skills_required', 'embedding_vector']);
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['skills', 'interests', 'learning_style']);
        });
    }
};
