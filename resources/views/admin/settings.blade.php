@extends('admin.layout')
@section('title', 'Site & Institution Settings')
@section('crumb', 'Site Settings')

@section('content')
<style>
  /* -------------------------------------------------------------------------
     SETTINGS ACCORDION & GLOBAL LAYOUT
     ------------------------------------------------------------------------- */
  .page-head-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }
  .head-toggle-btn {
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
    padding: 7px 13px;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
  }
  .head-toggle-btn:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #0f5132;
  }

  .settings-accordion {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 24px;
  }

  .accordion-item {
    background: #ffffff;
    border: 1px solid #d8e4dc;
    border-radius: 9px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(15, 81, 50, 0.03);
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .accordion-item.is-open {
    border-color: #7cb5a9;
    box-shadow: 0 4px 14px rgba(15, 81, 50, 0.07);
  }

  .accordion-header {
    padding: 14px 18px;
    background: #ffffff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    user-select: none;
    transition: background 0.15s ease;
  }

  .accordion-header:hover {
    background: #f8faf9;
  }

  .accordion-item.is-open .accordion-header {
    background: #f0f7f3;
    border-bottom: 1px solid #d8e4dc;
  }

  .accordion-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 0;
  }

  .accordion-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #eef7f2;
    color: var(--green, #0f5132);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
  }

  .accordion-title-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
  }

  .accordion-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--green, #0f5132);
    margin: 0;
  }

  .accordion-subtitle {
    font-size: 12px;
    color: #64748b;
    margin: 0;
    line-height: 1.3;
  }

  .accordion-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
  }

  .accordion-chevron {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1), background 0.2s, color 0.2s;
    font-size: 11px;
    font-weight: bold;
  }

  .accordion-item.is-open .accordion-chevron {
    transform: rotate(180deg);
    background: #0f5132;
    color: #ffffff;
  }

  .accordion-body {
    display: none;
    padding: 22px 20px;
    background: #ffffff;
  }

  .accordion-item.is-open .accordion-body {
    display: block;
  }

  /* -------------------------------------------------------------------------
     COLOR PALETTE & SHADE SWATCHES
     ------------------------------------------------------------------------- */
  .color-control-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 14px;
    margin-top: 14px;
  }

  .color-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
  }

  .color-box label {
    font-size: 12.5px;
    font-weight: 700;
    color: #1e293b;
    display: block;
    margin-bottom: 6px;
  }

  .color-input-pair {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .color-input-pair input[type="color"] {
    width: 44px;
    height: 38px;
    border: none;
    cursor: pointer;
    border-radius: 6px;
    padding: 0;
    background: none;
  }

  .color-input-pair input[type="text"] {
    flex: 1;
    padding: 7px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-family: monospace;
    font-size: 13.5px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .swatch-target-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 10px 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 12px;
  }

  .swatch-target-options {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }

  .swatch-target-label {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    gap: 6px !important;
    font-size: 12.5px !important;
    font-weight: 600 !important;
    color: #334155 !important;
    cursor: pointer !important;
    margin: 0 !important;
  }

  .swatch-target-label input[type="radio"] {
    width: 16px !important;
    height: 16px !important;
    accent-color: #0f5132 !important;
    cursor: pointer !important;
  }

  .swatch-palette-table {
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 14px;
  }

  .swatch-row {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .swatch-row-name {
    width: 105px;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    flex-shrink: 0;
  }

  .swatch-row-cells {
    display: flex;
    align-items: center;
    gap: 5px;
    flex: 1;
    overflow-x: auto;
  }

  .swatch-cell {
    flex: 1;
    min-width: 24px;
    height: 32px;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
    border: 1px solid rgba(0, 0, 0, 0.08);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
  }

  .swatch-cell:hover {
    transform: scale(1.18);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    z-index: 5;
    border-color: #ffffff;
  }

  .swatch-cell.is-active-cell::after {
    content: "✓";
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 13px;
    font-weight: 800;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.7);
  }

  /* -------------------------------------------------------------------------
     AFM (ADMISSION FORM MANDATORY FIELDS) - RESETS & SCOPED STYLES
     ------------------------------------------------------------------------- */
  .afm-container {
    background: #ffffff;
    border: 1px solid #d8e4dc;
    border-radius: 10px;
    padding: 16px 18px 20px;
    box-shadow: 0 1px 4px rgba(15, 81, 50, 0.04);
  }

  .form-panel .afm-container label.afm-row {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: space-between !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 9px 12px !important;
    background: #ffffff !important;
    border: none !important;
    border-bottom: 1px solid #f1f5f9 !important;
    cursor: pointer !important;
    transition: all 0.15s ease !important;
    box-sizing: border-box !important;
    font-weight: normal !important;
    user-select: none !important;
  }

  .form-panel .afm-container label.afm-row:last-child {
    border-bottom: none !important;
  }

  .form-panel .afm-container label.afm-row:hover {
    background: #f8fafc !important;
  }

  .form-panel .afm-container label.afm-row.is-active {
    background: #f0fdf4 !important;
  }

  .form-panel .afm-container label.afm-row span {
    color: inherit !important;
  }

  .form-panel .afm-container input[type="checkbox"].admission-field-chk {
    display: inline-block !important;
    width: 18px !important;
    min-width: 18px !important;
    max-width: 18px !important;
    height: 18px !important;
    margin: 0 10px 0 0 !important;
    padding: 0 !important;
    border: 1.5px solid #94a3b8 !important;
    border-radius: 4px !important;
    accent-color: var(--green, #0f5132) !important;
    cursor: pointer !important;
    flex-shrink: 0 !important;
    box-shadow: none !important;
  }

  .afm-top-panel {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    background: #f8faf9;
    border: 1px solid #e2ebe6;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 16px;
  }

  .afm-lock-notice {
    font-size: 12px;
    color: #0f5132;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .afm-counter-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    background: #e7f3ed;
    color: #0b5948;
    font-size: 12px;
    font-weight: 700;
  }

  .afm-actions-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }

  .afm-search-input {
    width: 220px !important;
    padding: 6px 12px !important;
    font-size: 12.5px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    background: #ffffff !important;
    outline: none !important;
    box-sizing: border-box !important;
  }
  .afm-search-input:focus {
    border-color: #0f5132 !important;
    box-shadow: 0 0 0 2px rgba(15, 81, 50, 0.15) !important;
  }

  .afm-btn {
    padding: 6px 11px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.15s ease;
    border: 1px solid transparent;
  }

  .afm-btn-select {
    background: #eef7f2;
    color: #0f5132;
    border-color: #a7d3c0;
  }
  .afm-btn-select:hover {
    background: #dcf2e6;
  }

  .afm-btn-default {
    background: #f1f5f9;
    color: #334155;
    border-color: #cbd5e1;
  }
  .afm-btn-default:hover {
    background: #e2e8f0;
  }

  .afm-btn-clear {
    background: #ffffff;
    color: #dc2626;
    border-color: #fca5a5;
  }
  .afm-btn-clear:hover {
    background: #fee2e2;
  }

  .afm-grid-cols {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    align-items: start;
  }

  .afm-col {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  @media (max-width: 1200px) {
    .afm-grid-cols {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 860px) {
    .afm-grid-cols {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .afm-col {
      display: contents;
    }
    .afm-card-1 { order: 1; }
    .afm-card-2 { order: 2; }
    .afm-card-3 { order: 3; }
    .afm-card-4 { order: 4; }
    .afm-card-5 { order: 5; }
    .afm-card-6 { order: 6; }
    .afm-card-7 { order: 7; }
    .afm-search-input {
      width: 100% !important;
    }
  }

  .afm-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    display: flex;
    flex-direction: column;
    height: auto !important;
  }

  .afm-card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 9px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .afm-card-title {
    font-size: 13px;
    font-weight: 700;
    color: #0f5132;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .afm-cat-btn {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569;
    border-radius: 4px;
    padding: 2px 7px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.12s ease;
  }
  .afm-cat-btn:hover {
    background: #f1f5f9;
    color: #0f5132;
    border-color: #94a3b8;
  }

  .afm-card-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 12px;
    background: #e2e8f0;
    color: #475569;
    display: inline-block;
    min-width: 28px;
    text-align: center;
  }

  .afm-card-body {
    padding: 0;
    display: flex;
    flex-direction: column;
  }

  .afm-row-left {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    flex: 1 1 auto !important;
    min-width: 0 !important;
  }

  .afm-row-text {
    display: flex !important;
    flex-direction: column !important;
    gap: 1px !important;
    line-height: 1.22 !important;
    min-width: 0 !important;
  }

  .afm-text-primary {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #1e293b !important;
    white-space: normal !important;
  }

  .afm-text-sub {
    font-size: 11px !important;
    font-weight: 400 !important;
    color: #64748b !important;
    white-space: normal !important;
  }

  .afm-row-right {
    flex-shrink: 0 !important;
    margin-left: 8px !important;
  }

  .afm-badge-req {
    display: inline-block !important;
    font-size: 10.5px !important;
    font-weight: 700 !important;
    padding: 2px 7px !important;
    border-radius: 9999px !important;
    background: #fef2f2 !important;
    color: #b91c1c !important;
    border: 1px solid #fecaca !important;
    white-space: nowrap !important;
    letter-spacing: 0.2px;
  }

  .afm-badge-opt {
    display: inline-block !important;
    font-size: 10.5px !important;
    font-weight: 500 !important;
    padding: 2px 7px !important;
    border-radius: 9999px !important;
    background: #f1f5f9 !important;
    color: #64748b !important;
    border: 1px solid #e2e8f0 !important;
    white-space: nowrap !important;
  }
</style>

<div class="page-head">
  <div>
    <h1>Site & Institution Settings</h1>
    <p>Manage branding, visual theme colors, contact details, hero banners, campus facilities, and admission requirements.</p>
  </div>
  <div class="page-head-actions">
    <button type="button" class="head-toggle-btn" onclick="expandAllAccordions(true)" title="Expand all sections">
      <span>📂</span> Expand All
    </button>
    <button type="button" class="head-toggle-btn" onclick="expandAllAccordions(false)" title="Collapse all sections">
      <span>📁</span> Collapse All
    </button>
  </div>
</div>

@if ($errors->any())
  <div style="background: #fef2f2; border: 1px solid #f87171; color: #991b1b; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(220,38,38,0.1);">
    <strong style="display: block; font-size: 14px; margin-bottom: 6px;">⚠️ Settings could not be saved due to validation errors:</strong>
    <ul style="margin: 0; padding-left: 20px; font-size: 13px;">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<section class="panel form-panel" style="max-width: 1120px;">
  <form method="post" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PUT')

    <div class="settings-accordion">

      <!-- =====================================================================
           SECTION 1: IDENTITY & LOGO
           ===================================================================== -->
      <div class="accordion-item" id="sec-identity">
        <div class="accordion-header" onclick="toggleAccordion(this)">
          <div class="accordion-title-wrap">
            <span class="accordion-icon">🏛️</span>
            <div class="accordion-title-text">
              <h2 class="accordion-title">1. Institution Identity & Official Logo</h2>
              <p class="accordion-subtitle">Main institution name, tagline, and official header/footer brand logo.</p>
            </div>
          </div>
          <div class="accordion-meta">
            <span class="accordion-chevron">▼</span>
          </div>
        </div>
        <div class="accordion-body">
          <div class="field-grid">
            <label>
              Institution Name
              <input name="institution_name" value="{{ old('institution_name', $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College')) }}" placeholder="Enter institution full name">
            </label>
            <label>
              Tagline / Institutional Slogan
              <input name="tagline" value="{{ old('tagline', $settings->get('tagline', 'Practical Skills · Better Opportunities')) }}" placeholder="e.g. Practical Skills · Better Opportunities">
            </label>
          </div>

          <div style="background:#f8faf9;border:1px solid #e2ebe6;padding:14px;border-radius:6px;margin-top:14px;">
            <label>
              <strong>Official Institution Logo</strong> — (Formats: PNG, JPG, SVG, WEBP; max 5 MB)
              <small style="color:#0369a1;background:#e0f2fe;padding:3px 8px;border-radius:4px;font-size:11.5px;font-weight:600;display:inline-block;margin:4px 0 6px 0;">
                📐 Recommended Resolution: 512 × 512 px (Square PNG/SVG) or 600 × 200 px (Horizontal PNG). Transparent background recommended.
              </small>
              <input type="file" name="site_logo" accept="image/*">
              @php
                $currentLogo = storage_asset($settings->get('site_logo'), 'assets/MC Logo.png');
              @endphp
              <div style="margin-top: 10px; display: flex; align-items: center; gap: 14px;">
                <img src="{{ $currentLogo }}" alt="Current Logo" style="height: 60px; max-width: 150px; object-fit: contain; background: #fff; padding: 4px; border: 1px solid #ccc; border-radius: 4px;">
                <span style="font-size: 12px; color: #555;">Current Active Logo Preview</span>
              </div>
            </label>
          </div>
        </div>
      </div>

      <!-- =====================================================================
           SECTION 2: SITE THEME & COLOR CUSTOMIZATION
           ===================================================================== -->
      <div class="accordion-item" id="theme-section">
        <div class="accordion-header" onclick="toggleAccordion(this)">
          <div class="accordion-title-wrap">
            <span class="accordion-icon">🎨</span>
            <div class="accordion-title-text">
              <h2 class="accordion-title">2. Site Theme & Color Customization</h2>
              <p class="accordion-subtitle">Clickable shade swatches, 1-click preset themes, and custom hex codes with real-time preview.</p>
            </div>
          </div>
          <div class="accordion-meta">
            <div style="display:flex;align-items:center;gap:6px;background:#f8fafc;padding:3px 8px;border-radius:14px;border:1px solid #e2e8f0;">
              <span id="headerPrimaryDot" style="width:12px;height:12px;border-radius:50%;background:{{ $settings->get('primary_color', '#0f5132') }};display:inline-block;"></span>
              <span id="headerAccentDot" style="width:12px;height:12px;border-radius:50%;background:{{ $settings->get('secondary_color', '#c59b27') }};display:inline-block;"></span>
            </div>
            <span class="accordion-chevron">▼</span>
          </div>
        </div>
        <div class="accordion-body">
          <p style="color: var(--muted); margin: 0 0 14px; font-size: 13px;">
            Select from the 1-click preset palettes, click directly on any shade swatch in the palette below, or type your own hex code.
          </p>

          <!-- 1-Click Preset Palettes -->
          <div style="background: #f8faf9; border: 1px solid #d8e6df; border-radius: 8px; padding: 12px 14px; margin-bottom: 16px;">
            <span style="font-size: 12.5px; font-weight: 700; color: #1e293b; display: block; margin-bottom: 8px;">
              ⚡ 1-Click Preset Color Themes:
            </span>
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
              <button type="button" onclick="setPalette('#0f5132', '#093722', '#c59b27', '#a37d18')"
                style="display: flex; align-items: center; gap: 7px; padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #0f5132; display: inline-block;"></span>
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #c59b27; display: inline-block;"></span>
                Academic Green (Default)
              </button>

              <button type="button" onclick="setPalette('#1e3a8a', '#172554', '#f59e0b', '#d97706')"
                style="display: flex; align-items: center; gap: 7px; padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #1e3a8a; display: inline-block;"></span>
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                Royal Navy Blue
              </button>

              <button type="button" onclick="setPalette('#881337', '#4c0519', '#d97706', '#b45309')"
                style="display: flex; align-items: center; gap: 7px; padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #881337; display: inline-block;"></span>
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #d97706; display: inline-block;"></span>
                Crimson Maroon
              </button>

              <button type="button" onclick="setPalette('#0f766e', '#115e59', '#f59e0b', '#d97706')"
                style="display: flex; align-items: center; gap: 7px; padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #0f766e; display: inline-block;"></span>
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                Modern Teal
              </button>

              <button type="button" onclick="setPalette('#1f2937', '#111827', '#eab308', '#ca8a04')"
                style="display: flex; align-items: center; gap: 7px; padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #1f2937; display: inline-block;"></span>
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #eab308; display: inline-block;"></span>
                Dark Charcoal
              </button>

              <button type="button" onclick="setPalette('#581c87', '#3b0764', '#fbbf24', '#f59e0b')"
                style="display: flex; align-items: center; gap: 7px; padding: 6px 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #581c87; display: inline-block;"></span>
                <span style="width: 13px; height: 13px; border-radius: 50%; background: #fbbf24; display: inline-block;"></span>
                Royal Purple
              </button>
            </div>
          </div>

          <!-- Interactive Shade Swatches Palette (Visual Palette Pickers) -->
          <div style="margin-bottom: 16px;">
            <div class="swatch-target-bar">
              <span style="font-size: 12.5px; font-weight: 700; color: #1e293b;">
                🎨 Interactive Color Swatches Palette — Click any shade to select:
              </span>
              <div class="swatch-target-options">
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">Apply click to:</span>
                <label class="swatch-target-label">
                  <input type="radio" name="swatch_target" value="primary" checked onchange="setSwatchTarget('primary')">
                  <span>Primary Brand Color</span>
                </label>
                <label class="swatch-target-label">
                  <input type="radio" name="swatch_target" value="secondary" onchange="setSwatchTarget('secondary')">
                  <span>Secondary Accent Color</span>
                </label>
              </div>
            </div>

            @php
              $shadeFamilies = [
                'Emerald Green' => ['#ecfdf5', '#d1fae5', '#a7f3d0', '#6ee7b7', '#34d399', '#10b981', '#059669', '#047857', '#0f5132', '#064e3b'],
                'Navy / Blue'   => ['#eff6ff', '#dbeafe', '#bfdbfe', '#93c5fd', '#60a5fa', '#3b82f6', '#2563eb', '#1d4ed8', '#1e3a8a', '#0c4a6e'],
                'Modern Teal'   => ['#f0fdfa', '#ccfbf1', '#99f6e4', '#5eead4', '#2dd4bf', '#14b8a6', '#0d9488', '#0f766e', '#115e59', '#134e4a'],
                'Royal Purple'  => ['#faf5ff', '#f3e8ff', '#e9d5ff', '#d8b4fe', '#c084fc', '#a855f7', '#9333ea', '#7e22ce', '#581c87', '#3b0764'],
                'Crimson / Red' => ['#fff1f2', '#ffe4e6', '#fecdd3', '#fda4af', '#fb7185', '#f43f5e', '#e11d48', '#be123c', '#9f1239', '#881337'],
                'Amber / Coral' => ['#fff7ed', '#ffedd5', '#fed7aa', '#fdba74', '#fb923c', '#f97316', '#ea580c', '#c2410c', '#9a3412', '#7c2d12'],
                'Gold / Yellow' => ['#fefce8', '#fef9c3', '#fef08a', '#fde047', '#facc15', '#eab308', '#ca8a04', '#c59b27', '#a37d18', '#713f12'],
                'Slate / Dark'  => ['#f8fafc', '#f1f5f9', '#e2e8f0', '#cbd5e1', '#94a3b8', '#64748b', '#475569', '#334155', '#1e293b', '#0f172a'],
              ];
            @endphp

            <div class="swatch-palette-table">
              @foreach($shadeFamilies as $family => $shades)
                <div class="swatch-row">
                  <span class="swatch-row-name">{{ $family }}</span>
                  <div class="swatch-row-cells">
                    @foreach($shades as $colorCode)
                      <div class="swatch-cell" style="background: {{ $colorCode }};" title="{{ $family }}: {{ $colorCode }}" onclick="pickColorSwatch('{{ $colorCode }}')"></div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Custom Hex Code & Native Color Pickers -->
          <div class="color-control-grid">
            <!-- Primary Color -->
            <div class="color-box">
              <label>Primary Brand Color:</label>
              <div class="color-input-pair">
                <input type="color" id="primaryColorPicker" value="{{ old('primary_color', $settings->get('primary_color', '#0f5132')) }}"
                  oninput="syncColor('primaryColorPicker', 'primaryColorInput')">
                <input type="text" name="primary_color" id="primaryColorInput" value="{{ old('primary_color', $settings->get('primary_color', '#0f5132')) }}"
                  oninput="syncInput('primaryColorInput', 'primaryColorPicker')">
              </div>
              <small style="color: #64748b; font-size: 11px; display: block; margin-top: 4px;">Top navigation, headers, and main buttons</small>
            </div>

            <!-- Primary Hover Color -->
            <div class="color-box">
              <label>Primary Deep / Hover:</label>
              <div class="color-input-pair">
                <input type="color" id="primaryHoverPicker" value="{{ old('primary_hover', $settings->get('primary_hover', '#093722')) }}"
                  oninput="syncColor('primaryHoverPicker', 'primaryHoverInput')">
                <input type="text" name="primary_hover" id="primaryHoverInput" value="{{ old('primary_hover', $settings->get('primary_hover', '#093722')) }}"
                  oninput="syncInput('primaryHoverInput', 'primaryHoverPicker')">
              </div>
              <small style="color: #64748b; font-size: 11px; display: block; margin-top: 4px;">Hover states and footer background</small>
            </div>

            <!-- Secondary Accent Color -->
            <div class="color-box">
              <label>Secondary Accent Color:</label>
              <div class="color-input-pair">
                <input type="color" id="secondaryColorPicker" value="{{ old('secondary_color', $settings->get('secondary_color', '#c59b27')) }}"
                  oninput="syncColor('secondaryColorPicker', 'secondaryColorInput')">
                <input type="text" name="secondary_color" id="secondaryColorInput" value="{{ old('secondary_color', $settings->get('secondary_color', '#c59b27')) }}"
                  oninput="syncInput('secondaryColorInput', 'secondaryColorPicker')">
              </div>
              <small style="color: #64748b; font-size: 11px; display: block; margin-top: 4px;">Gold badges, callout ribbons, action buttons</small>
            </div>

            <!-- Secondary Hover Color -->
            <div class="color-box">
              <label>Accent Deep / Hover:</label>
              <div class="color-input-pair">
                <input type="color" id="secondaryHoverPicker" value="{{ old('secondary_hover', $settings->get('secondary_hover', '#a37d18')) }}"
                  oninput="syncColor('secondaryHoverPicker', 'secondaryHoverInput')">
                <input type="text" name="secondary_hover" id="secondaryHoverInput" value="{{ old('secondary_hover', $settings->get('secondary_hover', '#a37d18')) }}"
                  oninput="syncInput('secondaryHoverInput', 'secondaryHoverPicker')">
              </div>
              <small style="color: #64748b; font-size: 11px; display: block; margin-top: 4px;">Hover background for secondary accent buttons</small>
            </div>
          </div>

          <!-- Live Preview Box -->
          <div style="margin-top: 14px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
              <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Live Theme Preview:</span>
              <span style="font-size: 12px; color: #047857; font-weight: 600;">💡 Click "Apply Now" to save and apply colors immediately to the live site</span>
            </div>
            <div id="themePreviewBanner" style="background: {{ $settings->get('primary_color', '#0f5132') }}; padding: 16px 20px; border-radius: 6px; border-bottom: 4px solid {{ $settings->get('secondary_color', '#c59b27') }}; display: flex; align-items: center; justify-content: space-between; color: #fff; gap: 16px;">
              <div style="font-weight: 700; font-size: 15px;">
                {{ $settings->get('institution_name', 'MCTSC') }}
                <small style="display: block; font-weight: 400; font-size: 12px; opacity: 0.92; margin-top: 2px;">Preview updates instantly as you click swatches or type hex codes</small>
              </div>
              <button type="submit" name="action" value="apply_color" id="themePreviewBtn" title="Click to save and activate these colors immediately"
                style="background: {{ $settings->get('secondary_color', '#c59b27') }}; color: #000; padding: 9px 22px; border-radius: 4px; font-weight: 700; font-size: 13px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.25); white-space: nowrap; transition: transform 0.15s, box-shadow 0.15s;">
                <span>Apply Now</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- =====================================================================
           SECTION 3: CONTACT & SOCIAL MEDIA
           ===================================================================== -->
      <div class="accordion-item" id="sec-contact">
        <div class="accordion-header" onclick="toggleAccordion(this)">
          <div class="accordion-title-wrap">
            <span class="accordion-icon">📞</span>
            <div class="accordion-title-text">
              <h2 class="accordion-title">3. Contact Information & Social Media</h2>
              <p class="accordion-subtitle">Official phone numbers, email address, campus physical location, and social links.</p>
            </div>
          </div>
          <div class="accordion-meta">
            <span class="accordion-chevron">▼</span>
          </div>
        </div>
        <div class="accordion-body">
          <div class="field-grid">
            <label>
              Official Phone Number
              <input name="phone" value="{{ old('phone', $settings->get('phone', '+8801880919275')) }}" placeholder="+8801880919275">
            </label>
            <label>
              Official Email Address
              <input name="email" type="email" value="{{ old('email', $settings->get('email', 'mnctsc@gmail.com')) }}" placeholder="mnctsc@gmail.com">
            </label>
          </div>

          <label style="margin-top: 12px;">
            Campus Physical Address
            <textarea name="address" rows="2">{{ old('address', $settings->get('address', '273/A/520, Chodu Chowdhury Road, Sagorika, Pahartali, Chattogram')) }}</textarea>
          </label>

          <div class="field-grid" style="margin-top: 12px;">
            <label>
              Facebook Page URL
              <input name="social_facebook" value="{{ old('social_facebook', $settings->get('social_facebook', 'https://www.facebook.com/mnctsc')) }}" placeholder="https://www.facebook.com/mnctsc">
            </label>
            <label>
              YouTube Channel URL
              <input name="social_youtube" value="{{ old('social_youtube', $settings->get('social_youtube')) }}" placeholder="https://youtube.com/@channel">
            </label>
          </div>
        </div>
      </div>

      <!-- =====================================================================
           SECTION 4: HERO BANNER & HEADLINES
           ===================================================================== -->
      <div class="accordion-item" id="sec-hero">
        <div class="accordion-header" onclick="toggleAccordion(this)">
          <div class="accordion-title-wrap">
            <span class="accordion-icon">🚀</span>
            <div class="accordion-title-text">
              <h2 class="accordion-title">4. Hero Banner & Homepage Headlines</h2>
              <p class="accordion-subtitle">Top visual banner, promotional badge, main headline, and primary call-to-action button.</p>
            </div>
          </div>
          <div class="accordion-meta">
            <span class="accordion-chevron">▼</span>
          </div>
        </div>
        <div class="accordion-body">
          <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:16px;border-radius:8px;margin-bottom:16px;">
            <div style="font-weight:700;color:#166534;margin-bottom:8px;font-size:14px;display:flex;align-items:center;gap:6px;">
              <span>🏛️</span> Institution Name on Hero Banner Display
            </div>
            <p style="font-size:12.5px;color:#15803d;margin:0 0 12px 0;">Configure whether and how the institution name appears at the top-left of the hero banner, including visibility and custom text color.</p>
            <div class="field-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
              <label>
                <strong>Visibility on Banner:</strong>
                <select name="hero_show_institution_name" style="background:#fff;font-weight:600;">
                  <option value="1" {{ old('hero_show_institution_name', $settings->get('hero_show_institution_name', '1')) === '1' ? 'selected' : '' }}>✓ Show Name on Banner</option>
                  <option value="0" {{ old('hero_show_institution_name', $settings->get('hero_show_institution_name', '1')) === '0' ? 'selected' : '' }}>✕ Hide Name from Banner</option>
                </select>
              </label>
              <label>
                <strong>Institution Name (Hero Banner Text):</strong>
                <input name="hero_institution_name" value="{{ old('hero_institution_name', $settings->get('hero_institution_name', 'Mahmudunnabi Chowdhury Technical School and College')) }}" placeholder="Mahmudunnabi Chowdhury Technical School and College">
              </label>
              <label>
                <strong>Name Text Color:</strong>
                <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                  <input type="color" value="{{ old('hero_text_color', $settings->get('hero_text_color', '#ffffff')) }}" onchange="document.getElementById('heroColorHex').value = this.value;" style="width:42px;height:38px;padding:2px;border:1px solid #cbd5e1;border-radius:4px;cursor:pointer;">
                  <input name="hero_text_color" id="heroColorHex" value="{{ old('hero_text_color', $settings->get('hero_text_color', '#ffffff')) }}" oninput="this.previousElementSibling.value = this.value;" placeholder="#ffffff" style="font-family:monospace;font-weight:700;">
                </div>
              </label>
            </div>
          </div>

          <div class="field-grid" style="margin-bottom: 16px;">
            <label>
              <strong>Hero Image Cropping / Display Mode:</strong>
              <select name="hero_fit_mode" style="background:#fff;font-weight:600;">
                <option value="natural" {{ old('hero_fit_mode', $settings->get('hero_fit_mode', 'natural')) === 'natural' ? 'selected' : '' }}>✓ Full Resolution (No Cropping / সম্পূর্ণ ছবি ক্রপ ছাড়া দেখাবে)</option>
                <option value="cover" {{ old('hero_fit_mode', $settings->get('hero_fit_mode', 'natural')) === 'cover' ? 'selected' : '' }}>Cover Full Frame (Crop to Fill Height)</option>
              </select>
              <small style="color:#64748b;font-size:11.5px;display:block;margin-top:4px;">'Full Resolution' ensures the image displays in its native aspect ratio without cutting off any details.</small>
            </label>
            <label>
              <strong>Hero Left Shadow Overlay (শেডো কালার):</strong>
              <select name="hero_show_overlay" style="background:#fff;font-weight:600;">
                <option value="0" {{ old('hero_show_overlay', $settings->get('hero_show_overlay', '0')) === '0' ? 'selected' : '' }}>✕ Off (Default - No Shadow / ক্লিয়ার ব্যানার ছবি)</option>
                <option value="1" {{ old('hero_show_overlay', $settings->get('hero_show_overlay', '0')) === '1' ? 'selected' : '' }}>✓ On (Dark Shadow on Left / বামে হালকা শেডো ইফেক্ট)</option>
              </select>
              <small style="color:#64748b;font-size:11.5px;display:block;margin-top:4px;">Controls the subtle gradient overlay on the left side of the hero image.</small>
            </label>
          </div>

          <!-- LATEST NOTICES BAR CUSTOMIZATION -->
          <div style="background:#f8fafc;border:1px solid #e2e8f0;padding:16px;border-radius:8px;margin-bottom:16px;">
            <div style="font-weight:700;color:#0f5132;margin-bottom:6px;font-size:14px;display:flex;align-items:center;gap:6px;">
              <span>📢</span> Latest Notices Bar Background & Transparency
            </div>
            <p style="font-size:12.5px;color:#64748b;margin:0 0 12px 0;">Customize the background tint color, transparency level, and text color for the rolling notices bar underneath the hero section.</p>
            <div class="field-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
              <label>
                <strong>Background Tint Color:</strong>
                <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                  <input type="color" value="{{ old('notice_bar_bg_color', $settings->get('notice_bar_bg_color', '#0f5132')) }}" onchange="document.getElementById('noticeBgHex').value = this.value;" style="width:40px;height:36px;padding:2px;border:1px solid #cbd5e1;border-radius:4px;cursor:pointer;">
                  <input name="notice_bar_bg_color" id="noticeBgHex" value="{{ old('notice_bar_bg_color', $settings->get('notice_bar_bg_color', '#0f5132')) }}" oninput="this.previousElementSibling.value = this.value;" placeholder="#0f5132" style="font-family:monospace;font-weight:600;">
                </div>
              </label>
              @php
                $curOp = (float)($settings->get('notice_bar_opacity', '10'));
                if ($curOp <= 1.0 && $curOp > 0) {
                    $curOpPct = (int)round($curOp * 100);
                } else {
                    $curOpPct = (int)$curOp;
                }
                if ($curOpPct <= 0) $curOpPct = 10;
              @endphp
              <label>
                <strong>Background Transparency:</strong>
                <select name="notice_bar_opacity" style="background:#fff;font-weight:600;">
                  <option value="5" {{ $curOpPct == 5 ? 'selected' : '' }}>5% (Very Subtle Tint / অত্যন্ত হালকা)</option>
                  <option value="10" {{ $curOpPct == 10 ? 'selected' : '' }}>10% (Default Soft Tint / মৃদু ব্যাকগ্রাউন্ড)</option>
                  <option value="15" {{ $curOpPct == 15 ? 'selected' : '' }}>15% (Light Tint / হালকা ব্যাকগ্রাউন্ড)</option>
                  <option value="25" {{ $curOpPct == 25 ? 'selected' : '' }}>25% (Noticeable Tint / স্পষ্ট হালকা)</option>
                  <option value="40" {{ $curOpPct == 40 ? 'selected' : '' }}>40% (Medium Tint / মাঝারি)</option>
                  <option value="60" {{ $curOpPct == 60 ? 'selected' : '' }}>60% (Dark Tint / গাঢ়)</option>
                  <option value="100" {{ $curOpPct == 100 ? 'selected' : '' }}>100% (Solid Color / ১০০% সলিড কালার)</option>
                </select>
              </label>
              <label>
                <strong>Notice Text Color:</strong>
                <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                  <input type="color" value="{{ old('notice_bar_text_color', $settings->get('notice_bar_text_color', '#163d32')) }}" onchange="document.getElementById('noticeTextHex').value = this.value;" style="width:40px;height:36px;padding:2px;border:1px solid #cbd5e1;border-radius:4px;cursor:pointer;">
                  <input name="notice_bar_text_color" id="noticeTextHex" value="{{ old('notice_bar_text_color', $settings->get('notice_bar_text_color', '#163d32')) }}" oninput="this.previousElementSibling.value = this.value;" placeholder="#163d32" style="font-family:monospace;font-weight:600;">
                </div>
              </label>
            </div>
          </div>

          <!-- HERO BANNER DISPLAY MODE & SLIDER CONTROLS -->
          <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;padding:16px;border-radius:8px;margin-top:14px;">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:12px;">
              <div>
                <strong style="color:#166534;font-size:15px;display:flex;align-items:center;gap:6px;">
                  <span>🖼️</span> Hero Banner Display Mode (হিরো ব্যানার স্লাইডার কন্ট্রোল)
                </strong>
                <small style="color:#15803d;display:block;margin-top:2px;">
                  হোমপেইজের শীর্ষে ছবিগুলো স্লাইডার হিসেবে চলবে নাকি স্লাইডিং অফ রেখে যেকোনো একটি ছবি স্থির থাকবে তা নির্ধারণ করুন।
                </small>
              </div>
              <a href="{{ route('admin.content.index', 'hero_slides') }}" class="btn gold" style="padding:6px 14px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                <span>➕</span> Manage Slider Images (ছবি যোগ/এডিট/ডিলেট)
              </a>
            </div>

            @php
              $heroMode = old('hero_slider_mode', $settings->get('hero_slider_mode', 'slide'));
              $heroSlidesList = \App\Models\SiteContent::where('section', 'hero_slides')->orderBy('sort_order')->get();
              $activeFixedId = old('hero_slider_fixed_id', $settings->get('hero_slider_fixed_id'));
              $heroIntervalVal = old('hero_slider_interval', $settings->get('hero_slider_interval', '1000'));
            @endphp

            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:14px;margin-top:10px;">
              <!-- Option 1: Slider Mode -->
              <label style="border:2px solid {{ $heroMode === 'slide' ? '#16a34a' : '#cbd5e1' }};padding:12px 14px;border-radius:8px;background:#ffffff;cursor:pointer;display:block;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                  <input type="radio" name="hero_slider_mode" value="slide" {{ $heroMode === 'slide' ? 'checked' : '' }} onchange="document.getElementById('fixedSlideSelect').style.display='none';">
                  <strong style="color:#15803d;font-size:13.5px;">🔄 Auto Sliding Mode (স্লাইডিং চালু)</strong>
                </div>
                <small style="color:#64748b;display:block;font-size:11.5px;line-height:1.4;">
                  এখানে থাকা সমস্ত সক্রিয় ছবি পর্যায়ক্রমে স্লাইড হবে। ২টি ছবি থাকলে ২টি, ১০টি থাকলে ১০টি স্লাইড হবে।
                </small>
              </label>

              <!-- Option 2: Fixed Banner Mode -->
              <label style="border:2px solid {{ $heroMode === 'fixed' ? '#16a34a' : '#cbd5e1' }};padding:12px 14px;border-radius:8px;background:#ffffff;cursor:pointer;display:block;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                  <input type="radio" name="hero_slider_mode" value="fixed" {{ $heroMode === 'fixed' ? 'checked' : '' }} onchange="document.getElementById('fixedSlideSelect').style.display='block';">
                  <strong style="color:#15803d;font-size:13.5px;">📌 Fixed Single Banner (স্লাইডিং অফ / স্থির ছবি)</strong>
                </div>
                <small style="color:#64748b;display:block;font-size:11.5px;line-height:1.4;">
                  স্লাইডিং বন্ধ থাকবে। তীর ও ডট হাইড থাকবে। শুধুমাত্র নির্বাচিত একটি ছবি স্থিরভাবে প্রদর্শিত হবে।
                </small>
              </label>
            </div>

            <!-- Slide Selection for Fixed Mode -->
            <div id="fixedSlideSelect" style="margin-top:12px;background:#ffffff;padding:12px;border:1px solid #cbd5e1;border-radius:6px;display:{{ $heroMode === 'fixed' ? 'block' : 'none' }};">
              <label style="margin:0;font-size:13px;font-weight:600;color:#1e293b;">
                স্থির রাখার জন্য ছবি নির্বাচন করুন (Select Slide to Keep Fixed):
                <select name="hero_slider_fixed_id" style="margin-top:6px;font-weight:500;">
                  <option value="0">-- প্রথম সক্রিয় ছবি প্রদর্শন করুন (Default First Slide) --</option>
                  @foreach($heroSlidesList as $sl)
                    <option value="{{ $sl->id }}" {{ $activeFixedId == $sl->id ? 'selected' : '' }}>
                      Slide #{{ $sl->sort_order }}: {{ $sl->title }} ({{ $sl->status === 'published' ? 'Active' : 'Draft' }})
                    </option>
                  @endforeach
                </select>
              </label>
            </div>

            <!-- Slider Speed / Interval -->
            <div style="margin-top:12px;display:flex;align-items:center;gap:12px;">
              <label style="margin:0;font-size:12.5px;color:#334155;font-weight:600;">
                স্লাইড পরিবর্তনের গতি (Transition Speed):
                <select name="hero_slider_interval" style="width:auto;margin-left:8px;font-size:12px;padding:4px 8px;">
                  <option value="1000" {{ $heroIntervalVal == '1000' ? 'selected' : '' }}>১ সেকেন্ড (ডিফল্ট / 1s Default)</option>
                  <option value="2000" {{ $heroIntervalVal == '2000' ? 'selected' : '' }}>২ সেকেন্ড (2s)</option>
                  <option value="3000" {{ $heroIntervalVal == '3000' ? 'selected' : '' }}>৩ সেকেন্ড (3s)</option>
                  <option value="4000" {{ $heroIntervalVal == '4000' ? 'selected' : '' }}>৪ সেকেন্ড (4s)</option>
                  <option value="5000" {{ $heroIntervalVal == '5000' ? 'selected' : '' }}>৫ সেকেন্ড (5s)</option>
                  <option value="6000" {{ $heroIntervalVal == '6000' ? 'selected' : '' }}>৬ সেকেন্ড (6s)</option>
                  <option value="8000" {{ $heroIntervalVal == '8000' ? 'selected' : '' }}>৮ সেকেন্ড (8s)</option>
                  <option value="10000" {{ $heroIntervalVal == '10000' ? 'selected' : '' }}>১০ সেকেন্ড (10s)</option>
                </select>
              </label>
            </div>
          </div>

          <!-- PRE-DESIGNED INSTITUTIONAL BANNERS SELECTOR -->
          <div style="background:#f8fafc;border:1px solid #e2e8f0;padding:16px;border-radius:8px;margin-top:14px;">
            <div style="font-weight:700;color:#0f5132;margin-bottom:6px;font-size:14px;display:flex;align-items:center;gap:6px;">
              <span>🎨</span> Quick Select: Professional Institutional Hero Banners
            </div>
            <p style="font-size:12.5px;color:#64748b;margin:0 0 12px 0;">Choose from our crafted high-resolution institutional banner designs, or upload your custom image below:</p>
            
            @php
              $activeBannerVal = $settings->get('hero_banner', 'assets/banners/banner_robotics_lab.jpg');
              if (!$activeBannerVal || $activeBannerVal === 'assets/mc Banner.jpg') {
                  $activeBannerVal = 'assets/banners/banner_robotics_lab.jpg';
              }
            @endphp
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:14px;">
              <!-- Option 1 -->
              <label style="border:2px solid {{ $activeBannerVal === 'assets/banners/banner_robotics_lab.jpg' ? '#0f5132' : '#cbd5e1' }};padding:10px;border-radius:8px;cursor:pointer;background:#fff;display:block;transition:all 0.2s ease;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                  <input type="radio" name="preset_banner" value="assets/banners/banner_robotics_lab.jpg" style="width:auto;margin:0;" {{ $activeBannerVal === 'assets/banners/banner_robotics_lab.jpg' ? 'checked' : '' }}>
                  <strong style="font-size:12px;color:#0f5132;">1. Robotics & TVET Lab</strong>
                </div>
                <img src="{{ asset('assets/banners/banner_robotics_lab.jpg') }}" alt="Robotics & TVET Lab" style="width:100%;height:85px;object-fit:cover;border-radius:4px;display:block;border:1px solid #e2e8f0;">
                <small style="color:#64748b;font-size:11px;display:block;margin-top:6px;line-height:1.3;">Robotics & digital test benches in modern glass lab with green accents</small>
              </label>

              <!-- Option 2 -->
              <label style="border:2px solid {{ $activeBannerVal === 'assets/banners/banner_innovation_hub.jpg' ? '#0f5132' : '#cbd5e1' }};padding:10px;border-radius:8px;cursor:pointer;background:#fff;display:block;transition:all 0.2s ease;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                  <input type="radio" name="preset_banner" value="assets/banners/banner_innovation_hub.jpg" style="width:auto;margin:0;" {{ $activeBannerVal === 'assets/banners/banner_innovation_hub.jpg' ? 'checked' : '' }}>
                  <strong style="font-size:12px;color:#0f5132;">2. Technical Innovation Hub</strong>
                </div>
                <img src="{{ asset('assets/banners/banner_innovation_hub.jpg') }}" alt="Innovation Hub" style="width:100%;height:85px;object-fit:cover;border-radius:4px;display:block;border:1px solid #e2e8f0;">
                <small style="color:#64748b;font-size:11px;display:block;margin-top:6px;line-height:1.3;">CAD & 3D prototyping maker lab with modern workstations & pillared hall</small>
              </label>

              <!-- Option 3 -->
              <label style="border:2px solid {{ $activeBannerVal === 'assets/banners/banner_electrical_lab.jpg' ? '#0f5132' : '#cbd5e1' }};padding:10px;border-radius:8px;cursor:pointer;background:#fff;display:block;transition:all 0.2s ease;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                  <input type="radio" name="preset_banner" value="assets/banners/banner_electrical_lab.jpg" style="width:auto;margin:0;" {{ $activeBannerVal === 'assets/banners/banner_electrical_lab.jpg' ? 'checked' : '' }}>
                  <strong style="font-size:12px;color:#0f5132;">3. Electrical & Solar Tech</strong>
                </div>
                <img src="{{ asset('assets/banners/banner_electrical_lab.jpg') }}" alt="Electrical Lab" style="width:100%;height:85px;object-fit:cover;border-radius:4px;display:block;border:1px solid #e2e8f0;">
                <small style="color:#64748b;font-size:11px;display:block;margin-top:6px;line-height:1.3;">Smart industrial PLC automation and clean renewable energy units</small>
              </label>
            </div>
          </div>

          <div style="background:#f8faf9;border:1px solid #e2ebe6;padding:14px;border-radius:6px;margin-top:14px;">
            <label>
              <strong>Upload Custom Hero Banner File</strong> — (Formats: JPG, PNG, WEBP)
              <small style="color:#0369a1;background:#e0f2fe;padding:3px 8px;border-radius:4px;font-size:11.5px;font-weight:600;display:inline-block;margin:4px 0 6px 0;">
                📐 Recommended Resolution: 1920 × 720 px (1920x720 Aspect Ratio). The banner maintains this proportion across screens without squishing.
              </small>
              <input type="file" name="hero_banner" accept="image/*">
              @php
                $currentHero = storage_asset($settings->get('hero_banner'), 'assets/banners/banner_robotics_lab.jpg');
              @endphp
              <div style="margin-top: 10px; display: flex; align-items: center; gap: 14px;">
                <img src="{{ $currentHero }}" alt="Hero Banner" style="height: 70px; max-width: 180px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                <span style="font-size: 12px; color: #555;">Current Active Hero Banner Preview</span>
              </div>
            </label>
          </div>

          <div style="margin-top: 14px; display: flex; justify-content: flex-end;">
            <button type="submit" style="background: #0f5132; color: #ffffff; border: none; padding: 10px 22px; border-radius: 6px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(15,81,50,0.25);">
              <span>💾</span> Save Banner & Notice Settings
            </button>
          </div>
        </div>
      </div>

      <!-- =====================================================================
           SECTION 4.1: HOMEPAGE CMS CONTENT & PARTNERSHIPS
           ===================================================================== -->
      <div class="accordion-item" id="sec-homepage-cms">
        <div class="accordion-header" onclick="toggleAccordion(this)">
          <div class="accordion-title-wrap">
            <span class="accordion-icon">🌐</span>
            <div class="accordion-title-text">
              <h2 class="accordion-title">4.1 Homepage CMS Content (Strategic Partnership, Vision/Mission, Partners)</h2>
              <p class="accordion-subtitle">Customize Strategic Partnership (IMI), Vision, Mission, Objectives, and Development Partners texts.</p>
            </div>
          </div>
          <div class="accordion-meta">
            <span class="accordion-chevron">▼</span>
          </div>
        </div>
        <div class="accordion-body">

          <!-- Strategic Partnership with Impact Initiative -->
          <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:16px;border-radius:8px;margin-bottom:18px;">
            <h4 style="margin:0 0 10px 0;color:#166534;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>🤝</span> Strategic Partnership with Impact Initiative (কৌশলগত অংশীদারিত্ব)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr;">
              <label>
                Section Kicker (ছোট হেডার)
                <input name="strategic_partnership_kicker" value="{{ old('strategic_partnership_kicker', $settings->get('strategic_partnership_kicker', 'STRATEGIC PARTNERSHIP')) }}">
              </label>
              <label>
                Section Main Title (শিরোনাম)
                <input name="strategic_partnership_title" value="{{ old('strategic_partnership_title', $settings->get('strategic_partnership_title', 'Partnership with Impact Initiative')) }}">
              </label>
            </div>
            <label style="margin-top: 10px;">
              Partnership Description Body 1 (মূল বিবরণ প্যারাগ্রাফ)
              <textarea name="strategic_partnership_body" rows="4">{{ old('strategic_partnership_body', $settings->get('strategic_partnership_body', "Established in 1995, Impact Initiative (IMI) is a national humanitarian and development organization in Bangladesh, registered with the NGO Affairs Bureau (Registration No: 1224) under the Prime Minister's Office. Its work spans youth development, education, skills development and livelihoods, among other areas. Through its TVET initiatives, the organization seeks to strengthen young people's practical capabilities and improve their access to employment opportunities.")) }}</textarea>
            </label>
            <label style="margin-top: 10px;">
              Partnership Footer / Closing Note (সমাপনী বাক্য)
              <input name="strategic_partnership_footer" value="{{ old('strategic_partnership_footer', $settings->get('strategic_partnership_footer', 'Through this partnership, MC Technical School & College continues to support skills development and employment-oriented training. For Quality Assurance of the institute, the following Management Committee has been formed.')) }}">
            </label>
          </div>

          <!-- Governance / Management Committee (Quality Assurance Body) -->
          <div style="background:#f0fdf4;border:1px solid #86efac;padding:16px;border-radius:8px;margin-bottom:18px;">
            <h4 style="margin:0 0 10px 0;color:#14532d;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>👥</span> Governance / Management Committee (পরিচালনা ও ব্যবস্থাপনা কমিটি - কোয়ালিটি এস্যুরেন্স)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr;">
              <label>
                Committee Section Title (কমিটি সেকশন শিরোনাম)
                <input name="gov_committee_title" value="{{ old('gov_committee_title', $settings->get('gov_committee_title', 'Governance / Management Committee')) }}">
              </label>
              <label>
                Committee Intro Text (ভূমিকা বক্তব্য)
                <input name="gov_committee_intro" value="{{ old('gov_committee_intro', $settings->get('gov_committee_intro', 'MCTSC has an Institute Management Committee. This Committee is comprised of 5 members. This Management Committee (MC) is responsible for Quality Assurance of the Institute. The following people are the members of the committee:')) }}">
              </label>
            </div>

            <p style="font-weight:700;color:#14532d;margin:14px 0 8px 0;font-size:13.5px;">কমিটির সম্মানিত ৫ জন সদস্যের বিবরণ (Committee Members):</p>

            <div style="display:flex;flex-direction:column;gap:12px;">
              @php
                $defaultGovMembers = [
                  1 => ['name' => 'Md. Enayet Hossain', 'pos' => 'Chair', 'qual' => 'M.Sc. in Psychology', 'exp' => '27 years in Education and TVET management'],
                  2 => ['name' => 'Dr. Md Shaif Ullah Mansur', 'pos' => 'Vice Chair', 'qual' => 'Ph.D. in the Bangladesh Constitution, M.Com in Finance', 'exp' => 'BGMEA Director'],
                  3 => ['name' => 'Md. Mozammel Haque', 'pos' => 'Member Secretary', 'qual' => 'BA Hons MA', 'exp' => '28 years of experience working in Social work'],
                  4 => ['name' => 'Shakh Nur Alam', 'pos' => 'Treasurer', 'qual' => 'B. Com Hons, M. Com', 'exp' => "20 years' social work experience"],
                  5 => ['name' => 'Md. Mosarrof Hossain', 'pos' => 'Member', 'qual' => 'MSS', 'exp' => '20 years of working experience in community development, 2 years in TVET'],
                ];
              @endphp

              @for($gm = 1; $gm <= 5; $gm++)
                @php $mDef = $defaultGovMembers[$gm]; @endphp
                <div style="background:#ffffff;border:1px solid #bbf7d0;padding:12px;border-radius:6px;">
                  <strong style="color:#14532d;font-size:13.5px;display:block;margin-bottom:8px;">Member #{{ $gm }} (সদস্য {{ $gm }})</strong>
                  <div class="field-grid" style="grid-template-columns: 1.5fr 1fr 1.5fr 2fr;">
                    <label>
                      Name (নাম)
                      <input name="gov_member_{{ $gm }}_name" value="{{ old('gov_member_' . $gm . '_name', $settings->get('gov_member_' . $gm . '_name', $mDef['name'])) }}">
                    </label>
                    <label>
                      Position (পদবী)
                      <input name="gov_member_{{ $gm }}_position" value="{{ old('gov_member_' . $gm . '_position', $settings->get('gov_member_' . $gm . '_position', $mDef['pos'])) }}">
                    </label>
                    <label>
                      Educational Qualification (শিক্ষাগত যোগ্যতা)
                      <input name="gov_member_{{ $gm }}_qualification" value="{{ old('gov_member_' . $gm . '_qualification', $settings->get('gov_member_' . $gm . '_qualification', $mDef['qual'])) }}">
                    </label>
                    <label>
                      Professional Experience (পেশাগত অভিজ্ঞতা)
                      <input name="gov_member_{{ $gm }}_experience" value="{{ old('gov_member_' . $gm . '_experience', $settings->get('gov_member_' . $gm . '_experience', $mDef['exp'])) }}">
                    </label>
                  </div>
                </div>
              @endfor
            </div>
          </div>

          <!-- Vision, Mission & Objectives -->
          <div style="background:#f8fafc;border:1px solid #e2e8f0;padding:16px;border-radius:8px;margin-bottom:18px;">
            <h4 style="margin:0 0 10px 0;color:#1e293b;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>🎯</span> Vision, Mission & Objectives (রূপকল্প, লক্ষ্য ও উদ্দেশ্যসমূহ)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 12px;">
              <div>
                <label>
                  Vision Pill Label
                  <input name="vmo_vision_pill" value="{{ old('vmo_vision_pill', $settings->get('vmo_vision_pill', 'Vision')) }}">
                </label>
                <label style="margin-top: 8px;">
                  Vision Statement (রূপকল্প)
                  <textarea name="vmo_vision_text" rows="4">{{ old('vmo_vision_text', $settings->get('vmo_vision_text', "Mahmudunnabi Chowdhury Technical School and College (MCTSC) envisions employing socially disadvantaged youth, men, and women, and encourages them to participate in the country's mainstream development initiatives.")) }}</textarea>
                </label>
              </div>
              <div>
                <label>
                  Mission Pill Label
                  <input name="vmo_mission_pill" value="{{ old('vmo_mission_pill', $settings->get('vmo_mission_pill', 'Mission')) }}">
                </label>
                <label style="margin-top: 8px;">
                  Mission Statement (লক্ষ্য)
                  <textarea name="vmo_mission_text" rows="4">{{ old('vmo_mission_text', $settings->get('vmo_mission_text', "Mahmudunnabi Chowdhury Technical School and College (MCTSC) is to promote the production of skilled/semi-skilled technical and professional manpower, to revitalize and sustain the national economy, reduce unemployment and poverty through the setting and maintenance of high standards, provision of current and reliable information for planning and decision making, sourcing and disbursing of funds, and adequate linkages with industry.")) }}</textarea>
                </label>
              </div>
            </div>

            <div>
              <div class="field-grid" style="grid-template-columns: 1fr 2fr;">
                <label>
                  Objectives Pill Label
                  <input name="vmo_objectives_pill" value="{{ old('vmo_objectives_pill', $settings->get('vmo_objectives_pill', 'Objectives')) }}">
                </label>
                <div></div>
              </div>
              <label style="margin-top: 8px;">
                Objectives Points (প্রতি লাইনে একটি করে উদ্দেশ্য পয়েন্ট লিখুন)
                <textarea name="vmo_objectives_text" rows="6" style="font-family: monospace; font-size: 13.5px;">{{ old('vmo_objectives_text', $settings->get('vmo_objectives_text', "To provide accessible, practical, and employment-oriented technical and vocational education and training.\nTo develop the technical, professional, and life skills of disadvantaged and unemployed youth.\nTo promote self-employment and facilitate access to suitable employment and entrepreneurship development opportunities.\nTo strengthen collaboration with industries and employers to improve training relevance and job placement.\nTo contribute to reducing unemployment and poverty through skills development and sustainable livelihoods.\nTo support trainees through post-training employment assistance and follow-up.")) }}</textarea>
                <small style="color:#64748b;font-size:12px;">💡 নতুন পয়েন্ট যোগ করতে নতুন লাইন লিখুন। টিকচিহ্ন স্বয়ংক্রিয়ভাবে দেখাবে।</small>
              </label>
            </div>
          </div>

          <!-- Development Partners Marquee Intro -->
          <div style="background:#fdfbf7;border:1px solid #f1e6d0;padding:16px;border-radius:8px;">
            <h4 style="margin:0 0 10px 0;color:#92400e;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>🏢</span> Development Partners Intro (উন্নয়ন অংশীদার পরিচিতি)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr;">
              <label>
                Partners Kicker
                <input name="dev_partners_kicker" value="{{ old('dev_partners_kicker', $settings->get('dev_partners_kicker', 'COLLABORATION & PARTNERSHIPS')) }}">
              </label>
              <label>
                Partners Main Title
                <input name="dev_partners_title" value="{{ old('dev_partners_title', $settings->get('dev_partners_title', 'Development Partners')) }}">
              </label>
            </div>
            <label style="margin-top: 10px;">
              Partners Introduction Paragraph
              <textarea name="dev_partners_text" rows="3">{{ old('dev_partners_text', $settings->get('dev_partners_text', "MCTSC's institutional profile records collaboration with development organisations and programmes including ASSET, POWER_kfw-BRAC, UPPR-UNDP, SOS, ILO, ECHO, and MAA International, among others. These partnerships reflect the institution's experience in delivering skills development and employment-oriented training initiatives.")) }}</textarea>
            </label>
          </div>

          <div style="margin-top: 14px; display: flex; justify-content: flex-end;">
            <button type="submit" style="background: #0f5132; color: #ffffff; border: none; padding: 10px 22px; border-radius: 6px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(15,81,50,0.25);">
              <span>💾</span> Save Homepage CMS Content
            </button>
          </div>
        </div>
      </div>

      <!-- =====================================================================
           SECTION 5: ABOUT INSTITUTION & CAMPUS PHOTO
           ===================================================================== -->
      <div class="accordion-item" id="sec-about">
        <div class="accordion-header" onclick="toggleAccordion(this)">
          <div class="accordion-title-wrap">
            <span class="accordion-icon">📖</span>
            <div class="accordion-title-text">
              <h2 class="accordion-title">5. About Institution & Campus Overview</h2>
              <p class="accordion-subtitle">Institutional history, mission description, aside quote banner, and campus building photo.</p>
            </div>
          </div>
          <div class="accordion-meta">
            <span class="accordion-chevron">▼</span>
          </div>
        </div>
        <div class="accordion-body">
          <label>
            About Section Title
            <input name="about_title" value="{{ old('about_title', $settings->get('about_title', 'About Our College')) }}">
          </label>

          <label style="margin-top: 12px;">
            Institution Overview & Detailed Description
            <textarea name="about" rows="5">{{ old('about', $settings->get('about', 'Mahmudunnabi Chowdhury Technical School and College provides quality technical and vocational education to equip learners with practical skills for a brighter future.')) }}</textarea>
          </label>

          <label style="margin-top: 12px;">
            Aside / Highlight Slogan
            <input name="about_aside" value="{{ old('about_aside', $settings->get('about_aside', 'Education / Skills / Opportunities / A Stronger Tomorrow')) }}">
          </label>

          <!-- About Page Header & Introduction CMS Settings -->
          <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:16px;border-radius:8px;margin-top:16px;">
            <h4 style="margin:0 0 8px 0;color:#166534;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>📌</span> About Page Header & Introduction (এবাউট পেজ ভূমিকা ও পরিচিতি)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr;">
              <label>
                Intro Top Kicker (হেডার কিপশন)
                <input name="about_intro_kicker" value="{{ old('about_intro_kicker', $settings->get('about_intro_kicker', 'INTRODUCTION')) }}" placeholder="INTRODUCTION">
              </label>
              <label>
                Intro Main Title (প্রধান শিরোনাম)
                <input name="about_intro_title" value="{{ old('about_intro_title', $settings->get('about_intro_title', 'Mahmudunnabi Chowdhury Technical School & College')) }}">
              </label>
            </div>
            <label style="margin-top: 10px;">
              Intro Subtitle / Tagline (সাবটাইটেল / স্লোগান)
              <input name="about_intro_subtitle" value="{{ old('about_intro_subtitle', $settings->get('about_intro_subtitle', 'Building Skills. Unlocking Potential. Shaping Futures.')) }}">
            </label>
            <label style="margin-top: 10px;">
              About Page Detailed Introduction Body (এবাউট পেজের মূল বর্ণনা প্যারাগ্রাফসমূহ)
              <textarea name="about_intro_body" rows="5" placeholder="Leave blank to use default institutional history and TVET overview...">{{ old('about_intro_body', $settings->get('about_intro_body')) }}</textarea>
            </label>
            <label style="margin-top: 10px;">
              Institutional Commitment (প্রতিষ্ঠানিক অঙ্গীকার)
              <textarea name="about_commitment" rows="2">{{ old('about_commitment', $settings->get('about_commitment', 'To develop a skilled workforce, expand employment opportunities, reduce poverty, and contribute to Bangladesh\'s sustainable socioeconomic development through quality, inclusive, and industry-responsive vocational education.')) }}</textarea>
            </label>
          </div>

          <!-- Industry Partnerships CMS Settings -->
          <div style="background:#fdfbf7;border:1px solid #f1e6d0;padding:16px;border-radius:8px;margin-top:16px;">
            <h4 style="margin:0 0 8px 0;color:#92400e;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>🏭</span> Industry Partnerships for Employment & Career Development (ইন্ডাস্ট্রি পার্টনার ও ক্যারিয়ার উন্নয়ন)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr;">
              <label>
                Section Kicker
                <input name="industry_partners_kicker" value="{{ old('industry_partners_kicker', $settings->get('industry_partners_kicker', 'EMPLOYMENT & CAREER DEVELOPMENT')) }}">
              </label>
              <label>
                Section Title
                <input name="industry_partners_title" value="{{ old('industry_partners_title', $settings->get('industry_partners_title', 'Industry Partnerships for Employment and Career Development')) }}">
              </label>
            </div>

            <label style="margin-top: 10px;">
              Intro Paragraph 1 (ভূমিকা প্যারাগ্রাফ ১)
              <textarea name="industry_partners_intro_1" rows="3">{{ old('industry_partners_intro_1', $settings->get('industry_partners_intro_1', 'Mahmudunnabi Chowdhury Technical School and College (MCTSC) maintains collaborative relationships with industries, employers, and service-sector organizations to enhance graduate employability and promote sustainable career opportunities. Through these partnerships, MCTSC supports job placement, workplace-based learning, industry exposure, and the development of market-relevant skills.')) }}</textarea>
            </label>

            <label style="margin-top: 10px;">
              Intro Paragraph 2 (ভূমিকা প্যারাগ্রাফ ২)
              <textarea name="industry_partners_intro_2" rows="2">{{ old('industry_partners_intro_2', $settings->get('industry_partners_intro_2', 'Our industry partners contribute to bridging the gap between vocational training and labour market demands, helping graduates transition into wage employment, self-employment, and long-term career development.')) }}</textarea>
            </label>

            <div class="field-grid" style="grid-template-columns: 1fr 1fr; margin-top: 10px;">
              <label>
                Partners List Heading
                <input name="industry_partners_list_title" value="{{ old('industry_partners_list_title', $settings->get('industry_partners_list_title', 'Our Industry and Employment Partners')) }}">
              </label>
              <label>
                Industry Commitment Heading / Text
                <input name="industry_partners_commitment" value="{{ old('industry_partners_commitment', $settings->get('industry_partners_commitment', 'To strengthen industry linkages, expand employment opportunities, and connect skilled graduates with employers for a productive and sustainable workforce.')) }}">
              </label>
            </div>

            <label style="margin-top: 10px;">
              Industry & Employment Partners List (প্রতি লাইনে একটি করে পার্টনার কোম্পানির নাম লিখুন)
              <textarea name="industry_partners_list" rows="8" style="font-family: monospace; font-size: 13.5px;">{{ old('industry_partners_list', $settings->get('industry_partners_list', "Alert Fashion Ltd.\nMellow Fashion Ltd.\nSalim & Brothers Ltd.\nAstech Ltd.\nHB Group\nHB Intimates Ltd.\nFashion of Wales Ltd.\nHotel New View")) }}</textarea>
              <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">
                💡 নতুন ইন্ডাস্ট্রি পার্টনার যোগ করতে নতুন লাইনে কোম্পানির নাম লিখুন, বাদ দিতে চাইলে লাইন মুছে দিন।
              </small>
            </label>
          </div>

          <div style="background:#f8faf9;border:1px solid #e2ebe6;padding:14px;border-radius:6px;margin-top:14px;">
            <label>
              <strong>About Us & Institute Photograph (আমাদের পরিচিতি ও ইন্সটিটিউট ছবি)</strong>
              <small style="color:#0369a1;background:#e0f2fe;padding:3px 8px;border-radius:4px;font-size:11.5px;font-weight:600;display:inline-block;margin:4px 0 6px 0;">
                📐 Recommended Resolution: 1000 × 650 px or 1200 × 800 px (3:2 Aspect Ratio, Landscape JPG/PNG/WEBP). এই ছবিটি ওয়েবসাইট হোমপেইজের Institute Background এবং About Us পেইজে সরাসরি প্রদর্শিত হয়।
              </small>
              <input type="file" name="about_image" accept="image/*">
              @php
                $currentAbout = storage_asset($settings->get('about_image'), 'assets/about_mahmudunnabi_legacy.jpg');
                if (empty($settings->get('about_image')) || $settings->get('about_image') === 'assets/MC Building Photo.jpg') {
                    $currentAbout = asset('assets/about_mahmudunnabi_legacy.jpg');
                }
              @endphp
              <div style="margin-top: 10px; display: flex; align-items: center; gap: 14px;">
                <img src="{{ $currentAbout }}" alt="About Image" style="height: 70px; max-width: 180px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                <span style="font-size: 12px; color: #555;">বর্তমান প্রদর্শিত ছবি (Current About Preview)</span>
              </div>
            </label>
          </div>

          <div style="margin-top: 14px; display: flex; justify-content: flex-end;">
            <!-- ================= CORE VALUES (8 POINTS) ================= -->
          <div style="background:#f8fafc;border:1px solid #cbd5e1;padding:16px;border-radius:8px;margin-top:16px;">
            <h4 style="margin:0 0 10px 0;color:#0f5132;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>⭐</span> Institute Core Values (কোর ভ্যালুস - ৮টি পয়েন্ট)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr 2fr; margin-bottom: 12px;">
              <label>
                Core Values Kicker
                <input name="core_values_kicker" value="{{ old('core_values_kicker', $settings->get('core_values_kicker', 'GUIDING PRINCIPLES')) }}">
              </label>
              <label>
                Core Values Title
                <input name="core_values_title" value="{{ old('core_values_title', $settings->get('core_values_title', 'Institute Core Values')) }}">
              </label>
              <label>
                Core Values Subtitle
                <input name="core_values_subtitle" value="{{ old('core_values_subtitle', $settings->get('core_values_subtitle', 'Mahmudunnabi Chowdhury Technical School and College (MCTSC)')) }}">
              </label>
            </div>

            <!-- 8 Value Points -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              @php
                $defaultValues = [
                  1 => ['title' => 'Integrity and Accountability', 'desc' => 'We uphold honesty, transparency, ethical conduct, and accountability in all our institutional activities, financial management, and stakeholder relationships.'],
                  2 => ['title' => 'Quality and Excellence', 'desc' => 'We are committed to delivering high-quality, competency-based technical and vocational education that meets national standards and evolving industry requirements.'],
                  3 => ['title' => 'Inclusion and Equal Opportunity', 'desc' => 'We promote equal access to skills training for disadvantaged, unemployed, unskilled, orphaned, and vulnerable youth, regardless of gender or socioeconomic background.'],
                  4 => ['title' => 'Employability and Entrepreneurship', 'desc' => 'We develop practical skills, professional competencies, and entrepreneurial capabilities to enable graduates to secure employment, establish businesses, and achieve sustainable livelihoods.'],
                  5 => ['title' => 'Collaboration and Partnership', 'desc' => 'We strengthen partnerships with government agencies, employers, development organizations, industries, and communities to improve training quality and employment outcomes.'],
                  6 => ['title' => 'Innovation and Continuous Learning', 'desc' => 'We encourage innovative teaching methods, practical learning, trainer development, Recognition of Prior Learning (RPL), and continuous improvement in skills development.'],
                  7 => ['title' => 'Respect, Dignity and Safeguarding', 'desc' => 'We foster a safe, respectful, inclusive, and supportive learning environment that protects the dignity, rights, and well-being of all trainees and staff.'],
                  8 => ['title' => 'Social Responsibility and Sustainable Development', 'desc' => 'We contribute to poverty reduction, youth empowerment, community development, and sustainable socioeconomic growth through skills training and employment support.'],
                ];
              @endphp

              @for($v = 1; $v <= 8; $v++)
                <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;">
                  <label style="font-weight:700;color:#1e293b;">
                    Value #{{ $v }} Title
                    <input name="core_val_{{ $v }}_title" value="{{ old('core_val_' . $v . '_title', $settings->get('core_val_' . $v . '_title', $defaultValues[$v]['title'])) }}">
                  </label>
                  <label style="margin-top:6px;">
                    Value #{{ $v }} Description
                    <textarea name="core_val_{{ $v }}_desc" rows="3">{{ old('core_val_' . $v . '_desc', $settings->get('core_val_' . $v . '_desc', $defaultValues[$v]['desc'])) }}</textarea>
                  </label>
                </div>
              @endfor
            </div>
          </div>

          <!-- ================= LEGAL STATUS CMS ================= -->
          <div style="background:#fffbeb;border:1px solid #fde68a;padding:16px;border-radius:8px;margin-top:16px;">
            <h4 style="margin:0 0 10px 0;color:#92400e;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>📜</span> Legal Status & Identification Records (আইনি স্বীকৃতি ও লাইসেন্স তথ্য)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr;">
              <label>
                Legal Status Kicker
                <input name="legal_status_kicker" value="{{ old('legal_status_kicker', $settings->get('legal_status_kicker', 'ACCREDITATION & COMPLIANCE')) }}">
              </label>
              <label>
                Legal Status Title
                <input name="legal_status_title" value="{{ old('legal_status_title', $settings->get('legal_status_title', 'Legal Status')) }}">
              </label>
            </div>
            <label style="margin-top: 10px;">
              Legal Status Introduction Text
              <textarea name="legal_status_intro" rows="3">{{ old('legal_status_intro', $settings->get('legal_status_intro', 'Mahmudunnabi Chowdhury Technical School and College (MCTSC) operates within the relevant regulatory and institutional framework for technical education and skills development in Bangladesh. The institution maintains registration, identification, and accreditation records with the relevant authorities to support its educational, training, and administrative activities.')) }}</textarea>
            </label>

            <div class="field-grid" style="grid-template-columns: repeat(3, 1fr); margin-top: 12px;">
              <label>
                BTEB Institute Code
                <input name="legal_status_bteb" value="{{ old('legal_status_bteb', $settings->get('legal_status_bteb', '70102')) }}">
              </label>
              <label>
                NSDA STP Registration Number
                <input name="legal_status_nsda" value="{{ old('legal_status_nsda', $settings->get('legal_status_nsda', 'STP-CHA-000135')) }}">
              </label>
              <label>
                Trade Licence (CCC)
                <input name="legal_status_trade" value="{{ old('legal_status_trade', $settings->get('legal_status_trade', '68883')) }}">
              </label>
            </div>

            <div class="field-grid" style="grid-template-columns: 1fr 1fr; margin-top: 10px;">
              <label>
                Tax Identification Number (TIN)
                <input name="legal_status_tin" value="{{ old('legal_status_tin', $settings->get('legal_status_tin', '433576326443')) }}">
              </label>
              <label>
                Business Identification Number (BIN/VAT)
                <input name="legal_status_bin" value="{{ old('legal_status_bin', $settings->get('legal_status_bin', '001583568')) }}">
              </label>
            </div>

            <label style="margin-top: 10px;">
              Legal Status Footer Assurance Note
              <input name="legal_status_footer" value="{{ old('legal_status_footer', $settings->get('legal_status_footer', 'MCTSC is committed to maintaining regulatory compliance, institutional accountability, transparency, and quality assurance in its technical and vocational education and training programmes.')) }}">
            </label>
          </div>

          <!-- ================= HONOURING THE LEGACY OF MAHMUDUNNABI CHOWDHURY ================= -->
          <div style="background:#fdfbf7;border:1px solid #f1e6d0;padding:16px;border-radius:8px;margin-top:16px;">
            <h4 style="margin:0 0 10px 0;color:#92400e;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>🏛️</span> Honouring the Legacy of Mahmudunnabi Chowdhury (স্মৃতি ও ঐতিহ্য)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr;">
              <label>
                Patron Section Kicker
                <input name="patron_kicker" value="{{ old('patron_kicker', $settings->get('patron_kicker', 'INSPIRATION & HERITAGE')) }}">
              </label>
              <label>
                Patron Section Title
                <input name="patron_title" value="{{ old('patron_title', $settings->get('patron_title', 'Honouring the Legacy of Mahmudunnabi Chowdhury')) }}">
              </label>
            </div>
            <label style="margin-top: 10px;">
              Patron Legacy Description / Biography Text
              <textarea name="patron_text" rows="4">{{ old('patron_text', $settings->get('patron_text', 'Mahmudunnabi Chowdhury (1908–1995) was a prominent businessman and politician from Chattogram who served as a member of the East Pakistan Legislative Assembly and held ministerial responsibilities. The institution carries forward a commitment to human development and community progress in remembrance of his legacy.')) }}</textarea>
            </label>
          </div>

          <!-- ================= 4 TRAINING PILLARS (WHAT WE DO) ================= -->
          <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:16px;border-radius:8px;margin-top:16px;">
            <h4 style="margin:0 0 10px 0;color:#166534;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>💼</span> Core Pillars - What We Do (মূল কার্যক্রমের ৪টি পিলার)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr; margin-bottom: 12px;">
              <label>
                Pillars Kicker
                <input name="pillars_kicker" value="{{ old('pillars_kicker', $settings->get('pillars_kicker', 'CORE PILLARS')) }}">
              </label>
              <label>
                Pillars Main Title
                <input name="pillars_title" value="{{ old('pillars_title', $settings->get('pillars_title', 'What We Do')) }}">
              </label>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;">
                <label><strong>Pillar 1 Title</strong><input name="pillar_1_title" value="{{ old('pillar_1_title', $settings->get('pillar_1_title', 'Technical & Vocational Training')) }}"></label>
                <label style="margin-top:6px;">Pillar 1 Description<textarea name="pillar_1_desc" rows="2">{{ old('pillar_1_desc', $settings->get('pillar_1_desc', 'Practical, occupation-focused skills development.')) }}</textarea></label>
              </div>
              <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;">
                <label><strong>Pillar 2 Title</strong><input name="pillar_2_title" value="{{ old('pillar_2_title', $settings->get('pillar_2_title', 'Project-Based Training')) }}"></label>
                <label style="margin-top:6px;">Pillar 2 Description<textarea name="pillar_2_desc" rows="2">{{ old('pillar_2_desc', $settings->get('pillar_2_desc', 'Skills training delivered under relevant development projects.')) }}</textarea></label>
              </div>
              <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;">
                <label><strong>Pillar 3 Title</strong><input name="pillar_3_title" value="{{ old('pillar_3_title', $settings->get('pillar_3_title', 'Assessment & Certification')) }}"></label>
                <label style="margin-top:6px;">Pillar 3 Description<textarea name="pillar_3_desc" rows="2">{{ old('pillar_3_desc', $settings->get('pillar_3_desc', 'Skills assessment and certification opportunities in accordance with applicable requirements.')) }}</textarea></label>
              </div>
              <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;">
                <label><strong>Pillar 4 Title</strong><input name="pillar_4_title" value="{{ old('pillar_4_title', $settings->get('pillar_4_title', 'Employment Linkages')) }}"></label>
                <label style="margin-top:6px;">Pillar 4 Description<textarea name="pillar_4_desc" rows="2">{{ old('pillar_4_desc', $settings->get('pillar_4_desc', 'Supporting trainees in connecting their acquired skills with employment opportunities.')) }}</textarea></label>
              </div>
            </div>
          </div>

          <button type="submit" style="background: #0f5132; color: #ffffff; border: none; padding: 10px 22px; border-radius: 6px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(15,81,50,0.25);">
              <span>💾</span> Save About Section Settings
            </button>
          </div>
        </div>
      </div>

      <!-- =====================================================================
           SECTION 6: CAMPUS FACILITIES & WORKSHOPS
           ===================================================================== -->
      <div class="accordion-item" id="sec-facilities">
        <div class="accordion-header" onclick="toggleAccordion(this)">
          <div class="accordion-title-wrap">
            <span class="accordion-icon">🔬</span>
            <div class="accordion-title-text">
              <h2 class="accordion-title">6. Infrastructure Page & Practical Workshops CMS</h2>
              <p class="accordion-subtitle">Showcase 4 laboratory facilities, equipment workshops, and hands-on training setups.</p>
            </div>
          </div>
          <div class="accordion-meta">
            <span class="accordion-chevron">▼</span>
          </div>
        </div>
        <div class="accordion-body">
          <!-- Infrastructure Page Overview & 8 Facilities -->
          <div style="background:#fdfbf7;border:1px solid #f1e6d0;padding:16px;border-radius:8px;margin-bottom:18px;">
            <h4 style="margin:0 0 10px 0;color:#92400e;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>🏢</span> Infrastructure Page Overview & Header (অবকাঠামো পেজ পরিচিতি)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 10px;">
              <label>
                Infrastructure Page Title
                <input name="infr_page_title" value="{{ old('infr_page_title', $settings->get('infr_page_title', 'Campus Infrastructure & Facilities')) }}">
              </label>
              <label>
                Infrastructure Page Subtitle
                <input name="infr_page_subtitle" value="{{ old('infr_page_subtitle', $settings->get('infr_page_subtitle', 'Comprehensive facilities for classroom instruction, practical training, and learner support.')) }}">
              </label>
            </div>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr;">
              <label>
                Campus Overview Kicker
                <input name="infr_overview_kicker" value="{{ old('infr_overview_kicker', $settings->get('infr_overview_kicker', 'CAMPUS OVERVIEW')) }}">
              </label>
              <label>
                Campus Overview Title
                <input name="infr_overview_title" value="{{ old('infr_overview_title', $settings->get('infr_overview_title', 'Two-Storey Campus Infrastructure')) }}">
              </label>
            </div>
            <label style="margin-top: 10px;">
              Campus Overview Description
              <textarea name="infr_overview_desc" rows="3">{{ old('infr_overview_desc', $settings->get('infr_overview_desc', 'MCTSC operates from a two-storey building with facilities for classroom instruction, practical training, administration, and learner support.')) }}</textarea>
            </label>
          </div>

          <!-- Training Facilities (8 Items) -->
          <div style="background:#f8fafc;border:1px solid #cbd5e1;padding:16px;border-radius:8px;margin-bottom:18px;">
            <h4 style="margin:0 0 10px 0;color:#0f5132;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>🛠️</span> 8 Training Facilities on Infrastructure Page (প্রশিক্ষণ সুবিধাসমূহ)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr; margin-bottom: 12px;">
              <label>
                Facilities Section Kicker
                <input name="infr_facilities_kicker" value="{{ old('infr_facilities_kicker', $settings->get('infr_facilities_kicker', 'FACILITIES')) }}">
              </label>
              <label>
                Facilities Main Title
                <input name="infr_facilities_title" value="{{ old('infr_facilities_title', $settings->get('infr_facilities_title', 'Training Facilities')) }}">
              </label>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              @php
                $defaultInfrFac = [
                  1 => ['title' => 'Theory Classrooms', 'desc' => 'Dedicated spaces for theoretical instruction and interactive learning.'],
                  2 => ['title' => 'Technical Training Workshops and Laboratories', 'desc' => 'Facilities for practical skills development across relevant technical and vocational occupations.'],
                  3 => ['title' => 'Computer Laboratories', 'desc' => 'Computer-based learning facilities with internet access to support digital skills development.'],
                  4 => ['title' => 'Seminar and Meeting Room', 'desc' => 'Space for seminars, workshops, meetings, and stakeholder engagement.'],
                  5 => ['title' => 'Library and Learning Resources', 'desc' => 'Learning resources to support trainees\' knowledge and professional development.'],
                  6 => ['title' => 'Student Counselling and Information Desk', 'desc' => 'Support for trainee enquiries, guidance, and access to institutional services.'],
                  7 => ['title' => 'Student Common Room', 'desc' => 'A shared space for trainees.'],
                  8 => ['title' => 'Administrative Facilities', 'desc' => 'Offices and operational spaces to support institutional management and programme delivery.'],
                ];
              @endphp

              @for($f = 1; $f <= 8; $f++)
                <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;">
                  <label style="font-weight:700;color:#1e293b;">
                    Facility #{{ $f }} Title
                    <input name="infr_fac_{{ $f }}_title" value="{{ old('infr_fac_' . $f . '_title', $settings->get('infr_fac_' . $f . '_title', $defaultInfrFac[$f]['title'])) }}">
                  </label>
                  <label style="margin-top:6px;">
                    Facility #{{ $f }} Description
                    <textarea name="infr_fac_{{ $f }}_desc" rows="2">{{ old('infr_fac_' . $f . '_desc', $settings->get('infr_fac_' . $f . '_desc', $defaultInfrFac[$f]['desc'])) }}</textarea>
                  </label>
                </div>
              @endfor
            </div>
          </div>

          <!-- Training Capacity at a Glance (7 Metrics) -->
          <div style="background:#fffbeb;border:1px solid #fde68a;padding:16px;border-radius:8px;margin-bottom:18px;">
            <h4 style="margin:0 0 10px 0;color:#92400e;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>📊</span> Training Capacity at a Glance (এক নজরে প্রশিক্ষণ সক্ষমতার ৭টি প্যারামিটার)
            </h4>
            <div class="field-grid" style="grid-template-columns: 1fr 2fr; margin-bottom: 12px;">
              <label>
                Capacity Section Kicker
                <input name="infr_capacity_kicker" value="{{ old('infr_capacity_kicker', $settings->get('infr_capacity_kicker', 'CAPACITY & SCOPE')) }}">
              </label>
              <label>
                Capacity Main Title
                <input name="infr_capacity_title" value="{{ old('infr_capacity_title', $settings->get('infr_capacity_title', 'Training Capacity at a Glance')) }}">
              </label>
            </div>

            <div class="field-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 10px;">
              <label>
                1. Campus Location
                <input name="infr_cap_location" value="{{ old('infr_cap_location', $settings->get('infr_cap_location', 'Chadu Chowdhury Road, Sagorika, Pahartali, Chattogram, Bangladesh')) }}">
              </label>
              <label>
                2. Total Reported Floor Area
                <input name="infr_cap_area" value="{{ old('infr_cap_area', $settings->get('infr_cap_area', 'Approximately 17,000 sq. ft.')) }}">
              </label>
            </div>

            <div class="field-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 10px;">
              <label>
                3. Building Structure
                <input name="infr_cap_structure" value="{{ old('infr_cap_structure', $settings->get('infr_cap_structure', 'Two-storey building')) }}">
              </label>
              <label>
                4. Classrooms & Laboratories
                <input name="infr_cap_rooms" value="{{ old('infr_cap_rooms', $settings->get('infr_cap_rooms', 'Eight rooms on each floor')) }}">
              </label>
            </div>

            <div class="field-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 10px;">
              <label>
                5. Annual Training Capacity
                <input name="infr_cap_annual" value="{{ old('infr_cap_annual', $settings->get('infr_cap_annual', 'Approximately 1,000 beneficiaries')) }}">
              </label>
              <label>
                6. Training Occupations (affiliated)
                <input name="infr_cap_occupations" value="{{ old('infr_cap_occupations', $settings->get('infr_cap_occupations', 'Fourteen')) }}">
              </label>
            </div>

            <label>
              7. Training Approach & Pedagogy
              <input name="infr_cap_approach" value="{{ old('infr_cap_approach', $settings->get('infr_cap_approach', 'Practical, competency-based, and employment-oriented learning')) }}">
            </label>
          </div>

          <h4 style="margin:20px 0 10px 0;color:#0f5132;font-size:15px;">🔬 4 Featured Homepage Labs & Workshops (৪টি প্রধান প্র্যাকটিক্যাল ল্যাব ছবি ও শিরোনাম)</h4>
          <p style="color: var(--muted); margin: 0 0 14px; font-size: 13px;">Configure images and titles for the 4 featured training workshops on the homepage.</p>

          <!-- Facility 1 -->
          <div style="background:#fdfdfd;border:1px solid #e2ebe6;padding:12px;border-radius:6px;margin-bottom:12px;">
            <div class="field-grid">
              <label>
                Facility 1 Title
                <input name="facility_1_title" value="{{ old('facility_1_title', $settings->get('facility_1_title', 'Computer Lab')) }}">
              </label>
              <label>
                Facility 1 Image
                <small style="color:#0369a1;background:#e0f2fe;padding:2px 6px;border-radius:4px;font-size:11px;font-weight:600;display:block;margin:3px 0 4px 0;">
                  📐 Recommended: 800 × 500 px (16:10 Ratio, JPG/WEBP)
                </small>
                <input type="file" name="facility_1_image" accept="image/*">
              </label>
            </div>
            @php
              $f1 = storage_asset($settings->get('facility_1_image'), 'assets/Computer Lab r.jpg');
            @endphp
            <div style="margin-top:8px;display:flex;align-items:center;gap:10px;">
              <img src="{{ $f1 }}" style="height:45px;width:75px;object-fit:cover;border-radius:4px;border:1px solid #ccc;">
              <small style="color:#666;">Current photo: {{ $settings->get('facility_1_title', 'Computer Lab') }}</small>
            </div>
          </div>

          <!-- Facility 2 -->
          <div style="background:#fdfdfd;border:1px solid #e2ebe6;padding:12px;border-radius:6px;margin-bottom:12px;">
            <div class="field-grid">
              <label>
                Facility 2 Title
                <input name="facility_2_title" value="{{ old('facility_2_title', $settings->get('facility_2_title', 'Refrigeration and AC Workshop')) }}">
              </label>
              <label>
                Facility 2 Image
                <small style="color:#0369a1;background:#e0f2fe;padding:2px 6px;border-radius:4px;font-size:11px;font-weight:600;display:block;margin:3px 0 4px 0;">
                  📐 Recommended: 800 × 500 px (16:10 Ratio, JPG/WEBP)
                </small>
                <input type="file" name="facility_2_image" accept="image/*">
              </label>
            </div>
            @php
              $f2 = storage_asset($settings->get('facility_2_image'), 'assets/RAC Lab.jpg');
            @endphp
            <div style="margin-top:8px;display:flex;align-items:center;gap:10px;">
              <img src="{{ $f2 }}" style="height:45px;width:75px;object-fit:cover;border-radius:4px;border:1px solid #ccc;">
              <small style="color:#666;">Current photo: {{ $settings->get('facility_2_title', 'Refrigeration and AC Workshop') }}</small>
            </div>
          </div>

          <!-- Facility 3 -->
          <div style="background:#fdfdfd;border:1px solid #e2ebe6;padding:12px;border-radius:6px;margin-bottom:12px;">
            <div class="field-grid">
              <label>
                Facility 3 Title
                <input name="facility_3_title" value="{{ old('facility_3_title', $settings->get('facility_3_title', 'Plumbing Workshop')) }}">
              </label>
              <label>
                Facility 3 Image
                <small style="color:#0369a1;background:#e0f2fe;padding:2px 6px;border-radius:4px;font-size:11px;font-weight:600;display:block;margin:3px 0 4px 0;">
                  📐 Recommended: 800 × 500 px (16:10 Ratio, JPG/WEBP)
                </small>
                <input type="file" name="facility_3_image" accept="image/*">
              </label>
            </div>
            @php
              $f3 = storage_asset($settings->get('facility_3_image'), 'assets/Plumbing Class.jpg');
            @endphp
            <div style="margin-top:8px;display:flex;align-items:center;gap:10px;">
              <img src="{{ $f3 }}" style="height:45px;width:75px;object-fit:cover;border-radius:4px;border:1px solid #ccc;">
              <small style="color:#666;">Current photo: {{ $settings->get('facility_3_title', 'Plumbing Workshop') }}</small>
            </div>
          </div>

          <!-- Facility 4 -->
          <div style="background:#fdfdfd;border:1px solid #e2ebe6;padding:12px;border-radius:6px;">
            <div class="field-grid">
              <label>
                Facility 4 Title
                <input name="facility_4_title" value="{{ old('facility_4_title', $settings->get('facility_4_title', 'Tailoring and Sewing Workshop')) }}">
              </label>
              <label>
                Facility 4 Image
                <small style="color:#0369a1;background:#e0f2fe;padding:2px 6px;border-radius:4px;font-size:11px;font-weight:600;display:block;margin:3px 0 4px 0;">
                  📐 Recommended: 800 × 500 px (16:10 Ratio, JPG/WEBP)
                </small>
                <input type="file" name="facility_4_image" accept="image/*">
              </label>
            </div>
            @php
              $f4 = storage_asset($settings->get('facility_4_image'), 'assets/SMO Lab.jpg');
            @endphp
            <div style="margin-top:8px;display:flex;align-items:center;gap:10px;">
              <img src="{{ $f4 }}" style="height:45px;width:75px;object-fit:cover;border-radius:4px;border:1px solid #ccc;">
              <small style="color:#666;">Current photo: {{ $settings->get('facility_4_title', 'Tailoring and Sewing Workshop') }}</small>
            </div>
          </div>

          <div style="margin-top: 14px; display: flex; justify-content: flex-end;">
            <button type="submit" style="background: #0f5132; color: #ffffff; border: none; padding: 10px 22px; border-radius: 6px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(15,81,50,0.25);">
              <span>💾</span> Save Facilities Settings
            </button>
          </div>
        </div>
      </div>

      <!-- =====================================================================
           SECTION 7: ADMISSIONS CALLOUT & INSTITUTIONAL QUOTES
           ===================================================================== -->
      <div class="accordion-item" id="sec-admissions">
        <div class="accordion-header" onclick="toggleAccordion(this)">
          <div class="accordion-title-wrap">
            <span class="accordion-icon">💬</span>
            <div class="accordion-title-text">
              <h2 class="accordion-title">7. Admissions Callout & Institutional Quotes</h2>
              <p class="accordion-subtitle">Admissions section headlines, prominent quotation banner, and footer highlight tagline.</p>
            </div>
          </div>
          <div class="accordion-meta">
            <span class="accordion-chevron">▼</span>
          </div>
        </div>
        <div class="accordion-body">
          <div class="field-grid">
            <label>
              Admission Section Title
              <input name="admission_title" value="{{ old('admission_title', $settings->get('admission_title', 'Admission Information')) }}">
            </label>
            <label>
              Admission Subtitle
              <input name="admission_subtitle" value="{{ old('admission_subtitle', $settings->get('admission_subtitle', 'Ask about eligibility, application process and important dates.')) }}">
            </label>
          </div>

          <div class="field-grid" style="margin-top:12px;">
            <label>
              Quotation Card Headline (Quote Title)
              <input name="quote_title" value="{{ old('quote_title', $settings->get('quote_title', 'Skilled People Build a Better Bangladesh')) }}">
            </label>
            <label>
              Footer Highlight Tagline
              <input name="footer_tag" value="{{ old('footer_tag', $settings->get('footer_tag', 'A Brighter Tomorrow Through Skills')) }}">
            </label>
          </div>

          <!-- Secondary Pages Headers CMS -->
          <div style="background:#f8fafc;border:1px solid #cbd5e1;padding:16px;border-radius:8px;margin-top:16px;">
            <h4 style="margin:0 0 10px 0;color:#0f5132;font-size:15px;display:flex;align-items:center;gap:6px;">
              <span>📑</span> Other Pages CMS Headers (কোর্স, প্রজেক্ট, গ্যালারি, নোটিশ ও যোগাযোগ পেজের শিরোনাম)
            </h4>

            <!-- Courses Page -->
            <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;margin-bottom:10px;">
              <strong style="color:#0f5132;font-size:14px;display:block;margin-bottom:6px;">🎓 Courses Page (কোর্স পেজ)</strong>
              <div class="field-grid" style="grid-template-columns:1fr 2fr;">
                <label>Page Title<input name="courses_page_title" value="{{ old('courses_page_title', $settings->get('courses_page_title', 'Our Technical & Vocational Courses')) }}"></label>
                <label>Page Subtitle<input name="courses_page_subtitle" value="{{ old('courses_page_subtitle', $settings->get('courses_page_subtitle', 'Market-relevant, hands-on trade training aligned with BTEB curriculum and industry workplace demands.')) }}"></label>
              </div>
            </div>

            <!-- Projects Page -->
            <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;margin-bottom:10px;">
              <strong style="color:#0f5132;font-size:14px;display:block;margin-bottom:6px;">🚀 Projects Page (প্রজেক্ট পেজ)</strong>
              <div class="field-grid" style="grid-template-columns:1fr 2fr;">
                <label>Page Title<input name="projects_page_title" value="{{ old('projects_page_title', $settings->get('projects_page_title', 'Projects & Development Initiatives')) }}"></label>
                <label>Page Subtitle<input name="projects_page_subtitle" value="{{ old('projects_page_subtitle', $settings->get('projects_page_subtitle', 'Collaborative training programs supported by government ministries and international development partners.')) }}"></label>
              </div>
            </div>

            <!-- Gallery Page -->
            <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;margin-bottom:10px;">
              <strong style="color:#0f5132;font-size:14px;display:block;margin-bottom:6px;">🖼️ Gallery Page (গ্যালারি পেজ)</strong>
              <div class="field-grid" style="grid-template-columns:1fr 2fr;">
                <label>Page Title<input name="gallery_page_title" value="{{ old('gallery_page_title', $settings->get('gallery_page_title', 'Campus Life & Workshop Gallery')) }}"></label>
                <label>Page Subtitle<input name="gallery_page_subtitle" value="{{ old('gallery_page_subtitle', $settings->get('gallery_page_subtitle', 'A visual glance into our modern technical workshops, student practical sessions, and campus events.')) }}"></label>
              </div>
            </div>

            <!-- Notice Board Page -->
            <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;margin-bottom:10px;">
              <strong style="color:#0f5132;font-size:14px;display:block;margin-bottom:6px;">📌 Notice Board Page (নোটিশ পেজ)</strong>
              <div class="field-grid" style="grid-template-columns:1fr 2fr;">
                <label>Page Title<input name="notices_page_title" value="{{ old('notices_page_title', $settings->get('notices_page_title', 'Official Notice Board')) }}"></label>
                <label>Page Subtitle<input name="notices_page_subtitle" value="{{ old('notices_page_subtitle', $settings->get('notices_page_subtitle', 'Official announcements, admission circulars, class routines, and exam schedules.')) }}"></label>
              </div>
            </div>

            <!-- Contact Us Page -->
            <div style="background:#ffffff;border:1px solid #e2e8f0;padding:12px;border-radius:6px;">
              <strong style="color:#0f5132;font-size:14px;display:block;margin-bottom:6px;">📞 Contact Page (যোগাযোগ পেজ)</strong>
              <div class="field-grid" style="grid-template-columns:1fr 2fr;">
                <label>Page Title<input name="contact_page_title" value="{{ old('contact_page_title', $settings->get('contact_page_title', 'Contact Our Campus')) }}"></label>
                <label>Page Subtitle<input name="contact_page_subtitle" value="{{ old('contact_page_subtitle', $settings->get('contact_page_subtitle', 'Get in touch with our admissions office for course inquiries, campus visits, or general assistance.')) }}"></label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- =====================================================================
           SECTION 8: ADMISSION FORM MANDATORY FIELDS
           ===================================================================== -->
      @php
        $reqFields = \App\Models\SiteSetting::getRequiredAdmissionFields();
      @endphp
      <div class="accordion-item" id="sec-mandatory-fields">
        <div class="accordion-header" onclick="toggleAccordion(this)">
          <div class="accordion-title-wrap">
            <span class="accordion-icon">🛡️</span>
            <div class="accordion-title-text">
              <h2 class="accordion-title">8. Admission Form Mandatory Fields Configuration</h2>
              <p class="accordion-subtitle">Configure which application form fields are mandatory (*) for applicants vs optional.</p>
            </div>
          </div>
          <div class="accordion-meta">
            <span id="headerAfmBadge" class="afm-counter-pill" style="font-size:11px;padding:3px 8px;">
              Loading...
            </span>
            <span class="accordion-chevron">▼</span>
          </div>
        </div>
        <div class="accordion-body">
          <input type="hidden" name="admission_fields_config_submitted" value="1">
          <p style="color: var(--muted); margin: 0 0 14px; font-size: 13px;">
            Checked fields will be <strong>Mandatory (marked with red asterisk <span style="color:#dc2626;font-weight:bold;">*</span>)</strong> on the public trainee admission form. Unchecked fields will remain optional.
          </p>

          <div class="afm-container afm-wrap">
            {{-- Top Toolbar --}}
            <div class="afm-top-panel">
              <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <span class="afm-lock-notice">
                  🔒 <strong>System Locked:</strong> Trainee Name (English) & Desired Course Trade are always mandatory
                </span>
                <span id="afmTotalCounter" class="afm-counter-pill">
                  📊 Loading...
                </span>
              </div>

              <div class="afm-actions-wrap">
                <input type="text" id="afmSearchInput" class="afm-search-input" placeholder="🔍 Search field name..." oninput="filterAfmFields(this.value)">
                <button type="button" class="afm-btn afm-btn-select" onclick="setAllAdmissionFields(true)" title="Mark all configurable fields as mandatory">
                  ✓ Select All
                </button>
                <button type="button" class="afm-btn afm-btn-default" onclick="setDefaultAdmissionFields()" title="Set recommended default required fields">
                  ⚡ Recommended Defaults
                </button>
                <button type="button" class="afm-btn afm-btn-clear" onclick="setAllAdmissionFields(false)" title="Mark all fields as optional">
                  ✕ Clear All
                </button>
              </div>
            </div>

            {{-- Categories Grid with 3 Compact Masonry Columns --}}
            <div class="afm-grid-cols" id="afmGrid">
              {{-- COLUMN 1: Card 1 & Card 4 --}}
              <div class="afm-col afm-col-1">
                {{-- Card 1: Trainee Personal Details --}}
                <div class="afm-card afm-card-1">
                  <div class="afm-card-header">
                    <span class="afm-card-title">1. Trainee Personal Details</span>
                    <div style="display:flex;align-items:center;gap:5px;">
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">All</button>
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">Clear</button>
                      <span class="afm-card-badge">0/0</span>
                    </div>
                  </div>
                  <div class="afm-card-body">
                    <label class="afm-row @if(in_array('trainee_name_bn', $reqFields)) is-active @endif" for="afm_trainee_name_bn">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_trainee_name_bn" name="admission_required_fields[]" value="trainee_name_bn" class="admission-field-chk" @checked(in_array('trainee_name_bn', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Trainee Name (Bengali)</span>
                          <span class="afm-text-sub">নাম বাংলায়</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('dob', $reqFields)) is-active @endif" for="afm_dob">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_dob" name="admission_required_fields[]" value="dob" class="admission-field-chk" @checked(in_array('dob', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Date of Birth</span>
                          <span class="afm-text-sub">DOB as per certificate</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('ident_type', $reqFields)) is-active @endif" for="afm_ident_type">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_ident_type" name="admission_required_fields[]" value="ident_type" class="admission-field-chk" @checked(in_array('ident_type', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Identity Document Type</span>
                          <span class="afm-text-sub">NID or Birth Registration</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('ident_no', $reqFields)) is-active @endif" for="afm_ident_no">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_ident_no" name="admission_required_fields[]" value="ident_no" class="admission-field-chk" @checked(in_array('ident_no', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">NID / Birth Certificate No.</span>
                          <span class="afm-text-sub">Identification document number</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('sex', $reqFields)) is-active @endif" for="afm_sex">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_sex" name="admission_required_fields[]" value="sex" class="admission-field-chk" @checked(in_array('sex', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Sex / Gender</span>
                          <span class="afm-text-sub">Male, Female, or Other</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('pwd', $reqFields)) is-active @endif" for="afm_pwd">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_pwd" name="admission_required_fields[]" value="pwd" class="admission-field-chk" @checked(in_array('pwd', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Disability Status (PWD)</span>
                          <span class="afm-text-sub">Physical disability status</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('contact_no', $reqFields)) is-active @endif" for="afm_contact_no">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_contact_no" name="admission_required_fields[]" value="contact_no" class="admission-field-chk" @checked(in_array('contact_no', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Mobile / Contact Number</span>
                          <span class="afm-text-sub">Primary student phone</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('email', $reqFields)) is-active @endif" for="afm_email">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_email" name="admission_required_fields[]" value="email" class="admission-field-chk" @checked(in_array('email', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Email Address</span>
                          <span class="afm-text-sub">Personal email account</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('marital_status', $reqFields)) is-active @endif" for="afm_marital_status">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_marital_status" name="admission_required_fields[]" value="marital_status" class="admission-field-chk" @checked(in_array('marital_status', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Marital Status</span>
                          <span class="afm-text-sub">Single, Married, etc.</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>
                  </div>
                </div>

                {{-- Card 4: Permanent Address Details --}}
                <div class="afm-card afm-card-4">
                  <div class="afm-card-header">
                    <span class="afm-card-title">4. Permanent Address Details</span>
                    <div style="display:flex;align-items:center;gap:5px;">
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">All</button>
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">Clear</button>
                      <span class="afm-card-badge">0/0</span>
                    </div>
                  </div>
                  <div class="afm-card-body">
                    <label class="afm-row @if(in_array('perm_division', $reqFields)) is-active @endif" for="afm_perm_division">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_perm_division" name="admission_required_fields[]" value="perm_division" class="admission-field-chk" @checked(in_array('perm_division', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Permanent Division</span>
                          <span class="afm-text-sub">Permanent home division</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('perm_district', $reqFields)) is-active @endif" for="afm_perm_district">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_perm_district" name="admission_required_fields[]" value="perm_district" class="admission-field-chk" @checked(in_array('perm_district', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Permanent District</span>
                          <span class="afm-text-sub">Permanent home district</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('perm_upazilla', $reqFields)) is-active @endif" for="afm_perm_upazilla">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_perm_upazilla" name="admission_required_fields[]" value="perm_upazilla" class="admission-field-chk" @checked(in_array('perm_upazilla', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Permanent Upazilla / Thana</span>
                          <span class="afm-text-sub">Permanent sub-district</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('perm_address', $reqFields)) is-active @endif" for="afm_perm_address">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_perm_address" name="admission_required_fields[]" value="perm_address" class="admission-field-chk" @checked(in_array('perm_address', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Permanent Full Address</span>
                          <span class="afm-text-sub">Permanent village/road/area</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('perm_area_type', $reqFields)) is-active @endif" for="afm_perm_area_type">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_perm_area_type" name="admission_required_fields[]" value="perm_area_type" class="admission-field-chk" @checked(in_array('perm_area_type', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Locality Area Type</span>
                          <span class="afm-text-sub">Rural / Urban classification</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- COLUMN 2: Card 2, Card 5 & Card 7 --}}
              <div class="afm-col afm-col-2">
                {{-- Card 2: Photos & Document Uploads --}}
                <div class="afm-card afm-card-2">
                  <div class="afm-card-header">
                    <span class="afm-card-title">2. Photos & Document Uploads</span>
                    <div style="display:flex;align-items:center;gap:5px;">
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">All</button>
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">Clear</button>
                      <span class="afm-card-badge">0/0</span>
                    </div>
                  </div>
                  <div class="afm-card-body">
                    <label class="afm-row @if(in_array('photo', $reqFields)) is-active @endif" for="afm_photo">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_photo" name="admission_required_fields[]" value="photo" class="admission-field-chk" @checked(in_array('photo', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Passport Photo Upload</span>
                          <span class="afm-text-sub">Recent passport size photo</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('signature_photo', $reqFields)) is-active @endif" for="afm_signature_photo">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_signature_photo" name="admission_required_fields[]" value="signature_photo" class="admission-field-chk" @checked(in_array('signature_photo', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Digital Signature Upload</span>
                          <span class="afm-text-sub">Applicant scanned signature</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('ident_doc', $reqFields)) is-active @endif" for="afm_ident_doc">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_ident_doc" name="admission_required_fields[]" value="ident_doc" class="admission-field-chk" @checked(in_array('ident_doc', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">NID / Birth Certificate File</span>
                          <span class="afm-text-sub">Document scan or PDF</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>
                  </div>
                </div>

                {{-- Card 5: Education & Demographics --}}
                <div class="afm-card afm-card-5">
                  <div class="afm-card-header">
                    <span class="afm-card-title">5. Education & Demographics</span>
                    <div style="display:flex;align-items:center;gap:5px;">
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">All</button>
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">Clear</button>
                      <span class="afm-card-badge">0/0</span>
                    </div>
                  </div>
                  <div class="afm-card-body">
                    <label class="afm-row @if(in_array('education_level', $reqFields)) is-active @endif" for="afm_education_level">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_education_level" name="admission_required_fields[]" value="education_level" class="admission-field-chk" @checked(in_array('education_level', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Educational Qualification</span>
                          <span class="afm-text-sub">Highest degree/exam level</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('religion', $reqFields)) is-active @endif" for="afm_religion">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_religion" name="admission_required_fields[]" value="religion" class="admission-field-chk" @checked(in_array('religion', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Religion</span>
                          <span class="afm-text-sub">Religious faith</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('ethnicity', $reqFields)) is-active @endif" for="afm_ethnicity">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_ethnicity" name="admission_required_fields[]" value="ethnicity" class="admission-field-chk" @checked(in_array('ethnicity', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Ethnicity / Community</span>
                          <span class="afm-text-sub">Ethnic identity</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>
                  </div>
                </div>

                {{-- Card 7: Employment & Background --}}
                <div class="afm-card afm-card-7">
                  <div class="afm-card-header">
                    <span class="afm-card-title">7. Employment & Background</span>
                    <div style="display:flex;align-items:center;gap:5px;">
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">All</button>
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">Clear</button>
                      <span class="afm-card-badge">0/0</span>
                    </div>
                  </div>
                  <div class="afm-card-body">
                    <label class="afm-row @if(in_array('has_bank_account', $reqFields)) is-active @endif" for="afm_has_bank_account">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_has_bank_account" name="admission_required_fields[]" value="has_bank_account" class="admission-field-chk" @checked(in_array('has_bank_account', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Has Active Bank Account</span>
                          <span class="afm-text-sub">Bank account (Yes / No)</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('past_training', $reqFields)) is-active @endif" for="afm_past_training">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_past_training" name="admission_required_fields[]" value="past_training" class="admission-field-chk" @checked(in_array('past_training', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Prior Technical Training</span>
                          <span class="afm-text-sub">Previous course experience</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('employment_status', $reqFields)) is-active @endif" for="afm_employment_status">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_employment_status" name="admission_required_fields[]" value="employment_status" class="admission-field-chk" @checked(in_array('employment_status', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Current Employment Status</span>
                          <span class="afm-text-sub">Unemployed, student, job</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('monthly_cash_income', $reqFields)) is-active @endif" for="afm_monthly_cash_income">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_monthly_cash_income" name="admission_required_fields[]" value="monthly_cash_income" class="admission-field-chk" @checked(in_array('monthly_cash_income', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Applicant Monthly Cash Income</span>
                          <span class="afm-text-sub">Personal monthly earnings</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('training_media_source', $reqFields)) is-active @endif" for="afm_training_media_source">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_training_media_source" name="admission_required_fields[]" value="training_media_source" class="admission-field-chk" @checked(in_array('training_media_source', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Information / Media Source</span>
                          <span class="afm-text-sub">How applicant learned about course</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>
                  </div>
                </div>
              </div>

              {{-- COLUMN 3: Card 3 & Card 6 --}}
              <div class="afm-col afm-col-3">
                {{-- Card 3: Present Address Details --}}
                <div class="afm-card afm-card-3">
                  <div class="afm-card-header">
                    <span class="afm-card-title">3. Present Address Details</span>
                    <div style="display:flex;align-items:center;gap:5px;">
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">All</button>
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">Clear</button>
                      <span class="afm-card-badge">0/0</span>
                    </div>
                  </div>
                  <div class="afm-card-body">
                    <label class="afm-row @if(in_array('pres_division', $reqFields)) is-active @endif" for="afm_pres_division">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_pres_division" name="admission_required_fields[]" value="pres_division" class="admission-field-chk" @checked(in_array('pres_division', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Present Division</span>
                          <span class="afm-text-sub">Current administrative division</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('pres_district', $reqFields)) is-active @endif" for="afm_pres_district">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_pres_district" name="admission_required_fields[]" value="pres_district" class="admission-field-chk" @checked(in_array('pres_district', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Present District</span>
                          <span class="afm-text-sub">Current residential district</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('pres_upazilla', $reqFields)) is-active @endif" for="afm_pres_upazilla">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_pres_upazilla" name="admission_required_fields[]" value="pres_upazilla" class="admission-field-chk" @checked(in_array('pres_upazilla', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Present Upazilla / Thana</span>
                          <span class="afm-text-sub">Current sub-district/police station</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('pres_address', $reqFields)) is-active @endif" for="afm_pres_address">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_pres_address" name="admission_required_fields[]" value="pres_address" class="admission-field-chk" @checked(in_array('pres_address', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Present Full Address Line</span>
                          <span class="afm-text-sub">Street, building, area</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>
                  </div>
                </div>

                {{-- Card 6: Family & Guardian Details --}}
                <div class="afm-card afm-card-6">
                  <div class="afm-card-header">
                    <span class="afm-card-title">6. Family & Guardian Details</span>
                    <div style="display:flex;align-items:center;gap:5px;">
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">All</button>
                      <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">Clear</button>
                      <span class="afm-card-badge">0/0</span>
                    </div>
                  </div>
                  <div class="afm-card-body">
                    <label class="afm-row @if(in_array('father_name_en', $reqFields)) is-active @endif" for="afm_father_name_en">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_father_name_en" name="admission_required_fields[]" value="father_name_en" class="admission-field-chk" @checked(in_array('father_name_en', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Father's Name (English)</span>
                          <span class="afm-text-sub">Father name in English</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('father_name_bn', $reqFields)) is-active @endif" for="afm_father_name_bn">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_father_name_bn" name="admission_required_fields[]" value="father_name_bn" class="admission-field-chk" @checked(in_array('father_name_bn', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Father's Name (Bengali)</span>
                          <span class="afm-text-sub">পিতার নাম বাংলায়</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('mother_name_en', $reqFields)) is-active @endif" for="afm_mother_name_en">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_mother_name_en" name="admission_required_fields[]" value="mother_name_en" class="admission-field-chk" @checked(in_array('mother_name_en', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Mother's Name (English)</span>
                          <span class="afm-text-sub">Mother name in English</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('mother_name_bn', $reqFields)) is-active @endif" for="afm_mother_name_bn">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_mother_name_bn" name="admission_required_fields[]" value="mother_name_bn" class="admission-field-chk" @checked(in_array('mother_name_bn', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Mother's Name (Bengali)</span>
                          <span class="afm-text-sub">মাতার নাম বাংলায়</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('emergency_contact', $reqFields)) is-active @endif" for="afm_emergency_contact">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_emergency_contact" name="admission_required_fields[]" value="emergency_contact" class="admission-field-chk" @checked(in_array('emergency_contact', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Emergency / Guardian Contact</span>
                          <span class="afm-text-sub">Alternative phone number</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('household_members', $reqFields)) is-active @endif" for="afm_household_members">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_household_members" name="admission_required_fields[]" value="household_members" class="admission-field-chk" @checked(in_array('household_members', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Household Members Count</span>
                          <span class="afm-text-sub">Total family members</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('income_source', $reqFields)) is-active @endif" for="afm_income_source">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_income_source" name="admission_required_fields[]" value="income_source" class="admission-field-chk" @checked(in_array('income_source', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Primary Family Income Source</span>
                          <span class="afm-text-sub">Occupation / earning head</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>

                    <label class="afm-row @if(in_array('monthly_income', $reqFields)) is-active @endif" for="afm_monthly_income">
                      <div class="afm-row-left">
                        <input type="checkbox" id="afm_monthly_income" name="admission_required_fields[]" value="monthly_income" class="admission-field-chk" @checked(in_array('monthly_income', $reqFields))>
                        <div class="afm-row-text">
                          <span class="afm-text-primary">Monthly Household Income</span>
                          <span class="afm-text-sub">Total monthly income (BDT)</span>
                        </div>
                      </div>
                      <div class="afm-row-right">
                        <span class="afm-badge-req">Required *</span>
                        <span class="afm-badge-opt">Optional</span>
                      </div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- STICKY ACTION BUTTON -->
    <div class="form-actions" style="position: sticky; bottom: 0; background: #fff; padding: 16px 0; border-top: 1px solid #ddd; z-index: 10;">
      <button class="btn gold" type="submit" style="font-size: 15px; padding: 12px 32px;">Save All Website Settings</button>
    </div>
  </form>
</section>

<script>
/* -------------------------------------------------------------------------
   ACCORDION LOGIC
   ------------------------------------------------------------------------- */
function toggleAccordion(header) {
  const item = header.closest('.accordion-item');
  if (!item) return;
  item.classList.toggle('is-open');
}

function expandAllAccordions(open) {
  document.querySelectorAll('.accordion-item').forEach(item => {
    if (open) {
      item.classList.add('is-open');
    } else {
      item.classList.remove('is-open');
    }
  });
}

/* -------------------------------------------------------------------------
   COLOR PALETTE & SWATCH INTERACTION LOGIC
   ------------------------------------------------------------------------- */
let activeSwatchTarget = 'primary'; // 'primary' or 'secondary'

function setSwatchTarget(target) {
  activeSwatchTarget = target;
}

function pickColorSwatch(hex) {
  if (activeSwatchTarget === 'primary') {
    document.getElementById('primaryColorPicker').value = hex;
    document.getElementById('primaryColorInput').value = hex;

    // Automatically calculate a slightly deeper shade for hover if possible
    const deepHex = adjustColorBrightness(hex, -25);
    document.getElementById('primaryHoverPicker').value = deepHex;
    document.getElementById('primaryHoverInput').value = deepHex;
  } else {
    document.getElementById('secondaryColorPicker').value = hex;
    document.getElementById('secondaryColorInput').value = hex;

    const deepHex = adjustColorBrightness(hex, -20);
    document.getElementById('secondaryHoverPicker').value = deepHex;
    document.getElementById('secondaryHoverInput').value = deepHex;
  }
  updateLivePreview();
  highlightActiveSwatch(hex);
}

function highlightActiveSwatch(hex) {
  document.querySelectorAll('.swatch-cell').forEach(cell => {
    cell.classList.remove('is-active-cell');
    if (cell.getAttribute('title') && cell.getAttribute('title').toLowerCase().includes(hex.toLowerCase())) {
      cell.classList.add('is-active-cell');
    }
  });
}

function adjustColorBrightness(hex, percent) {
  let num = parseInt(hex.replace('#', ''), 16);
  let r = (num >> 16) + Math.round(255 * (percent / 100));
  let g = ((num >> 8) & 0x00FF) + Math.round(255 * (percent / 100));
  let b = (num & 0x0000FF) + Math.round(255 * (percent / 100));

  r = Math.min(255, Math.max(0, r));
  g = Math.min(255, Math.max(0, g));
  b = Math.min(255, Math.max(0, b));

  return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
}

function setPalette(primary, deep, accent, accentDeep) {
  document.getElementById('primaryColorPicker').value = primary;
  document.getElementById('primaryColorInput').value = primary;
  document.getElementById('primaryHoverPicker').value = deep;
  document.getElementById('primaryHoverInput').value = deep;
  document.getElementById('secondaryColorPicker').value = accent;
  document.getElementById('secondaryColorInput').value = accent;
  document.getElementById('secondaryHoverPicker').value = accentDeep;
  document.getElementById('secondaryHoverInput').value = accentDeep;
  updateLivePreview();
  highlightActiveSwatch(primary);
}

function syncColor(pickerId, inputId) {
  document.getElementById(inputId).value = document.getElementById(pickerId).value;
  updateLivePreview();
}

function syncInput(inputId, pickerId) {
  let val = document.getElementById(inputId).value.trim();
  if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
    document.getElementById(pickerId).value = val;
    updateLivePreview();
  }
}

function updateLivePreview() {
  const p = document.getElementById('primaryColorInput').value;
  const s = document.getElementById('secondaryColorInput').value;
  const banner = document.getElementById('themePreviewBanner');
  const btn = document.getElementById('themePreviewBtn');

  if (banner) {
    banner.style.background = p;
    banner.style.borderBottomColor = s;
  }
  if (btn) {
    btn.style.background = s;
  }

  const dot1 = document.getElementById('headerPrimaryDot');
  const dot2 = document.getElementById('headerAccentDot');
  if (dot1) dot1.style.background = p;
  if (dot2) dot2.style.background = s;
}

/* -------------------------------------------------------------------------
   ADMISSION FORM MANDATORY FIELDS MANAGER LOGIC
   ------------------------------------------------------------------------- */
function syncAfmUi() {
  const checkboxes = document.querySelectorAll('.admission-field-chk');
  let activeCount = 0;

  checkboxes.forEach(chk => {
    const row = chk.closest('label.afm-row');
    if (!row) return;
    const reqBadge = row.querySelector('.afm-badge-req');
    const optBadge = row.querySelector('.afm-badge-opt');

    if (chk.checked) {
      activeCount++;
      row.classList.add('is-active');
      if (reqBadge) reqBadge.style.setProperty('display', 'inline-block', 'important');
      if (optBadge) optBadge.style.setProperty('display', 'none', 'important');
    } else {
      row.classList.remove('is-active');
      if (reqBadge) reqBadge.style.setProperty('display', 'none', 'important');
      if (optBadge) optBadge.style.setProperty('display', 'inline-block', 'important');
    }
  });

  // Update card counters
  document.querySelectorAll('.afm-card').forEach(card => {
    const cardChks = card.querySelectorAll('.admission-field-chk');
    const cardActive = Array.from(cardChks).filter(c => c.checked).length;
    const badge = card.querySelector('.afm-card-badge');
    if (badge) {
      badge.textContent = `${cardActive}/${cardChks.length}`;
      if (cardActive > 0) {
        badge.style.background = '#dcfce7';
        badge.style.color = '#15803d';
      } else {
        badge.style.background = '#e2e8f0';
        badge.style.color = '#475569';
      }
    }
  });

  // Update total counters
  const totalCounter = document.getElementById('afmTotalCounter');
  if (totalCounter) {
    totalCounter.textContent = `📊 ${activeCount} of ${checkboxes.length} fields mandatory`;
  }
  const headerBadge = document.getElementById('headerAfmBadge');
  if (headerBadge) {
    headerBadge.textContent = `${activeCount}/${checkboxes.length} Mandatory`;
    if (activeCount > 0) {
      headerBadge.style.background = '#dcfce7';
      headerBadge.style.color = '#15803d';
    } else {
      headerBadge.style.background = '#e2e8f0';
      headerBadge.style.color = '#475569';
    }
  }
}

function toggleAfmCard(btn, checked) {
  const card = btn.closest('.afm-card');
  if (!card) return;
  card.querySelectorAll('.admission-field-chk').forEach(chk => {
    chk.checked = checked;
  });
  syncAfmUi();
}

function filterAfmFields(query) {
  const q = (query || '').toLowerCase().trim();
  document.querySelectorAll('.afm-card').forEach(card => {
    let cardHasVisible = false;
    card.querySelectorAll('label.afm-row').forEach(row => {
      const text = row.innerText.toLowerCase();
      if (!q || text.includes(q)) {
        row.style.setProperty('display', 'flex', 'important');
        cardHasVisible = true;
      } else {
        row.style.setProperty('display', 'none', 'important');
      }
    });
    if (!q || cardHasVisible) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

function setAllAdmissionFields(checked) {
  document.querySelectorAll('.admission-field-chk').forEach(chk => {
    chk.checked = checked;
  });
  syncAfmUi();
}

function setDefaultAdmissionFields() {
  const defaults = [
    'trainee_name_bn',
    'dob',
    'ident_type',
    'ident_no',
    'sex',
    'pwd',
    'contact_no',
    'photo',
    'signature_photo',
    'ident_doc',
    'perm_division',
    'perm_district',
    'perm_upazilla',
    'perm_address',
    'perm_area_type'
  ];
  document.querySelectorAll('.admission-field-chk').forEach(chk => {
    chk.checked = defaults.includes(chk.value);
  });
  syncAfmUi();
}

/* -------------------------------------------------------------------------
   INITIALIZATION
   ------------------------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', function() {
  syncAfmUi();
  document.querySelectorAll('.admission-field-chk').forEach(chk => {
    chk.addEventListener('change', syncAfmUi);
  });

  // If URL has a specific hash (e.g. #theme-section or #sec-mandatory-fields), open that item
  if (window.location.hash) {
    const targetItem = document.querySelector(window.location.hash);
    if (targetItem && targetItem.classList.contains('accordion-item')) {
      targetItem.classList.add('is-open');
      targetItem.scrollIntoView({ behavior: 'smooth' });
    }
  }

  // Highlight initial swatch
  const curP = document.getElementById('primaryColorInput');
  if (curP) highlightActiveSwatch(curP.value);
});
</script>
@endsection
