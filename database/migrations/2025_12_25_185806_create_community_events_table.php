<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//F16 - Evan Yuvraj Munshi
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('community_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->dateTime('event_date');
            $table->string('location')->nullable();
            $table->string('type')->default('offline'); // online, offline
            $table->string('meeting_link')->nullable();
            $table->timestamps();
        });

        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('attending'); // attending, interested, declined
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('community_events');
    }
};
