@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
@extends('site.layout')

@section('title', $isBn ? 'আমাদের সম্পর্কে · মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'About Us · Mahmudunnabi Chowdhury Technical School and College')
@section('page_title', $isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ পরিচিতি' : 'About Mahmudunnabi Chowdhury Technical School and College')

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

/* Core Values Grid */
.core-val-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
  margin-top: 16px;
}
.core-val-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-left: 4px solid var(--green);
  border-radius: 8px;
  padding: 13px 15px;
  display: flex;
  gap: 12px;
  align-items: flex-start;
  box-shadow: 0 2px 5px rgba(0,0,0,0.02);
  transition: transform 0.2s, box-shadow 0.2s;
}
.core-val-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 14px rgba(15,81,50,0.07);
}
.core-val-icon-box {
  width: 38px;
  height: 38px;
  border-radius: 8px;
  background: rgba(15, 81, 50, 0.08);
  color: var(--green);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.core-val-content h4 {
  font-family: var(--sans);
  font-size: 15px;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 4px 0;
  line-height: 1.3;
}
.core-val-content p {
  font-size: 13.5px;
  line-height: 1.55;
  color: #475569;
  margin: 0;
}
@media (max-width: 768px) {
  .core-val-grid {
    grid-template-columns: 1fr;
    gap: 10px;
  }
}

/* Legal Status */
.legal-status-box {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-top: 4px solid var(--gold);
  border-radius: 10px;
  padding: 32px 34px;
  margin-bottom: 48px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.03);
}
.legal-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14.5px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
}
.legal-table th {
  background: var(--green);
  color: #ffffff;
  padding: 12px 18px;
  text-align: left;
  font-weight: 700;
  font-size: 14px;
}
.legal-table td {
  padding: 12px 18px;
  border-bottom: 1px solid #edf2f7;
  color: #334155;
}
.legal-table tr:nth-child(even) {
  background: #f8fafc;
}
.legal-table tr:hover {
  background: #f1f5f9;
}
.legal-badge {
  display: inline-block;
  font-weight: 700;
  font-family: 'Courier New', Courier, monospace, var(--sans);
  background: rgba(15, 81, 50, 0.08);
  color: var(--green);
  padding: 3px 10px;
  border-radius: 4px;
  border: 1px solid rgba(15, 81, 50, 0.15);
  letter-spacing: 0.5px;
}
@media (max-width: 600px) {
  .legal-status-box {
    padding: 20px 16px;
  }
  .legal-table th, .legal-table td {
    padding: 10px 12px;
    font-size: 13px;
  }
}

/* Industry Partners Grid */
.industry-partners-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin: 20px 0 24px 0;
}
.industry-partner-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 16px 18px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.03);
  transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
}
.industry-partner-card:hover {
  transform: translateY(-2px);
  border-color: var(--green);
  box-shadow: 0 6px 16px rgba(15,81,50,0.09);
}
.industry-partner-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  background: rgba(197, 155, 39, 0.12);
  color: var(--gold-deep);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.industry-partner-name {
  font-family: var(--sans);
  font-size: 14.5px;
  font-weight: 700;
  color: #1e293b;
  line-height: 1.35;
}
@media (max-width: 900px) {
  .industry-partners-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
@media (max-width: 500px) {
  .industry-partners-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<section class="section" style="padding-top: 36px; padding-bottom: 50px;">
  <div class="container">

    <!-- 1. INTRO (HERITAGE & FOUNDATION) WITH WRAP-AROUND IMAGE -->
    <div style="margin-bottom: 54px;">
      <span class="section-kicker">{{ $settings['about_intro_kicker'] ?? ($isBn ? 'ভূমিকা' : 'INTRODUCTION') }}</span>
      <h2 style="font-family: var(--serif); font-size: 30px; color: var(--green); margin: 6px 0 16px 0; line-height: 1.25;">
        {{ $settings['about_intro_title'] ?? ($isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Mahmudunnabi Chowdhury Technical School and College') }}
      </h2>
      <div class="gold-rule" style="width: 50px; height: 3px; background: var(--gold); margin-bottom: 22px;"></div>

      <!-- Floated Image (Text wraps around it and expands full width below) -->
      <div class="about-float-media">
        <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(15,81,50,0.12); border: 1px solid #e2e8f0; background: #ffffff; padding: 6px;">
          <img src="{{ $aboutImg }}" alt="Mahmudunnabi Chowdhury Legacy & College Campus" style="width: 100%; height: auto; display: block; object-fit: contain; border-radius: 8px;">
        </div>
      </div>

      <div style="font-size: 15px; line-height: 1.8; color: #334155; text-align: justify; text-justify: inter-word;">
        @if($isBn && !empty($settings['about_intro_body_bn']))
          @foreach(explode("\n\n", str_replace("\r\n", "\n", $settings['about_intro_body_bn'])) as $p)
            @if(trim($p))
              <p style="margin-bottom: 14px;">{!! nl2br(e(trim($p))) !!}</p>
            @endif
          @endforeach
        @elseif(!empty($settings['about_intro_body']))
          @foreach(explode("\n\n", str_replace("\r\n", "\n", $settings['about_intro_body'])) as $p)
            @if(trim($p))
              <p style="margin-bottom: 14px;">{!! nl2br(e(trim($p))) !!}</p>
            @endif
          @endforeach
        @else
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
            <p style="margin-bottom: 0;">
              প্রশিক্ষণ প্রদানের পাশাপাশি গ্র্যাজুয়েটদের টেকসই জীবিকা গড়ে তুলতে আমরা কর্মসংস্থান সহায়তা, ক্যারিয়ার গাইডেন্স, উদ্যোক্তা উন্নয়ন সহায়তা এবং প্রশিক্ষণোত্তর নিয়মিত ফলোআপ প্রদান করি।
            </p>
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
            <p style="margin-bottom: 0;">
              Beyond training, we provide employment support, career guidance, entrepreneurship assistance, and post-employment follow-up to help graduates build sustainable livelihoods.
            </p>
          @endif
        @endif
      </div>

      <div style="clear: both;"></div>
    </div>

    <!-- 2. CORE VALUES (CMS POWERED & 100% EDITABLE) -->
    <div style="margin-bottom: 48px;">
      <span class="section-kicker">{{ $settings['core_values_kicker'] ?? ($isBn ? 'মূল্যবোধ ও দিকনির্দেশক আদর্শ' : 'GUIDING PRINCIPLES') }}</span>
      <h2 style="font-family: var(--serif); font-size: 28px; color: var(--green); margin: 6px 0 4px 0;">
        {{ $settings['core_values_title'] ?? ($isBn ? 'ইনস্টিটিউট কোর ভ্যালুস (Institute Core Values)' : 'Institute Core Values') }}
      </h2>
      <p style="color: var(--gold); font-weight: 700; font-size: 14.5px; margin: 0 0 14px 0;">
        {{ $settings['core_values_subtitle'] ?? ($isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ (MCTSC)' : 'Mahmudunnabi Chowdhury Technical School and College (MCTSC)') }}
      </p>
      <div class="gold-rule" style="width: 48px; height: 3px; background: var(--gold); margin: 0 0 22px 0;"></div>

      <div class="core-val-grid">
        <!-- 1. Integrity and Accountability -->
        <div class="core-val-card">
          <div class="core-val-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              <polyline points="9 12 11 14 15 10"/>
            </svg>
          </div>
          <div class="core-val-content">
            <h4>1. {{ $settings['core_val_1_title'] ?? ($isBn ? 'সততা ও জবাবদিহিতা (Integrity and Accountability)' : 'Integrity and Accountability') }}</h4>
            <p>{{ $settings['core_val_1_desc'] ?? ($isBn ? 'আমরা আমাদের সকল প্রাতিষ্ঠানিক কার্যক্রম, আর্থিক ব্যবস্থাপনা এবং অংশীজনদের সাথে সম্পর্কে সততা, স্বচ্ছতা, নৈতিক আচরণ ও জবাবদিহিতা বজায় রাখি।' : 'We uphold honesty, transparency, ethical conduct, and accountability in all our institutional activities, financial management, and stakeholder relationships.') }}</p>
          </div>
        </div>

        <!-- 2. Quality and Excellence -->
        <div class="core-val-card">
          <div class="core-val-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
              <path d="M6 12v5c0 2 3 3 6 3s6-1 6-3v-5"/>
            </svg>
          </div>
          <div class="core-val-content">
            <h4>2. {{ $settings['core_val_2_title'] ?? ($isBn ? 'মান ও উৎকর্ষতা (Quality and Excellence)' : 'Quality and Excellence') }}</h4>
            <p>{{ $settings['core_val_2_desc'] ?? ($isBn ? 'আমরা মানসম্পন্ন, যোগ্যতাভিত্তিক কারিগরি ও বৃত্তিমূলক শিক্ষা প্রদানে প্রতিশ্রুতিবদ্ধ যা জাতীয় মানদণ্ড এবং শিল্পের পরিবর্তিত চাহিদার সাথে সামঞ্জস্যপূর্ণ।' : 'We are committed to delivering high-quality, competency-based technical and vocational education that meets national standards and evolving industry requirements.') }}</p>
          </div>
        </div>

        <!-- 3. Inclusion and Equal Opportunity -->
        <div class="core-val-card">
          <div class="core-val-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
          </div>
          <div class="core-val-content">
            <h4>3. {{ $settings['core_val_3_title'] ?? ($isBn ? 'অন্তর্ভুক্তি ও সমান সুযোগ (Inclusion and Equal Opportunity)' : 'Inclusion and Equal Opportunity') }}</h4>
            <p>{{ $settings['core_val_3_desc'] ?? ($isBn ? 'আমরা সুবিধাবঞ্চিত, বেকার, অদক্ষ, এতিম ও ঝুঁকিপূর্ণ তরুণ-তরুণীদের জন্য লিঙ্গ বা আর্থ-সামাজিক পটভূমি নির্বিশেষে দক্ষতা প্রশিক্ষণে সমান সুযোগ নিশ্চিত করি।' : 'We promote equal access to skills training for disadvantaged, unemployed, unskilled, orphaned, and vulnerable youth, regardless of gender or socioeconomic background.') }}</p>
          </div>
        </div>

        <!-- 4. Employability and Entrepreneurship -->
        <div class="core-val-card">
          <div class="core-val-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
          </div>
          <div class="core-val-content">
            <h4>4. {{ $settings['core_val_4_title'] ?? ($isBn ? 'কর্মসংস্থান যোগ্যতা ও উদ্যোক্তা উন্নয়ন (Employability and Entrepreneurship)' : 'Employability and Entrepreneurship') }}</h4>
            <p>{{ $settings['core_val_4_desc'] ?? ($isBn ? 'আমরা গ্র্যাজুয়েটদের চাকরি প্রাপ্তি, ব্যবসা প্রতিষ্ঠা এবং টেকসই জীবিকা অর্জনে সক্ষম করতে বাস্তবমুখী দক্ষতা, পেশাগত যোগ্যতা এবং উদ্যোক্তা সক্ষমতা বিকাশ করি।' : 'We develop practical skills, professional competencies, and entrepreneurial capabilities to enable graduates to secure employment, establish businesses, and achieve sustainable livelihoods.') }}</p>
          </div>
        </div>

        <!-- 5. Collaboration and Partnership -->
        <div class="core-val-card">
          <div class="core-val-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 17l-5-5a3 3 0 0 1 0-4.24l.7-.7a3 3 0 0 1 4.24 0L12 9l2.06-1.94a3 3 0 0 1 4.24 0l.7.7a3 3 0 0 1 0 4.24l-5 5"/>
              <path d="M18 11l2 2a3 3 0 0 1 0 4.24l-2 2a3 3 0 0 1-4.24 0L12 17.5"/>
              <path d="M6 13l-2 2a3 3 0 0 0 0 4.24l2 2a3 3 0 0 0 4.24 0L12 19.5"/>
            </svg>
          </div>
          <div class="core-val-content">
            <h4>5. {{ $settings['core_val_5_title'] ?? ($isBn ? 'সহযোগিতা ও অংশীদারিত্ব (Collaboration and Partnership)' : 'Collaboration and Partnership') }}</h4>
            <p>{{ $settings['core_val_5_desc'] ?? ($isBn ? 'আমরা প্রশিক্ষণের মান ও কর্মসংস্থানের ফলাফল উন্নয়নে সরকারি সংস্থা, নিয়োগকারী প্রতিষ্ঠান, উন্নয়ন সংস্থা, শিল্পপ্রতিষ্ঠান এবং সমাজের সাথে অংশীদারিত্ব জোরদার করি।' : 'We strengthen partnerships with government agencies, employers, development organizations, industries, and communities to improve training quality and employment outcomes.') }}</p>
          </div>
        </div>

        <!-- 6. Innovation and Continuous Learning -->
        <div class="core-val-card">
          <div class="core-val-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 18h6"/>
              <path d="M10 22h4"/>
              <path d="M12 2a7 7 0 0 0-7 7c0 2.38 1.19 4.47 3 5.74V17a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-2.26c1.81-1.27 3-3.36 3-5.74a7 7 0 0 0-7-7z"/>
            </svg>
          </div>
          <div class="core-val-content">
            <h4>6. {{ $settings['core_val_6_title'] ?? ($isBn ? 'উদ্ভাবন ও ধারাবাহিক শিক্ষা (Innovation and Continuous Learning)' : 'Innovation and Continuous Learning') }}</h4>
            <p>{{ $settings['core_val_6_desc'] ?? ($isBn ? 'আমরা উদ্ভাবনী শিক্ষাদান পদ্ধতি, ব্যবহারিক শিক্ষা, প্রশিক্ষক উন্নয়ন, পূর্ব অভিজ্ঞতার স্বীকৃতি (RPL) এবং দক্ষতা উন্নয়নে ধারাবাহিক উৎকর্ষ সাধনে উৎসাহিত করি।' : 'We encourage innovative teaching methods, practical learning, trainer development, Recognition of Prior Learning (RPL), and continuous improvement in skills development.') }}</p>
          </div>
        </div>

        <!-- 7. Respect, Dignity and Safeguarding -->
        <div class="core-val-card">
          <div class="core-val-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
          </div>
          <div class="core-val-content">
            <h4>7. {{ $settings['core_val_7_title'] ?? ($isBn ? 'শ্রদ্ধা, মর্যাদা ও সুরক্ষা (Respect, Dignity and Safeguarding)' : 'Respect, Dignity and Safeguarding') }}</h4>
            <p>{{ $settings['core_val_7_desc'] ?? ($isBn ? 'আমরা একটি নিরাপদ, সম্মানজনক, অন্তর্ভুক্তিমূলক ও সহায়ক শিক্ষার পরিবেশ বজায় রাখি যা সকল প্রশিক্ষণার্থী ও কর্মীদের মর্যাদা, অধিকার ও কল্যাণ নিশ্চিত করে।' : 'We foster a safe, respectful, inclusive, and supportive learning environment that protects the dignity, rights, and well-being of all trainees and staff.') }}</p>
          </div>
        </div>

        <!-- 8. Social Responsibility and Sustainable Development -->
        <div class="core-val-card">
          <div class="core-val-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M7 20h10"/>
              <path d="M10 20c0-5 2-7 5-11"/>
              <path d="M12 20c0-4.5 1-8 7-9-1.5 5-4 8.5-7 9z"/>
              <path d="M8 12c-3.5 0-5-2-5-5 3.5 0 5 2 5 5z"/>
            </svg>
          </div>
          <div class="core-val-content">
            <h4>8. {{ $settings['core_val_8_title'] ?? ($isBn ? 'সামাজিক দায়বদ্ধতা ও টেকসই উন্নয়ন (Social Responsibility and Sustainable Development)' : 'Social Responsibility and Sustainable Development') }}</h4>
            <p>{{ $settings['core_val_8_desc'] ?? ($isBn ? 'আমরা দক্ষতা প্রশিক্ষণ ও কর্মসংস্থান সহায়তার মাধ্যমে দারিদ্র্য হ্রাস, তরুণদের ক্ষমতায়ন, সামাজিক উন্নয়ন এবং টেকসই আর্থ-সামাজিক প্রবৃদ্ধিতে অবদান রাখি।' : 'We contribute to poverty reduction, youth empowerment, community development, and sustainable socioeconomic growth through skills training and employment support.') }}</p>
          </div>
        </div>
      </div>

      <!-- Institutional Commitment -->
      <div style="background: rgba(15, 81, 50, 0.05); border-left: 4px solid var(--green); border-radius: 8px; padding: 18px 24px; margin-top: 26px; font-size: 15px; line-height: 1.7; color: #163d32; box-shadow: 0 2px 8px rgba(15,81,50,0.03);">
        <strong style="color: var(--green); font-size: 15.5px;">{{ $isBn ? 'অঙ্গীকার (Commitment):' : 'Commitment:' }}</strong>
        {{ $settings['about_commitment'] ?? ($isBn 
          ? 'মানসম্মত, অন্তর্ভুক্তিমূলক এবং শিল্প-উপযোগী বৃত্তিমূলক শিক্ষার মাধ্যমে একটি দক্ষ কর্মীবাহিনী গড়ে তোলা, কর্মসংস্থানের সুযোগ সম্প্রসারণ করা, দারিদ্র্য হ্রাস করা এবং বাংলাদেশের টেকসই আর্থ-সামাজিক উন্নয়নে সক্রিয় অবদান রাখা।' 
          : 'To develop a skilled workforce, expand employment opportunities, reduce poverty, and contribute to Bangladesh\'s sustainable socioeconomic development through quality, inclusive, and industry-responsive vocational education.') }}
      </div>
    </div>

    <!-- 3. LEGAL STATUS (CMS POWERED & 100% EDITABLE) -->
    <div class="legal-status-box">
      <span class="section-kicker">{{ $settings['legal_status_kicker'] ?? ($isBn ? 'আইনি ও প্রাতিষ্ঠানিক তথ্য' : 'ACCREDITATION & COMPLIANCE') }}</span>
      <h2 style="font-family: var(--serif); font-size: 27px; color: var(--green); margin: 6px 0 10px 0;">
        {{ $settings['legal_status_title'] ?? ($isBn ? 'আইনি স্বীকৃতি ও স্থিতি (Legal Status)' : 'Legal Status') }}
      </h2>
      <div class="gold-rule" style="width: 45px; height: 3px; background: var(--gold); margin-bottom: 20px;"></div>

      <p style="font-size: 15px; line-height: 1.8; color: #334155; margin-bottom: 22px; text-align: justify;">
        @if($isBn && !empty($settings['legal_status_intro_bn']))
          {{ $settings['legal_status_intro_bn'] }}
        @elseif(!empty($settings['legal_status_intro']))
          {{ $settings['legal_status_intro'] }}
        @else
          {{ $isBn 
            ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ (MCTSC) বাংলাদেশে কারিগরি শিক্ষা ও দক্ষতা উন্নয়নের জন্য প্রাসঙ্গিক আইনি ও প্রাতিষ্ঠানিক কাঠামোর মধ্যে পরিচালিত হয়। প্রতিষ্ঠানটি তার শিক্ষা, প্রশিক্ষণ ও প্রশাসনিক কার্যক্রম পরিচালনার জন্য যথাযথ কর্তৃপক্ষের নিবন্ধন, পরিচিতি ও স্বীকৃতির রেকর্ড সংরক্ষণ করে।' 
            : 'Mahmudunnabi Chowdhury Technical School and College (MCTSC) operates within the relevant regulatory and institutional framework for technical education and skills development in Bangladesh. The institution maintains registration, identification, and accreditation records with the relevant authorities to support its educational, training, and administrative activities.' }}
        @endif
      </p>

      <div style="overflow-x: auto; margin-bottom: 20px;">
        <table class="legal-table">
          <thead>
            <tr>
              <th style="width: 62%;">{{ $isBn ? 'নিয়ন্ত্রক কর্তৃপক্ষ / নথি' : 'Regulatory Authority / Document' }}</th>
              <th style="width: 38%;">{{ $isBn ? 'নিবন্ধন / সনাক্তকরণ নম্বর' : 'Registration / Identification Number' }}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Bangladesh Technical Education Board (BTEB)</strong> – Institute Code</td>
              <td><span class="legal-badge">{{ $settings['legal_status_bteb'] ?? '70102' }}</span></td>
            </tr>
            <tr>
              <td><strong>National Skills Development Authority (NSDA)</strong> – STP Registration</td>
              <td><span class="legal-badge">{{ $settings['legal_status_nsda'] ?? 'STP-CHA-000135' }}</span></td>
            </tr>
            <tr>
              <td><strong>Trade Licence</strong> (Chattogram City Corporation)</td>
              <td><span class="legal-badge" style="background:#f1f5f9; color:#1e293b;">{{ $settings['legal_status_trade'] ?? '68883' }}</span></td>
            </tr>
            <tr>
              <td><strong>Tax Identification Number (TIN)</strong></td>
              <td><span class="legal-badge" style="background:#f1f5f9; color:#1e293b;">{{ $settings['legal_status_tin'] ?? '433576326443' }}</span></td>
            </tr>
            <tr>
              <td><strong>Business Identification Number (BIN/VAT)</strong></td>
              <td><span class="legal-badge" style="background:#f1f5f9; color:#1e293b;">{{ $settings['legal_status_bin'] ?? '001583568' }}</span></td>
            </tr>
          </tbody>
        </table>
      </div>

      <p style="font-size: 14.5px; line-height: 1.7; color: #475569; margin: 0; font-style: italic;">
        @if($isBn && !empty($settings['legal_status_footer_bn']))
          {{ $settings['legal_status_footer_bn'] }}
        @elseif(!empty($settings['legal_status_footer']))
          {{ $settings['legal_status_footer'] }}
        @else
          {{ $isBn 
            ? 'MCTSC তার কারিগরি ও বৃত্তিমূলক শিক্ষা ও প্রশিক্ষণ কর্মসূচিতে আইনি সম্মতি, প্রাতিষ্ঠানিক জবাবদিহিতা, স্বচ্ছতা এবং গুণগত মান বজায় রাখতে প্রতিশ্রুতিবদ্ধ।' 
            : 'MCTSC is committed to maintaining regulatory compliance, institutional accountability, transparency, and quality assurance in its technical and vocational education and training programmes.' }}
        @endif
      </p>
    </div>

    <!-- 4. HONOURING THE LEGACY OF MAHMUDUNNABI CHOWDHURY (CMS POWERED & 100% EDITABLE) -->
    <div style="background: #fdfbf7; border: 1px solid #f1e6d0; border-left: 5px solid var(--gold); border-radius: 10px; padding: 32px 36px; margin-bottom: 48px; box-shadow: 0 4px 18px rgba(197, 155, 39, 0.08);">
      <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px;">
        <span style="font-size: 28px;">🏛️</span>
        <div>
          <span style="font-size: 12px; font-weight: 700; color: var(--gold); letter-spacing: 1.5px; text-transform: uppercase;">
            {{ $settings['patron_kicker'] ?? ($isBn ? 'স্মৃতি ও ঐতিহ্য' : 'INSPIRATION & HERITAGE') }}
          </span>
          <h3 style="font-family: var(--serif); font-size: 23px; color: var(--green); margin: 2px 0 0 0;">
            {{ $settings['patron_title'] ?? ($isBn ? 'মরহুম মাহমুদুন্নবী চৌধুরীর অমর স্মৃতি ও সামাজিক অবদান' : 'Honouring the Legacy of Mahmudunnabi Chowdhury') }}
          </h3>
        </div>
      </div>
      <p style="font-size: 15.5px; line-height: 1.8; color: #475569; margin: 0;">
        @if($isBn && !empty($settings['patron_text_bn']))
          {!! nl2br(e($settings['patron_text_bn'])) !!}
        @elseif(!empty($settings['patron_text']))
          {!! nl2br(e($settings['patron_text'])) !!}
        @else
          @if($isBn)
            <strong>মাহমুদুন্নবী চৌধুরী (১৯০৮–১৯৯৫)</strong> ছিলেন চট্টগ্রামের একজন প্রখ্যাত শিল্পপতি, দানবীর ও রাজনীতিবিদ। তিনি তৎকালীন পূর্ব পাকিস্তান প্রাদেশিক পরিষদের সম্মানিত সদস্য হিসেবে দায়িত্ব পালন করেন এবং প্রাদেশিক সরকারের মন্ত্রী হিসেবে গুরুত্বপূর্ণ ভূমিকা রেখেছিলেন। তাঁর জনকল্যাণমুখী চিন্তা, শিক্ষা বিস্তার এবং সমাজ প্রগতির আজীবন অঙ্গীকারকে ধারণ করেই এ প্রতিষ্ঠানটি নতুন প্রজন্মের জন্য বাস্তবমুখী দক্ষতা বিনির্মাণে নিরলস কাজ করে যাচ্ছে।
          @else
            <strong>Mahmudunnabi Chowdhury (1908–1995)</strong> was a prominent businessman and politician from Chattogram who served as a member of the East Pakistan Legislative Assembly and held ministerial responsibilities. The institution carries forward a commitment to human development and community progress in remembrance of his legacy.
          @endif
        @endif
      </p>
    </div>

    <!-- 7. INDUSTRY PARTNERSHIPS FOR EMPLOYMENT AND CAREER DEVELOPMENT -->
    @php
      $indListRaw = $settings['industry_partners_list'] ?? "Alert Fashion Ltd.\nMellow Fashion Ltd.\nSalim & Brothers Ltd.\nAstech Ltd.\nHB Group\nHB Intimates Ltd.\nFashion of Wales Ltd.\nHotel New View";
      $indPartners = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $indListRaw))));
    @endphp
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-top: 4px solid var(--green); border-radius: 10px; padding: 34px 36px; margin-bottom: 48px; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
      <div style="margin-bottom: 18px;">
        <span class="section-kicker" style="color: var(--gold);">{{ $settings['industry_partners_kicker'] ?? ($isBn ? 'শিল্প সংযোগ ও ক্যারিয়ার উন্নয়ন' : 'EMPLOYMENT & CAREER DEVELOPMENT') }}</span>
        <h2 style="font-family: var(--serif); font-size: 26px; color: var(--green); margin: 6px 0 10px 0; line-height: 1.3;">
          <em>{{ $settings['industry_partners_title'] ?? ($isBn ? 'কর্মসংস্থান ও ক্যারিয়ার উন্নয়নে শিল্প অংশীদারিত্ব' : 'Industry Partnerships for Employment and Career Development') }}</em>
        </h2>
        <div class="gold-rule" style="width: 45px; height: 3px; background: var(--gold); margin: 0 0 18px 0;"></div>
      </div>

      <div style="font-size: 15px; line-height: 1.8; color: #334155; text-align: justify; margin-bottom: 24px;">
        <p style="margin-bottom: 14px;">
          {{ $settings['industry_partners_intro_1'] ?? ($isBn 
            ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ (MCTSC) গ্র্যাজুয়েটদের কর্মসংস্থান যোগ্যতা বৃদ্ধি এবং টেকসই ক্যারিয়ারের সুযোগ সৃষ্টির লক্ষ্যে বিভিন্ন শিল্পপ্রতিষ্ঠান, নিয়োগকর্তা এবং সেবা খাতের সংস্থাগুলির সাথে কার্যকর সহযোগিতাপূর্ণ সম্পর্ক বজায় রাখে। এই অংশীদারিত্বের মাধ্যমে MCTSC চাকরি প্রাপ্তি, কর্মক্ষেত্রভিত্তিক শিক্ষা, শিল্প সংযোগ এবং বাজার-চাহিদাভিত্তিক দক্ষতা বিকাশে সহায়তা করে।' 
            : 'Mahmudunnabi Chowdhury Technical School and College (MCTSC) maintains collaborative relationships with industries, employers, and service-sector organizations to enhance graduate employability and promote sustainable career opportunities. Through these partnerships, MCTSC supports job placement, workplace-based learning, industry exposure, and the development of market-relevant skills.') }}
        </p>
        <p style="margin: 0;">
          {{ $settings['industry_partners_intro_2'] ?? ($isBn 
            ? 'আমাদের শিল্প অংশীদারগণ বৃত্তিমূলক প্রশিক্ষণ এবং শ্রমবাজারের চাহিদার মধ্যকার ব্যবধান দূর করতে গুরুত্বপূর্ণ অবদান রাখছেন, যা গ্র্যাজুয়েটদের বেতনভিত্তিক চাকরি, আত্মকর্মসংস্থান এবং দীর্ঘমেয়াদী ক্যারিয়ার উন্নয়নে উত্তরণে সহায়তা করে।' 
            : 'Our industry partners contribute to bridging the gap between vocational training and labour market demands, helping graduates transition into wage employment, self-employment, and long-term career development.') }}
        </p>
      </div>

      <h3 style="font-family: var(--serif); font-size: 19px; color: var(--green); margin: 0 0 14px 0;">
        {{ $settings['industry_partners_list_title'] ?? ($isBn ? 'শিল্প ও কর্মসংস্থান অংশীদারগণ' : 'Our Industry and Employment Partners') }}
      </h3>

      <div class="industry-partners-grid">
        @foreach($indPartners as $partner)
          <div class="industry-partner-card">
            <div class="industry-partner-icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
              </svg>
            </div>
            <div>
              <div class="industry-partner-name">{{ $partner }}</div>
              <small style="color: #64748b; font-size: 11.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">{{ $isBn ? 'শিল্প অংশীদার' : 'Industry Partner' }}</small>
            </div>
          </div>
        @endforeach
      </div>

      <div style="background: rgba(15, 81, 50, 0.05); border-left: 4px solid var(--green); border-radius: 8px; padding: 18px 24px; margin-top: 22px; font-size: 15px; line-height: 1.7; color: #163d32; box-shadow: 0 2px 8px rgba(15,81,50,0.03);">
        <strong style="color: var(--green); font-size: 15.5px;">{{ $isBn ? 'আমাদের অঙ্গীকার (Our Commitment):' : 'Our Commitment:' }}</strong>
        {{ $settings['industry_partners_commitment'] ?? ($isBn 
          ? 'শিল্প সংযোগ জোরদার করা, কর্মসংস্থানের সুযোগ সম্প্রসারণ করা এবং একটি উৎপাদনশীল ও টেকসই কর্মীবাহিনীর জন্য দক্ষ গ্র্যাজুয়েটদের নিয়োগকর্তাদের সাথে যুক্ত করা।' 
          : 'To strengthen industry linkages, expand employment opportunities, and connect skilled graduates with employers for a productive and sustainable workforce.') }}
      </div>
    </div>

    <!-- 7. WHAT WE DO (CORE PILLARS - CMS POWERED & 100% EDITABLE) -->
    <div style="margin-bottom: 50px;">
      <div style="text-align: center; margin-bottom: 30px;">
        <span class="section-kicker">{{ $settings['pillars_kicker'] ?? ($isBn ? 'কার্যক্রম ও সেবাসমূহ' : 'CORE PILLARS') }}</span>
        <h2 style="font-family: var(--serif); font-size: 28px; color: var(--green); margin: 6px 0;">
          {{ $settings['pillars_title'] ?? ($isBn ? 'মূল কার্যক্রম (What We Do)' : 'What We Do') }}
        </h2>
        <div class="gold-rule" style="margin: 10px auto 0 auto; width: 45px; height: 3px; background: var(--gold);"></div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
        <!-- Pillar 1 -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
          <div style="font-size: 32px; margin-bottom: 14px;">🛠️</div>
          <h4 style="font-family: var(--serif); font-size: 17px; color: var(--green); margin: 0 0 8px 0; line-height: 1.3;">
            {{ $settings['pillar_1_title'] ?? ($isBn ? 'কারিগরি ও বৃত্তিমূলক প্রশিক্ষণ' : 'Technical & Vocational Training') }}
          </h4>
          <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
            {{ $settings['pillar_1_desc'] ?? ($isBn ? 'বাস্তবমুখী ও পেশাভিত্তিক ব্যবহারিক দক্ষতা উন্নয়ন।' : 'Practical, occupation-focused skills development.') }}
          </p>
        </div>

        <!-- Pillar 2 -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
          <div style="font-size: 32px; margin-bottom: 14px;">📋</div>
          <h4 style="font-family: var(--serif); font-size: 17px; color: var(--green); margin: 0 0 8px 0; line-height: 1.3;">
            {{ $settings['pillar_2_title'] ?? ($isBn ? 'প্রকল্পভিত্তিক প্রশিক্ষণ' : 'Project-Based Training') }}
          </h4>
          <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
            {{ $settings['pillar_2_desc'] ?? ($isBn ? 'জাতীয় ও আন্তর্জাতিক উন্নয়ন প্রকল্পের আওতায় প্রশিক্ষণ প্রদান।' : 'Skills training delivered under relevant development projects.') }}
          </p>
        </div>

        <!-- Pillar 3 -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
          <div style="font-size: 32px; margin-bottom: 14px;">🎖️</div>
          <h4 style="font-family: var(--serif); font-size: 17px; color: var(--green); margin: 0 0 8px 0; line-height: 1.3;">
            {{ $settings['pillar_3_title'] ?? ($isBn ? 'মূল্যায়ন ও সনদপত্র' : 'Assessment & Certification') }}
          </h4>
          <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
            {{ $settings['pillar_3_desc'] ?? ($isBn ? 'জাতীয় ও কারিগরি শিক্ষা বোর্ডের মানদণ্ড অনুযায়ী মূল্যায়ন ও স্বীকৃতি।' : 'Skills assessment and certification opportunities in accordance with applicable requirements.') }}
          </p>
        </div>

        <!-- Pillar 4 -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
          <div style="font-size: 32px; margin-bottom: 14px;">💼</div>
          <h4 style="font-family: var(--serif); font-size: 17px; color: var(--green); margin: 0 0 8px 0; line-height: 1.3;">
            {{ $settings['pillar_4_title'] ?? ($isBn ? 'কর্মসংস্থান সংযোগ' : 'Employment Linkages') }}
          </h4>
          <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin: 0;">
            {{ $settings['pillar_4_desc'] ?? ($isBn ? 'অর্জিত দক্ষতাকে সরাসরি কার্যকর কর্মসংস্থানের সুযোগের সাথে যুক্ত করা।' : 'Supporting trainees in connecting their acquired skills with employment opportunities.') }}
          </p>
        </div>
      </div>
    </div>

    <!-- 8. CAMPUS WORKSHOP & FACILITIES -->
    <div>
      <div style="text-align: center; margin-bottom: 30px;">
        <span class="section-kicker">{{ $isBn ? 'আধুনিক অবকাঠামো' : 'INFRASTRUCTURE' }}</span>
        <h2 style="font-family: var(--serif); font-size: 26px; color: var(--green); margin: 6px 0;">
          {{ $isBn ? 'অত্যাধুনিক প্র্যাকটিক্যাল ল্যাব ও ওয়ার্কশপ' : 'Modern Labs & Practical Workshops' }}
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

  </div>
</section>
@endsection
