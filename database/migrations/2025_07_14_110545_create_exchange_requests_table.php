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
        Schema::create('exchange_requests', function (Blueprint $table) {
            $table->id();
            
            // Foreign key for the book being requested
            $table->foreignId('book_id')->constrained()->onDelete('cascade');

            // Foreign key for the user making the request
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');

            $table->string('status')->default('pending'); // e.g., pending, accepted, rejected
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_requests');
    }
};
