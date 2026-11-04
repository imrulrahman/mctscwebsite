@extends('admin.layout')
@section('title', 'Activity & Security Logs · MCTSC Admin')
@section('crumb', 'Activity Logs')

@section('content')
<div class="page-head">
  <div>
    <h1>Activity & Security Logs (ইউজারদের একটিভিটি ও নিরাপত্তা লগ)</h1>
    <p>Track administrator and staff activities, logins, content changes, banner updates, and system operations</p>
  </div>
  <div style="display:flex;gap:10px;align-items:center;">
    <a class="btn light" href="{{ route('admin.users.index') }}">← Back to Users</a>
  </div>
</div>

<div class="panel" style="margin-bottom:20px;padding:16px;">
  <form method="get" action="{{ route('admin.activity-logs.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
    <div style="flex:1;min-width:200px;">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search description, user name, IP..." class="filter-input" style="padding:9px 12px;width:100%;">
    </div>

    <div style="min-width:180px;">
      <select name="user_id" class="filter-input" style="padding:9px 12px;width:100%;">
        <option value="">— All Users / Administrators —</option>
        @foreach($users as $u)
          <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
        @endforeach
      </select>
    </div>

    <div style="min-width:150px;">
      <select name="module" class="filter-input" style="padding:9px 12px;width:100%;">
        <option value="">— All Modules —</option>
        @foreach($modules as $m)
          <option value="{{ $m }}" {{ request('module') == $m ? 'selected' : '' }}>{{ $m }}</option>
        @endforeach
      </select>
    </div>

    <div style="min-width:140px;">
      <select name="action" class="filter-input" style="padding:9px 12px;width:100%;">
        <option value="">— All Action Types —</option>
        @foreach($actions as $a)
          <option value="{{ $a }}" {{ request('action') == $a ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $a)) }}</option>
        @endforeach
      </select>
    </div>

    <div style="min-width:140px;">
      <input type="date" name="date" value="{{ request('date') }}" class="filter-input" style="padding:8px 10px;width:100%;" title="Filter by date">
    </div>

    <div style="display:flex;gap:8px;">
      <button type="submit" class="btn light">Filter</button>
      @if(request()->hasAny(['q', 'user_id', 'module', 'action', 'date']))
        <a href="{{ route('admin.activity-logs.index') }}" class="btn light" style="color:#888;">Reset</a>
      @endif
    </div>
  </form>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Audit & Activity Trail ({{ $logs->total() }} Records)</h2>
    <span style="font-size:12px;color:var(--muted);">All actions are permanently recorded for system security and accountability</span>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Timestamp & Date</th>
          <th>User / Performed By</th>
          <th>Action Type</th>
          <th>Module</th>
          <th>Activity Description</th>
          <th>IP & Client</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
          <tr>
            <td style="white-space:nowrap;">
              <strong style="color:#1e293b;font-size:13px;display:block;">{{ $log->created_at->format('d/m/Y') }}</strong>
              <small style="color:#64748b;font-size:11.5px;">{{ $log->created_at->format('h:i:s A') }}</small>
              <div style="color:#94a3b8;font-size:10.5px;">{{ $log->created_at->diffForHumans() }}</div>
            </td>
            <td>
              <div style="display:flex;align-items:center;gap:8px;">
                @if($log->user && $log->user->avatarUrl())
                  <img src="{{ $log->user->avatarUrl() }}" alt="" style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:1px solid #d7e6de;flex-shrink:0;">
                @else
                  <div style="width:28px;height:28px;border-radius:50%;background:#e2e8f0;color:#334155;display:grid;place-items:center;font-weight:700;font-size:12px;flex-shrink:0;">
                    {{ strtoupper(substr($log->user_name ?: 'U', 0, 1)) }}
                  </div>
                @endif
                <div>
                  <strong style="font-size:13px;color:#18362b;display:block;">
                    {{ $log->user_name ?: 'System / Guest' }}
                  </strong>
                  <span style="font-size:11px;color:#64748b;">{{ $log->user_role ?: 'User' }}</span>
                </div>
              </div>
            </td>
            <td>
              @php
                $badgeStyle = match($log->action) {
                  'login' => 'background:#dcfce7;color:#15803d;',
                  'logout' => 'background:#f1f5f9;color:#475569;',
                  'failed_login' => 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                  'change_banner' => 'background:#ede9fe;color:#6d28d9;border:1px solid #ddd6fe;',
                  'create' => 'background:#e0f2fe;color:#0369a1;',
                  'update', 'edit' => 'background:#fef3c7;color:#92400e;',
                  'delete' => 'background:#fee2e2;color:#991b1b;',
                  default => 'background:#f1f5f9;color:#334155;'
                };
              @endphp
              <span class="badge" style="{{ $badgeStyle }}font-weight:600;font-size:11.5px;padding:3px 8px;border-radius:12px;">
                @if($log->action === 'failed_login')
                  ⚠️ Failed Login
                @elseif($log->action === 'change_banner')
                  🖼️ Banner Change
                @else
                  {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                @endif
              </span>
            </td>
            <td>
              <span style="font-size:12.5px;color:#334155;font-weight:600;background:#f8fafc;padding:3px 8px;border-radius:4px;border:1px solid #e2e8f0;">
                {{ $log->module ?: 'General' }}
              </span>
            </td>
            <td>
              <div style="font-size:13px;color:#1e293b;line-height:1.45;max-width:420px;">
                {{ $log->description }}
              </div>
              @if(!empty($log->properties))
                <small style="color:#64748b;font-size:11px;display:block;margin-top:2px;font-family:monospace;">
                  {{ json_encode($log->properties, JSON_UNESCAPED_UNICODE) }}
                </small>
              @endif
            </td>
            <td>
              <div style="font-size:12px;color:#475569;">
                <code>{{ $log->ip_address ?: '127.0.0.1' }}</code>
              </div>
              @if($log->user_agent)
                <div style="font-size:10.5px;color:#94a3b8;max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $log->user_agent }}">
                  {{ Str::limit($log->user_agent, 30) }}
                </div>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="empty" style="text-align:center;padding:30px;">কোন একটিভিটি রেকর্ড পাওয়া যায়নি।</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($logs->hasPages())
    <div class="pagination" style="padding:16px;">
      {{ $logs->links() }}
    </div>
  @endif
</div>
@endsection
