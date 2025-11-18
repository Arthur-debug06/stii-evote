<?php

namespace App\Livewire\RegistrationRequest;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\students;
use App\Models\course;
use App\Models\department;
use App\Models\school_year_and_semester;
use App\Models\registration_request;
use Illuminate\Support\Facades\Storage;

class RegistrationRequest extends Component
{
    use WithPagination;

    // Search functionality
    public $search = '';

    // Modal states
    public $showViewModal = false;
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $showPhotoSlider = false;
    public $showRejectRemarksModal = false;

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
    public $student_id_image_back_base64;
    public $status;
    public $created_at;
    public $reject_remarks = '';

    protected $rules = [
        'status' => 'required|in:active,rejected',
        'reject_remarks' => 'required_if:status,rejected|string|min:10|max:500',
    ];

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
            $this->student_id_image_back = $student->student_id_image_back;
            $this->status = $student->status;
            $this->created_at = $student->created_at;
            
            // Convert images to base64
            $this->profile_image_base64 = $this->getImageBase64($student->profile_image);
            $this->student_id_image_base64 = $this->getImageBase64($student->student_id_image);
            $this->student_id_image_back_base64 = $this->getImageBase64($student->student_id_image_back);
            $this->showViewModal = true;
        }
    }

    private function getImageBase64($imagePath)
    {
        if (!$imagePath) {
            \Log::info('No image path provided');
            return null;
        }

        try {
            // The imagePath already includes 'student_images/' prefix
            // Try different possible storage locations
            $possiblePaths = [
                $imagePath, // Direct path as stored in database
                'public/' . $imagePath, // With public prefix
                'private/public/' . $imagePath, // Private storage path
            ];

            \Log::info('Trying paths for: ' . $imagePath);
            foreach ($possiblePaths as $path) {
                \Log::info('Checking path: ' . $path);
            }

            // Try public disk first
            foreach ($possiblePaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    \Log::info('Found on public disk: ' . $path);
                    $imageData = Storage::disk('public')->get($path);
                    $mimeType = Storage::disk('public')->mimeType($path);
                    return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                }
            }

            // Try local disk (private storage)
            foreach ($possiblePaths as $path) {
                if (Storage::disk('local')->exists($path)) {
                    \Log::info('Found on local disk: ' . $path);
                    $imageData = Storage::disk('local')->get($path);
                    $mimeType = Storage::disk('local')->mimeType($path);
                    return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                }
            }

            \Log::info('Image not found in any location: ' . $imagePath);
            return null;
        } catch (\Exception $e) {
            \Log::error('Error getting image: ' . $e->getMessage());
            return null;
        }
    }

    public function approveStudent($studentId)
    {
        try {
            $student = students::find($studentId);
            if (!$student) {
                $this->dispatch('show-toast', [
                    'message' => 'Student not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }
            
            $this->selectedStudent = $student;
            $this->showApproveModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error loading student: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function rejectStudent($studentId)
    {
        try {
            $student = students::find($studentId);
            if (!$student) {
                $this->dispatch('show-toast', [
                    'message' => 'Student not found.',
                    'type' => 'error',
                    'title' => 'Not Found'
                ]);
                return;
            }
            
            $this->selectedStudent = $student;
            $this->reject_remarks = '';
            $this->showRejectRemarksModal = true;
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error loading student: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function approveConfirmed()
    {
        if (!$this->selectedStudent) {
            $this->dispatch('show-toast', [
                'message' => 'No student selected to approve.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        try {
            // Update student status to active
            $this->selectedStudent->update(['status' => 'active']);
            
            // Create or update registration request record
            $registrationRequest = registration_request::updateOrCreate(
                ['students_id' => $this->selectedStudent->id],
                [
                    'status' => 'approved',
                    'remarks' => 'Student registration has been approved by admin.',
                    'updated_at' => now()
                ]
            );
            
            $this->showApproveModal = false;
            $this->selectedStudent = null;
            
            $this->dispatch('show-toast', [
                'message' => 'Student registration approved successfully.',
                'type' => 'success',
                'title' => 'Registration Approved!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error approving registration: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function rejectConfirmed()
    {
        if (!$this->selectedStudent) {
            $this->dispatch('show-toast', [
                'message' => 'No student selected to reject.',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        $this->validate([
            'reject_remarks' => 'required|string|min:10|max:500',
        ]);

        try {
            // Update student status to rejected
            $this->selectedStudent->update(['status' => 'rejected']);
            
            // Create or update registration request record
            $registrationRequest = registration_request::updateOrCreate(
                ['students_id' => $this->selectedStudent->id],
                [
                    'status' => 'rejected',
                    'remarks' => $this->reject_remarks,
                    'updated_at' => now()
                ]
            );
            
            $this->showRejectRemarksModal = false;
            $this->selectedStudent = null;
            $this->reject_remarks = '';
            
            $this->dispatch('show-toast', [
                'message' => 'Student registration rejected.',
                'type' => 'error',
                'title' => 'Registration Rejected'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => 'Error rejecting registration: ' . $e->getMessage(),
                'type' => 'error',
                'title' => 'Error'
            ]);
        }
    }

    public function cancelAction()
    {
        $this->showViewModal = false;
        $this->showApproveModal = false;
        $this->showRejectModal = false;
        $this->showRejectRemarksModal = false;
        $this->selectedStudent = null;
        $this->reject_remarks = '';
    }

    public function openPhotoSlider($studentId)
    {
        $student = students::find($studentId);
        if ($student) {
            // Debug: Log the image paths
            \Log::info('Profile Image Path: ' . $student->profile_image);
            \Log::info('Student ID Image Path: ' . $student->student_id_image);
            
            // Convert images to base64 for the slider
            $this->profile_image_base64 = $this->getImageBase64($student->profile_image);
            $this->student_id_image_base64 = $this->getImageBase64($student->student_id_image);
            
            // Debug: Log the base64 results
            \Log::info('Profile Image Base64: ' . ($this->profile_image_base64 ? 'Found' : 'Not Found'));
            \Log::info('Student ID Image Base64: ' . ($this->student_id_image_base64 ? 'Found' : 'Not Found'));
            
            $this->showPhotoSlider = true;
        }
    }

    public function closePhotoSlider()
    {
        $this->showPhotoSlider = false;
        $this->profile_image_base64 = null;
        $this->student_id_image_base64 = null;
    }

    public function render()
    {
        $students = students::with(['course', 'department', 'school_year_and_semester'])
            ->where('status', 'pending')
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

        return view('livewire.registration-request.registration-request', compact('students'));
    }
}
