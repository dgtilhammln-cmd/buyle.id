<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeResponseMiddleware
{
    /**
     * Handle an incoming request.
     * Minify HTML output to reduce payload size and speed up browser parsing.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add X-Robots-Tag header for SEO compliance
        if (method_exists($response, 'header')) {
            $response->header('X-Robots-Tag', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1');
        } else if (isset($response->headers)) {
            $response->headers->set('X-Robots-Tag', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1');
        }

        // Only minify text/html responses (not JSON, files, redirects)
        $contentType = $response->headers->get('Content-Type', '');
        if (
            !str_contains($contentType, 'text/html') ||
            $response->getStatusCode() !== 200
        ) {
            return $response;
        }

        $content = $response->getContent();

        // Skip if already empty
        if (!$content) {
            return $response;
        }

        $minified = $this->minifyHtml($content);
        $response->setContent($minified);

        return $response;
    }

    private function minifyHtml(string $html): string
    {
        // Protect <pre>, <script>, <style>, <textarea> from whitespace removal
        $placeholders = [];
        $i = 0;

        // Protect <pre> blocks
        $html = preg_replace_callback(
            '/<pre\b[^>]*>.*?<\/pre>/is',
            function ($m) use (&$placeholders, &$i) {
                $key = "%%PROTECTED_{$i}%%";
                $placeholders[$key] = $m[0];
                $i++;
                return $key;
            },
            $html
        );

        // Protect <script> blocks
        $html = preg_replace_callback(
            '/<script\b[^>]*>.*?<\/script>/is',
            function ($m) use (&$placeholders, &$i) {
                $key = "%%PROTECTED_{$i}%%";
                $placeholders[$key] = $m[0];
                $i++;
                return $key;
            },
            $html
        );

        // Protect <style> blocks
        $html = preg_replace_callback(
            '/<style\b[^>]*>.*?<\/style>/is',
            function ($m) use (&$placeholders, &$i) {
                $key = "%%PROTECTED_{$i}%%";
                $placeholders[$key] = $m[0];
                $i++;
                return $key;
            },
            $html
        );

        // Protect <textarea> blocks
        $html = preg_replace_callback(
            '/<textarea\b[^>]*>.*?<\/textarea>/is',
            function ($m) use (&$placeholders, &$i) {
                $key = "%%PROTECTED_{$i}%%";
                $placeholders[$key] = $m[0];
                $i++;
                return $key;
            },
            $html
        );

        // Remove HTML comments (but keep conditional comments <!--[if ...])
        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);

        // Collapse multiple whitespace / newlines between tags into a single space
        $html = preg_replace('/\s{2,}/s', ' ', $html);
        $html = preg_replace('/>\s+</s', '><', $html);

        // Restore protected blocks
        $html = str_replace(array_keys($placeholders), array_values($placeholders), $html);

        return trim($html);
    }
}
