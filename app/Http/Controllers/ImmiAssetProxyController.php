<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Streams (and caches) static assets — mainly icon fonts and a few
 * SharePoint chrome images — from the live immi.homeaffairs.gov.au site.
 *
 * Why this exists: those fonts/images were never captured when the page
 * was saved locally (browsers only save what's directly in the DOM, not
 * everything referenced from CSS). Pointing app.css/oslo.css straight at
 * the live absolute URL doesn't work either, because @font-face requests
 * are subject to CORS and the government site doesn't send an
 * Access-Control-Allow-Origin header for its asset folder — so the
 * browser silently blocks the font.
 *
 * This controller fetches the file server-side (no CORS involved between
 * two servers), saves a copy to storage/app/public so it only ever hits
 * the remote site once per file, and serves it from your own domain from
 * then on — which the browser treats as same-origin.
 */
class ImmiAssetProxyController extends Controller
{
    /** Only these two remote path prefixes are allowed to be proxied. */
    protected array $allowedPrefixes = [
        'AssetLibrary/',
        '_layouts/',
    ];

    protected string $remoteBase = 'https://immi.homeaffairs.gov.au/';

    public function show(Request $request, string $path): Response
    {
        // Basic safety: only allow known prefixes and block path traversal.
        $path = ltrim($path, '/');
        $isAllowed = collect($this->allowedPrefixes)->contains(
            fn ($prefix) => str_starts_with($path, $prefix)
        );

        if (! $isAllowed || str_contains($path, '..')) {
            abort(404);
        }

        $cacheKey = 'immi-proxy/' . $path;

        // Serve from local cache if we've already fetched this file.
        if (Storage::disk('public')->exists($cacheKey)) {
            return $this->fileResponse(
                Storage::disk('public')->get($cacheKey),
                $this->guessContentType($path)
            );
        }

        $remoteUrl = $this->remoteBase . $path;

        $response = Http::timeout(10)
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (Laravel asset proxy)'])
            ->get($remoteUrl);

        if (! $response->successful()) {
            abort(502, "Could not fetch upstream asset: {$remoteUrl}");
        }

        $body = $response->body();
        $contentType = $response->header('Content-Type') ?: $this->guessContentType($path);

        // Cache to disk so future requests never leave your server.
        Storage::disk('public')->put($cacheKey, $body);

        return $this->fileResponse($body, $contentType);
    }

    protected function fileResponse(string $body, string $contentType): Response
    {
        return response($body, 200, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'public, max-age=2592000, immutable', // 30 days
        ]);
    }

    protected function guessContentType(string $path): string
    {
        return match (true) {
            str_ends_with($path, '.woff2') => 'font/woff2',
            str_ends_with($path, '.woff') => 'font/woff',
            str_ends_with($path, '.ttf') => 'font/ttf',
            str_ends_with($path, '.eot') => 'application/vnd.ms-fontobject',
            str_ends_with($path, '.svg') => 'image/svg+xml',
            str_ends_with($path, '.png') => 'image/png',
            str_ends_with($path, '.jpg'), str_ends_with($path, '.jpeg') => 'image/jpeg',
            str_ends_with($path, '.gif') => 'image/gif',
            str_ends_with($path, '.ico') => 'image/x-icon',
            str_ends_with($path, '.css') => 'text/css',
            str_ends_with($path, '.js') => 'application/javascript',
            default => 'application/octet-stream',
        };
    }
}
