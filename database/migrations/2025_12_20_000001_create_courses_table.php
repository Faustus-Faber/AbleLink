<?php
// Akida -F11
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('courses')) {
            return;
        }

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('level')->nullable(); // beginner | intermediate | advanced (free-form for now)
            $table->unsignedInteger('estimated_minutes')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
