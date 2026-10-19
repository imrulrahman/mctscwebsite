@extends('admin.layout')
@section('title', $role->exists ? 'Edit Role: ' . $role->name : 'Create Role · MCTSC Admin')
@section('crumb', $role->exists ? 'Edit Role' : 'Create Role')

@section('content')
<div class="page-head" style="margin-bottom:16px;">
  <div>
    <h1 style="font-size:24px;margin-bottom:3px;">{{ $role->exists ? 'Edit Role: ' . $role->name : 'Create New Role' }}</h1>
    <p style="font-size:13px;color:var(--muted);">Define role title, description and access permission checkboxes</p>
  </div>
  <div>
    <a class="btn light" href="{{ route('admin.roles.index') }}" style="font-size:12px;padding:7px 12px;">← Back to Roles</a>
  </div>
</div>

<div class="panel form-panel" style="max-width:1050px;padding:20px;">
  <form method="post" action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}">
    @csrf
    @if($role->exists)
      @method('put')
    @endif

    <div class="field-grid">
      <label>
        <span>Role Name <b style="color:#a30;font-weight:bold;">*</b></span>
        <input type="text" name="name" value="{{ old('name', $role->name) }}" required placeholder="e.g. Admission Officer, Content Manager">
        @error('name')<div class="field-error">{{ $message }}</div>@enderror
      </label>

      <label>
        <span>System Slug</span>
        <input type="text" name="slug" value="{{ old('slug', $role->slug) }}" placeholder="e.g. admission-officer" {{ $role->is_system ? 'readonly style=background:#f8faf9;cursor:not-allowed;' : '' }}>
        <small class="subline">System identifier (lowercase letters and hyphens)</small>
        @error('slug')<div class="field-error">{{ $message }}</div>@enderror
      </label>
    </div>

    <label style="margin-top:10px;">
      <span>Description</span>
      <input type="text" name="description" value="{{ old('description', $role->description) }}" placeholder="Brief summary of duties and responsibilities">
      @error('description')<div class="field-error">{{ $message }}</div>@enderror
    </label>

    <div style="margin-top:20px;padding-top:14px;border-top:1px solid #edf0ee;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
        <div>
          <h3 style="margin:0 0 2px;font-size:15px;color:#18362b;">Role Permissions Configuration</h3>
          <p style="margin:0;font-size:12px;color:var(--muted);">Check permissions that will be automatically granted to users assigned to this role.</p>
        </div>
        <div style="display:flex;gap:6px;">
          <button type="button" class="btn light" style="font-size:11px;padding:5px 10px;" onclick="toggleAllPermissions(true)">Select All</button>
          <button type="button" class="btn light" style="font-size:11px;padding:5px 10px;" onclick="toggleAllPermissions(false)">Deselect All</button>
        </div>
      </div>

      @if($role->slug === 'system-admin')
        <div class="alert" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;margin-bottom:14px;padding:8px 12px;font-size:13px;">
          <strong>Notice:</strong> This is the root 'System Admin' role. All permissions are permanently active by default.
        </div>
      @endif

      @php
        $currentRolePerms = old('permissions', $role->permissions ?? []);
      @endphp

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
                  $isChecked = in_array($permKey, $currentRolePerms, true) || $role->slug === 'system-admin';
                @endphp
                <label style="display:flex;align-items:center;gap:9px;padding:6px 9px;border-radius:4px;cursor:pointer;font-size:12.5px;color:#283832;transition:background 0.15s ease;" onmouseover="this.style.background='#eff5f2'" onmouseout="this.style.background='transparent'">
                  <input type="checkbox" name="permissions[]" value="{{ $permKey }}" class="perm-check perm-grp-{{ $groupKey }}" {{ $isChecked ? 'checked' : '' }} style="width:15px;height:15px;margin:0;accent-color:var(--green);cursor:pointer;">
                  <span style="font-weight:500;">{{ $permLabel }}</span>
                </label>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="form-actions" style="margin-top:20px;padding-top:14px;border-top:1px solid #edf0ee;">
      <button type="submit" class="btn gold" style="padding:10px 24px;font-size:13px;font-weight:600;">
        {{ $role->exists ? 'Update Role & Permissions' : 'Save New Role' }}
      </button>
      <a class="btn light" href="{{ route('admin.roles.index') }}" style="font-size:13px;padding:8px 16px;">Cancel</a>
    </div>
  </form>
</div>

<script>
  function toggleAllPermissions(checked) {
    document.querySelectorAll('.perm-check').forEach(function(el) {
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
