<!doctype html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>Application Print · {{ $application->tracking_no }}</title>
  <style>
    @page {
      size: A4 portrait;
      margin: 12mm 15mm;
    }
    body {
      font-family: system-ui, -apple-system, Arial, sans-serif;
      font-size: 12px;
      line-height: 1.35;
      color: #000;
      background: #fff;
      margin: 0;
      padding: 0;
    }
    .print-container {
      max-width: 100%;
      margin: 0 auto;
    }
    .header-table {
      width: 100%;
      border-bottom: 2px solid #000;
      padding-bottom: 8px;
      margin-bottom: 12px;
    }
    .header-logo {
      width: 70px;
      height: 70px;
      object-fit: contain;
    }
    .header-center {
      text-align: center;
    }
    .header-center h1 {
      margin: 0;
      font-size: 18px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .header-center p {
      margin: 2px 0 0;
      font-size: 11px;
    }
    .form-title-badge {
      display: inline-block;
      background: #000;
      color: #fff;
      padding: 3px 14px;
      font-size: 12px;
      font-weight: bold;
      border-radius: 3px;
      margin-top: 5px;
    }
    .tracking-table {
      width: 100%;
      margin-bottom: 10px;
      font-size: 12px;
    }
    .box-title {
      background: #e5e7eb;
      font-weight: bold;
      padding: 4px 8px;
      font-size: 11px;
      border: 1px solid #000;
      border-bottom: none;
      margin-top: 8px;
      text-transform: uppercase;
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 6px;
    }
    .data-table th, .data-table td {
      border: 1px solid #000;
      padding: 5px 8px;
      font-size: 11px;
      vertical-align: middle;
    }
    .data-table th {
      background: #f3f4f6;
      width: 26%;
      text-align: left;
    }
    .photo-cell {
      width: 105px;
      text-align: center;
      padding: 4px;
    }
    .photo-cell img {
      width: 95px;
      height: 115px;
      object-fit: cover;
      border: 1px solid #000;
    }
    .sig-cell {
      height: 45px;
      text-align: center;
      vertical-align: bottom;
    }
    .sig-cell img {
      max-height: 40px;
      max-width: 130px;
      object-fit: contain;
    }
    .signatures-table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
    }
    .signatures-table td {
      width: 33.33%;
      text-align: center;
      vertical-align: bottom;
      font-size: 11px;
      padding-top: 40px;
    }
    .sig-line {
      border-top: 1px solid #000;
      width: 80%;
      margin: 0 auto 4px;
    }
    .no-print-bar {
      background: #0f5132;
      color: #fff;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    .no-print-bar button {
      background: #c59b27;
      color: #000;
      border: none;
      font-weight: bold;
      padding: 8px 18px;
      border-radius: 4px;
      cursor: pointer;
    }
    @media print {
      .no-print-bar {
        display: none !important;
      }
    }
  </style>
</head>
<body>

<div class="no-print-bar">
  <span>Trainee Application: <strong>{{ $application->tracking_no }}</strong> ({{ $application->trainee_name_en }})</span>
  <div>
    <button onclick="window.print()">🖨️ Print Form</button>
  </div>
</div>

