<?php

namespace App\Http\Controllers\register;

use App\Http\Controllers\Controller;
use App\Models\students;
use App\Models\course;
use App\Models\department;
use App\Models\school_year_and_semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class RegisterController extends Controller
{
    public function index()
    {
        try {
            // Get active school year and semester
            $activeSchoolYear = school_year_and_semester::where('status', 'active')->first();

            // Get all courses and departments for dropdowns
            $courses = course::where('status', 'active')->orderBy('course_name')->get();
            $departments = department::where('status', 'active')->orderBy('department_name')->get();

            // Default values for branding
            $topLogoPath = asset('assets/dist/images/logo.svg');
            $topText = 'stii-evote Student Portal';
            $centerLogoPath = asset('assets/dist/images/illustration.svg');
            $centerText = 'stii-evote<br>Sign in to your account.';

            // Try to get system settings only if table exists
            try {
                if (\Schema::hasTable('system_settings')) {
                    $loginTopLogo = \App\Models\system_settings::where('key', 'login_top_logo')
                        ->where('type', 'image')
                        ->where('module_id', 6)
                        ->where('status', 'active')
                        ->first();
                    
                    if ($loginTopLogo && $loginTopLogo->value) {
                        try {
                            $topLogoPath = Storage::url($loginTopLogo->value);
                        } catch (\Exception $e) {
                            // Keep default
                        }
                    }

                    $loginTopText = \App\Models\system_settings::where('key', 'login_top_text')
                        ->where('type', 'text')
                        ->where('module_id', 4)
                        ->where('status', 'active')
                        ->first();
                    if ($loginTopText && $loginTopText->value) {
                        $topText = $loginTopText->value;
                    }

                    $loginCenterLogo = \App\Models\system_settings::where('key', 'login_center_logo')
                        ->where('type', 'image')
                        ->where('module_id', 3)
                        ->where('status', 'active')
                        ->first();
                    
                    if ($loginCenterLogo && $loginCenterLogo->value) {
                        try {
                            $centerLogoPath = Storage::url($loginCenterLogo->value);
                        } catch (\Exception $e) {
                            // Keep default
                        }
                    }

                    $loginCenterText = \App\Models\system_settings::where('key', 'login_center_text')
                        ->where('type', 'text')
                        ->where('module_id', 5)
                        ->where('status', 'active')
                        ->first();
                    if ($loginCenterText && $loginCenterText->value) {
                        $centerText = $loginCenterText->value;
                    }
                }
            } catch (\Exception $e) {
                // Silently fail and use defaults if system_settings queries fail
                \Log::warning('System settings query failed: ' . $e->getMessage());
            }

            return view('register.register', compact(
                'courses', 
                'departments', 
                'activeSchoolYear',
                'topLogoPath',
                'topText',
                'centerLogoPath',
                'centerText'
            ));
        } catch (\Exception $e) {
            // Log the error with full trace
            \Log::error('Register page error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            
            // Return view with safe defaults
            return view('register.register', [
                'courses' => collect([]),
                'departments' => collect([]),
                'activeSchoolYear' => null,
                'topLogoPath' => asset('assets/dist/images/logo.svg'),
                'topText' => 'stii-evote Student Portal',
                'centerLogoPath' => asset('assets/dist/images/illustration.svg'),
                'centerText' => 'stii-evote<br>Sign in to your account.'
            ]);
        }
    }

    public function store(Request $request)
    {
        // ✅ Validation rules
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string|max:255|unique:students,student_id',
            'course_id' => 'required|exists:course,id',
            'department_id' => 'required|exists:department,id',
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'suffix' => ['nullable', 'string', 'max:10', 'regex:/^[a-zA-Z.\s]+$/'],
            'gender' => 'required|in:Male,Female,Other',
            'marital_status' => 'required|in:Single,Married,Widowed,Divorced',
            'date_of_birth' => 'required|date|before:today',
            'age' => 'required|integer|min:18|max:150',
            'address' => 'required|string|max:500',
            'email' => 'required|email|max:255|unique:students,email',

            // ✅ Strong password: min 8 chars, at least 1 uppercase, lowercase, number
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],

            // ✅ Image uploads
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_id_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_id_image_back' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'age.min' => 'You must be at least 18 years old to register.',
            'age.max' => 'Age cannot exceed 150 years.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'first_name.regex' => 'First name must contain only letters and spaces.',
            'middle_name.regex' => 'Middle name must contain only letters and spaces.',
            'last_name.regex' => 'Last name must contain only letters and spaces.',
            'suffix.regex' => 'Suffix must contain only letters, periods, and spaces.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get current active school year
            $activeSchoolYear = school_year_and_semester::where('status', 'active')->first();

            if (!$activeSchoolYear) {
                return back()->withErrors(['error' => 'No active school year found. Please contact the administrator.'])->withInput();
            }

            // ✅ Prepare data safely
            $data = [
                'student_id' => trim($request->student_id),
                'course_id' => $request->course_id,
                'department_id' => $request->department_id,
                'first_name' => ucwords(strtolower($request->first_name)),
                'middle_name' => ucwords(strtolower($request->middle_name ?? '')),
                'last_name' => ucwords(strtolower($request->last_name)),
                'suffix' => $request->suffix,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'date_of_birth' => $request->date_of_birth,
                'age' => $request->age,
                'address' => $request->address,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'school_year_and_semester_id' => $activeSchoolYear->id,
                'status' => 'pending',
            ];

            // ✅ Handle uploads with unique filenames
            foreach (['profile_image', 'student_id_image', 'student_id_image_back'] as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $data[$field] = $file->storeAs('student_images', $filename, 'public');
                }
            }

            // ✅ Create student record
            students::create($data);

            return redirect()->route('register')
                ->with('success', 'Registration successful! Your account is pending approval. You will be notified once approved.');

        } catch (\Throwable $e) {
            // Optional: Log the error
            \Log::error('Registration error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'Registration failed. Please try again later.'])
                ->withInput();
        }
    }
}
