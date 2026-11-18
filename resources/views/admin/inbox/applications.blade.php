@extends('admin.layout')
@section('title', 'Trainee Admissions')
@section('crumb', 'Admissions')

@section('content')
<div class="page-head">
  <div>
    <h1>Trainee Admission Applications</h1>
    <p>Manage, review, search, and export trainee admission applications.</p>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap;">
    <a class="btn" href="{{ route('admin.applications.export', request()->query()) }}" style="background:#0f5132;color:#fff;display:inline-flex;align-items:center;gap:6px;font-weight:600;" title="Export current filtered list to Excel (CSV)">
      <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
      Export to Excel
    </a>
    @can('admissions.create')
      <a class="btn gold" href="{{ route('admin.applications.create') }}">+ Add New Student</a>
    @endcan
  </div>
</div>

<div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;">
  <a href="{{ route('admin.applications', array_merge(request()->except(['project', 'page']))) }}" style="padding:6px 14px;border-radius:20px;font-size:12.5px;font-weight:600;text-decoration:none;border:1px solid {{ !request('project') ? '#0f5132' : '#cbd5e1' }};background:{{ !request('project') ? '#0f5132' : '#fff' }};color:{{ !request('project') ? '#fff' : '#475569' }};">
    All Applications ({{ \App\Models\AdmissionApplication::count() }})
  </a>
  <a href="{{ route('admin.applications', array_merge(request()->except(['project', 'page']), ['project' => 'NHRDF'])) }}" style="padding:6px 14px;border-radius:20px;font-size:12.5px;font-weight:700;text-decoration:none;border:1px solid {{ request('project') === 'NHRDF' ? '#059669' : '#a7f3d0' }};background:{{ request('project') === 'NHRDF' ? '#059669' : '#ecfdf5' }};color:{{ request('project') === 'NHRDF' ? '#fff' : '#065f46' }};display:inline-flex;align-items:center;gap:5px;">
    🏛️ NHRDF Project ({{ \App\Models\AdmissionApplication::where('project_name', 'NHRDF')->count() }})
  </a>
  <a href="{{ route('admin.applications', array_merge(request()->except(['project', 'page']), ['project' => 'Paid Batch'])) }}" style="padding:6px 14px;border-radius:20px;font-size:12.5px;font-weight:600;text-decoration:none;border:1px solid {{ request('project') === 'Paid Batch' ? '#0284c7' : '#cbd5e1' }};background:{{ request('project') === 'Paid Batch' ? '#0284c7' : '#fff' }};color:{{ request('project') === 'Paid Batch' ? '#fff' : '#0369a1' }};display:inline-flex;align-items:center;gap:5px;">
    🎓 Regular / Paid Batch ({{ \App\Models\AdmissionApplication::where(function($q){ $q->where('project_name', 'Paid Batch')->orWhereNull('project_name')->orWhere('project_name', ''); })->count() }})
  </a>
</div>

<section class="panel" style="margin-bottom: 20px; padding: 18px 24px;">
  <form method="GET" action="{{ route('admin.applications') }}" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
    <div style="flex: 1; min-width: 220px;">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Mobile Phone, Tracking ID, Name, or Voucher..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
    </div>
    <div style="min-width: 180px;">
      <select name="course" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
        <option value="">All Courses / Trades</option>
        @foreach($courses as $c)
          <option value="{{ $c }}" @selected(request('course') == $c)>{{ $c }}</option>
        @endforeach
      </select>
    </div>
    <div style="min-width: 170px;">
      <select name="project" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
        <option value="">All Projects / Batches</option>
        <option value="NHRDF" @selected(request('project') == 'NHRDF')>🏛️ NHRDF Project</option>
        <option value="Paid Batch" @selected(request('project') == 'Paid Batch')>Paid Batch</option>
        @foreach($projects as $p)
          @if($p->title !== 'NHRDF')
            <option value="{{ $p->title }}" @selected(request('project') == $p->title)>{{ $p->title }}</option>
          @endif
        @endforeach
      </select>
    </div>
    <div style="min-width: 140px;">
      <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
        <option value="">All Statuses</option>
        <option value="new" @selected(request('status') == 'new')>New (Pending)</option>
        <option value="reviewing" @selected(request('status') == 'reviewing')>Reviewing</option>
        <option value="accepted" @selected(request('status') == 'accepted')>Accepted (Approved)</option>
        <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
      </select>
    </div>
    <button type="submit" style="background: var(--green); color: #fff; border: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; cursor: pointer;">Filter</button>
    @if(request()->hasAny(['q', 'course', 'project', 'status']))
      <a href="{{ route('admin.applications') }}" style="color: #64748b; font-size: 13px; text-decoration: none; padding: 8px;">Reset</a>
    @endif
  </form>
</section>

