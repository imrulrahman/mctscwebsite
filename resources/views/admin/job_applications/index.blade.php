@extends('admin.layout')
@section('title', 'Job Applications · MCTSC Admin')
@section('crumb', 'Job Applications')

@section('content')
<div class="page-head">
  <div>
    <h1>Job Applications (চাকরির আবেদনসমূহ)</h1>
    <p>Review online career applications, download CVs, shortlist candidates, and print applicant copies</p>
  </div>
  <a class="btn light" href="{{ route('admin.content.index', 'career') }}">Manage Job Circulars →</a>
</div>

<div class="panel" style="margin-bottom:20px;padding:16px;">
  <form method="get" action="{{ route('admin.job-applications.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
    <div style="flex:1;min-width:220px;">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search tracking ID, applicant name, post, mobile..." class="filter-input" style="padding:9px 12px;width:100%;">
    </div>
    <div style="min-width:160px;">
      <select name="status" class="filter-input" style="padding:9px 12px;width:100%;">
        <option value="">— All Statuses —</option>
        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
        <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
        <option value="interview" {{ request('status') === 'interview' ? 'selected' : '' }}>Interview</option>
        <option value="hired" {{ request('status') === 'hired' ? 'selected' : '' }}>Hired</option>
        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
      </select>
    </div>
    <div style="display:flex;gap:8px;">
      <button type="submit" class="btn light">Filter</button>
      @if(request()->hasAny(['q', 'status']))
        <a href="{{ route('admin.job-applications.index') }}" class="btn light" style="color:#888;">Reset</a>
      @endif
    </div>
  </form>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Received Applications ({{ $applications->total() }})</h2>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Tracking ID</th>
          <th>Applicant</th>
          <th>Applied Post</th>
          <th>Contact</th>
          <th>District</th>
          <th>Status</th>
          <th>Applied On</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($applications as $app)
          <tr>
            <td><strong style="color:#0f5132;">{{ $app->tracking_no }}</strong></td>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                @if($app->photo_path)
                  <img src="{{ storage_asset($app->photo_path) }}" alt="" style="width:36px;height:36px;border-radius:4px;object-fit:cover;border:1px solid #cbd5e1;">
                @endif
                <div>
                  <strong style="display:block;font-size:13.5px;">{{ $app->applicant_name }}</strong>
                  <small style="color:#64748b;">{{ $app->gender }} · {{ $app->dob?->age }} yrs</small>
                </div>
              </div>
            </td>
            <td><strong>{{ $app->post_name }}</strong></td>
            <td>
              <div>{{ $app->mobile_no }}</div>
              <small style="color:#64748b;">{{ $app->email }}</small>
            </td>
            <td>
              <div>{{ $app->present_address['district'] ?? '—' }}</div>
            </td>
            <td>
              @php
                $stColor = match($app->status) {
                  'shortlisted' => 'background:#e0f2fe;color:#0369a1;',
                  'interview' => 'background:#ede9fe;color:#6d28d9;',
                  'hired' => 'background:#dcfce7;color:#15803d;',
                  'rejected' => 'background:#fee2e2;color:#991b1b;',
                  default => 'background:#fef3c7;color:#92400e;',
                };
              @endphp
              <span class="badge" style="{{ $stColor }}font-weight:600;">{{ ucfirst($app->status) }}</span>
            </td>
            <td style="white-space:nowrap;font-size:12px;color:#64748b;">
              {{ $app->created_at->format('d/m/Y') }}<br>
              {{ $app->created_at->format('h:i A') }}
            </td>
            <td style="text-align:right;">
              <div class="actions" style="justify-content:flex-end;gap:8px;">
                <a class="edit-link" href="{{ route('admin.job-applications.show', $app) }}">View Details</a>
                <form method="post" action="{{ route('admin.job-applications.destroy', $app) }}" onsubmit="return confirm('Delete this job application?');">
                  @csrf
                  @method('delete')
                  <button class="delete-link">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="empty">No job applications received yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($applications->hasPages())
    <div class="pagination" style="padding:16px;">{{ $applications->links() }}</div>
  @endif
</div>
@endsection
