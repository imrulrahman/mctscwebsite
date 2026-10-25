@extends('admin.layout')
@section('title', ($item->exists ? 'Edit ' : 'Add ') . rtrim($label, 's'))
@section('crumb', $label)

@section('content')
<div class="page-head">
  <div>
    <h1>{{ $item->exists ? 'Edit' : 'Add' }} {{ rtrim($label, 's') }}</h1>
    <p>Changes are reflected on the public website when published.</p>
  </div>
  <a class="btn light" href="{{ route('admin.content.index', $section) }}">← Back to {{ $label }}</a>
</div>

<section class="panel form-panel">
  <form method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.content.update', ['section' => $section, 'content' => $item]) : route('admin.content.store', $section) }}">
    @csrf
    @if($item->exists)
      @method('PUT')
    @endif

    <div class="field-grid">
      <label>
        {{ $section === 'partners' ? 'Partner Name / উন্নয়ন সহযোগীর নাম' : ($section === 'hero_slides' ? 'Slide Title / ব্যানারের নাম' : 'Title') }} <span>*</span>
        <input name="title" required maxlength="180" value="{{ old('title', $item->title) }}" placeholder="{{ $section === 'partners' ? 'e.g. BRAC, UNDP, ILO, ASSET Project' : ($section === 'hero_slides' ? 'e.g. Robotics & Automation Lab' : 'Enter title') }}">
        @error('title')
          <small class="field-error">{{ $message }}</small>
        @enderror
      </label>
      <label>
        {{ $section === 'partners' ? 'Affiliation / Program Subtitle (কর্মসূচি/অংশীদারিত্ব)' : ($section === 'hero_slides' ? 'Category / Tag (ট্যাগ/ক্যাটেগরি)' : 'Category / Type') }}
        <input name="category" maxlength="120" value="{{ old('category', $item->category) }}" placeholder="{{ $section === 'partners' ? 'e.g. POWER_kfw-BRAC, UPPR - UNDP, DTE / World Bank' : ($section === 'hero_slides' ? 'e.g. Campus Lab, Workshop' : 'e.g. Training Initiative, IT & Computer') }}">
      </label>
    </div>

    @if($section === 'projects')
      <div style="background:#eef7f2;border:1px solid #b8dfd7;padding:12px 16px;border-radius:6px;margin:10px 0 14px;">
        <label style="margin:0;">
          <strong style="color:#0f5132;font-size:13.5px;">Student Admission Fee / শিক্ষার্থী ভর্তি ফি (৳ BDT)</strong>
          <span style="font-size:12px;color:#475569;display:block;margin-top:2px;">
            এই প্রজেক্টের শিক্ষার্থীদের জন্য ভর্তির সময় কত টাকা ফি নির্ধারণ হবে? (যেমন: ECHO এর জন্য ৫০০, NHRDF/ASSET এর জন্য ০ টাকা)
          </span>
          <input type="number" step="1" min="0" name="admission_fee" value="{{ old('admission_fee', $item->admission_fee ?? 0) }}" style="font-size:15px;font-weight:bold;color:#0f5132;margin-top:6px;max-width:260px;" placeholder="0">
        </label>
      </div>
    @endif

    @if($section === 'partners')
      <label>
        Partner Website / Program Link (ওয়েবসাইট লিঙ্ক - ঐচ্ছিক)
        <input type="url" name="excerpt" maxlength="1000" value="{{ old('excerpt', $item->excerpt) }}" placeholder="https://www.brac.net (লোগোতে ক্লিক করলে এই ঠিকানায় যাবে)">
      </label>
    @elseif($section === 'hero_slides')
      <label>
        Slide Link / Target URL (ঐচ্ছিক - ক্লিকেবল লিংক যুক্ত করতে চাইলে)
        <input type="url" name="excerpt" maxlength="1000" value="{{ old('excerpt', $item->excerpt) }}" placeholder="https://mctsc.edu.bd/courses (ঐচ্ছিক)">
      </label>
    @else
      <label>
        Short Description / Excerpt
        <textarea name="excerpt" rows="3" maxlength="1000" placeholder="Brief summary for cards...">{{ old('excerpt', $item->excerpt) }}</textarea>
      </label>

      <label>
        Details / Full Body Content
        <textarea name="body" rows="6" placeholder="Full details, syllabus or info...">{{ old('body', $item->body) }}</textarea>
      </label>
    @endif

    @if(in_array($section, ['courses', 'gallery', 'projects', 'partners', 'hero_slides']))
      <div style="background:#f8faf9;border:1px solid #e2ebe6;padding:14px;border-radius:6px;margin:10px 0;">
        <label>
          <strong>{{ $section === 'partners' ? 'Partner Logo / পার্টনার লোগো' : ($section === 'hero_slides' ? 'Banner Image / ব্যানার ছবি' : ($section === 'projects' ? 'Project Logo / Image' : 'Featured Image')) }}</strong> (JPG, PNG, WEBP, SVG; up to 5 MB)
          <input type="file" name="image" accept="image/*,.svg">
          <small style="color:#0369a1;background:#e0f2fe;padding:3px 8px;border-radius:4px;font-size:11.5px;font-weight:600;display:inline-block;margin-top:4px;">
            📐 Recommended: {{ $section === 'partners' ? '240 × 80 px (Transparent PNG or SVG vector)' : ($section === 'hero_slides' ? '1920 × 720 px (1920x720 Aspect Ratio, High-res JPG/WEBP)' : ($section === 'gallery' ? '1200 × 800 px or 1920 × 1080 px (Landscape)' : ($section === 'projects' ? '300 × 300 px (Square PNG)' : '800 × 500 px (16:10 Ratio)'))) }}
          </small>
          @if($item->image)
            <div style="margin-top:10px;display:flex;align-items:center;gap:12px;">
              @php
                $imgSrc = storage_asset($item->image);
              @endphp
              <img src="{{ $imgSrc }}" alt="Current" style="height:55px;{{ $section === 'hero_slides' ? 'max-width:220px;object-fit:cover;' : 'max-width:140px;object-fit:contain;' }}border:1px solid #ccd;background:#fff;padding:4px 8px;border-radius:4px;">
              <small class="subline" style="color:#555;">Current: {{ basename($item->image) }}</small>
            </div>
          @endif
          @error('image')
            <small class="field-error">{{ $message }}</small>
          @enderror
        </label>
      </div>
    @endif

    @if(in_array($section, ['events', 'notices']))
      <div class="field-grid">
        <label>
          Start / Publish Date
          <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $item->starts_at?->format('Y-m-d\TH:i')) }}">
        </label>
        <label>
          End Date (Optional)
          <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $item->ends_at?->format('Y-m-d\TH:i')) }}">
        </label>
      </div>
    @endif

    <div class="field-grid">
      <label>
        Display Order
        <input type="number" min="0" max="9999" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
      </label>
      <label>
        Publication Status
        <select name="status">
          <option value="published" @selected(old('status', $item->status ?: 'published') === 'published')>Published</option>
          <option value="draft" @selected(old('status', $item->status) === 'draft')>Draft</option>
        </select>
      </label>
    </div>

    <div class="form-actions" style="margin-top:20px;">
      <button class="btn gold" type="submit">Save {{ rtrim($label, 's') }}</button>
      <a class="btn light" href="{{ route('admin.content.index', $section) }}">Cancel</a>
    </div>
  </form>
</section>
@endsection
