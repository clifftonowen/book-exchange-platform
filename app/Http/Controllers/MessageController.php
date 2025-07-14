<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRequest; 
use App\Models\Message; 
use App\Models\User; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; 
use Illuminate\View\View; 
use Illuminate\Support\Facades\Auth; 

class MessageController extends Controller
{
    /**
     * Display the message conversation for a specific exchange request.
     *
     * @param ExchangeRequest $exchangeRequest The exchange request whose messages are being viewed.
     * @return View|RedirectResponse
     */
    public function show(ExchangeRequest $exchangeRequest): View|RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user(); // Get the currently authenticated user

        // Security check: Ensure the current user is part of this exchange (either book owner or requester)
        if ($currentUser->id !== $exchangeRequest->book->user_id && $currentUser->id !== $exchangeRequest->requester_id) {
            return redirect()->route('exchange-requests.index')->with('error', 'You are not authorized to view messages for this exchange.');
        }

        // Ensure the exchange is in an 'accepted' or 'completed' state to allow messaging
        if (!in_array($exchangeRequest->status, ['accepted', 'completed'])) {
            return redirect()->route('exchange-requests.index')->with('error', 'Messaging is only available for accepted or completed exchanges.');
        }

        // Mark messages addressed to the current user as read
        // This updates `read_at` timestamp for messages where current user is the receiver
        $exchangeRequest->messages()->where('receiver_id', $currentUser->id)->update(['read_at' => now()]);

        // Load all messages for this specific exchange, eager load sender and receiver details, ordered by oldest first
        $messages = $exchangeRequest->messages()->with(['sender', 'receiver'])->orderBy('created_at', 'asc')->get();

        // Determine the other participant in the conversation to display their name in the chat header
        $otherParticipant = null;
        if ($currentUser->id === $exchangeRequest->book->user_id) {
            $otherParticipant = $exchangeRequest->requester; // If current user is the book owner, the other party is the requester
        } elseif ($currentUser->id === $exchangeRequest->requester_id) {
            $otherParticipant = $exchangeRequest->book->user; // If current user is the requester, the other party is the book owner
        }

        // Return the view for the message conversation, passing necessary data
        return view('messages.show', [
            'exchangeRequest' => $exchangeRequest,
            'messages' => $messages,
            'otherParticipant' => $otherParticipant,
        ]);
    }

    /**
     * Store a new message for a specific exchange request.
     *
     * @param Request $request The incoming request containing message content.
     * @param ExchangeRequest $exchangeRequest The exchange request to which the message belongs.
     * @return RedirectResponse
     */
    public function store(Request $request, ExchangeRequest $exchangeRequest): RedirectResponse
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user(); // Get the currently authenticated user

        // Security check: Ensure the current user is part of this exchange
        if ($currentUser->id !== $exchangeRequest->book->user_id && $currentUser->id !== $exchangeRequest->requester_id) {
            return back()->with('error', 'You are not authorized to send messages for this exchange.');
        }

        // Ensure the exchange is in an 'accepted' or 'completed' state to allow messaging
        if (!in_array($exchangeRequest->status, ['accepted', 'completed'])) {
            return back()->with('error', 'Messaging is only available for accepted or completed exchanges.');
        }

        // Validate the message content from the request
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Determine the recipient of the message based on who the current user is
        $receiverId = ($currentUser->id === $exchangeRequest->book->user_id)
                      ? $exchangeRequest->requester_id // If current user is the owner, send to the requester
                      : $exchangeRequest->book->user_id; // If current user is the requester, send to the owner

        // Create and save the new message record
        $exchangeRequest->messages()->create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $receiverId,
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Message sent!'); // Redirect back to the chat page (messages.show)
    }
}