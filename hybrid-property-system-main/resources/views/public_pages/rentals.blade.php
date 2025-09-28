@extends('layouts.guest')

@section('title', 'Browse Available Rentals - HybridEstate')

@section('content')

<!-- Hero Section for Rentals -->
<section class="rent-hero">
    <div class="rent-hero-container">
        <div class="rent-hero-content">
            <h1>Find Your Perfect <span class="highlight">Rental</span></h1>
            <p>Discover thousands of verified rental properties across Bangladesh. From cozy apartments to spacious family homes, find your ideal living space today.</p>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="search-section">
    <div class="search-container">
        <div class="search-controls">
            <div class="search-group">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search by location, property type..." />
            </div>

            <div class="filter-group">
                <select id="locationFilter">
                    <option value="">📍 All Locations</option>
                    <option value="dhaka">Dhaka</option>
                    <option value="chattogram">Chattogram</option>
                    <option value="sylhet">Sylhet</option>
                    <option value="khulna">Khulna</option>
                    <option value="rajshahi">Rajshahi</option>
                    <option value="barisal">Barisal</option>
                    <option value="rangpur">Rangpur</option>
                    <option value="mymensingh">Mymensingh</option>
                </select>

                <select id="propertyTypeFilter">
                    <option value="">🏢 All Property Types</option>
                    <option value="apartment">Apartment</option>
                    <option value="house">House</option>
                    <option value="condo">Condo</option>
                    <option value="townhouse">Townhouse</option>
                    <option value="commercial">Commercial</option>
                </select>

                <select id="roomTypeFilter">
                    <option value="">🛏 Any Room Type</option>
                    <option value="studio">Studio</option>
                    <option value="1bed">1 Bedroom</option>
                    <option value="2bed">2 Bedrooms</option>
                    <option value="3bed">3 Bedrooms</option>
                    <option value="4bed">4+ Bedrooms</option>
                </select>

                <select id="priceRangeFilter">
                    <option value="">💰 Any Price Range</option>
                    <option value="0-15000">Under ৳15,000</option>
                    <option value="15000-30000">৳15,000 - ৳30,000</option>
                    <option value="30000-50000">৳30,000 - ৳50,000</option>
                    <option value="50000-100000">৳50,000 - ৳100,000</option>
                    <option value="100000+">৳100,000+</option>
                </select>
            </div>

            <div class="search-actions">
                <button id="searchBtn" class="btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                <button id="clearFilters" class="btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="search-stats">
            <div class="stat-item">
                <span class="stat-number" id="totalListings">0</span>
                <span class="stat-label">Available Units</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" id="avgPrice">৳0</span>
                <span class="stat-label">Average Rent</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" id="totalLocations">8</span>
                <span class="stat-label">Cities</span>
            </div>
        </div>
    </div>
</section>

<!-- Rental Listings Grid -->
<section class="rentals-section">
    <div class="rentals-container">
        <div class="rentals-header">
            <h2>Available Rentals</h2>
            <div class="sort-options">
                <select id="sortBy">
                    <option value="newest">Newest First</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="size">Size</option>
                </select>
            </div>
        </div>

        <!-- Loading indicator -->
        <div id="loadingIndicator" class="loading-grid">
            <div class="loading-card"></div>
            <div class="loading-card"></div>
            <div class="loading-card"></div>
            <div class="loading-card"></div>
            <div class="loading-card"></div>
            <div class="loading-card"></div>
        </div>

        <!-- Rental Grid -->
        <div id="rentalsGrid" class="rentals-grid">
            <!-- Rental cards will be loaded here via AJAX -->
        </div>

        <!-- Load More Button -->
        <div class="load-more-container">
            <button id="loadMoreBtn" class="btn-primary" style="display: none;">
                Load More Properties
            </button>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="no-results" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>No properties found</h3>
            <p>Try adjusting your search criteria or browse all available rentals.</p>
            <button id="showAllBtn" class="btn-secondary">Show All Properties</button>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="rental-features">
    <div class="features-container">
        <div class="section-header">
            <h2>Why Choose Our Rentals?</h2>
            <p>Experience the perfect blend of comfort, convenience, and modern amenities in all our verified rental properties.</p>
        </div>
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Verified Properties</h3>
                <p>All listings are thoroughly verified and regularly inspected for quality assurance.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Direct Owner Contact</h3>
                <p>Communicate directly with property owners for transparent and efficient rental process.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Flexible Booking</h3>
                <p>Easy inquiry system with flexible viewing schedules and booking options.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>24/7 Support</h3>
                <p>Round-the-clock customer support to assist you throughout your rental journey.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="rental-cta">
    <div class="cta-container">
        <h2>Ready to Find Your Dream Rental?</h2>
        <p>Join thousands of satisfied tenants who found their perfect home through HybridEstate.</p>
        <div class="cta-buttons">
            <a href="#search-section" class="btn-primary">Start Browsing</a>
            <a href="{{ route('register') }}" class="btn-secondary">Create Account</a>
        </div>
    </div>
