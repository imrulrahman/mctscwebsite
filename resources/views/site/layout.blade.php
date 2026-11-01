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
      align-items: flex-start;
      justify-content: center;
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
    }
    .foundation-logo-link {
      display: inline-flex;
      align-items: center;
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
    @media (max-width: 1024px) {
      .brand-banner-container {
        flex-wrap: wrap;
        gap: 12px;
      }
      .brand-logo-block {
        width: 100%;
        justify-content: space-between;
      }
      .brand-right-features {
        width: 100%;
        justify-content: space-between;
        padding-top: 8px;
        border-top: 1px dashed #e2e8f0;
      }
      .brand-name-single {
        white-space: normal !important;
        font-size: 16.5px !important;
      }
      .menu-toggle {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
      }
      .main-navbar-strip {
        position: relative;
      }
      .nav-strip-container {
        padding: 0;
      }
      .main-navbar-strip .main-nav {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #064e3b;
        padding: 16px 20px;
        box-shadow: 0 14px 28px rgba(0,0,0,0.25);
        flex-direction: column;
        align-items: stretch;
        gap: 0 !important;
        z-index: 1000;
        border-top: 1px solid rgba(255,255,255,0.1);
      }
      .main-navbar-strip .main-nav.open {
        display: flex !important;
      }
      .main-navbar-strip .main-nav a {
        padding: 12px 6px !important;
        border-bottom: 1px solid rgba(255,255,255,0.1);
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
        background: rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        margin: 4px 0 8px 0;
      }
      .nav-item-dropdown .dropdown-menu a {
        padding: 10px 12px !important;
        font-size: 13px !important;
        color: #e2e8f0 !important;
      }
    }
    @media (max-width: 680px) {
      .brand-name-single {
        font-size: 14.5px !important;
        line-height: 1.3 !important;
      }
      .brand-emblem-img {
        width: 44px;
        height: 44px;
      }
      .header-foundation {
        border-right: none;
        padding-right: 0;
      }
      .header-quick-call {
        display: none;
      }
      .badge-accred {
        font-size: 10px;
        padding: 2px 6px;
      }
      .badge-accred .badge-chips code {
        font-size: 9.5px;
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
  <!-- Tier 1: White Brand Banner (Institue Logo + Name in one line + Accreditation Badges + Implemented by Impact Initiative + Helpline & Admissions) -->
  <div class="brand-banner">
    <div class="container brand-banner-container">
      <div class="brand-logo-block">
        <a class="brand" href="{{ route('home') }}">
          <img src="{{ $siteLogo }}" alt="{{ $instName }} logo" class="brand-emblem-img" data-live-edit data-live-key="site_logo" data-live-type="image" data-live-title="অফিসিয়াল মনোগ্রাম / লোগো" data-live-location="হেডার (Header)">
          <div class="brand-text">
            <h1 class="brand-name-single">
              <span data-live-edit data-live-key="{{ $isBn ? 'institution_name_bn' : 'institution_name' }}" data-live-type="text" data-live-title="প্রতিষ্ঠানের নাম" data-live-location="হেডার (Header)">
                {{ $isBn ? $getSetting('institution_name_bn', 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল অ্যান্ড কলেজ') : $getSetting('institution_name', 'Mahmudunnabi Chowdhury Technical School and College') }}
              </span>
            </h1>
            <div class="brand-badges-bar">
              <span class="badge-accred badge-bteb" title="বাংলাদেশ কারিগরি শিক্ষা বোর্ড অনুমোদিত - কোড: ৭০১০২">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="badge-icon"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg>
                <span class="badge-title">BTEB Institute Code:</span>
                <span class="badge-chips"><code>70102</code></span>
              </span>
              <span class="badge-accred badge-nsda" title="জাতীয় দক্ষতা উন্নয়ন কর্তৃপক্ষ অনুমোদিত - নিবন্ধন: STP-CHA-000135">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="badge-icon"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                <span class="badge-title">NSDA Registration:</span>
                <span class="badge-chips"><code>STP-CHA-000135</code></span>
              </span>
            </div>
          </div>
        </a>
      </div>

      <!-- Right Side: Implemented by Impact Initiative + Live Admission & Helpline + Language Switcher -->
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
          <a href="tel:{{ preg_replace('/[^0-9+]/', '', $getSetting('contact_phone', '01880919275')) }}" class="header-phone-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;color:#e11d48;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            {{ $getSetting('contact_phone', '01880919275') }}
          </a>
        </div>

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

        <button class="menu-toggle" aria-label="Open navigation" aria-expanded="false">☰</button>
      </div>
    </div>
  </div>

  <!-- Tier 2: Dark Forest Green Institutional Navigation Bar (Badarshah / BTEB Portal Style) -->
  <div class="main-navbar-strip">
    <div class="container nav-strip-container">
      <nav class="main-nav" aria-label="Primary navigation">
        <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
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
          <a class="nav-link-parent {{ request()->routeIs('courses') || request()->routeIs('apply') ? 'active' : '' }}" href="{{ route('courses') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
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
        <a class="{{ request()->routeIs('notices') ? 'active' : '' }}" href="{{ route('notices') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          {{ $isBn ? 'নোটিশ' : 'Notices' }}
        </a>
        <a class="{{ request()->routeIs('activities*') ? 'active' : '' }}" href="{{ route('activities') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          {{ $isBn ? 'কার্যক্রম' : 'Activities' }}
        </a>
        <a class="{{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          {{ $isBn ? 'গ্যালারি' : 'Gallery' }}
        </a>
        <a class="{{ request()->routeIs('career*') ? 'active' : '' }}" href="{{ route('career') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-ico"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          {{ $isBn ? 'ক্যারিয়ার' : 'Career' }}
        </a>
      </nav>
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
          <strong data-live-edit data-live-key="address" data-live-type="textarea" data-live-title="প্রতিষ্ঠানের পূর্ণাঙ্গ ঠিকানা" data-live-location="ফুটার - ঠিকানা">{{ $getSetting('address', '273/A/520, Chodu Chowdhury Road, Sagorika, Pahartali, Chattogram') }}</strong>
        </div>
      </div>

      <div class="contact-row-sub">
        <div class="contact-sub-item">
          <svg class="c-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          </svg>
          <div>
            <small>{{ $isBn ? 'ফোন নম্বর' : 'Phone number' }}</small>
            <strong data-live-edit data-live-key="phone" data-live-type="text" data-live-title="অফিসিয়াল হেল্পলাইন ফোন নম্বর" data-live-location="ফুটার - হেল্পলাইন">{{ $getSetting('phone', '+8801880919275') }}</strong>
          </div>
        </div>

        <div class="contact-sub-item">
          <svg class="c-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
          <div>
            <small>{{ $isBn ? 'ইমেইল এড্রেস' : 'Email address' }}</small>
            <strong data-live-edit data-live-key="email" data-live-type="text" data-live-title="অফিসিয়াল ইমেইল এড্রেস" data-live-location="ফুটার - ইমেইল">{{ $getSetting('email', 'mnctsc@gmail.com') }}</strong>
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
@include('partials.live_editor')
</body>
</html>