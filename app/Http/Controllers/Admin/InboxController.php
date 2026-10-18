<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InboxController extends Controller
{
    public function applications(Request $request): View
    {
        $query = AdmissionApplication::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('course')) {
            $query->where('course', $request->input('course'));
        }
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('tracking_no', 'like', "%{$q}%")
                    ->orWhere('trainee_name_en', 'like', "%{$q}%")
                    ->orWhere('trainee_name_bn', 'like', "%{$q}%")
                    ->orWhere('contact_no', 'like', "%{$q}%")
                    ->orWhere('ident_no', 'like', "%{$q}%");
            });
        }

        $items = $query->paginate(20)->withQueryString();
        $courses = AdmissionApplication::select('course')->distinct()->pluck('course');

        return view('admin.inbox.applications', compact('items', 'courses'));
    }

    public function showApplication(AdmissionApplication $application): View
    {
        return view('admin.inbox.application_show', compact('application'));
    }

    public function printApplication(AdmissionApplication $application): View
    {
        return view('admin.inbox.application_print', compact('application'));
    }

    public function updateApplication(Request $request, AdmissionApplication $application): RedirectResponse
    {
        $application->update($request->validate([
            'status' => ['required', 'in:new,reviewing,accepted,rejected']
        ]));
        return back()->with('success', 'আবেদনের অবস্থা সফলভাবে হালনাগাদ করা হয়েছে।');
    }

    public function messages(): View
    {
        ContactMessage::where('is_read', false)->update(['is_read' => true]);
        return view('admin.inbox.messages', ['items' => ContactMessage::latest()->paginate(20)]);
    }
}
