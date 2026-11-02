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
      <a href="{{ route('admin.students.database', [], false) }}" class="sm-btn sm-btn-secondary">
        &larr; Back to Database
      </a>
    </div>
  </div>

  {{-- Notification Alerts --}}
  @if(session('error'))
    <div style="background:#fef2f2;border:1px solid #f87171;color:#991b1b;padding:14px 18px;border-radius:8px;margin-bottom:20px;font-size:14px;display:flex;align-items:flex-start;gap:12px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" style="flex-shrink:0;margin-top:2px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
      <div>
        <strong style="display:block;font-size:14px;margin-bottom:3px;">Import Notice:</strong>
        <span>{{ session('error') }}</span>
      </div>
    </div>
  @endif

  @if(session('success'))
    <div style="background:#ecfdf5;border:1px solid #34d399;color:#065f46;padding:14px 18px;border-radius:8px;margin-bottom:20px;font-size:14px;display:flex;align-items:flex-start;gap:12px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" style="flex-shrink:0;margin-top:2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
      <div>
        <strong style="display:block;font-size:14px;margin-bottom:3px;">Success:</strong>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  @if(isset($errors) && $errors->any())
    <div style="background:#fef2f2;border:1px solid #f87171;color:#991b1b;padding:14px 18px;border-radius:8px;margin-bottom:20px;font-size:14px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
      <strong style="display:block;font-size:14px;margin-bottom:6px;">Upload Validation Failed:</strong>
      <ul style="margin:0;padding-left:20px;line-height:1.6;">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- If preview analysis is available --}}
  @if(isset($analysis))
    <div class="sm-preview-card">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h3 style="font-size:18px;font-weight:700;color:#1f2937;margin:0;">
          File Preview: <span style="color:#047857;">{{ $originalName }}</span>
        </h3>
        <span class="sm-badge badge-active">{{ number_format($analysis['total_rows']) }} Total Rows Detected</span>
      </div>

      <div class="sm-preview-summary-bar">
        <div class="sm-prev-stat">
          <span>Total Records in File</span>
          <b>{{ number_format($analysis['total_rows']) }}</b>
        </div>
        <div class="sm-prev-stat {{ $analysis['duplicate_count'] > 0 ? 'text-amber' : '' }}">
          <span>Existing in DB (Will Skip)</span>
          <b>{{ number_format($analysis['duplicate_count']) }}</b>
        </div>
        <div class="sm-prev-stat {{ $analysis['error_count'] > 0 ? 'text-red' : '' }}">
          <span>Rows with Incomplete Data</span>
          <b>{{ number_format($analysis['error_count']) }}</b>
        </div>
        <div class="sm-prev-stat text-green">
          <span>New Records Ready to Import</span>
          <b>{{ number_format(max(0, $analysis['total_rows'] - $analysis['duplicate_count'] - $analysis['error_count'])) }}</b>
        </div>
      </div>

      @if($analysis['duplicate_count'] > 0)
        <div class="alert warning" style="background:#fef3c7;border:1px solid #fde68a;color:#92400e;padding:12px 16px;border-radius:6px;margin:18px 0;font-size:13px;">
          <strong>Notice:</strong> {{ number_format($analysis['duplicate_count']) }} Student IDs already exist in the database and will be safely preserved (skipped).
          @if(!empty($analysis['duplicate_samples']))
            <br><small style="color:#78350f;">Sample duplicate IDs: {{ implode(', ', $analysis['duplicate_samples']) }}</small>
          @endif
        </div>
      @endif

      @if(!empty($analysis['preview_rows']))
        <h4 style="font-size:14px;font-weight:700;color:#1f2937;margin:22px 0 10px;">First 10 Records Preview:</h4>
        <div class="sdb-table-responsive" style="border:1px solid #e2e8f0;border-radius:8px;overflow-x:auto;">
          <table class="sdb-table" style="font-size:12.5px;min-width:750px;">
            <thead>
              <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Occupation</th>
                <th>Academic Year</th>
                <th>Session</th>
                <th>Batch</th>
                <th>Grade</th>
                <th>Status</th>
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
                  <td><b>{{ $row['grade'] ?? '—' }}</b></td>
                  <td>
                    @if(!empty($row['is_duplicate']))
                      <span class="sm-badge" style="background:#fef3c7;color:#92400e;">Already in DB</span>
                    @else
                      <span class="sm-badge badge-completed">New Record</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.students.import.process', [], false) }}" id="confirmImportForm" style="margin-top:24px;display:flex;gap:12px;align-items:center;">
        @csrf
        <input type="hidden" name="import_token" value="{{ $importToken ?? '' }}">
        <input type="hidden" name="temp_path" value="{{ $tempPath ?? '' }}">
        <input type="hidden" name="original_name" value="{{ $originalName }}">
        <button type="submit" id="confirmImportBtn" class="sm-btn sm-btn-primary sm-btn-lg" onclick="handleConfirmSubmit(this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span id="confirmBtnText">Confirm & Import {{ number_format(max(0, $analysis['total_rows'] - $analysis['duplicate_count'] - $analysis['error_count'])) }} Records Now</span>
        </button>
        <a href="{{ route('admin.students.import', [], false) }}" class="sm-btn sm-btn-secondary">Cancel & Re-upload</a>
      </form>
    </div>
  @else
    {{-- Upload Form --}}
    <div class="sm-upload-card">
      <form method="POST" action="{{ route('admin.students.import.preview', [], false) }}" enctype="multipart/form-data" id="uploadForm">
        @csrf
        <div class="sm-drop-area" id="dropArea">
          <input type="file" id="excelFileInput" name="excel_file" accept=".xlsx,.xlsm,.xls,.csv,.txt" required style="display:none;" onchange="showSelectedFileName(this)">
          
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="1.8" style="margin-bottom:10px;">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="17 8 12 3 7 8"></polyline>
            <line x1="12" y1="3" x2="12" y2="15"></line>
          </svg>
          <h3 style="font-size:16px;font-weight:700;color:#1f2937;margin:0 0 4px;">Choose Excel (.xlsx, .xlsm) or CSV Workbook</h3>
          <p style="font-size:13px;color:#64748b;margin:0 0 16px;">Click Browse File or drag and drop your StudentDB workbook here</p>
          
          <div>
            <button type="button" class="sm-btn sm-btn-primary" onclick="document.getElementById('excelFileInput').click()">
              Browse File
            </button>
          </div>

          <div id="fileSelectedInfo" style="display:none;margin-top:14px;padding:8px 14px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:6px;display:inline-flex;align-items:center;gap:8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span id="selectedFileNameBadge" style="font-size:13px;font-weight:600;color:#065f46;">No file selected</span>
          </div>
        </div>

        <div style="margin-top:20px;display:flex;justify-content:flex-end;">
          <button type="submit" id="uploadBtn" class="sm-btn sm-btn-primary" onclick="handleUploadSubmit(this)">
            <span id="uploadBtnText">Upload & Preview Records &rarr;</span>
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
  var infoBox = document.getElementById('fileSelectedInfo');
  var badge = document.getElementById('selectedFileNameBadge');
  if (input.files && input.files[0]) {
    var f = input.files[0];
    var sizeKB = Math.round(f.size / 1024);
    var sizeStr = sizeKB > 1024 ? (sizeKB / 1024).toFixed(1) + ' MB' : sizeKB + ' KB';
    badge.textContent = f.name + ' (' + sizeStr + ')';
    infoBox.style.display = 'inline-flex';
  } else {
    infoBox.style.display = 'none';
  }
}

