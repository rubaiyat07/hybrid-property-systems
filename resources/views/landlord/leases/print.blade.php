@extends('layouts.landlord')

@section('title', 'Print Lease Agreement')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Lease Agreement Created Successfully!</h1>
                <p class="text-gray-600">The lease has been created and a PDF copy has been sent to the tenant.</p>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                    <div>
                        <h3 class="text-green-900 font-semibold">Lease Created</h3>
                        <p class="text-green-800 text-sm">Lease #{{ $lease->id }} for {{ $lease->tenant->user->name }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Lease Details</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Tenant:</span> {{ $lease->tenant->user->name }}</p>
                        <p><span class="font-medium">Property:</span> {{ $lease->unit->property->address }}</p>
                        <p><span class="font-medium">Unit:</span> {{ $lease->unit->unit_number }}</p>
                        <p><span class="font-medium">Period:</span> {{ $lease->start_date->format('M d, Y') }} - {{ $lease->end_date->format('M d, Y') }}</p>
                        <p><span class="font-medium">Rent:</span> ৳{{ number_format($lease->rent_amount, 2) }}/month</p>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-3">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ Storage::disk('public')->url($lease->pdf_path) }}"
                           target="_blank"
                           class="block w-full bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i>View PDF
                        </a>
                        <a href="{{ Storage::disk('public')->url($lease->pdf_path) }}"
                           download="lease_agreement_{{ $lease->id }}.pdf"
                           class="block w-full bg-green-600 text-white text-center px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </a>
                        <button onclick="window.print()"
                                class="block w-full bg-purple-600 text-white text-center px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-print mr-2"></i>Print PDF
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('landlord.leases.show', $lease) }}"
                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-eye mr-2"></i>View Lease Details
                </a>
                <a href="{{ route('landlord.leases.index') }}"
                   class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Leases
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-open PDF in new tab for printing
    window.onload = function() {
        const pdfUrl = '{{ Storage::disk("public")->url($lease->pdf_path) }}';
        window.open(pdfUrl, '_blank');
    };
</script>
@endsection
