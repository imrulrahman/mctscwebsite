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
      overflow-x: clip !important;
      scrollbar-width: none !important;
      -ms-overflow-style: none !important;
    }
    body {
      overflow-x: clip !important;
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

    /* Badarshah-style Two-Tier Header & Brand Banner */
    .site-header {
      width: 100% !important;
      background: #ffffff !important;
      position: -webkit-sticky !important;
      position: sticky !important;
      top: 0 !important;
      z-index: 1000 !important;
      box-shadow: 0 4px 15px rgba(0,0,0,0.06) !important;
      padding: 0 !important;
      margin: 0 !important;
    }
    .brand-banner {
      background: #ffffff;
      padding: 10px 0 9px 0;
      border-bottom: 1px solid #e2ebe6;
    }
    .brand-banner-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      width: min(1440px, calc(100% - 32px)) !important;
      max-width: 1440px !important;
      margin: 0 auto;
      padding: 0 16px;
      box-sizing: border-box;
    }
    .brand-logo-block {
      display: flex;
      align-items: center;
      flex: 1 1 auto;
      min-width: 0;
    }
    .brand {
      display: flex !important;
      align-items: center !important;
      gap: 13px;
      text-decoration: none !important;
      min-width: 0;
    }
    .brand-emblem-img {
      width: 54px;
      height: 54px;
      object-fit: contain;
      flex-shrink: 0;
      filter: drop-shadow(0 2px 5px rgba(0,0,0,0.08));
      border-radius: 50%;
    }
    .brand-text {
      display: flex;
      flex-direction: column;
      min-width: 0;
    }
    .brand-name-single {
      font-family: var(--serif, serif);
      font-size: clamp(16px, 1.45vw, 21.5px);
      font-weight: 800;
      color: #064e3b;
      margin: 0 0 5px 0;
      line-height: 1.25;
      letter-spacing: -0.2px;
      white-space: nowrap;
    }
    .brand-badges-bar {
      display: flex;
      align-items: center;
      gap: 7px;
      flex-wrap: wrap;
      max-width: 100%;
    }
    .badge-accred {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 2.5px 8.5px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 600;
      line-height: 1.35;
      transition: all 0.2s ease;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    .badge-title-short {
      display: none;
    }
    .badge-accred .badge-icon {
      width: 12.5px;
      height: 12.5px;
      flex-shrink: 0;
    }
    .badge-accred .badge-title {
      font-weight: 700;
    }
    .badge-accred .badge-chips {
      display: inline-flex;
      align-items: center;
      gap: 3px;
    }
    .badge-accred .badge-chips code {
      font-family: inherit;
      font-size: 11px;
      font-weight: 800;
      padding: 0.5px 5px;
      border-radius: 4px;
      letter-spacing: 0.3px;
    }
    .badge-accred.badge-bteb {
      background: #ecfdf5;
      border: 1px solid #a7f3d0;
      color: #065f46;
    }
    .badge-accred.badge-bteb .badge-chips code {
      background: #ffffff;
      border: 1px solid #6ee7b7;
      color: #047857;
    }
    .badge-accred.badge-nsda {
      background: #f0f9ff;
      border: 1px solid #bae6fd;
      color: #0369a1;
    }
    .badge-accred.badge-nsda .badge-chips code {
      background: #ffffff;
      border: 1px solid #7dd3fc;
      color: #0284c7;
    }
    .brand-right-features {
      display: flex;
      align-items: center;
      gap: 14px;
      flex-shrink: 0;
    }
    .header-foundation {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      gap: 3px;
      padding-right: 14px;
      border-right: 2px solid rgba(0, 106, 78, 0.12);
    }
    .foundation-label {
      font-size: 9.5px;
      font-weight: 800;
      letter-spacing: 1.1px;
      text-transform: uppercase;
      color: #047857;
      line-height: 1;
      text-align: center;
      width: 100%;
    }
    .foundation-logo-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: opacity 0.2s ease, transform 0.2s ease;
    }
    .foundation-logo-link:hover {
      opacity: 0.88;
      transform: translateY(-1px);
    }
    .foundation-logo-img {
      height: 32px;
      max-width: 140px;
      object-fit: contain;
      display: block;
    }
    .header-quick-call {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      padding-right: 14px;
      border-right: 2px solid rgba(0, 106, 78, 0.12);
    }
    .badge-admission-live {
      background: rgba(16, 185, 129, 0.12);
      color: #047857;
      font-size: 11.5px;
      font-weight: 700;
      padding: 2.5px 10px;
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 2px;
      border: 1px solid rgba(16, 185, 129, 0.3);
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .badge-admission-live:hover {
      background: #047857;
      color: #ffffff;
      border-color: #047857;
    }
    .pulse-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #10b981;
      display: inline-block;
      box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
      animation: pulse-green 1.8s infinite;
    }
    @keyframes pulse-green {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
      70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .header-phone-link {
      font-size: 12.5px;
      font-weight: 800;
      color: #0f172a;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }
    .header-phone-link:hover {
      color: #e11d48;
    }

    /* Green Navigation Bar (Badarshah / Institutional Technical Style) */
    .main-navbar-strip {
      background: #064e3b;
      background: linear-gradient(180deg, #064e3b 0%, #04382a 100%);
      box-shadow: 0 2px 8px rgba(0,0,0,0.12);
      border-top: 1px solid rgba(255,255,255,0.08);
    }
    .nav-strip-container {
      display: flex;
      align-items: center;
      justify-content: center;
      width: min(1440px, calc(100% - 32px)) !important;
      max-width: 1440px !important;
      margin: 0 auto;
      padding: 0 16px;
      box-sizing: border-box;
      min-height: 50px;
    }
    .main-navbar-strip .main-nav {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: clamp(8px, 1.3vw, 20px) !important;
      font-weight: 600;
      font-size: 15px !important;
      margin: 0 auto;
    }
    .main-navbar-strip .main-nav a {
      color: #f1fdf6;
      text-decoration: none;
      padding: 13px 12px !important;
      font-size: 15px !important;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap !important;
      border-radius: 6px;
      transition: background 0.15s, color 0.15s;
    }
    .main-navbar-strip .main-nav a:hover,
    .main-navbar-strip .main-nav a.active {
      background: rgba(255, 255, 255, 0.16) !important;
      color: #ffffff !important;
    }
    .nav-ico {
      width: 15px;
      height: 15px;
      opacity: 0.92;
      flex-shrink: 0;
    }

    /* Submenu Dropdown in Green Bar */
    .nav-item-dropdown {
      position: relative;
      display: inline-flex;
      align-items: center;
    }
    .nav-item-dropdown .nav-link-parent {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 15px !important;
      font-weight: 600;
      white-space: nowrap !important;
    }
    .nav-item-dropdown .dropdown-caret {
      font-size: 10px;
      transition: transform 0.2s ease;
      display: inline-block;
      color: rgba(255, 255, 255, 0.7);
    }
    .nav-item-dropdown:hover .dropdown-caret {
      transform: rotate(180deg);
      color: #ffffff;
    }
    .nav-item-dropdown .dropdown-menu {
      position: absolute;
      top: 100%;
      left: 0;
      min-width: 195px;
      background: #ffffff;
      border: 1px solid #d7e2dd;
      border-radius: 8px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.16);
      padding: 6px 0;
      display: none;
      z-index: 1000;
    }
    .nav-item-dropdown:hover .dropdown-menu,
    .nav-item-dropdown:focus-within .dropdown-menu {
      display: block;
    }
    .nav-item-dropdown .dropdown-menu a {
      display: block !important;
      padding: 10px 18px !important;
      font-size: 14.5px !important;
      font-weight: 600 !important;
      color: #1e3d35 !important;
      border-bottom: 1px solid #f1f5f3 !important;
      white-space: nowrap !important;
      text-decoration: none !important;
      border-radius: 0 !important;
      background: transparent !important;
    }
    .nav-item-dropdown .dropdown-menu a:last-child {
      border-bottom: none !important;
    }
    .nav-item-dropdown .dropdown-menu a:hover,
    .nav-item-dropdown .dropdown-menu a.active {
      background: #ecfdf5 !important;
      color: #064e3b !important;
    }

    /* Mobile menu button hidden on desktop */
    .menu-toggle {
      display: none;
      border: 0;
      background: #f1f5f9;
      font-size: 20px;
      color: #064e3b;
      padding: 6px 10px;
      border-radius: 6px;
      cursor: pointer;
    }

    /* Language Switcher safe margin */
    .lang-switch-box {
      flex-shrink: 0 !important;
      margin-left: 2px !important;
    }

    @media (max-width: 1200px) and (min-width: 1025px) {
      .main-navbar-strip .main-nav {
        gap: 12px !important;
        font-size: 14px !important;
        justify-content: center;
      }
      .main-navbar-strip .main-nav a {
        font-size: 14px !important;
        padding: 11px 9px !important;
      }
      .brand-name-single {
        font-size: 17px !important;
      }
      .header-foundation {
        padding-right: 10px;
        gap: 3px;
      }
      .foundation-logo-img {
        height: 28px;
        max-width: 120px;
      }
      .header-quick-call {
        padding-right: 10px;
      }
    }
    /* ========================================================
       SEMANTIC RESPONSIVE HEADER ARCHITECTURE
       ======================================================== */
    .header-mobile-controls {
      display: none;
      align-items: center;
      gap: 8px;
      flex-shrink: 0;
    }
    .mobile-nav-bar {
      display: none;
    }

    @media (max-width: 1024px) {
      .site-header {
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 1000 !important;
      }
      .brand-banner {
        padding: 8px 0 6px 0 !important;
      }
      .brand-banner-container {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 14px !important;
        gap: 6px !important;
        box-sizing: border-box !important;
      }
      .brand-logo-block {
        display: flex !important;
        align-items: flex-start !important;
        justify-content: space-between !important;
        width: 100% !important;
        gap: 10px !important;
      }
      .brand {
        display: flex !important;
        align-items: flex-start !important;
        gap: 10px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
      }
      .brand-emblem-img {
        width: 44px !important;
        height: 44px !important;
        min-width: 44px !important;
        margin-top: 2px !important;
      }
      .brand-text {
        flex: 1 1 auto !important;
        min-width: 0 !important;
      }
      .brand-name-single {
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: break-word !important;
        font-size: clamp(14px, 3.8vw, 17.5px) !important;
        line-height: 1.25 !important;
        margin: 0 0 4px 0 !important;
      }
      .brand-badges-bar {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 5px !important;
        max-width: 100% !important;
      }
      .badge-accred {
        font-size: 10.5px !important;
        padding: 2px 7px !important;
        white-space: nowrap !important;
      }
      .badge-accred .badge-chips code {
        font-size: 10px !important;
      }
      .header-mobile-controls {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        flex-shrink: 0 !important;
        margin-top: 2px !important;
      }
      .menu-toggle {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 36px !important;
        height: 36px !important;
        padding: 0 !important;
        border-radius: 6px !important;
        background: #ecfdf5 !important;
        border: 1px solid #a7f3d0 !important;
        color: #064e3b !important;
        font-size: 18px !important;
        cursor: pointer !important;
      }
      .brand-right-features {
        display: flex !important;
        width: 100% !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding-top: 6px !important;
        border-top: 1px dashed #e2e8f0 !important;
        margin-top: 2px !important;
      }
      .header-foundation {
        align-items: center !important;
        text-align: center !important;
        border-right: none !important;
        padding-right: 0 !important;
        gap: 2px !important;
      }
      .foundation-label {
        font-size: 8.5px !important;
        text-align: center !important;
        width: 100% !important;
      }
      .foundation-logo-img {
        height: 25px !important;
        max-width: 110px !important;
      }
      .header-quick-call {
        border-right: none !important;
        padding-right: 0 !important;
      }
      .badge-admission-live {
        display: none !important;
      }
      .header-phone-link {
        font-size: 12px !important;
      }
      .lang-switch-box.lang-switch-desktop {
        display: none !important;
      }

      /* Mobile Navigation Strip & Drawer */
      .main-navbar-strip {
        position: relative !important;
        min-height: auto !important;
      }
      .nav-strip-container {
        padding: 0 14px !important;
        min-height: 44px !important;
        width: 100% !important;
        max-width: 100% !important;
      }
      .mobile-nav-bar {
        display: flex !important;
        width: 100% !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 5px 0 !important;
        gap: 10px !important;
      }
      .mobile-menu-toggle-btn {
        display: inline-flex !important;
        align-items: center !important;
        gap: 7px !important;
        background: rgba(255, 255, 255, 0.14) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        padding: 7px 14px !important;
        border-radius: 6px !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        transition: background 0.15s ease !important;
      }
      .mobile-menu-toggle-btn:hover {
        background: rgba(255, 255, 255, 0.22) !important;
      }
      .mobile-nav-apply-btn {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        background: #c59b27 !important;
        color: #092c1d !important;
        font-weight: 800 !important;
        font-size: 12.5px !important;
        padding: 7px 14px !important;
        border-radius: 6px !important;
        text-decoration: none !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.18) !important;
        transition: transform 0.15s ease, background 0.15s ease !important;
      }
      .mobile-nav-apply-btn:hover {
        background: #d4a72c !important;
        transform: translateY(-1px) !important;
      }
      .pulse-dot-gold {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #092c1d;
        display: inline-block;
      }

      /* Mobile Navigation Drawer */
      .main-navbar-strip .main-nav {
        display: none;
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        right: 0 !important;
        background: #064e3b !important;
        padding: 12px 16px 20px 16px !important;
        box-shadow: 0 18px 35px rgba(0,0,0,0.35) !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0 !important;
        z-index: 1000 !important;
        border-top: 1px solid rgba(255,255,255,0.1) !important;
        border-bottom-left-radius: 12px !important;
        border-bottom-right-radius: 12px !important;
        max-height: 82vh !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
      }
      .main-navbar-strip .main-nav.open {
        display: flex !important;
      }
      .main-navbar-strip .main-nav a {
        padding: 11px 10px !important;
        border-bottom: 1px solid rgba(255,255,255,0.08) !important;
        font-size: 14px !important;
        color: #f1fdf6 !important;
      }
      .nav-item-dropdown {
        flex-direction: column !important;
        align-items: stretch !important;
      }
      .nav-item-dropdown .dropdown-menu {
        position: static !important;
        display: block !important;
        box-shadow: none !important;
        border: none !important;
        padding: 2px 0 4px 12px !important;
        background: rgba(0, 0, 0, 0.22) !important;
        border-radius: 6px !important;
        margin: 3px 0 6px 0 !important;
      }
      .nav-item-dropdown .dropdown-menu a {
        padding: 9px 12px !important;
        font-size: 13.5px !important;
        color: #d1fae5 !important;
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
      }
      .nav-item-dropdown .dropdown-menu a:last-child {
        border-bottom: none !important;
      }
    }

    @media (max-width: 680px) {
      .badge-title-full {
        display: none !important;
      }
      .badge-title-short {
        display: inline !important;
      }
      .badge-accred {
        font-size: 10px !important;
        padding: 2px 6px !important;
      }
      .badge-accred .badge-chips code {
        font-size: 9.5px !important;
      }
    }

    @media (max-width: 480px) {
      .header-foundation .foundation-logo-img {
        height: 22px !important;
        max-width: 95px !important;
      }
      .mobile-menu-toggle-btn {
        padding: 6px 11px !important;
        font-size: 12.5px !important;
      }
      .mobile-nav-apply-btn {
        padding: 6px 11px !important;
        font-size: 11.5px !important;
      }
    }

    /* Key Highlights / Stats Counter Section (Badarshah Style - Responsive) */
    .highlights-section {
      background: #f0f6f3;
      padding: 24px 0 16px 0;
      border-bottom: 1px solid #dce8e1;
      width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
      overflow: hidden !important;
    }
    .highlights-container {
      width: 100% !important;
      max-width: 1440px !important;
      margin: 0 auto !important;
      padding: 0 16px !important;
      box-sizing: border-box !important;
    }
    .highlights-grid {
      display: grid !important;
      grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
      gap: 15px !important;
      width: 100% !important;
      box-sizing: border-box !important;
    }
    .stat-card {
      background: #ffffff;
      border-radius: 14px;
      border: 1px solid #d9e6de;
      box-shadow: 0 4px 15px rgba(0, 106, 78, 0.05);
      padding: 22px 14px 18px 14px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      position: relative;
      overflow: hidden;
      min-width: 0 !important;
      box-sizing: border-box !important;
      word-break: break-word !important;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px rgba(0, 106, 78, 0.12);
    }
    .stat-card-stripe {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4.5px;
    }
    .stat-stripe-1 { background: linear-gradient(90deg, #e11d48, #006a4e); }
    .stat-stripe-2 { background: linear-gradient(90deg, #2563eb, #006a4e); }
    .stat-stripe-3 { background: linear-gradient(90deg, #ea580c, #006a4e); }
    .stat-stripe-4 { background: #006a4e; }
    .stat-stripe-5 { background: linear-gradient(90deg, #0d9488, #10b981); }

    .stat-icon-wrap {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 10px auto;
    }
    .stat-icon-wrap svg {
      width: 22px;
      height: 22px;
    }
    .stat-icon-1 { background: #fce7f3; color: #db2777; }
    .stat-icon-2 { background: #eff6ff; color: #2563eb; }
    .stat-icon-3 { background: #fff7ed; color: #ea580c; }
    .stat-icon-4 { background: #ecfdf5; color: #047857; }
    .stat-icon-5 { background: #f0fdfa; color: #0d9488; }

    .stat-number {
      font-family: var(--serif, serif);
      font-size: clamp(23px, 1.8vw, 30px);
      font-weight: 800;
      color: #064e3b;
      line-height: 1.15;
      margin: 2px 0 5px 0;
      letter-spacing: -0.5px;
    }
    .stat-title {
      font-size: 13.5px;
      font-weight: 700;
      color: #1e3d35;
      line-height: 1.35;
      margin: 0 0 3px 0;
    }
    .stat-sub {
      font-size: 11.5px;
      color: #64748b;
      font-weight: 500;
      line-height: 1.35;
      margin: 0;
    }

    @media (max-width: 1100px) {
      .highlights-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
      }
    }
    @media (max-width: 768px) {
      .highlights-container {
        padding: 0 12px !important;
      }
      .highlights-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
      }
      .stat-card {
        padding: 16px 8px 14px 8px !important;
        border-radius: 12px !important;
      }
      .stat-number {
        font-size: clamp(20px, 5.5vw, 26px) !important;
      }
      .stat-title {
        font-size: 12px !important;
        line-height: 1.3 !important;
      }
      .stat-sub {
        font-size: 10.5px !important;
      }
    }
    @media (max-width: 380px) {
      .highlights-grid {
        grid-template-columns: 1fr !important;
        gap: 10px !important;
      }
    }

    /* Floating Modal for Institute Background */
    .about-modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(6, 78, 59, 0.68);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      z-index: 10050;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.25s ease, visibility 0.25s ease;
    }
    .about-modal-backdrop.open {
      opacity: 1;
      visibility: visible;
    }
    .about-modal-card {
      background: #ffffff;
      width: 100%;
      max-width: 820px;
      max-height: 88vh;
      border-radius: 18px;
      box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.35);
      display: flex;
      flex-direction: column;
      overflow: hidden;
      transform: scale(0.95);
      transition: transform 0.25s ease;
      border: 1px solid rgba(255, 255, 255, 0.8);
    }
    .about-modal-backdrop.open .about-modal-card {
      transform: scale(1);
    }
    .about-modal-head {
      background: linear-gradient(135deg, #064e3b 0%, #006a4e 100%);
      color: #ffffff;
      padding: 16px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #10b981;
    }
    .about-modal-head h3 {
      margin: 0;
      font-family: var(--serif, serif);
      font-size: 19px;
      color: #ffffff;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .about-modal-close {
      background: rgba(255, 255, 255, 0.16);
      border: none;
      color: #ffffff;
      font-size: 22px;
      line-height: 1;
      width: 34px;
      height: 34px;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s ease, transform 0.2s ease;
    }
    .about-modal-close:hover {
      background: rgba(239, 68, 68, 0.9);
      transform: rotate(90deg);
    }
    .about-modal-body {
      padding: 24px;
      overflow-y: auto;
      font-size: 15px;
      line-height: 1.8;
      color: #334155;
      text-align: justify;
      text-justify: inter-word;
    }
    .about-modal-body p {
      margin-bottom: 16px;
    }
    .about-modal-body p:last-child {
      margin-bottom: 0;
    }
    .about-modal-body strong {
      color: #064e3b;
    }
    .about-modal-body .modal-about-img {
      width: 100%;
      max-height: 300px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 18px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
      border: 1px solid #e2ebe6;
    }
    .about-modal-foot {
      padding: 12px 24px;
      background: #f8faf9;
      border-top: 1px solid #e2ebe6;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .btn-readmore-floating {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: #006a4e;
      color: #ffffff !important;
      padding: 8px 18px;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 700;
      border: none;
      cursor: pointer;
      margin-top: 10px;
      box-shadow: 0 4px 12px rgba(0, 106, 78, 0.2);
      transition: all 0.2s ease;
    }
    .btn-readmore-floating:hover {
      background: #064e3b;
      box-shadow: 0 6px 16px rgba(0, 106, 78, 0.3);
      transform: translateY(-1px);
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
      overflow-x: clip !important;
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
      padding: 24px 0 36px 0 !important;
      background: #ffffff;
      width: 100% !important;
    }
    .about-wrap {
      width: 100% !important;
      box-sizing: border-box !important;
    }
    .about-wrap h2 {
      font-size: 30px !important;
      line-height: 1.25 !important;
      margin: 0 0 12px 0 !important;
      color: {{ $primaryColor }} !important;
    }
    .about-summary-text {
      width: 100% !important;
      box-sizing: border-box !important;
      color: #334d44 !important;
      font-size: 15px !important;
      line-height: 1.75 !important;
      text-align: justify !important;
      text-justify: inter-word !important;
      margin-bottom: 20px !important;
    }
    .about-summary-text::after,
    .about-wrap::after {
      content: "";
      display: table;
      clear: both;
    }
    .about-summary-text p,
    .about-copy p:not(.section-kicker) {
      font-size: 15px !important;
      line-height: 1.75 !important;
      color: #334d44 !important;
      text-align: justify !important;
      text-justify: inter-word !important;
      margin: 0 0 14px 0 !important;
    }
    .about-summary-text > p:first-of-type,
    .about-summary-text p:first-child {
      margin-top: 0 !important;
    }
    /* Floated image aligned to the very top with text */
    .about-image-floated {
      float: right !important;
      width: min(350px, 44%) !important;
      margin: 2px 0 16px 24px !important;
      border-radius: 12px !important;
      overflow: hidden !important;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08) !important;
      background: #ffffff !important;
      border: 1px solid #e2ebe6 !important;
      box-sizing: border-box !important;
    }
    .about-image-floated img {
      width: 100% !important;
      height: auto !important;
      display: block !important;
      object-fit: cover !important;
      border-radius: 11px !important;
      transition: transform 0.3s ease !important;
    }
    .about-image-floated:hover img {
      transform: scale(1.02) !important;
    }
    .about-action-row {
      clear: both !important;
      margin-top: 20px !important;
    }
    @media (max-width: 680px) {
      .about-image-floated {
        float: none !important;
        width: 100% !important;
        margin: 0 0 16px 0 !important;
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
    /* ========================================================
       PORTAL 2-COLUMN LAYOUT BELOW HERO BANNER
       Left: Main Content (About, Vision & Mission, Strategic Partnership)
       Right: Sticky Recent Activity Sidebar (Right of About photo, stays fixed as page scrolls)
       ======================================================== */
    .portal-wrapper {
      padding: 0 0 24px;
      background: #ffffff;
      width: 100%;
    }
    .portal-container {
      width: min(1140px, calc(100% - 64px)) !important;
      margin-left: auto !important;
      margin-right: auto !important;
      display: grid;
      grid-template-columns: minmax(0, 1fr) 255px;
      gap: 24px;
      align-items: start;
      box-sizing: border-box !important;
    }
    .portal-main-col {
      min-width: 0;
      width: 100%;
    }
    .portal-main-col .container {
      width: 100% !important;
      max-width: 100% !important;
      padding: 0 !important;
      margin: 0 !important;
    }
    .portal-main-col .section {
      padding-left: 0 !important;
      padding-right: 0 !important;
    }

    /* RIGHT STICKY SIDEBAR */
    .portal-sidebar-col {
      position: -webkit-sticky;
      position: sticky;
      top: 90px;
      align-self: start;
      width: 100%;
      z-index: 15;
      padding-top: 24px;
    }

    /* RECENT ACTIVITY SIDEBAR WIDGET (GOVERNMENT PORTAL STYLE) */
    .sidebar-widget {
      background: #ffffff;
      border-radius: 8px;
      border: 1px solid #cce2d6;
      box-shadow: 0 4px 18px rgba(15, 81, 50, 0.08);
      overflow: hidden;
      margin-bottom: 24px;
    }
    .sidebar-widget-header {
      background: var(--green, #0f5132);
      color: #ffffff;
      padding: 9px 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
      border-bottom: 2px solid var(--gold, #c59b27);
    }
    .sidebar-widget-title {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 13.5px;
      font-weight: 700;
      letter-spacing: 0.2px;
      color: #ffffff;
      white-space: nowrap;
    }
    .sidebar-widget-title svg {
      width: 15px;
      height: 15px;
      stroke: #ffffff;
      flex-shrink: 0;
    }
    .sidebar-widget-viewall {
      font-size: 11px;
      font-weight: 700;
      color: #ffffff;
      background: rgba(255, 255, 255, 0.2);
      padding: 2px 7px;
      border-radius: 4px;
      text-decoration: none;
      transition: background 0.2s;
      white-space: nowrap;
    }
    .sidebar-widget-viewall:hover {
      background: rgba(255, 255, 255, 0.35);
      color: #ffffff;
    }

    .sidebar-activity-list {
      display: flex;
      flex-direction: column;
      max-height: calc(100vh - 190px);
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: rgba(15, 81, 50, 0.25) transparent;
    }
    .sidebar-activity-list::-webkit-scrollbar {
      width: 4px;
    }
    .sidebar-activity-list::-webkit-scrollbar-thumb {
      background: rgba(15, 81, 50, 0.25);
      border-radius: 4px;
    }

    /* Activity Item Row */
    .sidebar-activity-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      padding: 10px 11px;
      border-bottom: 1px solid #edf2f7;
      cursor: pointer;
      transition: background 0.15s ease;
      text-decoration: none;
      color: inherit;
    }
    .sidebar-activity-item:last-child {
      border-bottom: none;
    }
    .sidebar-activity-item:hover {
      background: #f8fafc;
    }
    .sidebar-activity-thumb {
      width: 50px;
      height: 50px;
      border-radius: 6px;
      object-fit: cover;
      flex-shrink: 0;
      background: #f1f5f9;
      border: 1px solid #e2e8f0;
    }
    .sidebar-activity-body {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
      gap: 2px;
    }
    .sidebar-activity-title {
      font-size: 12.5px;
      font-weight: 700;
      color: #0f2942;
      line-height: 1.35;
      margin: 0;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      transition: color 0.15s;
    }
    .sidebar-activity-item:hover .sidebar-activity-title {
      color: var(--green, #0f5132);
    }
    .sidebar-activity-excerpt {
      font-size: 11px;
      line-height: 1.4;
      color: #64748b;
      margin: 0;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .sidebar-activity-readmore {
      font-size: 11px;
      font-weight: 700;
      color: #16a34a;
      display: inline-flex;
      align-items: center;
      gap: 3px;
      margin-top: 2px;
      transition: gap 0.15s;
    }
    .sidebar-activity-item:hover .sidebar-activity-readmore {
      gap: 5px;
    }
    .sidebar-widget-footer {
      padding: 8px 10px;
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
      text-align: center;
    }
    .sidebar-widget-footer a {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 11.5px;
      font-weight: 700;
      color: var(--green, #0f5132);
      text-decoration: none;
      transition: color 0.15s;
    }
    .sidebar-widget-footer a:hover {
      color: var(--gold, #c59b27);
    }

    /* Modal Styling */
    .activity-modal-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(15, 23, 42, 0.75);
      backdrop-filter: blur(4px);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      box-sizing: border-box;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.25s ease, visibility 0.25s ease;
    }
    .activity-modal-backdrop.is-open {
      opacity: 1;
      visibility: visible;
    }
    .activity-modal-dialog {
      background: #ffffff;
      border-radius: 20px;
      width: 100%;
      max-width: 720px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      position: relative;
      transform: scale(0.95);
      transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
      box-sizing: border-box;
    }
    .activity-modal-backdrop.is-open .activity-modal-dialog {
      transform: scale(1);
    }
    .activity-modal-close {
      position: absolute;
      top: 16px;
      right: 16px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid #e2e8f0;
      color: #0f172a;
      font-size: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 10;
      transition: all 0.2s;
    }
    .activity-modal-close:hover {
      background: #f1f5f9;
      transform: scale(1.05);
    }
    .activity-modal-body-text {
      font-size: 15px;
      line-height: 1.85;
      color: #334155;
      text-align: justify;
      text-justify: inter-word;
      word-break: break-word;
    }
    .activity-modal-body-text p {
      margin: 0 0 16px 0;
      line-height: 1.85;
      color: #334155;
      text-align: justify;
      text-justify: inter-word;
    }
    .activity-modal-body-text p:last-child {
      margin-bottom: 0;
    }
    .activity-modal-body-text strong,
    .activity-modal-body-text b {
      color: #0f172a;
      font-weight: 700;
    }
    .activity-modal-body-text iframe,
    .activity-modal-body-text iframe.ql-video {
      width: 100%;
      max-width: 100%;
      aspect-ratio: 16 / 9;
      min-height: 280px;
      border: 0;
      border-radius: 10px;
      display: block;
      margin: 14px 0 18px 0;
    }

    @media (max-width: 991px) {
      .portal-container {
        grid-template-columns: 1fr;
        gap: 24px;
      }
      .portal-sidebar-col {
        position: static;
        padding-top: 0;
      }
      .sidebar-activity-list {
        max-height: none;
        overflow-y: visible;
      }
    }
  </style>
</head>
<body>
<header class="site-header">
  <!-- Tier 1: White Brand Banner (Institue Logo + Name + Accreditation Badges + Implemented by Impact Initiative + Helpline & Admissions) -->
  <div class="brand-banner">
    <div class="container brand-banner-container">
      <div class="brand-logo-block">
        <a class="brand" href="{{ route('home') }}">
          <img src="{{ $siteLogo }}" alt="{{ $settings->get('institution_name', 'MCTSC') }} logo" class="brand-emblem-img" data-live-edit data-live-key="site_logo" data-live-type="image" data-live-title="অফিসিয়াল মনোগ্রাম / লোগো" data-live-location="হেডার (Header)">
          <div class="brand-text">
            <h1 class="brand-name-single">
              <span data-live-edit data-live-key="{{ $isBn ? 'institution_name_bn' : 'institution_name' }}" data-live-type="text" data-live-title="প্রতিষ্ঠানের নাম" data-live-location="হেডার (Header)">
                {{ $isBn ? $settings->get('institution_name_bn', 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল অ্যান্ড কলেজ') : $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }}
              </span>
            </h1>
            <div class="brand-badges-bar">
              <span class="badge-accred badge-bteb" title="বাংলাদেশ কারিগরি শিক্ষা বোর্ড অনুমোদিত - কোড: ৭০১০২">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="badge-icon"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg>
                <span class="badge-title badge-title-full">BTEB Institute Code:</span>
                <span class="badge-title badge-title-short">BTEB Code:</span>
                <span class="badge-chips"><code>70102</code></span>
              </span>
              <span class="badge-accred badge-nsda" title="জাতীয় দক্ষতা উন্নয়ন কর্তৃপক্ষ অনুমোদিত - নিবন্ধন: STP-CHA-000135">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="badge-icon"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                <span class="badge-title badge-title-full">NSDA Registration:</span>
                <span class="badge-title badge-title-short">NSDA Reg:</span>
                <span class="badge-chips"><code>STP-CHA-000135</code></span>
              </span>
            </div>
          </div>
        </a>

        <!-- Mobile Quick Lang Switcher & Mobile Menu Toggle (Visible right beside brand on small screens) -->
        <div class="header-mobile-controls">
          <div class="lang-switch-box" title="{{ $isBn ? 'Switch to English' : 'বাংলা ভাষায় দেখুন' }}">
            <a href="{{ route('lang.switch', 'en') }}" class="lang-code {{ !$isBn ? 'active' : 'inactive' }}" title="English Version">EN</a>
            <a href="{{ route('lang.switch', $isBn ? 'en' : 'bn') }}" class="toggle-slider-track {{ $isBn ? 'is-bn' : 'is-en' }}" aria-label="{{ $isBn ? 'Switch to English' : 'Switch to Bangla' }}" title="{{ $isBn ? 'Switch to English' : 'Switch to Bangla' }}">
              <div class="toggle-slider-knob">
                @if($isBn)
                  <svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <clipPath id="bd-knob-clip-mob"><circle cx="30" cy="30" r="30"/></clipPath>
                    <g clip-path="url(#bd-knob-clip-mob)">
                      <rect width="60" height="60" fill="#006a4e"/>
                      <circle cx="28" cy="30" r="14" fill="#f42a41"/>
                    </g>
                  </svg>
                @else
                  <svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <clipPath id="uk-knob-clip-mob"><circle cx="30" cy="30" r="30"/></clipPath>
                    <g clip-path="url(#uk-knob-clip-mob)">
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

          <button class="menu-toggle" aria-label="Open navigation" aria-expanded="false" type="button">
            <span class="menu-icon-bars">☰</span>
          </button>
        </div>
      </div>

      <!-- Right Side: Implemented by Impact Initiative + Live Admission & Helpline + Desktop Language Switcher -->
      <div class="brand-right-features">
        <div class="header-foundation" title="{{ $isBn ? 'বাস্তবায়নে: ইমপ্যাক্ট ইনিশিয়েটিভ' : 'Implemented by Impact Initiative' }}">
          <span class="foundation-label">{{ $isBn ? 'বাস্তবায়নে' : 'IMPLEMENTED BY' }}</span>
          <a href="https://impactini.org" target="_blank" rel="noopener noreferrer" class="foundation-logo-link" title="Visit Impact Initiative Website">
            <img src="{{ asset('assets/impact_initiative_official.png') }}" alt="Impact Initiative" class="foundation-logo-img">
          </a>
        </div>

        <div class="header-quick-call">
          <a href="{{ route('apply') }}" class="badge-admission-live" title="{{ $isBn ? 'ভর্তি আবেদন করুন' : 'Apply for Admission' }}">
            <span class="pulse-dot"></span>
            {{ $isBn ? 'ভর্তি আবেদন' : 'Apply for Admission' }}
          </a>
          <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->get('contact_phone', '01880919275')) }}" class="header-phone-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;color:#e11d48;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            {{ $settings->get('contact_phone', '01880919275') }}
          </a>
        </div>

        <!-- SKEUOMORPHIC FLAG LANGUAGE SWITCHER (Desktop) -->
        <div class="lang-switch-box lang-switch-desktop" title="{{ $isBn ? 'Switch to English' : 'বাংলা ভাষায় দেখুন' }}">
          <a href="{{ route('lang.switch', 'en') }}" class="lang-code {{ !$isBn ? 'active' : 'inactive' }}" title="English Version">EN</a>
          <a href="{{ route('lang.switch', $isBn ? 'en' : 'bn') }}" class="toggle-slider-track {{ $isBn ? 'is-bn' : 'is-en' }}" aria-label="{{ $isBn ? 'Switch to English' : 'Switch to Bangla' }}" title="{{ $isBn ? 'Switch to English' : 'Switch to Bangla' }}">
            <div class="toggle-slider-knob">
              @if($isBn)
                <svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <clipPath id="bd-knob-clip"><circle cx="30" cy="30" r="30"/></clipPath>
                  <g clip-path="url(#bd-knob-clip)">
                    <rect width="60" height="60" fill="#006a4e"/>
                    <circle cx="28" cy="30" r="14" fill="#f42a41"/>
                  </g>
                </svg>
              @else
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
  </div>

  <!-- Tier 2: Dark Forest Green Institutional Navigation Bar (Badarshah / BTEB Portal Style) -->
  <div class="main-navbar-strip">
    <div class="container nav-strip-container">
      
      <!-- Mobile Bar visible only on mobile/tablet (< 1024px) -->
      <div class="mobile-nav-bar">
        <button type="button" class="mobile-menu-toggle-btn" aria-label="Toggle navigation" aria-expanded="false">
          <span class="menu-icon-bars">☰</span>
          <span class="menu-btn-label">{{ $isBn ? 'মেনু ব্রাউজ করুন' : 'Browse Menu' }}</span>
          <span class="menu-caret">▾</span>
        </button>
        <a href="{{ route('apply') }}" class="mobile-nav-apply-btn">
          <span class="pulse-dot-gold"></span>
          <span>{{ $isBn ? 'ভর্তি আবেদন' : 'Apply Online' }}</span>
        </a>
      </div>

      <nav class="main-nav" aria-label="Primary navigation">
        <a class="active" href="{{ route('home') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          {{ $isBn ? 'হোম' : 'Home' }}
        </a>
        <div class="nav-item-dropdown">
          <a class="nav-link-parent {{ request()->routeIs('about') || request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('about') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg>
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
        <a class="{{ request()->routeIs('infrastructure') ? 'active' : '' }}" href="{{ route('infrastructure') }}" style="white-space: nowrap;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="22" x2="9" y2="22.01"/><line x1="15" y1="22" x2="15" y2="22.01"/><line x1="9" y1="18" x2="9" y2="18.01"/><line x1="15" y1="18" x2="15" y2="18.01"/><line x1="9" y1="14" x2="9" y2="14.01"/><line x1="15" y1="14" x2="15" y2="14.01"/><line x1="9" y1="10" x2="9" y2="10.01"/><line x1="15" y1="10" x2="15" y2="10.01"/><line x1="9" y1="6" x2="9" y2="6.01"/><line x1="15" y1="6" x2="15" y2="6.01"/></svg>
          {{ $isBn ? 'অবকাঠামো' : 'Infrastructure' }}
        </a>
        <div class="nav-item-dropdown">
          <a class="nav-link-parent" href="{{ route('courses') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
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
        <a href="{{ route('notices') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          {{ $isBn ? 'নোটিশ' : 'Notices' }}
        </a>
        <a href="{{ route('activities') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          {{ $isBn ? 'কার্যক্রম' : 'Activities' }}
        </a>
        <a href="{{ route('gallery') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          {{ $isBn ? 'গ্যালারি' : 'Gallery' }}
        </a>
        <div class="nav-item-dropdown">
          <a class="nav-link-parent {{ request()->routeIs('success_stories*') || request()->routeIs('case_studies*') || request()->routeIs('stories*') ? 'active' : '' }}" href="{{ route('success_stories') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/></svg>
            {{ $isBn ? 'সাফল্যের গল্প' : 'Success Story' }} <span class="dropdown-caret">▾</span>
          </a>
          <div class="dropdown-menu">
            <a class="{{ request()->routeIs('success_stories*') || (request()->routeIs('stories*') && request('type') !== 'case_study') ? 'active' : '' }}" href="{{ route('success_stories') }}">
              {{ $isBn ? 'সাফল্যের গল্প' : 'Success Story' }}
            </a>
            <a class="{{ request()->routeIs('case_studies*') || (request()->routeIs('stories*') && request('type') === 'case_study') ? 'active' : '' }}" href="{{ route('case_studies') }}">
              {{ $isBn ? 'কেস স্টাডি' : 'Case Study' }}
            </a>
          </div>
        </div>
        <a class="{{ request()->routeIs('career*') ? 'active' : '' }}" href="{{ route('career') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          {{ $isBn ? 'ক্যারিয়ার' : 'Career' }}
        </a>
      </nav>
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

  <!-- KEY HIGHLIGHTS / STATS COUNTER STRIP (BADARSHAH STYLE) -->
  <section class="highlights-section" aria-label="Key Institutional Highlights & Statistics">
    <div class="container highlights-container">
      <div class="highlights-grid">
        <!-- Card 1: Regular Students & Trainees -->
        <div class="stat-card">
          <div class="stat-card-stripe stat-stripe-1"></div>
          <div class="stat-icon-wrap stat-icon-1" title="Students & Trainees">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
          </div>
          <div class="stat-number" data-live-edit data-live-key="stat_1_num" data-live-type="text" data-live-title="কার্ড ১ - সংখ্যা" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get('stat_1_num', '280+') }}</div>
          <div class="stat-title" data-live-edit data-live-key="{{ $isBn ? 'stat_1_title_bn' : 'stat_1_title' }}" data-live-type="text" data-live-title="কার্ড ১ - শিরোনাম" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get($isBn ? 'stat_1_title_bn' : 'stat_1_title', $isBn ? 'নিয়মিত শিক্ষার্থী ও ট্রেইনি' : 'Regular Students & Trainees') }}</div>
          <div class="stat-sub" data-live-edit data-live-key="{{ $isBn ? 'stat_1_sub_bn' : 'stat_1_sub' }}" data-live-type="text" data-live-title="কার্ড ১ - সাবটাইটেল" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get($isBn ? 'stat_1_sub_bn' : 'stat_1_sub', $isBn ? 'ডিপ্লোমা ও সিবিটিঅ্যান্ডএ কোর্স' : 'Diploma & CBT&A Courses') }}</div>
        </div>

        <!-- Card 2: Approved Programs -->
        <div class="stat-card">
          <div class="stat-card-stripe stat-stripe-2"></div>
          <div class="stat-icon-wrap stat-icon-2" title="Approved Programs">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
          </div>
          <div class="stat-number" data-live-edit data-live-key="stat_2_num" data-live-type="text" data-live-title="কার্ড ২ - সংখ্যা" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get('stat_2_num', $isBn ? '১৪টি' : '14+') }}</div>
          <div class="stat-title" data-live-edit data-live-key="{{ $isBn ? 'stat_2_title_bn' : 'stat_2_title' }}" data-live-type="text" data-live-title="কার্ড ২ - শিরোনাম" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get($isBn ? 'stat_2_title_bn' : 'stat_2_title', $isBn ? 'অনুমোদিত প্রোগ্রাম' : 'Approved Programs') }}</div>
          <div class="stat-sub" data-live-edit data-live-key="{{ $isBn ? 'stat_2_sub_bn' : 'stat_2_sub' }}" data-live-type="text" data-live-title="কার্ড ২ - সাবটাইটেল" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get($isBn ? 'stat_2_sub_bn' : 'stat_2_sub', $isBn ? '২ ডিপ্লোমা ও ১২ শর্ট কোর্স' : '2 Diploma & 12 Short Courses') }}</div>
        </div>

        <!-- Card 3: Expert Instructors & Trainers -->
        <div class="stat-card">
          <div class="stat-card-stripe stat-stripe-3"></div>
          <div class="stat-icon-wrap stat-icon-3" title="Expert Instructors">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <div class="stat-number" data-live-edit data-live-key="stat_3_num" data-live-type="text" data-live-title="কার্ড ৩ - সংখ্যা" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get('stat_3_num', $isBn ? '১৩ জন' : '13+') }}</div>
          <div class="stat-title" data-live-edit data-live-key="{{ $isBn ? 'stat_3_title_bn' : 'stat_3_title' }}" data-live-type="text" data-live-title="কার্ড ৩ - শিরোনাম" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get($isBn ? 'stat_3_title_bn' : 'stat_3_title', $isBn ? 'দক্ষ শিক্ষক ও প্রশিক্ষক' : 'Expert Instructors & Trainers') }}</div>
          <div class="stat-sub" data-live-edit data-live-key="{{ $isBn ? 'stat_3_sub_bn' : 'stat_3_sub' }}" data-live-type="text" data-live-title="কার্ড ৩ - সাবটাইটেল" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get($isBn ? 'stat_3_sub_bn' : 'stat_3_sub', $isBn ? 'ইঞ্জিনিয়ার ও সার্টিফাইড মেন্টর' : 'Engineers & Certified Mentors') }}</div>
        </div>

        <!-- Card 4: Government Board Recognition -->
        <div class="stat-card">
          <div class="stat-card-stripe stat-stripe-4"></div>
          <div class="stat-icon-wrap stat-icon-4" title="Board Recognition">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
          </div>
          <div class="stat-number" data-live-edit data-live-key="stat_4_num" data-live-type="text" data-live-title="কার্ড ৪ - সংখ্যা" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get('stat_4_num', '100%') }}</div>
          <div class="stat-title" data-live-edit data-live-key="{{ $isBn ? 'stat_4_title_bn' : 'stat_4_title' }}" data-live-type="text" data-live-title="কার্ড ৪ - শিরোনাম" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get($isBn ? 'stat_4_title_bn' : 'stat_4_title', $isBn ? 'সরকারি বোর্ড স্বীকৃতি' : 'Government Board Recognition') }}</div>
          <div class="stat-sub" data-live-edit data-live-key="{{ $isBn ? 'stat_4_sub_bn' : 'stat_4_sub' }}" data-live-type="text" data-live-title="কার্ড ৪ - সাবটাইটেল" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get($isBn ? 'stat_4_sub_bn' : 'stat_4_sub', $isBn ? 'BTEB ও NSDA অনুমোদিত' : 'BTEB & NSDA Affiliated') }}</div>
        </div>

        <!-- Card 5: Employment & Placement -->
        <div class="stat-card">
          <div class="stat-card-stripe stat-stripe-5"></div>
          <div class="stat-icon-wrap stat-icon-5" title="Employment & Placement">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          </div>
          <div class="stat-number" data-live-edit data-live-key="stat_5_num" data-live-type="text" data-live-title="কার্ড ৫ - সংখ্যা" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get('stat_5_num', '94.8%') }}</div>
          <div class="stat-title" data-live-edit data-live-key="{{ $isBn ? 'stat_5_title_bn' : 'stat_5_title' }}" data-live-type="text" data-live-title="কার্ড ৫ - শিরোনাম" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get($isBn ? 'stat_5_title_bn' : 'stat_5_title', $isBn ? 'কর্মসংস্থান ও অ্যাটাচমেন্ট' : 'Employment & Placement') }}</div>
          <div class="stat-sub" data-live-edit data-live-key="{{ $isBn ? 'stat_5_sub_bn' : 'stat_5_sub' }}" data-live-type="text" data-live-title="কার্ড ৫ - সাবটাইটেল" data-live-location="হোমপেজ - পরিসংখ্যান">{{ $settings->get($isBn ? 'stat_5_sub_bn' : 'stat_5_sub', $isBn ? 'শিল্পকারখানা ও ফ্রিল্যান্সিং' : 'Industrial & Freelancing') }}</div>
        </div>
      </div>
    </div>
  </section>

  <!-- PORTAL 2-COLUMN LAYOUT BELOW HERO BANNER -->
  <div class="portal-wrapper">
    <div class="portal-container">
      <div class="portal-main-col">
        <!-- 2. ABOUT SECTION: Institute Background (Directly below Hero Slider, No Notice Bar) -->
  <section id="about" class="section about-section">
    <div class="container">
      <div class="about-wrap">
        <h2 data-live-edit data-live-key="about_title" data-live-type="text" data-live-title="ইনস্টিটিউটের পটভূমি শিরোনাম" data-live-location="হোমপেজ - পটভূমি">{{ $settings->get('about_title') ?: ($isBn ? 'ইনস্টিটিউটের পটভূমি' : 'Institute Background') }}</h2>
        <div class="gold-rule"></div>
        <div class="about-summary-text">
          @php
            $aboutImgSrc = storage_asset($settings->get('about_image'), 'assets/about_mahmudunnabi_legacy.jpg');
            if (empty($settings->get('about_image')) || $settings->get('about_image') === 'assets/MC Building Photo.jpg') {
                $aboutImgSrc = asset('assets/about_mahmudunnabi_legacy.jpg');
            }
          @endphp
          <div class="about-image-floated">
            <img src="{{ $aboutImgSrc }}" alt="Mahmudunnabi Chowdhury Legacy & Campus" data-live-edit data-live-key="about_image" data-live-type="image" data-live-title="ক্যাম্পাস ও পটভূমি ছবি" data-live-location="হোমপেজ - পটভূমি">
          </div>
          @if($isBn)
            <p>
              <strong>মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ (MCTSC)</strong> বাংলাদেশের চট্টগ্রামের পাহাড়তলী থানার সাগরিকা এলাকায় অবস্থিত একটি কারিগরি ও বৃত্তিমূলক শিক্ষা ও প্রশিক্ষণ (TVET) প্রতিষ্ঠান। মরহুম মাহমুদুন্নবী চৌধুরীর স্মরণে প্রতিষ্ঠিত <strong>মাহমুদুন্নবী ফাউন্ডেশন</strong> এবং <strong>ইমপ্যাক্ট ইনিশিয়েটিভ (IMI)</strong>-এর যৌথ উদ্যোগে ২০০৬ সালে প্রতিষ্ঠিত এ প্রতিষ্ঠানটি দক্ষ মানবসম্পদ গড়ে তুলতে এবং সামাজিক ও অর্থনৈতিকভাবে পিছিয়ে পড়া জনগোষ্ঠীর জন্য কর্মসংস্থানের সুযোগ সৃষ্টি করতে প্রতিশ্রুতিবদ্ধ।
            </p>
            <p>
              ২০১৮ সাল থেকে MCTSC কারিগরি ও বৃত্তিমূলক শিক্ষা এবং প্রশিক্ষণে (TVET) সুদীর্ঘ অভিজ্ঞতাসম্পন্ন উন্নয়ন সংস্থা <strong>ইমপ্যাক্ট ইনিশিয়েটিভ (IMI)</strong>-এর সাথে যৌথভাবে কার্যক্রম পরিচালনা করে আসছে...
            </p>
          @else
            <p>
              <strong>Mahmudunnabi Chowdhury Technical School and College (MCTSC)</strong> is a Technical and Vocational Education and Training (TVET) institution located in Sagorika, Pahartali, Chattogram, Bangladesh. Established in 2006, the institution was founded as a joint initiative of the <strong>Mahmudunnabi Foundation</strong>, established in memory of Mahmudunnabi Chowdhury, a Bangladeshi politician, businessman, former minister and father of Amir Khasru Mahmud Chowdhury, the Minister, Ministry of Finance and Ministry of Planning, Government of the People's Republic of Bangladesh.
            </p>
            <p>
              Since 2018, MCTSC has been working jointly with <strong>Impact Initiative (IMI)</strong>, a development organization with extensive experience in Technical and Vocational Education and Training (TVET)...
            </p>
          @endif

          <div style="margin-top: 14px;">
            <button type="button" id="openAboutModalBtn" class="btn-readmore-floating">
              <span>{{ $isBn ? 'সম্পূর্ণ পটভূমি পড়ুন' : 'Read More' }}</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FLOATING MODAL FOR FULL INSTITUTE BACKGROUND -->
  <div id="aboutDetailModal" class="about-modal-backdrop" aria-hidden="true" role="dialog" aria-labelledby="aboutModalTitle">
    <div class="about-modal-card">
      <div class="about-modal-head">
        <h3 id="aboutModalTitle">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px;"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg>
          <span>{{ $settings->get('about_title') ?: ($isBn ? 'ইনস্টিটিউটের পূর্ণাঙ্গ পটভূমি' : 'Institute Background') }}</span>
        </h3>
        <button type="button" id="closeAboutModalBtn" class="about-modal-close" aria-label="Close dialog">&times;</button>
      </div>
      <div class="about-modal-body" data-live-edit data-live-key="{{ $isBn ? 'about_bn' : 'about' }}" data-live-type="textarea" data-live-title="ইনস্টিটিউটের পটভূমি পূর্ণাঙ্গ বিবরণ" data-live-location="হোমপেজ - পটভূমি">
        <img src="{{ $aboutImgSrc }}" alt="Mahmudunnabi Chowdhury Legacy & Campus" class="modal-about-img">
        @php
          $customAbout = $settings->get('about');
        @endphp
        @if($isBn && !empty($settings->get('about_bn')))
          @foreach(explode("\n\n", str_replace("\r\n", "\n", $settings->get('about_bn'))) as $par)
            @if(trim($par))
              <p>{!! nl2br(e(trim($par))) !!}</p>
            @endif
          @endforeach
        @elseif(!empty($customAbout))
          @foreach(explode("\n\n", str_replace("\r\n", "\n", $customAbout)) as $par)
            @if(trim($par))
              <p>{!! nl2br(e(trim($par))) !!}</p>
            @endif
          @endforeach
        @else
          @if($isBn)
            <p>
              <strong>মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ (MCTSC)</strong> বাংলাদেশের চট্টগ্রামের পাহাড়তলী থানার সাগরিকা এলাকায় অবস্থিত একটি কারিগরি ও বৃত্তিমূলক শিক্ষা ও প্রশিক্ষণ (TVET) প্রতিষ্ঠান। মরহুম মাহমুদুন্নবী চৌধুরীর স্মরণে প্রতিষ্ঠিত <strong>মাহমুদুন্নবী ফাউন্ডেশন</strong> এবং <strong>ইমপ্যাক্ট ইনিশিয়েটিভ (IMI)</strong>-এর যৌথ উদ্যোগে ২০০৬ সালে প্রতিষ্ঠিত এ প্রতিষ্ঠানটি দক্ষ মানবসম্পদ গড়ে তুলতে এবং সামাজিক ও অর্থনৈতিকভাবে পিছিয়ে পড়া জনগোষ্ঠীর জন্য কর্মসংস্থানের সুযোগ সৃষ্টি করতে প্রতিশ্রুতিবদ্ধ।
            </p>
            <p>
              ২০১৮ সাল থেকে MCTSC কারিগরি ও বৃত্তিমূলক শিক্ষা এবং প্রশিক্ষণে (TVET) সুদীর্ঘ অভিজ্ঞতাসম্পন্ন উন্নয়ন সংস্থা <strong>ইমপ্যাক্ট ইনিশিয়েটিভ (IMI)</strong>-এর সাথে যৌথভাবে কার্যক্রম পরিচালনা করে আসছে। এই যৌথ উদ্যোগের লক্ষ্য প্রশিক্ষণ কর্মসূচির গুণগত মান নিশ্চিত করা, উচ্চ প্রশিক্ষণের মান বজায় রাখা এবং দক্ষতা ও উদ্যোক্তা উন্নয়ন কার্যক্রমের মাধ্যমে দারিদ্র্য বিমোচন ও প্রাসঙ্গিকতা বৃদ্ধি করা।
            </p>
            <p>
              <strong>ইমপ্যাক্ট ইনিশিয়েটিভ</strong> কর্তৃক পরিচালিত MCTSC বেকার, অদক্ষ, অনগ্রসর ও সুবিধাবঞ্চিত তরুণ-তরুণীদের জন্য বৃত্তিমূলক ও কারিগরি প্রশিক্ষণের সুযোগ প্রদান করে। ব্যবহারিক ও কর্মমুখী প্রশিক্ষণ, জীবনদক্ষতা উন্নয়ন এবং প্রশিক্ষণোত্তর কর্মসংস্থান সহায়তার মাধ্যমে প্রতিষ্ঠানটি ট্রেইনিদের টেকসই জীবিকা অর্জনের উপযোগী জ্ঞান, দক্ষতা ও আত্মবিশ্বাস অর্জনে সহায়তা করে।
            </p>
            <p>
              প্রতিষ্ঠালগ্ন থেকেই MCTSC তরুণদের শ্রমবাজারের উপযোগী বাস্তবমুখী দক্ষতায় সজ্জিত করে কারিগরি শিক্ষা ও কর্মসংস্থানের মধ্যকার দক্ষতার ব্যবধান দূর করতে নিরলসভাবে কাজ করে চলেছে।
            </p>
          @else
            <p>
              <strong>Mahmudunnabi Chowdhury Technical School and College (MCTSC)</strong> is a Technical and Vocational Education and Training (TVET) institution located in Sagorika, Pahartali, Chattogram, Bangladesh. Established in 2006, the institution was founded as a joint initiative of the <strong>Mahmudunnabi Foundation</strong>, established in memory of Mahmudunnabi Chowdhury, a Bangladeshi politician, businessman, former minister and father of Amir Khasru Mahmud Chowdhury, the Minister, Ministry of Finance and Ministry of Planning, Government of the People's Republic of Bangladesh.
            </p>
            <p>
              Since 2018, MCTSC has been working jointly with <strong>Impact Initiative (IMI)</strong>, a development organization with extensive experience in Technical and Vocational Education and Training (TVET). This collaboration aims to strengthen the quality assurance of training programmes, maintain high training standards, enhance the relevance and Poverty Elevation through Equip with Skills and Entrepreneurship development initiatives.
            </p>
            <p>
              MCTSC is committed to providing vocational and technical training opportunities for unemployed, unskilled, underprivileged, and distressed young men and women. Through practical, employment-oriented training, life-skills development, and post-training employment support, the institution helps trainees develop the knowledge, skills, and confidence needed to build sustainable livelihoods.
            </p>
            <p>
              Since its establishment, MCTSC has worked to bridge the competency gap between technical education and employment by equipping young people with practical skills relevant to the labour market.
            </p>
          @endif
        @endif
      </div>
      <div class="about-modal-foot">
        <a href="{{ route('about') }}" class="button button-green" style="font-size: 13px; padding: 7px 16px;">
          {{ $isBn ? 'আমাদের সম্পর্কে বিস্তারিত পেইজ' : 'Full About Us Page' }} →
        </a>
        <button type="button" id="closeAboutModalFootBtn" class="button" style="background:#e2e8f0; color:#334155; font-size: 13px; padding: 7px 16px; border:none; border-radius:6px; cursor:pointer;">
          {{ $isBn ? 'বন্ধ করুন' : 'Close' }}
        </button>
      </div>
    </div>
  </div>

  <!-- 3. VISION, MISSION & OBJECTIVES (PILL BADGE VIEW & TEXT-BASED AUTO-HEIGHT) -->
  <section id="vision-mission-objectives" class="section vmo-section">
    <div class="container">
      <div class="vmo-layout">
        <!-- Left Column: Vision & Mission (Stacked vertically with snug auto-height) -->
        <div class="vmo-col vmo-col-left">
          <!-- Vision Card -->
          <div class="vmo-card-wrap">
            <div class="vmo-pill pill-vision" data-live-edit data-live-key="vmo_vision_pill" data-live-type="text" data-live-title="Vision (Pill Tag)" data-live-location="Vision & Mission">{{ $settings->get('vmo_vision_pill') ?: ($isBn ? 'রূপকল্প' : 'Vision') }}</div>
            <div class="vmo-box">
              <p data-live-edit data-live-key="{{ $isBn ? 'vmo_vision_text_bn' : 'vmo_vision_text' }}" data-live-type="textarea" data-live-title="Vision (Description)" data-live-location="Vision & Mission">
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
            <div class="vmo-pill pill-mission" data-live-edit data-live-key="vmo_mission_pill" data-live-type="text" data-live-title="Mission (Pill Tag)" data-live-location="Vision & Mission">{{ $settings->get('vmo_mission_pill') ?: ($isBn ? 'লক্ষ্য' : 'Mission') }}</div>
            <div class="vmo-box">
              <p data-live-edit data-live-key="{{ $isBn ? 'vmo_mission_text_bn' : 'vmo_mission_text' }}" data-live-type="textarea" data-live-title="Mission (Description)" data-live-location="Vision & Mission">
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
            <div class="vmo-pill pill-objectives" data-live-edit data-live-key="vmo_objectives_pill" data-live-type="text" data-live-title="Objectives (Pill Tag)" data-live-location="Vision & Mission">{{ $settings->get('vmo_objectives_pill') ?: ($isBn ? 'উদ্দেশ্যসমূহ' : 'Objectives') }}</div>
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
            <span data-live-edit data-live-key="strategic_partnership_kicker" data-live-type="text" data-live-title="STRATEGIC PARTNERSHIP (Badge)" data-live-location="STRATEGIC PARTNERSHIP" style="font-size: 12px; font-weight: 700; color: #166534; letter-spacing: 1.5px; text-transform: uppercase;">
              {{ $settings->get('strategic_partnership_kicker') ?: ($isBn ? 'কৌশলগত অংশীদারিত্ব' : 'STRATEGIC PARTNERSHIP') }}
            </span>
            <h3 data-live-edit data-live-key="strategic_partnership_title" data-live-type="text" data-live-title="STRATEGIC PARTNERSHIP (Title)" data-live-location="STRATEGIC PARTNERSHIP" style="font-family: var(--serif); font-size: 22px; color: #14532d; margin: 2px 0 0 0;">
              {{ $settings->get('strategic_partnership_title') ?: ($isBn ? 'ইমপ্যাক্ট ইনিশিয়েটিভ (Impact Initiative)-এর সাথে অংশীদারিত্ব' : 'Partnership with Impact Initiative') }}
            </h3>
          </div>
        </div>
        <div data-live-edit data-live-key="{{ $isBn ? 'strategic_partnership_body_bn' : 'strategic_partnership_body' }}" data-live-type="textarea" data-live-title="STRATEGIC PARTNERSHIP (Description)" data-live-location="STRATEGIC PARTNERSHIP" style="font-size: 15px; line-height: 1.8; color: #166534; text-align: justify; text-justify: inter-word;">
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
                <span>👥</span> <em data-live-edit data-live-key="gov_committee_title" data-live-type="text" data-live-title="Management Committee Title" data-live-location="Management Committee">{{ $settings->get('gov_committee_title') ?: ($isBn ? 'পরিচালনা ও ব্যবস্থাপনা কমিটি (Governance / Management Committee)' : 'Governance / Management Committee') }}</em>
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
              <p data-live-edit data-live-key="{{ $isBn ? 'gov_committee_intro_bn' : 'gov_committee_intro' }}" data-live-type="textarea" data-live-title="Management Committee Intro" data-live-location="Management Committee" style="font-size: 14.5px; line-height: 1.65; color: #166534; margin: 0 0 16px 0; text-align: justify; text-justify: inter-word;">
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

            <div style="overflow-x: auto; background: #ffffff; border-radius: 8px; border: 1px solid #bbf7d0; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.06); max-width: 780px; margin: 0 auto;">
              <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
                <thead>
                  <tr style="background: #14532d; color: #ffffff;">
                    <th style="padding: 12px 16px; width: 60px; text-align: center; border-right: 1px solid rgba(255,255,255,0.15);">{{ $isBn ? 'নং' : 'SN' }}</th>
                    <th style="padding: 12px 18px; min-width: 220px; border-right: 1px solid rgba(255,255,255,0.15);">{{ $isBn ? 'নাম' : 'Name' }}</th>
                    <th style="padding: 12px 18px; min-width: 200px;">{{ $isBn ? 'পদবী' : 'Position' }}</th>
                  </tr>
                </thead>
                <tbody>
                  @for($m = 1; $m <= 5; $m++)
                    @php
                      $def = $defaultCommittee[$m];
                      $mName = $settings->get('gov_member_' . $m . '_name', $def['name']);
                      $mPos = $settings->get('gov_member_' . $m . '_position', ($isBn ? $def['position_bn'] : $def['position']));
                    @endphp
                    <tr style="background: {{ $m % 2 === 0 ? '#f8fdfa' : '#ffffff' }}; border-bottom: 1px solid #e2ebe6;">
                      <td style="padding: 12px 16px; text-align: center; font-weight: 700; color: #166534; border-right: 1px solid #eef4f0;">{{ $m }}</td>
                      <td style="padding: 12px 18px; font-weight: 700; color: #1e293b; border-right: 1px solid #eef4f0;">
                        <span data-live-edit data-live-key="gov_member_{{ $m }}_name" data-live-type="text" data-live-title="Member #{{ $m }} Name" data-live-location="Management Committee">{{ $mName }}</span>
                      </td>
                      <td style="padding: 12px 18px; font-weight: 600; color: #14532d;">
                        <span data-live-edit data-live-key="gov_member_{{ $m }}_position" data-live-type="text" data-live-title="Member #{{ $m }} Position" data-live-location="Management Committee">{{ $mPos }}</span>
                      </td>
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

      </div><!-- /.portal-main-col -->

      <!-- RIGHT: Sticky Recent Activity Sidebar Menu (Government Portal Style as per Sample 2) -->
      <aside class="portal-sidebar-col">
        @php
          $dbActivities = ($activities ?? collect());
          if ($dbActivities->isEmpty()) {
            $dbActivities = collect([
              (object)[
                'id' => 101,
                'title' => 'Expatriates\' Welfare Minister Visits Mahmudunnabi Chowdhury Technical School and College',
                'slug' => 'expatriates-welfare-minister-visits-mahmudunnabi-chowdhury-technical-school-and-college',
                'category' => 'Official Visit',
                'excerpt' => 'Expatriates\' Welfare and Overseas Employment Minister Ariful Haque Choudhury visited Mahmudunnabi Chowdhury Technical School and College (MCTSC) in Pahartali, Chattogram, inspecting its technical training programs and post-training job placement initiatives.',
                'body' => '<p><strong>CHATTOGRAM, Oct 3, 2026 (BSS)</strong> — Expatriates\' Welfare and Overseas Employment Minister Ariful Haque Choudhury visited Mahmudunnabi Chowdhury Technical School and College (MCTSC), managed by Impact Initiative, in Chattogram on Saturday.</p><p>During the visit, Mohammad Saif Ullah Mansur, CEO of Mahmudunnabi Chowdhury Foundation, gave the Minister a tour of the technical and vocational skills development training facilities and briefed him on trainees\' skill acquisition and post-training job placement support.</p>',
                'image' => 'assets/activities/minister_visit_mctsc.jpg',
                'created_at' => \Carbon\Carbon::parse('2026-10-29 18:00:00'),
              ],
              (object)[
                'id' => 102,
                'title' => 'Orientation & Class Inauguration of NHRDF Free Technical Training Courses (10 Oct 2026)',
                'slug' => 'nhrdf-free-technical-training-class-inauguration-orientation',
                'category' => 'NHRDF / Class Inauguration',
                'excerpt' => 'The class inauguration and orientation ceremony for NHRDF-funded free technical training courses was successfully held on 10 October 2026 at MCTSC.',
                'body' => '<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;margin-bottom:20px;box-shadow:0 4px 18px rgba(0,0,0,0.12);"><iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" src="https://www.youtube.com/embed/Xhzil1T8-RI" title="NHRDF-এর অর্থায়নে বিনামূল্যে কারিগরি প্রশিক্ষণ কোর্স এর অরিয়েন্টেশন ক্লাস" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div><p><strong>CHATTOGRAM, Oct 10, 2026</strong> — The class inauguration and orientation ceremony for the new batch of free technical training courses funded by NHRDF was successfully held at MCTSC campus in Pahartali, Chattogram.</p>',
                'image' => 'assets/activities/nhrdf_class_orientation.jpg',
                'created_at' => \Carbon\Carbon::parse('2026-10-28 15:00:00'),
              ],
              (object)[
                'id' => 103,
                'title' => 'Admission Test for NHRDF Subsidized Technical Training Batch Successfully Completed (08 Oct 2026)',
                'slug' => 'admission-test-for-nhrdf-subsidized-technical-training-batch',
                'category' => 'NHRDF / Admission Test',
                'excerpt' => 'The formal admission examination and screening test for the NHRDF subsidized technical training courses was successfully completed on 08 October 2026 at MCTSC.',
                'body' => '<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;margin-bottom:20px;box-shadow:0 4px 18px rgba(0,0,0,0.12);"><iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" src="https://www.youtube.com/embed/GLxni5-GswQ" title="Admission Test batch of NHRDF" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div><p><strong>CHATTOGRAM, Oct 8, 2026</strong> — The formal admission examination for the technical training batches under the NHRDF program was successfully conducted on 08 October 2026 at MCTSC.</p>',
                'image' => 'assets/activities/nhrdf_admission_test.jpg',
                'created_at' => \Carbon\Carbon::parse('2026-10-27 15:00:00'),
              ],
              (object)[
                'id' => 100,
                'title' => 'Admissions Open: NHRDF-Funded Free Technical and Vocational Training Courses',
                'slug' => 'admissions-open-nhrdf-funded-free-technical-training-courses',
                'category' => 'NHRDF / Admission',
                'excerpt' => 'Admissions are currently underway for NHRDF-funded free technical and vocational training courses at Mahmudunnabi Chowdhury Technical School and College (MCTSC) in Pahartali, Chattogram.',
                'body' => '<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;margin-bottom:20px;box-shadow:0 4px 18px rgba(0,0,0,0.12);"><iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" src="https://www.youtube.com/embed/xZ6aU40tGt4" title="Admission going on for Technical Training of MCTSC" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div><p><strong>CHATTOGRAM</strong> — Admissions are currently ongoing for the new batch of free technical and vocational training courses funded by the <strong>National Human Resource Development Fund (NHRDF)</strong> under the Prime Minister\'s Office, conducted at <strong>Mahmudunnabi Chowdhury Technical School and College (MCTSC)</strong> campus in Pahartali, Chattogram.</p><p>মাননীয় প্রধানমন্ত্রী কার্যালয়াধীন ন্যাশনাল হিউম্যান রিসোর্স ডেভেলপমেন্ট ফান্ড (NHRDF)-এর অর্থায়নে ও ইমপ্যাক্ট ইনিশিয়েটিভ পরিচালিত মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজে সুবিধাবঞ্চিত ও বেকার যুবক-যুবতীদের জন্য সম্পূর্ণ বিনামূল্যে কর্মসংস্থানমুখী বিভিন্ন কারিগরি প্রশিক্ষণ কোর্সে ভর্তি চলছে।</p><p>The training courses offer comprehensive theoretical knowledge and hands-on practical workshop training designed to develop high-demand market-oriented skills. Eligible youth (men and women) receive full tuition waivers, state-of-the-art laboratory access, practical consumables, and dedicated job placement assistance upon course completion.</p><p><strong>Available Trades Include:</strong></p><ul><li>Computer Applications & Office Management</li><li>Electrical Installation and Maintenance</li><li>Refrigeration and Air Conditioning (RAC)</li><li>Knit Sewing Machine Operation (KSMO) & Tailoring</li><li>Professional Driving & Auto-mechanics</li><li>Plumbing and Sanitary Occupations</li></ul><p>Interested applicants are encouraged to visit the MCTSC campus or submit their admission application online through our official website portal.</p>',
                'image' => 'assets/activities/nhrdf_admission_going_on.jpg',
                'created_at' => \Carbon\Carbon::parse('2026-10-26 12:00:00'),
              ],
            ]);
          }
        @endphp

        <div class="sidebar-widget">
          <!-- Widget Header matching sample photo 2 (Green Bar) -->
          <div class="sidebar-widget-header">
            <div class="sidebar-widget-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
              </svg>
              <span data-live-edit data-live-key="activities_sidebar_title" data-live-type="text" data-live-title="Recent Activity Sidebar Title" data-live-location="Sidebar">
                {{ $settings->get('activities_sidebar_title') ?: 'Recent Activity' }}
              </span>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
              <a href="{{ route('activities') }}" class="sidebar-widget-viewall" title="{{ $isBn ? 'সকল কার্যক্রম দেখুন' : 'View all activities' }}">
                {{ $isBn ? 'সব দেখুন' : 'View All' }}
              </a>
              @if(auth()->check() && (auth()->user()->is_admin || auth()->user()->can('settings.manage')))
                <a href="{{ route('admin.content.index', 'activities') }}" class="sidebar-widget-viewall" style="background:#bd9635; color:#ffffff;" title="এডমিন থেকে অ্যাক্টিভিটি ক্রমানুসারে সাজান" target="_blank">
                  ⚙️ সাজান
                </a>
              @endif
            </div>
          </div>

          <!-- Activity items list (Newest post at top, with thumb on left, title, excerpt, and Read more) -->
          <div class="sidebar-activity-list">
            @foreach($dbActivities as $act)
              @php
                $actImg = $act->image ? storage_asset($act->image, 'assets/activities/winter_warmer.jpg') : asset('assets/activities/winter_warmer.jpg');
              @endphp
              <div class="sidebar-activity-item open-activity-modal-btn" data-act-id="{{ $act->id }}" data-act-title="{{ $act->title }}" data-act-cat="{{ $act->category ?: 'Activity' }}" data-act-date="{{ $act->created_at ? $act->created_at->format('d F Y') : '' }}" data-act-img="{{ $actImg }}" data-act-excerpt="{{ $act->excerpt }}" data-act-url="{{ route('activities.show', $act->slug) }}" title="{{ $act->title }}">
                <template id="act-body-tmpl-{{ $act->id }}">{!! $act->body ?: nl2br(e($act->excerpt)) !!}</template>
                <img class="sidebar-activity-thumb" src="{{ $actImg }}" alt="{{ $act->title }}" loading="lazy">
                <div class="sidebar-activity-body">
                  <h4 class="sidebar-activity-title">
                    {{ $act->title }}
                  </h4>
                  @if(!empty($act->excerpt))
                    <p class="sidebar-activity-excerpt">
                      {{ $act->excerpt }}
                    </p>
                  @endif
                  <span class="sidebar-activity-readmore">
                    {{ $isBn ? 'বিস্তারিত পড়ুন →' : 'Read more →' }}
                  </span>
                </div>
              </div>
            @endforeach
          </div>

          <div class="sidebar-widget-footer">
            <a href="{{ route('activities') }}">
              {{ $isBn ? 'সকল কার্যক্রম দেখুন →' : 'View All Activities →' }}
            </a>
          </div>
        </div>
      </aside><!-- /.portal-sidebar-col -->
    </div><!-- /.portal-container -->
  </div><!-- /.portal-wrapper -->

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
<!-- Interactive Modal for Story / Activity Reading -->
<div id="activity-detail-modal" class="activity-modal-backdrop" aria-hidden="true" style="display:none;">
  <div class="activity-modal-dialog">
    <button type="button" class="activity-modal-close" id="activity-modal-close-btn" aria-label="Close modal">&times;</button>
    <div id="activity-modal-inner" style="padding: 28px 32px;"></div>
  </div>