// Drag & drop support
var dropArea = document.getElementById('dropArea');
if (dropArea) {
  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(eventName) {
    dropArea.addEventListener(eventName, function(e) {
      e.preventDefault();
      e.stopPropagation();
    }, false);
  });

  ['dragenter', 'dragover'].forEach(function(eventName) {
    dropArea.addEventListener(eventName, function() {
      dropArea.classList.add('highlight');
    }, false);
  });

  ['dragleave', 'drop'].forEach(function(eventName) {
    dropArea.addEventListener(eventName, function() {
      dropArea.classList.remove('highlight');
    }, false);
  });

  dropArea.addEventListener('drop', function(e) {
    var dt = e.dataTransfer;
    var files = dt.files;
    var fileInput = document.getElementById('excelFileInput');
    if (files && files.length > 0) {
      fileInput.files = files;
      showSelectedFileName(fileInput);
    }
  }, false);
}

function handleUploadSubmit(btn) {
  var fileInput = document.getElementById('excelFileInput');
  if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
    alert('Please select an Excel or CSV file first.');
    return false;
  }
  var btnText = document.getElementById('uploadBtnText');
  if (btnText) {
    btnText.textContent = 'Uploading & Parsing... Please wait';
  }
  btn.style.opacity = '0.7';
  btn.style.pointerEvents = 'none';
  document.getElementById('uploadForm').submit();
}

function handleConfirmSubmit(btn) {
  var text = document.getElementById('confirmBtnText');
  if (text) {
    text.textContent = 'Importing records into database... Please wait';
  }
  btn.style.opacity = '0.7';
  btn.style.pointerEvents = 'none';
  document.getElementById('confirmImportForm').submit();
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
  transition: all 0.2s ease;
}

.sm-drop-area.highlight {
  border-color: #047857;
  background: #ecfdf5;
}

.sm-preview-summary-bar {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  padding: 16px 0;
  border-top: 1px solid #e2e8f0;
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
