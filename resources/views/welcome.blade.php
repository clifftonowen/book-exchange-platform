<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} - P2P Book Exchange</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ url('/') }}">
                                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                                </a>
                            </div>
                        </div>

                        <div class="flex items-center sm:ms-6">
                            @if (Route::has('login'))
                                <div class="space-x-4">
                                    @auth
                                        {{-- If user is logged in, show dashboard link --}}
                                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                                    @else
                                        {{-- If user is not logged in, show login/register links --}}
                                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="ms-4 font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <header class="bg-indigo-600 dark:bg-indigo-900 py-20 text-center text-white">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 class="text-5xl font-extrabold leading-tight tracking-tight mb-4">
                        Give Your Books a Second Life!
                    </h1>
                    <p class="text-xl opacity-90 mb-8">
                        Connect with fellow readers and effortlessly exchange the books you've loved for new treasures.
                        Sustainable reading, one swap at a time.
                    </p>
                    <div class="space-x-4">
                        <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-700 font-bold py-3 px-6 rounded-lg text-lg hover:bg-gray-200 transition duration-300">
                            Join Now!
                        </a>
                        <a href="{{ route('login') }}" class="inline-block border-2 border-white text-white font-bold py-3 px-6 rounded-lg text-lg hover:bg-white hover:text-indigo-700 transition duration-300">
                            Learn More
                        </a>
                    </div>
                </div>
            </header>

            <section class="py-16 bg-gray-50 dark:bg-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl font-bold mb-10 dark:text-gray-100">Why Our Platform?</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="p-6 bg-white dark:bg-gray-700 rounded-lg shadow-md">
                            <h3 class="font-bold text-xl mb-3 dark:text-gray-100">Easy Exchange</h3>
                            <p class="text-gray-700 dark:text-gray-300">List, search, and swap books with just a few clicks. Our intuitive interface makes it simple.</p>
                        </div>
                        <div class="p-6 bg-white dark:bg-gray-700 rounded-lg shadow-md">
                            <h3 class="font-bold text-xl mb-3 dark:text-gray-100">Community Driven</h3>
                            <p class="text-gray-700 dark:text-gray-300">Join a vibrant community of book lovers. Discover new reads and connect with fellow readers.</p>
                        </div>
                        <div class="p-6 bg-white dark:bg-gray-700 rounded-lg shadow-md">
                            <h3 class="font-bold text-xl mb-3 dark:text-gray-100">Sustainable Reading</h3>
                            <p class="text-gray-700 dark:text-gray-300">Give your unread books a new home and reduce waste. Promote a circular economy for books.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-16 bg-gray-100 dark:bg-gray-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-3xl font-bold mb-10 text-center dark:text-gray-100">Browse Available Books</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @php
                            // Fetch available books directly in the view for POC simplicity.
                            // In a larger, production-ready application, this data would typically be
                            // fetched in a dedicated controller (e.g., a WelcomeController) and passed to the view.
                            $availableBooks = \App\Models\Book::where('status', 'available')->latest()->take(8)->get();
                        @endphp

                        @forelse ($availableBooks as $book)
                            <a href="{{ route('books.show', $book) }}">
                                <div class="bg-gray-200 dark:bg-gray-800 p-4 rounded-lg shadow space-y-2 hover:ring-2 hover:ring-indigo-500 transition-all">
                                    @if ($book->cover_image_path)
                                        <img src="{{ asset('storage/' . $book->cover_image_path) }}" alt="Cover of {{ $book->title }}" class="rounded-md w-full h-48 object-cover mb-2">
                                    @endif
                                    <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ $book->title }}</h3>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">by {{ $book->author }}</p>
                                    <p class="text-xs bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300 inline-block px-2 py-1 rounded-full">{{ $book->condition }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="col-span-full text-center text-gray-500 dark:text-gray-400">No books available for exchange right now. Check back soon!</p>
                        @endforelse
                    </div>

                    <div class="text-center mt-10">
                        <a href="{{ route('dashboard') }}" class="inline-block bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg text-lg hover:bg-indigo-700 transition duration-300">
                            View All Books (Login Required)
                        </a>
                    </div>
                </div>
            </section>

            <footer class="py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
            </footer>
        </div>
    </body>
</html>