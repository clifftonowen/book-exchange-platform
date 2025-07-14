<?php

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
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key

            // The user who SENT the message
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');

            // The user who RECEIVED the message
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');

            // Link this message to a specific exchange request
            $table->foreignId('exchange_request_id')->constrained('exchange_requests')->onDelete('cascade');

            $table->text('content'); // The actual text content of the message
            $table->timestamp('read_at')->nullable(); // Optional: timestamp when the message was read

            $table->timestamps(); // Adds `created_at` and `updated_at` columns automatically
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages'); // Drops the table if rolling back the migration
    }
};
