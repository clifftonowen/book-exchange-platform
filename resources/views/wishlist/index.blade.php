<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Wishlist') }}
        </h2>
    </x-slot>

    {{-- Add this block to display session messages --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 mx-auto max-w-7xl sm:px-6 lg:px-8" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mx-auto max-w-7xl sm:px-6 lg:px-8" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse ($wishlistItems as $wishlistItem)
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow space-y-2">
                                @if ($wishlistItem->book->cover_image_path)
                                    <img src="{{ asset('storage/' . $wishlistItem->book->cover_image_path) }}" alt="Cover of {{ $wishlistItem->book->title }}" class="rounded-md w-full h-48 object-cover mb-2">
                                @endif
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white">
                                    <a href="{{ route('books.show', $wishlistItem->book) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $wishlistItem->book->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">by {{ $wishlistItem->book->author }}</p>
                                <p class="text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 inline-block px-2 py-1 rounded-full">{{ $wishlistItem->book->condition }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Added: {{ $wishlistItem->created_at->format('M d, Y') }}</p>
                                
                                {{-- Remove from Wishlist Button --}}
                                <form action="{{ route('wishlist.destroy', $wishlistItem->book) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button type="submit" class="w-full bg-red-600 hover:bg-red-700">
                                        Remove
                                    </x-danger-button>
                                </form>
                            </div>
                        @empty
                            <p class="col-span-full text-center text-gray-500 dark:text-gray-400">Your wishlist is empty. Start adding books from the dashboard!</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>