<div class="print-container">
  <table class="header-table">
    <tr>
      <td style="width: 80px; text-align: left;">
        @php
          $logoSrc = storage_asset($settings->get('site_logo'), 'assets/MC Logo.png');
        @endphp
        <img class="header-logo" src="{{ $logoSrc }}" alt="Logo">
      </td>
      <td class="header-center">
        <h1>{{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }}</h1>
        <p>{{ $settings->get('address', 'Chattogram, Bangladesh') }} · Phone: {{ $settings->get('phone') }} · Email: {{ $settings->get('email') }}</p>
        <span class="form-title-badge">ভর্তি আবেদন ফরম (TRAINEE ADMISSION APPLICATION FORM)</span>
      </td>
      <td style="width: 80px; text-align: right; vertical-align: top;">
        <div style="border: 1px solid #000; padding: 4px; font-size: 10px; text-align: center;">
          Status:<br><strong>{{ strtoupper($application->status) }}</strong>
        </div>
      </td>
    </tr>
  </table>

  <table class="tracking-table">
    <tr>
      <td><strong>Application ID:</strong> <span style="font-size: 13px; font-family: monospace; font-weight: bold;">{{ $application->tracking_no }}</span></td>
      <td style="text-align: right;"><strong>Submission Date:</strong> {{ $application->created_at->format('d/m/Y (h:i A)') }}</td>
    </tr>
  </table>

  <!-- 1. COURSE & BASIC -->
  <div class="box-title">১. কোর্স ও ট্রেড সংক্রান্ত তথ্য (Course & Trade)</div>
  <table class="data-table">
    <tr>
      <th>Applied Trade / Course:</th>
      <td @if(!$application->preferred_shift) colspan="3" @endif><strong>{{ $application->course }}</strong></td>
      @if($application->preferred_shift)
        <th>Preferred Shift:</th>
        <td>{{ $application->preferred_shift }}</td>
      @endif
      <td rowspan="4" class="photo-cell">
        @if($application->photo)
          <img src="{{ storage_asset($application->photo) }}" alt="Trainee Photo">
        @else
          <div style="width: 95px; height: 115px; border: 1px dashed #000; display: flex; align-items: center; justify-content: center; font-size: 10px;">Passport Photo</div>
        @endif
      </td>
    </tr>
    <tr>
      <th>Trainee Name (English):</th>
      <td><strong>{{ $application->trainee_name_en ?: $application->name }}</strong></td>
      <th>Trainee Name (বাংলা):</th>
      <td>{{ $application->trainee_name_bn ?: '—' }}</td>
    </tr>
    <tr>
      <th>Date of Birth:</th>
      <td>{{ $application->dob ? $application->dob->format('d/m/Y') : '—' }}</td>
      <th>Gender / Sex:</th>
      <td>{{ $application->sex }} (PWD: {{ $application->pwd }})</td>
    </tr>
    <tr>
      <th>Identification ({{ $application->ident_type }}):</th>
      <td>
        <strong>{{ $application->ident_no }}</strong>
        @if($application->ident_doc)
          <span style="font-size: 10px; color: #0f5132; font-weight: 600;">(Doc Attached ✓)</span>
        @endif
      </td>
      <th>Contact Mobile:</th>
      <td><strong>{{ $application->contact_no ?: $application->phone }}</strong></td>
    </tr>
  </table>

  <!-- 2. PARENTAL & HOUSEHOLD -->
  <div class="box-title">২. পিতা, মাতা ও পারিবারিক তথ্য (Parental & Household Profile)</div>
  <table class="data-table">
    <tr>
      <th>Father's Name (English):</th>
      <td>{{ $application->father_name_en }}</td>
      <th>Father's Name (বাংলা):</th>
      <td>{{ $application->father_name_bn }}</td>
    </tr>
    <tr>
      <th>Mother's Name (English):</th>
      <td>{{ $application->mother_name_en }}</td>
      <th>Mother's Name (বাংলা):</th>
      <td>{{ $application->mother_name_bn }}</td>
    </tr>
    <tr>
      <th>Emergency Contact:</th>
      <td>{{ $application->emergency_contact ?: '—' }}</td>
      <th>Household Members:</th>
      <td>{{ $application->household_members ?: '—' }} Persons</td>
    </tr>
    <tr>
      <th>Source of Family Income:</th>
      <td>{{ $application->income_source ?: '—' }}</td>
      <th>Monthly Income:</th>
      <td>{{ $application->monthly_income ? number_format($application->monthly_income, 2) . ' BDT' : '—' }}</td>
    </tr>
    <tr>
      <th>Annual Household Income:</th>
      <td><strong>{{ $application->yearly_income ? number_format($application->yearly_income, 2) . ' BDT' : ($application->monthly_income ? number_format($application->monthly_income * 12, 2) . ' BDT' : '—') }}</strong></td>
      <th>Has Bank Account?:</th>
      <td>{{ $application->has_bank_account ?: 'No' }}</td>
    </tr>
  </table>

  <!-- 3. ADDRESSES & DEMOGRAPHICS -->
  <div class="box-title">৩. ঠিকানা ও সামাজিক তথ্য (Address & Socio-Demographics)</div>
  <table class="data-table">
    <tr>
      <th>স্থায়ী ঠিকানা (Permanent):</th>
      <td colspan="3">
        {{ $application->perm_address }}, Upazilla/Thana: {{ $application->perm_upazilla }}, District: {{ $application->perm_district }}, Division: {{ $application->perm_division }} ({{ $application->perm_area_type }})
      </td>
    </tr>
    <tr>
      <th>বর্তমান ঠিকানা (Present):</th>
      <td colspan="3">
        {{ $application->pres_address }}, Upazilla/Thana: {{ $application->pres_upazilla }}, District: {{ $application->pres_district }}, Division: {{ $application->pres_division }}
      </td>
    </tr>
    <tr>
      <th>Religion:</th>
      <td>{{ $application->religion }}</td>
      <th>Ethnicity (নৃগোষ্ঠী):</th>
      <td>{{ $application->ethnicity }}</td>
    </tr>
    <tr>
      <th>Educational Qualification:</th>
      <td>{{ $application->education_level ?: $application->education }}</td>
      <th>Marital Status:</th>
      <td>{{ $application->marital_status ?: 'Single' }}</td>
    </tr>
  </table>

  <!-- 4. SKILLS & PAST EMPLOYMENT -->
  <div class="box-title">৪. পূর্ব অভিজ্ঞতা ও কর্মসংস্থান (Skills & Pre-Training Status)</div>
  <table class="data-table">
    <tr>
      <th>Received Past Skills Training?:</th>
      <td>{{ $application->past_training ?: 'No' }}</td>
      <th>Pre-Training Employment:</th>
      <td>{{ $application->employment_status ?: 'Unemployed' }}</td>
    </tr>
    <tr>
      <th>Monthly Cash Income:</th>
      <td>{{ $application->monthly_cash_income ? number_format($application->monthly_cash_income, 2) . ' BDT' : '0.00 BDT' }}</td>
      <th>Heard About Training Via:</th>
      <td>{{ $application->training_media_source ?: 'College Notice' }}</td>
    </tr>
  </table>

  <!-- DECLARATION & SIGNATURES -->
  <p style="font-size: 10px; margin: 12px 0 0; text-align: justify; line-height: 1.4;">
    <strong>অঙ্গীকারনামা:</strong> আমি এই মর্মে ঘোষণা করছি যে, এই ভর্তি আবেদনপত্রে প্রদত্ত সকল তথ্য ও বিবরণ আমার জ্ঞান ও বিশ্বাস মতে সম্পূর্ণ সত্য ও সঠিক। কোনো অসত্য বা ভুল প্রমাণিত হলে আমার আবেদন/ভর্তি বাতিল বলিয়া গণ্য হইবে।
  </p>

  <table class="signatures-table">
    <tr>
      <td>
        <div class="sig-cell">
          @if($application->signature_photo)
            <img src="{{ storage_asset($application->signature_photo) }}" alt="Trainee Signature">
          @endif
        </div>
        <div class="sig-line"></div>
        আবেদনকারীর স্বাক্ষর ও তারিখ<br>(Applicant's Signature)
      </td>
      <td>
        <div class="sig-cell"></div>
        <div class="sig-line"></div>
        যাচাইকারী কর্মকর্তার স্বাক্ষর<br>(Verified By)
      </td>
      <td>
        <div class="sig-cell"></div>
        <div class="sig-line"></div>
        অধ্যক্ষ / ভর্তি ইনচার্জ<br>(Principal / In-Charge)
      </td>
    </tr>
  </table>
</div>

</body>
</html>
