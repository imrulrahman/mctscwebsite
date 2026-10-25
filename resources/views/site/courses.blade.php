@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
@extends('site.layout')

@section('title', $isBn ? 'কোর্সসমূহ' : 'Our Training Courses')
@section('page_title', $isBn ? 'আমাদের কারিগরি প্রশিক্ষণ কোর্সসমূহ' : 'Our Technical & Vocational Courses')
@section('page_subtitle', $isBn ? 'বাস্তবমুখী ও স্কিল-ভিত্তিক প্র্যাকটিক্যাল ট্রেইনিং। বাংলাদেশ কারিগরি শিক্ষা বোর্ড (BTEB) স্বীকৃত ও মানসম্মত কারিকুলাম।' : 'Market-relevant, hands-on trade training aligned with BTEB curriculum and industry workplace demands.')

@section('content')
@php
  $courseTranslations = [
    'Computer Operation' => 'কম্পিউটার অপারেশন',
    'Refrigeration and Air Conditioning' => 'রেফ্রিজারেশন এন্ড এয়ারকন্ডিশনিং (RAC)',
    'Plumbing' => 'প্লাম্বিং (পাইপ ফিটিং)',
    'Driving' => 'ড্রাইভিং ট্রেনিং',
    'Sewing Machine Operation' => 'স্যুইং মেশিন অপারেশন (SMO)',
    'Electrical Installation' => 'ইলেকট্রিক্যাল ইনস্টলেশন এন্ড মেইনটেন্যান্স',
  ];
@endphp

