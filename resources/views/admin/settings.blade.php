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

  @media (max-width: 1024px) {
    .afm-grid-cols {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 768px) {
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
          <div class="field-grid">
            <label>
              Eyebrow Badge Text
              <input name="hero_eyebrow" value="{{ old('hero_eyebrow', $settings->get('hero_eyebrow', 'SKILLS FOR A BRIGHTER FUTURE')) }}" placeholder="e.g. SKILLS FOR A BRIGHTER FUTURE">
            </label>
            <label>
              Hero Action Button Text
              <input name="hero_btn_text" value="{{ old('hero_btn_text', $settings->get('hero_btn_text', 'Explore Our Courses')) }}" placeholder="e.g. Explore Our Courses">
            </label>
          </div>

          <div class="field-grid" style="margin-top: 12px;">
            <label>
              Main Hero Heading
              <input name="hero_title" value="{{ old('hero_title', $settings->get('hero_title', 'Practical Skills. Better Opportunities.')) }}" placeholder="e.g. Practical Skills. Better Opportunities.">
            </label>
            <label>
              Hero Subtitle Description
              <input name="hero_subtitle" value="{{ old('hero_subtitle', $settings->get('hero_subtitle', 'Skills-focused technical training for a confident future.')) }}" placeholder="e.g. Skills-focused technical training for a confident future.">
            </label>
          </div>

          <div style="background:#f8faf9;border:1px solid #e2ebe6;padding:14px;border-radius:6px;margin-top:14px;">
            <label>
              <strong>Hero Banner Background Graphic</strong> — (Formats: JPG, PNG, WEBP; recommended 1920x800)
              <input type="file" name="hero_banner" accept="image/*">
              @php
                $currentHero = storage_asset($settings->get('hero_banner'), 'assets/mc Banner.jpg');
              @endphp
              <div style="margin-top: 10px; display: flex; align-items: center; gap: 14px;">
                <img src="{{ $currentHero }}" alt="Hero Banner" style="height: 70px; max-width: 180px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                <span style="font-size: 12px; color: #555;">Current Active Hero Banner Preview</span>
              </div>
            </label>
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

          <div style="background:#f8faf9;border:1px solid #e2ebe6;padding:14px;border-radius:6px;margin-top:14px;">
            <label>
              <strong>Campus Building Photograph</strong>
              <input type="file" name="about_image" accept="image/*">
              @php
                $currentAbout = storage_asset($settings->get('about_image'), 'assets/MC Building Photo.jpg');
              @endphp
              <div style="margin-top: 10px; display: flex; align-items: center; gap: 14px;">
                <img src="{{ $currentAbout }}" alt="Campus" style="height: 70px; max-width: 180px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                <span style="font-size: 12px; color: #555;">Current Campus Building Preview</span>
              </div>
            </label>
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
              <h2 class="accordion-title">6. Campus Facilities & Practical Workshops</h2>
              <p class="accordion-subtitle">Showcase 4 laboratory facilities, equipment workshops, and hands-on training setups.</p>
            </div>
          </div>
          <div class="accordion-meta">
            <span class="accordion-chevron">▼</span>
          </div>
        </div>
        <div class="accordion-body">
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