</section>

<!-- Custom Styles -->
<style>
.rent-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 8rem 0 6rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.rent-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="90" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
}

.rent-hero-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    text-align: center;
}

.rent-hero-content h1 {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.rent-hero-content .highlight {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.rent-hero-content p {
    font-size: 1.3rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.search-section {
    padding: 4rem 0;
    background: linear-gradient(180deg, #f8f9ff 0%, #ffffff 100%);
}

.search-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.search-controls {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.search-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.search-group i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
}

.search-group input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.search-group input:focus {
    outline: none;
    border-color: #667eea;
}

.filter-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.filter-group select {
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: white;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.filter-group select:focus {
    outline: none;
    border-color: #667eea;
}

.search-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.search-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}

.rentals-section {
    padding: 6rem 0;
    background: #f8f9ff;
}

.rentals-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.rentals-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
}

.rentals-header h2 {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1a1a2e;
}

.sort-options select {
    padding: 0.8rem 1.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: white;
    font-size: 1rem;
}

.loading-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.loading-card {
    height: 400px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 20px;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.rentals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.rental-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.rental-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.rental-image {
    position: relative;
    height: 200px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.rental-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.rental-image i {
    font-size: 3rem;
    color: white;
}

.rental-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: #10b981;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.rental-content {
    padding: 1.5rem;
}

.rental-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 0.5rem;
}

.rental-location {
    color: #667eea;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rental-location i {
    font-size: 0.8rem;
}

.rental-features {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.rental-feature {
    background: #f0f4ff;
    color: #667eea;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.rental-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: #667eea;
    margin-bottom: 1rem;
}

.rental-actions {
    display: flex;
    gap: 1rem;
}

.btn-inquire {
    background: #667eea;
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
}

.btn-inquire:hover {
    background: #5a67d8;
    transform: translateY(-2px);
}

.btn-favorite {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 0.8rem 1rem;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-favorite:hover {
    background: #667eea;
    color: white;
}

.load-more-container {
    text-align: center;
}

.no-results {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.no-results i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.no-results h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #1a1a2e;
}

.rental-features {
    padding: 6rem 0;
    background: white;
}

.features-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
}

.feature-item {
    text-align: center;
    padding: 2rem;
    border-radius: 20px;
    background: #f8f9ff;
    transition: transform 0.3s ease;
}

.feature-item:hover {
    transform: translateY(-5px);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
}

.feature-item h3 {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1a1a2e;
}

.feature-item p {
    color: #666;
    line-height: 1.6;
}

.rental-cta {
    padding: 6rem 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
}

.cta-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 2rem;
}

.rental-cta h2 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.rental-cta p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .rent-hero-content h1 {
        font-size: 2.5rem;
    }

    .rentals-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .filter-group {
        grid-template-columns: 1fr;
    }

    .search-actions {
        flex-direction: column;
    }

    .search-stats {
        gap: 2rem;
    }

    .rentals-grid {
        grid-template-columns: 1fr;
    }

    .features-grid {
        grid-template-columns: 1fr;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<!-- Custom JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const locationFilter = document.getElementById('locationFilter');
    const propertyTypeFilter = document.getElementById('propertyTypeFilter');
    const roomTypeFilter = document.getElementById('roomTypeFilter');
    const priceRangeFilter = document.getElementById('priceRangeFilter');
    const sortBy = document.getElementById('sortBy');
    const searchBtn = document.getElementById('searchBtn');
    const clearFilters = document.getElementById('clearFilters');
    const showAllBtn = document.getElementById('showAllBtn');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const rentalsGrid = document.getElementById('rentalsGrid');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const noResults = document.getElementById('noResults');

    let currentPage = 1;
    let isLoading = false;

    // Initial load
    loadRentals();

    // Search functionality
    searchBtn.addEventListener('click', function() {
        currentPage = 1;
        loadRentals();
    });

    // Clear filters
    clearFilters.addEventListener('click', function() {
        searchInput.value = '';
        locationFilter.value = '';
        propertyTypeFilter.value = '';
        roomTypeFilter.value = '';
        priceRangeFilter.value = '';
        sortBy.value = 'newest';
        currentPage = 1;
        loadRentals();
    });

    // Show all properties
    showAllBtn.addEventListener('click', function() {
        clearFilters.click();
    });

    // Load more functionality
    loadMoreBtn.addEventListener('click', function() {
        currentPage++;
        loadRentals(true);
    });

    // Real-time search (debounced)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadRentals();
        }, 500);
    });

    // Filter change events
    [locationFilter, propertyTypeFilter, roomTypeFilter, priceRangeFilter, sortBy].forEach(filter => {
        filter.addEventListener('change', function() {
            currentPage = 1;
            loadRentals();
        });
    });

    function loadRentals(append = false) {
        if (isLoading) return;

        isLoading = true;
        showLoadingState();

        const params = new URLSearchParams({
            page: currentPage,
            search: searchInput.value,
            location: locationFilter.value,
            property_type: propertyTypeFilter.value,
            room_type: roomTypeFilter.value,
            price_range: priceRangeFilter.value,
            sort_by: sortBy.value
        });

        fetch(`/rentals/search/ajax?${params}`)
            .then(response => response.json())
            .then(data => {
                hideLoadingState();

                if (data.success) {
                    if (append) {
                        rentalsGrid.insertAdjacentHTML('beforeend', data.html);
                    } else {
                        rentalsGrid.innerHTML = data.html;
                    }

                    // Update stats
                    updateStats(data.stats);

                    // Show/hide load more button
                    if (data.has_more) {
                        loadMoreBtn.style.display = 'inline-block';
                    } else {
                        loadMoreBtn.style.display = 'none';
                    }

                    // Show/hide no results
                    if (data.units.length === 0 && !append) {
                        noResults.style.display = 'block';
                        rentalsGrid.style.display = 'none';
                    } else {
                        noResults.style.display = 'none';
                        rentalsGrid.style.display = 'grid';
                    }
                } else {
                    showError('Failed to load rentals. Please try again.');
                }
            })
            .catch(error => {
                hideLoadingState();
                showError('An error occurred while loading rentals.');
                console.error('Error:', error);
            })
            .finally(() => {
                isLoading = false;
            });
    }

    function showLoadingState() {
        if (currentPage === 1) {
            loadingIndicator.style.display = 'grid';
            rentalsGrid.style.display = 'none';
            noResults.style.display = 'none';
        }
    }

    function hideLoadingState() {
        loadingIndicator.style.display = 'none';
    }

    function updateStats(stats) {
        document.getElementById('totalListings').textContent = stats.total || 0;
        document.getElementById('avgPrice').textContent = '৳' + (stats.average_price || 0).toLocaleString();
        document.getElementById('totalLocations').textContent = stats.locations || 0;
    }

    function showError(message) {
        // Create and show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ef4444;
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        `;
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);

        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    // Handle inquiry buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-inquire') || e.target.closest('.btn-inquire')) {
            const button = e.target.classList.contains('btn-inquire') ? e.target : e.target.closest('.btn-inquire');
            const unitId = button.dataset.unitId;

            if (unitId) {
                // Redirect to inquiry form
                window.location.href = `/inquiry/unit/${unitId}`;
            }
        }

        // Handle favorite buttons
        if (e.target.classList.contains('btn-favorite') || e.target.closest('.btn-favorite')) {
            const button = e.target.classList.contains('btn-favorite') ? e.target : e.target.closest('.btn-favorite');
            const unitId = button.dataset.unitId;

            if (unitId) {
                // Toggle favorite (requires authentication)
                fetch(`/favorites/${unitId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.classList.toggle('active');
                        button.innerHTML = button.classList.contains('active') ?
                            '<i class="fas fa-heart"></i>' :
                            '<i class="far fa-heart"></i>';
                    } else {
                        alert('Please log in to add favorites.');
                    }
                })
                .catch(error => {
                    console.error('Error toggling favorite:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        }
    });
});
</script>

@endsection
