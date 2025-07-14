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
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            
            // The user who added the item to their wishlist
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // The specific book on their wishlist.
            // For a more advanced system, you might allow free-text wishes (title/author) if the book doesn't exist yet.
            $table->foreignId('book_id')->constrained()->onDelete('cascade'); 
            
            $table->timestamps();

            // Ensure a user can only add a specific book to their wishlist once
            $table->unique(['user_id', 'book_id']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
    }
};
