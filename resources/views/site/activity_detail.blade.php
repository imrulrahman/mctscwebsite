@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
  $actImg = $activity->image ? storage_asset($activity->image, 'assets/activities/winter_warmer.jpg') : asset('assets/activities/winter_warmer.jpg');
@endphp
@extends('site.layout')

@section('title', $activity->title)
@section('page_title', $activity->title)
@section('page_subtitle', ($activity->category ? '[' . $activity->category . '] ' : '') . ($activity->created_at ? $activity->created_at->format('d F Y') : ''))

@section('content')
<section class="section" style="padding: 40px 0 70px 0; background: #f8fafc;">
  <div class="container">
    <div style="display: grid; grid-template-columns: minmax(0, 2.5fr) minmax(0, 1fr); gap: 36px; align-items: start;">
      
      <!-- Main Content Article -->
      <article style="background: #ffffff; border-radius: 18px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 20px rgba(15,23,42,0.04); padding: 32px 36px;">
        @if($activity->image)
          <div style="width: 100%; max-height: 480px; border-radius: 12px; overflow: hidden; margin-bottom: 24px; background: #f1f5f9;">
            <img src="{{ $actImg }}" alt="{{ $activity->title }}" style="width: 100%; height: auto; max-height: 480px; object-fit: cover; display: block;">
          </div>
        @endif

        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px; flex-wrap: wrap;">
          @if(!empty($activity->category))
            <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; text-transform: uppercase;">
              {{ $activity->category }}
            </span>
          @endif
          <span style="font-size: 13px; color: #64748b;">
            📅 {{ $activity->created_at ? $activity->created_at->format('d F Y, h:i A') : '' }}
          </span>
        </div>

        <h1 style="font-family: var(--serif); font-size: 26px; font-weight: 800; color: #0f172a; line-height: 1.35; margin: 0 0 18px 0;">
          {{ $activity->title }}
        </h1>

        @if(!empty($activity->excerpt))
          <div style="font-size: 16px; font-weight: 600; line-height: 1.7; color: #334155; margin-bottom: 22px; padding-bottom: 18px; border-bottom: 1px dashed #cbd5e1; text-align: justify; text-justify: inter-word;">
            {{ $activity->excerpt }}
          </div>
        @endif

        @if(!empty($activity->body))
          <div style="font-size: 15px; line-height: 1.85; color: #334155; text-align: justify; text-justify: inter-word;">
            {!! $activity->body !!}
          </div>
        @endif

        <div style="margin-top: 36px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
          <a href="{{ route('activities') }}" style="font-size: 13.5px; font-weight: 700; color: #16a34a; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            ← {{ $isBn ? 'সকল কার্যক্রমে ফিরে যান' : 'Back to all activities' }}
          </a>
          <a href="{{ route('home') }}" style="font-size: 13px; color: #64748b; text-decoration: none;">
            {{ $isBn ? 'হোমপেইজ' : 'Homepage' }}
          </a>
        </div>
      </article>

      <!-- Sidebar: Other Recent Activities -->
      <aside style="background: #ffffff; border-radius: 18px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 4px 18px rgba(15,23,42,0.03); position: sticky; top: 90px;">
        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 18px 0; padding-bottom: 12px; border-bottom: 2px solid #e2e8f0;">
          {{ $isBn ? 'অন্যান্য সাম্প্রতিক কার্যক্রম' : 'More Recent Activities' }}
        </h3>

        <div style="display: flex; flex-direction: column; gap: 14px;">
          @foreach($recentActivities as $rec)
            @php
              $rImg = $rec->image ? storage_asset($rec->image, 'assets/activities/winter_warmer.jpg') : asset('assets/activities/winter_warmer.jpg');
            @endphp
            <a href="{{ route('activities.show', $rec->slug) }}" style="display: flex; gap: 12px; align-items: center; text-decoration: none; color: inherit; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
              <img src="{{ $rImg }}" alt="{{ $rec->title }}" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover; flex-shrink: 0; background: #f1f5f9;">
              <div>
                <h4 style="font-size: 13.5px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                  {{ $rec->title }}
                </h4>
                <span style="font-size: 11.5px; color: #94a3b8;">
                  {{ $rec->created_at ? $rec->created_at->format('d M Y') : '' }}
                </span>
              </div>
            </a>
          @endforeach
        </div>

        <div style="margin-top: 18px; text-align: center;">
          <a href="{{ route('activities') }}" style="display: block; width: 100%; box-sizing: border-box; text-align: center; padding: 9px 14px; border: 1px solid #16a34a; color: #16a34a; border-radius: 8px; font-weight: 700; font-size: 13px; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#16a34a';this.style.color='#fff';" onmouseout="this.style.background='transparent';this.style.color='#16a34a';">
            {{ $isBn ? 'সকল কার্যক্রম দেখুন →' : 'View All Activities →' }}
          </a>
        </div>
      </aside>

    </div>
  </div>
</section>
@endsection
