<header class="owner-header flex justify-between items-center px-8 py-4">
    <!-- Logo -->
    <div class="logo">
        <h2 class="text-2xl font-extrabold bg-gradient-to-r from-indigo-400 to-purple-600 
                   bg-clip-text text-transparent">
            @if(auth()->check())
                @if(auth()->user()->hasRole('Landlord'))
                    <a href="{{ route('landlord.homepage') }}">HybridEstate</a>
                @elseif(auth()->user()->hasRole('Agent'))
                    <a href="{{ route('agent.homepage') }}">HybridEstate</a>
                @elseif(auth()->user()->hasRole('Tenant'))
                    <a href="{{ route('tenant.homepage') }}">HybridEstate</a>
                @elseif(auth()->user()->hasRole('Buyer'))
                    <a href="{{ url('/') }}">HybridEstate</a>
                @else
                    <a href="{{ url('/') }}">HybridEstate</a>
                @endif
            @else
                <a href="{{ url('/') }}">HybridEstate</a>
            @endif
        </h2>
    </div>

    <!-- Mobile menu button -->
    <button class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Nav Links -->
    <nav class="nav-links flex items-center gap-6">

        {{-- Landlord --}}
        @role('Landlord')
            <a href="{{ route('landlord.homepage') }}" class="relative font-medium text-black hover:text-indigo-400 transition">Home</a>
            
            <!-- Properties & Units Dropdown -->
            <div class="dropdown">
                <button class="dropdown-trigger relative font-medium text-black hover:text-indigo-400 transition">
                    Properties & Units <i class="fas fa-chevron-down ml-1"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('landlord.property.index') }}">
                        <i class="fas fa-building mr-2"></i> My Properties
                    </a>
                    <a href="{{ route('landlord.units.index') }}">
                        <i class="fas fa-home mr-2"></i> My Units
                    </a>
                </div>
            </div>
            
            <!-- Finance Dropdown -->
            <div class="dropdown">
                <button class="dropdown-trigger relative font-medium text-black hover:text-indigo-400 transition">
                    Finance <i class="fas fa-chevron-down ml-1"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('landlord.payments.index') }}">
                        <i class="fas fa-dollar-sign mr-2"></i> Payments
                    </a>
                    <a href="#">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> Billings
                    </a>
                    <a href="#">
                        <i class="fas fa-receipt mr-2"></i> Invoices
                    </a>
                </div>
            </div>
            
            <!-- Tenants & Leases Dropdown -->
            <div class="dropdown">
                <button class="dropdown-trigger relative font-medium text-black hover:text-indigo-400 transition">
                    Tenants & Leases <i class="fas fa-chevron-down ml-1"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('landlord.tenants.index') }}">
                        <i class="fas fa-users mr-2"></i> Tenants
                    </a>
                    <a href="{{ route('landlord.leases.index') }}">
                        <i class="fas fa-file-contract mr-2"></i> Leases
                    </a>
                </div>
            </div>


            
        @endrole

        {{-- Agent --}}
        @role('Agent')
            <a href="{{ route('agent.homepage') }}" class="relative font-medium text-black hover:text-indigo-400 transition">Home</a>
            <a href="{{ route('agent.properties.index') }}" class="relative font-medium text-black hover:text-indigo-400 transition">Property Listings</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Clients</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Leads</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Deals</a>
        @endrole

        {{-- Tenant --}}
        @role('Tenant')
            <a href="{{ route('tenant.homepage') }}" class="relative font-medium text-black hover:text-indigo-400 transition">Home</a>
            <a href="{{ route('tenant.rentals.index') }}" class="relative font-medium text-black hover:text-indigo-400 transition">Find Rentals</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">My Lease</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Payments</a>
        @endrole

        {{-- Buyer --}}
        @role('Buyer')
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Browse Properties</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Saved Listings</a>
            <a href="#" class="relative font-medium text-black hover:text-indigo-400 transition">Inquiries</a>
        @endrole

        <!-- Search bar (common for all roles) -->
        <div class="search-bar flex items-center ml-4">
            <input type="text" id="search" name="search" placeholder="Search..."
                class="px-3 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <button type="button" class="ml-2 text-indigo-500 hover:text-indigo-700" aria-label="Search">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- 👤 Profile dropdown -->
        <div class="profile-dropdown">
            <div class="profile-trigger cursor-pointer">
                @if(Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" 
                         alt="Profile" class="w-10 h-10 rounded-full"/>
                @else
                    <i class="fas fa-user-circle text-3xl text-gray-600"></i>
                @endif
            </div>

            <!-- Dropdown menu -->
            <div class="dropdown-menu">
                <a href="{{ route('profile.edit') }}">
                    <i class="fas fa-bell mr-1"></i> Notifications
                </a>
                <a href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-cog mr-1"></i> Profile
                </a>
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </nav>
</header>

<!-- Floating Messages Icon -->
@if(auth()->check() && (auth()->user()->hasAnyRole(['Admin', 'Landlord', 'Tenant', 'Agent', 'Buyer', 'Maintenance'])))
<div class="fixed bottom-6 right-6 z-40">
    <button onclick="openMessagesModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-4 shadow-lg transition-colors duration-200" title="Messages">
        <i class="fas fa-comments text-xl"></i>
        <span id="unreadBadge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center hidden">0</span>
    </button>
</div>
@endif

<!-- Messages Chatbox Modal -->
<div id="messagesModal" class="fixed bottom-20 right-6 w-80 h-96 bg-white rounded-lg shadow-xl border hidden z-50 flex flex-col">
    <div class="flex items-center justify-between p-4 border-b bg-indigo-600 text-white rounded-t-lg">
        <h3 id="chatTitle" class="text-lg font-medium">Messages</h3>
        <button onclick="closeMessagesModal()" class="text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div id="messagesContent" class="flex-1 overflow-y-auto p-4">
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <p class="mt-2 text-gray-500">Loading messages...</p>
        </div>
    </div>
    <div id="messageInput" class="p-4 border-t hidden">
        <div class="flex gap-2">
            <input type="text" id="messageText" placeholder="Type a message..." class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button onclick="sendMessage()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
let currentChatUserId = null;
let currentChatUserName = null;

function openMessagesModal() {
    document.getElementById('messagesModal').classList.remove('hidden');
    document.getElementById('chatTitle').textContent = 'Messages';
    document.getElementById('messageInput').classList.add('hidden');
    currentChatUserId = null;
    currentChatUserName = null;
    fetchMessages();
}

function closeMessagesModal() {
    document.getElementById('messagesModal').classList.add('hidden');
}

function openChatWithUser(userId, userName) {
    document.getElementById('messagesModal').classList.remove('hidden');
    document.getElementById('chatTitle').textContent = 'Chat with ' + userName;
    document.getElementById('messageInput').classList.remove('hidden');
    currentChatUserId = userId;
    currentChatUserName = userName;
    fetchConversation(userId);
}

function fetchMessages() {
    fetch('{{ route("messages.index") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMessages(data.conversations);
            } else {
                document.getElementById('messagesContent').innerHTML = '<p class="text-center py-8 text-gray-500">Failed to load messages.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching messages:', error);
            document.getElementById('messagesContent').innerHTML = '<p class="text-center py-8 text-gray-500">Error loading messages.</p>';
        });
}

