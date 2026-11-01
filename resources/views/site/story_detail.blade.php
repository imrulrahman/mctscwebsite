@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
  $storyImg = $story->image ? storage_asset($story->image, 'assets/activities/winter_warmer.jpg') : asset('assets/activities/winter_warmer.jpg');
  $isCaseItem = (str_contains(strtolower($story->category ?? ''), 'case'));
@endphp
@extends('site.layout')

@section('title', $story->title)
@section('page_title', $story->title)
@section('page_subtitle', ($story->category ? '[' . $story->category . '] ' : '') . ($story->created_at ? $story->created_at->format('d F Y') : ''))

@section('content')
<style>
  article iframe,
  article iframe.ql-video {
    width: 100%;
    max-width: 100%;
    aspect-ratio: 16 / 9;
    min-height: 340px;
    border: 0;
    border-radius: 12px;
    display: block;
    margin: 22px 0 26px 0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
  }
</style>

<section class="section" style="padding: 40px 0 70px 0; background: #f8fafc;">
  <div class="container">
    <div style="display: grid; grid-template-columns: minmax(0, 2.5fr) minmax(0, 1fr); gap: 36px; align-items: start;">
      
      <!-- Main Content Article -->
      <article style="background: #ffffff; border-radius: 18px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 20px rgba(15,23,42,0.04); padding: 32px 36px;">
        
        <!-- Featured Image -->
        @if($story->image)
          <div style="width: 100%; max-height: 480px; border-radius: 12px; overflow: hidden; margin-bottom: 24px; background: #f1f5f9; position: relative;">
            <img src="{{ $storyImg }}" 
                 alt="{{ $story->title }}" 
                 data-live-edit 
                 data-live-content-id="{{ $story->id }}" 
                 data-live-content-field="image" 
                 data-live-type="image" 
                 data-live-title="ছবি: {{ $story->title }}" 
                 data-live-location="stories"
                 style="width: 100%; height: auto; max-height: 480px; object-fit: cover; display: block;">
          </div>
        @endif

        <!-- Meta Bar -->
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <span data-live-edit 
                  data-live-content-id="{{ $story->id }}" 
                  data-live-content-field="category" 
                  data-live-type="text" 
                  data-live-title="টাইপ: {{ $story->title }}" 
                  data-live-location="stories"
                  style="background: {{ $isCaseItem ? '#e0f2fe' : '#dcfce7' }}; color: {{ $isCaseItem ? '#0369a1' : '#15803d' }}; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
              {{ $isCaseItem ? ($isBn ? '📊 কেস স্টাডি' : '📊 CASE STUDY') : ($isBn ? '🏆 সাফল্যের গল্প' : '🏆 SUCCESS STORY') }}
            </span>
            <span style="font-size: 13px; color: #64748b;">
              📅 {{ $story->created_at ? $story->created_at->format('d F Y') : '' }}
            </span>
          </div>

          @if(auth()->check() && (auth()->user()?->is_admin || auth()->user()?->can('projects.manage')))
            <a href="{{ route('admin.content.edit', ['stories', $story->id]) }}" style="background: #f1f5f9; color: #334155; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; border: 1px solid #e2e8f0;">
              ✏️ {{ $isBn ? 'এডমিন এডিট' : 'Admin Edit' }}
            </a>
          @endif
        </div>

        <!-- Story Title -->
        <h1 data-live-edit 
            data-live-content-id="{{ $story->id }}" 
            data-live-content-field="title" 
            data-live-type="text" 
            data-live-title="শিরোনাম: {{ $story->title }}" 
            data-live-location="stories"
            style="font-family: var(--serif); font-size: 26px; font-weight: 800; color: #0f172a; line-height: 1.35; margin: 0 0 18px 0;">
          {{ $story->title }}
        </h1>

        <!-- Excerpt / Highlights -->
        @if(!empty($story->excerpt))
          <div data-live-edit 
               data-live-content-id="{{ $story->id }}" 
               data-live-content-field="excerpt" 
               data-live-type="textarea" 
               data-live-title="সংক্ষিপ্ত বিবরণ: {{ $story->title }}" 
               data-live-location="stories"
               style="font-size: 16px; font-weight: 600; line-height: 1.7; color: #334155; margin-bottom: 24px; padding: 14px 18px; background: #f8fafc; border-left: 4px solid {{ $isCaseItem ? '#0284c7' : '#16a34a' }}; border-radius: 0 8px 8px 0; text-align: justify; text-justify: inter-word;">
            {{ $story->excerpt }}
          </div>
        @endif

        <!-- Body / Detailed Story -->
        @if(!empty($story->body))
          <div data-live-edit 
               data-live-content-id="{{ $story->id }}" 
               data-live-content-field="body" 
               data-live-type="rich" 
               data-live-title="বিস্তারিত বিবরণ: {{ $story->title }}" 
               data-live-location="stories"
               style="font-size: 15.5px; line-height: 1.85; color: #334155; text-align: justify; text-justify: inter-word;">
            {!! $story->body !!}
          </div>
        @else
          <p style="color: #94a3b8; font-style: italic;">
            {{ $isBn ? 'এই বিষয়ে বিস্তারিত তথ্য এখনো যুক্ত করা হয়নি।' : 'Detailed information has not been added yet.' }}
          </p>
        @endif

        <!-- Back Link Navigation -->
        <div style="margin-top: 40px; padding-top: 22px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
          <a href="{{ $isCaseItem ? route('case_studies') : route('success_stories') }}" 
             style="font-size: 14px; font-weight: 700; color: {{ $isCaseItem ? '#0284c7' : '#16a34a' }}; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            ← {{ $isCaseItem ? ($isBn ? 'সব কেস স্টাডি দেখুন' : 'Back to Case Studies') : ($isBn ? 'সব সাফল্যের গল্প দেখুন' : 'Back to Success Stories') }}
          </a>

          <a href="{{ route('success_stories', ['filter' => 'all']) }}" 
             style="font-size: 13.5px; color: #64748b; text-decoration: none;">
            {{ $isBn ? 'সকল স্টোরি আর্কাইভ' : 'All Stories Archive' }}
          </a>
        </div>

      </article>

      <!-- Sidebar -->
      <aside style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Related Stories Box -->
        <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: 0 4px 18px rgba(15,23,42,0.03);">
          <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 16px 0; padding-bottom: 10px; border-bottom: 2px solid #f1f5f9; display: flex; align-items: center; gap: 8px;">
            <span>🌟</span> {{ $isBn ? 'অন্যান্য গল্প ও কেস স্টাডি' : 'More Stories & Case Studies' }}
          </h3>

          @if($relatedStories->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 14px;">
              @foreach($relatedStories as $rel)
                @php
                  $relImg = $rel->image ? storage_asset($rel->image, 'assets/activities/winter_warmer.jpg') : asset('assets/activities/winter_warmer.jpg');
                  $relIsCase = (str_contains(strtolower($rel->category ?? ''), 'case'));
                @endphp
                <div style="display: flex; gap: 12px; align-items: flex-start;">
                  <a href="{{ route('stories.show', $rel->slug) }}" style="width: 72px; height: 56px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: #f1f5f9; display: block;">
                    <img src="{{ $relImg }}" alt="{{ $rel->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                  </a>
                  <div style="flex: 1; min-width: 0;">
                    <span style="font-size: 10.5px; font-weight: 700; color: {{ $relIsCase ? '#0284c7' : '#16a34a' }}; text-transform: uppercase;">
                      {{ $relIsCase ? 'Case Study' : 'Success Story' }}
                    </span>
                    <h4 style="font-size: 13.5px; font-weight: 700; margin: 2px 0 3px 0; line-height: 1.35;">
                      <a href="{{ route('stories.show', $rel->slug) }}" style="color: #0f172a; text-decoration: none; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $rel->title }}
                      </a>
                    </h4>
                    <span style="font-size: 11px; color: #94a3b8;">
                      📅 {{ $rel->created_at ? $rel->created_at->format('d M Y') : '' }}
                    </span>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p style="font-size: 13px; color: #94a3b8; margin: 0;">
              {{ $isBn ? 'অন্য কোনো গল্প নেই।' : 'No other stories found.' }}
            </p>
          @endif
        </div>

        <!-- Admission Helpline Card -->
        <div style="background: linear-gradient(135deg, #1e3d35 0%, #16a34a 100%); border-radius: 16px; padding: 22px; color: #ffffff; box-shadow: 0 4px 18px rgba(22,163,74,0.15); text-align: center;">
          <span style="font-size: 32px; display: block; margin-bottom: 8px;">🎓</span>
          <h4 style="font-size: 17px; font-weight: 800; margin: 0 0 6px 0; color: #ffffff;">
            {{ $isBn ? 'আপনিও হতে পারেন সফল!' : 'Be The Next Success Story!' }}
          </h4>
          <p style="font-size: 12.5px; color: rgba(255,255,255,0.85); line-height: 1.5; margin: 0 0 16px 0;">
            {{ $isBn ? 'সরকারি সনদপ্রাপ্ত বিভিন্ন যুগোপযোগী ট্রেডে ভর্তি চলছে।' : 'Admissions open for govt-certified technical courses.' }}
          </p>
          <a href="tel:01880919275" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #ffffff; color: #1e3d35; padding: 10px 16px; border-radius: 9999px; font-size: 14px; font-weight: 800; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <span>📞</span> 01880919275
          </a>
        </div>

        <!-- Implemented By Info Card -->
        <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 18px; text-align: center;">
          <span style="font-size: 10.5px; font-weight: 700; color: #64748b; letter-spacing: 1px; text-transform: uppercase; display: block; margin-bottom: 8px;">
            IMPLEMENTED BY
          </span>
          <a href="https://impactini.org" target="_blank" rel="noopener noreferrer" style="display: inline-block;">
            <img src="{{ asset('assets/logo/impact_initiative_logo.png') }}" alt="Impact Initiative" style="height: 38px; width: auto; object-fit: contain; margin: 0 auto;">
          </a>
        </div>

      </aside>

    </div>
  </div>
</section>
@endsection
