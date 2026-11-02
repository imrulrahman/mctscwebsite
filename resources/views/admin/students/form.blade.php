@extends('admin.layout')

@section('title', ($student->exists ? 'Edit Student · ' . $student->student_id : 'Add New Student') . ' · MCTSC Admin')
@section('crumb', 'Student Management / ' . ($student->exists ? 'Edit' : 'Add Student'))

@section('content')
<div class="sm-form-container">
  <div class="sm-form-header">
    <div>
      <h1 class="sm-form-title">{{ $student->exists ? 'Edit Student Record' : 'Add New Student' }}</h1>
      <p class="sm-form-subtitle">{{ $student->exists ? 'Update institutional record for ' . $student->student_id : 'Enter trainee information following the official institutional format' }}</p>
    </div>
    <div style="display:flex;gap:10px;">
      <a href="{{ $student->exists ? route('admin.students.show', $student) : route('admin.students.database') }}" class="sm-btn sm-btn-secondary">
        Cancel
      </a>
    </div>
  </div>

  @if($errors->any())
    <div class="alert danger" style="margin-bottom:20px;">
      <strong>Please check the errors below:</strong>
      <ul style="margin:5px 0 0 16px;padding:0;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ $student->exists ? route('admin.students.update', $student) : route('admin.students.store') }}" enctype="multipart/form-data" class="sm-student-form">
    @csrf
    @if($student->exists)
      @method('PUT')
    @endif

    {{-- SECTION A: TRAINING INFORMATION --}}
    <div class="sm-section-card">
      <div class="sm-section-title">
        <div class="sm-sec-num">A</div>
        <div>
          <h3>Training Information</h3>
          <p>Institutional enrollment, occupation, academic session and batch</p>
        </div>

        {{-- Generated ID Preview Badge --}}
        <div class="sm-id-preview-box">
          <span class="sm-id-badge-label">Generated Student ID:</span>
          <span id="previewIdBadge" class="sm-id-badge">{{ $previewId ?: 'Pending' }}</span>
        </div>
      </div>

      <div class="sm-grid sm-grid-3">
        {{-- Occupation --}}
        <div class="sm-field">
          <label>Occupation / Trade <span class="req">*</span></label>
          <select name="occupation" id="fieldOccupation" class="sm-input" required onchange="refreshIdPreview()">
            @foreach($occupations as $occ)
              <option value="{{ $occ->name }}" {{ old('occupation', $student->occupation) == $occ->name ? 'selected' : '' }}>
                {{ $occ->name }} ({{ $occ->code }})
              </option>
            @endforeach
          </select>
        </div>

        {{-- Academic Year --}}
        <div class="sm-field">
          <label>Academic Year <span class="req">*</span></label>
          <select name="academic_year" id="fieldAcademicYear" class="sm-input" required onchange="refreshIdPreview()">
            @foreach($years as $yr)
              <option value="{{ $yr }}" {{ old('academic_year', $student->academic_year) == $yr ? 'selected' : '' }}>
                {{ $yr }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Session --}}
        <div class="sm-field">
          <label>Session <span class="req">*</span></label>
          <select name="session" id="fieldSession" class="sm-input" required onchange="refreshIdPreview()">
            @foreach($sessions as $sess)
              <option value="{{ $sess }}" {{ old('session', $student->session) == $sess ? 'selected' : '' }}>
                {{ $sess }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Batch No --}}
        <div class="sm-field">
          <label>Batch No</label>
          <input type="text" name="batch_no" value="{{ old('batch_no', $student->batch_no) }}" placeholder="e.g. CT-24, Batch 1" class="sm-input">
        </div>

        {{-- Project / Donor --}}
        <div class="sm-field">
          <label>Project / Donor</label>
          <input type="text" name="donor" value="{{ old('donor', $student->donor) }}" placeholder="e.g. ASSET, IMI, ECHO, BRAC, Self" class="sm-input" list="donorList">
          <datalist id="donorList">
            @foreach($donors as $d)
              <option value="{{ $d }}">
            @endforeach
          </datalist>
        </div>

        {{-- Shift --}}
        <div class="sm-field">
          <label>Class Shift</label>
          <select name="shift" class="sm-input">
            <option value="">Select Shift</option>
            @foreach($shifts as $sh)
              <option value="{{ $sh }}" {{ old('shift', $student->shift) == $sh ? 'selected' : '' }}>{{ $sh }}</option>
            @endforeach
          </select>
        </div>

        {{-- Course Type --}}
        <div class="sm-field">
          <label>Course Type</label>
          <input type="text" name="course_type" value="{{ old('course_type', $student->course_type) }}" placeholder="e.g. Short Course, RPL" class="sm-input" list="courseTypeList">
          <datalist id="courseTypeList">
            @foreach($courseTypes as $ct)
              <option value="{{ $ct }}">
            @endforeach
          </datalist>
        </div>

        {{-- Section --}}
        <div class="sm-field">
          <label>Section / Sector</label>
          <input type="text" name="section" id="fieldSection" value="{{ old('section', $student->section) }}" placeholder="e.g. RMG, ICT, Construction, RPL" class="sm-input" onchange="refreshIdPreview()">
        </div>

        {{-- Date of Admission --}}
        <div class="sm-field">
          <label>Date of Admission</label>
          <input type="date" name="date_of_admission" value="{{ old('date_of_admission', $student->date_of_admission ? $student->date_of_admission->format('Y-m-d') : '') }}" class="sm-input">
        </div>
      </div>
    </div>

    {{-- SECTION B: STUDENT PERSONAL INFORMATION --}}
    <div class="sm-section-card">
      <div class="sm-section-title">
        <div class="sm-sec-num">B</div>
        <div>
          <h3>Student Personal Information</h3>
          <p>Trainee identity, biodata, contact details, and household profile</p>
        </div>
      </div>

      <div class="sm-grid sm-grid-3">
        {{-- Full Name (EN) --}}
        <div class="sm-field">
          <label>Student Full Name (English) <span class="req">*</span></label>
          <input type="text" name="student_name" value="{{ old('student_name', $student->student_name) }}" required placeholder="e.g. Arif Hossain" class="sm-input">
        </div>

        {{-- Bangla Name --}}
        <div class="sm-field">
          <label>শিক্ষার্থীর নাম (বাংলায়)</label>
          <input type="text" name="student_name_bn" value="{{ old('student_name_bn', $student->student_name_bn) }}" placeholder="উদা: আরিফ হোসেন" class="sm-input">
        </div>

        {{-- Gender --}}
        <div class="sm-field">
          <label>Gender <span class="req">*</span></label>
          <select name="gender" class="sm-input" required>
            <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
          </select>
        </div>

        {{-- Date of Birth --}}
        <div class="sm-field">
          <label>Date of Birth</label>
          <input type="date" name="dob" id="fieldDob" value="{{ old('dob', $student->dob ? $student->dob->format('Y-m-d') : '') }}" class="sm-input" onchange="calculateAge(this.value)">
        </div>

        {{-- Mobile Self --}}
        <div class="sm-field">
          <label>Contact Mobile (Self)</label>
          <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" placeholder="e.g. 01712-345678" class="sm-input">
        </div>

        {{-- Mobile Alt / Parent --}}
        <div class="sm-field">
          <label>Parent / Guardian Mobile</label>
          <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" placeholder="e.g. 01819-123456" class="sm-input">
        </div>

        {{-- NID / Birth Cert --}}
        <div class="sm-field">
          <label>NID / Birth Certificate No.</label>
          <input type="text" name="nid_birth_cert" value="{{ old('nid_birth_cert', $student->nid_birth_cert) }}" placeholder="National ID or Birth Reg No" class="sm-input">
        </div>

        {{-- Father's Name --}}
        <div class="sm-field">
          <label>Father's Name</label>
          <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}" placeholder="Father's full name" class="sm-input">
        </div>

        {{-- Mother's Name --}}
        <div class="sm-field">
          <label>Mother's Name</label>
          <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}" placeholder="Mother's full name" class="sm-input">
        </div>

        {{-- Educational Qualification --}}
        <div class="sm-field">
          <label>Educational Qualification</label>
          <input type="text" name="educational_qualification" value="{{ old('educational_qualification', $student->educational_qualification) }}" placeholder="e.g. SSC, HSC, Class 8" class="sm-input">
        </div>

        {{-- Religion --}}
        <div class="sm-field">
          <label>Religion</label>
          <select name="religion" class="sm-input">
            <option value="">Select Religion</option>
            <option value="Islam" {{ old('religion', $student->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
            <option value="Hindu" {{ old('religion', $student->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
            <option value="Buddhist" {{ old('religion', $student->religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
            <option value="Christian" {{ old('religion', $student->religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
          </select>
        </div>

        {{-- Marital Status --}}
        <div class="sm-field">
          <label>Marital Status</label>
          <select name="marital_status" class="sm-input">
            <option value="">Select</option>
            <option value="Unmarried" {{ old('marital_status', $student->marital_status) == 'Unmarried' ? 'selected' : '' }}>Unmarried</option>
            <option value="Married" {{ old('marital_status', $student->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
          </select>
        </div>

        {{-- Ethnic Category --}}
        <div class="sm-field">
          <label>Ethnic Minority?</label>
          <select name="ethnic" class="sm-input">
            <option value="No" {{ old('ethnic', $student->ethnic) == 'No' ? 'selected' : '' }}>No</option>
            <option value="Yes" {{ old('ethnic', $student->ethnic) == 'Yes' ? 'selected' : '' }}>Yes (Ethnic)</option>
          </select>
        </div>

        {{-- PWD --}}
        <div class="sm-field">
          <label>Person With Disability (PWD)?</label>
          <select name="pwd" class="sm-input">
            <option value="No" {{ old('pwd', $student->pwd) == 'No' ? 'selected' : '' }}>No</option>
            <option value="Yes" {{ old('pwd', $student->pwd) == 'Yes' ? 'selected' : '' }}>Yes (Disability)</option>
          </select>
        </div>

        {{-- Student Photo --}}
        <div class="sm-field">
          <label>Student Photo</label>
          <input type="file" name="photo" accept="image/*" class="sm-input">
          @if($student->photo)
            <small style="color:#047857;">Current photo is attached</small>
          @endif
        </div>
      </div>

      {{-- Addresses --}}
      <div class="sm-grid sm-grid-2" style="margin-top:14px;">
        <div class="sm-field">
          <label>Present Address</label>
          <textarea name="present_address" rows="2" class="sm-input" placeholder="Present residence, road, area, city">{{ old('present_address', $student->present_address ?: $student->address) }}</textarea>
        </div>
        <div class="sm-field">
          <label>Permanent Address</label>
          <textarea name="permanent_address" rows="2" class="sm-input" placeholder="Permanent home village, post, upazilla, district">{{ old('permanent_address', $student->permanent_address ?: $student->address) }}</textarea>
        </div>
      </div>
    </div>

    {{-- SECTION C: ASSESSMENT & CERTIFICATE --}}
    <div class="sm-section-card">
      <div class="sm-section-title">
        <div class="sm-sec-num">C</div>
        <div>
          <h3>Assessment & Certificate</h3>
          <p>Marks, grade, course completion status, and certificate tracking</p>
        </div>
      </div>

      <div class="sm-grid sm-grid-4">
        {{-- Viva --}}
        <div class="sm-field">
          <label>Viva (20)</label>
          <input type="number" step="0.5" min="0" max="20" name="viva_mark" id="fieldViva" value="{{ old('viva_mark', $student->viva_mark) }}" class="sm-input" oninput="calculateTotalMarks()">
        </div>

        {{-- Theory --}}
        <div class="sm-field">
          <label>Theory (20)</label>
          <input type="number" step="0.5" min="0" max="20" name="theory_mark" id="fieldTheory" value="{{ old('theory_mark', $student->theory_mark) }}" class="sm-input" oninput="calculateTotalMarks()">
        </div>

        {{-- Practical --}}
        <div class="sm-field">
          <label>Practical (60)</label>
          <input type="number" step="0.5" min="0" max="60" name="practical_mark" id="fieldPractical" value="{{ old('practical_mark', $student->practical_mark) }}" class="sm-input" oninput="calculateTotalMarks()">
        </div>

        {{-- Total Marks --}}
        <div class="sm-field">
          <label>Total Marks (100)</label>
          <input type="number" step="0.5" min="0" max="100" name="total_marks" id="fieldTotal" value="{{ old('total_marks', $student->total_marks) }}" class="sm-input" readonly style="background:#f8fafc;font-weight:700;">
        </div>

        {{-- Grade --}}
        <div class="sm-field">
          <label>Grade</label>
          <input type="text" name="grade" id="fieldGrade" value="{{ old('grade', $student->grade) }}" placeholder="e.g. A+, A, B, C" class="sm-input">
        </div>

        {{-- NSDA / BTEB Result --}}
        <div class="sm-field">
          <label>NSDA / BTEB Result</label>
          <select name="nsda_bteb_result" class="sm-input">
            <option value="">Select</option>
            <option value="Competent" {{ old('nsda_bteb_result', $student->nsda_bteb_result) == 'Competent' ? 'selected' : '' }}>Competent</option>
            <option value="Not Yet Competent" {{ old('nsda_bteb_result', $student->nsda_bteb_result) == 'Not Yet Competent' ? 'selected' : '' }}>Not Yet Competent</option>
          </select>
        </div>

        {{-- Training Status --}}
        <div class="sm-field">
          <label>Training Status</label>
          <select name="training_status" class="sm-input">
            <option value="Active" {{ old('training_status', $student->training_status) == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Running" {{ old('training_status', $student->training_status) == 'Running' ? 'selected' : '' }}>Running</option>
            <option value="Completed" {{ old('training_status', $student->training_status) == 'Completed' ? 'selected' : '' }}>Completed</option>
            <option value="Dropped" {{ old('training_status', $student->training_status) == 'Dropped' ? 'selected' : '' }}>Dropped</option>
          </select>
        </div>

        {{-- Certificate Status --}}
        <div class="sm-field">
          <label>Certificate Status</label>
          <select name="certificate_status" class="sm-input">
            <option value="Pending" {{ old('certificate_status', $student->certificate_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Issued" {{ old('certificate_status', $student->certificate_status) == 'Issued' ? 'selected' : '' }}>Issued</option>
            <option value="Not Eligible" {{ old('certificate_status', $student->certificate_status) == 'Not Eligible' ? 'selected' : '' }}>Not Eligible</option>
          </select>
        </div>
      </div>
    </div>

    {{-- SECTION D: EMPLOYMENT & JOB PLACEMENT --}}
    <div class="sm-section-card">
      <div class="sm-section-title">
        <div class="sm-sec-num">D</div>
        <div>
          <h3>Employment & Job Placement</h3>
          <p>Post-training graduate placement, employer links and salary</p>
        </div>
      </div>

      <div class="sm-grid sm-grid-3">
        {{-- Job Status --}}
        <div class="sm-field">
          <label>Job Placement Status</label>
          <select name="job_status" class="sm-input">
            <option value="Not Yet Employed" {{ old('job_status', $student->job_status) == 'Not Yet Employed' ? 'selected' : '' }}>Not Yet Employed</option>
            <option value="Employed" {{ old('job_status', $student->job_status) == 'Employed' ? 'selected' : '' }}>Employed</option>
            <option value="Self Employed" {{ old('job_status', $student->job_status) == 'Self Employed' ? 'selected' : '' }}>Self Employed</option>
            <option value="Seeking" {{ old('job_status', $student->job_status) == 'Seeking' ? 'selected' : '' }}>Seeking</option>
          </select>
        </div>

        {{-- Company Name --}}
        <div class="sm-field">
          <label>Company / Industry Name</label>
          <input type="text" name="company_name" value="{{ old('company_name', $student->company_name) }}" placeholder="e.g. Apex, Beximco, Square" class="sm-input">
        </div>

        {{-- Company Type --}}
        <div class="sm-field">
          <label>Company Type</label>
          <select name="company_type" class="sm-input">
            <option value="">Select Type</option>
            <option value="Private" {{ old('company_type', $student->company_type) == 'Private' ? 'selected' : '' }}>Private</option>
            <option value="Govt" {{ old('company_type', $student->company_type) == 'Govt' ? 'selected' : '' }}>Govt</option>
            <option value="Business" {{ old('company_type', $student->company_type) == 'Business' ? 'selected' : '' }}>Business / Self</option>
          </select>
        </div>

        {{-- Designation --}}
        <div class="sm-field">
          <label>Designation / Role</label>
          <input type="text" name="designation" value="{{ old('designation', $student->designation) }}" placeholder="e.g. Technician, Operator, Assistant" class="sm-input">
        </div>

        {{-- Joining Date --}}
        <div class="sm-field">
          <label>Joining Date</label>
          <input type="date" name="joining_date" value="{{ old('joining_date', $student->joining_date ? $student->joining_date->format('Y-m-d') : '') }}" class="sm-input">
        </div>

        {{-- Monthly Salary --}}
        <div class="sm-field">
          <label>Monthly Salary (BDT)</label>
          <input type="number" step="100" name="salary" value="{{ old('salary', $student->salary) }}" placeholder="e.g. 15000" class="sm-input">
        </div>

        {{-- Employer Phone --}}
        <div class="sm-field">
          <label>Employer / Supervisor Phone</label>
          <input type="text" name="employer_phone" value="{{ old('employer_phone', $student->employer_phone) }}" placeholder="e.g. 01711-000000" class="sm-input">
        </div>

        {{-- Remarks --}}
        <div class="sm-field" style="grid-column: span 2;">
          <label>Remarks</label>
          <input type="text" name="remarks" value="{{ old('remarks', $student->remarks) }}" placeholder="Any notable observations" class="sm-input">
        </div>
      </div>
    </div>

    {{-- Form Submit Actions --}}
    <div class="sm-form-footer">
      <button type="submit" class="sm-btn sm-btn-primary sm-btn-lg">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ $student->exists ? 'Update Student Record' : 'Save Student Record' }}</span>
      </button>
      <a href="{{ $student->exists ? route('admin.students.show', $student) : route('admin.students.database') }}" class="sm-btn sm-btn-secondary">
        Cancel
      </a>
    </div>
  </form>
</div>

<script>
function refreshIdPreview() {
  @if($student->exists)
    return; // Don't alter existing student ID
  @endif
  var occ = document.getElementById('fieldOccupation').value;
  var yr = document.getElementById('fieldAcademicYear').value;
  var sess = document.getElementById('fieldSession').value;
  var sec = document.getElementById('fieldSection').value;

  var url = '{{ route('admin.students.preview-id') }}?occupation=' + encodeURIComponent(occ) + '&academic_year=' + encodeURIComponent(yr) + '&session=' + encodeURIComponent(sess) + '&section=' + encodeURIComponent(sec);

  fetch(url)
    .then(function(res) { return res.json(); })
    .then(function(data) {
      if (data.candidate_id) {
        document.getElementById('previewIdBadge').textContent = data.candidate_id;
      }
    })
    .catch(function(err) { console.error('Error fetching ID preview:', err); });
}

function calculateTotalMarks() {
  var v = parseFloat(document.getElementById('fieldViva').value) || 0;
  var t = parseFloat(document.getElementById('fieldTheory').value) || 0;
  var p = parseFloat(document.getElementById('fieldPractical').value) || 0;
  var tot = v + t + p;
  document.getElementById('fieldTotal').value = tot;

  var gradeField = document.getElementById('fieldGrade');
  if (tot >= 80) gradeField.value = 'A+';
  else if (tot >= 70) gradeField.value = 'A';
  else if (tot >= 60) gradeField.value = 'B';
  else if (tot >= 50) gradeField.value = 'C';
  else if (tot >= 40) gradeField.value = 'D';
  else if (tot > 0) gradeField.value = 'Fail';
}
</script>

<style>
.sm-form-container {
  max-width: 1200px;
  margin: 0 auto 50px;
}

.sm-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.sm-form-title {
  font-size: 26px;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 4px;
}

.sm-form-subtitle {
  font-size: 13.5px;
  color: #64748b;
  margin: 0;
}

.sm-section-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 22px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.sm-section-title {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 20px;
  padding-bottom: 14px;
  border-bottom: 1px solid #f1f5f9;
  position: relative;
}

.sm-sec-num {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: #ecfdf5;
  color: #047857;
  font-size: 14px;
  font-weight: 800;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sm-section-title h3 {
  font-size: 17px;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 2px;
}

.sm-section-title p {
  font-size: 12.5px;
  color: #64748b;
  margin: 0;
}

.sm-id-preview-box {
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: 8px;
}

.sm-id-badge-label {
  font-size: 12px;
  font-weight: 600;
  color: #64748b;
}

.sm-id-badge {
  background: #ecfdf5;
  color: #047857;
  border: 1px solid #a7f3d0;
  padding: 4px 10px;
  border-radius: 6px;
  font-family: ui-monospace, SFMono-Regular, monospace;
  font-size: 14px;
  font-weight: 700;
}

.sm-grid {
  display: grid;
  gap: 16px;
}

.sm-grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
.sm-grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
.sm-grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }

.sm-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.sm-field label {
  font-size: 12.5px;
  font-weight: 600;
  color: #334155;
}

.sm-field label .req {
  color: #dc2626;
}

.sm-input {
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  padding: 9px 12px;
  font-size: 13.5px;
  color: #1f2937;
  background: #ffffff;
  outline: none;
  transition: all 0.15s;
}

.sm-input:focus {
  border-color: #047857;
  box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.12);
}

.sm-form-footer {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-top: 10px;
}

.sm-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  border: 1px solid transparent;
  transition: all 0.18s;
}

.sm-btn-primary {
  background: #047857;
  color: #ffffff;
}

.sm-btn-primary:hover {
  background: #065f46;
}

.sm-btn-secondary {
  background: #ffffff;
  border-color: #cbd5e1;
  color: #475569;
}

.sm-btn-secondary:hover {
  background: #f1f5f9;
}

.sm-btn-lg {
  padding: 12px 24px;
  font-size: 14.5px;
}

@media (max-width: 900px) {
  .sm-grid-3, .sm-grid-4 {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 600px) {
  .sm-grid-2, .sm-grid-3, .sm-grid-4 {
    grid-template-columns: 1fr;
  }
  .sm-section-title {
    flex-direction: column;
    align-items: flex-start;
  }
  .sm-id-preview-box {
    margin-left: 0;
  }
}
</style>
@endsection
