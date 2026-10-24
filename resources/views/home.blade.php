@php
  $isBn = ($currentLocale ?? 'en') === 'bn';
@endphp
<!doctype html>
<html lang="{{ $isBn ? 'bn' : 'en' }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="{{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }} — practical, skills-focused technical training.">
  <title>{{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }}{{ $isBn ? ' · টেকনিক্যাল স্কুল এন্ড কলেজ' : '' }}</title>
  <link rel="stylesheet" href="{{ asset('css/site.css') }}">
  <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
  @php
    $siteLogo = storage_asset($settings->get('site_logo'), 'assets/MC Logo.png');
    $heroBanner = storage_asset($settings->get('hero_banner'), 'assets/mc Banner.jpg');
    $aboutImg = storage_asset($settings->get('about_image'), 'assets/MC Building Photo.jpg');
    
    $f1_img = storage_asset($settings->get('facility_1_image'), 'assets/Computer Lab r.jpg');
    $f2_img = storage_asset($settings->get('facility_2_image'), 'assets/RAC Lab.jpg');
    $f3_img = storage_asset($settings->get('facility_3_image'), 'assets/Plumbing Class.jpg');
    $f4_img = storage_asset($settings->get('facility_4_image'), 'assets/SMO Lab.jpg');

    $primaryColor = $settings->get('primary_color', '#0f5132');
    $primaryHover = $settings->get('primary_hover', '#093722');
    $secondaryColor = $settings->get('secondary_color', '#c59b27');
    $secondaryHover = $settings->get('secondary_hover', '#a37d18');

    $courseTranslations = [
      'Computer Operation' => 'কম্পিউটার অপারেশন',
      'Refrigeration and Air Conditioning' => 'রেফ্রিজারেশন এন্ড এয়ারকন্ডিশনিং (RAC)',
      'Plumbing' => 'প্লাম্বিং (পাইপ ফিটিং)',
      'Driving' => 'ড্রাইভিং ট্রেনিং',
      'Sewing Machine Operation' => 'স্যুইং মেশিন অপারেশন (SMO)',
      'Electrical Installation' => 'ইলেকট্রিক্যাল ইনস্টলেশন এন্ড মেইনটেন্যান্স',
    ];

    $facilityTranslations = [
      'Computer Lab' => 'কম্পিউটার ল্যাব',
      'Refrigeration and AC Workshop' => 'রেফ্রিজারেশন ও এসি ওয়ার্কশপ',
      'Plumbing Workshop' => 'প্লাম্বিং ওয়ার্কশপ',
      'Tailoring and Sewing Workshop' => 'টেইলারিং ও স্যুইং ল্যাব',
    ];

    $noticeTranslations = [
      'Admission process for the next session' => 'নতুন সেশনে ট্রেইনী এডমিশন কার্যক্রম শুরু',
      'Practical class schedules' => 'প্র্যাকটিক্যাল ক্লাসের সময়সূচী',
      'College notice board is now online' => 'কলেজের অনলাইন নোটিশ বোর্ড চালু প্রসঙ্গে',
    ];

    $eventTranslations = [
      'Orientation Program' => 'নতুন ট্রেইনী ওরিয়েন্টেশন প্রোগ্রাম',
      'Practical Exam' => 'সেমিস্টার প্র্যাকটিক্যাল পরীক্ষা',
      'Cultural Program' => 'বার্ষিক সাংস্কৃতিক ও পুরস্কার বিতরণী',
    ];

    $toRgba = function (?string $hex, float $alpha = 1.0): string {
      if (!$hex) {
        return "rgba(15, 81, 50, {$alpha})";
      }
      $hex = ltrim($hex, '#');
      if (strlen($hex) === 3) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
      } elseif (strlen($hex) === 6) {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
      } else {
        return "rgba(15, 81, 50, {$alpha})";
      }
      return "rgba({$r}, {$g}, {$b}, {$alpha})";
    };
  @endphp
  <style>
    :root {
      --green: {{ $primaryColor }};
      --green-deep: {{ $primaryHover }};
      --gold: {{ $secondaryColor }};
      --gold-deep: {{ $secondaryHover }};
      --mint: {{ $toRgba($primaryColor, 0.05) }};
      --ink: {{ $primaryHover }};
      --line: {{ $toRgba($primaryColor, 0.15) }};
    }
    .brand { color: {{ $primaryColor }} !important; }
    .menu-toggle { color: {{ $primaryColor }} !important; }
    .button-green { background: {{ $primaryColor }} !important; }
    .button-green:hover { background: {{ $primaryHover }} !important; }
    .button-gold { background: {{ $secondaryColor }} !important; color: #000 !important; }
    .button-gold:hover { background: {{ $secondaryHover }} !important; }

    /* Header responsive & brand wrapping fix */
    .site-header {
      min-height: 78px !important;
      height: auto !important;
      padding: 6px 0 !important;
    }
    .nav-wrap {
      min-height: 66px !important;
    }
    .brand {
      max-width: 400px !important;
      font-size: 15px !important;
      line-height: 1.25 !important;
      text-decoration: none !important;
    }
    @media (max-width: 1200px) {
      .brand {
        max-width: 320px !important;
        font-size: 13.5px !important;
      }
    }

    /* Hero Overlay - Completely removed as requested by user */
    .hero-overlay {
      display: none !important;
    }

    /* Hero Institution Banner & Prominent Title */
    .hero {
      position: relative;
      min-height: 380px !important;
      display: flex;
      align-items: flex-start;
    }
    .hero-inst-banner {
      max-width: 680px;
      padding: 12px 0 24px 0;
    }
    .hero-inst-name {
      font-family: var(--serif);
      font-weight: 700;
      font-size: 38px;
      line-height: 1.22;
      color: #ffffff;
      text-shadow: 0 3px 12px rgba(0, 0, 0, 0.85), 0 1px 4px rgba(0, 0, 0, 0.95);
      margin: 0;
      letter-spacing: -0.3px;
    }
    @media (max-width: 768px) {
      .hero-inst-name {
        font-size: 26px;
        line-height: 1.25;
      }
      .hero {
        min-height: 300px !important;
      }
    }

    /* 2 Rows of 4 Cards (total 8), significantly larger cards & images */
    .course-grid {
      display: grid !important;
      grid-template-columns: repeat(4, 1fr) !important;
      gap: 20px !important;
    }
    .course-card {
      border-radius: 8px !important;
      overflow: hidden !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
      transition: transform 0.22s ease, box-shadow 0.22s ease !important;
      background: #ffffff !important;
      display: flex !important;
      flex-direction: column !important;
      text-decoration: none !important;
    }
    .course-card:hover {
      transform: translateY(-6px) !important;
      box-shadow: 0 12px 24px rgba(15, 81, 50, 0.16) !important;
    }
    .course-card img {
      width: 100% !important;
      height: 165px !important;
      object-fit: cover !important;
      display: block !important;
    }
    .course-card b {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      text-align: center !important;
      padding: 14px 12px !important;
      font: 700 15px/1.35 var(--serif) !important;
      color: #1e3d35 !important;
      min-height: 58px !important;
      background: #ffffff !important;
    }
    @media (max-width: 960px) {
      .course-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 14px !important;
      }
    }
    @media (max-width: 520px) {
      .course-grid {
        grid-template-columns: 1fr !important;
      }
    }

    /* Notice Bar bullet fix */
    .notice-scroll span:before {
      content: '\2022\a0 ' !important;
      color: {{ $secondaryColor }} !important;
      font-weight: 700;
    }

    /* Section Overrides to adopt chosen theme */
    .about-copy h2, .section-heading h2, .lower-grid h3 {
      color: {{ $primaryColor }} !important;
    }
    .about-aside {
      background: {{ $toRgba($primaryColor, 0.06) }} !important;
      color: {{ $primaryColor }} !important;
    }
    .courses-section, .gallery-section {
      background: {{ $toRgba($primaryColor, 0.04) }} !important;
    }
    .course-card:hover {
      box-shadow: 0 7px 18px {{ $toRgba($primaryColor, 0.15) }} !important;
    }
    .project-card {
      background: #ffffff !important;
      border: 1px solid {{ $toRgba($primaryColor, 0.12) }} !important;
    }
    .project-card i {
      background: {{ $primaryColor }} !important;
      color: #ffffff !important;
    }
    .admission-band {
      background: {{ $toRgba($primaryColor, 0.08) }} !important;
      border-top: 1px solid {{ $toRgba($primaryColor, 0.12) }};
      border-bottom: 1px solid {{ $toRgba($primaryColor, 0.12) }};
    }
    .admission-icon {
      background: {{ $primaryColor }} !important;
      color: #ffffff !important;
    }
    .admission-links {
      color: {{ $primaryColor }} !important;
    }
    .result.green {
      background: {{ $primaryColor }} !important;
    }
    .result.gold {
      background: {{ $secondaryColor }} !important;
    }

    /* Dynamic Quote Card - Matches selected theme gradient perfectly */
    .quote-card {
      background: linear-gradient(145deg, {{ $toRgba($primaryColor, 0.85) }}, {{ $toRgba($primaryHover, 0.96) }}), url('{{ $aboutImg }}') center/cover !important;
      border-radius: 4px;
    }

    .lightbox {
      background: {{ $toRgba($primaryHover, 0.95) }} !important;
    }

    /* Standard, Compact & Elegant Footer */
    .site-footer {
      background: {{ $primaryHover }} !important;
      color: #ffffff !important;
      padding-top: 18px !important;
      position: relative;
    }
    .site-footer .footer-main {
      display: grid !important;
      grid-template-columns: 1.15fr 1.7fr 0.65fr !important;
      gap: 26px !important;
      padding: 0 0 12px !important;
      align-items: center !important;
    }
    .site-footer .footer-brand {
      display: flex;
      gap: 12px;
      align-items: center;
    }
    .site-footer .footer-brand img {
      width: 44px;
      height: 44px;
      object-fit: contain;
      background: #ffffff;
      padding: 3px;
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      flex-shrink: 0;
    }
    .site-footer .footer-brand strong {
      display: block;
      font: 700 14px/1.3 var(--serif);
      color: #ffffff;
    }
    .site-footer .footer-contact-block {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .site-footer .contact-row-top {
      display: flex;
      align-items: flex-start;
      gap: 7px;
    }
    .site-footer .contact-row-sub {
      display: flex;
      gap: 24px;
      align-items: flex-start;
      flex-wrap: wrap;
    }
    .site-footer .contact-sub-item {
      display: flex;
      align-items: flex-start;
      gap: 7px;
    }
    .site-footer .c-icon {
      color: var(--gold);
      flex-shrink: 0;
      margin-top: 2px;
    }
    .site-footer small {
      display: block;
      font-size: 11px;
      color: rgba(255, 255, 255, 0.75);
      line-height: 1.2;
    }
    .site-footer strong {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: #ffffff;
      line-height: 1.3;
    }
    .site-footer .footer-social-col {
      border-left: 1px solid rgba(255, 255, 255, 0.16);
      padding-left: 20px;
      display: flex;
      flex-direction: column;
      gap: 3px;
    }
    .site-footer .footer-social-col small {
      font-size: 11px;
      color: rgba(255, 255, 255, 0.75);
    }
    .site-footer .social-text-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #ffffff;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      transition: color 0.15s;
    }
    .site-footer .social-text-link:hover {
      color: var(--gold);
    }
    .site-footer .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.12) !important;
      padding: 8px 0 !important;
      display: flex !important;
      justify-content: space-between !important;
      align-items: center !important;
      font-size: 11px !important;
      color: rgba(255, 255, 255, 0.65) !important;
      flex-wrap: wrap !important;
      gap: 8px !important;
    }
    @media (max-width: 900px) {
      .site-footer .footer-main {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
      }
      .site-footer .footer-social-col {
        border-left: 0 !important;
        padding-left: 0 !important;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        padding-top: 10px;
      }
    }

    /* Skeuomorphic Flag Language Switcher (matches user design) */
    .header-right-group {
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .lang-switch-box {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      user-select: none;
      flex-shrink: 0;
      vertical-align: middle;
    }
    .lang-switch-box .lang-code {
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 0.3px;
      text-decoration: none;
      transition: color 0.2s ease, transform 0.15s ease;
      line-height: 1;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .lang-switch-box .lang-code.active {
      color: #1e293b;
      font-weight: 900;
    }
    .lang-switch-box .lang-code.inactive {
      color: #94a3b8;
    }
    .lang-switch-box .lang-code:hover {
      color: #0f172a;
    }
    .toggle-slider-track {
      position: relative;
      display: inline-block;
      width: 52px;
      height: 27px;
      background: #e2e8f0;
      border-radius: 999px;
      border: 1px solid #cbd5e1;
      box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2), inset 0 1px 2px rgba(0, 0, 0, 0.12);
      box-sizing: border-box;
      padding: 2px;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .toggle-slider-track:hover {
      border-color: #94a3b8;
      box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.26), inset 0 1px 2px rgba(0, 0, 0, 0.15);
    }
    .toggle-slider-knob {
      width: 21px;
      height: 21px;
      border-radius: 50%;
      background: #ffffff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.32), 0 1px 2px rgba(0, 0, 0, 0.2);
      border: 1.5px solid #ffffff;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      transition: transform 0.24s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .toggle-slider-knob svg {
      width: 100%;
      height: 100%;
      display: block;
      border-radius: 50%;
      pointer-events: none;
    }
    /* When English: knob at left */
    .toggle-slider-track.is-en .toggle-slider-knob {
      transform: translateX(0);
    }
    /* When Bengali: knob at right */
    .toggle-slider-track.is-bn .toggle-slider-knob {
      transform: translateX(25px);
    }
    @media (max-width: 680px) {
      .header-right-group {
        gap: 8px;
      }
      .lang-switch-box {
        gap: 5px;
      }
      .lang-switch-box .lang-code {
        font-size: 11px;
      }
      .toggle-slider-track {
        width: 44px;
        height: 23px;
        padding: 2px;
      }
      .toggle-slider-knob {
        width: 17px;
        height: 17px;
      }
      .toggle-slider-track.is-bn .toggle-slider-knob {
        transform: translateX(21px);
      }
    }
  </style>
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="{{ route('home') }}">
      <img src="{{ $siteLogo }}" alt="{{ $settings->get('institution_name', 'MCTSC') }} logo">
      <span>{{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }}</span>
    </a>
    <div class="header-right-group">
      <button class="menu-toggle" aria-label="Open navigation" aria-expanded="false">☰</button>
      <nav class="main-nav" aria-label="Primary navigation">
        <a class="active" href="{{ route('home') }}">{{ $isBn ? 'হোম' : 'Home' }}</a>
        <a href="{{ route('about') }}">{{ $isBn ? 'আমাদের সম্পর্কে' : 'About' }}</a>
        <a href="{{ route('courses') }}">{{ $isBn ? 'কোর্সসমূহ' : 'Courses' }}</a>
        <a href="{{ route('projects') }}">{{ $isBn ? 'প্রজেক্টস' : 'Projects' }}</a>
        <a href="{{ route('apply') }}">{{ $isBn ? 'এডমিশন' : 'Admissions' }}</a>
        <a href="{{ route('notices') }}">{{ $isBn ? 'নোটিশ' : 'Notices' }}</a>
        <a href="{{ route('gallery') }}">{{ $isBn ? 'গ্যালারি' : 'Gallery' }}</a>
        <a href="{{ route('contact') }}">{{ $isBn ? 'যোগাযোগ' : 'Contact' }}</a>
        <a class="nav-cta" href="{{ route('apply') }}">{{ $isBn ? 'এপ্লাই নাও' : 'Apply Now' }}</a>
      </nav>
      <!-- SKEUOMORPHIC FLAG LANGUAGE SWITCHER (EN [ Flag Knob ] বাং) -->
      <div class="lang-switch-box" title="{{ $isBn ? 'Switch to English' : 'বাংলা ভাষায় দেখুন' }}">
        <a href="{{ route('lang.switch', 'en') }}" class="lang-code {{ !$isBn ? 'active' : 'inactive' }}" title="English Version">EN</a>
        <a href="{{ route('lang.switch', $isBn ? 'en' : 'bn') }}" class="toggle-slider-track {{ $isBn ? 'is-bn' : 'is-en' }}" aria-label="{{ $isBn ? 'Switch to English' : 'Switch to Bangla' }}" title="{{ $isBn ? 'Switch to English' : 'Switch to Bangla' }}">
          <div class="toggle-slider-knob">
            @if($isBn)
              <!-- Bangladesh Flag Knob (Round) -->
              <svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <clipPath id="bd-knob-clip"><circle cx="30" cy="30" r="30"/></clipPath>
                <g clip-path="url(#bd-knob-clip)">
                  <rect width="60" height="60" fill="#006a4e"/>
                  <circle cx="28" cy="30" r="14" fill="#f42a41"/>
                </g>
              </svg>
            @else
              <!-- UK Flag Knob (Round) -->
              <svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <clipPath id="uk-knob-clip"><circle cx="30" cy="30" r="30"/></clipPath>
                <g clip-path="url(#uk-knob-clip)">
                  <rect width="60" height="60" fill="#012169"/>
                  <path d="M0 0 L60 60 M60 0 L0 60" stroke="#ffffff" stroke-width="11"/>
                  <path d="M0 0 L60 60" stroke="#C8102E" stroke-width="4"/>
                  <path d="M60 0 L0 60" stroke="#C8102E" stroke-width="4"/>
                  <path d="M30 0 v60 M0 30 h60" stroke="#ffffff" stroke-width="15"/>
                  <path d="M30 0 v60 M0 30 h60" stroke="#C8102E" stroke-width="9"/>
                </g>
              </svg>
            @endif
          </div>
        </a>
        <a href="{{ route('lang.switch', 'bn') }}" class="lang-code {{ $isBn ? 'active' : 'inactive' }}" title="বাংলা সংস্করণ">বাং</a>
      </div>
    </div>
  </div>
</header>

<main>
  <!-- HERO SECTION -->
  <section id="home" class="hero" style="background-image: url('{{ $heroBanner }}'); background-size: cover; background-position: center;">
    <div class="container hero-content" style="padding-top: 48px;">
      @php
        $showHeroInstName = $settings->get('hero_show_institution_name', '1') !== '0';
        $heroInstName = $settings->get('hero_institution_name', $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College'));
      @endphp
      @if($showHeroInstName && !empty($heroInstName))
        <div class="hero-inst-banner">
          <h1 class="hero-inst-name">{{ $heroInstName }}</h1>
        </div>
      @endif
    </div>
  </section>

  <!-- NOTICE BAR -->
  <section id="notices" class="notice-bar">
    <div class="container notice-inner">
      <strong>⚑ &nbsp; {{ $isBn ? 'সাম্প্রতিক নোটিশ' : 'Latest Notices' }}</strong>
      <div class="notice-scroll">
        @forelse($notices->take(3) as $notice)
          <span>{{ $isBn ? ($noticeTranslations[$notice->title] ?? $notice->title) : $notice->title }}</span>
        @empty
          <span>{{ $isBn ? 'নতুন নোটিশ এখানে প্রকাশিত হবে।' : 'New notices will be published here.' }}</span>
        @endforelse
      </div>
      <a href="{{ route('notices') }}">{{ $isBn ? 'সব নোটিশ দেখুন →' : 'View All Notices →' }}</a>
    </div>
  </section>

  <!-- ABOUT SECTION -->
  <section id="about" class="section about-section">
    <div class="container about-grid">
      <div class="about-copy">
        <p class="section-kicker">{{ $isBn ? 'আমাদের পরিচিতি' : 'ABOUT US' }}</p>
        <h2>{{ $isBn ? 'আমাদের ইনস্টিটিউট সম্পর্কে' : $settings->get('about_title', 'About Our College') }}</h2>
        <div class="gold-rule"></div>
        <p>{{ $isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ একটি মানসম্মত কারিগরি ও বৃত্তিমূলক শিক্ষা প্রতিষ্ঠান। ব্যবহারিক প্রশিক্ষণ, বাস্তবমুখী দক্ষতা ও উপযুক্ত দিকনির্দেশনার মাধ্যমে শিক্ষার্থীদের আত্মবিশ্বাসী করে গড়ে তোলা এবং উন্নত কর্মসংস্থানের সুযোগ সৃষ্টি করাই আমাদের মূল লক্ষ্য।' : $settings->get('about', 'Quality technical and vocational education for a brighter future.') }}</p>
        <a class="button button-green" href="{{ route('contact') }}">{{ $isBn ? 'যোগাযোগ করুন' : 'Contact Our College' }} <span>→</span></a>
      </div>
      <div class="about-image">
        <img src="{{ $aboutImg }}" alt="{{ $settings->get('institution_name', 'College campus') }}">
      </div>
      <aside class="about-aside">
        <div></div>
        <strong>{!! nl2br(e($isBn ? "কোয়ালিটি এডুকেশন\nপ্র্যাকটিক্যাল স্কিলস\nকাজের সুযোগ\nসমৃদ্ধ আগামী" : $settings->get('about_aside', "Education\nSkills\nOpportunities\nA Stronger Tomorrow"))) !!}</strong>
      </aside>
    </div>
  </section>

  <!-- COURSES SECTION -->
  <section id="courses" class="section courses-section">
    <div class="container">
      <div class="section-heading">
        <div>
          <p class="section-kicker">{{ $isBn ? 'কোর্সসমূহ' : 'WHAT WE TEACH' }}</p>
          <h2>{{ $isBn ? 'আমাদের ট্রেইনিং কোর্সসমূহ' : 'Our Training Courses' }}</h2>
        </div>
        <div class="heading-action">
          {{ $isBn ? 'বাস্তবমুখী ও স্কিল-ভিত্তিক প্র্যাকটিক্যাল ট্রেইনিং' : 'Practical, skills-based training' }} <a class="button button-green" href="{{ route('apply') }}">{{ $isBn ? 'কোর্সে এডমিশন আবেদন →' : 'Apply for a Course →' }}</a>
        </div>
      </div>
      <div class="course-grid">
        @forelse($courses as $course)
          <a class="course-card" href="{{ route('apply') }}?course={{ urlencode($course->title) }}">
            @php
              $cImg = storage_asset($course->image, 'assets/MC Building Photo.jpg');
              $cTitle = $isBn ? ($courseTranslations[$course->title] ?? $course->title) : $course->title;
            @endphp
            <img src="{{ $cImg }}" alt="{{ $cTitle }}">
            <b>{{ $cTitle }}</b>
          </a>
        @empty
          <p>{{ $isBn ? 'কোর্স সংক্রান্ত তথ্য শীঘ্রই প্রকাশ করা হবে।' : 'Course information will be published soon.' }}</p>
        @endforelse
      </div>
      <div style="text-align: center; margin-top: 28px;">
        <a href="{{ route('courses') }}" class="button button-gold" style="font-weight: 700; padding: 12px 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
          {{ $isBn ? 'সবগুলো কোর্স ও পাঠ্যক্রম বিস্তারিত দেখুন →' : 'View All Courses & Full Curriculum →' }}
        </a>
      </div>
    </div>
  </section>

  <!-- PROJECTS SECTION (WITH CUSTOM PROJECT LOGOS) -->
  <section id="projects" class="section projects-section">
    <div class="container">
      <div class="section-heading">
        <div>
          <p class="section-kicker">{{ $isBn ? 'পার্টনারশিপ' : 'OUR PARTNERSHIPS' }}</p>
          <h2>{{ $isBn ? 'প্রজেক্টস ও ট্রেইনিং কার্যক্রম' : 'Projects & Training Initiatives' }}</h2>
        </div>
      </div>
      <div class="project-grid">
        @forelse($projects as $project)
          <div class="project-card">
            @if($project->image)
              @php
                $pImg = storage_asset($project->image);
              @endphp
              <img src="{{ $pImg }}" alt="{{ $project->title }}" style="width:48px;height:48px;object-fit:contain;border-radius:6px;background:#fff;padding:2px;box-shadow:0 1px 3px rgba(0,0,0,0.1);flex-shrink:0;">
            @else
              <i>⌁</i>
            @endif
            <div>
              <b>{{ $project->title }}</b>
              <span>{{ $isBn ? ($project->excerpt ?: 'ট্রেইনিং ইনিশিয়েটিভ') : ($project->excerpt ?: 'Training initiative') }}</span>
            </div>
          </div>
        @empty
          <p>{{ $isBn ? 'প্রজেক্ট সংক্রান্ত তথ্য শীঘ্রই যুক্ত করা হবে।' : 'Project information will be published soon.' }}</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- ADMISSIONS BAND -->
  <section id="admissions" class="admission-band">
    <div class="container admission-inner">
      <div class="admission-icon">▤</div>
      <div>
        <h3>{{ $isBn ? 'এডমিশন ইনফরমেশন' : $settings->get('admission_title', 'Admission Information') }}</h3>
        <p>{{ $isBn ? 'ভর্তি যোগ্যতা, আবেদন পদ্ধতি ও গুরুত্বপূর্ণ তারিখ সম্পর্কে জানুন।' : $settings->get('admission_subtitle', 'Ask about eligibility, application process and important dates.') }}</p>
      </div>
      <div class="admission-links">
        <span>♟ <b>{{ $isBn ? 'যোগ্যতা' : 'Eligibility' }}</b></span>
        <span>▤ <b>{{ $isBn ? 'আবেদন পদ্ধতি' : 'How to Apply' }}</b></span>
        <span>▦ <b>{{ $isBn ? 'জরুরি তারিখ' : 'Important Dates' }}</b></span>
      </div>
      <a class="button button-gold" href="{{ route('apply') }}">{{ $isBn ? 'অনলাইন এডমিশন আবেদন →' : 'Apply Online Now →' }}</a>
    </div>
  </section>

  <!-- FACILITIES SECTION (ALL 4 DYNAMIC IMAGES & TITLES) -->
  <section class="section facilities-section">
    <div class="container">
      <div class="section-heading">
        <div>
          <p class="section-kicker">{{ $isBn ? 'হাতে-কলমে শিক্ষা' : 'LEARN BY DOING' }}</p>
          <h2>{{ $isBn ? 'আমাদের সুযোগ-সুবিধা ও ল্যাব ওয়ার্কশপ' : 'Our Facilities and Hands-on Learning' }}</h2>
        </div>
        <span>{{ $isBn ? 'বাস্তবমুখী স্কিল ডেভেলপমেন্টের জন্য আধুনিক ল্যাব সুবিধা' : 'Modern facilities for practical skill development' }}</span>
      </div>
      <div class="facility-grid">
        @php
          $f1_title = $settings->get('facility_1_title', 'Computer Lab');
          $f2_title = $settings->get('facility_2_title', 'Refrigeration and AC Workshop');
          $f3_title = $settings->get('facility_3_title', 'Plumbing Workshop');
          $f4_title = $settings->get('facility_4_title', 'Tailoring and Sewing Workshop');
        @endphp
        <figure>
          <img src="{{ $f1_img }}" alt="{{ $f1_title }}">
          <figcaption>{{ $isBn ? ($facilityTranslations[$f1_title] ?? $f1_title) : $f1_title }}</figcaption>
        </figure>
        <figure>
          <img src="{{ $f2_img }}" alt="{{ $f2_title }}">
          <figcaption>{{ $isBn ? ($facilityTranslations[$f2_title] ?? $f2_title) : $f2_title }}</figcaption>
        </figure>
        <figure>
          <img src="{{ $f3_img }}" alt="{{ $f3_title }}">
          <figcaption>{{ $isBn ? ($facilityTranslations[$f3_title] ?? $f3_title) : $f3_title }}</figcaption>
        </figure>
        <figure>
          <img src="{{ $f4_img }}" alt="{{ $f4_title }}">
          <figcaption>{{ $isBn ? ($facilityTranslations[$f4_title] ?? $f4_title) : $f4_title }}</figcaption>
        </figure>
      </div>
    </div>
  </section>

  <!-- RESULTS & ACHIEVEMENTS -->
  <section class="section results-section">
    <div class="container">
      <div class="section-heading">
        <div>
          <p class="section-kicker">{{ $isBn ? 'অর্জন ও সাফল্য' : 'OUR IMPACT' }}</p>
          <h2>{{ $isBn ? 'ফলাফল ও অর্জনসমূহ' : 'Results &amp; Achievements' }}</h2>
        </div>
      </div>
      <div class="result-grid">
        <div class="result green"><span>♜</span>{{ $isBn ? 'একাডেমিক রেজাল্ট' : 'Academic Results' }}</div>
        <div class="result gold"><span>▥</span>{{ $isBn ? 'স্কিল ডেভেলপমেন্ট' : 'Skills Development' }}</div>
        <div class="result green"><span>♟</span>{{ $isBn ? 'স্টুডেন্ট অর্জন' : 'Student Achievements' }}</div>
        <div class="result gold"><span>♙</span>{{ $isBn ? 'প্রাতিষ্ঠানিক স্বীকৃতি' : 'Institutional Recognition' }}</div>
      </div>
    </div>
  </section>

  <!-- PHOTO GALLERY -->
  <section id="gallery" class="section gallery-section">
    <div class="container">
      <div class="section-heading">
        <div>
          <p class="section-kicker">{{ $isBn ? 'ছবিতে ক্যাম্পাস' : 'SEE OUR WORK' }}</p>
          <h2>{{ $isBn ? 'ফটো গ্যালারি' : 'Photo Gallery' }}</h2>
        </div>
      </div>
      <div class="gallery-grid">
        @forelse($gallery as $photo)
          <button class="gallery-item" type="button">
            @php
              $gImg = storage_asset($photo->image, 'assets/MC Building Photo.jpg');
            @endphp
            <img src="{{ $gImg }}" alt="{{ $photo->title }}">
          </button>
        @empty
          <p>{{ $isBn ? 'গ্যালারির ছবি শীঘ্রই যুক্ত করা হবে।' : 'Photos will be published soon.' }}</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- EVENTS & NOTICES -->
  <section class="section lower-section">
    <div class="container lower-grid">
      <div>
        <h3>▦ &nbsp; {{ $isBn ? 'আসন্ন ইভেন্টস' : 'Upcoming Events' }}</h3>
        @forelse($events as $event)
          <div class="event">
            <b>{{ $loop->iteration }}<br><small>{{ $event->starts_at?->format('M') ?? 'TBA' }}</small></b>
            <span><strong>{{ $isBn ? ($eventTranslations[$event->title] ?? $event->title) : $event->title }}</strong><small>{{ $event->excerpt }}</small></span>
          </div>
        @empty
          <p>{{ $isBn ? 'আসন্ন ইভেন্টের বিবরণ এখানে প্রকাশিত হবে।' : 'Upcoming events will be announced here.' }}</p>
        @endforelse
      </div>
      <div id="notices-list">
        <h3>⚑ &nbsp; {{ $isBn ? 'সাম্প্রতিক নোটিশ' : 'Recent Notices' }}</h3>
        @forelse($notices->take(4) as $notice)
          <div class="event">
            <b>•</b>
            <span><strong>{{ $isBn ? ($noticeTranslations[$notice->title] ?? $notice->title) : $notice->title }}</strong><small>{{ $notice->excerpt }}</small></span>
          </div>
        @empty
          <p>{{ $isBn ? 'কোনো নোটিশ পাওয়া যায়নি।' : 'No notices yet.' }}</p>
        @endforelse
      </div>
      <div class="quote-card">
        <h2>{!! nl2br(e($isBn ? "দক্ষ জনশক্তিই\nগড়বে সমৃদ্ধ বাংলাদেশ" : $settings->get('quote_title', "Skilled People\nBuild a Better Bangladesh"))) !!}</h2>
        <div class="gold-rule"></div>
      </div>
    </div>
  </section>

  <!-- FORMS & CONTACT -->
  <section class="section form-section" id="apply-form">
    <div class="container form-grid">
      @if($isBn)
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 32px 28px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
          <p class="section-kicker">এডমিশন ও রেজিস্ট্রেশন</p>
          <h2 style="font-size: 24px; color: var(--green); margin-bottom: 12px;">অনলাইন ট্রেইনী এডমিশন আবেদন</h2>
          <p style="color: #475569; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
            মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজে বিভিন্ন টেকনিক্যাল ও বৃত্তিমূলক ট্রেড কোর্সে ট্রেইনী ভর্তি কার্যক্রম চলছে। আগ্রহী প্রার্থীরা সরকারি ও ইনস্টিটিউট নির্ধারিত পূর্ণাঙ্গ ফরম পূরণ করে অনলাইনে সরাসরি আবেদন দাখিল করতে পারেন।
          </p>
          <div style="margin-bottom: 24px; display: flex; flex-direction: column; gap: 8px; font-size: 13px; color: #334155;">
            <span>✓ <b>সহজ ও দ্রুত:</b> ব্যক্তিগত ও ঠিকানার তথ্য অনলাইনে জমা দিন</span>
            <span>✓ <b>ডকুমেন্ট আপলোড:</b> ছবি, পরিচয়পত্র ও স্বাক্ষর সরাসরি সংযুক্ত করুন</span>
            <span>✓ <b>ইনস্ট্যান্ট ট্র্যাকিং:</b> আবেদন শেষে প্রিন্ট কপি ও ট্র্যাকিং নম্বর সংগ্রহ করুন</span>
          </div>
          <a class="button button-gold" style="display: block; text-align: center; font-size: 16px; padding: 14px 24px; font-weight: 700; text-decoration: none;" href="{{ route('apply') }}">
            ভর্তি আবেদন ফরম পূরণ করুন (Apply Now) ➔
          </a>
        </div>
      @else
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 32px 28px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
          <p class="section-kicker">ADMISSIONS & REGISTRATION</p>
          <h2 style="font-size: 24px; color: var(--green); margin-bottom: 12px;">Online Trainee Admission</h2>
          <p style="color: #475569; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
            Admissions are now open for various technical and vocational trade courses at Mahmudunnabi Chowdhury Technical School and College. Interested candidates can apply directly online by completing the official trainee admission application form.
          </p>
          <div style="margin-bottom: 24px; display: flex; flex-direction: column; gap: 8px; font-size: 13px; color: #334155;">
            <span>✓ <b>Fast &amp; Simple:</b> Submit your personal and address details online</span>
            <span>✓ <b>Document Upload:</b> Attach photo, NID / Birth certificate &amp; signature</span>
            <span>✓ <b>Instant Tracking:</b> Receive your printable application copy and tracking code</span>
          </div>
          <a class="button button-gold" style="display: block; text-align: center; font-size: 16px; padding: 14px 24px; font-weight: 700; text-decoration: none;" href="{{ route('apply') }}">
            Fill Out Admission Form (Apply Now) ➔
          </a>
        </div>
      @endif

      <div id="contact">
        <p class="section-kicker">{{ $isBn ? 'যোগাযোগ' : 'CONTACT' }}</p>
        <h2>{{ $isBn ? 'যোগাযোগ করুন' : 'Get in Touch' }}</h2>
        <p>{{ $settings->get('address', '273/A/520, Chodu Chowdhury Road, Sagorika, Pahartali, Chattogram') }}</p>
        <p>{{ $settings->get('phone', '+8801880919275') }}<br>{{ $settings->get('email', 'mnctsc@gmail.com') }}</p>
        <form method="post" action="{{ route('contact.store') }}">
          @csrf
          <label>{{ $isBn ? 'আপনার নাম' : 'Your name' }}<input name="name" required maxlength="150"></label>
          <label>{{ $isBn ? 'ইমেইল এড্রেস' : 'Email' }}<input name="email" type="email" required></label>
          <label>{{ $isBn ? 'বিষয়' : 'Subject' }}<input name="subject" maxlength="180"></label>
          <label>{{ $isBn ? 'বার্তা' : 'Message' }}<textarea name="message" rows="4" required maxlength="3000"></textarea></label>
          <button class="button button-gold" type="submit">{{ $isBn ? 'বার্তা পাঠান →' : 'Send Message →' }}</button>
        </form>
      </div>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="container footer-main">
    <div class="footer-brand">
      <img src="{{ $siteLogo }}" alt="{{ $settings->get('institution_name', 'MCTSC') }} crest">
      <strong>{{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }}</strong>
    </div>

    <div class="footer-contact-block">
      <div class="contact-row-top">
        <svg class="c-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
          <circle cx="12" cy="10" r="3"></circle>
        </svg>
        <div>
          <small>{{ $isBn ? 'আমাদের ঠিকানা' : 'Our address' }}</small>
          <strong>{{ $settings->get('address', '273/A/520, Chodu Chowdhury Road, Sagorika, Pahartali, Chattogram') }}</strong>
        </div>
      </div>

      <div class="contact-row-sub">
        <div class="contact-sub-item">
          <svg class="c-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          </svg>
          <div>
            <small>{{ $isBn ? 'ফোন নম্বর' : 'Phone number' }}</small>
            <strong>{{ $settings->get('phone', '+8801880919275') }}</strong>
          </div>
        </div>

        <div class="contact-sub-item">
          <svg class="c-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
          <div>
            <small>{{ $isBn ? 'ইমেইল এড্রেস' : 'Email address' }}</small>
            <strong>{{ $settings->get('email', 'mnctsc@gmail.com') }}</strong>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-social-col">
      <small>{{ $isBn ? 'সোশ্যাল মিডিয়ায় আমরা' : 'Follow Us' }}</small>
      <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <a class="social-text-link" href="{{ $settings->get('social_facebook', 'https://www.facebook.com/mnctsc') }}" target="_blank" rel="noopener">
          Facebook
        </a>
        @if($settings->get('social_youtube'))
          <a class="social-text-link" href="{{ $settings->get('social_youtube') }}" target="_blank" rel="noopener">
            YouTube
          </a>
        @endif
        @if($settings->get('social_linkedin'))
          <a class="social-text-link" href="{{ $settings->get('social_linkedin') }}" target="_blank" rel="noopener">
            LinkedIn
          </a>
        @endif
      </div>
    </div>
  </div>
  <div class="container footer-bottom">
    <span>© <span id="year">{{ date('Y') }}</span> {{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }}. {{ $isBn ? 'সর্বস্বত্ব সংরক্ষিত।' : 'All rights reserved.' }}</span>
    <span>{{ $isBn ? 'বাস্তবমুখী স্কিলস · উন্নত কর্মসংস্থান' : $settings->get('tagline', 'Practical Skills · Better Opportunities') }}</span>
  </div>
</footer>

@if(session('success'))
  <div class="toast-success">{{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="toast-error">{{ $errors->first() }}</div>
@endif

<div class="lightbox" aria-hidden="true">
  <button aria-label="Close gallery">×</button>
  <img src="" alt="">
</div>
<script src="{{ asset('js/site.js') }}"></script>
</body>
</html>
