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
    Schema::create('books', function (Blueprint $table) {
        $table->id(); // Primary key for the book
        
        // This line links the book to a user in the 'users' table
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        $table->string('title');
        $table->string('author');
        $table->string('isbn')->unique()->nullable(); // Unique and optional
        $table->text('description')->nullable();
        $table->string('cover_image_path')->nullable(); // To store the photo path
        $table->string('condition'); // e.g., 'New', 'Used - Good'
        $table->string('status')->default('available'); // e.g., 'available', 'exchanged'
        
        $table->timestamps(); // `created_at` and `updated_at` columns
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
