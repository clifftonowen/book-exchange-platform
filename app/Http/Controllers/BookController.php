<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\RecommendationController; 
use App\Models\User; 
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\Storage; 

class BookController extends Controller
{
    /**
     * Display a listing of available books on the dashboard (excluding user's own books).
     * Also includes AI recommendations.
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        $query = Book::query();

        // Only show books that are 'available' and NOT owned by the current user
        $query->where('status', 'available')
              ->where('user_id', '!=', $currentUser->id); // Exclude own books from main dashboard

        // If there is a search query, filter the results
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('author', 'like', '%' . $searchTerm . '%');
            });
        }

        $books = $query->latest()->get();

        // Generate AI recommendations for the current user
        $recommendations = RecommendationController::generate($currentUser);

        return view('dashboard', [
            'books' => $books,
            'recommendations' => $recommendations,
        ]);
    }

    /**
     * Show the form for creating a new book.
     */
    public function create(): View
    {
        return view('books.create');
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = $request->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255', // Assuming genre is nullable
            'condition' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // Handle image upload if a file is present
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image_path'] = $path;
        }

        // Create the new book record linked to the current user
        $currentUser->books()->create($validated);

        // Redirect to the new 'My Books' page after successful listing
        return redirect(route('my-books.index'))->with('success', 'Book listed successfully!');
    }

    /**
     * Display the specified book's details.
     */
    public function show(Book $book): View
    {
        return view('books.show', [
            'book' => $book,
        ]);
    }

    /**
     * Display a listing of books owned by the current user.
     */
    public function myBooks(): View
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        // Get all books owned by the current user, ordered by most recent first
        $myBooks = $currentUser->books()->latest()->get();

        return view('books.my-books', [
            'myBooks' => $myBooks,
        ]);
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book): View|RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        // Security check: Only the owner can edit their book
        if ($book->user_id !== $currentUser->id) {
            return redirect()->route('my-books.index')->with('error', 'You are not authorized to edit this book.');
        }

        return view('books.edit', [
            'book' => $book,
        ]);
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        // Security check: Only the owner can update their book
        if ($book->user_id !== $currentUser->id) {
            return redirect()->route('my-books.index')->with('error', 'You are not authorized to update this book.');
        }

        // Validate the incoming data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255', // Assuming genre is nullable
            'condition' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional: new image
        ]);

        // Handle new image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if it exists
            if ($book->cover_image_path) {
                Storage::disk('public')->delete($book->cover_image_path);
            }
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image_path'] = $path;
        }

        // Update the book details
        $book->update($validated);

        return redirect()->route('my-books.index')->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book): RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        // Security check: Only the owner can delete their book
        if ($book->user_id !== $currentUser->id) {
            return redirect()->route('my-books.index')->with('error', 'You are not authorized to delete this book.');
        }

        // Delete associated cover image if it exists
        if ($book->cover_image_path) {
            Storage::disk('public')->delete($book->cover_image_path);
        }

        // Delete the book record from the database
        $book->delete();

        return redirect()->route('my-books.index')->with('success', 'Book deleted successfully!');
    }
}