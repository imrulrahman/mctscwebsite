@extends('admin.layout')
@section('title', $user->exists ? 'Edit User: ' . $user->name : 'Create User · MCTSC Admin')
@section('crumb', $user->exists ? 'Edit User' : 'Create User')

@section('content')
<div class="page-head">
  <div>
    <h1>{{ $user->exists ? 'Edit User: ' . $user->name : 'Create New User Account' }}</h1>
    <p>Configure user personal credentials, role assignment and account status</p>
  </div>
  <div>
    <a class="btn light" href="{{ route('admin.users.index') }}">← Back to Users</a>
  </div>
</div>

<div class="panel form-panel" style="max-width:850px;">
  <form method="post" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" enctype="multipart/form-data">
    @csrf
    @if($user->exists)
      @method('put')
    @endif

    {{-- Avatar Upload Section --}}
    <div style="display:flex;align-items:center;gap:20px;padding:16px;background:#f8faf9;border:1px solid #e2ebe6;border-radius:6px;margin-bottom:8px;">
      <div style="position:relative;flex-shrink:0;">
        <img id="avatarPreview" 
             src="{{ $user->avatarUrl() ?: asset('assets/default-avatar.svg') }}" 
             alt="Avatar Preview" 
             style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid #0f5132;background:#e7f3ed;display:block;"
             onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'72\' height=\'72\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%230f5132\' stroke-width=\'2\'><circle cx=\'12\' cy=\'7\' r=\'4\'/><path d=\'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2\'/></svg>'">
      </div>
      <div style="flex:1;min-width:0;">
        <label style="margin:0;display:grid;gap:5px;">
          <span style="font-size:12px;font-weight:600;color:#2c3e37;">Profile Photo (Optional)</span>
          <input type="file" name="avatar" id="avatarInput" accept="image/png,image/jpeg,image/webp" style="padding:6px 8px;font-size:12px;background:#fff;">
          <small class="subline" style="margin-top:2px;">Upload JPG, PNG or WEBP (Max 2MB). Recommended square photo.</small>
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

    <div class="field-grid">
      <label>
        <span>Full Name <b style="color:#a30;font-weight:bold;">*</b></span>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="Enter full name">
        @error('name')<div class="field-error">{{ $message }}</div>@enderror
      </label>

      <label>
        <span>Email Address <b style="color:#a30;font-weight:bold;">*</b></span>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="e.g. staff@mctsc.edu.bd">
        <small class="subline">Used for signing into admin dashboard</small>
        @error('email')<div class="field-error">{{ $message }}</div>@enderror
      </label>
    </div>

    <div class="field-grid">
      <label>
        <span>Phone Number</span>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+88018XXXXXXXX">
        @error('phone')<div class="field-error">{{ $message }}</div>@enderror
      </label>

      <label>
        <span>Account Status <b style="color:#a30;font-weight:bold;">*</b></span>
        <select name="status" required>
          <option value="active" {{ old('status', $user->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status', $user->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')<div class="field-error">{{ $message }}</div>@enderror
      </label>
    </div>

    <label>
      <span>Assign Role <b style="color:#a30;font-weight:bold;">*</b></span>
      <select name="role_id" required style="font-size:14px;padding:11px;">
        <option value="">— Select a Role —</option>
        @foreach($roles as $role)
          <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
            {{ $role->name }}
          </option>
        @endforeach
      </select>
      <small class="subline">Roles provide baseline permissions. You can also customize individual overrides under 'Manage Permissions'.</small>
      @error('role_id')<div class="field-error">{{ $message }}</div>@enderror
    </label>

    <div style="background:#f9fbf9;border:1px solid #e2ebe6;border-radius:6px;padding:16px;margin-top:6px;">
      <label>
        <span>Password {!! $user->exists ? '' : '<b style="color:#a30;font-weight:bold;">*</b>' !!}</span>
        <input type="password" name="password" {{ $user->exists ? '' : 'required' }} placeholder="{{ $user->exists ? 'Leave blank to keep existing password' : 'At least 6 characters' }}">
        <small class="subline">
          {{ $user->exists ? 'Leave empty if you do not wish to alter the password.' : 'Define a secure password of at least 6 characters.' }}
        </small>
        @error('password')<div class="field-error">{{ $message }}</div>@enderror
      </label>
    </div>

    <div class="form-actions" style="margin-top:20px;">
      <button type="submit" class="btn gold" style="padding:11px 24px;font-size:13px;font-weight:600;">
        {{ $user->exists ? 'Save Changes' : 'Create User' }}
      </button>
      <a class="btn light" href="{{ route('admin.users.index') }}" style="font-size:13px;padding:9px 16px;">Cancel</a>
      @if($user->exists)
        <a class="btn light" href="{{ route('admin.users.permissions', $user) }}" style="margin-left:auto;color:#0284c7;font-size:13px;padding:9px 16px;">
          Manage Individual Permissions &rarr;
        </a>
      @endif
    </div>
  </form>
</div>

<script>
  const avatarInput = document.getElementById('avatarInput');
  const avatarPreview = document.getElementById('avatarPreview');
  if (avatarInput && avatarPreview) {
    avatarInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
          avatarPreview.src = evt.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }
</script>
@endsection
