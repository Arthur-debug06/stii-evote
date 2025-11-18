<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response as HttpResponse;

class PublicFileController extends Controller
{
    public function show(string $path)
    {
        // Normalize path and protect against traversal
        $path = ltrim($path, '/');
        $path = str_replace('..', '', $path);

        $storageRoot = storage_path('app/public');
        $storageFull = $storageRoot . DIRECTORY_SEPARATOR . $path;
        $publicSymlinkFull = public_path('storage' . DIRECTORY_SEPARATOR . $path);

        // Log diagnostics
        Log::info('PublicFileController accessed', [
            'requested_path' => $path,
            'storage_full' => $storageFull,
            'public_symlink_full' => $publicSymlinkFull,
            'exists_storage' => file_exists($storageFull),
            'exists_public_symlink' => file_exists($publicSymlinkFull),
            'storage_disk_exists' => Storage::disk('public')->exists($path),
        ]);

        // Strategy 1: Serve from storage/app/public via direct path
        if (file_exists($storageFull)) {
            return response()->file($storageFull, [
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }

        // Strategy 2: Serve from public/storage (if symlink exists)
        if (file_exists($publicSymlinkFull)) {
            return response()->file($publicSymlinkFull, [
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }

        // Strategy 3: If only filename was saved, search in system_settings
        $basename = basename($path);
        $systemSettingsDir = $storageRoot . DIRECTORY_SEPARATOR . 'system_settings';
        $candidate = $systemSettingsDir . DIRECTORY_SEPARATOR . $basename;
        if (file_exists($candidate)) {
            return response()->file($candidate, [
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }

        // Final fallback using Storage disk (in case of non-local drivers)
        if (Storage::disk('public')->exists($path)) {
            $stream = Storage::disk('public')->readStream($path);
            $mime = Storage::disk('public')->mimeType($path) ?? 'application/octet-stream';
            return new HttpResponse(stream_get_contents($stream), 200, [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        }

        abort(404, 'File not found: ' . $path);
    }
}
