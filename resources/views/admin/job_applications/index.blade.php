@extends('admin.layout')
@section('title', 'Job Applications · MCTSC Admin')
@section('crumb', 'Job Applications')

@section('content')
<div class="page-head">
  <div>
    <h1>Job Applications (চাকরির আবেদনসমূহ)</h1>
    <p>Review online career applications, download CVs, filter by post & district, shortlist candidates, and export to Excel</p>
  </div>
  <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <a class="btn" href="{{ route('admin.job-applications.export', request()->query()) }}" style="background:#0f5132;color:#fff;display:inline-flex;align-items:center;gap:6px;font-weight:600;" title="Export currently filtered candidates to Excel (CSV)">
      <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
      Export to Excel
    </a>
    <a class="btn light" href="{{ route('admin.content.index', 'career') }}">Manage Job Circulars →</a>
  </div>
</div>

@if(!empty($posts) && $posts->count() > 1)
  <div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;">
    <a href="{{ route('admin.job-applications.index', request()->except(['post', 'page'])) }}" style="padding:6px 14px;border-radius:20px;font-size:12.5px;font-weight:600;text-decoration:none;border:1px solid {{ !request('post') ? '#0f5132' : '#cbd5e1' }};background:{{ !request('post') ? '#0f5132' : '#fff' }};color:{{ !request('post') ? '#fff' : '#475569' }};">
      All Posts ({{ \App\Models\JobApplication::count() }})
    </a>
    @foreach($posts as $p)
      @php
        $cnt = \App\Models\JobApplication::where('post_name', $p)->count();
      @endphp
      <a href="{{ route('admin.job-applications.index', array_merge(request()->except(['post', 'page']), ['post' => $p])) }}" style="padding:6px 14px;border-radius:20px;font-size:12.5px;font-weight:600;text-decoration:none;border:1px solid {{ request('post') === $p ? '#0f5132' : '#cbd5e1' }};background:{{ request('post') === $p ? '#0f5132' : '#fff' }};color:{{ request('post') === $p ? '#fff' : '#475569' }};">
        {{ $p }} ({{ $cnt }})
      </a>
    @endforeach
  </div>
@endif

