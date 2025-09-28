{{-- File: resources/views/admin/property/partials/quick-action-modals.blade.php --}}

<!-- Quick Approve Modal -->
<div id="quickApproveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3">
                <h3 class="text-lg font-medium text-gray-900">Quick Approve Property</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeQuickApproveModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-2 px-2 py-3">
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">
                                Approve property: <span id="quickApprovePropertyName" class="font-semibold"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <form id="quickApproveForm" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="quickApproveNotes" class="block text-sm font-medium text-gray-700 mb-2">
                            Approval Notes (Optional)
                        </label>
                        <textarea 
                            name="notes" 
                            id="quickApproveNotes"
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                            placeholder="Add any notes for this approval..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            onclick="closeQuickApproveModal()">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-check mr-2"></i>
                            Approve Property
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quick Reject Modal -->
<div id="quickRejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3">
                <h3 class="text-lg font-medium text-gray-900">Quick Reject Property</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeQuickRejectModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-2 px-2 py-3">
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800">
                                Reject property: <span id="quickRejectPropertyName" class="font-semibold"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <form id="quickRejectForm" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="quickRejectNotes" class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="notes" 
                            id="quickRejectNotes"
                            rows="4" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                            placeholder="Please provide detailed reasons for rejection..."></textarea>
                    </div>

                    <!-- Quick Rejection Reasons -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Quick Reasons (Click to add):
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <button type="button" class="text-left px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded border" onclick="addQuickRejectReason('Missing required documents')">
                                Missing documents
                            </button>
                            <button type="button" class="text-left px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded border" onclick="addQuickRejectReason('Incomplete information')">
                                Incomplete information
                            </button>
                            <button type="button" class="text-left px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded border" onclick="addQuickRejectReason('Invalid images')">
                                Invalid images
                            </button>
                            <button type="button" class="text-left px-2 py-1 text-xs bg-gray-100 hover:bg-gray-200 rounded border" onclick="addQuickRejectReason('Address verification failed')">
                                Address verification failed
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            onclick="closeQuickRejectModal()">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <i class="fas fa-times-circle mr-2"></i>
                            Reject Property
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reset to Pending Modal -->
<div id="resetToPendingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3">
                <h3 class="text-lg font-medium text-gray-900">Reset to Pending</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeResetToPendingModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-2 px-2 py-3">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800">
                                Reset property: <span id="resetToPendingPropertyName" class="font-semibold"></span> back to pending status?
                            </p>
                            <p class="text-xs text-yellow-700 mt-1">
                                This will remove current approval/rejection status and allow re-review.
                            </p>
                        </div>
                    </div>
                </div>

                <form id="resetToPendingForm" method="POST">
                    @csrf
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            onclick="closeResetToPendingModal()">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <i class="fas fa-undo mr-2"></i>
                            Reset to Pending
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Property Details Quick View Modal -->
<div id="propertyDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3">
                <h3 class="text-lg font-medium text-gray-900">Property Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closePropertyDetailsModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div id="propertyDetailsContent" class="mt-2 px-2 py-3">
                <!-- Property details will be loaded here via AJAX -->
                <div class="flex items-center justify-center py-8">
                    <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
                    <span class="ml-2 text-gray-600">Loading property details...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Quick Approve Modal Functions
function openQuickApproveModal(propertyId, propertyName) {
    document.getElementById('quickApprovePropertyName').textContent = propertyName;
    document.getElementById('quickApproveForm').action = `/admin/property/${propertyId}/approve`;
    document.getElementById('quickApproveModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeQuickApproveModal() {
    document.getElementById('quickApproveModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('quickApproveForm').reset();
}

// Quick Reject Modal Functions
function openQuickRejectModal(propertyId, propertyName) {
    document.getElementById('quickRejectPropertyName').textContent = propertyName;
    document.getElementById('quickRejectForm').action = `/admin/property/${propertyId}/reject`;
    document.getElementById('quickRejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeQuickRejectModal() {
    document.getElementById('quickRejectModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('quickRejectForm').reset();
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

// Reset to Pending Modal Functions
function openResetToPendingModal(propertyId, propertyName) {
    document.getElementById('resetToPendingPropertyName').textContent = propertyName;
    document.getElementById('resetToPendingForm').action = `/admin/property/${propertyId}/reset-to-pending`;
    document.getElementById('resetToPendingModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeResetToPendingModal() {
    document.getElementById('resetToPendingModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Property Details Modal Functions
function openPropertyDetailsModal(propertyId) {
    document.getElementById('propertyDetailsModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Load property details via AJAX
    loadPropertyDetails(propertyId);
}

function closePropertyDetailsModal() {
    document.getElementById('propertyDetailsModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset content
    document.getElementById('propertyDetailsContent').innerHTML = `
        <div class="flex items-center justify-center py-8">
            <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
            <span class="ml-2 text-gray-600">Loading property details...</span>
        </div>
    `;
}

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
                        <p><strong>Price/Rent:</strong> ${property.price_or_rent ? ' + parseFloat(property.price_or_rent).toLocaleString() : 'Not specified'}</p>
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

// Form Validation
document.getElementById('quickRejectForm').addEventListener('submit', function(e) {
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

// Close modals when clicking outside
['quickApproveModal', 'quickRejectModal', 'resetToPendingModal', 'propertyDetailsModal'].forEach(modalId => {
    document.getElementById(modalId).addEventListener('click', function(e) {
        if (e.target === this) {
            switch(modalId) {
                case 'quickApproveModal':
                    closeQuickApproveModal();
                    break;
                case 'quickRejectModal':
                    closeQuickRejectModal();
                    break;
                case 'resetToPendingModal':
                    closeResetToPendingModal();
                    break;
                case 'propertyDetailsModal':
                    closePropertyDetailsModal();
                    break;
            }
        }
    });
});

// Keyboard event handlers
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Close any open modal
        if (!document.getElementById('quickApproveModal').classList.contains('hidden')) {
            closeQuickApproveModal();
        }
        if (!document.getElementById('quickRejectModal').classList.contains('hidden')) {
            closeQuickRejectModal();
        }
        if (!document.getElementById('resetToPendingModal').classList.contains('hidden')) {
            closeResetToPendingModal();
        }
        if (!document.getElementById('propertyDetailsModal').classList.contains('hidden')) {
            closePropertyDetailsModal();
        }
    }
});
</script>