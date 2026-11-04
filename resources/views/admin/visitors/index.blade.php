@extends('admin.layout')
@section('title', 'Visitor Analytics · MCTSC Admin')
@section('crumb', 'Visitor Analytics')

@section('content')
<div class="page-head">
  <div>
    <h1>Visitor Analytics (ওয়েবসাইট ভিজিটর ও দেশভিত্তিক পরিসংখ্যান)</h1>
    <p>Real-time website traffic metrics, unique daily visitors, country breakdown, and visit activity logs</p>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap;">
    <a href="{{ route('admin.visitors.index', ['range' => 'today']) }}" class="btn {{ $range === 'today' ? 'gold' : 'light' }}" style="font-size:12.5px;padding:6px 14px;">Today (আজ)</a>
    <a href="{{ route('admin.visitors.index', ['range' => 'yesterday']) }}" class="btn {{ $range === 'yesterday' ? 'gold' : 'light' }}" style="font-size:12.5px;padding:6px 14px;">Yesterday (গতকাল)</a>
    <a href="{{ route('admin.visitors.index', ['range' => '7days']) }}" class="btn {{ $range === '7days' ? 'gold' : 'light' }}" style="font-size:12.5px;padding:6px 14px;">Last 7 Days</a>
    <a href="{{ route('admin.visitors.index', ['range' => '30days']) }}" class="btn {{ $range === '30days' ? 'gold' : 'light' }}" style="font-size:12.5px;padding:6px 14px;">Last 30 Days</a>
    <a href="{{ route('admin.visitors.index', ['range' => 'all']) }}" class="btn {{ $range === 'all' ? 'gold' : 'light' }}" style="font-size:12.5px;padding:6px 14px;">All Time (সর্বমোট)</a>
  </div>
</div>

{{-- Top Analytics Cards --}}
<div class="stats-grid" style="margin-bottom:24px;">
  <div class="stat-card" style="border-left:4px solid #10b981;">
    <i style="color:#10b981;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
    </i>
    <div>
      <span>Today's Total Visits</span>
      <b>{{ number_format($todayTotal) }}</b>
      <small style="color:#059669;font-weight:700;font-size:11.5px;">{{ number_format($todayUnique) }} Unique Visitors</small>
    </div>
  </div>

  <div class="stat-card" style="border-left:4px solid #0284c7;">
    <i style="color:#0284c7;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
    </i>
    <div>
      <span>Yesterday's Visits</span>
      <b>{{ number_format($yesterdayTotal) }}</b>
      <small style="color:#0284c7;font-weight:600;font-size:11.5px;">{{ number_format($yesterdayUnique) }} Unique</small>
    </div>
  </div>

  <div class="stat-card" style="border-left:4px solid #8b5cf6;">
    <i style="color:#8b5cf6;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
    </i>
    <div>
      <span>{{ ucfirst(str_replace('_', ' ', $range)) }} Visits</span>
      <b>{{ number_format($filteredTotal) }}</b>
      <small style="color:#6d28d9;font-weight:600;font-size:11.5px;">{{ number_format($filteredUnique) }} Unique Visitors</small>
    </div>
  </div>

  <div class="stat-card" style="border-left:4px solid #d97706;">
    <i style="color:#d97706;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
    </i>
    <div>
      <span>All-Time Total Traffic</span>
      <b>{{ number_format($allTimeTotal) }}</b>
      <small style="color:#b45309;font-weight:600;font-size:11.5px;">{{ number_format($allTimeUnique) }} Unique</small>
    </div>
  </div>
</div>

<div class="dash-grid" style="margin-bottom:24px;">
  {{-- Country Breakdown --}}
  <section class="panel">
    <div class="panel-head">
      <div>
        <h2>🌍 Visitor Countries (দেশভিত্তিক ভিজিটর সংখ্যা)</h2>
        <span style="font-size:12px;color:var(--muted);">Filter Period: {{ ucfirst(str_replace('_', ' ', $range)) }}</span>
      </div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Country (দেশ)</th>
            <th>Total Visits</th>
            <th>Unique Visitors</th>
            <th>Traffic Share</th>
          </tr>
        </thead>
        <tbody>
          @php
            $grandTotalPeriod = $filteredTotal > 0 ? $filteredTotal : 1;
          @endphp
          @forelse($countryStats as $c)
            @php
              $percent = round(($c->total_visits / $grandTotalPeriod) * 100, 1);
            @endphp
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:8px;font-weight:600;font-size:13.5px;color:#18362b;">
                  <span style="font-size:20px;line-height:1;">{{ $c->flag }}</span>
                  <span>{{ $c->country_name }}</span>
                  <small style="color:#64748b;font-size:11px;font-weight:normal;">({{ $c->country_code }})</small>
                </div>
              </td>
              <td><strong>{{ number_format($c->total_visits) }}</strong></td>
              <td><span style="color:#059669;font-weight:600;">{{ number_format($c->unique_visits) }}</span></td>
              <td style="min-width:140px;">
                <div style="display:flex;align-items:center;gap:8px;">
                  <div style="flex:1;background:#e2e8f0;height:7px;border-radius:4px;overflow:hidden;">
                    <div style="background:#10b981;height:100%;width:{{ min(100, max(5, $percent)) }}%;border-radius:4px;"></div>
                  </div>
                  <span style="font-size:11.5px;color:#475569;font-weight:600;min-width:38px;">{{ $percent }}%</span>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="empty">এই সময়ের জন্য কোন ভিজিটর ডেটা পাওয়া যায়নি।</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  {{-- Devices & Browsers --}}
  <section class="panel">
    <div class="panel-head">
      <div>
        <h2>📱 Devices & Browsers</h2>
        <span style="font-size:12px;color:var(--muted);">Visitor technology environment</span>
      </div>
    </div>
    <div style="padding:16px;">
      <h3 style="font-size:13px;text-transform:uppercase;color:#64748b;letter-spacing:0.5px;margin-bottom:12px;">Device Types</h3>
      <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px;">
        @forelse($deviceStats as $d)
          @php
            $dPercent = $filteredTotal > 0 ? round(($d->total / $filteredTotal) * 100, 1) : 0;
            $dIcon = match($d->device_type) {
              'Mobile' => '📱',
              'Tablet' => '📟',
              default => '💻'
            };
          @endphp
          <div>
            <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:600;color:#1e293b;margin-bottom:4px;">
              <span>{{ $dIcon }} {{ $d->device_type }}</span>
              <span>{{ number_format($d->total) }} ({{ $dPercent }}%)</span>
            </div>
            <div style="background:#e2e8f0;height:6px;border-radius:3px;overflow:hidden;">
              <div style="background:#0284c7;height:100%;width:{{ $dPercent }}%;"></div>
            </div>
          </div>
        @empty
          <p style="font-size:12px;color:#94a3b8;">No device data yet.</p>
        @endforelse
      </div>

      <h3 style="font-size:13px;text-transform:uppercase;color:#64748b;letter-spacing:0.5px;margin-bottom:12px;">Top Browsers</h3>
      <div style="display:flex;flex-direction:column;gap:10px;">
        @forelse($browserStats as $b)
          @php
            $bPercent = $filteredTotal > 0 ? round(($b->total / $filteredTotal) * 100, 1) : 0;
          @endphp
          <div>
            <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:600;color:#1e293b;margin-bottom:4px;">
              <span>🌐 {{ $b->browser }}</span>
              <span>{{ number_format($b->total) }} ({{ $bPercent }}%)</span>
            </div>
            <div style="background:#e2e8f0;height:6px;border-radius:3px;overflow:hidden;">
              <div style="background:#8b5cf6;height:100%;width:{{ $bPercent }}%;"></div>
            </div>
          </div>
        @empty
          <p style="font-size:12px;color:#94a3b8;">No browser data yet.</p>
        @endforelse
      </div>
    </div>
  </section>
