@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
  $getSetting = function($key, $default = null) use ($settings) {
      if (is_array($settings)) {
          return $settings[$key] ?? $default;
      }
      return $settings?->get($key, $default) ?? $default;
  };
  $primaryColor = $getSetting('primary_color', '#0f5132');
  $primaryHover = $getSetting('primary_hover', '#093722');
  $secondaryColor = $getSetting('secondary_color', '#c59b27');
  $secondaryHover = $getSetting('secondary_hover', '#a37d18');

  $toRgba = function($hex, $alpha = 1) {
      $hex = str_replace('#', '', $hex);
      if (strlen($hex) == 3) {
          $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
          $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
          $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
      } elseif (strlen($hex) == 6) {
          $r = hexdec(substr($hex, 0, 2));
          $g = hexdec(substr($hex, 2, 2));
          $b = hexdec(substr($hex, 4, 2));
      } else {
          return "rgba(15, 81, 50, {$alpha})";
      }
      return "rgba({$r}, {$g}, {$b}, {$alpha})";
  };

  $siteLogo = storage_asset($getSetting('site_logo'), 'assets/MC Logo.png');
  $instName = $getSetting('institution_name', 'Mahmudunnabi Chowdhury Technical School and College');
@endphp
<!doctype html>
<html lang="{{ $isBn ? 'bn' : 'en' }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') · {{ $instName }}</title>
  <link rel="stylesheet" href="{{ asset('css/site.css') }}">
  <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
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

    /* Header responsive & brand wrapping fix */
    .site-header {
      min-height: 78px !important;
      height: auto !important;
      padding: 6px 0 !important;
      background: #ffffff !important;
      border-bottom: 1px solid #e2ebe6 !important;
      position: -webkit-sticky !important;
      position: sticky !important;
      top: 0 !important;
      z-index: 1000 !important;
      box-shadow: 0 2px 10px rgba(0,0,0,0.06) !important;
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
      display: flex !important;
      align-items: center !important;
      text-decoration: none !important;
      white-space: nowrap !important;
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

    /* Top Page Banner */
    .page-hero-banner {
      background: linear-gradient(135deg, {{ $primaryHover }} 0%, {{ $primaryColor }} 100%);
      color: #ffffff;
      padding: 16px 0 14px 0 !important;
      position: relative;
      border-bottom: 3px solid {{ $secondaryColor }};
      box-shadow: 0 3px 12px rgba(0,0,0,0.06);
    }
    .page-hero-banner h1 {
      margin: 0 0 4px 0 !important;
      font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
      font-size: 24px !important;
      font-weight: 700;
      letter-spacing: -0.2px;
      line-height: 1.25 !important;
    }
    .page-hero-banner p {
      margin: 2px 0 0 0 !important;
      font-size: 13.5px !important;
      color: rgba(255, 255, 255, 0.9);
      max-width: 820px;
      line-height: 1.45 !important;
    }
    .page-breadcrumbs {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 12px !important;
      color: rgba(255, 255, 255, 0.75);
      margin-top: 8px !important;
    }
    .page-breadcrumbs a {
      color: #ffffff;
      text-decoration: none;
      font-weight: 600;
    }
    .page-breadcrumbs a:hover {
      text-decoration: underline;
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
      transition: color 0.2s ease;
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
    }
    .toggle-slider-knob {
      width: 21px;
      height: 21px;
      border-radius: 50%;
      background: #ffffff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.32);
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
    }
    .toggle-slider-track.is-en .toggle-slider-knob {
      transform: translateX(0);
    }
    .toggle-slider-track.is-bn .toggle-slider-knob {
      transform: translateX(25px);
    }

    /* Standard, Compact & Elegant Footer */
    .site-footer {
      background: {{ $primaryHover }} !important;
      color: #ffffff !important;
      padding-top: 24px !important;
      margin-top: 50px;
      position: relative;
    }
    .site-footer .footer-main {
      display: grid !important;
      grid-template-columns: 1.15fr 1.7fr 0.65fr !important;
      gap: 26px !important;
      padding: 0 0 16px !important;
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
    .site-footer .contact-row-top,
    .site-footer .contact-sub-item {
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
    .social-text-link {
      color: #ffffff;
      font-weight: 700;
      font-size: 13px;
      text-decoration: none;
      background: rgba(255, 255, 255, 0.12);
      padding: 4px 10px;
      border-radius: 4px;
      transition: background 0.15s ease;
    }
    .social-text-link:hover {
      background: rgba(255, 255, 255, 0.25);
    }
    .site-footer .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.18);
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      font-size: 12px;
      color: rgba(255, 255, 255, 0.75);
    }

    @media (max-width: 900px) {
      .site-footer .footer-main {
        grid-template-columns: 1fr !important;
        gap: 18px !important;
      }
    }
    @media (max-width: 680px) {
      .site-footer .footer-bottom {
        flex-direction: column;
        gap: 6px;
      }
    }

    /* Lightbox Modal with Prev/Next and Close */
    .lightbox {
      display: none;
      position: fixed;
      z-index: 99999;
      inset: 0;
      background: rgba(4, 20, 15, 0.94) !important;
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      align-items: center;
      justify-content: center;
      user-select: none;
    }
    .lightbox.open {
      display: flex !important;
    }
    .lightbox-stage {
      max-width: 90vw;
      max-height: 88vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 100000;
    }
    .lightbox-img {
      max-width: min(1000px, 86vw);
      max-height: 78vh;
      object-fit: contain;
      border-radius: 8px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
      border: 2px solid rgba(255, 255, 255, 0.15);
      background: #000;
      transition: opacity 0.2s ease;
    }
    .lightbox-caption {
      margin-top: 12px;
      color: #ffffff;
      font-size: 15px;
      font-weight: 600;
      text-align: center;
      text-shadow: 0 1px 4px rgba(0,0,0,0.8);
      font-family: var(--serif);
    }
    .lightbox-counter {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.7);
      margin-top: 4px;
    }
    .lightbox-btn {
      position: fixed;
      background: rgba(255, 255, 255, 0.18);
      color: #ffffff;
      border: 1.5px solid rgba(255, 255, 255, 0.4);
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      z-index: 100001;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    }
    .lightbox-btn:hover {
      background: {{ $primaryColor }} !important;
      border-color: #ffffff;
      transform: scale(1.1);
    }
    .lightbox-close {
      top: 24px;
      right: 24px;
      width: 44px;
      height: 44px;
      font-size: 22px;
      line-height: 1;
    }
    .lightbox-prev {
      left: 24px;
      top: 50%;
      transform: translateY(-50%);
      width: 50px;
      height: 50px;
      font-size: 24px;
    }
    .lightbox-prev:hover {
      transform: translateY(-50%) scale(1.1);
    }
    .lightbox-next {
      right: 24px;
      top: 50%;
      transform: translateY(-50%);
      width: 50px;
      height: 50px;
      font-size: 24px;
    }
    .lightbox-next:hover {
      transform: translateY(-50%) scale(1.1);
    }
    @media (max-width: 680px) {
      .lightbox-prev {
        left: 8px;
        width: 40px;
        height: 40px;
        font-size: 20px;
      }
      .lightbox-next {
        right: 8px;
        width: 40px;
        height: 40px;
        font-size: 20px;
      }
      .lightbox-close {
        top: 14px;
        right: 14px;
        width: 38px;
        height: 38px;
        font-size: 18px;
      }
    }
  </style>
  @yield('styles')
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="{{ route('home') }}">
      <img src="{{ $siteLogo }}" alt="{{ $instName }} logo">
      <div class="brand-text">
        <span class="brand-line-1">{{ $isBn ? 'মাহমুদুন্নবী চৌধুরী' : 'Mahmudunnabi Chowdhury' }}</span>
        <span class="brand-line-2">{{ $isBn ? 'টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Technical School and College' }}</span>
      </div>
    </a>
    <div class="header-right-group">
      <button class="menu-toggle" aria-label="Open navigation" aria-expanded="false">☰</button>
      <nav class="main-nav" aria-label="Primary navigation">
        <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ $isBn ? 'হোম' : 'Home' }}</a>
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
          <a class="nav-link-parent {{ request()->routeIs('courses') || request()->routeIs('apply') ? 'active' : '' }}" href="{{ route('courses') }}">
            {{ $isBn ? 'কোর্সসমূহ' : 'Courses' }} <span class="dropdown-caret">▾</span>
          </a>
          <div class="dropdown-menu">
            <a class="{{ request()->routeIs('courses') ? 'active' : '' }}" href="{{ route('courses') }}">
              {{ $isBn ? 'কোর্সসমূহ' : 'Courses' }}
            </a>
            <a class="{{ request()->routeIs('apply') ? 'active' : '' }}" href="{{ route('apply') }}">
              {{ $isBn ? 'ভর্তি আবেদন' : 'Apply for Admission' }}
            </a>
          </div>
        </div>
        <a class="{{ request()->routeIs('notices') ? 'active' : '' }}" href="{{ route('notices') }}">{{ $isBn ? 'নোটিশ' : 'Notices' }}</a>
        <a class="{{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">{{ $isBn ? 'গ্যালারি' : 'Gallery' }}</a>
        <a class="{{ request()->routeIs('career*') ? 'active' : '' }}" href="{{ route('career') }}">{{ $isBn ? 'ক্যারিয়ার' : 'Career' }}</a>
      </nav>

      <!-- SKEUOMORPHIC FLAG LANGUAGE SWITCHER (EN [ Flag Knob ] বাং) -->
      <div class="lang-switch-box" title="{{ $isBn ? 'Switch to English' : 'বাংলা ভাষায় দেখুন' }}">
        <a href="{{ route('lang.switch', 'en') }}" class="lang-code {{ !$isBn ? 'active' : 'inactive' }}" title="English Version">EN</a>
        <a href="{{ route('lang.switch', $isBn ? 'en' : 'bn') }}" class="toggle-slider-track {{ $isBn ? 'is-bn' : 'is-en' }}" aria-label="{{ $isBn ? 'Switch to English' : 'Switch to Bangla' }}" title="{{ $isBn ? 'Switch to English' : 'Switch to Bangla' }}">
          <div class="toggle-slider-knob">
            @if($isBn)
              <!-- Bangladesh Flag Knob (Round) -->
              <svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <clipPath id="bd-knob-clip-layout"><circle cx="30" cy="30" r="30"/></clipPath>
                <g clip-path="url(#bd-knob-clip-layout)">
                  <rect width="60" height="60" fill="#006a4e"/>
                  <circle cx="28" cy="30" r="14" fill="#f42a41"/>
                </g>
              </svg>
            @else
              <!-- UK Flag Knob (Round) -->
              <svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <clipPath id="uk-knob-clip-layout"><circle cx="30" cy="30" r="30"/></clipPath>
                <g clip-path="url(#uk-knob-clip-layout)">
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

