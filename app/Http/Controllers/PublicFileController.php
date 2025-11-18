<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicFileController extends Controller
{
    public function show(string $path): StreamedResponse
    {
        $publicPath = storage_path('app/public');
        $fullPath = $publicPath . '/' . $path;
        
        Log::info('PublicFileController accessed', [
            'requested_path' => $path,
            'full_path' => $fullPath,
            'exists_via_storage' => Storage::disk('public')->exists($path),
            'exists_via_file' => file_exists($fullPath),
            'public_path' => $publicPath,
            'files_in_dir' => is_dir(dirname($fullPath)) ? scandir(dirname($fullPath)) : 'dir not found'
        ]);
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found in public storage: ' . $path);
        }

        return Storage::disk('public')->response($path);
    }
}
