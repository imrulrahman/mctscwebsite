@extends('admin.layout')
@section('title', 'My Profile & Security · MCTSC Admin')
@section('crumb', 'Profile & Password')

@section('content')
<div class="page-head">
  <div>
    <h1>My Profile & Security</h1>
    <p>Manage personal contact details and update your administrative password</p>
  </div>
  <div>
    <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:12px;padding:6px 12px;font-weight:600;">
      Role: {{ $user->role?->name ?? ($user->is_admin ? 'System Admin' : 'Staff') }}
    </span>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(420px, 1fr));gap:24px;max-width:1200px;">
  
  {{-- Card 1: Profile Information --}}
  <div class="panel form-panel" style="padding:22px;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #edf0ee;">
      @if($user->avatarUrl())
        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" style="width:44px;height:44px;border-radius:50%;object-fit:cover;border:2px solid #0f5132;flex-shrink:0;">
      @else
        <div style="width:44px;height:44px;border-radius:50%;background:#0f5132;color:#fff;display:grid;place-items:center;font-weight:700;font-size:16px;flex-shrink:0;">
          {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
      @endif
      <div>
        <h2 style="font-size:16px;margin:0;color:#18362b;">Profile Details</h2>
        <small style="color:var(--muted);">Personal photo, name, email address and phone</small>
      </div>
    </div>

    @if(session('profile_success'))
      <div class="alert success">{{ session('profile_success') }}</div>
    @endif

    <form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
      @csrf
      @method('put')

      {{-- Avatar Upload Section --}}
      <div style="display:flex;align-items:center;gap:16px;padding:14px;background:#f8faf9;border:1px solid #e2ebe6;border-radius:6px;margin-bottom:4px;">
        <div style="flex-shrink:0;">
          <img id="profileAvatarPreview" 
               src="{{ $user->avatarUrl() ?: asset('assets/default-avatar.svg') }}" 
               alt="Avatar Preview" 
               style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid #0f5132;background:#e7f3ed;display:block;"
               onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'64\' height=\'64\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%230f5132\' stroke-width=\'2\'><circle cx=\'12\' cy=\'7\' r=\'4\'/><path d=\'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2\'/></svg>'">
        </div>
        <div style="flex:1;min-width:0;">
          <label style="margin:0;display:grid;gap:5px;">
            <span style="font-size:12px;font-weight:600;color:#2c3e37;">Profile Photo (Optional)</span>
            <input type="file" name="avatar" id="profileAvatarInput" accept="image/png,image/jpeg,image/webp" style="padding:6px 8px;font-size:12px;background:#fff;">
            <small class="subline" style="margin-top:2px;">Upload JPG, PNG or WEBP (Max 2MB).</small>
            @error('avatar')<div class="field-error">{{ $message }}</div>@enderror
          </label>
          @if($user->avatar)
            <label style="display:inline-flex;align-items:center;gap:6px;margin-top:8px;font-size:12px;color:#a63e32;cursor:pointer;font-weight:500;">
              <input type="checkbox" name="remove_avatar" value="1" style="width:auto;margin:0;">
              Remove current photo
            </label>
          @endif
        </div>
      </div>

      <label>
        <span>Full Name <b style="color:#a30;font-weight:bold;">*</b></span>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name')<div class="field-error">{{ $message }}</div>@enderror
      </label>

      <label>
        <span>Email Address <b style="color:#a30;font-weight:bold;">*</b></span>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        <small class="subline">Used to authenticate into the dashboard.</small>
        @error('email')<div class="field-error">{{ $message }}</div>@enderror
      </label>

      <label>
        <span>Contact Phone</span>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+88018XXXXXXXX">
        @error('phone')<div class="field-error">{{ $message }}</div>@enderror
      </label>

      <div style="background:#f8faf9;border:1px solid #e7eee9;border-radius:6px;padding:12px;margin-top:6px;">
        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
          <span style="color:#64748b;">Account Status:</span>
          <strong style="color:#15803d;">{{ strtoupper($user->status) }}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:12px;">
          <span style="color:#64748b;">Joined On:</span>
          <span style="color:#334155;">{{ $user->created_at->format('d M, Y') }}</span>
        </div>
      </div>

      <div class="form-actions" style="margin-top:16px;">
        <button type="submit" class="btn gold" style="padding:10px 22px;font-size:13px;font-weight:600;">Save Profile</button>
      </div>
    </form>
  </div>

  {{-- Card 2: Password Change --}}
  <div class="panel form-panel" style="padding:22px;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #edf0ee;">
      <div style="width:42px;height:42px;border-radius:50%;background:#b45309;color:#fff;display:grid;place-items:center;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
      </div>
      <div>
        <h2 style="font-size:16px;margin:0;color:#18362b;">Change Password</h2>
        <small style="color:var(--muted);">Update and secure your account credentials</small>
      </div>
    </div>

    @if(session('password_success'))
      <div class="alert success">{{ session('password_success') }}</div>
    @endif

    <form method="post" action="{{ route('admin.profile.password') }}">
      @csrf
      @method('put')

      <label>
        <span>Current Password <b style="color:#a30;font-weight:bold;">*</b></span>
        <input type="password" name="current_password" required placeholder="Enter current active password">
        @error('current_password')<div class="field-error">{{ $message }}</div>@enderror
      </label>

      <label>
        <span>New Password <b style="color:#a30;font-weight:bold;">*</b></span>
        <input type="password" name="password" required placeholder="At least 6 characters">
        <small class="subline">Choose a secure password of at least 6 characters.</small>
        @error('password')<div class="field-error">{{ $message }}</div>@enderror
      </label>

      <label>
        <span>Confirm New Password <b style="color:#a30;font-weight:bold;">*</b></span>
        <input type="password" name="password_confirmation" required placeholder="Repeat new password">
      </label>

      <div class="form-actions" style="margin-top:16px;">
        <button type="submit" class="btn gold" style="padding:10px 22px;font-size:13px;font-weight:600;">Update Password</button>
      </div>
    </form>
  </div>
</div>

<script>
  const profileAvatarInput = document.getElementById('profileAvatarInput');
  const profileAvatarPreview = document.getElementById('profileAvatarPreview');
  if (profileAvatarInput && profileAvatarPreview) {
    profileAvatarInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
          profileAvatarPreview.src = evt.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }
</script>

{{-- Permissions Overview Section --}}
<div class="panel" style="margin-top:20px;max-width:1200px;">
  <div class="panel-head">
    <h2>Your Active Permissions</h2>
    <span style="font-size:12px;color:var(--muted);">Authorized access rights on this website</span>
  </div>
  <div style="padding:16px 22px;">
    @php
      $allKeys = \App\Services\PermissionManager::allKeys();
      $allowedKeys = array_filter($allKeys, fn($k) => $user->hasPermission($k));
    @endphp

    @if($user->isSystemAdmin())
      <div style="background:#e0f2fe;color:#0369a1;padding:12px 16px;border-radius:6px;font-weight:600;font-size:13px;">
        ★ Your account is configured as System Administrator. You possess complete, unrestricted access across all system modules and settings.
      </div>
    @else
      <div style="display:flex;flex-wrap:wrap;gap:8px;">
        @forelse($allowedKeys as $key)
          <span style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:5px 11px;border-radius:5px;font-size:12px;font-weight:500;display:inline-flex;align-items:center;gap:6px;">
            <span>✓</span> {{ \App\Services\PermissionManager::getLabel($key) }}
          </span>
        @empty
          <span style="color:#64748b;font-size:13px;">No specific permissions are currently assigned.</span>
        @endforelse
      </div>
    @endif
  </div>
</div>
@endsection
