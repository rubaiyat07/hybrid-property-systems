// dashboard.js - HybridEstate Admin Dashboard JavaScript

// Toggle dropdown menu
function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
window.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdown');
    const menuIcon = document.querySelector('.menu-icon');

    if (dropdown && (!menuIcon || !menuIcon.contains(event.target)) && !dropdown.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Loading overlay functions
function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

// Modal functions - Updated for all modal types
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = ''; // Re-enable scrolling
}

// Property-specific modal functions
function openBulkApproveModal() {
    const selected = document.querySelectorAll('.property-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select properties to approve.');
        return;
    }
    openModal('bulkApproveModal');
}

function closeBulkApproveModal() {
    closeModal('bulkApproveModal');
}

function openBulkRejectModal() {
    const selected = document.querySelectorAll('.property-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select properties to reject.');
        return;
    }
    openModal('bulkRejectModal');
}

function closeBulkRejectModal() {
    closeModal('bulkRejectModal');
}

function openQuickApproveModal(propertyId, propertyName) {
    document.getElementById('quickApprovePropertyName').textContent = propertyName;
    document.getElementById('quickApproveForm').action = `/admin/property/${propertyId}/approve`;
    openModal('quickApproveModal');
}

function closeQuickApproveModal() {
    closeModal('quickApproveModal');
    document.getElementById('quickApproveForm').reset();
}

function openQuickRejectModal(propertyId, propertyName) {
    document.getElementById('quickRejectPropertyName').textContent = propertyName;
    document.getElementById('quickRejectForm').action = `/admin/property/${propertyId}/reject`;
    openModal('quickRejectModal');
}

function closeQuickRejectModal() {
    closeModal('quickRejectModal');
    document.getElementById('quickRejectForm').reset();
}

function openResetToPendingModal(propertyId, propertyName) {
    document.getElementById('resetToPendingPropertyName').textContent = propertyName;
    document.getElementById('resetToPendingForm').action = `/admin/property/${propertyId}/reset-to-pending`;
    openModal('resetToPendingModal');
}

function closeResetToPendingModal() {
    closeModal('resetToPendingModal');
}

function openPropertyDetailsModal(propertyId) {
    openModal('propertyDetailsModal');
    // Load property details via AJAX
    loadPropertyDetails(propertyId);
}

function closePropertyDetailsModal() {
    closeModal('propertyDetailsModal');
    // Reset content
    document.getElementById('propertyDetailsContent').innerHTML = `
        <div class="flex items-center justify-center py-8">
            <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
            <span class="ml-2 text-gray-600">Loading property details...</span>
        </div>
    `;
}

