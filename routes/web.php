<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExchangeRequestController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MessageController; 
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

// Dashboard route, accessible only by authenticated and verified users
Route::get('/dashboard', [BookController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Group of routes that require user authentication
Route::middleware('auth')->group(function () { // <-- Start of the authenticated group
    // Profile Management Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Book Management Routes
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit'); 
    Route::patch('/books/{book}', [BookController::class, 'update'])->name('books.update'); 
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy'); 

    // Exchange Request Routes
    Route::post('/exchange-requests/{book}', [ExchangeRequestController::class, 'store'])->name('exchange-requests.store');
    Route::post('/exchange-requests/{exchangeRequest}/accept', [ExchangeRequestController::class, 'accept'])->name('exchange-requests.accept');
    Route::post('/exchange-requests/{exchangeRequest}/reject', [ExchangeRequestController::class, 'reject'])->name('exchange-requests.reject');
    Route::post('/exchange-requests/{exchangeRequest}/complete', [ExchangeRequestController::class, 'complete'])->name('exchange-requests.complete');
    Route::get('/my-exchange-requests', [ExchangeRequestController::class, 'index'])->name('exchange-requests.index'); 

    // Rating Routes
    Route::get('/ratings/create/{exchangeRequest}/{rateeId}', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');

    // Public User Profile Route
    Route::get('/users/{user}', [UserProfileController::class, 'show'])->name('users.show');

    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{book}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{book}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // My Books Page
    Route::get('/my-books', [BookController::class, 'myBooks'])->name('my-books.index'); 

    // Messaging Routes
    Route::get('/exchanges/{exchangeRequest}/messages', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/exchanges/{exchangeRequest}/messages', [MessageController::class, 'store'])->name('messages.store');

}); 


require __DIR__.'/auth.php';