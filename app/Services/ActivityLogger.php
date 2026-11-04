<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an activity into the database.
     *
     * @param string $action (e.g., 'login', 'logout', 'failed_login', 'change_banner', 'create', 'update', 'delete')
     * @param string $module (e.g., 'Security & Auth', 'Hero Banner', 'Website CMS', 'Site Settings', 'User Management', etc.)
     * @param string $description Human-friendly description (e.g., 'রাকিব হাসান ব্যানার ইমেজ চেঞ্জ করেছেন')
     * @param Model|null $subject Optional Eloquent model associated with action
     * @param array|null $properties Optional extra details/metadata
     * @param User|null $user Override user (defaults to Auth::user())
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?Model $subject = null,
        ?array $properties = null,
        ?User $user = null
    ): ?ActivityLog {
        try {
            $currentUser = $user ?? Auth::user();

            return ActivityLog::create([
                'user_id' => $currentUser?->id,
                'user_name' => $currentUser?->name ?? 'Guest / Unknown',
                'user_email' => $currentUser?->email ?? null,
                'user_role' => $currentUser?->role?->name ?? ($currentUser?->isSystemAdmin() ? 'System Admin' : 'Staff'),
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject ? $subject->getKey() : null,
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'user_agent' => Request::userAgent() ?? 'Unknown',
                'properties' => $properties,
            ]);
        } catch (\Throwable $e) {
            // Fail silently so activity logging never breaks the main flow
            report($e);
            return null;
        }
    }
}
