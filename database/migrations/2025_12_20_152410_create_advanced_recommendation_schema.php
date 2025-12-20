<?php
// F12 - Farhan Zarif
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
        // 1. Update User Profiles
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->json('skills')->nullable();
            $table->json('interests')->nullable();
            $table->string('learning_style')->nullable(); // 'visual', 'auditory', 'text'
        });

        // 2. Update Jobs
        Schema::table('employer_jobs', function (Blueprint $table) {
            $table->json('skills_required')->nullable();
            $table->json('embedding_vector')->nullable(); // Simulated vector (array of floats)
        });

        // 3. Update Courses
        Schema::table('courses', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
        });

        // 4. Create Recommendation Feedback Table
        Schema::create('recommendation_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('item_id');
            $table->string('item_type'); // 'job' or 'course'
            $table->string('action'); // 'viewed', 'clicked', 'dismissed', 'applied'
            $table->float('weight')->default(1.0);
            $table->timestamps();

            $table->index(['user_id', 'item_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
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
