<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ExchangeRequest;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; 

class ExchangeRequestController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        $incomingRequests = ExchangeRequest::where(function($query) use ($currentUser) {
            $query->whereHas('book', function($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id); 
            })->orWhere('requester_id', $currentUser->id); 
        })
        ->with(['book.user', 'requester']) 
        ->whereIn('status', ['pending', 'accepted', 'completed', 'rejected']) 
        ->latest()
        ->get();

        return view('exchange_requests.index', [
            'incomingRequests' => $incomingRequests,
        ]);
    }

   
}