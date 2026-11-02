<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Certificate — {{ $student->student_id }} — {{ $student->student_name ?: $student->full_name }}</title>
  <link rel="stylesheet" href="{{ asset('assets/certificate/fonts/certificate_fonts.css') }}">
  <style>
    @font-face {
      font-family: 'Edwardian Script ITC';
      src: local('Edwardian Script ITC'),
           url('{{ asset('assets/certificate/fonts/ITCEDSCR.TTF') }}') format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    @font-face {
      font-family: 'Edwardian Script ITC';
      src: local('Edwardian Script ITC'),
           url('{{ asset('assets/certificate/fonts/ITCEDSCR.TTF') }}') format('truetype');
      font-weight: bold;
      font-style: normal;
    }

    @font-face {
      font-family: 'Algerian';
      src: local('Algerian'),
           url('{{ asset('assets/certificate/fonts/ALGER.TTF') }}') format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    @font-face {
      font-family: 'Algerian';
      src: local('Algerian'),
           url('{{ asset('assets/certificate/fonts/ALGER.TTF') }}') format('truetype');
      font-weight: bold;
      font-style: normal;
    }

    @font-face {
      font-family: 'Gabriola';
      src: local('Gabriola'),
           url('{{ asset('assets/certificate/fonts/Gabriola.ttf') }}') format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: #475569;
      font-family: 'Times New Roman', Times, serif;
      color: #000000;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 24px 10px;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    /* Screen Toolbar */
    .no-print-toolbar {
      width: 841.8pt;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      padding: 10px 20px;
      margin-bottom: 16px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
      font-family: Arial, sans-serif;
    }

    .toolbar-left, .toolbar-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .tb-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 7px 16px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 6px;
      text-decoration: none;
      cursor: pointer;
      border: 1px solid transparent;
      transition: all 0.15s ease;
    }

    .tb-btn-back {
      background: #f1f5f9;
      color: #334155;
      border-color: #cbd5e1;
    }
    .tb-btn-back:hover {
      background: #e2e8f0;
    }

    .tb-btn-print {
      background: #002060;
      color: #ffffff;
    }
    .tb-btn-print:hover {
      background: #001740;
    }

    .tb-btn-toggle {
      background: #ffffff;
      color: #002060;
      border-color: #002060;
    }
    .tb-btn-toggle:hover {
      background: #eff6ff;
    }

    .toolbar-info {
      font-size: 12.5px;
      color: #475569;
    }

    /* Exact A4 Landscape Certificate Sheet (841.8pt x 595.2pt / 297mm x 210mm) */
    .cert-sheet-wrapper {
      width: 841.8pt;
      height: 595.2pt;
      background: #ffffff;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
      position: relative;
      margin: 0 auto;
      overflow: hidden;
    }

    /* Header Logos */
    .logo-left {
      position: absolute;
      left: 52.9pt;
      top: 45.4pt;
      width: 91.4pt;
      height: 82.1pt;
      object-fit: contain;
      z-index: 2;
    }

    .logo-right {
      position: absolute;
      left: 704.7pt;
      top: 39.6pt;
      width: 88.7pt;
      height: 90.5pt;
      object-fit: contain;
      z-index: 2;
    }

    /* Header Titles */
    .header-inst-name {
      position: absolute;
      left: 145pt;
      right: 145pt;
      top: 48pt;
      text-align: center;
      font-family: 'Times New Roman', Times, serif;
      font-size: 20pt;
      font-weight: bold;
      color: #000000;
      line-height: 1.1;
      z-index: 2;
    }

    .header-inst-sub {
      position: absolute;
      left: 145pt;
      right: 145pt;
      top: 72pt;
      text-align: center;
      font-family: 'Times New Roman', Times, serif;
      font-size: 20pt;
      font-weight: bold;
      color: #000000;
      line-height: 1.1;
      z-index: 2;
    }

    .header-inst-addr {
      position: absolute;
      left: 145pt;
      right: 145pt;
      top: 104pt;
      text-align: center;
      font-family: 'Times New Roman', Times, serif;
      font-size: 12pt;
      font-weight: bold;
      color: #000000;
      line-height: 1.1;
      z-index: 2;
    }

    .header-inst-affil {
      position: absolute;
      left: 145pt;
      right: 145pt;
      top: 122pt;
      text-align: center;
      font-family: Tahoma, Arial, sans-serif;
      font-size: 11pt;
      font-weight: bold;
      color: #000000;
      line-height: 1.1;
      z-index: 2;
    }

    /* CERTIFICATE Title */
    .cert-title-algerian {
      position: absolute;
      left: 0;
      right: 0;
      top: 168pt;
      text-align: center;
      font-family: 'Algerian', 'Cinzel', serif;
      font-size: 36pt;
      font-weight: normal;
      color: #000000;
      letter-spacing: 2.5px;
      line-height: 1;
      z-index: 2;
    }

    /* ID No */
    .cert-id-block {
      position: absolute;
      left: 66.3pt;
      top: 226pt;
      font-family: Tahoma, Arial, sans-serif;
      font-size: 12pt;
      color: #000000;
      display: flex;
      align-items: baseline;
      z-index: 2;
    }

    .cert-id-number {
      font-family: Calibri, Arial, sans-serif;
      font-size: 16pt;
      font-weight: bold;
      color: #000000;
      margin-left: 28pt;
    }

    /* Static Labels */
    .lbl-gabriola {
      font-family: 'Gabriola', Georgia, serif;
      font-size: 18pt;
      color: #000000;
      line-height: 1;
      z-index: 2;
    }

    /* Dynamic Calligraphic Values in Edwardian Script ITC (Bolded) */
    .val-script {
      font-family: 'Edwardian Script ITC', 'Brush Script MT', cursive;
      font-size: 26pt;
      font-weight: bold;
      -webkit-text-stroke: 0.65px #000000;
      color: #000000;
      line-height: 1;
      text-align: center;
      white-space: nowrap;
      z-index: 2;
    }

    /* Dashed Underlines */
    .dash-line {
      position: absolute;
      border-top: 1.2pt dashed #000000;
      z-index: 2;
    }

    /* Signatures Section */
    .sig-label-checked {
      position: absolute;
      left: 66pt;
      top: 531pt;
      width: 91.2pt;
      text-align: center;
      font-family: Arial, sans-serif;
      font-size: 12pt;
      font-weight: bold;
      color: #000000;
      z-index: 2;
    }

    .sig-label-po {
      position: absolute;
      left: 288.4pt;
      top: 531pt;
      width: 126.4pt;
      text-align: center;
      font-family: Arial, sans-serif;
      font-size: 12pt;
      font-weight: bold;
      color: #000000;
      z-index: 2;
    }

    .sig-vp-img {
      position: absolute;
      left: 590pt;
      top: 462pt;
      width: 155pt;
      height: 60pt;
      object-fit: contain;
      z-index: 2;
    }

    .sig-label-vp-name {
      position: absolute;
      left: 556.4pt;
      top: 528pt;
      width: 221.6pt;
      text-align: center;
      font-family: Arial, sans-serif;
      font-size: 12pt;
      font-weight: bold;
      color: #000000;
      z-index: 2;
    }

    .sig-label-vp-sub {
      position: absolute;
      left: 556.4pt;
      top: 543pt;
      width: 221.6pt;
      text-align: center;
      font-family: Arial, sans-serif;
      font-size: 11pt;
      font-weight: bold;
      color: #000000;
      z-index: 2;
    }

    /* Print Overrides */
    @media print {
      html, body {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
        min-height: auto !important;
      }

      .no-print-toolbar {
        display: none !important;
      }

      .cert-sheet-wrapper {
        box-shadow: none !important;
        margin: 0 !important;
        width: 841.8pt !important;
        height: 595.2pt !important;
        page-break-after: avoid !important;
        page-break-inside: avoid !important;
      }

      @page {
        size: A4 landscape;
        margin: 0;
      }
    }
  </style>
</head>
<body>

  <!-- Screen Toolbar (Hidden on Print) -->
  <div class="no-print-toolbar">
    <div class="toolbar-left">
      <a href="{{ route('admin.students.certificates') }}" class="tb-btn tb-btn-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
        <span>Back to Certificates</span>
      </a>

      @if($withSign)
        <a href="{{ route('admin.students.certificates.print', ['student' => $student->id, 'with_sign' => 0]) }}" class="tb-btn tb-btn-toggle" title="Switch to blank signature line for manual signing">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          <span>Print Without Sign</span>
        </a>
      @else
        <a href="{{ route('admin.students.certificates.print', ['student' => $student->id, 'with_sign' => 1]) }}" class="tb-btn tb-btn-toggle" title="Switch to digital signature">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
          <span>Print With Signature</span>
        </a>
      @endif

      <div class="toolbar-info">
        Mode: <strong>{{ $withSign ? 'With Signature' : 'Without Signature' }}</strong>
      </div>
    </div>

    <div class="toolbar-right">
      <button type="button" onclick="window.print()" class="tb-btn tb-btn-print">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
        <span>Print Certificate (Exact A4 Landscape)</span>
      </button>
    </div>
  </div>

  <!-- Exact Certificate Sheet -->
  <div class="cert-sheet-wrapper">
    <!-- Authentic Vector Double Border with Exact Rounded Corners extracted directly from PDF -->
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 841.8 595.2" style="position: absolute; left: 0; top: 0; width: 841.8pt; height: 595.2pt; pointer-events: none; z-index: 1;">
      <path d="M 26.21 24.21 C 26.21 19.80, 29.80 16.21, 34.21 16.21 L 807.92 16.21 C 812.34 16.21, 815.92 19.80, 815.92 24.21 L 815.92 568.40 C 815.92 572.82, 812.34 576.40, 807.92 576.40 L 34.21 576.40 C 29.80 576.40, 26.21 572.82, 26.21 568.40 Z M 29.41 568.40 C 29.41 571.05, 31.56 573.20, 34.21 573.20 L 807.92 573.20 C 810.57 573.20, 812.72 571.05, 812.72 568.40 L 812.72 24.21 C 812.72 21.56, 810.57 19.41, 807.92 19.41 L 34.21 19.41 C 31.56 19.41, 29.41 21.56, 29.41 24.21 Z M 32.61 24.21 C 32.61 23.33, 33.33 22.61, 34.21 22.61 L 807.92 22.61 C 808.80 22.61, 809.52 23.33, 809.52 24.21 L 809.52 568.40 C 809.52 569.28, 808.80 570.00, 807.92 570.00 L 34.21 570.00 C 33.33 570.00, 32.61 569.28, 32.61 568.40 Z M 42.21 560.40 L 799.92 560.40 L 799.92 32.21 L 42.21 32.21 Z" fill="#002060" fill-rule="evenodd" />
    </svg>

    <!-- Header Logos extracted directly from PDF -->
    <img src="{{ asset('assets/certificate/mc_gear_pdf.jpg') }}" alt="MC Technical Crest" class="logo-left">
    <img src="{{ asset('assets/certificate/impact_initiative_pdf.jpg') }}" alt="Impact Initiative Logo" class="logo-right">

    <!-- Header Text Block -->
    <div class="header-inst-name">Mahmudunnabi Chowdhury Technical School & College</div>
    <div class="header-inst-sub">Run by Impact Initiative (IMI)</div>
    <div class="header-inst-addr">Chodu Chowdhury Road, Sagorika, Pahartali, Chattogram</div>
    <div class="header-inst-affil">Affiliated by Bangladesh Technical Education Board (BTEB). Institute Code:70102</div>

    <!-- Certificate Title in Algerian -->
    <div class="cert-title-algerian">CERTIFICATE</div>

    <!-- ID No -->
    <div class="cert-id-block">
      <span>ID No:</span>
      <span class="cert-id-number">{{ $student->student_id }}</span>
    </div>

    <!-- Line 1: Student Name & Father's Name -->
    <span class="lbl-gabriola" style="position: absolute; left: 66.3pt; top: 264pt;">This is to Certify that</span>
    <div class="dash-line" style="left: 194.8pt; top: 285.0pt; width: 228.0pt;"></div>
    <div class="val-script" style="position: absolute; left: 195pt; top: 247pt; width: 228pt;">
      {{ $student->student_name ?: $student->full_name }}
    </div>

    <span class="lbl-gabriola" style="position: absolute; left: 430.6pt; top: 264pt;">Son/Daughter of,</span>
    <div class="dash-line" style="left: 541.7pt; top: 285.0pt; width: 242.7pt;"></div>
    <div class="val-script" style="position: absolute; left: 542pt; top: 247pt; width: 242pt;">
      {{ $student->father_name ?: '—' }}
    </div>

    <!-- Line 2: Duration & Occupation -->
    <span class="lbl-gabriola" style="position: absolute; left: 66.3pt; top: 304pt;">has successfully completed training course of</span>
    <div class="dash-line" style="left: 316.4pt; top: 323.1pt; width: 30.3pt;"></div>
    <div class="val-script" style="position: absolute; left: 316pt; top: 287pt; width: 31pt;">
      {{ str_pad($student->course_duration_months ?: 3, 2, '0', STR_PAD_LEFT) }}
    </div>

    <span class="lbl-gabriola" style="position: absolute; left: 355.6pt; top: 304pt;">months duration on</span>
    <div class="dash-line" style="left: 476.5pt; top: 323.1pt; width: 307.9pt;"></div>
    <div class="val-script" style="position: absolute; left: 476pt; top: 287pt; width: 308pt;">
      {{ $student->occupation ?: $student->occupation_name }}
    </div>

    <!-- Line 3: Dates & Grade -->
    <span class="lbl-gabriola" style="position: absolute; left: 66.3pt; top: 348pt;">from</span>
    <div class="dash-line" style="left: 103.6pt; top: 367.6pt; width: 139.0pt;"></div>
    <div class="val-script" style="position: absolute; left: 104pt; top: 331pt; width: 139pt; font-size: 22pt;">
      {{ $sessionDates['start'] }}
    </div>

    <span class="lbl-gabriola" style="position: absolute; left: 251pt; top: 348pt;">to</span>
    <div class="dash-line" style="left: 276.1pt; top: 367.6pt; width: 139.3pt;"></div>
    <div class="val-script" style="position: absolute; left: 276pt; top: 331pt; width: 139pt; font-size: 22pt;">
      {{ $sessionDates['end'] }}
    </div>

    <span class="lbl-gabriola" style="position: absolute; left: 422pt; top: 348pt;">with grade/performance</span>
    <div class="dash-line" style="left: 576.4pt; top: 367.6pt; width: 76.2pt;"></div>
    <div class="val-script" style="position: absolute; left: 576pt; top: 331pt; width: 76pt; font-size: 22pt;">
      {{ $student->grade ?: 'A+' }}
    </div>

    <!-- Line 4: Conducted by -->
    <div class="lbl-gabriola" style="position: absolute; left: 66.3pt; top: 393pt;">
      conducted by <strong>Mahmudunnabi Chowdhury Technical School & College</strong>
    </div>

    <!-- Line 5: Date of Birth -->
    <span class="lbl-gabriola" style="position: absolute; left: 66.3pt; top: 435pt;">His/ Her date of birth is</span>
    <div class="dash-line" style="left: 222.8pt; top: 456.1pt; width: 239.9pt;"></div>
    <div class="val-script" style="position: absolute; left: 223pt; top: 419pt; width: 240pt; font-size: 22pt;">
      @php
        $dobFormatted = $student->dob ? $student->dob->format('d F Y') : ($student->age ? $student->age . ' Years' : '—');
      @endphp
      {{ $dobFormatted }}
    </div>

    <!-- Signatures Section -->
    <!-- Column 1: Checked By -->
    <div class="dash-line" style="left: 66.0pt; top: 523.6pt; width: 91.2pt;"></div>
    <div class="sig-label-checked">Checked By</div>

    <!-- Column 2: Project Officer-TVET -->
    <div class="dash-line" style="left: 288.4pt; top: 523.6pt; width: 126.4pt;"></div>
    <div class="sig-label-po">Project Officer-TVET</div>

    <!-- Column 3: Vice President (with signature) -->
    @if($withSign)
      <img src="{{ asset('assets/certificate/signature_vp.png') }}" alt="VP Signature" class="sig-vp-img">
    @endif
    <div class="dash-line" style="left: 556.4pt; top: 523.6pt; width: 221.6pt;"></div>
    <div class="sig-label-vp-name">Md. Shaif Ullah Mansur</div>
    <div class="sig-label-vp-sub">Vice President, Management Committee</div>

  </div>

</body>
</html>