<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\SiteSetting;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CandidatePortalController extends Controller
{
    private function getSettings(): \Illuminate\Support\Collection
    {
        return SiteSetting::query()->pluck('value', 'key');
    }

    public function showLogin(): View|RedirectResponse
    {
        if (session('candidate_id')) {
            return redirect()->route('candidate.dashboard');
        }

        $settings = $this->getSettings();
        return view('site.candidate.login', compact('settings'));
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tracking_no' => ['required', 'string', 'max:50'],
            'mobile_no' => ['required', 'string', 'max:30'],
        ], [
            'tracking_no.required' => 'আবেদনের ট্র্যাকিং নম্বরটি লিখুন (Tracking ID is required)',
            'mobile_no.required' => 'আবেদনে ব্যবহৃত মোবাইল নম্বরটি লিখুন (Mobile number is required)',
        ]);

        $trackingNo = trim(strtoupper($validated['tracking_no']));
        $mobileInput = trim($validated['mobile_no']);
        $cleanPhone = preg_replace('/[^0-9]/', '', $mobileInput);

        // Find matching job application
        $application = JobApplication::where('tracking_no', $trackingNo)
            ->where(function ($query) use ($mobileInput, $cleanPhone) {
                $query->where('mobile_no', $mobileInput)
                    ->orWhereRaw("REPLACE(REPLACE(REPLACE(mobile_no, '+88', ''), '-', ''), ' ', '') LIKE ?", ['%' . substr($cleanPhone, -10) . '%']);
            })
            ->first();

        if (!$application) {
            return back()->withInput()->withErrors([
                'login_error' => 'প্রদত্ত ট্র্যাকিং নম্বর বা মোবাইল নম্বরের সাথে কোনো আবেদনের তথ্য মেলেনি। অনুগ্রহ করে সঠিক তথ্য প্রদান করুন।',
            ]);
        }

        // Set session
        session([
            'candidate_id' => $application->id,
            'candidate_tracking_no' => $application->tracking_no,
            'candidate_mobile' => $application->mobile_no,
            'candidate_email' => $application->email,
            'candidate_name' => $application->applicant_name,
        ]);

        return redirect()->route('candidate.dashboard')->with('success', 'স্বাগতম, ' . $application->applicant_name . '! আপনার ড্যাশবোর্ডে সফলভাবে লগইন হয়েছে।');
    }

    public function dashboard(): View|RedirectResponse
    {
        if (!session('candidate_id')) {
            return redirect()->route('candidate.login')->with('info', 'প্রার্থীর ড্যাশবোর্ডে প্রবেশ করতে অনুগ্রহ করে লগইন করুন।');
        }

        $candidateId = session('candidate_id');
        $mobile = session('candidate_mobile');
        $email = session('candidate_email');
        $trackingNo = session('candidate_tracking_no');

        $primaryApp = JobApplication::find($candidateId);
        if (!$primaryApp) {
            session()->forget(['candidate_id', 'candidate_tracking_no', 'candidate_mobile', 'candidate_email', 'candidate_name']);
            return redirect()->route('candidate.login');
        }

        // Get all applications of this candidate
        $cleanPhone = preg_replace('/[^0-9]/', '', $mobile);
        $applications = JobApplication::where('id', $candidateId)
            ->orWhere('tracking_no', $trackingNo)
            ->orWhere(function ($query) use ($mobile, $cleanPhone, $email) {
                if ($cleanPhone) {
                    $query->whereRaw("REPLACE(REPLACE(REPLACE(mobile_no, '+88', ''), '-', ''), ' ', '') LIKE ?", ['%' . substr($cleanPhone, -10) . '%']);
                }
                if ($email) {
                    $query->orWhere('email', $email);
                }
            })
            ->with('jobCircular')
            ->latest()
            ->get();

        $settings = $this->getSettings();
        return view('site.candidate.dashboard', compact('settings', 'primaryApp', 'applications'));
    }

    public function checkStatus(Request $request): \Illuminate\Http\JsonResponse|RedirectResponse
    {
        $trackingNo = trim(strtoupper($request->input('tracking_no', '')));

        if (empty($trackingNo)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'অনুগ্রহ করে ট্র্যাকিং আইডি লিখুন (Please enter tracking ID).',
                ], 422);
            }
            return redirect()->route('career')->with('tracking_error', 'অনুগ্রহ করে ট্র্যাকিং আইডি লিখুন।');
        }

        $app = JobApplication::where('tracking_no', $trackingNo)->first();

        if (!$app) {
            $msg = "প্রদত্ত ট্র্যাকিং নম্বর '{$trackingNo}' এর কোনো আবেদন পাওয়া যায়নি। অনুগ্রহ করে সঠিক ট্র্যাকিং নম্বর দিয়ে চেষ্টা করুন।";
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 404);
            }
            return redirect()->route('career')->with('tracking_error', $msg)->with('searched_tracking_no', $trackingNo);
        }

        $status = strtolower($app->status ?: 'new');
        $statusLabel = match($status) {
            'shortlisted' => 'Shortlisted (বাছাইকৃত)',
            'interview' => 'Interview Scheduled (সাক্ষাৎকার আহ্বান)',
            'selected', 'approved' => 'Selected / Approved (চূড়ান্ত নির্বাচিত)',
            'rejected' => 'Not Selected (নির্বাচিত হননি)',
            default => 'Application Submitted',
        };

        $result = [
            'success' => true,
            'tracking_no' => $app->tracking_no,
            'applicant_name' => $app->applicant_name,
            'post_name' => $app->post_name,
            'created_at' => $app->created_at->format('d M, Y h:i A'),
            'status' => $status,
            'status_label' => $statusLabel,
            'admin_notes' => $app->admin_notes,
            'confirmation_url' => route('career.apply.confirmation', $app->tracking_no),
            'portal_url' => route('candidate.login'),
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result);
        }

        return redirect()->route('career')->with('tracking_result', $result)->with('searched_tracking_no', $trackingNo);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        if (!session('candidate_id')) {
            return redirect()->route('candidate.login');
        }

        $appId = $request->input('application_id') ?: session('candidate_id');
        $application = JobApplication::findOrFail($appId);

        // Security check
        $mobile = session('candidate_mobile');
        $email = session('candidate_email');
        $cleanPhone = preg_replace('/[^0-9]/', '', $mobile);
        $appCleanPhone = preg_replace('/[^0-9]/', '', $application->mobile_no);

        if ($application->id !== session('candidate_id') && substr($cleanPhone, -10) !== substr($appCleanPhone, -10) && $application->email !== $email) {
            abort(403, 'অনুমতি নেই।');
        }

        $validated = $request->validate([
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
            'permanent_care_of' => ['nullable', 'string', 'max:200'],
            'permanent_village' => ['nullable', 'string', 'max:255'],
            'permanent_district' => ['nullable', 'string', 'max:100'],
            'permanent_upazila' => ['nullable', 'string', 'max:100'],
            'permanent_post_office' => ['nullable', 'string', 'max:100'],
            'permanent_post_code' => ['nullable', 'string', 'max:20'],

            // Contact
            'mobile_no' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:200'],

            'other_qualifications' => ['nullable', 'string', 'max:2000'],

            // Optional new files
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:1024'],
            'signature' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:512'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

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

        $updateData = [
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
            'other_qualifications' => $validated['other_qualifications'] ?? null,
        ];

        // Replace photo if uploaded
        if ($request->hasFile('photo')) {
            if ($application->photo_path && !str_starts_with($application->photo_path, 'http')) {
                Storage::disk('public')->delete($application->photo_path);
                $oldPub = public_path('storage/' . $application->photo_path);
                if (file_exists($oldPub)) @unlink($oldPub);
            }
            $newPhoto = $request->file('photo')->store('career-applications/photos', 'public');
            $this->mirrorToPublic($newPhoto);
            $updateData['photo_path'] = $newPhoto;
        }

        // Replace signature if uploaded
        if ($request->hasFile('signature')) {
            if ($application->signature_path && !str_starts_with($application->signature_path, 'http')) {
                Storage::disk('public')->delete($application->signature_path);
                $oldPub = public_path('storage/' . $application->signature_path);
                if (file_exists($oldPub)) @unlink($oldPub);
            }
            $newSig = $request->file('signature')->store('career-applications/signatures', 'public');
            $this->mirrorToPublic($newSig);
            $updateData['signature_path'] = $newSig;
        }

        // Replace CV if uploaded
        if ($request->hasFile('cv_file')) {
            if ($application->cv_path && !str_starts_with($application->cv_path, 'http')) {
                Storage::disk('public')->delete($application->cv_path);
                $oldPub = public_path('storage/' . $application->cv_path);
                if (file_exists($oldPub)) @unlink($oldPub);
            }
            $newCv = $request->file('cv_file')->store('career-applications/cvs', 'public');
            $this->mirrorToPublic($newCv);
            $updateData['cv_path'] = $newCv;
        }

        $application->update($updateData);

        // Update session
        session([
            'candidate_name' => $application->applicant_name,
            'candidate_mobile' => $application->mobile_no,
            'candidate_email' => $application->email,
        ]);

        ActivityLogger::log(
            'update_profile',
            'Career Applications',
            "প্রার্থী {$application->applicant_name} ({$application->tracking_no}) তার প্রোফাইলের তথ্য আপডেট করেছেন।"
        );

        return redirect()->route('candidate.dashboard')->with('success', 'আপনার আবেদন ও প্রোফাইলের তথ্য সফলভাবে হালনাগাদ (Updated) করা হয়েছে!');
    }

    public function updateCv(Request $request): RedirectResponse
    {
        if (!session('candidate_id')) {
            return redirect()->route('candidate.login');
        }

        $request->validate([
            'application_id' => ['nullable', 'integer', 'exists:job_applications,id'],
            'cv_file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ], [
            'cv_file.required' => 'অনুগ্রহ করে নতুন সিভি ফাইল নির্বাচন করুন (PDF, DOC, DOCX)।',
            'cv_file.mimes' => 'সিভি ফাইলটি অবশ্যই PDF, DOC বা DOCX ফরম্যাটে হতে হবে।',
            'cv_file.max' => 'ফাইলের সর্বোচ্চ আকার ৫ মেগাবাইট (5MB)।',
        ]);

        $appId = $request->input('application_id') ?: session('candidate_id');
        $application = JobApplication::findOrFail($appId);

        // Security check
        $mobile = session('candidate_mobile');
        $email = session('candidate_email');
        $cleanPhone = preg_replace('/[^0-9]/', '', $mobile);
        $appCleanPhone = preg_replace('/[^0-9]/', '', $application->mobile_no);

        if ($application->id !== session('candidate_id') && substr($cleanPhone, -10) !== substr($appCleanPhone, -10) && $application->email !== $email) {
            abort(403, 'অনুমতি নেই।');
        }

        // Delete old CV if exists
        if ($application->cv_path && !str_starts_with($application->cv_path, 'http')) {
            Storage::disk('public')->delete($application->cv_path);
            $oldPub = public_path('storage/' . $application->cv_path);
            if (file_exists($oldPub)) @unlink($oldPub);
        }

        $newCvPath = $request->file('cv_file')->store('career-applications/cvs', 'public');
        $this->mirrorToPublic($newCvPath);

        // Update CV for this application, and optionally across other matching applications of this candidate
        $application->update(['cv_path' => $newCvPath]);

        // Also update other active applications of same user
        JobApplication::where('mobile_no', $application->mobile_no)
            ->where('id', '!=', $application->id)
            ->update(['cv_path' => $newCvPath]);

        ActivityLogger::log(
            'update_cv',
            'Career Applications',
            "প্রার্থী {$application->applicant_name} ({$application->tracking_no}) পোর্টাল থেকে নতুন সিভি আপলোড করেছেন।"
        );

        return back()->with('success', 'আপনার সিভি সফলভাবে আপডেট ও সংরক্ষিত হয়েছে! সংশ্লিষ্ট কর্তৃপক্ষ আপনার হালনাগাদ সিভিটি দেখতে পাবেন।');
    }

    public function logout(): RedirectResponse
    {
        session()->forget(['candidate_id', 'candidate_tracking_no', 'candidate_mobile', 'candidate_email', 'candidate_name']);
        return redirect()->route('career')->with('success', 'আপনি সফলভাবে লগআউট হয়েছেন।');
    }

    private function mirrorToPublic(string $path): void
    {
        $source = storage_path('app/public/' . $path);
        if (!file_exists($source)) {
            return;
        }
        $targets = [
            public_path('storage/' . $path),
            '/home/mctscedu/public_html/storage/' . $path,
        ];
        foreach ($targets as $target) {
            $dir = dirname($target);
            if (!is_dir($dir) && is_dir(dirname($dir))) {
                @mkdir($dir, 0775, true);
            }
            if (is_dir($dir) && !is_link(dirname($dir))) {
                @copy($source, $target);
            }
        }
    }
}
