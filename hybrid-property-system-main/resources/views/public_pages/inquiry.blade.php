@extends('layouts.guest')

@section('title', 'Send Inquiry - ' . $unit->displayTitle())

@section('content')

<!-- Inquiry Hero Section -->
<section class="inquiry-hero">
    <div class="inquiry-hero-container">
        <div class="inquiry-hero-content">
            <div class="breadcrumb">
                <a href="{{ route('rentals.index') }}">← Back to Rentals</a>
            </div>
            <h1>Send Inquiry</h1>
            <p>Interested in <strong>{{ $unit->displayTitle() }}</strong>? Send a message to the property owner and get more information.</p>
        </div>
    </div>
</section>

<!-- Property Summary -->
<section class="property-summary">
    <div class="summary-container">
        <div class="summary-card">
            <div class="summary-image">
                @if($unit->photos && count($unit->photos) > 0)
                    <img src="{{ asset('storage/' . $unit->photos[0]) }}" alt="{{ $unit->displayTitle() }}">
                @else
                    <i class="fas fa-home"></i>
                @endif
            </div>
            <div class="summary-content">
                <h3>{{ $unit->displayTitle() }}</h3>
                <div class="summary-meta">
                    <span><i class="fas fa-map-marker-alt"></i> {{ $unit->property->fullAddress() }}</span>
                    <span><i class="fas fa-tag"></i> {{ $unit->displayPrice() }}</span>
                    <span><i class="fas fa-bed"></i> {{ $unit->bedrooms ?? 'N/A' }} beds</span>
                    <span><i class="fas fa-bath"></i> {{ $unit->bathrooms ?? 'N/A' }} baths</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Inquiry Form Section -->
