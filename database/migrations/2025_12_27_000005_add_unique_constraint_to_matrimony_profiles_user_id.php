<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

//F16 - Evan Yuvraj Munshi
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clean up duplicates before adding unique constraint
        $duplicates = DB::table('matrimony_profiles')
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $keepId = DB::table('matrimony_profiles')
                ->where('user_id', $duplicate->user_id)
                ->orderBy('id', 'desc') // Keep the latest one
                ->value('id');

            DB::table('matrimony_profiles')
                ->where('user_id', $duplicate->user_id)
                ->where('id', '!=', $keepId)
                ->delete();
        }

        Schema::table('matrimony_profiles', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matrimony_profiles', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });
    }
};
