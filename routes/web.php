<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// QA fixture for flywp-app PR #3200: records what a notification webhook sends (temporary repo).
Route::post('/qa-hook', function (\Illuminate\Http\Request $request) {
    $entry = json_encode(['at' => now()->toIso8601String(), 'body' => $request->getContent()]);
    file_put_contents(storage_path('app/qa-hook.log'), $entry."\n", FILE_APPEND | LOCK_EX);

    return response()->json(['ok' => true]);
});

Route::get('/qa-hook-last', function () {
    $path = storage_path('app/qa-hook.log');
    $lines = is_file($path) ? array_slice(file($path, FILE_IGNORE_NEW_LINES), -5) : [];

    return response()->json($lines);
});
