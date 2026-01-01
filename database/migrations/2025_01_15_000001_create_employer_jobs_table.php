<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//F10 - Employer Job Posting & Dashboard
return new class extends Migration
{
    public function up()
    {
        Schema::create('employer_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('location')->nullable();
            $table->string('job_type')->default('full-time'); // full-time, part-time, contract, remote
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->string('salary_currency', 3)->default('USD');
            $table->date('application_deadline')->nullable();
            
            // Accessibility features
            $table->boolean('wheelchair_accessible')->default(false);
            $table->boolean('sign_language_support')->default(false);
            $table->boolean('screen_reader_compatible')->default(false);
            $table->boolean('flexible_hours')->default(false);
            $table->boolean('remote_work_available')->default(false);
            $table->text('accessibility_accommodations')->nullable();
            $table->text('additional_requirements')->nullable();
            
            // Job status
            $table->enum('status', ['draft', 'active', 'closed', 'filled'])->default('draft');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employer_jobs');
    }
};

