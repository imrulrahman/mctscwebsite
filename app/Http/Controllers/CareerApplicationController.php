<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\SiteContent;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CareerApplicationController extends Controller
{
    public function showForm(Request $request, ?string $slug = null): View
    {
        $settings = SiteSetting::query()->pluck('value', 'key');
        $circulars = SiteContent::query()
            ->published()
            ->where('section', 'career')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        $jobParam = $slug ?: $request->query('job');
        $selectedJob = null;
        if ($jobParam) {
            $selectedJob = $circulars->firstWhere('slug', $jobParam)
                ?? $circulars->firstWhere('id', (int)$jobParam);
        }

        return view('site.career_apply', compact('settings', 'circulars', 'selectedJob'));
    }

    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_content_id' => ['nullable', 'integer', 'exists:site_contents,id'],
            'post_name' => ['required', 'string', 'max:255'],

            // Personal Info
            'applicant_name' => ['required', 'string', 'max:200'],
            'father_name' => ['required', 'string', 'max:200'],
            'mother_name' => ['required', 'string', 'max:200'],
            'dob' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:Male,Female,Third Gender'],
            'nationality' => ['required', 'string', 'max:60'],
            'religion' => ['required', 'string', 'max:40'],
            'nid_no' => ['nullable', 'string', 'max:60'],
            'birth_reg_no' => ['nullable', 'string', 'max:60'],
            'passport_no' => ['nullable', 'string', 'max:60'],
            'marital_status' => ['required', 'in:Single,Married,Divorced,Widowed'],
            'spouse_name' => ['nullable', 'string', 'max:200'],
            'quota' => ['nullable', 'string', 'max:80'],
            'departmental_candidate' => ['nullable', 'in:Yes,No'],

            // Present Address
            'present_care_of' => ['required', 'string', 'max:200'],
            'present_village' => ['required', 'string', 'max:255'],
            'present_district' => ['required', 'string', 'max:100'],
            'present_upazila' => ['required', 'string', 'max:100'],
            'present_post_office' => ['required', 'string', 'max:100'],
            'present_post_code' => ['required', 'string', 'max:20'],

            // Permanent Address
            'same_as_present' => ['nullable', 'boolean'],
            'permanent_care_of' => ['required_unless:same_as_present,1', 'nullable', 'string', 'max:200'],
            'permanent_village' => ['required_unless:same_as_present,1', 'nullable', 'string', 'max:255'],
            'permanent_district' => ['required_unless:same_as_present,1', 'nullable', 'string', 'max:100'],
            'permanent_upazila' => ['required_unless:same_as_present,1', 'nullable', 'string', 'max:100'],
            'permanent_post_office' => ['required_unless:same_as_present,1', 'nullable', 'string', 'max:100'],
            'permanent_post_code' => ['required_unless:same_as_present,1', 'nullable', 'string', 'max:20'],

            // Contact
            'mobile_no' => ['required', 'string', 'max:20'],
            'confirm_mobile_no' => ['required', 'same:mobile_no'],
            'email' => ['required', 'email', 'max:200'],

            // Education & Experience
            'education' => ['nullable', 'array'],
            'experiences' => ['nullable', 'array'],
            'other_qualifications' => ['nullable', 'string', 'max:2000'],

            // Files
            'photo' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:1024'],
            'signature' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:512'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],

            // Declaration
            'declaration' => ['accepted'],
        ], [
            'confirm_mobile_no.same' => 'Mobile number and Confirm Mobile number must match.',
            'declaration.accepted' => 'You must accept the declaration to submit your application.',
            'photo.required' => 'Please upload your passport-size photograph (300x300 px recommended).',
            'signature.required' => 'Please upload your scanned signature (300x80 px recommended).',
        ]);

        // Handle Addresses
        $presentAddress = [
            'care_of' => $validated['present_care_of'],
            'village' => $validated['present_village'],
            'district' => $validated['present_district'],
            'upazila' => $validated['present_upazila'],
            'post_office' => $validated['present_post_office'],
            'post_code' => $validated['present_post_code'],
        ];

        $sameAsPresent = $request->boolean('same_as_present');
        $permanentAddress = $sameAsPresent ? array_merge($presentAddress, ['same_as_present' => true]) : [
            'same_as_present' => false,
            'care_of' => $validated['permanent_care_of'] ?? '',
            'village' => $validated['permanent_village'] ?? '',
            'district' => $validated['permanent_district'] ?? '',
            'upazila' => $validated['permanent_upazila'] ?? '',
            'post_office' => $validated['permanent_post_office'] ?? '',
            'post_code' => $validated['permanent_post_code'] ?? '',
        ];

        // Handle File Uploads
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('career-applications/photos', 'public');
            $this->mirrorToPublic($photoPath);
        }

        $signaturePath = null;
        if ($request->hasFile('signature')) {
            $signaturePath = $request->file('signature')->store('career-applications/signatures', 'public');
            $this->mirrorToPublic($signaturePath);
        }

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('career-applications/cvs', 'public');
            $this->mirrorToPublic($cvPath);
        }

        // Generate Unique Tracking No
        do {
            $trackingNo = 'JOB-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (JobApplication::where('tracking_no', $trackingNo)->exists());

        // Filter out empty experience rows
        $experiences = collect($request->input('experiences', []))
            ->filter(fn($exp) => !empty($exp['designation']) || !empty($exp['organization']))
            ->values()
            ->all();

        $application = JobApplication::create([
            'tracking_no' => $trackingNo,
            'site_content_id' => $validated['site_content_id'] ?? null,
            'post_name' => $validated['post_name'],
            'applicant_name' => strtoupper($validated['applicant_name']),
            'father_name' => strtoupper($validated['father_name']),
            'mother_name' => strtoupper($validated['mother_name']),
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'nationality' => $validated['nationality'],
            'religion' => $validated['religion'],
            'nid_no' => $validated['nid_no'] ?? null,
            'birth_reg_no' => $validated['birth_reg_no'] ?? null,
            'passport_no' => $validated['passport_no'] ?? null,
            'marital_status' => $validated['marital_status'],
            'spouse_name' => $validated['marital_status'] === 'Married' ? ($validated['spouse_name'] ?? null) : null,
            'quota' => $validated['quota'] ?? 'Non-Quota',
            'departmental_candidate' => $validated['departmental_candidate'] ?? 'No',
            'present_address' => $presentAddress,
            'permanent_address' => $permanentAddress,
            'mobile_no' => $validated['mobile_no'],
            'email' => $validated['email'],
            'education' => $request->input('education', []),
            'experiences' => $experiences,
            'other_qualifications' => $validated['other_qualifications'] ?? null,
            'photo_path' => $photoPath,
            'signature_path' => $signaturePath,
            'cv_path' => $cvPath,
            'status' => 'new',
        ]);

        return redirect()->route('career.apply.confirmation', $application->tracking_no)
            ->with('success', 'আপনার চাকরির আবেদনটি সফলভাবে জমা হয়েছে!');
    }

    public function confirmation(string $tracking_no): View
    {
        $settings = SiteSetting::query()->pluck('value', 'key');
        $application = JobApplication::where('tracking_no', $tracking_no)->firstOrFail();

        return view('site.career_confirmation', compact('settings', 'application'));
    }

    public function sendEmailApplication(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $cvNote = '';
        $cvFullPath = null;
        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('career-applications/email-cvs', 'public');
            $this->mirrorToPublic($cvPath);
            $cvFullPath = storage_path('app/public/' . $cvPath);
            $cvUrl = function_exists('storage_asset') ? storage_asset($cvPath) : asset('storage/' . $cvPath);
            $cvNote = "\n\nAttached CV URL: " . $cvUrl;
        }

        // Save to ContactMessage so Admin always receives it in Inbox
        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'] . $cvNote,
        ]);

        // Also attempt to send email to career@mctsc.edu.bd / configured email
        try {
            $toEmail = SiteSetting::where('key', 'email')->value('value') ?: 'career@mctsc.edu.bd';
            Mail::raw(
                "Applicant Name: {$validated['name']}\n" .
                "Email: {$validated['email']}\n" .
                "Phone: " . ($validated['phone'] ?? 'N/A') . "\n\n" .
                "Message / Cover Letter:\n{$validated['message']}{$cvNote}",
                function ($msg) use ($toEmail, $validated, $cvFullPath) {
                    $msg->to($toEmail)
                        ->replyTo($validated['email'], $validated['name'])
                        ->subject($validated['subject']);
                    if ($cvFullPath && file_exists($cvFullPath)) {
                        $msg->attach($cvFullPath);
                    }
                }
            );
        } catch (\Throwable $e) {
            // Silently ignore mail driver errors on local/unconfigured SMTP
        }

        return back()->with('success', 'আপনার ইমেইল আবেদনটি সফলভাবে পাঠানো হয়েছে!');
    }

    private function mirrorToPublic(string $path): void
    {
        try {
            $source = storage_path('app/public/' . $path);
            if (!file_exists($source)) {
                return;
            }
            $targets = [
                public_path('storage/' . $path),
            ];
            if (is_dir('/home/mctscedu/public_html')) {
                $targets[] = '/home/mctscedu/public_html/storage/' . $path;
            }
            foreach ($targets as $target) {
                $dir = dirname($target);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }
                if (is_dir($dir) && !is_link($dir) && realpath($source) !== realpath($target)) {
                    @copy($source, $target);
                }
            }
        } catch (\Throwable $e) {
            // Ignore mirror errors
        }
    }
}
