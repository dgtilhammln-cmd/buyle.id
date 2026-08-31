<style>
    .sidebar-container {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        left: 0; top: 0;
        padding: 30px 0 30px 20px;
        z-index: 1000;
        background: var(--bg-dark);
        transition: 0.5s cubic-bezier(0.77, 0, 0.175, 1);
    }

    .sidebar-brand { padding: 10px 15px 60px 15px; text-align: left; }
    .logo-box {
    display: inline-flex;
    align-items: center; /* Memastikan logo di tengah secara vertikal */
    background: #000;
    padding: 12px 24px; /* Padding lebih luas agar mewah */
    border-radius: 100px;
    border: 1px solid rgba(161, 255, 90, 0.3); /* Border dipertegas sedikit */
    box-shadow: 0 5px 25px rgba(161, 255, 90, 0.15); /* Shadow lebih dalam */
    }
    .logo-box img { height: 38px; width: auto; }

    .menu-list { list-style: none; margin-top: 10px; }

    .nav-item {
        position: relative;
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px 25px;
        color: rgba(255,255,255,0.3);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: 0.3s;
    }
    .nav-item i { font-size: 18px; width: 25px; text-align: center; }

    /* Efek Liquid / Menyambung */
    .nav-item.active {
        background: var(--white);
        color: #000;
        border-radius: 40px 0 0 40px;
        margin-right: -1px;
        z-index: 10;
    }

    /* Lengkung Halus Atas */
    .nav-item.active::before {
        content: ""; position: absolute;
        top: -45px; right: 0; width: 45px; height: 45px;
        background: transparent;
        border-bottom-right-radius: 35px;
        box-shadow: 15px 15px 0 15px var(--white);
        pointer-events: none;
    }

    /* Lengkung Halus Bawah */
    .nav-item.active::after {
        content: ""; position: absolute;
        bottom: -45px; right: 0; width: 45px; height: 45px;
        background: transparent;
        border-top-right-radius: 35px;
        box-shadow: 15px -15px 0 15px var(--white);
        pointer-events: none;
    }

    .nav-item:hover:not(.active) { color: var(--neon); transform: translateX(5px); }

    .nav-item.logout { margin-top: 30px; color: #ff5f5f; opacity: 0.6; }
    .nav-item.logout:hover { opacity: 1; color: #ff5f5f; }

    @media (max-width: 992px) {
        .sidebar-container { left: -300px; padding-right: 20px; }
        .sidebar-container.open { left: 0; border-right: 1px solid rgba(255,255,255,0.1); }
        /* Hilangkan efek liquid di mobile agar rapi */
        .nav-item.active { border-radius: 20px; margin-right: 15px; }
        .nav-item.active::before, .nav-item.active::after { display: none; }
    }
</style>

<aside class="sidebar-container" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-box">
            <img src="/assets/images/logo.png" alt="HVM LOGO">
        </div>
    </div>

    <ul class="menu-list">
        <li>
            <a href="?page=overview" class="nav-item <?= $view == 'overview' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> <span>Overview</span>
            </a>
        </li>
        <li>
            <a href="?page=accounts" class="nav-item <?= $view == 'accounts' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> <span>Accounts</span>
            </a>
        </li>
        <li>
            <a href="?page=analytics" class="nav-item <?= $view == 'analytics' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> <span>Analytics</span>
            </a>
        </li>
        <li>
            <a href="?page=withdraw" class="nav-item <?= $view == 'withdraw' ? 'active' : '' ?>">
                <i class="fas fa-wallet"></i> <span>Withdraw</span>
            </a>
        </li>
        <li>
            <a href="?page=payments" class="nav-item <?= $view == 'payments' ? 'active' : '' ?>">
                <i class="fas fa-credit-card"></i> <span>Payments</span>
            </a>
        </li>

        <hr style="opacity: 0.05; margin: 20px;">

        <li>
            <a href="/logout" class="nav-item logout">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </li>
    </ul>
</aside>