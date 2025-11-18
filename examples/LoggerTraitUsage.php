<?php

/**
 * LoggerTrait Usage Examples
 * 
 * This file demonstrates how to use the revised LoggerTrait
 * with your ActionLog model and dual user system.
 */

namespace Examples;

use App\Models\User;
use App\Models\students;
use App\Models\set_signatory;
use App\Models\signatory_action;
use App\Models\ActionLog;

class LoggerTraitUsage
{
    public function demonstrateUsage()
    {
        // Example 1: Basic usage - Add trait to any model
        /*
        class User extends Model
        {
            use LoggerTrait;
            
            // All model changes will be automatically logged
        }
        */

        // Example 2: Customize what gets logged
        /*
        class set_signatory extends Model
        {
            use LoggerTrait;
            
            // Only log specific attributes
            protected static $logAttributes = ['position', 'academic_suffix', 'status'];
            
            // Ignore specific attributes
            protected static $ignoreAttributes = ['created_at', 'updated_at', 'deleted_at'];
            
            // Log empty updates (default: false)
            protected static $submitEmptyLogs = true;
        }
        */

        // Example 3: Accessing logs
        $user = User::find(1);
        
        // Get all activity logs for this user
        $logs = $user->actionLogs;
        
        // Query logs with filters
        $recentLogs = ActionLog::where('trackable_type', get_class($user))
            ->where('trackable_id', $user->id)
            ->where('action', 'updated')
            ->latest()
            ->take(10)
            ->get();

        // Example 4: Manual logging
        $user->logActivity('custom_action', [
            'custom_property' => 'custom_value',
            'additional_info' => 'Some additional information'
        ]);

        // Example 5: Batch operations
        User::startBatch();
        $user->update(['name' => 'New Name']);
        $user->update(['email' => 'new@email.com']);
        User::stopBatch();
        // Both updates will be grouped together with the same batch_uuid

        // Example 6: Disable logging temporarily
        $user->withoutLogging(function ($user) {
            $user->update(['login_count' => $user->login_count + 1]);
            // This update won't be logged
        });

        // Example 7: Manual enable/disable
        $user->disableLogging();
        $user->update(['last_login' => now()]); // Won't be logged
        $user->enableLogging();
        $user->update(['status' => 'active']); // Will be logged

        // Example 8: Working with signatory models
        $signatory = set_signatory::find(1);
        
        // All changes to signatory will be logged
        $signatory->update([
            'position' => 'New Position',
            'academic_suffix' => 'PhD'
        ]);

        // Get signatory's activity logs
        $signatoryLogs = $signatory->actionLogs;

        // Example 9: Working with dual user system
        $adminUser = User::find(1);
        $studentUser = students::find(1);
        
        // Both user types will be properly logged
        $adminUser->update(['name' => 'New Admin Name']);
        $studentUser->update(['first_name' => 'New First Name']);

        // Example 10: Query logs by user type
        $adminLogs = ActionLog::where('user_id', $adminUser->id)->get();
        $studentLogs = ActionLog::where('user_id', $studentUser->id)->get();

        // Example 11: Get logs for specific model type
        $signatoryLogs = ActionLog::where('trackable_type', set_signatory::class)->get();
        $actionLogs = ActionLog::where('trackable_type', signatory_action::class)->get();

        // Example 12: Get logs by action type
        $createdLogs = ActionLog::where('action', 'created')->get();
        $updatedLogs = ActionLog::where('action', 'updated')->get();
        $deletedLogs = ActionLog::where('action', 'deleted')->get();
    }

    /**
     * Example of using LoggerTrait in a Livewire component
     */
    public function livewireComponentExample()
    {
        /*
        class UserManagement extends Component
        {
            use LoggerTrait;

            public function updateUser($userId, $data)
            {
                $user = User::find($userId);
                
                // Start batch for related updates
                User::startBatch();
                
                $user->update($data);
                
                // Log custom action
                $user->logActivity('profile_updated', [
                    'updated_by' => auth()->id(),
                    'update_reason' => 'Admin update'
                ]);
                
                User::stopBatch();
                
                $this->dispatch('user-updated');
            }

            public function deleteUser($userId)
            {
                $user = User::find($userId);
                
                // Log before deletion
                $user->logActivity('user_deletion_requested', [
                    'requested_by' => auth()->id(),
                    'reason' => 'Admin deletion'
                ]);
                
                $user->delete(); // This will also be logged automatically
            }
        }
        */
    }

