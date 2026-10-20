<!doctype html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ভর্তি আবেদন ফরম প্রিন্ট · {{ $application->tracking_no }}</title>
  <style>
    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    body {
      background: #f1f5f9;
      color: #0f172a;
      font-family: 'SolaimanLipi', 'Kalpurush', 'Hind Siliguri', 'Nikosh', 'Arial', sans-serif;
      font-size: 11px;
      line-height: 1.35;
      margin: 0;
      padding: 16px 8px;
    }
    .page-wrap {
      max-width: 820px;
      margin: 0 auto;
    }

    /* Screen Action Bar */
    .no-print-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
      background: #ffffff;
      padding: 10px 18px;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #005953;
      text-decoration: none;
      font-weight: 600;
      font-size: 12.5px;
      padding: 6px 14px;
      border-radius: 4px;
      background: #e6f4f1;
      border: 1px solid #b8dfd7;
    }
    .btn-back:hover {
      background: #d4ece7;
    }
    .btn-print {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #005953;
      color: #ffffff;
      border: none;
      padding: 7px 20px;
      border-radius: 4px;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
    }
    .btn-print:hover {
      background: #00443f;
    }

    /* Official Form Container */
    .admission-form {
      background: #ffffff;
      border: 1.5px solid #005953;
      padding: 12px 16px 14px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.06);
      position: relative;
    }

    /* Header */
    .header-grid {
      display: grid;
      grid-template-columns: 80px 1fr 90px;
      gap: 10px;
      align-items: center;
      margin-bottom: 6px;
    }
    .header-logo {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .header-logo img {
      width: 76px;
      height: 76px;
      object-fit: contain;
    }
    .header-center {
      text-align: center;
    }
    .inst-name-bn {
      font-size: 21px;
      font-weight: 800;
      color: #005953;
      margin: 0;
      line-height: 1.2;
      letter-spacing: 0.2px;
    }
    .inst-name-en {
      font-size: 13.5px;
      font-weight: 700;
      color: #0f172a;
      margin-top: 1px;
    }
    .inst-slogan {
      font-size: 11px;
      font-weight: 600;
      color: #334155;
      margin-top: 2px;
      letter-spacing: 0.5px;
    }
    .header-right {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
    }
    .qr-frame {
      border: 1px solid #005953;
      padding: 2px;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .qr-frame img {
      width: 48px;
      height: 48px;
      display: block;
    }
    .qr-label {
      font-size: 7.5px;
      font-family: monospace;
      font-weight: bold;
      color: #005953;
      margin-top: 1px;
    }
    .photo-frame {
      width: 68px;
      height: 78px;
      border: 1px dashed #005953;
      background: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      overflow: hidden;
      padding: 1px;
    }
    .photo-frame img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .photo-placeholder {
      font-size: 8px;
      line-height: 1.2;
      color: #334155;
    }

    /* Sub-header Bar (Title & Tracking) */
    .title-bar-wrap {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 4px;
      margin-bottom: 4px;
    }
    .form-title-pill {
      background: #005953;
      color: #ffffff;
      font-size: 13px;
      font-weight: bold;
      padding: 3px 18px;
      border-radius: 3px;
    }
    .app-no-box {
      border: 1px solid #005953;
      padding: 2px 12px;
      font-size: 11px;
      border-radius: 2px;
      background: #ffffff;
    }
    .app-no-box strong {
      font-family: monospace;
      font-size: 11.5px;
      color: #005953;
    }

    /* Notice banner */
    .notice-strip {
      background: #fdf5e6;
      border-left: 3px solid #c07817;
      color: #78350f;
      font-size: 10px;
      padding: 2.5px 8px;
      margin-bottom: 6px;
      border-radius: 2px;
    }

    /* Section Cards */
    .sec-card {
      border: 1px solid #7cb5a9;
      border-radius: 2px;
      margin-bottom: 5px;
      background: #ffffff;
    }
    .sec-head {
      background: #d8eee7;
      color: #005953;
      font-weight: bold;
      font-size: 11.5px;
      padding: 2.5px 8px;
      border-bottom: 1px solid #7cb5a9;
    }
    .sec-body {
      padding: 4px 8px 5px;
    }

    /* Field Rows & Dotted Lines */
    .form-row {
      display: flex;
      align-items: baseline;
      margin-bottom: 3.5px;
      font-size: 11px;
      line-height: 1.35;
    }
    .form-row:last-child {
      margin-bottom: 0;
    }
    .field-lbl {
      color: #0f172a;
      font-weight: 600;
      white-space: nowrap;
    }
    .field-badge-lbl {
      background: #e6f4f1;
      border: 1px solid #b2dbd2;
      color: #005953;
      font-weight: bold;
      font-size: 10px;
      padding: 1px 6px;
      border-radius: 2px;
      white-space: nowrap;
    }
    .dotted-val {
      flex: 1;
      border-bottom: 1px dotted #475569;
      padding: 0 4px;
      color: #0f172a;
      font-weight: 600;
      min-height: 15px;
    }
    .grid-2col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      column-gap: 16px;
      row-gap: 3.5px;
    }

    /* Checkbox & Options */
    .chk-inline {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      margin-right: 12px;
      font-size: 10.5px;
    }
    .chk-square {
      width: 12px;
      height: 12px;
      border: 1px solid #005953;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 9px;
      line-height: 1;
      font-weight: bold;
      background: #fff;
    }

    /* Qualifications Table */
    .qual-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1px;
    }
    .qual-table th, .qual-table td {
      border: 1px solid #7cb5a9;
      padding: 2.5px 5px;
      font-size: 10.5px;
      text-align: center;
    }
    .qual-table th {
      background: #eef8f5;
      color: #005953;
      font-weight: bold;
    }
    .qual-table td {
      height: 18px;
    }

    /* Checklist & Declaration */
    .checklist-wrap {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 4px;
      font-size: 10px;
    }
    .declaration-text {
      font-size: 10px;
      color: #1e293b;
      margin: 4px 0 6px 0;
      line-height: 1.35;
    }
    .sig-row {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 20px;
      margin-top: 18px;
      text-align: center;
    }
    .sig-col {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
    }
    .sig-space {
      height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 2px;
    }
    .sig-space img {
      max-height: 26px;
      max-width: 110px;
      object-fit: contain;
    }
    .sig-line {
      width: 85%;
      border-top: 1px solid #334155;
      padding-top: 3px;
      font-size: 10px;
      font-weight: bold;
      color: #0f172a;
    }

    /* Money Receipt Voucher */
    .voucher-card {
      background: #ffffff;
      border: 1.5px dashed #005953;
      padding: 14px 18px;
      margin-top: 16px;
      position: relative;
    }
    .voucher-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #cbd5e1;
      padding-bottom: 6px;
      margin-bottom: 8px;
    }
    .voucher-title {
      font-size: 14px;
      font-weight: 800;
      color: #005953;
    }
    .voucher-table {
      width: 100%;
      border-collapse: collapse;
      margin: 8px 0;
      font-size: 11px;
    }
    .voucher-table th, .voucher-table td {
      border: 1px solid #cbd5e1;
      padding: 5px 8px;
      text-align: left;
    }
    .voucher-table th {
      background: #f1f5f9;
      color: #334155;
    }

    /* Print Optimization */
    @media print {
      .no-print, .no-print * {
        display: none !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        visibility: hidden !important;
      }
      body {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
      }
      .page-wrap {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
      }
      .admission-form {
        border: 1.5px solid #005953 !important;
        box-shadow: none !important;
        padding: 8px 12px 10px !important;
        width: 100% !important;
        margin-bottom: 12px !important;
      }
      .voucher-card {
        border: 1px dashed #005953 !important;
        padding: 10px 14px !important;
        page-break-inside: avoid !important;
      }
      @page {
        size: A4 portrait;
        margin: 7mm 7mm 7mm 7mm;
      }
    }
  </style>
