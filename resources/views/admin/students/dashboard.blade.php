@extends('admin.layout')

@section('title', 'Student Management · MCTSC Admin')
@section('crumb', 'Student Management')

@section('content')
<div class="sm-dashboard-wrapper">
  {{-- Header: Title, Subtitle and Academic Year Picker --}}
  <div class="sm-header-row">
    <div>
      <h1 class="sm-title">Student Management</h1>
      <p class="sm-subtitle">Manage student services and records across the institute</p>
    </div>

    <div class="sm-year-selector-box">
      <label for="academicYearSelect" class="sm-year-label">Academic Year</label>
      <div class="sm-select-input-wrap">
        <svg class="sm-calendar-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="16" y1="2" x2="16" y2="6"></line>
          <line x1="8" y1="2" x2="8" y2="6"></line>
          <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <select id="academicYearSelect" class="sm-year-select" onchange="window.location.href='{{ route('admin.students.dashboard') }}?year=' + encodeURIComponent(this.value)">
          <option value="all" {{ $selectedYear === 'all' ? 'selected' : '' }}>All Academic Years</option>
          @foreach($academicYears as $year)
            <option value="{{ $year }}" {{ (string)$selectedYear === (string)$year ? 'selected' : '' }}>
              {{ $year }}
            </option>
          @endforeach
        </select>
        <svg class="sm-chevron-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
      </div>
    </div>
  </div>

  {{-- 5 Statistics Cards Grid --}}
  <div class="sm-stats-grid">
    {{-- Card 1: Total Students --}}
    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap icon-total">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Total Students</span>
        <b class="sm-stat-value">{{ number_format($totalStudents) }}</b>
      </div>
    </div>

    {{-- Card 2: Male --}}
    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap icon-male">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <circle cx="12" cy="7" r="4"></circle>
          <path d="M6 21v-2a6 6 0 0 1 12 0v2"></path>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Male</span>
        <b class="sm-stat-value">{{ number_format($maleStudents) }}</b>
      </div>
    </div>

    {{-- Card 3: Female --}}
    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap icon-female">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <circle cx="12" cy="7" r="4"></circle>
          <path d="M7 21v-3a5 5 0 0 1 10 0v3"></path>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Female</span>
        <b class="sm-stat-value">{{ number_format($femaleStudents) }}</b>
      </div>
    </div>

    {{-- Card 4: Ethnic --}}
    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap icon-ethnic">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <polyline points="16 11 18 13 22 9"></polyline>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Ethnic</span>
        <b class="sm-stat-value">{{ number_format($ethnicStudents) }}</b>
      </div>
    </div>

    {{-- Card 5: PWD --}}
    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap icon-pwd">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <circle cx="12" cy="4" r="2"></circle>
          <path d="M7 13h5l3 8h4"></path>
          <path d="M10 8v8a4 4 0 0 0 4 4"></path>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">PWD</span>
        <b class="sm-stat-value">{{ number_format($pwdStudents) }}</b>
      </div>
    </div>
  </div>

  {{-- 4 Module Navigation Cards (2 columns x 2 rows) --}}
  <div class="sm-modules-grid">
    {{-- Module 1: Student Database --}}
    <a href="{{ route('admin.students.database') }}" class="sm-module-card highlight-card">
      <div class="sm-module-icon-box">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
      </div>
      <div class="sm-module-info">
        <h3 class="sm-module-title">Student Database</h3>
        <p class="sm-module-desc">Browse, search and manage student records</p>
        <span class="sm-module-pill pill-green">{{ number_format($allStudentsCount) }} students</span>
      </div>
      <div class="sm-module-arrow">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
          <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
      </div>
    </a>

    {{-- Module 2: Admission --}}
    <a href="{{ route('admin.students.admission') }}" class="sm-module-card">
      <div class="sm-module-icon-box">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="12" y1="18" x2="12" y2="12"></line>
          <line x1="9" y1="15" x2="15" y2="15"></line>
        </svg>
      </div>
      <div class="sm-module-info">
        <h3 class="sm-module-title">Admission</h3>
        <p class="sm-module-desc">Review applications and enrollment</p>
        @if($pendingAdmissionsCount > 0)
          <span class="sm-module-pill pill-amber">{{ $pendingAdmissionsCount }} pending</span>
        @else
          <span class="sm-module-pill pill-gray">Active Enrollment</span>
        @endif
      </div>
      <div class="sm-module-arrow">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
          <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
      </div>
    </a>

    {{-- Module 3: Certificate Management --}}
    <a href="{{ route('admin.students.certificates') }}" class="sm-module-card">
      <div class="sm-module-icon-box">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <circle cx="12" cy="14" r="3"></circle>
          <polyline points="10 17 12 19 14 17"></polyline>
        </svg>
      </div>
      <div class="sm-module-info">
        <h3 class="sm-module-title">Certificate Management</h3>
        <p class="sm-module-desc">Issue and track student certificates</p>
        <span class="sm-module-pill pill-teal">{{ number_format($certificatesIssuedCount) }} issued</span>
      </div>
      <div class="sm-module-arrow">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
          <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
      </div>
    </a>

    {{-- Module 4: Job Placement --}}
    <a href="{{ route('admin.students.job_placement') }}" class="sm-module-card">
      <div class="sm-module-icon-box">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
          <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
        </svg>
      </div>
      <div class="sm-module-info">
        <h3 class="sm-module-title">Job Placement</h3>
        <p class="sm-module-desc">Track graduate employment and employer links</p>
        <span class="sm-module-pill pill-emerald">{{ number_format($placementsCount) }} employed</span>
      </div>
      <div class="sm-module-arrow">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
          <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
      </div>
    </a>
  </div>
