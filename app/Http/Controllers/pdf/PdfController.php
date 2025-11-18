<?php

namespace App\Http\Controllers\pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\applied_candidacy;
use App\Models\school_year_and_semester;
use App\Models\voting_vote_count;
use App\Models\voting_exclusive;
use App\Models\students;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function candidatesList()
    {
        // Get the active school year
        $activeSchoolYear = school_year_and_semester::active()->first();
        
        if (!$activeSchoolYear) {
            abort(404, 'No active school year found');
        }

        // Get approved candidacies for the current school year
        $approvedCandidacies = applied_candidacy::where('status', 'approved')
            ->where('school_year_and_semester_id', $activeSchoolYear->id)
            ->with(['students', 'position', 'school_year_and_semester'])
            ->get();

        // Group candidates by position
        $candidatesByPosition = [];
        foreach ($approvedCandidacies as $candidacy) {
            $positionName = $candidacy->position ? $candidacy->position->position_name : 'Unknown Position';
            if (!isset($candidatesByPosition[$positionName])) {
                $candidatesByPosition[$positionName] = collect();
            }
            $candidatesByPosition[$positionName]->push($candidacy);
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.print-candidates-position', [
            'candidatesByPosition' => $candidatesByPosition,
            'activeSchoolYear' => $activeSchoolYear,
            'totalCandidates' => $approvedCandidacies->count()
        ]);

        return $pdf->stream('candidates-list-' . date('Y-m-d') . '.pdf');
    }

    public function candidatesElection(Request $request)
    {
        // Get filter parameters from request
        $search = $request->get('search', '');
        $schoolYearFilter = $request->get('school_year', '');
        $votingExclusiveFilter = $request->get('voting_exclusive', '');

        // Build query with filters
        $query = voting_vote_count::with(['student', 'appliedCandidacy.position', 'voting_exclusive']);

        // Apply voting exclusive filter if provided
        if (!empty($votingExclusiveFilter)) {
            $query->where('voting_exclusive_id', $votingExclusiveFilter);
        }

        // Apply school year filter if provided
        if (!empty($schoolYearFilter)) {
            $query->whereHas('voting_exclusive', function($q) use ($schoolYearFilter) {
                $q->where('school_year_id', $schoolYearFilter);
            });
        }

        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($studentQ) use ($search) {
                    $studentQ->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhere('student_id', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhereHas('appliedCandidacy.position', function($posQ) use ($search) {
                    $posQ->where('position_name', 'like', '%' . $search . '%');
                });
            });
        }

        // Get filtered vote counts
        $voteCounts = $query->get();

        // Get all voting exclusives for display
        $activeVotings = voting_exclusive::all();

        // Get any school year for display (or create a default one)
        $activeSchoolYear = school_year_and_semester::active()->first();
        if (!$activeSchoolYear) {
            // Create a default school year object if none exists
            $activeSchoolYear = (object) [
                'school_year' => 'Current',
                'semester' => 'Semester'
            ];
        }

        // Group candidates by position
        $candidatesByPosition = [];
        foreach ($voteCounts as $voteCount) {
            $position = $voteCount->appliedCandidacy?->position;
            if ($position) {
                $positionName = $position->position_name;
                if (!isset($candidatesByPosition[$positionName])) {
                    $candidatesByPosition[$positionName] = collect();
                }
                $candidatesByPosition[$positionName]->push($voteCount);
            }
        }

        // Sort candidates within each position by vote count (descending)
        foreach ($candidatesByPosition as $positionName => $candidates) {
            $candidatesByPosition[$positionName] = $candidates->sortByDesc('number_of_vote');
        }

        // Ensure candidatesByPosition is not null
        if (empty($candidatesByPosition)) {
            $candidatesByPosition = [];
        }

        // Generate PDF and stream it (preview in browser)
        $pdf = Pdf::loadView('pdf.print-candidates-election', [
            'candidatesByPosition' => $candidatesByPosition,
            'activeSchoolYear' => $activeSchoolYear,
            'activeVotings' => $activeVotings,
            'totalCandidates' => $voteCounts->count()
        ]);

        return $pdf->stream('election-results-' . date('Y-m-d') . '.pdf');
    }

    public function studentsAccount()
    {
        // Get the active school year
        $activeSchoolYear = school_year_and_semester::active()->first();
        
        if (!$activeSchoolYear) {
            // Create a default school year object if none exists
            $activeSchoolYear = (object) [
                'school_year' => 'Current',
                'semester' => 'Semester'
            ];
        }

        // Get all students with their relationships
        $students = students::with(['course', 'department', 'school_year_and_semester'])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Group students by course for better organization
        $studentsByCourse = [];
        foreach ($students as $student) {
            $courseName = $student->course ? $student->course->course_name : 'No Course';
            if (!isset($studentsByCourse[$courseName])) {
                $studentsByCourse[$courseName] = collect();
            }
            $studentsByCourse[$courseName]->push($student);
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.print-students-account', [
            'studentsByCourse' => $studentsByCourse,
            'activeSchoolYear' => $activeSchoolYear,
            'totalStudents' => $students->count()
        ]);

        return $pdf->stream('students-account-' . date('Y-m-d') . '.pdf');
    }

    public function adminAccount()
    {
        // Get all admins
        $admins = User::orderBy('name')->get();

        // Group admins by role for better organization
        $adminsByRole = [];
        foreach ($admins as $admin) {
            $roleName = $admin->role ? ucfirst($admin->role) : 'No Role';
            if (!isset($adminsByRole[$roleName])) {
                $adminsByRole[$roleName] = collect();
            }
            $adminsByRole[$roleName]->push($admin);
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.print-admin-account', [
            'adminsByRole' => $adminsByRole,
            'totalAdmins' => $admins->count()
        ]);

        return $pdf->stream('admin-account-' . date('Y-m-d') . '.pdf');
    }

    // Show a simple index page for selecting a voting exclusive to generate student voters report
    public function studentVotersIndex()
    {
        $votings = voting_exclusive::orderBy('start_datetime', 'desc')->get();
        return view('student-voter.index', ['votings' => $votings]);
    }

    // Generate PDF listing students who voted in a specific voting exclusive
    public function studentVoters($id)
    {
        $voting = voting_exclusive::find($id);
        if (!$voting) {
            abort(404, 'Voting exclusive not found');
        }

        // Get voters for this exclusive (distinct student votes)
        $voters = \App\Models\voting_voted_by::whereHas('voting_vote_count', function ($q) use ($id) {
            $q->where('voting_exclusive_id', $id);
        })->with(['student.course', 'student.department'])->get();

        $pdf = Pdf::loadView('pdf.print-student-voters', [
            'votingExclusive' => $voting,
            'voters' => $voters
        ]);

        return $pdf->stream('student-voters-' . $id . '-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Return JSON list of voters for a voting exclusive.
     * Supports optional ?q=search to filter by student name or student id.
     */
    public function studentVotersJson(Request $request, $id)
    {
        $voting = voting_exclusive::find($id);
        if (!$voting) {
            return response()->json(['error' => 'Voting exclusive not found'], 404);
        }

        $q = $request->get('q');

        $votersQuery = \App\Models\voting_voted_by::whereHas('voting_vote_count', function ($q2) use ($id) {
            $q2->where('voting_exclusive_id', $id);
        })->with(['student.course', 'student.department', 'voting_vote_count']);

        if ($q) {
            $votersQuery->whereHas('student', function ($sq) use ($q) {
                $sq->where('student_id', 'like', "%{$q}%")
                   ->orWhere('first_name', 'like', "%{$q}%")
                   ->orWhere('last_name', 'like', "%{$q}%")
                   ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$q}%"]);
            });
        }

        $voters = $votersQuery->get();

        // Map to the requested structure
        $data = $voters->map(function ($v) {
            return [
                'id' => $v->id,
                'voting_vote_count_id' => $v->voting_vote_count_id,
                'students_id' => $v->students_id,
                'status' => $v->status,
                'created_at' => optional($v->created_at)->toDateTimeString(),
                'updated_at' => optional($v->updated_at)->toDateTimeString(),
                'deleted_at' => optional($v->deleted_at)->toDateTimeString(),
                'student' => [
                    'student_id' => optional($v->student)->student_id,
                    'first_name' => optional($v->student)->first_name,
                    'last_name' => optional($v->student)->last_name,
                    'course' => optional($v->student->course)->course_name,
                    'department' => optional($v->student->department)->department_name,
                ],
            ];
        });

        return response()->json(['voting' => $voting, 'voters' => $data]);
    }
}
