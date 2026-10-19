@extends('admin.layout')
@section('title', 'User Management · MCTSC Admin')
@section('crumb', 'User Management')

@section('content')
<div class="page-head">
  <div>
    <h1>User Management</h1>
    <p>Create, edit, manage administrative accounts, assign roles and configure individual permissions</p>
  </div>
  <div>
    <a class="btn gold" href="{{ route('admin.users.create') }}">+ Create New User</a>
  </div>
</div>

<div class="panel" style="margin-bottom:20px;padding:16px;">
  <form method="get" action="{{ route('admin.users.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
    <div style="flex:1;min-width:220px;">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, email, or phone..." class="filter-input" style="padding:9px 12px;">
    </div>
    <div style="min-width:180px;">
      <select name="role_id" class="filter-input" style="padding:9px 12px;">
        <option value="">— All Roles —</option>
        @foreach($roles as $role)
          <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
      </select>
    </div>
    <div style="min-width:140px;">
      <select name="status" class="filter-input" style="padding:9px 12px;">
        <option value="">— All Statuses —</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>
    <div style="display:flex;gap:8px;">
      <button type="submit" class="btn light">Filter</button>
      @if(request()->hasAny(['q', 'role_id', 'status']))
        <a href="{{ route('admin.users.index') }}" class="btn light" style="color:#888;">Reset</a>
      @endif
    </div>
  </form>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Registered Users ({{ $users->total() }})</h2>
    <span style="font-size:12px;color:var(--muted);">Use Manage Permissions to customize access for specific users</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>User & Email</th>
          <th>Phone</th>
          <th>Assigned Role</th>
          <th>Status</th>
          <th>Custom Access</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                @if($user->avatarUrl())
                  <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:1px solid #d7e6de;flex-shrink:0;">
                @else
                  <div style="width:36px;height:36px;border-radius:50%;background:#e8f4ee;color:#0e523f;display:grid;place-items:center;font-weight:700;font-size:14px;flex-shrink:0;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                  </div>
                @endif
                <div>
                  <strong style="font-size:14px;color:#18362b;display:block;">
                    {{ $user->name }}
                    @if(auth()->id() === $user->id)
                      <span style="font-size:10px;background:#0f5132;color:#fff;padding:1px 6px;border-radius:10px;margin-left:4px;">You</span>
                    @endif
                  </strong>
                  <span style="font-size:12px;color:#6b7c75;">{{ $user->email }}</span>
                </div>
              </div>
            </td>
            <td>
              <span style="color:#41534c;">{{ $user->phone ?: '—' }}</span>
            </td>
            <td>
              @if($user->role)
                <span class="badge" style="{{ $user->role->slug === 'system-admin' ? 'background:#e0f2fe;color:#0369a1;' : 'background:#f0fdf4;color:#166534;' }}font-weight:600;">
                  {{ $user->role->name }}
                </span>
              @elseif($user->is_admin)
                <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:600;">System Admin</span>
              @else
                <span class="badge" style="background:#fef2f2;color:#991b1b;">No Role</span>
              @endif
            </td>
            <td>
              @if($user->status === 'active')
                <span class="badge" style="background:#dcfce7;color:#15803d;font-weight:600;">● Active</span>
              @else
                <span class="badge" style="background:#fee2e2;color:#991b1b;font-weight:600;">● Inactive</span>
              @endif
            </td>
            <td>
              @if($user->isSystemAdmin())
                <span style="font-size:11px;color:#0284c7;font-weight:600;">System Admin (Full Access)</span>
              @elseif(!empty($user->custom_permissions) && count($user->custom_permissions) > 0)
                <span class="badge" style="background:#fef3c7;color:#92400e;font-weight:600;">
                  Custom Overrides
                </span>
              @else
                <span style="font-size:11px;color:#6b7c75;">Role Defaults</span>
              @endif
            </td>
            <td style="text-align:right;">
              <div class="actions" style="justify-content:flex-end;gap:8px;">
                <a class="edit-link" href="{{ route('admin.users.permissions', $user) }}" style="color:#0284c7;background:#f0f9ff;padding:4px 8px;border-radius:4px;font-size:11.5px;" title="Manage Permissions">
                  Manage Permissions
                </a>
                <a class="edit-link" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                @if(auth()->id() !== $user->id)
                  <form method="post" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('delete')
                    <button class="delete-link">Delete</button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="empty">No users found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
    <div class="pagination">
      {{ $users->links() }}
    </div>
  @endif
</div>
@endsection
