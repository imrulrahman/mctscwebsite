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
        if ($request->filled('project')) {
            $query->where('project_name', $request->input('project'));
        }
        if ($request->filled('course')) {
            $query->where('course', $request->input('course'));
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('tracking_no', 'like', "%{$q}%")
                    ->orWhere('trainee_name_en', 'like', "%{$q}%")
                    ->orWhere('trainee_name_bn', 'like', "%{$q}%")
                    ->orWhere('contact_no', 'like', "%{$q}%")
                    ->orWhere('ident_no', 'like', "%{$q}%")
                    ->orWhere('project_name', 'like', "%{$q}%");
            });
        }

        $items = $query->paginate(20)->withQueryString();
        $courses = AdmissionApplication::select('course')->distinct()->pluck('course');
        $projects = SiteContent::where('section', 'projects')->where('status', 'published')->orderBy('sort_order')->get();

        return view('admin.inbox.applications', compact('items', 'courses', 'projects'));
    }

    public function createApplication(): View
    {
        abort_unless(auth()->user()?->can('admissions.create'), 403, 'Unauthorized. Permission to add student applications required.');

        $courses = SiteContent::where('section', 'courses')->where('status', 'published')->orderBy('sort_order')->get();
        $projects = SiteContent::where('section', 'projects')->where('status', 'published')->orderBy('sort_order')->get();
        $application = new AdmissionApplication();

        return view('admin.inbox.application_create', compact('courses', 'projects', 'application'));
    }

    public function storeApplication(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('admissions.create'), 403, 'Unauthorized. Permission to add student applications required.');

        $validated = $request->validate([
            'project_name' => ['nullable', 'string', 'max:150'],
            'course_fee' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_trx_id' => ['nullable', 'string', 'max:100'],
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

        $projectName = trim($request->input('project_name') ?: 'Paid Batch');
        $baseFee = (float)($request->input('course_fee') ?? 6000.00);
        $discount = max(0, (float)($request->input('discount') ?? 0));
        $payable = max(0, $baseFee - $discount);
        $paid = (float)($request->input('paid_amount') ?? 0);

        $validated['project_name'] = $projectName;
        $validated['course_fee'] = $baseFee;
        $validated['discount'] = $discount;
        $validated['payable_amount'] = $payable;
        $validated['paid_amount'] = $paid;

        if ($payable <= 0) {
            $validated['payment_status'] = 'free';
        } elseif ($paid >= $payable) {
            $validated['payment_status'] = 'paid';
        } else {
            $validated['payment_status'] = 'unpaid';
        }

        if ($validated['status'] === 'accepted') {
            $validated['approved_at'] = now();
            $validated['approved_by'] = auth()->id();
        }

        $app = AdmissionApplication::create($validated);

        return redirect()->route('admin.applications.show', $app)->with('success', 'Student application saved successfully. Tracking ID: ' . $app->tracking_no);
    }

    public function approveApplication(Request $request, AdmissionApplication $application): RedirectResponse
    {
        abort_unless(auth()->user()?->can('admissions.edit'), 403, 'Unauthorized. Permission to edit application required.');

        $projectName = trim($request->input('project_name') ?: ($application->project_name ?: 'Paid Batch'));
        $baseFee = $request->filled('course_fee') ? (float)$request->input('course_fee') : (float)($application->course_fee ?: 6000);
        $discount = max(0, (float)($request->input('discount') ?? $application->discount ?? 0));
        $payable = max(0, $baseFee - $discount);
        $paid = max(0, (float)($request->input('paid_amount') ?? $application->paid_amount ?? 0));

        $isFree = in_array(strtoupper($projectName), ['NHRDF', 'ASSET']) || $payable <= 0;

        $application->project_name = $projectName;
        $application->course_fee = $baseFee;
        $application->discount = $discount;
        $application->payable_amount = $payable;
        $application->paid_amount = $isFree ? 0 : $paid;
        $application->payment_method = $request->input('payment_method') ?: ($application->payment_method ?: 'Cash');
        $application->payment_trx_id = $request->input('payment_trx_id') ?: $application->payment_trx_id;

        if ($isFree) {
            $application->payment_status = 'free';
        } elseif ($paid >= $payable && $payable > 0) {
            $application->payment_status = 'paid';
        } else {
            $application->payment_status = ($paid > 0) ? 'partial' : 'unpaid';
        }

        $application->status = 'accepted';
        $application->approved_at = now();
        $application->approved_by = auth()->id();
        $application->save();

        return back()->with('success', 'আবেদনটি সফলভাবে অনুমোদিত (Approved) হয়েছে এবং প্রজেক্ট ও ফি নির্ধারিত হয়েছে! এখন অফিসিয়াল ভর্তি ফরম ও ভাউচার প্রিন্ট করা যাবে।');
    }

    public function recordPayment(Request $request, AdmissionApplication $application): RedirectResponse
    {
        abort_unless(auth()->user()?->can('admissions.edit'), 403, 'Unauthorized. Permission to edit application required.');

        $validated = $request->validate([
            'project_name' => ['nullable', 'string', 'max:150'],
            'course_fee' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'payment_trx_id' => ['nullable', 'string', 'max:100'],
        ]);

        if ($request->filled('project_name')) {
            $application->project_name = trim($request->input('project_name'));
        }
        if ($request->filled('course_fee')) {
            $application->course_fee = (float)$request->input('course_fee');
        }

        if ($request->has('discount')) {
            $discount = max(0, (float)$request->input('discount'));
            $application->discount = $discount;
            $application->payable_amount = max(0, (float)$application->course_fee - $discount);
        }

        $paid = (float)$validated['paid_amount'];
        $application->paid_amount = $paid;
        $application->payment_method = $validated['payment_method'] ?? $application->payment_method;
        $application->payment_trx_id = $validated['payment_trx_id'] ?? $application->payment_trx_id;

        if ($application->isFreeProject()) {
            $application->payment_status = 'free';
        } elseif ($paid >= $application->payable_amount) {
            $application->payment_status = 'paid';
        } else {
            $application->payment_status = 'unpaid';
        }

        $application->save();

        return back()->with('success', 'প্রজেক্ট, পেমেন্ট ও ফি তথ্য সফলভাবে আপডেট হয়েছে!');
    }

    public function showApplication(AdmissionApplication $application): View
    {
        abort_unless(auth()->user()?->can('admissions.view'), 403, 'Unauthorized access to admission applications.');

        $projects = SiteContent::where('section', 'projects')->where('status', 'published')->orderBy('sort_order')->get();

        return view('admin.inbox.application_show', compact('application', 'projects'));
    }

    public function serveFile(AdmissionApplication $application, string $type)
    {
        abort_unless(auth()->user()?->can('admissions.view'), 403, 'Unauthorized access to application files.');

        $relative = match ($type) {
            'photo' => $application->photo,
            'signature' => $application->signature_photo,
            'doc' => $application->ident_doc,
            default => null,
        };

        if (!$relative) {
            abort(404, 'File not attached to this application record.');
        }

        $cleanPath = ltrim(preg_replace('#^storage/#', '', $relative), '/');
        $candidates = [
            storage_path('app/public/' . $cleanPath),
            public_path('storage/' . $cleanPath),
            base_path('storage/app/public/' . $cleanPath),
            '/home/mctscedu/public_html/storage/' . $cleanPath,
            '/home/mctscedu/storage/app/public/' . $cleanPath,
            storage_path('app/' . $cleanPath),
        ];

        foreach ($candidates as $filePath) {
            if (file_exists($filePath) && !is_dir($filePath)) {
                $mime = @mime_content_type($filePath) ?: 'application/octet-stream';
                return response()->file($filePath, [
                    'Content-Type' => $mime,
                    'Cache-Control' => 'no-cache, private',
                ]);
            }
        }

        abort(404, 'File not found on storage disk.');
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
