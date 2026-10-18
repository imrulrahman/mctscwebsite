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
    public function applications(): View
    {
        return view('admin.inbox.applications', ['items' => AdmissionApplication::latest()->paginate(20)]);
    }

    public function updateApplication(Request $request, AdmissionApplication $application): RedirectResponse
    {
        $application->update($request->validate(['status' => ['required', 'in:new,reviewing,accepted,rejected']]));
        return back()->with('success', 'আবেদনের অবস্থা হালনাগাদ হয়েছে।');
    }

    public function messages(): View
    {
        ContactMessage::where('is_read', false)->update(['is_read' => true]);
        return view('admin.inbox.messages', ['items' => ContactMessage::latest()->paginate(20)]);
    }
}
