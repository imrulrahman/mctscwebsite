<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Dashboard') · MCTSC Admin</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  @php
    $admPrimary = \App\Models\SiteSetting::get('primary_color', '#0f5132');
    $admHover = \App\Models\SiteSetting::get('primary_hover', '#093722');
    $admAccent = \App\Models\SiteSetting::get('secondary_color', '#c59b27');
    $admAccentHover = \App\Models\SiteSetting::get('secondary_hover', '#a37d18');
  @endphp
  <style>
    :root {
      --green: {{ $admPrimary }};
      --green-deep: {{ $admHover }};
      --gold: {{ $admAccent }};
      --gold-deep: {{ $admAccentHover }};
    }
    .sidebar { background: {{ $admHover }}; }
    .btn.gold { background: {{ $admAccent }}; }
    .btn.gold:hover { background: {{ $admAccentHover }}; }
    .sidebar nav a:hover, .sidebar nav a.active { border-left-color: {{ $admAccent }}; }
    .sidebar nav a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 16px;
      color: #e4efea;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .sidebar nav a span {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 22px;
      height: 22px;
    }
    .sidebar nav a svg {
      width: 19px;
      height: 19px;
      stroke-width: 2;
    }
    .side-brand strong {
      text-align: center;
      font-size: 14px;
      line-height: 1.25;
      max-width: 180px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .top-actions svg {
      vertical-align: middle;
      margin-right: 4px;
    }
  </style>
</head>
<body>
<div class="admin-shell">
  <aside class="sidebar">
    <a class="side-brand" href="{{ route('admin.dashboard') }}">
      @php
        $adminLogo = !empty($settings) && $settings->get('site_logo') 
          ? storage_asset($settings->get('site_logo'), 'assets/MC Logo.png')
          : asset('assets/MC Logo.png');
      @endphp
      <img src="{{ $adminLogo }}" alt="Logo">
      <strong>{{ !empty($settings) && $settings->get('institution_name') ? $settings->get('institution_name') : 'MCTSC Admin' }}</strong>
    </a>
    <nav>
      <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        </span>
        Dashboard
      </a>
      <a class="{{ request()->is('admin/content/courses*') ? 'active' : '' }}" href="{{ route('admin.content.index','courses') }}">
        <span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
        </span>
        Courses
      </a>
      <a class="{{ request()->is('admin/content/projects*') ? 'active' : '' }}" href="{{ route('admin.content.index','projects') }}">
        <span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
        </span>
        Projects
      </a>
      <a class="{{ request()->is('admin/content/notices*') ? 'active' : '' }}" href="{{ route('admin.content.index','notices') }}">
        <span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        </span>
        Notices
      </a>
      <a class="{{ request()->routeIs('admin.applications*') ? 'active' : '' }}" href="{{ route('admin.applications') }}">
        <span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
        </span>
        Admissions
      </a>
      <a class="{{ request()->is('admin/content/gallery*') ? 'active' : '' }}" href="{{ route('admin.content.index','gallery') }}">
        <span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        </span>
        Gallery
      </a>
      <a class="{{ request()->is('admin/content/events*') ? 'active' : '' }}" href="{{ route('admin.content.index','events') }}">
        <span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </span>
        Events
      </a>
      <a class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}" href="{{ route('admin.messages') }}">
        <span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </span>
        Messages
      </a>
      <a class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
        <span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        </span>
        Site Settings
      </a>
    </nav>
    <div class="side-footer">
      <p>{{ auth()->user()->name ?? 'Administrator' }}</p>
      <small>Institution Website</small>
    </div>
  </aside>

  <main class="admin-main">
    <header class="topbar">
      <div class="crumb">Admin <span>/</span> @yield('crumb','Dashboard')</div>
      <div class="top-actions">
        <a class="preview" href="{{ route('home') }}" target="_blank">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
          Preview Website
        </a>
        <span class="account">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          {{ auth()->user()->name ?? 'Administrator' }}
        </span>
        <form method="post" action="{{ route('admin.logout') }}">
          @csrf
          <button class="logout">Sign out</button>
        </form>
      </div>
    </header>

    <section class="page-area">
      @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert danger">{{ session('error') }}</div>
      @endif
      @yield('content')
    </section>
  </main>
</div>
</body>
</html>
