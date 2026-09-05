<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecuritySanitizerMiddleware
{
    /**
     * Middleware untuk membersihkan input request dari potensi Script Injection (XSS)
     * dan pola serangan berbahaya sebelum sampai ke controller.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        if (!empty($input)) {
            array_walk_recursive($input, function (&$value) {
                if (is_string($value) && !empty($value)) {
                    // Strips NULL bytes
                    $value = str_replace(chr(0), '', $value);
                    
                    // Netralkan tag script berbahaya & event listener inline XSS jika ada di input teks biasa
                    // (tetapi biarkan tag HTML standar jika memang dibutuhkan)
                    $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);
                    $value = preg_replace('/javascript:/i', '', $value);
                    $value = preg_replace('/(on[a-z]+)\s*=\s*([\'"][^\'"]*[\'"]|[^\s>]+)/i', '', $value);
                }
            });

            $request->merge($input);
        }

        return $next($request);
    }
}
