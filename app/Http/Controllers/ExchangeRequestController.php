<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ExchangeRequest;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Ensure this is imported

class ExchangeRequestController extends Controller
{
    /**
     * Store a newly created exchange request in storage.
     *
     * @param Request $request
     * @param Book $book
     * @return RedirectResponse
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Prevent a user from requesting their own book or an unavailable book
        if ($book->user_id === $currentUser->id || $book->status !== 'available') {
            return back()->with('error', 'This book cannot be requested.');
        }

        // Create the exchange request
        $book->exchangeRequests()->create([
            'requester_id' => $currentUser->id,
            'status' => 'pending',
        ]);

        // Update the book's status to 'pending' to prevent further requests
        $book->status = 'pending';
        $book->save();

        return redirect(route('dashboard'))->with('success', 'Exchange request sent!');
    }

    /**
     * Display a listing of exchange requests for the logged-in user's books.
     * NOW also displays requests the user has made.
     */
    public function index(): View
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Fetch exchange requests where:
        // 1. The current user OWNS the book being requested (incoming request)
        // OR
        // 2. The current user IS the requester (outgoing request)
        $incomingRequests = ExchangeRequest::where(function($query) use ($currentUser) {
            $query->whereHas('book', function($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id); // Current user is the book owner
            })->orWhere('requester_id', $currentUser->id); // Current user is the requester
        })
        ->with(['book.user', 'requester']) // Eager load book, book owner, and requester details
        ->whereIn('status', ['pending', 'accepted', 'completed', 'rejected']) // Show all relevant statuses
        ->latest()
        ->get();

        return view('exchange_requests.index', [
            'incomingRequests' => $incomingRequests,
        ]);
    }

    /**
     * Accept an exchange request.
     *
     * @param ExchangeRequest $exchangeRequest
     * @return RedirectResponse
     */
    public function accept(ExchangeRequest $exchangeRequest): RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Security check: Ensure the logged-in user owns the book being requested
        if ($currentUser->id !== $exchangeRequest->book->user_id) {
            return back()->with('error', 'You are not authorized to accept this request.');
        }

        // Update the request status to 'accepted'
        $exchangeRequest->status = 'accepted';
        $exchangeRequest->save();

        // Update the book status to 'accepted' - indicates owner agreed, awaiting physical exchange
        $book = $exchangeRequest->book;
        $book->status = 'accepted';
        $book->save();

        return redirect()->route('exchange-requests.index')->with('success', 'Exchange request accepted! Book is now reserved for exchange.');
    }

    /**
     * Mark an accepted exchange as completed.
     * This should be callable by either the book owner or the requester.
     *
     * @param ExchangeRequest $exchangeRequest
     * @return RedirectResponse
     */
    public function complete(ExchangeRequest $exchangeRequest): RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Ensure only involved parties can mark as complete
        if ($currentUser->id !== $exchangeRequest->book->user_id && $currentUser->id !== $exchangeRequest->requester_id) {
            return back()->with('error', 'You are not authorized to complete this exchange.');
        }

        // Ensure the exchange is in an 'accepted' state before completing
        if ($exchangeRequest->status !== 'accepted') {
            return back()->with('error', 'Exchange must be accepted before it can be marked as complete.');
        }

        // Update the request status to 'completed'
        $exchangeRequest->status = 'completed';
        $exchangeRequest->save();

        // Mark the book as 'exchanged' (permanently unavailable from main listings)
        $book = $exchangeRequest->book;
        $book->status = 'exchanged';
        $book->save();

        return redirect()->route('exchange-requests.index')->with('success', 'Exchange marked as completed! Both parties can now leave a review.');
    }

    /**
     * Reject an exchange request.
     *
     * @param ExchangeRequest $exchangeRequest
     * @return RedirectResponse
     */
    public function reject(ExchangeRequest $exchangeRequest): RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Security check: Ensure the logged-in user owns the book being requested
        if ($currentUser->id !== $exchangeRequest->book->user_id) {
            return back()->with('error', 'You are not authorized to reject this request.');
        }

        // Update the request status to 'rejected'
        $exchangeRequest->status = 'rejected';
        $exchangeRequest->save();

        // Change the book status back to 'available'
        // This makes it available for other requests again
        $book = $exchangeRequest->book;
        $book->status = 'available';
        $book->save();

        return redirect()->route('exchange-requests.index')->with('success', 'Exchange request rejected. Book is now available again.');
    }
}