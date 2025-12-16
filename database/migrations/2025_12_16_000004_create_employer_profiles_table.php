<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//F10 - Employer Job Posting & Dashboard - Company Profile
return new class extends Migration
{
    public function up()
    {
        Schema::create('employer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique();
            $table->string('company_name');
            $table->text('company_description')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('industry')->nullable();
            $table->integer('company_size')->nullable();
            
            // Accessibility features
            $table->boolean('wheelchair_accessible_office')->default(false);
            $table->boolean('sign_language_available')->default(false);
            $table->boolean('assistive_technology_support')->default(false);
            $table->text('accessibility_accommodations')->nullable();
            $table->text('inclusive_hiring_practices')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employer_profiles');
    }
};

