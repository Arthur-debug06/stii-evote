<?php

namespace App\Traits;

use App\Models\ActionLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
/**
 * LoggerTrait - A comprehensive activity logging solution for Laravel models
 *
 * This trait provides automatic activity logging for model events (create, update, delete, restore).
 * It tracks who made changes, what changed, and additional metadata about the activity.
 * Works with your existing ActionLog model and dual user system (User + students).
 *
 * BASIC USAGE:
 * ```php
 * class User extends Model
 * {
 *     use LoggerTrait;
 *
 *     // All model changes will be automatically logged
 * }
 * ```
 *
 * CUSTOMIZATION OPTIONS:
 * ```php
 * class User extends Model
 * {
 *     use LoggerTrait;
 *
 *     // Only log specific attributes
 *     protected static $logAttributes = ['name', 'email'];
 *
 *     // Ignore specific attributes
 *     protected static $ignoreAttributes = ['password', 'remember_token'];
 *
 *     // Log empty updates (default: false)
 *     protected static $submitEmptyLogs = true;
 * }
 * ```
 *
 * ACCESSING LOGS:
 * ```php
 * // Get all activity logs for a model
 * $user->actionLogs;
 *
 * // Query logs
 * ActionLog::where('trackable_type', get_class($user))
 *     ->where('trackable_id', $user->id)
 *     ->get();
 * ```
 *
 * DISABLE LOGGING:
 * ```php
 * // Temporarily disable logging for specific operations
 * $user->withoutLogging(function ($user) {
 *     $user->update(['login_count' => $user->login_count + 1]);
 * });
 *
 * // Or manually disable/enable
 * $user->disableLogging();
 * $user->update(['name' => 'New Name']); // Won't be logged
 * $user->enableLogging();
 * ```
 *
 * LOGGED INFORMATION:
 * - Event type (created, updated, deleted, restored)
 * - User who made the change (supports both User and students models)
 * - Old and new values for updates
 * - IP address and user agent
 * - Timestamp
 * - Additional custom properties
 *
 * NOTE:
 * - The trait automatically handles model events (create, update, delete, restore)
 * - Soft-deleted models are supported
 * - Logs are stored in the action_logs table
 * - Works with your dual user system (User + students models)
 */
trait LoggerTrait
{
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;
    protected static $logAttributes = ['*'];
    protected static $ignoreAttributes = ['created_at', 'updated_at', 'deleted_at'];
    protected static $batchUuid = null;

    /**
     * Boot the trait.
     */
    protected static function bootLoggerTrait()
    {
        static::created(function (Model $model) {
            $model->logActivity('created');
        });

        static::updated(function (Model $model) {
            $model->logActivity('updated');
        });

        static::deleted(function (Model $model) {
            $model->logActivity('deleted');
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function (Model $model) {
                $model->logActivity('restored');
            });
        }
    }

    /**
     * Get the log name for this model.
     */
    public function getLogName(): string
    {
        return Str::snake(class_basename($this));
    }

    /**
     * Get the description for the activity log.
     */
    protected function getLogDescription(string $event): string
    {
        return ucfirst($event) . ' ' . Str::snake(class_basename($this), ' ');
    }

