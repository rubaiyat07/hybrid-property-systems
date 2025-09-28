{{-- File: resources/views/admin/property/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Property Review - ' . $property->name)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Property Registration Review</h1>
            <p class="text-gray-600 mt-1">{{ $property->name }} - ID: #{{ $property->id }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.property.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Registration Status Banner -->
    <div class="mb-6">
        @if($property->registration_status === 'pending')
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <strong>Pending Review:</strong> This property registration is awaiting your approval.
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="showApproveModal()"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-check mr-1"></i> Approve
                        </button>
                        <button onclick="showRejectModal()"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-times mr-1"></i> Reject
                        </button>
                    </div>
                </div>
            </div>
        @elseif($property->registration_status === 'approved')
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <div>
                            <strong>Property Approved</strong>
                            @if($property->approved_at && $property->approver)
                                <br><small>Approved by {{ $property->approver->name }} on {{ $property->approved_at->format('M d, Y \a\t g:i A') }}</small>
                            @endif
                        </div>
                    </div>
                    <button onclick="resetToPending()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-undo mr-1"></i> Reset to Pending
                    </button>
                </div>
            </div>
        @elseif($property->registration_status === 'rejected')
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle mr-2"></i>
                        <div>
                            <strong>Property Rejected</strong>
                            @if($property->approved_at && $property->approver)
                                <br><small>Rejected by {{ $property->approver->name }} on {{ $property->approved_at->format('M d, Y \a\t g:i A') }}</small>
                            @endif
                        </div>
                    </div>
                    <button onclick="resetToPending()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-undo mr-1"></i> Reset to Pending
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Property Image -->
            @if($property->image)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <img src="{{ $property->image }}" alt="{{ $property->name }}"
                         class="w-full h-64 object-cover">
                    <div class="p-4">
                        <p class="text-sm text-gray-600">Property Image</p>
                    </div>
                </div>
            @endif

            <!-- Property Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Property Information</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Property Name</dt>
                            <dd class="text-sm text-gray-900 font-medium">{{ $property->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Property Type</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($property->type) }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="text-sm text-gray-900">{{ $property->address }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">City</dt>
                            <dd class="text-sm text-gray-900">{{ $property->city }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">State</dt>
                            <dd class="text-sm text-gray-900">{{ $property->state }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ZIP Code</dt>
                            <dd class="text-sm text-gray-900">{{ $property->zip_code }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Expected Rent/Price</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($property->price_or_rent ?? 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Date</dt>
                            <dd class="text-sm text-gray-900">{{ $property->created_at->format('M d, Y \a\t g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="text-sm text-gray-900">{{ $property->updated_at->format('M d, Y \a\t g:i A') }}</dd>
                        </div>
                    </dl>

                    @if($property->description)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Description</dt>
                            <dd class="text-sm text-gray-900 leading-relaxed bg-gray-50 p-4 rounded">{{ $property->description }}</dd>
                        </div>
                    @endif

                    @if($property->registration_notes)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Admin Notes</dt>
                            <dd class="text-sm text-gray-900 leading-relaxed bg-yellow-50 p-4 rounded border border-yellow-200">{{ $property->registration_notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Property Documents -->
            @if($property->documents && $property->documents->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Property Documents</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($property->documents as $document)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-alt text-gray-400 mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($document->doc_type) }}</p>
                                            <p class="text-sm text-gray-500">Uploaded {{ $document->uploaded_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $document->status === 'approved' ? 'bg-green-100 text-green-800' :
                                               ($document->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($document->status) }}
                                        </span>
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Owner Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Property Owner</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if($property->owner->profile_photo)
                                <img class="h-10 w-10 rounded-full object-cover"
                                     src="{{ $property->owner->profile_photo }}" alt="{{ $property->owner->name }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $property->owner->name }}</p>
                            <p class="text-sm text-gray-500">{{ $property->owner->email }}</p>
                        </div>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Phone:</span>
                            <span class="text-gray-900">{{ $property->owner->phone }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-gray-500">Member Since:</span>
                            <span class="text-gray-900">{{ $property->owner->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Actions -->
            @if($property->registration_status === 'pending')
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Review Actions</h3>
                    <div class="space-y-3">
                        <button onclick="showApproveModal()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-check mr-2"></i> Approve Property
                        </button>
                        <button onclick="showRejectModal()"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-times mr-2"></i> Reject Property
                        </button>
                    </div>
                </div>
            @endif

            <!-- Registration History -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Registration Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-plus text-blue-600 text-xs"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Property Registered</p>
                            <p class="text-sm text-gray-500">{{ $property->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @if($property->updated_at != $property->created_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-edit text-yellow-600 text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Property Updated</p>
                                <p class="text-sm text-gray-500">{{ $property->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($property->approved_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full
                                {{ $property->registration_status === 'approved' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center">
                                <i class="fas {{ $property->registration_status === 'approved' ? 'fa-check text-green-600' : 'fa-times text-red-600' }} text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $property->registration_status === 'approved' ? 'Property Approved' : 'Property Rejected' }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $property->approved_at->format('M d, Y \a\t g:i A') }}</p>
                                @if($property->approver)
                                    <p class="text-xs text-gray-400">by {{ $property->approver->name }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <form action="{{ route('admin.property.approve', $property) }}" method="POST">
                @csrf
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Approve Property</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Are you sure you want to approve "{{ $property->name }}"? The owner will be able to add units for rental.
                        </p>
                        <div class="mb-4">
                            <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-2 text-left">Notes (Optional)</label>
                            <textarea name="notes" id="approve_notes" rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                      placeholder="Add any notes for the property owner..."></textarea>
                        </div>
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="closeApproveModal()"
                                    class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Approve Property
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <form action="{{ route('admin.property.reject', $property) }}" method="POST">
                @csrf
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <i class="fas fa-times text-red-600"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Reject Property</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Please provide a reason for rejecting "{{ $property->name }}". This will be sent to the property owner.
                        </p>
                        <div class="mb-4">
                            <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2 text-left">Rejection Reason <span class="text-red-500">*</span></label>
                            <textarea name="notes" id="reject_notes" rows="4" required
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                                      placeholder="Explain why this property is being rejected..."></textarea>
                        </div>
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="closeRejectModal()"
                                    class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                Reject Property
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showApproveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    // Clear form when closing
    document.getElementById('approve_notes').value = '';
}

function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    // Clear form when closing
    document.getElementById('reject_notes').value = '';
}

function resetToPending() {
    if (confirm('Are you sure you want to reset this property to pending status?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.property.reset-pending", $property) }}';

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';

        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Enhanced form validation and submission
function validateApproveForm() {
    const notes = document.getElementById('approve_notes').value.trim();
    return true; // Notes are optional for approval
}

function validateRejectForm() {
    const notes = document.getElementById('reject_notes').value.trim();
    if (!notes) {
        alert('Please provide a reason for rejecting this property.');
        return false;
    }
    if (notes.length < 10) {
        alert('Please provide a more detailed reason for rejection (at least 10 characters).');
        return false;
    }
    return true;
}

// Add loading states to forms
document.addEventListener('DOMContentLoaded', function() {
    // Close modals when clicking outside
    document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });

    // Add form submission handlers
    const approveForm = document.querySelector('form[action*="approve"]');
    const rejectForm = document.querySelector('form[action*="reject"]');

    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            if (!validateApproveForm()) {
                e.preventDefault();
                return false;
            }
            // Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;
        });
    }

    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            if (!validateRejectForm()) {
                e.preventDefault();
                return false;
            }
            // Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;
        });
    }

    // Add keyboard support (ESC to close modals)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.add('hidden');
        }
    });
});
</script>
@endpush

@endsection
