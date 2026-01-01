<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//F10 - Employer Job Posting & Dashboard
return new class extends Migration
{
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('employer_jobs')->onDelete('cascade');
            $table->foreignId('applicant_id')->constrained('users')->onDelete('cascade');
            $table->text('cover_letter')->nullable();
            $table->string('resume_path')->nullable();
            $table->enum('status', ['pending', 'reviewing', 'shortlisted', 'interviewed', 'accepted', 'rejected'])->default('pending');
            $table->text('employer_notes')->nullable();
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();
            
            // Prevent duplicate applications
            $table->unique(['job_id', 'applicant_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
};

