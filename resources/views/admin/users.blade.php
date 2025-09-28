{{-- resources/views/admin/users.blade.php --}}

@extends('layouts.admin')

@section('title', 'User Management - HybridEstate')

@section('content')
<div class="users-management">
    <div class="users-header">
        <h2>User Management</h2>
        <div class="search-filter">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search users..." value="{{ request('search') }}">
            </div>
            <select class="filter-select" id="roleFilter">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="landlord" {{ request('role') == 'landlord' ? 'selected' : '' }}>Landlord</option>
                <option value="tenant" {{ request('role') == 'tenant' ? 'selected' : '' }}>Tenant</option>
                <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                <option value="maintenance" {{ request('role') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Banned</option>
            </select>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add User
            </a>
        </div>
    </div>

    <!-- Bulk Actions Panel -->
    <div id="bulkActions" class="bulk-actions-panel" style="display: none; margin-bottom: 1rem; padding: 1rem; background: #f3f4f6; border-radius: 8px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span id="selectedCount">0</span> users selected
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn btn-ban" onclick="submitBulkAction('ban')">Ban Selected</button>
                <button class="btn btn-view" onclick="submitBulkAction('unban')">Unban Selected</button>
                <button class="btn btn-delete" onclick="submitBulkAction('delete')">Delete Selected</button>
            </div>
        </div>
    </div>

    <!-- Bulk Action Form -->
    <form id="bulkActionForm" action="{{ route('admin.users.bulk-action') }}" method="POST" style="display: none;">
        @csrf
        @method('POST')
        <input type="hidden" name="action" id="actionInput">
        <input type="hidden" name="user_ids" id="userIdsInput">
    </form>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="users-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" onchange="toggleSelectAll(this)">
                    </th>
                    <th>User</th>
                    <th>Role</th>
                    <th>ID</th>
                    <th>Contact Info</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <input type="checkbox" class="user-checkbox" value="{{ $user->id }}" onchange="toggleBulkActions()">
                    </td>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="user-details">
                                <span class="user-name">{{ $user->name }}</span>
                                <span class="user-email">{{ $user->email }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $roleName = $user->getRoleNames()->first() ?? 'user';
                            $roleClass = 'role-' . strtolower($roleName);
                        @endphp
                        <span class="user-role {{ $roleClass }}">
                            {{ $roleName }}
                        </span>
                    </td>
                    <td>
                        <span class="user-id">#{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td>
                        <div class="user-contact">
                            <div><i class="fas fa-phone-alt me-1"></i> {{ $user->phone }}</div>
                            @if($user->address)
                            <div><i class="fas fa-map-marker-alt me-1"></i> {{ Str::limit($user->address, 30) }}</div>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($user->status === 'active')
                            <span class="status-active">Active</span>
                        @else
                            <span class="status-banned">Banned</span>
                        @endif
                    </td>
                    <td>
                        <div class="user-actions">
                            <button class="btn btn-view" onclick="viewUser({{ $user->id }})" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-edit" title="Edit User">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->status === 'active')
                            <button class="btn btn-ban" onclick="banUser({{ $user->id }})" title="Ban User">
                                <i class="fas fa-ban"></i>
                            </button>
                            @else
                            <button class="btn btn-ban" onclick="unbanUser({{ $user->id }})" title="Unban User">
                                <i class="fas fa-check"></i>
                            </button>
                            @endif
                            <button class="btn btn-delete" onclick="deleteUser({{ $user->id }})" title="Delete User">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($users->isEmpty())
        <div class="no-users">
            <i class="fas fa-users"></i>
            <h3>No users found</h3>
            <p>Try adjusting your search or filter criteria</p>
        </div>
    @endif

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="pagination">
         {{ $users->links() }}
     </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Delete</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this user? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="closeModal('deleteModal')">Cancel</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-confirm">Delete</button>
            </form>
        </div>
    </div>
</div>

<!-- Ban Confirmation Modal -->
<div class="modal" id="banModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="banModalTitle">Confirm Ban</h3>
            <button class="modal-close" onclick="closeModal('banModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p id="banModalMessage">Are you sure you want to ban this user?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-cancel" onclick="closeModal('banModal')">Cancel</button>
            <form id="banForm" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-confirm" id="confirmBanButton">Confirm</button>
            </form>
        </div>
    </div>
</div>

<!-- User View Modal -->
<div class="modal" id="viewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>User Details</h3>
            <button class="modal-close" onclick="closeModal('viewModal')">&times;</button>
        </div>
        <div class="modal-body" id="userDetails">
            <!-- User details will be loaded here via AJAX -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('.users-table tbody tr');
    
    rows.forEach(row => {
        const userName = row.querySelector('.user-name').textContent.toLowerCase();
        const userEmail = row.querySelector('.user-email').textContent.toLowerCase();
        if (userName.includes(searchValue) || userEmail.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    toggleBulkActions();
});

// Filter by role
document.getElementById('roleFilter').addEventListener('change', function() {
    filterUsers();
});

// Filter by status
document.getElementById('statusFilter').addEventListener('change', function() {
    filterUsers();
});

function filterUsers() {
    const roleValue = document.getElementById('roleFilter').value;
    const statusValue = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.users-table tbody tr');
    
    rows.forEach(row => {
        const userRole = row.querySelector('.user-role').textContent.toLowerCase();
        const userStatusElement = row.querySelector('.status-active, .status-banned');
        const userStatus = userStatusElement ? userStatusElement.textContent.toLowerCase() : '';
        
        const roleMatch = !roleValue || userRole === roleValue;
        const statusMatch = !statusValue || userStatus === statusValue;
        
        if (roleMatch && statusMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    toggleBulkActions();
}

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
    document.body.style.overflow = '';
}

// User actions
let currentUserId = null;

function viewUser(userId) {
    showLoading();
    currentUserId = userId;
    
    // Fetch user details via AJAX
    fetch(`/admin/users/${userId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(user => {
        document.getElementById('userDetails').innerHTML = `
            <div class="user-detail-item">
                <div class="user-detail-label">ID:</div>
                <div class="user-detail-value">#${String(user.id).padStart(6, '0')}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Name:</div>
                <div class="user-detail-value">${user.name}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Email:</div>
                <div class="user-detail-value">${user.email}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Phone:</div>
                <div class="user-detail-value">${user.phone}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Address:</div>
                <div class="user-detail-value">${user.address || 'N/A'}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Role:</div>
                <div class="user-detail-value">${user.role}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Status:</div>
                <div class="user-detail-value">${user.status}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Joined:</div>
                <div class="user-detail-value">${new Date(user.created_at).toLocaleDateString()}</div>
            </div>
            ${user.last_login ? `
            <div class="user-detail-item">
                <div class="user-detail-label">Last Login:</div>
                <div class="user-detail-value">${new Date(user.last_login).toLocaleString()}</div>
            </div>
            ` : ''}
        `;
        hideLoading();
        openModal('viewModal');
    })
    .catch(error => {
        console.error('Error fetching user details:', error);
        hideLoading();
        alert('Error loading user details');
    });
}

function banUser(userId) {
    currentUserId = userId;
    document.getElementById('banModalTitle').textContent = 'Confirm Ban';
    document.getElementById('banModalMessage').textContent = 'Are you sure you want to ban this user? They will not be able to access their account.';
    document.getElementById('confirmBanButton').textContent = 'Ban User';
    document.getElementById('banForm').action = `/admin/users/${userId}/ban`;
    openModal('banModal');
}

function unbanUser(userId) {
    currentUserId = userId;
    document.getElementById('banModalTitle').textContent = 'Confirm Unban';
    document.getElementById('banModalMessage').textContent = 'Are you sure you want to unban this user? They will regain access to their account.';
    document.getElementById('confirmBanButton').textContent = 'Unban User';
    document.getElementById('banForm').action = `/admin/users/${userId}/unban`;
    openModal('banModal');
}

function deleteUser(userId) {
    currentUserId = userId;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    openModal('deleteModal');
}

// Bulk selection functionality
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    toggleBulkActions();
}

function toggleBulkActions() {
    const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
    const bulkActions = document.getElementById('bulkActions');
    
    if (selectedCount > 0) {
        bulkActions.style.display = 'block';
        document.getElementById('selectedCount').textContent = selectedCount;
    } else {
        bulkActions.style.display = 'none';
    }
}

function submitBulkAction(action) {
    const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedUsers.length === 0) {
        alert('Please select at least one user.');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm('Are you sure you want to delete the selected users? This action cannot be undone.')) {
            return;
        }
    }
    
    document.getElementById('actionInput').value = action;
    document.getElementById('userIdsInput').value = JSON.stringify(selectedUsers);
    document.getElementById('bulkActionForm').submit();
}

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    const modals = document.querySelectorAll('.modal.show');
    modals.forEach(modal => {
        if (e.target === modal) {
            closeModal(modal.id);
        }
    });
});

// Initialize bulk actions
document.addEventListener('DOMContentLoaded', function() {
    toggleBulkActions();
    
    // Apply initial filters from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('role')) {
        filterUsers();
    }
    if (urlParams.get('status')) {
        filterUsers();
    }
});
</script>

@endsection