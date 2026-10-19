@extends('admin.layout')
@section('title', 'Manage Permissions: ' . $user->name . ' · MCTSC Admin')
@section('crumb', 'Manage Permissions')

@section('content')
<div class="page-head" style="margin-bottom:16px;">
  <div>
    <h1 style="font-size:24px;margin-bottom:3px;">Manage User Permissions</h1>
    <p style="font-size:13px;color:var(--muted);">Configure customized access rights for <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
  </div>
  <div style="display:flex;gap:10px;">
    <form method="post" action="{{ route('admin.users.permissions.reset', $user) }}" onsubmit="return confirm('Reset all custom overrides back to default role permissions?');">
      @csrf
      <button type="submit" class="btn light" style="font-size:12px;padding:7px 12px;color:#92400e;background:#fef3c7;border-color:#fde68a;">
        ↺ Reset to Role Defaults
      </button>
    </form>
    <a class="btn light" href="{{ route('admin.users.index') }}" style="font-size:12px;padding:7px 12px;">← Back to Users</a>
  </div>
</div>

{{-- Compact User Summary Banner --}}
<div class="panel" style="margin-bottom:16px;padding:12px 18px;background:#fff;border-left:4px solid var(--green);">
  <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:12px;">
    <div style="display:flex;align-items:center;gap:10px;">
      <div style="width:34px;height:34px;border-radius:50%;background:#e7f3ed;color:var(--green);display:grid;place-items:center;font-weight:700;font-size:14px;">
        {{ strtoupper(substr($user->name, 0, 1)) }}
      </div>
      <div>
        <strong style="font-size:14px;color:#18362b;">{{ $user->name }}</strong>
        <span style="font-size:12px;color:#6b7c75;margin-left:6px;">{{ $user->email }}</span>
      </div>
      <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:600;margin-left:8px;">
        Role: {{ $user->role?->name ?? ($user->is_admin ? 'System Admin' : 'No Role') }}
      </span>
      @if($user->status === 'active')
        <span class="badge" style="background:#dcfce7;color:#15803d;font-weight:600;">Active</span>
      @else
        <span class="badge" style="background:#fee2e2;color:#991b1b;font-weight:600;">Inactive</span>
      @endif
    </div>

    <div style="display:flex;align-items:center;gap:8px;">
      <button type="button" class="btn light" style="font-size:11px;padding:5px 10px;" onclick="toggleAllPermissions(true)">Select All</button>
      <button type="button" class="btn light" style="font-size:11px;padding:5px 10px;" onclick="toggleAllPermissions(false)">Deselect All</button>
    </div>
  </div>
</div>

<div class="panel" style="padding:18px 20px;">
  <form method="post" action="{{ route('admin.users.permissions.update', $user) }}">
    @csrf
    @method('put')

    {{-- Clean 2-Column Dynamic Height Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(380px, 1fr));gap:14px;align-items:start;">
      @foreach($permissionGroups as $groupKey => $group)
        <div style="background:#fbfdfc;border:1px solid #e1e8e4;border-radius:6px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,0.02);">
          <div style="background:#f2f6f4;padding:8px 12px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e1e8e4;">
            <strong style="font-size:13px;color:#16382b;display:flex;align-items:center;gap:6px;">
              <span style="width:7px;height:7px;background:var(--green);border-radius:50%;display:inline-block;"></span>
              {{ $group['title'] }}
            </strong>
            <div style="font-size:11px;">
              <button type="button" style="background:none;border:none;color:var(--green);font-size:11px;cursor:pointer;font-weight:600;padding:0;" onclick="toggleGroup('{{ $groupKey }}', true)">All</button>
              <span style="color:#cbd5e1;margin:0 3px;">|</span>
              <button type="button" style="background:none;border:none;color:#64748b;font-size:11px;cursor:pointer;padding:0;" onclick="toggleGroup('{{ $groupKey }}', false)">None</button>
            </div>
          </div>

          <div style="padding:8px 10px;display:grid;gap:4px;">
            @foreach($group['permissions'] as $permKey => $permLabel)
              @php
                $hasPerm = $user->hasPermission($permKey);
              @endphp

              <label style="display:flex;align-items:center;gap:9px;padding:6px 9px;border-radius:4px;cursor:pointer;font-size:12.5px;color:#283832;transition:background 0.15s ease;" onmouseover="this.style.background='#eff5f2'" onmouseout="this.style.background='transparent'">
                <input type="checkbox" name="permissions[]" value="{{ $permKey }}" class="perm-chk perm-grp-{{ $groupKey }}" {{ $hasPerm ? 'checked' : '' }} style="width:15px;height:15px;margin:0;accent-color:var(--green);cursor:pointer;">
                <span style="font-weight:500;">{{ $permLabel }}</span>
              </label>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>

    {{-- Clean Footer Action Bar --}}
    <div style="margin-top:18px;padding-top:14px;border-top:1px solid #edf2ee;display:flex;align-items:center;justify-content:space-between;">
      <div style="display:flex;gap:10px;align-items:center;">
        <button type="submit" class="btn gold" style="padding:9px 22px;font-size:13px;font-weight:600;">
          Save Permissions
        </button>
        <a class="btn light" href="{{ route('admin.users.index') }}" style="font-size:13px;padding:8px 16px;">Cancel</a>
      </div>
      <small style="color:var(--muted);font-size:12px;">Checked permissions will be active for this user.</small>
    </div>
  </form>
</div>

<script>
  function toggleAllPermissions(checked) {
    document.querySelectorAll('.perm-chk').forEach(function(el) {
      el.checked = checked;
    });
  }

  function toggleGroup(groupKey, checked) {
    document.querySelectorAll('.perm-grp-' + groupKey).forEach(function(el) {
      el.checked = checked;
    });
  }
</script>
@endsection
