@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
@extends('site.layout')

@section('title', $isBn ? 'আমাদের সম্পর্কে' : 'About Us')
@section('page_title', $isBn ? 'আমাদের পরিচিতি ও লক্ষ্য' : 'About Our College')
@section('page_subtitle', $isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজের পরিচিতি, লক্ষ্য, উদ্দেশ্য ও প্র্যাকটিক্যাল ল্যাব সুযোগ-সুবিধা।' : 'Practical, skills-focused technical education preparing the youth for national and global employment.')

@section('content')
@php
  $aboutImg = storage_asset($settings['about_image'] ?? null, 'assets/MC Building Photo.jpg');
  $f1_img = storage_asset($settings['facility_1_image'] ?? null, 'assets/Computer Lab r.jpg');
  $f2_img = storage_asset($settings['facility_2_image'] ?? null, 'assets/RAC Lab.jpg');
  $f3_img = storage_asset($settings['facility_3_image'] ?? null, 'assets/Plumbing Class.jpg');
  $f4_img = storage_asset($settings['facility_4_image'] ?? null, 'assets/SMO Lab.jpg');
@endphp

<section class="section" style="padding-top: 40px;">
  <div class="container">
    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 40px; align-items: center; margin-bottom: 50px;">
      <div>
        <span class="section-kicker">{{ $isBn ? 'ইতিহাস ও পটভূমি' : 'OUR HERITAGE & MISSION' }}</span>
        <h2 style="font-family: var(--serif); font-size: 28px; color: var(--green); margin: 6px 0 16px 0;">
          {{ $settings['about_title'] ?? ($isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Mahmudunnabi Chowdhury Technical School and College') }}
        </h2>
        <div class="gold-rule" style="width: 50px; height: 3px; background: var(--gold); margin-bottom: 20px;"></div>
        <div style="font-size: 15px; line-height: 1.8; color: #334155;">
          {!! nl2br(e($settings['about'] ?? ($isBn 
            ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ একটি মানসম্মত কারিগরি ও বৃত্তিমূলক শিক্ষা প্রতিষ্ঠান। আধুনিক প্রযুক্তিনির্ভর ল্যাবরেটরি, অভিজ্ঞ প্রশিক্ষকমণ্ডলী ও যুগোপযোগী কারিকুলামের মাধ্যমে আমরা শিক্ষার্থীদের আন্তর্জাতিক মানের প্র্যাকটিক্যাল দক্ষতায় দক্ষ করে গড়ে তুলছি। বাংলাদেশ কারিগরি শিক্ষা বোর্ড (BTEB) এবং জাতীয় দক্ষতা উন্নয়ন কর্তৃপক্ষ (NSDA) এর মানদণ্ড অনুসারে প্রশিক্ষণ নিশ্চিত করাই আমাদের মূল ব্রত।'
            : 'Mahmudunnabi Chowdhury Technical School and College is dedicated to providing industry-standard technical and vocational training. With modern workshop facilities, experienced instructors, and standardized curriculum aligned with BTEB and NSDA, our goal is to empower trainees with hands-on, marketable skills for a productive career.'))) !!}
        </div>
        <div style="margin-top: 24px; display: flex; gap: 14px; flex-wrap: wrap;">
          <a href="{{ route('apply') }}" class="button button-gold" style="font-weight: 700;">
            {{ $isBn ? 'অনলাইন ভর্তি আবেদন ➔' : 'Apply for Admission ➔' }}
          </a>
          <a href="{{ route('courses') }}" class="button button-green" style="font-weight: 700;">
            {{ $isBn ? 'কোর্সসমূহ দেখুন' : 'View All Courses' }}
          </a>
        </div>
      </div>
      <div>
        <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 12px 30px rgba(0,0,0,0.12); border: 4px solid #fff;">
          <img src="{{ $aboutImg }}" alt="College Campus" style="width: 100%; height: auto; display: block; object-fit: cover;">
        </div>
      </div>
    </div>

    <!-- MISSION & VISION CARDS -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 50px;">
      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-top: 4px solid var(--green); border-radius: 8px; padding: 26px;">
        <div style="font-size: 28px; margin-bottom: 10px;">🎯</div>
        <h3 style="font-family: var(--serif); font-size: 19px; color: var(--green); margin: 0 0 10px 0;">
          {{ $isBn ? 'আমাদের ভিশন (Vision)' : 'Our Vision' }}
        </h3>
        <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0;">
          {{ $isBn ? 'আন্তর্জাতিক মানসম্পন্ন দক্ষ জনশক্তি তৈরির মাধ্যমে দেশের অর্থনৈতিক সমৃদ্ধি অর্জন ও বেকারত্ব দূরীকরণে অগ্রণী ভূমিকা পালন।' : 'To become a premier center of technical excellence, empowering the youth through market-driven vocational skills.' }}
        </p>
      </div>

      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-top: 4px solid var(--gold); border-radius: 8px; padding: 26px;">
        <div style="font-size: 28px; margin-bottom: 10px;">⚙️</div>
        <h3 style="font-family: var(--serif); font-size: 19px; color: var(--green); margin: 0 0 10px 0;">
          {{ $isBn ? 'আমাদের মিশন (Mission)' : 'Our Mission' }}
        </h3>
        <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0;">
          {{ $isBn ? 'প্রত্যেক ট্রেইনীকে বাস্তবমুখী প্র্যাকটিক্যাল ল্যাব ট্রেইনিং, কারিগরি শিক্ষা বোর্ডের সনদ এবং কর্মসংস্থানের সুযোগ নিশ্চিত করা।' : 'Deliver rigorous hands-on technical training adhering to national standards and connect trainees directly with industry careers.' }}
        </p>
      </div>

      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-top: 4px solid var(--green); border-radius: 8px; padding: 26px;">
        <div style="font-size: 28px; margin-bottom: 10px;">🏆</div>
        <h3 style="font-family: var(--serif); font-size: 19px; color: var(--green); margin: 0 0 10px 0;">
          {{ $isBn ? 'মূল্যায়ন ও স্বীকৃতি' : 'Accreditation' }}
        </h3>
        <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0;">
          {{ $isBn ? 'বাংলাদেশ কারিগরি শিক্ষা বোর্ড (BTEB) স্বীকৃত ও অনুমোদিত পাঠ্যক্রম এবং সরকারি প্রজেক্ট বৃত্তি সহায়তা সুবিধা।' : 'Approved training programs aligned with BTEB standards, fully equipped workshop facilities, and scholarship partner programs.' }}
        </p>
      </div>
    </div>

    <!-- CAMPUS WORKSHOP & FACILITIES -->
    <div style="margin-bottom: 50px;">
      <div style="text-align: center; margin-bottom: 30px;">
        <span class="section-kicker">{{ $isBn ? 'আধুনিক অবকাঠামো' : 'INFRASTRUCTURE' }}</span>
        <h2 style="font-family: var(--serif); font-size: 26px; color: var(--green); margin: 6px 0;">
          {{ $isBn ? 'আমাদের অত্যাধুনিক প্র্যাকটিক্যাল ল্যাব ও ওয়ার্কশপ' : 'Modern Labs & Practical Workshops' }}
        </h2>
        <p style="font-size: 14px; color: #64748b; max-width: 600px; margin: 0 auto;">
          {{ $isBn ? 'প্রতিটি কোর্সের জন্য রয়েছে সুসজ্জিত আধুনিক ল্যাব যন্ত্রপাতি ও নিরাপদ কাজের পরিবেশ।' : 'Equipped with real-world machinery and industrial tools to ensure immediate job readiness.' }}
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
          <img src="{{ $f1_img }}" alt="Computer Lab" style="width: 100%; height: 160px; object-fit: cover; display: block;">
          <div style="padding: 14px 16px;">
            <strong style="font-size: 15px; color: #1e293b; display: block; margin-bottom: 4px;">{{ $settings['facility_1_title'] ?? ($isBn ? 'কম্পিউটার ল্যাব' : 'Computer Lab') }}</strong>
            <small style="color: #64748b; font-size: 12px; display: block; line-height: 1.4;">{{ $isBn ? 'হাই-স্পিড ব্রডব্যান্ড ইন্টারনেট এবং আধুনিক পিসি সমৃদ্ধ আইটি ল্যাব।' : 'High-speed broadband network & modern workstations.' }}</small>
          </div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
          <img src="{{ $f2_img }}" alt="RAC Lab" style="width: 100%; height: 160px; object-fit: cover; display: block;">
          <div style="padding: 14px 16px;">
            <strong style="font-size: 15px; color: #1e293b; display: block; margin-bottom: 4px;">{{ $settings['facility_2_title'] ?? ($isBn ? 'রেফ্রিজারেশন ও এসি ল্যাব' : 'RAC Workshop') }}</strong>
            <small style="color: #64748b; font-size: 12px; display: block; line-height: 1.4;">{{ $isBn ? 'ইনভার্টার এসি ও ফ্রিজ মেরামত, গ্যাস চার্জিং এর পূর্ণাঙ্গ সেটআপ।' : 'Full HVAC test stations and refrigerant charging gear.' }}</small>
          </div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
          <img src="{{ $f3_img }}" alt="Plumbing Workshop" style="width: 100%; height: 160px; object-fit: cover; display: block;">
          <div style="padding: 14px 16px;">
            <strong style="font-size: 15px; color: #1e293b; display: block; margin-bottom: 4px;">{{ $settings['facility_3_title'] ?? ($isBn ? 'প্লাম্বিং ওয়ার্কশপ' : 'Plumbing Workshop') }}</strong>
            <small style="color: #64748b; font-size: 12px; display: block; line-height: 1.4;">{{ $isBn ? 'স্যানিটারি ফিটিংস, থ্রেডিং মেশিন ও আধুনিক পাইপলাইন ট্রেইনিং।' : 'PPR, PVC and sanitary installations equipment.' }}</small>
          </div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
          <img src="{{ $f4_img }}" alt="SMO Workshop" style="width: 100%; height: 160px; object-fit: cover; display: block;">
          <div style="padding: 14px 16px;">
            <strong style="font-size: 15px; color: #1e293b; display: block; margin-bottom: 4px;">{{ $settings['facility_4_title'] ?? ($isBn ? 'স্যুইং ও টেইলারিং ল্যাব' : 'Sewing Machine Lab') }}</strong>
            <small style="color: #64748b; font-size: 12px; display: block; line-height: 1.4;">{{ $isBn ? 'ইন্ডাস্ট্রিয়াল প্লেইন লকস্টিচ ও ওভারলক মেশিনে প্র্যাকটিক্যাল কাজ।' : 'Industrial lockstitch and overlock sewing stations.' }}</small>
          </div>
        </div>
      </div>
    </div>

    <!-- QUOTE CALLOUT -->
    <div style="background: linear-gradient(135deg, var(--green-deep), var(--green)); color: #fff; padding: 36px 30px; border-radius: 10px; text-align: center; box-shadow: 0 6px 20px rgba(15,81,50,0.18);">
      <h3 style="font-family: var(--serif); font-size: 24px; font-weight: 700; margin: 0 0 10px 0;">
        {!! nl2br(e($isBn ? "দক্ষ জনশক্তিই গড়ে সমৃদ্ধ বাংলাদেশ" : ($settings['quote_title'] ?? "Skilled People Build a Better Bangladesh"))) !!}
      </h3>
      <div style="width: 40px; height: 3px; background: var(--gold); margin: 12px auto 18px auto;"></div>
      <p style="font-size: 15px; color: rgba(255,255,255,0.85); max-width: 640px; margin: 0 auto 20px auto;">
        {{ $isBn ? 'আজই আপনার পছন্দের কোর্সে ভর্তি হয়ে গড়ে তুলুন একটি নিশ্চিত ও সম্মানজনক ক্যারিয়ার।' : 'Enroll in your chosen technical training program today and build an independent, rewarding career.' }}
      </p>
      <a href="{{ route('apply') }}" class="button button-gold" style="font-weight: 700; padding: 12px 30px;">
        {{ $isBn ? 'ভর্তি আবেদন ফরম পূরণ করুন ➔' : 'Apply Online Now ➔' }}
      </a>
    </div>
  </div>
</section>
@endsection
