<div id="loginSplash" style="position:fixed; inset:0; background:#020b09; z-index:999999; display:flex; align-items:center; justify-content:center; flex-direction:column; color:#fff;">
    <div class="splash-logo" style="margin-bottom:30px; transform: scale(0.8); opacity:0; animation: logoEntry 1s forwards 0.5s;">
        <img src="/assets/images/logobio.png" style="height:60px;">
    </div>
    <div class="splash-text" style="text-align:center;">
        <h2 style="font-weight:800; opacity:0; animation: textFade 1s forwards 1s;">Welcome Back, <?= explode(' ', $me['fullname'])[0] ?>!</h2>
        <p style="color:#a1ff5a; font-size:12px; font-weight:700; letter-spacing:3px; opacity:0; animation: textFade 1s forwards 1.3s;">SYNCING YOUR ASSETS</p>
    </div>
</div>

<style>
@keyframes logoEntry { from { transform: scale(0.5); opacity:0; } to { transform: scale(1); opacity:1; } }
@keyframes textFade { from { opacity:0; transform: translateY(10); } to { opacity:1; transform: translateY(0); } }
</style>

<script>
setTimeout(() => {
    const splash = document.getElementById('loginSplash');
    splash.style.transition = '1s ease';
    splash.style.opacity = '0';
    splash.style.pointerEvents = 'none';
    // Hilangkan parameter welcome dari URL tanpa refresh
    window.history.replaceState({}, document.title, window.location.pathname + window.location.search.replace('&welcome=1','').replace('welcome=1',''));
    setTimeout(() => splash.remove(), 1000);
}, 3500);
</script>