<?php

namespace App\Traits;

use App\Models\signatory_action;
use App\Models\set_signatory;
use App\Models\User;
use App\Models\students;

trait SignatoryTrait
{
    /**
     * Get all signatory actions
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSignatoryActions()
    {
        return signatory_action::where('status', 'active')->get();
    }

    /**
     * Get signatory action by ID
     *
     * @param int $actionId
     * @return \App\Models\signatory_action|null
     */
    public function getSignatoryAction($actionId)
    {
        return signatory_action::find($actionId);
    }

    /**
     * Get signatories for a specific action
     *
     * @param int $actionId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSignatoriesForAction($actionId)
    {
        return set_signatory::where('signatory_action_id', $actionId)
            ->where('status', 'active')
            ->with(['users', 'signatoryAction'])
            ->get();
    }

    /**
     * Get all active signatories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllActiveSignatories()
    {
        return set_signatory::where('status', 'active')
            ->with(['users', 'signatoryAction'])
            ->get();
    }

    /**
     * Get signatory roles based on signatory_action
     */
    public static function getSignatoryRoles()
    {
        $predefinedRoles = [
            'Prepared by'    => 'Prepared by',
            'Approved by'    => 'Approved by',
            'Noted by'       => 'Noted by',
            'Checked by'     => 'Checked by',
            'Recommended by' => 'Recommended by',
            'Reviewed by'    => 'Reviewed by',
            'Verified by'    => 'Verified by',
            'Endorsed by'    => 'Endorsed by',
        ];

        // Get custom roles from signatory_action table
        $customRoles = signatory_action::where('status', 'active')
            ->pluck('action_name')
            ->filter()
            ->toArray();

        foreach ($customRoles as $role) {
            if (!array_key_exists($role, $predefinedRoles)) {
                $predefinedRoles[$role] = $role;
            }
        }

        // Add "Other" for custom input
        $predefinedRoles['Other'] = 'Other';

        return $predefinedRoles;
    }

    /**
     * Get signatory positions
     */
    public static function getSignatoryPositions()
    {
        $predefinedPositions = [
            'Department Head'     => 'Department Head',
            'Dean'               => 'Dean',
            'Registrar'          => 'Registrar',
            'Vice President'     => 'Vice President',
            'President'          => 'President',
            'Student Representative' => 'Student Representative',
            'Faculty Member'     => 'Faculty Member',
            'Administrative Staff' => 'Administrative Staff',
        ];

        // Get custom positions from set_signatory table
        $customPositions = set_signatory::where('status', 'active')
            ->distinct()
            ->pluck('position')
            ->filter()
            ->toArray();

        foreach ($customPositions as $position) {
            if (!array_key_exists($position, $predefinedPositions)) {
                $predefinedPositions[$position] = $position;
            }
        }

        return $predefinedPositions;
    }

    /**
     * Get academic suffixes
     */
    public static function getAcademicSuffixes()
    {
        return [
            'PhD'    => 'PhD',
            'EdD'    => 'EdD',
            'MD'     => 'MD',
            'CPA'    => 'CPA',
            'LLB'    => 'LLB',
            'MA'     => 'MA',
            'MS'     => 'MS',
            'MBA'    => 'MBA',
            'BS'     => 'BS',
            'BA'     => 'BA',
            'Jr.'    => 'Jr.',
            'Sr.'    => 'Sr.',
            'II'     => 'II',
            'III'    => 'III',
        ];
    }

    /**
     * Create a new signatory
     *
     * @param array $data
     * @return \App\Models\set_signatory
     */
    public function createSignatory(array $data)
    {
        return set_signatory::create([
            'users_id' => $data['users_id'],
            'position' => $data['position'],
            'academic_suffix' => $data['academic_suffix'] ?? null,
            'signatory_action_id' => $data['signatory_action_id'],
            'status' => $data['status'] ?? 'active',
        ]);
    }

    /**
     * Update signatory status
     *
     * @param int $signatoryId
     * @param string $status
     * @return bool
     */
    public function updateSignatoryStatus($signatoryId, $status)
    {
        $signatory = set_signatory::find($signatoryId);
        if ($signatory) {
            $signatory->update(['status' => $status]);
            return true;
        }
        return false;
    }

    /**
     * Get signatories by user type (User or students)
     *
     * @param string $userType 'admin' or 'student'
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSignatoriesByUserType($userType = 'admin')
    {
        $query = set_signatory::where('status', 'active')
            ->with(['users', 'signatoryAction']);

        if ($userType === 'student') {
            // Get signatories where users_id exists in students table
            $query->whereHas('users', function ($q) {
                $q->whereIn('id', students::pluck('id'));
            });
        } else {
            // Get signatories where users_id exists in users table
            $query->whereHas('users', function ($q) {
                $q->whereIn('id', User::pluck('id'));
            });
        }

        return $query->get();
    }

    /**
     * Check if user is a signatory for a specific action
     *
     * @param int $userId
     * @param int $actionId
     * @return bool
     */
    public function isUserSignatoryForAction($userId, $actionId)
    {
        return set_signatory::where('users_id', $userId)
            ->where('signatory_action_id', $actionId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get user's signatory assignments
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserSignatoryAssignments($userId)
    {
        return set_signatory::where('users_id', $userId)
            ->where('status', 'active')
            ->with(['signatoryAction'])
            ->get();
    }
}