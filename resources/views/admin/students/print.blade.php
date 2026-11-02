<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Batch Roster · MC Technical School & College</title>
  <style>
    @page {
      size: A4 landscape;
      margin: 12mm 15mm;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 11px;
      color: #1f2937;
      background: #ffffff;
      padding: 15px;
    }

    .roster-header {
      text-align: center;
      margin-bottom: 12px;
    }

    .roster-logo {
      width: 48px;
      height: 48px;
      object-fit: contain;
      margin: 0 auto 4px;
      display: block;
    }

    .roster-institution-name {
      font-size: 20px;
      font-weight: 800;
      color: #064e3b;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      margin-bottom: 2px;
    }

    .roster-sheet-title {
      font-size: 13px;
      font-weight: 700;
      color: #1f2937;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: 8px;
    }

    .roster-divider {
      height: 2px;
      background: #064e3b;
      margin: 0 0 10px;
      border: none;
    }

    .roster-meta-bar {
      font-size: 11px;
      font-weight: 600;
      color: #334155;
      margin-bottom: 8px;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px;
      padding: 0 4px;
    }

    .roster-meta-item b {
      color: #0f172a;
    }

    .roster-meta-sep {
      color: #94a3b8;
      margin: 0 6px;
    }

    .roster-summary-box {
      background: #ecfdf5;
      border: 1px solid #a7f3d0;
      border-radius: 4px;
      padding: 6px 14px;
      margin-bottom: 12px;
      display: flex;
      justify-content: space-around;
      font-size: 11px;
      font-weight: 600;
      color: #065f46;
    }

    .roster-summary-box span b {
      color: #064e3b;
      font-weight: 700;
    }

    .roster-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .roster-table th {
      background: #f1f5f9;
      color: #064e3b;
      border: 1px solid #cbd5e1;
      padding: 6px 8px;
      font-size: 10.5px;
      font-weight: 700;
      text-align: left;
      white-space: nowrap;
    }

    .roster-table td {
      border: 1px solid #cbd5e1;
      padding: 5px 8px;
      font-size: 10.5px;
      vertical-align: middle;
    }

    .roster-table tbody tr:nth-child(even) {
      background: #f8fafc;
    }

    .roster-id-cell {
      font-family: ui-monospace, SFMono-Regular, Consolas, monospace;
      font-weight: 700;
      color: #064e3b;
    }

    .roster-footer {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-top: 25px;
      padding-top: 10px;
      font-size: 10.5px;
      color: #475569;
    }

    .roster-signature {
      font-weight: 600;
    }

    .roster-signature-line {
      display: inline-block;
      width: 160px;
      border-bottom: 1px solid #334155;
      margin-left: 6px;
    }

    .no-print-bar {
      background: #064e3b;
      color: #ffffff;
      padding: 10px 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      border-radius: 6px;
    }

    .no-print-btn {
      background: #10b981;
      color: #ffffff;
      border: none;
      padding: 6px 14px;
      border-radius: 4px;
      font-weight: 700;
      font-size: 12px;
      cursor: pointer;
    }

    @media print {
      body {
        padding: 0;
      }
      .no-print-bar {
        display: none !important;
      }
      .roster-summary-box {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .roster-table th {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
  </style>
</head>
<body>

  {{-- Top Print Bar (Hidden on print) --}}
  <div class="no-print-bar">
    <div>
      <strong>MC Technical School & College</strong> · Batch Roster Print Preview ({{ $students->count() }} Trainees)
    </div>
    <div style="display:flex;gap:10px;">
      <button class="no-print-btn" onclick="window.print()">Print Now (Ctrl + P)</button>
      <button class="no-print-btn" style="background:#475569;" onclick="window.close()">Close Window</button>
    </div>
  </div>

  <div class="roster-header">
    <img src="{{ asset('assets/MC Logo.png') }}" alt="Institution Crest" class="roster-logo" onerror="this.style.display='none'">
    <h1 class="roster-institution-name">MC Technical School & College</h1>
    <h2 class="roster-sheet-title">STUDENT DATABASE — BATCH ROSTER</h2>
    <hr class="roster-divider">
  </div>

  <div class="roster-meta-bar">
    <div>
      <span class="roster-meta-item"><b>Program:</b> {{ $program ?: 'All Programs' }}</span>
      <span class="roster-meta-sep">|</span>
      <span class="roster-meta-item"><b>Batch:</b> {{ $batch ?: 'All Batches' }}</span>
      <span class="roster-meta-sep">|</span>
      <span class="roster-meta-item"><b>Project:</b> {{ $project ?: 'All Projects' }}</span>
    </div>
    <div>
      <span class="roster-meta-item"><b>Academic Year:</b> {{ $academicYear ?: 'All' }}</span>
      <span class="roster-meta-sep">|</span>
      <span class="roster-meta-item"><b>Printed:</b> {{ date('d M Y') }}</span>
    </div>
  </div>

  <div class="roster-summary-box">
    <span>Total Students: <b>{{ $summary['total'] }}</b></span>
    <span>|</span>
    <span>Male: <b>{{ $summary['male'] }}</b></span>
    <span>|</span>
    <span>Female: <b>{{ $summary['female'] }}</b></span>
    <span>|</span>
    <span>Ethnic: <b>{{ $summary['ethnic'] }}</b></span>
    <span>|</span>
    <span>PWD: <b>{{ $summary['pwd'] }}</b></span>
  </div>

  <table class="roster-table">
    <thead>
      <tr>
        <th style="width: 32px; text-align: center;">No.</th>
        <th style="width: 90px;">Student ID</th>
        <th>Student Name</th>
        <th>Technology / Occupation</th>
        <th style="width: 65px;">Batch</th>
        <th style="width: 55px;">Gender</th>
        <th style="width: 100px;">Phone</th>
        <th style="width: 85px;">Special Category</th>
        <th style="width: 60px;">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($students as $idx => $s)
        <tr>
          <td style="text-align: center;">{{ $idx + 1 }}</td>
          <td class="roster-id-cell">{{ $s->student_id }}</td>
          <td><b>{{ $s->student_name }}</b></td>
          <td>{{ $s->occupation }}</td>
          <td>{{ $s->batch_no ?: '—' }}</td>
          <td>{{ $s->gender ?: '—' }}</td>
          <td>{{ $s->phone ?: '—' }}</td>
          <td>
            @if(strtolower($s->ethnic) === 'yes')
              Ethnic
            @elseif(strtolower($s->pwd) === 'yes')
              PWD
            @else
              —
            @endif
          </td>
          <td>{{ $s->training_status ?: 'Active' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="9" style="text-align:center;padding:20px;color:#64748b;">
            No student records matching criteria.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="roster-footer">
    <div>Prepared by: <b>Student Management Office</b></div>
    <div>Page 1 of 1</div>
    <div class="roster-signature">
      Authorized Signature:<span class="roster-signature-line"></span>
    </div>
  </div>

</body>
</html>
