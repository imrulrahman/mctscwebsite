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
    $rawHero = $settings->get('hero_banner');
    if (!$rawHero || $rawHero === 'assets/mc Banner.jpg') {
        $rawHero = 'assets/banners/banner_robotics_lab.jpg';
    }
    $heroBanner = storage_asset($rawHero, 'assets/banners/banner_robotics_lab.jpg');
    $aboutImg = storage_asset($settings->get('about_image'), 'assets/MC Building Photo.jpg');

    $primaryColor = $settings->get('primary_color', '#0f5132');
    $primaryHover = $settings->get('primary_hover', '#093722');
    $secondaryColor = $settings->get('secondary_color', '#c59b27');
    $secondaryHover = $settings->get('secondary_hover', '#a37d18');

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

    /* Hide scrollbars across browsers */
    html {
      overflow-x: hidden !important;
      scrollbar-width: none !important;
      -ms-overflow-style: none !important;
    }
    body {
      overflow-x: hidden !important;
      scrollbar-width: none !important;
      -ms-overflow-style: none !important;
    }
    html::-webkit-scrollbar,
    body::-webkit-scrollbar {
      display: none !important;
      width: 0 !important;
      height: 0 !important;
    }

    /* Brand 2-Line Header (Logo centered vertically) */
    .brand {
      flex-shrink: 0 !important;
      display: inline-flex !important;
      align-items: center !important;
      text-decoration: none !important;
      gap: 12px !important;
      white-space: normal !important;
    }
    .brand img {
      width: 48px !important;
      height: 48px !important;
      object-fit: contain !important;
      margin: 0 !important;
      flex-shrink: 0 !important;
    }
    .brand-text {
      display: flex !important;
      flex-direction: column !important;
      justify-content: center !important;
      line-height: 1.25 !important;
    }
    .brand-line-1 {
      font-family: var(--serif, 'Libre Baskerville', Georgia, serif) !important;
      font-size: 15.5px !important;
      font-weight: 700 !important;
      color: {{ $primaryColor }} !important;
      letter-spacing: -0.2px !important;
      white-space: nowrap !important;
    }
    .brand-line-2 {
      font-family: var(--serif, 'Libre Baskerville', Georgia, serif) !important;
      font-size: 13px !important;
      font-weight: 700 !important;
      color: {{ $primaryColor }} !important;
      letter-spacing: -0.2px !important;
      white-space: nowrap !important;
    }

    /* Larger, clearer Navigation Links */
    .main-nav {
      display: flex;
      align-items: center;
      gap: clamp(12px, 1.5vw, 22px) !important;
      font-weight: 700;
      font-size: 15.5px !important;
    }
    .main-nav a {
      white-space: nowrap !important;
      padding: 8px 4px !important;
      color: #252d2a;
      text-decoration: none;
      font-size: 15.5px !important;
    }
    .nav-item-dropdown .dropdown-menu {
      min-width: 195px !important;
    }
    .nav-item-dropdown .dropdown-menu a {
      font-size: 14.5px !important;
      padding: 10px 18px !important;
    }

    /* Ken Burns Subtle Zoom Animations for Hero Slider */
    @keyframes kenBurnsZoomIn {
      0% {
        transform: scale(1.0);
      }
      100% {
        transform: scale(1.08);
      }
    }
    @keyframes kenBurnsZoomOut {
      0% {
        transform: scale(1.08);
      }
      100% {
        transform: scale(1.0);
      }
    }
    .hero-slider-wrap.hero-slider-animated .hero-slide:nth-child(odd).active .hero-slide-img {
      animation: kenBurnsZoomIn 7s ease-out forwards;
    }
    .hero-slider-wrap.hero-slider-animated .hero-slide:nth-child(even).active .hero-slide-img {
      animation: kenBurnsZoomOut 7s ease-out forwards;
    }

    /* Header responsive & brand wrapping fix */
    .site-header {
      min-height: 78px !important;
      height: auto !important;
      padding: 6px 0 !important;
      position: sticky;
      top: 0;
      z-index: 50;
      background: #ffffff;
      border-bottom: 1px solid #e2ebe6;
      box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }
    .site-header .container {
      width: min(1440px, calc(100% - 32px)) !important;
      max-width: 1440px !important;
      padding: 0 16px !important;
      box-sizing: border-box !important;
    }
    .nav-wrap {
      min-height: 66px !important;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }
    .brand {
      flex-shrink: 0 !important;
      max-width: none !important;
      font-size: 14.5px !important;
      line-height: 1.25 !important;
      text-decoration: none !important;
      white-space: nowrap !important;
      display: flex !important;
      align-items: center !important;
    }
    .brand img {
      width: 48px;
      height: 48px;
      object-fit: contain;
      margin-right: 10px;
      flex-shrink: 0;
    }
    .header-right-group {
      display: flex;
      align-items: center;
      gap: 14px;
      flex-shrink: 0;
      margin-left: auto;
    }
    .main-nav {
      display: flex;
      align-items: center;
      gap: clamp(10px, 1.2vw, 18px) !important;
      font-weight: 700;
      font-size: 13.5px !important;
    }
    .main-nav a {
      white-space: nowrap !important;
      padding: 8px 3px !important;
      color: #252d2a;
      text-decoration: none;
    }
    .main-nav a:hover,
    .main-nav a.active {
      color: {{ $primaryColor }};
    }

    /* About us Submenu Dropdown */
    .nav-item-dropdown {
      position: relative;
      display: inline-flex;
      align-items: center;
    }
    .nav-item-dropdown .nav-link-parent {
      display: inline-flex;
      align-items: center;
      gap: 3px;
      white-space: nowrap !important;
    }
    .nav-item-dropdown .dropdown-caret {
      font-size: 9px;
      transition: transform 0.2s ease;
      display: inline-block;
      color: #718096;
    }
    .nav-item-dropdown:hover .dropdown-caret {
      transform: rotate(180deg);
      color: {{ $primaryColor }};
    }
    .nav-item-dropdown .dropdown-menu {
      position: absolute;
      top: 100%;
      left: 0;
      min-width: 165px;
      background: #ffffff;
      border: 1px solid #d7e2dd;
      border-radius: 6px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
      padding: 6px 0;
      display: none;
      z-index: 100;
    }
    .nav-item-dropdown:hover .dropdown-menu,
    .nav-item-dropdown:focus-within .dropdown-menu {
      display: block;
    }
    .nav-item-dropdown .dropdown-menu a {
      display: block !important;
      padding: 9px 16px !important;
      font-size: 13px !important;
      font-weight: 600 !important;
      color: #1e3d35 !important;
      border-bottom: 1px solid #f1f5f3 !important;
      white-space: nowrap !important;
      text-decoration: none !important;
    }
    .nav-item-dropdown .dropdown-menu a:last-child {
      border-bottom: none !important;
    }
    .nav-item-dropdown .dropdown-menu a:hover,
    .nav-item-dropdown .dropdown-menu a.active {
      background: rgba(15, 81, 50, 0.08) !important;
      color: {{ $primaryColor }} !important;
    }

    /* 2-line Apply for Admission CTA button */
    .main-nav .nav-cta {
      background: var(--gold, #bd9635) !important;
      color: #ffffff !important;
      padding: 5px 12px !important;
      border-radius: 5px !important;
      display: inline-flex !important;
      flex-direction: column !important;
      align-items: center !important;
      justify-content: center !important;
      line-height: 1.15 !important;
      font-size: 11.5px !important;
      font-weight: 700 !important;
      text-align: center !important;
      white-space: nowrap !important;
      margin-left: 2px !important;
      border: 0 !important;
      box-shadow: 0 2px 5px rgba(0,0,0,0.12);
      transition: transform 0.15s, background 0.15s;
      flex-shrink: 0 !important;
    }
    .main-nav .nav-cta:hover {
      transform: translateY(-1px);
      background: var(--secondaryHover, #a37d18) !important;
      color: #ffffff !important;
    }
    .main-nav .nav-cta span {
      display: block;
      line-height: 1.15;
    }

    /* Language Switcher safe margin */
    .lang-switch-box {
      flex-shrink: 0 !important;
      margin-left: 2px !important;
    }

    @media (max-width: 1200px) and (min-width: 1025px) {
      .main-nav {
        gap: 10px !important;
        font-size: 12.5px !important;
      }
      .brand {
        font-size: 13.5px !important;
      }
    }
    @media (max-width: 1024px) {
      .main-nav {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #ffffff;
        padding: 16px 24px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        flex-direction: column;
        align-items: stretch;
        gap: 0 !important;
        border-top: 1px solid #edf1ef;
      }
      .main-nav.open {
        display: flex !important;
      }
      .main-nav a {
        padding: 12px 0 !important;
        border-bottom: 1px solid #f1f5f3;
        font-size: 14px !important;
      }
      .nav-item-dropdown {
        flex-direction: column;
        align-items: stretch;
      }
      .nav-item-dropdown .dropdown-menu {
        position: static;
        display: block;
        box-shadow: none;
        border: none;
        padding: 0 0 0 14px;
        background: #f8faf9;
        border-radius: 6px;
        margin: 4px 0 8px 0;
      }
      .nav-item-dropdown .dropdown-menu a {
        padding: 10px 12px !important;
        font-size: 13px !important;
      }
      .main-nav .nav-cta {
        text-align: center;
        margin-top: 10px;
        padding: 10px !important;
        font-size: 13px !important;
      }
      .menu-toggle {
        display: block !important;
      }
    }
    @media (max-width: 680px) {
      .brand {
        font-size: 12px !important;
        white-space: normal !important;
        max-width: 220px !important;
        line-height: 1.2 !important;
      }
    }

    /* Hero Image Carousel Slider (1920x720 Resolution Aspect Ratio & Non-Squishing) */
    .hero-slider-wrap {
      position: relative;
      width: 100%;
      height: clamp(220px, 37.5vw, 720px) !important;
      max-height: 720px !important;
      aspect-ratio: 1920 / 720;
      overflow: hidden;
      background-color: #0b3d30;
      user-select: none;
    }
    .hero-slides-container {
      position: relative;
      width: 100%;
      height: 100%;
    }
    .hero-slide {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.8s ease-in-out, visibility 0.8s ease-in-out;
      z-index: 1;
    }
    .hero-slide.active {
      opacity: 1;
      visibility: visible;
      z-index: 2;
    }
    .hero-slide img {
      width: 100% !important;
      height: 100% !important;
      display: block !important;
      object-fit: cover !important;
    }
    /* Slider Prev/Next Controls */
    .hero-slider-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 10;
      background: rgba(0, 0, 0, 0.4);
      color: #ffffff;
      border: 1px solid rgba(255, 255, 255, 0.5);
      width: 44px;
      height: 44px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 20px;
      transition: all 0.25s ease;
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }
    .hero-slider-btn:hover {
      background: {{ $primaryColor }} !important;
      border-color: #ffffff;
      transform: translateY(-50%) scale(1.08);
    }
    .hero-slider-prev {
      left: 20px;
    }
    .hero-slider-next {
      right: 20px;
    }
    /* Slider Pagination Dots */
    .hero-slider-dots {
      position: absolute;
      bottom: 14px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 10;
      display: flex;
      gap: 10px;
      align-items: center;
      background: rgba(0, 0, 0, 0.42);
      padding: 6px 14px;
      border-radius: 20px;
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
    }
    .hero-dot {
      width: 11px;
      height: 11px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.55);
      cursor: pointer;
      border: none;
      padding: 0;
      transition: all 0.25s ease;
    }
    .hero-dot.active {
      background: {{ $secondaryColor }} !important;
      width: 26px;
      border-radius: 10px;
    }
    @media (max-width: 992px) {
      .hero-slider-btn {
        width: 36px;
        height: 36px;
        font-size: 16px;
      }
      .hero-slider-prev { left: 10px; }
      .hero-slider-next { right: 10px; }
    }
    @media (max-width: 600px) {
      .hero-slider-btn {
        width: 30px;
        height: 30px;
        font-size: 14px;
      }
      .hero-slider-prev { left: 8px; }
      .hero-slider-next { right: 8px; }
      .hero-slider-dots {
        bottom: 8px;
        padding: 4px 10px;
        gap: 6px;
      }
      .hero-dot { width: 8px; height: 8px; }
      .hero-dot.active { width: 18px; }
    }

    /* Global Symmetrical Container */
    body {
      overflow-x: hidden !important;
    }
    .container {
      width: min(1140px, calc(100% - 64px)) !important;
      margin-left: auto !important;
      margin-right: auto !important;
      padding-left: 0 !important;
      padding-right: 0 !important;
      box-sizing: border-box !important;
    }

    /* About Section */
    .about-copy h2, .section-heading h2 {
      color: {{ $primaryColor }} !important;
    }
    .about-aside {
      display: none !important;
    }
    .about-section {
      padding: 50px 0 46px 0 !important;
      background: #ffffff;
      width: 100% !important;
      overflow: hidden !important;
    }
    .about-grid {
      display: grid !important;
      grid-template-columns: 1.25fr 0.95fr !important;
      gap: 40px !important;
      align-items: center !important;
      width: 100% !important;
      box-sizing: border-box !important;
    }
    .about-grid > * {
      min-width: 0 !important;
      box-sizing: border-box !important;
    }
    .about-copy {
      padding: 10px 0 !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
    }
    .about-copy h2 {
      font-size: 30px !important;
      line-height: 1.25 !important;
      margin: 0 0 12px 0 !important;
      color: {{ $primaryColor }} !important;
    }
    .about-summary-text p,
    .about-copy p:not(.section-kicker) {
      font-size: 15px !important;
      line-height: 1.75 !important;
      color: #334d44 !important;
      text-align: justify !important;
      text-justify: inter-word !important;
    }
    .about-image {
      width: 100% !important;
      height: 100% !important;
      min-height: 280px !important;
      max-height: 380px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      border-radius: 12px !important;
      overflow: hidden !important;
      box-shadow: 0 6px 24px rgba(0, 0, 0, 0.07) !important;
      background: #ffffff !important;
      box-sizing: border-box !important;
    }
    .about-image img {
      width: 100% !important;
      height: 100% !important;
      max-height: 380px !important;
      object-fit: contain !important;
      display: block !important;
      border-radius: 12px !important;
      transition: transform 0.3s ease;
    }
    .about-image:hover img {
      transform: scale(1.02);
    }
    @media (max-width: 900px) {
      .about-grid {
        grid-template-columns: 1fr !important;
        gap: 24px !important;
      }
      .about-image {
        max-height: 300px !important;
        min-height: 220px !important;
      }
      .about-image img {
        max-height: 300px !important;
        min-height: 220px !important;
      }
    }

    /* ========================================================
       VISION, MISSION & OBJECTIVES (PILL BADGE & NATURAL HEIGHT)
       ======================================================== */
    .vmo-section {
      padding: 48px 0 54px 0 !important;
      background: #f8faf9 !important;
      border-top: 1px solid rgba(15, 81, 50, 0.08);
      border-bottom: 1px solid rgba(15, 81, 50, 0.08);
    }
    .vmo-layout {
      display: grid !important;
      grid-template-columns: 1fr 1fr !important;
      gap: 30px !important;
      align-items: start !important;
    }
    .vmo-col-left {
      display: flex !important;
      flex-direction: column !important;
      gap: 24px !important;
    }
    .vmo-col-right {
      display: flex !important;
      flex-direction: column !important;
    }
    .vmo-card-wrap {
      display: flex !important;
      flex-direction: column !important;
      align-items: flex-start !important;
      width: 100% !important;
    }
    .vmo-pill {
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      padding: 8px 24px !important;
      border-radius: 8px !important;
      color: #ffffff !important;
      font-weight: 700 !important;
      font-size: 20px !important;
      line-height: 1.2 !important;
      margin-bottom: 12px !important;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08) !important;
      letter-spacing: -0.2px !important;
    }
    .pill-vision {
      background: #1b8e4b !important;
    }
    .pill-mission {
      background: #2b5288 !important;
    }
    .pill-objectives {
      background: #b45309 !important;
    }
    .vmo-box {
      width: 100% !important;
      background: #ffffff !important;
      border-radius: 10px !important;
      padding: 24px 28px !important;
      border: 1px solid rgba(0, 0, 0, 0.06) !important;
      box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04) !important;
      transition: transform 0.25s ease, box-shadow 0.25s ease !important;
      box-sizing: border-box !important;
      height: auto !important;
    }
    .vmo-box:hover {
      transform: translateY(-4px) !important;
      box-shadow: 0 12px 30px rgba(15, 81, 50, 0.1) !important;
    }
    .vmo-box p {
      font-size: 15.5px !important;
      line-height: 1.75 !important;
      color: #2d3748 !important;
      margin: 0 !important;
      text-align: justify !important;
      text-justify: inter-word !important;
    }
    .vmo-objectives-list {
      list-style: none !important;
      padding: 0 !important;
      margin: 0 !important;
      display: flex !important;
      flex-direction: column !important;
      gap: 13px !important;
    }
    .vmo-objectives-list li {
      display: flex !important;
      align-items: flex-start !important;
      gap: 11px !important;
      font-size: 14.5px !important;
      line-height: 1.55 !important;
      color: #2d3748 !important;
      text-align: justify !important;
      text-justify: inter-word !important;
    }
    .vmo-objectives-list li span {
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 22px !important;
      height: 22px !important;
      border-radius: 50% !important;
      background: #e8f5e9 !important;
      color: #1b8e4b !important;
      font-size: 13px !important;
      font-weight: 800 !important;
      flex-shrink: 0 !important;
      margin-top: 2px !important;
    }
    @media (max-width: 900px) {
      .vmo-layout {
        grid-template-columns: 1fr !important;
        gap: 24px !important;
      }
    }

    /* ========================================================
       DEVELOPMENT PARTNERS (SLIDING MARQUEE - HIERARCHICAL MATCH)
       ======================================================== */
    .partners-marquee-section {
      padding: 56px 0 52px 0 !important;
      background: #ffffff !important;
      overflow: hidden !important;
    }
    .partners-intro {
      text-align: center;
      max-width: 880px;
      margin: 0 auto 36px auto;
    }
    .partners-intro h2 {
      font-size: 30px;
      color: var(--green);
      margin: 0 0 12px 0;
      font-family: var(--serif);
    }
    .partners-intro p {
      font-size: 15.5px;
      line-height: 1.75;
      color: #3f584e;
      margin: 0;
    }
    /* Infinite Marquee exact to impactini.org */
    .imi-lmq {
      overflow: hidden;
      -webkit-mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent);
      mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent);
      padding: 12px 0 16px 0;
      width: 100%;
    }
    .imi-lmq-in {
      display: flex;
      width: max-content;
      animation: imiLmq 38s linear infinite;
    }
    .imi-lmq:hover .imi-lmq-in {
      animation-play-state: paused;
    }
    @keyframes imiLmq {
      to {
        transform: translateX(-50%);
      }
    }
    .imi-lcard {
      flex: 0 0 auto;
      width: 220px;
      height: 110px;
      margin-right: 22px;
      background: #ffffff;
      border-radius: 18px;
      box-shadow: 0 8px 24px rgba(0, 26, 117, 0.06);
      border: 1px solid rgba(0, 0, 0, 0.07);
      transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
      box-sizing: border-box;
      overflow: hidden;
      padding: 0 !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
    }
    .imi-lcard:hover {
      transform: translateY(-6px);
      box-shadow: 0 18px 40px rgba(15, 81, 50, 0.14);
      border-color: rgba(15, 81, 50, 0.3);
    }
    .imi-lcard-link {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 100% !important;
      height: 100% !important;
      padding: 16px 22px !important;
      box-sizing: border-box !important;
      text-decoration: none !important;
    }
    .imi-lcard img {
      max-width: 100% !important;
      max-height: 100% !important;
      width: auto !important;
      height: auto !important;
      object-fit: contain !important;
      margin: auto !important;
      display: block !important;
      filter: grayscale(15%);
      transition: filter 0.3s ease, transform 0.3s ease;
    }
    .imi-lcard:hover img {
      filter: grayscale(0%);
      transform: scale(1.05);
    }
    @media (max-width: 768px) {
      .imi-lcard {
        width: 175px;
        height: 94px;
        margin-right: 14px;
        padding: 0 !important;
        border-radius: 14px;
      }
      .imi-lcard-link {
        padding: 12px 16px !important;
      }
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

    /* Skeuomorphic Flag Language Switcher */
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
      <div class="brand-text">
        <span class="brand-line-1">{{ $isBn ? 'মাহমুদুন্নবী চৌধুরী' : 'Mahmudunnabi Chowdhury' }}</span>
        <span class="brand-line-2">{{ $isBn ? 'টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Technical School and College' }}</span>
      </div>
    </a>
    <div class="header-right-group">
      <button class="menu-toggle" aria-label="Open navigation" aria-expanded="false">☰</button>
      <nav class="main-nav" aria-label="Primary navigation">
        <a class="active" href="{{ route('home') }}">{{ $isBn ? 'হোম' : 'Home' }}</a>
        <div class="nav-item-dropdown">
          <a class="nav-link-parent {{ request()->routeIs('about') || request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('about') }}">
            {{ $isBn ? 'আমাদের সম্পর্কে' : 'About us' }} <span class="dropdown-caret">▾</span>
          </a>
          <div class="dropdown-menu">
            <a class="{{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
              {{ $isBn ? 'আমাদের সম্পর্কে' : 'About us' }}
            </a>
            <a class="{{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
              {{ $isBn ? 'যোগাযোগ' : 'Contact Us' }}
            </a>
          </div>
        </div>
        <a class="{{ request()->routeIs('infrastructure') ? 'active' : '' }}" href="{{ route('infrastructure') }}" style="white-space: nowrap;">{{ $isBn ? 'অবকাঠামো' : 'Infrastructure' }}</a>
        <div class="nav-item-dropdown">
          <a class="nav-link-parent" href="{{ route('courses') }}">
            {{ $isBn ? 'কোর্সসমূহ' : 'Courses' }} <span class="dropdown-caret">▾</span>
          </a>
          <div class="dropdown-menu">
            <a href="{{ route('courses') }}">
              {{ $isBn ? 'কোর্সসমূহ' : 'Courses' }}
            </a>
            <a href="{{ route('apply') }}">
              {{ $isBn ? 'ভর্তি আবেদন' : 'Apply for Admission' }}
            </a>
          </div>
        </div>
        <a href="{{ route('projects') }}">{{ $isBn ? 'প্রজেক্টস' : 'Projects' }}</a>
        <a href="{{ route('notices') }}">{{ $isBn ? 'নোটিশ' : 'Notices' }}</a>
        <a href="{{ route('gallery') }}">{{ $isBn ? 'গ্যালারি' : 'Gallery' }}</a>
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
  <!-- 1. HERO SLIDER SECTION (DYNAMIC SLIDES, MULTIPLE IMAGES OR FIXED BANNER) -->
  @php
    $heroSliderMode = $settings->get('hero_slider_mode', 'slide'); // 'slide' or 'fixed'
    $heroFixedId = $settings->get('hero_slider_fixed_id');
    $heroInterval = (int)($settings->get('hero_slider_interval') ?: 1000);

    // Dynamic slides from database
    $dbSlides = ($heroSlides ?? collect());

    if ($dbSlides->isEmpty()) {
      // Fallback default slides if none in DB
      $dbSlides = collect([
        (object)['id' => 1, 'title' => 'Robotics & Automation Lab', 'image' => 'assets/banners/banner_robotics_lab.jpg', 'excerpt' => null],
        (object)['id' => 2, 'title' => 'Modern Innovation Hub', 'image' => 'assets/banners/banner_innovation_hub.jpg', 'excerpt' => null],
        (object)['id' => 3, 'title' => 'Electrical & Advanced Workshop', 'image' => 'assets/banners/banner_electrical_lab.jpg', 'excerpt' => null],
      ]);
    }

    if ($heroSliderMode === 'fixed') {
      // Single Fixed Banner Mode - pick chosen slide or the first published slide
      $fixedSlide = $dbSlides->firstWhere('id', (int)$heroFixedId) ?? $dbSlides->first();
      $activeSlides = collect([$fixedSlide]);
    } else {
      // Auto Sliding Mode - all published slides (whether 2, 5, 10 or more)
      $activeSlides = $dbSlides;
    }
  @endphp
  @php
    $heroAnimSetting = $settings->get('hero_slider_animation', 'zoom');
  @endphp
  <section id="home" class="hero-slider-wrap {{ $heroAnimSetting !== 'none' ? 'hero-slider-animated' : '' }}" aria-label="Campus Hero Banner">
    <div class="hero-slides-container">
      @foreach($activeSlides as $idx => $slide)
        @php
          $slideImg = storage_asset($slide->image, 'assets/banners/banner_robotics_lab.jpg');
        @endphp
        <div class="hero-slide {{ $idx === 0 ? 'active' : '' }}" data-slide="{{ $idx }}">
          @if(!empty($slide->excerpt))
            <a href="{{ $slide->excerpt }}" style="display:block;width:100%;height:100%;">
              <img src="{{ $slideImg }}" alt="{{ $slide->title ?? ('MCTSC Technical Campus Banner ' . ($idx + 1)) }}" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}">
            </a>
          @else
            <img src="{{ $slideImg }}" alt="{{ $slide->title ?? ('MCTSC Technical Campus Banner ' . ($idx + 1)) }}" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}">
          @endif
        </div>
      @endforeach
    </div>

    @if($activeSlides->count() > 1 && $heroSliderMode !== 'fixed')
      <!-- Controls -->
      <button class="hero-slider-btn hero-slider-prev" type="button" aria-label="Previous Slide">❮</button>
      <button class="hero-slider-btn hero-slider-next" type="button" aria-label="Next Slide">❯</button>

      <!-- Indicators -->
      <div class="hero-slider-dots">
        @foreach($activeSlides as $idx => $slide)
          <button class="hero-dot {{ $idx === 0 ? 'active' : '' }}" type="button" data-index="{{ $idx }}" aria-label="Go to Slide {{ $idx + 1 }}"></button>
        @endforeach
      </div>
    @endif
  </section>

  <!-- 2. ABOUT SECTION: Institute Background (Directly below Hero Slider, No Notice Bar) -->
  <section id="about" class="section about-section">
    <div class="container">
      <div class="about-grid">
        <div class="about-copy">
          <h2>{{ $settings->get('about_title') ?: ($isBn ? 'ইনস্টিটিউটের পটভূমি' : 'Institute Background') }}</h2>
          <div class="gold-rule"></div>
          <div class="about-summary-text" style="font-size: 15px; line-height: 1.75; color: #334d44; margin-bottom: 24px; text-align: justify; text-justify: inter-word;">
            @php
              $customAbout = $settings->get('about');
            @endphp
            @if($isBn && !empty($settings->get('about_bn')))
              @foreach(explode("\n\n", str_replace("\r\n", "\n", $settings->get('about_bn'))) as $par)
                @if(trim($par))
                  <p style="margin: 0 0 14px 0;">{!! nl2br(e(trim($par))) !!}</p>
                @endif
              @endforeach
            @elseif(!empty($customAbout))
              @foreach(explode("\n\n", str_replace("\r\n", "\n", $customAbout)) as $par)
                @if(trim($par))
                  <p style="margin: 0 0 14px 0;">{!! nl2br(e(trim($par))) !!}</p>
                @endif
              @endforeach
            @else
              @if($isBn)
                <p style="margin: 0 0 14px 0;">
                  <strong>মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ (MCTSC)</strong> বাংলাদেশের চট্টগ্রামের পাহাড়তলী থানার সাগরিকা এলাকায় অবস্থিত একটি কারিগরি ও বৃত্তিমূলক শিক্ষা ও প্রশিক্ষণ (TVET) প্রতিষ্ঠান। মরহুম মাহমুদুন্নবী চৌধুরীর স্মরণে প্রতিষ্ঠিত <strong>মাহমুদুন্নবী ফাউন্ডেশন</strong> এবং <strong>ইমপ্যাক্ট ইনিশিয়েটিভ (IMI)</strong>-এর যৌথ উদ্যোগে ২০০৬ সালে প্রতিষ্ঠিত এ প্রতিষ্ঠানটি দক্ষ মানবসম্পদ গড়ে তুলতে এবং সামাজিক ও অর্থনৈতিকভাবে পিছিয়ে পড়া জনগোষ্ঠীর জন্য কর্মসংস্থানের সুযোগ সৃষ্টি করতে প্রতিশ্রুতিবদ্ধ।
                </p>
                <p style="margin: 0 0 14px 0;">
                  <strong>ইমপ্যাক্ট ইনিশিয়েটিভ</strong> কর্তৃক পরিচালিত MCTSC বেকার, অদক্ষ, অনগ্রসর ও সুবিধাবঞ্চিত তরুণ-তরুণীদের জন্য বৃত্তিমূলক ও কারিগরি প্রশিক্ষণের সুযোগ প্রদান করে। ব্যবহারিক ও কর্মমুখী প্রশিক্ষণ, জীবনদক্ষতা উন্নয়ন এবং প্রশিক্ষণোত্তর কর্মসংস্থান সহায়তার মাধ্যমে প্রতিষ্ঠানটি ট্রেইনিদের টেকসই জীবিকা অর্জনের উপযোগী জ্ঞান, দক্ষতা ও আত্মবিশ্বাস অর্জনে সহায়তা করে।
                </p>
                <p style="margin: 0;">
                  প্রতিষ্ঠালগ্ন থেকেই MCTSC তরুণদের শ্রমবাজারের উপযোগী বাস্তবমুখী দক্ষতায় সজ্জিত করে কারিগরি শিক্ষা ও কর্মসংস্থানের মধ্যকার দক্ষতার ব্যবধান দূর করতে নিরলসভাবে কাজ করে চলেছে।
                </p>
              @else
                <p style="margin: 0 0 14px 0;">
                  <strong>Mahmudunnabi Chowdhury Technical School & College (MCTSC)</strong> is a Technical and Vocational Education and Training (TVET) institution located in Sagorika, Pahartali, Chattogram, Bangladesh. Established in 2006 as a joint initiative of the <strong>Mahmudunnabi Foundation</strong>, established in memory of Mahmudunnabi Chowdhury, and <strong>Impact Initiative (IMI)</strong>, the institution is committed to developing skilled human resources and creating employment opportunities for socially and economically disadvantaged people.
                </p>
                <p style="margin: 0 0 14px 0;">
                  Managed by <strong>Impact Initiative</strong>, MCTSC provides vocational and technical training opportunities for unemployed, unskilled, underprivileged group and distressed young men and women. Through practical, employment-oriented training, life-skills development, and post-training employment support, the institution helps trainees develop the knowledge, skills, and confidence needed to build sustainable livelihoods.
                </p>
                <p style="margin: 0;">
                  Since its establishment, MCTSC has worked to bridge the competency gap between technical education and employment by equipping young people with practical skills relevant to the labour market.
                </p>
              @endif
            @endif
          </div>
          <a class="button button-green" href="{{ route('about') }}">{{ $isBn ? 'আরও বিস্তারিত দেখুন' : 'Read More' }} <span>→</span></a>
        </div>
        <div class="about-image">
          @php
            $aboutImgSrc = storage_asset($settings->get('about_image'), 'assets/about_mahmudunnabi_legacy.jpg');
            if (empty($settings->get('about_image')) || $settings->get('about_image') === 'assets/MC Building Photo.jpg') {
                $aboutImgSrc = asset('assets/about_mahmudunnabi_legacy.jpg');
            }
          @endphp
          <img src="{{ $aboutImgSrc }}" alt="Mahmudunnabi Chowdhury Legacy & Campus">
        </div>
      </div>
    </div>
  </section>

  <!-- 3. VISION, MISSION & OBJECTIVES (PILL BADGE VIEW & TEXT-BASED AUTO-HEIGHT) -->
  <section id="vision-mission-objectives" class="section vmo-section">
    <div class="container">
      <div class="vmo-layout">
        <!-- Left Column: Vision & Mission (Stacked vertically with snug auto-height) -->
        <div class="vmo-col vmo-col-left">
          <!-- Vision Card -->
          <div class="vmo-card-wrap">
            <div class="vmo-pill pill-vision">{{ $settings->get('vmo_vision_pill') ?: ($isBn ? 'রূপকল্প' : 'Vision') }}</div>
            <div class="vmo-box">
              <p>
                @if($isBn && !empty($settings->get('vmo_vision_text_bn')))
                  {{ $settings->get('vmo_vision_text_bn') }}
                @elseif(!empty($settings->get('vmo_vision_text')))
                  {{ $settings->get('vmo_vision_text') }}
                @else
                  {{ $isBn 
                    ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ (MCTSC) সুবিধাবঞ্চিত যুবক, পুরুষ ও নারীদের কর্মসংস্থানের সুযোগ সৃষ্টি করতে এবং দেশের মূলধারার উন্নয়নমূলক কর্মকাণ্ডে তাদের অংশগ্রহণে উদ্বুদ্ধ করতে কাজ করে।' 
                    : "Mahmudunnabi Chowdhury Technical School and College (MCTSC) envisions employing socially disadvantaged youth, men, and women, and encourages them to participate in the country's mainstream development initiatives." }}
                @endif
              </p>
            </div>
          </div>

          <!-- Mission Card -->
          <div class="vmo-card-wrap">
            <div class="vmo-pill pill-mission">{{ $settings->get('vmo_mission_pill') ?: ($isBn ? 'লক্ষ্য' : 'Mission') }}</div>
            <div class="vmo-box">
              <p>
                @if($isBn && !empty($settings->get('vmo_mission_text_bn')))
                  {{ $settings->get('vmo_mission_text_bn') }}
                @elseif(!empty($settings->get('vmo_mission_text')))
                  {{ $settings->get('vmo_mission_text') }}
                @else
                  {{ $isBn 
                    ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ (MCTSC)-এর মূল লক্ষ্য হলো উচ্চমান বজায় রাখা, পরিকল্পনা ও সিদ্ধান্ত গ্রহণের জন্য নির্ভরযোগ্য তথ্য নিশ্চিতকরণ, তহবিল সংগ্রহ ও সঠিক বণ্টন এবং শিল্পের সাথে কার্যকর সংযোগ স্থাপনের মাধ্যমে দক্ষ ও আধা-দক্ষ কারিগরি ও পেশাদার জনশক্তি তৈরি করে জাতীয় অর্থনীতিকে পুনরুজ্জীবিত ও টেকসই করা এবং বেকারত্ব ও দারিদ্র্য হ্রাস করা।' 
                    : 'Mahmudunnabi Chowdhury Technical School and College (MCTSC) is to promote the production of skilled/semi-skilled technical and professional manpower, to revitalize and sustain the national economy, reduce unemployment and poverty through the setting and maintenance of high standards, provision of current and reliable information for planning and decision making, sourcing and disbursing of funds, and adequate linkages with industry.' }}
                @endif
              </p>
            </div>
          </div>
        </div>

        <!-- Right Column: Objectives Card -->
        <div class="vmo-col vmo-col-right">
          <div class="vmo-card-wrap">
            <div class="vmo-pill pill-objectives">{{ $settings->get('vmo_objectives_pill') ?: ($isBn ? 'উদ্দেশ্যসমূহ' : 'Objectives') }}</div>
            <div class="vmo-box">
              <ul class="vmo-objectives-list">
                @php
                  $customObjs = $settings->get($isBn ? 'vmo_objectives_text_bn' : 'vmo_objectives_text') ?: $settings->get('vmo_objectives_text');
                  $objsList = $customObjs ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $customObjs)))) : null;
                @endphp
                @if(!empty($objsList))
                  @foreach($objsList as $objItem)
                    <li><span>✓</span> <div>{{ $objItem }}</div></li>
                  @endforeach
                @else
                  @if($isBn)
                    <li><span>✓</span> <div>সহজলভ্য, বাস্তবমুখী ও কর্মসংস্থানমুখী কারিগরি ও বৃত্তিমূলক শিক্ষা ও প্রশিক্ষণ প্রদান।</div></li>
                    <li><span>✓</span> <div>সুবিধাবঞ্চিত ও বেকার যুবসমাজের কারিগরি, পেশাগত ও জীবন দক্ষতার বিকাশ সাধন।</div></li>
                    <li><span>✓</span> <div>আত্মকর্মসংস্থান উৎসাহিত করা এবং উপযুক্ত কর্মসংস্থান ও উদ্যোক্তা তৈরির সুযোগ সৃষ্টি।</div></li>
                    <li><span>✓</span> <div>শিল্পপ্রতিষ্ঠান ও নিয়োগকারীদের সাথে অংশীদারিত্ব জোরদার করে প্রশিক্ষণের উপযোগিতা ও চাকরি প্রাপ্তি নিশ্চিত করা।</div></li>
                    <li><span>✓</span> <div>দক্ষতা উন্নয়ন ও টেকসই জীবিকার মাধ্যমে বেকারত্ব ও দারিদ্র্য হ্রাসকরণে অবদান রাখা।</div></li>
                    <li><span>✓</span> <div>প্রশিক্ষণোত্তর কর্মসংস্থান সহায়তা এবং নিয়মিত ফলোআপের মাধ্যমে ট্রেইনিদের সহায়তা প্রদান।</div></li>
                  @else
                    <li><span>✓</span> <div>To provide accessible, practical, and employment-oriented technical and vocational education and training.</div></li>
                    <li><span>✓</span> <div>To develop the technical, professional, and life skills of disadvantaged and unemployed youth.</div></li>
                    <li><span>✓</span> <div>To promote self-employment and facilitate access to suitable employment and entrepreneurship development opportunities.</div></li>
                    <li><span>✓</span> <div>To strengthen collaboration with industries and employers to improve training relevance and job placement.</div></li>
                    <li><span>✓</span> <div>To contribute to reducing unemployment and poverty through skills development and sustainable livelihoods.</div></li>
                    <li><span>✓</span> <div>To support trainees through post-training employment assistance and follow-up.</div></li>
                  @endif
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- STRATEGIC PARTNERSHIP WITH IMPACT INITIATIVE (IMI) - CMS POWERED & 100% EDITABLE -->
  <section class="section" style="padding-top: 10px; padding-bottom: 24px;">
    <div class="container">
      <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 28px 34px; box-shadow: 0 4px 16px rgba(16, 185, 129, 0.05);">
        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px;">
          <span style="font-size: 28px;">🤝</span>
          <div>
            <span style="font-size: 12px; font-weight: 700; color: #166534; letter-spacing: 1.5px; text-transform: uppercase;">
              {{ $settings->get('strategic_partnership_kicker') ?: ($isBn ? 'কৌশলগত অংশীদারিত্ব' : 'STRATEGIC PARTNERSHIP') }}
            </span>
            <h3 style="font-family: var(--serif); font-size: 22px; color: #14532d; margin: 2px 0 0 0;">
              {{ $settings->get('strategic_partnership_title') ?: ($isBn ? 'ইমপ্যাক্ট ইনিশিয়েটিভ (Impact Initiative)-এর সাথে অংশীদারিত্ব' : 'Partnership with Impact Initiative') }}
            </h3>
          </div>
        </div>
        <div style="font-size: 15px; line-height: 1.8; color: #166534; text-align: justify; text-justify: inter-word;">
          @php
            $hasSpBodyBn = $settings->has('strategic_partnership_body_bn');
            $hasSpBodyEn = $settings->has('strategic_partnership_body');
            if ($isBn && $hasSpBodyBn) {
                $spBodyText = $settings->get('strategic_partnership_body_bn');
            } elseif ($hasSpBodyEn) {
                $spBodyText = $settings->get('strategic_partnership_body');
            } elseif ($isBn) {
                $spBodyText = '<strong>১৯৯৫ সালে</strong> প্রতিষ্ঠিত <strong>ইমপ্যাক্ট ইনিশিয়েটিভ (Impact Initiative - IMI)</strong> গণপ্রজাতন্ত্রী বাংলাদেশ সরকারের প্রধানমন্ত্রীর কার্যালয়াধীন <strong>এনজিও বিষয়ক ব্যুরো (নিবন্ধন নং: ১২২৪)</strong> কর্তৃক নিবন্ধিত একটি শীর্ষস্থানীয় জাতীয় পর্যায়ের মানবিক ও উন্নয়ন সংস্থা। সংস্থাটির কর্মপরিধি যুব উন্নয়ন, শিক্ষা, দক্ষতা বৃদ্ধি এবং জীবিকায়ন সহ বিভিন্ন সামাজিক উন্নয়নমূলক ক্ষেত্রে বিস্তৃত। এর TVET উদ্যোগের মাধ্যমে সংস্থাটি তরুণ সমাজের ব্যবহারিক সক্ষমতা বৃদ্ধি এবং কর্মসংস্থানের সুযোগ সম্প্রসারণে সক্রিয় ভূমিকা পালন করে আসছে।';
            } else {
                $spBodyText = "Established in <strong>1995</strong>, <strong>Impact Initiative (IMI)</strong> is a national humanitarian and development organization in Bangladesh, registered with the <strong>NGO Affairs Bureau (Registration No: 1224)</strong> under the Prime Minister's Office. Its work spans youth development, education, skills development and livelihoods, among other areas. Through its TVET initiatives, the organization seeks to strengthen young people's practical capabilities and improve their access to employment opportunities.";
            }
          @endphp

          @if(filled(trim(strip_tags($spBodyText ?? ''))))
            <p style="margin-bottom: 12px; text-align: justify; text-justify: inter-word;">{!! nl2br($spBodyText) !!}</p>
          @endif

          @php
            $hasSpFootBn = $settings->has('strategic_partnership_footer_bn');
            $hasSpFootEn = $settings->has('strategic_partnership_footer');
            if ($isBn && $hasSpFootBn) {
                $spFootText = $settings->get('strategic_partnership_footer_bn');
            } elseif ($hasSpFootEn) {
                $spFootText = $settings->get('strategic_partnership_footer');
            } elseif ($isBn) {
                $spFootText = 'এই অংশীদারিত্বের মধ্য দিয়ে মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ যুবসমাজের দক্ষতা উন্নয়ন ও কর্মসংস্থানমূলক প্রশিক্ষণ কার্যক্রম নিরবচ্ছিন্নভাবে পরিচালনা করছে। প্রতিষ্ঠানের কোয়ালিটি এস্যুরেন্স (গুণগত মান নিশ্চিতকরণ)-এর জন্য নিম্নোক্ত ম্যানেজমেন্ট কমিটি গঠন করা হয়েছে।';
            } else {
                $spFootText = 'Through this partnership, Mahmudunnabi Chowdhury Technical School and College continues to support skills development and employment-oriented training. For Quality Assurance of the institute, the following Management Committee has been formed.';
            }
          @endphp

          @if(filled(trim($spFootText ?? '')))
            <p style="margin: 0; font-weight: 600;">{{ $spFootText }}</p>
          @endif

          <!-- Governance / Management Committee for Quality Assurance (CMS Powered & Fully Editable) -->
          <div style="margin-top: 24px; padding-top: 20px; border-top: 1px dashed #86efac;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
              <h4 style="font-family: var(--serif); font-size: 20px; color: #14532d; margin: 0; display: flex; align-items: center; gap: 8px;">
                <span>👥</span> <em>{{ $settings->get('gov_committee_title') ?: ($isBn ? 'পরিচালনা ও ব্যবস্থাপনা কমিটি (Governance / Management Committee)' : 'Governance / Management Committee') }}</em>
              </h4>
              <span style="font-size: 12px; font-weight: 700; color: #166534; background: #dcfce7; padding: 4px 10px; border-radius: 4px; border: 1px solid #bbf7d0; text-transform: uppercase; letter-spacing: 0.5px;">
                {{ $isBn ? 'কোয়ালিটি এস্যুরেন্স বডি' : 'Quality Assurance' }}
              </span>
            </div>
            @php
              $hasGovIntroBn = $settings->has('gov_committee_intro_bn');
              $hasGovIntroEn = $settings->has('gov_committee_intro');
              if ($isBn && $hasGovIntroBn) {
                  $govIntroText = $settings->get('gov_committee_intro_bn');
              } elseif ($hasGovIntroEn) {
                  $govIntroText = $settings->get('gov_committee_intro');
              } elseif ($isBn) {
                  $govIntroText = 'MCTSC-এর একটি ইনস্টিটিউট ম্যানেজমেন্ট কমিটি রয়েছে। এই কমিটি ৫ জন সদস্যের সমন্বয়ে গঠিত। এই ম্যানেজমেন্ট কমিটি (MC) প্রতিষ্ঠানের কোয়ালিটি এস্যুরেন্স (গুণগত মান নিশ্চিতকরণ)-এর দায়িত্ব পালন করে। নিম্নোক্ত ব্যক্তিবর্গ এই কমিটির সম্মানিত সদস্য:';
              } else {
                  $govIntroText = 'MCTSC has an Institute Management Committee. This Committee is comprised of 5 members. This Management Committee (MC) is responsible for Quality Assurance of the Institute. The following people are the members of the committee:';
              }
            @endphp
            @if(filled(trim($govIntroText ?? '')))
              <p style="font-size: 14.5px; line-height: 1.65; color: #166534; margin: 0 0 16px 0; text-align: justify; text-justify: inter-word;">
                {{ $govIntroText }}
              </p>
            @endif

            @php
              $defaultCommittee = [
                1 => [
                  'name' => 'Md. Enayet Hossain',
                  'position' => 'Chair',
                  'position_bn' => 'চেয়ারম্যান / সভাপতি (Chair)',
                  'qualification' => 'M.Sc. in Psychology',
                  'experience' => '27 years in Education and TVET management',
                ],
                2 => [
                  'name' => 'Dr. Md Shaif Ullah Mansur',
                  'position' => 'Vice Chair',
                  'position_bn' => 'ভাইস চেয়ারম্যান (Vice Chair)',
                  'qualification' => 'Ph.D. in the Bangladesh Constitution, M.Com in Finance',
                  'experience' => 'BGMEA Director',
                ],
                3 => [
                  'name' => 'Md. Mozammel Haque',
                  'position' => 'Member Secretary',
                  'position_bn' => 'সদস্য সচিব (Member Secretary)',
                  'qualification' => 'BA Hons MA',
                  'experience' => '28 years of experience working in Social work',
                ],
                4 => [
                  'name' => 'Shakh Nur Alam',
                  'position' => 'Treasurer',
                  'position_bn' => 'কোষাধ্যক্ষ (Treasurer)',
                  'qualification' => 'B. Com Hons, M. Com',
                  'experience' => "20 years' social work experience",
                ],
                5 => [
                  'name' => 'Md. Mosarrof Hossain',
                  'position' => 'Member',
                  'position_bn' => 'সদস্য (Member)',
                  'qualification' => 'MSS',
                  'experience' => '20 years of working experience in community development, 2 years in TVET',
                ],
              ];
            @endphp

            <div style="overflow-x: auto; background: #ffffff; border-radius: 8px; border: 1px solid #bbf7d0; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.04);">
              <table style="width: 100%; border-collapse: collapse; font-size: 13.5px; text-align: left;">
                <thead>
                  <tr style="background: #14532d; color: #ffffff;">
                    <th style="padding: 10px 14px; width: 45px; text-align: center; border-right: 1px solid rgba(255,255,255,0.15);">{{ $isBn ? 'নং' : 'SN' }}</th>
                    <th style="padding: 10px 14px; min-width: 180px; border-right: 1px solid rgba(255,255,255,0.15);">{{ $isBn ? 'নাম (Name)' : 'Name' }}</th>
                    <th style="padding: 10px 14px; min-width: 140px; border-right: 1px solid rgba(255,255,255,0.15);">{{ $isBn ? 'পদবী (Position)' : 'Position' }}</th>
                    <th style="padding: 10px 14px; min-width: 260px;">{{ $isBn ? 'শিক্ষাগত যোগ্যতা (Educational Qualification)' : 'Educational Qualification' }}</th>
                  </tr>
                </thead>
                <tbody>
                  @for($m = 1; $m <= 5; $m++)
                    @php
                      $def = $defaultCommittee[$m];
                      $mName = $settings->get('gov_member_' . $m . '_name', $def['name']);
                      $mPos = $settings->get('gov_member_' . $m . '_position', ($isBn ? $def['position_bn'] : $def['position']));
                      $mQual = $settings->get('gov_member_' . $m . '_qualification', $def['qualification']);
                      $mExp = $settings->get('gov_member_' . $m . '_experience', $def['experience']);
                    @endphp
                    <tr style="background: {{ $m % 2 === 0 ? '#f8fdfa' : '#ffffff' }}; border-bottom: 1px solid #e2ebe6;">
                      <td style="padding: 11px 14px; text-align: center; font-weight: 700; color: #166534; border-right: 1px solid #eef4f0;">{{ $m }}</td>
                      <td style="padding: 11px 14px; font-weight: 700; color: #1e293b; border-right: 1px solid #eef4f0;">{{ $mName }}</td>
                      <td style="padding: 11px 14px; border-right: 1px solid #eef4f0; font-weight: 600; color: #14532d;">
                        {{ $mPos }}
                      </td>
                      <td style="padding: 11px 14px; color: #334155;">{{ $mQual }}</td>
                    </tr>
                  @endfor
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 4. DEVELOPMENT PARTNERS (EXACT SLIDING MARQUEE AS ON IMPACTINI.ORG) -->
  <section id="development-partners" class="section partners-marquee-section">
    <div class="container">
      <div class="partners-intro">
        <p class="section-kicker" style="justify-content: center;">{{ $settings->get('dev_partners_kicker') ?: ($isBn ? 'অংশীদারিত্ব ও কোলাবোরেশন' : 'COLLABORATION & PARTNERSHIPS') }}</p>
        <h2>{{ $settings->get('dev_partners_title') ?: ($isBn ? 'উন্নয়ন অংশীদারগণ' : 'Development Partners') }}</h2>
        <div class="gold-rule" style="margin: 0 auto 16px auto;"></div>
        <p>
          @if($isBn && !empty($settings->get('dev_partners_text_bn')))
            {{ $settings->get('dev_partners_text_bn') }}
          @elseif(!empty($settings->get('dev_partners_text')))
            {{ $settings->get('dev_partners_text') }}
          @else
            @if($isBn)
              MCTSC-এর প্রাতিষ্ঠানিক পথচলায় বিভিন্ন শীর্ষস্থানীয় উন্নয়ন সংস্থা ও কর্মসূচির সাথে যৌথভাবে কাজ করার অভিজ্ঞতা রয়েছে, যার মধ্যে ASSET, POWER_kfw-BRAC, UPPR-UNDP, SOS, ILO, ECHO এবং MAA International অন্যতম। এই অংশীদারিত্বগুলো দক্ষ জনশক্তি গড়ে তোলা ও কর্মসংস্থানমুখী প্রশিক্ষণ কার্যক্রম পরিচালনায় প্রতিষ্ঠানের অভিজ্ঞতা ও সফলতার প্রতিফলন।
            @else
              MCTSC's institutional profile records collaboration with development organisations and programmes including ASSET, POWER_kfw-BRAC, UPPR-UNDP, SOS, ILO, ECHO, and MAA International, among others. These partnerships reflect the institution's experience in delivering skills development and employment-oriented training initiatives.
            @endif
          @endif
        </p>
      </div>
    </div>

    @php
      $activePartners = ($partners ?? collect())->isNotEmpty() ? $partners->map(function($p) {
          return [
              'title' => $p->title,
              'category' => $p->category,
              'image' => storage_asset($p->image, 'assets/partners/asset_project.svg'),
              'url' => $p->excerpt ?: null,
          ];
      }) : collect([
          ['title' => 'ASSET Project', 'category' => 'DTE / World Bank', 'image' => asset('assets/partners/asset_project.svg'), 'url' => null],
          ['title' => 'BRAC', 'category' => 'POWER_kfw-BRAC', 'image' => asset('assets/partners/brac.png'), 'url' => null],
          ['title' => 'UNDP', 'category' => 'UPPR - UNDP', 'image' => asset('assets/partners/undp.png'), 'url' => null],
          ['title' => 'ILO', 'category' => 'International Labour Organization', 'image' => asset('assets/partners/ilo.svg'), 'url' => null],
          ['title' => 'ECHO', 'category' => 'European Civil Protection & Humanitarian Aid Operations', 'image' => asset('assets/partners/echo.png'), 'url' => null],
          ['title' => 'MAA International', 'category' => 'MAA International', 'image' => asset('assets/partners/maa_international.png'), 'url' => null],
          ['title' => 'SOS Children\'s Villages', 'category' => 'SOS Children\'s Villages', 'image' => asset('assets/partners/sos.svg'), 'url' => null],
          ['title' => 'PAA', 'category' => 'PAA', 'image' => asset('assets/partners/paa.png'), 'url' => null],
          ['title' => 'KfW Development Bank', 'category' => 'KfW', 'image' => asset('assets/partners/partner_kfw.png'), 'url' => null],
      ]);
    @endphp

    <!-- Infinite Scrolling Partner Cards (Exact CSS & Structure as impactini.org) -->
    <div class="imi-lmq" aria-label="Development Partners Carousel">
      <div class="imi-lmq-in">
        <!-- Set 1: Partner Logos -->
        @foreach($activePartners as $p)
          <div class="imi-lcard" title="{{ $p['title'] }}{{ !empty($p['category']) ? ' (' . $p['category'] . ')' : '' }}">
            @if(!empty($p['url']))
              <a href="{{ $p['url'] }}" target="_blank" rel="noopener noreferrer" class="imi-lcard-link">
                <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}">
              </a>
            @else
              <div class="imi-lcard-link">
                <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}">
              </div>
            @endif
          </div>
        @endforeach

        <!-- Set 2: Duplicate Set for Seamless Continuous Sliding Loop -->
        @foreach($activePartners as $p)
          <div class="imi-lcard" aria-hidden="true">
            @if(!empty($p['url']))
              <a href="{{ $p['url'] }}" target="_blank" rel="noopener noreferrer" tabindex="-1" class="imi-lcard-link">
                <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}">
              </a>
            @else
              <div class="imi-lcard-link">
                <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}">
              </div>
            @endif
          </div>
        @endforeach
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

