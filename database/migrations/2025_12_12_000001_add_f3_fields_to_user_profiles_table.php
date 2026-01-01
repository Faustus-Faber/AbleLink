<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//F3 - Evan Yuvraj Munshi
return new class extends Migration
{
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('accessibility_preferences');
            $table->string('phone_number')->nullable()->after('avatar');
            $table->text('address')->nullable()->after('phone_number');
            $table->date('date_of_birth')->nullable()->after('address');
            $table->string('emergency_contact_name')->nullable()->after('date_of_birth');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
        });
    }

    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'phone_number',
                'address',
                'date_of_birth',
                'emergency_contact_name',
                'emergency_contact_phone'
            ]);
        });
    }
};
