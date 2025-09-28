@extends('layouts.guest')

@section('title', $unit->displayTitle() . ' - Rental Details')

@section('content')

<!-- Unit Detail Hero Section -->
<section class="unit-hero">
    <div class="unit-hero-container">
        <div class="unit-hero-content">
            <div class="breadcrumb">
                <a href="{{ route('rentals.index') }}">← Back to Rentals</a>
            </div>
            <h1>{{ $unit->displayTitle() }}</h1>
            <div class="unit-meta">
                <div class="meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $unit->property->fullAddress() }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-tag"></i>
                    <span class="price">{{ $unit->displayPrice() }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>Available Now</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Unit Details Section -->
<section class="unit-details">
    <div class="unit-container">
        <div class="unit-content">
            <!-- Image Gallery -->
            <div class="image-gallery">
                <div class="main-image">
                    @if($unit->photos && count($unit->photos) > 0)
                        <img src="{{ asset('storage/' . $unit->photos[0]) }}" alt="{{ $unit->displayTitle() }}" id="mainImage">
                    @else
                        <div class="no-image">
                            <i class="fas fa-home"></i>
                            <p>No images available</p>
                        </div>
                    @endif
                </div>
                @if($unit->photos && count($unit->photos) > 1)
                    <div class="thumbnail-gallery">
                        @foreach(array_slice($unit->photos, 0, 4) as $index => $photo)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" data-image="{{ asset('storage/' . $photo) }}">
                                <img src="{{ asset('storage/' . $photo) }}" alt="View {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Unit Information -->
            <div class="unit-info">
                <div class="info-section">
                    <h2>Property Details</h2>
                    <div class="details-grid">
                        <div class="detail-item">
                            <label>Property Type:</label>
                            <span>{{ ucfirst($unit->property->type) }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Unit Number:</label>
                            <span>{{ $unit->unit_number }}</span>
                        </div>
                        @if($unit->floor)
                            <div class="detail-item">
                                <label>Floor:</label>
                                <span>{{ $unit->floor }}</span>
                            </div>
                        @endif
                        @if($unit->size)
                            <div class="detail-item">
                                <label>Size:</label>
                                <span>{{ $unit->size }}</span>
                            </div>
                        @endif
                        @if($unit->bedrooms)
                            <div class="detail-item">
                                <label>Bedrooms:</label>
                                <span>{{ $unit->bedrooms }}</span>
                            </div>
                        @endif
                        @if($unit->bathrooms)
                            <div class="detail-item">
                                <label>Bathrooms:</label>
                                <span>{{ $unit->bathrooms }}</span>
                            </div>
                        @endif
                        <div class="detail-item">
                            <label>Status:</label>
                            <span class="status-badge {{ $unit->status }}">{{ ucfirst($unit->status) }}</span>
                        </div>
                        @if($unit->deposit_amount)
                            <div class="detail-item">
                                <label>Security Deposit:</label>
                                <span>{{ $unit->displayDeposit() }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($unit->features && count($unit->features) > 0)
                    <div class="info-section">
                        <h3>Features & Amenities</h3>
                        <div class="features-list">
                            @foreach($unit->features as $feature)
                                <span class="feature-tag">{{ $feature }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($unit->description)
                    <div class="info-section">
                        <h3>Description</h3>
                        <p class="description">{{ $unit->description }}</p>
                    </div>
                @endif

                <!-- Property Owner Contact -->
                <div class="info-section">
                    <h3>Contact Information</h3>
                    <div class="contact-info">
                        <p><strong>Property Owner:</strong> {{ $unit->property->owner->name }}</p>
                        <p><strong>Location:</strong> {{ $unit->property->fullAddress() }}</p>
                        <div class="contact-actions">
                            <button class="btn-primary" onclick="openInquiryModal()">
                                <i class="fas fa-envelope"></i> Send Inquiry
                            </button>
                            <button class="btn-secondary" onclick="shareProperty()">
                                <i class="fas fa-share"></i> Share
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Inquiry Modal -->
<div id="inquiryModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Send Inquiry</h2>
            <span class="close" onclick="closeInquiryModal()">&times;</span>
        </div>
        <form id="inquiryForm" method="POST" action="{{ route('inquiry.store', $unit) }}">
            @csrf
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number *</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="inquiry_type">Inquiry Type</label>
                <select id="inquiry_type" name="inquiry_type">
                    <option value="general">General Inquiry</option>
                    <option value="viewing">Request Viewing</option>
                    <option value="availability">Check Availability</option>
                    <option value="negotiation">Price Negotiation</option>
                </select>
            </div>
            <div class="form-group">
                <label for="preferred_date">Preferred Viewing Date (Optional)</label>
                <input type="date" id="preferred_date" name="preferred_date" min="{{ date('Y-m-d') }}">
            </div>
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" rows="4" placeholder="Tell the owner about yourself and why you're interested in this property..." required></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeInquiryModal()">Cancel</button>
                <button type="submit" class="btn-primary">Send Inquiry</button>
            </div>
        </form>
    </div>
</div>

<!-- Share Modal -->
<div id="shareModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Share This Property</h2>
            <span class="close" onclick="closeShareModal()">&times;</span>
        </div>
        <div class="share-options">
            <a href="#" class="share-btn facebook" onclick="shareOnFacebook()">
                <i class="fab fa-facebook-f"></i> Facebook
            </a>
            <a href="#" class="share-btn twitter" onclick="shareOnTwitter()">
                <i class="fab fa-twitter"></i> Twitter
            </a>
            <a href="#" class="share-btn whatsapp" onclick="shareOnWhatsApp()">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <button class="share-btn copy-link" onclick="copyLink()">
                <i class="fas fa-link"></i> Copy Link
            </button>
        </div>
    </div>
</div>

<!-- Similar Properties Section -->
@if($similarUnits->count() > 0)
<section class="similar-properties">
    <div class="similar-container">
        <h2>Similar Properties</h2>
        <div class="similar-grid">
            @foreach($similarUnits as $similarUnit)
                <div class="similar-card">
                    <div class="similar-image">
                        @if($similarUnit->photos && count($similarUnit->photos) > 0)
                            <img src="{{ asset('storage/' . $similarUnit->photos[0]) }}" alt="{{ $similarUnit->displayTitle() }}">
                        @else
                            <i class="fas fa-home"></i>
                        @endif
                    </div>
                    <div class="similar-content">
                        <h3>{{ $similarUnit->displayTitle() }}</h3>
                        <p class="similar-location">{{ $similarUnit->property->city }}, {{ $similarUnit->property->state }}</p>
                        <p class="similar-price">{{ $similarUnit->displayPrice() }}</p>
                        <a href="{{ route('rentals.show', $similarUnit) }}" class="btn-secondary">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Custom Styles -->
<style>
.unit-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 6rem 0 4rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.unit-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
}

.unit-hero-container {
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

.unit-hero-content h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.unit-meta {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
}

.meta-item i {
    color: rgba(255, 255, 255, 0.8);
}

.price {
    font-size: 1.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.unit-details {
    padding: 4rem 0;
    background: #f8f9ff;
}

.unit-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.unit-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.image-gallery {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.main-image {
    height: 400px;
    overflow: hidden;
}

.main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #667eea;
}

.no-image i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.thumbnail-gallery {
    display: flex;
    padding: 1rem;
    gap: 0.5rem;
}

.thumbnail {
    width: 80px;
    height: 60px;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 3px solid transparent;
}

.thumbnail.active {
    border-color: #667eea;
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.unit-info {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.info-section {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.info-section h2,
.info-section h3 {
    margin-bottom: 1rem;
    color: #1a1a2e;
    font-weight: 700;
}

.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item label {
    font-weight: 600;
    color: #666;
    font-size: 0.9rem;
}

.detail-item span {
    color: #1a1a2e;
    font-size: 1rem;
}

.status-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.vacant {
    background: #10b981;
    color: white;
}

.status-badge.occupied {
    background: #ef4444;
    color: white;
}

.features-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.feature-tag {
    background: #f0f4ff;
    color: #667eea;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.description {
    line-height: 1.6;
    color: #666;
}

.contact-info p {
    margin-bottom: 1rem;
    color: #666;
}

.contact-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.similar-properties {
    padding: 4rem 0;
    background: white;
}

.similar-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.similar-container h2 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 2rem;
    color: #1a1a2e;
}

.similar-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.similar-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.similar-card:hover {
    transform: translateY(-5px);
}

.similar-image {
    height: 180px;
    background: #f0f4ff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.similar-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.similar-image i {
    font-size: 2rem;
    color: #667eea;
}

.similar-content {
    padding: 1.5rem;
}

.similar-content h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1a1a2e;
}

.similar-location {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.similar-price {
    font-size: 1.3rem;
    font-weight: 800;
    color: #667eea;
    margin-bottom: 1rem;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 0;
    border-radius: 20px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    animation: slideIn 0.3s ease;
}

.modal-header {
    padding: 2rem 2rem 1rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    color: #1a1a2e;
    font-weight: 700;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close:hover {
    color: #667eea;
}

#inquiryForm {
    padding: 2rem;
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
    padding: 0.8rem;
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

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.share-options {
    padding: 2rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.share-btn {
    flex: 1;
    min-width: 120px;
    padding: 1rem;
    border: none;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.share-btn.facebook {
    background: #1877f2;
    color: white;
}

.share-btn.twitter {
    background: #1da1f2;
    color: white;
}

.share-btn.whatsapp {
    background: #25d366;
    color: white;
}

.share-btn.copy-link {
    background: #667eea;
    color: white;
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@media (max-width: 768px) {
    .unit-hero-content h1 {
        font-size: 2rem;
    }

    .unit-meta {
        flex-direction: column;
        gap: 1rem;
    }

    .unit-content {
        grid-template-columns: 1fr;
    }

    .details-grid {
        grid-template-columns: 1fr;
    }

    .contact-actions {
        flex-direction: column;
    }

    .form-actions {
        flex-direction: column;
    }

    .similar-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Custom JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image gallery functionality
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.getElementById('mainImage');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));

            // Add active class to clicked thumbnail
            this.classList.add('active');

            // Update main image
            if (mainImage) {
                mainImage.src = this.dataset.image;
            }
        });
    });

    // Modal functionality
    window.openInquiryModal = function() {
        document.getElementById('inquiryModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    };

    window.closeInquiryModal = function() {
        document.getElementById('inquiryModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    window.openShareModal = function() {
        document.getElementById('shareModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    };

    window.closeShareModal = function() {
        document.getElementById('shareModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    // Share functionality
    window.shareOnFacebook = function() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('Check out this amazing rental property!');
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank', 'width=600,height=400');
    };

    window.shareOnTwitter = function() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('Check out this amazing rental property!');
        window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
    };

    window.shareOnWhatsApp = function() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('Check out this amazing rental property: ' + url);
        window.open(`https://wa.me/?text=${text}`, '_blank');
    };

    window.copyLink = function() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            // Show success message
            const button = document.querySelector('.share-btn.copy-link');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copied!';
            button.style.background = '#10b981';

            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '#667eea';
            }, 2000);
        }).catch(() => {
            alert('Failed to copy link. Please copy manually: ' + window.location.href);
        });
    };

    // Close modal when clicking outside
    window.onclick = function(event) {
        const inquiryModal = document.getElementById('inquiryModal');
        const shareModal = document.getElementById('shareModal');

        if (event.target === inquiryModal) {
            closeInquiryModal();
        }
        if (event.target === shareModal) {
            closeShareModal();
        }
    };

    // Form submission handling
    const inquiryForm = document.getElementById('inquiryForm');
    if (inquiryForm) {
        inquiryForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.textContent = 'Sending...';
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
                    showNotification('Inquiry sent successfully! The property owner will contact you soon.', 'success');
                    closeInquiryModal();
                    inquiryForm.reset();
                } else {
                    showNotification(data.message || 'Failed to send inquiry. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
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
