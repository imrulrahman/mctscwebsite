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

  <a class="stat-card" href="{{ route('admin.content.index','gallery') }}">
    <i>
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
    </i>
    <div>
      <span>Gallery Images</span>
      <b>{{ $counts['gallery'] }}</b>
    </div>
  </a>
</div>

<div class="dash-grid">
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
      <a href="{{ route('admin.content.create','notices') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        <b>Post a Notice</b>
        <span>›</span>
      </a>
      <a href="{{ route('admin.content.create','projects') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
        <b>Add a Project</b>
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
