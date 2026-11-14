@extends('site.layout')

@section('title', $isBn ? 'প্রার্থীর ড্যাশবোর্ড · মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Candidate Dashboard · Mahmudunnabi Chowdhury Technical School and College')
@section('page_title', $isBn ? 'প্রার্থীর ড্যাশবোর্ড ও প্রোফাইল ম্যানেজমেন্ট' : 'Candidate Dashboard & Profile Management')
@section('page_subtitle', $isBn ? 'আপনার চাকরির আবেদনের স্ট্যাটাস দেখুন এবং প্রয়োজনীয় তথ্য ও সিভি হালনাগাদ করুন।' : 'Track your job application progress and keep your profile & resume up to date.')

@section('content')
<style>
.cand-dash-container {
  max-width: 1050px;
  margin: 0 auto;
  padding: 30px 16px 60px;
}
.cand-top-bar {
  background: #ffffff;
  border: 1px solid #dbe6e0;
  border-left: 5px solid var(--green, #0f5132);
  border-radius: 10px;
  padding: 20px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  margin-bottom: 24px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
}
.cand-user-info {
  display: flex;
  align-items: center;
  gap: 16px;
}
.cand-avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: #dcfce7;
  color: #166534;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  font-weight: 700;
  border: 2px solid #bbf7d0;
  overflow: hidden;
}
.cand-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.cand-user-meta h2 {
  margin: 0 0 4px 0;
  font-size: 19px;
  color: #0f172a;
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
}
.cand-user-meta p {
  margin: 0;
  font-size: 13px;
  color: #64748b;
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
}
.cand-logout-btn {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #cbd5e1;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s ease;
}
.cand-logout-btn:hover {
  background: #fee2e2;
  color: #dc2626;
  border-color: #fca5a5;
}
.cand-grid {
  display: grid;
  grid-template-columns: 2fr 1.1fr;
  gap: 24px;
}
@media (max-width: 900px) {
  .cand-grid {
    grid-template-columns: 1fr;
  }
}
.cand-card {
  background: #ffffff;
  border: 1px solid #dbe6e0;
  border-radius: 10px;
  padding: 22px 24px;
  margin-bottom: 24px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
}
.cand-card-title {
  font-size: 16px;
  font-weight: 700;
  color: #14532d;
  margin: 0 0 16px 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-bottom: 12px;
  border-bottom: 1px dashed #e2e8f0;
  flex-wrap: wrap;
  gap: 10px;
}
.status-pill {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 12px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 20px;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}
.status-new {
  background: #ecfdf5;
  color: #047857;
  border: 1px solid #a7f3d0;
}
.status-shortlisted {
  background: #faf5ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
}
.status-interview {
  background: #fffbeb;
  color: #b45309;
  border: 1px solid #fde68a;
}
.status-selected {
  background: #f0fdf4;
  color: #15803d;
  border: 1px solid #86efac;
}
.status-rejected {
  background: #fef2f2;
  color: #b91c1c;
  border: 1px solid #fecaca;
}
.app-item-card {
  border: 1px solid #e2ebe6;
  border-radius: 8px;
  padding: 16px 18px;
  margin-bottom: 14px;
  background: #fbfdfc;
  transition: all 0.2s ease;
}
.app-item-card:hover {
  border-color: #86efac;
  background: #ffffff;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);
}
.app-item-head {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 8px;
  flex-wrap: wrap;
}
.app-post-name {
  font-size: 16px;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}
