<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'HybridEstate - Revolutionary Property Investment Platform')</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <!--Custome css (welcome.css)-->
    <link rel="stylesheet" href="{{ asset('css/welcome.css')}}">

</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay"><div class="spinner"></div></div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">HybridEstate</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#rent">House Rent</a></li>
                <li><a href="#customers">Customers</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            {{-- <a href="#rent" class="cta-btn">Get Started</a> --}}
            <div>
                <a href="{{ route('login') }}" class="cta-btn">Log in</a>
                <a href="{{ route('register') }}" class="cta-btn">Sign up</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-container">
            <div class="hero-content">
                <h1>The Future of <span class="highlight">Property Investment</span> is Here</h1>
                <p>Discover revolutionary hybrid properties that combine traditional real estate with cutting-edge technology. Invest smarter, earn more, and be part of the next generation of property ownership.</p>
                <div class="hero-buttons">
                    <a href="#rent" class="btn-primary">Start Investing</a>
                    <a href="#features" class="btn-secondary">Learn More</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="property-card">
                    <div class="card-header">
                        <div class="property-icon"><i class="fas fa-building"></i></div>
                        <div class="card-content"><h3>Smart Office Complex</h3><p>AI-powered workspace with 12% annual returns</p></div>
                    </div>
                </div>
                <div class="property-card">
                    <div class="card-header">
                        <div class="property-icon"><i class="fas fa-home"></i></div>
                        <div class="card-content"><h3>Hybrid Residential</h3><p>IoT-enabled apartments with sustainable features</p></div>
                    </div>
                </div>
                <div class="property-card">
                    <div class="card-header">
                        <div class="property-icon"><i class="fas fa-store"></i></div>
                        <div class="card-content"><h3>Digital Retail Space</h3><p>Phygital stores with AR/VR capabilities</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header">
            <h2>Why Choose HybridEstate?</h2>
            <p>Experience the perfect blend of traditional real estate stability and modern technology innovation. Our platform offers unprecedented opportunities in the evolving property market.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                <h3>Smart Analytics</h3>
                <p>Advanced AI algorithms analyze market trends, predict property values, and optimize your investment portfolio for maximum returns.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Blockchain Security</h3>
                <p>Every transaction is secured by blockchain technology, ensuring transparency, immutability, and complete protection of your investments.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <h3>IoT Integration</h3>
                <p>Properties equipped with smart sensors and IoT devices provide real-time data, automated management, and enhanced tenant experiences.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item"><h3 data-count="2.5">0</h3><p>Billion in Assets</p></div>
            <div class="stat-item"><h3 data-count="15000">0</h3><p>Active Investors</p></div>
            <div class="stat-item"><h3 data-count="98">0</h3><p>Success Rate</p></div>
            <div class="stat-item"><h3 data-count="24">0</h3><p>Countries Served</p></div>
        </div>
    </section>

    <!-- HOUSE RENT Section -->
    <section class="rent" id="rent">
        <div class="section-header">
            <h2>House Rent</h2>
            <p>Browse smart, sustainable rentals across Dhaka, Chattogram, Sylhet & more. Filter by city, price and bedrooms.</p>
        </div>
        <div class="rent-controls">
            <input type="text" id="rentSearch" placeholder="Search location e.g. Banani, Dhanmondi" />
            <select id="rentCity">
                <option value="">All Cities</option>
                <option>Dhaka</option>
                <option>Chattogram</option>
                <option>Sylhet</option>
                <option>Khulna</option>
            </select>
            <select id="rentBeds">
                <option value="">Any Beds</option>
                <option value="1">1+</option>
                <option value="2">2+</option>
                <option value="3">3+</option>
            </select>
            <select id="rentPrice">
                <option value="">Max Price</option>
                <option value="15000">৳15k</option>
                <option value="30000">৳30k</option>
                <option value="50000">৳50k</option>
            </select>
        </div>
        <div class="rent-grid" id="rentGrid">
            <!-- Cards injected by JS -->
        </div>
    </section>

    <!-- OUR CUSTOMERS / Testimonials -->
    <section class="testimonials" id="customers">
        <div class="testi-container">
            <div class="section-header" style="color:#fff;">
                <h2 style="color:#fff;">Our Customers</h2>
                <p style="color:rgba(255,255,255,0.9)">Hear from investors and tenants who trust HybridEstate.</p>
            </div>
            <div class="testi-grid">
                <div class="testi-card">
                    <div class="testi-head">
                        <div class="avatar">AR</div>
                        <div>
                            <div class="testi-name">Anika Rahman</div>
                            <div class="testi-role">Investor, Dhaka</div>
                        </div>
                        <div class="stars" style="margin-left:auto;">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                    <p>“The analytics helped me rebalance my portfolio and boost returns by 15% in two quarters.”</p>
                </div>
                <div class="testi-card">
                    <div class="testi-head">
                        <div class="avatar">MS</div>
                        <div>
                            <div class="testi-name">Mohsin Siddique</div>
                            <div class="testi-role">Tenant, Chattogram</div>
                        </div>
                        <div class="stars" style="margin-left:auto;">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                        </div>
                    </div>
                    <p>“IoT-enabled apartment means lower bills and quick maintenance. Loved the seamless onboarding.”</p>
                </div>
                <div class="testi-card">
                    <div class="testi-head">
                        <div class="avatar">TS</div>
                        <div>
                            <div class="testi-name">Tahsin Sultana</div>
                            <div class="testi-role">Owner, Sylhet</div>
                        </div>
                        <div class="stars" style="margin-left:auto;">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p>“Verified tenants and blockchain-secure contracts made renting out my flat stress-free.”</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2>Ready to Transform Your Investment Strategy?</h2>
            <p>Join thousands of forward-thinking investors who are already benefiting from the hybrid property revolution. Start your journey today and secure your financial future.</p>
            <a href="#rent" class="btn-primary">Start Your Investment Journey</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="footer-container">
            <div class="footer-section">
                <h4>HybridEstate</h4>
                <p>Revolutionizing property investment through innovative technology and sustainable practices.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#rent">House Rent</a></li>
                    <li><a href="#customers">Customers</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Services</h4>
                <ul>
                    <li><a href="#">Investment Advisory</a></li>
                    <li><a href="#">Property Management</a></li>
                    <li><a href="#">Market Analysis</a></li>
                    <li><a href="#">Portfolio Optimization</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <ul>
                    <li><a href="mailto:info@hybridestate.com">info@hybridestate.com</a></li>
                    <li><a href="tel:+1234567890">+1 (234) 567-890</a></li>
                    <li><a href="#">Live Chat Support</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} HybridEstate. All rights reserved.</p>
        </div>
    </footer>
 
    <!--Custome js (welcome.js)-->
    <script src="{{ asset('js/welcome.js') }}"></script>

</body>
</html>
