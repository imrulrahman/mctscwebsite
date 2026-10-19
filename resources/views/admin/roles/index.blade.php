@extends('admin.layout')
@section('title', 'Role Management · MCTSC Admin')
@section('crumb', 'Role Management')

@section('content')
<div class="page-head">
  <div>
    <h1>Role Management</h1>
    <p>Create, edit system roles and define access permissions for administrative accounts</p>
  </div>
  <div>
    <a class="btn gold" href="{{ route('admin.roles.create') }}">+ Create New Role</a>
  </div>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>All Roles ({{ $roles->count() }})</h2>
    <span style="font-size:12px;color:var(--muted);">System roles are protected and cannot be deleted</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Role Name</th>
          <th>Slug</th>
          <th>Description</th>
          <th>Type</th>
          <th>Assigned Users</th>
          <th>Permissions</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($roles as $role)
          <tr>
            <td>
              <strong style="font-size:14px;color:#18362b;">{{ $role->name }}</strong>
            </td>
            <td>
              <code style="background:#f1f5f3;padding:2px 6px;border-radius:4px;font-size:12px;color:#0b5948;">{{ $role->slug }}</code>
            </td>
            <td style="max-width:280px;color:#556b62;">
              {{ $role->description ?: '—' }}
            </td>
            <td>
              @if($role->is_system)
                <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:600;">System Role</span>
              @else
                <span class="badge" style="background:#fef3c7;color:#92400e;font-weight:600;">Custom Role</span>
              @endif
            </td>
            <td>
              <span style="display:inline-flex;align-items:center;gap:6px;background:#f3f6f4;padding:3px 10px;border-radius:20px;font-weight:600;font-size:12px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                {{ $role->users_count }} users
              </span>
            </td>
            <td>
              @if($role->slug === 'system-admin')
                <span class="badge" style="background:#dcfce7;color:#15803d;font-weight:600;">Full Access (All Permissions)</span>
              @else
                <span class="badge" style="background:#e7f3ed;color:#165b44;font-weight:600;">{{ count($role->permissions ?? []) }} Permissions</span>
              @endif
            </td>
            <td style="text-align:right;">
              <div class="actions" style="justify-content:flex-end;gap:8px;">
                <a class="edit-link" href="{{ route('admin.roles.edit', $role) }}">Edit & Permissions</a>
                @if(!$role->is_system)
                  <form method="post" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Are you sure you want to delete this role?');">
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
            <td colspan="7" class="empty">No roles found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