<section class="panel">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Tracking ID</th>
          <th>Photo</th>
          <th>Trainee Details</th>
          <th>Course & Shift</th>
          <th>Project / Batch</th>
          <th>Fee & Payment</th>
          <th>Status</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
          <tr>
            <td>
              <strong style="color: var(--green); font-size: 13px;">{{ $item->tracking_no ?: ('#' . $item->id) }}</strong>
            </td>
            <td style="width: 50px;">
              @if($item->photo)
                <img src="{{ storage_asset($item->photo) }}" alt="Photo" style="width: 40px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1;">
              @else
                <div style="width: 40px; height: 45px; background: #e2e8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #64748b;">No Img</div>
              @endif
            </td>
            <td>
              <strong style="font-size: 13.5px;">{{ $item->trainee_name_en ?: $item->name }}</strong>
              @if($item->trainee_name_bn)
                <small class="subline" style="color: #475569;">{{ $item->trainee_name_bn }}</small>
              @endif
              <small class="subline" style="color: #0f5132; font-weight: 600;">📞 {{ $item->contact_no ?: $item->phone }}</small>
            </td>
            <td>
              <strong style="color: #1e293b;">{{ $item->course }}</strong>
              @if($item->preferred_shift)
                <small class="subline" style="color: #64748b;">Shift: {{ $item->preferred_shift }}</small>
              @endif
            </td>
            <td>
              @if(strtoupper($item->project_name ?? '') === 'NHRDF')
                <span style="display:inline-block;background:#ecfdf5;border:1px solid #10b981;color:#065f46;padding:3px 9px;border-radius:999px;font-weight:700;font-size:11.5px;letter-spacing:0.3px;">
                  🏛️ NHRDF
                </span>
              @else
                <span style="display:inline-block;background:#f8fafc;border:1px solid #cbd5e1;padding:2px 8px;border-radius:4px;font-weight:600;font-size:12px;color:#0f172a;">
                  {{ $item->project_name ?: 'Paid Batch' }}
                </span>
              @endif
            </td>
            <td>
              <div>
                <strong style="color:{{ $item->isFreeProject() ? '#059669' : '#0f172a' }};">
                  {{ $item->isFreeProject() ? 'Free (৳ 0)' : ('৳ ' . number_format($item->payable_amount, 0)) }}
                </strong>
                @if((float)$item->discount > 0)
                  <small class="subline" style="color:#16a34a;">ছাড়: ৳ {{ number_format($item->discount, 0) }}</small>
                @endif
                <div>
                  @if($item->isPaid())
                    <span style="color:#059669;font-size:10.5px;font-weight:700;">● Paid / Free</span>
                  @else
                    <span style="color:#dc2626;font-size:10.5px;font-weight:700;">○ Unpaid</span>
                  @endif
                </div>
              </div>
            </td>
            <td>
              <div style="display:flex;flex-direction:column;gap:4px;">
                @if($item->status === 'accepted')
                  <span style="background:#d1fae5;color:#065f46;padding:3px 8px;border-radius:4px;font-weight:700;font-size:11px;display:inline-block;text-align:center;">
                    ✔ Approved
                  </span>
                @elseif($item->status === 'rejected')
                  <span style="background:#fee2e2;color:#991b1b;padding:3px 8px;border-radius:4px;font-weight:700;font-size:11px;display:inline-block;text-align:center;">
                    Rejected
                  </span>
                @else
                  <span style="background:#fef3c7;color:#92400e;padding:3px 8px;border-radius:4px;font-weight:700;font-size:11px;display:inline-block;text-align:center;">
                    ⏳ Pending
                  </span>
                  @can('admissions.edit')
                    <a href="{{ route('admin.applications.show', $item) }}#approval-section" style="width:100%;background:#0f5132;color:#fff;padding:3px 6px;border-radius:3px;font-size:10.5px;font-weight:700;text-decoration:none;display:inline-block;text-align:center;">
                      ✔ Approve
                    </a>
                  @endcan
                @endif
              </div>
            </td>
            <td>
              <small>{{ $item->created_at->format('d M, Y') }}</small>
              <small class="subline">{{ $item->created_at->format('h:i A') }}</small>
            </td>
            <td>
              <div style="display: flex; gap: 5px; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('admin.applications.show', $item) }}" style="background: #0f5132; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11.5px; text-decoration: none; font-weight: 600;">Details</a>
                @can('admissions.print')
                  <a href="{{ route('admin.applications.print', $item) }}" target="_blank" style="background: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1; padding: 4px 7px; border-radius: 4px; font-size: 11px; text-decoration: none; font-weight: 600;" title="Print Admission Form Copy">🖨️ Form</a>
                  @if($item->isApproved() || $item->isPaid())
                    <a href="{{ route('admin.applications.voucher', $item) }}" target="_blank" style="background: #e6f4f1; color: #005953; border: 1px solid #7cb5a9; padding: 4px 7px; border-radius: 4px; font-size: 11px; text-decoration: none; font-weight: 700;" title="Print 2-Part A4 Official Payment Voucher">🧾 Voucher</a>
                    <a href="{{ route('admin.applications.voucher', [$item, 'duplicate' => 1]) }}" target="_blank" style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 4px 7px; border-radius: 4px; font-size: 10.5px; text-decoration: none; font-weight: 700;" title="Print Duplicate Payment Voucher Copy">🔁 Duplicate</a>
                  @endif
                @endcan
                @can('admissions.delete')
                  <form method="post" action="{{ route('admin.applications.destroy', $item) }}" onsubmit="return confirm('Are you sure you want to delete this application?');" style="display:inline;margin:0;">
                    @csrf
                    @method('delete')
                    <button type="submit" style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 4px 6px; border-radius: 4px; font-size: 11px; cursor: pointer;" title="Delete Application">🗑️</button>
                  </form>
                @endcan
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="empty">No applications found matching your criteria.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $items->links() }}</div>
</section>
@endsection
