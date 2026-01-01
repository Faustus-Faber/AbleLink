<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            
            $userOneColumn = $table->foreignId('user_one_id');
            $userOneColumn->constrained('users');
            $userOneColumn->onDelete('cascade');
            
            $userTwoColumn = $table->foreignId('user_two_id');
            $userTwoColumn->constrained('users');
            $userTwoColumn->onDelete('cascade');
            
            $table->timestamps();

            $table->unique(['user_one_id', 'user_two_id']);
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            $conversationIdColumn = $table->foreignId('conversation_id');
            $conversationIdColumn->constrained();
            $conversationIdColumn->onDelete('cascade');
            
            $senderIdColumn = $table->foreignId('sender_id');
            $senderIdColumn->constrained('users');
            $senderIdColumn->onDelete('cascade');
            
            $table->text('body');
            
            $isReadColumn = $table->boolean('is_read');
            $isReadColumn->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