</head>
<body>

@php
  // Blood group verification
  $bloodVal = $application->blood_group ?? null;
  if (!$bloodVal && in_array(strtoupper(trim($application->ethnicity ?? '')), ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])) {
    $bloodVal = strtoupper(trim($application->ethnicity));
  }

  // Sex/Gender check
  $sexVal = strtolower(trim($application->sex ?? ''));
  $isMale = in_array($sexVal, ['male', 'পুরুষ', 'm']);
  $isFemale = in_array($sexVal, ['female', 'নারী', 'মহিলা', 'f']);
  $isOther = !$isMale && !$isFemale && !empty($sexVal);

  // Identity checks
  $identType = trim($application->ident_type ?? '');
  $isBirthCert = str_contains(strtolower($identType), 'birth') || empty($identType);
@endphp

<div class="page-wrap">
  {{-- Screen Action Bar (Hidden Completely During Print) --}}
  <div class="no-print no-print-bar">
    <a href="{{ route('admin.applications.show', $application) }}" class="btn-back">
      ← আবেদন পেজে ফিরে যান (Back to Application)
    </a>
    <div style="font-size:12.5px;color:#334155;">
      আবেদন ট্র্যাকিং নং: <strong style="color:#005953;">{{ $application->tracking_no }}</strong>
    </div>
    <button onclick="window.print()" class="btn-print">
      🖨️ প্রিন্ট করুন (Print Application)
    </button>
  </div>

  {{-- Official Form Container --}}
  <div class="admission-form">

    {{-- Header Section --}}
    <div class="header-grid">
      {{-- Header Left: Official MC Logo --}}
      <div class="header-logo">
        <img src="{{ asset('assets/MC Logo.png') }}" alt="MC Logo">
      </div>

      {{-- Header Center: Institutional Titles & Slogan --}}
      <div class="header-center">
        <h1 class="inst-name-bn">{{ $settings->get('institution_name', 'এম সি টেকনিক্যাল স্কুল অ্যান্ড কলেজ') }}</h1>
        <div class="inst-name-en">MC Technical School & College</div>
        <div class="inst-slogan">দক্ষতা &nbsp;|&nbsp; কর্মসংস্থান &nbsp;|&nbsp; উন্নত আগামী</div>
      </div>

      {{-- Header Right: QR Code on TOP, Passport Photo DIRECTLY UNDERNEATH --}}
      <div class="header-right">
        {{-- TOP: Auto-generated QR Code --}}
        <div class="qr-frame">
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(url('/?tracking=' . $application->tracking_no)) }}" alt="QR Code">
          <span class="qr-label">{{ $application->tracking_no }}</span>
        </div>

        {{-- BOTTOM: Student Passport Photo Frame --}}
        <div class="photo-frame">
          @if($application->photo)
            <img src="{{ $application->photoUrl() }}" alt="Trainee Photo">
          @else
            <div class="photo-placeholder">
              শিক্ষার্থীর ছবি<br>(পাসপোর্ট সাইজ)
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Title & Application Number Bar --}}
    <div class="title-bar-wrap">
      <div class="form-title-pill">ভর্তি আবেদন ফরম</div>
      <div class="app-no-box">
        আবেদন নং : <strong>{{ $application->tracking_no }}</strong>
      </div>
    </div>

    {{-- Instruction Strip --}}
    <div class="notice-strip">
      অনুগ্রহ করে সকল তথ্য সঠিকভাবে ও স্পষ্টভাবে পূরণ করুন। তথ্য বিভ্রান্তিকর হলে ভর্তি বাতিল হতে পারে।
    </div>

    {{-- SECTION ক: ভর্তি ও শিক্ষার্থীর তথ্য --}}
    <div class="sec-card">
      <div class="sec-head">ক. ভর্তি ও শিক্ষার্থীর তথ্য</div>
      <div class="sec-body">
        <div class="form-row">
          <span class="field-lbl">কোর্স / পর্ব :</span>
          <span class="dotted-val">{{ $application->course }}</span>
          <span class="field-lbl" style="margin-left:14px;">ট্রেড / বিভাগ :</span>
          <span class="dotted-val">{{ $application->preferred_shift ?: 'কারিগরি প্রশিক্ষণ' }}</span>
        </div>

        <div class="form-row">
          <span class="field-lbl">শিক্ষার্থীর পূর্ণ নাম (বাংলা) :</span>
          <span class="dotted-val">{{ $application->trainee_name_bn ?: $application->name }}</span>
        </div>

        <div class="form-row">
          <span class="field-lbl">শিক্ষার্থীর পূর্ণ নাম (ইংরেজি) :</span>
          <span class="dotted-val" style="font-family:'Courier New',Courier,monospace;letter-spacing:0.5px;">{{ strtoupper($application->trainee_name_en ?: $application->name) }}</span>
        </div>

        <div class="form-row">
          <span class="field-lbl">জন্ম তারিখ :</span>
          <span class="dotted-val" style="flex:0 0 110px;">{{ $application->dob ? $application->dob->format('d / m / Y') : '___ / ___ / ______' }}</span>
          <span style="font-size:10px;color:#64748b;margin-right:14px;">(দিন / মাস / বছর)</span>

          <span class="field-lbl">লিঙ্গ :</span>
          <span style="margin-left:8px;">
            <span class="chk-inline"><span class="chk-square">{{ $isMale ? '✔' : '' }}</span> পুরুষ</span>
            <span class="chk-inline"><span class="chk-square">{{ $isFemale ? '✔' : '' }}</span> নারী</span>
            <span class="chk-inline"><span class="chk-square">{{ $isOther ? '✔' : '' }}</span> অন্যান্য</span>
          </span>
        </div>

        <div class="form-row">
          <span class="field-lbl">ধর্ম :</span>
          <span class="dotted-val">{{ $application->religion ?: 'ইসলাম' }}</span>
          <span class="field-lbl" style="margin-left:14px;">রক্তের গ্রুপ :</span>
          <span class="dotted-val" style="flex:0 0 140px;">{{ $bloodVal ?: '—' }}</span>
        </div>

        <div class="form-row" style="margin-top:2px;">
          <span class="field-badge-lbl">জন্মনিবন্ধন নম্বর</span>
          <span style="margin:0 4px;">:</span>
          <span class="dotted-val" style="font-family:monospace;letter-spacing:0.5px;">{{ $isBirthCert ? $application->ident_no : '—' }}</span>

          <span class="field-badge-lbl" style="margin-left:12px;">জাতীয় পরিচয়পত্র (NID) নম্বর (যদি থাকে)</span>
          <span style="margin:0 4px;">:</span>
          <span class="dotted-val" style="font-family:monospace;letter-spacing:0.5px;">{{ !$isBirthCert ? $application->ident_no : '—' }}</span>
        </div>
      </div>
    </div>

    {{-- SECTION খ: পিতা ও মাতার পরিচিতি --}}
    <div class="sec-card">
      <div class="sec-head">খ. পিতা ও মাতার পরিচিতি</div>
      <div class="sec-body">
        <div class="grid-2col">
          {{-- Left Column: Father Info --}}
          <div>
            <div class="form-row">
              <span class="field-lbl">পিতার পূর্ণ নাম :</span>
              <span class="dotted-val">{{ $application->father_name_bn ?: ($application->father_name_en ?: '—') }}</span>
            </div>
            <div class="form-row">
              <span class="field-lbl">পিতার পেশা :</span>
              <span class="dotted-val">{{ $application->income_source ?: '—' }}</span>
            </div>
            <div class="form-row">
              <span class="field-lbl">পিতার NID নম্বর (যদি থাকে) :</span>
              <span class="dotted-val">—</span>
            </div>
            <div class="form-row">
              <span class="field-lbl">পিতার জন্মনিবন্ধন নম্বর :</span>
              <span class="dotted-val">—</span>
            </div>
          </div>

          {{-- Right Column: Mother Info --}}
          <div>
            <div class="form-row">
              <span class="field-lbl">মাতার পূর্ণ নাম :</span>
              <span class="dotted-val">{{ $application->mother_name_bn ?: ($application->mother_name_en ?: '—') }}</span>
            </div>
            <div class="form-row">
              <span class="field-lbl">মাতার পেশা :</span>
              <span class="dotted-val">গৃহিণী</span>
            </div>
            <div class="form-row">
              <span class="field-lbl">মাতার NID নম্বর (যদি থাকে) :</span>
              <span class="dotted-val">—</span>
            </div>
            <div class="form-row">
              <span class="field-lbl">মাতার জন্মনিবন্ধন নম্বর :</span>
              <span class="dotted-val">—</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- SECTION গ: অভিভাবক ও যোগাযোগের তথ্য --}}
    <div class="sec-card">
      <div class="sec-head">গ. অভিভাবক ও যোগাযোগের তথ্য</div>
      <div class="sec-body">
        <div class="grid-2col">
          <div class="form-row">
            <span class="field-lbl">অভিভাবকের নাম :</span>
            <span class="dotted-val">{{ $application->emergency_contact_name ?: ($application->father_name_bn ?: $application->father_name_en ?: '—') }}</span>
          </div>
          <div class="form-row">
            <span class="field-lbl">শিক্ষার্থীর সাথে সম্পর্ক :</span>
            <span class="dotted-val">পিতা / অভিভাবক</span>
          </div>
        </div>

        <div class="grid-2col">
          <div class="form-row">
            <span class="field-lbl">অভিভাবকের মোবাইল নম্বর :</span>
            <span class="dotted-val" style="font-family:monospace;letter-spacing:0.5px;">{{ $application->emergency_contact ?: '—' }}</span>
          </div>
          <div class="form-row">
            <span class="field-lbl">শিক্ষার্থীর মোবাইল নম্বর :</span>
            <span class="dotted-val" style="font-family:monospace;letter-spacing:0.5px;color:#005953;">{{ $application->contact_no ?: $application->phone }}</span>
          </div>
        </div>

        <div style="font-weight:bold;margin-top:2px;font-size:10.5px;color:#005953;">বর্তমান ঠিকানা</div>
        <div class="grid-2col">
          <div class="form-row">
            <span class="field-lbl">গ্রাম / মহল্লা / ওয়ার্ড :</span>
            <span class="dotted-val">{{ $application->pres_address }}</span>
          </div>
          <div class="form-row">
            <span class="field-lbl">ডাকঘর :</span>
            <span class="dotted-val">—</span>
          </div>
        </div>
        <div class="grid-2col">
          <div class="form-row">
            <span class="field-lbl">উপজেলা / থানা :</span>
            <span class="dotted-val">{{ $application->pres_upazilla }}</span>
          </div>
          <div class="form-row">
            <span class="field-lbl">জেলা :</span>
            <span class="dotted-val">{{ $application->pres_district }}</span>
          </div>
        </div>

        <div class="form-row" style="margin-top:2px;">
          <span class="field-lbl">স্থায়ী ঠিকানা (যদি ভিন্ন হয়) :</span>
          <span class="dotted-val">{{ $application->perm_address }}, থানা: {{ $application->perm_upazilla }}, জেলা: {{ $application->perm_district }}</span>
        </div>
      </div>
    </div>

    {{-- SECTION ঘ: পূর্ববর্তী শিক্ষাগত যোগ্যতা --}}
    <div class="sec-card">
      <div class="sec-head">ঘ. পূর্ববর্তী শিক্ষাগত যোগ্যতা</div>
      <div class="sec-body" style="padding:2px 4px 4px;">
        <table class="qual-table">
          <thead>
            <tr>
              <th style="width:20%;">পরীক্ষার নাম</th>
              <th style="width:30%;">প্রতিষ্ঠানের নাম</th>
              <th style="width:14%;">বোর্ড</th>
              <th style="width:12%;">পাশের সন</th>
              <th style="width:12%;">বিভাগ / বিষয়</th>
              <th style="width:12%;">ফলাফল / জিপিএ</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="font-weight:600;">এসএসসি / সমমান</td>
              <td>{{ $application->education_level ?: '—' }}</td>
              <td>—</td>
              <td>—</td>
              <td>—</td>
              <td>—</td>
            </tr>
            <tr>
              <td style="font-weight:600;">এইচএসসি / সমমান</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td style="font-weight:600;">অন্যান্য (যদি থাকে)</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- SECTION ঙ: সংযুক্তি ও অঙ্গীকার --}}
    <div class="sec-card">
      <div class="sec-head">ঙ. সংযুক্তি ও অঙ্গীকার</div>
      <div class="sec-body">
        <div class="checklist-wrap">
          <span class="chk-inline"><span class="chk-square">✔</span> জন্মনিবন্ধন সনদ / NID এর কপি</span>
          <span class="chk-inline"><span class="chk-square">✔</span> পিতা-মাতার NID এর কপি</span>
          <span class="chk-inline"><span class="chk-square">✔</span> শিক্ষাগত যোগ্যতার সনদপত্রের কপি</span>
          <span class="chk-inline"><span class="chk-square">✔</span> পাসপোর্ট সাইজ ছবি</span>
          <span class="chk-inline"><span class="chk-square"></span> অন্যান্য (উল্লেখ করুন) ______________</span>
        </div>

        <div class="declaration-text">
          আমি ঘোষণা করছি যে, এই ফরমে প্রদত্ত সকল তথ্য আমার জ্ঞান অনুযায়ী সত্য ও সঠিক। কোনো তথ্য অসত্য প্রমাণিত হলে প্রতিষ্ঠানের নিয়ম অনুযায়ী আমার ভর্তি বাতিল হতে পারে।
        </div>

        <div class="sig-row">
          <div class="sig-col">
            <div class="sig-space">
              @if($application->signature_photo)
                <img src="{{ $application->signatureUrl() }}" alt="Signature">
              @endif
            </div>
            <div class="sig-line">শিক্ষার্থীর স্বাক্ষর ও তারিখ</div>
          </div>
          <div class="sig-col">
            <div class="sig-space"></div>
            <div class="sig-line">পিতা / মাতার স্বাক্ষর ও তারিখ</div>
          </div>
          <div class="sig-col">
            <div class="sig-space"></div>
            <div class="sig-line">অভিভাবকের স্বাক্ষর ও তারিখ</div>
          </div>
        </div>
      </div>
    </div>

    {{-- SECTION: অফিস কর্তৃক পূরণীয় (Office Use Only) --}}
    <div class="sec-card" style="margin-bottom:0;">
      <div class="sec-head">অফিস কর্তৃক পূরণীয়</div>
      <div class="sec-body">
        <div class="form-row">
          <span class="field-lbl">আবেদন গ্রহণের তারিখ :</span>
          <span class="dotted-val" style="flex:0 0 110px;">{{ $application->created_at->format('d / m / Y') }}</span>

          <span class="field-lbl" style="margin-left:14px;">কাগজপত্র :</span>
          <span style="margin-left:8px;">
            <span class="chk-inline"><span class="chk-square">✔</span> সম্পূর্ণ</span>
            <span class="chk-inline"><span class="chk-square"></span> অসম্পূর্ণ</span>
          </span>

          <span class="field-lbl" style="margin-left:14px;">ভর্তি রোল :</span>
          <span class="dotted-val"></span>
        </div>

        <div class="form-row">
          <span class="field-lbl">ভর্তির সিদ্ধান্ত :</span>
          <span style="margin-left:8px;">
            <span class="chk-inline"><span class="chk-square">✔</span> নির্বাচিত</span>
            <span class="chk-inline"><span class="chk-square"></span> অপেক্ষমাণ তালিকা</span>
            <span class="chk-inline"><span class="chk-square"></span> অনির্বাচিত</span>
          </span>

          <span class="field-lbl" style="margin-left:14px;">মন্তব্য :</span>
          <span class="dotted-val"></span>
        </div>

        <div class="sig-row" style="margin-top:14px;">
          <div class="sig-col">
            <div class="sig-space"></div>
            <div class="sig-line">ভর্তি কর্মকর্তা<br><span style="font-weight:normal;font-size:9px;">নাম ও স্বাক্ষর</span></div>
          </div>
          <div class="sig-col">
            <div class="sig-space"></div>
            <div class="sig-line">প্রোগ্রাম সমন্বয়কারী<br><span style="font-weight:normal;font-size:9px;">নাম ও স্বাক্ষর</span></div>
          </div>
          <div class="sig-col">
            <div class="sig-space"></div>
            <div class="sig-line">অধ্যক্ষ<br><span style="font-weight:normal;font-size:9px;">নাম ও স্বাক্ষর</span></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
