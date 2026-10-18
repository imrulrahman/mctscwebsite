<!doctype html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>আবেদন কনফার্মেশন ও প্রাপ্তিস্বীকার · {{ $application->tracking_no }}</title>
  <link rel="stylesheet" href="{{ asset('css/site.css') }}">
  @php
    $primaryColor = $settings->get('primary_color', '#0f5132');
    $secondaryColor = $settings->get('secondary_color', '#c59b27');
  @endphp
  <style>
    body {
      background: #f1f5f3;
      color: #1e293b;
      font-family: system-ui, -apple-system, sans-serif;
      margin: 0;
      padding: 0;
    }
    .confirm-wrap {
      max-width: 850px;
      margin: 30px auto;
      padding: 0 20px;
    }
    .receipt-card {
      background: #fff;
      border-radius: 12px;
      border: 1px solid #d8e2dc;
      padding: 40px;
      box-shadow: 0 8px 24px rgba(15, 81, 50, 0.08);
      position: relative;
    }
    .receipt-header {
      display: flex;
      align-items: center;
      gap: 18px;
      border-bottom: 2px solid {{ $primaryColor }};
      padding-bottom: 18px;
      margin-bottom: 24px;
    }
    .receipt-header img {
      width: 75px;
      height: 75px;
      object-fit: contain;
    }
    .receipt-header h1 {
      margin: 0;
      font-size: 20px;
      color: {{ $primaryColor }};
    }
    .receipt-header p {
      margin: 3px 0 0;
      font-size: 13px;
      color: #64748b;
    }
    .status-badge {
      display: inline-block;
      padding: 6px 16px;
      border-radius: 20px;
      font-weight: 700;
      font-size: 13px;
      background: #dcfce7;
      color: #15803d;
      margin-bottom: 20px;
    }
    .tracking-banner {
      background: #f0fdf4;
      border: 1px solid #86efac;
      padding: 16px 20px;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }
    .tracking-banner strong {
      font-size: 14px;
      color: #166534;
    }
    .tracking-banner span.no {
      font-size: 20px;
      font-weight: 800;
      color: #0f5132;
      letter-spacing: 1px;
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 24px;
    }
    .data-table th, .data-table td {
      padding: 10px 14px;
      border: 1px solid #e2e8f0;
      font-size: 14px;
      text-align: left;
    }
    .data-table th {
      background: #f8fafc;
      width: 28%;
      color: #475569;
    }
    .photo-cell {
      text-align: center;
      vertical-align: middle;
      width: 130px;
      padding: 10px;
    }
    .photo-cell img {
      width: 110px;
      height: 125px;
      object-fit: cover;
      border: 1px solid #cbd5e1;
      border-radius: 4px;
    }
    .sig-img {
      max-height: 45px;
      max-width: 140px;
      object-fit: contain;
      display: block;
      margin-top: 4px;
    }
    .actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px dashed #cbd5e1;
    }
    .btn-print {
      background: {{ $primaryColor }};
      color: #fff;
      padding: 10px 24px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-print:hover {
      opacity: 0.9;
    }
    .btn-home {
      color: #475569;
      text-decoration: none;
      font-size: 14px;
    }
    @media print {
      body {
        background: #fff;
      }
      .confirm-wrap {
        margin: 0;
        padding: 0;
        max-width: 100%;
      }
      .receipt-card {
        border: none;
        box-shadow: none;
        padding: 0;
      }
      .actions, .status-badge {
        display: none !important;
      }
    }
  </style>
</head>
<body>

<div class="confirm-wrap">
  <div class="no-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 7px; color: {{ $primaryColor }}; text-decoration: none; font-weight: 600; font-size: 13px; background: #fff; padding: 7px 16px; border-radius: 6px; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
      <svg style="width:15px;height:15px;fill:currentColor;" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
      হোম পেজে ফিরে যান (Back to Home)
    </a>
    <button onclick="window.print()" style="display: inline-flex; align-items: center; gap: 6px; background: {{ $primaryColor }}; color: #fff; border: none; padding: 8px 18px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      🖨️ প্রিন্ট রিসিট
    </button>
  </div>

  <div class="receipt-card">
    <div class="status-badge">✓ আবেদন সফলভাবে গৃহীত হয়েছে (Application Submitted)</div>

    <div class="receipt-header">
      @php
        $logoSrc = storage_asset($settings->get('site_logo'), 'assets/MC Logo.png');
      @endphp
      <img src="{{ $logoSrc }}" alt="Logo">
      <div>
        <h1>{{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }}</h1>
        <p>{{ $settings->get('address', 'Chattogram, Bangladesh') }} · ফোন: {{ $settings->get('phone') }}</p>
        <p><strong>অনলাইন ট্রেইনী ভর্তি আবেদন প্রাপ্তিস্বীকার পত্র (Trainee Admission Acknowledgement)</strong></p>
      </div>
    </div>

    <div class="tracking-banner">
      <div>
        <strong>আবেদন ট্র্যাকিং নম্বর (Application ID):</strong><br>
        <span class="no">{{ $application->tracking_no }}</span>
      </div>
      <div style="text-align: right; font-size: 13px; color: #475569;">
        আবেদনের তারিখ: <strong>{{ $application->created_at->format('d M, Y (h:i A)') }}</strong><br>
        অবস্থা: <strong style="color:#0f5132;">প্রাথমিক আবেদন গৃহীত (New)</strong>
      </div>
    </div>

    <table class="data-table">
      <tr>
        <th>আবেদিত কোর্স ও ট্রেড:</th>
        <td><strong>{{ $application->course }}</strong></td>
        <td rowspan="4" class="photo-cell">
          @if($application->photo)
            <img src="{{ storage_asset($application->photo) }}" alt="Trainee Photo">
          @else
            <div style="width:110px;height:125px;border:1px dashed #ccc;display:flex;align-items:center;justify-content:center;font-size:12px;color:#999;">ছবি নেই</div>
          @endif
          <small style="display:block;margin-top:4px;font-size:11px;color:#666;">আবেদনকারীর ছবি</small>
        </td>
      </tr>
      @if($application->preferred_shift)
      <tr>
        <th>পছন্দের শিফট (Shift):</th>
        <td>{{ $application->preferred_shift }}</td>
      </tr>
      @endif
      <tr>
        <th>আবেদনকারীর নাম (English):</th>
        <td><strong>{{ $application->trainee_name_en }}</strong></td>
      </tr>
      <tr>
        <th>আবেদনকারীর নাম (বাংলা):</th>
        <td>{{ $application->trainee_name_bn }}</td>
      </tr>
      <tr>
        <th>পিতার নাম (Father's Name):</th>
        <td colspan="2">{{ $application->father_name_en }} ({{ $application->father_name_bn }})</td>
      </tr>
      <tr>
        <th>মাতার নাম (Mother's Name):</th>
        <td colspan="2">{{ $application->mother_name_en }} ({{ $application->mother_name_bn }})</td>
      </tr>
      <tr>
        <th>জন্ম তারিখ ও লিঙ্গ:</th>
        <td colspan="2">{{ $application->dob ? $application->dob->format('d/m/Y') : '—' }} &nbsp;·&nbsp; লিঙ্গ: {{ $application->sex }} &nbsp;·&nbsp; প্রতিবন্ধিতা (PWD): {{ $application->pwd }}</td>
      </tr>
      <tr>
        <th>পরিচয়পত্র ({{ $application->ident_type }}):</th>
        <td colspan="2">
          <strong>{{ $application->ident_no }}</strong>
          @if($application->ident_doc)
            &nbsp;·&nbsp; <a href="{{ storage_asset($application->ident_doc) }}" target="_blank" style="color:#0f5132;font-weight:600;text-decoration:underline;">[📄 সংযুক্ত NID/সনদ দেখুন]</a>
          @endif
        </td>
      </tr>
      <tr>
        <th>যোগাযোগের মোবাইল নং:</th>
        <td colspan="2"><strong>{{ $application->contact_no }}</strong> {{ $application->email ? '· ইমেইল: ' . $application->email : '' }}</td>
      </tr>
      @if($application->monthly_income || $application->yearly_income)
      <tr>
        <th>পারিবারিক আয়:</th>
        <td colspan="2">
          মাসিক: ৳{{ number_format((float)$application->monthly_income, 2) }} &nbsp;·&nbsp;
          বার্ষিক আয়: <strong>৳{{ number_format((float)($application->yearly_income ?: $application->monthly_income * 12), 2) }}</strong>
        </td>
      </tr>
      @endif
      <tr>
        <th>শিক্ষাগত যোগ্যতা ও ধর্ম:</th>
        <td colspan="2">{{ $application->education_level }} &nbsp;·&nbsp; ধর্ম: {{ $application->religion }} &nbsp;·&nbsp; নৃগোষ্ঠী: {{ $application->ethnicity }}</td>
      </tr>
      <tr>
        <th>স্থায়ী ঠিকানা:</th>
        <td colspan="2">{{ $application->perm_address }}, থানা: {{ $application->perm_upazilla }}, জেলা: {{ $application->perm_district }} ({{ $application->perm_area_type }})</td>
      </tr>
      <tr>
        <th>বর্তমান ঠিকানা:</th>
        <td colspan="2">{{ $application->pres_address }}, থানা: {{ $application->pres_upazilla }}, জেলা: {{ $application->pres_district }}</td>
      </tr>
      <tr>
        <th>স্বাক্ষর (Signature):</th>
        <td colspan="2">
          @if($application->signature_photo)
            <img class="sig-img" src="{{ storage_asset($application->signature_photo) }}" alt="Signature">
          @else
            <span>—</span>
          @endif
        </td>
      </tr>
    </table>

    <div style="background:#f8fafc;padding:12px;border-radius:6px;font-size:12px;color:#64748b;line-height:1.5;">
      💡 <strong>নির্দেশনা:</strong> আপনার আবেদন সফলভাবে ডাটাবেজে সংরক্ষিত হয়েছে। আপনার আবেদন ট্র্যাকিং নম্বরটি (<strong>{{ $application->tracking_no }}</strong>) ভবিষ্যৎ অনুসন্ধানের জন্য সংরক্ষণ করুন। কলেজ কর্তৃপক্ষ যাচাই-বাছাই শেষে আপনার মোবাইল নম্বরে যোগাযোগ করবে।
    </div>

    <div class="actions">
      <a href="{{ route('home') }}" class="btn-home">← হোম পেজে ফিরে যান</a>
      <button onclick="window.print()" class="btn-print">🖨️ এই প্রাপ্তিস্বীকার পত্রটি প্রিন্ট করুন</button>
    </div>
  </div>
</div>

</body>
</html>
