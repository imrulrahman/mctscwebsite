@extends('admin.layout')

@section('title', 'Certificate Management · Student Management')
@section('crumb', 'Student Management / Certificate Management' . ($viewMode === 'issue' ? ' / Issue Certificate' : ($viewMode === 'issued' ? ' / Issued Certificates' : '')))

@section('content')
<div class="sm-page-container">
  {{-- Header --}}
  <div class="sm-header-row">
    <div>
      <h1 class="sm-title">Certificate Management</h1>
      <p class="sm-subtitle">Manage institutional course certificates, issuance workflow, printing, and graduate distribution</p>
    </div>
    <div style="display:flex;gap:10px;">
      @if($viewMode !== 'cards')
        <a href="{{ route('admin.students.certificates') }}" class="sm-btn sm-btn-secondary">
          &larr; Back to Certificate Overview
        </a>
      @endif
      <a href="{{ route('admin.students.database') }}" class="sm-btn sm-btn-outline">
        Student Database &rarr;
      </a>
    </div>
  </div>

  {{-- Metrics Counters Bar --}}
  <div class="sm-stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 24px;">
    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#ecfdf5;color:#047857;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <circle cx="12" cy="8" r="7"></circle>
          <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Total Issued</span>
        <b class="sm-stat-value">{{ number_format($totalIssued) }}</b>
      </div>
    </div>

    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#eff6ff;color:#2563eb;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <polyline points="6 9 6 2 18 2 18 9"></polyline>
          <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
          <rect x="6" y="14" width="12" height="8"></rect>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Printed</span>
        <b class="sm-stat-value">{{ number_format($totalPrinted) }}</b>
      </div>
    </div>

    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#f0fdf4;color:#16a34a;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Distributed to Trainees</span>
        <b class="sm-stat-value">{{ number_format($totalDistributed) }}</b>
      </div>
    </div>

    <div class="sm-stat-card">
      <div class="sm-stat-icon-wrap" style="background:#fef3c7;color:#b45309;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
      </div>
      <div class="sm-stat-content">
        <span class="sm-stat-label">Not Issued / Pending</span>
        <b class="sm-stat-value">{{ number_format($totalNotIssued) }}</b>
      </div>
    </div>
  </div>

  {{-- VIEW 1: LANDING CARD VIEW (NO STUDENT LIST DEFAULT) --}}
  @if($viewMode === 'cards')
    <div class="cert-cards-container">
      {{-- Card 1: Issue Certificate --}}
      <a href="{{ route('admin.students.certificates', ['view' => 'issue']) }}" class="cert-choice-card highlight-card">
        <div class="cert-card-icon-box">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            <line x1="11" y1="8" x2="11" y2="14"></line>
            <line x1="8" y1="11" x2="14" y2="11"></line>
          </svg>
        </div>
        <div class="cert-card-info">
          <h2 class="cert-card-title">Issue Certificate</h2>
          <p class="cert-card-desc">Search trainee by Student ID, Name, Mobile number, or select a Batch to verify completion and issue or re-issue certificates.</p>
          <span class="cert-card-action-btn">Search & Issue Certificate &rarr;</span>
        </div>
      </a>

      {{-- Card 2: Issued Certificates --}}
      <a href="{{ route('admin.students.certificates', ['view' => 'issued']) }}" class="cert-choice-card">
        <div class="cert-card-icon-box icon-box-issued">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="8" r="7"></circle>
            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
          </svg>
        </div>
        <div class="cert-card-info">
          <h2 class="cert-card-title">Issued Certificates</h2>
          <p class="cert-card-desc">View all issued, printed and distributed certificates. Track action dates, print official certificates and manage re-issuance.</p>
          <span class="cert-card-action-btn" style="background:#f1f5f9;color:#334155;">View Issued Certificates ({{ number_format($totalIssued) }}) &rarr;</span>
        </div>
      </a>
    </div>

  {{-- VIEW 2: ISSUE CERTIFICATE (SEARCH BY ID / NAME / MOBILE / BATCH) --}}
  @elseif($viewMode === 'issue')
    <div class="cert-search-card">
      <div class="cert-search-header">
        <h2 style="font-size:18px;font-weight:700;color:#1f2937;margin:0 0 4px;">Search Trainee to Issue Certificate</h2>
        <p style="font-size:13px;color:#64748b;margin:0;">Enter trainee Student ID, Name, Mobile number, or choose a Batch to find records.</p>
      </div>

      <form method="GET" action="{{ route('admin.students.certificates') }}" class="cert-search-form">
        <input type="hidden" name="view" value="issue">

        <div class="cert-search-input-group">
          <div style="flex: 2; position: relative;">
            <svg style="position:absolute;left:12px;top:12px;color:#94a3b8;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search Student ID (e.g. C261001), Name, or Mobile..." class="sm-input" style="padding-left:36px;width:100%;">
          </div>

          <div style="flex: 1; min-width: 160px;">
            <select name="batch" class="sm-input" style="width:100%;">
              <option value="all">All Batches</option>
              @foreach($batches as $b)
                <option value="{{ $b }}" {{ request('batch') == $b ? 'selected' : '' }}>{{ $b }}</option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="sm-btn sm-btn-primary" style="padding:10px 20px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <span>Search</span>
          </button>

          @if(request()->filled('q') || request()->filled('batch'))
            <a href="{{ route('admin.students.certificates', ['view' => 'issue']) }}" class="sm-btn sm-btn-secondary">Clear</a>
          @endif
        </div>
      </form>
    </div>

    {{-- Search Results --}}
    @if($hasSearched)
      <div class="sdb-table-card" style="margin-top:20px;">
        <div class="sdb-table-header">
          <h2 class="sdb-table-title">Matching Trainees ({{ $searchResults->total() }})</h2>
        </div>

        <div class="sdb-table-responsive">
          <table class="sdb-table">
            <thead>
              <tr>
                <th>Student ID</th>
                <th>Trainee Name</th>
                <th>Occupation / Trade</th>
                <th>Batch</th>
                <th>Academic Year</th>
                <th>Phone</th>
                <th>Marks / Grade</th>
                <th>Certificate Status</th>
                <th style="text-align:right;width:240px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($searchResults as $s)
                @php
                  $normStatus = $s->certificate_normalized_status;
                  $actDate = $s->certificate_action_date;
                @endphp
                <tr>
                  <td>
                    <a href="{{ route('admin.students.show', $s) }}" class="sdb-id-link">{{ $s->student_id }}</a>
                  </td>
                  <td>
                    <b>{{ $s->student_name }}</b>
                    @if($s->student_name_bn)
                      <span style="display:block;font-size:11px;color:#64748b;">{{ $s->student_name_bn }}</span>
                    @endif
                  </td>
                  <td>{{ $s->occupation }}</td>
                  <td>{{ $s->batch_no ?: '—' }}</td>
                  <td>{{ $s->academic_year }} · {{ $s->session ?: 'Session 1' }}</td>
                  <td>{{ $s->phone ?: '—' }}</td>
                  <td>
                    <b>{{ $s->total_marks !== null ? $s->total_marks : '—' }}</b>
                    @if($s->grade)
                      <span class="sm-badge" style="background:#ede9fe;color:#6d28d9;margin-left:4px;">{{ $s->grade }}</span>
                    @endif
                  </td>
                  <td>
                    @if($normStatus === 'Not Issued')
                      <span class="sm-badge st-not-issued">Not Issued</span>
                    @elseif($normStatus === 'Issued')
                      <span class="sm-badge st-issued">Issued {{ $actDate ? "({$actDate})" : '' }}</span>
                    @elseif($normStatus === 'Printed')
                      <span class="sm-badge st-printed">Printed {{ $actDate ? "({$actDate})" : '' }}</span>
                    @elseif($normStatus === 'Distributed')
                      <span class="sm-badge st-distributed">Distributed {{ $actDate ? "({$actDate})" : '' }}</span>
                    @endif

                    @if($s->certificate_reissue_count > 0)
                      <span style="display:block;font-size:10.5px;color:#b45309;font-weight:600;margin-top:2px;">Re-issued {{ $s->certificate_reissue_count }}x</span>
                    @endif
                  </td>
                  <td style="text-align:right;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:wrap;">
                      @if($normStatus === 'Not Issued')
                        {{-- Issue Certificate Button --}}
                        <form method="POST" action="{{ route('admin.students.certificates.issue', $s) }}" style="display:inline;">
                          @csrf
                          <button type="submit" class="sm-btn sm-btn-primary" style="padding:5px 12px;font-size:12px;">
                            Issue Certificate
                          </button>
                        </form>
                      @else
                        {{-- Print Official Certificate --}}
                        <a href="{{ route('admin.students.certificates.print', $s) }}" target="_blank" class="sm-btn sm-btn-outline" style="padding:5px 10px;font-size:12px;" title="Print Official Certificate">
                          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                          <span>Print</span>
                        </a>

                        {{-- Re-Issue Certificate Option --}}
                        <form method="POST" action="{{ route('admin.students.certificates.reissue', $s) }}" onsubmit="return confirm('Re-issue certificate for {{ $s->student_name }} ({{ $s->student_id }})? This will update the certificate issue date and increment the re-issue counter.');" style="display:inline;">
                          @csrf
                          <button type="submit" class="sm-btn sm-btn-amber" style="padding:5px 10px;font-size:12px;" title="Re-issue certificate">
                            Re-Issue
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" style="text-align:center;padding:35px;color:#64748b;">
                    No trainees found matching your search. Please check the Student ID, Name or Batch.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($searchResults->hasPages())
          <div class="sdb-table-footer">
            <div>Showing {{ $searchResults->firstItem() }}–{{ $searchResults->lastItem() }} of {{ $searchResults->total() }}</div>
            <div>{{ $searchResults->links('admin.students.pagination') }}</div>
          </div>
        @endif
      </div>
    @else
      <div style="background:#fff;border:1px dashed #cbd5e1;border-radius:12px;padding:50px 20px;text-align:center;margin-top:20px;color:#64748b;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" style="margin-bottom:12px;">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <h3 style="font-size:16px;font-weight:700;color:#1f2937;margin:0 0 4px;">Search Trainee to Issue Certificate</h3>
        <p style="font-size:13px;max-width:480px;margin:0 auto;">Enter a Student ID (e.g. <code>C261001</code>), Trainee Name, Phone number or select a Batch from the search box above to begin.</p>
      </div>
    @endif

  {{-- VIEW 3: ISSUED CERTIFICATES LIST --}}
  @elseif($viewMode === 'issued')
    {{-- Filter bar for Issued Certificates --}}
    <form method="GET" action="{{ route('admin.students.certificates') }}" class="sdb-filters-card" style="margin-bottom:20px;">
      <input type="hidden" name="view" value="issued">
      <div class="sdb-basic-filter-row">
        <div class="sdb-filter-item">
          <label class="sdb-label">Status:</label>
          <select name="status" class="sdb-select" onchange="this.form.submit()">
            <option value="all">All (Issued / Printed / Distributed)</option>
            <option value="Issued" {{ request('status') == 'Issued' ? 'selected' : '' }}>Issued</option>
            <option value="Printed" {{ request('status') == 'Printed' ? 'selected' : '' }}>Printed</option>
            <option value="Distributed" {{ request('status') == 'Distributed' ? 'selected' : '' }}>Distributed</option>
          </select>
        </div>

        <div class="sdb-filter-item">
          <label class="sdb-label">Batch:</label>
          <select name="batch" class="sdb-select" onchange="this.form.submit()">
            <option value="all">All Batches</option>
            @foreach($batches as $b)
              <option value="{{ $b }}" {{ request('batch') == $b ? 'selected' : '' }}>{{ $b }}</option>
            @endforeach
          </select>
        </div>

        <div class="sdb-filter-item">
          <label class="sdb-label">Year:</label>
          <select name="year" class="sdb-select" onchange="this.form.submit()">
            <option value="all">All Years</option>
            @foreach($years as $yr)
              <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
            @endforeach
          </select>
        </div>

        <div class="sdb-filter-search">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, ID, phone..." class="sdb-search-input">
        </div>

        <button type="submit" class="sm-btn sm-btn-primary" style="padding:7px 14px;">Filter</button>
        @if(request()->hasAny(['status', 'batch', 'year', 'search']))
          <a href="{{ route('admin.students.certificates', ['view' => 'issued']) }}" class="sm-btn sm-btn-secondary" style="padding:7px 12px;">Reset</a>
        @endif
      </div>
    </form>

    {{-- Issued Certificates Table --}}
    <div class="sdb-table-card">
      <div class="sdb-table-header">
        <h2 class="sdb-table-title">Issued Certificates Directory ({{ number_format($issuedStudents->total()) }})</h2>
      </div>

      <div class="sdb-table-responsive">
        <table class="sdb-table">
          <thead>
            <tr>
              <th>Student ID</th>
              <th>Trainee Name</th>
              <th>Occupation</th>
              <th>Batch / Year</th>
              <th>Status & Action Date</th>
              <th>Certificate No</th>
              <th style="text-align:right;width:280px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($issuedStudents as $s)
              @php
                $normStatus = $s->certificate_normalized_status;
                $actDate = $s->certificate_action_date;
              @endphp
              <tr>
                <td>
                  <a href="{{ route('admin.students.show', $s) }}" class="sdb-id-link">{{ $s->student_id }}</a>
                </td>
                <td>
                  <b>{{ $s->student_name }}</b>
                </td>
                <td>{{ $s->occupation }}</td>
                <td>{{ $s->batch_no ?: '—' }} ({{ $s->academic_year }})</td>
                <td>
                  @if($normStatus === 'Issued')
                    <span class="sm-badge st-issued">Issued on {{ $actDate ?: '—' }}</span>
                  @elseif($normStatus === 'Printed')
                    <span class="sm-badge st-printed">Printed on {{ $actDate ?: '—' }}</span>
                  @elseif($normStatus === 'Distributed')
                    <span class="sm-badge st-distributed">Distributed on {{ $actDate ?: '—' }}</span>
                  @endif

                  @if($s->certificate_reissue_count > 0)
                    <span style="display:block;font-size:10.5px;color:#b45309;font-weight:600;margin-top:2px;">Re-issued {{ $s->certificate_reissue_count }}x</span>
                  @endif
                </td>
                <td>
                  <code style="font-size:12px;color:#047857;">{{ $s->certificate_no ?: ('MCTSC-' . $s->student_id) }}</code>
                </td>
                <td style="text-align:right;">
                  <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;flex-wrap:wrap;">
                    {{-- Print Certificate --}}
                    <a href="{{ route('admin.students.certificates.print', $s) }}" target="_blank" class="sm-btn sm-btn-outline" style="padding:4px 8px;font-size:12px;">
                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                      <span>Print</span>
                    </a>

                    {{-- Re-Issue --}}
                    <form method="POST" action="{{ route('admin.students.certificates.reissue', $s) }}" onsubmit="return confirm('Re-issue certificate for {{ $s->student_name }} ({{ $s->student_id }})?');" style="display:inline;">
                      @csrf
                      <button type="submit" class="sm-btn sm-btn-amber" style="padding:4px 8px;font-size:12px;">Re-Issue</button>
                    </form>

                    {{-- Status Dropdown Update --}}
                    <form method="POST" action="{{ route('admin.students.certificates.update_status', $s) }}" style="display:inline;">
                      @csrf
                      <select name="status" class="sm-status-select" onchange="this.form.submit()" title="Change certificate stage">
                        <option value="Issued" {{ $normStatus === 'Issued' ? 'selected' : '' }}>Mark Issued</option>
                        <option value="Printed" {{ $normStatus === 'Printed' ? 'selected' : '' }}>Mark Printed</option>
                        <option value="Distributed" {{ $normStatus === 'Distributed' ? 'selected' : '' }}>Mark Distributed</option>
                        <option value="Not Issued">Mark Not Issued</option>
                      </select>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align:center;padding:35px;color:#64748b;">
                  No issued certificates found matching criteria.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="sdb-table-footer">
        <div>Showing {{ $issuedStudents->firstItem() ?? 0 }}–{{ $issuedStudents->lastItem() ?? 0 }} of {{ number_format($issuedStudents->total()) }}</div>
        <div>{{ $issuedStudents->links('admin.students.pagination') }}</div>
      </div>
    </div>
  @endif
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

