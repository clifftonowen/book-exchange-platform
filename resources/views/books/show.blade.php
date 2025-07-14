<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <h3 class="font-bold text-2xl dark:text-white">{{ $book->title }}</h3>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mt-1">by {{ $book->author }}</p>

                    <div class="mt-4">
                        <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 inline-block px-2 py-1 rounded-full">{{ $book->condition }}</span>
                    </div>

                    <div class="mt-6 flex items-center space-x-2">
                        @auth
                            @if ($book->status === 'available' && $book->user_id !== auth()->id())
                                {{-- Check if book is already on wishlist for the current user --}}
                                @php
                                    /** @var \App\Models\User $currentUser */
                                    $currentUser = auth()->user();
                                    $isOnWishlist = $currentUser->wishlistItems()->where('book_id', $book->id)->exists();
                                @endphp

                                @if (!$isOnWishlist)
                                    {{-- Button to add to wishlist --}}
                                    <form action="{{ route('wishlist.store', $book) }}" method="POST">
                                        @csrf
                                        <x-secondary-button type="submit">
                                            Add to Wishlist
                                        </x-secondary-button>
                                    </form>
                                @else
                                    {{-- Message and button to remove from wishlist --}}
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Already on your wishlist.</p>
                                    <form action="{{ route('wishlist.destroy', $book) }}" method="POST">
                                        @csrf
                                        @method('DELETE') {{-- Use DELETE method for removal --}}
                                        <x-danger-button type="submit" class="bg-red-600 hover:bg-red-700">
                                            Remove from Wishlist
                                        </x-danger-button>
                                    </form>
                                @endif

                                {{-- The original Request to Exchange button --}}
                                <form action="{{ route('exchange-requests.store', $book) }}" method="POST">
                                    @csrf
                                    <x-primary-button type="submit">
                                        Request to Exchange
                                    </x-primary-button>
                                </form>
                            @elseif ($book->status !== 'available')
                                <p class="font-semibold text-lg text-yellow-500">This book is currently unavailable.</p>
                            @else
                                <p class="font-semibold text-lg text-gray-500">This is your own book.</p>
                            @endif
                        @endauth
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>