function fetchConversation(userId) {
    fetch(`/messages/${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                displayConversation(data.messages);
            } else {
                console.error('Error fetching conversation:', data.error);
                document.getElementById('messagesContent').innerHTML = '<p class="text-center py-8 text-gray-500">Failed to load conversation.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching conversation:', error);
            document.getElementById('messagesContent').innerHTML = '<p class="text-center py-8 text-gray-500">Error loading conversation.</p>';
        });
}

function displayMessages(conversations) {
    const content = document.getElementById('messagesContent');
    const badge = document.getElementById('unreadBadge');

    // Update unread badge
    const totalUnread = conversations.reduce((sum, conv) => sum + conv.unread, 0);
    if (totalUnread > 0) {
        badge.textContent = totalUnread > 99 ? '99+' : totalUnread;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }

    if (conversations.length === 0) {
        content.innerHTML = '<p class="text-center py-8 text-gray-500">No messages yet.</p>';
        return;
    }

    let html = '<div class="space-y-2">';
    conversations.forEach(conversation => {
        const unreadBadge = conversation.unread > 0 ? `<span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full ml-2">${conversation.unread}</span>` : '';
        html += `
            <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 cursor-pointer" onclick="openConversation(${conversation.user_id}, '${conversation.user_name}')">
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h4 class="font-medium text-gray-900">${conversation.user_name}</h4>
                        ${unreadBadge}
                    </div>
                    <p class="text-sm text-gray-600 truncate">${conversation.last_message}</p>
                    <p class="text-xs text-gray-400">${new Date(conversation.updated_at).toLocaleDateString()}</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        `;
    });
    html += '</div>';

    content.innerHTML = html;
}

function displayConversation(messages) {
    const content = document.getElementById('messagesContent');

    if (messages.length === 0) {
        content.innerHTML = '<p class="text-center py-8 text-gray-500">No messages in this conversation yet.</p>';
        return;
    }

    let html = '<div class="space-y-3">';
    messages.forEach(message => {
        const isSent = message.sender_id == {{ auth()->id() }};
        const messageClass = isSent ? 'bg-indigo-100 ml-8' : 'bg-gray-100 mr-8';
        const alignClass = isSent ? 'justify-end' : 'justify-start';
        html += `
            <div class="flex ${alignClass}">
                <div class="${messageClass} px-3 py-2 rounded-lg max-w-xs">
                    <p class="text-sm">${message.content}</p>
                    <p class="text-xs text-gray-500 mt-1">${new Date(message.created_at).toLocaleTimeString()}</p>
                </div>
            </div>
        `;
    });
    html += '</div>';

    content.innerHTML = html;
    content.scrollTop = content.scrollHeight;
}

function openConversation(userId, userName) {
    openChatWithUser(userId, userName);
}

function sendMessage() {
    const messageText = document.getElementById('messageText');
    const text = messageText.value.trim();

    if (!text || !currentChatUserId) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token meta tag not found');
        alert('CSRF token not found. Please refresh the page.');
        return;
    }

    fetch('{{ route("messages.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            receiver_id: currentChatUserId,
            content: text
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            messageText.value = '';
            fetchConversation(currentChatUserId);
        } else {
            console.error('Server returned error:', data);
            alert('Failed to send message: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Error sending message: ' + error.message);
    });
}

// Allow sending message on Enter key
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('messageText').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});
</script>
