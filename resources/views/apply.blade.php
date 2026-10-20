@php
  $isBn = ($currentLocale ?? 'en') === 'bn';
  $primaryColor = $settings->get('primary_color', '#0f5132');
  $primaryHover = $settings->get('primary_hover', '#093722');
  $secondaryColor = $settings->get('secondary_color', '#c59b27');
  $secondaryHover = $settings->get('secondary_hover', '#a37d18');
@endphp
<!doctype html>
<html lang="{{ $isBn ? 'bn' : 'en' }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $isBn ? 'অনলাইন ট্রেইনী ভর্তি আবেদন ফরম' : 'Online Trainee Admission Form' }} · {{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }}</title>
  <link rel="stylesheet" href="{{ asset('css/site.css') }}">
  <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
  <style>
    :root {
      --primary-green: {{ $primaryColor }};
      --primary-hover: {{ $primaryHover }};
      --accent-gold: {{ $secondaryColor }};
      --accent-hover: {{ $secondaryHover }};
      --card-border: #d8e2dc;
      --bg-subtle: #f8faf9;
      --text-dark: #1e293b;
    }
    body {
      background: #f1f5f3;
      color: var(--text-dark);
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .apply-header {
      background: {{ $primaryHover }};
      color: #fff;
      padding: 20px 0;
      border-bottom: 4px solid var(--accent-gold);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .apply-header .inner {
      max-width: 1050px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      align-items: center;
      gap: 18px;
    }
    .apply-header img {
      width: 75px;
      height: 75px;
      object-fit: contain;
      background: #fff;
      padding: 4px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .apply-header h1 {
      margin: 0;
      font-size: 22px;
      font-weight: 700;
      letter-spacing: 0.3px;
    }
    .apply-header p {
      margin: 4px 0 0;
      font-size: 14px;
      color: #d1e7dd;
    }
    .apply-container {
      max-width: 1050px;
      margin: 30px auto 60px;
      padding: 0 20px;
    }
    .form-card {
      background: #fff;
      border-radius: 12px;
      border: 1px solid var(--card-border);
      box-shadow: 0 6px 20px rgba(15, 81, 50, 0.06);
      padding: 32px 36px;
    }
    .section-title {
      font-size: 17px;
      font-weight: 700;
      color: var(--primary-green);
      padding-bottom: 8px;
      border-bottom: 2px solid #e2ebe6;
      margin: 28px 0 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .section-title:first-of-type {
      margin-top: 0;
    }
    .req {
      color: #dc2626;
      font-weight: bold;
    }
    .grid-3 {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 16px;
      margin-bottom: 14px;
    }
    .grid-2 {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 16px;
      margin-bottom: 14px;
    }
    .grid-4 {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      margin-bottom: 14px;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    .form-group label {
      font-size: 13px;
      font-weight: 600;
      color: #334155;
    }
    .form-group input, .form-group select, .form-group textarea {
      padding: 9px 12px;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      font-size: 14px;
      background: #fff;
      transition: all 0.2s ease;
      color: #1e293b;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
      outline: none;
      border-color: var(--primary-green);
      box-shadow: 0 0 0 3px rgba(15, 81, 50, 0.15);
    }
    .form-group input[readonly] {
      background: #f1f5f9;
      color: #64748b;
      cursor: not-allowed;
    }
    .upload-box {
      background: var(--bg-subtle);
      border: 1px dashed #94a3b8;
      border-radius: 8px;
      padding: 14px;
      text-align: center;
      transition: all 0.2s ease;
    }
    .upload-box:hover {
      border-color: var(--primary-green);
      background: #f0fdf4;
    }
    .upload-box input[type="file"] {
      font-size: 12px;
      width: 100%;
    }
    .upload-preview {
      margin-top: 8px;
      display: inline-block;
      max-height: 80px;
      max-width: 100px;
      object-fit: contain;
      border: 1px solid #cbd5e1;
      border-radius: 4px;
      background: #fff;
      padding: 2px;
    }
    .note-text {
      font-size: 11px;
      color: #dc2626;
      margin-top: 4px;
      display: block;
    }
    .btn-copy-address {
      background: #0f5132;
      color: #fff;
      border: none;
      padding: 6px 14px;
      font-size: 12px;
      font-weight: 600;
      border-radius: 20px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background 0.2s;
    }
    .btn-copy-address:hover {
      background: #093722;
    }
    .shift-radios {
      display: flex;
      gap: 20px;
      align-items: center;
      margin-top: 6px;
    }
    .shift-radios label {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 13px;
      cursor: pointer;
      font-weight: 500;
    }
    .declaration-box {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      padding: 16px 20px;
      border-radius: 8px;
      margin: 24px 0 20px;
    }
    .declaration-box label {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      font-size: 13px;
      color: #334155;
      line-height: 1.5;
      cursor: pointer;
    }
    .declaration-box input[type="checkbox"] {
      margin-top: 3px;
      width: 18px;
      height: 18px;
      accent-color: var(--primary-green);
    }
    .submit-bar {
      display: flex;
      justify-content: flex-end;
      gap: 16px;
      align-items: center;
    }
    .btn-submit {
      background: #0f5132;
      color: #fff;
      border: none;
      padding: 13px 36px;
      font-size: 16px;
      font-weight: 700;
      border-radius: 8px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(15, 81, 50, 0.25);
      transition: all 0.2s ease;
    }
    .btn-submit:hover {
      background: #0a3d25;
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(15, 81, 50, 0.35);
    }
    .back-home {
      color: #64748b;
      font-size: 14px;
      text-decoration: none;
      font-weight: 500;
    }
    .back-home:hover {
      color: var(--primary-green);
      text-decoration: underline;
    }
    .alert-error {
      background: #fef2f2;
      border-left: 4px solid #ef4444;
      padding: 14px 18px;
      border-radius: 6px;
      margin-bottom: 24px;
      color: #991b1b;
      font-size: 13px;
    }
    .alert-error ul {
      margin: 6px 0 0 18px;
      padding: 0;
    }
    .header-home-btn {
      margin-left: auto;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.35);
      color: #fff;
      padding: 9px 18px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease;
      white-space: nowrap;
    }
    .header-home-btn:hover {
      background: #fff;
      color: var(--primary-green);
      border-color: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.18);
      transform: translateY(-1px);
    }
    /* Skeuomorphic Flag Language Switcher */
    .lang-switch-box {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      user-select: none;
      flex-shrink: 0;
      vertical-align: middle;
    }
    .lang-switch-box .lang-code {
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 0.3px;
      text-decoration: none;
      transition: color 0.2s ease;
      line-height: 1;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .lang-switch-box .lang-code.active {
      color: #ffffff;
      font-weight: 900;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }
    .lang-switch-box .lang-code.inactive {
      color: rgba(255, 255, 255, 0.6);
    }
    .lang-switch-box .lang-code:hover {
      color: #ffffff;
    }
    .toggle-slider-track {
      position: relative;
      display: inline-block;
      width: 52px;
      height: 27px;
      background: #e2e8f0;
      border-radius: 999px;
      border: 1px solid #cbd5e1;
      box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.25), inset 0 1px 2px rgba(0, 0, 0, 0.15);
      box-sizing: border-box;
      padding: 2px;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .toggle-slider-track:hover {
      border-color: #94a3b8;
    }
    .toggle-slider-knob {
      width: 21px;
      height: 21px;
      border-radius: 50%;
      background: #ffffff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.35), 0 1px 2px rgba(0, 0, 0, 0.2);
      border: 1.5px solid #ffffff;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      transition: transform 0.24s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .toggle-slider-knob svg {
      width: 100%;
      height: 100%;
      display: block;
      border-radius: 50%;
      pointer-events: none;
    }
    /* When English: knob at left */
    .toggle-slider-track.is-en .toggle-slider-knob {
      transform: translateX(0);
    }
    /* When Bengali: knob at right */
    .toggle-slider-track.is-bn .toggle-slider-knob {
      transform: translateX(25px);
    }
    .apply-header-actions {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 16px;
    }
    @media (max-width: 768px) {
      .apply-header .inner {
        flex-wrap: wrap;
      }
      .apply-header-actions {
        margin-left: 0;
        width: 100%;
        justify-content: space-between;
        margin-top: 8px;
      }
      .header-home-btn {
        margin-left: 0;
        flex: 1;
        justify-content: center;
      }
      .lang-switch-box .lang-code {
        font-size: 11px;
      }
      .toggle-slider-track {
        width: 44px;
        height: 23px;
        padding: 2px;
      }
      .toggle-slider-knob {
        width: 17px;
        height: 17px;
      }
      .toggle-slider-track.is-bn .toggle-slider-knob {
        transform: translateX(21px);
      }
    }
  </style>
</head>
<body>

<header class="apply-header">
  <div class="inner">
    @php
      $logoSrc = storage_asset($settings->get('site_logo'), 'assets/MC Logo.png');
    @endphp
    <img src="{{ $logoSrc }}" alt="MCTSC Crest">
    <div>
      <h1>{{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }}</h1>
      <p>{{ $isBn ? 'অনলাইন ট্রেইনী ভর্তি ও প্রশিক্ষণ আবেদন ফরম' : 'Online Trainee Admission & Training Application Form' }}</p>
    </div>
    <div class="apply-header-actions">
      <a href="{{ route('home') }}" class="header-home-btn">
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        {{ $isBn ? 'হোম পেজে ফিরে যান' : 'Back to Home' }}
      </a>
      <!-- SKEUOMORPHIC FLAG LANGUAGE SWITCHER (EN [ Flag Knob ] বাং) -->
      <div class="lang-switch-box" title="{{ $isBn ? 'Switch to English' : 'বাংলা ভাষায় দেখুন' }}">
        <a href="{{ route('lang.switch', 'en') }}" class="lang-code {{ !$isBn ? 'active' : 'inactive' }}" title="English Version">EN</a>
        <a href="{{ route('lang.switch', $isBn ? 'en' : 'bn') }}" class="toggle-slider-track {{ $isBn ? 'is-bn' : 'is-en' }}" aria-label="{{ $isBn ? 'Switch to English' : 'Switch to Bangla' }}" title="{{ $isBn ? 'Switch to English' : 'Switch to Bangla' }}">
          <div class="toggle-slider-knob">
            @if($isBn)
              <!-- Bangladesh Flag Knob (Round) -->
              <svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <clipPath id="bd-knob-apply-clip"><circle cx="30" cy="30" r="30"/></clipPath>
                <g clip-path="url(#bd-knob-apply-clip)">
                  <rect width="60" height="60" fill="#006a4e"/>
                  <circle cx="28" cy="30" r="14" fill="#f42a41"/>
                </g>
              </svg>
            @else
              <!-- UK Flag Knob (Round) -->
              <svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <clipPath id="uk-knob-apply-clip"><circle cx="30" cy="30" r="30"/></clipPath>
                <g clip-path="url(#uk-knob-apply-clip)">
                  <rect width="60" height="60" fill="#012169"/>
                  <path d="M0 0 L60 60 M60 0 L0 60" stroke="#ffffff" stroke-width="11"/>
                  <path d="M0 0 L60 60" stroke="#C8102E" stroke-width="4"/>
                  <path d="M60 0 L0 60" stroke="#C8102E" stroke-width="4"/>
                  <path d="M30 0 v60 M0 30 h60" stroke="#ffffff" stroke-width="15"/>
                  <path d="M30 0 v60 M0 30 h60" stroke="#C8102E" stroke-width="9"/>
                </g>
              </svg>
            @endif
          </div>
        </a>
        <a href="{{ route('lang.switch', 'bn') }}" class="lang-code {{ $isBn ? 'active' : 'inactive' }}" title="বাংলা সংস্করণ">বাং</a>
      </div>
    </div>
  </div>
</header>

<main class="apply-container">
  <div class="form-card">
    @if ($errors->any())
      <div class="alert-error">
        <strong>আবেদনে কিছু তথ্য সংশোধন প্রয়োজন:</strong>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('apply.store') }}" enctype="multipart/form-data" id="traineeApplicationForm">
      @csrf

      <!-- 1. PROJECT & COURSE SELECTION -->
      <div class="section-title">
        <span>১. প্রজেক্ট ও কোর্স নির্বাচন (Project & Course Selection)</span>
      </div>

      <!-- Project Selection Radio Group -->
      <div class="form-group" style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 14px 18px; margin-bottom: 16px;">
        <label style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 8px; display: block;">
          <span class="req">*</span> প্রজেক্ট / ব্যাচ নির্বাচন করুন (Select Project / Batch):
        </label>
        
        <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 6px;" id="projectRadioGroup">
          {{-- Paid Batch (Default) --}}
          <label class="project-radio-card" style="display: inline-flex; align-items: center; gap: 8px; background: #eef7f2; border: 1.5px solid #0f5132; padding: 10px 16px; border-radius: 6px; cursor: pointer; transition: all 0.2s;">
            <input type="radio" name="project_name" value="Paid Batch" data-fee="6000" @checked(old('project_name', 'Paid Batch') === 'Paid Batch') onchange="updateFees()" style="accent-color: #0f5132; transform: scale(1.2);">
            <div>
              <strong style="color: #0f5132; font-size: 13.5px;">Paid Batch (পেইড ব্যাচ)</strong>
              <small style="display: block; color: #64748b; font-size: 11px;">কোর্স ফি: ৳ ৬,০০০</small>
            </div>
          </label>

          @foreach($projects as $p)
            @php
              $pFee = (float)($p->admission_fee ?? 0);
              $isEcho = strtoupper(trim($p->title)) === 'ECHO';
              if ($isEcho && $pFee == 0) $pFee = 500;
              $isFree = in_array(strtoupper(trim($p->title)), ['NHRDF', 'ASSET']) || $pFee <= 0;
            @endphp
            <label class="project-radio-card" style="display: inline-flex; align-items: center; gap: 8px; background: #fff; border: 1px solid #cbd5e1; padding: 10px 16px; border-radius: 6px; cursor: pointer; transition: all 0.2s;">
              <input type="radio" name="project_name" value="{{ $p->title }}" data-fee="{{ $pFee }}" @checked(old('project_name') === $p->title) onchange="updateFees()" style="accent-color: #0f5132; transform: scale(1.2);">
              <div>
                <strong style="font-size: 13.5px;">{{ $p->title }}</strong>
                <small style="display: block; color: #64748b; font-size: 11px;">
                  @if($pFee > 0)
                    কোর্স ফি: ৳ {{ number_format($pFee, 0) }}
                  @else
                    কোর্স ফি: ৳ ০ (সম্পূর্ণ ফ্রি)
                  @endif
                </small>
              </div>
            </label>
          @endforeach
        </div>

        {{-- Dynamic Fee Summary Box --}}
        <div style="margin-top: 14px; background: #ffffff; border: 1px solid #b8dfd7; border-radius: 6px; padding: 12px 18px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 14px;">
          <div>
            <span style="font-size: 12.5px; color: #475569;">নির্ধারিত কোর্স ফি:</span>
            <strong id="displayCourseFee" style="font-size: 16px; color: #0f5132; margin-left: 6px;">৳ ৬,০০০</strong>
            <input type="hidden" name="course_fee" id="inputCourseFee" value="{{ old('course_fee', 6000) }}">
          </div>

          <div style="display: flex; align-items: center; gap: 8px;">
            <label for="inputDiscount" style="font-size: 12.5px; color: #475569; margin: 0;">বিশেষ ছাড় / ডিস্কাউন্ট (ঐচ্ছিক):</label>
            <div style="position: relative;">
              <span style="position: absolute; left: 8px; top: 7px; color: #64748b; font-weight: bold;">৳</span>
              <input type="number" name="discount" id="inputDiscount" min="0" value="{{ old('discount', 0) }}" oninput="updateFees()" style="width: 110px; padding: 6px 8px 6px 22px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: bold; font-size: 13px;" placeholder="0">
            </div>
          </div>

          <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 6px 14px; border-radius: 4px;">
            <span style="font-size: 12.5px; color: #991b1b;">পরিশোধযোগ্য মোট ফি:</span>
            <strong id="displayPayableFee" style="font-size: 17px; color: #b91c1c; margin-left: 6px;">৳ ৬,০০০</strong>
            <input type="hidden" name="payable_amount" id="inputPayableAmount" value="{{ old('payable_amount', 6000) }}">
          </div>
        </div>
      </div>

      <div class="form-group" style="max-width: 650px;">
        <label><span class="req">*</span> পছন্দের ট্রেড / কোর্স (Desired Course/Trade):</label>
        <select name="course" required id="courseSelect" style="font-size: 15px; padding: 11px 14px; font-weight: 500;">
          <option value="">-- কোর্স সিলেক্ট করুন (Select Course) --</option>
          @foreach($courses as $c)
            <option value="{{ $c->title }}" @selected(old('course', $selectedCourse) == $c->title)>{{ $c->title }}</option>
          @endforeach
          @if($courses->isEmpty())
            <option value="Computer Operation & IT Support" @selected(old('course', $selectedCourse) == 'Computer Operation & IT Support')>Computer Operation & IT Support</option>
            <option value="Electrical Installation & Maintenance (EIM)" @selected(old('course', $selectedCourse) == 'Electrical Installation & Maintenance (EIM)')>Electrical Installation & Maintenance (EIM)</option>
            <option value="Refrigeration & Air Conditioning (RAC)" @selected(old('course', $selectedCourse) == 'Refrigeration & Air Conditioning (RAC)')>Refrigeration & Air Conditioning (RAC)</option>
            <option value="Plumbing & Pipe Fitting" @selected(old('course', $selectedCourse) == 'Plumbing & Pipe Fitting')>Plumbing & Pipe Fitting</option>
            <option value="Sewing Machine Operation (SMO)" @selected(old('course', $selectedCourse) == 'Sewing Machine Operation (SMO)')>Sewing Machine Operation (SMO)</option>
            <option value="Driving & Auto Mechanics" @selected(old('course', $selectedCourse) == 'Driving & Auto Mechanics')>Driving & Auto Mechanics</option>
          @endif
        </select>
      </div>

      <!-- 2. PERSONAL DATA -->
      <div class="section-title">
        <span>২. আবেদনকারীর ব্যক্তিগত তথ্য (Personal Data)</span>
      </div>
      <div class="grid-4">
        <div class="form-group">
          <label><span class="req">*</span> Trainee Name in English:</label>
          <input type="text" name="trainee_name_en" value="{{ old('trainee_name_en') }}" placeholder="Insert trainee name in english" required>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Trainee Name in Bangla:</label>
          <input type="text" name="trainee_name_bn" value="{{ old('trainee_name_bn') }}" placeholder="আবেদনকারীর নাম বাংলায়" required>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Date of Birth:</label>
          <input type="date" name="dob" value="{{ old('dob') }}" required>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Identification type:</label>
          <select name="ident_type" required>
            <option value="">Select one</option>
            <option value="National ID" @selected(old('ident_type') == 'National ID')>National ID (জাতীয় পরিচয়পত্র)</option>
            <option value="Birth Certificate" @selected(old('ident_type') == 'Birth Certificate')>Birth Certificate (জন্ম নিবন্ধন)</option>
            <option value="Passport" @selected(old('ident_type') == 'Passport')>Passport (পাসপোর্ট)</option>
          </select>
        </div>
      </div>

      <div class="grid-4">
        <div class="form-group">
          <label><span class="req">*</span> NID / Birth Reg No (নম্বর):</label>
          <input type="text" name="ident_no" value="{{ old('ident_no') }}" placeholder="NID বা জন্ম সনদ নম্বর লিখুন" required>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Sex (লিঙ্গ):</label>
          <select name="sex" required>
            <option value="">Select one</option>
            <option value="Male" @selected(old('sex') == 'Male')>Male (পুরুষ)</option>
            <option value="Female" @selected(old('sex') == 'Female')>Female (মহিলা)</option>
            <option value="Other" @selected(old('sex') == 'Other')>Other (অন্যান্য)</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Person With Disability (PWD)?:</label>
          <select name="pwd" required>
            <option value="No" @selected(old('pwd', 'No') == 'No')>No</option>
            <option value="Yes" @selected(old('pwd') == 'Yes')>Yes</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Contact Number (মোবাইল নং):</label>
          <input type="text" name="contact_no" value="{{ old('contact_no') }}" placeholder="01XXXXXXXXX" required>
        </div>
      </div>

      <div class="grid-2">
        <div class="form-group">
          <label>Email Address (ইমেইল):</label>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="example@gmail.com">
        </div>
        <div class="form-group">
          <label>Marital Status (বৈবাহিক অবস্থা):</label>
          <select name="marital_status">
            <option value="Single" @selected(old('marital_status', 'Single') == 'Single')>Single (অবিবাহিত)</option>
            <option value="Married" @selected(old('marital_status') == 'Married')>Married (বিবাহিত)</option>
            <option value="Other" @selected(old('marital_status') == 'Other')>Other (অন্যান্য)</option>
          </select>
        </div>
      </div>

      <!-- DEDICATED DOCUMENT & PHOTO UPLOADS -->
      <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 18px 20px; margin: 16px 0 24px;">
        <h4 style="margin: 0 0 14px; font-size: 15px; color: #0f5132; font-weight: 700;">
          📎 প্রয়োজনীয় ছবি ও ডকুমেন্ট আপলোড (Document & Photo Uploads)
        </h4>

        <div class="grid-3">
          <div class="form-group upload-box">
            <label><span class="req">*</span> আবেদনকারীর ছবি (Trainee Photo):</label>
            <input type="file" name="photo" accept=".png, .jpg, .jpeg" required id="photoInput">
            <span class="note-text">*পাসপোর্ট সাইজ ছবি (JPG, PNG - সর্বোচ্চ 3 MB)</span>
            <img id="photoPreview" class="upload-preview" style="display:none;" alt="Preview">
          </div>

          <div class="form-group upload-box">
            <label><span class="req">*</span> NID বা জন্ম নিবন্ধন সনদ (NID / Birth Certificate Doc):</label>
            <input type="file" name="ident_doc" accept=".png, .jpg, .jpeg, .pdf" required id="identDocInput">
            <span class="note-text">*NID বা জন্ম সনদ কপি (PDF, JPG, PNG - সর্বোচ্চ 3 MB)</span>
            <div id="identDocSelected" style="display:none; font-size:12px; color:#15803d; font-weight:600; margin-top:6px;">
              ✓ ফাইল সিলেক্ট হয়েছে: <span id="identDocFileName"></span>
            </div>
            <img id="identDocPreview" class="upload-preview" style="display:none;" alt="NID Preview">
          </div>

          <div class="form-group upload-box">
            <label><span class="req">*</span> আবেদনকারীর স্বাক্ষর (Signature Photo):</label>
            <input type="file" name="signature_photo" accept=".png, .jpg, .jpeg" required id="sigInput">
            <span class="note-text">*স্বাক্ষরের ছবি (JPG, PNG - সর্বোচ্চ 3 MB)</span>
            <img id="sigPreview" class="upload-preview" style="display:none;" alt="Signature Preview">
          </div>
        </div>
      </div>

      <!-- 3. PERMANENT ADDRESS -->
      <div class="section-title">
        <span>৩. স্থায়ী ঠিকানা (Permanent Address)</span>
      </div>
      <div class="grid-3">
        <div class="form-group">
          <label><span class="req">*</span> Division:</label>
          <select name="perm_division" id="permDivision" required>
            <option value="">Select one</option>
            <option value="Chattogram">Chattogram</option>
            <option value="Dhaka">Dhaka</option>
            <option value="Rajshahi">Rajshahi</option>
            <option value="Khulna">Khulna</option>
            <option value="Barishal">Barishal</option>
            <option value="Sylhet">Sylhet</option>
            <option value="Rangpur">Rangpur</option>
            <option value="Mymensingh">Mymensingh</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> District:</label>
          <select name="perm_district" id="permDistrict" required>
            <option value="">Select one</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Upazilla / Thana:</label>
          <input type="text" name="perm_upazilla" id="permUpazilla" value="{{ old('perm_upazilla') }}" placeholder="Upazilla / Thana name" required>
        </div>
      </div>
      <div class="grid-2">
        <div class="form-group">
          <label><span class="req">*</span> Address (গ্রাম / বাসা নং, রোড, পোস্ট অফিস):</label>
          <textarea name="perm_address" id="permAddress" rows="2" placeholder="Write your address please" required>{{ old('perm_address') }}</textarea>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> From Rural or Urban area?:</label>
          <select name="perm_area_type" id="permAreaType" required>
            <option value="">Select one</option>
            <option value="Rural" @selected(old('perm_area_type') == 'Rural')>Rural (পল্লী / গ্রাম)</option>
            <option value="Urban" @selected(old('perm_area_type') == 'Urban')>Urban (পৌরসভা / শহর)</option>
          </select>
        </div>
      </div>

      <!-- 4. PRESENT ADDRESS & DEMOGRAPHICS -->
      <div class="section-title">
        <span>৪. বর্তমান ঠিকানা ও তথ্য (Present Address)</span>
        <button type="button" class="btn-copy-address" id="btnCopyAddress">↓ Same as Permanent</button>
      </div>
      <div class="grid-4">
        <div class="form-group">
          <label><span class="req">*</span> Division:</label>
          <select name="pres_division" id="presDivision" required>
            <option value="">Select one</option>
            <option value="Chattogram">Chattogram</option>
            <option value="Dhaka">Dhaka</option>
            <option value="Rajshahi">Rajshahi</option>
            <option value="Khulna">Khulna</option>
            <option value="Barishal">Barishal</option>
            <option value="Sylhet">Sylhet</option>
            <option value="Rangpur">Rangpur</option>
            <option value="Mymensingh">Mymensingh</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> District:</label>
          <select name="pres_district" id="presDistrict" required>
            <option value="">Select one</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Upazilla / Thana:</label>
          <input type="text" name="pres_upazilla" id="presUpazilla" value="{{ old('pres_upazilla') }}" placeholder="Upazilla / Thana name" required>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Address:</label>
          <textarea name="pres_address" id="presAddress" rows="1" placeholder="Write your address please" required>{{ old('pres_address') }}</textarea>
        </div>
      </div>

      <div class="grid-4">
        <div class="form-group">
          <label><span class="req">*</span> Religion (ধর্ম):</label>
          <select name="religion" required>
            <option value="">Select one</option>
            <option value="Islam" @selected(old('religion') == 'Islam')>Islam</option>
            <option value="Hinduism" @selected(old('religion') == 'Hinduism')>Hinduism</option>
            <option value="Christianity" @selected(old('religion') == 'Christianity')>Christianity</option>
            <option value="Buddhism" @selected(old('religion') == 'Buddhism')>Buddhism</option>
            <option value="Other" @selected(old('religion') == 'Other')>Other</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Ethnicity (নৃগোষ্ঠী):</label>
          <select name="ethnicity" required>
            <option value="">Select one</option>
            <option value="Bengali" @selected(old('ethnicity', 'Bengali') == 'Bengali')>Bengali (বাঙালি)</option>
            <option value="Chakma" @selected(old('ethnicity') == 'Chakma')>Chakma (চাকমা)</option>
            <option value="Marma" @selected(old('ethnicity') == 'Marma')>Marma (মারমা)</option>
            <option value="Garo" @selected(old('ethnicity') == 'Garo')>Garo (গারো)</option>
            <option value="Santal" @selected(old('ethnicity') == 'Santal')>Santal (সাঁওতাল)</option>
            <option value="Tripura" @selected(old('ethnicity') == 'Tripura')>Tripura (ত্রিপুরা)</option>
            <option value="Other" @selected(old('ethnicity') == 'Other')>Other (অন্যান্য)</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Educational Qualification:</label>
          <select name="education_level" required>
            <option value="">Select one</option>
            <option value="Class 8 / JSC / JDC" @selected(old('education_level') == 'Class 8 / JSC / JDC')>Class 8 / JSC / JDC</option>
            <option value="SSC / Dakhil / Equivalent" @selected(old('education_level') == 'SSC / Dakhil / Equivalent')>SSC / Dakhil / Equivalent</option>
            <option value="HSC / Alim / Equivalent" @selected(old('education_level') == 'HSC / Alim / Equivalent')>HSC / Alim / Equivalent</option>
            <option value="Diploma in Engineering" @selected(old('education_level') == 'Diploma in Engineering')>Diploma in Engineering</option>
            <option value="Degree / Graduate" @selected(old('education_level') == 'Degree / Graduate')>Degree / Graduate</option>
            <option value="Masters / Post Graduate" @selected(old('education_level') == 'Masters / Post Graduate')>Masters / Post Graduate</option>
            <option value="Other" @selected(old('education_level') == 'Other')>Other</option>
          </select>
        </div>
        <div class="form-group">
          <label>Marital status:</label>
          <select name="marital_status">
            <option value="">Select one</option>
            <option value="Single" @selected(old('marital_status') == 'Single')>Single (অবিবাহিত)</option>
            <option value="Married" @selected(old('marital_status') == 'Married')>Married (বিবাহিত)</option>
            <option value="Widowed" @selected(old('marital_status') == 'Widowed')>Widowed (বিধবা/বিপত্নীক)</option>
            <option value="Divorced" @selected(old('marital_status') == 'Divorced')>Divorced (তালাকপ্রাপ্ত)</option>
          </select>
        </div>
      </div>

      <!-- 5. HOUSEHOLD PROFILE -->
      <div class="section-title">
        <span>৫. পারিবারিক তথ্য (Household Profile)</span>
      </div>
      <div class="grid-4">
        <div class="form-group">
          <label><span class="req">*</span> Father's Name in English:</label>
          <input type="text" name="father_name_en" value="{{ old('father_name_en') }}" placeholder="Father Name English" required>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Father's Name in Bangla:</label>
          <input type="text" name="father_name_bn" value="{{ old('father_name_bn') }}" placeholder="পিতার নাম বাংলায়" required>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Mother's Name in English:</label>
          <input type="text" name="mother_name_en" value="{{ old('mother_name_en') }}" placeholder="Mother Name English" required>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Mother's Name in Bangla:</label>
          <input type="text" name="mother_name_bn" value="{{ old('mother_name_bn') }}" placeholder="মাতার নাম বাংলায়" required>
        </div>
      </div>

      <div class="grid-4">
        <div class="form-group">
          <label>Emergency Contact Number:</label>
          <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="Insert number">
        </div>
        <div class="form-group">
          <label>Number of household member:</label>
          <input type="number" name="household_members" id="householdMembers" value="{{ old('household_members', 4) }}" min="1" max="25" placeholder="Insert family member">
        </div>
        <div class="form-group">
          <label>Source of household income:</label>
          <input type="text" name="income_source" value="{{ old('income_source') }}" placeholder="e.g. Agriculture / Business / Job">
        </div>
        <div class="form-group">
          <label>Monthly household income (BDT):</label>
          <input type="number" name="monthly_income" id="monthlyIncome" value="{{ old('monthly_income') }}" min="0" placeholder="Insert amount">
        </div>
      </div>

      <div class="grid-2">
        <div class="form-group">
          <label>Annual household income / বার্ষিক পারিবারিক আয় (BDT):</label>
          <input type="text" name="yearly_income" id="yearlyIncome" value="{{ old('yearly_income') }}" readonly placeholder="স্বয়ংক্রিয়ভাবে হিসাব হবে (মাসিক আয় × ১২)" style="background:#f8fafc; font-weight:700; color:#0f5132;">
        </div>
        <div class="form-group">
          <label>Do you have any Bank Account?:</label>
          <select name="has_bank_account">
            <option value="No" @selected(old('has_bank_account') == 'No')>No</option>
            <option value="Yes" @selected(old('has_bank_account') == 'Yes')>Yes</option>
          </select>
        </div>
      </div>

      <!-- 6. SKILLS & PAST EMPLOYMENT -->
      <div class="section-title">
        <span>৬. পূর্ব অভিজ্ঞতা ও আয়ের তথ্য (Skills, Experiences, Past Employment & Income)</span>
      </div>
      <div class="grid-3">
        <div class="form-group">
          <label><span class="req">*</span> Received any skills training in the past?:</label>
          <select name="past_training" required>
            <option value="">Please select</option>
            <option value="No" @selected(old('past_training') == 'No')>No (না)</option>
            <option value="Yes" @selected(old('past_training') == 'Yes')>Yes (হ্যাঁ)</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Employment status before training:</label>
          <select name="employment_status" required>
            <option value="">Please select</option>
            <option value="Unemployed" @selected(old('employment_status') == 'Unemployed')>Unemployed (বেকার)</option>
            <option value="Employed" @selected(old('employment_status') == 'Employed')>Employed (চাকরিজীবী)</option>
            <option value="Self-employed" @selected(old('employment_status') == 'Self-employed')>Self-employed (স্বনিয়োজিত)</option>
            <option value="Student" @selected(old('employment_status') == 'Student')>Student (শিক্ষার্থী)</option>
            <option value="Housewife" @selected(old('employment_status') == 'Housewife')>Housewife (গৃহিণী)</option>
            <option value="Other" @selected(old('employment_status') == 'Other')>Other (অন্যান্য)</option>
          </select>
        </div>
        <div class="form-group">
          <label><span class="req">*</span> Amount of Monthly Income (BDT) - Cash:</label>
          <input type="number" name="monthly_cash_income" value="{{ old('monthly_cash_income', 0) }}" min="0" placeholder="Insert amount" required>
        </div>
      </div>

      <div class="form-group" style="max-width: 500px; margin-bottom: 20px;">
        <label>How did you learn about this training?:</label>
        <select name="training_media_source">
          <option value="">Please select</option>
          <option value="Social Media / Facebook" @selected(old('training_media_source') == 'Social Media / Facebook')>Social Media / Facebook</option>
          <option value="Newspaper / Advertisement" @selected(old('training_media_source') == 'Newspaper / Advertisement')>Newspaper / Advertisement</option>
          <option value="Leaflet / Poster / Banner" @selected(old('training_media_source') == 'Leaflet / Poster / Banner')>Leaflet / Poster / Banner</option>
          <option value="Friends / Family / Relatives" @selected(old('training_media_source') == 'Friends / Family / Relatives')>Friends / Family / Relatives</option>
          <option value="College Notice / Office" @selected(old('training_media_source') == 'College Notice / Office')>College Notice / Office</option>
          <option value="Other" @selected(old('training_media_source') == 'Other')>Other</option>
        </select>
      </div>

      <!-- 7. DECLARATION & SUBMIT -->
      <div class="declaration-box">
        <label>
          <input type="checkbox" required name="agreement">
          <span>আমি এই মর্মে অঙ্গীকার করছি যে, উপরে প্রদত্ত সকল তথ্য সত্য ও নির্ভুল। ভবিষ্যতে কোনো তথ্য অসত্য বা ভুল প্রমাণিত হলে কলেজ কর্তৃপক্ষের গৃহীত যেকোনো সিদ্ধান্ত বা প্রার্থিতা বাতিল আমি মেনে নিতে বাধ্য থাকিব। (I declare that the information provided above is true and correct to the best of my knowledge).</span>
        </label>
      </div>

      <div class="submit-bar">
        <a href="{{ route('home') }}" class="back-home">← হোম পেজে ফিরে যান (Back to Home)</a>
        <button type="submit" class="btn-submit">আবেদন দাখিল করুন (Submit Application) ➔</button>
      </div>
    </form>
  </div>
</main>

<script>
  // Bangladesh Districts mapping
  const districtData = {
    'Chattogram': ['Chattogram', 'Cox\'s Bazar', 'Cumilla', 'Feni', 'Brahmanbaria', 'Rangamati', 'Noakhali', 'Chandpur', 'Lakshmipur', 'Khagrachhari', 'Bandarban'],
    'Dhaka': ['Dhaka', 'Gazipur', 'Narayanganj', 'Tangail', 'Faridpur', 'Manikganj', 'Munshiganj', 'Narsingdi', 'Gopalganj', 'Kishoreganj', 'Madaripur', 'Rajbari', 'Shariatpur'],
    'Rajshahi': ['Rajshahi', 'Bogura', 'Pabna', 'Sirajganj', 'Naogaon', 'Natore', 'Chapai Nawabganj', 'Joypurhat'],
    'Khulna': ['Khulna', 'Jashore', 'Kushtia', 'Satkhira', 'Bagerhat', 'Jhenaidah', 'Chuadanga', 'Magura', 'Meherpur', 'Narail'],
    'Barishal': ['Barishal', 'Patuakhali', 'Bhola', 'Pirojpur', 'Barguna', 'Jhalokati'],
    'Sylhet': ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj'],
    'Rangpur': ['Rangpur', 'Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari', 'Panchagarh', 'Thakurgaon'],
    'Mymensingh': ['Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur']
  };

  function updateDistricts(divSelectId, distSelectId, selectedDistrict = '') {
    const divSelect = document.getElementById(divSelectId);
    const distSelect = document.getElementById(distSelectId);
    const division = divSelect.value;
    distSelect.innerHTML = '<option value="">Select one</option>';
    if (districtData[division]) {
      districtData[division].forEach(d => {
        const opt = document.createElement('option');
        opt.value = d;
        opt.textContent = d;
        if (d === selectedDistrict) opt.selected = true;
        distSelect.appendChild(opt);
      });
    }
  }

  document.getElementById('permDivision').addEventListener('change', function() {
    updateDistricts('permDivision', 'permDistrict');
  });
  document.getElementById('presDivision').addEventListener('change', function() {
    updateDistricts('presDivision', 'presDistrict');
  });

  // Initialize districts if old values exist
  updateDistricts('permDivision', 'permDistrict', '{{ old('perm_district', 'Chattogram') }}');
  updateDistricts('presDivision', 'presDistrict', '{{ old('pres_district', 'Chattogram') }}');
  document.getElementById('permDivision').value = '{{ old('perm_division', 'Chattogram') }}';
  document.getElementById('presDivision').value = '{{ old('pres_division', 'Chattogram') }}';

  // Copy Permanent to Present Address
  document.getElementById('btnCopyAddress').addEventListener('click', function() {
    const pDiv = document.getElementById('permDivision').value;
    const pDist = document.getElementById('permDistrict').value;
    const pUpaz = document.getElementById('permUpazilla').value;
    const pAddr = document.getElementById('permAddress').value;

    document.getElementById('presDivision').value = pDiv;
    updateDistricts('presDivision', 'presDistrict', pDist);
    document.getElementById('presUpazilla').value = pUpaz;
    document.getElementById('presAddress').value = pAddr;
  });

  // Auto calculate Yearly Income (Monthly income * 12)
  function calcYearlyIncome() {
    const income = parseFloat(document.getElementById('monthlyIncome').value) || 0;
    if (income > 0) {
      document.getElementById('yearlyIncome').value = (income * 12).toFixed(2);
    } else {
      document.getElementById('yearlyIncome').value = '';
    }
  }
  document.getElementById('monthlyIncome').addEventListener('input', calcYearlyIncome);
  calcYearlyIncome();

  // Photo & Signature Live Previews
  function setupPreview(inputId, previewId) {
    const inp = document.getElementById(inputId);
    const prev = document.getElementById(previewId);
    inp.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          prev.src = e.target.result;
          prev.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
      }
    });
  }
  setupPreview('photoInput', 'photoPreview');
  setupPreview('sigInput', 'sigPreview');

  // NID / Birth Certificate document file select indicator
  const identInp = document.getElementById('identDocInput');
  if (identInp) {
    identInp.addEventListener('change', function() {
      const file = this.files[0];
      const selectedBox = document.getElementById('identDocSelected');
      const nameSpan = document.getElementById('identDocFileName');
      const previewImg = document.getElementById('identDocPreview');
      if (file) {
        selectedBox.style.display = 'block';
        nameSpan.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        if (file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'inline-block';
          };
          reader.readAsDataURL(file);
        } else {
          previewImg.style.display = 'none';
        }
      } else {
        selectedBox.style.display = 'none';
        previewImg.style.display = 'none';
      }
    });
  }
  // Dynamic Fee & Project Update Function
  function updateFees() {
    const selectedRadio = document.querySelector('input[name="project_name"]:checked');
    const fee = selectedRadio ? parseFloat(selectedRadio.dataset.fee || 6000) : 6000;
    const discountInput = document.getElementById('inputDiscount');
    const discount = Math.max(0, parseFloat(discountInput.value || 0));
    const payable = Math.max(0, fee - discount);

    document.getElementById('inputCourseFee').value = fee;
    document.getElementById('displayCourseFee').textContent = fee > 0 ? ('৳ ' + fee.toLocaleString()) : '৳ ০ (ফ্রি)';
    document.getElementById('inputPayableAmount').value = payable;
    document.getElementById('displayPayableFee').textContent = payable > 0 ? ('৳ ' + payable.toLocaleString()) : '৳ ০ (ফ্রি)';

    // Visual card highlight
    document.querySelectorAll('.project-radio-card').forEach(card => {
      const radio = card.querySelector('input[type="radio"]');
      if (radio && radio.checked) {
        card.style.borderColor = '#0f5132';
        card.style.background = '#eef7f2';
      } else {
        card.style.borderColor = '#cbd5e1';
        card.style.background = '#ffffff';
      }
    });
  }
  document.addEventListener('DOMContentLoaded', updateFees);
</script>

</body>
</html>
