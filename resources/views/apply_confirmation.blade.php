<!doctype html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ভর্তি আবেদন ফরম · {{ $application->tracking_no }}</title>
  <style>
    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    body {
      background: #eef3f0;
      color: #1e293b;
      font-family: 'SolaimanLipi', 'Kalpurush', 'Nikosh', 'Arial', sans-serif;
      font-size: 12px;
      line-height: 1.35;
      margin: 0;
      padding: 20px 10px;
    }
    .page-wrap {
      max-width: 830px;
      margin: 0 auto;
    }
    .no-print-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 14px;
      background: #fff;
      padding: 12px 20px;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .btn-home {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #0f5132;
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
      padding: 8px 16px;
      border-radius: 5px;
      background: #eef7f2;
      border: 1px solid #c8e0d4;
      transition: background 0.15s;
    }
    .btn-home:hover {
      background: #dfefe6;
    }
    .btn-print {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #0f5132;
      color: #fff;
      border: none;
      padding: 9px 22px;
      border-radius: 5px;
      font-weight: 600;
      font-size: 13.5px;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(15, 81, 50, 0.25);
      transition: background 0.15s;
    }
    .btn-print:hover {
      background: #093722;
    }
    .official-form {
      background: #fff;
      border: 2px solid #0f5132;
      border-radius: 4px;
      padding: 16px 20px 18px;
      box-shadow: 0 4px 18px rgba(0,0,0,0.08);
      position: relative;
    }
    .bismillah {
      text-align: center;
      font-size: 13px;
      font-weight: bold;
      color: #0f5132;
      margin-bottom: 3px;
    }
    .form-header-grid {
      display: grid;
      grid-template-columns: 165px 1fr 130px;
      gap: 12px;
      align-items: center;
      margin-bottom: 10px;
      padding-bottom: 8px;
      border-bottom: 1.5px solid #0f5132;
    }
    .seal-box {
      border: 1.5px dashed #0f5132;
      border-radius: 4px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-size: 11px;
      font-weight: bold;
      color: #0f5132;
      padding: 4px;
      background: #f0fdf4;
    }
    .seal-meta {
      font-size: 11px;
      margin-top: 5px;
      line-height: 1.35;
      color: #334155;
    }
    .header-center {
      text-align: center;
      padding: 0 4px;
    }
    .affiliation-badge {
      display: inline-block;
      background: #fefce8;
      color: #854d0e;
      border: 1px solid #fde047;
      padding: 2px 10px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 700;
      margin-bottom: 2px;
    }
    .inst-title {
      font-size: 18px;
      font-weight: 800;
      margin: 1px 0 1px 0;
      color: #0f5132;
      line-height: 1.25;
      letter-spacing: 0.2px;
    }
    .inst-title-en {
      font-size: 12.5px;
      font-weight: 700;
      margin-bottom: 2px;
      color: #334155;
      letter-spacing: 0.3px;
    }
    .inst-address {
      font-size: 11px;
      color: #475569;
      margin-bottom: 2px;
    }
    .header-pills {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin-top: 3px;
    }
    .inst-code-pill {
      font-size: 11px;
      font-weight: bold;
      background: #0f5132;
      color: #fff;
      padding: 1px 8px;
      border-radius: 3px;
    }
    .form-title-pill {
      font-size: 11px;
      font-weight: bold;
      background: #f1f5f9;
      color: #0f172a;
      border: 1px solid #cbd5e1;
      padding: 1px 8px;
      border-radius: 3px;
    }
    /* Right column: QR code on TOP, Photo directly UNDERNEATH */
    .header-right {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
    }
    .qr-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 1px solid #0f5132;
      border-radius: 4px;
      padding: 3px;
      background: #f0fdf4;
    }
    .qr-card img {
      width: 68px;
      height: 68px;
      display: block;
    }
    .qr-badge {
      font-size: 9px;
      font-family: monospace;
      font-weight: bold;
      color: #0f5132;
      margin-top: 2px;
      letter-spacing: 0.3px;
    }
    .photo-card {
      width: 82px;
      height: 96px;
      border: 1px solid #0f5132;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 2px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .photo-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 2px;
    }
    .dummy-photo {
      display: flex;
      flex-direction: column;
      align-items: center;
      color: #64748b;
      font-size: 9.5px;
      line-height: 1.2;
    }
    .dummy-photo svg {
      width: 26px;
      height: 26px;
      margin-bottom: 2px;
    }

    /* Section Ribbons */
    .sec-ribbon {
      background: #0f5132;
      color: #fff;
      padding: 4px 10px;
      font-size: 11.5px;
      font-weight: bold;
      border-radius: 3px;
      display: flex;
      align-items: center;
      gap: 6px;
      margin: 8px 0 6px 0;
    }
    .sec-dot {
      width: 7px;
      height: 7px;
      background: #c59b27;
      border-radius: 50%;
      display: inline-block;
    }
    .field-row {
      margin-bottom: 6px;
      font-size: 12px;
    }
    .field-title {
      font-weight: bold;
      color: #0f172a;
    }
    .char-table {
      width: 100%;
      border-collapse: collapse;
      margin: 2px 0 5px 0;
    }
    .char-table td {
      border: 1px solid #475569;
      width: 4.166%;
      height: 20px;
      text-align: center;
      font-family: 'Courier New', Courier, monospace;
      font-size: 12px;
      font-weight: bold;
      padding: 0;
      line-height: 20px;
      background: #fbfcfb;
    }
    .dob-table {
      border-collapse: collapse;
      display: inline-table;
      vertical-align: middle;
      margin-left: 6px;
    }
    .dob-table th, .dob-table td {
      border: 1px solid #334155;
      width: 20px;
      height: 18px;
      text-align: center;
      font-size: 10.5px;
      padding: 0;
    }
    .dob-table th {
      font-weight: normal;
      background: #f1f5f9;
      color: #475569;
    }
    .dob-table td {
      font-family: 'Courier New', Courier, monospace;
      font-weight: bold;
      font-size: 12.5px;
      color: #0f5132;
      background: #fff;
    }

    /* 2-Column Address Cards */
    .address-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px;
      margin-bottom: 6px;
    }
    .address-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 4px;
      padding: 5px 8px;
      font-size: 11.5px;
      line-height: 1.35;
    }
    .address-card-title {
      font-weight: bold;
      color: #0f5132;
      font-size: 11px;
      margin-bottom: 2px;
    }

    .meta-pills-row {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 6px;
      align-items: center;
    }
    .info-pill {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      padding: 2px 8px;
      border-radius: 3px;
      font-size: 11.5px;
    }
    .blood-badge {
      display: inline-block;
      background: #fef2f2;
      color: #991b1b;
      border: 1px solid #fecaca;
      font-weight: bold;
      padding: 1px 6px;
      border-radius: 3px;
      font-size: 11px;
    }

    /* Education Table */
    .edu-table {
      width: 100%;
      border-collapse: collapse;
      margin: 4px 0 6px 0;
    }
    .edu-table th, .edu-table td {
      border: 1px solid #334155;
      padding: 3.5px 6px;
      font-size: 11px;
      text-align: center;
    }
    .edu-table th {
      font-weight: bold;
      background: #0f5132;
      color: #fff;
    }

    .doc-checklist {
      font-size: 11px;
      margin: 6px 0 10px 0;
      line-height: 1.45;
      background: #fdfdfd;
      padding: 4px 8px;
      border: 1px solid #e2e8f0;
      border-radius: 4px;
    }
    .check-icon {
      color: #16a34a;
      font-weight: bold;
      margin-right: 3px;
    }

    /* Signatures Section */
    .signatures-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 15px;
      margin-top: 26px;
      text-align: center;
    }
    .sig-box {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
    }
    .sig-space {
      height: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 2px;
    }
    .sig-space img {
      max-height: 36px;
      max-width: 130px;
      object-fit: contain;
    }
    .sig-border {
      width: 90%;
      border-top: 1px dashed #334155;
      padding-top: 4px;
      font-size: 11px;
      font-weight: bold;
      color: #0f172a;
    }

    @media print {
      .no-print, .no-print * {
        display: none !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        visibility: hidden !important;
      }
      body {
        background: #fff !important;
        padding: 0 !important;
        margin: 0 !important;
      }
      .page-wrap {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
      }
      .official-form {
        border: 1.5px solid #0f5132 !important;
        box-shadow: none !important;
        padding: 8px 14px 10px !important;
        margin: 0 !important;
        width: 100% !important;
      }
      @page {
        size: A4 portrait;
        margin: 6mm 8mm 6mm 8mm;
      }
    }
  </style>
