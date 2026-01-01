<?php

// F20 - Akida Lisi

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('aid_programs')) {
            return;
        }

        Schema::create('aid_programs', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->string('title');
            $table->string('agency')->nullable();
            $table->string('category')->nullable()->index();
            $table->string('region')->nullable()->index(); // country/state/city or "National"

            $table->text('summary')->nullable();
            $table->longText('eligibility')->nullable();
            $table->longText('benefits')->nullable();
            $table->longText('how_to_apply')->nullable();

            $table->string('application_url')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();

            $table->boolean('is_active')->default(true)->index();
            $table->json('tags')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aid_programs');
    }
};