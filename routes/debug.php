<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Debug route to check database tables
Route::get('/debug/check-tables', function() {
    try {
        $tables = [
            'system_settings',
            'course',
            'department',
            'school_year_and_semester',
            'students'
        ];
        
        $results = [];
        
        foreach ($tables as $table) {
            $results[$table] = [
                'exists' => Schema::hasTable($table),
                'count' => Schema::hasTable($table) ? DB::table($table)->count() : 'N/A'
            ];
        }
        
        return response()->json([
            'database' => config('database.default'),
            'connection' => config('database.connections.mysql'),
            'tables' => $results,
            'env_check' => [
                'DB_HOST' => env('DB_HOST'),
                'DB_DATABASE' => env('DB_DATABASE'),
                'DB_USERNAME' => env('DB_USERNAME') ? 'SET' : 'NOT SET',
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
