<aside class="sidebar-container" id="sidebar">
    <div class="sidebar-glass">
        
        <div class="sidebar-header">
            <div class="logo-box">
                <!-- Gunakan path absolut / agar logo muncul -->
                <img src="/assets/images/logobio.png" alt="Logo" class="logo-img" onerror="this.src='https://via.placeholder.com/150x50?text=LOGO+BIO'">
            </div>
        </div>

        <nav class="menu-list">
            <a href="?view=overview" class="nav-item <?= $view=='overview'?'active':'' ?>">
                <i class="fas fa-home-alt"></i> <span>Overview</span>
            </a>
            <a href="?view=design" class="nav-item <?= $view=='design'?'active':'' ?>">
                <i class="fas fa-paint-brush"></i> <span>Tema Design</span>
            </a>
            <a href="?view=analytics" class="nav-item <?= $view=='analytics'?'active':'' ?>">
                <i class="fas fa-chart-line"></i> <span>Analyze</span>
            </a>
            <a href="?view=settings" class="nav-item <?= $view=='settings'?'active':'' ?>">
                <i class="fas fa-sliders"></i> <span>Settings</span>
            </a>
            <a href="?view=premium" class="nav-item <?= $view=='premium'?'active':'' ?>" style="color: #fbbf24;">
                <i class="fas fa-crown"></i> <span>Premium</span>
            </a>
        </nav>

        <div style="padding: 30px; margin-top: auto;">
            <a href="/logout" class="nav-item" style="color: #ff5a5a; padding-left: 10px;">
                <i class="fas fa-power-off"></i> <span>Logout</span>
            </a>
        </div>

    </div>
</aside>