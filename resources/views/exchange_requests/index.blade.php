<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Exchange Requests') }}
        </h2>
    </x-slot>

=
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

                    <div class="mb-6">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 dark:hover:bg-gray-600 focus:bg-gray-500 dark:focus:bg-gray-600 active:bg-gray-700 dark:active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← Back to Dashboard
                        </a>
                    </div>

                    @forelse ($incomingRequests as $request)
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow mb-4">
                            <p class="text-lg font-semibold dark:text-white">
                                Request for: <a href="{{ route('books.show', $request->book) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $request->book->title }}</a>
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                From: <a href="{{ route('users.show', $request->requester) }}" class="font-medium text-indigo-500 hover:underline">{{ $request->requester->name }}</a>
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Status: <span class="font-medium capitalize">{{ $request->status }}</span>
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Requested On: {{ $request->created_at->format('M d, H:i') }}
                            </p>

                            <div class="mt-4 flex space-x-2">
                                @if ($request->status === 'pending' && auth()->id() === $request->book->user_id)
                                    {{-- Show Accept/Reject only to the book owner for pending requests --}}
                                    <form action="{{ route('exchange-requests.accept', $request) }}" method="POST">
                                        @csrf
                                        <x-primary-button type="submit" class="bg-green-600 hover:bg-green-700">
                                            Accept
                                        </x-primary-button>
                                    </form>

                                    <form action="{{ route('exchange-requests.reject', $request) }}" method="POST">
                                        @csrf
                                        @method('DELETE') 
                                        <x-danger-button type="submit" class="bg-red-600 hover:bg-red-700">
                                            Reject
                                        </x-danger-button>
                                    </form>
                                @elseif ($request->status === 'accepted' && (auth()->id() === $request->book->user_id || auth()->id() === $request->requester_id))
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-semibold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                        Accepted! Coordinate exchange.
                                    </p>
                                    <form action="{{ route('exchange-requests.complete', $request) }}" method="POST">
                                        @csrf
                                        <x-secondary-button type="submit">
                                            Mark as Completed
                                        </x-secondary-button>
                                    </form>
=
                                    <a href="{{ route('messages.show', $request) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Message
                                    </a>
                                @elseif ($request->status === 'rejected')
                                    <p class="text-sm text-red-600 dark:text-red-400 font-semibold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                        Request Rejected.
                                    </p>
                                @elseif ($request->status === 'completed')
                                    <p class="text-sm text-green-600 dark:text-green-400 font-semibold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        Exchange Completed!
                                    </p>
                                    @php
                                        $hasUserRated = $request->rating()->where('rater_id', auth()->id())->exists();
                                        $otherPartyId = (auth()->id() === $request->book->user_id) ? $request->requester_id : $request->book->user_id;
                                    @endphp

                                    @if (!$hasUserRated && (auth()->id() === $request->book->user_id || auth()->id() === $request->requester_id))
                                        <div class="mt-2">
                                            <a href="{{ route('ratings.create', ['exchangeRequest' => $request->id, 'rateeId' => $otherPartyId]) }}"
                                               class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Leave a Review
                                            </a>
                                        </div>
                                    @elseif ($hasUserRated)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">You have already reviewed this exchange.</p>
                                    @endif

                                    <a href="{{ route('messages.show', $request) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2">
                                        View Messages
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p>You have no pending exchange requests for your books at the moment.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>