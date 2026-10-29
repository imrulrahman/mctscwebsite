@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
  $f1_img = storage_asset($settings['facility_1_image'] ?? null, 'assets/Computer Lab r.jpg');
  $f2_img = storage_asset($settings['facility_2_image'] ?? null, 'assets/RAC Lab.jpg');
  $f3_img = storage_asset($settings['facility_3_image'] ?? null, 'assets/Plumbing Class.jpg');
  $f4_img = storage_asset($settings['facility_4_image'] ?? null, 'assets/SMO Lab.jpg');
  $campusImg = storage_asset($settings['about_image'] ?? null, 'assets/about_mahmudunnabi_legacy.jpg');
  if (empty($settings['about_image']) || $settings['about_image'] === 'assets/MC Building Photo.jpg') {
      $campusImg = asset('assets/about_mahmudunnabi_legacy.jpg');
  }
@endphp
@extends('site.layout')

@section('title', $settings['infr_tab_title'] ?? ($isBn ? 'ক্যাম্পাস অবকাঠামো · মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Infrastructure · Mahmudunnabi Chowdhury Technical School and College'))
@section('page_title', $settings['infr_page_title'] ?? ($isBn ? 'ক্যাম্পাস অবকাঠামো ও সুবিধাসমূহ' : 'Campus Infrastructure & Facilities'))
@section('page_subtitle', $settings['infr_page_subtitle'] ?? ($isBn ? 'ব্যবহারিক প্রশিক্ষণ, আধুনিক ল্যাবরেটরি ও সহায়ক শিখন পরিবেশ' : 'Comprehensive facilities for classroom instruction, practical training, and learner support.'))

