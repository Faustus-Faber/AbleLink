<?php
//Akida - F11
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('course_media')) {
            return;
        }

        Schema::create('course_media', function (Blueprint $table) {
            $table->id();
            // Use explicit type + add FK after ensuring compatible engines.
            $table->unsignedBigInteger('course_id');

            $table->string('kind'); // video | audio | document | link
            $table->string('title')->nullable();

            // Provide either a local file (media_path) OR an external URL.
            $table->string('external_url')->nullable();
            $table->string('media_path')->nullable();
            $table->string('mime_type')->nullable();

            // Accessibility
            $table->string('captions_path')->nullable(); // .vtt
            $table->string('captions_language')->nullable(); // e.g. en
            $table->longText('transcript')->nullable();
            $table->string('audio_description_path')->nullable(); // separate audio track

            // Ordering / UX
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['course_id', 'kind']);
            $table->index(['course_id', 'is_primary']);
        });

        // Try to ensure InnoDB and add FK. If the existing `courses` table is not InnoDB
        // (or the DB doesn't support FKs), don't fail the whole migration.
        try {
            DB::statement('ALTER TABLE courses ENGINE=InnoDB');
        } catch (Throwable) {
            // ignore
        }
        try {
            DB::statement('ALTER TABLE course_media ENGINE=InnoDB');
        } catch (Throwable) {
            // ignore
        }

        try {
            Schema::table('course_media', function (Blueprint $table) {
                $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            });
        } catch (Throwable) {
            // ignore
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('course_media');
    }
};