.app-meta-line {
  font-size: 13px;
  color: #64748b;
  display: flex;
  gap: 14px;
  margin-bottom: 10px;
  flex-wrap: wrap;
}
.app-admin-note {
  background: #fefce8;
  border: 1px solid #fef08a;
  border-radius: 6px;
  padding: 10px 12px;
  font-size: 12.5px;
  color: #854d0e;
  margin-top: 10px;
  line-height: 1.5;
}
.btn-view-app {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: #f0fdf4;
  color: #15803d !important;
  border: 1px solid #bbf7d0;
  padding: 5px 12px;
  border-radius: 6px;
  font-size: 12.5px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.15s ease;
}
.btn-view-app:hover {
  background: #15803d;
  color: #ffffff !important;
}
.cv-upload-box {
  background: #f8fafc;
  border: 2px dashed #cbd5e1;
  border-radius: 8px;
  padding: 16px;
  text-align: center;
  margin-top: 12px;
  transition: border-color 0.2s ease;
}
.cv-upload-box:hover {
  border-color: #0f5132;
}
.btn-update-cv {
  width: 100%;
  background: #0f5132;
  color: #ffffff;
  border: none;
  padding: 10px 16px;
  border-radius: 6px;
  font-weight: 700;
  font-size: 13.5px;
  cursor: pointer;
  margin-top: 12px;
  transition: all 0.2s ease;
}
.btn-update-cv:hover {
  background: #093722;
}

