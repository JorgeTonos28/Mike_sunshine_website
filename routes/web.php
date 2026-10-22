<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

Route::get('/media/{filename}', function (string $filename) {
    abort_unless(preg_match('/^[a-f0-9-]{36}\.webp$/', $filename), 404);
    abort_unless(Storage::disk('public')->exists($filename), 404);

    return response()->file(Storage::disk('public')->path($filename), ['Content-Type' => 'image/webp', 'Cache-Control' => 'public, max-age=31536000, immutable']);
});

Route::get('/', function () {
    return view('welcome');
});
