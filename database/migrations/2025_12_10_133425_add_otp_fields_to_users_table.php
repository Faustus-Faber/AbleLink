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
            $table->string('phone')->nullable()->after('email');
            $table->timestamp('otp_verified_at')->nullable()->after('password');
            $table->timestamp('last_login_at')->nullable()->after('otp_verified_at');
            $table->string('password')->nullable()->change(); // F1 passwords can be null for OTP users? Check logic. F1 User model implied password still exists but registration sets it to random. Keeping it nullable if F1 schema had it, or just keeping standard. F1 migration said nullable.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'otp_verified_at', 'last_login_at']);
             // Reverting password to not null might fail if nulls exist, skipping for simple down.
        });
    }
};
