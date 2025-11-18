<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\voting_vote_count;
use App\Models\voting_voted_by;
use App\Models\students;
use App\Models\User;
use App\Models\course;
use App\Models\department;
use App\Models\meeting_de_abanse;
use App\Models\position;
use App\Models\voting_exclusive;
use App\Models\applied_candidacy;
use App\Models\partylist;
use App\Services\VotingStatusService;

class Dashboard extends Component
{
    use WithPagination;
    
    // Modal states for different card types
    public $showModal = false;
    public $modalTitle = '';
    public $modalDescription = '';
    public $modalData = [];
    public $modalType = '';

    public function render()
    {
        // Get real statistics from the database
        $stats = $this->getDashboardStats();
        
        $appointments = new LengthAwarePaginator(
            items: [],
            total: 0,
            perPage: request('per_page', 10),
            currentPage: request('page', 1),
            options: [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        // Get top candidates for the table
        $topCandidates = $this->getTopCandidates();
        
        // Get voting transactions
        $votingTransactions = $this->getVotingTransactions();

        // Get partylists for students
        $partylists = partylist::where('status', 'active')
            ->with(['applied_candidacies.students', 'applied_candidacies.position'])
            ->get();

        return view('livewire.dashboard.dashboard', [
            'appointments' => $appointments,
            'stats' => $stats,
            'topCandidates' => $topCandidates,
            'votingTransactions' => $votingTransactions,
            'partylists' => $partylists,
        ]);
    }
    
    private function getDashboardStats()
    {
        return [
            // Voting Statistics
            'total_votes' => voting_vote_count::sum('number_of_vote'),
            'total_candidates' => voting_vote_count::count(),
            'winners' => voting_vote_count::where('status', 'win')->count(),
            'losers' => voting_vote_count::where('status', 'loss')->count(),
            'official_candidates' => voting_vote_count::where('status', 'official')->count(),
            
            // Voting Participation
            'total_voters' => voting_voted_by::distinct('students_id')->count(),
            'total_voting_sessions' => voting_voted_by::count(),
            
            // User Statistics
            'total_students' => students::count(),
            'total_users' => User::count(),
            'active_students' => students::where('status', 'active')->count(),
            'active_users' => User::where('status', 'active')->count(),
            
            // Academic Statistics
            'total_courses' => course::count(),
            'total_departments' => department::count(),
            'active_courses' => course::where('status', 'active')->count(),
            'active_departments' => department::where('status', 'active')->count(),
            
            // Meeting Statistics
            'total_meetings' => meeting_de_abanse::count(),
            'active_meetings' => meeting_de_abanse::where('status', 'active')->count(),
            'completed_meetings' => meeting_de_abanse::where('status', 'completed')->count(),
            
            // Position Statistics
            'total_positions' => position::count(),
            'active_positions' => position::where('status', 'active')->count(),
            
            // Voting Exclusive Statistics
            'total_voting_exclusives' => voting_exclusive::count(),
            'active_voting_exclusives' => voting_exclusive::where('status', 'active')->count(),
            'completed_voting_exclusives' => voting_exclusive::where('status', 'completed')->count(),
            
            // Candidacy Statistics
            'total_candidacies' => applied_candidacy::count(),
            'approved_candidacies' => applied_candidacy::where('status', 'approved')->count(),
            'pending_candidacies' => applied_candidacy::where('status', 'pending')->count(),
            'rejected_candidacies' => applied_candidacy::where('status', 'rejected')->count(),
        ];
    }
    
    private function getTopCandidates()
    {
        return voting_vote_count::with(['student.course', 'appliedCandidacy.position'])
            ->whereHas('student')
            ->whereHas('appliedCandidacy')
            ->orderBy('number_of_vote', 'desc')
            ->limit(5)
            ->get();
    }
    
    private function getVotingTransactions()
    {
        // Get unique students who have voted with their latest vote time
        $uniqueStudents = voting_voted_by::select('students_id')
            ->selectRaw('MAX(created_at) as latest_vote_time')
            ->whereHas('student')
            ->groupBy('students_id')
            ->orderBy('latest_vote_time', 'desc')
            ->limit(10)
            ->get();

        // Get the full transaction details for each unique student's latest vote
        $transactions = collect();
        foreach ($uniqueStudents as $student) {
            $transaction = voting_voted_by::with(['student.course'])
                ->where('students_id', $student->students_id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($transaction) {
                $transactions->push($transaction);
            }
        }

        return $transactions;
    }


    public function showCardModal($cardType)
    {
        try {
            $this->modalType = $cardType;
            
            switch ($cardType) {
                case 'total_votes':
                    $this->modalTitle = 'Total Votes Cast';
                    $this->modalDescription = 'Detailed breakdown of all votes cast in the election';
                    $this->modalData = $this->getVotesBreakdown();
                    break;
                    
                case 'total_candidates':
                    $this->modalTitle = 'Total Candidates';
                    $this->modalDescription = 'List of all candidates participating in the election';
                    $this->modalData = $this->getCandidatesData();
                    break;
                    
                case 'winners':
                    $this->modalTitle = 'Election Winners';
                    $this->modalDescription = 'Candidates who won their respective positions';
                    $this->modalData = $this->getWinnersData();
                    break;
                    
                case 'total_students':
                    $this->modalTitle = 'Total Students';
                    $this->modalDescription = 'Student enrollment statistics by course and department';
                    $this->modalData = $this->getStudentsData();
                    break;
                    
                case 'total_users':
                    $this->modalTitle = 'Total Users';
                    $this->modalDescription = 'User accounts and their status breakdown';
                    $this->modalData = $this->getUsersData();
                    break;
                    
                case 'total_voters':
                    $this->modalTitle = 'Total Voters';
                    $this->modalDescription = 'Students who have participated in voting';
                    $this->modalData = $this->getVotersData();
                    break;
                    
                case 'total_courses':
                    $this->modalTitle = 'Total Courses';
                    $this->modalDescription = 'Course offerings and enrollment statistics';
                    $this->modalData = $this->getCoursesData();
                    break;
                    
                case 'total_meetings':
                    $this->modalTitle = 'Total Meetings';
                    $this->modalDescription = 'Meeting schedules and attendance records';
                    $this->modalData = $this->getMeetingsData();
                    break;
                    
                default:
                    $this->modalTitle = 'Information';
                    $this->modalDescription = 'No data available for this category';
                    $this->modalData = collect();
                    break;
            }
            
            $this->showModal = true;
        } catch (\Exception $e) {
            // Log the error and show a generic message
            \Log::error('Dashboard modal error: ' . $e->getMessage());
            $this->modalTitle = 'Error';
            $this->modalDescription = 'Unable to load data at this time';
            $this->modalData = collect();
            $this->showModal = true;
        }
    }

    private function getVotesBreakdown()
    {
        return voting_vote_count::with(['student.course', 'appliedCandidacy.position'])
            ->orderBy('number_of_vote', 'desc')
            ->get()
            ->map(function ($vote) {
                return [
                    'candidate_name' => $vote->student ? $vote->student->first_name . ' ' . $vote->student->last_name : 'Unknown',
                    'position' => $vote->appliedCandidacy && $vote->appliedCandidacy->position ? $vote->appliedCandidacy->position->position_name : 'N/A',
                    'votes' => $vote->number_of_vote,
                    'status' => $vote->status,
                    'course' => $vote->student && $vote->student->course ? $vote->student->course->course_name : 'N/A'
                ];
            });
    }

    private function getCandidatesData()
    {
        return voting_vote_count::with(['student.course', 'appliedCandidacy.position'])
            ->get()
            ->map(function ($candidate) {
                return [
                    'candidate_name' => $candidate->student ? $candidate->student->first_name . ' ' . $candidate->student->last_name : 'Unknown',
                    'student_id' => $candidate->student ? $candidate->student->student_id : 'N/A',
                    'position' => $candidate->appliedCandidacy && $candidate->appliedCandidacy->position ? $candidate->appliedCandidacy->position->position_name : 'N/A',
                    'course' => $candidate->student && $candidate->student->course ? $candidate->student->course->course_name : 'N/A',
                    'votes' => $candidate->number_of_vote,
                    'status' => $candidate->status
                ];
            });
    }

    private function getWinnersData()
    {
        return voting_vote_count::with(['student.course', 'appliedCandidacy.position'])
            ->where('status', 'win')
            ->orderBy('number_of_vote', 'desc')
            ->get()
            ->map(function ($winner) {
                return [
                    'candidate_name' => $winner->student ? $winner->student->first_name . ' ' . $winner->student->last_name : 'Unknown',
                    'position' => $winner->appliedCandidacy && $winner->appliedCandidacy->position ? $winner->appliedCandidacy->position->position_name : 'N/A',
                    'votes' => $winner->number_of_vote,
                    'course' => $winner->student && $winner->student->course ? $winner->student->course->course_name : 'N/A',
                    'winning_margin' => $this->calculateWinningMargin($winner)
                ];
            });
    }

    private function getStudentsData()
    {
        return students::with('course')
            ->get()
            ->groupBy('course.course_name')
            ->map(function ($students, $courseName) {
                return [
                    'course' => $courseName ?? 'No Course',
                    'count' => $students->count(),
                    'active' => $students->where('status', 'active')->count(),
                    'inactive' => $students->where('status', 'inactive')->count()
                ];
            });
    }

    private function getUsersData()
    {
        // Get all users and categorize them
        $allUsers = User::all();
        $students = students::all();
        
        return collect([
            [
                'type' => 'System Users',
                'count' => $allUsers->count(),
                'active' => $allUsers->where('status', 'active')->count(),
                'inactive' => $allUsers->where('status', 'inactive')->count()
            ],
            [
                'type' => 'Students',
                'count' => $students->count(),
                'active' => $students->where('status', 'active')->count(),
                'inactive' => $students->where('status', 'inactive')->count()
            ]
        ]);
    }

    private function getVotersData()
    {
        return voting_voted_by::with(['student.course'])
            ->get()
            ->groupBy(function ($voter) {
                return $voter->student && $voter->student->course 
                    ? $voter->student->course->course_name 
                    : 'No Course';
            })
            ->map(function ($voters, $courseName) {
                return [
                    'course' => $courseName,
                    'voters_count' => $voters->count(),
                    'unique_voters' => $voters->unique('students_id')->count()
                ];
            });
    }

    private function getCoursesData()
    {
        return course::all()
            ->map(function ($course) {
                $students = students::where('course_id', $course->id)->get();
                return [
                    'course_name' => $course->course_name,
                    'status' => $course->status,
                    'students_count' => $students->count(),
                    'active_students' => $students->where('status', 'active')->count()
                ];
            });
    }

    private function getMeetingsData()
    {
        return meeting_de_abanse::all()
            ->map(function ($meeting) {
                return [
                    'title' => $meeting->meeting_de_abanse_name ?? 'Meeting',
                    'status' => $meeting->status,
                    'date' => $meeting->created_at->format('M d, Y'),
                    'description' => $meeting->description ?? 'No description'
                ];
            });
    }

    private function calculateWinningMargin($winner)
    {
        $positionId = $winner->appliedCandidacy && $winner->appliedCandidacy->position ? $winner->appliedCandidacy->position->id : null;
        if (!$positionId) return $winner->number_of_vote;

        $secondPlace = voting_vote_count::with('appliedCandidacy')
            ->whereHas('appliedCandidacy', function ($query) use ($positionId) {
                $query->where('position_id', $positionId);
            })
            ->where('id', '!=', $winner->id)
            ->orderBy('number_of_vote', 'desc')
            ->first();

        return $secondPlace ? $winner->number_of_vote - $secondPlace->number_of_vote : $winner->number_of_vote;
    }
}
