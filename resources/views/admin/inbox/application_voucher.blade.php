<!doctype html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>পেমেন্ট ভাউচার · {{ $application->voucher_no ?: $application->tracking_no }}{{ $isDuplicate ? ' (DUPLICATE)' : '' }}</title>
  <style>
    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    body {
      background: #e2e8f0;
      color: #0f172a;
      font-family: 'SolaimanLipi', 'Kalpurush', 'Hind Siliguri', 'Nikosh', 'Arial', sans-serif;
      font-size: 10px;
      line-height: 1.25;
      margin: 0;
      padding: 12px 6px;
    }
    .print-sheet-wrap {
      max-width: 900px;
      margin: 0 auto;
    }

    /* Top Screen Control Toolbar */
    .screen-toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      padding: 10px 18px;
      margin-bottom: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.06);
      flex-wrap: wrap;
      gap: 10px;
    }
    .btn-tool-back {
      color: #005953;
      text-decoration: none;
      font-weight: 700;
      font-size: 12px;
      padding: 6px 12px;
      background: #e6f4f1;
      border: 1px solid #b8dfd7;
      border-radius: 4px;
    }
    .btn-tool-print {
      background: #005953;
      color: #ffffff;
      border: none;
      padding: 7px 22px;
      border-radius: 4px;
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
    }
    .btn-tool-duplicate {
      background: #fff;
      color: #b91c1c;
      border: 1px solid #fca5a5;
      padding: 6px 12px;
      border-radius: 4px;
      font-weight: 700;
      font-size: 12px;
      text-decoration: none;
    }

    /* A4 Paper Sheet Container */
    .a4-print-page {
      background: #ffffff;
      padding: 5mm 6mm;
      box-shadow: 0 4px 14px rgba(0,0,0,0.1);
      position: relative;
    }

    /* Individual Voucher Frame (Fits 2 on 1 A4 portrait page) */
    .voucher-half-card {
      border: 1.5px solid #005953;
      padding: 6px 10px 6px;
      position: relative;
      background: #ffffff;
      overflow: hidden;
    }

    /* Copy Title Flag */
    .copy-flag-badge {
      display: inline-block;
      position: absolute;
      top: 0;
      left: 10px;
      background: #005953;
      color: #ffffff;
      font-size: 8.5px;
      font-weight: 800;
      padding: 1px 8px;
      border-radius: 0 0 4px 4px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      z-index: 5;
    }

    /* Watermark for Duplicate */
    .duplicate-watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-24deg);
      font-size: 68px;
      font-weight: 900;
      color: rgba(185, 28, 28, 0.12);
      border: 5px dashed rgba(185, 28, 28, 0.16);
      padding: 10px 30px;
      border-radius: 12px;
      letter-spacing: 6px;
      pointer-events: none;
      z-index: 2;
      white-space: nowrap;
      text-transform: uppercase;
    }

    /* Middle Perforation / Cut Line */
    .cut-separator-line {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin: 6px 0;
      font-size: 10px;
      font-weight: bold;
      color: #64748b;
      position: relative;
    }
    .cut-separator-line::before,
    .cut-separator-line::after {
      content: '';
      flex: 1;
      border-top: 1.5px dashed #94a3b8;
    }

    /* Header Section */
    .v-header {
      display: grid;
      grid-template-columns: 280px 1fr 220px;
      align-items: center;
      gap: 6px;
      border-bottom: 2px solid #005953;
      padding-bottom: 4px;
      margin-bottom: 4px;
    }
    .v-head-left {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .v-head-left img {
      width: 44px;
      height: 44px;
      object-fit: contain;
    }
    .v-inst-en {
      font-size: 12px;
      font-weight: 800;
      color: #005953;
      line-height: 1.15;
    }
    .v-inst-bn {
      font-size: 13.5px;
      font-weight: 800;
      color: #005953;
      line-height: 1.15;
      margin: 1px 0;
    }
    .v-inst-slogan {
      font-size: 8.5px;
      color: #334155;
      letter-spacing: 0.3px;
    }

    .v-head-center {
      text-align: center;
    }
    .v-head-title-en {
      font-size: 12.5px;
      font-weight: 800;
      color: #005953;
      letter-spacing: 0.5px;
    }
    .v-head-title-bn {
      font-size: 13px;
      font-weight: 800;
      color: #005953;
      margin-top: 1px;
    }
    .v-head-divider {
      width: 140px;
      height: 2px;
      background: #005953;
      margin: 2px auto 0;
    }

    .v-head-right {
      text-align: right;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
    }
    .v-type-box {
      background: #005953;
      color: #ffffff;
      padding: 2px 14px;
      border-radius: 2px;
      text-align: center;
      width: 100%;
    }
    .v-type-en {
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 0.5px;
    }
    .v-type-bn {
      font-size: 10.5px;
      font-weight: 700;
    }
    .v-no-text {
      font-size: 9.5px;
      font-weight: 800;
      color: #0f172a;
      margin-top: 2px;
    }
    .v-barcode-wrap {
      margin-top: 1px;
      text-align: center;
      width: 100%;
    }
    .v-barcode-text {
      font-size: 8px;
      font-family: monospace;
      font-weight: bold;
      color: #0f172a;
      letter-spacing: 1px;
      margin-top: -1px;
    }

    /* Meta Grid Boxes */
    .v-meta-grid {
      display: flex;
      flex-direction: column;
      gap: 2.5px;
      margin-bottom: 4px;
    }
    .v-meta-row {
      display: flex;
      gap: 3px;
    }
    .v-meta-box {
      border: 1px solid #cbd5e1;
      padding: 2.5px 5px;
      background: #ffffff;
      font-size: 9.5px;
      display: flex;
      align-items: baseline;
      gap: 3px;
      min-height: 19px;
    }
    .v-meta-lbl {
      color: #0f172a;
      font-weight: 700;
      white-space: nowrap;
      font-size: 9px;
    }
    .v-meta-val {
      color: #0f172a;
      font-weight: 600;
      flex: 1;
      border-bottom: 1px dotted #94a3b8;
      padding-bottom: 0px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .chk-sq {
      display: inline-block;
      width: 10px;
      height: 10px;
      border: 1px solid #475569;
      text-align: center;
      line-height: 9px;
      font-size: 8.5px;
      font-weight: bold;
      margin-right: 2px;
      background: #ffffff;
      vertical-align: middle;
    }

    /* Items Table */
    .v-table {
      width: 100%;
      border-collapse: collapse;
      margin: 2px 0 3px;
      font-size: 9.5px;
    }
    .v-table th, .v-table td {
      border: 1px solid #7cb5a9;
      padding: 2.5px 5px;
    }
    .v-table th {
      background: #d8eee7;
      color: #005953;
      font-weight: 800;
      text-align: left;
    }
    .v-table-total-row {
      background: #ffffff;
      font-weight: 800;
    }

    /* Amount in Words */
    .v-words-line {
      border: 1px solid #cbd5e1;
      padding: 2.5px 6px;
      margin-bottom: 3.5px;
      font-size: 9.5px;
      display: flex;
      align-items: baseline;
      gap: 4px;
    }

    /* Bottom 3-box Grid */
    .v-bottom-grid {
      display: grid;
      grid-template-columns: 1fr 140px 1.4fr;
      gap: 4px;
      align-items: stretch;
      margin-top: 2px;
    }
    .v-pay-box {
      border: 1px solid #cbd5e1;
      padding: 3px 5px;
      font-size: 9px;
    }
    .v-qr-box {
      border: 1px solid #cbd5e1;
      padding: 2px 4px;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-size: 8px;
      color: #475569;
    }
    .v-qr-box img {
      width: 44px;
      height: 44px;
      display: block;
      margin-bottom: 1px;
    }
    .v-sig-box {
      display: flex;
      justify-content: space-around;
      align-items: flex-end;
      text-align: center;
      padding: 2px 4px 1px;
    }
    .v-sig-col {
      width: 48%;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .v-sig-line {
      width: 90%;
      border-top: 1px solid #334155;
      padding-top: 2px;
      font-size: 8.5px;
      font-weight: 700;
      color: #0f172a;
      line-height: 1.15;
    }

    /* Bottom-most Footer / Tracking */
    .v-footer-strip {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top: 1px solid #cbd5e1;
      padding-top: 2px;
      margin-top: 3px;
      font-size: 8px;
      color: #475569;
    }

    /* Print Optimization */
    @media print {
      .no-print, .no-print * {
        display: none !important;
        height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
      }
      body {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
      }
      .print-sheet-wrap {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
      }
      .a4-print-page {
        box-shadow: none !important;
        padding: 3mm 4mm !important;
      }
      .voucher-half-card {
        page-break-inside: avoid !important;
      }
      @page {
        size: A4 portrait;
        margin: 3mm 4mm 3mm 4mm;
      }
    }
  </style>
</head>
<body>

@php
  $isFree = $application->isFreeProject();
  $projName = strtoupper(trim($application->project_name ?: 'PAID BATCH'));
  $isPaidBatch = str_contains($projName, 'PAID');
  $isNHRDF = str_contains($projName, 'NHRDF');
  $isASSET = str_contains($projName, 'ASSET');
  $isOther = !$isPaidBatch && !$isNHRDF && !$isASSET;

  $payMethod = strtolower($application->payment_method ?: 'cash');
  $isCash = str_contains($payMethod, 'cash');
  $isBank = str_contains($payMethod, 'bank');
  $isMFS = str_contains($payMethod, 'bkash') || str_contains($payMethod, 'nagad') || str_contains($payMethod, 'rocket') || str_contains($payMethod, 'mfs');
  $isOtherPay = !$isCash && !$isBank && !$isMFS;

  $paidVal = $isFree ? 0 : ($application->paid_amount ?: $application->payable_amount);
  $words = amount_in_words((float)$paidVal);

  $voucherNo = $application->voucher_no ?: ('MC-' . date('Y') . '-' . str_pad($application->id, 6, '0', STR_PAD_LEFT));
  $barcodeClean = preg_replace('/[^0-9A-Z]/', '', $voucherNo);
  $barcodeSvg = \App\Services\BarcodeService::svg($barcodeClean, 22);

  $verifiedOfficer = $receivedBy ?: ($application->approver?->name ?: auth()->user()->name);
@endphp

<div class="print-sheet-wrap">

  {{-- SCREEN TOOLBAR --}}
  <div class="no-print screen-toolbar">
    <div style="display:flex;align-items:center;gap:10px;">
      <a href="{{ route('admin.applications.show', $application) }}" class="btn-tool-back">
        ← Back to Application Details
      </a>
      <span style="font-size:12.5px;color:#334155;">
        Trainee: <strong>{{ $application->trainee_name_en ?: $application->name }}</strong> ({{ $application->tracking_no }})
      </span>
      @if($isDuplicate)
        <span style="background:#fee2e2;color:#991b1b;padding:3px 8px;border-radius:3px;font-weight:bold;font-size:11px;">
          ⚠ DUPLICATE MODE ACTIVE
        </span>
      @endif
    </div>

    <div style="display:flex;align-items:center;gap:10px;">
      @if(!$isDuplicate)
        <a href="{{ route('admin.applications.voucher', [$application, 'duplicate' => 1]) }}" class="btn-tool-duplicate">
          🔁 View / Print as Duplicate Copy
        </a>
      @else
        <a href="{{ route('admin.applications.voucher', $application) }}" class="btn-tool-back" style="color:#1e293b;background:#f8fafc;">
          Original Copy Mode
        </a>
      @endif
      <button onclick="window.print()" class="btn-tool-print">
        🖨️ Print 2-Part Voucher (A4)
      </button>
    </div>
  </div>

  {{-- A4 PAGE SHEET CONTAINER --}}
  <div class="a4-print-page">

    {{-- ========================================================================= --}}
    {{-- PART 1: STUDENT COPY (শিক্ষার্থী কপি)                                     --}}
    {{-- ========================================================================= --}}
    <div class="voucher-half-card">
      <div class="copy-flag-badge">STUDENT COPY / শিক্ষার্থী কপি</div>

      @if($isDuplicate)
        <div class="duplicate-watermark">DUPLICATE</div>
      @endif

      {{-- Header --}}
      <div class="v-header">
        <div class="v-head-left">
          <img src="{{ asset('assets/MC Logo.png') }}" alt="MC Logo">
          <div>
            <div class="v-inst-en">MC Technical School & College</div>
            <div class="v-inst-bn">{{ $settings->get('institution_name', 'এম সি টেকনিক্যাল স্কুল অ্যান্ড কলেজ') }}</div>
            <div class="v-inst-slogan">দক্ষতা &nbsp;|&nbsp; কর্মসংস্থান &nbsp;|&nbsp; উজ্জ্বল আগামী</div>
          </div>
        </div>

        <div class="v-head-center">
          <div class="v-head-title-en">OFFICIAL PAYMENT RECEIPT</div>
          <div class="v-head-title-bn">অফিসিয়াল পেমেন্ট রসিদ</div>
          <div class="v-head-divider"></div>
        </div>

        <div class="v-head-right">
          <div class="v-type-box">
            <div class="v-type-en">PAYMENT VOUCHER</div>
            <div class="v-type-bn">পেমেন্ট ভাউচার{{ $isDuplicate ? ' (ডুপ্লিকেট)' : '' }}</div>
          </div>
          <div class="v-no-text">Voucher No.: {{ $voucherNo }}</div>
          <div class="v-barcode-wrap">
            {!! $barcodeSvg !!}
            <div class="v-barcode-text">{{ $barcodeClean }}</div>
          </div>
        </div>
      </div>

      {{-- Meta Grid Rows --}}
      <div class="v-meta-grid">
        {{-- Row 1 --}}
        <div class="v-meta-row">
          <div class="v-meta-box" style="flex: 1.4;">
            <span class="v-meta-lbl">Payment Category / পেমেন্টের ধরন :</span>
            <span style="margin-left:4px;">
              <span class="chk-sq">✔</span> ভর্তি ফি (Admission) &nbsp;&nbsp;
              <span class="chk-sq"></span> প্রশিক্ষণ ফি &nbsp;&nbsp;
              <span class="chk-sq"></span> অন্যান্য
            </span>
          </div>
          <div class="v-meta-box" style="flex: 1;">
            <span class="v-meta-lbl">Payment Date / পেমেন্টের তারিখ :</span>
            <span class="v-meta-val" style="font-family:monospace;font-size:10px;">{{ ($application->approved_at ?: $application->updated_at)->format('d / m / Y') }}</span>
            <span style="font-size:7.5px;color:#64748b;">(DD/MM/YYYY)</span>
          </div>
          <div class="v-meta-box" style="flex: 0.9;">
            <span class="v-meta-lbl">Receipt No. / রসিদ নং :</span>
            <span class="v-meta-val" style="font-family:monospace;font-size:10px;color:#005953;">{{ $application->payment_trx_id ?: $voucherNo }}</span>
          </div>
        </div>

        {{-- Row 2 --}}
        <div class="v-meta-row">
          <div class="v-meta-box" style="flex: 1.3;">
            <span class="v-meta-lbl">Student Name / শিক্ষার্থীর নাম :</span>
            <span class="v-meta-val" style="font-weight:700;">{{ $application->trainee_name_bn ?: $application->name }} ({{ strtoupper($application->trainee_name_en ?: $application->name) }})</span>
          </div>
          <div class="v-meta-box" style="flex: 0.9;">
            <span class="v-meta-lbl">Student ID / শিক্ষার্থী আইডি :</span>
            <span class="v-meta-val" style="font-family:monospace;font-weight:700;color:#005953;">{{ $application->tracking_no }}</span>
          </div>
          <div class="v-meta-box" style="flex: 1.1;">
            <span class="v-meta-lbl">Course / কোর্সের নাম :</span>
            <span class="v-meta-val">{{ $application->course }}</span>
          </div>
          <div class="v-meta-box" style="flex: 0.7;">
            <span class="v-meta-lbl">Batch / ব্যাচ নং :</span>
            <span class="v-meta-val">{{ $application->project_name ?: 'Paid Batch' }} ({{ $application->preferred_shift ?: 'Day' }})</span>
          </div>
        </div>

        {{-- Row 3 --}}
        <div class="v-meta-row">
          <div class="v-meta-box" style="flex: 1.3;">
            <span class="v-meta-lbl">Project / প্রকল্প :</span>
            <span style="margin-left:2px;">
              <span class="chk-sq">{{ $isPaidBatch ? '✔' : '' }}</span> Paid &nbsp;
              <span class="chk-sq">{{ $isNHRDF ? '✔' : '' }}</span> NHRDF &nbsp;
              <span class="chk-sq">{{ $isASSET ? '✔' : '' }}</span> ASSET &nbsp;
              <span class="chk-sq">{{ $isOther ? '✔' : '' }}</span> {{ $isOther ? $application->project_name : 'Other' }}
            </span>
          </div>
          <div class="v-meta-box" style="flex: 0.9;">
            <span class="v-meta-lbl">Guardian / অভিভাবকের নাম :</span>
            <span class="v-meta-val">{{ $application->father_name_bn ?: ($application->father_name_en ?: '—') }}</span>
          </div>
          <div class="v-meta-box" style="flex: 0.9;">
            <span class="v-meta-lbl">Mobile No. / মোবাইল নং :</span>
            <span class="v-meta-val" style="font-family:monospace;">{{ $application->contact_no ?: $application->phone }}</span>
          </div>
          <div class="v-meta-box" style="flex: 0.9;">
            <span class="v-meta-lbl">Period / পেমেন্টের সময়কাল :</span>
            <span class="v-meta-val">3 Months / 360 Hours</span>
          </div>
        </div>
      </div>

      {{-- Items Table --}}
      <table class="v-table">
        <thead>
          <tr>
            <th style="width: 35px; text-align: center;">SL</th>
            <th>Payment Description / পেমেন্টের বিবরণ</th>
            <th style="width: 120px; text-align: right;">Amount (৳) / পরিমাণ (৳)</th>
            <th style="width: 120px; text-align: right;">Paid (৳) / পরিশোধিত (৳)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="text-align: center; font-weight: bold;">01</td>
            <td>
              <strong>ভর্তি ফি ও কোর্স প্রশিক্ষণ ফি (Course Admission & Training Fee)</strong> — {{ $application->course }}
              @if((float)$application->discount > 0)
                <small style="color:#059669; margin-left:6px;">[বিশেষ ছাড় / Waiver: ৳ {{ number_format($application->discount, 0) }}]</small>
              @endif
            </td>
            <td style="text-align: right; font-family: monospace;">{{ number_format($application->course_fee ?: 6000, 2) }}</td>
            <td style="text-align: right; font-family: monospace; font-weight: bold; color: #005953;">
              {{ $isFree ? '0.00 (Free)' : number_format($paidVal, 2) }}
            </td>
          </tr>
          <tr>
            <td style="text-align: center; font-weight: bold;">02</td>
            <td>ব্যবহারিক প্রশিক্ষণ উপকরণ ও আইডি কার্ড (Practical Tools & Trainee ID Card)</td>
            <td style="text-align: right; font-family: monospace;">0.00</td>
            <td style="text-align: right; font-family: monospace;">0.00</td>
          </tr>
          <tr>
            <td style="text-align: center; font-weight: bold;">03</td>
            <td>মূল্যায়ন ও চূড়ান্ত সনদপত্র ফি (Assessment & Certificate Fee)</td>
            <td style="text-align: right; font-family: monospace;">0.00</td>
            <td style="text-align: right; font-family: monospace;">0.00</td>
          </tr>
          <tr class="v-table-total-row">
            <td colspan="3" style="text-align: right; font-weight: 800; color: #005953; padding-right: 10px;">
              TOTAL PAID / সর্বমোট পরিশোধিত :
            </td>
            <td style="text-align: right; font-family: monospace; font-size: 11px; font-weight: 800; color: #005953; background: #e6f4f1;">
              ৳ {{ number_format($paidVal, 2) }}
            </td>
          </tr>
        </tbody>
      </table>

      {{-- Amount in words --}}
      <div class="v-words-line">
        <span class="v-meta-lbl">Amount in words / কথায় (টাকা মাত্র) :</span>
        <span style="font-weight:700; color:#005953; font-style:italic;">
          {{ $words }} Taka only.
        </span>
      </div>

      {{-- Bottom Blocks: Payment Method, QR, Signatures --}}
      <div class="v-bottom-grid">
        {{-- Payment Method Box --}}
        <div class="v-pay-box">
          <div style="font-weight:700; color:#005953; font-size:9px; margin-bottom:2px;">PAYMENT METHOD / পেমেন্টের মাধ্যম</div>
          <div style="margin-bottom:2px;">
            <span class="chk-sq">{{ $isCash ? '✔' : '' }}</span> নগদ &nbsp;
            <span class="chk-sq">{{ $isBank ? '✔' : '' }}</span> ব্যাংক &nbsp;
            <span class="chk-sq">{{ $isMFS ? '✔' : '' }}</span> এমএফএস &nbsp;
            <span class="chk-sq">{{ $isOtherPay ? '✔' : '' }}</span> অন্যান্য
          </div>
          <div><span style="font-weight:600;">TrxID / Ref No.:</span> <span style="font-family:monospace;font-weight:bold;">{{ $application->payment_trx_id ?: 'N/A (Cash)' }}</span></div>
          <div><span style="font-weight:600;">Paid By / পরিশোধকারী:</span> {{ $application->trainee_name_en ?: $application->name }}</div>
        </div>

        {{-- Verification QR Box --}}
        <div class="v-qr-box">
          <div style="font-weight:800; color:#005953; font-size:8px; margin-bottom:1px;">SCAN TO VERIFY</div>
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(url('/?tracking=' . $application->tracking_no)) }}" alt="QR Code">
          <div>Generated by MC Web<br>(For reference only)</div>
        </div>

        {{-- Signatures Box --}}
        <div class="v-sig-box">
          <div class="v-sig-col">
            <div style="height: 22px;"></div>
            <div class="v-sig-line">
              Cashier / Received By<br>
              <span style="font-weight:normal;">নগদ গ্রহণকারী</span><br>
              <strong style="color:#005953;">{{ $verifiedOfficer }}</strong>
            </div>
          </div>
          <div class="v-sig-col">
            <div style="height: 22px;"></div>
            <div class="v-sig-line">
              Authorized Signature & Seal<br>
              <span style="font-weight:normal;">অনুমোদনকারীর স্বাক্ষর ও সিল</span><br>
              <strong style="color:#005953;">{{ $application->approver?->name ?: 'অধ্যক্ষ / পরিচালক' }}</strong>
            </div>
          </div>
        </div>
      </div>

      {{-- Bottom Audit Footer --}}
      <div class="v-footer-strip">
        <div>MC Technical School & College • Official payment record • Please verify all details before payment.</div>
        <div>
          Received By: <strong style="color:#005953;">{{ $verifiedOfficer }}</strong> &nbsp;|&nbsp;
          Printed By: <strong>{{ $printedBy }}</strong> ({{ now()->format('d/m/Y h:i A') }})
          @if($isDuplicate) &nbsp;|&nbsp; <strong style="color:#b91c1c;">[DUPLICATE COPY]</strong> @endif
        </div>
      </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MIDDLE CUTTING LINE (কাটিং লাইন)                                           --}}
    {{-- ========================================================================= --}}
    <div class="cut-separator-line">
      <span>✂ কাটিং লাইন (Cut Here) ✂</span>
    </div>

    {{-- ========================================================================= --}}
    {{-- PART 2: OFFICE COPY (অফিস কপি)                                            --}}
    {{-- ========================================================================= --}}
    <div class="voucher-half-card">
      <div class="copy-flag-badge" style="background:#0f172a;">OFFICE COPY / অফিস কপি</div>

      @if($isDuplicate)
        <div class="duplicate-watermark">DUPLICATE</div>
      @endif

      {{-- Header --}}
      <div class="v-header">
        <div class="v-head-left">
          <img src="{{ asset('assets/MC Logo.png') }}" alt="MC Logo">
          <div>
            <div class="v-inst-en">MC Technical School & College</div>
            <div class="v-inst-bn">{{ $settings->get('institution_name', 'এম সি টেকনিক্যাল স্কুল অ্যান্ড কলেজ') }}</div>
            <div class="v-inst-slogan">দক্ষতা &nbsp;|&nbsp; কর্মসংস্থান &nbsp;|&nbsp; উজ্জ্বল আগামী</div>
          </div>
        </div>

        <div class="v-head-center">
          <div class="v-head-title-en">OFFICIAL PAYMENT RECEIPT</div>
          <div class="v-head-title-bn">অফিসিয়াল পেমেন্ট রসিদ</div>
          <div class="v-head-divider"></div>
        </div>

        <div class="v-head-right">
          <div class="v-type-box" style="background:#0f172a;">
            <div class="v-type-en">PAYMENT VOUCHER</div>
            <div class="v-type-bn">পেমেন্ট ভাউচার (অফিস কপি){{ $isDuplicate ? ' - ডুপ্লিকেট' : '' }}</div>
          </div>
          <div class="v-no-text">Voucher No.: {{ $voucherNo }}</div>
          <div class="v-barcode-wrap">
            {!! $barcodeSvg !!}
            <div class="v-barcode-text">{{ $barcodeClean }}</div>
          </div>
        </div>
      </div>

      {{-- Meta Grid Rows --}}
      <div class="v-meta-grid">
        {{-- Row 1 --}}
        <div class="v-meta-row">
          <div class="v-meta-box" style="flex: 1.4;">
            <span class="v-meta-lbl">Payment Category / পেমেন্টের ধরন :</span>
            <span style="margin-left:4px;">
              <span class="chk-sq">✔</span> ভর্তি ফি (Admission) &nbsp;&nbsp;
              <span class="chk-sq"></span> প্রশিক্ষণ ফি &nbsp;&nbsp;
              <span class="chk-sq"></span> অন্যান্য
            </span>
          </div>
          <div class="v-meta-box" style="flex: 1;">
            <span class="v-meta-lbl">Payment Date / পেমেন্টের তারিখ :</span>
            <span class="v-meta-val" style="font-family:monospace;font-size:10px;">{{ ($application->approved_at ?: $application->updated_at)->format('d / m / Y') }}</span>
            <span style="font-size:7.5px;color:#64748b;">(DD/MM/YYYY)</span>
          </div>
          <div class="v-meta-box" style="flex: 0.9;">
            <span class="v-meta-lbl">Receipt No. / রসিদ নং :</span>
            <span class="v-meta-val" style="font-family:monospace;font-size:10px;color:#005953;">{{ $application->payment_trx_id ?: $voucherNo }}</span>
          </div>
        </div>

        {{-- Row 2 --}}
        <div class="v-meta-row">
          <div class="v-meta-box" style="flex: 1.3;">
            <span class="v-meta-lbl">Student Name / শিক্ষার্থীর নাম :</span>
            <span class="v-meta-val" style="font-weight:700;">{{ $application->trainee_name_bn ?: $application->name }} ({{ strtoupper($application->trainee_name_en ?: $application->name) }})</span>
          </div>
          <div class="v-meta-box" style="flex: 0.9;">
            <span class="v-meta-lbl">Student ID / শিক্ষার্থী আইডি :</span>
            <span class="v-meta-val" style="font-family:monospace;font-weight:700;color:#005953;">{{ $application->tracking_no }}</span>
          </div>
          <div class="v-meta-box" style="flex: 1.1;">
            <span class="v-meta-lbl">Course / কোর্সের নাম :</span>
            <span class="v-meta-val">{{ $application->course }}</span>
          </div>
          <div class="v-meta-box" style="flex: 0.7;">
            <span class="v-meta-lbl">Batch / ব্যাচ নং :</span>
            <span class="v-meta-val">{{ $application->project_name ?: 'Paid Batch' }} ({{ $application->preferred_shift ?: 'Day' }})</span>
          </div>
        </div>

        {{-- Row 3 --}}
        <div class="v-meta-row">
          <div class="v-meta-box" style="flex: 1.3;">
            <span class="v-meta-lbl">Project / প্রকল্প :</span>
            <span style="margin-left:2px;">
              <span class="chk-sq">{{ $isPaidBatch ? '✔' : '' }}</span> Paid &nbsp;
              <span class="chk-sq">{{ $isNHRDF ? '✔' : '' }}</span> NHRDF &nbsp;
              <span class="chk-sq">{{ $isASSET ? '✔' : '' }}</span> ASSET &nbsp;
              <span class="chk-sq">{{ $isOther ? '✔' : '' }}</span> {{ $isOther ? $application->project_name : 'Other' }}
            </span>
          </div>
          <div class="v-meta-box" style="flex: 0.9;">
            <span class="v-meta-lbl">Guardian / অভিভাবকের নাম :</span>
            <span class="v-meta-val">{{ $application->father_name_bn ?: ($application->father_name_en ?: '—') }}</span>
          </div>
          <div class="v-meta-box" style="flex: 0.9;">
            <span class="v-meta-lbl">Mobile No. / মোবাইল নং :</span>
            <span class="v-meta-val" style="font-family:monospace;">{{ $application->contact_no ?: $application->phone }}</span>
          </div>
          <div class="v-meta-box" style="flex: 0.9;">
            <span class="v-meta-lbl">Period / পেমেন্টের সময়কাল :</span>
            <span class="v-meta-val">3 Months / 360 Hours</span>
          </div>
        </div>
      </div>

      {{-- Items Table --}}
      <table class="v-table">
        <thead>
          <tr>
            <th style="width: 35px; text-align: center;">SL</th>
            <th>Payment Description / পেমেন্টের বিবরণ</th>
            <th style="width: 120px; text-align: right;">Amount (৳) / পরিমাণ (৳)</th>
            <th style="width: 120px; text-align: right;">Paid (৳) / পরিশোধিত (৳)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="text-align: center; font-weight: bold;">01</td>
            <td>
              <strong>ভর্তি ফি ও কোর্স প্রশিক্ষণ ফি (Course Admission & Training Fee)</strong> — {{ $application->course }}
              @if((float)$application->discount > 0)
                <small style="color:#059669; margin-left:6px;">[বিশেষ ছাড় / Waiver: ৳ {{ number_format($application->discount, 0) }}]</small>
              @endif
            </td>
            <td style="text-align: right; font-family: monospace;">{{ number_format($application->course_fee ?: 6000, 2) }}</td>
            <td style="text-align: right; font-family: monospace; font-weight: bold; color: #005953;">
              {{ $isFree ? '0.00 (Free)' : number_format($paidVal, 2) }}
            </td>
          </tr>
          <tr>
            <td style="text-align: center; font-weight: bold;">02</td>
            <td>ব্যবহারিক প্রশিক্ষণ উপকরণ ও আইডি কার্ড (Practical Tools & Trainee ID Card)</td>
            <td style="text-align: right; font-family: monospace;">0.00</td>
            <td style="text-align: right; font-family: monospace;">0.00</td>
          </tr>
          <tr>
            <td style="text-align: center; font-weight: bold;">03</td>
            <td>মূল্যায়ন ও চূড়ান্ত সনদপত্র ফি (Assessment & Certificate Fee)</td>
            <td style="text-align: right; font-family: monospace;">0.00</td>
            <td style="text-align: right; font-family: monospace;">0.00</td>
          </tr>
          <tr class="v-table-total-row">
            <td colspan="3" style="text-align: right; font-weight: 800; color: #005953; padding-right: 10px;">
              TOTAL PAID / সর্বমোট পরিশোধিত :
            </td>
            <td style="text-align: right; font-family: monospace; font-size: 11px; font-weight: 800; color: #005953; background: #e6f4f1;">
              ৳ {{ number_format($paidVal, 2) }}
            </td>
          </tr>
        </tbody>
      </table>

      {{-- Amount in words --}}
      <div class="v-words-line">
        <span class="v-meta-lbl">Amount in words / কথায় (টাকা মাত্র) :</span>
        <span style="font-weight:700; color:#005953; font-style:italic;">
          {{ $words }} Taka only.
        </span>
      </div>

      {{-- Bottom Blocks: Payment Method, QR, Signatures --}}
      <div class="v-bottom-grid">
        {{-- Payment Method Box --}}
        <div class="v-pay-box">
          <div style="font-weight:700; color:#005953; font-size:9px; margin-bottom:2px;">PAYMENT METHOD / পেমেন্টের মাধ্যম</div>
          <div style="margin-bottom:2px;">
            <span class="chk-sq">{{ $isCash ? '✔' : '' }}</span> নগদ &nbsp;
            <span class="chk-sq">{{ $isBank ? '✔' : '' }}</span> ব্যাংক &nbsp;
            <span class="chk-sq">{{ $isMFS ? '✔' : '' }}</span> এমএফএস &nbsp;
            <span class="chk-sq">{{ $isOtherPay ? '✔' : '' }}</span> অন্যান্য
          </div>
          <div><span style="font-weight:600;">TrxID / Ref No.:</span> <span style="font-family:monospace;font-weight:bold;">{{ $application->payment_trx_id ?: 'N/A (Cash)' }}</span></div>
          <div><span style="font-weight:600;">Paid By / পরিশোধকারী:</span> {{ $application->trainee_name_en ?: $application->name }}</div>
        </div>

        {{-- Verification QR Box --}}
        <div class="v-qr-box">
          <div style="font-weight:800; color:#005953; font-size:8px; margin-bottom:1px;">SCAN TO VERIFY</div>
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(url('/?tracking=' . $application->tracking_no)) }}" alt="QR Code">
          <div>Generated by MC Web<br>(For reference only)</div>
        </div>

        {{-- Signatures Box --}}
        <div class="v-sig-box">
          <div class="v-sig-col">
            <div style="height: 22px;"></div>
            <div class="v-sig-line">
              Cashier / Received By<br>
              <span style="font-weight:normal;">নগদ গ্রহণকারী</span><br>
              <strong style="color:#005953;">{{ $verifiedOfficer }}</strong>
            </div>
          </div>
          <div class="v-sig-col">
            <div style="height: 22px;"></div>
            <div class="v-sig-line">
              Authorized Signature & Seal<br>
              <span style="font-weight:normal;">অনুমোদনকারীর স্বাক্ষর ও সিল</span><br>
              <strong style="color:#005953;">{{ $application->approver?->name ?: 'অধ্যক্ষ / পরিচালক' }}</strong>
            </div>
          </div>
        </div>
      </div>

      {{-- Bottom Audit Footer --}}
      <div class="v-footer-strip">
        <div>MC Technical School & College • Official payment record • Please verify all details before payment.</div>
        <div>
          Received By: <strong style="color:#005953;">{{ $verifiedOfficer }}</strong> &nbsp;|&nbsp;
          Printed By: <strong>{{ $printedBy }}</strong> ({{ now()->format('d/m/Y h:i A') }})
          @if($isDuplicate) &nbsp;|&nbsp; <strong style="color:#b91c1c;">[DUPLICATE COPY]</strong> @endif
        </div>
      </div>
    </div>

  </div>

</div>

</body>
</html>
