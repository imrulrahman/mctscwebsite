@extends('admin.layout')

@section('title', 'Job Placement · Student Management')
@section('crumb', 'Student Management / Job Placement')

@section('content')
<div class="sm-page-container">
  <div class="sm-header-row">
    <div>
      <h1 class="sm-title">Graduate Job Placement</h1>
      <p class="sm-subtitle">Track employed graduates, corporate hiring partners, self-employment and salary distributions</p>
    </div>
    <div style="display:flex;gap:10px;">
      <a href="{{ route('admin.students.database') }}?job_status=Employed" class="sm-btn sm-btn-secondary">
        Employed in Database &rarr;
      </a>
    </div>
  </div>

  {{-- Metrics --}}
  <div class="sm-stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom:24px;">
    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#ecfdf5;color:#047857;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
          <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Employed Graduates</span>
        <b class="sm-stat-value">{{ number_format($totalEmployed) }}</b>
      </div>
    </div>

    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#ccfbf1;color:#0f766e;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Self Employed</span>
        <b class="sm-stat-value">{{ number_format($totalSelfEmployed) }}</b>
      </div>
    </div>

    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#fef3c7;color:#b45309;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Seeking / Not Employed</span>
        <b class="sm-stat-value">{{ number_format($totalSeeking) }}</b>
      </div>
    </div>

    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#eff6ff;color:#2563eb;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <line x1="12" y1="1" x2="12" y2="23"></line>
          <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Average Monthly Salary</span>
        <b class="sm-stat-value">{{ $avgSalary ? '৳' . number_format($avgSalary, 0) : '—' }}</b>
      </div>
    </div>
  </div>

  {{-- Filter bar --}}
  <form method="GET" action="{{ route('admin.students.job_placement') }}" class="sdb-filters-card" style="margin-bottom:20px;">
    <div class="sdb-basic-filter-row">
      <div class="sdb-filter-item">
        <label class="sdb-label">Job Status:</label>
        <select name="job_status" class="sdb-select" onchange="this.form.submit()">
          <option value="all">All Statuses</option>
          <option value="Employed" {{ request('job_status') == 'Employed' ? 'selected' : '' }}>Employed</option>
          <option value="Self Employed" {{ request('job_status') == 'Self Employed' ? 'selected' : '' }}>Self Employed</option>
          <option value="Not Yet Employed" {{ request('job_status') == 'Not Yet Employed' ? 'selected' : '' }}>Not Yet Employed</option>
        </select>
      </div>

      <div class="sdb-filter-item">
        <label class="sdb-label">Year:</label>
        <select name="year" class="sdb-select" onchange="this.form.submit()">
          <option value="all">All</option>
          @foreach($years as $yr)
            <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
          @endforeach
        </select>
      </div>

      <div class="sdb-filter-search">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search company, student, role..." class="sdb-search-input">
      </div>

      <button type="submit" class="sm-btn sm-btn-primary" style="padding:7px 14px;">Filter</button>
      @if(request()->hasAny(['job_status', 'year', 'search']))
        <a href="{{ route('admin.students.job_placement') }}" class="sm-btn sm-btn-secondary" style="padding:7px 12px;">Reset</a>
      @endif
    </div>
  </form>

  {{-- Job Placement Table --}}
  <div class="sdb-table-card">
    <div class="sdb-table-header">
      <div class="sdb-header-title-box">
        <h2 class="sdb-table-title">Graduate Placements & Employment Records</h2>
        <span class="sdb-count-badge">{{ number_format($students->total()) }} records</span>
      </div>
    </div>

    <div class="sdb-table-responsive">
      <table class="sdb-table">
        <thead>
          <tr>
            <th>Student ID</th>
            <th>Trainee Name</th>
            <th>Occupation</th>
            <th>Status</th>
            <th>Company Name</th>
            <th>Designation</th>
            <th>Salary (BDT)</th>
            <th>Joining Date</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $s)
            <tr>
              <td>
                <a href="{{ route('admin.students.show', $s) }}" class="sdb-id-link">{{ $s->student_id }}</a>
              </td>
              <td><b>{{ $s->student_name }}</b></td>
              <td>{{ $s->occupation }}</td>
              <td>
                @if($s->job_status === 'Employed')
                  <span class="sm-badge" style="background:#d1fae5;color:#047857;">Employed</span>
                @elseif($s->job_status === 'Self Employed')
                  <span class="sm-badge" style="background:#ccfbf1;color:#0f766e;">Self Employed</span>
                @else
                  <span class="sm-badge" style="background:#fef3c7;color:#92400e;">{{ $s->job_status }}</span>
                @endif
              </td>
              <td><b>{{ $s->company_name ?: '—' }}</b></td>
              <td>{{ $s->designation ?: '—' }}</td>
              <td>{{ $s->salary ? '৳' . number_format($s->salary, 0) : '—' }}</td>
              <td>{{ $s->joining_date ? $s->joining_date->format('d M Y') : '—' }}</td>
              <td style="text-align:right;">
                <a href="{{ route('admin.students.show', $s) }}" class="sm-btn sm-btn-secondary" style="padding:4px 10px;font-size:12px;">
                  Profile
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" style="text-align:center;padding:30px;color:#64748b;">No placement records matching criteria.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="sdb-table-footer">
      <div>Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ number_format($students->total()) }}</div>
      <div>{{ $students->links('admin.students.pagination') }}</div>
    </div>
  </div>
