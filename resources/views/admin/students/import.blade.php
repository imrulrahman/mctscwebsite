@extends('admin.layout')

@section('title', 'Excel Import · Student Management')
@section('crumb', 'Student Management / Import Excel')

@section('content')
<div class="sm-import-container">
  <div class="sm-import-header">
    <div>
      <h1 class="sm-import-title">Import Student Database from Excel</h1>
      <p class="sm-import-subtitle">Upload institution Excel (.xlsx, .xlsm) or CSV file with automatic schema mapping, duplicate identification and historical ID preservation.</p>
    </div>
    <div>
      <a href="{{ route('admin.students.database') }}" class="sm-btn sm-btn-secondary">
        &larr; Back to Database
      </a>
    </div>
  </div>

  {{-- If preview analysis is available --}}
  @if(isset($analysis))
    <div class="sm-preview-card">
      <div class="sm-preview-summary-bar">
        <div class="sm-prev-stat">
          <span>Total Records in File</span>
          <b>{{ number_format($analysis['total_rows']) }}</b>
        </div>
        <div class="sm-prev-stat {{ $analysis['duplicate_count'] > 0 ? 'text-amber' : '' }}">
          <span>Existing / Duplicates</span>
          <b>{{ number_format($analysis['duplicate_count']) }}</b>
        </div>
        <div class="sm-prev-stat {{ $analysis['error_count'] > 0 ? 'text-red' : '' }}">
          <span>Rows with Errors</span>
          <b>{{ number_format($analysis['error_count']) }}</b>
        </div>
        <div class="sm-prev-stat text-green">
          <span>Ready to Import</span>
          <b>{{ number_format(max(0, $analysis['total_rows'] - $analysis['duplicate_count'] - $analysis['error_count'])) }}</b>
        </div>
      </div>

      @if($analysis['duplicate_count'] > 0)
        <div class="alert warning" style="background:#fef3c7;border:1px solid #fde68a;color:#92400e;padding:12px;border-radius:6px;margin:15px 0;">
          <strong>Notice:</strong> {{ $analysis['duplicate_count'] }} Student IDs already exist in the database and will be safely skipped to preserve historical integrity.
          <br><small>Sample duplicate IDs: {{ implode(', ', $analysis['duplicate_samples']) }}</small>
        </div>
      @endif

      @if(!empty($analysis['preview_rows']))
        <h4 style="font-size:14px;color:#1f2937;margin:20px 0 10px;">First 10 Records Preview:</h4>
        <div class="sdb-table-responsive" style="border:1px solid #e2e8f0;border-radius:8px;overflow-x:auto;">
          <table class="sdb-table" style="font-size:12px;">
            <thead>
              <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Occupation</th>
                <th>Academic Year</th>
                <th>Session</th>
                <th>Batch</th>
                <th>Phone</th>
              </tr>
            </thead>
            <tbody>
              @foreach(array_slice($analysis['preview_rows'], 0, 10) as $row)
                <tr>
                  <td><b>{{ $row['student_id'] }}</b></td>
                  <td>{{ $row['student_name'] }}</td>
                  <td>{{ $row['gender'] }}</td>
                  <td>{{ $row['occupation'] }}</td>
                  <td>{{ $row['academic_year'] }}</td>
                  <td>{{ $row['session'] }}</td>
                  <td>{{ $row['batch_no'] ?: '—' }}</td>
                  <td>{{ $row['phone'] ?: '—' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.students.import.process') }}" style="margin-top:20px;display:flex;gap:12px;">
        @csrf
        <input type="hidden" name="temp_path" value="{{ $tempPath }}">
        <input type="hidden" name="original_name" value="{{ $originalName }}">
        <button type="submit" class="sm-btn sm-btn-primary sm-btn-lg">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span>Confirm & Import Records Now</span>
        </button>
        <a href="{{ route('admin.students.import') }}" class="sm-btn sm-btn-secondary">Cancel</a>
      </form>
    </div>
  @else
    {{-- Upload Form --}}
    <div class="sm-upload-card">
      <form method="POST" action="{{ route('admin.students.import.preview') }}" enctype="multipart/form-data">
        @csrf
        <div class="sm-drop-area" onclick="document.getElementById('excelFileInput').click()">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="1.8" style="margin-bottom:10px;">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="17 8 12 3 7 8"></polyline>
            <line x1="12" y1="3" x2="12" y2="15"></line>
          </svg>
          <h3 style="font-size:16px;font-weight:700;color:#1f2937;margin:0 0 4px;">Choose Excel or CSV Workbook</h3>
          <p style="font-size:13px;color:#64748b;margin:0 0 14px;">Select the official institute StudentDB workbook (.xlsx, .xlsm, .csv)</p>
          <input type="file" id="excelFileInput" name="excel_file" accept=".xlsx,.xlsm,.xls,.csv,.txt" required style="display:none;" onchange="showSelectedFileName(this)">
          <span id="selectedFileNameBadge" class="sm-btn sm-btn-primary">Browse File</span>
        </div>

        <div style="margin-top:20px;display:flex;justify-content:flex-end;">
          <button type="submit" class="sm-btn sm-btn-primary">
            Upload & Preview Records &rarr;
          </button>
        </div>
      </form>
    </div>
  @endif

  {{-- Import History --}}
  <div class="sm-history-card" style="margin-top:35px;">
    <h3 style="font-size:16px;font-weight:700;color:#1f2937;margin:0 0 14px;">Recent Import History</h3>
    <div class="sdb-table-responsive" style="border:1px solid #e2e8f0;border-radius:8px;">
      <table class="sdb-table">
        <thead>
          <tr>
            <th>Date & Time</th>
            <th>Source File</th>
            <th>Imported By</th>
            <th>Total Rows</th>
            <th>Success</th>
            <th>Duplicates</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($importBatches as $b)
            <tr>
              <td>{{ $b->created_at->format('d M Y, h:i A') }}</td>
              <td><b>{{ $b->file_name }}</b></td>
              <td>{{ $b->user?->name ?? 'System' }}</td>
              <td>{{ number_format($b->total_rows) }}</td>
              <td><span style="color:#047857;font-weight:700;">{{ number_format($b->successful_rows) }}</span></td>
              <td><span style="color:#b45309;">{{ number_format($b->duplicate_rows) }}</span></td>
              <td>
                @if($b->status === 'completed')
                  <span class="sm-badge badge-completed">Completed</span>
                @else
                  <span class="sm-badge badge-active">{{ ucfirst($b->status) }}</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align:center;padding:20px;color:#94a3b8;">No import history recorded yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function showSelectedFileName(input) {
  if (input.files && input.files[0]) {
    var badge = document.getElementById('selectedFileNameBadge');
    if (badge) {
      badge.textContent = 'Selected: ' + input.files[0].name;
    }
  }
}
</script>

<style>
.sm-import-container {
  max-width: 1100px;
  margin: 0 auto 50px;
}

.sm-import-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.sm-import-title {
  font-size: 26px;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 4px;
}

.sm-import-subtitle {
  font-size: 13.5px;
  color: #64748b;
  margin: 0;
}

.sm-upload-card, .sm-preview-card, .sm-history-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.sm-drop-area {
  border: 2px dashed #a7f3d0;
  background: #f0fdf4;
  border-radius: 10px;
  padding: 40px 20px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.sm-drop-area:hover {
  border-color: #047857;
  background: #ecfdf5;
}

.sm-preview-summary-bar {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  padding-bottom: 16px;
  border-bottom: 1px solid #e2e8f0;
}

.sm-prev-stat {
  display: flex;
  flex-direction: column;
}

.sm-prev-stat span {
  font-size: 12px;
  color: #64748b;
  font-weight: 600;
}

.sm-prev-stat b {
  font-size: 24px;
  color: #1f2937;
  margin-top: 2px;
}

.text-amber { color: #b45309 !important; }
.text-red { color: #dc2626 !important; }
.text-green { color: #047857 !important; }

.sm-badge {
  display: inline-block;
  padding: 3px 9px;
  border-radius: 14px;
  font-size: 11.5px;
  font-weight: 600;
}

.badge-completed { background: #d1fae5; color: #047857; }
.badge-active { background: #ecfdf5; color: #065f46; }
</style>
@endsection
