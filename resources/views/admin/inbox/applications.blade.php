@extends('admin.layout')
@section('title', 'Trainee Admissions')
@section('crumb', 'Admissions')

@section('content')
<div class="page-head">
  <div>
    <h1>Trainee Admission Applications</h1>
    <p>Manage, review, search, and print trainee admission applications.</p>
  </div>
  @can('admissions.create')
    <div>
      <a class="btn gold" href="{{ route('admin.applications.create') }}">+ Add New Student</a>
    </div>
  @endcan
</div>

<section class="panel" style="margin-bottom: 20px; padding: 18px 24px;">
  <form method="GET" action="{{ route('admin.applications') }}" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
    <div style="flex: 1; min-width: 220px;">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Tracking ID, Name, Phone, or NID..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
    </div>
    <div style="min-width: 180px;">
      <select name="course" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
        <option value="">All Courses / Trades</option>
        @foreach($courses as $c)
          <option value="{{ $c }}" @selected(request('course') == $c)>{{ $c }}</option>
        @endforeach
      </select>
    </div>
    <div style="min-width: 140px;">
      <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
        <option value="">All Statuses</option>
        <option value="new" @selected(request('status') == 'new')>New</option>
        <option value="reviewing" @selected(request('status') == 'reviewing')>Reviewing</option>
        <option value="accepted" @selected(request('status') == 'accepted')>Accepted</option>
        <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
      </select>
    </div>
    <button type="submit" style="background: var(--green); color: #fff; border: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; cursor: pointer;">Search</button>
    @if(request()->hasAny(['q', 'course', 'status']))
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
          <th>Applied Course & Shift</th>
          <th>Status</th>
          <th>Received Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
          <tr>
            <td>
              <strong style="color: var(--green); font-size: 13px;">{{ $item->tracking_no ?: ('#' . $item->id) }}</strong>
            </td>
            <td style="width: 55px;">
              @if($item->photo)
                <img src="{{ storage_asset($item->photo) }}" alt="Photo" style="width: 44px; height: 48px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1;">
              @else
                <div style="width: 44px; height: 48px; background: #e2e8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #64748b;">No Img</div>
              @endif
            </td>
            <td>
              <strong style="font-size: 14px;">{{ $item->trainee_name_en ?: $item->name }}</strong>
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
              <form class="inbox-form" method="POST" action="{{ route('admin.applications.update', $item) }}">
                @csrf
                @method('PATCH')
                <select name="status" style="font-size: 12px; padding: 4px 6px;">
                  <option value="new" @selected($item->status === 'new')>New</option>
                  <option value="reviewing" @selected($item->status === 'reviewing')>Reviewing</option>
                  <option value="accepted" @selected($item->status === 'accepted')>Accepted</option>
                  <option value="rejected" @selected($item->status === 'rejected')>Rejected</option>
                </select>
                <button type="submit" style="font-size: 12px; padding: 4px 8px;">Save</button>
              </form>
            </td>
            <td>
              {{ $item->created_at->format('d M, Y') }}
              <small class="subline">{{ $item->created_at->format('h:i A') }}</small>
            </td>
            <td>
              <div style="display: flex; gap: 6px; align-items: center;">
                <a href="{{ route('admin.applications.show', $item) }}" style="background: #0f5132; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; text-decoration: none; font-weight: 600;">Details</a>
                @can('admissions.print')
                  <a href="{{ route('admin.applications.print', $item) }}" target="_blank" style="background: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1; padding: 5px 8px; border-radius: 4px; font-size: 12px; text-decoration: none;" title="Print Application">🖨️</a>
                @endcan
                @can('admissions.delete')
                  <form method="post" action="{{ route('admin.applications.destroy', $item) }}" onsubmit="return confirm('Are you sure you want to delete this application?');" style="display:inline;margin:0;">
                    @csrf
                    @method('delete')
                    <button type="submit" style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 5px 8px; border-radius: 4px; font-size: 12px; cursor: pointer;" title="Delete Application">🗑️</button>
                  </form>
                @endcan
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="empty">No applications found matching your criteria.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $items->links() }}</div>
</section>
@endsection