// Property detail view functions
function loadPropertyDetails(propertyId) {
    const container = document.getElementById('propertyDetailsContent');
    
    fetch(`/admin/property/${propertyId}/quick-view`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                container.innerHTML = generatePropertyDetailsHTML(data.property);
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                        <p class="mt-2 text-red-600">Failed to load property details</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading property details:', error);
            container.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                    <p class="mt-2 text-red-600">Error loading property details</p>
                </div>
            `;
        });
}

function generatePropertyDetailsHTML(property) {
    const statusBadge = getStatusBadge(property.registration_status);
    const imageUrl = property.image || '/images/default-property.jpg';
    
    return `
        <div class="space-y-4">
            <!-- Property Image and Basic Info -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-shrink-0">
                    <img src="${imageUrl}" alt="${property.name}" 
                         class="w-full sm:w-32 h-32 object-cover rounded-lg">
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-lg font-semibold text-gray-900">${property.name}</h4>
                        ${statusBadge}
                    </div>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        ${property.address}, ${property.city}, ${property.state} ${property.zip_code || ''}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-building mr-1"></i>
                        Type: ${property.type ? property.type.charAt(0).toUpperCase() + property.type.slice(1) : 'N/A'}
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-user mr-1"></i>
                        Owner: ${property.owner ? property.owner.name : 'Unknown'}
                    </p>
                </div>
            </div>

            <!-- Property Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h5 class="font-medium text-gray-900 mb-2">Registration Details</h5>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p><strong>ID:</strong> #${property.id}</p>
                        <p><strong>Created:</strong> ${formatDate(property.created_at)}</p>
                        <p><strong>Status:</strong> ${property.registration_status}</p>
                        ${property.approved_at ? `<p><strong>Approved:</strong> ${formatDate(property.approved_at)}</p>` : ''}
                        ${property.approver ? `<p><strong>Approved By:</strong> ${property.approver.name}</p>` : ''}
                    </div>
                </div>
                
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h5 class="font-medium text-gray-900 mb-2">Property Info</h5>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p><strong>Price/Rent:</strong> ${property.price_or_rent ? '$' + parseFloat(property.price_or_rent).toLocaleString() : 'Not specified'}</p>
                        <p><strong>Units:</strong> ${property.units_count || 0}</p>
                        <p><strong>Status:</strong> ${property.status}</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            ${property.description ? `
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h5 class="font-medium text-gray-900 mb-2">Description</h5>
                    <p class="text-sm text-gray-600">${property.description}</p>
                </div>
            ` : ''}

            <!-- Registration Notes -->
            ${property.registration_notes ? `
                <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                    <h5 class="font-medium text-yellow-900 mb-2">
                        <i class="fas fa-sticky-note mr-1"></i>
                        Admin Notes
                    </h5>
                    <p class="text-sm text-yellow-800">${property.registration_notes}</p>
                </div>
            ` : ''}

            <!-- Quick Actions -->
            <div class="flex justify-end space-x-2 pt-4 border-t">
                <a href="/admin/property/${property.id}" 
                   class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    <i class="fas fa-eye mr-1"></i>
                    View Details
                </a>
                ${property.registration_status === 'pending' ? `
                    <button onclick="closePropertyDetailsModal(); openQuickApproveModal(${property.id}, '${property.name}')" 
                            class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                        <i class="fas fa-check mr-1"></i>
                        Approve
                    </button>
                    <button onclick="closePropertyDetailsModal(); openQuickRejectModal(${property.id}, '${property.name}')" 
                            class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                        <i class="fas fa-times mr-1"></i>
                        Reject
                    </button>
                ` : `
                    <button onclick="closePropertyDetailsModal(); openResetToPendingModal(${property.id}, '${property.name}')" 
                            class="px-3 py-1 text-sm bg-yellow-600 text-white rounded hover:bg-yellow-700">
                        <i class="fas fa-undo mr-1"></i>
                        Reset
                    </button>
                `}
            </div>
        </div>
    `;
}

function getStatusBadge(status) {
    switch(status) {
        case 'pending':
            return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>';
        case 'approved':
            return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>';
        case 'rejected':
            return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>';
        default:
            return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
    }
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Add reason text to rejection forms
function addReasonText(reason) {
    const textarea = document.getElementById('bulkRejectNotes');
    const currentValue = textarea.value.trim();
    
    if (currentValue === '') {
        textarea.value = reason;
    } else {
        // Add reason with a separator if text already exists
        textarea.value = currentValue + '\n\n• ' + reason;
    }
    
    // Focus on textarea for further editing
    textarea.focus();
    // Move cursor to end
    textarea.setSelectionRange(textarea.value.length, textarea.value.length);
}

function addQuickRejectReason(reason) {
    const textarea = document.getElementById('quickRejectNotes');
    const currentValue = textarea.value.trim();
    
    if (currentValue === '') {
        textarea.value = reason;
    } else {
        textarea.value = currentValue + '\n\n• ' + reason;
    }
    
    textarea.focus();
    textarea.setSelectionRange(textarea.value.length, textarea.value.length);
}

// Update selected properties preview
function updateSelectedPropertiesPreview(checkboxes, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    
    checkboxes.forEach(checkbox => {
        const row = checkbox.closest('tr');
        const propertyName = row.querySelector('.property-name')?.textContent || 'Unknown Property';
        const propertyId = checkbox.value;
        
        const propertyItem = document.createElement('div');
        propertyItem.className = 'text-sm text-gray-700 py-1';
        propertyItem.innerHTML = `<i class="fas fa-building mr-2 text-gray-400"></i>#${propertyId} - ${propertyName}`;
        container.appendChild(propertyItem);
    });
}

// Users Management Functions
function initUsersManagement() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
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
        });
    }

    // Filter by role
    const roleFilter = document.getElementById('roleFilter');
    if (roleFilter) {
        roleFilter.addEventListener('change', filterUsers);
    }

    // Filter by status
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', filterUsers);
    }

    // Initialize bulk actions
    initBulkActions();
}

