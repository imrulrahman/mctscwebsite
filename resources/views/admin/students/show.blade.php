@extends('admin.layout')

@section('title', "{$student->student_name} ({$student->student_id}) · Student Profile")
@section('crumb', "Student Management / {$student->student_id}")

@section('content')
<div class="sm-profile-container">
  {{-- Profile Top Card / Header --}}
  <div class="sm-profile-hero-card">
    <div class="sm-profile-hero-main">
      @if($student->photo)
        <img src="{{ storage_asset($student->photo) }}" alt="{{ $student->student_name }}" class="sm-profile-photo">
      @else
        @php $color = $student->avatar_color; @endphp
        <div class="sm-profile-avatar-initials" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
          {{ $student->avatar_initials }}
        </div>
      @endif

      <div class="sm-hero-details">
        <div class="sm-hero-title-row">
          <h1 class="sm-hero-name">{{ $student->student_name }}</h1>
          @if($student->student_name_bn)
            <span class="sm-hero-name-bn">({{ $student->student_name_bn }})</span>
          @endif
          <span class="sm-hero-id">{{ $student->student_id }}</span>
        </div>

        <div class="sm-hero-badges-row">
          <span class="sm-badge badge-occ">{{ $student->occupation ?: 'General' }}</span>
          <span class="sm-badge badge-year">{{ $student->academic_year }} · {{ $student->session ?: 'Session 1' }}</span>
          @if($student->batch_no)
            <span class="sm-badge badge-batch">Batch: {{ $student->batch_no }}</span>
          @endif
          @if($student->donor)
            <span class="sm-badge badge-donor">Project: {{ $student->donor }}</span>
          @endif
          @if(strtolower($student->training_status) === 'completed')
            <span class="sm-badge badge-completed">Completed</span>
          @else
            <span class="sm-badge badge-active">{{ $student->training_status ?: 'Active' }}</span>
          @endif
          @if(in_array($student->job_status, ['Employed', 'Self Employed']))
            <span class="sm-badge badge-employed">{{ $student->job_status }}</span>
          @endif
        </div>

        <div class="sm-hero-quick-meta">
          <span><b>Gender:</b> {{ $student->gender ?: '—' }}</span>
          <span><b>Phone:</b> {{ $student->phone ?: '—' }}</span>
          <span><b>Age:</b> {{ $student->age ? "{$student->age} yrs" : '—' }}</span>
          <span><b>Admission:</b> {{ $student->date_of_admission ? $student->date_of_admission->format('d M Y') : '—' }}</span>
        </div>
      </div>
    </div>

    <div class="sm-hero-actions">
      @can('students.edit')
        <a href="{{ route('admin.students.edit', $student) }}" class="sm-btn sm-btn-primary">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg>
          <span>Edit Student</span>
        </a>
      @endcan

      <a href="{{ route('admin.students.print-roster', ['batch' => $student->batch_no, 'year' => $student->academic_year]) }}" target="_blank" class="sm-btn sm-btn-outline">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="6 9 6 2 18 2 18 9"></polyline>
          <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
          <rect x="6" y="14" width="12" height="8"></rect>
        </svg>
        <span>Print Roster</span>
      </a>

      @can('students.delete')
        <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Are you sure you want to permanently delete this student record? This action cannot be undone.');">
          @csrf
          @method('DELETE')
          <button type="submit" class="sm-btn sm-btn-danger" title="Delete record">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="3 6 5 6 21 6"></polyline>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
          </button>
        </form>
      @endcan
    </div>
  </div>

  {{-- Profile Tabs Navigation --}}
  <div class="sm-tabs-bar">
    <button type="button" class="sm-tab-btn active" onclick="switchTab(event, 'tabOverview')">Overview</button>
    <button type="button" class="sm-tab-btn" onclick="switchTab(event, 'tabPersonal')">Personal Information</button>
    <button type="button" class="sm-tab-btn" onclick="switchTab(event, 'tabTraining')">Training Information</button>
    <button type="button" class="sm-tab-btn" onclick="switchTab(event, 'tabAssessment')">Assessment & Marks</button>
    <button type="button" class="sm-tab-btn" onclick="switchTab(event, 'tabEmployment')">Employment & Job</button>
    <button type="button" class="sm-tab-btn" onclick="switchTab(event, 'tabContact')">Contact & Address</button>
    <button type="button" class="sm-tab-btn" onclick="switchTab(event, 'tabDocuments')">Documents</button>
  </div>

  {{-- TAB 1: OVERVIEW --}}
  <div id="tabOverview" class="sm-tab-content active">
    <div class="sm-overview-grid">
      <div class="sm-info-card">
        <h3>Training Snapshot</h3>
        <table class="sm-detail-table">
          <tr><th>Occupation</th><td>{{ $student->occupation ?: '—' }}</td></tr>
          <tr><th>Course Type</th><td>{{ $student->course_type ?: 'Short Course' }}</td></tr>
          <tr><th>Academic Year</th><td>{{ $student->academic_year }}</td></tr>
          <tr><th>Session</th><td>{{ $student->session ?: 'Session 1' }}</td></tr>
          <tr><th>Batch</th><td>{{ $student->batch_no ?: '—' }}</td></tr>
          <tr><th>Shift</th><td>{{ $student->shift ?: '—' }}</td></tr>
          <tr><th>Training Status</th><td><span class="sm-badge badge-active">{{ $student->training_status ?: 'Active' }}</span></td></tr>
        </table>
      </div>

      <div class="sm-info-card">
        <h3>Personal & Contact Summary</h3>
        <table class="sm-detail-table">
          <tr><th>Gender</th><td>{{ $student->gender ?: '—' }}</td></tr>
          <tr><th>Date of Birth</th><td>{{ $student->dob ? $student->dob->format('d M Y') : '—' }} ({{ $student->age ? $student->age . ' yrs' : '—' }})</td></tr>
          <tr><th>Phone (Self)</th><td><b>{{ $student->phone ?: '—' }}</b></td></tr>
          <tr><th>Father's Name</th><td>{{ $student->father_name ?: '—' }}</td></tr>
          <tr><th>Mother's Name</th><td>{{ $student->mother_name ?: '—' }}</td></tr>
          <tr><th>Special Category</th><td>{{ $student->category_label }}</td></tr>
          <tr><th>Address</th><td>{{ $student->address ?: ($student->present_address ?: '—') }}</td></tr>
        </table>
      </div>

      <div class="sm-info-card">
        <h3>Assessment & Certificate</h3>
        <table class="sm-detail-table">
          <tr><th>Total Marks</th><td><b>{{ $student->total_marks !== null ? $student->total_marks . ' / 100' : '—' }}</b></td></tr>
          <tr><th>Grade</th><td><span class="sm-badge badge-batch">{{ $student->grade ?: '—' }}</span></td></tr>
          <tr><th>NSDA / BTEB</th><td>{{ $student->nsda_bteb_result ?: '—' }}</td></tr>
          <tr><th>Certificate</th><td><span class="sm-badge badge-completed">{{ $student->certificate_status ?: 'Pending' }}</span></td></tr>
        </table>
      </div>

      <div class="sm-info-card">
        <h3>Employment Status</h3>
        <table class="sm-detail-table">
          <tr><th>Job Placement</th><td><b>{{ $student->job_status ?: 'Not Yet Employed' }}</b></td></tr>
          <tr><th>Company Name</th><td>{{ $student->company_name ?: '—' }}</td></tr>
          <tr><th>Designation</th><td>{{ $student->designation ?: '—' }}</td></tr>
          <tr><th>Monthly Salary</th><td>{{ $student->salary ? 'BDT ' . number_format($student->salary, 2) : '—' }}</td></tr>
        </table>
      </div>
    </div>
  </div>

  {{-- TAB 2: PERSONAL INFORMATION --}}
  <div id="tabPersonal" class="sm-tab-content">
    <div class="sm-info-card">
      <h3>Complete Personal & Household Information</h3>
      <table class="sm-detail-table sm-table-wide">
        <tr><th>Full Name (English)</th><td>{{ $student->student_name }}</td></tr>
        <tr><th>Name in Bengali (বাংলা)</th><td>{{ $student->student_name_bn ?: '—' }}</td></tr>
        <tr><th>Gender</th><td>{{ $student->gender ?: '—' }}</td></tr>
        <tr><th>Date of Birth</th><td>{{ $student->dob ? $student->dob->format('d M Y') : '—' }}</td></tr>
        <tr><th>Calculated Age</th><td>{{ $student->age ? "{$student->age} years" : '—' }}</td></tr>
        <tr><th>NID / Birth Certificate No.</th><td>{{ $student->nid_birth_cert ?: '—' }}</td></tr>
        <tr><th>Educational Qualification</th><td>{{ $student->educational_qualification ?: '—' }}</td></tr>
        <tr><th>Father's Name</th><td>{{ $student->father_name ?: '—' }}</td></tr>
        <tr><th>Mother's Name</th><td>{{ $student->mother_name ?: '—' }}</td></tr>
        <tr><th>Number of Siblings</th><td>{{ $student->siblings ?: '—' }}</td></tr>
        <tr><th>Marital Status</th><td>{{ $student->marital_status ?: '—' }}</td></tr>
        <tr><th>Religion</th><td>{{ $student->religion ?: '—' }}</td></tr>
        <tr><th>Ethnic Minority Category</th><td>{{ $student->ethnic ?: 'No' }}</td></tr>
        <tr><th>Person With Disability (PWD)</th><td>{{ $student->pwd ?: 'No' }}</td></tr>
      </table>
    </div>
  </div>

  {{-- TAB 3: TRAINING INFORMATION --}}
  <div id="tabTraining" class="sm-tab-content">
    <div class="sm-info-card">
      <h3>Enrollment & Course Details</h3>
      <table class="sm-detail-table sm-table-wide">
        <tr><th>Student ID</th><td><b style="font-family:monospace;color:#047857;">{{ $student->student_id }}</b></td></tr>
        <tr><th>Occupation / Trade</th><td>{{ $student->occupation ?: '—' }}</td></tr>
        <tr><th>Occupation Code</th><td>{{ $student->occupation_code ?: '—' }}</td></tr>
        <tr><th>RPL Course</th><td>{{ $student->rpl_course ?: '—' }}</td></tr>
        <tr><th>Section / Sector</th><td>{{ $student->section ?: '—' }}</td></tr>
        <tr><th>Academic Year</th><td>{{ $student->academic_year }}</td></tr>
        <tr><th>Session</th><td>{{ $student->session ?: 'Session 1' }} (Session #{{ $student->session_number }})</td></tr>
        <tr><th>Batch No</th><td>{{ $student->batch_no ?: '—' }}</td></tr>
        <tr><th>Class Shift</th><td>{{ $student->shift ?: '—' }}</td></tr>
        <tr><th>Development Partner / Donor</th><td>{{ $student->donor ?: 'Self' }}</td></tr>
        <tr><th>Date of Admission</th><td>{{ $student->date_of_admission ? $student->date_of_admission->format('d M Y') : '—' }}</td></tr>
        <tr><th>Payment Status</th><td>{{ $student->payment_status ?: 'Free Govt / Subsidized' }}</td></tr>
        <tr><th>Training Status</th><td>{{ $student->training_status ?: 'Active' }}</td></tr>
        <tr><th>Remarks</th><td>{{ $student->remarks ?: '—' }}</td></tr>
      </table>
    </div>
  </div>

  {{-- TAB 4: ASSESSMENT & MARKS --}}
  <div id="tabAssessment" class="sm-tab-content">
    <div class="sm-info-card">
      <h3>Examination Marks & Assessment Breakdown</h3>
      <table class="sm-detail-table sm-table-wide">
        <tr><th>Viva Examination (20)</th><td>{{ $student->viva_mark !== null ? $student->viva_mark : '—' }}</td></tr>
        <tr><th>Theory Examination (20)</th><td>{{ $student->theory_mark !== null ? $student->theory_mark : '—' }}</td></tr>
        <tr><th>Practical Examination (60)</th><td>{{ $student->practical_mark !== null ? $student->practical_mark : '—' }}</td></tr>
        <tr><th>Total Marks (100)</th><td><b>{{ $student->total_marks !== null ? $student->total_marks : '—' }}</b></td></tr>
        <tr><th>Grade</th><td><span class="sm-badge badge-batch">{{ $student->grade ?: '—' }}</span></td></tr>
        <tr><th>NSDA / BTEB Assessment Result</th><td><b>{{ $student->nsda_bteb_result ?: '—' }}</b></td></tr>
        <tr><th>Certificate Issuance Status</th><td>{{ $student->certificate_status ?: 'Pending' }}</td></tr>
        <tr><th>Certificate Number</th><td>{{ $student->certificate_no ?: '—' }}</td></tr>
        <tr><th>Certificate Issue Date</th><td>{{ $student->certificate_issue_date ? $student->certificate_issue_date->format('d M Y') : '—' }}</td></tr>
      </table>
    </div>
  </div>

  {{-- TAB 5: EMPLOYMENT & JOB --}}
  <div id="tabEmployment" class="sm-tab-content">
    <div class="sm-info-card">
      <h3>Graduate Employment & Placement Information</h3>
      <table class="sm-detail-table sm-table-wide">
        <tr><th>Job Placement Status</th><td><b>{{ $student->job_status ?: 'Not Yet Employed' }}</b></td></tr>
        <tr><th>Company / Industry Name</th><td>{{ $student->company_name ?: '—' }}</td></tr>
        <tr><th>Company Sector / Type</th><td>{{ $student->company_type ?: '—' }}</td></tr>
        <tr><th>Designation / Role</th><td>{{ $student->designation ?: '—' }}</td></tr>
        <tr><th>Employment Type</th><td>{{ $student->employment_type ?: '—' }}</td></tr>
        <tr><th>Joining Date</th><td>{{ $student->joining_date ? $student->joining_date->format('d M Y') : '—' }}</td></tr>
        <tr><th>Monthly Salary</th><td>{{ $student->salary ? 'BDT ' . number_format($student->salary, 2) : '—' }}</td></tr>
        <tr><th>Employer Phone</th><td>{{ $student->employer_phone ?: '—' }}</td></tr>
        <tr><th>Company Address</th><td>{{ $student->company_address ?: '—' }}</td></tr>
      </table>
    </div>
  </div>

  {{-- TAB 6: CONTACT & ADDRESS --}}
  <div id="tabContact" class="sm-tab-content">
    <div class="sm-info-card">
      <h3>Contact Information & Addresses</h3>
      <table class="sm-detail-table sm-table-wide">
        <tr><th>Mobile No (Self)</th><td><b>{{ $student->phone ?: '—' }}</b></td></tr>
        <tr><th>Alternative Mobile</th><td>{{ $student->alt_phone ?: '—' }}</td></tr>
        <tr><th>Parents / Guardian Mobile</th><td>{{ $student->parent_phone ?: '—' }}</td></tr>
        <tr><th>Email Address</th><td>{{ $student->email ?: '—' }}</td></tr>
        <tr><th>Present Address</th><td>{{ $student->present_address ?: ($student->address ?: '—') }}</td></tr>
        <tr><th>Permanent Address</th><td>{{ $student->permanent_address ?: ($student->address ?: '—') }}</td></tr>
      </table>
    </div>
  </div>

  {{-- TAB 7: DOCUMENTS --}}
  <div id="tabDocuments" class="sm-tab-content">
    <div class="sm-info-card">
      <h3>Institutional Identification & Documents</h3>
      <table class="sm-detail-table sm-table-wide">
        <tr><th>Identification Type</th><td>{{ $student->ident_type ?: 'National ID / Birth Certificate' }}</td></tr>
        <tr><th>Identification Document No.</th><td><b>{{ $student->nid_birth_cert ?: '—' }}</b></td></tr>
        <tr><th>Attached Photo</th><td>
          @if($student->photo)
            <img src="{{ storage_asset($student->photo) }}" alt="Photo" style="width:120px;height:140px;object-fit:cover;border-radius:6px;border:1px solid #cbd5e1;">
          @else
            <span style="color:#94a3b8;">No photo attached</span>
          @endif
        </td></tr>
      </table>
    </div>
  </div>
</div>

<script>
function switchTab(e, tabId) {
  var btns = document.querySelectorAll('.sm-tab-btn');
  btns.forEach(function(b) { b.classList.remove('active'); });
  e.currentTarget.classList.add('active');

  var contents = document.querySelectorAll('.sm-tab-content');
  contents.forEach(function(c) { c.classList.remove('active'); });

  var target = document.getElementById(tabId);
  if (target) {
    target.classList.add('active');
  }
}
</script>

<style>
.sm-profile-container {
  max-width: 1300px;
  margin: 0 auto 50px;
}

.sm-profile-hero-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 24px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 20px;
  margin-bottom: 24px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  flex-wrap: wrap;
}

