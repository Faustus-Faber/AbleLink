<?php
//F19 - Evan Munshi//

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
        Schema::create('medication_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('caregiver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('medication_name');
            $table->string('dosage');
            $table->string('frequency'); // e.g., 'daily', 'weekly', 'as_needed'
            $table->time('scheduled_time')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_schedules');
    }
};