/* 2 Big Choice Cards */
.cert-cards-container {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 24px;
  margin-top: 10px;
}

.cert-choice-card {
  background: #ffffff;
  border: 1.5px solid #e2e8f0;
  border-radius: 16px;
  padding: 32px 28px;
  display: flex;
  align-items: flex-start;
  gap: 22px;
  text-decoration: none;
  box-shadow: 0 2px 6px rgba(0,0,0,0.03);
  transition: all 0.22s ease;
}

.cert-choice-card:hover {
  transform: translateY(-3px);
  border-color: #047857;
  box-shadow: 0 10px 25px rgba(6, 78, 59, 0.1);
}

.cert-choice-card.highlight-card {
  border-color: #10b981;
  background: linear-gradient(135deg, #ffffff 65%, #ecfdf5 100%);
}

.cert-card-icon-box {
  width: 68px;
  height: 68px;
  border-radius: 16px;
  background: #ecfdf5;
  color: #047857;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: background 0.2s;
}

.icon-box-issued {
  background: #eff6ff;
  color: #2563eb;
}

.cert-choice-card:hover .cert-card-icon-box {
  background: #047857;
  color: #ffffff;
}

.cert-choice-card:hover .icon-box-issued {
  background: #2563eb;
  color: #ffffff;
}

.cert-card-info {
  flex: 1;
}

.cert-card-title {
  font-size: 20px;
  font-weight: 800;
  color: #1f2937;
  margin: 0 0 8px;
}

.cert-card-desc {
  font-size: 14px;
  color: #64748b;
  line-height: 1.5;
  margin: 0 0 16px;
}

.cert-card-action-btn {
  display: inline-block;
  padding: 8px 16px;
  background: #047857;
  color: #ffffff;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 700;
}

/* Search Card in Issue mode */
.cert-search-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}