.sm-profile-hero-main {
  display: flex;
  align-items: center;
  gap: 20px;
  flex: 1;
  min-width: 300px;
}

.sm-profile-photo {
  width: 80px;
  height: 80px;
  border-radius: 12px;
  object-fit: cover;
  border: 2px solid #047857;
  flex-shrink: 0;
}

.sm-profile-avatar-initials {
  width: 80px;
  height: 80px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
  font-weight: 800;
  flex-shrink: 0;
}

.sm-hero-title-row {
  display: flex;
  align-items: baseline;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 8px;
}

.sm-hero-name {
  font-size: 24px;
  font-weight: 800;
  color: #1f2937;
  margin: 0;
}

.sm-hero-name-bn {
  font-size: 16px;
  color: #64748b;
}

.sm-hero-id {
  font-family: ui-monospace, SFMono-Regular, monospace;
  font-size: 14px;
  font-weight: 700;
  background: #ecfdf5;
  color: #047857;
  border: 1px solid #a7f3d0;
  padding: 2px 8px;
  border-radius: 6px;
}

.sm-hero-badges-row {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 10px;
}

.sm-badge {
  display: inline-block;
  padding: 3px 9px;
  border-radius: 14px;
  font-size: 11.5px;
  font-weight: 600;
}

.badge-occ { background: #f1f5f9; color: #334155; }
.badge-year { background: #e0f2fe; color: #0369a1; }
.badge-batch { background: #ede9fe; color: #6d28d9; }
.badge-donor { background: #fef3c7; color: #92400e; }
.badge-completed { background: #d1fae5; color: #047857; }
.badge-active { background: #ecfdf5; color: #065f46; }
.badge-employed { background: #ccfbf1; color: #0f766e; }

.sm-hero-quick-meta {
  display: flex;
  align-items: center;
  gap: 16px;
  font-size: 12.5px;
  color: #64748b;
  flex-wrap: wrap;
}

.sm-hero-quick-meta b {
  color: #334155;
}

.sm-hero-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.sm-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  border: 1px solid transparent;
  transition: all 0.15s;
}

.sm-btn-primary { background: #047857; color: #ffffff; }
.sm-btn-primary:hover { background: #065f46; }
.sm-btn-outline { background: #ffffff; border-color: #cbd5e1; color: #334155; }
.sm-btn-outline:hover { background: #f8fafc; border-color: #047857; color: #047857; }
.sm-btn-danger { background: #fee2e2; border-color: #fecaca; color: #dc2626; padding: 8px 10px; }
.sm-btn-danger:hover { background: #dc2626; color: #ffffff; }

/* Tabs */
.sm-tabs-bar {
  display: flex;
  gap: 6px;
  border-bottom: 2px solid #e2e8f0;
  margin-bottom: 24px;
  overflow-x: auto;
}

.sm-tab-btn {
  background: transparent;
  border: none;
  padding: 10px 16px;
  font-size: 13.5px;
  font-weight: 600;
  color: #64748b;
  cursor: pointer;
  border-bottom: 2px solid transparent;
  margin-bottom: -2px;
  white-space: nowrap;
  transition: all 0.15s;
}

.sm-tab-btn:hover {
  color: #047857;
}

.sm-tab-btn.active {
  color: #047857;
  border-bottom-color: #047857;
}

.sm-tab-content {
  display: none;
}

.sm-tab-content.active {
  display: block;
}

/* Overview Grid */
.sm-overview-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 20px;
}

.sm-info-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.sm-info-card h3 {
  font-size: 15px;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 14px;
  padding-bottom: 10px;
  border-bottom: 1px solid #f1f5f9;
}

.sm-detail-table {
  width: 100%;
  border-collapse: collapse;
}

.sm-detail-table th {
  font-size: 12.5px;
  font-weight: 600;
  color: #64748b;
  padding: 8px 0;
  text-align: left;
  width: 40%;
  vertical-align: top;
}

.sm-detail-table td {
  font-size: 13px;
  color: #1f2937;
  padding: 8px 0;
  vertical-align: top;
}

.sm-table-wide th {
  width: 30%;
}

@media (max-width: 900px) {
  .sm-overview-grid {
    grid-template-columns: 1fr;
  }
}
</style>
@endsection
