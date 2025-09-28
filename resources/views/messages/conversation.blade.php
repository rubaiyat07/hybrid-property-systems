@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Header -->
            <div class="border-b px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <button onclick="window.history.back()" class="mr-4 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-900">
                            Conversation with {{ $otherUser->name }}
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div id="messages-container" class="h-96 overflow-y-auto p-6 space-y-4">
                @foreach($messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }}">
                            <p class="text-sm">{{ $message->content }}</p>
                            <p class="text-xs mt-1 opacity-75">
                                {{ $message->created_at->format('M j, g:i A') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="border-t px-6 py-4">
                <form id="message-form" class="flex space-x-4">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                    <div class="flex-1">
                        <textarea name="content" id="message-content"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                  rows="3" placeholder="Type your message..."></textarea>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-paper-plane mr-2"></i>Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const content = formData.get('content').trim();

    if (!content) {
        alert('Please enter a message');
        return;
    }

    fetch('{{ route("messages.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add the new message to the UI
            const messagesContainer = document.getElementById('messages-container');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex justify-end';
            messageDiv.innerHTML = `
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-blue-600 text-white">
                    <p class="text-sm">${content}</p>
                    <p class="text-xs mt-1 opacity-75">Just now</p>
                </div>
            `;
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Clear the input
            document.getElementById('message-content').value = '';
        } else {
            alert('Error sending message: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending message');
    });
});

// Auto-scroll to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
});
</script>
@endsection
