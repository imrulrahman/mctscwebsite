<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobApplicationController extends Controller
{
    private function authorizeAccess(): void
    {
        $user = auth()->user();
        abort_unless($user && ($user->is_admin || $user->isSystemAdmin() || $user->can('career.manage') || $user->can('notices.manage')), 403);
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = JobApplication::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('post')) {
            $query->where('post_name', $request->input('post'));
        }

        if ($request->filled('district')) {
            $district = trim($request->input('district'));
            $query->where(function ($sub) use ($district) {
                $sub->where('present_address->district', $district)
                    ->orWhere('permanent_address->district', $district)
                    ->orWhere('present_address->district', 'like', "%{$district}%");
            });
        }

        if ($request->filled('education')) {
            $edu = strtolower(trim($request->input('education')));
            $query->where(function ($sub) use ($edu) {
                if ($edu === 'masters') {
                    $sub->whereNotNull('education->masters->exam')->where('education->masters->exam', '!=', '');
                } elseif ($edu === 'graduation') {
                    $sub->whereNotNull('education->graduation->exam')->where('education->graduation->exam', '!=', '');
                } elseif ($edu === 'diploma' || $edu === 'hsc') {
                    $sub->whereNotNull('education->hsc->exam')->where('education->hsc->exam', '!=', '');
                } elseif ($edu === 'ssc') {
                    $sub->whereNotNull('education->ssc->exam')->where('education->ssc->exam', '!=', '');
                }
            });
        }

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('tracking_no', 'like', "%{$q}%")
                    ->orWhere('applicant_name', 'like', "%{$q}%")
                    ->orWhere('post_name', 'like', "%{$q}%")
                    ->orWhere('mobile_no', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nid_no', 'like', "%{$q}%")
                    ->orWhere('birth_reg_no', 'like', "%{$q}%")
                    ->orWhere('present_address->district', 'like', "%{$q}%");
            });
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $this->authorizeAccess();

        $query = $this->buildFilteredQuery($request);
        $applications = $query->paginate(100)->withQueryString();

        // Get unique post / occupation names
        $posts = JobApplication::select('post_name')
            ->distinct()
            ->whereNotNull('post_name')
            ->pluck('post_name')
            ->merge(SiteContent::where('section', 'career')->pluck('title'))
            ->unique()
            ->filter()
            ->sort()
            ->values();

        // Collect all distinct districts from actual applications + standard Bangladesh districts
        $districtsInDb = JobApplication::all()
            ->map(fn($app) => $app->present_address['district'] ?? null)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $standardDistricts = [
            'Chattogram', 'Dhaka', 'Cumilla', 'Cox\'s Bazar', 'Feni', 'Noakhali', 'Brahmanbaria',
            'Chandpur', 'Lakshmipur', 'Gazipur', 'Narayanganj', 'Tangail', 'Kishoreganj', 'Manikganj',
            'Munshiganj', 'Narsingdi', 'Faridpur', 'Gopalganj', 'Madaripur', 'Rajbari', 'Shariatpur',
            'Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur', 'Sylhet', 'Moulvibazar', 'Habiganj',
            'Sunamganj', 'Rajshahi', 'Bogura', 'Joypurhat', 'Naogaon', 'Natore', 'Pabna', 'Sirajganj',
            'Rangpur', 'Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari', 'Panchagarh',
            'Thakurgaon', 'Khulna', 'Bagerhat', 'Chuadanga', 'Jashore', 'Jhenaidah', 'Kushtia',
            'Magura', 'Meherpur', 'Narail', 'Satkhira', 'Barishal', 'Barguna', 'Bhola', 'Jhalokathi',
            'Patuakhali', 'Pirojpur', 'Bandarban', 'Khagrachhari', 'Rangamati'
        ];

        $districts = collect(array_unique(array_merge($districtsInDb, $standardDistricts)))->sort()->values();

        return view('admin.job_applications.index', compact('applications', 'posts', 'districts'));
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorizeAccess();

        $query = $this->buildFilteredQuery($request);

        $filterLabels = [];
        if ($request->filled('post')) $filterLabels[] = preg_replace('/[^a-zA-Z0-9_-]/', '_', $request->post);
        if ($request->filled('district')) $filterLabels[] = preg_replace('/[^a-zA-Z0-9_-]/', '_', $request->district);
        if ($request->filled('status')) $filterLabels[] = $request->status;
        $tag = !empty($filterLabels) ? implode('_', $filterLabels) : 'All';
        $filename = 'MCTSC_Job_Applications_' . $tag . '_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Microsoft Excel correctly displays Bengali and English characters
            fwrite($handle, "\xEF\xBB\xBF");

            // CSV Header with comprehensive applicant columns
            fputcsv($handle, [
                'SL',
                'Tracking No',
                'Applied Post / Occupation',
                'Applicant Name',
                'Father Name',
                'Mother Name',
                'Date of Birth',
                'Age (Years)',
                'Gender',
                'Nationality',
                'Religion',
                'Marital Status',
                'Spouse Name',
                'NID No',
                'Birth Registration No',
                'Passport No',
                'Quota',
                'Departmental Candidate',
                'Mobile No',
                'Email',
                'Present District',
                'Present Upazila',
                'Present Post Office',
                'Present Post Code',
                'Present Village / Road',
                'Present Care Of',
                'Present Full Address',
                'Permanent District',
                'Permanent Upazila',
                'Permanent Full Address',
                'Highest Qualification Level',
                'SSC Exam',
                'SSC Board',
                'SSC Group',
                'SSC Passing Year',
                'SSC Result / GPA',
                'HSC / Diploma Exam',
                'HSC / Diploma Board / Inst.',
                'HSC / Diploma Group',
                'HSC / Diploma Passing Year',
                'HSC / Diploma Result',
                'Graduation Exam / Degree',
                'Graduation Subject / Major',
                'Graduation University',
                'Graduation Passing Year',
                'Graduation Result / CGPA',
                'Masters Exam / Degree',
                'Masters Subject / Major',
                'Masters University',
                'Masters Passing Year',
                'Masters Result / CGPA',
                'All Educational Summary',
                'Other Qualifications & Skills',
                'Experiences Count',
                'Experiences Summary',
                'Application Status',
                'Admin Notes',
                'Applied Date & Time',
                'CV File Link',
            ]);

            $sl = 1;
            $query->chunk(200, function ($applications) use ($handle, &$sl) {
                foreach ($applications as $app) {
                    $pres = is_array($app->present_address) ? $app->present_address : [];
                    $perm = is_array($app->permanent_address) ? $app->permanent_address : [];
                    $edu = is_array($app->education) ? $app->education : [];

                    $ssc = $edu['ssc'] ?? [];
                    $hsc = $edu['hsc'] ?? [];
                    $grad = $edu['graduation'] ?? [];
                    $mas = $edu['masters'] ?? [];

                    // Determine Highest Qualification Level
                    $highestLevel = 'Other';
                    if (!empty($mas['exam'])) {
                        $highestLevel = 'Masters';
                    } elseif (!empty($grad['exam'])) {
                        $highestLevel = 'Graduation / Bachelor';
                    } elseif (!empty($hsc['exam'])) {
                        $highestLevel = str_contains(strtolower((string)$hsc['exam']), 'diploma') ? 'Diploma' : 'HSC / Equivalent';
                    } elseif (!empty($ssc['exam'])) {
                        $highestLevel = 'SSC / Equivalent';
                    }

                    // Build educational summary string
                    $eduParts = [];
                    if (!empty($ssc['exam'])) {
                        $eduParts[] = "SSC: {$ssc['exam']} (" . ($ssc['board'] ?? '') . ', ' . ($ssc['group'] ?? '') . ', Pass: ' . ($ssc['passing_year'] ?? '') . ', GPA: ' . ($ssc['result'] ?? '') . ')';
                    }
                    if (!empty($hsc['exam'])) {
                        $eduParts[] = "HSC: {$hsc['exam']} (" . ($hsc['board'] ?? '') . ', ' . ($hsc['group'] ?? '') . ', Pass: ' . ($hsc['passing_year'] ?? '') . ', Result: ' . ($hsc['result'] ?? '') . ')';
                    }
                    if (!empty($grad['exam'])) {
                        $eduParts[] = "Grad: {$grad['exam']} (" . ($grad['group'] ?? '') . ', ' . ($grad['board'] ?? '') . ', Pass: ' . ($grad['passing_year'] ?? '') . ', CGPA: ' . ($grad['result'] ?? '') . ')';
                    }
                    if (!empty($mas['exam'])) {
                        $eduParts[] = "Masters: {$mas['exam']} (" . ($mas['group'] ?? '') . ', ' . ($mas['board'] ?? '') . ', Pass: ' . ($mas['passing_year'] ?? '') . ', CGPA: ' . ($mas['result'] ?? '') . ')';
                    }
                    $allEduSummary = implode('; ', $eduParts);

                    // Build experience summary string
                    $expList = [];
                    $expCount = 0;
                    if (is_array($app->experiences)) {
                        foreach ($app->experiences as $exp) {
                            if (!empty($exp['designation']) || !empty($exp['organization'])) {
                                $expCount++;
                                $desig = $exp['designation'] ?? '';
                                $org = $exp['organization'] ?? '';
                                $from = $exp['from'] ?? '';
                                $to = $exp['to'] ?? '';
                                $expList[] = "{$desig} at {$org} ({$from} to {$to})";
                            }
                        }
                    }
                    $expSummary = implode('; ', $expList);

                    $presFull = implode(', ', array_filter([
                        $pres['care_of'] ?? '',
                        $pres['village'] ?? '',
                        ($pres['post_office'] ?? '') . (!empty($pres['post_code']) ? '-' . $pres['post_code'] : ''),
                        $pres['upazila'] ?? '',
                        $pres['district'] ?? '',
                    ]));

                    $permFull = ($perm['same_as_present'] ?? false) ? 'Same as Present' : implode(', ', array_filter([
                        $perm['care_of'] ?? '',
                        $perm['village'] ?? '',
                        ($perm['post_office'] ?? '') . (!empty($perm['post_code']) ? '-' . $perm['post_code'] : ''),
                        $perm['upazila'] ?? '',
                        $perm['district'] ?? '',
                    ]));

                    $permDistrict = ($perm['same_as_present'] ?? false) ? ($pres['district'] ?? '') : ($perm['district'] ?? '');
                    $permUpazila = ($perm['same_as_present'] ?? false) ? ($pres['upazila'] ?? '') : ($perm['upazila'] ?? '');

                    $cvUrl = $app->cv_path ? url('storage/' . $app->cv_path) : 'No CV Uploaded';

                    fputcsv($handle, [
                        $sl++,
                        '="' . $app->tracking_no . '"',
                        $app->post_name,
                        $app->applicant_name,
                        $app->father_name,
                        $app->mother_name,
                        $app->dob ? $app->dob->format('Y-m-d') : '',
                        $app->dob ? $app->dob->age : '',
                        $app->gender,
                        $app->nationality,
                        $app->religion,
                        $app->marital_status,
                        $app->spouse_name ?: '',
                        $app->nid_no ? '="' . $app->nid_no . '"' : '',
                        $app->birth_reg_no ? '="' . $app->birth_reg_no . '"' : '',
                        $app->passport_no ? '="' . $app->passport_no . '"' : '',
                        $app->quota ?: 'Non-Quota',
                        $app->departmental_candidate ?: 'No',
                        '="' . $app->mobile_no . '"',
                        $app->email,
                        $pres['district'] ?? '',
                        $pres['upazila'] ?? '',
                        $pres['post_office'] ?? '',
                        $pres['post_code'] ? '="' . $pres['post_code'] . '"' : '',
                        $pres['village'] ?? '',
                        $pres['care_of'] ?? '',
                        $presFull,
                        $permDistrict,
                        $permUpazila,
                        $permFull,
                        $highestLevel,
                        $ssc['exam'] ?? '',
                        $ssc['board'] ?? '',
                        $ssc['group'] ?? '',
                        $ssc['passing_year'] ?? '',
                        $ssc['result'] ?? '',
                        $hsc['exam'] ?? '',
                        $hsc['board'] ?? '',
                        $hsc['group'] ?? '',
                        $hsc['passing_year'] ?? '',
                        $hsc['result'] ?? '',
                        $grad['exam'] ?? '',
                        $grad['group'] ?? '',
                        $grad['board'] ?? '',
                        $grad['passing_year'] ?? '',
                        $grad['result'] ?? '',
                        $mas['exam'] ?? '',
                        $mas['group'] ?? '',
                        $mas['board'] ?? '',
                        $mas['passing_year'] ?? '',
                        $mas['result'] ?? '',
                        $allEduSummary,
                        $app->other_qualifications ?: '',
                        $expCount,
                        $expSummary,
                        strtoupper($app->status ?: 'new'),
                        $app->admin_notes ?: '',
                        $app->created_at ? $app->created_at->format('Y-m-d H:i') : '',
                        $cvUrl,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    public function downloadCv(JobApplication $jobApplication): \Symfony\Component\HttpFoundation\BinaryFileResponse|RedirectResponse
    {
        $this->authorizeAccess();

        if (empty($jobApplication->cv_path)) {
            return back()->with('error', 'No CV file attached to this application.');
        }

        $candidates = [
            storage_path('app/public/' . $jobApplication->cv_path),
            public_path('storage/' . $jobApplication->cv_path),
            '/home/mctscedu/public_html/storage/' . $jobApplication->cv_path,
        ];

        $filePath = null;
        foreach ($candidates as $candidate) {
            if (file_exists($candidate) && !is_dir($candidate)) {
                $filePath = $candidate;
                break;
            }
        }

        if (!$filePath && Storage::disk('public')->exists($jobApplication->cv_path)) {
            $filePath = Storage::disk('public')->path($jobApplication->cv_path);
        }

        if (!$filePath || !file_exists($filePath)) {
            abort(404, 'CV file not found on server.');
        }

        $filename = $jobApplication->getDownloadCvFilename();

        return response()->download($filePath, $filename, [
            'Content-Type' => mime_content_type($filePath) ?: 'application/pdf',
        ]);
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