@hasSection('header')
  @yield('header')
@else
  <section class="page-hero-banner">
    <div class="container">
      <h1>@yield('page_title', 'Page')</h1>
      @hasSection('page_subtitle')
        <p>@yield('page_subtitle')</p>
      @endif
      <div class="page-breadcrumbs">
        <a href="{{ route('home') }}">{{ $isBn ? 'হোম' : 'Home' }}</a>
        <span>›</span>
        <span>@yield('page_title', 'Page')</span>
      </div>
    </div>
  </section>
@endif

<main>
  @yield('content')
</main>

<footer class="site-footer">
  <div class="container footer-main">
    <div class="footer-brand">
      <img src="{{ $siteLogo }}" alt="{{ $instName }} crest">
      <strong>{{ $instName }}</strong>
    </div>

    <div class="footer-contact-block">
      <div class="contact-row-top">
        <svg class="c-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
          <circle cx="12" cy="10" r="3"></circle>
        </svg>
        <div>
          <small>{{ $isBn ? 'আমাদের ঠিকানা' : 'Our address' }}</small>
          <strong>{{ $getSetting('address', '273/A/520, Chodu Chowdhury Road, Sagorika, Pahartali, Chattogram') }}</strong>
        </div>
      </div>

      <div class="contact-row-sub">
        <div class="contact-sub-item">
          <svg class="c-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          </svg>
          <div>
            <small>{{ $isBn ? 'ফোন নম্বর' : 'Phone number' }}</small>
            <strong>{{ $getSetting('phone', '+8801880919275') }}</strong>
          </div>
        </div>

        <div class="contact-sub-item">
          <svg class="c-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
          <div>
            <small>{{ $isBn ? 'ইমেইল এড্রেস' : 'Email address' }}</small>
            <strong>{{ $getSetting('email', 'mnctsc@gmail.com') }}</strong>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-social-col">
      <small>{{ $isBn ? 'সোশ্যাল মিডিয়ায় আমরা' : 'Follow Us' }}</small>
      <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-top:6px;">
        <a class="social-text-link" href="{{ $getSetting('social_facebook', 'https://www.facebook.com/mnctsc') }}" target="_blank" rel="noopener">
          Facebook
        </a>
        @if(!empty($getSetting('social_youtube')))
          <a class="social-text-link" href="{{ $getSetting('social_youtube') }}" target="_blank" rel="noopener">
            YouTube
          </a>
        @endif
        @if(!empty($getSetting('social_linkedin')))
          <a class="social-text-link" href="{{ $getSetting('social_linkedin') }}" target="_blank" rel="noopener">
            LinkedIn
          </a>
        @endif
      </div>
    </div>
  </div>
  <div class="container footer-bottom">
    <span>© {{ date('Y') }} {{ $instName }}. {{ $isBn ? 'সর্বস্বত্ব সংরক্ষিত।' : 'All rights reserved.' }}</span>
    <span>{{ $isBn ? 'বাস্তবমুখী স্কিলস · উন্নত কর্মসংস্থান' : $getSetting('tagline', 'Practical Skills · Better Opportunities') }}</span>
  </div>
</footer>

<div class="lightbox" id="siteLightbox" aria-hidden="true" role="dialog" aria-modal="true">
  <button class="lightbox-btn lightbox-close" type="button" aria-label="Close" title="Close (Esc)">✕</button>
  <button class="lightbox-btn lightbox-prev" type="button" aria-label="Previous" title="Previous (Left Arrow)">❮</button>
  <div class="lightbox-stage">
    <img id="lightboxImg" class="lightbox-img" src="" alt="">
    <div id="lightboxCaption" class="lightbox-caption"></div>
    <div id="lightboxCounter" class="lightbox-counter"></div>
  </div>
  <button class="lightbox-btn lightbox-next" type="button" aria-label="Next" title="Next (Right Arrow)">❯</button>
</div>

<script src="{{ asset('js/site.js') }}"></script>
@yield('scripts')
</body>
</html>