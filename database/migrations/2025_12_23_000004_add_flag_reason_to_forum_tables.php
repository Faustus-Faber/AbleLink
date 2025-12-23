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
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->string('flag_reason')->nullable()->after('status');
        });

        Schema::table('forum_replies', function (Blueprint $table) {
            $table->string('flag_reason')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn('flag_reason');
        });

        Schema::table('forum_replies', function (Blueprint $table) {
            $table->dropColumn('flag_reason');
        });
    }
};
