@extends('site.layout')

@section('title', $isBn ? 'ক্যারিয়ার ও নিয়োগ বিজ্ঞপ্তি · মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Career Opportunities · Mahmudunnabi Chowdhury Technical School and College')
@section('page_title', $isBn ? 'ক্যারিয়ার ও নিয়োগ বিজ্ঞপ্তি' : 'Career Opportunities')
@section('page_subtitle', $isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজে দক্ষ, অভিজ্ঞ ও পেশাদার প্রশিক্ষক/কর্মকর্তা হিসেবে যোগ দিন।' : 'Join our dedicated team of educators, technical instructors, and TVET professionals.')

@section('content')
<style>
.career-container {
  max-width: 1120px;
  margin: 0 auto;
  padding: 32px 16px 60px;
}

/* ========================================================
   TOP HERO HEADER (Matching User Uploaded Mockup)
   ======================================================== */
.career-hero-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  margin-bottom: 28px;
  flex-wrap: wrap;
}
.career-hero-left {
  display: flex;
  align-items: center;
  gap: 18px;
  flex: 1;
  min-width: 320px;
}
.career-hero-icon {
  width: 58px;
  height: 58px;
  border-radius: 50%;
  background: #e6f7ee;
  border: 1px solid #bbf7d0;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.career-hero-title {
  margin: 0 0 6px 0;
  font-size: 27px;
  font-weight: 700;
  color: #14532d;
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
  line-height: 1.25;
}
.career-hero-desc {
  margin: 0;
  font-size: 13.5px;
  color: #64748b;
  line-height: 1.55;
  max-width: 620px;
}

/* Right Stack of Buttons (উপরে নিচে) */
.career-hero-right {
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: stretch;
  min-width: 220px;
}
.btn-hero-status {
  background: #ffffff;
  color: #0284c7;
  border: 1.5px solid #0284c7;
  font-size: 13.5px;
  font-weight: 600;
  padding: 9px 20px;
  border-radius: 6px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.2s ease;
  box-shadow: 0 1px 3px rgba(2, 132, 199, 0.08);
}
.btn-hero-status:hover {
  background: #f0f9ff;
  border-color: #0369a1;
  color: #0369a1;
}
.btn-hero-login {
  background: #0f5132;
  color: #ffffff !important;
  border: 1.5px solid #0f5132;
  font-size: 13.5px;
  font-weight: 700;
  padding: 9px 20px;
  border-radius: 6px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.2s ease;
  box-shadow: 0 2px 6px rgba(15, 81, 50, 0.2);
}
.btn-hero-login:hover {
  background: #093722;
  border-color: #093722;
  transform: translateY(-1px);
}
.btn-hero-white {
  background: #ffffff;
  color: #1e293b !important;
  border: 1.5px solid #cbd5e1;
  font-size: 13px;
  font-weight: 600;
  padding: 8px 18px;
  border-radius: 6px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.2s ease;
}
.btn-hero-white:hover {
  background: #f8fafc;
  border-color: #94a3b8;
  color: #0f5132 !important;
}

/* ========================================================
   STATUS CHECKER SEARCH BOX (Hidden by default, shown on click)
   ======================================================== */
.career-status-checker-card {
  background: #ffffff;
  border: 1.5px solid #0284c7;
  border-radius: 10px;
  padding: 20px 24px;
  margin-bottom: 26px;
  box-shadow: 0 6px 20px rgba(2, 132, 199, 0.1);
  animation: fadeInDown 0.25s ease-out;
}
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-8px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ========================================================
   VACANCIES CARD & TABLE (Matching User Mockup)
   ======================================================== */
.career-vacancies-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
  margin-bottom: 30px;
}
.vacancies-head {
  margin-bottom: 18px;
}
.vacancies-title {
  margin: 0;
  font-size: 21px;
  font-weight: 700;
  color: #0f172a;
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
}
.openings-pill {
  background: #ecfdf5;
  color: #059669;
  border: 1px solid #a7f3d0;
  font-size: 12px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 14px;
}
.vacancies-sub {
  margin: 4px 0 0 0;
  font-size: 13.5px;
  color: #64748b;
  line-height: 1.5;
}