    /**
     * Get the properties that should be logged.
     */
    protected function getLogProperties(string $event): array
    {
        $properties = [];

        // Get all attributes for complete data capture
        $allAttributes = $this->getAttributes();

        // Remove ignored attributes
        $attributes = array_diff_key(
            $allAttributes,
            array_flip(static::$ignoreAttributes)
        );

        // No custom formatting for now - keep it simple

        if ($event === 'created') {
            $properties['attributes'] = $this->formatAttributesForLog($attributes);
            $properties['changes_summary'] = array_map(function ($key) use ($attributes) {
                return [
                    'field' => $key,
                    'from' => null,
                    'to' => $this->formatValueForComparison($attributes[$key]),
                ];
            }, array_keys($attributes));
        } elseif ($event === 'updated') {
            // Get original values before update
            $original = $this->getOriginal();

            // Remove ignored attributes from original
            $oldValues = array_diff_key(
                $original,
                array_flip(static::$ignoreAttributes)
            );

            // No custom formatting for now - keep it simple

            $properties['old'] = $this->formatAttributesForLog($oldValues);
            $properties['attributes'] = $this->formatAttributesForLog($attributes);
            $properties['changes_summary'] = $this->getChangesSummary($oldValues, $attributes);
        } elseif ($event === 'deleted') {
            $properties['attributes'] = $this->formatAttributesForLog($attributes);
            $properties['changes_summary'] = array_map(function ($key) use ($attributes) {
                return [
                    'field' => $key,
                    'from' => $this->formatValueForComparison($attributes[$key]),
                    'to' => null,
                ];
            }, array_keys($attributes));
        } elseif ($event === 'restored') {
            $properties['attributes'] = $this->formatAttributesForLog($attributes);

            $lastDeletedState = $this->activities()
                ->where('event', 'deleted')
                ->latest()
                ->first()?->properties['attributes'] ?? [];

            $properties['changes_summary'] = array_map(function ($key) use ($attributes, $lastDeletedState) {
                return [
                    'field' => $key,
                    'from' => $this->formatValueForComparison($lastDeletedState[$key] ?? null),
                    'to' => $this->formatValueForComparison($attributes[$key]),
                ];
            }, array_keys($attributes));
        }

        return $properties;
    }

    /**
     * Format attributes for logging
     */
    protected function formatAttributesForLog(array $attributes): array
    {
        return array_map(function ($value) {
            return $this->formatValueForComparison($value);
        }, $attributes);
    }

