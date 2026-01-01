<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caregiver_user', function (Blueprint $table) {
            $table->id();

            $caregiverIdColumn = $table->foreignId('caregiver_id');
            $caregiverIdColumn->constrained('users');
            $caregiverIdColumn->onDelete('cascade');

            $userIdColumn = $table->foreignId('user_id');
            $userIdColumn->constrained('users');
            $userIdColumn->onDelete('cascade');

            $statusColumn = $table->string('status');
            $statusColumn->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caregiver_user');
    }
};
