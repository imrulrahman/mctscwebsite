<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\ContactMessage;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InboxController extends Controller
{
    private function mirrorToPublic(string $path): void
    {
        try {
            $src = storage_path('app/public/' . $path);
            $localDest = public_path('storage/' . $path);
            $cpanelDest = '/home/mctscedu/public_html/storage/' . $path;

            foreach ([$localDest, $cpanelDest] as $dest) {
                $dir = dirname($dest);
                if (!file_exists($dir)) {
                    @mkdir($dir, 0755, true);
                }
                if (file_exists($src)) {
                    @copy($src, $dest);
                }
            }
        } catch (\Throwable $e) {
            // Ignore mirror copy issues
        }
    }

    public function applications(Request $request): View
    {
        abort_unless(auth()->user()?->can('admissions.view'), 403, 'Unauthorized access to admission applications.');

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

    public function createApplication(): View
    {
        abort_unless(auth()->user()?->can('admissions.create'), 403, 'Unauthorized. Permission to add student applications required.');

        $courses = SiteContent::where('section', 'courses')->where('status', 'published')->orderBy('sort_order')->get();
        $application = new AdmissionApplication();

        return view('admin.inbox.application_create', compact('courses', 'application'));
    }

    public function storeApplication(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('admissions.create'), 403, 'Unauthorized. Permission to add student applications required.');

        $validated = $request->validate([
            'course' => ['required', 'string', 'max:150'],
            'preferred_shift' => ['nullable', 'string', 'max:30'],
            'trainee_name_en' => ['required', 'string', 'max:150'],
            'trainee_name_bn' => ['nullable', 'string', 'max:150'],
            'father_name_en' => ['nullable', 'string', 'max:150'],
            'mother_name_en' => ['nullable', 'string', 'max:150'],
            'contact_no' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:190'],
            'ident_type' => ['nullable', 'string', 'max:50'],
            'ident_no' => ['nullable', 'string', 'max:60'],
            'dob' => ['nullable', 'date'],
            'sex' => ['nullable', 'string', 'in:Male,Female,Other'],
            'education_level' => ['nullable', 'string', 'max:100'],
            'perm_address' => ['nullable', 'string', 'max:500'],
            'pres_address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:new,reviewing,accepted,rejected'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('trainees/photos', 'public');
            $this->mirrorToPublic($validated['photo']);
        }

        $validated['name'] = $validated['trainee_name_en'];
        $validated['phone'] = $validated['contact_no'];

        $app = AdmissionApplication::create($validated);

        return redirect()->route('admin.applications.show', $app)->with('success', 'Student application saved successfully. Tracking ID: ' . $app->tracking_no);
    }

    public function showApplication(AdmissionApplication $application): View
    {
        abort_unless(auth()->user()?->can('admissions.view'), 403, 'Unauthorized access to admission applications.');

        return view('admin.inbox.application_show', compact('application'));
    }

    public function printApplication(AdmissionApplication $application): View
    {
        abort_unless(auth()->user()?->can('admissions.print'), 403, 'Unauthorized. Permission to print application required.');

        return view('admin.inbox.application_print', compact('application'));
    }

    public function updateApplication(Request $request, AdmissionApplication $application): RedirectResponse
    {
        abort_unless(auth()->user()?->can('admissions.edit'), 403, 'Unauthorized. Permission to edit application required.');

        $validated = $request->validate([
            'status' => ['nullable', 'in:new,reviewing,accepted,rejected'],
            'course' => ['nullable', 'string', 'max:150'],
            'preferred_shift' => ['nullable', 'string', 'max:30'],
            'trainee_name_en' => ['nullable', 'string', 'max:150'],
            'contact_no' => ['nullable', 'string', 'max:30'],
            'pres_address' => ['nullable', 'string', 'max:500'],
        ]);

        $application->update(array_filter($validated, fn($val) => $val !== null));

        return back()->with('success', 'Application details and status updated successfully.');
    }

    public function destroyApplication(AdmissionApplication $application): RedirectResponse
    {
        abort_unless(auth()->user()?->can('admissions.delete'), 403, 'Unauthorized. Permission to delete applications required.');

        $application->delete();

        return redirect()->route('admin.applications')->with('success', 'Application deleted successfully.');
    }

    public function messages(): View
    {
        abort_unless(auth()->user()?->can('messages.view'), 403, 'Unauthorized access to contact messages.');

        ContactMessage::where('is_read', false)->update(['is_read' => true]);
        return view('admin.inbox.messages', ['items' => ContactMessage::latest()->paginate(20)]);
    }

    public function destroyMessage(ContactMessage $message): RedirectResponse
    {
        abort_unless(auth()->user()?->can('messages.delete'), 403, 'Unauthorized. Permission to delete messages required.');

        $message->delete();

        return redirect()->route('admin.messages')->with('success', 'Message deleted successfully.');
    }
}
