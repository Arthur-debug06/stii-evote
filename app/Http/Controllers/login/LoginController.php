<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\students;

class LoginController extends Controller
{
    public function login()
    {
        return view('login.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Check if email exists in User model
        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            // Login as User using web guard
            Auth::guard('web')->login($user);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard?login=success');
        }

        // Check if email exists in students model
        $student = students::where('email', $email)->first();
        if ($student && Hash::check($password, $student->password)) {
            // Check if student status is active (not pending)
            if ($student->status === 'pending') {
                return back()->withErrors([
                    'email' => 'Your account is still pending approval. Please wait for admin approval.',
                ]);
            }
            
            // Login as Student using students guard
            Auth::guard('students')->login($student);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard?login=success');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        // Logout from both guards
        Auth::guard('web')->logout();
        Auth::guard('students')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
