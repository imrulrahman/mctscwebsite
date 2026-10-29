@extends('admin.layout')
@section('title','Dashboard')
@section('crumb','Dashboard')

@section('content')
<div class="page-head">
  <div>
    <h1>Dashboard</h1>
    <p>Manage your college website content</p>
  </div>
  <a class="btn gold" href="{{ route('admin.content.create','courses') }}">+ New Content</a>
</div>

<div class="stats-grid">
  <a class="stat-card" href="{{ route('admin.applications') }}" style="border-left: 4px solid #0f5132;">
    <i style="color: #0f5132;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
    </i>
    <div>
      <span>Trainee Admissions</span>
      <b>{{ $totalApplicationCount }}</b>
      @if($pendingApplicationCount > 0)
        <small style="background:#fef3c7;color:#92400e;padding:2px 6px;border-radius:4px;font-weight:700;font-size:11px;margin-top:2px;display:inline-block;">{{ $pendingApplicationCount }} New Pending</small>
      @endif
    </div>
  </a>

  <a class="stat-card" href="{{ route('admin.content.index','courses') }}">
    <i>
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
    </i>
    <div>
      <span>Published Courses</span>
      <b>{{ $counts['courses'] }}</b>
    </div>
  </a>

  <a class="stat-card" href="{{ route('admin.content.index','projects') }}">
    <i>
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
    </i>
    <div>
      <span>Active Projects</span>
      <b>{{ $counts['projects'] }}</b>
    </div>
  </a>

  <a class="stat-card" href="{{ route('admin.content.index','notices') }}">
    <i>
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
    </i>
    <div>
      <span>Notices</span>
      <b>{{ $counts['notices'] }}</b>
    </div>
  </a>
</div>

{{-- Top Alert for Pending Admissions --}}
@if($pendingApplicationCount > 0)
  <div style="background:#ecfdf5;border:1.5px solid #10b981;border-radius:8px;padding:12px 18px;margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
    <div style="display:flex;align-items:center;gap:10px;">
      <span style="font-size:22px;">📢</span>
      <div>
        <strong style="color:#065f46;font-size:14px;">নতুন {{ $pendingApplicationCount }}টি শিক্ষার্থী ভর্তি আবেদন অনুমোদনের অপেক্ষায় রয়েছে!</strong>
        <p style="color:#047857;margin:0;font-size:12.5px;">শিক্ষার্থীর অফিসিয়াল ভর্তি ফরম ও ভাউচার জেনারেট করতে আবেদনটি অনুমোদন (Approve) করুন।</p>
      </div>
    </div>
    <a href="{{ route('admin.applications', ['status' => 'new']) }}" class="btn gold" style="font-size:12.5px;padding:6px 14px;">View Pending Applications &rarr;</a>
  </div>
@endif

