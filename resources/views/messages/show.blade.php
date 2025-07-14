<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Messages for') }} "{{ $exchangeRequest->book->title }}"
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

                    {{-- Back to Exchange Requests button --}}
                    <div class="mb-6">
                        <a href="{{ route('exchange-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 dark:hover:bg-gray-600 focus:bg-gray-500 dark:focus:bg-gray-600 active:bg-gray-700 dark:active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← Back to Exchange Requests
                        </a>
                    </div>

                    {{-- Conversation Header --}}
                    <h3 class="font-bold text-xl dark:text-white mb-4">
                        Conversation with: {{ $otherParticipant->name }}
                    </h3>

                    <div class="space-y-4 h-96 overflow-y-auto p-4 border border-gray-300 dark:border-gray-700 rounded-lg mb-6">
                        @forelse ($messages as $message)
                            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs p-3 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-600 dark:text-gray-200' }}">
                                    <p class="font-semibold text-sm">{{ $message->sender->name }}</p>
                                    <p class="text-sm">{{ $message->content }}</p>
                                    <p class="text-xs opacity-75 mt-1">{{ $message->created_at->format('M d, H:i') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center">No messages yet. Start the conversation!</p>
                        @endforelse
                    </div>

                    <form method="POST" action="{{ route('messages.store', $exchangeRequest) }}">
                        @csrf
                        <div class="flex items-center">
                            <textarea name="content" rows="1" placeholder="Type your message..."
                                      class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-l-md shadow-sm resize-none"></textarea>
                            <x-primary-button type="submit" class="px-4 py-2 rounded-r-md">
                                Send
                            </x-primary-button>
                        </div>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>