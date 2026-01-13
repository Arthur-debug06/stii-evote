<?php

namespace App\Http\Controllers\otp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\students;
use App\Models\otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpController extends Controller
{
    public function index()
    {
        // Check if email exists in session
        if (!Session::has('otp_email')) {
            return redirect()->route('forgot-password')->withErrors(['error' => 'Please request OTP first.']);
        }

        $email = Session::get('otp_email');
        
        // Check if there's a valid OTP for this email
        $otpRecord = otp::where('email', $email)
                        ->where('status', 'pending')
                        ->where('expired_at', '>', Carbon::now())
                        ->first();

        if (!$otpRecord) {
            Session::forget('otp_email');
            return redirect()->route('forgot-password')->withErrors(['error' => 'OTP has expired or not found. Please request a new one.']);
        }

        return view('otp.otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'new_password' => 'required|min:8|confirmed'
        ]);

        if (!Session::has('otp_email')) {
            return redirect()->route('forgot-password')->withErrors(['error' => 'Session expired. Please request OTP again.']);
        }

        $email = Session::get('otp_email');
        
        // Find the OTP record
        $otpRecord = otp::where('email', $email)
                        ->where('status', 'pending')
                        ->where('expired_at', '>', Carbon::now())
                        ->first();

        if (!$otpRecord) {
            Session::forget('otp_email');
            return redirect()->route('forgot-password')->withErrors(['error' => 'OTP has expired or not found. Please request a new one.']);
        }

        // Verify OTP
        if ($request->otp != $otpRecord->otp_number) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // Check which model the email belongs to and update password accordingly
        $user = null;
        $student = null;
        
        if ($otpRecord->email_from_id === 'User') {
            $user = User::where('email', $email)->first();
            if (!$user) {
                return back()->withErrors(['error' => 'User not found.']);
            }
        } elseif ($otpRecord->email_from_id === 'students') {
            $student = students::where('email', $email)->first();
            if (!$student) {
                return back()->withErrors(['error' => 'Student not found.']);
            }
        } else {
            return back()->withErrors(['error' => 'Invalid user type.']);
        }

        // Update password based on model type
        try {
            if ($user) {
                $user->password = Hash::make($request->new_password);
                $user->save();
            } elseif ($student) {
                $student->password = Hash::make($request->new_password);
                $student->save();
            }

            // Mark OTP as used
            $otpRecord->status = 'used';
            $otpRecord->save();

            // Clear email from session
            Session::forget('otp_email');

            return redirect()->route('login')->with('success', 'Password reset successfully. You can now login with your new password.');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to reset password. Please try again. Error: ' . $e->getMessage()]);
        }
    }

    public function resendOtp()
    {
        if (!Session::has('otp_email')) {
            return redirect()->route('forgot-password')->withErrors(['error' => 'Please request OTP first.']);
        }

        $email = Session::get('otp_email');
        
        // Get the OTP record to determine which model the email belongs to
        $otpRecord = otp::where('email', $email)->first();
        if (!$otpRecord) {
            Session::forget('otp_email');
            return redirect()->route('forgot-password')->withErrors(['error' => 'No OTP record found. Please request OTP again.']);
        }

        // Check if user/student still exists based on email_from_id
        $user = null;
        $student = null;
        $userData = null;
        
        if ($otpRecord->email_from_id === 'User') {
            $user = User::where('email', $email)->first();
            $userData = $user;
            if (!$user) {
                Session::forget('otp_email');
                return redirect()->route('forgot-password')->withErrors(['error' => 'User not found.']);
            }
        } elseif ($otpRecord->email_from_id === 'students') {
            $student = students::where('email', $email)->first();
            $userData = $student;
            if (!$student) {
                Session::forget('otp_email');
                return redirect()->route('forgot-password')->withErrors(['error' => 'Student not found.']);
            }
        }
        
        // Generate new OTP
        $newOtp = rand(100000, 999999);
        
        // Delete any existing OTP for this email
        otp::where('email', $email)->delete();
        
        // Create new OTP record
        try {
            otp::create([
                'email_from_id' => $otpRecord->email_from_id,
                'email' => $email,
                'otp_number' => $newOtp,
                'status' => 'pending',
                'expired_at' => Carbon::now()->addMinutes(10)
            ]);

            // Send new OTP via email
            Mail::send('emails.otp', ['otp' => $newOtp, 'user' => $userData], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Password Reset OTP - Voting System');
            });

            return back()->with('success', 'New OTP has been sent to your email.');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to send OTP. Please try again. Error: ' . $e->getMessage()]);
        }
    }
}