</head>
<body>

@php
  $nameEn = strtoupper(trim($application->trainee_name_en ?: $application->name ?: ''));
  $nameChars = array_pad(mb_str_split($nameEn), 24, '');

  $fatherEn = strtoupper(trim($application->father_name_en ?: ''));
  $fatherChars = array_pad(mb_str_split($fatherEn), 24, '');

  $motherEn = strtoupper(trim($application->mother_name_en ?: ''));
  $motherChars = array_pad(mb_str_split($motherEn), 24, '');

  $dobD = $application->dob ? $application->dob->format('d') : '  ';
  $dobM = $application->dob ? $application->dob->format('m') : '  ';
  $dobY = $application->dob ? $application->dob->format('Y') : '    ';

  // Blood group verification
  $bloodVal = $application->blood_group ?? null;
  if (!$bloodVal && in_array(strtoupper(trim($application->ethnicity ?? '')), ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])) {
    $bloodVal = strtoupper(trim($application->ethnicity));
  }
@endphp

<div class="page-wrap">
  {{-- Buttons Bar (Hidden Completely During Print) --}}
  <div class="no-print no-print-bar">
    <a href="{{ route('home') }}" class="btn-home">
      ← হোম পেজে ফিরে যান (Back to Home)
    </a>
    <div style="font-size:12.5px;color:#475569;">
      আবেদন ট্র্যাকিং নং: <strong style="color:#0f5132;">{{ $application->tracking_no }}</strong>
    </div>
    <button onclick="window.print()" class="btn-print">
      🖨️ প্রিন্ট করুন (Print Application)
    </button>
  </div>

  {{-- Official Form Container --}}
  <div class="official-form">
    <div class="bismillah">বিসমিল্লাহির রাহমানির রাহিম</div>

    <div class="form-header-grid">
      {{-- Header Left: Seal, Siblings, Trade, Date --}}
      <div>
        <div class="seal-box">
          শিক্ষা প্রতিষ্ঠানের<br>সীলমোহর (Seal)
        </div>
        <div class="seal-meta">
          <div>ভাই-বোন সংখ্যা: <b>{{ $application->household_members ?? '—' }}</b></div>
          <div>ট্রেড / কোর্স: <b style="color:#0f5132;">{{ $application->course }}</b></div>
          <div style="margin-top:2px;">ভর্তির তারিখ: <b>{{ $application->created_at->format('d/m/Y') }}</b></div>
        </div>
      </div>

      {{-- Header Center: Institute Details --}}
      <div class="header-center">
        <div class="affiliation-badge">বাংলাদেশ কারিগরি শিক্ষা বোর্ড (BTEB) অনুমোদিত প্রশিক্ষণ কেন্দ্র</div>
        <h1 class="inst-title">{{ $settings->get('institution_name', 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ') }}</h1>
        <div class="inst-title-en">Mahmudunnabi Chowdhury Technical School and College</div>
        <div class="inst-address">{{ $settings->get('address', 'ছদু চৌধুরী রোড, সাগরিকা, পাহাড়তলী, চট্টগ্রাম - ৪২১৯।') }}</div>
        <div class="header-pills">
          <span class="inst-code-pill">প্রতিষ্ঠান কোড : 70102</span>
          <span class="form-title-pill">ভর্তি আবেদন ফরম</span>
          <span style="font-size:11px;color:#475569;display:inline-flex;align-items:center;">ফোন: {{ $settings->get('phone', '01880-919275') }}</span>
        </div>
      </div>

      {{-- Header Right: 1. QR Code on TOP, 2. Profile Photo DIRECTLY UNDERNEATH --}}
      <div class="header-right">
        {{-- TOP: QR Code --}}
        <div class="qr-card">
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(url('/?tracking=' . $application->tracking_no)) }}" alt="QR Code">
          <span class="qr-badge">{{ $application->tracking_no }}</span>
        </div>

        {{-- BOTTOM: Profile Photo --}}
        <div class="photo-card">
          @if($application->photo)
            <img src="{{ storage_asset($application->photo) }}" alt="Trainee Photo">
          @else
            <div class="dummy-photo">
              <svg viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="1.6"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
              <span>পাসপোর্ট ছবি<br>(Photo)</span>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- SECTION 1: Personal Profile --}}
    <div class="sec-ribbon">
      <span class="sec-dot"></span>
      ১. প্রার্থীর ব্যক্তিগত তথ্য (Personal Profile)
    </div>

    {{-- Candidate Name --}}
    <div class="field-row">
      <div style="display:flex;justify-content:space-between;align-items:baseline;">
        <span><span class="field-title">প্রার্থীর নাম :</span> &nbsp; বাংলা : <b>{{ $application->trainee_name_bn ?: $application->name }}</b></span>
        <span style="font-size:11px;color:#64748b;">(স্পষ্টাক্ষরে) &nbsp; ইংরেজিতে (বড় হাতের অক্ষরে)</span>
      </div>
      <table class="char-table">
        <tr>
          @for($i = 0; $i < 24; $i++)
            <td>{{ $nameChars[$i] ?? '' }}</td>
          @endfor
        </tr>
      </table>
    </div>

    {{-- Father Name --}}
    <div class="field-row">
      <div style="display:flex;justify-content:space-between;align-items:baseline;">
        <span><span class="field-title">পিতার নাম :</span> &nbsp; বাংলা : <b>{{ $application->father_name_bn ?: '—' }}</b></span>
        <span style="font-size:11px;color:#64748b;">ইংরেজিতে (বড় হাতের অক্ষরে)</span>
      </div>
      <table class="char-table">
        <tr>
          @for($i = 0; $i < 24; $i++)
            <td>{{ $fatherChars[$i] ?? '' }}</td>
          @endfor
        </tr>
      </table>
    </div>

    {{-- Mother Name --}}
    <div class="field-row">
      <div style="display:flex;justify-content:space-between;align-items:baseline;">
        <span><span class="field-title">মাতার নাম :</span> &nbsp; বাংলা : <b>{{ $application->mother_name_bn ?: '—' }}</b></span>
        <span style="font-size:11px;color:#64748b;">ইংরেজিতে (বড় হাতের অক্ষরে)</span>
      </div>
      <table class="char-table">
        <tr>
          @for($i = 0; $i < 24; $i++)
            <td>{{ $motherChars[$i] ?? '' }}</td>
          @endfor
        </tr>
      </table>
    </div>

    {{-- Guardian & Demographics --}}
    <div class="meta-pills-row">
      <span class="info-pill"><span class="field-title">অভিভাবকের নাম:</span> {{ $application->emergency_contact_name ?: ($application->father_name_bn ?: $application->father_name_en ?: '—') }}</span>
      <span class="info-pill"><span class="field-title">জাতীয়তা:</span> <b>বাংলাদেশী</b></span>
      <span class="info-pill"><span class="field-title">ধর্ম:</span> <b>{{ $application->religion ?: '—' }}</b></span>
      <span class="info-pill">
        <span class="field-title">রক্তের গ্রুপ:</span> 
        @if($bloodVal)
          <span class="blood-badge">{{ $bloodVal }}</span>
        @else
          <span>—</span>
        @endif
      </span>
    </div>

    {{-- Date of Birth & NID --}}
    <div class="field-row" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:7px;">
      <div style="display:flex;align-items:center;">
        <span class="field-title">জন্ম তারিখ (সনদপত্র অনুসারে) :</span>
        <table class="dob-table">
          <tr>
            <th colspan="2">দিন</th>
            <th colspan="2">মাস</th>
            <th colspan="4">বছর</th>
          </tr>
          <tr>
            <td>{{ $dobD[0] ?? '' }}</td>
            <td>{{ $dobD[1] ?? '' }}</td>
            <td>{{ $dobM[0] ?? '' }}</td>
            <td>{{ $dobM[1] ?? '' }}</td>
            <td>{{ $dobY[0] ?? '' }}</td>
            <td>{{ $dobY[1] ?? '' }}</td>
            <td>{{ $dobY[2] ?? '' }}</td>
            <td>{{ $dobY[3] ?? '' }}</td>
          </tr>
        </table>
      </div>
      <div style="background:#f8fafc;border:1px solid #cbd5e1;padding:3px 10px;border-radius:3px;">
        <span class="field-title">জাতীয় পরিচয়পত্র / জন্ম নিবন্ধন নং:</span> 
        <b style="letter-spacing:1px;font-family:monospace;font-size:12.5px;color:#0f5132;">{{ $application->ident_no ?: '—' }}</b>
      </div>
    </div>

    {{-- SECTION 2: Address & Contact --}}
    <div class="sec-ribbon">
      <span class="sec-dot"></span>
      ২. ঠিকানা ও যোগাযোগ (Address & Contact Details)
    </div>

    <div class="address-grid">
      <div class="address-card">
        <div class="address-card-title">📍 বর্তমান ঠিকানা (Present Address):</div>
        <div>{{ $application->pres_address }}, থানা: {{ $application->pres_upazilla }}, জেলা: {{ $application->pres_district }}</div>
        <div style="margin-top:4px;"><span class="field-title">মোবাইল নম্বর:</span> <b style="color:#0f5132;">{{ $application->contact_no ?: $application->phone }}</b></div>
      </div>
      <div class="address-card">
        <div class="address-card-title">🏠 স্থায়ী ঠিকানা (Permanent Address):</div>
        <div>{{ $application->perm_address }}, থানা: {{ $application->perm_upazilla }}, জেলা: {{ $application->perm_district }}</div>
        <div style="margin-top:4px;"><span class="field-title">অভিভাবকের মোবাইল:</span> <b>{{ $application->emergency_contact ?: '—' }}</b></div>
      </div>
    </div>

    {{-- SECTION 3: Educational Qualifications --}}
    <div class="sec-ribbon">
      <span class="sec-dot"></span>
      ৩. শিক্ষাগত যোগ্যতা (Academic Qualifications)
    </div>

    <table class="edu-table">
      <thead>
        <tr>
          <th style="width:20%;">পরীক্ষা / শ্রেণী</th>
          <th style="width:34%;">শিক্ষা প্রতিষ্ঠান / বোর্ড</th>
          <th style="width:14%;">পাশের সন</th>
          <th style="width:16%;">জিপিএ / বিভাগ</th>
          <th style="width:16%;">মন্তব্য</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="height:21px;"><b>{{ $application->education_level ?: '—' }}</b></td>
          <td>—</td>
          <td>—</td>
          <td>—</td>
          <td>—</td>
        </tr>
        <tr>
          <td style="height:20px;"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td style="height:20px;"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>

    {{-- Employment & Fees --}}
    <div style="display:flex;justify-content:space-between;background:#f8fafc;padding:5px 10px;border-radius:4px;border:1px solid #e2e8f0;margin-bottom:6px;font-size:11.5px;">
      <span><span class="field-title">কর্মসংস্থান / শিল্পকারখানা:</span> {{ $application->employment_status ?: 'প্রযোজ্য নয়' }}</span>
      <span><span class="field-title">টাকা প্রদানের রশিদ নং:</span> <b style="color:#0f5132;font-family:monospace;">{{ $application->tracking_no }}</b></span>
      <span><span class="field-title">তারিখ:</span> <b>{{ $application->created_at->format('d/m/Y') }}</b></span>
    </div>

    {{-- Attachments Checklist --}}
    <div class="doc-checklist">
      <span class="field-title">সংযোজনী চেকলিস্ট:</span> &nbsp;
      <span><span class="check-icon">✓</span> ক. পরীক্ষা পাশের সনদপত্রের ফটোকপি।</span> &nbsp;&nbsp;&nbsp;
      <span><span class="check-icon">✓</span> খ. জন্ম নিবন্ধন সনদ / এনআইডি কার্ডের ফটোকপি।</span> &nbsp;&nbsp;&nbsp;
      <span><span class="check-icon">✓</span> গ. সদ্যতোলা পাসপোর্ট আকারের ২ কপি রঙিন ছবি।</span>
    </div>

    {{-- Signatures Row --}}
    <div class="signatures-grid">
      <div class="sig-box">
        <div class="sig-space">
          @if($application->signature_photo)
            <img src="{{ storage_asset($application->signature_photo) }}" alt="Applicant Signature">
          @endif
        </div>
        <div class="sig-border">
          আবেদনকারীর স্বাক্ষর ও তারিখ
        </div>
      </div>
      <div class="sig-box">
        <div class="sig-space"></div>
        <div class="sig-border">
          যাচাইকারীর স্বাক্ষর ও তারিখ
        </div>
      </div>
      <div class="sig-box">
        <div class="sig-space"></div>
        <div class="sig-border">
          অধ্যক্ষ / অনুমোদনকারীর স্বাক্ষর ও সীলমোহর
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
