<?php

namespace App\Http\Controllers\forgotpassword;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\students;
use App\Models\otp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('forgot-password.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Check if email exists in either User or students model
        $user = User::where('email', $request->email)->first();
        $student = students::where('email', $request->email)->first();
        
        if (!$user && !$student) {
            return back()->withErrors(['email' => 'Email not found in our records. Please check your email address.']);
        }
        
        // Determine which model the email belongs to
        $emailFromId = null;
        $userData = null;
        
        if ($user) {
            $emailFromId = 'User';
            $userData = $user;
        } elseif ($student) {
            $emailFromId = 'students';
            $userData = $student;
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        
        // Delete any existing OTP for this email
        otp::where('email', $request->email)->delete();
        
        // Store OTP in database
        try {
            otp::create([
                'email_from_id' => $emailFromId,
                'email' => $request->email,
                'otp_number' => $otp,
                'status' => 'pending',
                'expired_at' => Carbon::now()->addMinutes(10) // OTP expires in 10 minutes
            ]);

            // Send OTP via email
            Mail::send('emails.otp', ['otp' => $otp, 'user' => $userData], function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Password Reset OTP - Voting System');
            });

            // Store email in session for OTP verification page
            Session::put('otp_email', $request->email);

            return redirect()->route('otp')->with('success', 'OTP has been sent to your email address.');
            
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again. Error: ' . $e->getMessage()]);
        }
    }
}
