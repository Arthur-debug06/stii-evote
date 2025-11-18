<?php

namespace App\Livewire\ProfileManagement;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\students;
use App\Models\course;
use App\Models\department;
use App\Models\school_year_and_semester;
use App\Models\otp;
use App\Models\ActionLog;
use Carbon\Carbon;

class ProfileManagement extends Component
{
    public $user;
    public $userType;
    
    // Form properties for editing (dynamic based on user model)
    // User model fields
    public $name;
    public $email;
    // Note: Role and Status are not editable for security reasons
    
    // Students model fields
    public $student_id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $gender;
    public $marital_status;
    public $date_of_birth;
    public $age;
    public $address;
    public $profile_image;
    public $student_id_image;
    public $course_id;
    public $department_id;
    public $school_year_and_semester_id;
    
    // Password change fields
    public $current_password;
    public $new_password;
    public $confirm_password;
    
    // Password visibility states
    public $show_current_password = false;
    public $show_new_password = false;
    public $show_confirm_password = false;
    
    // OTP verification properties
    public $showOtpModal = false;
    public $otpCode = '';
    public $pendingChanges = [];
    public $changeType = ''; // 'password' or 'email'
    public $originalEmail = '';

    public function mount()
    {
        // Check both guards to get the authenticated user
        $this->user = Auth::guard('web')->user() ?? Auth::guard('students')->user();
        
        // Determine user type and load relationships
        if ($this->user instanceof User) {
            $this->userType = 'admin';
            // Initialize form data for admin (based on User model fields)
            $this->name = $this->user->name ?? '';
            $this->email = $this->user->email ?? '';
            // Note: Role and Status are not editable for security reasons
        } elseif ($this->user instanceof students) {
            $this->userType = 'student';
            // Load student relationships
            $this->user->load(['course', 'department', 'school_year_and_semester']);
            // Initialize form data for student (based on students model fields)
            $this->student_id = $this->user->student_id ?? '';
            $this->first_name = $this->user->first_name ?? '';
            $this->middle_name = $this->user->middle_name ?? '';
            $this->last_name = $this->user->last_name ?? '';
            $this->suffix = $this->user->suffix ?? '';
            $this->gender = $this->user->gender ?? '';
            $this->marital_status = $this->user->marital_status ?? '';
            $this->date_of_birth = $this->user->date_of_birth ?? '';
            $this->age = $this->user->age ?? '';
            $this->address = $this->user->address ?? '';
            $this->profile_image = $this->user->profile_image ?? '';
            $this->student_id_image = $this->user->student_id_image ?? '';
            $this->email = $this->user->email ?? '';
            $this->course_id = $this->user->course_id ?? '';
            $this->department_id = $this->user->department_id ?? '';
            $this->school_year_and_semester_id = $this->user->school_year_and_semester_id ?? '';
            $this->status = $this->user->status ?? '';
        } else {
            $this->userType = 'unknown';
        }
        
        // Log profile access
        if ($this->user) {
            $this->logProfileActivity('profile_accessed', [
                'user_type' => $this->userType,
                'access_time' => now()->toISOString()
            ]);
        }
    }


    public function getCourses()
    {
        return course::all();
    }
    
    public function getDepartments()
    {
        return department::all();
    }
    
    public function getSchoolYearSemesters()
    {
        return school_year_and_semester::all();
    }
    
    public function toggleCurrentPasswordVisibility()
    {
        $this->show_current_password = !$this->show_current_password;
    }
    
    public function toggleNewPasswordVisibility()
    {
        $this->show_new_password = !$this->show_new_password;
    }
    
    public function toggleConfirmPasswordVisibility()
    {
        $this->show_confirm_password = !$this->show_confirm_password;
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        try {
            // Check if current password is correct
            if (!Hash::check($this->current_password, $this->user->password)) {
                session()->flash('error', 'Current password is incorrect.');
                return;
            }

            // Store pending changes and send OTP
            $this->pendingChanges = [
                'password' => Hash::make($this->new_password),
            ];
            $this->changeType = 'password';
            $this->originalEmail = $this->user->email;
            
            $this->sendOtp();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to change password: ' . $e->getMessage());
        }
    }
    
