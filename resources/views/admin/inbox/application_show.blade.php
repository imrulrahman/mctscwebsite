@extends('admin.layout')
@section('title', 'Application Review & Voucher · ' . ($application->tracking_no ?: ('#' . $application->id)))
@section('crumb')
  <a href="{{ route('admin.applications') }}" style="color: inherit; text-decoration: none;">Admissions</a> / Details & Voucher
@endsection

@section('content')
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

  // Name Uppercase Array for Grid
  $engName = strtoupper(trim($application->trainee_name_en ?: $application->name));
  $fatherName = strtoupper(trim($application->father_name_en ?: ''));
  $motherName = strtoupper(trim($application->mother_name_en ?: ''));

  $isApproved = $application->isApproved();
  $isFree = $application->isFreeProject();
  $isPaid = $application->isPaid();

  $currentFee = (float)($application->course_fee ?: 6000);
  $currentDiscount = (float)($application->discount ?: 0);
  $currentPayable = max(0, $currentFee - $currentDiscount);
  $currentPaid = (float)($application->paid_amount ?: 0);
  $currentDue = max(0, $currentPayable - $currentPaid);
@endphp

<style>
  /* Base Container */
  .doc-container {
    max-width: 960px;
    margin: 0 auto 50px;
  }

  /* Top Navigation Bar */
  .doc-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 12px 20px;
    margin-bottom: 22px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    flex-wrap: wrap;
    gap: 12px;
  }
  .doc-top-left {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
  }
  .btn-nav-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    padding: 6px 12px;
    background: #f1f5f9;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
  }
  .btn-nav-back:hover {
    background: #e2e8f0;
    color: #0f172a;
  }
  .doc-top-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }
  .btn-action-print {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #005953;
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
  }
  .btn-action-print:hover {
    background: #00443f;
    color: #ffffff;
  }
  .btn-jump-voucher {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e6f4f1;
    color: #005953;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    border: 1px solid #b8dfd7;
    transition: all 0.2s;
  }
  .btn-jump-voucher:hover {
    background: #b8dfd7;
  }

  /* DOCUMENT 1: Official Admission Form Preview (A4 Paper Aesthetic) */
  .a4-form-sheet {
    background: #ffffff;
    border: 1.5px solid #005953;
    border-radius: 4px;
    padding: 22px 26px 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    margin-bottom: 30px;
    position: relative;
    font-family: 'SolaimanLipi', 'Kalpurush', 'Hind Siliguri', 'Nikosh', 'Arial', sans-serif;
    font-size: 11.5px;
    line-height: 1.4;
    color: #0f172a;
  }
  .sheet-badge-strip {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 6px 14px;
    margin: -22px -26px 16px -26px;
    border-radius: 3px 3px 0 0;
    font-size: 12px;
    color: #475569;
  }

  /* Header inside admission form */
  .form-header-grid {
    display: grid;
    grid-template-columns: 80px 1fr 100px;
    gap: 12px;
    align-items: center;
    margin-bottom: 10px;
  }
  .header-logo-box {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .header-logo-box img {
    width: 76px;
    height: 76px;
    object-fit: contain;
  }
  .header-center-info {
    text-align: center;
  }
  .bismillah-text {
    font-size: 12px;
    color: #334155;
    margin-bottom: 2px;
    font-family: 'Amiri', serif, sans-serif;
  }
  .header-inst-bn {
    font-size: 21px;
    font-weight: 800;
    color: #005953;
    margin: 0;
    line-height: 1.2;
  }
  .header-inst-en {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    margin-top: 2px;
  }
  .header-inst-sub {
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    margin-top: 3px;
  }
  .header-inst-code {
    display: inline-block;
    background: #e6f4f1;
    border: 1px solid #b8dfd7;
    color: #005953;
    padding: 1px 8px;
    border-radius: 3px;
    font-weight: 700;
    font-size: 11px;
    margin-top: 2px;
  }

  /* Right column: QR code on top, Photo directly underneath */
  .header-media-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
  }
  .qr-code-frame {
    border: 1px solid #005953;
    padding: 3px;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    border-radius: 2px;
  }
  .qr-code-frame img {
    width: 52px;
    height: 52px;
    display: block;
  }
  .qr-code-label {
    font-size: 7.5px;
    font-family: monospace;
    font-weight: bold;
    color: #005953;
    margin-top: 1px;
  }
  .passport-photo-frame {
    width: 74px;
    height: 86px;
    border: 1px solid #005953;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
    padding: 1px;
    border-radius: 2px;
    position: relative;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
  }
  .passport-photo-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .photo-empty-tag {
    font-size: 8.5px;
    line-height: 1.2;
    color: #64748b;
    padding: 4px;
  }

  /* Form Title Banner */
  .form-title-strip {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 8px 0 10px;
  }
  .form-title-pill {
    background: #005953;
    color: #ffffff;
    font-size: 13px;
    font-weight: bold;
    padding: 4px 18px;
    border-radius: 3px;
    letter-spacing: 0.3px;
  }
  .form-app-no-box {
    border: 1px solid #005953;
    padding: 3px 12px;
    font-size: 11.5px;
    border-radius: 3px;
    background: #ffffff;
  }

  /* Section cards inside form */
  .a4-section-card {
    border: 1px solid #7cb5a9;
    border-radius: 3px;
    margin-bottom: 7px;
    background: #ffffff;
  }
  .a4-section-head {
    background: #d8eee7;
    color: #005953;
    font-weight: bold;
    font-size: 11.5px;
    padding: 3px 10px;
    border-bottom: 1px solid #7cb5a9;
  }
  .a4-section-body {
    padding: 6px 10px 7px;
  }
  .a4-row {
    display: flex;
    align-items: baseline;
    margin-bottom: 4px;
    font-size: 11.5px;
    line-height: 1.35;
  }
  .a4-row:last-child {
    margin-bottom: 0;
  }
  .a4-lbl {
    color: #0f172a;
    font-weight: 600;
    white-space: nowrap;
  }
  .a4-val {
    flex: 1;
    border-bottom: 1px dotted #64748b;
    margin-left: 6px;
    margin-right: 8px;
    padding-bottom: 1px;
    color: #0f172a;
  }
  .a4-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  /* Character Box Grids (24-box English Name) */
  .char-grid-row {
    display: flex;
    flex-wrap: nowrap;
    gap: 2px;
    margin: 3px 0 5px 6px;
    overflow-x: auto;
  }
  .char-box {
    width: 17px;
    height: 20px;
    border: 1px solid #475569;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Courier New', Courier, monospace;
    font-size: 11px;
    font-weight: bold;
    color: #0f172a;
    text-transform: uppercase;
  }

  /* Educational Table */
  .a4-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 3px;
    font-size: 10.5px;
  }
  .a4-table th, .a4-table td {
    border: 1px solid #7cb5a9;
    padding: 3px 6px;
    text-align: left;
  }
  .a4-table th {
    background: #eef7f4;
    color: #005953;
    font-weight: bold;
  }

  /* Signatures Row */
  .a4-sig-row {
    display: flex;
    justify-content: space-between;
    margin-top: 14px;
    text-align: center;
  }
  .a4-sig-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  .a4-sig-space {
    height: 40px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    margin-bottom: 4px;
  }
  .a4-sig-space img {
    max-height: 38px;
    max-width: 130px;
    object-fit: contain;
  }
  .a4-sig-line {
    width: 85%;
    border-top: 1px solid #334155;
    padding-top: 3px;
    font-size: 10.5px;
    font-weight: bold;
    color: #0f172a;
  }

  /* DOCUMENT 2: Professional Ryans / Super Shop Style Payment Voucher */
  .voucher-billing-sheet {
    background: #ffffff;
    border: 2px solid #005953;
    border-radius: 6px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 40px;
  }
  .voucher-top-banner {
    background: #005953;
    color: #ffffff;
    padding: 10px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
  }
  .voucher-top-banner h2 {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .voucher-inner {
    padding: 22px 26px;
  }

  /* Super Shop / Ryans Invoice Header */
  .bill-header-grid {
    display: grid;
    grid-template-columns: 1fr 260px;
    gap: 20px;
    border-bottom: 2px solid #0f172a;
    padding-bottom: 14px;
    margin-bottom: 16px;
  }
  .bill-company-info h3 {
    margin: 0 0 4px;
    font-size: 20px;
    font-weight: 800;
    color: #005953;
  }
  .bill-company-info p {
    margin: 0;
    font-size: 12px;
    color: #475569;
    line-height: 1.45;
  }
  .bill-meta-box {
    border: 1.5px solid #0f172a;
    background: #f8fafc;
    border-radius: 4px;
    overflow: hidden;
  }
  .bill-meta-title {
    background: #0f172a;
    color: #ffffff;
    font-size: 12px;
    font-weight: 800;
    text-align: center;
    padding: 3px 8px;
    letter-spacing: 0.5px;
  }
  .bill-meta-body {
    padding: 6px 12px;
    font-size: 12px;
    display: grid;
    grid-template-columns: 80px 1fr;
    gap: 4px;
    line-height: 1.4;
  }
  .bill-meta-body strong {
    color: #0f172a;
  }

  /* Customer / Trainee Strip (Ryans style) */
  .bill-customer-strip {
    background: #f1f5f9;
    border-left: 4px solid #005953;
    padding: 8px 14px;
    border-radius: 0 4px 4px 0;
    font-size: 12px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 8px 16px;
    margin-bottom: 18px;
  }

  /* Interactive Project Selection POS Chips */
  .bill-proj-selector-wrap {
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 14px 18px;
    margin-bottom: 18px;
  }
  .bill-proj-selector-title {
    font-size: 13.5px;
    font-weight: 800;
    color: #005953;
    margin: 0 0 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .proj-chips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
  }
  .proj-chip-card {
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    border-radius: 6px;
    padding: 10px 14px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    gap: 2px;
    position: relative;
  }
  .proj-chip-card:hover {
    border-color: #005953;
    background: #f0fdf9;
  }
  .proj-chip-card.active {
    border-color: #005953;
    background: #e6f4f1;
    box-shadow: 0 0 0 1px #005953;
  }
  .proj-chip-card input[type="radio"] {
    position: absolute;
    top: 10px;
    right: 10px;
    accent-color: #005953;
    transform: scale(1.15);
  }
  .proj-chip-name {
    font-size: 13.5px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .proj-chip-card.active .proj-chip-name {
    color: #005953;
  }
  .proj-chip-fee {
    font-size: 12.5px;
    font-weight: 700;
    color: #047857;
  }
  .proj-chip-desc {
    font-size: 11px;
    color: #64748b;
  }

  /* Itemized Table (Ryans & Super Shop Standard) */
  .bill-items-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    margin-bottom: 12px;
  }
  .bill-items-table th {
    background: #0f172a;
    color: #ffffff;
    font-weight: 700;
    padding: 8px 10px;
    text-align: left;
    border: 1px solid #0f172a;
    letter-spacing: 0.3px;
  }
  .bill-items-table td {
    border: 1px solid #cbd5e1;
    padding: 8px 10px;
    vertical-align: middle;
  }
  .bill-input-fee, .bill-input-discount, .bill-input-paid {
    width: 100%;
    box-sizing: border-box;
    padding: 6px 8px;
    border: 1.5px solid #cbd5e1;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 700;
    font-family: monospace;
    color: #0f172a;
    text-align: right;
  }
  .bill-input-fee:focus, .bill-input-discount:focus, .bill-input-paid:focus {
    outline: none;
    border-color: #005953;
    background: #f0fdf9;
  }

  /* Summary Table on Bottom Right */
  .bill-summary-wrap {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
    margin-bottom: 16px;
  }
  .bill-summary-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
  }
  .bill-summary-table td {
    border: 1px solid #cbd5e1;
    padding: 6px 12px;
  }
  .bill-summary-lbl {
    font-weight: 600;
    color: #334155;
    background: #f8fafc;
  }
  .bill-summary-val {
    text-align: right;
    font-weight: 700;
    font-family: monospace;
    font-size: 13px;
    color: #0f172a;
  }

  /* Amount in Words box */
  .bill-words-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-left: 3px solid #005953;
    padding: 8px 12px;
    font-size: 12px;
    color: #0f172a;
    margin-bottom: 14px;
    line-height: 1.4;
  }

  /* Transaction & Payment Inputs */
  .bill-pay-options-card {
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 12px 16px;
    margin-bottom: 18px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
  }
  .bill-pay-lbl {
    font-size: 11.5px;
    font-weight: 700;
    color: #334155;
    display: block;
    margin-bottom: 4px;
  }
  .bill-pay-ctrl {
    width: 100%;
    padding: 7px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    font-size: 12.5px;
    background: #ffffff;
    box-sizing: border-box;
  }

  /* Action Buttons Box */
  .bill-action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 2px solid #e2e8f0;
    padding-top: 18px;
    flex-wrap: wrap;
    gap: 12px;
  }
  .btn-submit-approve {
    background: #005953;
    color: #ffffff;
    border: none;
    padding: 12px 28px;
    border-radius: 6px;
    font-size: 14.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 6px rgba(0,89,83,0.25);
    transition: background 0.2s;
  }
  .btn-submit-approve:hover {
    background: #00443f;
  }

  /* Terms and Signatures in Voucher */
  .bill-terms-row {
    font-size: 10.5px;
    color: #64748b;
    border-top: 1px dashed #cbd5e1;
    padding-top: 10px;
    margin-top: 16px;
    line-height: 1.45;
  }
  .bill-sig-row {
    display: flex;
    justify-content: space-between;
    margin-top: 28px;
    text-align: center;
  }
  .bill-sig-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  .bill-sig-line {
    width: 80%;
    border-top: 1px solid #334155;
    padding-top: 4px;
    font-size: 11px;
    font-weight: 700;
    color: #0f172a;
  }