<div class="dash-grid">
  <section class="panel" style="grid-column: 1 / -1;">
    <div class="panel-head">
      <div>
        <h2 style="display:flex;align-items:center;gap:8px;">
          <span>Recent Student Applications (সর্বশেষ ভর্তি আবেদন)</span>
          @if($pendingApplicationCount > 0)
            <span style="background:#fef3c7;color:#92400e;font-size:11.5px;padding:2px 8px;border-radius:12px;font-weight:700;">{{ $pendingApplicationCount }} Pending</span>
          @endif
        </h2>
        <p style="font-size:12px;color:#64748b;margin:2px 0 0 0;">Review, approve and print student applications</p>
      </div>
      <a href="{{ route('admin.applications') }}">View All Applications →</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Tracking ID</th>
            <th>Applicant Name</th>
            <th>Course & Shift</th>
            <th>Project / Batch</th>
            <th>Fee & Discount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentApplications as $app)
            <tr>
              <td><strong style="color:#0f5132;">{{ $app->tracking_no }}</strong></td>
              <td>
                <strong>{{ $app->trainee_name_en ?: $app->name }}</strong>
                @if($app->trainee_name_bn)
                  <small class="subline" style="color:#64748b;">{{ $app->trainee_name_bn }}</small>
                @endif
                <small class="subline" style="color:#0f5132;font-weight:600;">📞 {{ $app->contact_no ?: $app->phone }}</small>
              </td>
              <td>
                <strong>{{ $app->course }}</strong>
                @if($app->preferred_shift)
                  <small class="subline" style="color:#64748b;">{{ $app->preferred_shift }}</small>
                @endif
              </td>
              <td>
                <span style="display:inline-block;background:#f1f5f9;border:1px solid #cbd5e1;padding:2px 8px;border-radius:4px;font-weight:600;font-size:12px;color:#0f172a;">
                  {{ $app->project_name ?: '— (অনির্ধারিত)' }}
                </span>
              </td>
              <td>
                <div>
                  <strong style="color:{{ $app->isFreeProject() ? '#059669' : '#0f172a' }};">
                    {{ $app->isFreeProject() ? 'Free (৳ 0)' : ('৳ ' . number_format($app->payable_amount, 0)) }}
                  </strong>
                  @if((float)$app->discount > 0)
                    <small class="subline" style="color:#16a34a;">ছাড়: ৳ {{ number_format($app->discount, 0) }}</small>
                  @endif
                </div>
              </td>
              <td>
                @if($app->status === 'accepted')
                  <span style="background:#d1fae5;color:#065f46;padding:3px 8px;border-radius:4px;font-weight:700;font-size:11.5px;display:inline-flex;align-items:center;gap:4px;">
                    ✔ Approved
                  </span>
                @elseif($app->status === 'rejected')
                  <span style="background:#fee2e2;color:#991b1b;padding:3px 8px;border-radius:4px;font-weight:700;font-size:11.5px;">Rejected</span>
                @else
                  <span style="background:#fef3c7;color:#92400e;padding:3px 8px;border-radius:4px;font-weight:700;font-size:11.5px;">⏳ Pending</span>
                @endif
              </td>
              <td>
                <small>{{ $app->created_at->format('d M, Y') }}</small>
              </td>
              <td>
                <div style="display:flex;gap:6px;align-items:center;">
                  @if($app->status !== 'accepted')
                    <a href="{{ route('admin.applications.show', $app) }}#approval-section" style="background:#0f5132;color:#fff;padding:5px 10px;border-radius:4px;font-size:11.5px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                      ✔ Approve
                    </a>
                  @endif
                  <a href="{{ route('admin.applications.show', $app) }}" style="background:#e2e8f0;color:#0f172a;padding:5px 8px;border-radius:4px;font-size:11.5px;text-decoration:none;font-weight:600;">Details</a>
                  <a href="{{ route('admin.applications.print', $app) }}" target="_blank" style="background:#f8fafc;color:#0f172a;border:1px solid #cbd5e1;padding:4px 7px;border-radius:4px;font-size:11.5px;text-decoration:none;" title="Print Form & Voucher">🖨️</a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="empty">No student applications submitted yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
  <section class="panel">
    <div class="panel-head">
      <h2>Recent Updates</h2>
      <a href="{{ route('admin.content.index','notices') }}">View All →</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Content</th>
            <th>Type</th>
            <th>Status</th>
            <th>Last updated</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recent as $row)
            <tr>
              <td><strong>{{ $row->title }}</strong></td>
              <td>{{ ucfirst($row->section) }}</td>
              <td><span class="badge {{ $row->status }}">{{ ucfirst($row->status) }}</span></td>
              <td>{{ $row->updated_at->diffForHumans() }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4">No content yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <section class="panel">
    <div class="panel-head">
      <h2>Quick Actions</h2>
    </div>
    <div class="quick-grid">
      <a href="{{ route('admin.content.create','courses') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"></path></svg>
        <b>Add a Course</b>
        <span>›</span>
      </a>
      <a href="{{ route('admin.content.create','projects') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
        <b>Add a Project</b>
        <span>›</span>
      </a>
      <a href="{{ route('admin.content.create','career') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
        <b>Job Circular</b>
        <span>›</span>
      </a>
      <a href="{{ route('admin.content.create','notices') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        <b>Post a Notice</b>
        <span>›</span>
      </a>
      <a href="{{ route('admin.content.index','hero_slides') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
        <b>Hero Slider</b>
        <span>›</span>
      </a>
      <a href="{{ route('admin.content.create','gallery') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        <b>Upload Photos</b>
        <span>›</span>
      </a>
      <a href="{{ route('admin.applications') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
        <b>Applications ({{ $applicationCount }})</b>
        <span>›</span>
      </a>
      <a href="{{ route('admin.settings') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        <b>Site Settings</b>
        <span>›</span>
      </a>
      @can('users.view')
        <a href="{{ route('admin.users.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          <b>User Management</b>
          <span>›</span>
        </a>
      @endcan
      @can('roles.manage')
        <a href="{{ route('admin.roles.index') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
          <b>Role Management</b>
          <span>›</span>
        </a>
      @endcan
    </div>
  </section>

  <section class="panel">
    <div class="panel-head">
      <h2>Course Management</h2>
      <a href="{{ route('admin.content.index','courses') }}">View All →</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Course Name</th>
            <th>Category</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse(\App\Models\SiteContent::where('section','courses')->orderBy('sort_order')->take(6)->get() as $i=>$course)
            <tr>
              <td>{{ $i+1 }}</td>
              <td><strong>{{ $course->title }}</strong></td>
              <td>{{ $course->category }}</td>
              <td><span class="badge {{ $course->status }}">{{ ucfirst($course->status) }}</span></td>
              <td><a class="edit-link" href="{{ route('admin.content.edit',['section'=>'courses','content'=>$course]) }}">Edit</a></td>
            </tr>
          @empty
            <tr>
              <td colspan="5">No courses added.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <section class="panel">
    <div class="panel-head">
      <h2>Projects</h2>
      <a href="{{ route('admin.content.index','projects') }}">View All →</a>
    </div>
    @forelse($projects as $project)
      <div class="project-row">
        @if($project->image)
          @php
            $pThumb = storage_asset($project->image);
          @endphp
          <img src="{{ $pThumb }}" alt="" style="width:42px;height:42px;object-fit:contain;border-radius:4px;border:1px solid #ddd;padding:2px;background:#fff;flex-shrink:0;">
        @else
          <i>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
          </i>
        @endif
        <div>
          <b>{{ $project->title }}</b>
          <span>{{ $project->excerpt }}</span>
        </div>
        <a class="edit-link" href="{{ route('admin.content.edit',['section'=>'projects','content'=>$project]) }}" style="font-size:12px;margin-right:8px;">Edit</a>
        <em>Active</em>
      </div>
    @empty
      <p class="empty">No active projects.</p>
    @endforelse
    <div class="message-link">
      <a href="{{ route('admin.messages') }}">Unread messages: {{ $messageCount }} →</a>
    </div>
  </section>
</div>
@endsection
