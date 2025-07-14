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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            
            // The user who GAVE the rating
            $table->foreignId('rater_id')->constrained('users')->onDelete('cascade');
            
            // The user who RECEIVED the rating
            $table->foreignId('ratee_id')->constrained('users')->onDelete('cascade');
            
            // Link to the specific exchange request this rating is for
            $table->foreignId('exchange_request_id')->constrained('exchange_requests')->onDelete('cascade');
            
            $table->integer('rating')->unsigned()->min(1)->max(5); // Rating out of 5 stars
            $table->text('comment')->nullable(); // Optional text comment
            
            $table->timestamps();

            // Ensure a user can only rate another user for a specific exchange once
            $table->unique(['rater_id', 'exchange_request_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