@section('content')
<style>
.infr-facility-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
  margin-top: 18px;
  align-items: stretch;
}
.infr-facility-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-left: 4px solid var(--green);
  border-radius: 8px;
  padding: 11px 15px;
  display: flex;
  gap: 12px;
  align-items: flex-start;
  box-shadow: 0 2px 5px rgba(0,0,0,0.02);
  transition: transform 0.2s, box-shadow 0.2s;
  height: 100%;
  box-sizing: border-box;
}
.infr-facility-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 14px rgba(15,81,50,0.07);
}
.infr-icon-box {
  width: 36px;
  height: 36px;
  border-radius: 6px;
  background: rgba(15, 81, 50, 0.08);
  color: var(--green);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 2px;
}
.infr-facility-card > div:last-child {
  flex: 1;
  min-width: 0;
}
.infr-facility-card h4 {
  font-family: var(--sans);
  font-size: 15px;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 3px 0;
  line-height: 1.3;
}
.infr-facility-card p {
  font-size: 13.5px;
  line-height: 1.45;
  color: #475569;
  margin: 0;
}
.capacity-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14.5px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
}
.capacity-table th {
  background: var(--green);
  color: #ffffff;
  padding: 12px 18px;
  text-align: left;
  font-weight: 700;
  font-size: 14px;
}
.capacity-table td {
  padding: 12px 18px;
  border-bottom: 1px solid #edf2f7;
  color: #334155;
}
.capacity-table tr:nth-child(even) {
  background: #f8fafc;
}
.capacity-badge {
  display: inline-block;
  font-weight: 700;
  background: rgba(15, 81, 50, 0.08);
  color: var(--green);
  padding: 3px 10px;
  border-radius: 4px;
  border: 1px solid rgba(15, 81, 50, 0.15);
}
@media (max-width: 768px) {
  .infr-facility-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<section class="section" style="padding-top: 36px; padding-bottom: 50px;">
  <div class="container">

    <!-- 1. OVERVIEW (CMS POWERED & 100% EDITABLE) -->
    <div style="background: #fdfbf7; border: 1px solid #f1e6d0; border-left: 5px solid var(--gold); border-radius: 10px; padding: 28px 32px; margin-bottom: 46px; box-shadow: 0 4px 18px rgba(197, 155, 39, 0.06);">
      <span class="section-kicker" style="color: var(--gold);">{{ $settings['infr_overview_kicker'] ?? ($isBn ? 'ক্যাম্পাস পরিচিতি' : 'CAMPUS OVERVIEW') }}</span>
      <h2 data-live-edit data-live-key="infr_overview_title" data-live-type="text" data-live-title="অবকাঠামো পরিচিতি শিরোনাম" data-live-location="Infrastructure পেজ - পরিচিতি" style="font-family: var(--serif); font-size: 26px; color: var(--green); margin: 4px 0 12px 0;">
        {{ $settings['infr_overview_title'] ?? ($isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ ভবন ও সুবিধাসমূহ' : 'Two-Storey Campus Infrastructure') }}
      </h2>
      <p data-live-edit data-live-key="{{ $isBn ? 'infr_overview_desc_bn' : 'infr_overview_desc' }}" data-live-type="textarea" data-live-title="অবকাঠামো পরিচিতি বিবরণ" data-live-location="Infrastructure পেজ - পরিচিতি" style="font-size: 16px; line-height: 1.8; color: #334155; margin: 0;">
        @if($isBn && !empty($settings['infr_overview_desc_bn']))
          {{ $settings['infr_overview_desc_bn'] }}
        @elseif(!empty($settings['infr_overview_desc']))
          {{ $settings['infr_overview_desc'] }}
        @else
          {{ $isBn 
            ? 'MCTSC একটি দুই তলা ভবনে পরিচালিত হয়, যেখানে শ্রেণীকক্ষ নির্দেশনা, ব্যবহারিক প্রশিক্ষণ, প্রশাসন এবং শিক্ষার্থীদের সহায়তা সুবিধার সুব্যবস্থা রয়েছে।' 
            : 'MCTSC operates from a two-storey building with facilities for classroom instruction, practical training, administration, and learner support.' }}
        @endif
      </p>
    </div>

    <!-- 2. TRAINING FACILITIES (CMS POWERED & 100% EDITABLE) -->
    <div style="margin-bottom: 50px;">
      <span class="section-kicker">{{ $settings['infr_facilities_kicker'] ?? ($isBn ? 'প্রশিক্ষণ অবকাঠামো' : 'FACILITIES') }}</span>
      <h2 style="font-family: var(--serif); font-size: 28px; color: var(--green); margin: 6px 0 6px 0;">
        {{ $settings['infr_facilities_title'] ?? ($isBn ? 'প্রশিক্ষণ সুবিধাসমূহ' : 'Training Facilities') }}
      </h2>
      <div class="gold-rule" style="width: 45px; height: 3px; background: var(--gold); margin: 0 0 20px 0;"></div>

      <div class="infr-facility-grid">
        <!-- 1. Theory Classrooms -->
        <div class="infr-facility-card">
          <div class="infr-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
              <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
            </svg>
          </div>
          <div>
            <h4>{{ $settings['infr_fac_1_title'] ?? ($isBn ? 'তত্ত্বীয় শ্রেণীকক্ষ' : 'Theory Classrooms') }}</h4>
            <p>{{ $settings['infr_fac_1_desc'] ?? ($isBn ? 'তাত্ত্বিক নির্দেশনা এবং মিথস্ক্রিয়ামূলক শিক্ষার জন্য সুনির্দিষ্ট স্থান।' : 'Dedicated spaces for theoretical instruction and interactive learning.') }}</p>
          </div>
        </div>

        <!-- 2. Technical Training Workshops and Laboratories -->
        <div class="infr-facility-card">
          <div class="infr-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
          </div>
          <div>
            <h4>{{ $settings['infr_fac_2_title'] ?? ($isBn ? 'কারিগরি প্রশিক্ষণ কর্মশালা ও ল্যাবরেটরি' : 'Technical Training Workshops and Laboratories') }}</h4>
            <p>{{ $settings['infr_fac_2_desc'] ?? ($isBn ? 'সংশ্লিষ্ট কারিগরি ও বৃত্তিমূলক পেশায় ব্যবহারিক দক্ষতা উন্নয়নের পূর্ণাঙ্গ সুবিধাদি।' : 'Facilities for practical skills development across relevant technical and vocational occupations.') }}</p>
          </div>
        </div>

        <!-- 3. Computer Laboratories -->
        <div class="infr-facility-card">
          <div class="infr-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
              <line x1="8" y1="21" x2="16" y2="21"/>
              <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
          </div>
          <div>
            <h4>{{ $settings['infr_fac_3_title'] ?? ($isBn ? 'কম্পিউটার ল্যাবরেটরি' : 'Computer Laboratories') }}</h4>
            <p>{{ $settings['infr_fac_3_desc'] ?? ($isBn ? 'ডিজিটাল দক্ষতা উন্নয়নে ইন্টারনেট সুবিধাসম্পন্ন কম্পিউটারভিত্তিক ল্যাব।' : 'Computer-based learning facilities with internet access to support digital skills development.') }}</p>
          </div>
        </div>

        <!-- 4. Seminar and Meeting Room -->
        <div class="infr-facility-card">
          <div class="infr-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
          </div>
          <div>
            <h4>{{ $settings['infr_fac_4_title'] ?? ($isBn ? 'সেমিনার ও সভাকক্ষ' : 'Seminar and Meeting Room') }}</h4>
            <p>{{ $settings['infr_fac_4_desc'] ?? ($isBn ? 'সেমিনার, কর্মশালা, সভা এবং অংশীজনদের সাথে আলোচনার জন্য নির্ধারিত স্থান।' : 'Space for seminars, workshops, meetings, and stakeholder engagement.') }}</p>
          </div>
        </div>

        <!-- 5. Library and Learning Resources -->
        <div class="infr-facility-card">
          <div class="infr-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
          </div>
          <div>
            <h4>{{ $settings['infr_fac_5_title'] ?? ($isBn ? 'লাইব্রেরি ও শিখন সম্পদ' : 'Library and Learning Resources') }}</h4>
            <p>{{ $settings['infr_fac_5_desc'] ?? ($isBn ? 'প্রশিক্ষণার্থীদের জ্ঞান ও পেশাগত উন্নয়নে সহায়ক শিখন উপকরণ।' : "Learning resources to support trainees' knowledge and professional development.") }}</p>
          </div>
        </div>

        <!-- 6. Student Counselling and Information Desk -->
        <div class="infr-facility-card">
          <div class="infr-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
              <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
          </div>
          <div>
            <h4>{{ $settings['infr_fac_6_title'] ?? ($isBn ? 'শিক্ষার্থী কাউন্সেলিং ও তথ্য ডেস্ক' : 'Student Counselling and Information Desk') }}</h4>
            <p>{{ $settings['infr_fac_6_desc'] ?? ($isBn ? 'প্রশিক্ষণার্থীদের তথ্য অনুসন্ধান, দিকনির্দেশনা এবং প্রাতিষ্ঠানিক সেবা প্রাপ্তিতে সহায়তা।' : 'Support for trainee enquiries, guidance, and access to institutional services.') }}</p>
          </div>
        </div>

        <!-- 7. Student Common Room -->
        <div class="infr-facility-card">
          <div class="infr-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
              <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
              <line x1="6" y1="1" x2="6" y2="4"/>
              <line x1="10" y1="1" x2="10" y2="4"/>
              <line x1="14" y1="1" x2="14" y2="4"/>
            </svg>
          </div>
          <div>
            <h4>{{ $settings['infr_fac_7_title'] ?? ($isBn ? 'ছাত্র কমন রুম' : 'Student Common Room') }}</h4>
            <p>{{ $settings['infr_fac_7_desc'] ?? ($isBn ? 'শিক্ষার্থীদের জন্য একটি উন্মুক্ত মিলন ও বিশ্রামস্থল।' : 'A shared space for trainees.') }}</p>
          </div>
        </div>

        <!-- 8. Administrative Facilities -->
        <div class="infr-facility-card">
          <div class="infr-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
          </div>
          <div>
            <h4>{{ $settings['infr_fac_8_title'] ?? ($isBn ? 'প্রশাসনিক সুবিধাদি' : 'Administrative Facilities') }}</h4>
            <p>{{ $settings['infr_fac_8_desc'] ?? ($isBn ? 'প্রাতিষ্ঠানিক ব্যবস্থাপনা ও কর্মসূচি বাস্তবায়নে কর্মকর্তা ও দাপ্তরিক কক্ষ।' : 'Offices and operational spaces to support institutional management and programme delivery.') }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. TRAINING CAPACITY AT A GLANCE (CMS POWERED & 100% EDITABLE) -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-top: 4px solid var(--gold); border-radius: 10px; padding: 32px 34px; margin-bottom: 50px; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
      <span class="section-kicker" style="color: var(--gold);">{{ $settings['infr_capacity_kicker'] ?? ($isBn ? 'পরিসংখ্যান ও সক্ষমতা' : 'CAPACITY & SCOPE') }}</span>
      <h2 style="font-family: var(--serif); font-size: 26px; color: var(--green); margin: 6px 0 10px 0;">
        {{ $settings['infr_capacity_title'] ?? ($isBn ? 'এক নজরে প্রশিক্ষণ সক্ষমতা' : 'Training Capacity at a Glance') }}
      </h2>
      <div class="gold-rule" style="width: 45px; height: 3px; background: var(--gold); margin-bottom: 20px;"></div>

      <div style="overflow-x: auto;">
        <table class="capacity-table">
          <thead>
            <tr>
              <th style="width: 45%;">{{ $isBn ? 'বিবরণ / প্যারামিটার' : 'Parameter / Metric' }}</th>
              <th style="width: 55%;">{{ $isBn ? 'তথ্য ও বিবরণ' : 'Details' }}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>{{ $isBn ? 'অবস্থান' : 'Location' }}</strong></td>
              <td>{{ $settings['infr_cap_location'] ?? ($isBn ? 'চাঁদু চৌধুরী রোড, সাগরিকা, পাহাড়তলী, চট্টগ্রাম, বাংলাদেশ' : 'Chadu Chowdhury Road, Sagorika, Pahartali, Chattogram, Bangladesh') }}</td>
            </tr>
            <tr>
              <td><strong>{{ $isBn ? 'মোট ফ্লোর এরিয়া' : 'Total Reported Floor Area' }}</strong></td>
              <td><span class="capacity-badge">{{ $settings['infr_cap_area'] ?? ($isBn ? 'আনুমানিক ১৭,০০০ বর্গফুট' : 'Approximately 17,000 sq. ft.') }}</span></td>
            </tr>
            <tr>
              <td><strong>{{ $isBn ? 'ভবনের কাঠামো' : 'Building Structure' }}</strong></td>
              <td>{{ $settings['infr_cap_structure'] ?? ($isBn ? 'দুই তলা ভবন' : 'Two-storey building') }}</td>
            </tr>
            <tr>
              <td><strong>{{ $isBn ? 'শ্রেণীকক্ষ ও ল্যাবরেটরি' : 'Reported Classrooms and Laboratories' }}</strong></td>
              <td>{{ $settings['infr_cap_rooms'] ?? ($isBn ? 'প্রতি তলায় ৮টি করে কক্ষ' : 'Eight rooms on each floor') }}</td>
            </tr>
            <tr>
              <td><strong>{{ $isBn ? 'বার্ষিক প্রশিক্ষণ সক্ষমতা' : 'Annual Training Capacity' }}</strong></td>
              <td><span class="capacity-badge">{{ $settings['infr_cap_annual'] ?? ($isBn ? 'আনুমানিক ১,০০০ জন প্রশিক্ষণার্থী' : 'Approximately 1,000 beneficiaries') }}</span></td>
            </tr>
            <tr>
              <td><strong>{{ $isBn ? 'অনুমোদিত অকুপেশন (ট্রেড)' : 'Training Occupations (affiliated)' }}</strong></td>
              <td><span class="capacity-badge" style="background:#f1f5f9; color:#1e293b;">{{ $settings['infr_cap_occupations'] ?? ($isBn ? '১৪টি' : 'Fourteen') }}</span></td>
            </tr>
            <tr>
              <td><strong>{{ $isBn ? 'প্রশিক্ষণ পদ্ধতি' : 'Training Approach' }}</strong></td>
              <td>{{ $settings['infr_cap_approach'] ?? ($isBn ? 'ব্যবহারিক, দক্ষতাভিত্তিক ও কর্মসংস্থানমুখী শিখন' : 'Practical, competency-based, and employment-oriented learning') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 4. FEATURED WORKSHOPS & PRACTICAL LABS (CMS POWERED & 100% EDITABLE) -->
    <div>
      <div style="text-align: center; margin-bottom: 26px;">
        <span class="section-kicker">{{ $settings['infr_labs_kicker'] ?? ($isBn ? 'ব্যবহারিক ল্যাব' : 'HANDS-ON WORKSHOPS') }}</span>
        <h2 style="font-family: var(--serif); font-size: 26px; color: var(--green); margin: 6px 0;">
          {{ $settings['infr_labs_title'] ?? ($isBn ? 'অত্যাধুনিক প্র্যাকটিক্যাল ল্যাব ও ওয়ার্কশপ' : 'Practical Laboratories & Workshops') }}
        </h2>
        <p style="font-size: 14px; color: #64748b; max-width: 600px; margin: 0 auto;">
          {{ $settings['infr_labs_subtitle'] ?? ($isBn ? 'প্রতিটি কোর্সের জন্য রয়েছে সুসজ্জিত আধুনিক ল্যাব যন্ত্রপাতি ও নিরাপদ কাজের পরিবেশ।' : 'Equipped with real-world machinery and industrial tools to ensure immediate job readiness.') }}
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; align-items: stretch;">
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column; height: 100%; box-sizing: border-box;">
          <img src="{{ $f1_img }}" alt="{{ $settings['facility_1_title'] ?? 'Computer Lab' }}" style="width: 100%; height: 160px; object-fit: cover; display: block;">
          <div style="padding: 12px 14px; flex-grow: 1; display: flex; flex-direction: column;">
            <strong style="font-size: 15px; color: #1e293b; display: block; margin-bottom: 4px;">{{ $settings['facility_1_title'] ?? ($isBn ? 'কম্পিউটার ল্যাব' : 'Computer Lab') }}</strong>
            <small style="color: #64748b; font-size: 12px; display: block; line-height: 1.45;">{{ $settings['facility_1_desc'] ?? ($isBn ? 'হাই-স্পিড ব্রডব্যান্ড ইন্টারনেট এবং আধুনিক পিসি সমৃদ্ধ আইটি ল্যাব।' : 'High-speed broadband network & modern workstations.') }}</small>
          </div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column; height: 100%; box-sizing: border-box;">
          <img src="{{ $f2_img }}" alt="{{ $settings['facility_2_title'] ?? 'RAC Workshop' }}" style="width: 100%; height: 160px; object-fit: cover; display: block;">
          <div style="padding: 12px 14px; flex-grow: 1; display: flex; flex-direction: column;">
            <strong style="font-size: 15px; color: #1e293b; display: block; margin-bottom: 4px;">{{ $settings['facility_2_title'] ?? ($isBn ? 'রেফ্রিজারেশন ও এসি ল্যাব' : 'RAC Workshop') }}</strong>
            <small style="color: #64748b; font-size: 12px; display: block; line-height: 1.45;">{{ $settings['facility_2_desc'] ?? ($isBn ? 'ইনভার্টার এসি ও ফ্রিজ মেরামত, গ্যাস চার্জিং এর পূর্ণাঙ্গ সেটআপ।' : 'Full HVAC test stations and refrigerant charging gear.') }}</small>
          </div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column; height: 100%; box-sizing: border-box;">
          <img src="{{ $f3_img }}" alt="{{ $settings['facility_3_title'] ?? 'Plumbing Workshop' }}" style="width: 100%; height: 160px; object-fit: cover; display: block;">
          <div style="padding: 12px 14px; flex-grow: 1; display: flex; flex-direction: column;">
            <strong style="font-size: 15px; color: #1e293b; display: block; margin-bottom: 4px;">{{ $settings['facility_3_title'] ?? ($isBn ? 'প্লাম্বিং ওয়ার্কশপ' : 'Plumbing Workshop') }}</strong>
            <small style="color: #64748b; font-size: 12px; display: block; line-height: 1.45;">{{ $settings['facility_3_desc'] ?? ($isBn ? 'স্যানিটারি ফিটিংস, থ্রেডিং মেশিন ও আধুনিক পাইপলাইন ট্রেইনিং।' : 'PPR, PVC and sanitary installations equipment.') }}</small>
          </div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column; height: 100%; box-sizing: border-box;">
          <img src="{{ $f4_img }}" alt="{{ $settings['facility_4_title'] ?? 'Sewing Machine Lab' }}" style="width: 100%; height: 160px; object-fit: cover; display: block;">
          <div style="padding: 12px 14px; flex-grow: 1; display: flex; flex-direction: column;">
            <strong style="font-size: 15px; color: #1e293b; display: block; margin-bottom: 4px;">{{ $settings['facility_4_title'] ?? ($isBn ? 'স্যুইং ও টেইলারিং ল্যাব' : 'Sewing Machine Lab') }}</strong>
            <small style="color: #64748b; font-size: 12px; display: block; line-height: 1.45;">{{ $settings['facility_4_desc'] ?? ($isBn ? 'ইন্ডাস্ট্রিয়াল প্লেইন লকস্টিচ ও ওভারলক মেশিনে প্র্যাকটিক্যাল কাজ।' : 'Industrial lockstitch and overlock sewing stations.') }}</small>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
