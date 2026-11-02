@extends('admin.layout')

@section('title', 'Student Database · MCTSC Admin')
@section('crumb', 'Student Management / Student Database')

@section('content')
<div class="sdb-wrapper">
  {{-- Header: Title, Compact Stats and Action Buttons --}}
  <div class="sdb-page-header">
    <div class="sdb-header-left">
      <h1 class="sdb-title">Student Database</h1>
      {{-- Compact Horizontal Statistics Bar --}}
      <div class="sdb-compact-stats">
        <span class="c-stat-item"><b>Total</b> {{ number_format($compactStats['total']) }}</span>
        <span class="c-stat-sep">|</span>
        <span class="c-stat-item"><b>Male</b> {{ number_format($compactStats['male']) }}</span>
        <span class="c-stat-sep">|</span>
        <span class="c-stat-item"><b>Female</b> {{ number_format($compactStats['female']) }}</span>
        <span class="c-stat-sep">|</span>
        <span class="c-stat-item"><b>Ethnic</b> {{ number_format($compactStats['ethnic']) }}</span>
        <span class="c-stat-sep">|</span>
        <span class="c-stat-item"><b>PWD</b> {{ number_format($compactStats['pwd']) }}</span>
      </div>
    </div>

    <div class="sdb-header-actions">
      <a href="{{ route('admin.students.import') }}" class="sdb-btn sdb-btn-outline" title="Import from Excel or CSV">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="17 8 12 3 7 8"></polyline>
          <line x1="12" y1="3" x2="12" y2="15"></line>
        </svg>
        <span>Import Excel</span>
      </a>

      <a href="{{ route('admin.students.export', request()->query()) }}" class="sdb-btn sdb-btn-outline" title="Export Current List to Excel">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="7 10 12 15 17 10"></polyline>
          <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        <span>Export Excel</span>
      </a>

      <a href="{{ route('admin.students.print-roster', request()->query()) }}" target="_blank" class="sdb-btn sdb-btn-outline" title="Print Batch Roster">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="6 9 6 2 18 2 18 9"></polyline>
          <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
          <rect x="6" y="14" width="12" height="8"></rect>
        </svg>
        <span>Print List</span>
      </a>

      @can('students.create')
        <a href="{{ route('admin.students.create') }}" class="sdb-btn sdb-btn-primary">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          <span>Add Student</span>
        </a>
      @endcan
    </div>
  </div>

  {{-- Filter Form (Basic & Collapsible Advanced) --}}
  <form id="sdbFilterForm" method="GET" action="{{ route('admin.students.database') }}" class="sdb-filters-card">
    <div class="sdb-basic-filter-row">
      {{-- Batch Filter --}}
      <div class="sdb-filter-item">
        <label class="sdb-label">Batch:</label>
        <select name="batch" class="sdb-select" onchange="this.form.submit()">
          <option value="all">All</option>
          @foreach($batches as $b)
            <option value="{{ $b }}" {{ request('batch') == $b ? 'selected' : '' }}>{{ $b }}</option>
          @endforeach
        </select>
      </div>

      {{-- Project Filter --}}
      <div class="sdb-filter-item">
        <label class="sdb-label">Project:</label>
        <select name="project" class="sdb-select" onchange="this.form.submit()">
          <option value="all">All</option>
          @foreach($projects as $p)
            <option value="{{ $p }}" {{ request('project') == $p ? 'selected' : '' }}>{{ $p }}</option>
          @endforeach
        </select>
      </div>

      {{-- Academic Year Filter --}}
      <div class="sdb-filter-item">
        <label class="sdb-label">Year:</label>
        <select name="year" class="sdb-select" onchange="this.form.submit()">
          <option value="all">All</option>
          @foreach($years as $y)
            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
          @endforeach
        </select>
      </div>

      {{-- Search Input --}}
      <div class="sdb-filter-search">
        <svg class="sdb-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, ID, phone..." class="sdb-search-input">
        @if(request('search'))
          <a href="{{ route('admin.students.database', request()->except('search')) }}" class="sdb-clear-search">&times;</a>
        @endif
      </div>

      {{-- Advanced Filters Toggle Button --}}
      <button type="button" class="sdb-adv-toggle-btn {{ request()->hasAny(['occupation', 'session', 'gender', 'shift', 'course_type', 'training_status', 'job_status', 'certificate_status', 'category', 'religion']) ? 'adv-active' : '' }}" onclick="toggleAdvancedFilters()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="4" y1="21" x2="4" y2="14"></line>
          <line x1="4" y1="10" x2="4" y2="3"></line>
          <line x1="12" y1="21" x2="12" y2="12"></line>
          <line x1="12" y1="8" x2="12" y2="3"></line>
          <line x1="20" y1="21" x2="20" y2="16"></line>
          <line x1="20" y1="12" x2="20" y2="3"></line>
          <line x1="1" y1="14" x2="7" y2="14"></line>
          <line x1="9" y1="8" x2="15" y2="8"></line>
          <line x1="17" y1="16" x2="23" y2="16"></line>
        </svg>
        <span>Advanced Filters</span>
      </button>

      @if(request()->hasAny(['search', 'batch', 'project', 'year', 'occupation', 'session', 'gender', 'shift', 'course_type', 'training_status', 'job_status', 'category']))
        <a href="{{ route('admin.students.database') }}" class="sdb-reset-btn" title="Reset all filters">Reset</a>
      @endif
    </div>

    {{-- Advanced Filters Collapsible Drawer --}}
    <div id="sdbAdvancedFiltersDrawer" class="sdb-adv-drawer" style="{{ request()->hasAny(['occupation', 'session', 'gender', 'shift', 'course_type', 'training_status', 'job_status', 'category', 'religion']) ? 'display:block;' : 'display:none;' }}">
      <div class="sdb-adv-grid">
        {{-- Occupation / Course --}}
        <div class="sdb-adv-col">
          <label>Occupation / Course</label>
          <select name="occupation" class="sdb-select">
            <option value="all">All Occupations</option>
            @foreach($occupations as $occ)
              <option value="{{ $occ }}" {{ request('occupation') == $occ ? 'selected' : '' }}>{{ $occ }}</option>
            @endforeach
          </select>
        </div>

        {{-- Session --}}
        <div class="sdb-adv-col">
          <label>Session</label>
          <select name="session" class="sdb-select">
            <option value="all">All Sessions</option>
            @foreach($sessions as $sess)
              <option value="{{ $sess }}" {{ request('session') == $sess ? 'selected' : '' }}>{{ $sess }}</option>
            @endforeach
          </select>
        </div>

        {{-- Gender --}}
        <div class="sdb-adv-col">
          <label>Gender</label>
          <select name="gender" class="sdb-select">
            <option value="all">All</option>
            <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
          </select>
        </div>

        {{-- Special Category --}}
        <div class="sdb-adv-col">
          <label>Special Category</label>
          <select name="category" class="sdb-select">
            <option value="all">All</option>
            <option value="ethnic" {{ request('category') == 'ethnic' ? 'selected' : '' }}>Ethnic Category</option>
            <option value="pwd" {{ request('category') == 'pwd' ? 'selected' : '' }}>Person With Disability (PWD)</option>
          </select>
        </div>

        {{-- Training Status --}}
        <div class="sdb-adv-col">
          <label>Training Status</label>
          <select name="training_status" class="sdb-select">
            <option value="all">All Status</option>
            <option value="Active" {{ request('training_status') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Running" {{ request('training_status') == 'Running' ? 'selected' : '' }}>Running</option>
            <option value="Completed" {{ request('training_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
            <option value="Dropped" {{ request('training_status') == 'Dropped' ? 'selected' : '' }}>Dropped</option>
          </select>
        </div>

        {{-- Employment Status --}}
        <div class="sdb-adv-col">
          <label>Job Status</label>
          <select name="job_status" class="sdb-select">
            <option value="all">All</option>
            <option value="Employed" {{ request('job_status') == 'Employed' ? 'selected' : '' }}>Employed</option>
            <option value="Self Employed" {{ request('job_status') == 'Self Employed' ? 'selected' : '' }}>Self Employed</option>
            <option value="Not Yet Employed" {{ request('job_status') == 'Not Yet Employed' ? 'selected' : '' }}>Not Yet Employed</option>
          </select>
        </div>

        {{-- Shift --}}
        <div class="sdb-adv-col">
          <label>Class Shift</label>
          <select name="shift" class="sdb-select">
            <option value="all">All Shifts</option>
            <option value="Morning" {{ request('shift') == 'Morning' ? 'selected' : '' }}>Morning</option>
            <option value="Day" {{ request('shift') == 'Day' ? 'selected' : '' }}>Day</option>
            <option value="Evening" {{ request('shift') == 'Evening' ? 'selected' : '' }}>Evening</option>
          </select>
        </div>

        {{-- Certificate Status --}}
        <div class="sdb-adv-col">
          <label>Certificate</label>
          <select name="certificate_status" class="sdb-select">
            <option value="all">All</option>
            <option value="Issued" {{ request('certificate_status') == 'Issued' ? 'selected' : '' }}>Issued</option>
            <option value="Pending" {{ request('certificate_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
          </select>
        </div>
      </div>

      <div class="sdb-adv-actions">
        <button type="submit" class="sdb-btn sdb-btn-primary sdb-btn-sm">Apply Advanced Filters</button>
        <a href="{{ route('admin.students.database') }}" class="sdb-reset-link">Clear All</a>
      </div>
    </div>
  </form>

  {{-- Student Records Table Card --}}
  <div class="sdb-table-card">
    <div class="sdb-table-header">
      <div class="sdb-header-title-box">
        <h2 class="sdb-table-title">Student Records</h2>
        <span class="sdb-count-badge">{{ number_format($students->total()) }} records</span>
      </div>

      <div class="sdb-table-actions-right">
        {{-- Selected bulk info --}}
        <div id="bulkActionsBox" style="display:none; align-items:center; gap:10px;">
          <span id="selectedCountText" style="font-size:12px; font-weight:600; color:#047857;">0 selected</span>
        </div>
      </div>
    </div>

    {{-- Responsive Table --}}
    <div class="sdb-table-responsive">
      <table class="sdb-table">
        <thead>
          <tr>
            <th style="width: 40px; text-align: center;">
              <input type="checkbox" id="selectAllCheckbox" onclick="toggleSelectAll(this)" title="Select All">
            </th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Course</th>
            <th>Batch</th>
            <th>Year</th>
            <th>Gender</th>
            <th>Category</th>
            <th>Status</th>
            <th style="text-align: right; width: 90px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $s)
            @php
              $color = $s->avatar_color;
            @endphp
            <tr>
              <td style="text-align: center;">
                <input type="checkbox" class="student-row-check" value="{{ $s->id }}" onclick="updateSelectedCount()">
              </td>
              <td>
                <a href="{{ route('admin.students.show', $s) }}" class="sdb-id-link" title="View Profile">
                  {{ $s->student_id }}
                </a>
              </td>
              <td>
                <div class="sdb-name-cell">
                  <div class="sdb-avatar" style="background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                    {{ $s->avatar_initials }}
                  </div>
                  <div class="sdb-name-meta">
                    <a href="{{ route('admin.students.show', $s) }}" class="sdb-student-name">
                      {{ $s->student_name }}
                    </a>
                    @if($s->father_name || $s->age)
                      <span class="sdb-student-sub">
                        {{ $s->father_name ? "F: {$s->father_name}" : '' }}
                        {{ ($s->father_name && $s->age) ? '·' : '' }}
                        {{ $s->age ? "{$s->age} yrs" : '' }}
                      </span>
                    @endif
                  </div>
                </div>
              </td>
              <td>
                <span class="sdb-course-name" title="{{ $s->occupation }}">{{ $s->occupation ?: '—' }}</span>
              </td>
              <td>
                <span class="sdb-batch-badge">{{ $s->batch_no ?: '—' }}</span>
              </td>
              <td>
                <span class="sdb-year-badge">{{ $s->academic_year }}</span>
              </td>
              <td>
                <span class="sdb-gender">{{ $s->gender ?: '—' }}</span>
              </td>
              <td>
                @if(strtolower($s->ethnic) === 'yes')
                  <span class="sdb-cat-badge badge-ethnic">Ethnic</span>
                @elseif(strtolower($s->pwd) === 'yes')
                  <span class="sdb-cat-badge badge-pwd">PWD</span>
                @else
                  <span class="sdb-cat-dash">—</span>
                @endif
              </td>
              <td>
                @php
                  $st = strtolower($s->training_status ?: 'active');
                @endphp
                @if($st === 'completed')
                  <span class="sdb-status-pill st-completed">Completed</span>
                @elseif($st === 'dropped')
                  <span class="sdb-status-pill st-dropped">Dropped</span>
                @else
                  <span class="sdb-status-pill st-active">Active</span>
                @endif
              </td>
              <td style="text-align: right;">
                <div class="sdb-row-actions">
                  <a href="{{ route('admin.students.show', $s) }}" class="sdb-action-btn" title="View Profile">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                      <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                  </a>
                  <a href="{{ route('admin.students.edit', $s) }}" class="sdb-action-btn" title="Edit Student">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" style="text-align: center; padding: 40px 10px; color: #64748b;">
                <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin-bottom: 8px;">
                  <circle cx="11" cy="11" r="8"></circle>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <p style="font-weight: 600; margin: 0 0 4px; color: #334155;">No student records found</p>
                <small style="color: #94a3b8;">Try adjusting your search criteria or filters</small>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Table Footer: Rows per page, Showing Range, Bootstrap Pagination --}}
    <div class="sdb-table-footer">
      <div class="sdb-footer-left">
        <label for="perPageSelect" class="sdb-footer-label">Rows per page</label>
        <select id="perPageSelect" class="sdb-footer-select" onchange="changePerPage(this.value)">
          <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
          <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
          <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
          <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
        </select>
      </div>

      <div class="sdb-footer-range">
        Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ number_format($students->total()) }}
      </div>

      <div class="sdb-footer-pagination">
        {{ $students->links('admin.students.pagination') }}
      </div>
    </div>
  </div>
</div>

<script>
function toggleAdvancedFilters() {
  var drawer = document.getElementById('sdbAdvancedFiltersDrawer');
  if (drawer) {
    var isHidden = drawer.style.display === 'none';
    drawer.style.display = isHidden ? 'block' : 'none';
  }
}

function changePerPage(val) {
  var url = new URL(window.location.href);
  url.searchParams.set('per_page', val);
  url.searchParams.set('page', 1);
  window.location.href = url.toString();
}

function toggleSelectAll(master) {
  var checks = document.querySelectorAll('.student-row-check');
  checks.forEach(function(c) {
    c.checked = master.checked;
  });
  updateSelectedCount();
}

function updateSelectedCount() {
  var checks = document.querySelectorAll('.student-row-check:checked');
  var box = document.getElementById('bulkActionsBox');
  var txt = document.getElementById('selectedCountText');
  if (box && txt) {
    if (checks.length > 0) {
      box.style.display = 'flex';
      txt.textContent = checks.length + ' selected';
    } else {
      box.style.display = 'none';
    }
  }
}
</script>

<style>
/* Student Database Page Styles (Forest Green Palette) */
.sdb-wrapper {
  max-width: 1560px;
  margin: 0 auto;
}

.sdb-page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  gap: 16px;
  flex-wrap: wrap;
}

.sdb-header-left {
  display: flex;
  align-items: center;
  gap: 24px;
  flex-wrap: wrap;
}

.sdb-title {
  font-size: 26px;
  font-weight: 700;
  color: #1f2937;
  letter-spacing: -0.4px;
  margin: 0;
}

/* Compact Stats */
.sdb-compact-stats {
  display: inline-flex;
  align-items: center;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 6px 14px;
  font-size: 13px;
  color: #475569;
  box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}

.c-stat-item b {
  color: #1f2937;
  font-weight: 700;
  margin-right: 2px;
}

.c-stat-sep {
  color: #cbd5e1;
  margin: 0 10px;
}

/* Header Action Buttons */
.sdb-header-actions {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.sdb-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 8px 14px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  border: 1px solid transparent;
  transition: all 0.18s ease;
}

.sdb-btn-outline {
  background: #ffffff;
  border-color: #cbd5e1;
  color: #334155;
  box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}

.sdb-btn-outline:hover {
  background: #f8fafc;
  border-color: #047857;
  color: #047857;
}

.sdb-btn-primary {
  background: #047857;
  color: #ffffff;
  box-shadow: 0 1px 3px rgba(4, 120, 87, 0.25);
}

.sdb-btn-primary:hover {
  background: #065f46;
}

.sdb-btn-sm {
  padding: 6px 12px;
  font-size: 12px;
}

/* Filter Card */
.sdb-filters-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 14px 18px;
  margin-bottom: 20px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.sdb-basic-filter-row {
  display: flex;
  align-items: center;
  gap: 14px;
  flex-wrap: wrap;
}

.sdb-filter-item {
  display: flex;
  align-items: center;
  gap: 6px;
}

.sdb-label {
  font-size: 13px;
  font-weight: 600;
  color: #475569;
  white-space: nowrap;
}

.sdb-select {
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  padding: 7px 10px;
  font-size: 13px;
  color: #1f2937;
  background: #ffffff;
  outline: none;
  min-width: 90px;
}

.sdb-select:focus {
  border-color: #047857;
  box-shadow: 0 0 0 2px rgba(4, 120, 87, 0.12);
}

.sdb-filter-search {
  position: relative;
  display: flex;
  align-items: center;
  flex: 1;
  min-width: 220px;
}

.sdb-search-icon {
  position: absolute;
  left: 10px;
  color: #94a3b8;
  pointer-events: none;
}

.sdb-search-input {
  width: 100%;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  padding: 7px 28px 7px 32px;
  font-size: 13px;
  color: #1f2937;
  outline: none;
  transition: border-color 0.15s;
}

.sdb-search-input:focus {
  border-color: #047857;
  box-shadow: 0 0 0 2px rgba(4, 120, 87, 0.12);
}

.sdb-clear-search {
  position: absolute;
  right: 10px;
  color: #94a3b8;
  font-size: 16px;
  text-decoration: none;
  font-weight: 700;
}

.sdb-adv-toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  padding: 7px 12px;
  font-size: 13px;
  font-weight: 600;
  color: #334155;
  cursor: pointer;
  transition: all 0.15s;
}

.sdb-adv-toggle-btn:hover,
.sdb-adv-toggle-btn.adv-active {
  background: #ecfdf5;
  border-color: #047857;
  color: #047857;
}

.sdb-reset-btn {
  font-size: 12.5px;
  font-weight: 600;
  color: #dc2626;
  text-decoration: none;
  padding: 6px 8px;
}

/* Advanced Drawer */
.sdb-adv-drawer {
  margin-top: 14px;
  padding-top: 14px;
  border-top: 1px solid #e2e8f0;
}

.sdb-adv-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 14px;
  margin-bottom: 14px;
}

.sdb-adv-col {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.sdb-adv-col label {
  font-size: 11.5px;
  font-weight: 600;
  color: #64748b;
  text-transform: uppercase;
}

.sdb-adv-col select {
  width: 100%;
}

.sdb-adv-actions {
  display: flex;
  align-items: center;
  gap: 12px;
  justify-content: flex-end;
}

.sdb-reset-link {
  font-size: 12px;
  color: #64748b;
  text-decoration: none;
}

/* Table Card */
.sdb-table-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.03);
  overflow: hidden;
}

