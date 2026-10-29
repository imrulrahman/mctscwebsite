@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
@extends('site.layout')

@section('title', $isBn ? 'সাম্প্রতিক কার্যক্রম' : 'Recent Activities')
@section('page_title', $isBn ? 'আমাদের সাম্প্রতিক কার্যক্রম ও ফিল্ড স্টোরিজ' : 'Recent Activities & Field Stories')
@section('page_subtitle', $isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজের বাস্তবমুখী প্রশিক্ষণ, যুব উন্নয়ন ও মানবিক সহায়তা কার্যক্রম।' : 'Explore technical training initiatives, youth development programs, and community support stories from MCTSC.')

@section('content')
<section class="section" style="padding: 40px 0 70px 0; background: #f8fafc;">
  <div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 14px;">
      <div>
        <span style="font-size: 12px; font-weight: 700; color: #16a34a; letter-spacing: 1.5px; text-transform: uppercase;">
          — {{ $isBn ? 'কার্যক্রম আর্কাইভ' : 'ALL ACTIVITIES' }}
        </span>
        <h2 style="font-family: var(--serif); font-size: 26px; color: #0f172a; margin: 4px 0 0 0;">
          {{ $isBn ? 'সর্বশেষ প্রকাশিত কার্যক্রম' : 'Latest Stories & Field Updates' }}
        </h2>
      </div>
      <div style="font-size: 13.5px; color: #64748b; background: #ffffff; padding: 6px 14px; border-radius: 9999px; border: 1px solid #e2e8f0;">
        {{ $isBn ? 'মোট কার্যক্রম:' : 'Total Activities:' }} <strong style="color: #0f172a;">{{ $activities->total() }}</strong>
      </div>
    </div>

    @if($activities->count() > 0)
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 26px;">
        @foreach($activities as $act)
          @php
            $actImg = $act->image ? storage_asset($act->image, 'assets/activities/winter_warmer.jpg') : asset('assets/activities/winter_warmer.jpg');
          @endphp
          <article style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04); display: flex; flex-direction: column; transition: transform 0.2s ease, box-shadow 0.2s ease;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 28px rgba(15,23,42,0.08)';" onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 18px rgba(15,23,42,0.04)';">
            <div style="width: 100%; height: 210px; background: #f1f5f9; overflow: hidden; position: relative;">
              <img src="{{ $actImg }}" alt="{{ $act->title }}" style="width: 100%; height: 100%; object-fit: cover;">
              @if(!empty($act->category))
                <span style="position: absolute; top: 12px; left: 12px; background: rgba(15, 23, 42, 0.82); color: #ffffff; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; backdrop-filter: blur(4px);">
                  {{ $act->category }}
                </span>
              @endif
            </div>

            <div style="padding: 20px 22px; display: flex; flex-direction: column; flex: 1;">
              <span style="font-size: 12px; color: #94a3b8; margin-bottom: 6px;">
                📅 {{ $act->created_at ? $act->created_at->format('d M Y') : '' }}
              </span>
              <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; line-height: 1.4; margin: 0 0 8px 0;">
                <a href="{{ route('activities.show', $act->slug) }}" style="color: inherit; text-decoration: none;">
                  {{ $act->title }}
                </a>
              </h3>
              @if(!empty($act->excerpt))
                <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0 0 16px 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                  {{ $act->excerpt }}
                </p>
              @endif

              <div style="margin-top: auto; padding-top: 12px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('activities.show', $act->slug) }}" style="font-size: 13px; font-weight: 700; color: #16a34a; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                  {{ $isBn ? 'বিস্তারিত পড়ুন →' : 'Read more →' }}
                </a>
              </div>
            </div>
          </article>
        @endforeach
      </div>

      <div style="margin-top: 40px;">
        {{ $activities->links() }}
      </div>
    @else
      <div style="text-align: center; padding: 60px 20px; background: #ffffff; border-radius: 12px; border: 1px dashed #cbd5e1;">
        <span style="font-size: 40px; display: block; margin-bottom: 10px;">📋</span>
        <h3 style="color: #64748b;">{{ $isBn ? 'বর্তমানে কোনো কার্যক্রম পাওয়া যায়নি।' : 'No activities published yet.' }}</h3>
      </div>
    @endif
  </div>
</section>
@endsection
