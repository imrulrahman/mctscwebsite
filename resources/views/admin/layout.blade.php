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
  <script>
    (function() {
      try {
        if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
          document.documentElement.classList.add('sidebar-is-collapsed');
        }
      } catch(e) {}
    })();
  </script>
  <style>
    :root {
      --green: {{ $admPrimary }};
      --green-deep: {{ $admHover }};
      --gold: {{ $admAccent }};
      --gold-deep: {{ $admAccentHover }};
    }
    .sidebar {
      background: {{ $admHover }};
      height: 100vh;
      max-height: 100vh;
      overflow-y: auto;
      overflow-x: hidden;
      scrollbar-width: thin;
      scrollbar-color: rgba(255, 255, 255, 0.25) transparent;
      display: flex;
      flex-direction: column;
      box-sizing: border-box;
      transition: width 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sidebar::-webkit-scrollbar {
      width: 4px;
    }
    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.25);
      border-radius: 4px;
    }
    .admin-main {
      transition: margin-left 0.22s cubic-bezier(0.4, 0, 0.2, 1), width 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn.gold { background: {{ $admAccent }}; }
    .btn.gold:hover { background: {{ $admAccentHover }}; }
    .sidebar nav a:hover, .sidebar nav a.active { border-left-color: {{ $admAccent }}; }
    .sidebar nav {
      display: grid;
      gap: 3px;
      flex: 1 0 auto;
    }
    .sidebar nav a {
      display: flex;
      align-items: center;
      gap: 11px;
      padding: 9px 14px;
      color: #e4efea;
      border-radius: 6px;
      font-weight: 600;
      font-size: 13px;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .side-footer {
      margin: 16px 14px 10px;
      padding-top: 12px;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
      flex-shrink: 0;
    }
    .sidebar nav a span.nav-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 22px;
      height: 22px;
      flex-shrink: 0;
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
      transition: opacity 0.2s ease;
    }
    .top-actions svg {
      vertical-align: middle;
      margin-right: 4px;
    }
    .sidebar-toggle-btn {
      background: #f4f8f6;
      border: 1px solid #d7e4dd;
      border-radius: 6px;
      width: 34px;
      height: 34px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #27473a;
      padding: 0;
      transition: all 0.15s ease;
      flex-shrink: 0;
    }
    .sidebar-toggle-btn:hover {
      background: #e7f2ec;
      border-color: var(--green);
      color: var(--green);
    }

    /* Collapsed Sidebar Rules */
    html.sidebar-is-collapsed .sidebar {
      width: 68px !important;
    }
    html.sidebar-is-collapsed .admin-main {
      margin-left: 68px !important;
      width: calc(100% - 68px) !important;
    }
    html.sidebar-is-collapsed .side-brand {
      padding: 12px 6px !important;
      justify-content: center !important;
      height: 64px !important;
    }
    html.sidebar-is-collapsed .side-brand img {
      width: 38px !important;
      height: 38px !important;
      margin: 0 !important;
    }
    html.sidebar-is-collapsed .side-brand strong {
      display: none !important;
    }
    html.sidebar-is-collapsed .sidebar nav a {
      justify-content: center !important;
      padding: 10px 0 !important;
      gap: 0 !important;
      border-left: 3px solid transparent !important;
    }
    html.sidebar-is-collapsed .sidebar nav a.active {
      border-left-color: var(--gold) !important;
      background: rgba(255, 255, 255, 0.15) !important;
    }
    html.sidebar-is-collapsed .sidebar nav a .nav-text {
      display: none !important;
    }
    html.sidebar-is-collapsed .side-footer .side-user-meta {
      display: none !important;
    }
    html.sidebar-is-collapsed .side-footer {
      margin: 12px 6px 10px !important;
      padding-top: 10px !important;
      text-align: center !important;
    }
    html.sidebar-is-collapsed .side-footer a {
      justify-content: center !important;
      padding: 0 !important;
    }

    @media (max-width: 700px) {
      .sidebar-toggle-btn {
        display: none;
      }
    }
  </style>
</head>
<body>
<div class="admin-shell">
  <aside class="sidebar">
    <a class="side-brand" href="{{ route('admin.dashboard') }}" title="MCTSC Dashboard">
      @php
        $adminLogo = !empty($settings) && $settings->get('site_logo') 
          ? storage_asset($settings->get('site_logo'), 'assets/MC Logo.png')
          : asset('assets/MC Logo.png');
      @endphp
      <img src="{{ $adminLogo }}" alt="Logo">
      <strong>{{ !empty($settings) && $settings->get('institution_name') ? $settings->get('institution_name') : 'MCTSC Admin' }}</strong>
    </a>
    <nav>
      <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" title="Dashboard">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        </span>
        <span class="nav-text">Dashboard</span>
      </a>

      @can('courses.manage')
        <a class="{{ request()->is('admin/content/courses*') ? 'active' : '' }}" href="{{ route('admin.content.index','courses') }}" title="Courses">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
          </span>
          <span class="nav-text">Courses</span>
        </a>
      @endcan

      @can('projects.manage')
        <a class="{{ request()->is('admin/content/projects*') ? 'active' : '' }}" href="{{ route('admin.content.index','projects') }}" title="Projects">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
          </span>
          <span class="nav-text">Projects</span>
        </a>
      @endcan

      @can('notices.manage')
        <a class="{{ request()->is('admin/content/notices*') ? 'active' : '' }}" href="{{ route('admin.content.index','notices') }}" title="Notices">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
          </span>
          <span class="nav-text">Notices</span>
        </a>
      @endcan

      @can('admissions.view')
        <a class="{{ request()->routeIs('admin.applications*') ? 'active' : '' }}" href="{{ route('admin.applications') }}" title="Admissions">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
          </span>
          <span class="nav-text">Admissions</span>
        </a>
      @endcan

      @can('gallery.manage')
        <a class="{{ request()->is('admin/content/gallery*') ? 'active' : '' }}" href="{{ route('admin.content.index','gallery') }}" title="Gallery">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
          </span>
          <span class="nav-text">Gallery</span>
        </a>
      @endcan

      @can('events.manage')
        <a class="{{ request()->is('admin/content/events*') ? 'active' : '' }}" href="{{ route('admin.content.index','events') }}" title="Events">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
          </span>
          <span class="nav-text">Events</span>
        </a>
      @endcan

      @can('messages.view')
        <a class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}" href="{{ route('admin.messages') }}" title="Messages">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          </span>
          <span class="nav-text">Messages</span>
        </a>
      @endcan

      @can('users.view')
        <a class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}" title="User Management">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          </span>
          <span class="nav-text">Users</span>
        </a>
      @endcan

      @can('roles.manage')
        <a class="{{ request()->routeIs('admin.roles*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}" title="Role Management">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
          </span>
          <span class="nav-text">Roles</span>
        </a>
      @endcan

      @can('settings.manage')
        <a class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings') }}" title="Site Settings">
          <span class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
          </span>
          <span class="nav-text">Site Settings</span>
        </a>
      @endcan

      <a class="{{ request()->routeIs('admin.profile*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}" title="My Profile & Security">
        <span class="nav-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="7" r="4"></circle><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path></svg>
        </span>
        <span class="nav-text">My Profile</span>
      </a>
    </nav>
    <div class="side-footer">
      <a href="{{ route('admin.profile.edit') }}" style="color:#fff;text-decoration:none;display:flex;align-items:center;gap:10px;" title="My Profile & Security">
        @if(auth()->user()?->avatarUrl())
          <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:1px solid rgba(255,255,255,0.4);flex-shrink:0;">
        @else
          <div style="width:34px;height:34px;border-radius:50%;background:#ffffff22;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0;">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
          </div>
        @endif
        <div class="side-user-meta" style="min-width:0;overflow:hidden;">
          <p style="margin:0;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name ?? 'Administrator' }}</p>
          <small style="color:#c3d9d0;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->role?->name ?? (auth()->user()?->isSystemAdmin() ? 'System Admin' : 'Staff') }}</small>
        </div>
      </a>
    </div>
  </aside>

  <main class="admin-main">
    <header class="topbar">
      <div style="display:flex;align-items:center;gap:12px;">
        <button id="sidebarToggle" class="sidebar-toggle-btn" type="button" title="Toggle Sidebar (Expand/Collapse)" aria-label="Toggle Sidebar">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <div class="crumb">Admin <span>/</span> @yield('crumb','Dashboard')</div>
      </div>
      <div class="top-actions">
        <a class="preview" href="{{ route('home') }}" target="_blank">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
          Preview Website
        </a>
        <a class="account" href="{{ route('admin.profile.edit') }}" title="Profile & Password" style="text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
          @if(auth()->user()?->avatarUrl())
            <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" style="width:26px;height:26px;border-radius:50%;object-fit:cover;border:1px solid #c9d8d1;">
          @else
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          @endif
          <span>{{ auth()->user()->name ?? 'Administrator' }}</span>
        </a>
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('sidebarToggle');
    if (toggleBtn) {
      toggleBtn.addEventListener('click', function() {
        document.documentElement.classList.toggle('sidebar-is-collapsed');
        var isCollapsed = document.documentElement.classList.contains('sidebar-is-collapsed');
        try {
          localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');
        } catch(e) {}
      });
    }
  });
</script>
</body>
</html>
