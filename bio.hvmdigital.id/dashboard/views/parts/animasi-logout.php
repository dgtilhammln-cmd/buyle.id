<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HVM STUDIO | Securing Account</title>
    <link rel="icon" type="image/png" href="/assets/images/logobio.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;800&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #020b09; --neon: #a1ff5a; }
        body { margin: 0; background: var(--bg); color: #fff; font-family: 'Montserrat', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; overflow: hidden; }
        .container { text-align: center; width: 100%; max-width: 320px; }
        
        img { height: 50px; margin-bottom: 40px; filter: drop-shadow(0 0 15px rgba(161, 255, 90, 0.4)); animation: pulse 2s infinite; }
        
        .status-text { font-size: 10px; font-weight: 800; letter-spacing: 3px; color: #444; text-transform: uppercase; margin-bottom: 10px; }
        .percent { font-size: 50px; font-weight: 800; margin-bottom: 10px; font-variant-numeric: tabular-nums; }
        
        .progress-bg { width: 100%; height: 4px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden; }
        .progress-bar { height: 100%; width: 0%; background: var(--neon); box-shadow: 0 0 15px var(--neon); transition: width 0.1s linear; }

        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        .exit-anim { transition: 0.8s ease-in; transform: scale(1.2); filter: blur(20px); opacity: 0; }
    </style>
</head>
<body>

    <div class="container" id="mainBody">
        <img src="/assets/images/logobio.png">
        <div class="status-text" id="status">Securing Session</div>
        <div class="percent" id="pct">0%</div>
        <div class="progress-bg">
            <div class="progress-bar" id="bar"></div>
        </div>
    </div>

    <script>
        let p = 0;
        const bar = document.getElementById('bar');
        const pct = document.getElementById('pct');
        const status = document.getElementById('status');
        const body = document.getElementById('mainBody');

        const states = ["Cleaning Cache", "Securing Data", "Logging Out"];

        const interval = setInterval(() => {
            p += Math.floor(Math.random() * 4) + 2;
            if (p >= 100) {
                p = 100;
                clearInterval(interval);
                setTimeout(finish, 300);
            }
            bar.style.width = p + '%';
            pct.innerText = p + '%';
            
            // Ubah teks status berdasarkan progress
            if(p > 30 && p < 70) status.innerText = states[1];
            if(p >= 70) status.innerText = states[2];
        }, 60);

        function finish() {
            body.classList.add('exit-anim');
            setTimeout(() => {
                window.location.href = '/logout'; // Arahkan ke file PHP logout asli yang isinya session_destroy
            }, 600);
        }
    </script>
</body>
</html>