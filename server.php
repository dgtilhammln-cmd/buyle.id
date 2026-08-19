<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$cleanUri = parse_url($uri, PHP_URL_PATH) ?? $uri;

// INTERCEPT /storage/ FIRST so it never returns false and doesn't rely on the built-in server
if (strpos($cleanUri, '/storage/') === 0) {
    $relative = substr($cleanUri, strlen('/storage/'));
    $file = __DIR__.'/storage/app/public/'.$relative;
    if (file_exists($file) && is_file($file)) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $mimes = [
            'jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png',
            'gif'=>'image/gif','webp'=>'image/webp','svg'=>'image/svg+xml',
            'ico'=>'image/x-icon','css'=>'text/css','js'=>'application/javascript',
            'pdf'=>'application/pdf','woff'=>'font/woff','woff2'=>'font/woff2',
            'mp4'=>'video/mp4','txt'=>'text/plain','xml'=>'text/xml',
        ];
        header('HTTP/1.1 200 OK');
        header('Content-Type: '.($mimes[$ext] ?? 'application/octet-stream'));
        header('Content-Length: '.filesize($file));
        header('Cache-Control: public, max-age=3600');
        readfile($file);
        exit;
    }
}

// Serve other static files directly from public_html
if ($cleanUri !== '/' && file_exists(__DIR__.'/public_html'.$cleanUri) && is_file(__DIR__.'/public_html'.$cleanUri)) {
    // If it's a real file inside public_html (not storage), we can try to serve it manually to avoid built-in server document root issues
    $file = __DIR__.'/public_html'.$cleanUri;
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimes = [
        'jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png',
        'gif'=>'image/gif','webp'=>'image/webp','svg'=>'image/svg+xml',
        'ico'=>'image/x-icon','css'=>'text/css','js'=>'application/javascript',
        'pdf'=>'application/pdf','woff'=>'font/woff','woff2'=>'font/woff2',
    ];
    header('HTTP/1.1 200 OK');
    header('Content-Type: '.($mimes[$ext] ?? 'application/octet-stream'));
    header('Content-Length: '.filesize($file));
    header('Cache-Control: public, max-age=3600');
    readfile($file);
    exit;
}

require_once __DIR__.'/public_html/index.php';