<section class="inquiry-form-section">
    <div class="inquiry-container">
        <div class="inquiry-form-card">
            <div class="form-header">
                <h2>Send Your Inquiry</h2>
                <p>Fill out the form below and the property owner will get back to you within 24 hours.</p>
            </div>

            <form id="inquiryForm" method="POST" action="{{ route('inquiry.store', $unit) }}">
                @csrf

                <!-- Personal Information -->
                <div class="form-section">
                    <h3>Personal Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="occupation">Occupation</label>
                            <input type="text" id="occupation" name="occupation" value="{{ old('occupation') }}">
                            @error('occupation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Inquiry Details -->
                <div class="form-section">
                    <h3>Inquiry Details</h3>
                    <div class="form-group">
                        <label for="inquiry_type">What type of inquiry is this?</label>
                        <select id="inquiry_type" name="inquiry_type">
                            <option value="general" {{ old('inquiry_type') === 'general' ? 'selected' : '' }}>General Inquiry</option>
                            <option value="viewing" {{ old('inquiry_type') === 'viewing' ? 'selected' : '' }}>Request Property Viewing</option>
                            <option value="availability" {{ old('inquiry_type') === 'availability' ? 'selected' : '' }}>Check Availability</option>
                            <option value="negotiation" {{ old('inquiry_type') === 'negotiation' ? 'selected' : '' }}>Price Negotiation</option>
                            <option value="application" {{ old('inquiry_type') === 'application' ? 'selected' : '' }}>Rental Application</option>
                        </select>
                        @error('inquiry_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="move_in_date">When do you want to move in?</label>
                            <input type="date" id="move_in_date" name="move_in_date" value="{{ old('move_in_date') }}" min="{{ date('Y-m-d') }}">
                            @error('move_in_date')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="lease_duration">Preferred Lease Duration</label>
                            <select id="lease_duration" name="lease_duration">
                                <option value="">Select Duration</option>
                                <option value="6_months" {{ old('lease_duration') === '6_months' ? 'selected' : '' }}>6 Months</option>
                                <option value="1_year" {{ old('lease_duration') === '1_year' ? 'selected' : '' }}>1 Year</option>
                                <option value="2_years" {{ old('lease_duration') === '2_years' ? 'selected' : '' }}>2 Years</option>
                                <option value="flexible" {{ old('lease_duration') === 'flexible' ? 'selected' : '' }}>Flexible</option>
                            </select>
                            @error('lease_duration')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="adults">Number of Adults</label>
                        <input type="number" id="adults" name="adults" value="{{ old('adults', 1) }}" min="1" max="10">
                        @error('adults')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="children">Number of Children</label>
                        <input type="number" id="children" name="children" value="{{ old('children', 0) }}" min="0" max="10">
                        @error('children')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                    </div>

                    <div class="form-group">
                        <label for="pets">Do you have pets?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="pets" value="no" {{ old('pets', 'no') === 'no' ? 'checked' : '' }}>
                                <span class="radio-custom"></span>
                                No pets
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="pets" value="yes" {{ old('pets') === 'yes' ? 'checked' : '' }}>
                                <span class="radio-custom"></span>
                                Yes, I have pets
                            </label>
                        </div>
                        @error('pets')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" id="petDetails" style="display: {{ old('pets') === 'yes' ? 'block' : 'none' }};">
                        <label for="pet_info">Pet Information</label>
                        <textarea id="pet_info" name="pet_info" rows="3" placeholder="Please describe your pets (type, breed, size, etc.)">{{ old('pet_info') }}</textarea>
                        @error('pet_info')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Message -->
                <div class="form-section">
                    <h3>Your Message</h3>
                    <div class="form-group">
                        <label for="message">Message to Property Owner *</label>
                        <textarea id="message" name="message" rows="5" placeholder="Introduce yourself, explain why you're interested in this property, ask any specific questions, and mention any special requirements or preferences you have." required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-section">
                    <h3>Additional Information</h3>
                    <div class="form-group">
                        <label for="budget">Monthly Budget Range</label>
                        <select id="budget" name="budget">
                            <option value="">Select Budget Range</option>
                            <option value="under_15000" {{ old('budget') === 'under_15000' ? 'selected' : '' }}>Under ৳15,000</option>
                            <option value="15000_25000" {{ old('budget') === '15000_25000' ? 'selected' : '' }}>৳15,000 - ৳25,000</option>
                            <option value="25000_40000" {{ old('budget') === '25000_40000' ? 'selected' : '' }}>৳25,000 - ৳40,000</option>
                            <option value="40000_60000" {{ old('budget') === '40000_60000' ? 'selected' : '' }}>৳40,000 - ৳60,000</option>
                            <option value="60000_plus" {{ old('budget') === '60000_plus' ? 'selected' : '' }}>৳60,000+</option>
                        </select>
                        @error('budget')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="how_found">How did you find this property?</label>
                        <select id="how_found" name="how_found">
                            <option value="">Select Option</option>
                            <option value="search_engine" {{ old('how_found') === 'search_engine' ? 'selected' : '' }}>Search Engine</option>
                            <option value="social_media" {{ old('how_found') === 'social_media' ? 'selected' : '' }}>Social Media</option>
                            <option value="friend_referral" {{ old('how_found') === 'friend_referral' ? 'selected' : '' }}>Friend/Family Referral</option>
                            <option value="agent" {{ old('how_found') === 'agent' ? 'selected' : '' }}>Real Estate Agent</option>
                            <option value="advertisement" {{ old('how_found') === 'advertisement' ? 'selected' : '' }}>Advertisement</option>
                            <option value="other" {{ old('how_found') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('how_found')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Terms and Privacy -->
                <div class="form-section">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms_accepted" value="1" {{ old('terms_accepted') ? 'checked' : '' }} required>
                            <span class="checkbox-custom"></span>
                            I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a> *
                        </label>
                        @error('terms_accepted')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="newsletter" value="1" {{ old('newsletter', true) ? 'checked' : '' }}>
                            <span class="checkbox-custom"></span>
                            I would like to receive updates about similar properties and rental tips
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="button" class="btn-primary" onclick="window.history.back()">Cancel</button>
                    <button type="submit" class="btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> Send Inquiry
                    </button>
                </div>
            </form>
        </div>

        <!-- Contact Information Sidebar -->
        <div class="contact-sidebar">
            <div class="contact-card">
                <h3>Contact Information</h3>
                <div class="contact-item">
                    <i class="fas fa-user"></i>
                    <div>
                        <strong>Property Owner</strong>
                        <p>{{ $unit->property->owner->name }}</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Location</strong>
                        <p>{{ $unit->property->fullAddress() }}</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Response Time</strong>
                        <p>Within 24 hours</p>
                    </div>
                </div>
            </div>

            <div class="tips-card">
                <h3>Tips for a Better Response</h3>
                <ul>
                    <li>Be specific about your requirements</li>
                    <li>Mention your move-in timeline</li>
                    <li>Include information about your background</li>
                    <li>Ask clear questions about the property</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Custom Styles -->
<style>
.inquiry-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 6rem 0 4rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.inquiry-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
}

.inquiry-hero-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.breadcrumb {
    margin-bottom: 1rem;
}

.breadcrumb a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb a:hover {
    color: white;
}

.inquiry-hero-content h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.inquiry-hero-content p {
    font-size: 1.2rem;
    opacity: 0.9;
    line-height: 1.6;
}

.property-summary {
    padding: 4rem 0;
    background: #f8f9ff;
}

.summary-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.summary-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    display: flex;
    gap: 2rem;
    align-items: center;
}

.summary-image {
    width: 120px;
    height: 120px;
    border-radius: 15px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f4ff;
}

.summary-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.summary-image i {
    font-size: 2rem;
    color: #667eea;
}

.summary-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1a1a2e;
}

.summary-meta {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.summary-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
    font-size: 0.9rem;
}

.inquiry-form-section {
    padding: 4rem 0;
    background: white;
}

.inquiry-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.inquiry-form-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.form-header {
    margin-bottom: 2rem;
    text-align: center;
}

.form-header h2 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    color: #1a1a2e;
}