.cert-search-header {
  margin-bottom: 16px;
}

.cert-search-input-group {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

/* Status Badges */
.sm-badge {
  display: inline-block;
  padding: 3px 9px;
  border-radius: 14px;
  font-size: 11.5px;
  font-weight: 600;
}

.st-not-issued { background: #f1f5f9; color: #475569; }
.st-issued { background: #d1fae5; color: #047857; }
.st-printed { background: #e0f2fe; color: #0369a1; }
.st-distributed { background: #dcfce7; color: #15803d; }

.sm-status-select {
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  padding: 4px 6px;
  font-size: 11.5px;
  background: #ffffff;
  color: #334155;
  cursor: pointer;
  outline: none;
}

.sm-status-select:focus {
  border-color: #047857;
}

.sm-btn-amber {
  background: #fef3c7;
  border: 1px solid #fde68a;
  color: #92400e;
}
.sm-btn-amber:hover {
  background: #fde68a;
}

/* Tables */
.sdb-table-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
.sdb-table-header { padding:16px 20px; border-bottom:1px solid #e2e8f0; }
.sdb-table-title { font-size:16px; font-weight:700; color:#1f2937; margin:0; }
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

@media (max-width: 900px) {
  .cert-cards-container { grid-template-columns: 1fr; }
  .sm-stats-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endsection
