<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\Occupation;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Models\StudentImportBatch;
use App\Services\StudentIdService;
use App\Services\StudentImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentManagementController extends Controller
{
    private function checkPermission(string $permission = 'students.view'): void
    {
        $user = auth()->user();
        abort_unless($user && ($user->isSystemAdmin() || $user->can($permission)), 403, 'Unauthorized access to Student Management.');
    }

    /**
     * Dashboard Overview (Matches Image 1)
     */
    public function dashboard(Request $request): View
    {
        $this->checkPermission('students.view');

        // All academic years available in database
        $academicYears = Student::select('academic_year')
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year')
            ->toArray();

        // Selected academic year (defaults to 'all' as requested)
        $selectedYear = $request->get('year', 'all');
        if (empty($selectedYear)) {
            $selectedYear = 'all';
        }

        // Base query for stats
        $baseQuery = Student::query();
        if ($selectedYear !== 'all' && !empty($selectedYear)) {
            $baseQuery->academicYear($selectedYear);
        }

        // 5 Top Statistics Cards
        $totalStudents = (clone $baseQuery)->count();
        $maleStudents = (clone $baseQuery)->where('gender', 'Male')->count();
        $femaleStudents = (clone $baseQuery)->where('gender', 'Female')->count();
        $ethnicStudents = (clone $baseQuery)->where('ethnic', 'Yes')->count();
        $pwdStudents = (clone $baseQuery)->where('pwd', 'Yes')->count();

        // Module Navigation Counts
        $allStudentsCount = Student::count();
        $pendingAdmissionsCount = AdmissionApplication::where('status', 'new')->count();
        $certificatesIssuedCount = Student::where('certificate_status', 'Issued')->count();
        $placementsCount = Student::whereIn('job_status', ['Employed', 'Self Employed'])->count();

        return view('admin.students.dashboard', compact(
            'academicYears',
            'selectedYear',
            'totalStudents',
            'maleStudents',
            'femaleStudents',
            'ethnicStudents',
            'pwdStudents',
            'allStudentsCount',
            'pendingAdmissionsCount',
            'certificatesIssuedCount',
            'placementsCount'
        ));
    }

    /**
     * Student Database Table (Matches Image 2)
     */
    public function database(Request $request): View
    {
        $this->checkPermission('students.view');

        $query = Student::query();

        // Basic Filters
        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }
        if ($request->filled('batch') && $request->get('batch') !== 'all') {
            $query->batch($request->get('batch'));
        }
        if ($request->filled('project') && $request->get('project') !== 'all') {
            $query->donor($request->get('project'));
        }
        if ($request->filled('year') && $request->get('year') !== 'all') {
            $query->academicYear($request->get('year'));
        }

        // Advanced Filters
        if ($request->filled('occupation') && $request->get('occupation') !== 'all') {
            $query->occupation($request->get('occupation'));
        }
        if ($request->filled('session') && $request->get('session') !== 'all') {
            $query->where('session', $request->get('session'));
        }
        if ($request->filled('gender') && $request->get('gender') !== 'all') {
            $query->gender($request->get('gender'));
        }
        if ($request->filled('shift') && $request->get('shift') !== 'all') {
            $query->where('shift', $request->get('shift'));
        }
        if ($request->filled('course_type') && $request->get('course_type') !== 'all') {
            $query->where('course_type', $request->get('course_type'));
        }
        if ($request->filled('training_status') && $request->get('training_status') !== 'all') {
            $query->trainingStatus($request->get('training_status'));
        }
        if ($request->filled('job_status') && $request->get('job_status') !== 'all') {
            $query->jobStatus($request->get('job_status'));
        }
        if ($request->filled('certificate_status') && $request->get('certificate_status') !== 'all') {
            $query->certificateStatus($request->get('certificate_status'));
        }
        if ($request->filled('category') && $request->get('category') !== 'all') {
            $query->category($request->get('category'));
        }
        if ($request->filled('religion') && $request->get('religion') !== 'all') {
            $query->where('religion', $request->get('religion'));
        }
        if ($request->filled('marital_status') && $request->get('marital_status') !== 'all') {
            $query->where('marital_status', $request->get('marital_status'));
        }

        // Compact stats matching active filters
        $statsQuery = clone $query;
        $compactStats = [
            'total' => (clone $statsQuery)->count(),
            'male' => (clone $statsQuery)->where('gender', 'Male')->count(),
            'female' => (clone $statsQuery)->where('gender', 'Female')->count(),
            'ethnic' => (clone $statsQuery)->where('ethnic', 'Yes')->count(),
            'pwd' => (clone $statsQuery)->where('pwd', 'Yes')->count(),
        ];

        // Sorting
        $sortCol = $request->get('sort', 'id');
        $sortDir = strtolower($request->get('direction', 'desc')) === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['id', 'student_id', 'student_name', 'occupation', 'batch_no', 'academic_year', 'gender', 'training_status'];
        if (!in_array($sortCol, $allowedSorts)) {
            $sortCol = 'id';
        }

        $perPage = (int)$request->get('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $students = $query->orderBy($sortCol, $sortDir)->paginate($perPage)->withQueryString();

        // Dropdown values for filters
        $batches = Student::select('batch_no')
            ->whereNotNull('batch_no')
            ->where('batch_no', '!=', '')
            ->distinct()
            ->orderBy('batch_no')
            ->pluck('batch_no')
            ->toArray();

        $projects = Student::select('donor')
            ->whereNotNull('donor')
            ->where('donor', '!=', '')
            ->distinct()
            ->orderBy('donor')
            ->pluck('donor')
            ->toArray();

        $years = Student::select('academic_year')
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year')
            ->toArray();

        $occupations = Occupation::where('status', 'active')->orderBy('name')->pluck('name')->toArray();
        if (empty($occupations)) {
            $occupations = Student::select('occupation')->whereNotNull('occupation')->distinct()->pluck('occupation')->toArray();
        }

        $sessions = ['Session 1', 'Session 2', 'Session 3', 'Session 4'];

        return view('admin.students.database', compact(
            'students',
            'compactStats',
            'batches',
            'projects',
            'years',
            'occupations',
            'sessions',
            'sortCol',
            'sortDir',
            'perPage'
        ));
    }

    /**
     * Add Student Form
     */
    public function create(): View
    {
        $this->checkPermission('students.create');

        $student = new Student();
        $student->academic_year = date('Y');
        $student->session = 'Session 1';
        $student->course_type = 'Short Course';
        $student->training_status = 'Active';
        $student->job_status = 'Not Yet Employed';
        $student->certificate_status = 'Pending';
        $student->date_of_admission = now();

        $occupations = Occupation::where('status', 'active')->orderBy('name')->get();
        $batches = StudentBatch::where('status', 'active')->orderBy('name')->get();
        $years = range(date('Y') + 1, 2018);
        $sessions = ['Session 1', 'Session 2', 'Session 3', 'Session 4'];
        $shifts = ['Morning', 'Day', 'Evening'];
        $courseTypes = ['Short Course', 'RPL', 'Diploma', 'Apprenticeship', 'Custom / Other'];
        $donors = ['IMI', 'ECHO', 'BRAC', 'SOS', 'ASSET', 'Self / General', 'Government'];

        $defaultOccupation = $occupations->first()?->name ?? 'Computer Office Application';
        $previewId = StudentIdService::previewId($defaultOccupation, date('Y'), 'Session 1');

        return view('admin.students.form', compact(
            'student',
            'occupations',
            'batches',
            'years',
            'sessions',
            'shifts',
            'courseTypes',
            'donors',
            'previewId'
        ));
    }

    /**
     * Store new student
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkPermission('students.create');

        $validated = $request->validate([
            'student_name' => ['required', 'string', 'max:180'],
            'student_name_bn' => ['nullable', 'string', 'max:180'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'occupation' => ['required', 'string', 'max:180'],
            'academic_year' => ['required', 'string', 'max:20'],
            'session' => ['required', 'string', 'max:50'],
            'date_of_admission' => ['nullable', 'date'],
            'course_type' => ['nullable', 'string', 'max:100'],
            'section' => ['nullable', 'string', 'max:100'],
            'rpl_course' => ['nullable', 'string', 'max:180'],
            'batch_no' => ['nullable', 'string', 'max:50'],
            'shift' => ['nullable', 'string', 'max:30'],
            'donor' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'alt_phone' => ['nullable', 'string', 'max:50'],
            'parent_phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:120'],
            'dob' => ['nullable', 'date'],
            'nid_birth_cert' => ['nullable', 'string', 'max:60'],
            'educational_qualification' => ['nullable', 'string', 'max:150'],
            'father_name' => ['nullable', 'string', 'max:150'],
            'mother_name' => ['nullable', 'string', 'max:150'],
            'siblings' => ['nullable', 'string', 'max:50'],
            'marital_status' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'present_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'religion' => ['nullable', 'string', 'max:50'],
            'ethnic' => ['nullable', 'string', 'in:Yes,No'],
            'pwd' => ['nullable', 'string', 'in:Yes,No'],
            'training_status' => ['nullable', 'string', 'max:50'],
            'viva_mark' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'theory_mark' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'practical_mark' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'total_marks' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'grade' => ['nullable', 'string', 'max:20'],
            'nsda_bteb_result' => ['nullable', 'string', 'max:100'],
            'certificate_status' => ['nullable', 'string', 'max:50'],
            'job_status' => ['nullable', 'string', 'max:50'],
            'company_name' => ['nullable', 'string', 'max:180'],
            'company_type' => ['nullable', 'string', 'max:50'],
            'employer_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string'],
            'designation' => ['nullable', 'string', 'max:120'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'joining_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        // Calculate Age if DOB is given
        if (!empty($validated['dob'])) {
            $validated['age'] = \Carbon\Carbon::parse($validated['dob'])->age;
        }

        // Calculate Total Marks and Grade if components provided
        if (isset($validated['viva_mark']) || isset($validated['theory_mark']) || isset($validated['practical_mark'])) {
            $v = (float)($validated['viva_mark'] ?? 0);
            $t = (float)($validated['theory_mark'] ?? 0);
            $p = (float)($validated['practical_mark'] ?? 0);
            $validated['total_marks'] = $v + $t + $p;

            if (empty($validated['grade'])) {
                if ($validated['total_marks'] >= 80) $validated['grade'] = 'A+';
                elseif ($validated['total_marks'] >= 70) $validated['grade'] = 'A';
                elseif ($validated['total_marks'] >= 60) $validated['grade'] = 'B';
                elseif ($validated['total_marks'] >= 50) $validated['grade'] = 'C';
                elseif ($validated['total_marks'] >= 40) $validated['grade'] = 'D';
                else $validated['grade'] = 'Fail';
            }
        }

        // Session number extraction
        $sNum = 1;
        if (preg_match('/(\d)/', $validated['session'], $m)) {
            $sNum = (int)$m[1];
        }
        $validated['session_number'] = $sNum;

        // Photo upload handling
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students/photos', 'public');
            $validated['photo'] = 'storage/' . $path;
        }

        // Server-side Student ID Generation (Strict Excel Logic)
        $validated['student_id'] = StudentIdService::generateId(
            $validated['occupation'],
            $validated['academic_year'],
            $validated['session'],
            $validated['section'] ?? null
        );

        $validated['occupation_code'] = StudentIdService::resolveOccupationCode(
            $validated['occupation'],
            $validated['section'] ?? null
        );

        $validated['ethnic'] = $validated['ethnic'] ?? 'No';
        $validated['pwd'] = $validated['pwd'] ?? 'No';
        $validated['training_status'] = $validated['training_status'] ?? 'Active';
        $validated['job_status'] = $validated['job_status'] ?? 'Not Yet Employed';
        $validated['certificate_status'] = $validated['certificate_status'] ?? 'Pending';
        $validated['created_by'] = auth()->id();

        $student = Student::create($validated);

        // Record batch if not existing
        if (!empty($student->batch_no)) {
            StudentBatch::firstOrCreate(
                ['name' => $student->batch_no, 'academic_year' => $student->academic_year],
                ['status' => 'active']
            );
        }

        return redirect()->route('admin.students.show', $student)
            ->with('success', "Student record created successfully! Generated Student ID: {$student->student_id}");
    }

    /**
     * Show Student Profile (7 Tabs)
     */
    public function show(Student $student): View
    {
        $this->checkPermission('students.view');
        return view('admin.students.show', compact('student'));
    }

    /**
     * Edit Student Form
     */
    public function edit(Student $student): View
    {
        $this->checkPermission('students.edit');

        $occupations = Occupation::where('status', 'active')->orderBy('name')->get();
        $batches = StudentBatch::where('status', 'active')->orderBy('name')->get();
        $years = range(date('Y') + 1, 2018);
        $sessions = ['Session 1', 'Session 2', 'Session 3', 'Session 4'];
        $shifts = ['Morning', 'Day', 'Evening'];
        $courseTypes = ['Short Course', 'RPL', 'Diploma', 'Apprenticeship', 'Custom / Other'];
        $donors = ['IMI', 'ECHO', 'BRAC', 'SOS', 'ASSET', 'Self / General', 'Government'];

        $previewId = $student->student_id;

        return view('admin.students.form', compact(
            'student',
            'occupations',
            'batches',
            'years',
            'sessions',
            'shifts',
            'courseTypes',
            'donors',
            'previewId'
        ));
    }

    /**
     * Update Student
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $this->checkPermission('students.edit');

        $validated = $request->validate([
            'student_name' => ['required', 'string', 'max:180'],
            'student_name_bn' => ['nullable', 'string', 'max:180'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'occupation' => ['required', 'string', 'max:180'],
            'academic_year' => ['required', 'string', 'max:20'],
            'session' => ['required', 'string', 'max:50'],
            'date_of_admission' => ['nullable', 'date'],
            'course_type' => ['nullable', 'string', 'max:100'],
            'section' => ['nullable', 'string', 'max:100'],
            'rpl_course' => ['nullable', 'string', 'max:180'],
            'batch_no' => ['nullable', 'string', 'max:50'],
            'shift' => ['nullable', 'string', 'max:30'],
            'donor' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'alt_phone' => ['nullable', 'string', 'max:50'],
            'parent_phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:120'],
            'dob' => ['nullable', 'date'],
            'nid_birth_cert' => ['nullable', 'string', 'max:60'],
            'educational_qualification' => ['nullable', 'string', 'max:150'],
            'father_name' => ['nullable', 'string', 'max:150'],
            'mother_name' => ['nullable', 'string', 'max:150'],
            'siblings' => ['nullable', 'string', 'max:50'],
            'marital_status' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'present_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'religion' => ['nullable', 'string', 'max:50'],
            'ethnic' => ['nullable', 'string', 'in:Yes,No'],
            'pwd' => ['nullable', 'string', 'in:Yes,No'],
            'training_status' => ['nullable', 'string', 'max:50'],
            'viva_mark' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'theory_mark' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'practical_mark' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'total_marks' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'grade' => ['nullable', 'string', 'max:20'],
            'nsda_bteb_result' => ['nullable', 'string', 'max:100'],
            'certificate_status' => ['nullable', 'string', 'max:50'],
            'certificate_no' => ['nullable', 'string', 'max:100'],
            'certificate_issue_date' => ['nullable', 'date'],
            'job_status' => ['nullable', 'string', 'max:50'],
            'company_name' => ['nullable', 'string', 'max:180'],
            'company_type' => ['nullable', 'string', 'max:50'],
            'employer_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string'],
            'designation' => ['nullable', 'string', 'max:120'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'joining_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        if (!empty($validated['dob'])) {
            $validated['age'] = \Carbon\Carbon::parse($validated['dob'])->age;
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students/photos', 'public');
            $validated['photo'] = 'storage/' . $path;
        }

        $student->update($validated);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student record updated successfully.');
    }

    /**
     * Delete / Archive Student
     */
    public function destroy(Student $student): RedirectResponse
    {
        $this->checkPermission('students.delete');
        $id = $student->student_id;
        $name = $student->student_name;
        $student->delete();

        return redirect()->route('admin.students.database')
            ->with('success', "Student record [{$id} - {$name}] deleted successfully.");
    }

    /**
     * Preview candidate Student ID via AJAX
     */
    public function previewId(Request $request): JsonResponse
    {
        $occupation = $request->get('occupation');
        $year = $request->get('academic_year', date('Y'));
        $session = $request->get('session', 'Session 1');
        $section = $request->get('section');

        $candidateId = StudentIdService::previewId($occupation, $year, $session, $section);
        $code = StudentIdService::resolveOccupationCode($occupation, $section);

        return response()->json([
            'success' => true,
            'candidate_id' => $candidateId,
            'occupation_code' => $code,
        ]);
    }

    /**
     * Admission Management Module
     */
    public function admission(Request $request): View
    {
        $this->checkPermission('students.view');

        $pendingApplications = AdmissionApplication::where('status', 'new')->latest()->paginate(15);
        $totalApplications = AdmissionApplication::count();
        $approvedApplications = AdmissionApplication::whereIn('status', ['accepted', 'approved'])->count();

        // Count of enrolled students who came from admissions
        $enrolledStudentsCount = Student::whereNotNull('admission_id')->count();

        return view('admin.students.admission', compact(
            'pendingApplications',
            'totalApplications',
            'approvedApplications',
            'enrolledStudentsCount'
        ));
    }

    /**
     * Certificate Management Module
     * Card view with "Issue Certificate" and "Issued Certificates"
     */
    public function certificates(Request $request): View
    {
        $this->checkPermission('students.certificates');

        $viewMode = $request->get('view', 'cards'); // 'cards', 'issue', 'issued'

        // Key metrics
        $totalIssued = Student::whereIn('certificate_status', ['Issued', 'Printed', 'Distributed'])->count();
        $totalPrinted = Student::where('certificate_status', 'Printed')->count();
        $totalDistributed = Student::where('certificate_status', 'Distributed')->count();
        $totalNotIssued = Student::where(function ($q) {
            $q->whereNull('certificate_status')
              ->orWhereIn('certificate_status', ['Not Issued', 'Pending', 'not eligible']);
        })->count();

        $batches = Student::select('batch_no')
            ->whereNotNull('batch_no')
            ->where('batch_no', '!=', '')
            ->distinct()
            ->orderBy('batch_no')
            ->pluck('batch_no')
            ->toArray();

        $years = Student::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year')
            ->toArray();

        // 1. Issue Certificate Search Mode
        $searchResults = collect([]);
        $hasSearched = false;
        if ($viewMode === 'issue') {
            if ($request->filled('q') || $request->filled('batch')) {
                $hasSearched = true;
                $searchQuery = Student::query();

                if ($request->filled('q')) {
                    $searchQuery->search($request->get('q'));
                }
                if ($request->filled('batch') && $request->get('batch') !== 'all') {
                    $searchQuery->batch($request->get('batch'));
                }

                $searchResults = $searchQuery->orderBy('student_id', 'asc')->paginate(20)->withQueryString();
            }
        }

        // 2. Issued Certificates List Mode
        $issuedStudents = collect([]);
        if ($viewMode === 'issued') {
            $issuedQuery = Student::whereIn('certificate_status', ['Issued', 'Printed', 'Distributed']);

            if ($request->filled('status') && $request->get('status') !== 'all') {
                $issuedQuery->where('certificate_status', $request->get('status'));
            }
            if ($request->filled('batch') && $request->get('batch') !== 'all') {
                $issuedQuery->batch($request->get('batch'));
            }
            if ($request->filled('year') && $request->get('year') !== 'all') {
                $issuedQuery->academicYear($request->get('year'));
            }
            if ($request->filled('search')) {
                $issuedQuery->search($request->get('search'));
            }

            $issuedStudents = $issuedQuery->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();
        }

        return view('admin.students.certificates', compact(
            'viewMode',
            'totalIssued',
            'totalPrinted',
            'totalDistributed',
            'totalNotIssued',
            'batches',
            'years',
            'searchResults',
            'hasSearched',
            'issuedStudents'
        ));
    }

    /**
     * Issue Certificate for a student
     */
    public function issueCertificate(Request $request, Student $student): RedirectResponse
    {
        $this->checkPermission('students.certificates');

        $issueDate = $request->filled('issue_date') ? $request->get('issue_date') : now()->toDateString();
        $certNo = $request->filled('certificate_no')
            ? $request->get('certificate_no')
            : ($student->certificate_no ?: ('MCTSC-' . date('Y') . '-' . $student->student_id));

        $student->update([
            'certificate_status' => 'Issued',
            'certificate_no' => $certNo,
            'certificate_issue_date' => $issueDate,
        ]);

        return back()->with('success', "Certificate issued successfully for {$student->student_name} ({$student->student_id})!");
    }

    /**
     * Re-Issue Certificate for a student
     */
    public function reissueCertificate(Request $request, Student $student): RedirectResponse
    {
        $this->checkPermission('students.certificates');

        $reissueCount = (int)($student->certificate_reissue_count ?: 0) + 1;
        $issueDate = now()->toDateString();

        $student->update([
            'certificate_status' => 'Issued',
            'certificate_issue_date' => $issueDate,
            'certificate_reissued_at' => now(),
            'certificate_reissue_count' => $reissueCount,
        ]);

        return back()->with('success', "Certificate re-issued successfully for {$student->student_name} ({$student->student_id})! (Re-issue #{$reissueCount})");
    }

    /**
     * Update Certificate Status (Issued, Printed, Distributed, Not Issued)
     */
    public function updateCertificateStatus(Request $request, Student $student): RedirectResponse
    {
        $this->checkPermission('students.certificates');

        $newStatus = $request->validate([
            'status' => ['required', 'string', 'in:Not Issued,Issued,Printed,Distributed'],
        ])['status'];

        $updates = ['certificate_status' => $newStatus];

        if ($newStatus === 'Printed') {
            $updates['certificate_printed_at'] = now();
        } elseif ($newStatus === 'Distributed') {
            $updates['certificate_distributed_at'] = now();
        } elseif ($newStatus === 'Issued') {
            if (!$student->certificate_issue_date) {
                $updates['certificate_issue_date'] = now()->toDateString();
            }
        } elseif ($newStatus === 'Not Issued') {
            $updates['certificate_issue_date'] = null;
            $updates['certificate_printed_at'] = null;
            $updates['certificate_distributed_at'] = null;
        }

        $student->update($updates);

        return back()->with('success', "Certificate status for {$student->student_id} updated to '{$newStatus}'.");
    }

    /**
     * Print Official Certificate matching Certificate.xlsm
     */
    public function printCertificate(Request $request, Student $student): View
    {
        $this->checkPermission('students.certificates');

        $withSign = $request->boolean('with_sign', true);
        $sessionDates = $student->computed_session_dates;

        return view('admin.students.certificate_print', compact(
            'student',
            'withSign',
            'sessionDates'
        ));
    }

    /**
     * Job Placement Module
     */
    public function jobPlacement(Request $request): View
    {
        $this->checkPermission('students.placements');

        $query = Student::query();

        if ($request->filled('job_status') && $request->get('job_status') !== 'all') {
            $query->where('job_status', $request->get('job_status'));
        } else {
            // By default, show employed & self employed first
            $query->whereIn('job_status', ['Employed', 'Self Employed']);
        }

        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }
        if ($request->filled('year') && $request->get('year') !== 'all') {
            $query->academicYear($request->get('year'));
        }

        $students = $query->orderBy('joining_date', 'desc')->paginate(20)->withQueryString();

        $totalEmployed = Student::where('job_status', 'Employed')->count();
        $totalSelfEmployed = Student::where('job_status', 'Self Employed')->count();
        $totalSeeking = Student::where('job_status', 'Seeking')->orWhere('job_status', 'Not Yet Employed')->count();
        $avgSalary = Student::where('salary', '>', 0)->avg('salary');

        $years = Student::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year')->toArray();

        return view('admin.students.job_placement', compact(
            'students',
            'totalEmployed',
            'totalSelfEmployed',
            'totalSeeking',
            'avgSalary',
            'years'
        ));
    }

    /**
     * Batch Roster Print Layout (Matches Image 3)
     */
    public function printRoster(Request $request): View
    {
        $this->checkPermission('students.export');

        $query = Student::query();

        $program = $request->get('occupation') ?: $request->get('course');
        if (!empty($program) && $program !== 'all') {
            $query->occupation($program);
        }

        $batch = $request->get('batch');
        if (!empty($batch) && $batch !== 'all') {
            $query->batch($batch);
        }

        $project = $request->get('project');
        if (!empty($project) && $project !== 'all') {
            $query->donor($project);
        }

        $academicYear = $request->get('year');
        if (!empty($academicYear) && $academicYear !== 'all') {
            $query->academicYear($academicYear);
        }

        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }

        $students = $query->orderBy('student_id', 'asc')->get();

        // Summary counts for the header box
        $summary = [
            'total' => $students->count(),
            'male' => $students->where('gender', 'Male')->count(),
            'female' => $students->where('gender', 'Female')->count(),
            'ethnic' => $students->where('ethnic', 'Yes')->count(),
            'pwd' => $students->where('pwd', 'Yes')->count(),
        ];

        return view('admin.students.print', compact(
            'students',
            'summary',
            'program',
            'batch',
            'project',
            'academicYear'
        ));
    }

    /**
     * Export to CSV / Excel matching current filters
     */
    public function export(Request $request): StreamedResponse
    {
        $this->checkPermission('students.export');

        $query = Student::query();

        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }
        if ($request->filled('batch') && $request->get('batch') !== 'all') {
            $query->batch($request->get('batch'));
        }
        if ($request->filled('project') && $request->get('project') !== 'all') {
            $query->donor($request->get('project'));
        }
        if ($request->filled('year') && $request->get('year') !== 'all') {
            $query->academicYear($request->get('year'));
        }
        if ($request->filled('occupation') && $request->get('occupation') !== 'all') {
            $query->occupation($request->get('occupation'));
        }
        if ($request->filled('gender') && $request->get('gender') !== 'all') {
            $query->gender($request->get('gender'));
        }
        if ($request->filled('category') && $request->get('category') !== 'all') {
            $query->category($request->get('category'));
        }
        if ($request->filled('training_status') && $request->get('training_status') !== 'all') {
            $query->trainingStatus($request->get('training_status'));
        }
        if ($request->filled('job_status') && $request->get('job_status') !== 'all') {
            $query->jobStatus($request->get('job_status'));
        }

        $fileName = 'MCTSC_Student_Database_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens Bengali and English text properly
            fputs($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, [
                'ID No',
                'Student Name',
                'Gender',
                'Date of Admission',
                'Course Type',
                'Section',
                'Occupation',
                'Academic Year',
                'Session',
                'Batch No',
                'Shift',
                'Donor',
                'Phone',
                'DOB',
                'Age',
                'NID/Birth Cert',
                'Father Name',
                'Mother Name',
                'Address',
                'Ethnic',
                'PWD',
                'Religion',
                'Training Status',
                'Total Marks',
                'Grade',
                'Job Status',
                'Company Name',
                'Designation',
                'Salary'
            ]);

            $query->chunk(250, function ($students) use ($handle) {
                foreach ($students as $s) {
                    fputcsv($handle, [
                        $s->student_id,
                        $s->student_name,
                        $s->gender,
                        $s->date_of_admission ? $s->date_of_admission->format('Y-m-d') : '',
                        $s->course_type,
                        $s->section,
                        $s->occupation,
                        $s->academic_year,
                        $s->session,
                        $s->batch_no,
                        $s->shift,
                        $s->donor,
                        $s->phone,
                        $s->dob ? $s->dob->format('Y-m-d') : '',
                        $s->age,
                        $s->nid_birth_cert,
                        $s->father_name,
                        $s->mother_name,
                        $s->address,
                        $s->ethnic,
                        $s->pwd,
                        $s->religion,
                        $s->training_status,
                        $s->total_marks,
                        $s->grade,
                        $s->job_status,
                        $s->company_name,
                        $s->designation,
                        $s->salary,
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Import Page
     */
    public function importForm(): View
    {
        $this->checkPermission('students.import');
        $importBatches = StudentImportBatch::latest()->paginate(10);
        return view('admin.students.import', compact('importBatches'));
    }

    /**
     * Preview uploaded file before importing
     */
    public function importPreview(Request $request): View|RedirectResponse
    {
        $this->checkPermission('students.import');

        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        if (!$request->hasFile('excel_file')) {
            $postMax = ini_get('post_max_size') ?: 'N/A';
            $uploadMax = ini_get('upload_max_filesize') ?: 'N/A';
            return back()->with('error', "No file received by the server. If you selected a file, it may exceed server upload limits (upload_max_filesize: {$uploadMax}, post_max_size: {$postMax}). Please choose a file smaller than {$uploadMax} or check your server configuration.");
        }

        $file = $request->file('excel_file');
        if (!$file->isValid()) {
            return back()->with('error', 'File upload error: ' . $file->getErrorMessage());
        }

        $originalName = $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension() ?: pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['xlsx', 'xlsm', 'xls', 'csv', 'txt'])) {
            return back()->with('error', "The selected file '{$originalName}' has an unsupported extension (." . ($ext ?: 'unknown') . "). Please upload an Excel (.xlsx, .xlsm) or CSV file.");
        }

        $fileName = 'import_' . time() . '_' . uniqid() . '.' . $ext;

        $tempPath = null;
        $fullPath = null;
        try {
            $tempPath = $file->storeAs('temp_imports', $fileName, 'local');
            $fullPath = Storage::disk('local')->path($tempPath);
        } catch (\Throwable $e) {}

        if (!$fullPath || !file_exists($fullPath)) {
            $fullPath = $file->getRealPath();
        }

        $rawRows = [];
        try {
            $rawRows = StudentImportService::parseFile($fullPath);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('parseFile primary path failed: ' . $e->getMessage());
        }

        if (empty($rawRows) && $file->getRealPath() && $file->getRealPath() !== $fullPath) {
            try {
                $rawRows = StudentImportService::parseFile($file->getRealPath());
            } catch (\Throwable $e) {}
        }

        if (empty($rawRows)) {
            if ($tempPath) {
                Storage::disk('local')->delete($tempPath);
            }
            return back()->with('error', 'Unable to parse records from "' . htmlspecialchars($originalName) . '". Please ensure it is a valid Excel (.xlsx, .xlsm) or CSV file containing student records.');
        }

        // Cache rows for 2 hours so execution never fails even if temp file is purged
        $importToken = 'import_' . time() . '_' . uniqid();
        \Illuminate\Support\Facades\Cache::put($importToken, [
            'rows' => $rawRows,
            'file_name' => $originalName,
            'temp_path' => $tempPath,
        ], 7200);

        try {
            $analysis = StudentImportService::previewAnalysis($rawRows);
        } catch (\Throwable $e) {
            return back()->with('error', 'Error analyzing records: ' . $e->getMessage());
        }

        $importBatches = StudentImportBatch::latest()->paginate(10);

        return view('admin.students.import', compact(
            'analysis',
            'importToken',
            'tempPath',
            'originalName',
            'importBatches'
        ));
    }

    /**
     * Confirm and execute import
     */
    public function importProcess(Request $request): RedirectResponse
    {
        $this->checkPermission('students.import');

        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $importToken = $request->input('import_token');
        $tempPath = $request->input('temp_path');
        $originalName = $request->input('original_name', 'students_import.xlsx');

        $rawRows = [];

        // 1. Retrieve from cache first (fastest and immune to disk permission issues)
        if ($importToken && \Illuminate\Support\Facades\Cache::has($importToken)) {
            $cached = \Illuminate\Support\Facades\Cache::get($importToken);
            $rawRows = $cached['rows'] ?? [];
            $originalName = $cached['file_name'] ?? $originalName;
            \Illuminate\Support\Facades\Cache::forget($importToken);
        }

        // 2. Fallback to temp file if cache expired
        if (empty($rawRows) && $tempPath) {
            $fullPath = Storage::disk('local')->path($tempPath);
            if (!file_exists($fullPath)) {
                $fullPath = storage_path('app/' . $tempPath);
            }
            if (file_exists($fullPath)) {
                $rawRows = StudentImportService::parseFile($fullPath);
            }
        }

        if (empty($rawRows)) {
            return redirect()->route('admin.students.import')
                ->with('error', 'Temporary import session expired or file not found. Please re-upload the file.');
        }

        $batch = StudentImportService::executeImport($rawRows, $originalName, auth()->id());

        if ($tempPath) {
            Storage::disk('local')->delete($tempPath);
        }

        return redirect()->route('admin.students.import')
            ->with('success', "Import completed! Successfully imported {$batch->successful_rows} student records. (Skipped duplicates: {$batch->duplicate_rows})");
    }

    /**
     * Clean invalid / bogus header records from student database
     */
    public function cleanBogusRecords(): RedirectResponse
    {
        $this->checkPermission('students.delete');

        $bogusIds = [
            'Contact No (Parents)', 'Address', 'Siblings', 'Mother\'s Name',
            'Father\'s Name', 'Family Information', 'MR No', 'Student Name', 'ID No',
            'Designation', 'Company Name', 'Remarks', 'Religion', 'Gender', 'Ethnic', 'PWD'
        ];

        $deleted = Student::whereIn('student_id', $bogusIds)
            ->orWhere('student_name', 'ST')
            ->delete();

        return redirect()->route('admin.students.database')
            ->with('success', "Cleaned up {$deleted} invalid header records from database.");
    }
}