</div>

<script>
  // Recent Activities In-Page Modal Handler
  document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('activity-detail-modal');
    const modalInner = document.getElementById('activity-modal-inner');
    const closeBtn = document.getElementById('activity-modal-close-btn');

    function openActivityModal(data) {
      if (!modal || !modalInner) return;
      modalInner.innerHTML = `
        ${data.img ? `<div style="width:100%;max-height:360px;border-radius:14px;overflow:hidden;margin-bottom:20px;background:#f1f5f9;"><img src="${data.img}" alt="${data.title}" style="width:100%;height:100%;object-fit:cover;display:block;"></div>` : ''}
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;flex-wrap:wrap;">
          <span style="background:#e0f2fe;color:#0369a1;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;text-transform:uppercase;">${data.cat || 'Activity'}</span>
          ${data.date ? `<span style="font-size:12.5px;color:#64748b;">📅 ${data.date}</span>` : ''}
        </div>
        <h2 style="font-family:var(--serif, serif);font-size:22px;font-weight:800;color:#0f172a;line-height:1.35;margin:0 0 14px 0;">${data.title}</h2>
        ${data.excerpt ? `<div style="font-size:14.5px;font-weight:600;color:#334155;line-height:1.6;margin-bottom:16px;padding-bottom:14px;border-bottom:1px dashed #cbd5e1;text-align:justify;text-justify:inter-word;">${data.excerpt}</div>` : ''}
        <div class="activity-modal-body-text">${data.body || data.excerpt || ''}</div>
        <div style="margin-top:24px;padding-top:16px;border-top:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
          <a href="${data.url}" style="font-size:13px;font-weight:700;color:#16a34a;text-decoration:none;">{{ $isBn ? 'আলাদা পেইজে দেখুন →' : 'Open in full page →' }}</a>
          <button type="button" class="btn light" id="activity-modal-ok-btn" style="padding:6px 16px;font-size:13px;cursor:pointer;border:1px solid #cbd5e1;background:#f8fafc;border-radius:6px;">{{ $isBn ? 'বন্ধ করুন' : 'Close' }}</button>
        </div>
      `;

      modal.style.display = 'flex';
      setTimeout(() => modal.classList.add('is-open'), 10);
      document.body.style.overflow = 'hidden';

      const okBtn = document.getElementById('activity-modal-ok-btn');
      if (okBtn) okBtn.addEventListener('click', closeActivityModal);
    }

    function closeActivityModal() {
      if (!modal) return;
      modal.classList.remove('is-open');
      setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
      }, 250);
    }

    document.querySelectorAll('.open-activity-modal-btn').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        const tmpl = document.getElementById('act-body-tmpl-' + this.dataset.actId);
        const bodyContent = tmpl ? tmpl.innerHTML : (this.dataset.actExcerpt || '');
        const data = {
          id: this.dataset.actId,
          title: this.dataset.actTitle,
          cat: this.dataset.actCat,
          date: this.dataset.actDate,
          img: this.dataset.actImg,
          excerpt: this.dataset.actExcerpt,
          body: bodyContent,
          url: this.dataset.actUrl,
        };
        openActivityModal(data);
      });
    });

    if (closeBtn) closeBtn.addEventListener('click', closeActivityModal);
    if (modal) {
      modal.addEventListener('click', function (e) {
        if (e.target === modal) closeActivityModal();
      });
    }
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modal && modal.classList.contains('is-open')) {
        closeActivityModal();
      }
    });

    // About Modal (Floating Full Institute Background)
    const aboutModal = document.getElementById('aboutDetailModal');
    const openAboutBtn = document.getElementById('openAboutModalBtn');
    const closeAboutBtn = document.getElementById('closeAboutModalBtn');
    const closeAboutFootBtn = document.getElementById('closeAboutModalFootBtn');

    function openAboutDialog() {
      if (!aboutModal) return;
      aboutModal.classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeAboutDialog() {
      if (!aboutModal) return;
      aboutModal.classList.remove('open');
      document.body.style.overflow = '';
    }

    if (openAboutBtn) openAboutBtn.addEventListener('click', openAboutDialog);
    if (closeAboutBtn) closeAboutBtn.addEventListener('click', closeAboutDialog);
    if (closeAboutFootBtn) closeAboutFootBtn.addEventListener('click', closeAboutDialog);
    if (aboutModal) {
      aboutModal.addEventListener('click', function (e) {
        if (e.target === aboutModal) closeAboutDialog();
      });
    }
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && aboutModal && aboutModal.classList.contains('open')) {
        closeAboutDialog();
      }
    });
  });
</script>
@include('partials.live_editor')
</body>
</html>
