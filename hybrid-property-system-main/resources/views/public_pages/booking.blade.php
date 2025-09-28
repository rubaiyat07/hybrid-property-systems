@extends('layouts.guest')

@section('title', 'Book Viewing - ' . $unit->displayTitle())

@section('content')

<!-- Booking Hero Section -->
<section class="booking-hero">
    <div class="booking-hero-container">
        <div class="booking-hero-content">
            <div class="breadcrumb">
                <a href="{{ route('rentals.index') }}">← Back to Rentals</a>
            </div>
            <h1>Book a Viewing</h1>
            <p>Schedule a viewing for <strong>{{ $unit->displayTitle() }}</strong> and take the first step towards your new home.</p>
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

<!-- Booking Form Section -->
<section class="booking-form-section">
    <div class="booking-container">
        <div class="booking-form-card">
            <div class="form-header">
                <h2>Schedule Your Viewing</h2>
                <p>Choose your preferred date and time for viewing this property. We'll confirm your appointment within 2 hours.</p>
            </div>

            <form id="bookingForm" method="POST" action="{{ route('booking.store', $unit) }}">
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

                <!-- Viewing Preferences -->
                <div class="form-section">
                    <h3>Viewing Preferences</h3>
                    <div class="form-group">
                        <label for="viewing_type">Type of Viewing</label>
                        <select id="viewing_type" name="viewing_type" required>
                            <option value="">Select Viewing Type</option>
                            <option value="in_person" {{ old('viewing_type') === 'in_person' ? 'selected' : '' }}>In-Person Viewing</option>
                            <option value="virtual" {{ old('viewing_type') === 'virtual' ? 'selected' : '' }}>Virtual Tour</option>
                            <option value="video_call" {{ old('viewing_type') === 'video_call' ? 'selected' : '' }}>Video Call</option>
                        </select>
                        @error('viewing_type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="preferred_date">Preferred Date *</label>
                            <input type="date" id="preferred_date" name="preferred_date" value="{{ old('preferred_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            @error('preferred_date')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="preferred_time">Preferred Time *</label>
                            <select id="preferred_time" name="preferred_time" required>
                                <option value="">Select Time</option>
                                <option value="09:00" {{ old('preferred_time') === '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                <option value="10:00" {{ old('preferred_time') === '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                <option value="11:00" {{ old('preferred_time') === '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                <option value="12:00" {{ old('preferred_time') === '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                <option value="13:00" {{ old('preferred_time') === '13:00' ? 'selected' : '' }}>1:00 PM</option>
                                <option value="14:00" {{ old('preferred_time') === '14:00' ? 'selected' : '' }}>2:00 PM</option>
                                <option value="15:00" {{ old('preferred_time') === '15:00' ? 'selected' : '' }}>3:00 PM</option>
                                <option value="16:00" {{ old('preferred_time') === '16:00' ? 'selected' : '' }}>4:00 PM</option>
                                <option value="17:00" {{ old('preferred_time') === '17:00' ? 'selected' : '' }}>5:00 PM</option>
                                <option value="18:00" {{ old('preferred_time') === '18:00' ? 'selected' : '' }}>6:00 PM</option>
                            </select>
                            @error('preferred_time')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alternative_date">Alternative Date (Optional)</label>
                        <input type="date" id="alternative_date" name="alternative_date" value="{{ old('alternative_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        @error('alternative_date')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="alternative_time">Alternative Time (Optional)</label>
                        <select id="alternative_time" name="alternative_time">
                            <option value="">Select Alternative Time</option>
                            <option value="09:00" {{ old('alternative_time') === '09:00' ? 'selected' : '' }}>9:00 AM</option>
                            <option value="10:00" {{ old('alternative_time') === '10:00' ? 'selected' : '' }}>10:00 AM</option>
                            <option value="11:00" {{ old('alternative_time') === '11:00' ? 'selected' : '' }}>11:00 AM</option>
                            <option value="12:00" {{ old('alternative_time') === '12:00' ? 'selected' : '' }}>12:00 PM</option>
                            <option value="13:00" {{ old('alternative_time') === '13:00' ? 'selected' : '' }}>1:00 PM</option>
                            <option value="14:00" {{ old('alternative_time') === '14:00' ? 'selected' : '' }}>2:00 PM</option>
                            <option value="15:00" {{ old('alternative_time') === '15:00' ? 'selected' : '' }}>3:00 PM</option>
                            <option value="16:00" {{ old('alternative_time') === '16:00' ? 'selected' : '' }}>4:00 PM</option>
                            <option value="17:00" {{ old('alternative_time') === '17:00' ? 'selected' : '' }}>5:00 PM</option>
                            <option value="18:00" {{ old('alternative_time') === '18:00' ? 'selected' : '' }}>6:00 PM</option>
                        </select>
                        @error('alternative_time')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="adults">Number of Adults Attending</label>
                        <input type="number" id="adults" name="adults" value="{{ old('adults', 1) }}" min="1" max="10">
                        @error('adults')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="children">Number of Children Attending</label>
                        <input type="number" id="children" name="children" value="{{ old('children', 0) }}" min="0" max="10">
                        @error('children')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-section">
                    <h3>Additional Information</h3>
                    <div class="form-group">
                        <label for="message">Special Requirements or Questions</label>
                        <textarea id="message" name="message" rows="4" placeholder="Please let us know if you have any special requirements, accessibility needs, or specific questions about the property.">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

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
                        <label for="move_in_date">When do you plan to move in?</label>
                        <input type="date" id="move_in_date" name="move_in_date" value="{{ old('move_in_date') }}" min="{{ date('Y-m-d') }}">
                        @error('move_in_date')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Terms and Confirmation -->
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

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="contact_preference" value="email" {{ old('contact_preference', 'email') === 'email' ? 'checked' : '' }}>
                            <span class="checkbox-custom"></span>
                            I prefer to be contacted via email
                        </label>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="contact_preference" value="phone" {{ old('contact_preference') === 'phone' ? 'checked' : '' }}>
                            <span class="checkbox-custom"></span>
                            I prefer to be contacted via phone
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="window.history.back()">Cancel</button>
                    <button type="submit" class="btn-primary" id="submitBtn">
                        <i class="fas fa-calendar-check"></i> Book Viewing
                    </button>
                </div>
            </form>
        </div>

        <!-- Booking Information Sidebar -->
        <div class="booking-sidebar">
            <div class="info-card">
                <h3>Viewing Information</h3>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Duration</strong>
                        <p>30-45 minutes</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-user"></i>
                    <div>
                        <strong>Host</strong>
                        <p>{{ $unit->property->owner->name }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <div>
                        <strong>Available</strong>
                        <p>Monday - Saturday<br>9:00 AM - 6:00 PM</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>COVID-19 Safety</strong>
                        <p>Masks required<br>Social distancing</p>
                    </div>
                </div>
            </div>

            <div class="tips-card">
                <h3>Tips for Your Viewing</h3>
                <ul>
                    <li>Bring valid ID for verification</li>
                    <li>Prepare questions about the property</li>
                    <li>Take photos during the viewing</li>
                    <li>Check neighborhood amenities</li>
                    <li>Review lease terms if interested</li>
                </ul>
            </div>

            <div class="contact-card">
                <h3>Need Help?</h3>
                <p>Contact us if you need to reschedule or have questions about the viewing process.</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>support@propertymanagement.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+880 1234 567890</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom Styles -->
<style>
.booking-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 6rem 0 4rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.booking-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
}

.booking-hero-container {
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

.booking-hero-content h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.booking-hero-content p {
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

.booking-form-section {
    padding: 4rem 0;
    background: white;
}

.booking-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.booking-form-card {
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

.booking-sidebar {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.info-card,
.tips-card,
.contact-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.info-card h3,
.tips-card h3,
.contact-card h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1a1a2e;
}

.info-item {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    align-items: flex-start;
}

.info-item i {
    color: #667eea;
    margin-top: 0.2rem;
    flex-shrink: 0;
}

.info-item div {
    flex: 1;
}

.info-item strong {
    display: block;
    color: #1a1a2e;
    margin-bottom: 0.2rem;
}

.info-item p {
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

.contact-info {
    margin-top: 1rem;
}

.contact-item {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    margin-bottom: 0.5rem;
}

.contact-item i {
    color: #667eea;
    width: 16px;
}

.contact-item span {
    color: #666;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .booking-hero-content h1 {
        font-size: 2rem;
    }

    .booking-container {
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
    const bookingForm = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const preferredDate = document.getElementById('preferred_date');

    // Set minimum date for alternative date based on preferred date
    if (preferredDate) {
        preferredDate.addEventListener('change', function() {
            const altDate = document.getElementById('alternative_date');
            if (altDate) {
                altDate.min = this.value;
                if (altDate.value && altDate.value < this.value) {
                    altDate.value = '';
                }
            }
        });
    }

    // Form submission handling
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Booking...';
            submitBtn.disabled = true;

            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('Your viewing has been booked successfully! You will receive a confirmation email shortly.', 'success');

                    // Redirect to success page or back to listings
                    setTimeout(() => {
                        window.location.href = '{{ route("rentals.index") }}';
                    }, 2000);
                } else {
                    showNotification(data.message || 'Failed to book viewing. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while booking your viewing. Please try again.', 'error');
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
