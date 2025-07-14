<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\WishlistItem; 
use App\Models\User; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display a listing of the user's wishlist items.
     *
     * @return View
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Load wishlist items, eager load the associated book details
        $wishlistItems = $user->wishlistItems()->with('book')->latest()->get();

        return view('wishlist.index', [
            'wishlistItems' => $wishlistItems,
        ]);
    }

    /**
     * Store a new wishlist item (add a book to wishlist).
     *
     * @param Request $request
     * @param Book $book The book to add to the wishlist
     * @return RedirectResponse
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Prevent adding your own book to wishlist
        if ($book->user_id === $user->id) {
            return back()->with('error', 'You cannot add your own book to your wishlist.');
        }

        // Check if the item is already on the wishlist
        $existing = WishlistItem::where('user_id', $user->id)
                                ->where('book_id', $book->id)
                                ->first();

        if ($existing) {
            return back()->with('error', 'This book is already on your wishlist.');
        }

        // Create the wishlist item
        $user->wishlistItems()->create([
            'book_id' => $book->id,
        ]);

        return back()->with('success', 'Book added to wishlist!');
    }

    /**
     * Remove a wishlist item (remove a book from wishlist).
     *
     * @param Book $book The book to remove from the wishlist
     * @return RedirectResponse
     */
    public function destroy(Book $book): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Find and delete the wishlist item
        $deleted = WishlistItem::where('user_id', $user->id)
                               ->where('book_id', $book->id)
                               ->delete();

        if ($deleted) {
            return back()->with('success', 'Book removed from wishlist.');
        }

        return back()->with('error', 'Could not remove book from wishlist.');
    }
}