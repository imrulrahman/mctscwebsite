@extends('site.layout')

@section('title', $isBn ? 'প্রার্থীর ড্যাশবোর্ড · মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Candidate Dashboard · Mahmudunnabi Chowdhury Technical School and College')
@section('page_title', $isBn ? 'প্রার্থীর ড্যাশবোর্ড ও সিভি ম্যানেজমেন্ট' : 'Candidate Dashboard & CV Management')
@section('page_subtitle', $isBn ? 'আপনার চাকরির আবেদনের স্ট্যাটাস ও হালনাগাদ সিভি নিয়ন্ত্রণ করুন।' : 'Track your job application progress and keep your resume up to date.')

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
  width: 56px;
  height: 56px;
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
  grid-template-columns: 2fr 1fr;
  gap: 24px;
}
@media (max-width: 860px) {
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
  background: #eff6ff;
  color: #1d4ed8;
  border: 1px solid #bfdbfe;
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
  background: #ecfdf5;
  color: #047857;
  border: 1px solid #a7f3d0;
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
  padding: 18px;
  text-align: center;
  margin-top: 14px;
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
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  margin-top: 12px;
  transition: background 0.2s ease;
}
.btn-update-cv:hover {
  background: #093722;
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
          <img src="{{ storage_asset($primaryApp->photo_path) }}" alt="{{ $primaryApp->applicant_name }}">
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
    <div style="display:flex; align-items:center; gap:10px;">
      <a href="{{ route('career') }}" class="btn-view-app" style="background:#f8fafc; border-color:#cbd5e1; color:#334155 !important;">
        💼 {{ $isBn ? 'নিয়োগ বিজ্ঞপ্তি সমূহ' : 'View Circulars' }}
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
    {{-- Left Col: Submitted Applications & Real-time Status --}}
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
              default => ($isBn ? 'জমা হয়েছে (যাচাই চলছে)' : 'Under Review / New'),
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
                <strong>📢 {{ $isBn ? 'কর্তৃপক্ষের মন্তব্য / বার্তা:' : 'Notice from Management:' }}</strong>
                {{ $app->admin_notes }}
              </div>
            @endif

            <div style="margin-top: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
              <a href="{{ route('career.apply.confirmation', $app->tracking_no) }}" target="_blank" class="btn-view-app">
                <span>📄</span> {{ $isBn ? 'আবেদনপত্র দেখুন / প্রিন্ট' : 'View / Print Application' }} ↗
              </a>
              @if($app->cv_path)
                <a href="{{ storage_asset($app->cv_path) }}" target="_blank" class="btn-view-app" style="background:#eff6ff; border-color:#bfdbfe; color:#1e40af !important;">
                  <span>📎</span> {{ $isBn ? 'সংযুক্ত সিভি ডাউনলোড' : 'Download CV' }}
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
    </div>

    {{-- Right Col: Update CV & Profile Info --}}
    <div>
      {{-- Update CV Card --}}
      <div class="cand-card">
        <h3 class="cand-card-title">
          <span>📄 {{ $isBn ? 'হালনাগাদ সিভি আপলোড' : 'Update Resume / CV' }}</span>
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
            💾 {{ $isBn ? 'নতুন সিভি সংরক্ষণ ও আপডেট করুন' : 'Save & Update CV' }}
          </button>
        </form>
      </div>

      {{-- Candidate Profile Summary Card --}}
      <div class="cand-card">
        <h3 class="cand-card-title">
          <span>👤 {{ $isBn ? 'প্রার্থীর ব্যক্তিগত বিবরণ' : 'Personal Information' }}</span>
        </h3>
        <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
          <tbody>
            <tr style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 6px 0; color: #64748b; width: 40%;">{{ $isBn ? 'পিতার নাম' : 'Father:' }}</td>
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
            <tr>
              <td style="padding: 6px 0; color: #64748b; vertical-align: top;">{{ $isBn ? 'ঠিকানা' : 'Address:' }}</td>
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
@endsection
