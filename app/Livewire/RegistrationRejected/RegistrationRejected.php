<?php

namespace App\Livewire\RegistrationRejected;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\students;
use App\Models\course;
use App\Models\department;
use App\Models\school_year_and_semester;
use App\Models\registration_request;
use Illuminate\Support\Facades\Storage;

class RegistrationRejected extends Component
{
    use WithPagination;

    // Search functionality
    public $search = '';

    // Modal states
    public $showViewModal = false;
    public $showPhotoSlider = false;

    // Selected student for actions
    public $selectedStudent = null;

    // Form data for viewing student details
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
    public $course_id;
    public $department_id;
    public $school_year_and_semester_id;
    public $profile_image;
    public $student_id_image;
    public $profile_image_base64;
    public $student_id_image_base64;
    public $status;
    public $created_at;
    public $rejection_reason = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewStudent($studentId)
    {
        $student = students::with(['course', 'department', 'school_year_and_semester'])->find($studentId);
        if ($student) {
            $this->selectedStudent = $student;
            $this->student_id = $student->student_id;
            $this->first_name = $student->first_name;
            $this->middle_name = $student->middle_name;
            $this->last_name = $student->last_name;
            $this->suffix = $student->suffix;
            $this->gender = $student->gender;
            $this->date_of_birth = $student->date_of_birth;
            $this->age = $student->age;
            $this->address = $student->address;
            $this->email = $student->email;
            $this->course_id = $student->course_id;
            $this->department_id = $student->department_id;
            $this->school_year_and_semester_id = $student->school_year_and_semester_id;
            $this->profile_image = $student->profile_image;
            $this->student_id_image = $student->student_id_image;
            $this->status = $student->status;
            $this->created_at = $student->created_at;
            
            // Get rejection reason from registration_request table
            $registrationRequest = registration_request::where('students_id', $student->id)->first();
            $this->rejection_reason = $registrationRequest ? $registrationRequest->remarks : 'No reason provided';
            
            // Convert images to base64
            $this->profile_image_base64 = $this->getImageBase64($student->profile_image);
            $this->student_id_image_base64 = $this->getImageBase64($student->student_id_image);
            
            $this->showViewModal = true;
        }
    }

    private function getImageBase64($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        try {
            $possiblePaths = [
                $imagePath,
                'public/' . $imagePath,
            ];

            // Try public disk first
            foreach ($possiblePaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    $imageData = Storage::disk('public')->get($path);
                    $mimeType = Storage::disk('public')->mimeType($path);
                    return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                }
            }

            // Try local disk (private storage)
            foreach ($possiblePaths as $path) {
                if (Storage::disk('local')->exists($path)) {
                    $imageData = Storage::disk('local')->get($path);
                    $mimeType = Storage::disk('local')->mimeType($path);
                    return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function openPhotoSlider($studentId)
    {
        $student = students::find($studentId);
        if ($student) {
            // Convert images to base64 for the slider
            $this->profile_image_base64 = $this->getImageBase64($student->profile_image);
            $this->student_id_image_base64 = $this->getImageBase64($student->student_id_image);
            
            $this->showPhotoSlider = true;
        }
    }

    public function closePhotoSlider()
    {
        $this->showPhotoSlider = false;
        $this->profile_image_base64 = null;
        $this->student_id_image_base64 = null;
    }

    public function cancelAction()
    {
        $this->showViewModal = false;
        $this->showPhotoSlider = false;
        $this->selectedStudent = null;
    }

    public function render()
    {
        $students = students::with(['course', 'department', 'school_year_and_semester'])
            ->where('status', 'rejected')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('student_id', 'like', '%' . $this->search . '%')
                      ->orWhere('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.registration-rejected.registration-rejected', compact('students'));
    }
}
