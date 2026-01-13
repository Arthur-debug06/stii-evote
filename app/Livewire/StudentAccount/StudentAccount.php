<?php

namespace App\Livewire\StudentAccount;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\students;
use App\Models\course;
use App\Models\department;
use App\Models\school_year_and_semester;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentAccount extends Component
{
    use WithPagination, WithFileUploads;

    // Properties for student management
    public $search = '';
    public $perPage = 10;
    
    // Modal states
    public $showAddModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    // Student properties
    public $student_id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $gender;
    public $date_of_birth;
    public $age;
    public $address;
    public $email;
    public $password;
    public $password_confirmation;
    public $status = 'active';
    public $profile_image;
    public $student_id_image;
    public $course_id;
    public $department_id;
    public $school_year_and_semester_id;
    
    // For editing
    public $editStudentId;
    public $deleteStudentId;
    public $temp_profile_image;
    public $temp_student_id_image;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->perPage = 10;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDateOfBirth()
    {
        if ($this->date_of_birth) {
            $this->age = Carbon::parse($this->date_of_birth)->age;
        }
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->status = 'active'; // Automatically set status to active
        $this->showAddModal = true;
    }

    public function openEditModal($id)
    {
        $student = students::findOrFail($id);

        $this->editStudentId = $id;
        $this->student_id = $student->student_id;
        $this->first_name = $student->first_name;
        $this->middle_name = $student->middle_name;
        $this->last_name = $student->last_name;
        $this->suffix = $student->suffix;
        $this->gender = $student->gender;
        $this->date_of_birth = $student->date_of_birth;
        // Calculate current age based on date of birth
        $this->age = $student->date_of_birth ? Carbon::parse($student->date_of_birth)->age : $student->age;
        $this->address = $student->address;
        $this->email = $student->email;
        $this->status = $student->status;
        $this->course_id = $student->course_id;
        $this->department_id = $student->department_id;
        $this->school_year_and_semester_id = $student->school_year_and_semester_id;

        $this->temp_profile_image = $student->profile_image;
        $this->temp_student_id_image = $student->student_id_image;

        $this->showEditModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deleteStudentId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteStudentId = null;
    }

    public function createStudent()
    {
               $this->validate([
                   'student_id' => 'required|string|max:255|unique:students,student_id',
                   'first_name' => 'required|string|max:255',
                   'middle_name' => 'nullable|string|max:255',
                   'last_name' => 'required|string|max:255',
                   'suffix' => 'nullable|string|max:10',
                   'gender' => 'required|in:male,female',
                   'date_of_birth' => 'required|date|before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
                   'age' => 'nullable|integer|min:18|max:150',
                   'address' => 'required|string|max:500',
                   'email' => 'required|email|unique:students,email',
                   'password' => 'required|string|min:8|confirmed',
                   'status' => 'nullable|in:active,inactive',
                   'course_id' => 'required|exists:course,id',
                   'department_id' => 'required|exists:department,id',
                   'school_year_and_semester_id' => 'required|exists:school_year_and_semester,id',
                   'profile_image' => 'nullable|image|max:2048',
                   'student_id_image' => 'nullable|image|max:2048',
               ]);

        $profileImagePath = null;
        $studentIdImagePath = null;

        if ($this->profile_image) {
            $profileImagePath = $this->profile_image->store('profile_pictures', 'public');
        }

        if ($this->student_id_image) {
            $studentIdImagePath = $this->student_id_image->store('student_id_images', 'public');
        }

        // Calculate age if not set
        $calculatedAge = $this->age;
        if (!$calculatedAge && $this->date_of_birth) {
            $calculatedAge = Carbon::parse($this->date_of_birth)->age;
        }

               $student = students::create([
                   'student_id' => $this->student_id,
                   'first_name' => $this->first_name,
                   'middle_name' => $this->middle_name,
                   'last_name' => $this->last_name,
                   'suffix' => $this->suffix,
                   'gender' => $this->gender,
                   'date_of_birth' => $this->date_of_birth,
                   'age' => $calculatedAge,
                   'address' => $this->address,
                   'email' => $this->email,
                   'password' => Hash::make($this->password),
                   'status' => 'active', // Always set to active when creating
                   'course_id' => $this->course_id,
                   'department_id' => $this->department_id,
                   'school_year_and_semester_id' => $this->school_year_and_semester_id,
                   'profile_image' => $profileImagePath,
                   'student_id_image' => $studentIdImagePath,
               ]);

               $student->logActivity('student_account_created', [
                   'message' => 'Student account created: ' . $this->first_name . ' ' . $this->last_name . ' (' . $this->email . ')',
                   'student_id' => $this->student_id,
                   'course_id' => $this->course_id,
                   'department_id' => $this->department_id,
                   'status' => 'active',
                   'profile_image_uploaded' => $profileImagePath ? true : false,
                   'student_id_image_uploaded' => $studentIdImagePath ? true : false,
               ]);

        $this->resetForm();
        $this->showAddModal = false;
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Student account created successfully.',
        ]);
    }

    public function updateStudent()
    {
               $this->validate([
                   'student_id' => 'required|string|max:255|unique:students,student_id,' . $this->editStudentId,
                   'first_name' => 'required|string|max:255',
                   'middle_name' => 'nullable|string|max:255',
                   'last_name' => 'required|string|max:255',
                   'suffix' => 'nullable|string|max:10',
                   'gender' => 'required|in:male,female',
                   'date_of_birth' => 'required|date|before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
                   'age' => 'nullable|integer|min:18|max:150',
                   'address' => 'required|string|max:500',
                   'email' => 'required|email|unique:students,email,' . $this->editStudentId,
                   'password' => 'nullable|string|min:8|confirmed',
                   'status' => 'required|in:active,inactive',
                   'course_id' => 'required|exists:course,id',
                   'department_id' => 'required|exists:department,id',
                   'school_year_and_semester_id' => 'required|exists:school_year_and_semester,id',
                   'profile_image' => 'nullable|image|max:2048',
                   'student_id_image' => 'nullable|image|max:2048',
               ]);

        $student = students::findOrFail($this->editStudentId);
        
        // Calculate age if not set
        $calculatedAge = $this->age;
        if (!$calculatedAge && $this->date_of_birth) {
            $calculatedAge = Carbon::parse($this->date_of_birth)->age;
        }
        
        $updateData = [
            'student_id' => $this->student_id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'age' => $calculatedAge,
            'address' => $this->address,
            'email' => $this->email,
            'status' => $this->status,
            'course_id' => $this->course_id,
            'department_id' => $this->department_id,
            'school_year_and_semester_id' => $this->school_year_and_semester_id,
        ];

        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }

        if ($this->profile_image) {
            if ($student->profile_image) {
                Storage::disk('public')->delete($student->profile_image);
            }
            $updateData['profile_image'] = $this->profile_image->store('profile_pictures', 'public');
        }

        if ($this->student_id_image) {
            if ($student->student_id_image) {
                Storage::disk('public')->delete($student->student_id_image);
            }
            $updateData['student_id_image'] = $this->student_id_image->store('student_id_images', 'public');
        }

        $student->update($updateData);
        
        $student->logActivity('student_account_updated', [
            'message' => 'Student account updated: ' . $this->first_name . ' ' . $this->last_name . ' (' . $this->email . ')',
            'student_id' => $this->student_id,
            'course_id' => $this->course_id,
            'department_id' => $this->department_id,
            'status' => $this->status,
            'password_changed' => !empty($this->password),
            'profile_image_updated' => $this->profile_image ? true : false,
            'student_id_image_updated' => $this->student_id_image ? true : false,
        ]);

        $this->resetForm();
        $this->showEditModal = false;
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Student account updated successfully.',
        ]);
    }

    public function deleteConfirmed()
    {
        $student = students::findOrFail($this->deleteStudentId);
        
        $student->logActivity('student_account_deleted', [
            'message' => 'Student account deleted: ' . $student->first_name . ' ' . $student->last_name . ' (' . $student->email . ')',
            'student_id' => $student->student_id,
            'course_id' => $student->course_id,
            'department_id' => $student->department_id,
            'status' => $student->status,
        ]);

        if ($student->profile_image) {
            Storage::disk('public')->delete($student->profile_image);
        }
        
        if ($student->student_id_image) {
            Storage::disk('public')->delete($student->student_id_image);
        }

        $student->delete();
        
        $this->showDeleteModal = false;
        $this->deleteStudentId = null;
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Student account deleted successfully.',
        ]);
    }

    private function resetForm()
    {
        $this->student_id = '';
        $this->first_name = '';
        $this->middle_name = '';
        $this->last_name = '';
        $this->suffix = '';
        $this->gender = '';
        $this->date_of_birth = '';
        $this->age = '';
        $this->address = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->status = 'active'; // Always default to active
        $this->profile_image = null;
        $this->student_id_image = null;
        $this->course_id = '';
        $this->department_id = '';
        $this->school_year_and_semester_id = '';
        $this->editStudentId = null;
        $this->temp_profile_image = null;
        $this->temp_student_id_image = null;
    }

    public function render()
    {
        $query = students::with(['course', 'department', 'school_year_and_semester']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('student_id', 'like', '%' . $this->search . '%');
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate($this->perPage);
        
               $courses = course::active()->get();
               $departments = department::active()->get();
               $schoolYearSemesters = school_year_and_semester::active()->get();

        return view('livewire.student-account.student-account', [
            'students' => $students,
            'courses' => $courses,
            'departments' => $departments,
            'schoolYearSemesters' => $schoolYearSemesters,
        ]);
    }
}
