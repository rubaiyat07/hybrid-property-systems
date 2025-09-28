<nav class="navbar">
    <div class="nav-container">
        <div class="logo"><a href="{{ route('admin.dashboard') }}">HybridEstate</a></div>

        <div class="user-menu">
            <div class="menu-icon" onclick="toggleDropdown()">
                &#9776; <!-- Hamburger icon -->
            </div>
            <div id="dropdown" class="dropdown">
                <a href="#profile">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="#settings">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>