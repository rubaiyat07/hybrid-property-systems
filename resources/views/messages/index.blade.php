@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Messages</h1>

        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Recent Conversations</h2>

                @if($conversations->count() > 0)
                    <div class="space-y-4">
                        @foreach($conversations as $conversation)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer"
                                 onclick="window.location.href='{{ route('messages.conversation', $conversation->other_user_id) }}'">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">
                                            {{ $conversation->other_user_name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ Str::limit($conversation->last_message, 100) }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">
                                            {{ $conversation->last_message_time->diffForHumans() }}
                                        </p>
                                        @if($conversation->unread_count > 0)
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-full mt-1">
                                                {{ $conversation->unread_count }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-comments text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No messages yet</h3>
                        <p class="text-gray-600">Start a conversation by contacting other users.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