.career-table-wrap {
  background: #ffffff;
  border-radius: 8px;
  border: 1.5px solid #2d3748;
  overflow-x: auto;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
  margin-bottom: 20px;
}
.career-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
  text-align: left;
}
.career-table thead tr {
  background: #2d3748;
  color: #ffffff;
}
.career-table th {
  padding: 12px 14px;
  font-weight: 700;
  font-size: 13.5px;
  border-right: 1px solid #4a5568;
  white-space: nowrap;
}
.career-table th:last-child {
  border-right: none;
}
.career-table tbody tr {
  border-bottom: 1px solid #e2e8f0;
  transition: background 0.15s ease;
}
.career-table tbody tr:nth-child(even) {
  background: #f8fafc;
}
.career-table tbody tr:hover {
  background: #f0fdf4;
}
.career-table td {
  padding: 13px 14px;
  vertical-align: middle;
  border-right: 1px solid #e2e8f0;
  color: #1e293b;
}
.career-table td:last-child {
  border-right: none;
}
.td-sl {
  text-align: center;
  font-weight: 700;
  color: #334155;
  width: 48px;
}
.td-position {
  font-weight: 600;
  color: #0f172a;
}
.td-required {
  text-align: center;
  font-weight: 700;
  color: #334155;
  width: 70px;
}
.td-location {
  color: #475569;
  font-size: 13.5px;
}
.td-date {
  text-align: center;
  color: #475569;
  font-size: 13px;
  white-space: nowrap;
}
.td-pdf {
  text-align: center;
  width: 70px;
}
.pdf-icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.15s ease;
}
.pdf-icon-btn:hover {
  transform: scale(1.15);
}
.pdf-icon-btn svg {
  width: 24px;
  height: 24px;
}
.td-action {
  text-align: center;
  width: 95px;
}
.btn-table-details {
  background: #0284c7;
  color: #ffffff !important;
  font-weight: 700;
  font-size: 13px;
  padding: 7px 16px;
  border-radius: 6px;
  text-decoration: none;
  display: inline-block;
  transition: all 0.15s ease;
  box-shadow: 0 1px 4px rgba(2, 132, 199, 0.25);
}
.btn-table-details:hover {
  background: #0369a1;
  transform: translateY(-1px);
}

/* Card Bottom Banner (Matching User Mockup) */
.career-card-footer {
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  border-radius: 8px;
  padding: 12px 18px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
}
.footer-left-msg {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
  color: #166534;
  font-weight: 500;
}
.footer-right-slogan {
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
  font-style: italic;
  font-size: 13px;
  color: #64748b;
  white-space: nowrap;
}

/* ========================================================
   DETAIL VIEW STYLING (Matching User Sample 2)
   ======================================================== */
.career-detail-card {
  background: #ffffff;
  border: 2px solid #0f5132;
  border-radius: 10px;
  padding: 30px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
  margin-bottom: 30px;
}
.career-detail-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  border-bottom: 2px solid #0f5132;
  padding-bottom: 18px;
  margin-bottom: 20px;
}
.detail-job-title {
  font-size: 24px;
  font-weight: 700;
  color: #14532d;
  margin: 0 0 10px 0;
}
.badge-deadline-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
  padding: 4px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.4px;
}
.detail-meta-bar {
  display: flex;
  align-items: center;
  gap: 24px;
  flex-wrap: wrap;
  background: #f8fafc;
  border: 1px solid #edf2f7;
  padding: 12px 18px;
  border-radius: 8px;
  margin-bottom: 24px;
  font-size: 14px;
  color: #334155;
}
.detail-meta-item {
  display: flex;
  align-items: center;
  gap: 8px;
}
.detail-meta-item strong {
  color: #0f5132;
}
.detail-info-block {
  margin-bottom: 20px;
  font-size: 15px;
  line-height: 1.7;
  color: #1e293b;
}
.detail-info-block p {
  margin: 0 0 8px 0;
}
.detail-info-block strong {
  color: #0f172a;
}
.detail-body {
  font-size: 15px;
  line-height: 1.8;
  color: #334155;
  margin-bottom: 30px;
  border-top: 1px solid #f1f5f9;
  padding-top: 20px;
}
.detail-actions-bar {
  display: flex;
  align-items: center;
  gap: 14px;
  flex-wrap: wrap;
  border-top: 1px solid #e2e8f0;
  padding-top: 22px;
}
.btn-apply-primary {
  background: #0f5132;
  color: #ffffff !important;
  font-size: 14px;
  font-weight: 700;
  padding: 10px 24px;
  border-radius: 8px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(15, 81, 50, 0.25);
  transition: all 0.2s ease;
}
.btn-apply-primary:hover {
  background: #093722;
  transform: translateY(-1px);
}
.btn-apply-email {
  background: #ffffff;
  color: #0f5132 !important;
  border: 1.5px solid #0f5132;
  font-size: 14px;
  font-weight: 700;
  padding: 10px 22px;
  border-radius: 8px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s ease;
}
.btn-apply-email:hover {
  background: #f0fdf4;
}
.btn-download-pdf {
  background: #fef2f2;
  color: #dc2626 !important;
  border: 1px solid #fecaca;
  font-size: 13.5px;
  font-weight: 700;
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s ease;
}
.btn-download-pdf:hover {
  background: #fee2e2;
}
.btn-back-list {
  background: #f1f5f9;
  color: #334155 !important;
  border: 1px solid #cbd5e1;
  font-size: 13.5px;
  font-weight: 600;
  padding: 10px 18px;
  border-radius: 8px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  margin-left: auto;
}
.btn-back-list:hover {
  background: #e2e8f0;
}