.sdb-table-header {
  padding: 16px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid #e2e8f0;
}

.sdb-header-title-box {
  display: flex;
  align-items: center;
  gap: 10px;
}

.sdb-table-title {
  font-size: 16px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.sdb-count-badge {
  font-size: 12px;
  color: #64748b;
}

/* Table */
.sdb-table-responsive {
  overflow-x: auto;
}

.sdb-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

.sdb-table th {
  background: #f8fafc;
  color: #64748b;
  font-size: 11.5px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.4px;
  padding: 11px 16px;
  border-bottom: 1px solid #e2e8f0;
  white-space: nowrap;
}

.sdb-table td {
  padding: 12px 16px;
  border-bottom: 1px solid #f1f5f9;
  font-size: 13px;
  color: #334155;
  vertical-align: middle;
}

.sdb-table tbody tr:hover {
  background: #f8fafc;
}

.sdb-id-link {
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
  font-size: 13px;
  font-weight: 700;
  color: #047857;
  text-decoration: none;
}

.sdb-id-link:hover {
  text-decoration: underline;
}

.sdb-name-cell {
  display: flex;
  align-items: center;
  gap: 11px;
}

.sdb-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 12px;
  flex-shrink: 0;
  user-select: none;
}

.sdb-name-meta {
  display: flex;
  flex-direction: column;
}