</div>

{{-- Search & Detailed Log Table --}}
<div class="panel" style="margin-bottom:20px;padding:16px;">
  <form method="get" action="{{ route('admin.visitors.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
    <input type="hidden" name="range" value="{{ $range }}">
    <div style="flex:1;min-width:200px;">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by IP, country, city, URL..." class="filter-input" style="padding:9px 12px;width:100%;">
    </div>

    <div style="min-width:180px;">
      <select name="country" class="filter-input" style="padding:9px 12px;width:100%;">
        <option value="">— All Countries —</option>
        @foreach($countryStats as $c)
          <option value="{{ $c->country_code }}" {{ request('country') == $c->country_code ? 'selected' : '' }}>{{ $c->flag }} {{ $c->country_name }}</option>
        @endforeach
      </select>
    </div>

    <div style="min-width:140px;">
      <select name="device" class="filter-input" style="padding:9px 12px;width:100%;">
        <option value="">— All Devices —</option>
        <option value="Desktop" {{ request('device') === 'Desktop' ? 'selected' : '' }}>Desktop</option>
        <option value="Mobile" {{ request('device') === 'Mobile' ? 'selected' : '' }}>Mobile</option>
        <option value="Tablet" {{ request('device') === 'Tablet' ? 'selected' : '' }}>Tablet</option>
      </select>
    </div>

    <div style="display:flex;gap:8px;">
      <button type="submit" class="btn light">Filter</button>
      @if(request()->hasAny(['q', 'country', 'device']))
        <a href="{{ route('admin.visitors.index', ['range' => $range]) }}" class="btn light" style="color:#888;">Reset</a>
      @endif
    </div>
  </form>
</div>

<div class="panel">
  <div class="panel-head">
    <h2>Detailed Visitor Logs ({{ $logs->total() }} Visits)</h2>
    <span style="font-size:12px;color:var(--muted);">Live chronological record of user hits</span>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Time & Date</th>
          <th>Country & Location</th>
          <th>IP Address</th>
          <th>Device & Browser</th>
          <th>Page Visited</th>
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
                <span style="font-size:22px;line-height:1;">{{ $log->getCountryFlagEmoji() }}</span>
                <div>
                  <strong style="font-size:13.5px;color:#18362b;display:block;">
                    {{ $log->country_name ?: 'Bangladesh' }}
                  </strong>
                  <span style="font-size:11px;color:#64748b;">
                    {{ $log->city ? $log->city . ', ' : '' }}{{ $log->country_code ?: 'BD' }}
                    @if($log->is_unique_today)
                      <span style="background:#dcfce7;color:#15803d;padding:1px 6px;border-radius:10px;font-size:10px;font-weight:700;margin-left:4px;">Unique Today</span>
                    @endif
                  </span>
                </div>
              </div>
            </td>
            <td>
              <code>{{ $log->ip_address }}</code>
            </td>
            <td>
              <div style="font-size:12.5px;color:#1e293b;font-weight:600;">
                {{ $log->device_type ?: 'Desktop' }} · {{ $log->browser ?: 'Browser' }}
              </div>
              <small style="color:#64748b;font-size:11px;">{{ $log->platform ?: 'OS' }}</small>
            </td>
            <td>
              <div style="max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12.5px;" title="{{ $log->page_url }}">
                <a href="{{ $log->page_url }}" target="_blank" style="color:#0284c7;text-decoration:none;">
                  {{ parse_url($log->page_url, PHP_URL_PATH) ?: '/' }}
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="empty" style="text-align:center;padding:30px;">কোন ভিজিটর রেকর্ড পাওয়া যায়নি।</td>
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
