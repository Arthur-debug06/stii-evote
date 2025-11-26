<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\login\LoginController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\PartylistController;
use App\Livewire\Dashboard\Dashboard as DashboardComponent;
use App\Http\Controllers\forgotpassword\ForgotPasswordController;
use App\Http\Controllers\otp\OtpController;
use App\Http\Controllers\appointment\AppointmentController;
use App\Http\Controllers\register\RegisterController;
use App\Http\Controllers\pdf\PdfController;
use App\Http\Controllers\PublicFileController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Super simple test route
Route::get('/debug/simple-test', function() {
    return response()->json([
        'status' => 'ok',
        'php_version' => phpversion(),
        'laravel_version' => app()->version(),
        'environment' => app()->environment()
    ]);
});

// Test database connection
Route::get('/debug/db-test', function() {
    try {
        DB::connection()->getPdo();
        $tables = DB::select('SHOW TABLES');
        return response()->json([
            'status' => 'ok',
            'db_connected' => true,
            'tables_count' => count($tables)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test model loading
Route::get('/debug/model-test', function() {
    try {
        $courses = \App\Models\course::count();
        $departments = \App\Models\department::count();
        return response()->json([
            'status' => 'ok',
            'courses' => $courses,
            'departments' => $departments
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Test register controller data only
Route::get('/debug/register-data', function() {
    try {
        $activeSchoolYear = \App\Models\School_Year_And_Semester::where('status', 'active')->first();
        $courses = \App\Models\course::where('status', 'active')->orderBy('course_name')->get();
        $departments = \App\Models\department::where('status', 'active')->orderBy('department_name')->get();
        
        return response()->json([
            'status' => 'ok',
            'school_year' => $activeSchoolYear ? $activeSchoolYear->year_and_semester : null,
            'courses_count' => $courses->count(),
            'departments_count' => $departments->count()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
});

// Test view rendering with real data
Route::get('/debug/render-test', function() {
    try {
        $controller = new \App\Http\Controllers\register\RegisterController();
        return $controller->index();
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => array_slice(explode("\n", $e->getTraceAsString()), 0, 10)
        ], 500);
    }
});

// Test view compilation without rendering
Route::get('/debug/view-test', function() {
    try {
        $viewPath = resource_path('views/register/register.blade.php');
        $viewExists = file_exists($viewPath);
        $manifestPath = public_path('build/manifest.json');
        $manifestExists = file_exists($manifestPath);
        
        return response()->json([
            'status' => 'ok',
            'view_path' => $viewPath,
            'view_exists' => $viewExists,
            'manifest_path' => $manifestPath,
            'manifest_exists' => $manifestExists,
            'public_path' => public_path(),
            'resource_path' => resource_path()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage()
        ], 500);
    }
});

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

// Debug register error route
Route::get('/debug/register-error', function() {
    try {
        // Test 1: Controller instantiation
        $controller = new \App\Http\Controllers\register\RegisterController();
        
        // Test 2: Get data without rendering view
        $activeSchoolYear = \App\Models\School_Year_And_Semester::where('status', 'active')->first();
        $courses = \App\Models\Course::where('status', 'active')->orderBy('course_name')->get();
        $departments = \App\Models\Department::where('status', 'active')->orderBy('department_name')->get();
        
        // Test 3: Check if view file exists
        $viewPath = resource_path('views/register/register.blade.php');
        $viewExists = file_exists($viewPath);
        
        // Test 4: Check vite manifest
        $manifestPath = public_path('build/manifest.json');
        $manifestExists = file_exists($manifestPath);
        
        return response()->json([
            'status' => 'success',
            'tests' => [
                'controller_loaded' => true,
                'school_year_found' => $activeSchoolYear ? true : false,
                'courses_count' => $courses->count(),
                'departments_count' => $departments->count(),
                'view_exists' => $viewExists,
                'manifest_exists' => $manifestExists,
                'view_path' => $viewPath,
                'manifest_path' => $manifestPath
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => array_slice($e->getTrace(), 0, 5)
        ], 500);
    }
});

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Register Routes
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('forgot-password.send');


// OTP Routes
Route::get('/otp', [OtpController::class, 'index'])->name('otp');
Route::post('/otp', [OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp/resend', [OtpController::class, 'resendOtp'])->name('otp.resend');

// Appointment Routes
Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
Route::post('/appointment', [AppointmentController::class, 'store'])->name('appointment.store');

// PDF Routes
Route::get('/pdf/candidates-list', [PdfController::class, 'candidatesList'])->name('pdf.candidates-list');
Route::get('/pdf/candidates-election', [PdfController::class, 'candidatesElection'])->name('pdf.candidates-election');
Route::get('/pdf/students-account', [PdfController::class, 'studentsAccount'])->name('pdf.students-account');
Route::get('/pdf/admin-account', [PdfController::class, 'adminAccount'])->name('pdf.admin-account');
// Student Voter routes
Route::get('/student-voter', [PdfController::class, 'studentVotersIndex'])->name('student-voter.index');
Route::get('/pdf/student-voters/{id}', [PdfController::class, 'studentVoters'])->name('pdf.student-voters');
// AJAX endpoint to return student voters for a voting exclusive (JSON)
Route::get('/api/student-voters/{id}', [PdfController::class, 'studentVotersJson'])->name('api.student-voters');

Route::get('/dashboard', function() {
    return view('dashboard.index-dashboard');
})->name('dashboard');

// Notification Routes
Route::post('/notifications/{notification}/mark-read', function($notificationId) {
    $notification = \App\Models\Notification::findOrFail($notificationId);
    $notification->markAsRead();
    
    return response()->json(['success' => true]);
})->name('notifications.mark-read');

// API route to fetch unread notifications for login toasts
Route::get('/api/notifications/unread', function() {
    // Get current user ID and type from either guard
    $userId = null;
    $userType = null;
    
    if (auth()->check()) {
        $userId = auth()->id();
        $userType = 'App\\Models\\User';
    } elseif (auth()->guard('students')->check()) {
        $userId = auth()->guard('students')->id();
        $userType = 'App\\Models\\students';
    }
    
    // Debug logging
    \Log::info('API Notifications Debug', [
        'user_id' => $userId,
        'user_type' => $userType,
        'auth_check' => auth()->check(),
        'students_auth_check' => auth()->guard('students')->check(),
        'auth_id' => auth()->id(),
        'students_auth_id' => auth()->guard('students')->id(),
    ]);
    
    if (!$userId) {
        return response()->json(['success' => false, 'message' => 'Not authenticated']);
    }
    
    // Get unread notifications for the current user where they are the notifiable_id (the one who was notified)
    $notifications = \App\Models\Notification::where('user_id', $userId)
        ->where('notifiable_id', (string) $userId) // Only show if current user is the notifiable_id
        ->where('notifiable_type', $userType) // Match the user type
        ->whereNull('read_at')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    // Additional debug: Check if any notifications exist for this user
    \Log::info('User notification check', [
        'current_user_id' => $userId,
        'current_user_type' => auth()->check() ? 'User' : 'Student',
        'notifications_for_user' => $notifications->count(),
        'all_notifications_in_db' => \App\Models\Notification::count(),
        'sample_notification' => \App\Models\Notification::first() ? \App\Models\Notification::first()->toArray() : null
    ]);
    
    // Debug logging
    \Log::info('Notifications found', [
        'count' => $notifications->count(),
        'notifications' => $notifications->toArray()
    ]);
    
    $formattedNotifications = $notifications->map(function($notification) {
        return [
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'status' => $notification->status,
            'type' => $notification->type,
            'icon' => $notification->icon,
            'icon_color' => $notification->icon_color,
            'url' => $notification->url,
            'created_at' => $notification->created_at->format('M d, Y h:i A'),
        ];
    });
    
    return response()->json([
        'success' => true,
        'notifications' => $formattedNotifications,
        'count' => $formattedNotifications->count(),
        'debug' => [
            'user_id' => $userId,
            'total_notifications' => \App\Models\Notification::where('user_id', $userId)->count(),
            'unread_notifications' => \App\Models\Notification::where('user_id', $userId)->whereNull('read_at')->count()
        ]
    ]);
})->name('api.notifications.unread');

// API route to process expired voting status updates
Route::post('/api/voting/process-expired', function() {
    try {
        $processedCount = \App\Services\VotingStatusService::processExpiredVotings();
        
        return response()->json([
            'success' => true,
            'processed_count' => $processedCount,
            'message' => $processedCount > 0 ? "Processed {$processedCount} expired voting(s)" : 'No expired votings to process'
        ]);
    } catch (\Exception $e) {
        \Log::error('Error processing expired votings: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error processing expired votings',
            'error' => $e->getMessage()
        ], 500);
    }
})->name('api.voting.process-expired');

// Voting history routes
Route::get('/voting-histories', [\App\Http\Controllers\VotingHistoryController::class, 'index'])->name('voting-histories.index');
Route::get('/voting-histories/{id}', [\App\Http\Controllers\VotingHistoryController::class, 'show'])->name('voting-histories.show');
// Explicit routes for candidacy management pages (prefer explicit routes over catch-all)
// Attachment route for grade attachments (served via controller to avoid direct storage issues)
Route::get('/attachments/candidacy-grade/{id}', [\App\Http\Controllers\AttachmentController::class, 'candidacyGrade'])->name('attachments.candidacy-grade');
Route::get('/attachments/student-image/{student}/{type}', [\App\Http\Controllers\AttachmentController::class, 'studentImage'])->name('attachments.student-image');

// Public file serving route (avoids symlink 403 issues)
Route::get('/files/{path}', [PublicFileController::class, 'show'])
    ->where('path', '.*')
    ->name('public.file');

Route::get('/candidacy-management', function() {
    return view('candidacy-management.index-candidacy-management');
})->name('candidacy-management');

Route::get('/candidacy-management1', function() {
    return view('candidacy-management.index-candidacy-management1');
})->name('candidacy-management1');

// Public partylist page: show candidates for a given partylist
Route::get('/partylist/{id}', [PartylistController::class, 'show'])->name('partylist.show');

// STSG Admin Notification Module
Route::get('/stsg-admin-notifications', function() {
    return view('livewire.stsg-admin-notification.stsg-admin-notification');
})->name('stsg-admin-notifications');

// Directly serve system_settings assets from public for reliability on Railway
Route::get('storage/system_settings/{path}', function ($path) {
    $file = public_path('system_settings/' . $path);
    if (!file_exists($file)) {
        abort(404);
    }
    return response()->file($file, [
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.system_settings');

// Storage file serving route (for Railway - must come before catch-all route)
Route::get('storage/{path}', [PublicFileController::class, 'show'])
    ->where('path', '.*')
    ->name('storage.serve');

// Catch-all route for single-file view resolution (kept as fallback)
Route::get("{any}", [RouteController::class, 'routes']);