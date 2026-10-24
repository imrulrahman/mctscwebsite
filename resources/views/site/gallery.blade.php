@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
@extends('site.layout')

@section('title', $isBn ? 'ফটো গ্যালারি' : 'Campus Photo Gallery')
@section('page_title', $isBn ? 'ইনস্টিটিউট ফটো গ্যালারি' : 'Campus Life & Workshop Gallery')
@section('page_subtitle', $isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজের প্র্যাকটিক্যাল ল্যাব, প্রশিক্ষণ কার্যক্রম ও ক্যাম্পাস জীবনের মুহূর্তসমূহ।' : 'A visual glance into our modern technical workshops, student practical sessions, and campus events.')

@section('content')
<section class="section" style="padding: 40px 0 60px 0;">
  <div class="container">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; flex-wrap: wrap; gap: 14px;">
      <div>
        <span class="section-kicker">{{ $isBn ? 'ক্যাম্পাস অ্যালবাম' : 'CAMPUS ALBUM' }}</span>
        <h2 style="font-family: var(--serif); font-size: 26px; color: var(--green); margin: 6px 0 0 0;">
          {{ $isBn ? 'প্র্যাকটিক্যাল ট্রেইনিং ও ল্যাব কার্যক্রমের স্থিরচিত্র' : 'Practical Workshop & Training Moments' }}
        </h2>
      </div>
      <div style="font-size: 13.5px; color: #64748b;">
        {{ $isBn ? 'যেকোনো ছবিতে ক্লিক করে বড় করে দেখুন' : 'Click on any photo to view full size' }}
      </div>
    </div>

    <!-- GALLERY GRID -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px;">
      @forelse($gallery as $item)
        @php
          $imgUrl = storage_asset($item->image, 'assets/MC Building Photo.jpg');
          $title = $item->title ?: ($isBn ? 'ক্যাম্পাস ছবি' : 'Campus Photo');
        @endphp
        <div class="gallery-card" data-src="{{ $imgUrl }}" data-title="{{ $title }}" style="background: #ffffff; border: 1px solid #d8e4dc; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); cursor: pointer; transition: transform 0.22s, box-shadow 0.22s;">
          <div style="overflow: hidden; position: relative;">
            <img src="{{ $imgUrl }}" alt="{{ $title }}" style="width: 100%; height: 210px; object-fit: cover; display: block; transition: transform 0.3s ease;">
            <div class="gallery-overlay" style="position: absolute; inset: 0; background: rgba(15, 81, 50, 0.4); opacity: 0; transition: opacity 0.2s ease; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 26px;">
              🔍
            </div>
          </div>
          <div style="padding: 12px 14px; background: #ffffff;">
            <strong style="font-size: 13.5px; color: #1e3d35; font-family: var(--serif); display: block; line-height: 1.3;">
              {{ $title }}
            </strong>
            @if($item->excerpt)
              <small style="color: #64748b; font-size: 11.5px; display: block; margin-top: 3px;">{{ $item->excerpt }}</small>
            @endif
          </div>
        </div>
      @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1;">
          <p style="color: #64748b; font-size: 16px;">{{ $isBn ? 'গ্যালারিতে কোনো ছবি পাওয়া যায়নি।' : 'No gallery photos have been uploaded yet.' }}</p>
        </div>
      @endforelse
    </div>
  </div>
</section>
<style>
  .gallery-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 22px rgba(15,81,50,0.15) !important;
  }
  .gallery-card:hover .gallery-overlay {
    opacity: 1 !important;
  }
  .gallery-card:hover img {
    transform: scale(1.06);
  }
  @media (max-width: 900px) {
    div[style*="grid-template-columns: repeat(4, 1fr)"] {
      grid-template-columns: repeat(2, 1fr) !important;
    }
  }
  @media (max-width: 520px) {
    div[style*="grid-template-columns: repeat(4, 1fr)"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>
@endsection
