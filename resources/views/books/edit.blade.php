<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Book') }}: "{{ $book->title }}"
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH') {{-- Use PATCH method for update --}}

                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $book->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="author" :value="__('Author')" />
                            <x-text-input id="author" class="block mt-1 w-full" type="text" name="author" :value="old('author', $book->author)" required />
                            <x-input-error :messages="$errors->get('author')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="genre" :value="__('Genre')" />
                            <x-text-input id="genre" class="block mt-1 w-full" type="text" name="genre" :value="old('genre', $book->genre)" placeholder="e.g., Fantasy, Sci-Fi, Thriller" />
                            <x-input-error :messages="$errors->get('genre')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="cover_image" :value="__('Current Cover Image')" />
                            @if ($book->cover_image_path)
                                <img src="{{ asset('storage/' . $book->cover_image_path) }}" alt="Current Cover" class="mt-2 mb-2 w-32 h-32 object-cover rounded-md">
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">No image uploaded.</p>
                            @endif
                            <x-input-label for="cover_image" :value="__('Upload New Cover Image (optional)')" class="mt-2" />
                            <input type="file" name="cover_image" id="cover_image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
                        </div>
                        
                        <div class="mt-4">
                            <x-input-label for="condition" :value="__('Condition')" />
                            <select name="condition" id="condition" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="New" {{ old('condition', $book->condition) == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Used - Like New" {{ old('condition', $book->condition) == 'Used - Like New' ? 'selected' : '' }}>Used - Like New</option>
                                <option value="Used - Good" {{ old('condition', $book->condition) == 'Used - Good' ? 'selected' : '' }}>Used - Good</option>
                                <option value="Used - Worn" {{ old('condition', $book->condition) == 'Used - Worn' ? 'selected' : '' }}>Used - Worn</option>
                            </select>
                            <x-input-error :messages="$errors->get('condition')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-3">
                                {{ __('Update Book') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>