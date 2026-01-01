<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $attachmentPathColumn = $table->string('attachment_path');
            $attachmentPathColumn->nullable();
            $attachmentPathColumn->after('body');

            $attachmentTypeColumn = $table->string('attachment_type');
            $attachmentTypeColumn->nullable();
            $attachmentTypeColumn->after('attachment_path');
            
            $attachmentOriginalNameColumn = $table->string('attachment_original_name');
            $attachmentOriginalNameColumn->nullable();
            $attachmentOriginalNameColumn->after('attachment_type');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['attachment_path', 'attachment_type', 'attachment_original_name']);
        });
    }
};