/* Empty State */
.career-empty-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 50px 30px;
  text-align: center;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
  max-width: 680px;
  margin: 30px auto 0;
}
</style>

<div class="career-container">
  {{-- Flash message --}}
  @if(session('status') || session('success'))
    <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 14px 18px; border-radius: 8px; margin-bottom: 22px; font-weight: 600; font-size: 14px;">
      ✓ {{ session('status') ?: session('success') }}
    </div>
  @endif

  {{-- ========================================================
       TOP HERO HEADER SECTION (Matching User Uploaded Image)
       ======================================================== --}}
  <div class="career-hero-bar">
    <div class="career-hero-left">
      <div class="career-hero-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0f5132" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
          <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
        </svg>
      </div>
      <div class="career-hero-text">
        <h1 class="career-hero-title">{{ $isBn ? 'ক্যারিয়ার ও নিয়োগ বিজ্ঞপ্তি' : 'Career Opportunities & Recruitment' }}</h1>
        <p class="career-hero-desc">
          {{ $isBn 
            ? 'আমাদের টিমে যোগ দিন এবং একটি দক্ষ, উদ্ভাবনী ও পেশাদার কারিগরি শিক্ষাপ্রতিষ্ঠানের অংশ হোন। নিচে উপলব্ধ নিয়োগ বিজ্ঞপ্তিগুলো দেখুন এবং আবেদন করুন।' 
            : 'Join our team and be part of a skilled, innovative and community-focused institution. Explore current job opportunities below and apply for a meaningful career with us.' }}
        </p>
      </div>
    </div>

    {{-- Right Buttons Stack (উপরে নিচে) --}}
    <div class="career-hero-right">
      @if(session('candidate_id') && isset($candidateApp))
        {{-- When Logged In: View my Resume, Update Resume, Application status --}}
        <button type="button" onclick="toggleStatusSearch()" class="btn-hero-status" title="{{ $isBn ? 'আবেদনের স্ট্যাটাস দেখতে ক্লিক করুন' : 'Click to view application status' }}">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <span>{{ $isBn ? 'Application Status' : 'Application Status' }}</span>
        </button>

        @if($candidateApp->cv_path)
          <a href="{{ storage_asset($candidateApp->cv_path) }}" target="_blank" class="btn-hero-white">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            <span>{{ $isBn ? 'View My Resume' : 'View My Resume' }}</span>
          </a>
        @else
          <a href="{{ route('candidate.dashboard') }}#update-profile-section" class="btn-hero-white">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            <span>{{ $isBn ? 'Upload Resume' : 'Upload Resume' }}</span>
          </a>
        @endif

        <a href="{{ route('candidate.dashboard') }}#update-profile-section" class="btn-hero-white">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
          <span>{{ $isBn ? 'Update Resume' : 'Update Resume' }}</span>
        </a>

        <a href="{{ route('candidate.dashboard') }}" class="btn-hero-login">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          <span>{{ $isBn ? 'ক্যান্ডিডেট ড্যাশবোর্ড' : 'Candidate Dashboard' }} →</span>
        </a>
      @else
        {{-- When Guest: View Application Status & Candidate Login --}}
        <button type="button" onclick="toggleStatusSearch()" class="btn-hero-status" title="{{ $isBn ? 'আবেদনের স্ট্যাটাস সার্চ বক্স খুলতে ক্লিক করুন' : 'Click to view application status search' }}">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <span>{{ $isBn ? 'View Application Status' : 'View Application Status' }}</span>
        </button>

        <a href="{{ route('candidate.login') }}" class="btn-hero-login">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          <span>{{ $isBn ? 'Candidate Login' : 'Candidate Login' }} →</span>
        </a>
      @endif
    </div>
  </div>

  {{-- ========================================================
       APPLICATION STATUS SEARCH BOX
       (Hidden by default; only shown when clicking Application Status)
       ======================================================== --}}
  <div class="career-status-checker-card" id="status-checker-box" style="{{ (session('tracking_result') || session('tracking_error')) ? 'display:block;' : 'display:none;' }}">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
      <h3 style="margin:0; font-size:15.5px; color:#14532d; display:flex; align-items:center; gap:8px;">
        <span>🔍</span> {{ $isBn ? 'আবেদনের স্ট্যাটাস যাচাই (View Application Status)' : 'View Application Status' }}
      </h3>
      <button type="button" onclick="toggleStatusSearch()" style="background:none; border:none; color:#64748b; font-size:13px; font-weight:600; cursor:pointer;">
        ✕ {{ $isBn ? 'বন্ধ করুন' : 'Close' }}
      </button>
    </div>
    <p style="font-size:12.5px; color:#64748b; margin:0 0 12px 0;">
      {{ $isBn ? 'আবেদন করার পর প্রাপ্ত ট্র্যাকিং নম্বরটি প্রবেশ করিয়ে স্ট্যাটাস দেখুন:' : 'Enter your Tracking ID to view the latest status of your application:' }}
    </p>

    <form method="POST" action="{{ route('candidate.check_status') }}" id="trackingStatusForm" style="display:flex; gap:10px; flex-wrap:wrap;">
      @csrf
      <div style="flex:1; min-width:240px;">
        <input type="text" name="tracking_no" id="tracking_no_input" value="{{ session('searched_tracking_no', (isset($candidateApp) ? $candidateApp->tracking_no : request('tracking_no'))) }}" placeholder="{{ $isBn ? 'ট্র্যাকিং আইডি লিখুন (যেমন: JOB-2026-XXXXXX)' : 'Enter Tracking ID (e.g. JOB-2026-XXXXXX)' }}" required style="width:100%; padding:10px 14px; border:2px solid #cbd5e1; border-radius:6px; font-size:14px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">
      </div>
      <button type="submit" id="btnCheckStatus" style="background:#0284c7; color:#fff; border:none; padding:10px 22px; border-radius:6px; font-weight:700; font-size:14px; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:background 0.2s ease;">
        <span>🔎</span> {{ $isBn ? 'স্ট্যাটাস দেখুন' : 'Check Status' }}
      </button>
    </form>

    {{-- Result Area (Dynamic via JS and Session Fallback) --}}
    <div id="statusResultContainer" style="{{ session('tracking_result') || session('tracking_error') ? '' : 'display:none;' }} margin-top:16px;">
      @if(session('tracking_error'))
        <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:8px; font-size:13.5px;">
          ❌ {{ session('tracking_error') }}
        </div>
      @endif

      @if(session('tracking_result'))
        @php $res = session('tracking_result'); @endphp
        <div style="background:#f0fdf4; border:1.5px solid #86efac; border-radius:8px; padding:16px; box-shadow:0 2px 8px rgba(16,185,129,0.08);">
          <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:10px;">
            <div style="font-size:16px; font-weight:700; color:#14532d;">
              {{ $res['post_name'] }}
            </div>
            <span style="background:#dcfce7; color:#15803d; border:1px solid #86efac; padding:4px 12px; border-radius:20px; font-size:12.5px; font-weight:700; text-transform:uppercase;">
              ● {{ $res['status_label'] }}
            </span>
          </div>
          <div style="font-size:13px; color:#334155; display:flex; gap:16px; flex-wrap:wrap; margin-bottom:12px;">
            <span>👤 <strong>{{ $res['applicant_name'] }}</strong></span>
            <span>🆔 <strong>{{ $res['tracking_no'] }}</strong></span>
            <span>📅 {{ $res['created_at'] }}</span>
          </div>
          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ $res['confirmation_url'] }}" target="_blank" style="background:#15803d; color:#fff; padding:6px 14px; border-radius:6px; text-decoration:none; font-size:12.5px; font-weight:600;">
              📄 {{ $isBn ? 'আবেদনপত্র দেখুন / প্রিন্ট' : 'View Application Copy' }} ↗
            </a>
            <a href="{{ $res['portal_url'] }}" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; padding:6px 14px; border-radius:6px; text-decoration:none; font-size:12.5px; font-weight:600;">
              🔑 {{ $isBn ? 'প্রার্থী পোর্টালে লগইন ও সিভি আপডেট' : 'Login to Portal' }} →
            </a>
          </div>
        </div>
      @endif
    </div>
  </div>

  @if($selectedJob)
    {{-- ========================================================
         VIEW 2: DETAILED CIRCULAR VIEW (Matching User Sample 2)
         ======================================================== --}}
    <div style="margin-bottom: 16px;">
      <a href="{{ route('career') }}" style="display:inline-flex; align-items:center; gap:6px; color:#0f5132; font-weight:700; font-size:14px; text-decoration:none;">
        ← {{ $isBn ? 'সকল নিয়োগ বিজ্ঞপ্তির তালিকায় ফিরুন' : 'Back to All Circulars' }}
      </a>
    </div>

    <article class="career-detail-card" id="job-{{ $selectedJob->id }}">
      <div class="career-detail-header">
        <h1 class="detail-job-title" data-live-edit data-live-content-id="{{ $selectedJob->id }}" data-live-content-field="title" data-live-type="text" data-live-title="Job: {{ $selectedJob->title }}" data-live-location="Career">{{ $selectedJob->title }}</h1>
        @if($selectedJob->ends_at)
          @if($selectedJob->ends_at->endOfDay()->isPast())
            <div class="badge-deadline-pill" style="background:#fee2e2; color:#991b1b; border-color:#fca5a5;">
              ⏰ {{ $isBn ? 'সময়সীমা উত্তীর্ণ (DEADLINE EXPIRED):' : 'DEADLINE EXPIRED:' }} {{ $selectedJob->ends_at->format('d M, Y') }}
            </div>
          @else
            <div class="badge-deadline-pill">
              ⏰ {{ $isBn ? 'DEADLINE:' : 'DEADLINE:' }} {{ $selectedJob->ends_at->format('d M, Y') }}
            </div>
          @endif
        @else
          <div class="badge-deadline-pill" style="background:#eff6ff; color:#1e40af; border-color:#bfdbfe;">
            ● {{ $isBn ? 'আবেদন চলছে' : 'ACTIVE / OPEN' }}
          </div>
        @endif
      </div>

      @php
        $jobLoc = $selectedJob->metadata['job_location'] ?? 'Sagorika, Pahartali, Chattogram';
        $jobVac = $selectedJob->metadata['vacancies'] ?? '1';
        $pdfPath = $selectedJob->metadata['pdf_circular'] ?? null;
      @endphp

      <div class="detail-meta-bar">
        <div class="detail-meta-item">
          <span>📍</span>
          <div><strong>{{ $isBn ? 'Location:' : 'Location:' }}</strong> {{ $jobLoc }}</div>
        </div>
        <div class="detail-meta-item">
          <span>📅</span>
          <div><strong>{{ $isBn ? 'Published:' : 'Published:' }}</strong> {{ $selectedJob->starts_at ? $selectedJob->starts_at->format('d M, Y') : $selectedJob->created_at->format('d M, Y') }}</div>
        </div>
        <div class="detail-meta-item">
          <span>⏳</span>
          <div><strong>{{ $isBn ? 'Deadline:' : 'Deadline:' }}</strong> {{ $selectedJob->ends_at ? $selectedJob->ends_at->format('d M, Y') : ($isBn ? 'চলমান' : 'Until Filled') }}</div>
        </div>
      </div>

      <div class="detail-info-block">
        <p><strong>{{ $isBn ? 'Number of vacancies:' : 'Number of vacancies:' }}</strong> {{ $jobVac }}</p>
        <p><strong>{{ $isBn ? 'Workplace:' : 'Workplace:' }}</strong> {{ $jobLoc }}</p>
      </div>

      @if($selectedJob->excerpt)
        <div data-live-edit data-live-content-id="{{ $selectedJob->id }}" data-live-content-field="excerpt" data-live-type="textarea" data-live-title="Job Summary: {{ $selectedJob->title }}" data-live-location="Career" style="font-weight: 600; color: #1e293b; font-size: 15px; margin-bottom: 16px; line-height: 1.6;">
          {{ $selectedJob->excerpt }}
        </div>
      @endif

      @if($selectedJob->body)
        <div class="detail-body" data-live-edit data-live-content-id="{{ $selectedJob->id }}" data-live-content-field="body" data-live-type="rich" data-live-title="Circular Body: {{ $selectedJob->title }}" data-live-location="Career">
          @if(strip_tags($selectedJob->body) !== $selectedJob->body)
            {!! $selectedJob->body !!}
          @else
            {!! nl2br(e($selectedJob->body)) !!}
          @endif
        </div>
      @endif

      <div class="detail-actions-bar">
        @if($selectedJob->ends_at && $selectedJob->ends_at->endOfDay()->isPast())
          <div style="background:#fee2e2; border:1.5px solid #f87171; color:#991b1b; padding:12px 18px; border-radius:8px; font-weight:700; font-size:13.5px; display:inline-flex; align-items:center; gap:8px;">
            <span>⛔</span>
            <span>{{ $isBn ? 'এই নিয়োগ বিজ্ঞপ্তির আবেদনের নির্ধারিত সময়সীমা (' . $selectedJob->ends_at->format('d M, Y') . ') শেষ হয়ে গেছে। বর্তমানে আর আবেদন গ্রহণ করা হচ্ছে না।' : 'The application deadline for this position (' . $selectedJob->ends_at->format('d M, Y') . ') has expired. New applications are no longer accepted.' }}</span>
          </div>
        @else
          <a href="{{ route('career.apply', ['job' => $selectedJob->slug]) }}" class="btn-apply-primary">
            <span>📝</span> {{ $isBn ? 'অনলাইনে আবেদন করুন (Apply Now)' : 'Apply Now' }}
          </a>

          @php
            $gmailTo = 'mnctsc@gmail.com';
            $gmailSubject = 'Application for ' . $selectedJob->title;
            $gmailBody = "Dear Hiring Committee,\n\nI am writing to apply for the position of {$selectedJob->title} at Mahmudunnabi Chowdhury Technical School and College.\n\nPlease find my CV/resume attached.\n\nSincerely,\n[Your Name]\n[Your Phone Number]";
            $gmailUrl = 'https://mail.google.com/mail/?view=cm&fs=1&to=' . urlencode($gmailTo) . '&su=' . urlencode($gmailSubject) . '&body=' . urlencode($gmailBody);
          @endphp
          <a href="{{ $gmailUrl }}" target="_blank" rel="noopener" class="btn-apply-email">
            <span>✉️</span> {{ $isBn ? 'Apply via Email' : 'Apply via Email' }}
          </a>
        @endif

        @if(!empty($pdfPath))
          <a href="{{ storage_asset($pdfPath) }}" target="_blank" class="btn-download-pdf">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
            {{ $isBn ? 'মূল সার্কুলার ডাউনলোড (PDF)' : 'Download Circular PDF' }}
          </a>
        @endif

        <a href="{{ route('career') }}" class="btn-back-list">
          ← {{ $isBn ? 'তালিকা দেখুন' : 'Back to Circulars' }}
        </a>
      </div>
    </article>

  @else
    {{-- ========================================================
         VIEW 1: TABLE VIEW (Matching User Mockup in Exact Detail)
         ======================================================== --}}
    @if($circulars->isEmpty())
      <!-- EMPTY STATE WHEN NO RECRUITMENT CIRCULAR -->
      <div class="career-empty-card">
        <div style="font-size: 50px; margin-bottom: 14px;">💼</div>
        <h3 style="font-family: var(--serif, 'Libre Baskerville', Georgia, serif); font-size: 22px; color: #1e293b; margin: 0 0 10px 0; font-weight: 700;">
          {{ $isBn ? 'বর্তমানে কোনো নিয়োগ বিজ্ঞপ্তি নেই' : 'No recruitment circular is available' }}
        </h3>
        <p style="font-size: 14.5px; color: #64748b; line-height: 1.6; max-width: 520px; margin: 0 auto 20px;">
          {{ $isBn 
            ? 'এই মুহূর্তে প্রতিষ্ঠানে কোনো উন্মুক্ত নিয়োগ বিজ্ঞপ্তি প্রকাশিত নেই। নতুন কোনো পদের সার্কুলার প্রকাশিত হলে এখানে তা দেখতে পাবেন। স্বতঃপ্রণোদিত সিভি পাঠাতে নিচে দেওয়া ইমেইলে যোগাযোগ করতে পারেন।'
            : 'There are currently no active job vacancies or recruitment circulars at this moment. Please check back later or send your spontaneous CV for future opportunities.' }}
        </p>
        <div style="display: inline-flex; align-items: center; gap: 10px; background: #f8fafc; border: 1px solid #cbd5e1; padding: 10px 20px; border-radius: 30px; font-size: 13.5px; color: #1e293b;">
          <span>📧 {{ $isBn ? 'সিভি পাঠাতে:' : 'Direct CV submission:' }}</span>
          <a href="https://mail.google.com/mail/?view=cm&fs=1&to=mnctsc%40gmail.com" target="_blank" rel="noopener" style="color: #0f5132; font-weight: 700; text-decoration: none;">mnctsc@gmail.com</a>
        </div>
      </div>
    @else
      <div class="career-vacancies-card">
        {{-- Card Header --}}
        <div class="vacancies-head">
          <div style="display:flex; align-items:center; gap:12px; margin-bottom:4px;">
            <h2 class="vacancies-title">{{ $isBn ? 'চলমান নিয়োগ বিজ্ঞপ্তি' : 'Current Job Vacancies' }}</h2>
            <span class="openings-pill">{{ $circulars->count() }} {{ $isBn ? 'টি পদ' : 'openings' }}</span>
          </div>
          <p class="vacancies-sub">
            {{ $isBn 
              ? 'নিচে উপলব্ধ পদসমূহের বিবরণ দেখুন। সম্পূর্ণ তথ্য এবং আবেদনের নিয়ম দেখতে বিস্তারিত (Details) বাটনে ক্লিক করুন।' 
              : 'Find details of available positions below. Click on Details to view the full information and application instructions.' }}
          </p>
        </div>

        {{-- Table --}}
        <div class="career-table-wrap">
          <table class="career-table">
            <thead>
              <tr>
                <th class="td-sl">{{ $isBn ? 'SL' : 'SL' }}</th>
                <th>{{ $isBn ? 'Position Name' : 'Position Name' }}</th>
                <th style="text-align: center;">{{ $isBn ? 'Required' : 'Required' }}</th>
                <th>{{ $isBn ? 'Job Location' : 'Job Location' }}</th>
                <th style="text-align: center;">{{ $isBn ? 'Posted' : 'Posted' }}</th>
                <th style="text-align: center;">{{ $isBn ? 'Deadline' : 'Deadline' }}</th>
                <th style="text-align: center;">{{ $isBn ? 'Details' : 'Details' }}</th>
                <th style="text-align: center;">{{ $isBn ? 'Action' : 'Action' }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($circulars as $idx => $job)
                @php
                  $jobLoc = $job->metadata['job_location'] ?? 'Sagorika, Pahartali, Chattogram';
                  $jobVac = $job->metadata['vacancies'] ?? '1';
                  $pdfPath = $job->metadata['pdf_circular'] ?? null;
                @endphp
                <tr id="job-{{ $job->id }}">
                  <td class="td-sl">{{ $idx + 1 }}</td>
                  <td class="td-position">
                    <a href="{{ route('career', $job->slug) }}" style="color: #0f172a; text-decoration: none; font-weight: 700;">
                      {{ $job->title }}
                    </a>
                    @if($job->category)
                      <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 2px;">
                        {{ $job->category }}
                      </div>
                    @endif
                  </td>
                  <td class="td-required">{{ $jobVac }}</td>
                  <td class="td-location">{{ $jobLoc }}</td>
                  <td class="td-date">
                    @if($job->starts_at)
                      {{ $job->starts_at->format('d M Y') }}
                    @else
                      {{ $job->created_at->format('d M Y') }}
                    @endif
                  </td>
                  <td class="td-date">
                    @if($job->ends_at)
                      {{ $job->ends_at->format('d M Y') }}
                    @else
                      <span style="color: #15803d; font-weight: 700;">{{ $isBn ? 'চলমান' : 'Open' }}</span>
                    @endif
                  </td>
                  <td class="td-pdf">
                    @if(!empty($pdfPath))
                      <a href="{{ storage_asset($pdfPath) }}" target="_blank" class="pdf-icon-btn" title="{{ $isBn ? 'অফিসিয়াল সার্কুলার PDF ডাউনলোড করুন' : 'View / Download Official PDF Circular' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" fill="#ffffff" stroke="#94a3b8" stroke-width="1.8" stroke-linejoin="round"/>
                          <path d="M14 2V8H20" stroke="#94a3b8" stroke-width="1.8" stroke-linejoin="round"/>
                          <path d="M9 13H15M9 17H13" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                      </a>
                    @else
                      <a href="{{ route('career', $job->slug) }}" class="pdf-icon-btn" title="{{ $isBn ? 'বিজ্ঞপ্তির বিস্তারিত দেখুন' : 'View Circular Details' }}">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" fill="#ffffff" stroke="#94a3b8" stroke-width="1.8" stroke-linejoin="round"/>
                          <path d="M14 2V8H20" stroke="#94a3b8" stroke-width="1.8" stroke-linejoin="round"/>
                          <path d="M9 13H15M9 17H13" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                      </a>
                    @endif
                  </td>
                  <td class="td-action">
                    <a href="{{ route('career', $job->slug) }}" class="btn-table-details">
                      {{ $isBn ? 'Details' : 'Details' }}
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Footer Banner inside Card --}}
        <div class="career-card-footer">
          <div class="footer-left-msg">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            <span>{{ $isBn ? 'আমরা যোগ্য ও উদ্যমী ব্যক্তিদের কারিগরি শিক্ষা ও দক্ষতা উন্নয়নে আবেদন করার জন্য উৎসাহিত করি।' : 'We encourage qualified and motivated individuals to apply and contribute to technical education and skills development in Bangladesh.' }}</span>
          </div>
          <div class="footer-right-slogan">
            Build Skills. Build Futures.
          </div>
        </div>
      </div>

      {{-- Hidden accessible link for direct application --}}
      <div style="text-align: right; margin-top: -10px; margin-bottom: 20px;">
        <span style="font-size: 13px; color: #64748b;">
          {{ $isBn ? 'সরাসরি অনলাইনে আবেদন করতে চান?' : 'Want to submit direct online application?' }}
        </span>
        <a href="{{ route('career.apply') }}" style="color: #0f5132; font-weight: 700; font-size: 13.5px; margin-left: 6px; text-decoration: underline;">
          Apply Now →
        </a>
      </div>
    @endif
  @endif