</style>

<div class="doc-container">

  {{-- TOP CONTROLS & BREADCRUMB --}}
  <div class="doc-top-bar">
    <div class="doc-top-left">
      <a href="{{ route('admin.applications') }}" class="btn-nav-back">
        ← Back to Applications List
      </a>
      <div>
        <span style="font-size:14px; font-weight:800; color:#0f172a;">
          {{ $application->trainee_name_en ?: $application->name }}
        </span>
        <span style="font-size:12px; color:#64748b; margin-left:6px;">
          (Tracking: <strong style="color:#005953; font-family:monospace;">{{ $application->tracking_no }}</strong>)
        </span>
      </div>
      <div>
        @if($isApproved)
          <span style="background:#d1fae5; color:#065f46; font-size:12px; font-weight:700; padding:3px 10px; border-radius:4px; border:1px solid #a7f3d0;">
            ✔ Approved
          </span>
        @elseif($application->status === 'rejected')
          <span style="background:#fee2e2; color:#991b1b; font-size:12px; font-weight:700; padding:3px 10px; border-radius:4px; border:1px solid #fecaca;">
            ✖ Rejected
          </span>
        @else
          <span style="background:#fef3c7; color:#92400e; font-size:12px; font-weight:700; padding:3px 10px; border-radius:4px; border:1px solid #fde68a;">
            ⏳ Pending Approval
          </span>
        @endif
      </div>
    </div>

    <div class="doc-top-actions">
      <a href="#billing-approval-voucher" class="btn-jump-voucher">
        ⚡ {{ $isApproved ? 'Update Fee & Voucher' : 'Assign Project & Approve' }} ↓
      </a>
      <a href="{{ route('admin.applications.print', $application) }}" target="_blank" class="btn-action-print">
        🖨️ Print Admission Form
      </a>
      @if($isApproved || $isPaid)
        <a href="{{ route('admin.applications.voucher', $application) }}" target="_blank" class="btn-action-print" style="background:#047857;">
          🧾 Print Payment Voucher
        </a>
        <a href="{{ route('admin.applications.voucher', [$application, 'duplicate' => 1]) }}" target="_blank" class="btn-action-print" style="background:#b91c1c;">
          🔁 Print Duplicate Voucher
        </a>
        <form method="POST" action="{{ route('admin.applications.undo-payment', $application) }}" style="display:inline;" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই আবেদনটির পেমেন্ট এবং অনুমোদন বাতিল (Undo) করতে চান? এটি আবেদনটিকে পুনরায় বিবেচনাধীন/বকেয়া অবস্থায় ফিরিয়ে নেবে।');">
          @csrf
          <button type="submit" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:8px 14px;border-radius:6px;font-weight:700;font-size:12.5px;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
            ↩ Undo Payment
          </button>
        </form>
      @endif
    </div>
  </div>

  {{-- ========================================================================= --}}
  {{-- SECTION 1: AUTHENTIC ADMISSION APPLICATION FORM (ভর্তি আবেদন ফরম)         --}}
  {{-- ========================================================================= --}}
  <div class="a4-form-sheet" id="admission-form-preview">

    <div class="sheet-badge-strip">
      <div>
        <strong style="color:#005953;">📄 Official Admission Form Preview</strong> · A4 Format
      </div>
      <div>
        Submission Date: <strong>{{ $application->created_at->format('d/m/Y (h:i A)') }}</strong>
      </div>
    </div>

    {{-- Header Grid --}}
    <div class="form-header-grid">
      {{-- Header Left Logo --}}
      <div class="header-logo-box">
        <img src="{{ asset('assets/MC Logo.png') }}" alt="MC Logo">
      </div>

      {{-- Header Center --}}
      <div class="header-center-info">
        <div class="bismillah-text">বিসমিল্লাহির রাহমানির রাহিম</div>
        <h1 class="header-inst-bn">{{ $settings->get('institution_name_bn', 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল অ্যান্ড কলেজ') }}</h1>
        <div class="header-inst-en">Mahmudunnabi Chowdhury Technical School and College</div>
        <div class="header-inst-sub">
          বাংলাদেশ কারিগরি শিক্ষা বোর্ড (BTEB) অনুমোদিত প্রশিক্ষণ কেন্দ্র &nbsp;|&nbsp;
          <span class="header-inst-code">প্রতিষ্ঠান কোড : ৭০১০২</span>
        </div>
        <div style="font-size:10px; color:#475569; margin-top:2px;">
          ছদু চৌধুরী রোড, সাগরিকা, পাহাড়তলী, চট্টগ্রাম - ৪২১৯ &nbsp;|&nbsp; মোবাইল: ০১৮৮০-৯১৯২৭৫
        </div>
      </div>

      {{-- Header Right: QR Code on TOP, Passport Photo DIRECTLY UNDERNEATH --}}
      <div class="header-media-column">
        {{-- TOP: Scannable QR Code --}}
        <div class="qr-code-frame" title="Application QR Tracking Code">
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(url('/?tracking=' . $application->tracking_no)) }}" alt="QR Code">
          <span class="qr-code-label">{{ $application->tracking_no }}</span>
        </div>

        {{-- BOTTOM: Student Passport Photo Frame --}}
        <div class="passport-photo-frame" title="Trainee Passport Photo">
          @if($application->photo)
            <img src="{{ $application->photoUrl() }}" alt="Trainee Photo">
          @else
            <div class="photo-empty-tag">
              শিক্ষার্থীর ছবি<br>(Passport)
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Title & Application Number Bar --}}
    <div class="form-title-strip">
      <div class="form-title-pill">ভর্তি আবেদন ফরম (TRAINEE ADMISSION APPLICATION FORM)</div>
      <div class="form-app-no-box">
        আবেদন নং : <strong style="color:#005953; font-family:monospace;">{{ $application->tracking_no }}</strong>
      </div>
    </div>

    {{-- SECTION ক: ভর্তি ও শিক্ষার্থীর তথ্য --}}
    <div class="a4-section-card">
      <div class="a4-section-head">ক. ভর্তি ও প্রার্থীর ব্যক্তিগত তথ্য (Course & Trainee Profile)</div>
      <div class="a4-section-body">
        <div class="a4-row">
          <span class="a4-lbl">১. নির্বাচিত কোর্স / ট্রেড :</span>
          <span class="a4-val" style="font-weight:700; color:#005953;">{{ $application->course }}</span>
          <span class="a4-lbl">পছন্দের শিফট :</span>
          <span class="a4-val" style="flex:0 0 140px;">{{ $application->preferred_shift ?: 'যেকোনো শিফট (Any)' }}</span>
        </div>

        <div class="a4-row">
          <span class="a4-lbl">২. প্রার্থীর নাম (বাংলা) :</span>
          <span class="a4-val" style="font-weight:600;">{{ $application->trainee_name_bn ?: $application->name }}</span>
        </div>

        {{-- 24-character English Grid --}}
        <div style="margin: 4px 0 6px;">
          <div class="a4-lbl" style="font-size:11px;">৩. প্রার্থীর নাম (ইংরেজিতে বড় হাতের অক্ষরে / In English Block Letters) :</div>
          <div class="char-grid-row">
            @for($i = 0; $i < 24; $i++)
              <span class="char-box">{{ isset($engName[$i]) && $engName[$i] !== ' ' ? $engName[$i] : '' }}</span>
            @endfor
          </div>
        </div>

        <div class="a4-row">
          <span class="a4-lbl">৪. জন্ম তারিখ :</span>
          <span class="a4-val" style="flex:0 0 120px; font-weight:600;">{{ $application->dob ? $application->dob->format('d / m / Y') : '—' }}</span>

          <span class="a4-lbl" style="margin-left:10px;">লিঙ্গ :</span>
          <span style="margin-left:6px;">
            <span style="margin-right:8px;">[{{ $isMale ? '✔' : ' ' }}] পুরুষ (Male)</span>
            <span style="margin-right:8px;">[{{ $isFemale ? '✔' : ' ' }}] নারী (Female)</span>
            <span>[{{ $isOther ? '✔' : ' ' }}] অন্যান্য</span>
          </span>

          <span class="a4-lbl" style="margin-left:14px;">রক্তের গ্রুপ :</span>
          <span class="a4-val" style="flex:0 0 60px; font-weight:700; color:#b91c1c;">{{ $bloodVal ?: '—' }}</span>
        </div>

        <div class="a4-row">
          <span class="a4-lbl">৫. ধর্ম :</span>
          <span class="a4-val" style="flex:0 0 100px;">{{ $application->religion ?: 'ইসলাম' }}</span>

          <span class="a4-lbl" style="margin-left:10px;">জাতীয় পরিচয়পত্র / জন্ম নিবন্ধন নং :</span>
          <span class="a4-val" style="font-family:monospace; font-weight:bold; color:#005953;">{{ $application->ident_no ?: '—' }}</span>
        </div>
      </div>
    </div>

    {{-- SECTION খ: পিতা ও মাতার পরিচিতি --}}
    <div class="a4-section-card">
      <div class="a4-section-head">খ. পিতা ও মাতার পরিচিতি (Parents Information)</div>
      <div class="a4-section-body">
        <div class="a4-grid-2">
          {{-- Father Column --}}
          <div>
            <div class="a4-row">
              <span class="a4-lbl">পিতার নাম (বাংলা) :</span>
              <span class="a4-val">{{ $application->father_name_bn ?: ($application->father_name_en ?: '—') }}</span>
            </div>
            <div style="margin: 2px 0 4px;">
              <span class="a4-lbl" style="font-size:10.5px;">পিতার নাম (ইংরেজিতে) :</span>
              <div class="char-grid-row">
                @for($i = 0; $i < 20; $i++)
                  <span class="char-box">{{ isset($fatherName[$i]) && $fatherName[$i] !== ' ' ? $fatherName[$i] : '' }}</span>
                @endfor
              </div>
            </div>
            <div class="a4-row">
              <span class="a4-lbl">পেশা :</span>
              <span class="a4-val">{{ $application->income_source ?: '—' }}</span>
            </div>
          </div>

          {{-- Mother Column --}}
          <div>
            <div class="a4-row">
              <span class="a4-lbl">মাতার নাম (বাংলা) :</span>
              <span class="a4-val">{{ $application->mother_name_bn ?: ($application->mother_name_en ?: '—') }}</span>
            </div>
            <div style="margin: 2px 0 4px;">
              <span class="a4-lbl" style="font-size:10.5px;">মাতার নাম (ইংরেজিতে) :</span>
              <div class="char-grid-row">
                @for($i = 0; $i < 20; $i++)
                  <span class="char-box">{{ isset($motherName[$i]) && $motherName[$i] !== ' ' ? $motherName[$i] : '' }}</span>
                @endfor
              </div>
            </div>
            <div class="a4-row">
              <span class="a4-lbl">পেশা :</span>
              <span class="a4-val">গৃহিণী (Homemaker)</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- SECTION গ: যোগাযোগ ও ঠিকানা --}}
    <div class="a4-section-card">
      <div class="a4-section-head">গ. যোগাযোগ ও ঠিকানা (Contact & Address Details)</div>
      <div class="a4-section-body">
        <div class="a4-grid-2">
          <div class="a4-row">
            <span class="a4-lbl">প্রার্থীর মোবাইল :</span>
            <span class="a4-val" style="font-family:monospace; font-weight:bold; color:#005953;">{{ $application->contact_no ?: $application->phone }}</span>
          </div>
          <div class="a4-row">
            <span class="a4-lbl">অভিভাবকের মোবাইল :</span>
            <span class="a4-val" style="font-family:monospace;">{{ $application->emergency_contact ?: '—' }}</span>
          </div>
        </div>

        <div class="a4-row">
          <span class="a4-lbl">বর্তমান ঠিকানা :</span>
          <span class="a4-val">{{ $application->pres_address }}, থানা: {{ $application->pres_upazilla }}, জেলা: {{ $application->pres_district }}</span>
        </div>

        <div class="a4-row">
          <span class="a4-lbl">স্থায়ী ঠিকানা :</span>
          <span class="a4-val">{{ $application->perm_address }}, থানা: {{ $application->perm_upazilla }}, জেলা: {{ $application->perm_district }}</span>
        </div>
      </div>
    </div>

    {{-- SECTION ঘ: পূর্ববর্তী শিক্ষাগত যোগ্যতা --}}
    <div class="a4-section-card">
      <div class="a4-section-head">ঘ. পূর্ববর্তী শিক্ষাগত যোগ্যতা (Educational Qualifications)</div>
      <div class="a4-section-body" style="padding:4px 8px;">
        <table class="a4-table">
          <thead>
            <tr>
              <th style="width:22%;">পরীক্ষার নাম</th>
              <th style="width:34%;">শিক্ষা প্রতিষ্ঠান / বোর্ড</th>
              <th style="width:14%;">পাশের সন</th>
              <th style="width:14%;">বিভাগ / বিষয়</th>
              <th style="width:16%;">প্রাপ্ত জিপিএ / বিভাগ</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="font-weight:700;">এসএসসি / সমমান (SSC)</td>
              <td>{{ $application->education_level ?: '—' }}</td>
              <td>—</td>
              <td>—</td>
              <td style="font-weight:700; color:#005953;">—</td>
            </tr>
            <tr>
              <td style="font-weight:700;">এইচএসসি / সমমান (HSC)</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- SECTION ঙ: সংযুক্তি ও ডকুমেন্টস --}}
    <div class="a4-section-card">
      <div class="a4-section-head">ঙ. সংযুক্তি ও অঙ্গীকার (Documents & Signatures)</div>
      <div class="a4-section-body">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:8px;">
          <div>
            <span style="margin-right:12px;">[{{ $application->photo ? '✔' : ' ' }}] পাসপোর্ট সাইজ ছবি</span>
            <span style="margin-right:12px;">[{{ $application->ident_no ? '✔' : ' ' }}] জন্ম নিবন্ধন / NID কপি</span>
            <span style="margin-right:12px;">[{{ $application->education_level ? '✔' : ' ' }}] শিক্ষাগত সনদের কপি</span>
          </div>

          {{-- Uploaded Document View Button --}}
          @if($application->ident_doc)
            <div>
              <a href="{{ $application->identDocUrl() }}" target="_blank" style="display:inline-flex; align-items:center; gap:5px; background:#e6f4f1; border:1px solid #7cb5a9; color:#005953; padding:4px 10px; border-radius:4px; font-weight:700; text-decoration:none; font-size:11px;">
                📄 View Uploaded ID / Certificate Document ↗
              </a>
            </div>
          @endif
        </div>

        <div style="font-size:10.5px; color:#475569; line-height:1.4; border-top:1px dashed #cbd5e1; padding-top:6px; font-style:italic;">
          আমি অঙ্গীকার করছি যে, এই ভর্তি আবেদনপত্রে প্রদত্ত সকল তথ্য ও সনদপত্র আমার জ্ঞানমতে সম্পূর্ণ সত্য ও সঠিক।
        </div>

        {{-- Signature Row --}}
        <div class="a4-sig-row">
          <div class="a4-sig-col">
            <div class="a4-sig-space">
              @if($application->signature_photo)
                <img src="{{ $application->signatureUrl() }}" alt="Applicant Signature">
              @endif
            </div>
            <div class="a4-sig-line">আবেদনকারীর স্বাক্ষর ও তারিখ</div>
          </div>
          <div class="a4-sig-col">
            <div class="a4-sig-space"></div>
            <div class="a4-sig-line">যাচাইকারী কর্মকর্তার স্বাক্ষর</div>
          </div>
          <div class="a4-sig-col">
            <div class="a4-sig-space"></div>
            <div class="a4-sig-line">অধ্যক্ষ / পরিচালকের সীলমোহর</div>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ========================================================================= --}}
  {{-- SECTION 2: RYANS / SUPER SHOP STYLE PAYMENT VOUCHER & APPROVAL MEMO        --}}
  {{-- ========================================================================= --}}
  <div class="voucher-billing-sheet" id="billing-approval-voucher">

    {{-- Voucher Top Banner --}}
    <div class="voucher-top-banner">
      <h2>
        🧾 OFFICIAL MONEY RECEIPT & ADMISSION BILLING VOUCHER
      </h2>
      <div style="font-size:12px; font-weight:600; opacity:0.95;">
        {{ $isApproved ? 'Official Confirmed Voucher' : 'Draft Voucher & Project Assignment' }}
      </div>
    </div>

    <div class="voucher-inner">

      {{-- Voucher Header: Company Info + Boxed Invoice Details (Ryans Style) --}}
      <div class="bill-header-grid">
        <div class="bill-company-info">
          <h3>{{ $settings->get('institution_name', 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল অ্যান্ড কলেজ') }}</h3>
          <p>
            <strong>Mahmudunnabi Chowdhury Technical School and College</strong> (BTEB Code: 70102)<br>
            Chodu Chowdhury Road, Sagarika, Pahartali, Chattogram - 4219, Bangladesh<br>
            Tel: 01880-919275 &nbsp;|&nbsp; Email: info@mctsc.edu.bd &nbsp;|&nbsp; Web: www.mctsc.edu.bd
          </p>
        </div>

        <div class="bill-meta-box">
          <div class="bill-meta-title">MONEY RECEIPT / INVOICE</div>
          <div class="bill-meta-body">
            <span>VOUCHER NO:</span>
            <strong>{{ $application->voucher_no ?: ('VR-' . $application->tracking_no) }}</strong>
            <span>DATE:</span>
            <strong>{{ now()->format('d-M-Y') }}</strong>
            <span>STATUS:</span>
            @if($isPaid || $isFree)
              <strong style="color:#059669;">● PAID / FREE</strong>
            @else
              <strong style="color:#dc2626;">○ UNPAID / DUE</strong>
            @endif
          </div>
        </div>
      </div>

      {{-- Customer / Trainee Strip (Ryans customer row) --}}
      <div class="bill-customer-strip">
        <div><strong>Trainee:</strong> {{ $application->trainee_name_en ?: $application->name }}</div>
        <div><strong>Tracking No:</strong> <span style="font-family:monospace; font-weight:700; color:#005953;">{{ $application->tracking_no }}</span></div>
        <div><strong>Mobile:</strong> {{ $application->contact_no ?: $application->phone }}</div>
        <div><strong>Trade:</strong> {{ $application->course }}</div>
        <div><strong>Shift:</strong> {{ $application->preferred_shift ?: 'Day' }}</div>
        <div><strong>Officer:</strong> {{ auth()->user()->name ?? 'Admission Officer' }}</div>
      </div>

      {{-- Interactive Form for Approval & Billing --}}
      <form method="POST" action="{{ $isApproved ? route('admin.applications.payment', $application) : route('admin.applications.approve', $application) }}" id="billingForm">
        @csrf

        {{-- 1. Project / Scheme Selection POS Chips --}}
        <div class="bill-proj-selector-wrap">
          <div class="bill-proj-selector-title">
            <span>🏷️ Select Project / Billing Scheme (প্রজেক্ট বা ব্যাচ নির্বাচন করুন):</span>
            <small style="color:#64748b; font-weight:normal; font-size:11.5px;">* Selecting a project automatically updates fees & voucher</small>
          </div>

          <div class="proj-chips-grid">
            {{-- Paid Batch (Default) --}}
            <label class="proj-chip-card {{ old('project_name', $application->project_name ?: 'Paid Batch') === 'Paid Batch' ? 'active' : '' }}" id="chip-paid">
              <input type="radio" name="project_name" value="Paid Batch" data-fee="6000" data-desc="Regular Institute Admission" @checked(old('project_name', $application->project_name ?: 'Paid Batch') === 'Paid Batch') onchange="selectBillingProject(this)">
              <div class="proj-chip-name">💼 Paid Batch</div>
              <div class="proj-chip-fee">৳ 6,000.00</div>
              <div class="proj-chip-desc">Standard Paid Batch</div>
            </label>

            {{-- ECHO Project --}}
            <label class="proj-chip-card {{ old('project_name', $application->project_name) === 'ECHO' ? 'active' : '' }}" id="chip-echo">
              <input type="radio" name="project_name" value="ECHO" data-fee="500" data-desc="ECHO Subsidized Project" @checked(old('project_name', $application->project_name) === 'ECHO') onchange="selectBillingProject(this)">
              <div class="proj-chip-name">🤝 ECHO</div>
              <div class="proj-chip-fee">৳ 500.00</div>
              <div class="proj-chip-desc">Subsidized Project Fee</div>
            </label>

            {{-- NHRDF Project --}}
            <label class="proj-chip-card {{ old('project_name', $application->project_name) === 'NHRDF' ? 'active' : '' }}" id="chip-nhrdf">
              <input type="radio" name="project_name" value="NHRDF" data-fee="0" data-desc="Govt Free Scholarship" @checked(old('project_name', $application->project_name) === 'NHRDF') onchange="selectBillingProject(this)">
              <div class="proj-chip-name">🎓 NHRDF</div>
              <div class="proj-chip-fee">৳ 0.00 (Free)</div>
              <div class="proj-chip-desc">100% Free Scholarship</div>
            </label>

            {{-- ASSET Project --}}
            <label class="proj-chip-card {{ old('project_name', $application->project_name) === 'ASSET' ? 'active' : '' }}" id="chip-asset">
              <input type="radio" name="project_name" value="ASSET" data-fee="0" data-desc="Govt Free Scholarship" @checked(old('project_name', $application->project_name) === 'ASSET') onchange="selectBillingProject(this)">
              <div class="proj-chip-name">🌟 ASSET</div>
              <div class="proj-chip-fee">৳ 0.00 (Free)</div>
              <div class="proj-chip-desc">100% Free Scholarship</div>
            </label>

            {{-- Dynamic Projects from Database --}}
            @foreach($projects as $p)
              @if(!in_array(strtoupper(trim($p->title)), ['ECHO', 'NHRDF', 'ASSET', 'PAID BATCH']))
                @php
                  $pFee = (float)($p->admission_fee ?? 0);
                @endphp
                <label class="proj-chip-card {{ old('project_name', $application->project_name) === $p->title ? 'active' : '' }}">
                  <input type="radio" name="project_name" value="{{ $p->title }}" data-fee="{{ $pFee }}" data-desc="{{ $p->subtitle ?: 'Special Project' }}" @checked(old('project_name', $application->project_name) === $p->title) onchange="selectBillingProject(this)">
                  <div class="proj-chip-name">📌 {{ $p->title }}</div>
                  <div class="proj-chip-fee">{{ $pFee > 0 ? ('৳ ' . number_format($pFee, 2)) : '৳ 0.00 (Free)' }}</div>
                  <div class="proj-chip-desc">{{ Str::limit($p->subtitle ?: 'Custom Project', 25) }}</div>
                </label>
              @endif
            @endforeach
          </div>
        </div>

        {{-- 2. Itemized Bill Table (Super Shop / Ryans Style) --}}
        <table class="bill-items-table">
          <thead>
            <tr>
              <th style="width: 45px; text-align: center;">SL.</th>
              <th>ITEM & COURSE PARTICULARS</th>
              <th style="width: 150px;">PROJECT SCHEME</th>
              <th style="width: 160px; text-align: right;">COURSE FEE (৳)</th>
              <th style="width: 150px; text-align: right;">DISCOUNT / WAIVER (৳)</th>
              <th style="width: 160px; text-align: right;">NET PAYABLE (৳)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="text-align: center; font-weight: bold; color: #475569;">1</td>
              <td>
                <div style="font-weight: 700; color: #0f172a; font-size: 13px;">{{ $application->course }}</div>
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                  3-Month Technical Training Certificate · Shift: {{ $application->preferred_shift ?: 'Regular' }}
                </div>
              </td>
              <td>
                <span id="billProjectBadge" style="font-weight: 700; color: #005953; background: #e6f4f1; border: 1px solid #b8dfd7; padding: 3px 8px; border-radius: 4px; display: inline-block; font-size: 11.5px;">
                  {{ $application->project_name ?: 'Paid Batch' }}
                </span>
              </td>
              <td>
                <input type="number" name="course_fee" id="billCourseFee" value="{{ old('course_fee', $application->course_fee ?: 6000) }}" oninput="recalcBill()" class="bill-input-fee" step="any" min="0" required>
              </td>
              <td>
                <input type="number" name="discount" id="billDiscount" value="{{ old('discount', $application->discount ?: 0) }}" oninput="recalcBill()" class="bill-input-discount" step="any" min="0">
              </td>
              <td style="text-align: right;">
                <span id="billNetPayableCell" style="font-weight: 800; color: #005953; font-size: 14px; font-family: monospace;">
                  ৳ {{ number_format($currentPayable, 2) }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>

        {{-- 3. Bill Summary & Amount in Words (Ryans Style) --}}
        <div class="bill-summary-wrap">
          {{-- Left: Amount in Words & Notes --}}
          <div>
            <div class="bill-words-box" id="billWordsBox">
              <strong>Amount in Words:</strong> <span id="billAmountInWords">BDT Six Thousand Only</span>
            </div>

            <div style="font-size:11.5px; color:#64748b; background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px; padding:8px 12px;">
              💡 <em>Free Scholarship Projects (NHRDF, ASSET) will generate official zero-amount vouchers with full fee exemption.</em>
            </div>
          </div>

          {{-- Right: Summary Totals Box --}}
          <div>
            <table class="bill-summary-table">
              <tr>
                <td class="bill-summary-lbl">Subtotal (Course Fee):</td>
                <td class="bill-summary-val" id="summarySubtotal">৳ {{ number_format($currentFee, 2) }}</td>
              </tr>
              <tr>
                <td class="bill-summary-lbl">Less Discount / Waiver:</td>
                <td class="bill-summary-val" style="color:#b91c1c;" id="summaryDiscount">(-) ৳ {{ number_format($currentDiscount, 2) }}</td>
              </tr>
              <tr style="background:#eef7f4;">
                <td class="bill-summary-lbl" style="font-weight:800; color:#005953; font-size:13px;">Grand Total (Payable):</td>
                <td class="bill-summary-val" style="font-weight:800; color:#005953; font-size:15px;" id="summaryGrandTotal">৳ {{ number_format($currentPayable, 2) }}</td>
              </tr>
              <tr>
                <td class="bill-summary-lbl" style="vertical-align:middle;">Paid Amount (জমা):</td>
                <td style="padding:4px 6px;">
                  <input type="number" name="paid_amount" id="billPaidAmount" value="{{ old('paid_amount', $application->paid_amount ?: 0) }}" oninput="recalcBill()" class="bill-input-paid" step="any" min="0" placeholder="0.00">
                </td>
              </tr>
              <tr>
                <td class="bill-summary-lbl">Due Balance (বকেয়া):</td>
                <td class="bill-summary-val" id="summaryDue" style="color: {{ $currentDue > 0 ? '#b91c1c' : '#059669' }};">
                  ৳ {{ number_format($currentDue, 2) }}
                </td>
              </tr>
            </table>
          </div>
        </div>

        {{-- 4. Payment Method & Transaction Details --}}
        <div class="bill-pay-options-card">
          <div>
            <label class="bill-pay-lbl">Payment Method (পদ্ধতি):</label>
            <select name="payment_method" class="bill-pay-ctrl">
              <option value="Cash" @selected(old('payment_method', $application->payment_method) === 'Cash')>Cash (নগদ)</option>
              <option value="bKash" @selected(old('payment_method', $application->payment_method) === 'bKash')>bKash</option>
              <option value="Nagad" @selected(old('payment_method', $application->payment_method) === 'Nagad')>Nagad</option>
              <option value="Rocket" @selected(old('payment_method', $application->payment_method) === 'Rocket')>Rocket</option>
              <option value="Bank" @selected(old('payment_method', $application->payment_method) === 'Bank')>Bank Deposit</option>
            </select>
          </div>

          <div>
            <label class="bill-pay-lbl">TrxID / Money Receipt Serial:</label>
            <input type="text" name="payment_trx_id" value="{{ old('payment_trx_id', $application->payment_trx_id) }}" placeholder="e.g. 9X29A... বা ম্যানুয়াল রসিদ নং" class="bill-pay-ctrl">
          </div>

          <div>
            <label class="bill-pay-lbl">Receipt Date:</label>
            <input type="date" name="payment_date" value="{{ now()->format('Y-m-d') }}" class="bill-pay-ctrl">
          </div>

          <div>
            <label class="bill-pay-lbl">Received By (Officer):</label>
            <input type="text" readonly value="{{ auth()->user()->name ?? 'Accounts Officer' }}" class="bill-pay-ctrl" style="background:#f1f5f9;">
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="bill-action-bar">
          <div>
            @if($isApproved)
              <span style="display:inline-flex; align-items:center; gap:6px; color:#065f46; font-size:13px; font-weight:700;">
                ✔ Approved by {{ $application->approver?->name ?: 'Administrator' }} on {{ $application->approved_at?->format('d M, Y') }}
              </span>
            @else
              <span style="font-size:12.5px; color:#64748b;">
                * Submitting will mark this application as Approved and generate the official money voucher.
              </span>
            @endif
          </div>

          <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <a href="{{ route('admin.applications.print', $application) }}" target="_blank" class="btn-action-print" style="background:#475569;">
              🖨️ Print Admission Form
            </a>

            @if($isApproved || $isPaid)
              <a href="{{ route('admin.applications.voucher', $application) }}" target="_blank" class="btn-action-print" style="background:#047857;">
                🧾 Print Payment Voucher
              </a>
              <a href="{{ route('admin.applications.voucher', [$application, 'duplicate' => 1]) }}" target="_blank" class="btn-action-print" style="background:#b91c1c;">
                🔁 Duplicate Voucher
              </a>
              <button type="submit" class="btn-submit-approve">
                💾 Save Payment Voucher Changes
              </button>
            @else
              <button type="submit" class="btn-submit-approve">
                ✔ Approve Application & Issue Official Voucher
              </button>
            @endif
          </div>
        </div>

      </form>

      {{-- Voucher Terms & Conditions --}}
      <div class="bill-terms-row">
        <strong>Terms & Conditions:</strong><br>
        1. Course fees are non-refundable once admission is officially finalized.<br>
        2. Free Scholarship beneficiaries (NHRDF / ASSET) are subject to 80% mandatory attendance and government project compliance.<br>
        3. This money receipt voucher must be preserved by the trainee for all exam registration and certification purposes.
      </div>

      {{-- Signatures in Voucher --}}
      <div class="bill-sig-row">
        <div class="bill-sig-col">
          <div style="height:35px;"></div>
          <div class="bill-sig-line">শিক্ষার্থী / জমাকারীর স্বাক্ষর</div>
        </div>
        <div class="bill-sig-col">
          <div style="height:35px;"></div>
          <div class="bill-sig-line">হিসাবরক্ষক / ক্যাশিয়ার</div>
        </div>
        <div class="bill-sig-col">
          <div style="height:35px;"></div>
          <div class="bill-sig-line">অনুমোদনকারী কর্মকর্তা ও সীলমোহর</div>
        </div>
      </div>

      {{-- Voucher Auto User Audit Strip --}}
      <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #cbd5e1;padding-top:6px;margin-top:14px;font-size:11px;color:#64748b;">
        <div>
          Received By / নগদ গ্রহণকারী: <strong style="color:#005953;">{{ $application->approver?->name ?: auth()->user()->name }}</strong>
        </div>
        <div>
          Current User / Printed By: <strong>{{ auth()->user()->name }}</strong> ({{ now()->format('d/m/Y h:i A') }})
        </div>
      </div>

    </div>

  </div>

</div>

{{-- Dynamic Real-Time Fee Calculation & Number-to-Words Script --}}
<script>
function selectBillingProject(radio) {
  // Update chip cards styling
  document.querySelectorAll('.proj-chip-card').forEach(c => c.classList.remove('active'));
  const card = radio.closest('.proj-chip-card');
  if (card) card.classList.add('active');

  const pFee = parseFloat(radio.dataset.fee || 0);
  const pName = radio.value;

  // Set fee input
  const feeInput = document.getElementById('billCourseFee');
  if (feeInput) {
    feeInput.value = pFee;
  }

  // Update badge in table
  const badge = document.getElementById('billProjectBadge');
  if (badge) {
    badge.textContent = pName;
  }

  // Free scholarship handling
  if (pFee === 0) {
    const discountInput = document.getElementById('billDiscount');
    const paidInput = document.getElementById('billPaidAmount');
    if (discountInput) discountInput.value = 0;
    if (paidInput) paidInput.value = 0;
  } else if (pFee === 500) {
    // ECHO special
    const paidInput = document.getElementById('billPaidAmount');
    if (paidInput && (!paidInput.value || paidInput.value == 0 || paidInput.value == 6000)) {
      paidInput.value = 500;
    }
  } else if (pFee === 6000) {
    const paidInput = document.getElementById('billPaidAmount');
    if (paidInput && (!paidInput.value || paidInput.value == 0 || paidInput.value == 500)) {
      paidInput.value = 6000;
    }
  }

  recalcBill();
}

function recalcBill() {
  const feeInput = document.getElementById('billCourseFee');
  const discountInput = document.getElementById('billDiscount');
  const paidInput = document.getElementById('billPaidAmount');

  const fee = Math.max(0, parseFloat(feeInput.value || 0));
  const discount = Math.max(0, parseFloat(discountInput.value || 0));
  const payable = Math.max(0, fee - discount);
  const paid = Math.max(0, parseFloat(paidInput.value || 0));
  const due = Math.max(0, payable - paid);

  // Update summary table
  document.getElementById('summarySubtotal').textContent = '৳ ' + fee.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  document.getElementById('summaryDiscount').textContent = '(-) ৳ ' + discount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  document.getElementById('summaryGrandTotal').textContent = '৳ ' + payable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  document.getElementById('billNetPayableCell').textContent = '৳ ' + payable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

  const dueEl = document.getElementById('summaryDue');
  dueEl.textContent = '৳ ' + due.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
  if (due > 0) {
    dueEl.style.color = '#b91c1c';
  } else {
    dueEl.style.color = '#059669';
  }

  // Update words
  updateAmountInWords(payable);
}

function updateAmountInWords(amount) {
  const wordsEl = document.getElementById('billAmountInWords');
  if (!wordsEl) return;

  if (amount === 0) {
    wordsEl.textContent = 'BDT Zero (100% Free Govt Scholarship)';
    return;
  }

  wordsEl.textContent = 'BDT ' + numberToWords(Math.round(amount)) + ' Only';
}

function numberToWords(num) {
  const a = ['','One ','Two ','Three ','Four ','Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '];
  const b = ['', '', 'Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];

  if ((num = num.toString()).length > 9) return 'Overflow';
  let n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
  if (!n) return '';
  let str = '';
  str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
  str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
  str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
  str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
  str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
  return str.trim();
}

document.addEventListener('DOMContentLoaded', function() {
  recalcBill();
});
</script>
@endsection