</div>

<style>
.sm-page-container { max-width: 1400px; margin: 0 auto; }
.sm-header-row { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px; gap:20px; flex-wrap:wrap; }
.sm-title { font-size:26px; font-weight:700; color:#1f2937; margin:0 0 4px; }
.sm-subtitle { font-size:13.5px; color:#64748b; margin:0; }
.sm-stats-grid { display:grid; gap:16px; }
.sm-stat-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:18px 16px; display:flex; align-items:center; gap:14px; box-shadow:0 1px 3px rgba(0,0,0,0.02); }
.sm-stat-icon-wrap { width:48px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.sm-stat-content { display:flex; flex-direction:column; }
.sm-stat-label { font-size:12.5px; color:#64748b; font-weight:500; margin-bottom:2px; }
.sm-stat-value { font-size:24px; font-weight:700; color:#1f2937; }
.sm-btn { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; cursor:pointer; border:1px solid transparent; }
.sm-btn-primary { background:#047857; color:#fff; }
.sm-btn-primary:hover { background:#065f46; }
.sm-btn-secondary { background:#fff; border-color:#cbd5e1; color:#334155; }
.sm-badge { display:inline-block; padding:3px 9px; border-radius:14px; font-size:11.5px; font-weight:600; }
.sdb-table-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
.sdb-table-header { padding:16px 20px; border-bottom:1px solid #e2e8f0; }
.sdb-table-title { font-size:16px; font-weight:700; color:#1f2937; margin:0; }
.sdb-count-badge { font-size:12px; color:#64748b; margin-left:8px; }
.sdb-table { width:100%; border-collapse:collapse; }
.sdb-table th { background:#f8fafc; color:#64748b; font-size:11.5px; font-weight:600; text-transform:uppercase; padding:11px 16px; border-bottom:1px solid #e2e8f0; text-align:left; }
.sdb-table td { padding:12px 16px; border-bottom:1px solid #f1f5f9; font-size:13px; color:#334155; }
.sdb-table-footer { padding:14px 20px; display:flex; justify-content:space-between; align-items:center; border-top:1px solid #e2e8f0; font-size:13px; color:#64748b; }
.sdb-id-link { font-family:ui-monospace,SFMono-Regular,monospace; font-weight:700; color:#047857; text-decoration:none; }
.sdb-filters-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:14px 18px; }
.sdb-basic-filter-row { display:flex; align-items:center; gap:14px; flex-wrap:wrap; }
.sdb-filter-item { display:flex; align-items:center; gap:6px; }
.sdb-label { font-size:13px; font-weight:600; color:#475569; }
.sdb-select { border:1px solid #cbd5e1; border-radius:6px; padding:7px 10px; font-size:13px; }
.sdb-filter-search { flex:1; min-width:200px; }
.sdb-search-input { width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:7px 12px; font-size:13px; }
</style>
@endsection
