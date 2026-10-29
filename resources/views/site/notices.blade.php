@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
@extends('site.layout')

@section('title', $settings['notices_tab_title'] ?? ($isBn ? 'নোটিশ বোর্ড' : 'Notice Board'))
@section('page_title', $settings['notices_page_title'] ?? ($isBn ? 'ইনস্টিটিউট নোটিশ বোর্ড' : 'Official Notice Board'))
@section('page_subtitle', $settings['notices_page_subtitle'] ?? ($isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজের ভর্তি, ক্লাস ও পরীক্ষার যাবতীয় অফিশিয়াল নোটিশ।' : 'Official announcements, admission circulars, class routines, and exam schedules.'))

@section('content')
<section class="section" style="padding: 40px 0 60px 0;">
  <div class="container">
    <div style="background: #ffffff; border: 1px solid #d8e4dc; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
      <div style="background: #f8fafc; padding: 18px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 22px;">📌</span>
          <strong style="font-size: 16px; color: var(--green); font-family: var(--serif);">
            {{ $isBn ? 'প্রকাশিত সকল নোটিশ তালিকা' : 'All Published Notices' }}
          </strong>
        </div>
        <div style="font-size: 13px; color: #64748b;">
          {{ $isBn ? 'সর্বমোট নোটিশ:' : 'Total Notices:' }} <strong>{{ $notices->total() }}</strong>
        </div>
      </div>

      <div style="divide-y: 1px solid #eef2f0;">
        @forelse($notices as $notice)
          <div style="padding: 22px 24px; border-bottom: 1px solid #edf2f0; display: flex; align-items: flex-start; gap: 20px; transition: background 0.15s ease;" onmouseover="this.style.background='#fbfdfc'" onmouseout="this.style.background='transparent'">
            <!-- DATE BADGE -->
            <div style="background: #e8f5ed; color: var(--green); border: 1px solid #cce2d6; border-radius: 8px; padding: 8px 14px; text-align: center; min-width: 65px; flex-shrink: 0;">
              <span style="display: block; font-size: 20px; font-weight: 800; line-height: 1;">
                {{ $notice->created_at ? $notice->created_at->format('d') : '--' }}
              </span>
              <span style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-top: 3px; color: var(--gold);">
                {{ $notice->created_at ? $notice->created_at->format('M Y') : '' }}
              </span>
            </div>

            <!-- NOTICE CONTENT -->
            <div style="flex: 1; min-width: 0;">
              <h3 data-live-edit data-live-content-id="{{ $notice->id }}" data-live-content-field="title" data-live-type="text" data-live-title="Notice: {{ $notice->title }}" data-live-location="Notices" style="font-size: 17px; font-weight: 700; color: #1e3d35; margin: 0 0 6px 0; font-family: var(--serif); line-height: 1.35;">
                {{ $notice->title }}
              </h3>
              @if(!empty($notice->excerpt))
                <p data-live-edit data-live-content-id="{{ $notice->id }}" data-live-content-field="excerpt" data-live-type="textarea" data-live-title="Notice Summary: {{ $notice->title }}" data-live-location="Notices" style="font-size: 14px; color: #475569; margin: 0 0 10px 0; line-height: 1.5;">
                  {{ $notice->excerpt }}
                </p>
              @endif
              @if(!empty($notice->body))
                <div data-live-edit data-live-content-id="{{ $notice->id }}" data-live-content-field="body" data-live-type="rich" data-live-title="Notice Details: {{ $notice->title }}" data-live-location="Notices" style="font-size: 13.5px; color: #334155; line-height: 1.6; margin-bottom: 10px;">
                  {!! nl2br(e($notice->body)) !!}
                </div>
              @endif

              <div style="display: flex; align-items: center; gap: 14px; font-size: 12px; color: #94a3b8;">
                <span>📅 {{ $notice->created_at ? $notice->created_at->format('h:i A, d F Y') : '' }}</span>
                @if($notice->image)
                  <a href="{{ storage_asset($notice->image) }}" target="_blank" class="button button-gold" style="font-size: 11.5px; padding: 4px 10px; border-radius: 4px; text-decoration: none;">
                    📎 {{ $isBn ? 'সংযুক্ত ফাইল / ছবি দেখুন' : 'View Attachment' }}
                  </a>
                @endif
              </div>
            </div>
          </div>
        @empty
          <div style="padding: 60px 20px; text-align: center; color: #64748b;">
            <p style="font-size: 16px; margin: 0;">{{ $isBn ? 'বর্তমানে কোনো নোটিশ প্রকাশিত হয়নি।' : 'No official notices currently published.' }}</p>
          </div>
        @endforelse
      </div>

      <!-- PAGINATION -->
      @if($notices->hasPages())
        <div style="padding: 18px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
          {{ $notices->links() }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection
