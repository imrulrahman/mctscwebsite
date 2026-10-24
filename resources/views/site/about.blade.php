@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
@extends('site.layout')

@section('title', $isBn ? 'আমাদের সম্পর্কে · এমসি টেকনিক্যাল স্কুল এন্ড কলেজ' : 'About Us · MC Technical School & College')
@section('page_title', $isBn ? 'এমসি টেকনিক্যাল স্কুল এন্ড কলেজ পরিচিতি' : 'About MC Technical School & College')
@section('page_subtitle', $isBn ? 'দক্ষতা অর্জন · সুপ্ত সম্ভাবনার বিকাশ · ভবিষ্যৎ বিনির্মাণ' : 'Building Skills. Unlocking Potential. Shaping Futures.')

@section('content')
@php
  $aboutImg = storage_asset($settings['about_image'] ?? null, 'assets/about_mahmudunnabi_legacy.jpg');
  if (empty($settings['about_image']) || $settings['about_image'] === 'assets/MC Building Photo.jpg') {
      $aboutImg = asset('assets/about_mahmudunnabi_legacy.jpg');
  }
  $f1_img = storage_asset($settings['facility_1_image'] ?? null, 'assets/Computer Lab r.jpg');
  $f2_img = storage_asset($settings['facility_2_image'] ?? null, 'assets/RAC Lab.jpg');
  $f3_img = storage_asset($settings['facility_3_image'] ?? null, 'assets/Plumbing Class.jpg');
  $f4_img = storage_asset($settings['facility_4_image'] ?? null, 'assets/SMO Lab.jpg');
@endphp

<style>
.about-float-media {
  float: right;
  width: 440px;
  max-width: 44%;
  margin: 6px 0 24px 34px;
}
@media (max-width: 768px) {
  .about-float-media {
    float: none !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 0 22px 0 !important;
  }
}
</style>

<section class="section" style="padding-top: 36px; padding-bottom: 50px;">
  <div class="container">

    <!-- 1. INTRO (HERITAGE & FOUNDATION) WITH WRAP-AROUND IMAGE -->
    <div style="margin-bottom: 54px;">
      <span class="section-kicker">{{ $isBn ? 'আমাদের পরিচিতি' : 'ABOUT US' }}</span>
      <h2 style="font-family: var(--serif); font-size: 30px; color: var(--green); margin: 6px 0 8px 0; line-height: 1.25;">
        {{ $isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Mahmudunnabi Chowdhury Technical School & College' }}
      </h2>
      <p style="color: var(--gold); font-weight: 700; font-size: 15px; margin: 0 0 16px 0; letter-spacing: 0.3px;">
        {{ $isBn ? 'দক্ষতা অর্জন · সুপ্ত সম্ভাবনার বিকাশ · ভবিষ্যৎ বিনির্মাণ' : 'Building Skills. Unlocking Potential. Shaping Futures.' }}
      </p>
      <div class="gold-rule" style="width: 50px; height: 3px; background: var(--gold); margin-bottom: 22px;"></div>

      <!-- Floated Image (Text wraps around it and expands full width below) -->
      <div class="about-float-media">
        <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(15,81,50,0.12); border: 1px solid #e2e8f0; background: #ffffff; padding: 6px;">
          <img src="{{ $aboutImg }}" alt="Mahmudunnabi Chowdhury Legacy & College Campus" style="width: 100%; height: auto; display: block; object-fit: contain; border-radius: 8px;">
        </div>
      </div>

      <div style="font-size: 15px; line-height: 1.8; color: #334155; text-align: justify; text-justify: inter-word;">
        @if($isBn)
          <p style="margin-bottom: 14px;">
            <strong>মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ (MCTSC)</strong> চট্টগ্রামের পাহাড়তলী থানাধীন সাগরিকা এলাকায় অবস্থিত একটি বৃত্তিমূলক শিক্ষা ও দক্ষতা উন্নয়ন প্রতিষ্ঠান, যা <strong>ইমপ্যাক্ট ইনিশিয়েটিভ (Impact Initiative-IMI)</strong> কর্তৃক পরিচালিত। ২০০৬ সালে প্রতিষ্ঠিত এ প্রতিষ্ঠানটি অন্তর্ভুক্তি ও বাজার-চাহিদাভিত্তিক কারিগরি ও বৃত্তিমূলক শিক্ষা ও প্রশিক্ষণের (TVET) মাধ্যমে সুবিধাবঞ্চিত ও বেকার তরুণ-তরুণী, পুরুষ ও নারীদের ক্ষমতায়নে প্রতিশ্রুতিবদ্ধ।
          </p>
          <p style="margin-bottom: 14px;">
            প্রায় ২০ বছরের প্রাতিষ্ঠানিক অভিজ্ঞতার মাধ্যমে MCTSC বিভিন্ন পেশা ও ট্রেডে ৭,০০০-এরও বেশি তরুণ-তরুণীকে বাস্তবমুখী কারিগরি দক্ষতায় সজ্জিত করেছে, যা তাদেরকে বেতনভিত্তিক চাকরি, আত্মকর্মসংস্থান এবং উদ্যোক্তা হতে সহায়তা করেছে। আমাদের প্রশিক্ষণ পদ্ধতিতে <strong>৮০% ব্যবহারিক ও হাতে-কলমে শিক্ষা এবং ২০% তাত্ত্বিক নির্দেশনা</strong> প্রদান করা হয়, যা কর্মক্ষেত্রের বাস্তব অভিজ্ঞতা, শিল্পপ্রতিষ্ঠানের সাথে নিবিড় সংযোগ এবং সমাজভিত্তিক প্রশিক্ষণের মাধ্যমে পরিচালিত।
          </p>
          <p style="margin-bottom: 14px;">
            MCTSC সরকারি সংস্থা, উন্নয়ন সহযোগী, শিল্পপ্রতিষ্ঠান এবং জাতীয় ও আন্তর্জাতিক সংস্থার (যার মধ্যে <strong>ASSET, BRAC, UNDP, ILO, SOS Children's Villages</strong> এবং অন্যান্য অংশীদার অন্তর্ভুক্ত) সাথে যৌথভাবে কাজ করে। সংশ্লিষ্ট কারিগরি শিক্ষা ও দক্ষতা উন্নয়ন কর্তৃপক্ষের অধীন একটি অনুমোদিত ও স্বীকৃত প্রশিক্ষণ কেন্দ্র হিসেবে আমরা যোগ্যতাভিত্তিক প্রশিক্ষণ (CBT), পূর্ব অভিজ্ঞতার স্বীকৃতি (RPL) এবং বিভিন্ন কারিগরি ও বৃত্তিমূলক পেশায় স্বল্পমেয়াদী কোর্স পরিচালনা করি।
          </p>
          <p style="margin-bottom: 16px;">
            প্রশিক্ষণ প্রদানের পাশাপাশি গ্র্যাজুয়েটদের টেকসই জীবিকা গড়ে তুলতে আমরা কর্মসংস্থান সহায়তা, ক্যারিয়ার গাইডেন্স, উদ্যোক্তা উন্নয়ন সহায়তা এবং প্রশিক্ষণোত্তর নিয়মিত ফলোআপ প্রদান করি।
          </p>
          <div style="background: rgba(15, 81, 50, 0.06); border-left: 4px solid var(--green); border-radius: 8px; padding: 14px 18px; margin-top: 14px; font-size: 14.5px; line-height: 1.65; color: #163d32;">
            <strong style="color: var(--green);">আমাদের অঙ্গীকার (Our Commitment):</strong> মানসম্মত, অন্তর্ভুক্তিমূলক এবং শিল্প-উপযোগী বৃত্তিমূলক শিক্ষার মাধ্যমে একটি দক্ষ কর্মীবাহিনী গড়ে তোলা, কর্মসংস্থানের সুযোগ সম্প্রসারণ করা, দারিদ্র্য হ্রাস করা এবং বাংলাদেশের টেকসই আর্থ-সামাজিক উন্নয়নে সক্রিয় অবদান রাখা।
          </div>
        @else
          <p style="margin-bottom: 14px;">
            <strong>Mahmudunnabi Chowdhury Technical School and College (MCTSC)</strong> is a vocational education and skills development institution managed by <strong>Impact Initiative-IMI</strong> and based in Sagorika, Chattogram, Bangladesh. Established in 2006, MCTSC is committed to empowering disadvantaged and unemployed youth, men, and women through inclusive, market-oriented technical and vocational education and training (TVET).
          </p>
          <p style="margin-bottom: 14px;">
            With nearly 20 years of institutional experience, MCTSC has equipped more than 7,000 young people with practical skills across various occupations, helping them pursue wage employment, self-employment, and entrepreneurship. Our training approach emphasizes <strong>80% practical learning and 20% theoretical instruction</strong>, supported by real-work experience, industry engagement, and community-based training.
          </p>
          <p style="margin-bottom: 14px;">
            MCTSC works in collaboration with government agencies, development partners, employers, and national and international organizations, including <strong>ASSET, BRAC, UNDP, ILO, SOS Children's Villages</strong>, and other stakeholders. As an affiliated and recognized training centre under relevant technical education and skills development authorities, we offer competency-based training, Recognition of Prior Learning (RPL), and short courses in diverse technical and vocational occupations.
          </p>
          <p style="margin-bottom: 16px;">
            Beyond training, we provide employment support, career guidance, entrepreneurship assistance, and post-employment follow-up to help graduates build sustainable livelihoods.
          </p>
          <div style="background: rgba(15, 81, 50, 0.06); border-left: 4px solid var(--green); border-radius: 8px; padding: 14px 18px; margin-top: 14px; font-size: 14.5px; line-height: 1.65; color: #163d32;">
            <strong style="color: var(--green);">Our Commitment:</strong> To develop a skilled workforce, expand employment opportunities, reduce poverty, and contribute to Bangladesh's sustainable socioeconomic development through quality, inclusive, and industry-responsive vocational education.
          </div>
        @endif
      </div>

      <div style="margin-top: 26px; display: flex; gap: 14px; flex-wrap: wrap;">
        <a href="{{ route('apply') }}" class="button button-gold" style="font-weight: 700; padding: 11px 24px;">
          {{ $isBn ? 'অনলাইন ভর্তি আবেদন ➔' : 'Apply for Admission ➔' }}
        </a>
        <a href="{{ route('courses') }}" class="button button-green" style="font-weight: 700; padding: 11px 24px;">
          {{ $isBn ? 'কোর্সসমূহ দেখুন' : 'Explore Courses' }}
        </a>
      </div>

      <div style="clear: both;"></div>
    </div>

    <!-- 2. HONOURING THE LEGACY OF MAHMUDUNNABI CHOWDHURY -->
    <div style="background: #fdfbf7; border: 1px solid #f1e6d0; border-left: 5px solid var(--gold); border-radius: 10px; padding: 32px 36px; margin-bottom: 48px; box-shadow: 0 4px 18px rgba(197, 155, 39, 0.08);">
      <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px;">
        <span style="font-size: 28px;">🏛️</span>
        <div>
          <span style="font-size: 12px; font-weight: 700; color: var(--gold); letter-spacing: 1.5px; text-transform: uppercase;">
            {{ $isBn ? 'স্মৃতি ও ঐতিহ্য' : 'INSPIRATION & HERITAGE' }}
          </span>
          <h3 style="font-family: var(--serif); font-size: 23px; color: var(--green); margin: 2px 0 0 0;">
            {{ $isBn ? 'মরহুম মাহমুদুন্নবী চৌধুরীর অমর স্মৃতি ও সামাজিক অবদান' : 'Honouring the Legacy of Mahmudunnabi Chowdhury' }}
          </h3>
        </div>
      </div>
      <p style="font-size: 15.5px; line-height: 1.8; color: #475569; margin: 0;">
        @if($isBn)
          <strong>মাহমুদুন্নবী চৌধুরী (১৯০৮–১৯৯৫)</strong> ছিলেন চট্টগ্রামের একজন প্রখ্যাত শিল্পপতি, দানবীর ও রাজনীতিবিদ। তিনি তৎকালীন পূর্ব পাকিস্তান প্রাদেশিক পরিষদের সম্মানিত সদস্য হিসেবে দায়িত্ব পালন করেন এবং প্রাদেশিক সরকারের মন্ত্রী হিসেবে গুরুত্বপূর্ণ ভূমিকা রেখেছিলেন। তাঁর জনকল্যাণমুখী চিন্তা, শিক্ষা বিস্তার এবং সমাজ প্রগতির আজীবন অঙ্গীকারকে ধারণ করেই এ প্রতিষ্ঠানটি নতুন প্রজন্মের জন্য বাস্তবমুখী দক্ষতা বিনির্মাণে নিরলস কাজ করে যাচ্ছে।
        @else
          <strong>Mahmudunnabi Chowdhury (1908–1995)</strong> was a prominent businessman and politician from Chattogram who served as a member of the East Pakistan Legislative Assembly and held ministerial responsibilities. The institution carries forward a commitment to human development and community progress in remembrance of his legacy.
        @endif
      </p>
    </div>

    <!-- 3. DUAL VISION CARDS (FOUNDING VISION & FUTURE VISION) -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 26px; margin-bottom: 48px;">
      <!-- Founding Vision -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-top: 4px solid var(--green); border-radius: 10px; padding: 30px; box-shadow: 0 4px 14px rgba(0,0,0,0.04);">
        <div style="font-size: 32px; margin-bottom: 12px;">🎯</div>
        <h3 style="font-family: var(--serif); font-size: 21px; color: var(--green); margin: 0 0 12px 0;">
          {{ $isBn ? 'আমাদের প্রতিষ্ঠাকালীন লক্ষ্য ও দর্শন' : 'Our Founding Vision' }}
        </h3>
        <p style="font-size: 15px; color: #475569; line-height: 1.75; margin: 0;">
          @if($isBn)
            আমরা বিশ্বাস করি যে মানসম্মত কারিগরি শিক্ষা মানুষের জীবন বদলে দিতে পারে। তরুণ সমাজকে বাস্তব জ্ঞান ও শিল্প-উপযোগী দক্ষতায় দক্ষ করে তোলার মাধ্যমে আমাদের লক্ষ্য তাদেরকে অর্থবহ ও সম্মানজনক কর্মসংস্থান খুঁজে পেতে, আত্মনির্ভরশীলতা অর্জন করতে এবং বাংলাদেশের সামগ্রিক আর্থ-সামাজিক উন্নয়নে সক্রিয় অবদান রাখতে সহায়তা করা।
          @else
            We believe that quality technical education can transform lives. By equipping young people with practical knowledge and industry-relevant skills, we aim to help them pursue meaningful employment, achieve greater self-reliance and contribute to the socioeconomic development of Bangladesh.
          @endif
        </p>
      </div>

      <!-- Vision for the Future -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-top: 4px solid var(--gold); border-radius: 10px; padding: 30px; box-shadow: 0 4px 14px rgba(0,0,0,0.04);">
        <div style="font-size: 32px; margin-bottom: 12px;">🚀</div>
        <h3 style="font-family: var(--serif); font-size: 21px; color: var(--green); margin: 0 0 12px 0;">
          {{ $isBn ? 'ভবিষ্যতের রূপকল্প ও প্রত্যয়' : 'Our Vision for the Future' }}
        </h3>
        <p style="font-size: 15px; color: #475569; line-height: 1.75; margin: 0;">
          @if($isBn)
            আমরা মানসম্মত কারিগরি প্রশিক্ষণের সুযোগকে আরও বিস্তৃত করতে চাই, যাতে শিক্ষার্থীরা বাস্তবমুখী দক্ষতায় সমৃদ্ধ হয়ে টেকসই জীবিকা গড়ে তুলতে পারে। আমাদের চূড়ান্ত লক্ষ্য এমন এক দক্ষ কর্মীবাহিনী গড়ে তোলা যা সমাজকে শক্তিশালী করবে এবং সমৃদ্ধ বাংলাদেশের অগ্রযাত্রাকে বেগবান রাখবে।
          @else
            We aspire to expand access to quality skills training, empower learners with practical competencies and help them build sustainable livelihoods. Our goal is to contribute to a skilled workforce that can strengthen communities and support the continued development of Bangladesh.
          @endif
        </p>
      </div>
    </div>

    <!-- 4. PARTNERSHIP WITH IMPACT INITIATIVE (IMI) -->
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 32px 36px; margin-bottom: 48px;">
      <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px;">
        <span style="font-size: 28px;">🤝</span>
        <div>
          <span style="font-size: 12px; font-weight: 700; color: #166534; letter-spacing: 1.5px; text-transform: uppercase;">
            {{ $isBn ? 'কৌশলগত অংশীদারিত্ব' : 'STRATEGIC PARTNERSHIP' }}
          </span>
          <h3 style="font-family: var(--serif); font-size: 22px; color: #14532d; margin: 2px 0 0 0;">
            {{ $isBn ? 'ইমপ্যাক্ট ইনিশিয়েটিভ (Impact Initiative)-এর সাথে অংশীদারিত্ব' : 'Our Partnership with Impact Initiative' }}
          </h3>
        </div>
      </div>
      <div style="font-size: 15px; line-height: 1.8; color: #166534;">
        @if($isBn)
          <p style="margin-bottom: 12px;">
            <strong>১৯৯৫ সালে</strong> প্রতিষ্ঠিত <strong>ইমপ্যাক্ট ইনিশিয়েটিভ (Impact Initiative - IMI)</strong> বাংলাদেশের একটি শীর্ষস্থানীয় জাতীয় পর্যায়ের মানবিক ও উন্নয়ন সংস্থা। সংস্থাটির কর্মপরিধি যুব উন্নয়ন, শিক্ষা, দক্ষতা বৃদ্ধি এবং জীবিকায়ন সহ বিভিন্ন সামাজিক উন্নয়নমূলক ক্ষেত্রে বিস্তৃত। এর TVET উদ্যোগের মাধ্যমে সংস্থাটি তরুণ সমাজের ব্যবহারিক সক্ষমতা বৃদ্ধি এবং কর্মসংস্থানের সুযোগ সম্প্রসারণে সক্রিয় ভূমিকা পালন করে আসছে।
          </p>
          <p style="margin: 0; font-weight: 600;">
            এই অংশীদারিত্বের মধ্য দিয়ে মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ যুবসমাজের দক্ষতা উন্নয়ন ও কর্মসংস্থানমূলক প্রশিক্ষণ কার্যক্রম নিরবচ্ছিন্নভাবে পরিচালনা করছে।
          </p>
        @else
          <p style="margin-bottom: 12px;">
            Established in <strong>1995</strong>, <strong>Impact Initiative (IMI)</strong> is a national humanitarian and development organization in Bangladesh. Its work spans youth development, education, skills development and livelihoods, among other areas. Through its TVET initiatives, the organization seeks to strengthen young people's practical capabilities and improve their access to employment opportunities.
          </p>
          <p style="margin: 0; font-weight: 600;">
            Through this partnership, MC Technical School & College continues to support skills development and employment-oriented training.
          </p>
        @endif
      </div>
    </div>

    <!-- 5. WHAT WE DO (CORE PILLARS) -->
    <div style="margin-bottom: 50px;">
      <div style="text-align: center; margin-bottom: 30px;">
        <span class="section-kicker">{{ $isBn ? 'কার্যক্রম ও সেবাসমূহ' : 'CORE PILLARS' }}</span>
        <h2 style="font-family: var(--serif); font-size: 28px; color: var(--green); margin: 6px 0;">
          {{ $isBn ? 'আমাদের মূল কার্যক্রম (What We Do)' : 'What We Do' }}
        </h2>
        <div class="gold-rule" style="margin: 10px auto 0 auto; width: 45px; height: 3px; background: var(--gold);"></div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
        <!-- Pillar 1 -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
          <div style="font-size: 32px; margin-bottom: 14px;">🛠️</div>
          <h4 style="font-family: var(--serif); font-size: 17px; color: var(--green); margin: 0 0 8px 0; line-height: 1.3;">
            {{ $isBn ? 'কারিগরি ও বৃত্তিমূলক প্রশিক্ষণ' : 'Technical & Vocational Training' }}
          </h4>
          <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
            {{ $isBn ? 'বাস্তবমুখী ও পেশাভিত্তিক ব্যবহারিক দক্ষতা উন্নয়ন।' : 'Practical, occupation-focused skills development.' }}
          </p>
        </div>

        <!-- Pillar 2 -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
          <div style="font-size: 32px; margin-bottom: 14px;">📋</div>
          <h4 style="font-family: var(--serif); font-size: 17px; color: var(--green); margin: 0 0 8px 0; line-height: 1.3;">
            {{ $isBn ? 'প্রকল্পভিত্তিক প্রশিক্ষণ' : 'Project-Based Training' }}
          </h4>
          <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
            {{ $isBn ? 'জাতীয় ও আন্তর্জাতিক উন্নয়ন প্রকল্পের আওতায় প্রশিক্ষণ প্রদান।' : 'Skills training delivered under relevant development projects.' }}
          </p>
        </div>

        <!-- Pillar 3 -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
          <div style="font-size: 32px; margin-bottom: 14px;">🎖️</div>
          <h4 style="font-family: var(--serif); font-size: 17px; color: var(--green); margin: 0 0 8px 0; line-height: 1.3;">
            {{ $isBn ? 'মূল্যায়ন ও সনদপত্র' : 'Assessment & Certification' }}
          </h4>
          <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
            {{ $isBn ? 'জাতীয় ও কারিগরি শিক্ষা বোর্ডের মানদণ্ড অনুযায়ী মূল্যায়ন ও স্বীকৃতি।' : 'Skills assessment and certification opportunities in accordance with applicable requirements.' }}
          </p>
        </div>

        <!-- Pillar 4 -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
          <div style="font-size: 32px; margin-bottom: 14px;">💼</div>
          <h4 style="font-family: var(--serif); font-size: 17px; color: var(--green); margin: 0 0 8px 0; line-height: 1.3;">
            {{ $isBn ? 'কর্মসংস্থান সংযোগ' : 'Employment Linkages' }}
          </h4>
          <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
            {{ $isBn ? 'অর্জিত দক্ষতাকে সরাসরি কার্যকর কর্মসংস্থানের সুযোগের সাথে যুক্ত করা।' : 'Supporting trainees in connecting their acquired skills with employment opportunities.' }}
          </p>
        </div>
      </div>
    </div>

    <!-- 6. CAMPUS WORKSHOP & FACILITIES -->
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

    <!-- 7. MOTTO & CALLOUT FOOTER -->
    <div style="background: linear-gradient(135deg, var(--green-deep), var(--green)); color: #fff; padding: 38px 30px; border-radius: 12px; text-align: center; box-shadow: 0 6px 20px rgba(15,81,50,0.18);">
      <h3 style="font-family: var(--serif); font-size: 26px; font-weight: 700; margin: 0 0 8px 0; color: #ffffff;">
        MC Technical School & College — {{ $isBn ? 'সুন্দর আগামী বিনির্মাণে বাস্তবমুখী দক্ষতা।' : 'Skills for a Better Tomorrow.' }}
      </h3>
      <div style="width: 45px; height: 3px; background: var(--gold); margin: 12px auto 16px auto;"></div>
      <p style="font-size: 15.5px; color: rgba(255,255,255,0.9); max-width: 680px; margin: 0 auto 22px auto;">
        {{ $isBn ? 'আজই আপনার পছন্দের কোর্সে ভর্তি হয়ে গড়ে তুলুন একটি নিশ্চিত, আত্মনির্ভরশীল ও সম্মানজনক ক্যারিয়ার।' : 'Join our practical vocational courses today to unlock your potential and build a sustainable livelihood.' }}
      </p>
      <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('apply') }}" class="button button-gold" style="font-weight: 700; padding: 12px 32px; font-size: 15px;">
          {{ $isBn ? 'অনলাইন ভর্তি আবেদন ➔' : 'Apply Online Now ➔' }}
        </a>
        <a href="{{ route('contact') }}" class="button" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3); font-weight: 700; padding: 12px 28px;">
          {{ $isBn ? 'যোগাযোগ করুন' : 'Contact Us' }}
        </a>
      </div>
    </div>

  </div>
</section>
@endsection
