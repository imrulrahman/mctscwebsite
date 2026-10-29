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
    ＋ Add {{ $section === 'partners' ? 'Partner' : ($section === 'hero_slides' ? 'Slide' : ($section === 'activities' ? 'Activity' : rtrim($label, 's'))) }}
  </a>
</div>

@if($section === 'activities')
  <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;padding:14px 18px;border-radius:6px;margin-bottom:18px;">
    <strong style="color:#166534;font-size:14px;display:flex;align-items:center;gap:6px;">
      <span>💡</span> Recent Activity (সাম্প্রতিক কার্যক্রম) নির্দেশনা
    </strong>
    <p style="margin:4px 0 0 0;font-size:13px;color:#15803d;line-height:1.6;">
      এখানে সর্বশেষ যে এক্টিভিটি আপনি পোস্ট করবেন, সেটি স্বয়ংক্রিয়ভাবে হোমপেইজের 'Recent Activity' কলামের সবার প্রথমে থাকবে। এছাড়া কোনো এক্টিভিটির ক্যাটাগরি 'Featured Story' (বা বিশেষ কার্যক্রম) হিসেবে দিলে সেটি হোমপেইজের বামপাশের মূল বড় কার্ড হিসেবে হাইলাইট হবে।
    </p>
  </div>
@endif

@if($section === 'hero_slides')
  <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;padding:14px 18px;border-radius:6px;margin-bottom:18px;">
    <strong style="color:#166534;font-size:14px;display:flex;align-items:center;gap:6px;">
      <span>💡</span> হিরো স্লাইডার ব্যানার নির্দেশনা
    </strong>
    <p style="margin:4px 0 0 0;font-size:13px;color:#15803d;line-height:1.6;">
      হোমপেইজের হিরো ব্যানারে এখানে থাকা সমস্ত 'Published' ছবি পর্যায়ক্রমে স্লাইড হতে থাকবে। আপনি ইচ্ছামতো ছবি পরিবর্তন, নতুন ছবি যোগ বা অপ্রয়োজনীয় ছবি মুছে ফেলতে পারেন। ২ টি ছবি দিলে ২টিই স্লাইড হবে, ১০টি দিলে ১০টিই স্লাইড হবে। আবার চাইলে স্লাইডিং অফ রেখে কোনো একটি ছবি স্থির (Fixed) করতে পারেন — এজন্য <a href="{{ route('admin.settings') }}#sec-hero" style="font-weight:700;text-decoration:underline;">Settings → Hero Section</a> এ যান।
    </p>
  </div>
@endif

<section class="panel">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          @if($section === 'activities')
            <th style="width: 70px;">Photo</th>
            <th>Title & Excerpt</th>
            <th>Badge / Category</th>
            <th style="width: 150px;">Posted Date</th>
          @elseif($section === 'partners')
            <th style="width: 130px;">Logo</th>
            <th>Partner Name</th>
            <th>Affiliation / Program</th>
            <th style="width: 100px;">Sort Order</th>
          @elseif($section === 'hero_slides')
            <th style="width: 160px;">Banner Preview</th>
            <th>Slide Title</th>
            <th>Category / Tag</th>
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
            @if($section === 'activities')
              <td>
                @if($item->image)
                  <img src="{{ storage_asset($item->image) }}" alt="{{ $item->title }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; display: block;">
                @else
                  <span style="font-size: 11px; color: #94a3b8; font-style: italic;">No Photo</span>
                @endif
              </td>
              <td>
                <strong style="color: #0f2942; font-size: 14px;">{{ $item->title }}</strong>
                @if($item->excerpt)
                  <small class="subline" style="display:block; color:#64748b; margin-top:3px;">{{ \Illuminate\Support\Str::limit($item->excerpt, 100) }}</small>
                @endif
              </td>
              <td>
                @if(stripos($item->category ?? '', 'featured') !== false)
                  <span style="background: #ffe4e6; color: #e11d48; padding: 3px 8px; border-radius: 4px; font-weight: 700; font-size: 11px;">● FEATURED</span>
                @else
                  <span style="background: #f1f5f9; color: #334155; padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 11.5px;">{{ $item->category ?: 'General' }}</span>
                @endif
              </td>
              <td>
                <span style="font-size: 12px; color: #64748b;">{{ $item->created_at ? $item->created_at->format('d M Y, h:i A') : '—' }}</span>
              </td>
            @elseif($section === 'partners')
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
            @elseif($section === 'hero_slides')
              <td>
                @if($item->image)
                  <img src="{{ storage_asset($item->image) }}" alt="{{ $item->title }}" style="height: 44px; width: 140px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1; display: block;">
                @else
                  <span style="font-size: 11px; color: #94a3b8; font-style: italic;">No Image</span>
                @endif
              </td>
              <td><strong>{{ $item->title }}</strong></td>
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
              <form method="post" action="{{ route('admin.content.destroy', ['section' => $section, 'content' => $item]) }}" onsubmit="return confirm('Delete this {{ $section === 'partners' ? 'partner' : ($section === 'hero_slides' ? 'slide' : 'item') }}?')">
                @csrf
                @method('DELETE')
                <button class="delete-link">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="{{ in_array($section, ['activities', 'partners', 'hero_slides', 'projects']) ? 7 : 6 }}" class="empty">
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
