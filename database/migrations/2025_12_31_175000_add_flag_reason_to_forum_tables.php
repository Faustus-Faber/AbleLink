<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $flagReasonColumn = $table->string('flag_reason');
            $flagReasonColumn->nullable();
            $flagReasonColumn->after('status');
        });

        Schema::table('forum_replies', function (Blueprint $table) {
            $flagReasonColumn = $table->string('flag_reason');
            $flagReasonColumn->nullable();
            $flagReasonColumn->after('status');
        });
    }

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
