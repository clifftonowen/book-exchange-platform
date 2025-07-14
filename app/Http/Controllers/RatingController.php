<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Rating; 

class RatingController extends Controller
{
    /**
     * Show the form for creating a new rating.
     *
     * @param ExchangeRequest $exchangeRequest
     * @param User $rateeId The User model of the user to be rated (route model binding)
     * @return View|RedirectResponse
     */
    public function create(ExchangeRequest $exchangeRequest, User $rateeId): View|RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        // Security check: Ensure the current user is part of this exchange
        if ($currentUser->id !== $exchangeRequest->book->user_id && $currentUser->id !== $exchangeRequest->requester_id) {
            return redirect()->route('exchange-requests.index')->with('error', 'You are not authorized to rate this exchange.');
        }

        // Ensure the exchange is completed
        if ($exchangeRequest->status !== 'completed') {
            return redirect()->route('exchange-requests.index')->with('error', 'Only completed exchanges can be rated.');
        }

        // Ensure the rateeId is indeed the other party in the exchange
        if ($rateeId->id !== $exchangeRequest->book->user_id && $rateeId->id !== $exchangeRequest->requester_id) {
            return redirect()->route('exchange-requests.index')->with('error', 'Invalid user to rate for this exchange.');
        }

        // Ensure user hasn't already rated this exchange
        $existingRating = Rating::where('rater_id', $currentUser->id)
                                ->where('exchange_request_id', $exchangeRequest->id)
                                ->first();

        if ($existingRating) {
            return redirect()->route('exchange-requests.index')->with('error', 'You have already rated this exchange.');
        }

        return view('ratings.create', [
            'exchangeRequest' => $exchangeRequest,
            'ratee' => $rateeId, // Passing the full User model for the ratee
        ]);
    }

    /**
     * Store a newly created rating in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        // 1. Validate the incoming data
        $validated = $request->validate([
            'exchange_request_id' => 'required|exists:exchange_requests,id',
            'ratee_id' => 'required|exists:users,id',
            'rater_id' => 'required|numeric|in:' . $currentUser->id, // Ensure rater_id matches current user
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // 2. Retrieve related models based on validated IDs
        $exchangeRequest = ExchangeRequest::findOrFail($validated['exchange_request_id']);
        $ratee = User::findOrFail($validated['ratee_id']);

        // 3. Security Check: Ensure the current user (rater) is part of this exchange
        // And ensure the ratee is the other participant in the exchange
        if ($currentUser->id !== $exchangeRequest->book->user_id && $currentUser->id !== $exchangeRequest->requester_id) {
            return back()->with('error', 'Security check failed: You are not authorized to rate this exchange.');
        }

        if ($ratee->id !== $exchangeRequest->book->user_id && $ratee->id !== $exchangeRequest->requester_id) {
            return back()->with('error', 'Security check failed: Invalid user to rate for this exchange.');
        }

        // 4. Ensure the exchange is completed before rating
        if ($exchangeRequest->status !== 'completed') {
            return back()->with('error', 'Only completed exchanges can be rated.');
        }

        // 5. Prevent double rating for the same exchange by the same rater
        $existingRating = Rating::where('rater_id', $currentUser->id)
                                ->where('exchange_request_id', $exchangeRequest->id)
                                ->first();
        if ($existingRating) {
            return back()->with('error', 'You have already rated this exchange.');
        }

        // 6. Create the rating
        Rating::create([
            'rater_id' => $currentUser->id,
            'ratee_id' => $ratee->id,
            'exchange_request_id' => $exchangeRequest->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('exchange-requests.index')->with('success', 'Review submitted successfully!');
    }
}