/* Profile Form Elements */
.cand-form-section {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 16px;
}
.cand-form-sec-title {
  font-size: 13.5px;
  font-weight: 700;
  color: #0f5132;
  margin: 0 0 12px 0;
  text-transform: uppercase;
  letter-spacing: 0.4px;
}
.cand-form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
}
.cand-form-group {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.cand-form-group label {
  font-size: 12.5px;
  font-weight: 600;
  color: #334155;
}
.cand-form-group input, .cand-form-group select, .cand-form-group textarea {
  padding: 7px 10px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 13px;
  background: #ffffff;
  color: #0f172a;
}
.cand-form-group input:focus, .cand-form-group select:focus, .cand-form-group textarea:focus {
  outline: none;
  border-color: #0f5132;
  box-shadow: 0 0 0 2px rgba(15, 81, 50, 0.15);
}
</style>

<div class="cand-dash-container">
  {{-- Flash Notifications --}}
  @if(session('success'))
    <div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:14px 18px; border-radius:8px; margin-bottom:20px; font-weight:600; font-size:14px;">
      ✓ {{ session('success') }}
    </div>
  @endif
  @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:14px 18px; border-radius:8px; margin-bottom:20px; font-size:13.5px;">
      <strong>⚠️ ত্রুটি:</strong>
      <ul style="margin:6px 0 0 18px; padding:0;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Top Bar / Candidate Profile Summary --}}
  <div class="cand-top-bar">
    <div class="cand-user-info">
      <div class="cand-avatar">
        @if($primaryApp->photo_path)
          <img src="{{ storage_asset($primaryApp->photo_path) }}" alt="{{ $primaryApp->applicant_name }}" onerror="this.onerror=null; this.src='{{ asset('assets/images/user-placeholder.png') }}';">
        @else
          {{ mb_substr($primaryApp->applicant_name, 0, 1) }}
        @endif
      </div>
      <div class="cand-user-meta">
        <h2>{{ $primaryApp->applicant_name }}</h2>
        <p>
          <span>📱 {{ $primaryApp->mobile_no }}</span>
          <span>✉️ {{ $primaryApp->email }}</span>
          <span>🆔 Tracking: <strong>{{ $primaryApp->tracking_no }}</strong></span>
        </p>
      </div>
    </div>
    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
      <a href="#update-profile-section" class="btn-view-app" style="background:#0f5132; color:#ffffff !important; border-color:#0f5132; padding:8px 14px; font-size:13px;">
        ✏️ {{ $isBn ? 'প্রোফাইল ও তথ্য পরিবর্তন' : 'Update Profile & CV' }}
      </a>
      <a href="{{ route('career') }}" class="btn-view-app" style="background:#f8fafc; border-color:#cbd5e1; color:#334155 !important; padding:8px 14px; font-size:13px;">
        💼 {{ $isBn ? 'সকল সার্কুলার' : 'All Circulars' }}
      </a>
      <form method="POST" action="{{ route('candidate.logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="cand-logout-btn">
          <span>🚪</span> {{ $isBn ? 'লগআউট' : 'Logout' }}
        </button>
      </form>
    </div>
  </div>

  <div class="cand-grid">
    {{-- Left Col: Submitted Applications & Status --}}
    <div>
      <div class="cand-card">
        <h3 class="cand-card-title">
          <span>📋 {{ $isBn ? 'আমার আবেদনসমূহ ও বর্তমান স্ট্যাটাস' : 'My Job Applications & Status' }}</span>
          <span style="font-size: 13px; font-weight: 600; color: #166534; background: #dcfce7; padding: 2px 10px; border-radius: 12px;">
            {{ $applications->count() }} {{ $isBn ? 'টি আবেদন' : 'Application(s)' }}
          </span>
        </h3>

        @forelse($applications as $app)
          @php
            $status = strtolower($app->status ?: 'new');
            $statusClass = match($status) {
              'shortlisted' => 'status-shortlisted',
              'interview' => 'status-interview',
              'selected', 'approved' => 'status-selected',
              'rejected' => 'status-rejected',
              default => 'status-new',
            };
            $statusLabel = match($status) {
              'shortlisted' => ($isBn ? 'বাছাইকৃত' : 'Shortlisted'),
              'interview' => ($isBn ? 'সাক্ষাৎকার আহ্বান' : 'Interview Scheduled'),
              'selected', 'approved' => ($isBn ? 'চূড়ান্ত নির্বাচিত' : 'Selected / Approved'),
              'rejected' => ($isBn ? 'নির্বাচিত হননি' : 'Not Selected'),
              default => ($isBn ? 'আবেদন জমা হয়েছে' : 'Application Submitted'),
            };
          @endphp

          <div class="app-item-card">
            <div class="app-item-head">
              <h4 class="app-post-name">{{ $app->post_name }}</h4>
              <span class="status-pill {{ $statusClass }}">
                ● {{ $statusLabel }}
              </span>
            </div>

            <div class="app-meta-line">
              <span>🆔 <strong>{{ $app->tracking_no }}</strong></span>
              <span>📅 {{ $isBn ? 'আবেদনের তারিখ:' : 'Applied Date:' }} {{ $app->created_at->format('d M, Y h:i A') }}</span>
              @if($app->jobCircular && $app->jobCircular->category)
                <span>🏷️ {{ $app->jobCircular->category }}</span>
              @endif
            </div>

            @if(!empty($app->admin_notes))
              <div class="app-admin-note">
                <strong>📢 {{ $isBn ? 'কর্তৃপক্ষের বার্তা:' : 'Notice from Management:' }}</strong>
                {{ $app->admin_notes }}
              </div>
            @endif

            <div style="margin-top: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
              <a href="{{ route('career.apply.confirmation', $app->tracking_no) }}" target="_blank" class="btn-view-app">
                <span>📄</span> {{ $isBn ? 'আবেদন কপি দেখুন / প্রিন্ট' : 'View / Print Application' }} ↗
              </a>
              @if($app->cv_path)
                <a href="{{ storage_asset($app->cv_path) }}" target="_blank" class="btn-view-app" style="background:#eff6ff; border-color:#bfdbfe; color:#1e40af !important;">
                  <span>📎</span> {{ $isBn ? 'সিভি ডাউনলোড' : 'Download CV' }}
                </a>
              @endif
            </div>
          </div>
        @empty
          <p style="text-align: center; color: #64748b; margin: 30px 0;">
            {{ $isBn ? 'আপনার কোনো আবেদন পাওয়া যায়নি।' : 'No job applications found.' }}
          </p>
        @endforelse
      </div>

      {{-- Complete Profile & CV Update Section --}}
      <div class="cand-card" id="update-profile-section">
        <h3 class="cand-card-title">
          <span>✏️ {{ $isBn ? 'প্রোফাইল ও আবেদনের তথ্য পরিবর্তন করুন' : 'Update My Profile & Application Details' }}</span>
        </h3>
        <p style="font-size: 13px; color: #64748b; margin-top: -6px; margin-bottom: 16px;">
          {{ $isBn ? 'আবেদন করার সময় যেসব তথ্য দিয়েছিলেন, তা প্রয়োজনে নিচে সংশোধন করে সংরক্ষণ করুন।' : 'Review and update any information submitted during your application below.' }}
        </p>

        <form method="POST" action="{{ route('candidate.update_profile') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="application_id" value="{{ $primaryApp->id }}">

          {{-- 1. Personal Information --}}
          <div class="cand-form-section">
            <div class="cand-form-sec-title">1. {{ $isBn ? 'ব্যক্তিগত তথ্য (Personal Information)' : 'Personal Information' }}</div>
            <div class="cand-form-grid">
              <div class="cand-form-group">
                <label>{{ $isBn ? 'প্রার্থীর নাম *' : 'Applicant Name *' }}</label>
                <input type="text" name="applicant_name" value="{{ old('applicant_name', $primaryApp->applicant_name) }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'পিতার নাম *' : 'Father\'s Name *' }}</label>
                <input type="text" name="father_name" value="{{ old('father_name', $primaryApp->father_name) }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'মাতার নাম *' : 'Mother\'s Name *' }}</label>
                <input type="text" name="mother_name" value="{{ old('mother_name', $primaryApp->mother_name) }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'জন্ম তারিখ *' : 'Date of Birth *' }}</label>
                <input type="date" name="dob" value="{{ old('dob', $primaryApp->dob?->format('Y-m-d')) }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'লিঙ্গ *' : 'Gender *' }}</label>
                <select name="gender" required>
                  <option value="Male" @selected(old('gender', $primaryApp->gender) === 'Male')>Male</option>
                  <option value="Female" @selected(old('gender', $primaryApp->gender) === 'Female')>Female</option>
                  <option value="Third Gender" @selected(old('gender', $primaryApp->gender) === 'Third Gender')>Third Gender</option>
                </select>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'জাতীয়তা *' : 'Nationality *' }}</label>
                <input type="text" name="nationality" value="{{ old('nationality', $primaryApp->nationality ?: 'Bangladeshi') }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'ধর্ম *' : 'Religion *' }}</label>
                <input type="text" name="religion" value="{{ old('religion', $primaryApp->religion ?: 'Islam') }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'বৈবাহিক অবস্থা *' : 'Marital Status *' }}</label>
                <select name="marital_status" id="edit_marital_status" required onchange="toggleSpouseEdit(this.value)">
                  <option value="Single" @selected(old('marital_status', $primaryApp->marital_status) === 'Single')>Single</option>
                  <option value="Married" @selected(old('marital_status', $primaryApp->marital_status) === 'Married')>Married</option>
                  <option value="Divorced" @selected(old('marital_status', $primaryApp->marital_status) === 'Divorced')>Divorced</option>
                  <option value="Widowed" @selected(old('marital_status', $primaryApp->marital_status) === 'Widowed')>Widowed</option>
                </select>
              </div>
              <div class="cand-form-group" id="edit_spouse_group" style="{{ old('marital_status', $primaryApp->marital_status) === 'Married' ? '' : 'display:none;' }}">
                <label>{{ $isBn ? 'স্বামী/স্ত্রীর নাম' : 'Spouse Name' }}</label>
                <input type="text" name="spouse_name" value="{{ old('spouse_name', $primaryApp->spouse_name) }}">
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'জাতীয় পরিচয়পত্র নম্বর (NID)' : 'National ID No' }}</label>
                <input type="text" name="nid_no" value="{{ old('nid_no', $primaryApp->nid_no) }}">
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'জন্ম নিবন্ধন নম্বর' : 'Birth Reg No' }}</label>
                <input type="text" name="birth_reg_no" value="{{ old('birth_reg_no', $primaryApp->birth_reg_no) }}">
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'কোটা' : 'Quota' }}</label>
                <input type="text" name="quota" value="{{ old('quota', $primaryApp->quota ?: 'Non-Quota') }}">
              </div>
            </div>
          </div>

          {{-- 2. Contact & Addresses --}}
          <div class="cand-form-section">
            <div class="cand-form-sec-title">2. {{ $isBn ? 'যোগাযোগ ও ঠিকানা (Contact & Address)' : 'Contact & Address' }}</div>
            <div class="cand-form-grid" style="margin-bottom: 12px;">
              <div class="cand-form-group">
                <label>{{ $isBn ? 'মোবাইল নম্বর *' : 'Mobile Number *' }}</label>
                <input type="text" name="mobile_no" value="{{ old('mobile_no', $primaryApp->mobile_no) }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'ইমেইল অ্যাড্রেস *' : 'Email Address *' }}</label>
                <input type="email" name="email" value="{{ old('email', $primaryApp->email) }}" required>
              </div>
            </div>

            @php
              $present = is_array($primaryApp->present_address) ? $primaryApp->present_address : [];
              $permanent = is_array($primaryApp->permanent_address) ? $primaryApp->permanent_address : [];
              $same = !empty($permanent['same_as_present']);
            @endphp

            <strong style="font-size:12.5px; color:#334155; display:block; margin:8px 0 6px;">{{ $isBn ? 'বর্তমান ঠিকানা (Present Address):' : 'Present Address:' }}</strong>
            <div class="cand-form-grid">
              <div class="cand-form-group">
                <label>{{ $isBn ? 'প্রযত্নে (Care of) *' : 'Care of *' }}</label>
                <input type="text" name="present_care_of" value="{{ old('present_care_of', $present['care_of'] ?? '') }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'গ্রাম/রাস্তা/বাড়ি *' : 'Village / Street *' }}</label>
                <input type="text" name="present_village" value="{{ old('present_village', $present['village'] ?? '') }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'জেলা *' : 'District *' }}</label>
                <input type="text" name="present_district" value="{{ old('present_district', $present['district'] ?? '') }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'উপজেলা/থানা *' : 'Upazila / Thana *' }}</label>
                <input type="text" name="present_upazila" value="{{ old('present_upazila', $present['upazila'] ?? '') }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'পোস্ট অফিস *' : 'Post Office *' }}</label>
                <input type="text" name="present_post_office" value="{{ old('present_post_office', $present['post_office'] ?? '') }}" required>
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'পোস্ট কোড *' : 'Post Code *' }}</label>
                <input type="text" name="present_post_code" value="{{ old('present_post_code', $present['post_code'] ?? '') }}" required>
              </div>
            </div>

            <div style="margin: 14px 0 6px; display:flex; align-items:center; gap:8px;">
              <input type="checkbox" name="same_as_present" id="same_as_present_edit" value="1" @checked(old('same_as_present', $same)) onchange="togglePermanentEdit(this.checked)">
              <label for="same_as_present_edit" style="font-size:12.5px; font-weight:600; color:#334155; cursor:pointer;">
                {{ $isBn ? 'স্থায়ী ঠিকানা বর্তমান ঠিকানার অনুরূপ (Same as Present Address)' : 'Permanent address is same as present' }}
              </label>
            </div>

            <div id="permanent_address_fields" style="{{ (old('same_as_present', $same)) ? 'display:none;' : '' }}">
              <strong style="font-size:12.5px; color:#334155; display:block; margin:8px 0 6px;">{{ $isBn ? 'স্থায়ী ঠিকানা (Permanent Address):' : 'Permanent Address:' }}</strong>
              <div class="cand-form-grid">
                <div class="cand-form-group">
                  <label>{{ $isBn ? 'প্রযত্নে (Care of)' : 'Care of' }}</label>
                  <input type="text" name="permanent_care_of" value="{{ old('permanent_care_of', $permanent['care_of'] ?? '') }}">
                </div>
                <div class="cand-form-group">
                  <label>{{ $isBn ? 'গ্রাম/রাস্তা/বাড়ি' : 'Village / Street' }}</label>
                  <input type="text" name="permanent_village" value="{{ old('permanent_village', $permanent['village'] ?? '') }}">
                </div>
                <div class="cand-form-group">
                  <label>{{ $isBn ? 'জেলা' : 'District' }}</label>
                  <input type="text" name="permanent_district" value="{{ old('permanent_district', $permanent['district'] ?? '') }}">
                </div>
                <div class="cand-form-group">
                  <label>{{ $isBn ? 'উপজেলা/থানা' : 'Upazila' }}</label>
                  <input type="text" name="permanent_upazila" value="{{ old('permanent_upazila', $permanent['upazila'] ?? '') }}">
                </div>
                <div class="cand-form-group">
                  <label>{{ $isBn ? 'পোস্ট অফিস' : 'Post Office' }}</label>
                  <input type="text" name="permanent_post_office" value="{{ old('permanent_post_office', $permanent['post_office'] ?? '') }}">
                </div>
                <div class="cand-form-group">
                  <label>{{ $isBn ? 'পোস্ট কোড' : 'Post Code' }}</label>
                  <input type="text" name="permanent_post_code" value="{{ old('permanent_post_code', $permanent['post_code'] ?? '') }}">
                </div>
              </div>
            </div>
          </div>

          {{-- 3. Additional Qualifications & Experience --}}
          <div class="cand-form-section">
            <div class="cand-form-sec-title">3. {{ $isBn ? 'অন্যান্য যোগ্যতা ও দক্ষতা' : 'Other Qualifications & Skills' }}</div>
            <div class="cand-form-group">
              <label>{{ $isBn ? 'দক্ষতা / প্রশিক্ষণ / অভিজ্ঞতার বিবরণ' : 'Skills / Trainings / Special Qualifications' }}</label>
              <textarea name="other_qualifications" rows="3" placeholder="{{ $isBn ? 'কম্পিউটার স্কিল, ড্রাইভিং লাইসেন্স, ভোকেশনাল ট্রেনিং ইত্যাদি...' : 'Computer skills, certifications, language fluency...' }}">{{ old('other_qualifications', $primaryApp->other_qualifications) }}</textarea>
            </div>
          </div>

          {{-- 4. Upload / Update Files --}}
          <div class="cand-form-section">
            <div class="cand-form-sec-title">4. {{ $isBn ? 'ছবি, স্বাক্ষর ও সিভি পরিবর্তন (ঐচ্ছিক)' : 'Update Photo, Signature & CV (Optional)' }}</div>
            <div class="cand-form-grid">
              <div class="cand-form-group">
                <label>{{ $isBn ? 'নতুন ছবি (Passport Size)' : 'New Photo (Max 1MB)' }}</label>
                <input type="file" name="photo" accept="image/*">
                @if($primaryApp->photo_path)
                  <span style="font-size:11px; color:#15803d;">✓ বর্তমান ছবি বিদ্যমান</span>
                @endif
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'নতুন স্বাক্ষর (Signature)' : 'New Signature (Max 512KB)' }}</label>
                <input type="file" name="signature" accept="image/*">
                @if($primaryApp->signature_path)
                  <span style="font-size:11px; color:#15803d;">✓ বর্তমান স্বাক্ষর বিদ্যমান</span>
                @endif
              </div>
              <div class="cand-form-group">
                <label>{{ $isBn ? 'নতুন সিভি (Resume/CV)' : 'New CV (PDF, DOC, DOCX)' }}</label>
                <input type="file" name="cv_file" accept=".pdf,.doc,.docx">
                @if($primaryApp->cv_path)
                  <span style="font-size:11px; color:#15803d;">✓ বর্তমান সিভি: {{ basename($primaryApp->cv_path) }}</span>
                @endif
              </div>
            </div>
          </div>

          <button type="submit" class="btn-update-cv" style="font-size:15px; padding:12px;">
            💾 {{ $isBn ? 'সকল পরিবর্তন সংরক্ষণ করুন' : 'Save & Update Profile Information' }}
          </button>
        </form>
      </div>
    </div>

    {{-- Right Col: Quick CV Upload & Summary --}}
    <div>
      {{-- Quick CV Card --}}
      <div class="cand-card">
        <h3 class="cand-card-title">
          <span>📄 {{ $isBn ? 'দ্রুত সিভি আপলোড' : 'Quick CV Upload' }}</span>
        </h3>

        <div style="margin-bottom: 12px;">
          <span style="font-size: 13px; color: #475569; display: block; margin-bottom: 6px;">
            {{ $isBn ? 'বর্তমান আপলোডকৃত সিভি:' : 'Current CV on File:' }}
          </span>
          @if($primaryApp->cv_path)
            <div style="display: flex; align-items: center; gap: 8px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 8px 12px; border-radius: 6px;">
              <span style="font-size: 18px;">📄</span>
              <a href="{{ storage_asset($primaryApp->cv_path) }}" target="_blank" style="color: #0f5132; font-weight: 700; font-size: 13px; text-decoration: underline; word-break: break-all;">
                {{ basename($primaryApp->cv_path) }}
              </a>
            </div>
          @else
            <div style="font-size: 13px; color: #94a3b8; font-style: italic;">
              {{ $isBn ? 'এখনো কোনো সিভি আপলোড করা হয়নি।' : 'No CV uploaded yet.' }}
            </div>
          @endif
        </div>

        <form method="POST" action="{{ route('candidate.update_cv') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="application_id" value="{{ $primaryApp->id }}">
          
          <div class="cv-upload-box">
            <span style="font-size: 28px; display: block; margin-bottom: 4px;">📤</span>
            <label style="font-size: 13px; font-weight: 700; color: #1e293b; display: block; margin-bottom: 4px; cursor: pointer;">
              {{ $isBn ? 'নতুন সিভি ফাইল নির্বাচন করুন' : 'Select New CV File' }}
            </label>
            <span style="font-size: 11.5px; color: #64748b; display: block; margin-bottom: 10px;">
              PDF, DOC, DOCX (Max 5MB)
            </span>
            <input type="file" name="cv_file" accept=".pdf,.doc,.docx" required style="font-size: 12px; width: 100%;">
          </div>

          <button type="submit" class="btn-update-cv">
            💾 {{ $isBn ? 'সিভি সংরক্ষণ করুন' : 'Save & Update CV' }}
          </button>
        </form>
      </div>

      {{-- Candidate Profile Summary Card --}}
      <div class="cand-card">
        <h3 class="cand-card-title">
          <span>👤 {{ $isBn ? 'সংক্ষিপ্ত তথ্য' : 'Candidate Summary' }}</span>
        </h3>
        <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
          <tbody>
            <tr style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 6px 0; color: #64748b; width: 38%;">{{ $isBn ? 'পিতার নাম' : 'Father:' }}</td>
              <td style="padding: 6px 0; font-weight: 600; color: #1e293b;">{{ $primaryApp->father_name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 6px 0; color: #64748b;">{{ $isBn ? 'মাতার নাম' : 'Mother:' }}</td>
              <td style="padding: 6px 0; font-weight: 600; color: #1e293b;">{{ $primaryApp->mother_name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 6px 0; color: #64748b;">{{ $isBn ? 'জন্ম তারিখ' : 'DOB:' }}</td>
              <td style="padding: 6px 0; font-weight: 600; color: #1e293b;">{{ $primaryApp->dob?->format('d/m/Y') }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 6px 0; color: #64748b;">{{ $isBn ? 'লিঙ্গ / ধর্ম' : 'Gender/Rel:' }}</td>
              <td style="padding: 6px 0; font-weight: 600; color: #1e293b;">{{ $primaryApp->gender }} / {{ $primaryApp->religion }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 6px 0; color: #64748b;">{{ $isBn ? 'বৈবাহিক অবস্থা' : 'Marital:' }}</td>
              <td style="padding: 6px 0; font-weight: 600; color: #1e293b;">{{ $primaryApp->marital_status }}</td>
            </tr>
            <tr>
              <td style="padding: 6px 0; color: #64748b; vertical-align: top;">{{ $isBn ? 'বর্তমান ঠিকানা' : 'Address:' }}</td>
              <td style="padding: 6px 0; font-weight: 600; color: #1e293b;">
                @if(is_array($primaryApp->present_address))
                  {{ $primaryApp->present_address['village'] ?? '' }}, {{ $primaryApp->present_address['district'] ?? '' }}
                @else
                  -
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function toggleSpouseEdit(val) {
  var grp = document.getElementById('edit_spouse_group');
  if (grp) grp.style.display = (val === 'Married') ? '' : 'none';
}
function togglePermanentEdit(checked) {
  var flds = document.getElementById('permanent_address_fields');
  if (flds) flds.style.display = checked ? 'none' : '';
}
</script>
@endsection
