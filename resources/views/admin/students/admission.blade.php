@extends('admin.layout')

@section('title', 'Admission Management · Student Management')
@section('crumb', 'Student Management / Admission')

@section('content')
<div class="sm-page-container">
  <div class="sm-header-row">
    <div>
      <h1 class="sm-title">Admission & Enrollment Management</h1>
      <p class="sm-subtitle">Review incoming applications, verify documents, and enroll students into Student Database</p>
    </div>
    <div style="display:flex;gap:10px;">
      <a href="{{ route('admin.applications.create') }}" class="sm-btn sm-btn-primary">
        + New Application
      </a>
      <a href="{{ route('admin.applications') }}" class="sm-btn sm-btn-secondary">
        All Applications &rarr;
      </a>
    </div>
  </div>

  {{-- Metrics Cards --}}
  <div class="sm-stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom:24px;">
    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#fef3c7;color:#b45309;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Pending Applications</span>
        <b class="sm-stat-value">{{ number_format($pendingApplications->total()) }}</b>
      </div>
    </div>

    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#ecfdf5;color:#047857;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Approved Applications</span>
        <b class="sm-stat-value">{{ number_format($approvedApplications) }}</b>
      </div>
    </div>

    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#eff6ff;color:#2563eb;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Enrolled in Database</span>
        <b class="sm-stat-value">{{ number_format($enrolledStudentsCount) }}</b>
      </div>
    </div>

    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#f1f5f9;color:#475569;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Total Applications</span>
        <b class="sm-stat-value">{{ number_format($totalApplications) }}</b>
      </div>
    </div>
  </div>

  {{-- Pending Applications Table --}}
  <div class="sdb-table-card">
    <div class="sdb-table-header">
      <div class="sdb-header-title-box">
        <h2 class="sdb-table-title">Recent Pending Applications Requiring Review</h2>
        <span class="sdb-count-badge">{{ $pendingApplications->total() }} pending</span>
      </div>
    </div>

    <div class="sdb-table-responsive">
      <table class="sdb-table">
        <thead>
          <tr>
            <th>Tracking No</th>
            <th>Applicant Name</th>
            <th>Course / Trade</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Applied Date</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pendingApplications as $app)
            <tr>
              <td><b style="font-family:monospace;color:#047857;">{{ $app->tracking_no ?: ('#' . $app->id) }}</b></td>
              <td><b>{{ $app->trainee_name_en ?: $app->name }}</b></td>
              <td>{{ $app->course }}</td>
              <td>{{ $app->contact_no ?: $app->phone }}</td>
              <td>{{ $app->sex ?: '—' }}</td>
              <td>{{ $app->created_at->format('d M Y') }}</td>
              <td>
                <span class="sm-badge" style="background:#fef3c7;color:#92400e;">New Review</span>
              </td>
              <td style="text-align:right;">
                <a href="{{ route('admin.applications.show', $app) }}" class="sm-btn sm-btn-primary" style="padding:4px 10px;font-size:12px;">
                  Review & Enroll
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="text-align:center;padding:30px;color:#64748b;">
                No pending applications currently. All applicants are up to date!
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="sdb-table-footer">
      <div>Showing {{ $pendingApplications->firstItem() ?? 0 }}–{{ $pendingApplications->lastItem() ?? 0 }} of {{ $pendingApplications->total() }}</div>
      <div>{{ $pendingApplications->links('admin.students.pagination') }}</div>
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
</style>
@endsection
