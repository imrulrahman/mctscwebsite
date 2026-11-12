<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    private function authorizeAccess(): void
    {
        $user = auth()->user();
        abort_unless($user && ($user->is_admin || $user->isSystemAdmin() || $user->can('career.manage') || $user->can('notices.manage')), 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeAccess();

        $query = JobApplication::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('tracking_no', 'like', "%{$q}%")
                    ->orWhere('applicant_name', 'like', "%{$q}%")
                    ->orWhere('post_name', 'like', "%{$q}%")
                    ->orWhere('mobile_no', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $applications = $query->paginate(20)->withQueryString();

        return view('admin.job_applications.index', compact('applications'));
    }

    public function show(JobApplication $jobApplication): View
    {
        $this->authorizeAccess();
        return view('admin.job_applications.show', ['application' => $jobApplication]);
    }

    public function update(Request $request, JobApplication $jobApplication): RedirectResponse
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'status' => ['required', 'in:new,shortlisted,interview,hired,rejected'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $jobApplication->update($validated);

        return back()->with('success', 'Job application status updated successfully.');
    }

    public function destroy(JobApplication $jobApplication): RedirectResponse
    {
        $this->authorizeAccess();

        foreach (['photo_path', 'signature_path', 'cv_path'] as $col) {
            if ($jobApplication->$col) {
                Storage::disk('public')->delete($jobApplication->$col);
                $pub = public_path('storage/' . $jobApplication->$col);
                if (file_exists($pub)) @unlink($pub);
            }
        }

        $jobApplication->delete();

        return redirect()->route('admin.job-applications.index')->with('success', 'Job application deleted.');
    }
}