    /**
     * Example of using LoggerTrait in a Controller
     */
    public function controllerExample()
    {
        /*
        class UserController extends Controller
        {
            public function update(Request $request, $id)
            {
                $user = User::findOrFail($id);
                
                // Validate request
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email,' . $id,
                ]);
                
                // Update user (automatically logged)
                $user->update($request->only(['name', 'email']));
                
                // Log custom action
                $user->logActivity('admin_update', [
                    'admin_id' => auth()->id(),
                    'update_reason' => $request->input('reason', 'No reason provided')
                ]);
                
                return redirect()->route('users.index')
                    ->with('success', 'User updated successfully.');
            }

            public function bulkUpdate(Request $request)
            {
                $userIds = $request->input('user_ids', []);
                $updateData = $request->only(['status', 'role']);
                
                // Start batch for bulk operations
                User::startBatch();
                
                foreach ($userIds as $userId) {
                    $user = User::find($userId);
                    if ($user) {
                        $user->update($updateData);
                    }
                }
                
                User::stopBatch();
                
                return response()->json(['success' => true]);
            }
        }
        */
    }

    /**
     * Example of using LoggerTrait in a Service class
     */
    public function serviceClassExample()
    {
        /*
        class UserService
        {
            use LoggerTrait;

            public function createUser(array $data)
            {
                // Create user (automatically logged)
                $user = User::create($data);
                
                // Log custom action
                $user->logActivity('user_created', [
                    'created_by' => auth()->id(),
                    'creation_method' => 'admin_panel',
                    'initial_status' => $data['status'] ?? 'active'
                ]);
                
                return $user;
            }

            public function transferUser($userId, $newDepartmentId)
            {
                $user = User::findOrFail($userId);
                $oldDepartmentId = $user->department_id;
                
                // Start batch for related updates
                User::startBatch();
                
                $user->update(['department_id' => $newDepartmentId]);
                
                // Log transfer action
                $user->logActivity('department_transfer', [
                    'from_department' => $oldDepartmentId,
                    'to_department' => $newDepartmentId,
                    'transferred_by' => auth()->id(),
                    'transfer_reason' => 'Administrative transfer'
                ]);
                
                User::stopBatch();
                
                return $user;
            }

            public function getActivityHistory($userId)
            {
                $user = User::findOrFail($userId);
                
                return [
                    'user' => $user,
                    'activities' => $user->actionLogs()
                        ->with(['user'])
                        ->latest()
                        ->paginate(20),
                    'summary' => [
                        'total_activities' => $user->actionLogs()->count(),
                        'last_activity' => $user->actionLogs()->latest()->first(),
                        'most_common_action' => $user->actionLogs()
                            ->selectRaw('action, COUNT(*) as count')
                            ->groupBy('action')
                            ->orderBy('count', 'desc')
                            ->first()
                    ]
                ];
            }
        }
        */
    }

    /**
     * Example of querying logs for reporting
     */
    public function reportingExample()
    {
        // Get all activities for today
        $todayActivities = ActionLog::whereDate('created_at', today())->get();
        
        // Get activities by user type
        $adminActivities = ActionLog::whereHas('user', function ($query) {
            $query->whereIn('id', User::pluck('id'));
        })->get();
        
        $studentActivities = ActionLog::whereHas('user', function ($query) {
            $query->whereIn('id', students::pluck('id'));
        })->get();
        
        // Get activities by model type
        $userActivities = ActionLog::where('trackable_type', User::class)->get();
        $signatoryActivities = ActionLog::where('trackable_type', set_signatory::class)->get();
        
        // Get activities by action type
        $createActivities = ActionLog::where('action', 'created')->get();
        $updateActivities = ActionLog::where('action', 'updated')->get();
        $deleteActivities = ActionLog::where('action', 'deleted')->get();
        
        // Get activities with specific properties
        $customActivities = ActionLog::whereJsonContains('remarks->custom_property', 'custom_value')->get();
        
        // Get batch activities
        $batchUuid = 'some-batch-uuid';
        $batchActivities = ActionLog::where('batch_uuid', $batchUuid)->get();
    }
}
