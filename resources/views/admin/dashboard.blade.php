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

  <a class="stat-card" href="{{ route('admin.visitors.index') }}" style="border-left: 4px solid #10b981;">
    <i style="color: #10b981;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
    </i>
    <div>
      <span>Today's Visitors</span>
      <b>{{ number_format($todayVisitorsCount) }}</b>
      <small style="color:#059669;font-weight:700;font-size:11px;margin-top:2px;display:inline-block;">
        {{ number_format($todayUniqueVisitorsCount) }} Unique
        @if($todayCountries->isNotEmpty())
          · {{ $todayCountries->pluck('flag')->take(3)->implode(' ') }}
        @endif
      </small>
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
  {{-- Trainee Demographic Summary Cards --}}
  <section class="panel" style="grid-column: 1 / -1;">
    <div class="panel-head">
      <div>
        <h2>👥 Trainees Overview (ট্রেইনী পরিসংখ্যান)</h2>
        <p style="font-size:12px;color:#64748b;margin:2px 0 0 0;">Total trainees breakdown by gender, ethnicity, and occupation</p>
      </div>
      <a href="{{ route('admin.students.database') }}">Open Student Database →</a>
    </div>
    <div style="padding:16px;">
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(170px, 1fr));gap:14px;margin-bottom:22px;">
        <a href="{{ route('admin.students.database') }}" style="text-decoration:none;background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #0f5132;border-radius:8px;padding:14px 16px;display:block;">
          <div style="font-size:12px;color:#166534;font-weight:700;text-transform:uppercase;">Total Trainees</div>
          <div style="font-size:26px;font-weight:800;color:#0f5132;margin:4px 0 2px;">{{ number_format($traineeStats['total']) }}</div>
          <div style="font-size:11.5px;color:#15803d;">সর্বমোট প্রশিক্ষণার্থী</div>
        </a>

        <a href="{{ route('admin.students.database', ['gender' => 'Male']) }}" style="text-decoration:none;background:#eff6ff;border:1px solid #bfdbfe;border-left:4px solid #0284c7;border-radius:8px;padding:14px 16px;display:block;">
          <div style="font-size:12px;color:#0369a1;font-weight:700;text-transform:uppercase;">Male (পুরুষ)</div>
          <div style="font-size:26px;font-weight:800;color:#0c4a6e;margin:4px 0 2px;">{{ number_format($traineeStats['male']) }}</div>
          <div style="font-size:11.5px;color:#0284c7;">
            {{ $traineeStats['total'] > 0 ? round(($traineeStats['male'] / $traineeStats['total']) * 100, 1) : 0 }}% of total
          </div>
        </a>

        <a href="{{ route('admin.students.database', ['gender' => 'Female']) }}" style="text-decoration:none;background:#fdf2f8;border:1px solid #fbcfe8;border-left:4px solid #db2777;border-radius:8px;padding:14px 16px;display:block;">
          <div style="font-size:12px;color:#be185d;font-weight:700;text-transform:uppercase;">Female (নারী)</div>
          <div style="font-size:26px;font-weight:800;color:#831843;margin:4px 0 2px;">{{ number_format($traineeStats['female']) }}</div>
          <div style="font-size:11.5px;color:#db2777;">
            {{ $traineeStats['total'] > 0 ? round(($traineeStats['female'] / $traineeStats['total']) * 100, 1) : 0 }}% of total
          </div>
        </a>

        <a href="{{ route('admin.students.database', ['ethnic' => 'Yes']) }}" style="text-decoration:none;background:#fefce8;border:1px solid #fde68a;border-left:4px solid #d97706;border-radius:8px;padding:14px 16px;display:block;">
          <div style="font-size:12px;color:#b45309;font-weight:700;text-transform:uppercase;">Ethnic (ক্ষুদ্র নৃ-গোষ্ঠী)</div>
          <div style="font-size:26px;font-weight:800;color:#78350f;margin:4px 0 2px;">{{ number_format($traineeStats['ethnic']) }}</div>
          <div style="font-size:11.5px;color:#d97706;">
            {{ $traineeStats['total'] > 0 ? round(($traineeStats['ethnic'] / $traineeStats['total']) * 100, 1) : 0 }}% of total
          </div>
        </a>

        @if($traineeStats['pwd'] > 0)
          <a href="{{ route('admin.students.database', ['pwd' => 'Yes']) }}" style="text-decoration:none;background:#f5f3ff;border:1px solid #ddd6fe;border-left:4px solid #7c3aed;border-radius:8px;padding:14px 16px;display:block;">
            <div style="font-size:12px;color:#6d28d9;font-weight:700;text-transform:uppercase;">PWD (প্রতিবন্ধী)</div>
            <div style="font-size:26px;font-weight:800;color:#4c1d95;margin:4px 0 2px;">{{ number_format($traineeStats['pwd']) }}</div>
            <div style="font-size:11.5px;color:#7c3aed;">Persons with Disability</div>
          </a>
        @endif
      </div>

      {{-- Occupation-wise Card View --}}
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
        <h3 style="margin:0;font-size:14px;color:#1e293b;">🎓 Occupation-Wise Trainees (অকুপেশন ভিত্তিক ট্রেইনী তথ্য)</h3>
        <span style="font-size:12px;color:#64748b;">{{ $occupationStats->count() }} Occupations</span>
      </div>

      @if($occupationStats->isNotEmpty())
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(255px, 1fr));gap:14px;">
          @foreach($occupationStats as $occ)
            <a href="{{ route('admin.students.database', ['occupation' => $occ->occupation]) }}" style="text-decoration:none;background:#ffffff;border:1px solid #dbe6e0;border-radius:8px;padding:14px 16px;display:flex;flex-direction:column;justify-content:space-between;gap:10px;transition:box-shadow 0.2s, border-color 0.2s;" onmouseover="this.style.borderColor='#0f5132';this.style.boxShadow='0 4px 12px rgba(15,81,50,0.08)';" onmouseout="this.style.borderColor='#dbe6e0';this.style.boxShadow='none';">
              <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;">
                <div>
                  <strong style="font-size:13.5px;color:#0f172a;display:block;line-height:1.35;">{{ $occ->occupation }}</strong>
                  @if($occ->occupation_code)
                    <span style="font-size:11px;color:#64748b;font-weight:600;">Code: {{ $occ->occupation_code }}</span>
                  @endif
                </div>
                <span style="background:#ecfdf5;color:#0f5132;border:1px solid #bbf7d0;font-size:15px;font-weight:800;padding:4px 10px;border-radius:6px;flex-shrink:0;">
                  {{ number_format($occ->total) }}
                </span>
              </div>

              <div style="display:flex;flex-wrap:wrap;gap:6px;padding-top:8px;border-top:1px dashed #e2e8f0;font-size:11.5px;font-weight:600;">
                <span style="background:#eff6ff;color:#0369a1;padding:2px 8px;border-radius:12px;">♂ Male: {{ number_format($occ->male_count) }}</span>
                <span style="background:#fdf2f8;color:#be185d;padding:2px 8px;border-radius:12px;">♀ Female: {{ number_format($occ->female_count) }}</span>
                <span style="background:#fefce8;color:#b45309;padding:2px 8px;border-radius:12px;">🌿 Ethnic: {{ number_format($occ->ethnic_count) }}</span>
                @if($occ->pwd_count > 0)
                  <span style="background:#f5f3ff;color:#6d28d9;padding:2px 8px;border-radius:12px;">♿ PWD: {{ number_format($occ->pwd_count) }}</span>
                @endif
              </div>
            </a>
          @endforeach
        </div>
      @else
        <div style="text-align:center;padding:24px;color:#64748b;background:#f8fafc;border-radius:8px;border:1px dashed #cbd5e1;">
          No trainee data found yet.
        </div>
      @endif
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
