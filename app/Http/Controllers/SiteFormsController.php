<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplication;
use App\Models\ContactMessage;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteFormsController extends Controller
{
    public function showApplyForm(Request $request): View
    {
        $courses = SiteContent::where('section', 'courses')
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->get();

        $projects = SiteContent::where('section', 'projects')
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->get();

        $selectedCourse = $request->query('course', '');

        return view('apply', compact('courses', 'projects', 'selectedCourse'));
    }

    public function apply(Request $request): RedirectResponse
    {
        if ($request->filled('name') && !$request->filled('trainee_name_en')) {
            $request->merge(['trainee_name_en' => $request->input('name')]);
        }
        if ($request->filled('phone') && !$request->filled('contact_no')) {
            $request->merge(['contact_no' => $request->input('phone')]);
        }

        $reqFields = \App\Models\SiteSetting::getRequiredAdmissionFields();
        $isReq = fn($f) => in_array($f, $reqFields);

        $validated = $request->validate([
            // Project & Course selection
            'project_name' => ['nullable', 'string', 'max:150'],
            'course_fee' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'course' => ['required', 'string', 'max:150'],
            'preferred_shift' => ['nullable', 'string', 'max:30'],

            // Personal Data
            'trainee_name_en' => ['required', 'string', 'max:150'],
            'name' => ['nullable', 'string', 'max:150'],
            'trainee_name_bn' => [$isReq('trainee_name_bn') ? 'required' : 'nullable', 'string', 'max:150'],
            'dob' => [$isReq('dob') ? 'required' : 'nullable', 'date'],
            'ident_type' => [$isReq('ident_type') ? 'required' : 'nullable', 'string', 'max:50'],
            'ident_no' => [$isReq('ident_no') ? 'required' : 'nullable', 'string', 'max:60'],
            'sex' => [$isReq('sex') ? 'required' : 'nullable', 'string', 'in:Male,Female,Other'],
            'pwd' => [$isReq('pwd') ? 'required' : 'nullable', 'string', 'in:Yes,No'],
            'contact_no' => [$isReq('contact_no') ? 'required' : 'nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => [$isReq('email') ? 'required' : 'nullable', 'email', 'max:190'],
            'marital_status' => [$isReq('marital_status') ? 'required' : 'nullable', 'string', 'max:30'],

            // File uploads (max 3MB each)
            'photo' => [$isReq('photo') ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'ident_doc' => [$isReq('ident_doc') ? 'required' : 'nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:3072'],
            'signature_photo' => [$isReq('signature_photo') ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],

            // Permanent Address
            'perm_division' => [$isReq('perm_division') ? 'required' : 'nullable', 'string', 'max:60'],
            'perm_district' => [$isReq('perm_district') ? 'required' : 'nullable', 'string', 'max:60'],
            'perm_upazilla' => [$isReq('perm_upazilla') ? 'required' : 'nullable', 'string', 'max:80'],
            'perm_address' => [$isReq('perm_address') ? 'required' : 'nullable', 'string', 'max:500'],
            'perm_area_type' => [$isReq('perm_area_type') ? 'required' : 'nullable', 'string', 'in:Rural,Urban'],

            // Present Address
            'pres_division' => [$isReq('pres_division') ? 'required' : 'nullable', 'string', 'max:60'],
            'pres_district' => [$isReq('pres_district') ? 'required' : 'nullable', 'string', 'max:60'],
            'pres_upazilla' => [$isReq('pres_upazilla') ? 'required' : 'nullable', 'string', 'max:80'],
            'pres_address' => [$isReq('pres_address') ? 'required' : 'nullable', 'string', 'max:500'],
            'religion' => [$isReq('religion') ? 'required' : 'nullable', 'string', 'max:40'],
            'ethnicity' => [$isReq('ethnicity') ? 'required' : 'nullable', 'string', 'max:40'],
            'education_level' => [$isReq('education_level') ? 'required' : 'nullable', 'string', 'max:100'],
            'education' => ['nullable', 'string', 'max:100'],

            // Household Profile
            'father_name_en' => [$isReq('father_name_en') ? 'required' : 'nullable', 'string', 'max:150'],
            'father_name_bn' => [$isReq('father_name_bn') ? 'required' : 'nullable', 'string', 'max:150'],
            'mother_name_en' => [$isReq('mother_name_en') ? 'required' : 'nullable', 'string', 'max:150'],
            'mother_name_bn' => [$isReq('mother_name_bn') ? 'required' : 'nullable', 'string', 'max:150'],
            'emergency_contact' => [$isReq('emergency_contact') ? 'required' : 'nullable', 'string', 'max:30'],
            'household_members' => [$isReq('household_members') ? 'required' : 'nullable', 'integer', 'min:1', 'max:30'],
            'income_source' => [$isReq('income_source') ? 'required' : 'nullable', 'string', 'max:150'],
            'monthly_income' => [$isReq('monthly_income') ? 'required' : 'nullable', 'numeric', 'min:0'],
            'yearly_income' => ['nullable', 'numeric', 'min:0'],
            'daily_income_per_person' => ['nullable', 'numeric', 'min:0'],
            'has_bank_account' => [$isReq('has_bank_account') ? 'required' : 'nullable', 'string', 'in:Yes,No'],

            // Skills & Experiences
            'past_training' => [$isReq('past_training') ? 'required' : 'nullable', 'string', 'in:Yes,No'],
            'employment_status' => [$isReq('employment_status') ? 'required' : 'nullable', 'string', 'max:60'],
            'monthly_cash_income' => [$isReq('monthly_cash_income') ? 'required' : 'nullable', 'numeric', 'min:0'],
            'training_media_source' => [$isReq('training_media_source') ? 'required' : 'nullable', 'string', 'max:100'],
        ]);

        // Upload files
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('trainees/photos', 'public');
            $this->mirrorToPublic($validated['photo']);
        }
        if ($request->hasFile('ident_doc')) {
            $validated['ident_doc'] = $request->file('ident_doc')->store('trainees/docs', 'public');
            $this->mirrorToPublic($validated['ident_doc']);
        }
        if ($request->hasFile('signature_photo')) {
            $validated['signature_photo'] = $request->file('signature_photo')->store('trainees/signatures', 'public');
            $this->mirrorToPublic($validated['signature_photo']);
        }

        // Normalize names and contacts for backwards and forward compatibility
        $validated['trainee_name_en'] = $validated['trainee_name_en'] ?? $request->input('name') ?? 'Applicant';
        $validated['name'] = $validated['trainee_name_en'];
        $validated['trainee_name_bn'] = $validated['trainee_name_bn'] ?? $validated['trainee_name_en'];
        $validated['contact_no'] = $validated['contact_no'] ?? $request->input('phone') ?? '';
        $validated['phone'] = $validated['contact_no'];
        $validated['education_level'] = $validated['education_level'] ?? $request->input('education') ?? 'SSC / Equivalent';
        $validated['education'] = $validated['education_level'];
        if (isset($validated['monthly_income']) && is_numeric($validated['monthly_income'])) {
            $validated['yearly_income'] = $validated['yearly_income'] ?? round(((float)$validated['monthly_income']) * 12, 2);
        }

        // Project & fee are decided by institute admin during approval
        $validated['project_name'] = null;
        $validated['course_fee'] = 0.00;
        $validated['discount'] = 0.00;
        $validated['payable_amount'] = 0.00;
        $validated['paid_amount'] = 0.00;
        $validated['payment_status'] = 'unpaid';
        $validated['status'] = 'new'; // Pending admin approval

        $application = AdmissionApplication::create($validated);

        return redirect()->route('apply.confirmation', $application->tracking_no)
            ->with('success', 'আপনার আবেদন সফলভাবে দাখিল হয়েছে!');
    }

    public function confirmPayment(Request $request, string $tracking_no): RedirectResponse
    {
        $application = AdmissionApplication::where('tracking_no', $tracking_no)->firstOrFail();

        $validated = $request->validate([
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
            'payment_trx_id' => ['nullable', 'string', 'max:100'],
        ]);

        $paid = (float)$validated['paid_amount'];
        $application->paid_amount = $paid;
        $application->payment_method = $validated['payment_method'];
        $application->payment_trx_id = $validated['payment_trx_id'] ?? null;
        if ($paid >= $application->payable_amount || $application->isFreeProject()) {
            $application->payment_status = 'paid';
        }
        $application->save();

        return redirect()->route('apply.confirmation', $application->tracking_no)
            ->with('success', 'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে! আপনার চূড়ান্ত ভর্তি ফরম ও ভাউচার প্রস্তুত।');
    }

    public function applyConfirmation(string $tracking_no): View
    {
        $application = AdmissionApplication::where('tracking_no', $tracking_no)->firstOrFail();
        return view('apply_confirmation', compact('application'));
    }

    public function contact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:3000']
        ]);
        ContactMessage::create($data);
        return back()->with('success', 'আপনার বার্তা পাঠানো হয়েছে। ধন্যবাদ।');
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
