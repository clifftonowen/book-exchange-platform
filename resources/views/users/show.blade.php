<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Profile') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-bold text-2xl dark:text-white mb-4">{{ $user->name }}</h3>

                    <p class="text-gray-700 dark:text-gray-300">
                        Member since: {{ $user->created_at->format('M d, Y') }}
                    </p>

                    @if ($user->averageRating())
                        <p class="mt-2 text-lg font-semibold">
                            Average Rating: {{ number_format($user->averageRating(), 1) }} / 5 Stars
                        </p>
                    @else
                        <p class="mt-2 text-gray-500 dark:text-gray-400">No ratings yet.</p>
                    @endif

                    <h4 class="font-bold text-xl dark:text-white mt-8 mb-4">Reviews</h4>
                    @forelse ($ratings as $rating)
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow mb-3">
                            <p class="text-md font-semibold dark:text-white">
                                Rating: {{ $rating->rating }} / 5
                            </p>
                            @if ($rating->comment)
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">"{{ $rating->comment }}"</p>
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                By: {{ $rating->rater->name }} on {{ $rating->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No reviews received yet.</p>
                    @endforelse

                    
                    <h4 class="font-bold text-xl dark:text-white mt-8 mb-4">Exchange History</h4>
                    @forelse ($exchangeHistory as $exchange)
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow mb-3">
                            <p class="text-md font-semibold dark:text-white">
                                Book: <a href="{{ route('books.show', $exchange->book) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $exchange->book->title }}</a>
                            </p>

                            @if ($exchange->book->user_id === $user->id)
                                {{-- This user (whose profile we're viewing) is the book owner --}}
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                                    Role: Gave this book for exchange to <a href="{{ route('users.show', $exchange->requester) }}" class="text-indigo-500 hover:underline">{{ $exchange->requester->name }}</a>
                                </p>
                            @else
                                {{-- This user (whose profile we're viewing) is the requester --}}
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                                    Role: Received this book from <a href="{{ route('users.show', $exchange->book->user) }}" class="text-indigo-500 hover:underline">{{ $exchange->book->user->name }}</a>
                                </p>
                            @endif

                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Status: <span class="capitalize">{{ $exchange->status }}</span> (Last Updated: {{ $exchange->updated_at->format('M d, Y') }})
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No exchange history to display yet.</p>
                    @endforelse

                </div> {{-- Closing div for p-6 text-gray-900... --}}
            </div> {{-- Closing div for bg-white dark:bg-gray-800... --}}
        </div> {{-- Closing div for max-w-7xl... --}}
    </div> {{-- Closing div for py-12 --}}
</x-app-layout>