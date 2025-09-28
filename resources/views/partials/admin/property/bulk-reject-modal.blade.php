{{-- File: resources/views/admin/property/partials/bulk-reject-modal.blade.php --}}

<!-- Bulk Reject Modal -->
<div id="bulkRejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3">
                <h3 class="text-lg font-medium text-gray-900">Bulk Reject Properties</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeBulkRejectModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-2 px-2 py-3">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Bulk Rejection Warning</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>You are about to reject <span id="selectedRejectCount" class="font-semibold">0</span> selected properties. This action will:</p>
                                <ul class="mt-2 list-disc list-inside">
                                    <li>Change registration status to "Rejected"</li>
                                    <li>Prevent landlords from adding units</li>
                                    <li>Send rejection notifications with your reasons</li>
                                    <li>Allow property owners to resubmit after corrections</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="bulkRejectForm" action="{{ route('admin.property.bulk-reject') }}" method="POST">
                    @csrf
                    <input type="hidden" name="property_ids" id="bulkRejectPropertyIds">
                    
                    <div class="mb-4">
                        <label for="bulkRejectNotes" class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="notes" 
                            id="bulkRejectNotes"
                            rows="4" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                            placeholder="Please provide detailed reasons for rejection. This will help property owners understand what needs to be corrected..."></textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Be specific about what needs to be corrected (e.g., missing documents, incomplete information, etc.)
                        </p>
                    </div>

                    <!-- Common Rejection Reasons -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Common Rejection Reasons (Click to add):
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <button type="button" class="text-left px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded border" onclick="addReasonText('Missing required documents')">
                                Missing required documents
                            </button>
                            <button type="button" class="text-left px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded border" onclick="addReasonText('Incomplete property information')">
                                Incomplete property information
                            </button>
                            <button type="button" class="text-left px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded border" onclick="addReasonText('Invalid property images')">
                                Invalid property images
                            </button>
                            <button type="button" class="text-left px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded border" onclick="addReasonText('Property address verification failed')">
                                Address verification failed
                            </button>
                            <button type="button" class="text-left px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded border" onclick="addReasonText('Property type classification error')">
                                Property type error
                            </button>
                            <button type="button" class="text-left px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded border" onclick="addReasonText('Description does not match property')">
                                Description mismatch
                            </button>
                        </div>
                    </div>

                    <!-- Selected Properties Preview -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Selected Properties:
                        </label>
                        <div id="selectedRejectPropertiesPreview" class="bg-gray-50 border border-gray-200 rounded-md p-3 max-h-32 overflow-y-auto">
                            <!-- Selected properties will be displayed here -->
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            onclick="closeBulkRejectModal()">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <i class="fas fa-times-circle mr-2"></i>
                            Reject Selected Properties
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openBulkRejectModal() {
    const checkboxes = document.querySelectorAll('.property-checkbox:checked');
    const propertyIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (propertyIds.length === 0) {
        alert('Please select at least one property to reject.');
        return;
    }

    // Update selected count
    document.getElementById('selectedRejectCount').textContent = propertyIds.length;
    
    // Set property IDs in hidden input
    document.getElementById('bulkRejectPropertyIds').value = JSON.stringify(propertyIds);
    
    // Update selected properties preview
    updateSelectedPropertiesPreview(checkboxes, 'selectedRejectPropertiesPreview');
    
    // Show modal
    document.getElementById('bulkRejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBulkRejectModal() {
    document.getElementById('bulkRejectModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('bulkRejectForm').reset();
    document.getElementById('bulkRejectPropertyIds').value = '';
}

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

// Form validation
document.getElementById('bulkRejectForm').addEventListener('submit', function(e) {
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

// Close modal when clicking outside
document.getElementById('bulkRejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkRejectModal();
    }
});
</script>