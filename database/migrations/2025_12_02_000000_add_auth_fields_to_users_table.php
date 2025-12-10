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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('id');
            $table->string('phone')->nullable()->after('email');
            $table->string('disability_type')->nullable()->after('name');
            $table->json('accessibility_settings')->nullable()->after('disability_type');

            // OTP-based authentication fields
            $table->string('otp_code', 6)->nullable()->after('password');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            $table->timestamp('otp_verified_at')->nullable()->after('otp_expires_at');

            // Simple caregiver linkage: a user may have one primary caregiver
            $table->foreignId('primary_caregiver_id')
                ->nullable()
                ->after('otp_verified_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['primary_caregiver_id']);
            $table->dropColumn([
                'role',
                'phone',
                'disability_type',
                'accessibility_settings',
                'otp_code',
                'otp_expires_at',
                'otp_verified_at',
                'primary_caregiver_id',
            ]);
        });
    }
};


