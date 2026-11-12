@extends('site.layout')

@section('title', ($isBn ? 'চাকরির আবেদন ফরম (Job Application Form)' : 'Job Application Form') . ' · MCTSC')
@section('page_title', $isBn ? 'চাকরির আবেদন ফরম' : 'Job Application Form')
@section('page_subtitle', $selectedJob ? $selectedJob->title : ($isBn ? 'সকল তথ্য সঠিকভাবে পূরণ করে আবেদন জমা দিন' : 'Please fill out all required fields accurately as per your academic certificates.'))

@section('content')
<style>
.tt-wrap {
  max-width: 980px;
  margin: 0 auto;
  padding: 28px 14px 60px;
}
.tt-header-bar {
  background: linear-gradient(135deg, #0f5132 0%, #146c43 100%);
  color: #fff;
  padding: 18px 24px;
  border-radius: 10px 10px 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
}
.tt-header-bar h2 {
  margin: 0;
  font-size: 19px;
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
}
.tt-header-bar p {
  margin: 4px 0 0 0;
  font-size: 12.5px;
  color: #d1fae5;
}
.tt-form-card {
  background: #ffffff;
  border: 1px solid #cbd5e1;
  border-top: none;
  border-radius: 0 0 10px 10px;
  padding: 24px;
  box-shadow: 0 6px 24px rgba(15, 23, 42, 0.06);
}
.tt-section {
  border: 1px solid #d1d5db;
  border-radius: 8px;
  margin-bottom: 22px;
  overflow: hidden;
  background: #fff;
}
.tt-section-head {
  background: #f1f5f9;
  border-bottom: 1px solid #d1d5db;
  padding: 10px 16px;
  font-weight: 700;
  font-size: 14px;
  color: #0f5132;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.tt-section-body {
  padding: 16px;
}
.tt-grid-2 {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 14px 18px;
}
.tt-grid-3 {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px 18px;
}
.tt-field {
  margin-bottom: 0;
}
.tt-field label {
  display: block;
  font-size: 12.5px;
  font-weight: 700;
  color: #334155;
  margin-bottom: 5px;
}
.tt-field label span.req {
  color: #dc2626;
}
.tt-field input[type="text"],
.tt-field input[type="email"],
.tt-field input[type="tel"],
.tt-field input[type="date"],
.tt-field input[type="number"],
.tt-field select,
.tt-field textarea {
  width: 100%;
  padding: 9px 11px;
  border: 1px solid #cbd5e1;
  border-radius: 5px;
  font-size: 13.5px;
  color: #0f172a;
  background: #fff;
  box-sizing: border-box;
  font-family: inherit;
}
.tt-field input:focus,
.tt-field select:focus,
.tt-field textarea:focus {
  outline: none;
  border-color: #0f5132;
  box-shadow: 0 0 0 3px rgba(15, 81, 50, 0.12);
}
.tt-field small.hint {
  display: block;
  font-size: 11px;
  color: #64748b;
  margin-top: 3px;
}
.tt-edu-box {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  padding: 14px;
  margin-bottom: 14px;
}
.tt-edu-box:last-child {
  margin-bottom: 0;
}
.tt-edu-title {
  font-size: 13px;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 10px 0;
  padding-bottom: 6px;
  border-bottom: 1px dashed #cbd5e1;
}
.tt-upload-box {
  border: 1px dashed #94a3b8;
  border-radius: 8px;
  padding: 14px;
  background: #f8fafc;
  text-align: center;
}
.tt-preview-img {
  max-width: 100%;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  background: #fff;
  margin-top: 8px;
  display: none;
  margin-left: auto;
  margin-right: auto;
}
.tt-alert-error {
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #991b1b;
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 18px;
  font-size: 13px;
}
.tt-btn-submit {
  background: #0f5132;
  color: #fff;
  border: none;
  padding: 13px 32px;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: background 0.2s;
}
.tt-btn-submit:hover {
  background: #0b3d26;
}
@media (max-width: 768px) {
  .tt-grid-2, .tt-grid-3 {
    grid-template-columns: 1fr;
  }
  .tt-form-card {
    padding: 16px;
  }
}
</style>

@php
  $bdDistricts = [
    'Bagerhat','Bandarban','Barguna','Barishal','Bhola','Bogura','Brahmanbaria','Chandpur',
    'Chapainawabganj','Chattogram','Chuadanga','Comilla','Cox\'s Bazar','Dhaka','Dinajpur',
    'Faridpur','Feni','Gaibandha','Gazipur','Gopalganj','Habiganj','Jamalpur','Jashore',
    'Jhalokati','Jhenaidah','Joypurhat','Khagrachhari','Khulna','Kishoreganj','Kurigram',
    'Kushtia','Lakshmipur','Lalmonirhat','Madaripur','Magura','Manikganj','Meherpur',
    'Moulvibazar','Munshiganj','Mymensingh','Naogaon','Narail','Narayanganj','Narsingdi',
    'Natore','Netrokona','Nilphamari','Noakhali','Pabna','Panchagarh','Patuakhali',
    'Pirojpur','Rajbari','Rajshahi','Rangamati','Rangpur','Satkhira','Shariatpur',
    'Sherpur','Sirajganj','Sunamganj','Sylhet','Tangail','Thakurgaon'
  ];
@endphp

<div class="tt-wrap">
  <div style="margin-bottom: 14px;">
    <a href="{{ route('career') }}" style="color:#0f5132;font-weight:700;font-size:13.5px;text-decoration:none;">← {{ $isBn ? 'নিয়োগ বিজ্ঞপ্তিতে ফিরে যান' : 'Back to Career Circulars' }}</a>
  </div>

  <div class="tt-header-bar">
    <div>
      <h2>{{ $isBn ? 'চাকরির আবেদন ফরম (Job Application Form)' : 'Job Application Form' }}</h2>
      <p>{{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School & College') }}</p>
    </div>
    <div style="font-size:12px;background:rgba(255,255,255,0.16);padding:6px 12px;border-radius:6px;">
      <span style="color:#fef08a;font-weight:700;">*</span> {{ $isBn ? 'চিহ্নিত ঘরগুলো অবশ্যই পূরণীয়' : 'Indicates Mandatory Fields' }}
    </div>
  </div>

  <div class="tt-form-card">
    @if ($errors->any())
      <div class="tt-alert-error">
        <strong>{{ $isBn ? 'অনুগ্রহ করে নিচের ত্রুটিগুলো সংশোধন করুন:' : 'Please fix the following errors:' }}</strong>
        <ul style="margin:6px 0 0 18px;padding:0;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="post" action="{{ route('career.apply.submit') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="site_content_id" id="siteContentIdInput" value="{{ old('site_content_id', $selectedJob?->id) }}">

      {{-- POST SELECTION --}}
      <div class="tt-section">
        <div class="tt-section-head">
          <span>📌 {{ $isBn ? 'পদের নাম (Name of the Post)' : 'Post Selection' }}</span>
        </div>
        <div class="tt-section-body">
          <div class="tt-field">
            <label>{{ $isBn ? 'আবেদনের পদের নাম' : 'Name of the Post' }} <span class="req">*</span></label>
            @if($circulars->isNotEmpty())
              <select name="post_name" id="postNameSelect" required onchange="syncCircularId(this)">
                <option value="">— {{ $isBn ? 'পদ নির্বাচন করুন' : 'Select Post' }} —</option>
                @foreach($circulars as $c)
                  <option value="{{ $c->title }}" data-id="{{ $c->id }}" {{ old('post_name', $selectedJob?->title) === $c->title ? 'selected' : '' }}>
                    {{ $c->title }} {{ $c->category ? '(' . $c->category . ')' : '' }}
                  </option>
                @endforeach
              </select>
            @else
              <input type="text" name="post_name" required value="{{ old('post_name', $selectedJob?->title) }}" placeholder="e.g. Trade Instructor">
            @endif
          </div>
        </div>
      </div>

      {{-- 1. PERSONAL INFORMATION --}}
      <div class="tt-section">
        <div class="tt-section-head">
          <span>👤 {{ $isBn ? '১. ব্যক্তিগত তথ্য (Basic & Personal Information)' : '1. Basic & Personal Information' }}</span>
        </div>
        <div class="tt-section-body">
          <div class="tt-grid-2">
            <div class="tt-field">
              <label>Applicant's Name (in English) <span class="req">*</span></label>
              <input type="text" name="applicant_name" required value="{{ old('applicant_name') }}" placeholder="AS PER SSC CERTIFICATE (CAPITAL LETTERS)" style="text-transform:uppercase;">
              <small class="hint">S.S.C / সমমান সনদ অনুযায়ী ইংরেজিতে বড় অক্ষরে লিখুন</small>
            </div>
            <div class="tt-field">
              <label>Father's Name <span class="req">*</span></label>
              <input type="text" name="father_name" required value="{{ old('father_name') }}" placeholder="FATHER'S FULL NAME" style="text-transform:uppercase;">
            </div>
            <div class="tt-field">
              <label>Mother's Name <span class="req">*</span></label>
              <input type="text" name="mother_name" required value="{{ old('mother_name') }}" placeholder="MOTHER'S FULL NAME" style="text-transform:uppercase;">
            </div>
            <div class="tt-field">
              <label>Date of Birth <span class="req">*</span></label>
              <input type="date" name="dob" required value="{{ old('dob') }}" max="{{ date('Y-m-d') }}">
            </div>
            <div class="tt-field">
              <label>Gender <span class="req">*</span></label>
              <select name="gender" required>
                <option value="">— Select Gender —</option>
                <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Third Gender" {{ old('gender') === 'Third Gender' ? 'selected' : '' }}>Third Gender</option>
              </select>
            </div>
            <div class="tt-field">
              <label>Nationality <span class="req">*</span></label>
              <input type="text" name="nationality" required value="{{ old('nationality', 'Bangladeshi') }}">
            </div>
            <div class="tt-field">
              <label>Religion <span class="req">*</span></label>
              <select name="religion" required>
                <option value="">— Select Religion —</option>
                <option value="Islam" {{ old('religion') === 'Islam' ? 'selected' : '' }}>Islam</option>
                <option value="Hinduism" {{ old('religion') === 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
                <option value="Buddhism" {{ old('religion') === 'Buddhism' ? 'selected' : '' }}>Buddhism</option>
                <option value="Christianity" {{ old('religion') === 'Christianity' ? 'selected' : '' }}>Christianity</option>
                <option value="Others" {{ old('religion') === 'Others' ? 'selected' : '' }}>Others</option>
              </select>
            </div>
            <div class="tt-field">
              <label>National ID (NID) / Smart Card No</label>
              <input type="text" name="nid_no" value="{{ old('nid_no') }}" placeholder="10 or 17 digit NID Number">
            </div>
            <div class="tt-field">
              <label>Birth Registration No (If no NID)</label>
              <input type="text" name="birth_reg_no" value="{{ old('birth_reg_no') }}" placeholder="17 digit Birth Registration No">
            </div>
            <div class="tt-field">
              <label>Passport ID (Optional)</label>
              <input type="text" name="passport_no" value="{{ old('passport_no') }}" placeholder="Passport Number">
            </div>
            <div class="tt-field">
              <label>Marital Status <span class="req">*</span></label>
              <select name="marital_status" id="maritalStatusSelect" required onchange="toggleSpouseField()">
                <option value="Single" {{ old('marital_status', 'Single') === 'Single' ? 'selected' : '' }}>Single</option>
                <option value="Married" {{ old('marital_status') === 'Married' ? 'selected' : '' }}>Married</option>
                <option value="Divorced" {{ old('marital_status') === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                <option value="Widowed" {{ old('marital_status') === 'Widowed' ? 'selected' : '' }}>Widowed</option>
              </select>
            </div>
            <div class="tt-field" id="spouseNameWrap" style="display:{{ old('marital_status') === 'Married' ? 'block' : 'none' }};">
              <label>Spouse Name (স্বামী/স্ত্রীর নাম)</label>
              <input type="text" name="spouse_name" value="{{ old('spouse_name') }}" placeholder="Spouse Full Name">
            </div>
          </div>
        </div>
      </div>

      {{-- 2. ADDRESS DETAILS --}}
      <div class="tt-grid-2" style="margin-bottom:22px;">
        {{-- Present Address --}}
        <div class="tt-section" style="margin-bottom:0;">
          <div class="tt-section-head">
            <span>🏠 Present / Mailing Address (বর্তমান ঠিকানা)</span>
          </div>
          <div class="tt-section-body" style="display:flex;flex-direction:column;gap:12px;">
            <div class="tt-field">
              <label>Care of (প্রযত্নে) <span class="req">*</span></label>
              <input type="text" name="present_care_of" id="p_care_of" required value="{{ old('present_care_of') }}" placeholder="Guardian / Care of name">
            </div>
            <div class="tt-field">
              <label>Village / Town / Road / House / Flat <span class="req">*</span></label>
              <input type="text" name="present_village" id="p_village" required value="{{ old('present_village') }}" placeholder="House, Road, Village / Area">
            </div>
            <div class="tt-grid-2">
              <div class="tt-field">
                <label>District <span class="req">*</span></label>
                <select name="present_district" id="p_district" required>
                  <option value="">— Select District —</option>
                  @foreach($bdDistricts as $dist)
                    <option value="{{ $dist }}" {{ old('present_district') === $dist ? 'selected' : '' }}>{{ $dist }}</option>
                  @endforeach
                </select>
              </div>
              <div class="tt-field">
                <label>Upazila / Thana <span class="req">*</span></label>
                <input type="text" name="present_upazila" id="p_upazila" required value="{{ old('present_upazila') }}" placeholder="e.g. Beanibazar">
              </div>
            </div>
            <div class="tt-grid-2">
              <div class="tt-field">
                <label>Post Office <span class="req">*</span></label>
                <input type="text" name="present_post_office" id="p_post_office" required value="{{ old('present_post_office') }}" placeholder="Post Office name">
              </div>
              <div class="tt-field">
                <label>Post Code <span class="req">*</span></label>
                <input type="text" name="present_post_code" id="p_post_code" required value="{{ old('present_post_code') }}" placeholder="e.g. 3170">
              </div>
            </div>
          </div>
        </div>

        {{-- Permanent Address --}}
        <div class="tt-section" style="margin-bottom:0;">
          <div class="tt-section-head">
            <span>🏡 Permanent Address (স্থায়ী ঠিকানা)</span>
            <label style="font-size:12px;font-weight:600;color:#1e293b;display:flex;align-items:center;gap:5px;cursor:pointer;margin:0;">
              <input type="checkbox" name="same_as_present" id="sameAsPresentCheck" value="1" {{ old('same_as_present') ? 'checked' : '' }} onchange="copyPresentAddress()">
              Same as Present
            </label>
          </div>
          <div class="tt-section-body" id="permanentAddressBody" style="display:flex;flex-direction:column;gap:12px;">
            <div class="tt-field">
              <label>Care of (প্রযত্নে) <span class="req">*</span></label>
              <input type="text" name="permanent_care_of" id="perm_care_of" required value="{{ old('permanent_care_of') }}" placeholder="Guardian / Care of name">
            </div>
            <div class="tt-field">
              <label>Village / Town / Road / House / Flat <span class="req">*</span></label>
              <input type="text" name="permanent_village" id="perm_village" required value="{{ old('permanent_village') }}" placeholder="House, Road, Village / Area">
            </div>
            <div class="tt-grid-2">
              <div class="tt-field">
                <label>District <span class="req">*</span></label>
                <select name="permanent_district" id="perm_district" required>
                  <option value="">— Select District —</option>
                  @foreach($bdDistricts as $dist)
                    <option value="{{ $dist }}" {{ old('permanent_district') === $dist ? 'selected' : '' }}>{{ $dist }}</option>
                  @endforeach
                </select>
              </div>
              <div class="tt-field">
                <label>Upazila / Thana <span class="req">*</span></label>
                <input type="text" name="permanent_upazila" id="perm_upazila" required value="{{ old('permanent_upazila') }}" placeholder="e.g. Beanibazar">
              </div>
            </div>
            <div class="tt-grid-2">
              <div class="tt-field">
                <label>Post Office <span class="req">*</span></label>
                <input type="text" name="permanent_post_office" id="perm_post_office" required value="{{ old('permanent_post_office') }}" placeholder="Post Office name">
              </div>
              <div class="tt-field">
                <label>Post Code <span class="req">*</span></label>
                <input type="text" name="permanent_post_code" id="perm_post_code" required value="{{ old('permanent_post_code') }}" placeholder="e.g. 3170">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- 3. CONTACT INFORMATION --}}
      <div class="tt-section">
        <div class="tt-section-head">
          <span>📞 {{ $isBn ? '৩. যোগাযোগের তথ্য (Contact Information)' : '3. Contact Information' }}</span>
        </div>
        <div class="tt-section-body">
          <div class="tt-grid-3">
            <div class="tt-field">
              <label>Mobile Number <span class="req">*</span></label>
              <input type="tel" name="mobile_no" required value="{{ old('mobile_no') }}" placeholder="01XXXXXXXXX">
              <small class="hint">১১ ডিজিটের সচল মোবাইল নম্বর দিন</small>
            </div>
            <div class="tt-field">
              <label>Confirm Mobile Number <span class="req">*</span></label>
              <input type="tel" name="confirm_mobile_no" required value="{{ old('confirm_mobile_no') }}" placeholder="Re-enter 01XXXXXXXXX">
            </div>
            <div class="tt-field">
              <label>Email Address <span class="req">*</span></label>
              <input type="email" name="email" required value="{{ old('email') }}" placeholder="name@example.com">
            </div>
          </div>
        </div>
      </div>

      {{-- 4. EDUCATIONAL QUALIFICATIONS --}}
      <div class="tt-section">
        <div class="tt-section-head">
          <span>🎓 {{ $isBn ? '৪. শিক্ষাগত যোগ্যতা (Educational Qualifications)' : '4. Educational Qualifications' }}</span>
        </div>
        <div class="tt-section-body">
          {{-- SSC --}}
          <div class="tt-edu-box">
            <div class="tt-edu-title">SSC / Equivalent Level (মাধ্যমিক বা সমমান)</div>
            <div class="tt-grid-3">
              <div class="tt-field">
                <label>Examination</label>
                <select name="education[ssc][exam]">
                  <option value="">— Select —</option>
                  @foreach(['S.S.C', 'Dakhil', 'S.S.C Vocational', 'O-Level/Cambridge', 'Equivalent'] as $ex)
                    <option value="{{ $ex }}" {{ old('education.ssc.exam') === $ex ? 'selected' : '' }}>{{ $ex }}</option>
                  @endforeach
                </select>
              </div>
              <div class="tt-field">
                <label>Board</label>
                <select name="education[ssc][board]">
                  <option value="">— Select Board —</option>
                  @foreach(['Sylhet','Dhaka','Chattogram','Cumilla','Rajshahi','Jashore','Barishal','Dinajpur','Mymensingh','Madrasah','Technical (BTEB)','Others'] as $brd)
                    <option value="{{ $brd }}" {{ old('education.ssc.board') === $brd ? 'selected' : '' }}>{{ $brd }}</option>
                  @endforeach
                </select>
              </div>
              <div class="tt-field">
                <label>Roll No</label>
                <input type="text" name="education[ssc][roll]" value="{{ old('education.ssc.roll') }}" placeholder="Roll Number">
              </div>
              <div class="tt-field">
                <label>Group / Trade</label>
                <input type="text" name="education[ssc][group]" value="{{ old('education.ssc.group') }}" placeholder="Science / Humanities / Trade">
              </div>
              <div class="tt-field">
                <label>Result (GPA / Division)</label>
                <input type="text" name="education[ssc][result]" value="{{ old('education.ssc.result') }}" placeholder="e.g. GPA 4.50 (out of 5)">
              </div>
              <div class="tt-field">
                <label>Passing Year</label>
                <input type="number" name="education[ssc][passing_year]" min="1975" max="{{ date('Y') }}" value="{{ old('education.ssc.passing_year') }}" placeholder="e.g. 2016">
              </div>
            </div>
          </div>

          {{-- HSC / Diploma --}}
          <div class="tt-edu-box">
            <div class="tt-edu-title">HSC / Diploma / Equivalent Level (উচ্চ মাধ্যমিক / ডিপ্লোমা)</div>
            <div class="tt-grid-3">
              <div class="tt-field">
                <label>Examination</label>
                <select name="education[hsc][exam]">
                  <option value="">— Select —</option>
                  @foreach(['H.S.C', 'Alim', 'Diploma-in-Engineering', 'H.S.C Vocational', 'Business Management', 'A-Level', 'Equivalent'] as $ex)
                    <option value="{{ $ex }}" {{ old('education.hsc.exam') === $ex ? 'selected' : '' }}>{{ $ex }}</option>
                  @endforeach
                </select>
              </div>
              <div class="tt-field">
                <label>Board / Institute</label>
                <input type="text" name="education[hsc][board]" value="{{ old('education.hsc.board') }}" placeholder="Board or BTEB / Polytechnic">
              </div>
              <div class="tt-field">
                <label>Roll No</label>
                <input type="text" name="education[hsc][roll]" value="{{ old('education.hsc.roll') }}" placeholder="Roll Number">
              </div>
              <div class="tt-field">
                <label>Group / Technology / Trade</label>
                <input type="text" name="education[hsc][group]" value="{{ old('education.hsc.group') }}" placeholder="e.g. Electrical / Computer / Science">
              </div>
              <div class="tt-field">
                <label>Result (GPA / CGPA)</label>
                <input type="text" name="education[hsc][result]" value="{{ old('education.hsc.result') }}" placeholder="e.g. CGPA 3.75 (out of 4)">
              </div>
              <div class="tt-field">
                <label>Passing Year</label>
                <input type="number" name="education[hsc][passing_year]" min="1975" max="{{ date('Y') }}" value="{{ old('education.hsc.passing_year') }}" placeholder="e.g. 2020">
              </div>
            </div>
          </div>

          {{-- Graduation --}}
          <div class="tt-edu-box">
            <div class="tt-edu-title">Graduation / Equivalent Level (স্নাতক / বি.এস.সি - প্রযোজ্য ক্ষেত্রে)</div>
            <div class="tt-grid-3">
              <div class="tt-field">
                <label>Examination / Degree</label>
                <select name="education[graduation][exam]">
                  <option value="">— Select —</option>
                  @foreach(['B.Sc in Engineering', 'B.Sc (Honours)', 'B.Sc (Pass)', 'B.A (Honours)', 'B.S.S', 'B.B.A', 'Fazil', 'Equivalent'] as $ex)
                    <option value="{{ $ex }}" {{ old('education.graduation.exam') === $ex ? 'selected' : '' }}>{{ $ex }}</option>
                  @endforeach
                </select>
              </div>
              <div class="tt-field">
                <label>Subject / Major</label>
                <input type="text" name="education[graduation][group]" value="{{ old('education.graduation.group') }}" placeholder="e.g. CSE / EEE / Mechanical">
              </div>
              <div class="tt-field">
                <label>University / Institute</label>
                <input type="text" name="education[graduation][board]" value="{{ old('education.graduation.board') }}" placeholder="University Name">
              </div>
              <div class="tt-field">
                <label>Result (CGPA / Class)</label>
                <input type="text" name="education[graduation][result]" value="{{ old('education.graduation.result') }}" placeholder="e.g. CGPA 3.50">
              </div>
              <div class="tt-field">
                <label>Passing Year</label>
                <input type="number" name="education[graduation][passing_year]" min="1980" max="{{ date('Y') }}" value="{{ old('education.graduation.passing_year') }}" placeholder="e.g. 2023">
              </div>
              <div class="tt-field">
                <label>Course Duration</label>
                <select name="education[graduation][duration]">
                  <option value="">— Select —</option>
                  <option value="03 Years" {{ old('education.graduation.duration') === '03 Years' ? 'selected' : '' }}>03 Years</option>
                  <option value="04 Years" {{ old('education.graduation.duration') === '04 Years' ? 'selected' : '' }}>04 Years</option>
                  <option value="05 Years" {{ old('education.graduation.duration') === '05 Years' ? 'selected' : '' }}>05 Years</option>
                </select>
              </div>
            </div>
          </div>

          {{-- Masters --}}
          <div class="tt-edu-box">
            <div class="tt-edu-title">Masters / Post-Graduation (স্নাতকোত্তর - ঐচ্ছিক)</div>
            <div class="tt-grid-3">
              <div class="tt-field">
                <label>Examination / Degree</label>
                <input type="text" name="education[masters][exam]" value="{{ old('education.masters.exam') }}" placeholder="e.g. M.Sc / MBA / MA / Kamil">
              </div>
              <div class="tt-field">
                <label>Subject / Major</label>
                <input type="text" name="education[masters][group]" value="{{ old('education.masters.group') }}" placeholder="Subject Name">
              </div>
              <div class="tt-field">
                <label>University / Institute</label>
                <input type="text" name="education[masters][board]" value="{{ old('education.masters.board') }}" placeholder="University Name">
              </div>
              <div class="tt-field">
                <label>Result (CGPA / Class)</label>
                <input type="text" name="education[masters][result]" value="{{ old('education.masters.result') }}" placeholder="e.g. CGPA 3.60">
              </div>
              <div class="tt-field">
                <label>Passing Year</label>
                <input type="number" name="education[masters][passing_year]" min="1980" max="{{ date('Y') }}" value="{{ old('education.masters.passing_year') }}" placeholder="e.g. 2025">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- 5. PROFESSIONAL EXPERIENCE --}}
      <div class="tt-section">
        <div class="tt-section-head">
          <span>💼 {{ $isBn ? '৫. চাকরির অভিজ্ঞতা ও অন্যান্য যোগ্যতা (Experience - প্রযোজ্য ক্ষেত্রে)' : '5. Professional Experience & Skills (If Applicable)' }}</span>
          <button type="button" onclick="addExperienceRow()" style="background:#0f5132;color:#fff;border:none;padding:4px 10px;border-radius:4px;font-size:12px;cursor:pointer;">+ Add Experience</button>
        </div>
        <div class="tt-section-body">
          <div id="experienceList">
            <div class="tt-edu-box experience-row">
              <div class="tt-grid-2">
                <div class="tt-field">
                  <label>Designation / Post Name</label>
                  <input type="text" name="experiences[0][designation]" value="{{ old('experiences.0.designation') }}" placeholder="e.g. Junior Instructor / Technician">
                </div>
                <div class="tt-field">
                  <label>Organization / Institution Name</label>
                  <input type="text" name="experiences[0][organization]" value="{{ old('experiences.0.organization') }}" placeholder="Institution or Company Name">
                </div>
                <div class="tt-field">
                  <label>From Date</label>
                  <input type="date" name="experiences[0][from_date]" value="{{ old('experiences.0.from_date') }}">
                </div>
                <div class="tt-field">
                  <label>To Date (Leave empty if currently working)</label>
                  <input type="date" name="experiences[0][to_date]" value="{{ old('experiences.0.to_date') }}">
                </div>
              </div>
              <div class="tt-field" style="margin-top:10px;">
                <label>Responsibilities / Key Duties</label>
                <input type="text" name="experiences[0][responsibilities]" value="{{ old('experiences.0.responsibilities') }}" placeholder="Brief summary of responsibilities...">
              </div>
            </div>
          </div>

          <div class="tt-field" style="margin-top:12px;">
            <label>Other Qualifications / NTVQF / BTEB CBT&A / Computer Literacy (অন্যান্য যোগ্যতা)</label>
            <textarea name="other_qualifications" rows="2" placeholder="e.g. CBT&A Level-4 Certified Assessor, MS Office, AutoCAD, Industrial Training...">{{ old('other_qualifications') }}</textarea>
          </div>
        </div>
      </div>

      {{-- 6. PHOTO, SIGNATURE & CV UPLOAD --}}
      <div class="tt-section">
        <div class="tt-section-head">
          <span>🖼️ {{ $isBn ? '৬. ছবি, স্বাক্ষর ও সিভি আপলোড (Photo, Signature & CV)' : '6. Photo, Signature & CV Upload' }}</span>
        </div>
        <div class="tt-section-body">
          <div class="tt-grid-3">
            <div class="tt-upload-box">
              <label style="font-weight:700;font-size:13px;color:#0f172a;display:block;margin-bottom:6px;">
                Applicant Photo <span style="color:#dc2626;">*</span>
              </label>
              <small style="display:block;color:#64748b;font-size:11px;margin-bottom:8px;">300 × 300 px recommended (JPG/PNG, Max 1MB)</small>
              <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required onchange="previewFile(this, 'photoPreview')" style="font-size:12px;max-width:100%;">
              <img id="photoPreview" class="tt-preview-img" style="width:110px;height:110px;object-fit:cover;">
            </div>

            <div class="tt-upload-box">
              <label style="font-weight:700;font-size:13px;color:#0f172a;display:block;margin-bottom:6px;">
                Applicant Signature <span style="color:#dc2626;">*</span>
              </label>
              <small style="display:block;color:#64748b;font-size:11px;margin-bottom:8px;">300 × 80 px recommended (JPG/PNG, Max 512KB)</small>
              <input type="file" name="signature" accept="image/jpeg,image/png,image/webp" required onchange="previewFile(this, 'sigPreview')" style="font-size:12px;max-width:100%;">
              <img id="sigPreview" class="tt-preview-img" style="width:180px;height:54px;object-fit:contain;">
            </div>

            <div class="tt-upload-box">
              <label style="font-weight:700;font-size:13px;color:#0f172a;display:block;margin-bottom:6px;">
                CV / Resume (Optional)
              </label>
              <small style="display:block;color:#64748b;font-size:11px;margin-bottom:8px;">PDF, DOC or DOCX (Max 5MB)</small>
              <input type="file" name="cv" accept=".pdf,.doc,.docx" style="font-size:12px;max-width:100%;">
            </div>
          </div>
        </div>
      </div>

      {{-- 7. DECLARATION --}}
      <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px;margin-bottom:20px;">
        <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;margin:0;">
          <input type="checkbox" name="declaration" value="1" required {{ old('declaration') ? 'checked' : '' }} style="margin-top:3px;width:17px;height:17px;accent-color:#0f5132;">
          <span style="font-size:13px;color:#14532d;line-height:1.5;">
            <strong>Declaration:</strong> I declare that the information provided in this form are correct, true and complete to the best of my knowledge and belief. If any information is found false, incorrect or incomplete at any stage, my candidature shall be liable to cancellation.
          </span>
        </label>
      </div>

      <div style="display:flex;justify-content:center;gap:14px;">
        <button type="submit" class="tt-btn-submit">
          <span>✅</span> {{ $isBn ? 'আবেদন জমা দিন (Submit Application)' : 'Submit Application' }}
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function syncCircularId(sel) {
  var opt = sel.options[sel.selectedIndex];
  document.getElementById('siteContentIdInput').value = opt ? (opt.getAttribute('data-id') || '') : '';
}

function toggleSpouseField() {
  var val = document.getElementById('maritalStatusSelect').value;
  document.getElementById('spouseNameWrap').style.display = (val === 'Married') ? 'block' : 'none';
}

function copyPresentAddress() {
  var checked = document.getElementById('sameAsPresentCheck').checked;
  var fields = ['care_of', 'village', 'district', 'upazila', 'post_office', 'post_code'];
  fields.forEach(function(f) {
    var src = document.getElementById('p_' + f);
    var dst = document.getElementById('perm_' + f);
    if (src && dst) {
      if (checked) {
        dst.value = src.value;
        dst.readOnly = true;
        if (dst.tagName === 'SELECT') dst.style.pointerEvents = 'none';
        dst.style.background = '#f1f5f9';
      } else {
        dst.readOnly = false;
        if (dst.tagName === 'SELECT') dst.style.pointerEvents = 'auto';
        dst.style.background = '#fff';
      }
    }
  });
}

// Keep Permanent synced if Same as Present is checked and user edits Present
['care_of', 'village', 'district', 'upazila', 'post_office', 'post_code'].forEach(function(f) {
  var el = document.getElementById('p_' + f);
  if (el) {
    el.addEventListener('input', function() {
      if (document.getElementById('sameAsPresentCheck').checked) {
        copyPresentAddress();
      }
    });
    el.addEventListener('change', function() {
      if (document.getElementById('sameAsPresentCheck').checked) {
        copyPresentAddress();
      }
    });
  }
});

var expIndex = 1;
function addExperienceRow() {
  var container = document.getElementById('experienceList');
  var div = document.createElement('div');
  div.className = 'tt-edu-box experience-row';
  div.innerHTML =
    '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">' +
      '<strong style="font-size:12.5px;color:#475569;">Experience #' + (expIndex + 1) + '</strong>' +
      '<button type="button" onclick="this.closest(\'.experience-row\').remove()" style="background:#fee2e2;color:#991b1b;border:none;padding:2px 8px;border-radius:4px;font-size:11px;cursor:pointer;">Remove</button>' +
    '</div>' +
    '<div class="tt-grid-2">' +
      '<div class="tt-field"><label>Designation / Post Name</label><input type="text" name="experiences[' + expIndex + '][designation]" placeholder="Designation"></div>' +
      '<div class="tt-field"><label>Organization Name</label><input type="text" name="experiences[' + expIndex + '][organization]" placeholder="Organization"></div>' +
      '<div class="tt-field"><label>From Date</label><input type="date" name="experiences[' + expIndex + '][from_date]"></div>' +
      '<div class="tt-field"><label>To Date</label><input type="date" name="experiences[' + expIndex + '][to_date]"></div>' +
    '</div>' +
    '<div class="tt-field" style="margin-top:10px;"><label>Responsibilities</label><input type="text" name="experiences[' + expIndex + '][responsibilities]" placeholder="Brief responsibilities..."></div>';
  container.appendChild(div);
  expIndex++;
}

function previewFile(input, imgId) {
  var img = document.getElementById(imgId);
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      img.src = e.target.result;
      img.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

document.addEventListener('DOMContentLoaded', function() {
  if (document.getElementById('sameAsPresentCheck').checked) {
    copyPresentAddress();
  }
});
</script>
@endsection