<script src="{{ asset('js/site.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const wrap = document.querySelector('.hero-slider-wrap');
    if (!wrap) return;
    const slides = wrap.querySelectorAll('.hero-slide');
    const dots = wrap.querySelectorAll('.hero-dot');
    const prevBtn = wrap.querySelector('.hero-slider-prev');
    const nextBtn = wrap.querySelector('.hero-slider-next');
    if (slides.length <= 1) {
      if (prevBtn) prevBtn.style.display = 'none';
      if (nextBtn) nextBtn.style.display = 'none';
      const dotsWrap = wrap.querySelector('.hero-slider-dots');
      if (dotsWrap) dotsWrap.style.display = 'none';
      return;
    }

    let current = 0;
    let timer = null;
    const interval = {{ (int)($heroInterval ?: 1000) }};

    function goToSlide(idx) {
      slides[current].classList.remove('active');
      if (dots[current]) dots[current].classList.remove('active');
      current = (idx + slides.length) % slides.length;
      slides[current].classList.add('active');
      if (dots[current]) dots[current].classList.add('active');
    }

    function nextSlide() {
      goToSlide(current + 1);
    }

    function prevSlide() {
      goToSlide(current - 1);
    }

    function startTimer() {
      stopTimer();
      timer = setInterval(nextSlide, interval);
    }

    function stopTimer() {
      if (timer) clearInterval(timer);
    }

    if (nextBtn) nextBtn.addEventListener('click', function (e) { e.preventDefault(); nextSlide(); startTimer(); });
    if (prevBtn) prevBtn.addEventListener('click', function (e) { e.preventDefault(); prevSlide(); startTimer(); });

    dots.forEach((dot, idx) => {
      dot.addEventListener('click', function (e) {
        e.preventDefault();
        goToSlide(idx);
        startTimer();
      });
    });

    wrap.addEventListener('mouseenter', stopTimer);
    wrap.addEventListener('mouseleave', startTimer);

    // Touch swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    wrap.addEventListener('touchstart', function (e) {
      touchStartX = e.changedTouches[0].screenX;
      stopTimer();
    }, { passive: true });
    wrap.addEventListener('touchend', function (e) {
      touchEndX = e.changedTouches[0].screenX;
      if (touchStartX - touchEndX > 45) {
        nextSlide();
      } else if (touchEndX - touchStartX > 45) {
        prevSlide();
      }
      startTimer();
    }, { passive: true });

    startTimer();
  });
</script>
</body>
</html>
