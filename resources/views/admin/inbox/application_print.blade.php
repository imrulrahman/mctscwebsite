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
      background: #e2e8f0;
      color: #000;
      font-family: 'SolaimanLipi', 'Kalpurush', 'Nikosh', 'Arial', sans-serif;
      font-size: 12px;
      line-height: 1.35;
      margin: 0;
      padding: 20px 10px;
    }
    .page-wrap {
      max-width: 820px;
      margin: 0 auto;
    }
    
    /* Screen toolbar */
    .no-print-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 14px;
      background: #fff;
      padding: 10px 18px;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }
    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #0f172a;
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
      padding: 7px 14px;
      border-radius: 4px;
      background: #f1f5f9;
      border: 1px solid #cbd5e1;
    }
    .btn-back:hover {
      background: #e2e8f0;
    }
    .btn-print {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #0f172a;
      color: #fff;
      border: none;
      padding: 8px 20px;
      border-radius: 4px;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
    }
    .btn-print:hover {
      background: #334155;
    }

    /* Official Formal Academic Document Form */
    .official-form {
      background: #fff;
      border: 2px solid #000;
      padding: 16px 20px 16px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
      position: relative;
    }
    .bismillah {
      text-align: center;
      font-size: 13px;
      font-weight: bold;
      color: #000;
      margin-bottom: 3px;
    }
    .form-header-grid {
      display: grid;
      grid-template-columns: 155px 1fr 115px;
      gap: 10px;
      align-items: start;
      margin-bottom: 8px;
      padding-bottom: 6px;
      border-bottom: 1.5px solid #000;
    }
    
    /* Left Seal Box */
    .seal-box {
      border: 1px solid #000;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-size: 11px;
      font-weight: bold;
      color: #000;
      padding: 3px;
      background: #fff;
    }
    .seal-meta {
      font-size: 11px;
      margin-top: 5px;
      line-height: 1.35;
      color: #000;
    }
    
    /* Center Institute Details */
    .header-center {
      text-align: center;
      padding: 0 2px;
    }
    .sub-board {
      font-size: 11.5px;
      font-weight: bold;
      margin-bottom: 1px;
      color: #000;
    }
    .inst-title {
      font-size: 18px;
      font-weight: 800;
      margin: 1px 0;
      color: #000;
      line-height: 1.25;
    }
    .inst-title-en {
      font-size: 12px;
      font-weight: 700;
      margin-bottom: 1px;
      color: #000;
    }
    .inst-address {
      font-size: 10.5px;
      color: #000;
      margin-bottom: 1px;
    }
    .inst-phone {
      font-size: 11px;
      margin-bottom: 2px;
    }
    .subline-note {
      font-size: 10.5px;
      font-style: italic;
      color: #000;
      margin-bottom: 2px;
    }
    .inst-code-box {
      display: inline-block;
      border: 1px solid #000;
      padding: 1px 10px;
      font-size: 11px;
      font-weight: bold;
      margin-top: 1px;
    }

    /* Right Column: QR Code on TOP, Passport Photo DIRECTLY UNDERNEATH */
    .header-right {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
    }
    .qr-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 1px solid #000;
      padding: 2px;
      background: #fff;
    }
    .qr-card img {
      width: 58px;
      height: 58px;
      display: block;
    }
    .qr-badge {
      font-size: 8.5px;
      font-family: monospace;
      font-weight: bold;
      color: #000;
      margin-top: 1px;
      letter-spacing: 0.3px;
    }
    .photo-card {
      width: 78px;
      height: 90px;
      border: 1px solid #000;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 1px;
      background: #fff;
      overflow: hidden;
    }
    .photo-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .photo-caption {
      font-size: 8.5px;
      line-height: 1.25;
      color: #000;
      padding: 2px;
    }

    /* Formal Form Rows */
    .field-row {
      margin-bottom: 5px;
      font-size: 11.5px;
      color: #000;
    }
    .field-title {
      font-weight: bold;
      color: #000;
    }
    .char-table {
      width: 100%;
      border-collapse: collapse;
      margin: 2px 0 4px 0;
    }
    .char-table td {
      border: 1px solid #000;
      width: 4.166%;
      height: 19px;
      text-align: center;
      font-family: 'Courier New', Courier, monospace;
      font-size: 11.5px;
      font-weight: bold;
      padding: 0;
      line-height: 19px;
      background: #fff;
      color: #000;
    }
    .dob-table {
      border-collapse: collapse;
      display: inline-table;
      vertical-align: middle;
      margin-left: 6px;
    }
    .dob-table th, .dob-table td {
      border: 1px solid #000;
      width: 18px;
      height: 18px;
      text-align: center;
      font-size: 10px;
      padding: 0;
    }
    .dob-table th {
      font-weight: normal;
      background: #f8f8f8;
      color: #000;
    }
    .dob-table td {
      font-family: 'Courier New', Courier, monospace;
      font-weight: bold;
      font-size: 11.5px;
      color: #000;
      background: #fff;
    }

    /* Qualifications Table */
    .edu-table {
      width: 100%;
      border-collapse: collapse;
      margin: 2px 0 4px 0;
    }
    .edu-table th, .edu-table td {
      border: 1px solid #000;
      padding: 3px 6px;
      font-size: 11px;
      text-align: center;
      color: #000;
    }
    .edu-table th {
      font-weight: bold;
      background: #f8f8f8;
    }

    /* Attachments list */
    .doc-checklist {
      font-size: 11px;
      margin: 4px 0 8px 0;
      line-height: 1.4;
      color: #000;
    }

    /* Signatures */
    .signatures-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 15px;
      margin-top: 22px;
      text-align: center;
    }
    .sig-box {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
    }
    .sig-space {
      height: 34px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 2px;
    }
    .sig-space img {
      max-height: 32px;
      max-width: 120px;
      object-fit: contain;
    }
    .sig-border {
      width: 90%;
      border-top: 1px solid #000;
      padding-top: 4px;
      font-size: 11px;
      font-weight: bold;
      color: #000;
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
        border: 1.5px solid #000 !important;
        box-shadow: none !important;
        padding: 6px 12px 8px !important;
        margin: 0 !important;
        width: 100% !important;
      }
      @page {
        size: A4 portrait;
        margin: 8mm 8mm 8mm 8mm;
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
  {{-- Screen Action Bar (Hidden Completely During Print) --}}
  <div class="no-print no-print-bar">
    <a href="{{ route('admin.applications.show', $application) }}" class="btn-back">
      ← আবেদন পেজে ফিরে যান (Back to Application)
    </a>
    <div style="font-size:12.5px;color:#334155;">
      আবেদন ট্র্যাকিং নং: <strong style="color:#000;">{{ $application->tracking_no }}</strong>
    </div>
    <button onclick="window.print()" class="btn-print">
      🖨️ প্রিন্ট করুন (Print Application)
    </button>
  </div>

  {{-- Official Formal Form Container --}}
  <div class="official-form">
    <div class="bismillah">বিসমিল্লাহির রাহমানির রাহিম</div>

    <div class="form-header-grid">
      {{-- Header Left: Seal, Siblings, Trade, Date --}}
      <div>
        <div class="seal-box">
          শিক্ষা প্রতিষ্ঠানের<br>সীলমোহর
        </div>
        <div class="seal-meta">
          <div>ভাই-বোন সংখ্যা :- <b>{{ $application->household_members ?? '—' }}</b></div>
          <div>ট্রেড :- <b>{{ $application->course }}</b></div>
          <div style="margin-top:1px;">ভর্তির তারিখঃ <b>{{ $application->created_at->format('d/m/Y') }}</b></div>
        </div>
      </div>

      {{-- Header Center: Official Institute Details --}}
      <div class="header-center">
        <div class="sub-board">বাংলাদেশ কারিগরি শিক্ষা বোর্ড অনুমোদিত প্রশিক্ষণ কেন্দ্র</div>
        <h1 class="inst-title">{{ $settings->get('institution_name', 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ') }}</h1>
        <div class="inst-title-en">Mahmudunnabi Chowdhury Technical School and College</div>
        <div class="inst-address">{{ $settings->get('address', 'ছদু চৌধুরী রোড, সাগরিকা, পাহাড়তলী, চট্টগ্রাম - ৪২১৯।') }}</div>
        <div class="inst-phone">মোবাইল নং <b>{{ $settings->get('phone', '01880-919275') }}</b></div>
        <div class="subline-note">(শিক্ষাগত যোগ্যতার সনদ অনুসারে স্ব-হস্তে পূরণ করতে হবে)</div>
        <div>
          <span class="inst-code-box">প্রতিষ্ঠান কোড : 70102</span>
        </div>
      </div>

      {{-- Header Right: 1. QR Code on TOP, 2. Passport Photo DIRECTLY UNDERNEATH --}}
      <div class="header-right">
        {{-- TOP: Auto-generated QR Code --}}
        <div class="qr-card">
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(url('/?tracking=' . $application->tracking_no)) }}" alt="QR Code">
          <span class="qr-badge">{{ $application->tracking_no }}</span>
        </div>

        {{-- BOTTOM: Student Passport Photo --}}
        <div class="photo-card">
          @if($application->photo)
            <img src="{{ storage_asset($application->photo) }}" alt="Trainee Photo">
          @else
            <div class="photo-caption">
              পাসপোর্ট আকারের<br>দুই কপি ছবি<br>নাম সহ সত্যায়িত
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Official Form Fields (১ to ১৫) --}}

    {{-- ১. প্রার্থীর নাম --}}
    <div class="field-row">
      <div style="display:flex;justify-content:space-between;align-items:baseline;">
        <span><span class="field-title">১. প্রার্থীর নাম :</span> &nbsp; বাংলা : <b>{{ $application->trainee_name_bn ?: $application->name }}</b></span>
        <span style="font-size:11px;color:#222;">( স্পষ্টাক্ষরে ) &nbsp; ইংরেজিতে (বড় হাতের অক্ষরে)</span>
      </div>
      <table class="char-table">
        <tr>
          @for($i = 0; $i < 24; $i++)
            <td>{{ $nameChars[$i] ?? '' }}</td>
          @endfor
        </tr>
      </table>
    </div>

    {{-- ২. পিতার নাম --}}
    <div class="field-row">
      <div style="display:flex;justify-content:space-between;align-items:baseline;">
        <span><span class="field-title">২. পিতার নাম :</span> &nbsp; বাংলা : <b>{{ $application->father_name_bn ?: '—' }}</b></span>
        <span style="font-size:11px;color:#222;">ইংরেজিতে (বড় হাতের অক্ষরে)</span>
      </div>
      <table class="char-table">
        <tr>
          @for($i = 0; $i < 24; $i++)
            <td>{{ $fatherChars[$i] ?? '' }}</td>
          @endfor
        </tr>
      </table>
    </div>

    {{-- ৩. মাতার নাম --}}
    <div class="field-row">
      <div style="display:flex;justify-content:space-between;align-items:baseline;">
        <span><span class="field-title">৩. মাতার নাম :</span> &nbsp; বাংলা : <b>{{ $application->mother_name_bn ?: '—' }}</b></span>
        <span style="font-size:11px;color:#222;">ইংরেজিতে (বড় হাতের অক্ষরে)</span>
      </div>
      <table class="char-table">
        <tr>
          @for($i = 0; $i < 24; $i++)
            <td>{{ $motherChars[$i] ?? '' }}</td>
          @endfor
        </tr>
      </table>
    </div>

    {{-- ৪. অভিভাবকের নাম --}}
    <div class="field-row" style="margin-bottom:6px;">
      <span class="field-title">৪. অভিভাবকের নাম :</span> (পিতার অবর্তমানে) 
      <b>{{ $application->emergency_contact_name ?: ($application->father_name_bn ?: $application->father_name_en ?: '—') }}</b>
    </div>

    {{-- ৫. বর্তমান ঠিকানা ও মোবাইল --}}
    <div class="field-row" style="margin-bottom:3px;">
      <span class="field-title">৫. বর্তমান ঠিকানা :</span>
      <span>{{ $application->pres_address }}, থানা: {{ $application->pres_upazilla }}, জেলা: {{ $application->pres_district }}</span>
    </div>
    <div class="field-row" style="display:flex;justify-content:space-between;margin-bottom:6px;padding-left:14px;">
      <span>মোবাইল নম্বর : <b>{{ $application->contact_no ?: $application->phone }}</b></span>
      <span>অভিভাবকের মোবাইল নম্বর : <b>{{ $application->emergency_contact ?: '—' }}</b></span>
    </div>

    {{-- ৬. স্থায়ী ঠিকানা --}}
    <div class="field-row" style="margin-bottom:6px;">
      <span class="field-title">৬. স্থায়ী ঠিকানা :</span>
      <span>{{ $application->perm_address }}, থানা: {{ $application->perm_upazilla }}, জেলা: {{ $application->perm_district }}</span>
    </div>

    {{-- ৭, ৮, ৯. জাতীয়তা, ধর্ম, রক্তের গ্রুপ --}}
    <div class="field-row" style="display:flex;gap:45px;margin-bottom:6px;">
      <span><span class="field-title">৭. জাতীয়তা :</span> <b>বাংলাদেশী</b></span>
      <span><span class="field-title">৮. ধর্ম :</span> <b>{{ $application->religion ?: '—' }}</b></span>
      <span><span class="field-title">৯. রক্তের গ্রুপ :</span> <b>{{ $bloodVal ?: '—' }}</b></span>
    </div>

    {{-- ১০ ও ১১. জন্ম তারিখ ও NID --}}
    <div class="field-row" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
      <div style="display:flex;align-items:center;">
        <span class="field-title">১০. জন্ম তারিখ (সনদপত্র অনুসারে) :</span>
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
      <div>
        <span class="field-title">১১. জাতীয় পরিচয়পত্র / জন্ম নিবন্ধন নং :</span> 
        <b style="letter-spacing:1px;font-family:monospace;font-size:12px;">{{ $application->ident_no ?: '—' }}</b>
      </div>
    </div>

    {{-- ১২. শিক্ষাগত যোগ্যতা --}}
    <div class="field-row" style="margin-bottom:2px;">
      <span class="field-title">১২. শিক্ষাগত যোগ্যতা :</span>
      <table class="edu-table">
        <thead>
          <tr>
            <th style="width:20%;">পরীক্ষা / শ্রেণী</th>
            <th style="width:34%;">শিক্ষা প্রতিষ্ঠান / বোর্ড</th>
            <th style="width:14%;">পাশের সন</th>
            <th style="width:16%;">জিপিএ / মোট নম্বর</th>
            <th style="width:16%;">বিভাগ (প্রযোজ্য ক্ষেত্রে)</th>
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
    </div>

    {{-- ১৩. স্ব নিয়োজিত/ কর্মরত শিল্পকারখানার নাম --}}
    <div class="field-row" style="margin-bottom:6px;">
      <span class="field-title">১৩. স্ব-নিয়োজিত/ কর্মরত শিল্পকারখানার নাম, পদ ও ঠিকানা :</span>
      <span>{{ $application->employment_status ?: 'প্রযোজ্য নয়' }}</span>
    </div>

    {{-- ১৪. টাকা প্রদানের রশিদ নং ও তারিখ --}}
    <div class="field-row" style="display:flex;justify-content:space-between;margin-bottom:6px;">
      <span><span class="field-title">১৪. টাকা প্রদানের রশিদ নং :</span> <b style="letter-spacing:0.5px;font-family:monospace;">{{ $application->tracking_no }}</b></span>
      <span><span class="field-title">তারিখ :</span> <b>{{ $application->created_at->format('d/m/Y') }}</b></span>
    </div>

    {{-- ১৫. সংযোজন যোগ্য --}}
    <div class="doc-checklist">
      <span class="field-title">১৫. সংযোজন যোগ্য :</span> &nbsp;
      <span>ক. পরীক্ষা পাশের সনদপত্রের ফটোকপি।</span> &nbsp;&nbsp;&nbsp;&nbsp;
      <span>খ. জন্ম নিবন্ধন সনদ / এনআইডি কার্ড এর ফটোকপি।</span> &nbsp;&nbsp;&nbsp;&nbsp;
      <span>গ. সদ্যতোলা পাসপোর্ট আকারের ২ কপি রঙিন ছবি।</span>
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
          অনুমোদনকারীর স্বাক্ষর ও তারিখ
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
