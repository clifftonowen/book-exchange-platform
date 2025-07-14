<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use App\Models\ExchangeRequest; 

class UserProfileController extends Controller
{
    /**
     * Display the specified user's public profile, ratings, and exchange history.
     *
     * @param User $user The user whose profile is being viewed (Route Model Binding)
     * @return View
     */
    public function show(User $user): View
    {
        // Load ratings received by this user, eager load the rater's name
        $ratings = $user->ratingsReceived()->with('rater')->latest()->get();

        // Get all exchange requests where this user is the book owner OR the requester
        // Eager load the book (and its owner) and the requester to display names efficiently
        $exchangeHistory = ExchangeRequest::where(function($query) use ($user) {
            $query->whereHas('book', function($q) use ($user) {
                $q->where('user_id', $user->id); // User is the book owner
            })->orWhere('requester_id', $user->id); // User is the requester
        })
        ->with(['book.user', 'requester']) // Load book details, book owner, and requester details
        ->orderBy('updated_at', 'desc') // Order by last updated (most recent activity first)
        ->get();

        return view('users.show', [
            'user' => $user,
            'ratings' => $ratings,
            'exchangeHistory' => $exchangeHistory, // Pass the exchange history to the view
        ]);
    }
}