.sdb-student-name {
  color: #1f2937;
  font-weight: 600;
  text-decoration: none;
}

.sdb-student-name:hover {
  color: #047857;
}

.sdb-student-sub {
  font-size: 11.5px;
  color: #94a3b8;
}

.sdb-course-name {
  max-width: 180px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  display: block;
}

.sdb-batch-badge, .sdb-year-badge {
  font-size: 12px;
  color: #475569;
}

.sdb-cat-badge {
  display: inline-block;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
}

.badge-ethnic { background: #dcfce7; color: #15803d; }
.badge-pwd { background: #ede9fe; color: #6d28d9; }
.sdb-cat-dash { color: #94a3b8; }

.sdb-status-pill {
  display: inline-block;
  padding: 3px 9px;
  border-radius: 14px;
  font-size: 11.5px;
  font-weight: 600;
}

.st-active { background: #d1fae5; color: #047857; }
.st-completed { background: #dbeafe; color: #1d4ed8; }
.st-dropped { background: #fee2e2; color: #b91c1c; }

.sdb-row-actions {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 6px;
}

.sdb-action-btn {
  width: 28px;
  height: 28px;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #64748b;
  text-decoration: none;
  transition: all 0.15s;
}

.sdb-action-btn:hover {
  background: #f1f5f9;
  color: #047857;
}

/* Footer & Pagination */
.sdb-table-footer {
  padding: 14px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid #e2e8f0;
  gap: 16px;
  flex-wrap: wrap;
}

.sdb-footer-left {
  display: flex;
  align-items: center;
  gap: 8px;
}

.sdb-footer-label {
  font-size: 12.5px;
  color: #64748b;
}

.sdb-footer-select {
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  padding: 4px 8px;
  font-size: 12px;
  color: #1f2937;
  background: #ffffff;
  outline: none;
}

.sdb-footer-range {
  font-size: 13px;
  color: #64748b;
}

.sdb-footer-pagination {
  display: flex;
  align-items: center;
}

@media (max-width: 900px) {
  .sdb-adv-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .sdb-adv-grid {
    grid-template-columns: 1fr;
  }
  .sdb-header-actions {
    width: 100%;
    justify-content: flex-start;
  }
  .sdb-compact-stats {
    width: 100%;
    overflow-x: auto;
  }
}
</style>
@endsection
