@extends('admin.layout')
@section('title', $label)
@section('crumb', $label)

@section('content')
<div class="page-head">
  <div>
    <h1>{{ $label }}</h1>
    <p>Manage {{ strtolower($label) }} displayed on the public website.</p>
  </div>
  <a class="btn gold" href="{{ route('admin.content.create', $section) }}">
    ＋ Add {{ $section === 'partners' ? 'Partner' : rtrim($label, 's') }}
  </a>
</div>

<section class="panel">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          @if($section === 'partners')
            <th style="width: 130px;">Logo</th>
            <th>Partner Name</th>
            <th>Affiliation / Program</th>
            <th style="width: 100px;">Sort Order</th>
          @else
            <th>Title</th>
            <th>Category</th>
            @if($section === 'projects')
              <th>Admission Fee</th>
            @endif
          @endif
          <th>Status</th>
          <th>Updated</th>
          <th style="width: 140px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
          <tr>
            @if($section === 'partners')
              <td>
                @if($item->image)
                  <img src="{{ storage_asset($item->image) }}" alt="{{ $item->title }}" style="height: 34px; max-width: 110px; object-fit: contain; background: #ffffff; padding: 2px 6px; border: 1px solid #e2e8f0; border-radius: 4px; display: block;">
                @else
                  <span style="font-size: 11px; color: #94a3b8; font-style: italic;">No Logo</span>
                @endif
              </td>
              <td>
                <strong>{{ $item->title }}</strong>
                @if($item->excerpt)
                  <small class="subline" style="display:block; color:#0284c7;">
                    <a href="{{ $item->excerpt }}" target="_blank" rel="noopener" style="text-decoration:underline;">{{ $item->excerpt }}</a>
                  </small>
                @endif
              </td>
              <td>{{ $item->category ?: '—' }}</td>
              <td>
                <span style="display: inline-block; padding: 2px 8px; background: #f1f5f9; border-radius: 4px; font-weight: 600; font-size: 12px;">#{{ $item->sort_order }}</span>
              </td>
            @else
              <td>
                <strong>{{ $item->title }}</strong>
                @if($item->excerpt)
                  <small class="subline">{{ \Illuminate\Support\Str::limit($item->excerpt, 90) }}</small>
                @endif
              </td>
              <td>{{ $item->category ?: '—' }}</td>
              @if($section === 'projects')
                <td><strong style="color:#0f5132;">{{ (float)($item->admission_fee ?? 0) > 0 ? ('৳ ' . number_format($item->admission_fee, 0)) : 'Free (৳ 0)' }}</strong></td>
              @endif
            @endif
            <td><span class="badge {{ $item->status }}">{{ ucfirst($item->status) }}</span></td>
            <td>{{ $item->updated_at->format('d M Y') }}</td>
            <td class="actions">
              <a class="edit-link" href="{{ route('admin.content.edit', ['section' => $section, 'content' => $item]) }}">Edit</a>
              <form method="post" action="{{ route('admin.content.destroy', ['section' => $section, 'content' => $item]) }}" onsubmit="return confirm('Delete this {{ $section === 'partners' ? 'partner' : 'item' }}?')">
                @csrf
                @method('DELETE')
                <button class="delete-link">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="{{ $section === 'projects' ? 6 : ($section === 'partners' ? 6 : 5) }}" class="empty">
              No {{ strtolower($label) }} have been added yet.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $items->links() }}</div>
</section>
@endsection
