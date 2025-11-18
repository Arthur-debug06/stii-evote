<?php

namespace App\Http\Controllers\register;

use App\Http\Controllers\Controller;
use App\Models\Students;
use App\Models\Course;
use App\Models\Department;
use App\Models\School_Year_And_Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function index()
    {
        // Get active school year and semester
        $activeSchoolYear = School_Year_And_Semester::where('status', 'active')->first();

        // Get all courses and departments for dropdowns
        $courses = Course::where('status', 'active')->orderBy('course_name')->get();
        $departments = Department::where('status', 'active')->orderBy('department_name')->get();

        return view('register.register', compact('courses', 'departments', 'activeSchoolYear'));
    }

    public function store(Request $request)
    {
        // ✅ Validation rules
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string|max:255|unique:students,student_id',
            'course_id' => 'required|exists:course,id',
            'department_id' => 'required|exists:department,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get current active school year
            $activeSchoolYear = School_Year_And_Semester::where('status', 'active')->first();

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
            Students::create($data);

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