<section class="section" style="padding: 40px 0 60px 0;">
  <div class="container">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; flex-wrap: wrap; gap: 14px;">
      <div>
        <span class="section-kicker">{{ $isBn ? 'উপলব্ধ অকুপেশন' : 'AVAILABLE OCCUPATIONS' }}</span>
        <h2 style="font-family: var(--serif); font-size: 26px; color: var(--green); margin: 6px 0 0 0;">
          {{ $isBn ? 'বর্তমানে চলমান ট্রেড কোর্সসমূহ' : 'Currently Active Trade Courses' }}
        </h2>
      </div>
      <div style="font-size: 14px; color: #64748b; display: flex; align-items: center; gap: 16px;">
        <span><strong>{{ $courses->count() }}</strong> {{ $isBn ? 'টি কোর্স উপলব্ধ' : 'Courses Available' }}</span>
        <a href="{{ route('apply') }}" class="button button-gold" style="font-weight: 700; padding: 10px 20px;">
          {{ $isBn ? 'অনলাইন ভর্তি ফরম ➔' : 'Apply for Admission ➔' }}
        </a>
      </div>
    </div>

    <!-- 4-COLUMN COURSE GRID (2 ROWS OF 4 OR ALL COURSES) -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 50px;">
      @forelse($courses as $course)
        @php
          $cImg = storage_asset($course->image, 'assets/MC Building Photo.jpg');
          $cTitle = $isBn ? ($courseTranslations[$course->title] ?? $course->title) : $course->title;
        @endphp
        <div style="background: #ffffff; border: 1px solid #d8e4dc; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,0.05); display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;">
          <div style="position: relative; overflow: hidden;">
            <img src="{{ $cImg }}" alt="{{ $cTitle }}" style="width: 100%; height: 180px; object-fit: cover; display: block;">
          </div>

          <div style="padding: 18px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <h3 style="font-family: var(--serif); font-size: 17px; font-weight: 700; color: #1e3d35; margin: 0 0 8px 0; line-height: 1.35;">
                {{ $cTitle }}
              </h3>
              @if(!empty($course->excerpt))
                <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin: 0 0 14px 0;">
                  {{ $course->excerpt }}
                </p>
              @else
                <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin: 0 0 14px 0;">
                  {{ $isBn ? 'থিওরি ও ল্যাব প্র্যাকটিক্যাল সমন্বিত কোর্স। আধুনিক যন্ত্রপাতি ও ইন্ডাস্ট্রিয়াল ট্রেনিং সুবিধা।' : 'Hands-on practical training with modern laboratory tools and certification.' }}
                </p>
              @endif
            </div>

            <div>
              <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #edf2f0; padding-top: 12px; margin-top: 10px;">
                <span style="font-size: 12px; color: #0f5132; font-weight: 700; background: #e8f5ed; padding: 3px 8px; border-radius: 4px;">
                  {{ $isBn ? 'বৃত্তি ও পেইড ব্যাচ' : 'Govt Scholarship / Paid' }}
                </span>
                <span style="font-size: 12px; color: #64748b;">
                  {{ $isBn ? '৩-৬ মাস মেয়াদী' : '3-6 Months' }}
                </span>
              </div>
              <a href="{{ route('apply') }}?course={{ urlencode($course->title) }}" class="button button-green" style="display: block; text-align: center; margin-top: 14px; font-size: 13px; font-weight: 700; text-decoration: none; border-radius: 6px; padding: 10px 14px;">
                {{ $isBn ? 'ভর্তি আবেদন করুন ➔' : 'Apply for this Course ➔' }}
              </a>
            </div>
          </div>
        </div>
      @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1;">
          <p style="color: #64748b; font-size: 16px;">{{ $isBn ? 'বর্তমানে কোনো কোর্স তালিকাভুক্ত নেই। শীঘ্রই কোর্স যোগ করা হবে।' : 'No courses are currently published. Please check back soon.' }}</p>
        </div>
      @endforelse
    </div>

    <!-- ADMISSION CRITERIA & SCHOLARSHIP NOTE -->
    <div style="background: #f0f7f4; border: 1px solid #cce2d6; border-radius: 10px; padding: 28px 32px; display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; align-items: center;">
      <div>
        <h3 style="font-family: var(--serif); font-size: 20px; color: var(--green); margin: 0 0 10px 0;">
          {{ $isBn ? 'ভর্তি ও প্রজেক্ট স্কলারশিপ সংক্রান্ত তথ্য' : 'Admission & Project Scholarship Information' }}
        </h3>
        <p style="font-size: 14px; color: #334155; line-height: 1.6; margin: 0 0 12px 0;">
          {{ $isBn 
            ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজে সাধারণ পেইড ব্যাচের পাশাপাশি সরকারি বিভিন্ন প্রজেক্ট (যেমন NHRDF, ASSET, ECHO) এর আওতায় ১০০% ফ্রি ও বৃত্তি সুবিধায় ভর্তির সুযোগ রয়েছে। প্রজেক্ট নির্বাচনের মাধ্যমে যোগ্য শিক্ষার্থীরা নির্দিষ্ট সুবিধাপ্রাপ্ত হবেন।'
            : 'Along with our regular standard paid batches, MCTSC also offers admission under various government and donor projects (e.g. NHRDF, ASSET, ECHO) with 100% free tuition and scholarship allowances for eligible trainees.' }}
        </p>
        <span style="font-size: 13px; color: #0f5132; font-weight: 700;">
          {{ $isBn ? '✓ প্রয়োজনীয় কাগজপত্র: পাসপোর্ট সাইজ ছবি, ভোটার আইডি বা জন্ম সনদ এবং শিক্ষাগত যোগ্যতার সনদ।' : '✓ Required documents: Passport size photo, NID / Birth certificate copy, and educational certificates.' }}
        </span>
      </div>
      <div style="text-align: right;">
        <a href="{{ route('apply') }}" class="button button-gold" style="font-size: 15px; font-weight: 700; padding: 14px 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
          {{ $isBn ? 'ভর্তি আবেদন ফরম পূরণ করুন ➔' : 'Proceed to Admission Form ➔' }}
        </a>
      </div>
    </div>
  </div>
</section>
@endsection
