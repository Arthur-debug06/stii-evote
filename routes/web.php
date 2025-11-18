<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\PartylistController;
use App\Livewire\Dashboard\Dashboard as DashboardComponent;
use App\Http\Controllers\forgotpassword\ForgotPasswordController;
use App\Http\Controllers\otp\OtpController;
use App\Http\Controllers\appointment\AppointmentController;
use App\Http\Controllers\register\RegisterController;
use App\Http\Controllers\pdf\PdfController;
use App\Http\Controllers\PublicFileController;

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

// Catch-all route for single-file view resolution (kept as fallback)
Route::get("{any}", [RouteController::class, 'routes']);