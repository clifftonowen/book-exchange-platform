<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Listed Books') }}
        </h2>
    </x-slot>


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

                    <h4 class="font-bold text-xl dark:text-white mb-4">My Books for Exchange</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse ($myBooks as $book)
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow space-y-2 flex flex-col justify-between"> 
                                <a href="{{ route('books.show', $book) }}" class="block h-full hover:ring-2 hover:ring-indigo-500 transition-all rounded-md"> 
                                    @if ($book->cover_image_path)
                                        <img src="{{ asset('storage/' . $book->cover_image_path) }}" alt="Cover of {{ $book->title }}" class="rounded-md w-full h-48 object-cover mb-2">
                                    @endif

                                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $book->title }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">by {{ $book->author }}</p>
                                    <p class="text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 inline-block px-2 py-1 rounded-full">{{ $book->condition }}</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Status: {{ ucfirst($book->status) }}</p>
                                </a> 

                                <div class="mt-4"> 
                                    <div class="mt-4 flex space-x-2"> 
                                        <a href="{{ route('books.edit', $book) }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Edit
                                        </a>
                                        <form action="{{ route('books.destroy', $book) }}" method="POST">
                                            @csrf
                                            @method('DELETE') 
                                            <x-danger-button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-700"> 
                                                Delete
                                            </x-danger-button>
                                        </form>
                                    </div>
                                </div>
                            </div> 
                        @empty
                            <p class="col-span-full">You haven't listed any books yet. Click "List a New Book" to get started!</p>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            + List a New Book
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>