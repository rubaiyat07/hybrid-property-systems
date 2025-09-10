// Loading animation
window.addEventListener("load", function () {
    const loadingOverlay = document.getElementById("loadingOverlay");
    setTimeout(() => {
        loadingOverlay.style.opacity = "0";
        setTimeout(() => {
            loadingOverlay.style.display = "none";
        }, 500);
    }, 1000);
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        const href = this.getAttribute("href");
        if (href && href.length > 1) {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        }
    });
});

// Navbar background change on scroll
window.addEventListener("scroll", function () {
    const navbar = document.querySelector(".navbar");
    if (window.scrollY > 100) {
        navbar.style.background = "rgba(255, 255, 255, 0.95)";
        navbar.style.backdropFilter = "blur(20px)";
    } else {
        navbar.style.background = "rgba(255, 255, 255, 0.1)";
        navbar.style.backdropFilter = "blur(20px)";
    }
});

// Counter animation for stats
function animateCounters() {
    const counters = document.querySelectorAll("[data-count]");
    counters.forEach((counter) => {
        const target = parseFloat(counter.dataset.count);
        const increment = target / 100;
        let current = 0;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent =
                    target + (target < 100 ? "%" : target === 2.5 ? "B" : "+");
                clearInterval(timer);
            } else {
                counter.textContent =
                    Math.floor(current) +
                    (target < 100 ? "%" : target === 2.5 ? "B" : "+");
            }
        }, 20);
    });
}

// Intersection Observer for animations
const observer = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains("stats")) animateCounters();
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
            }
        });
    },
    { threshold: 0.1 }
);

// Observe sections for scroll animations
document.querySelectorAll("section").forEach((section) => {
    section.style.opacity = "0";
    section.style.transform = "translateY(50px)";
    section.style.transition = "all 0.8s ease-in-out";
    observer.observe(section);
});

// Parallax effect for hero section
window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector(".hero");
    if (hero) hero.style.transform = `translateY(${scrolled * 0.5}px)`;
});

// Interactive property & feature cards
document.querySelectorAll(".property-card, .feature-card").forEach((card) => {
    card.addEventListener("mouseenter", function () {
        this.style.transform = `scale(1.05) translateY(-10px)`;
        this.style.boxShadow = "0 30px 60px rgba(0, 0, 0, 0.2)";
    });
    card.addEventListener("mouseleave", function () {
        this.style.transform = "";
        this.style.boxShadow = "";
    });
});

// --- House Rent demo data & filtering ---
const rentItems = [
    {
        id: 1,
        title: "Modern 2BR in Banani",
        city: "Dhaka",
        beds: 2,
        price: 30000,
        area: "950 sqft",
    },
    {
        id: 2,
        title: "Cozy Studio near Agrabad",
        city: "Chattogram",
        beds: 1,
        price: 15000,
        area: "520 sqft",
    },
    {
        id: 3,
        title: "3BR Family Home, Sylhet",
        city: "Sylhet",
        beds: 3,
        price: 42000,
        area: "1250 sqft",
    },
    {
        id: 4,
        title: "Smart Loft in Gulshan",
        city: "Dhaka",
        beds: 1,
        price: 50000,
        area: "700 sqft",
    },
    {
        id: 5,
        title: "Riverside Apt, Khulna",
        city: "Khulna",
        beds: 2,
        price: 22000,
        area: "880 sqft",
    },
    {
        id: 6,
        title: "Green View 3BR, Uttara",
        city: "Dhaka",
        beds: 3,
        price: 38000,
        area: "1180 sqft",
    },
];

function renderRent(list) {
    const grid = document.getElementById("rentGrid");
    grid.innerHTML = "";
    list.forEach((item) => {
        const card = document.createElement("div");
        card.className = "rent-card";
        card.innerHTML = `
            <div class="rent-img">
                <i class="fas fa-house"></i>
                <span class="rent-badge">Verified</span>
            </div>
            <div class="rent-body">
                <div class="rent-title">${item.title}</div>
                <div class="rent-meta"><i class="fa-solid fa-location-dot"></i> ${
                    item.city
                } • ${item.area} • ${item.beds} Beds</div>
                <div class="rent-footer">
                    <div class="rent-price">৳${item.price.toLocaleString()}</div>
                    <button class="rent-btn">View</button>
                </div>
            </div>`;
        grid.appendChild(card);
    });
}

function applyRentFilters() {
    const q = document.getElementById("rentSearch").value.toLowerCase();
    const city = document.getElementById("rentCity").value;
    const beds = document.getElementById("rentBeds").value;
    const price = document.getElementById("rentPrice").value;
    let filtered = rentItems.filter(
        (r) =>
            (!q || r.title.toLowerCase().includes(q)) &&
            (!city || r.city === city) &&
            (!beds || r.beds >= parseInt(beds)) &&
            (!price || r.price <= parseInt(price))
    );
    renderRent(filtered);
}

["rentSearch", "rentCity", "rentBeds", "rentPrice"].forEach((id) => {
    document.getElementById(id).addEventListener("input", applyRentFilters);
    document.getElementById(id).addEventListener("change", applyRentFilters);
});

renderRent(rentItems);
