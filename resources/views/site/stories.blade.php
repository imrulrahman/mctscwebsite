@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
  $activeTab = $filter ?? 'success_story';
  $isCase = ($activeTab === 'case_study');
@endphp
@extends('site.layout')

@section('title', $isCase ? ($isBn ? 'কেস স্টাডি' : 'Case Studies') : ($isBn ? 'সাফল্যের গল্প' : 'Success Stories'))
@section('page_title', $isCase ? ($isBn ? 'কেস স্টাডি ও প্রভাব মূল্যায়ন' : 'Case Studies & Impact Assessments') : ($isBn ? 'আমাদের শিক্ষার্থীদের সাফল্যের গল্প' : 'Trainee Success Stories'))
@section('page_subtitle', $isCase ? ($isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজের বাস্তবমুখী কারিগরি প্রশিক্ষণ, যুব কর্মসংস্থান ও প্রাতিষ্ঠানিক প্রভাবের কেস স্টাডি।' : 'In-depth case studies, training impact assessments, and real-world employment outcomes from MCTSC.') : ($isBn ? 'দক্ষ কারিগরি প্রশিক্ষণ গ্রহণ করে স্বাবলম্বী হওয়া ও দেশে-বিদেশে সফল ক্যারিয়ার গড়ার বাস্তব অনুপ্রেরণাদায়ী গল্প।' : 'Inspiring career journeys and achievements of skilled trainees graduated from MCTSC.'))

@section('content')
<section class="section" style="padding: 40px 0 70px 0; background: #f8fafc;">
  <div class="container">
    
    <!-- Top Bar: Navigation Tabs & Admin Action -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 16px;">
      
      <!-- Filter Tabs -->
      <div style="display: inline-flex; align-items: center; background: #ffffff; padding: 6px; border-radius: 9999px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(15,23,42,0.04); gap: 4px; flex-wrap: wrap;">
        <a href="{{ route('success_stories', ['filter' => 'all']) }}" 
           style="padding: 8px 18px; border-radius: 9999px; font-size: 13.5px; font-weight: 700; text-decoration: none; transition: all 0.2s ease; {{ $activeTab === 'all' ? 'background: #16a34a; color: #ffffff; box-shadow: 0 2px 6px rgba(22,163,74,0.3);' : 'color: #64748b; background: transparent;' }}">
          {{ $isBn ? 'সবগুলো' : 'All' }}
        </a>
        <a href="{{ route('success_stories', ['filter' => 'success_story']) }}" 
           style="padding: 8px 18px; border-radius: 9999px; font-size: 13.5px; font-weight: 700; text-decoration: none; transition: all 0.2s ease; {{ $activeTab === 'success_story' ? 'background: #16a34a; color: #ffffff; box-shadow: 0 2px 6px rgba(22,163,74,0.3);' : 'color: #64748b; background: transparent;' }}">
          🏆 {{ $isBn ? 'সাফল্যের গল্প' : 'Success Stories' }}
        </a>
        <a href="{{ route('case_studies') }}" 
           style="padding: 8px 18px; border-radius: 9999px; font-size: 13.5px; font-weight: 700; text-decoration: none; transition: all 0.2s ease; {{ $activeTab === 'case_study' ? 'background: #0284c7; color: #ffffff; box-shadow: 0 2px 6px rgba(2,132,199,0.3);' : 'color: #64748b; background: transparent;' }}">
          📊 {{ $isBn ? 'কেস স্টাডি' : 'Case Studies' }}
        </a>
      </div>

      <!-- Right Side Info & Admin Shortcuts -->
      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <div style="font-size: 13px; color: #64748b; background: #ffffff; padding: 7px 16px; border-radius: 9999px; border: 1px solid #e2e8f0;">
          {{ $isBn ? 'মোট কনটেন্ট:' : 'Total Stories:' }} <strong style="color: #0f172a;">{{ $stories->total() }}</strong>
        </div>

        @if(auth()->check() && (auth()->user()?->is_admin || auth()->user()?->can('projects.manage')))
          <a href="{{ route('admin.content.create', 'stories') }}" style="background: #1e3d35; color: #ffffff; padding: 7px 14px; border-radius: 9999px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
            <span>+</span> {{ $isBn ? 'নতুন যোগ করুন' : 'Add Story' }}
          </a>
        @endif
      </div>

    </div>

    <!-- Stories Grid -->
    @if($stories->count() > 0)
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 26px;">
        @foreach($stories as $story)
          @php
            $storyImg = $story->image ? storage_asset($story->image, 'assets/activities/winter_warmer.jpg') : asset('assets/activities/winter_warmer.jpg');
            $isCaseItem = (str_contains(strtolower($story->category ?? ''), 'case'));
          @endphp
          <article style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04); display: flex; flex-direction: column; transition: transform 0.2s ease, box-shadow 0.2s ease;" 
                   onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 28px rgba(15,23,42,0.08)';" 
                   onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 18px rgba(15,23,42,0.04)';">
            
            <!-- Image Wrap -->
            <div style="width: 100%; height: 210px; background: #f1f5f9; overflow: hidden; position: relative;">
              <a href="{{ route('stories.show', $story->slug) }}" style="display: block; width: 100%; height: 100%;">
                <img src="{{ $storyImg }}" 
                     alt="{{ $story->title }}" 
                     data-live-edit 
                     data-live-content-id="{{ $story->id }}" 
                     data-live-content-field="image" 
                     data-live-type="image" 
                     data-live-title="ছবি: {{ $story->title }}" 
                     data-live-location="stories"
                     style="width: 100%; height: 100%; object-fit: cover;">
              </a>

              <!-- Category Badge -->
              <span data-live-edit 
                    data-live-content-id="{{ $story->id }}" 
                    data-live-content-field="category" 
                    data-live-type="text" 
                    data-live-title="টাইপ: {{ $story->title }}" 
                    data-live-location="stories"
                    style="position: absolute; top: 12px; left: 12px; background: {{ $isCaseItem ? 'rgba(2, 132, 199, 0.92)' : 'rgba(22, 163, 74, 0.92)' }}; color: #ffffff; padding: 4px 11px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; backdrop-filter: blur(4px); box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                {{ $isCaseItem ? ($isBn ? '📊 কেস স্টাডি' : '📊 CASE STUDY') : ($isBn ? '🏆 সাফল্যের গল্প' : '🏆 SUCCESS STORY') }}
              </span>
            </div>

            <!-- Content Body -->
            <div style="padding: 22px; display: flex; flex-direction: column; flex: 1;">
              
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 12px; color: #94a3b8;">
                  📅 {{ $story->created_at ? $story->created_at->format('d M Y') : '' }}
                </span>
                @if(auth()->check() && (auth()->user()?->is_admin || auth()->user()?->can('projects.manage')))
                  <a href="{{ route('admin.content.edit', ['stories', $story->id]) }}" title="এডমিন প্যানেলে এডিট করুন" style="color: #64748b; font-size: 12px; text-decoration: none; padding: 2px 6px; border-radius: 4px; background: #f1f5f9;">
                    ✏️ এডিট
                  </a>
                @endif
              </div>

              <!-- Title -->
              <h3 style="font-size: 17.5px; font-weight: 700; color: #0f172a; line-height: 1.4; margin: 0 0 10px 0;">
                <a href="{{ route('stories.show', $story->slug) }}" 
                   data-live-edit 
                   data-live-content-id="{{ $story->id }}" 
                   data-live-content-field="title" 
                   data-live-type="text" 
                   data-live-title="শিরোনাম: {{ $story->title }}" 
                   data-live-location="stories"
                   style="color: inherit; text-decoration: none;">
                  {{ $story->title }}
                </a>
              </h3>

              <!-- Excerpt -->
              @if(!empty($story->excerpt))
                <p data-live-edit 
                   data-live-content-id="{{ $story->id }}" 
                   data-live-content-field="excerpt" 
                   data-live-type="textarea" 
                   data-live-title="সংক্ষিপ্ত বিবরণ: {{ $story->title }}" 
                   data-live-location="stories"
                   style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0 0 18px 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-align: justify;">
                  {{ $story->excerpt }}
                </p>
              @endif

              <!-- Footer CTA -->
              <div style="margin-top: auto; padding-top: 14px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('stories.show', $story->slug) }}" 
                   style="font-size: 13px; font-weight: 700; color: {{ $isCaseItem ? '#0284c7' : '#16a34a' }}; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                  {{ $isBn ? 'বিস্তারিত পড়ুন →' : 'Read full story →' }}
                </a>
              </div>

            </div>
          </article>
        @endforeach
      </div>

      <!-- Pagination -->
      <div style="margin-top: 40px;">
        {{ $stories->links() }}
      </div>

    @else
      <!-- Empty State -->
      <div style="text-align: center; padding: 70px 20px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1; max-width: 600px; margin: 0 auto;">
        <span style="font-size: 48px; display: block; margin-bottom: 14px;">{{ $isCase ? '📊' : '🏆' }}</span>
        <h3 style="color: #334155; font-size: 20px; margin-bottom: 8px;">
          {{ $isCase ? ($isBn ? 'বর্তমানে কোনো কেস স্টাডি পাওয়া যায়নি।' : 'No case studies published yet.') : ($isBn ? 'বর্তমানে কোনো সাফল্যের গল্প পাওয়া যায়নি।' : 'No success stories published yet.') }}
        </h3>
        <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">
          {{ $isBn ? 'খুব শীঘ্রই নতুন তথ্য প্রকাশ করা হবে।' : 'New stories will be published here soon.' }}
        </p>
        @if(auth()->check() && (auth()->user()?->is_admin || auth()->user()?->can('projects.manage')))
          <a href="{{ route('admin.content.create', 'stories') }}" class="btn" style="background: #16a34a; color: #ffffff; padding: 9px 20px; border-radius: 8px; font-weight: 700; text-decoration: none; display: inline-block;">
            + {{ $isBn ? 'প্রথম স্টোরি যোগ করুন' : 'Add First Story' }}
          </a>
        @else
          <a href="{{ route('success_stories', ['filter' => 'all']) }}" class="btn" style="background: #e2e8f0; color: #334155; padding: 8px 18px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block;">
            {{ $isBn ? 'সব কনটেন্ট দেখুন' : 'View All Stories' }}
          </a>
        @endif
      </div>
    @endif

  </div>
</section>
@endsection