</div>

<style>
/* Student Management Overview Styles (Deep Forest Green Theme) */
.sm-dashboard-wrapper {
  max-width: 1400px;
  margin: 0 auto;
}

.sm-header-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 24px;
  gap: 20px;
  flex-wrap: wrap;
}

.sm-title {
  font-size: 28px;
  font-weight: 700;
  color: #1f2937;
  letter-spacing: -0.5px;
  margin: 0 0 4px;
}

.sm-subtitle {
  font-size: 14px;
  color: #64748b;
  margin: 0;
}

.sm-year-selector-box {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 6px;
}

.sm-year-label {
  font-size: 11px;
  font-weight: 600;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.sm-select-input-wrap {
  position: relative;
  display: flex;
  align-items: center;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04);
  padding: 0 10px;
  transition: all 0.2s ease;
}

.sm-select-input-wrap:focus-within {
  border-color: #047857;
  box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.12);
}

.sm-calendar-icon {
  color: #047857;
  margin-right: 6px;
  flex-shrink: 0;
}

.sm-year-select {
  appearance: none;
  background: transparent;
  border: none;
  font-size: 14px;
  font-weight: 600;
  color: #1f2937;
  padding: 8px 24px 8px 4px;
  cursor: pointer;
  outline: none;
}

.sm-chevron-icon {
  position: absolute;
  right: 10px;
  color: #64748b;
  pointer-events: none;
}

/* 5 Statistics Cards */
.sm-stats-grid {
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: 16px;
  margin-bottom: 28px;
}

.sm-stat-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 18px 16px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.03);
  transition: transform 0.18s ease, box-shadow 0.18s ease;
}

.sm-stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(6, 78, 59, 0.08);
}

.sm-stat-icon-wrap {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.icon-total { background: #ecfdf5; color: #047857; }
.icon-male { background: #eff6ff; color: #2563eb; }
.icon-female { background: #fdf2f8; color: #db2777; }
.icon-ethnic { background: #f0fdf4; color: #16a34a; }
.icon-pwd { background: #f5f3ff; color: #7c3aed; }

.sm-stat-content {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.sm-stat-label {
  font-size: 12.5px;
  color: #64748b;
  font-weight: 500;
  margin-bottom: 2px;
  white-space: nowrap;
}

.sm-stat-value {
  font-size: 24px;
  font-weight: 700;
  color: #1f2937;
  line-height: 1.15;
}

/* 4 Module Navigation Cards */
.sm-modules-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 20px;
}

.sm-module-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 20px;
  text-decoration: none;
  box-shadow: 0 2px 5px rgba(0,0,0,0.03);
  transition: all 0.2s ease;
  position: relative;
}

.sm-module-card:hover {
  border-color: #047857;
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(6, 78, 59, 0.09);
}

.sm-module-card.highlight-card {
  border: 1.5px solid #10b981;
  background: linear-gradient(135deg, #ffffff 70%, #ecfdf5 100%);
}

.sm-module-icon-box {
  width: 60px;
  height: 60px;
  border-radius: 14px;
  background: #ecfdf5;
  color: #047857;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: background 0.2s ease;
}

.sm-module-card:hover .sm-module-icon-box {
  background: #047857;
  color: #ffffff;
}

.sm-module-info {
  flex: 1;
  min-width: 0;
}

.sm-module-title {
  font-size: 18px;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 5px;
}

.sm-module-desc {
  font-size: 13.5px;
  color: #64748b;
  margin: 0 0 10px;
}

.sm-module-pill {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.pill-green { background: #d1fae5; color: #065f46; }
.pill-amber { background: #fef3c7; color: #92400e; }
.pill-teal { background: #ccfbf1; color: #115e59; }
.pill-emerald { background: #d1fae5; color: #047857; }
.pill-gray { background: #f1f5f9; color: #475569; }

.sm-module-arrow {
  color: #94a3b8;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.2s ease, color 0.2s ease;
}

.sm-module-card:hover .sm-module-arrow {
  color: #047857;
  transform: translateX(4px);
}

@media (max-width: 1100px) {
  .sm-stats-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
  .sm-modules-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 640px) {
  .sm-stats-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
  .sm-header-row {
    flex-direction: column;
    align-items: flex-start;
  }
  .sm-year-selector-box {
    align-items: flex-start;
    width: 100%;
  }
  .sm-select-input-wrap {
    width: 100%;
  }
  .sm-year-select {
    width: 100%;
  }
}
</style>
@endsection
