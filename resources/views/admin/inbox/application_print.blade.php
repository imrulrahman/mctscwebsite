<!doctype html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trainee Application Print · {{ $application->tracking_no }}</title>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      background: #f1f5f3;
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
    .no-print-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      background: #fff;
      padding: 10px 18px;
      border-radius: 8px;
      border: 1px solid #d1d5db;
      box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #0f5132;
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
      padding: 7px 14px;
      border-radius: 5px;
      background: #eef7f2;
      border: 1px solid #c8e0d4;
    }
    .btn-print {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #0f5132;
      color: #fff;
      border: none;
      padding: 8px 20px;
      border-radius: 5px;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(15, 81, 50, 0.2);
    }
    .btn-print:hover {
      background: #093722;
    }
    .official-form {
      background: #fff;
      border: 1.5px solid #000;
      padding: 16px 20px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      position: relative;
    }
    .bismillah {
      text-align: center;
      font-size: 13px;
      font-weight: bold;
      margin-bottom: 3px;
    }
    .form-header-grid {
      display: grid;
      grid-template-columns: 160px 1fr 140px;
      gap: 10px;
      align-items: start;
      margin-bottom: 10px;
      padding-bottom: 6px;
      border-bottom: 1.5px solid #000;
    }
    .seal-box {
      border: 1px solid #000;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-size: 11px;
      font-weight: bold;
      padding: 4px;
    }
    .seal-meta {
      font-size: 11px;
      margin-top: 5px;
      line-height: 1.35;
    }
    .header-center {
      text-align: center;
    }
    .sub-head {
      font-size: 11.5px;
      margin-bottom: 2px;
    }
    .inst-title {
      font-size: 17px;
      font-weight: 800;
      margin: 0 0 2px 0;
      color: #0f5132;
      letter-spacing: 0.3px;
    }
    .inst-address {
      font-size: 11px;
      margin-bottom: 2px;
    }
    .inst-phone {
      font-size: 11px;
      margin-bottom: 3px;
    }
    .instruction-note {
      font-size: 10.5px;
      font-style: italic;
      color: #333;
      margin-bottom: 3px;
    }
    .code-badge {
      display: inline-block;
      font-weight: bold;
      font-size: 11.5px;
      border: 1px solid #000;
      padding: 1px 8px;
      background: #fafafa;
    }
    .header-right {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 6px;
    }
    .photo-frame {
      width: 105px;
      height: 115px;
      border: 1px solid #000;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 2px;
      overflow: hidden;
      background: #fff;
    }
    .photo-frame img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .photo-caption {
      font-size: 9.5px;
      line-height: 1.25;
      color: #222;
      padding: 2px;
    }
    .qr-block {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .qr-block img {
      width: 65px;
      height: 65px;
      border: 1px solid #000;
      padding: 2px;
      background: #fff;
    }
    .qr-tracking {
      font-size: 9px;
      font-family: monospace;
      font-weight: bold;
      margin-top: 1px;
      letter-spacing: 0.5px;
    }
    .field-row {
      margin-bottom: 5px;
      font-size: 12px;
    }
    .field-title {
      font-weight: bold;
    }
    .char-table {
      width: 100%;
      border-collapse: collapse;
      margin: 2px 0 5px 0;
    }
    .char-table td {
      border: 1px solid #000;
      width: 4.166%;
      height: 18px;
      text-align: center;
      font-family: 'Courier New', Courier, monospace;
      font-size: 12px;
      font-weight: bold;
      padding: 0;
      line-height: 18px;
    }
    .dob-table {
      border-collapse: collapse;
      display: inline-table;
      vertical-align: middle;
      margin-left: 6px;
    }
    .dob-table th, .dob-table td {
      border: 1px solid #000;
      width: 20px;
      height: 17px;
      text-align: center;
      font-size: 10.5px;
      padding: 0;
    }
    .dob-table th {
      font-weight: normal;
      background: #fdfdfd;
    }
    .dob-table td {
      font-family: 'Courier New', Courier, monospace;
      font-weight: bold;
      font-size: 12px;
    }
    .edu-table {
      width: 100%;
      border-collapse: collapse;
      margin: 4px 0 6px 0;
    }
    .edu-table th, .edu-table td {
      border: 1px solid #000;
      padding: 3px 6px;
      font-size: 11px;
      text-align: center;
    }
    .edu-table th {
      font-weight: bold;
      background: #fbfbfb;
    }
    .doc-checklist {
      font-size: 11px;
      margin: 4px 0 10px 0;
      line-height: 1.45;
    }
    .signatures-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 15px;
      margin-top: 25px;
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
      max-width: 140px;
      object-fit: contain;
    }
    .sig-border {
      width: 90%;
      border-top: 1px dashed #000;
      padding-top: 4px;
      font-size: 11px;
      font-weight: bold;
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
        padding: 8px 14px !important;
        margin: 0 !important;
        width: 100% !important;
      }
      .inst-title {
        color: #000 !important;
      }
      @page {
        size: A4 portrait;
        margin: 8mm 10mm;
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
@endphp

<div class="page-wrap">
  {{-- Buttons Bar (Hidden Completely During Print) --}}
  <div class="no-print no-print-bar">
    <a href="{{ route('admin.applications.show', $application) }}" class="btn-back">
      ← Back to Application View
    </a>
    <div style="font-size:12px;color:#555;">
      Tracking No: <strong>{{ $application->tracking_no }}</strong> | Status: <strong>{{ strtoupper($application->status) }}</strong>
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
          শিক্ষা প্রতিষ্ঠানের<br>সীলমোহর
        </div>
        <div class="seal-meta">
          <div>ভাই-বোন সংখ্যা :- <b>{{ $application->household_members ?? '—' }}</b></div>
          <div>ট্রেড :- <b>{{ $application->course }}</b></div>
          <div style="margin-top:2px;">ভর্তির তারিখঃ <b>{{ $application->created_at->format('d/m/Y') }}</b></div>
        </div>
      </div>

      {{-- Header Center: Institute Details --}}
      <div class="header-center">
        <div class="sub-head">বাংলাদেশ কারিগরি শিক্ষা বোর্ড অনুমোদিত প্রশিক্ষণ কেন্দ্র</div>
        <h1 class="inst-title">{{ $settings->get('institution_name', 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ') }}</h1>
        <div class="inst-address">{{ $settings->get('address', 'ছদু চৌধুরী রোড, সাগরিকা, পাহাড়তলী, চট্টগ্রাম - ৪২১৯।') }}</div>
        <div class="inst-phone">মোবাইল নং {{ $settings->get('phone', '01880-919275') }}</div>
        <div class="instruction-note">(শিক্ষাগত যোগ্যতার সনদ অনুসারে স্ব-হস্তে পূরণ করতে হবে)</div>
        <div class="code-badge">প্রতিষ্ঠান কোড : 70102</div>
      </div>

      {{-- Header Right: Photo Frame & Auto-generated QR Code --}}
      <div class="header-right">
        <div class="photo-frame">
          @if($application->photo)
            <img src="{{ storage_asset($application->photo) }}" alt="Trainee Photo">
          @else
            <div class="photo-caption">
              পাসপোর্ট আকারের<br>দুই কপি ছবি<br>নাম সহ সত্যায়িত
            </div>
          @endif
        </div>
        <div class="qr-block">
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(url('/?tracking=' . $application->tracking_no)) }}" alt="QR Code">
          <span class="qr-tracking">{{ $application->tracking_no }}</span>
        </div>
      </div>
    </div>

    {{-- Form Fields --}}

    {{-- 1. Candidate Name --}}
    <div class="field-row">
      <div style="display:flex;justify-content:space-between;">
        <span><span class="field-title">১. প্রার্থীর নাম :</span> &nbsp; বাংলা : <b>{{ $application->trainee_name_bn ?: $application->name }}</b></span>
        <span style="font-size:11px;">(স্পষ্টাক্ষরে) &nbsp; ইংরেজিতে (বড় হাতের অক্ষরে)</span>
      </div>
      <table class="char-table">
        <tr>
          @for($i = 0; $i < 24; $i++)
            <td>{{ $nameChars[$i] ?? '' }}</td>
          @endfor
        </tr>
      </table>
    </div>

    {{-- 2. Father Name --}}
    <div class="field-row">
      <div style="display:flex;justify-content:space-between;">
        <span><span class="field-title">২. পিতার নাম :</span> &nbsp; বাংলা : <b>{{ $application->father_name_bn ?: '—' }}</b></span>
        <span style="font-size:11px;">ইংরেজিতে (বড় হাতের অক্ষরে)</span>
      </div>
      <table class="char-table">
        <tr>
          @for($i = 0; $i < 24; $i++)
            <td>{{ $fatherChars[$i] ?? '' }}</td>
          @endfor
        </tr>
      </table>
    </div>

    {{-- 3. Mother Name --}}
    <div class="field-row">
      <div style="display:flex;justify-content:space-between;">
        <span><span class="field-title">৩. মাতার নাম :</span> &nbsp; বাংলা : <b>{{ $application->mother_name_bn ?: '—' }}</b></span>
        <span style="font-size:11px;">ইংরেজিতে (বড় হাতের অক্ষরে)</span>
      </div>
      <table class="char-table">
        <tr>
          @for($i = 0; $i < 24; $i++)
            <td>{{ $motherChars[$i] ?? '' }}</td>
          @endfor
        </tr>
      </table>
    </div>

    {{-- 4. Guardian Name --}}
    <div class="field-row" style="margin-bottom:6px;">
      <span class="field-title">৪. অভিভাবকের নাম (পিতার অবর্তমানে) :</span>
      <span>{{ $application->emergency_contact_name ?: ($application->father_name_bn ?: $application->father_name_en ?: '—') }}</span>
    </div>

    {{-- 5. Present Address & Mobile --}}
    <div class="field-row" style="margin-bottom:4px;">
      <span class="field-title">৫. বর্তমান ঠিকানা :</span>
      <span>{{ $application->pres_address }}, থানা: {{ $application->pres_upazilla }}, জেলা: {{ $application->pres_district }}</span>
    </div>
    <div class="field-row" style="display:flex;justify-content:space-between;margin-bottom:6px;padding-left:14px;">
      <span>মোবাইল নম্বর : <b>{{ $application->contact_no ?: $application->phone }}</b></span>
      <span>অভিভাবকের মোবাইল নম্বর : <b>{{ $application->emergency_contact ?: '—' }}</b></span>
    </div>

    {{-- 6. Permanent Address --}}
    <div class="field-row" style="margin-bottom:6px;">
      <span class="field-title">৬. স্থায়ী ঠিকানা :</span>
      <span>{{ $application->perm_address }}, থানা: {{ $application->perm_upazilla }}, জেলা: {{ $application->perm_district }}</span>
    </div>

    {{-- 7, 8, 9. Nationality, Religion, Blood Group --}}
    <div class="field-row" style="display:flex;gap:35px;margin-bottom:6px;">
      <span><span class="field-title">৭. জাতীয়তা :</span> <b>বাংলাদেশী</b></span>
      <span><span class="field-title">৮. ধর্ম :</span> <b>{{ $application->religion ?: '—' }}</b></span>
      <span><span class="field-title">৯. রক্তের গ্রুপ :</span> <b>{{ $application->ethnicity ?: '—' }}</b></span>
    </div>

    {{-- 10. Date of Birth --}}
    <div class="field-row" style="display:flex;align-items:center;margin-bottom:6px;">
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

    {{-- 11. NID / Birth Reg --}}
    <div class="field-row" style="margin-bottom:6px;">
      <span class="field-title">১১. জাতীয় পরিচয়পত্র / জন্ম নিবন্ধন নং :</span>
      <b style="letter-spacing:0.8px;">{{ $application->ident_no ?: '—' }}</b>
    </div>

    {{-- 12. Educational Qualifications Table --}}
    <div class="field-row" style="margin-bottom:2px;">
      <span class="field-title">১২. শিক্ষাগত যোগ্যতা :</span>
      <table class="edu-table">
        <thead>
          <tr>
            <th style="width:22%;">পরীক্ষা/শ্রেণী</th>
            <th style="width:32%;">শিক্ষা প্রতিষ্ঠান/বোর্ড</th>
            <th style="width:14%;">পাশের সন</th>
            <th style="width:16%;">জিপিএ/ মোট নম্বর</th>
            <th style="width:16%;">বিভাগ (প্রযোজ্য ক্ষেত্রে)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><b>{{ $application->education_level ?: '—' }}</b></td>
            <td>—</td>
            <td>—</td>
            <td>—</td>
            <td>—</td>
          </tr>
          <tr>
            <td style="height:17px;"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>

    {{-- 13. Self Employed / Industry --}}
    <div class="field-row" style="margin-bottom:6px;">
      <span class="field-title">১৩. স্ব-নিয়োজিত/ কর্মরত শিল্পকারখানার নাম, পদ ও ঠিকানা :</span>
      <span>{{ $application->employment_status ?: 'প্রযোজ্য নয়' }}</span>
    </div>

    {{-- 14. Payment Receipt & Date --}}
    <div class="field-row" style="display:flex;justify-content:space-between;margin-bottom:6px;">
      <span><span class="field-title">১৪. টাকা প্রদানের রশিদ নং :</span> <b>{{ $application->tracking_no }}</b></span>
      <span><span class="field-title">তারিখ :</span> <b>{{ $application->created_at->format('d/m/Y') }}</b></span>
    </div>

    {{-- 15. Attachments Checklist --}}
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
