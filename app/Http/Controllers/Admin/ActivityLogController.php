<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        abort_unless($user && ($user->isSystemAdmin() || $user->can('users.view') || $user->can('settings.manage')), 403, 'Unauthorized access to activity logs.');

        $query = ActivityLog::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('user_name', 'like', "%{$q}%")
                    ->orWhere('user_email', 'like', "%{$q}%")
                    ->orWhere('ip_address', 'like', "%{$q}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(20)->withQueryString();

        $users = User::orderBy('name')->get();
        $modules = ActivityLog::select('module')->distinct()->whereNotNull('module')->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->whereNotNull('action')->pluck('action');

        return view('admin.activity_logs.index', compact('logs', 'users', 'modules', 'actions'));
    }

    public function clearOldLogs(Request $request): RedirectResponse
    {
        $user = auth()->user();
        abort_unless($user && $user->isSystemAdmin(), 403, 'Only System Administrator can clear activity logs.');

        // Keep last 30 days of logs by default
        $days = (int)$request->input('days', 60);
        $count = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', "পুরোনো {$count}টি একটিভিটি লগ মুছে ফেলা হয়েছে (বিগত {$days} দিনের আগের রেকর্ড)।");
    }
}
