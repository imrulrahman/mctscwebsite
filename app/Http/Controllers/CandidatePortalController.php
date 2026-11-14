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