    public function updateProfile()
    {
        // Check if email is being changed
        $emailChanged = false;
        if ($this->userType === 'admin' && $this->email !== $this->user->email) {
            $emailChanged = true;
        } elseif ($this->userType === 'student' && $this->email !== $this->user->email) {
            $emailChanged = true;
        }
        
        if ($emailChanged) {
            // Validate email change
            $this->validate([
                'email' => 'required|email|unique:users,email,' . $this->user->id . '|unique:students,email,' . $this->user->id,
            ]);
            
            // Store pending changes and send OTP
            $this->pendingChanges = [
                'email' => $this->email,
            ];
            $this->changeType = 'email';
            $this->originalEmail = $this->user->email;
            
            $this->sendOtp();
        } else {
            // Regular profile update without OTP
            $this->performProfileUpdate();
        }
    }
    
    public function sendOtp()
    {
        try {
            // Generate 6-digit OTP
            $otp = rand(100000, 999999);
            
            // Delete any existing OTP for this email
            otp::where('email', $this->originalEmail)->delete();
            
            // Store OTP in database
            $otpRecord = otp::create([
                'email_from_id' => $this->userType === 'admin' ? 'User' : 'students',
                'email' => $this->originalEmail,
                'otp_number' => $otp,
                'status' => 'pending',
                'expired_at' => Carbon::now()->addMinutes(10) // OTP expires in 10 minutes
            ]);

            // Log OTP generation activity
            $this->logProfileActivity('otp_sent', [
                'change_type' => $this->changeType,
                'email' => $this->originalEmail,
                'otp_id' => $otpRecord->id
            ]);

            // Send OTP via email
            Mail::send('emails.otp', ['otp' => $otp, 'user' => $this->user], function ($message) {
                $message->to($this->originalEmail)
                        ->subject('Profile Change Verification - Voting System');
            });

            // Show OTP modal
            $this->showOtpModal = true;
            $this->otpCode = '';
            
            // Dispatch event to focus OTP input
            $this->dispatch('otp-modal-opened');
            
            session()->flash('success', 'OTP has been sent to your email address. Please verify to complete the change.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send OTP. Please try again. Error: ' . $e->getMessage());
        }
    }
    
    public function verifyOtp()
    {
        $this->validate([
            'otpCode' => 'required|string|size:6',
        ]);

        try {
            // Find the OTP record
            $otpRecord = otp::where('email', $this->originalEmail)
                           ->where('otp_number', $this->otpCode)
                           ->where('status', 'pending')
                           ->where('expired_at', '>', Carbon::now())
                           ->first();

            if (!$otpRecord) {
                // Log failed OTP attempt
                $this->logProfileActivity('otp_verification_failed', [
                    'change_type' => $this->changeType,
                    'email' => $this->originalEmail,
                    'otp_code' => $this->otpCode,
                    'reason' => 'Invalid or expired OTP'
                ]);
                
                session()->flash('error', 'Invalid or expired OTP. Please try again.');
                return;
            }

            // Mark OTP as used
            $otpRecord->update(['status' => 'used']);

            // Log OTP verification activity
            $this->logProfileActivity('otp_verified', [
                'change_type' => $this->changeType,
                'email' => $this->originalEmail,
                'otp_id' => $otpRecord->id
            ]);

            // Store change type before resetting
            $changeType = $this->changeType;
            
            // Perform the pending changes
            $this->performPendingChanges();

            // Close modal and reset
            $this->showOtpModal = false;
            $this->otpCode = '';
            $this->pendingChanges = [];
            $this->changeType = '';
            $this->originalEmail = '';

            $successMessage = $changeType === 'password' ? 'Password changed successfully!' : 'Profile updated successfully!';
            session()->flash('success', $successMessage);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to verify OTP: ' . $e->getMessage());
        }
    }
    