function filterUsers() {
    const roleValue = document.getElementById('roleFilter')?.value || '';
    const statusValue = document.getElementById('statusFilter')?.value || '';
    const rows = document.querySelectorAll('.users-table tbody tr');
    
    rows.forEach(row => {
        if (row.style.display === 'none') return; // Skip already hidden rows
        
        const userRole = row.querySelector('.user-role')?.textContent.toLowerCase() || '';
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
}

// User actions
let currentUserId = null;

function viewUser(userId) {
    showLoading();
    
    // Simulate API call - in real application, fetch user data from server
    setTimeout(() => {
        // This would be replaced with actual API call
        const user = {
            id: userId,
            name: 'User ' + userId,
            email: 'user' + userId + '@example.com',
            phone: '+1234567890',
            address: '123 Main St, City, State',
            role: 'Admin',
            status: 'Active',
            joined: '2023-01-15',
            last_login: '2023-10-25 14:30:45'
        };
        
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
                <div class="user-detail-value">${user.address}</div>
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
                <div class="user-detail-value">${user.joined}</div>
            </div>
            <div class="user-detail-item">
                <div class="user-detail-label">Last Login:</div>
                <div class="user-detail-value">${user.last_login}</div>
            </div>
        `;
        
        hideLoading();
        openModal('viewModal');
    }, 500);
}

function editUser(userId) {
    // Redirect to edit page
    window.location.href = `/admin/users/${userId}/edit`;
}

function banUser(userId) {
    currentUserId = userId;
    document.getElementById('banModalTitle').textContent = 'Confirm Ban';
    document.getElementById('banModalMessage').textContent = 'Are you sure you want to ban this user? They will not be able to access their account.';
    document.getElementById('confirmBan').textContent = 'Ban User';
    document.getElementById('confirmBan').onclick = confirmBanAction;
    openModal('banModal');
}

function unbanUser(userId) {
    currentUserId = userId;
    document.getElementById('banModalTitle').textContent = 'Confirm Unban';
    document.getElementById('banModalMessage').textContent = 'Are you sure you want to unban this user? They will regain access to their account.';
    document.getElementById('confirmBan').textContent = 'Unban User';
    document.getElementById('confirmBan').onclick = confirmUnbanAction;
    openModal('banModal');
}

function deleteUser(userId) {
    currentUserId = userId;
    openModal('deleteModal');
}

// Confirm actions
function confirmBanAction() {
    showLoading();
    
    // Simulate API call - in real application, send request to server
    setTimeout(() => {
        // Find the user row and update status
        const userRow = document.querySelector(`tr:has(input[value="${currentUserId}"])`);
        if (userRow) {
            const statusCell = userRow.querySelector('td:nth-child(5)');
            statusCell.innerHTML = '<span class="status-banned">Banned</span>';
            
            // Update ban button to unban
            const actionsCell = userRow.querySelector('td:nth-child(6)');
            const banButton = actionsCell.querySelector('.btn-ban');
            banButton.innerHTML = '<i class="fas fa-check"></i>';
            banButton.onclick = function() { unbanUser(currentUserId); };
        }
        
        hideLoading();
        closeModal('banModal');
        showNotification('User banned successfully.', 'success');
    }, 500);
}

function confirmUnbanAction() {
    showLoading();
    
    // Simulate API call - in real application, send request to server
    setTimeout(() => {
        // Find the user row and update status
        const userRow = document.querySelector(`tr:has(input[value="${currentUserId}"])`);
        if (userRow) {
            const statusCell = userRow.querySelector('td:nth-child(5)');
            statusCell.innerHTML = '<span class="status-active">Active</span>';
            
            // Update unban button to ban
            const actionsCell = userRow.querySelector('td:nth-child(6)');
            const banButton = actionsCell.querySelector('.btn-ban');
            banButton.innerHTML = '<i class="fas fa-ban"></i>';
            banButton.onclick = function() { banUser(currentUserId); };
        }
        
        hideLoading();
        closeModal('banModal');
        showNotification('User unbanned successfully.', 'success');
    }, 500);
}

document.getElementById('confirmDelete')?.addEventListener('click', function() {
    showLoading();
    
    // Simulate API call - in real application, send request to server
    setTimeout(() => {
        // Remove the user row from table
        const userRow = document.querySelector(`tr:has(input[value="${currentUserId}"])`);
        if (userRow) {
            userRow.remove();
        }
        
        hideLoading();
        closeModal('deleteModal');
        showNotification('User deleted successfully.', 'success');
        toggleBulkActions(); // Update bulk actions panel
    }, 500);
});

// Bulk selection functionality
function initBulkActions() {
    const bulkActionForm = document.getElementById('bulkActionForm');
    if (!bulkActionForm) return;
    
    // Add event listeners to all checkboxes
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });
}

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
    
    if (selectedCount > 0 && bulkActions) {
        bulkActions.style.display = 'block';
        document.getElementById('selectedCount').textContent = selectedCount;
    } else if (bulkActions) {
        bulkActions.style.display = 'none';
    }
}

function submitBulkAction(action) {
    const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedUsers.length === 0) {
        showNotification('Please select at least one user.', 'error');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm('Are you sure you want to delete the selected users? This action cannot be undone.')) {
            return;
        }
    }
    
    const form = document.getElementById('bulkActionForm');
    document.getElementById('actionInput').value = action;
    document.getElementById('userIdsInput').value = JSON.stringify(selectedUsers);
    
    showLoading();
    form.submit();
}

// Notification system
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notification => {
        notification.remove();
    });
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `custom-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize users management if on users page
    if (document.querySelector('.users-management')) {
        initUsersManagement();
    }
    
    // Property management initialization
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.property-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }
    
    // Form validation for rejection forms
    const bulkRejectForm = document.getElementById('bulkRejectForm');
    if (bulkRejectForm) {
        bulkRejectForm.addEventListener('submit', function(e) {
            const notes = document.getElementById('bulkRejectNotes').value.trim();
            
            if (notes === '') {
                e.preventDefault();
                alert('Please provide a reason for rejection.');
                document.getElementById('bulkRejectNotes').focus();
                return false;
            }
            
            if (notes.length < 10) {
                e.preventDefault();
                alert('Please provide a more detailed reason (at least 10 characters).');
                document.getElementById('bulkRejectNotes').focus();
                return false;
            }
            
            // Confirmation dialog
            const count = document.getElementById('selectedRejectCount').textContent;
            if (!confirm(`Are you sure you want to reject ${count} selected properties? This action will notify the property owners.`)) {
                e.preventDefault();
                return false;
            }
        });
    }
    
    const quickRejectForm = document.getElementById('quickRejectForm');
    if (quickRejectForm) {
        quickRejectForm.addEventListener('submit', function(e) {
            const notes = document.getElementById('quickRejectNotes').value.trim();
            
            if (notes === '') {
                e.preventDefault();
                alert('Please provide a reason for rejection.');
                document.getElementById('quickRejectNotes').focus();
                return false;
            }
            
            if (notes.length < 10) {
                e.preventDefault();
                alert('Please provide a more detailed reason (at least 10 characters).');
                document.getElementById('quickRejectNotes').focus();
                return false;
            }
        });
    }
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal:not(.hidden)');
            modals.forEach(modal => {
                const modalId = modal.id;
                if (modalId === 'quickApproveModal') closeQuickApproveModal();
                else if (modalId === 'quickRejectModal') closeQuickRejectModal();
                else if (modalId === 'resetToPendingModal') closeResetToPendingModal();
                else if (modalId === 'propertyDetailsModal') closePropertyDetailsModal();
                else if (modalId === 'bulkApproveModal') closeBulkApproveModal();
                else if (modalId === 'bulkRejectModal') closeBulkRejectModal();
                else closeModal(modalId);
            });
        }
    });
    
    // Add click outside to close modals
    document.addEventListener('click', function(e) {
        const modals = document.querySelectorAll('.modal:not(.hidden)');
        modals.forEach(modal => {
            if (e.target === modal) {
                const modalId = modal.id;
                if (modalId === 'quickApproveModal') closeQuickApproveModal();
                else if (modalId === 'quickRejectModal') closeQuickRejectModal();
                else if (modalId === 'resetToPendingModal') closeResetToPendingModal();
                else if (modalId === 'propertyDetailsModal') closePropertyDetailsModal();
                else if (modalId === 'bulkApproveModal') closeBulkApproveModal();
                else if (modalId === 'bulkRejectModal') closeBulkRejectModal();
                else closeModal(modalId);
            }
        });
    });
});

function initDashboardCharts() {
    // This would initialize charts using Chart.js or similar library
    console.log('Dashboard charts initialized');
}

// Responsive sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    const content = document.querySelector('.admin-content');
    
    if (sidebar && content) {
        sidebar.classList.toggle('mobile-hidden');
        content.classList.toggle('full-width');
    }
}

// Add CSS for notifications
const notificationStyles = `
<style>
.custom-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 8px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-width: 300px;
    max-width: 500px;
    z-index: 10000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease;
}

.custom-notification.success {
    background: #10b981;
}

.custom-notification.error {
    background: #ef4444;
}

.custom-notification.info {
    background: #3b82f6;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.notification-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    margin-left: 15px;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .custom-notification {
        min-width: auto;
        width: calc(100% - 40px);
        left: 20px;
        right: 20px;
    }
}
</style>
`;

// Inject notification styles
document.head.insertAdjacentHTML('beforeend', notificationStyles);
