<?php

namespace App\Livewire\ListStudentsAccount;

use Livewire\Component;
use App\Models\students;
use App\Models\course;
use App\Models\department;
use App\Models\school_year_and_semester;

class ListStudentsAccount extends Component
{
    public $search = '';
    public $students = [];
    public $courses = [];
    public $departments = [];
    public $activeSchoolYear;

    public function mount()
    {
        $this->loadStudents();
        $this->loadCourses();
        $this->loadDepartments();
        $this->loadActiveSchoolYear();
    }

    public function loadStudents()
    {
        $query = students::with(['course', 'department', 'school_year_and_semester']);

        // Apply search filter if provided
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('student_id', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhereHas('course', function($courseQuery) {
                      $courseQuery->where('course_name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('department', function($deptQuery) {
                      $deptQuery->where('department_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $this->students = $query->orderBy('last_name')->orderBy('first_name')->get();
    }

    public function loadCourses()
    {
        $this->courses = course::orderBy('course_name')->get();
    }

    public function loadDepartments()
    {
        $this->departments = department::orderBy('department_name')->get();
    }

    public function loadActiveSchoolYear()
    {
        $this->activeSchoolYear = school_year_and_semester::active()->first();
    }

    public function updatedSearch()
    {
        $this->loadStudents();
    }

    public function render()
    {
        return view('livewire.list-students-account.list-students-account');
    }
}
