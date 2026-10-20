@extends('admin.layout')
@section('title', 'Site & Institution Settings')
@section('crumb', 'Site Settings')

@section('content')
<div class="page-head">
  <div>
    <h1>Site & Institution Settings</h1>
    <p>Manage all website elements, logos, images, slogans, contact information, and facilities.</p>
  </div>
</div>

<section class="panel form-panel" style="max-width: 1050px;">
  <form method="post" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PUT')

    <!-- 1. IDENTITY & LOGO -->
    <div style="border-bottom: 2px solid #e2ebe6; padding-bottom: 16px; margin-bottom: 20px;">
      <h2 style="font-size: 18px; color: var(--green); margin: 0 0 6px;">1. প্রাতিষ্ঠানিক পরিচয় ও লোগো (Identity & Logo)</h2>
      <p style="color: var(--muted); margin: 0 0 15px; font-size: 13px;">ওয়েবসাইটের হেডার ও ফুটারসহ সব জায়গায় এই নাম ও লোগো প্রদর্শিত হবে।</p>
      
      <div class="field-grid">
        <label>
          প্রতিষ্ঠানের নাম (Institution Name)
          <input name="institution_name" value="{{ old('institution_name', $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School and College')) }}">
        </label>
        <label>
          ট্যাগলাইন (Tagline / Slogan)
          <input name="tagline" value="{{ old('tagline', $settings->get('tagline', 'Practical Skills · Better Opportunities')) }}">
        </label>
      </div>

      <div style="background:#f8faf9;border:1px solid #e2ebe6;padding:14px;border-radius:6px;margin-top:14px;">
        <label>
          <strong>প্রতিষ্ঠানের অফিশিয়াল লোগো (Institution Logo)</strong> — (PNG, JPG, SVG, WEBP; সর্বোচ্চ 5 MB)
          <input type="file" name="site_logo" accept="image/*">
          @php
            $currentLogo = storage_asset($settings->get('site_logo'), 'assets/MC Logo.png');
          @endphp
          <div style="margin-top: 10px; display: flex; align-items: center; gap: 14px;">
            <img src="{{ $currentLogo }}" alt="Current Logo" style="height: 60px; max-width: 150px; object-fit: contain; background: #fff; padding: 4px; border: 1px solid #ccc; border-radius: 4px;">
            <span style="font-size: 12px; color: #555;">বর্তমান লোগো প্রিভিউ</span>
          </div>
        </label>
      </div>
    </div>

    <!-- 2. THEME COLORS & PALETTES -->
    <div id="theme-section" style="border-bottom: 2px solid #e2ebe6; padding-bottom: 20px; margin-bottom: 22px; scroll-margin-top: 20px;">
      <h2 style="font-size: 18px; color: var(--green); margin: 0 0 6px;">2. সাইটের থিম কালার সেটিংস (Site Theme & Color Customization)</h2>
      <p style="color: var(--muted); margin: 0 0 15px; font-size: 13px;">
        কালার পিকার বা হেক্স কোড লিখে ইচ্ছামত সাইটের কালার পরিবর্তন করুন অথবা নিচের প্রস্তুতকৃত প্যালেটগুলো থেকে এক ক্লিকে পছন্দ করুন।
      </p>

      <!-- PRESET PALETTES -->
      <div style="background: #f8faf9; border: 1px solid #d8e6df; border-radius: 8px; padding: 14px 16px; margin-bottom: 16px;">
        <span style="font-size: 13px; font-weight: 700; color: #1e293b; display: block; margin-bottom: 10px;">
          🎨 এক ক্লিকে কালার প্যালেট বাছাই করুন (Preset Color Palettes):
        </span>
        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
          <button type="button" onclick="setPalette('#0f5132', '#093722', '#c59b27', '#a37d18')"
            style="display: flex; align-items: center; gap: 8px; padding: 7px 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #0f5132; display: inline-block;"></span>
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #c59b27; display: inline-block;"></span>
            Academic Green (ডিফল্ট)
          </button>

          <button type="button" onclick="setPalette('#1e3a8a', '#172554', '#f59e0b', '#d97706')"
            style="display: flex; align-items: center; gap: 8px; padding: 7px 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #1e3a8a; display: inline-block;"></span>
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
            Royal Navy Blue
          </button>

          <button type="button" onclick="setPalette('#881337', '#4c0519', '#d97706', '#b45309')"
            style="display: flex; align-items: center; gap: 8px; padding: 7px 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #881337; display: inline-block;"></span>
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #d97706; display: inline-block;"></span>
            Crimson Maroon
          </button>

          <button type="button" onclick="setPalette('#0f766e', '#115e59', '#f59e0b', '#d97706')"
            style="display: flex; align-items: center; gap: 8px; padding: 7px 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #0f766e; display: inline-block;"></span>
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
            Modern Teal
          </button>

          <button type="button" onclick="setPalette('#1f2937', '#111827', '#eab308', '#ca8a04')"
            style="display: flex; align-items: center; gap: 8px; padding: 7px 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #1f2937; display: inline-block;"></span>
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #eab308; display: inline-block;"></span>
            Dark Charcoal
          </button>

          <button type="button" onclick="setPalette('#581c87', '#3b0764', '#fbbf24', '#f59e0b')"
            style="display: flex; align-items: center; gap: 8px; padding: 7px 13px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 12px; font-weight: 600;">
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #581c87; display: inline-block;"></span>
            <span style="width: 14px; height: 14px; border-radius: 50%; background: #fbbf24; display: inline-block;"></span>
            Royal Purple
          </button>
        </div>
      </div>

      <!-- CUSTOM COLOR PICKERS -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px;">
        <!-- Primary Color -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
          <label style="font-size: 13px; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px;">
            প্রাথমিক ব্র‍্যান্ড কালার (Primary):
          </label>
          <div style="display: flex; align-items: center; gap: 8px;">
            <input type="color" id="primaryColorPicker" value="{{ old('primary_color', $settings->get('primary_color', '#0f5132')) }}"
              oninput="syncColor('primaryColorPicker', 'primaryColorInput')"
              style="width: 44px; height: 38px; border: none; cursor: pointer; border-radius: 6px; padding: 0; background: none;">
            <input type="text" name="primary_color" id="primaryColorInput" value="{{ old('primary_color', $settings->get('primary_color', '#0f5132')) }}"
              oninput="syncInput('primaryColorInput', 'primaryColorPicker')"
              style="flex: 1; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: monospace; font-size: 14px; font-weight: 600;">
          </div>
          <small style="color: #64748b; font-size: 11px; display: block; margin-top: 4px;">হেডার, বাটন ও প্রধান টাইটেল কালার</small>
        </div>

        <!-- Primary Hover Color -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
          <label style="font-size: 13px; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px;">
            ডিপ / হোভার কালার (Primary Deep):
          </label>
          <div style="display: flex; align-items: center; gap: 8px;">
            <input type="color" id="primaryHoverPicker" value="{{ old('primary_hover', $settings->get('primary_hover', '#093722')) }}"
              oninput="syncColor('primaryHoverPicker', 'primaryHoverInput')"
              style="width: 44px; height: 38px; border: none; cursor: pointer; border-radius: 6px; padding: 0; background: none;">
            <input type="text" name="primary_hover" id="primaryHoverInput" value="{{ old('primary_hover', $settings->get('primary_hover', '#093722')) }}"
              oninput="syncInput('primaryHoverInput', 'primaryHoverPicker')"
              style="flex: 1; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: monospace; font-size: 14px; font-weight: 600;">
          </div>
          <small style="color: #64748b; font-size: 11px; display: block; margin-top: 4px;">বাটন ও কার্ডের হোভার এবং ফুটার কালার</small>
        </div>

        <!-- Secondary Accent Color -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
          <label style="font-size: 13px; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px;">
            অ্যাকসেন্ট / গোল্ড কালার (Accent):
          </label>
          <div style="display: flex; align-items: center; gap: 8px;">
            <input type="color" id="secondaryColorPicker" value="{{ old('secondary_color', $settings->get('secondary_color', '#c59b27')) }}"
              oninput="syncColor('secondaryColorPicker', 'secondaryColorInput')"
              style="width: 44px; height: 38px; border: none; cursor: pointer; border-radius: 6px; padding: 0; background: none;">
            <input type="text" name="secondary_color" id="secondaryColorInput" value="{{ old('secondary_color', $settings->get('secondary_color', '#c59b27')) }}"
              oninput="syncInput('secondaryColorInput', 'secondaryColorPicker')"
              style="flex: 1; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: monospace; font-size: 14px; font-weight: 600;">
          </div>
          <small style="color: #64748b; font-size: 11px; display: block; margin-top: 4px;">গোল্ড লাইন, হাইলাইট ও সেকেন্ডারি বাটন</small>
        </div>

        <!-- Secondary Hover Color -->
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">
          <label style="font-size: 13px; font-weight: 700; color: #1e293b; display: block; margin-bottom: 6px;">
            অ্যাকসেন্ট হোভার (Accent Deep):
          </label>
          <div style="display: flex; align-items: center; gap: 8px;">
            <input type="color" id="secondaryHoverPicker" value="{{ old('secondary_hover', $settings->get('secondary_hover', '#a37d18')) }}"
              oninput="syncColor('secondaryHoverPicker', 'secondaryHoverInput')"
              style="width: 44px; height: 38px; border: none; cursor: pointer; border-radius: 6px; padding: 0; background: none;">
            <input type="text" name="secondary_hover" id="secondaryHoverInput" value="{{ old('secondary_hover', $settings->get('secondary_hover', '#a37d18')) }}"
              oninput="syncInput('secondaryHoverInput', 'secondaryHoverPicker')"
              style="flex: 1; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: monospace; font-size: 14px; font-weight: 600;">
          </div>
          <small style="color: #64748b; font-size: 11px; display: block; margin-top: 4px;">সেকেন্ডারি বাটনের হোভার কালার</small>
        </div>
      </div>

      <!-- LIVE PREVIEW BOX -->
      <div style="margin-top: 14px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
          <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">লাইভ কালার প্রিভিউ (Live Color Preview):</span>
          <span style="font-size: 12px; color: #047857; font-weight: 600;">💡 কালার পছন্দ করে "Apply Now" এ ক্লিক করলেই সাথে সাথে সেভ ও কার্যকর হবে</span>
        </div>
        <div id="themePreviewBanner" style="background: {{ $settings->get('primary_color', '#0f5132') }}; padding: 16px 20px; border-radius: 6px; border-bottom: 4px solid {{ $settings->get('secondary_color', '#c59b27') }}; display: flex; align-items: center; justify-content: space-between; color: #fff; gap: 16px;">
          <div style="font-weight: 700; font-size: 15px;">
            {{ $settings->get('institution_name', 'MCTSC') }}
            <small style="display: block; font-weight: 400; font-size: 12px; opacity: 0.92; margin-top: 2px;">কালার পরিবর্তন করলেই সাথে সাথে এই প্রিভিউ আপডেট হবে</small>
          </div>
          <button type="submit" name="action" value="apply_color" id="themePreviewBtn" title="এই কালারটি এখনই সাইটে সেভ ও কার্যকর করতে ক্লিক করুন"
            style="background: {{ $settings->get('secondary_color', '#c59b27') }}; color: #000; padding: 9px 22px; border-radius: 4px; font-weight: 700; font-size: 13px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.25); white-space: nowrap; transition: transform 0.15s, box-shadow 0.15s;">
            <span>Apply Now</span>
          </button>
        </div>
      </div>
    </div>

    <!-- 3. CONTACT & SOCIAL -->
    <div style="border-bottom: 2px solid #e2ebe6; padding-bottom: 16px; margin-bottom: 20px;">
      <h2 style="font-size: 18px; color: var(--green); margin: 0 0 6px;">3. যোগাযোগ ও ঠিকানা (Contact & Social Info)</h2>
      <p style="color: var(--muted); margin: 0 0 15px; font-size: 13px;">ওয়েবসাইটের ফুটার এবং Contact সেকশনে এই তথ্যগুলো থাকবে।</p>
      
      <div class="field-grid">
        <label>
          ফোন নম্বর (Phone Number)
          <input name="phone" value="{{ old('phone', $settings->get('phone', '+8801880919275')) }}">
        </label>
        <label>
          অফিশিয়াল ইমেইল (Email Address)
          <input name="email" type="email" value="{{ old('email', $settings->get('email', 'mnctsc@gmail.com')) }}">
        </label>
      </div>

      <label style="margin-top: 12px;">
        ক্যাম্পাসের ঠিকানা (Address)
        <textarea name="address" rows="2">{{ old('address', $settings->get('address', '273/A/520, Chodu Chowdhury Road, Sagorika, Pahartali, Chattogram')) }}</textarea>
      </label>

      <div class="field-grid" style="margin-top: 12px;">
        <label>
          ফেসবুক পেজ লিঙ্ক (Facebook URL)
          <input name="social_facebook" value="{{ old('social_facebook', $settings->get('social_facebook', 'https://www.facebook.com/mnctsc')) }}" placeholder="https://www.facebook.com/mnctsc">
        </label>
        <label>
          ইউটিউব লিঙ্ক (YouTube URL)
          <input name="social_youtube" value="{{ old('social_youtube', $settings->get('social_youtube')) }}" placeholder="https://youtube.com/@channel">
        </label>
      </div>
    </div>

    <!-- 3. HERO SECTION -->
    <div style="border-bottom: 2px solid #e2ebe6; padding-bottom: 16px; margin-bottom: 20px;">
      <h2 style="font-size: 18px; color: var(--green); margin: 0 0 6px;">3. হিরো সেকশন ব্যানার ও টেক্সট (Hero Banner & Text)</h2>
      <p style="color: var(--muted); margin: 0 0 15px; font-size: 13px;">ওয়েবসাইট ওপেন করলেই সবার উপরে যে মূল ব্যানার প্রদর্শিত হয়।</p>
      
      <div class="field-grid">
        <label>
          হিরো আইব্রো ব্যাজ (Badge Text)
          <input name="hero_eyebrow" value="{{ old('hero_eyebrow', $settings->get('hero_eyebrow', 'SKILLS FOR A BRIGHTER FUTURE')) }}">
        </label>
        <label>
          বাটন টেক্সট (Hero Button Text)
          <input name="hero_btn_text" value="{{ old('hero_btn_text', $settings->get('hero_btn_text', 'Explore Our Courses')) }}">
        </label>
      </div>

      <div class="field-grid" style="margin-top: 12px;">
        <label>
          মূল শিরোনাম (Hero Main Heading)
          <input name="hero_title" value="{{ old('hero_title', $settings->get('hero_title', 'Practical Skills. Better Opportunities.')) }}">
        </label>
        <label>
          সাব-শিরোনাম (Hero Subtitle)
          <input name="hero_subtitle" value="{{ old('hero_subtitle', $settings->get('hero_subtitle', 'Skills-focused technical training for a confident future.')) }}">
        </label>
      </div>

      <div style="background:#f8faf9;border:1px solid #e2ebe6;padding:14px;border-radius:6px;margin-top:14px;">
        <label>
          <strong>হিরো ব্যাকগ্রাউন্ড ব্যানার ছবি (Hero Banner Image)</strong>
          <input type="file" name="hero_banner" accept="image/*">
          @php
            $currentHero = storage_asset($settings->get('hero_banner'), 'assets/mc Banner.jpg');
          @endphp
          <div style="margin-top: 10px; display: flex; align-items: center; gap: 14px;">
            <img src="{{ $currentHero }}" alt="Hero Banner" style="height: 70px; max-width: 180px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
            <span style="font-size: 12px; color: #555;">বর্তমান হিরো ব্যানার প্রিভিউ</span>
          </div>
        </label>
      </div>
    </div>

    <!-- 4. ABOUT SECTION -->
    <div style="border-bottom: 2px solid #e2ebe6; padding-bottom: 16px; margin-bottom: 20px;">
      <h2 style="font-size: 18px; color: var(--green); margin: 0 0 6px;">4. আমাদের সম্পর্কে সেকশন (About Us Section)</h2>
      <p style="color: var(--muted); margin: 0 0 15px; font-size: 13px;">প্রতিষ্ঠান পরিচিতি এবং ক্যাম্পাসের ছবি।</p>
      
      <label>
        সেকশন শিরোনাম (About Title)
        <input name="about_title" value="{{ old('about_title', $settings->get('about_title', 'About Our College')) }}">
      </label>

      <label style="margin-top: 12px;">
        প্রতিষ্ঠান পরিচিতি বর্ণনা (About Description)
        <textarea name="about" rows="5">{{ old('about', $settings->get('about', 'Mahmudunnabi Chowdhury Technical School and College provides quality technical and vocational education to equip learners with practical skills for a brighter future.')) }}</textarea>
      </label>

      <label style="margin-top: 12px;">
        সাইডবার স্লোগান (Aside Box Text)
        <input name="about_aside" value="{{ old('about_aside', $settings->get('about_aside', 'Education / Skills / Opportunities / A Stronger Tomorrow')) }}">
      </label>

      <div style="background:#f8faf9;border:1px solid #e2ebe6;padding:14px;border-radius:6px;margin-top:14px;">
        <label>
          <strong>ক্যাম্পাস ভবন / ক্যাম্পাসের ছবি (Campus Photo)</strong>
          <input type="file" name="about_image" accept="image/*">
          @php
            $currentAbout = storage_asset($settings->get('about_image'), 'assets/MC Building Photo.jpg');
          @endphp
          <div style="margin-top: 10px; display: flex; align-items: center; gap: 14px;">
            <img src="{{ $currentAbout }}" alt="Campus" style="height: 70px; max-width: 180px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
            <span style="font-size: 12px; color: #555;">বর্তমান ক্যাম্পাসের ছবি প্রিভিউ</span>
          </div>
        </label>
      </div>
    </div>

    <!-- 5. FACILITIES SECTION -->
    <div style="border-bottom: 2px solid #e2ebe6; padding-bottom: 16px; margin-bottom: 20px;">
      <h2 style="font-size: 18px; color: var(--green); margin: 0 0 6px;">5. ল্যাব ও ওয়ার্কশপ সুবিধাসমূহ (Facilities Photos & Titles)</h2>
      <p style="color: var(--muted); margin: 0 0 15px; font-size: 13px;">"Our Facilities and Hands-on Learning" সেকশনের ৪টি ল্যাব ও ওয়ার্কশপের ছবি ও শিরোনাম।</p>
      
      <!-- Facility 1 -->
      <div style="background:#fdfdfd;border:1px solid #e2ebe6;padding:12px;border-radius:6px;margin-bottom:12px;">
        <div class="field-grid">
          <label>
            ল্যাব ১ শিরোনাম (Facility 1 Title)
            <input name="facility_1_title" value="{{ old('facility_1_title', $settings->get('facility_1_title', 'Computer Lab')) }}">
          </label>
          <label>
            ল্যাব ১ ছবি (Facility 1 Image)
            <input type="file" name="facility_1_image" accept="image/*">
          </label>
        </div>
        @php
          $f1 = storage_asset($settings->get('facility_1_image'), 'assets/Computer Lab r.jpg');
        @endphp
        <div style="margin-top:8px;display:flex;align-items:center;gap:10px;">
          <img src="{{ $f1 }}" style="height:45px;width:75px;object-fit:cover;border-radius:4px;border:1px solid #ccc;">
          <small style="color:#666;">বর্তমান ছবি: {{ $settings->get('facility_1_title', 'Computer Lab') }}</small>
        </div>
      </div>

      <!-- Facility 2 -->
      <div style="background:#fdfdfd;border:1px solid #e2ebe6;padding:12px;border-radius:6px;margin-bottom:12px;">
        <div class="field-grid">
          <label>
            ল্যাব ২ শিরোনাম (Facility 2 Title)
            <input name="facility_2_title" value="{{ old('facility_2_title', $settings->get('facility_2_title', 'Refrigeration and AC Workshop')) }}">
          </label>
          <label>
            ল্যাব ২ ছবি (Facility 2 Image)
            <input type="file" name="facility_2_image" accept="image/*">
          </label>
        </div>
        @php
          $f2 = storage_asset($settings->get('facility_2_image'), 'assets/RAC Lab.jpg');
        @endphp
        <div style="margin-top:8px;display:flex;align-items:center;gap:10px;">
          <img src="{{ $f2 }}" style="height:45px;width:75px;object-fit:cover;border-radius:4px;border:1px solid #ccc;">
          <small style="color:#666;">বর্তমান ছবি: {{ $settings->get('facility_2_title', 'Refrigeration and AC Workshop') }}</small>
        </div>
      </div>

      <!-- Facility 3 -->
      <div style="background:#fdfdfd;border:1px solid #e2ebe6;padding:12px;border-radius:6px;margin-bottom:12px;">
        <div class="field-grid">
          <label>
            ল্যাব ৩ শিরোনাম (Facility 3 Title)
            <input name="facility_3_title" value="{{ old('facility_3_title', $settings->get('facility_3_title', 'Plumbing Workshop')) }}">
          </label>
          <label>
            ল্যাব ৩ ছবি (Facility 3 Image)
            <input type="file" name="facility_3_image" accept="image/*">
          </label>
        </div>
        @php
          $f3 = storage_asset($settings->get('facility_3_image'), 'assets/Plumbing Class.jpg');
        @endphp
        <div style="margin-top:8px;display:flex;align-items:center;gap:10px;">
          <img src="{{ $f3 }}" style="height:45px;width:75px;object-fit:cover;border-radius:4px;border:1px solid #ccc;">
          <small style="color:#666;">বর্তমান ছবি: {{ $settings->get('facility_3_title', 'Plumbing Workshop') }}</small>
        </div>
      </div>

      <!-- Facility 4 -->
      <div style="background:#fdfdfd;border:1px solid #e2ebe6;padding:12px;border-radius:6px;">
        <div class="field-grid">
          <label>
            ল্যাব ৪ শিরোনাম (Facility 4 Title)
            <input name="facility_4_title" value="{{ old('facility_4_title', $settings->get('facility_4_title', 'Tailoring and Sewing Workshop')) }}">
          </label>
          <label>
            ল্যাব ৪ ছবি (Facility 4 Image)
            <input type="file" name="facility_4_image" accept="image/*">
          </label>
        </div>
        @php
          $f4 = storage_asset($settings->get('facility_4_image'), 'assets/SMO Lab.jpg');
        @endphp
        <div style="margin-top:8px;display:flex;align-items:center;gap:10px;">
          <img src="{{ $f4 }}" style="height:45px;width:75px;object-fit:cover;border-radius:4px;border:1px solid #ccc;">
          <small style="color:#666;">বর্তমান ছবি: {{ $settings->get('facility_4_title', 'Tailoring and Sewing Workshop') }}</small>
        </div>
      </div>
    </div>

    <!-- 6. ADMISSION & QUOTES -->
    <div style="border-bottom: 2px solid #e2ebe6; padding-bottom: 22px; margin-bottom: 25px;">
      <h2 style="font-size: 18px; color: var(--green); margin: 0 0 6px;">6. ভর্তি ও অন্যান্য স্লোগান (Admissions & Quote Banner)</h2>
      <div class="field-grid">
        <label>
          ভর্তি সেকশন শিরোনাম (Admission Title)
          <input name="admission_title" value="{{ old('admission_title', $settings->get('admission_title', 'Admission Information')) }}">
        </label>
        <label>
          ভর্তি সাব-শিরোনাম (Admission Subtitle)
          <input name="admission_subtitle" value="{{ old('admission_subtitle', $settings->get('admission_subtitle', 'Ask about eligibility, application process and important dates.')) }}">
        </label>
      </div>

      <div class="field-grid" style="margin-top:12px;">
        <label>
          বড় কোটেশন কার্ড টেক্সট (Quote Title)
          <input name="quote_title" value="{{ old('quote_title', $settings->get('quote_title', 'Skilled People Build a Better Bangladesh')) }}">
        </label>
        <label>
          ফুটার ট্যাগলাইন (Footer Highlight)
          <input name="footer_tag" value="{{ old('footer_tag', $settings->get('footer_tag', 'A Brighter Tomorrow Through Skills')) }}">
        </label>
      </div>
    </div>

    <!-- 7. ADMISSION FORM REQUIRED FIELDS SETTINGS -->
    @php
      $reqFields = \App\Models\SiteSetting::getRequiredAdmissionFields();
    @endphp
    <style>
      /* Admission Fields Manager (AFM) - Scoped Styles & Resets */
      .afm-container {
        background: #ffffff;
        border: 1px solid #d8e4dc;
        border-radius: 10px;
        padding: 18px 20px 22px;
        margin-top: 10px;
        box-shadow: 0 1px 4px rgba(15, 81, 50, 0.04);
      }

      /* Force override all high-specificity .form-panel overrides */
      .form-panel .afm-container label.afm-row {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 9px 12px !important;
        background: #ffffff !important;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        box-sizing: border-box !important;
        font-weight: normal !important;
        user-select: none !important;
      }

      .form-panel .afm-container label.afm-row:last-child {
        border-bottom: none !important;
      }

      .form-panel .afm-container label.afm-row:hover {
        background: #f8fafc !important;
      }

      .form-panel .afm-container label.afm-row.is-active {
        background: #f0fdf4 !important;
      }

      .form-panel .afm-container label.afm-row span {
        color: inherit !important;
      }

      .form-panel .afm-container input[type="checkbox"].admission-field-chk {
        display: inline-block !important;
        width: 18px !important;
        min-width: 18px !important;
        max-width: 18px !important;
        height: 18px !important;
        margin: 0 10px 0 0 !important;
        padding: 0 !important;
        border: 1.5px solid #94a3b8 !important;
        border-radius: 4px !important;
        accent-color: var(--green, #0f5132) !important;
        cursor: pointer !important;
        flex-shrink: 0 !important;
        box-shadow: none !important;
      }

      .afm-top-panel {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #f8faf9;
        border: 1px solid #e2ebe6;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 16px;
      }

      .afm-lock-notice {
        font-size: 12px;
        color: #0f5132;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
      }

      .afm-counter-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        background: #e7f3ed;
        color: #0b5948;
        font-size: 12px;
        font-weight: 700;
      }

      .afm-actions-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
      }

      .afm-search-input {
        width: 220px !important;
        padding: 6px 12px !important;
        font-size: 12.5px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        outline: none !important;
        box-sizing: border-box !important;
      }
      .afm-search-input:focus {
        border-color: #0f5132 !important;
        box-shadow: 0 0 0 2px rgba(15, 81, 50, 0.15) !important;
      }

      .afm-btn {
        padding: 6px 11px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
        border: 1px solid transparent;
      }

      .afm-btn-select {
        background: #eef7f2;
        color: #0f5132;
        border-color: #a7d3c0;
      }
      .afm-btn-select:hover {
        background: #dcf2e6;
      }

      .afm-btn-default {
        background: #f1f5f9;
        color: #334155;
        border-color: #cbd5e1;
      }
      .afm-btn-default:hover {
        background: #e2e8f0;
      }

      .afm-btn-clear {
        background: #ffffff;
        color: #dc2626;
        border-color: #fca5a5;
      }
      .afm-btn-clear:hover {
        background: #fee2e2;
      }

      .afm-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
      }

      @media (max-width: 1200px) {
        .afm-grid {
          grid-template-columns: repeat(2, minmax(0, 1fr));
        }
      }

      @media (max-width: 720px) {
        .afm-grid {
          grid-template-columns: 1fr;
        }
        .afm-search-input {
          width: 100% !important;
        }
      }

      .afm-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
      }

      .afm-card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 9px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      .afm-card-title {
        font-size: 13px;
        font-weight: 700;
        color: #0f5132;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
      }

      .afm-cat-btn {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        border-radius: 4px;
        padding: 2px 7px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.12s ease;
      }
      .afm-cat-btn:hover {
        background: #f1f5f9;
        color: #0f5132;
        border-color: #94a3b8;
      }

      .afm-card-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 12px;
        background: #e2e8f0;
        color: #475569;
        display: inline-block;
        min-width: 28px;
        text-align: center;
      }

      .afm-card-body {
        padding: 0;
        display: flex;
        flex-direction: column;
      }

      .afm-row-left {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
      }

      .afm-row-text {
        display: flex !important;
        flex-direction: column !important;
        gap: 1px !important;
        line-height: 1.22 !important;
        min-width: 0 !important;
      }

      .afm-text-bn {
        font-size: 12.5px !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        white-space: normal !important;
      }

      .afm-text-en {
        font-size: 11px !important;
        font-weight: 400 !important;
        color: #64748b !important;
        white-space: normal !important;
      }

      .afm-row-right {
        flex-shrink: 0 !important;
        margin-left: 8px !important;
      }

      .afm-badge-req {
        display: inline-block !important;
        font-size: 10.5px !important;
        font-weight: 700 !important;
        padding: 2px 7px !important;
        border-radius: 9999px !important;
        background: #fef2f2 !important;
        color: #b91c1c !important;
        border: 1px solid #fecaca !important;
        white-space: nowrap !important;
        letter-spacing: 0.2px;
      }

      .afm-badge-opt {
        display: inline-block !important;
        font-size: 10.5px !important;
        font-weight: 500 !important;
        padding: 2px 7px !important;
        border-radius: 9999px !important;
        background: #f1f5f9 !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
        white-space: nowrap !important;
      }
    </style>

    <div style="margin-bottom: 25px;">
      <input type="hidden" name="admission_fields_config_submitted" value="1">
      <div style="margin-bottom: 12px;">
        <h2 style="font-size: 18px; color: var(--green); margin: 0 0 6px;">7. ভর্তি আবেদন ফরমের ফিল্ড রিকোয়ারমেন্ট (Admission Form Mandatory Fields)</h2>
        <p style="color: var(--muted); margin: 0; font-size: 13px;">
          অনলাইন আবেদন ফরমে যে ফিল্ডগুলোতে টিক দেওয়া থাকবে সেগুলো শিক্ষার্থীকে পূরণ করা <strong>বাধ্যতামূলক (Mandatory — লাল তারাচিহ্ন <span style="color:#dc2626;font-weight:bold;">*</span>)</strong> থাকবে। আনটিক করলে তা ঐচ্ছিক (Optional) হয়ে যাবে।
        </p>
      </div>

      <div class="afm-container afm-wrap">
        {{-- Top Toolbar --}}
        <div class="afm-top-panel">
          <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <span class="afm-lock-notice">
              🔒 <strong>সিস্টেম আবশ্যিক:</strong> প্রার্থীর ইংরেজি নাম ও কোর্স কোড সর্বদা আবশ্যিক
            </span>
            <span id="afmTotalCounter" class="afm-counter-pill">
              📊 লোড হচ্ছে...
            </span>
          </div>

          <div class="afm-actions-wrap">
            <input type="text" id="afmSearchInput" class="afm-search-input" placeholder="🔍 ফিল্ড খুঁজুন..." oninput="filterAfmFields(this.value)">
            <button type="button" class="afm-btn afm-btn-select" onclick="setAllAdmissionFields(true)" title="সকল ফিল্ডকে আবশ্যক করুন">
              ✓ সব আবশ্যক
            </button>
            <button type="button" class="afm-btn afm-btn-default" onclick="setDefaultAdmissionFields()" title="স্ট্যান্ডার্ড ফিল্ডগুলো নির্বাচন করুন">
              ⚡ প্রস্তাবিত ডিফল্ট
            </button>
            <button type="button" class="afm-btn afm-btn-clear" onclick="setAllAdmissionFields(false)" title="সব ফিল্ডকে ঐচ্ছিক করুন">
              ✕ সব ঐচ্ছিক
            </button>
          </div>
        </div>

        {{-- Categories Grid --}}
        <div class="afm-grid" id="afmGrid">
          {{-- Card 1: Personal Profile --}}
          <div class="afm-card">
            <div class="afm-card-header">
              <span class="afm-card-title">১. প্রার্থীর ব্যক্তিগত তথ্য</span>
              <div style="display:flex;align-items:center;gap:5px;">
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">সব</button>
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">বাদ</button>
                <span class="afm-card-badge">0/0</span>
              </div>
            </div>
            <div class="afm-card-body">
              <label class="afm-row @if(in_array('trainee_name_bn', $reqFields)) is-active @endif" for="afm_trainee_name_bn">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_trainee_name_bn" name="admission_required_fields[]" value="trainee_name_bn" class="admission-field-chk" @checked(in_array('trainee_name_bn', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">নাম বাংলায়</span>
                    <span class="afm-text-en">Trainee Name (Bengali)</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('dob', $reqFields)) is-active @endif" for="afm_dob">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_dob" name="admission_required_fields[]" value="dob" class="admission-field-chk" @checked(in_array('dob', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">জন্ম তারিখ</span>
                    <span class="afm-text-en">Date of Birth</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('ident_type', $reqFields)) is-active @endif" for="afm_ident_type">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_ident_type" name="admission_required_fields[]" value="ident_type" class="admission-field-chk" @checked(in_array('ident_type', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">আইডি টাইপ</span>
                    <span class="afm-text-en">ID Type (NID / Birth Cert)</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('ident_no', $reqFields)) is-active @endif" for="afm_ident_no">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_ident_no" name="admission_required_fields[]" value="ident_no" class="admission-field-chk" @checked(in_array('ident_no', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">এনআইডি বা জন্ম সনদ নম্বর</span>
                    <span class="afm-text-en">NID / Birth Cert No.</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('sex', $reqFields)) is-active @endif" for="afm_sex">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_sex" name="admission_required_fields[]" value="sex" class="admission-field-chk" @checked(in_array('sex', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">লিঙ্গ</span>
                    <span class="afm-text-en">Sex / Gender</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('pwd', $reqFields)) is-active @endif" for="afm_pwd">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_pwd" name="admission_required_fields[]" value="pwd" class="admission-field-chk" @checked(in_array('pwd', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">প্রতিবন্ধিতা অবস্থা</span>
                    <span class="afm-text-en">Disability (PWD Status)</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('contact_no', $reqFields)) is-active @endif" for="afm_contact_no">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_contact_no" name="admission_required_fields[]" value="contact_no" class="admission-field-chk" @checked(in_array('contact_no', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">মোবাইল নম্বর</span>
                    <span class="afm-text-en">Contact Phone Number</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('email', $reqFields)) is-active @endif" for="afm_email">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_email" name="admission_required_fields[]" value="email" class="admission-field-chk" @checked(in_array('email', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">ইমেইল ঠিকানা</span>
                    <span class="afm-text-en">Email Address</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('marital_status', $reqFields)) is-active @endif" for="afm_marital_status">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_marital_status" name="admission_required_fields[]" value="marital_status" class="admission-field-chk" @checked(in_array('marital_status', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">বৈবাহিক অবস্থা</span>
                    <span class="afm-text-en">Marital Status</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>
            </div>
          </div>

          {{-- Card 2: Uploads & Documents --}}
          <div class="afm-card">
            <div class="afm-card-header">
              <span class="afm-card-title">২. ছবি ও ফাইল আপলোড</span>
              <div style="display:flex;align-items:center;gap:5px;">
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">সব</button>
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">বাদ</button>
                <span class="afm-card-badge">0/0</span>
              </div>
            </div>
            <div class="afm-card-body">
              <label class="afm-row @if(in_array('photo', $reqFields)) is-active @endif" for="afm_photo">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_photo" name="admission_required_fields[]" value="photo" class="admission-field-chk" @checked(in_array('photo', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">প্রার্থীর পাসপোর্ট ছবি</span>
                    <span class="afm-text-en">Passport Photo Upload</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('signature_photo', $reqFields)) is-active @endif" for="afm_signature_photo">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_signature_photo" name="admission_required_fields[]" value="signature_photo" class="admission-field-chk" @checked(in_array('signature_photo', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">প্রার্থীর স্বাক্ষর</span>
                    <span class="afm-text-en">Digital Signature Upload</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('ident_doc', $reqFields)) is-active @endif" for="afm_ident_doc">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_ident_doc" name="admission_required_fields[]" value="ident_doc" class="admission-field-chk" @checked(in_array('ident_doc', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">NID বা জন্ম সনদ কপি</span>
                    <span class="afm-text-en">Identity Document Upload</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>
            </div>
          </div>

          {{-- Card 3: Present Address --}}
          <div class="afm-card">
            <div class="afm-card-header">
              <span class="afm-card-title">৩. বর্তমান ঠিকানা</span>
              <div style="display:flex;align-items:center;gap:5px;">
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">সব</button>
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">বাদ</button>
                <span class="afm-card-badge">0/0</span>
              </div>
            </div>
            <div class="afm-card-body">
              <label class="afm-row @if(in_array('pres_division', $reqFields)) is-active @endif" for="afm_pres_division">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_pres_division" name="admission_required_fields[]" value="pres_division" class="admission-field-chk" @checked(in_array('pres_division', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">বর্তমান বিভাগ</span>
                    <span class="afm-text-en">Present Division</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('pres_district', $reqFields)) is-active @endif" for="afm_pres_district">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_pres_district" name="admission_required_fields[]" value="pres_district" class="admission-field-chk" @checked(in_array('pres_district', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">বর্তমান জেলা</span>
                    <span class="afm-text-en">Present District</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('pres_upazilla', $reqFields)) is-active @endif" for="afm_pres_upazilla">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_pres_upazilla" name="admission_required_fields[]" value="pres_upazilla" class="admission-field-chk" @checked(in_array('pres_upazilla', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">বর্তমান উপজেলা / থানা</span>
                    <span class="afm-text-en">Present Upazilla / Thana</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('pres_address', $reqFields)) is-active @endif" for="afm_pres_address">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_pres_address" name="admission_required_fields[]" value="pres_address" class="admission-field-chk" @checked(in_array('pres_address', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">বর্তমান পূর্ণ ঠিকানা</span>
                    <span class="afm-text-en">Present Address Line</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>
            </div>
          </div>

          {{-- Card 4: Permanent Address --}}
          <div class="afm-card">
            <div class="afm-card-header">
              <span class="afm-card-title">৪. স্থায়ী ঠিকানা</span>
              <div style="display:flex;align-items:center;gap:5px;">
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">সব</button>
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">বাদ</button>
                <span class="afm-card-badge">0/0</span>
              </div>
            </div>
            <div class="afm-card-body">
              <label class="afm-row @if(in_array('perm_division', $reqFields)) is-active @endif" for="afm_perm_division">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_perm_division" name="admission_required_fields[]" value="perm_division" class="admission-field-chk" @checked(in_array('perm_division', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">স্থায়ী বিভাগ</span>
                    <span class="afm-text-en">Permanent Division</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('perm_district', $reqFields)) is-active @endif" for="afm_perm_district">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_perm_district" name="admission_required_fields[]" value="perm_district" class="admission-field-chk" @checked(in_array('perm_district', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">স্থায়ী জেলা</span>
                    <span class="afm-text-en">Permanent District</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('perm_upazilla', $reqFields)) is-active @endif" for="afm_perm_upazilla">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_perm_upazilla" name="admission_required_fields[]" value="perm_upazilla" class="admission-field-chk" @checked(in_array('perm_upazilla', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">স্থায়ী উপজেলা / থানা</span>
                    <span class="afm-text-en">Permanent Upazilla / Thana</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('perm_address', $reqFields)) is-active @endif" for="afm_perm_address">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_perm_address" name="admission_required_fields[]" value="perm_address" class="admission-field-chk" @checked(in_array('perm_address', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">স্থায়ী পূর্ণ ঠিকানা</span>
                    <span class="afm-text-en">Permanent Address Line</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('perm_area_type', $reqFields)) is-active @endif" for="afm_perm_area_type">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_perm_area_type" name="admission_required_fields[]" value="perm_area_type" class="admission-field-chk" @checked(in_array('perm_area_type', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">পল্লী / শহর এলাকা</span>
                    <span class="afm-text-en">Area Type (Rural / Urban)</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>
            </div>
          </div>

          {{-- Card 5: Education & Demographics --}}
          <div class="afm-card">
            <div class="afm-card-header">
              <span class="afm-card-title">৫. শিক্ষা ও জনসংখ্যাতাত্ত্বিক</span>
              <div style="display:flex;align-items:center;gap:5px;">
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">সব</button>
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">বাদ</button>
                <span class="afm-card-badge">0/0</span>
              </div>
            </div>
            <div class="afm-card-body">
              <label class="afm-row @if(in_array('education_level', $reqFields)) is-active @endif" for="afm_education_level">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_education_level" name="admission_required_fields[]" value="education_level" class="admission-field-chk" @checked(in_array('education_level', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">শিক্ষাগত যোগ্যতা</span>
                    <span class="afm-text-en">Education Level</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('religion', $reqFields)) is-active @endif" for="afm_religion">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_religion" name="admission_required_fields[]" value="religion" class="admission-field-chk" @checked(in_array('religion', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">ধর্ম</span>
                    <span class="afm-text-en">Religion</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('ethnicity', $reqFields)) is-active @endif" for="afm_ethnicity">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_ethnicity" name="admission_required_fields[]" value="ethnicity" class="admission-field-chk" @checked(in_array('ethnicity', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">নৃগোষ্ঠী</span>
                    <span class="afm-text-en">Ethnicity</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>
            </div>
          </div>

          {{-- Card 6: Family & Guardian --}}
          <div class="afm-card">
            <div class="afm-card-header">
              <span class="afm-card-title">৬. অভিভাবক ও পারিবারিক তথ্য</span>
              <div style="display:flex;align-items:center;gap:5px;">
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">সব</button>
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">বাদ</button>
                <span class="afm-card-badge">0/0</span>
              </div>
            </div>
            <div class="afm-card-body">
              <label class="afm-row @if(in_array('father_name_en', $reqFields)) is-active @endif" for="afm_father_name_en">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_father_name_en" name="admission_required_fields[]" value="father_name_en" class="admission-field-chk" @checked(in_array('father_name_en', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">পিতার নাম ইংরেজিতে</span>
                    <span class="afm-text-en">Father's Name (English)</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('father_name_bn', $reqFields)) is-active @endif" for="afm_father_name_bn">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_father_name_bn" name="admission_required_fields[]" value="father_name_bn" class="admission-field-chk" @checked(in_array('father_name_bn', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">পিতার নাম বাংলায়</span>
                    <span class="afm-text-en">Father's Name (Bengali)</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('mother_name_en', $reqFields)) is-active @endif" for="afm_mother_name_en">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_mother_name_en" name="admission_required_fields[]" value="mother_name_en" class="admission-field-chk" @checked(in_array('mother_name_en', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">মাতার নাম ইংরেজিতে</span>
                    <span class="afm-text-en">Mother's Name (English)</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('mother_name_bn', $reqFields)) is-active @endif" for="afm_mother_name_bn">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_mother_name_bn" name="admission_required_fields[]" value="mother_name_bn" class="admission-field-chk" @checked(in_array('mother_name_bn', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">মাতার নাম বাংলায়</span>
                    <span class="afm-text-en">Mother's Name (Bengali)</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('emergency_contact', $reqFields)) is-active @endif" for="afm_emergency_contact">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_emergency_contact" name="admission_required_fields[]" value="emergency_contact" class="admission-field-chk" @checked(in_array('emergency_contact', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">জরুরি অভিভাবক যোগাযোগ</span>
                    <span class="afm-text-en">Emergency / Guardian Contact</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('household_members', $reqFields)) is-active @endif" for="afm_household_members">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_household_members" name="admission_required_fields[]" value="household_members" class="admission-field-chk" @checked(in_array('household_members', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">পরিবারের সদস্য সংখ্যা</span>
                    <span class="afm-text-en">Household Members Count</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('income_source', $reqFields)) is-active @endif" for="afm_income_source">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_income_source" name="admission_required_fields[]" value="income_source" class="admission-field-chk" @checked(in_array('income_source', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">পরিবারের আয়ের প্রধান উৎস</span>
                    <span class="afm-text-en">Main Income Source</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('monthly_income', $reqFields)) is-active @endif" for="afm_monthly_income">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_monthly_income" name="admission_required_fields[]" value="monthly_income" class="admission-field-chk" @checked(in_array('monthly_income', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">মাসিক পারিবারিক মোট আয়</span>
                    <span class="afm-text-en">Monthly Household Income</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>
            </div>
          </div>

          {{-- Card 7: Employment & Background --}}
          <div class="afm-card">
            <div class="afm-card-header">
              <span class="afm-card-title">৭. কর্মসংস্থান ও অন্যান্য</span>
              <div style="display:flex;align-items:center;gap:5px;">
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, true)">সব</button>
                <button type="button" class="afm-cat-btn" onclick="toggleAfmCard(this, false)">বাদ</button>
                <span class="afm-card-badge">0/0</span>
              </div>
            </div>
            <div class="afm-card-body">
              <label class="afm-row @if(in_array('has_bank_account', $reqFields)) is-active @endif" for="afm_has_bank_account">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_has_bank_account" name="admission_required_fields[]" value="has_bank_account" class="admission-field-chk" @checked(in_array('has_bank_account', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">ব্যাংক অ্যাকাউন্ট আছে কি না</span>
                    <span class="afm-text-en">Has Bank Account (Yes/No)</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('past_training', $reqFields)) is-active @endif" for="afm_past_training">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_past_training" name="admission_required_fields[]" value="past_training" class="admission-field-chk" @checked(in_array('past_training', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">পূর্বে প্রশিক্ষণ নিয়েছেন কি না</span>
                    <span class="afm-text-en">Prior Technical Training</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('employment_status', $reqFields)) is-active @endif" for="afm_employment_status">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_employment_status" name="admission_required_fields[]" value="employment_status" class="admission-field-chk" @checked(in_array('employment_status', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">বর্তমান কর্মসংস্থান অবস্থা</span>
                    <span class="afm-text-en">Current Employment Status</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('monthly_cash_income', $reqFields)) is-active @endif" for="afm_monthly_cash_income">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_monthly_cash_income" name="admission_required_fields[]" value="monthly_cash_income" class="admission-field-chk" @checked(in_array('monthly_cash_income', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">মাসিক নিজস্ব নগদ আয়</span>
                    <span class="afm-text-en">Applicant Monthly Cash Income</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>

              <label class="afm-row @if(in_array('training_media_source', $reqFields)) is-active @endif" for="afm_training_media_source">
                <div class="afm-row-left">
                  <input type="checkbox" id="afm_training_media_source" name="admission_required_fields[]" value="training_media_source" class="admission-field-chk" @checked(in_array('training_media_source', $reqFields))>
                  <div class="afm-row-text">
                    <span class="afm-text-bn">যেভাবে জানতে পেরেছেন</span>
                    <span class="afm-text-en">Media / Information Source</span>
                  </div>
                </div>
                <div class="afm-row-right">
                  <span class="afm-badge-req">আবশ্যক *</span>
                  <span class="afm-badge-opt">ঐচ্ছিক</span>
                </div>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="form-actions" style="position: sticky; bottom: 0; background: #fff; padding: 16px 0; border-top: 1px solid #ddd; z-index: 2;">
      <button class="btn gold" type="submit" style="font-size: 15px; padding: 12px 28px;">Save All Website Settings</button>
    </div>
  </form>
</section>

<script>
function syncAfmUi() {
  const checkboxes = document.querySelectorAll('.admission-field-chk');
  let activeCount = 0;

  checkboxes.forEach(chk => {
    const row = chk.closest('label.afm-row');
    if (!row) return;
    const reqBadge = row.querySelector('.afm-badge-req');
    const optBadge = row.querySelector('.afm-badge-opt');

    if (chk.checked) {
      activeCount++;
      row.classList.add('is-active');
      if (reqBadge) reqBadge.style.setProperty('display', 'inline-block', 'important');
      if (optBadge) optBadge.style.setProperty('display', 'none', 'important');
    } else {
      row.classList.remove('is-active');
      if (reqBadge) reqBadge.style.setProperty('display', 'none', 'important');
      if (optBadge) optBadge.style.setProperty('display', 'inline-block', 'important');
    }
  });

  // Update card counters
  document.querySelectorAll('.afm-card').forEach(card => {
    const cardChks = card.querySelectorAll('.admission-field-chk');
    const cardActive = Array.from(cardChks).filter(c => c.checked).length;
    const badge = card.querySelector('.afm-card-badge');
    if (badge) {
      badge.textContent = `${cardActive}/${cardChks.length}`;
      if (cardActive > 0) {
        badge.style.background = '#dcfce7';
        badge.style.color = '#15803d';
      } else {
        badge.style.background = '#e2e8f0';
        badge.style.color = '#475569';
      }
    }
  });

  // Update total counter
  const totalCounter = document.getElementById('afmTotalCounter');
  if (totalCounter) {
    totalCounter.textContent = `📊 মোট ${checkboxes.length} টির মধ্যে ${activeCount} টি ফিল্ড আবশ্যিক`;
  }
}

function toggleAfmCard(btn, checked) {
  const card = btn.closest('.afm-card');
  if (!card) return;
  card.querySelectorAll('.admission-field-chk').forEach(chk => {
    chk.checked = checked;
  });
  syncAfmUi();
}

function filterAfmFields(query) {
  const q = (query || '').toLowerCase().trim();
  document.querySelectorAll('.afm-card').forEach(card => {
    let cardHasVisible = false;
    card.querySelectorAll('label.afm-row').forEach(row => {
      const text = row.innerText.toLowerCase();
      if (!q || text.includes(q)) {
        row.style.setProperty('display', 'flex', 'important');
        cardHasVisible = true;
      } else {
        row.style.setProperty('display', 'none', 'important');
      }
    });
    if (!q || cardHasVisible) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

function setAllAdmissionFields(checked) {
  document.querySelectorAll('.admission-field-chk').forEach(chk => {
    chk.checked = checked;
  });
  syncAfmUi();
}

function setDefaultAdmissionFields() {
  const defaults = [
    'trainee_name_bn',
    'dob',
    'ident_type',
    'ident_no',
    'sex',
    'pwd',
    'contact_no',
    'photo',
    'signature_photo',
    'ident_doc',
    'perm_division',
    'perm_district',
    'perm_upazilla',
    'perm_address',
    'perm_area_type'
  ];
  document.querySelectorAll('.admission-field-chk').forEach(chk => {
    chk.checked = defaults.includes(chk.value);
  });
  syncAfmUi();
}

document.addEventListener('DOMContentLoaded', function() {
  syncAfmUi();
  document.querySelectorAll('.admission-field-chk').forEach(chk => {
    chk.addEventListener('change', syncAfmUi);
  });
});

function setPalette(primary, deep, accent, accentDeep) {
  document.getElementById('primaryColorPicker').value = primary;
  document.getElementById('primaryColorInput').value = primary;
  document.getElementById('primaryHoverPicker').value = deep;
  document.getElementById('primaryHoverInput').value = deep;
  document.getElementById('secondaryColorPicker').value = accent;
  document.getElementById('secondaryColorInput').value = accent;
  document.getElementById('secondaryHoverPicker').value = accentDeep;
  document.getElementById('secondaryHoverInput').value = accentDeep;
  updateLivePreview();
}

function syncColor(pickerId, inputId) {
  document.getElementById(inputId).value = document.getElementById(pickerId).value;
  updateLivePreview();
}

function syncInput(inputId, pickerId) {
  let val = document.getElementById(inputId).value.trim();
  if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
    document.getElementById(pickerId).value = val;
    updateLivePreview();
  }
}

function updateLivePreview() {
  const p = document.getElementById('primaryColorInput').value;
  const s = document.getElementById('secondaryColorInput').value;
  const banner = document.getElementById('themePreviewBanner');
  const btn = document.getElementById('themePreviewBtn');
  if (banner) {
    banner.style.background = p;
    banner.style.borderBottomColor = s;
  }
  if (btn) {
    btn.style.background = s;
  }
}

document.getElementById('themePreviewBtn')?.addEventListener('click', function() {
  this.innerHTML = '<span>Applying & Saving... ⏳</span>';
  this.style.opacity = '0.85';
});
</script>
@endsection
