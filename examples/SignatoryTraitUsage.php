<?php

/**
 * SignatoryTrait Usage Examples
 * 
 * This file demonstrates how to use the revised SignatoryTrait
 * with your signatory_action and set_signatory models.
 */

namespace Examples;

use App\Models\signatory_action;
use App\Models\set_signatory;
use App\Models\User;
use App\Models\students;

class SignatoryTraitUsage
{
    public function demonstrateUsage()
    {
        // Example 1: Get all signatory actions
        $signatoryActions = $this->getSignatoryActions();
        foreach ($signatoryActions as $action) {
            echo "Action: {$action->action_name} - Status: {$action->status}\n";
        }

        // Example 2: Get signatory action by ID
        $action = $this->getSignatoryAction(1);
        if ($action) {
            echo "Found action: {$action->action_name}\n";
        }

        // Example 3: Get signatories for a specific action
        $signatories = $this->getSignatoriesForAction(1);
        foreach ($signatories as $signatory) {
            echo "Signatory: {$signatory->user_name} - Position: {$signatory->position}\n";
        }

        // Example 4: Get all active signatories
        $allSignatories = $this->getAllActiveSignatories();
        echo "Total active signatories: " . $allSignatories->count() . "\n";

        // Example 5: Get signatory roles (static method)
        $roles = self::getSignatoryRoles();
        echo "Available roles:\n";
        foreach ($roles as $key => $value) {
            echo "- {$value}\n";
        }

        // Example 6: Get signatory positions (static method)
        $positions = self::getSignatoryPositions();
        echo "Available positions:\n";
        foreach ($positions as $key => $value) {
            echo "- {$value}\n";
        }

        // Example 7: Get academic suffixes (static method)
        $suffixes = self::getAcademicSuffixes();
        echo "Available suffixes:\n";
        foreach ($suffixes as $key => $value) {
            echo "- {$value}\n";
        }

        // Example 8: Create a new signatory
        $newSignatory = $this->createSignatory([
            'users_id' => 1,
            'position' => 'Department Head',
            'academic_suffix' => 'PhD',
            'signatory_action_id' => 1,
            'status' => 'active',
        ]);
        echo "Created signatory with ID: {$newSignatory->id}\n";

        // Example 9: Update signatory status
        $updated = $this->updateSignatoryStatus($newSignatory->id, 'inactive');
        if ($updated) {
            echo "Signatory status updated successfully\n";
        }

        // Example 10: Get signatories by user type
        $adminSignatories = $this->getSignatoriesByUserType('admin');
        echo "Admin signatories: " . $adminSignatories->count() . "\n";

        $studentSignatories = $this->getSignatoriesByUserType('student');
        echo "Student signatories: " . $studentSignatories->count() . "\n";

        // Example 11: Check if user is signatory for action
        $isSignatory = $this->isUserSignatoryForAction(1, 1);
        if ($isSignatory) {
            echo "User 1 is a signatory for action 1\n";
        }

        // Example 12: Get user's signatory assignments
        $userAssignments = $this->getUserSignatoryAssignments(1);
        echo "User 1 has " . $userAssignments->count() . " signatory assignments\n";
        foreach ($userAssignments as $assignment) {
            echo "- {$assignment->signatoryAction->action_name} as {$assignment->position}\n";
        }
    }

    /**
     * Example of using SignatoryTrait in a Livewire component
     */
    public function livewireComponentExample()
    {
        /*
        class SignatoryManagement extends Component
        {
            use SignatoryTrait;

            public $signatoryActions = [];
            public $signatories = [];
            public $selectedAction = null;

            public function mount()
            {
                $this->signatoryActions = $this->getSignatoryActions();
                $this->signatories = $this->getAllActiveSignatories();
            }

            public function selectAction($actionId)
            {
                $this->selectedAction = $actionId;
                $this->signatories = $this->getSignatoriesForAction($actionId);
            }

            public function createSignatory()
            {
                $this->validate([
                    'users_id' => 'required|exists:users,id',
                    'position' => 'required|string',
                    'academic_suffix' => 'nullable|string',
                    'signatory_action_id' => 'required|exists:signatory_action,id',
                ]);

                $this->createSignatory([
                    'users_id' => $this->users_id,
                    'position' => $this->position,
                    'academic_suffix' => $this->academic_suffix,
                    'signatory_action_id' => $this->signatory_action_id,
                ]);

                $this->signatories = $this->getAllActiveSignatories();
                $this->dispatch('signatory-created');
            }

            public function render()
            {
                return view('livewire.signatory-management', [
                    'roles' => self::getSignatoryRoles(),
                    'positions' => self::getSignatoryPositions(),
                    'suffixes' => self::getAcademicSuffixes(),
                ]);
            }
        }
        */
    }

    /**
     * Example of using SignatoryTrait in a Controller
     */
    public function controllerExample()
    {
        /*
        class SignatoryController extends Controller
        {
            use SignatoryTrait;

            public function index()
            {
                $signatoryActions = $this->getSignatoryActions();
                $signatories = $this->getAllActiveSignatories();
                
                return view('signatories.index', compact('signatoryActions', 'signatories'));
            }

            public function show($id)
            {
                $action = $this->getSignatoryAction($id);
                $signatories = $this->getSignatoriesForAction($id);
                
                return view('signatories.show', compact('action', 'signatories'));
            }

            public function store(Request $request)
            {
                $request->validate([
                    'users_id' => 'required|exists:users,id',
                    'position' => 'required|string',
                    'academic_suffix' => 'nullable|string',
                    'signatory_action_id' => 'required|exists:signatory_action,id',
                ]);

                $signatory = $this->createSignatory($request->all());
                
                return redirect()->route('signatories.show', $signatory->id)
                    ->with('success', 'Signatory created successfully.');
            }

            public function updateStatus(Request $request, $id)
            {
                $updated = $this->updateSignatoryStatus($id, $request->status);
                
                if ($updated) {
                    return response()->json(['success' => true]);
                }
                
                return response()->json(['success' => false], 404);
            }
        }
        */
    }

    /**
     * Example of using SignatoryTrait in a Service class
     */
    public function serviceClassExample()
    {
        /*
        class SignatoryService
        {
            use SignatoryTrait;

            public function assignSignatoryToDocument($documentId, $signatoryData)
            {
                // Create signatory
                $signatory = $this->createSignatory($signatoryData);
                
                // Check if user is already assigned to this action
                $isAlreadyAssigned = $this->isUserSignatoryForAction(
                    $signatoryData['users_id'], 
                    $signatoryData['signatory_action_id']
                );
                
                if ($isAlreadyAssigned) {
                    throw new \Exception('User is already assigned to this action');
                }
                
                return $signatory;
            }

            public function getSignatoryWorkload($userId)
            {
                $assignments = $this->getUserSignatoryAssignments($userId);
                
                return [
                    'total_assignments' => $assignments->count(),
                    'assignments' => $assignments->map(function ($assignment) {
                        return [
                            'action' => $assignment->signatoryAction->action_name,
                            'position' => $assignment->position,
                            'status' => $assignment->status,
                        ];
                    }),
                ];
            }

            public function getSignatoryStatistics()
            {
                $adminSignatories = $this->getSignatoriesByUserType('admin');
                $studentSignatories = $this->getSignatoriesByUserType('student');
                
                return [
                    'total_signatories' => $adminSignatories->count() + $studentSignatories->count(),
                    'admin_signatories' => $adminSignatories->count(),
                    'student_signatories' => $studentSignatories->count(),
                    'active_actions' => $this->getSignatoryActions()->count(),
                ];
            }
        }
        */
    }
}
