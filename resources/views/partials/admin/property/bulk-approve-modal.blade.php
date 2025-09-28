{{-- File: resources/views/admin/property/partials/bulk-approve-modal.blade.php --}}

<!-- Bulk Approve Modal -->
<div id="bulkApproveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3">
                <h3 class="text-lg font-medium text-gray-900">Bulk Approve Properties</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeBulkApproveModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-2 px-2 py-3">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Bulk Approval</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>You are about to approve <span id="selectedCount" class="font-semibold">0</span> selected properties. This action will:</p>
                                <ul class="mt-2 list-disc list-inside">
                                    <li>Change registration status to "Approved"</li>
                                    <li>Allow landlords to add units to these properties</li>
                                    <li>Send approval notifications to property owners</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="bulkApproveForm" action="{{ route('admin.property.bulk-approve') }}" method="POST">
                    @csrf
                    <input type="hidden" name="property_ids" id="bulkApprovePropertyIds">
                    
                    <div class="mb-4">
                        <label for="bulkApproveNotes" class="block text-sm font-medium text-gray-700 mb-2">
                            Approval Notes (Optional)
                        </label>
                        <textarea 
                            name="notes" 
                            id="bulkApproveNotes"
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Enter any notes for the approval (visible to property owners)..."></textarea>
                    </div>

                    <!-- Selected Properties Preview -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Selected Properties:
                        </label>
                        <div id="selectedPropertiesPreview" class="bg-gray-50 border border-gray-200 rounded-md p-3 max-h-32 overflow-y-auto">
                            <!-- Selected properties will be displayed here -->
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button 
                            type="button" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            onclick="closeBulkApproveModal()">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-check mr-2"></i>
                            Approve Selected Properties
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openBulkApproveModal() {
    const checkboxes = document.querySelectorAll('.property-checkbox:checked');
    const propertyIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (propertyIds.length === 0) {
        alert('Please select at least one property to approve.');
        return;
    }

    // Update selected count
    document.getElementById('selectedCount').textContent = propertyIds.length;
    
    // Set property IDs in hidden input
    document.getElementById('bulkApprovePropertyIds').value = JSON.stringify(propertyIds);
    
    // Update selected properties preview
    updateSelectedPropertiesPreview(checkboxes, 'selectedPropertiesPreview');
    
    // Show modal
    document.getElementById('bulkApproveModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBulkApproveModal() {
    document.getElementById('bulkApproveModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('bulkApproveForm').reset();
    document.getElementById('bulkApprovePropertyIds').value = '';
}

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

// Close modal when clicking outside
document.getElementById('bulkApproveModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkApproveModal();
    }
});
</script>