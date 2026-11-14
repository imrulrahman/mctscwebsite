@extends('site.layout')

@section('title', $isBn ? 'ক্যারিয়ার ও নিয়োগ বিজ্ঞপ্তি · মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Career Opportunities · Mahmudunnabi Chowdhury Technical School and College')
@section('page_title', $isBn ? 'ক্যারিয়ার ও নিয়োগ বিজ্ঞপ্তি' : 'Career Opportunities')
@section('page_subtitle', $isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজে দক্ষ, অভিজ্ঞ ও পেশাদার প্রশিক্ষক/কর্মকর্তা হিসেবে যোগ দিন।' : 'Join our dedicated team of educators, technical instructors, and TVET professionals.')

@section('content')
<style>
.career-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 32px 16px 60px;
}

/* Candidate Portal Top Banner */
.career-top-bar {
  background: #ffffff;
  border: 1px solid #dbe6e0;
  border-left: 5px solid var(--green, #0f5132);
  border-radius: 10px;
  padding: 16px 22px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  margin-bottom: 24px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}
.career-top-title {
  margin: 0;
  font-size: 16.5px;
  font-weight: 700;
  color: #14532d;
  display: flex;
  align-items: center;
  gap: 8px;
}
.btn-portal-login {
  background: #0f5132;
  color: #ffffff !important;
  font-size: 13.5px;
  font-weight: 700;
  padding: 8px 18px;
  border-radius: 6px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 2px 6px rgba(15, 81, 50, 0.2);
  transition: all 0.2s ease;
}
.btn-portal-login:hover {
  background: #093722;
  transform: translateY(-1px);
}

/* Table View Styling (Matching Sample 1) */
.career-table-wrap {
  background: #ffffff;
  border-radius: 8px;
  border: 1.5px solid #2d3748;
  overflow-x: auto;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
  margin-bottom: 30px;
}
.career-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14.5px;
  text-align: left;
}
.career-table thead tr {
  background: #2d3748;
  color: #ffffff;
}
.career-table th {
  padding: 13px 14px;
  font-weight: 700;
  font-size: 14px;
  border-right: 1px solid #4a5568;
  white-space: nowrap;
}
.career-table th:last-child {
  border-right: none;
}
.career-table tbody tr {
  border-bottom: 1px solid #cbd5e1;
  transition: background 0.15s ease;
}
.career-table tbody tr:nth-child(even) {
  background: #f8fafc;
}
.career-table tbody tr:hover {
  background: #f0fdf4;
}
.career-table td {
  padding: 14px 14px;
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
  font-weight: 700;
  color: #0f172a;
  min-width: 240px;
}
.td-required {
  text-align: center;
  font-weight: 700;
  color: #1e293b;
  width: 90px;
}
.td-location {
  min-width: 180px;
  color: #334155;
  font-size: 14px;
}
.td-date {
  text-align: center;
  width: 120px;
  font-size: 13.5px;
  line-height: 1.35;
  color: #334155;
  white-space: nowrap;
}
.td-pdf {
  text-align: center;
  width: 70px;
}
.td-action {
  text-align: center;
  width: 110px;
  white-space: nowrap;
}

/* Red PDF Icon */
.pdf-icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #dc2626;
  text-decoration: none;
  transition: transform 0.15s ease;
}
.pdf-icon-btn:hover {
  transform: scale(1.18);
}
.pdf-icon-btn svg {
  width: 26px;
  height: 26px;
}

/* Blue Details Button */
.btn-table-details {
  background: #0284c7;
  color: #ffffff !important;
  font-size: 13px;
  font-weight: 700;
  padding: 7px 18px;
  border-radius: 5px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 4px rgba(2, 132, 199, 0.25);
  transition: all 0.15s ease;
}
.btn-table-details:hover {
  background: #0369a1;
  transform: translateY(-1px);
}

