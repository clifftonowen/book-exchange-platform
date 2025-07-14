<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Session success/error messages display --}}
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

                    {{-- Search Form --}}
                    <form action="{{ route('dashboard') }}" method="GET" class="mb-6">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Search by title or author..."
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-l-md shadow-sm"
                                value="{{ request('search') }}">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-r-md hover:bg-indigo-500">
                                Search
                            </button>
                        </div>
                    </form>

                    {{--AI-Powered Recommendations Section --}}
                    @if (!empty($recommendations))
                        <div class="mb-8">
                            <h4 class="font-bold text-xl dark:text-white mb-4">Recommended for You</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($recommendations as $rec)
                                    <div class="bg-indigo-50 dark:bg-indigo-900/50 p-4 rounded-lg shadow">
                                        <h5 class="font-semibold text-lg text-indigo-800 dark:text-indigo-200">{{ $rec['title'] }}</h5>
                                        <p class="text-sm text-indigo-600 dark:text-indigo-400">by {{ $rec['author'] }}</p>
                                        <p class="text-xs text-indigo-500 dark:text-indigo-300 mt-2">
                                            </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="mb-8 text-gray-500 dark:text-gray-400">No recommendations available at the moment. Make sure your OpenAI API key is set and try listing some books!</p>
                    @endif

                    <h4 class="font-bold text-xl dark:text-white mb-4">Available Books</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse ($books as $book)
                            <a href="{{ route('books.show', $book) }}">
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow space-y-2 hover:ring-2 hover:ring-indigo-500 transition-all">

                                    @if ($book->cover_image_path)
                                        <img src="{{ asset('storage/' . $book->cover_image_path) }}" alt="Cover of {{ $book->title }}" class="rounded-md w-full h-48 object-cover mb-2">
                                    @endif

                                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $book->title }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">by {{ $book->author }}</p>
                                    <p class="text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 inline-block px-2 py-1 rounded-full">{{ $book->condition }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="col-span-full">No books have been listed yet.</p>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-black uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-2 transition ease-in-out duration-150">
                            + List a New Book
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>