    public function performPendingChanges()
    {
        try {
            if ($this->changeType === 'password') {
                // Update password - LoggerTrait will automatically log this change
                $this->user->update($this->pendingChanges);
                
                // Clear password fields and reset visibility
                $this->current_password = '';
                $this->new_password = '';
                $this->confirm_password = '';
                $this->show_current_password = false;
                $this->show_new_password = false;
                $this->show_confirm_password = false;
            } elseif ($this->changeType === 'email') {
                // Update email - LoggerTrait will automatically log this change
                $this->user->update($this->pendingChanges);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }
    
    public function performProfileUpdate()
    {
        try {
            if ($this->userType === 'admin') {
                $this->validate([
                    'name' => 'required|string|max:255',
                ]);

                // Update admin profile - LoggerTrait will automatically log this change
                // Note: Role and Status are not editable for security reasons
                $this->user->update([
                    'name' => $this->name,
                ]);
            } else {
                $this->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'gender' => 'required|string|in:Male,Female,Other',
                    'marital_status' => 'required|string|in:Single,Married,Widowed,Divorced',
                    'date_of_birth' => 'required|date',
                    'age' => 'required|integer|min:1|max:120',
                    'address' => 'required|string|max:500',
                ]);

                // Update student profile - LoggerTrait will automatically log this change
                $this->user->update([
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'last_name' => $this->last_name,
                    'suffix' => $this->suffix,
                    'gender' => $this->gender,
                    'marital_status' => $this->marital_status,
                    'date_of_birth' => $this->date_of_birth,
                    'age' => $this->age,
                    'address' => $this->address,
                ]);
            }

            session()->flash('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }
    
    public function cancelOtpVerification()
    {
        $this->showOtpModal = false;
        $this->otpCode = '';
        $this->pendingChanges = [];
        $this->changeType = '';
        $this->originalEmail = '';
        
        // Clear password fields if it was a password change
        if ($this->changeType === 'password') {
            $this->current_password = '';
            $this->new_password = '';
            $this->confirm_password = '';
        }
    }
    
    /**
     * Log profile-related activities manually
     */
    private function logProfileActivity($action, $properties = [])
    {
        try {
            $userId = null;
            $userType = 'unknown';
            
            // Get current user ID from either guard
            if (Auth::guard('web')->check()) {
                $userId = Auth::guard('web')->id();
                $userType = 'admin';
            } elseif (Auth::guard('students')->check()) {
                $userId = Auth::guard('students')->id();
                $userType = 'student';
            }
            
            ActionLog::create([
                'document_type' => get_class($this->user),
                'document_id' => $this->user->id,
                'user_id' => $userId,
                'created_by' => $userId,
                'action' => $action,
                'details' => ucfirst(str_replace('_', ' ', $action)) . ' for ' . ($userType === 'admin' ? 'Admin' : 'Student') . ' profile',
                'remarks' => json_encode(array_merge($properties, [
                    'user_type' => $userType,
                    'profile_management' => true,
                    'timestamp' => now()->toISOString()
                ])),
                'trackable_type' => get_class($this->user),
                'trackable_id' => $this->user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'location' => 'Profile Management',
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the main functionality
            \Log::error('Failed to log profile activity: ' . $e->getMessage());
        }
    }
    
    /**
     * Get recent activity logs for the current user
     */
    public function getRecentActivityLogs($limit = 10)
    {
        if (!$this->user) {
            return collect();
        }
        
        return ActionLog::where('trackable_type', get_class($this->user))
            ->where('trackable_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    

    public function render()
    {
        return view('livewire.profile-management.profile-management', [
            'courses' => $this->getCourses(),
            'departments' => $this->getDepartments(),
            'schoolYearSemesters' => $this->getSchoolYearSemesters(),
            'recentActivityLogs' => $this->getRecentActivityLogs(5), // Get last 5 activity logs
        ]);
    }
}
