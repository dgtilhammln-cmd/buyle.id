<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logging Out...</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@800&display=swap" rel="stylesheet">
    <style>
        body { margin:0; background:#020b09; height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Montserrat'; color:#fff; overflow:hidden; }
        .logout-box { text-align:center; animation: fadeOut 1s forwards 2.5s; }
        .circle-loader { width: 80px; height: 80px; border: 5px solid rgba(161,255,90,0.1); border-top-color: #a1ff5a; border-radius: 50%; animation: spin 1s infinite linear; margin: 0 auto 30px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes fadeOut { to { opacity:0; transform:scale(0.9); } }
    </style>
</head>
<body>
    <div class="logout-box">
        <div class="circle-loader"></div>
        <h1 style="letter-spacing:-1px;">SECURELY LOGGING OUT</h1>
        <p style="color:#666; font-size:14px;">See you soon at HVM STUDIO</p>
    </div>
    <script>
        setTimeout(() => {
            window.location.href = "/logout.php"; // Arahkan ke file logout asli Anda yang menghapus SESSION
        }, 3000);
    </script>
</body>
</html>