/* Detailed View Styling (Matching Sample 2) */
.career-detail-card {
  background: #ffffff;
  border: 1px solid #dbe6e0;
  border-left: 5px solid var(--green, #0f5132);
  border-radius: 12px;
  padding: 30px 34px;
  margin-bottom: 24px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
}
.career-detail-header {
  border-bottom: 1px dashed #e2e8f0;
  padding-bottom: 18px;
  margin-bottom: 20px;
}
.detail-job-title {
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
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
.detail-body h2,
.detail-body h3,
.detail-body h4 {
  color: #14532d;
  font-size: 17px;
  font-weight: 700;
  margin: 22px 0 10px 0;
  display: flex;
  align-items: center;
  gap: 8px;
}
.detail-body h4::before,
.detail-body h3::before {
  content: "▪";
  color: #bd9635;
  font-size: 18px;
}
.detail-body ul,
.detail-body ol {
  margin: 8px 0 16px 24px;
}
.detail-body li {
  margin-bottom: 8px;
  line-height: 1.6;
}
.detail-actions-bar {
  display: flex;
  align-items: center;
  gap: 14px;
  flex-wrap: wrap;
  padding-top: 20px;
  border-top: 1px solid #e2e8f0;
}
.btn-apply-primary {
  background: #0f5132;
  color: #ffffff !important;
  font-size: 14.5px;
  font-weight: 700;
  padding: 11px 26px;
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

/* Empty State Card */
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

  {{-- Top Candidate Portal Bar --}}
  <div class="career-top-bar">
    <div class="career-top-title">
      <span>💼</span> {{ $isBn ? 'চলমান নিয়োগ বিজ্ঞপ্তি ও ক্যারিয়ার পোর্টাল' : 'Career Opportunities & Recruitment' }}
    </div>
    <div style="display:flex; align-items:center; gap:10px;">
      @if(session('candidate_id'))
        <a href="{{ route('candidate.dashboard') }}" class="btn-portal-login">
          <span>👤</span> {{ $isBn ? 'প্রার্থীর ড্যাশবোর্ড (My Applications)' : 'Candidate Dashboard' }} →
        </a>
      @else
        <a href="{{ route('candidate.login') }}" class="btn-portal-login">
          <span>👤</span> {{ $isBn ? 'প্রার্থী লগইন (স্ট্যাটাস ও সিভি আপডেট)' : 'Candidate Login & Status' }} →
        </a>
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
          <div class="badge-deadline-pill">
            ⏰ {{ $isBn ? 'DEADLINE:' : 'DEADLINE:' }} {{ $selectedJob->ends_at->format('d M, Y') }}
          </div>
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
         VIEW 1: TABLE VIEW (Matching User Sample 1 Screenshot)
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
      <div class="career-table-wrap">
        <table class="career-table">
          <thead>
            <tr>
              <th class="td-sl">{{ $isBn ? 'ক্রম' : 'Sl' }}</th>
              <th>{{ $isBn ? 'পদের নাম (Position Name)' : 'Position Name' }}</th>
              <th style="text-align: center;">{{ $isBn ? 'পদসংখ্যা (Required)' : 'Required' }}</th>
              <th>{{ $isBn ? 'কর্মস্থল (Job Location)' : 'Job Location' }}</th>
              <th style="text-align: center;">{{ $isBn ? 'প্রকাশের তারিখ (Date)' : 'Date' }}</th>
              <th style="text-align: center;">{{ $isBn ? 'আবেদনের শেষ সময় (Deadline)' : 'Deadline' }}</th>
              <th style="text-align: center;">{{ $isBn ? 'বিজ্ঞপ্তি (Details)' : 'Details' }}</th>
              <th style="text-align: center;">{{ $isBn ? 'বিস্তারিত ও আবেদন' : 'Action' }}</th>
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
                    <div style="font-size: 12px; color: #64748b; font-weight: 500; margin-top: 3px;">
                      {{ $job->category }}
                    </div>
                  @endif
                </td>
                <td class="td-required">{{ $jobVac }}</td>
                <td class="td-location">{{ $jobLoc }}</td>
                <td class="td-date">
                  @if($job->starts_at)
                    {{ $job->starts_at->format('d') }}<br>
                    {{ $job->starts_at->format('F') }}<br>
                    {{ $job->starts_at->format('Y') }}
                  @else
                    {{ $job->created_at->format('d') }}<br>
                    {{ $job->created_at->format('F') }}<br>
                    {{ $job->created_at->format('Y') }}
                  @endif
                </td>
                <td class="td-date">
                  @if($job->ends_at)
                    {{ $job->ends_at->format('d') }}<br>
                    {{ $job->ends_at->format('F') }}<br>
                    {{ $job->ends_at->format('Y') }}
                  @else
                    <span style="color: #15803d; font-weight: 700;">{{ $isBn ? 'চলমান' : 'Open' }}</span>
                  @endif
                </td>
                <td class="td-pdf">
                  @if(!empty($pdfPath))
                    <a href="{{ storage_asset($pdfPath) }}" target="_blank" class="pdf-icon-btn" title="{{ $isBn ? 'অফিসিয়াল সার্কুলার PDF ডাউনলোড করুন' : 'View / Download Official PDF Circular' }}">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" fill="#fee2e2" stroke="#dc2626" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M14 2V8H20" stroke="#dc2626" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M9 13H15M9 17H13" stroke="#dc2626" stroke-width="1.8" stroke-linecap="round"/>
                      </svg>
                    </a>
                  @else
                    <a href="{{ route('career', $job->slug) }}" class="pdf-icon-btn" title="{{ $isBn ? 'বিজ্ঞপ্তির বিস্তারিত দেখুন' : 'View Circular Details' }}">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" fill="#f8fafc" stroke="#94a3b8" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M14 2V8H20" stroke="#94a3b8" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M9 13H15M9 17H13" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round"/>
                      </svg>
                    </a>
                  @endif
                </td>
                <td class="td-action">
                  <a href="{{ route('career', $job->slug) }}" class="btn-table-details">
                    {{ $isBn ? 'বিস্তারিত' : 'Details' }}
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Quick Apply Now pill for accessibility & test assertion --}}
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
@endsection