</div>

<script>
function toggleStatusSearch() {
  const box = document.getElementById('status-checker-box');
  if (!box) return;
  if (box.style.display === 'none' || getComputedStyle(box).display === 'none') {
    box.style.display = 'block';
    box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    const inp = document.getElementById('tracking_no_input');
    if (inp) {
      setTimeout(function() { inp.focus(); }, 150);
    }
  } else {
    box.style.display = 'none';
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('trackingStatusForm');
  const resContainer = document.getElementById('statusResultContainer');
  const btn = document.getElementById('btnCheckStatus');
  const input = document.getElementById('tracking_no_input');

  if (form && resContainer) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const trackingNo = (input.value || '').trim();
      if (!trackingNo) return;

      btn.disabled = true;
      btn.innerHTML = '<span>⏳</span> {{ $isBn ? "যাচাই হচ্ছে..." : "Checking..." }}';
      resContainer.style.display = 'block';
      resContainer.innerHTML = '<div style="background:#f8fafc; border:1px solid #cbd5e1; padding:12px; border-radius:6px; color:#475569; font-size:13px;">🔎 {{ $isBn ? "স্ট্যাটাস অনুসন্ধান করা হচ্ছে..." : "Searching application status..." }}</div>';

      fetch('{{ route("candidate.check_status") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ tracking_no: trackingNo })
      })
      .then(res => res.json().then(data => ({ status: res.status, body: data })))
      .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<span>🔎</span> {{ $isBn ? "স্ট্যাটাস দেখুন" : "Check Status" }}';
        if (res.status === 200 && res.body.success) {
          const d = res.body;
          resContainer.innerHTML = `
            <div style="background:#f0fdf4; border:1.5px solid #86efac; border-radius:8px; padding:16px; box-shadow:0 2px 8px rgba(16,185,129,0.08);">
              <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:10px;">
                <div style="font-size:16px; font-weight:700; color:#14532d;">
                  ${d.post_name}
                </div>
                <span style="background:#dcfce7; color:#15803d; border:1px solid #86efac; padding:4px 12px; border-radius:20px; font-size:12.5px; font-weight:700; text-transform:uppercase;">
                  ● ${d.status_label}
                </span>
              </div>
              <div style="font-size:13px; color:#334155; display:flex; gap:16px; flex-wrap:wrap; margin-bottom:12px;">
                <span>👤 <strong>${d.applicant_name}</strong></span>
                <span>🆔 <strong>${d.tracking_no}</strong></span>
                <span>📅 ${d.created_at}</span>
              </div>
              <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="${d.confirmation_url}" target="_blank" style="background:#15803d; color:#fff; padding:6px 14px; border-radius:6px; text-decoration:none; font-size:12.5px; font-weight:600;">
                  📄 {{ $isBn ? 'আবেদনপত্র দেখুন / প্রিন্ট' : 'View Application Copy' }} ↗
                </a>
                <a href="${d.portal_url}" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; padding:6px 14px; border-radius:6px; text-decoration:none; font-size:12.5px; font-weight:600;">
                  🔑 {{ $isBn ? 'প্রার্থী পোর্টালে লগইন ও সিভি আপডেট' : 'Login to Portal' }} →
                </a>
              </div>
            </div>
          `;
        } else {
          resContainer.innerHTML = `
            <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:8px; font-size:13.5px;">
              ❌ ${res.body.message || 'No application found with this Tracking ID.'}
            </div>
          `;
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<span>🔎</span> {{ $isBn ? "স্ট্যাটাস দেখুন" : "Check Status" }}';
        form.submit();
      });
    });
  }
});
</script>
@endsection