<div class="panel" style="margin-bottom:20px;padding:16px;">
  <form method="get" action="{{ route('admin.job-applications.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
    <div style="flex:1;min-width:200px;">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search tracking ID, applicant name, post, mobile, NID..." class="filter-input" style="padding:9px 12px;width:100%;border:1px solid #cbd5e1;border-radius:6px;">
    </div>
    <div style="min-width:180px;">
      <select name="post" class="filter-input" style="padding:9px 12px;width:100%;border:1px solid #cbd5e1;border-radius:6px;">
        <option value="">— All Positions / Posts —</option>
        @foreach($posts as $p)
          <option value="{{ $p }}" {{ request('post') === $p ? 'selected' : '' }}>{{ $p }}</option>
        @endforeach
      </select>
    </div>
    <div style="min-width:160px;">
      <select name="district" class="filter-input" style="padding:9px 12px;width:100%;border:1px solid #cbd5e1;border-radius:6px;">
        <option value="">— All Districts —</option>
        @foreach($districts as $d)
          <option value="{{ $d }}" {{ request('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
        @endforeach
      </select>
    </div>
    <div style="min-width:150px;">
      <select name="education" class="filter-input" style="padding:9px 12px;width:100%;border:1px solid #cbd5e1;border-radius:6px;">
        <option value="">— Qualification —</option>
        <option value="masters" {{ request('education') === 'masters' ? 'selected' : '' }}>Masters</option>
        <option value="graduation" {{ request('education') === 'graduation' ? 'selected' : '' }}>Graduation / Bachelor</option>
        <option value="diploma" {{ request('education') === 'diploma' ? 'selected' : '' }}>Diploma / HSC</option>
        <option value="ssc" {{ request('education') === 'ssc' ? 'selected' : '' }}>SSC / Equivalent</option>
      </select>
    </div>
    <div style="min-width:140px;">
      <select name="status" class="filter-input" style="padding:9px 12px;width:100%;border:1px solid #cbd5e1;border-radius:6px;">
        <option value="">— All Statuses —</option>
        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
        <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
        <option value="interview" {{ request('status') === 'interview' ? 'selected' : '' }}>Interview</option>
        <option value="hired" {{ request('status') === 'hired' ? 'selected' : '' }}>Hired</option>
        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
      </select>
    </div>
    <div style="display:flex;gap:8px;">
      <button type="submit" class="btn light" style="background:#0f5132;color:#fff;border:none;padding:9px 16px;border-radius:6px;font-weight:600;cursor:pointer;">Filter</button>
      @if(request()->hasAny(['q', 'post', 'district', 'education', 'status']))
        <a href="{{ route('admin.job-applications.index') }}" class="btn light" style="color:#64748b;padding:9px 12px;text-decoration:none;">Reset</a>
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
          <th style="width:50px;text-align:center;">SL</th>
          <th>Applicant</th>
          <th>Applied Post</th>
          <th>Contact</th>
          <th>District</th>
          <th>Attached CV</th>
          <th>Applied On</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($applications as $app)
          <tr>
            <td style="text-align:center;font-weight:700;color:#64748b;">
              {{ $applications->firstItem() ? ($applications->firstItem() + $loop->index) : $loop->iteration }}
            </td>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                @if($app->photo_path)
                  <img src="{{ storage_asset($app->photo_path) }}" alt="" style="width:36px;height:36px;border-radius:4px;object-fit:cover;border:1px solid #cbd5e1;">
                @endif
                <div>
                  <strong style="display:block;font-size:13.5px;">{{ $app->applicant_name }}</strong>
                  <small style="color:#64748b;">{{ $app->gender }} · {{ $app->dob?->age }} yrs</small>
                  @php
                    $edu = is_array($app->education) ? $app->education : [];
                    $highest = null;
                    if (!empty($edu['masters']['exam'])) {
                      $highest = '🎓 ' . $edu['masters']['exam'];
                    } elseif (!empty($edu['graduation']['exam'])) {
                      $highest = '🎓 ' . $edu['graduation']['exam'];
                    } elseif (!empty($edu['hsc']['exam'])) {
                      $highest = '📜 ' . $edu['hsc']['exam'];
                    } elseif (!empty($edu['ssc']['exam'])) {
                      $highest = '📖 ' . $edu['ssc']['exam'];
                    }
                  @endphp
                  @if($highest)
                    <div style="font-size:11px;color:#0369a1;font-weight:600;margin-top:2px;">{{ $highest }}</div>
                  @endif
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
              @if($app->cv_path)
                <div style="display:inline-flex;align-items:center;gap:6px;">
                  <button type="button" 
                          onclick="openCvModal('{{ storage_asset($app->cv_path) }}', '{{ e($app->applicant_name) }} — {{ e($app->post_name) }} (CV Preview)')" 
                          style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;padding:4px 9px;border-radius:4px;font-size:12px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:4px;"
                          title="পপআপে সিভি প্রিভিউ দেখুন (View CV)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span>View</span>
                  </button>
                  <a href="{{ storage_asset($app->cv_path) }}" 
                     download 
                     style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:26px;background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;border-radius:4px;text-decoration:none;" 
                     title="সিভি ডাউনলোড করুন (Download CV)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                  </a>
                </div>
              @else
                <span style="color:#94a3b8;font-size:12px;">— No CV</span>
              @endif
            </td>
            <td style="white-space:nowrap;font-size:12px;color:#64748b;">
              {{ $app->created_at->format('d/m/Y') }}<br>
              {{ $app->created_at->format('h:i A') }}
            </td>
            <td style="text-align:right;">
              <div class="actions" style="justify-content:flex-end;gap:8px;flex-wrap:wrap;">
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

{{-- CV Document Popup Preview Modal --}}
<div id="cvPreviewModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.75);backdrop-filter:blur(4px);z-index:999999;align-items:center;justify-content:center;padding:16px;" onclick="if(event.target===this) closeCvModal()">
  <div style="background:#fff;border-radius:12px;width:100%;max-width:960px;height:88vh;display:flex;flex-direction:column;box-shadow:0 25px 50px -12px rgba(0,0,0,0.35);overflow:hidden;border:1px solid #cbd5e1;">
    <div style="padding:12px 18px;background:#f8fafc;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;gap:12px;">
      <div style="display:flex;align-items:center;gap:10px;min-width:0;">
        <span style="font-size:20px;">📄</span>
        <h3 id="cvModalTitle" style="margin:0;font-size:15px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Candidate CV Preview</h3>
      </div>
      <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
        <a id="cvModalDownloadBtn" href="#" download class="btn" style="background:#0f5132;color:#fff;padding:6px 12px;font-size:12px;display:inline-flex;align-items:center;gap:5px;text-decoration:none;border-radius:6px;font-weight:600;">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
          Download
        </a>
        <a id="cvModalNewTabBtn" href="#" target="_blank" class="btn light" style="padding:6px 10px;font-size:12px;text-decoration:none;border:1px solid #cbd5e1;border-radius:6px;color:#475569;" title="Open in New Tab">
          New Tab ↗
        </a>
        <button type="button" onclick="closeCvModal()" style="background:#fee2e2;border:1px solid #fca5a5;color:#dc2626;width:28px;height:28px;border-radius:50%;font-size:16px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Close">&times;</button>
      </div>
    </div>
    <div style="flex:1;background:#525659;position:relative;">
      <iframe id="cvModalIframe" src="" style="width:100%;height:100%;border:none;"></iframe>
    </div>
  </div>
</div>

<script>
function openCvModal(url, title) {
  const modal = document.getElementById('cvPreviewModal');
  const iframe = document.getElementById('cvModalIframe');
  const titleEl = document.getElementById('cvModalTitle');
  const dlBtn = document.getElementById('cvModalDownloadBtn');
  const tabBtn = document.getElementById('cvModalNewTabBtn');
  if (modal && iframe) {
    if (titleEl) titleEl.textContent = title || 'Candidate CV Preview';
    iframe.src = url;
    if (dlBtn) dlBtn.href = url;
    if (tabBtn) tabBtn.href = url;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
}

function closeCvModal() {
  const modal = document.getElementById('cvPreviewModal');
  const iframe = document.getElementById('cvModalIframe');
  if (modal) {
    modal.style.display = 'none';
    if (iframe) iframe.src = '';
    document.body.style.overflow = '';
  }
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeCvModal();
});
</script>
@endsection
