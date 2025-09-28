@extends('layouts.admin')

@section('title', 'Edit Lease')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Lease</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.leases.show', $lease) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-eye mr-2"></i>View Lease
                </a>
                <a href="{{ route('admin.leases.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Leases
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.leases.update', $lease) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tenant Selection -->
                    <div>
                        <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-2">Tenant *</label>
                        <select name="tenant_id" id="tenant_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Select a tenant</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ $lease->tenant_id == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->user->name }} ({{ $tenant->user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('tenant_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Selection -->
                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-2">Unit *</label>
                        <select name="unit_id" id="unit_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Select a unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" data-rent="{{ $unit->rent_amount }}" {{ $lease->unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->unit_number }} - {{ $unit->property->address }} ({{ $unit->size }} sq ft)
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $lease->start_date->format('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $lease->end_date->format('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rent Amount -->
                    <div>
                        <label for="rent_amount" class="block text-sm font-medium text-gray-700 mb-2">Rent Amount (৳) *</label>
                        <input type="number" name="rent_amount" id="rent_amount" value="{{ $lease->rent_amount }}"
                               step="0.01" min="0" max="999999.99"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('rent_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deposit -->
                    <div>
                        <label for="deposit" class="block text-sm font-medium text-gray-700 mb-2">Security Deposit (৳)</label>
                        <input type="number" name="deposit" id="deposit" value="{{ $lease->deposit }}"
                               step="0.01" min="0" max="999999.99"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('deposit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="active" {{ $lease->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ $lease->status == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="terminated" {{ $lease->status == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Document -->
                @if($lease->document_path)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Document</label>
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                <span class="text-sm text-gray-700">Current lease document</span>
                            </div>
                            <a href="{{ route('admin.leases.download-document', $lease) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                @endif

                <!-- New Document Upload -->
                <div class="mt-6">
                    <label for="document" class="block text-sm font-medium text-gray-700 mb-2">Upload New Document (Optional)</label>
                    <div class="border-2 border-gray-300 border-dashed rounded-lg p-6 text-center">
                        <input type="file" name="document" id="document" accept=".pdf,.doc,.docx"
                               class="hidden" onchange="updateFileName(this)">
                        <label for="document" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600">Click to upload new lease document</p>
                            <p class="text-sm text-gray-500">PDF, DOC, or DOCX (max 10MB)</p>
                        </label>
                    </div>
                    <div id="file-name" class="mt-2 text-sm text-gray-600"></div>
                    @error('document')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Leave empty to keep current document</p>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Any additional notes or special terms...">{{ $lease->notes }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('admin.leases.show', $lease) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Lease
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name || 'No file selected';
    document.getElementById('file-name').textContent = fileName;
}

// Auto-populate rent amount when unit is selected
document.getElementById('unit_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const rentAmount = selectedOption.getAttribute('data-rent');
    if (rentAmount && !document.getElementById('rent_amount').value) {
        document.getElementById('rent_amount').value = rentAmount;
    }
});

// Ensure end date is after start date
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = new Date(this.value);
    const endDateInput = document.getElementById('end_date');

    if (endDateInput.value) {
        const endDate = new Date(endDateInput.value);
        if (endDate <= startDate) {
            // Set end date to one year after start date
            const newEndDate = new Date(startDate);
            newEndDate.setFullYear(newEndDate.getFullYear() + 1);
            endDateInput.value = newEndDate.toISOString().split('T')[0];
        }
    }
});
</script>
@endsection
