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
        Schema::create('health_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('metric_type'); // e.g., 'weight', 'blood_pressure', 'steps'
            $table->string('value');
            $table->string('unit')->nullable(); // e.g., 'kg', 'mmHg', 'count'
            $table->timestamp('measured_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_metrics');
    }
};
