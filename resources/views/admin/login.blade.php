<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login · MCTSC</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-page">
  <form class="login-card" method="post" action="{{ route('admin.login.store') }}">
    @csrf
    @php
      $adminLogo = !empty($settings) && $settings->get('site_logo') 
        ? (str_starts_with($settings->get('site_logo'), 'assets/') ? asset($settings->get('site_logo')) : \Illuminate\Support\Facades\Storage::url($settings->get('site_logo'))) 
        : asset('assets/MC Logo.png');
    @endphp
    <img src="{{ $adminLogo }}" alt="Logo">
    <p class="eyebrow">MCTSC CONTENT MANAGEMENT</p>
    <h1>Admin Sign In</h1>
    <p class="muted">Sign in to manage the college website.</p>
    
    @if($errors->any())
      <div class="alert danger">{{ $errors->first() }}</div>
    @endif
    @if(session('error'))
      <div class="alert danger">{{ session('error') }}</div>
    @endif

    <label>
      Email address
      <input type="email" name="email" required autocomplete="username" value="{{ old('email') }}" placeholder="admin@mctsc.edu.bd">
    </label>
    <label>
      Password
      <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
    </label>
    <label class="check">
      <input type="checkbox" name="remember" value="1"> Remember me
    </label>
    <button class="btn gold full" type="submit">Sign in</button>
    <a class="back-link" href="{{ route('home') }}">← Back to website</a>
  </form>
</body>
</html>
