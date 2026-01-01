<?php
//F15 - Akida Lisi
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_sos_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Location is optional (depends on browser permissions/support)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('accuracy_m')->nullable();

            // Optional snapshot/context
            $table->string('address')->nullable();
            $table->text('notes')->nullable();

            // Resolution workflow (optional)
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['resolved_at', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_sos_events');
    }
};