.form-header p {
    color: #666;
    line-height: 1.6;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h3 {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1a1a2e;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #1a1a2e;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
}

.radio-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-weight: normal;
}

.radio-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #e5e7eb;
    border-radius: 50%;
    position: relative;
    transition: all 0.3s ease;
}

.radio-label input[type="radio"]:checked + .radio-custom {
    border-color: #667eea;
    background: #667eea;
}

.radio-label input[type="radio"]:checked + .radio-custom::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
}

.checkbox-group {
    margin-bottom: 1rem;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    cursor: pointer;
    font-weight: normal;
    line-height: 1.4;
}

.checkbox-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #e5e7eb;
    border-radius: 4px;
    position: relative;
    margin-top: 2px;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.checkbox-label input[type="checkbox"]:checked + .checkbox-custom {
    border-color: #667eea;
    background: #667eea;
}

.checkbox-label input[type="checkbox"]:checked + .checkbox-custom::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 12px;
}

.error-message {
    color: #ef4444;
    font-size: 0.8rem;
    margin-top: 0.5rem;
    display: block;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.contact-sidebar {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.contact-card,
.tips-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.contact-card h3,
.tips-card h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1a1a2e;
}

.contact-item {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    align-items: flex-start;
}

.contact-item i {
    color: #667eea;
    margin-top: 0.2rem;
    flex-shrink: 0;
}

.contact-item div {
    flex: 1;
}

.contact-item strong {
    display: block;
    color: #1a1a2e;
    margin-bottom: 0.2rem;
}

.contact-item p {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
}

.tips-card ul {
    list-style: none;
    padding: 0;
}

.tips-card li {
    padding: 0.5rem 0;
    color: #666;
    position: relative;
    padding-left: 1.5rem;
}

.tips-card li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}

@media (max-width: 768px) {
    .inquiry-hero-content h1 {
        font-size: 2rem;
    }

    .inquiry-container {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .summary-card {
        flex-direction: column;
        text-align: center;
    }

    .summary-meta {
        justify-content: center;
    }
}
</style>

<!-- Custom JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const petsRadio = document.querySelectorAll('input[name="pets"]');
    const petDetails = document.getElementById('petDetails');
    const inquiryForm = document.getElementById('inquiryForm');
    const submitBtn = document.getElementById('submitBtn');

    // Show/hide pet details based on pets selection
    petsRadio.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'yes') {
                petDetails.style.display = 'block';
            } else {
                petDetails.style.display = 'none';
            }
        });
    });

    // Form submission handling
    if (inquiryForm) {
        inquiryForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;

            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('Your inquiry has been sent successfully! The property owner will contact you within 24 hours.', 'success');

                    // Redirect to success page or back to listings
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '{{ route("rentals.index") }}';
                    }, 2000);
                } else {
                    showNotification(data.message || 'Failed to send inquiry. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.errors) {
                    // Validation errors
                    let errorMessages = [];
                    for (let field in error.errors) {
                        errorMessages.push(...error.errors[field]);
                    }
                    showNotification(errorMessages.join('<br>'), 'error');
                } else {
                    showNotification(error.message || 'An error occurred while sending your inquiry. Please try again.', 'error');
                }
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 2rem;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            z-index: 1001;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: slideInRight 0.3s ease;
            max-width: 400px;
        `;

        if (type === 'success') {
            notification.style.background = '#10b981';
        } else {
            notification.style.background = '#ef4444';
        }

        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
});
</script>

@endsection