    /**
     * Get a summary of changes between old and new values.
     */
    protected function getChangesSummary(array $oldValues, array $newValues): array
    {
        $changes = [];

        // Process all keys from both arrays
        $allKeys = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));

        foreach ($allKeys as $key) {
            $oldValue = $oldValues[$key] ?? null;
            $newValue = $newValues[$key] ?? null;

            // Format values for comparison
            $oldValue = $this->formatValueForComparison($oldValue);
            $newValue = $this->formatValueForComparison($newValue);

            $changes[] = [
                'field' => $key,
                'from' => $oldValue,
                'to' => $newValue,
            ];
        }

        return $changes;
    }

    /**
     * Format a value for comparison in change summary.
     */
    protected function formatValueForComparison($value)
    {
        if (is_bool($value)) {
            return $value ? true : false;
        }

        if (is_null($value)) {
            return null;
        }

        // Handle arrays and objects that can be converted to arrays
        if (is_array($value) || (is_object($value) && method_exists($value, 'toArray'))) {
            return is_array($value) ? json_encode($value) : json_encode($value->toArray());
        }

        // Handle date/datetime values
        if ($value instanceof \DateTime || $value instanceof \Carbon\Carbon) {
            return $value->format('Y-m-d H:i:s');
        }

        // Handle objects that have __toString method
        if (is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        }

        // Handle other objects by converting to a readable format
        if (is_object($value)) {
            return '[Object:' . get_class($value) . ']';
        }

        // Convert to string for comparison
        try {
            return (string) $value;
        } catch (\Exception $e) {
            return '[Unconvertible Value]';
        }
    }

    /**
     * Get the attributes that should be logged.
     */
    protected function getLoggedAttributes(): array
    {
        $attributes = $this->getAttributes();

        if (static::$logAttributes === ['*']) {
            $attributes = array_diff_key(
                $attributes,
                array_flip(static::$ignoreAttributes)
            );
        } else {
            $attributes = array_intersect_key(
                $attributes,
                array_flip(static::$logAttributes)
            );
        }

        // No custom formatting for now - keep it simple

        return $attributes;
    }

    /**
     * Get location information (simplified)
     */
    protected function getLocationFromIp($ip)
    {
        // Simple location detection - can be enhanced later
        return [
            'city' => 'Unknown',
            'region' => 'Unknown',
            'country' => 'Unknown',
            'address' => 'Unknown Location',
            'latitude' => null,
            'longitude' => null
        ];
    }

    /**
     * Log activity for this model.
     */
    public function logActivity(string $event, array $properties = []): ?ActionLog
    {
        // Don't log if nothing has changed and we're not logging empty events
        if (!static::$submitEmptyLogs && $event === 'updated' && empty($this->getDirty())) {
            return null;
        }

        $logProperties = array_merge(
            $this->getLogProperties($event),
            $properties
        );

        // Get user agent info
        $userAgent = Request::userAgent();
        $browser = 'Unknown';
        if (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Edge';
        } elseif (strpos($userAgent, 'Opera') !== false) {
            $browser = 'Opera';
        }

        $ip = request()->ip();
        $location = $this->getLocationFromIp($ip);

        $log = ActionLog::create([
            'document_type' => get_class($this),
            'document_id' => $this->getKey(),
            'user_id' => $this->getCurrentUserId(),
            'created_by' => $this->getCurrentUserId(),
            'action' => $event,
            'details' => $this->getLogDescription($event),
            'remarks' => json_encode($logProperties),
            'trackable_type' => get_class($this),
            'trackable_id' => $this->getKey(),
            'ip_address' => $ip,
            'user_agent' => $browser,
            'location' => $location['address'],
            'batch_uuid' => static::$batchUuid,
        ]);

        return $log;
    }

    /**
     * Start logging activities in a batch.
     */
    public static function startBatch(): string
    {
        static::$batchUuid = (string) Str::uuid();
        return static::$batchUuid;
    }

    /**
     * Stop logging activities in a batch.
     */
    public static function stopBatch(): void
    {
        static::$batchUuid = null;
    }

    /**
     * Get all activity logs for this model.
     */
    public function actionLogs()
    {
        return $this->morphMany(ActionLog::class, 'trackable');
    }

    /**
     * Disable activity logging for the next operation.
     */
    public function withoutLogging(callable $callback)
    {
        $wasLogging = $this->isLogging();
        $this->disableLogging();

        try {
            return $callback($this);
        } finally {
            if ($wasLogging) {
                $this->enableLogging();
            }
        }
    }

    /**
     * Enable activity logging.
     */
    public function enableLogging(): self
    {
        static::$logOnlyDirty = true;
        return $this;
    }

    /**
     * Disable activity logging.
     */
    public function disableLogging(): self
    {
        static::$logOnlyDirty = false;
        return $this;
    }

    /**
     * Determine if logging is enabled.
     */
    public function isLogging(): bool
    {
        return static::$logOnlyDirty;
    }

    /**
     * Get user name from either User or students model
     */
    protected function getUserName($userId): string
    {
        if (!$userId) {
            return 'System';
        }

        // Try to find in User model first (admin/staff)
        $user = \App\Models\User::find($userId);
        if ($user) {
            return $user->name ?? 'Unknown User';
        }

        // Try to find in students model
        $student = \App\Models\students::find($userId);
        if ($student) {
            $name = trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name);
            $suffix = $student->suffix ? ', ' . $student->suffix : '';
            return $name . $suffix ?: 'Unknown Student';
        }

        return 'Unknown User';
    }

    /**
     * Get user type (admin or student)
     */
    protected function getUserType($userId): string
    {
        if (!$userId) {
            return 'system';
        }

        // Check if it's a User model (admin/staff)
        if (\App\Models\User::find($userId)) {
            return 'admin';
        }

        // Check if it's a students model
        if (\App\Models\students::find($userId)) {
            return 'student';
        }

        return 'unknown';
    }

    /**
     * Get current logged-in user ID from either guard
     */
    protected function getCurrentUserId(): ?int
    {
        // Try to get from default guard (User model)
        if (Auth::check()) {
            return Auth::id();
        }

        // Try to get from students guard
        if (Auth::guard('students')->check()) {
            return Auth::guard('students')->id();
        }

        // Return null if no user is authenticated
        return null;
    }
}