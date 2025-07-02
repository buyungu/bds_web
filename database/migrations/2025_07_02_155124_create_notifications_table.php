<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // If notification is for a specific user
            $table->string('fcm_token')->nullable(); // The FCM token used
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable(); // Store the custom data array
            $table->string('status')->default('sent'); // e.g., 'sent', 'failed', 'read'
            $table->text('error_message')->nullable(); // To store error details if sending fails
            $table->timestamp('sent_at')->nullable();
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
