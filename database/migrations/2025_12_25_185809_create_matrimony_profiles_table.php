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
        Schema::create('matrimony_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->string('occupation')->nullable();
            $table->string('education')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('religion')->nullable();
            $table->text('partner_preferences')->nullable();
            $table->json('hobbies')->nullable();
            $table->string('privacy_level')->default('public'); // public, connections_only, private
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrimony_profiles');
    }
};
