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
    <div style="margin-bottom: 25px;">
      <input type="hidden" name="admission_fields_config_submitted" value="1">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:12px;">
        <div>
          <h2 style="font-size: 18px; color: var(--green); margin: 0 0 6px;">7. ভর্তি আবেদন ফরমের ফিল্ড রিকোয়ারমেন্ট (Admission Form Mandatory Fields)</h2>
          <p style="color: var(--muted); margin: 0; font-size: 13px;">
            অনলাইন আবেদন ফরমে যে ফিল্ডগুলোতে টিক দেওয়া থাকবে সেগুলো শিক্ষার্থীকে পূরণ করা <strong>বাধ্যতামূলক (Mandatory — লাল তারাচিহ্ন <span style="color:#dc2626;font-weight:bold;">*</span>)</strong> থাকবে। আনটিক করলে তা ঐচ্ছিক (Optional) হয়ে যাবে।
          </p>
        </div>
        <div style="display:flex;gap:8px;">
          <button type="button" onclick="setAllAdmissionFields(true)" style="background:#eef7f2;color:#0f5132;border:1px solid #7cb5a9;padding:6px 12px;border-radius:4px;font-size:12px;font-weight:600;cursor:pointer;">
            Select All (সব আবশ্যক)
          </button>
          <button type="button" onclick="setDefaultAdmissionFields()" style="background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;padding:6px 12px;border-radius:4px;font-size:12px;font-weight:600;cursor:pointer;">
            Recommended Defaults
          </button>
          <button type="button" onclick="setAllAdmissionFields(false)" style="background:#fff;color:#dc2626;border:1px solid #fca5a5;padding:6px 12px;border-radius:4px;font-size:12px;font-weight:600;cursor:pointer;">
            Clear All (সব ঐচ্ছিক)
          </button>
        </div>
      </div>

      <div style="background:#f8fafc;border:1px solid #cbd5e1;border-radius:8px;padding:16px;">
        <div style="margin-bottom:8px;font-size:12px;color:#0f5132;font-weight:700;">
          📌 দ্রষ্টব্য: শিক্ষার্থীর নাম (Trainee Name in English) এবং কোর্সের নাম (Course) সিস্টেমের জন্য সর্বদা আবশ্যিক থাকবে।
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:16px;margin-top:12px;">
          {{-- Group 1: Personal Profile --}}
          <div style="background:#fff;border:1px solid #e2e8f0;border-radius:6px;padding:12px;">
            <strong style="color:#0f5132;font-size:13.5px;display:block;margin-bottom:10px;border-bottom:1px solid #e2e8f0;padding-bottom:6px;">
              ১. প্রার্থীর ব্যক্তিগত তথ্য
            </strong>
            <div style="display:flex;flex-direction:column;gap:8px;">
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="trainee_name_bn" class="admission-field-chk" @checked(in_array('trainee_name_bn', $reqFields)) style="accent-color:#0f5132;">
                <span>নাম বাংলায় (Trainee Name Bangla)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="dob" class="admission-field-chk" @checked(in_array('dob', $reqFields)) style="accent-color:#0f5132;">
                <span>জন্ম তারিখ (Date of Birth)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="ident_type" class="admission-field-chk" @checked(in_array('ident_type', $reqFields)) style="accent-color:#0f5132;">
                <span>আইডি টাইপ (NID / Birth Cert)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="ident_no" class="admission-field-chk" @checked(in_array('ident_no', $reqFields)) style="accent-color:#0f5132;">
                <span>NID বা জন্ম সনদ নম্বর (ID Number)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="sex" class="admission-field-chk" @checked(in_array('sex', $reqFields)) style="accent-color:#0f5132;">
                <span>লিঙ্গ (Sex / Gender)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="pwd" class="admission-field-chk" @checked(in_array('pwd', $reqFields)) style="accent-color:#0f5132;">
                <span>প্রতিবন্ধিতা (PWD Status)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="contact_no" class="admission-field-chk" @checked(in_array('contact_no', $reqFields)) style="accent-color:#0f5132;">
                <span>মোবাইল নম্বর (Contact Number)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="email" class="admission-field-chk" @checked(in_array('email', $reqFields)) style="accent-color:#0f5132;">
                <span>ইমেইল ঠিকানা (Email Address)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="marital_status" class="admission-field-chk" @checked(in_array('marital_status', $reqFields)) style="accent-color:#0f5132;">
                <span>বৈবাহিক অবস্থা (Marital Status)</span>
              </label>
            </div>
          </div>

          {{-- Group 2: Uploads --}}
          <div style="background:#fff;border:1px solid #e2e8f0;border-radius:6px;padding:12px;">
            <strong style="color:#0f5132;font-size:13.5px;display:block;margin-bottom:10px;border-bottom:1px solid #e2e8f0;padding-bottom:6px;">
              ২. ছবি ও ফাইল আপলোড
            </strong>
            <div style="display:flex;flex-direction:column;gap:8px;">
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="photo" class="admission-field-chk" @checked(in_array('photo', $reqFields)) style="accent-color:#0f5132;">
                <span>প্রার্থীর ছবি (Passport Photo Upload)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="signature_photo" class="admission-field-chk" @checked(in_array('signature_photo', $reqFields)) style="accent-color:#0f5132;">
                <span>প্রার্থীর স্বাক্ষর (Signature Upload)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="ident_doc" class="admission-field-chk" @checked(in_array('ident_doc', $reqFields)) style="accent-color:#0f5132;">
                <span>NID বা জন্ম সনদ কপি (Doc Upload)</span>
              </label>
            </div>

            <strong style="color:#0f5132;font-size:13.5px;display:block;margin-top:18px;margin-bottom:10px;border-bottom:1px solid #e2e8f0;padding-bottom:6px;">
              ৩. স্থায়ী ঠিকানা (Permanent Address)
            </strong>
            <div style="display:flex;flex-direction:column;gap:8px;">
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="perm_division" class="admission-field-chk" @checked(in_array('perm_division', $reqFields)) style="accent-color:#0f5132;">
                <span>স্থায়ী বিভাগ (Permanent Division)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="perm_district" class="admission-field-chk" @checked(in_array('perm_district', $reqFields)) style="accent-color:#0f5132;">
                <span>স্থায়ী জেলা (Permanent District)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="perm_upazilla" class="admission-field-chk" @checked(in_array('perm_upazilla', $reqFields)) style="accent-color:#0f5132;">
                <span>স্থায়ী উপজেলা/থানা (Upazilla)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="perm_address" class="admission-field-chk" @checked(in_array('perm_address', $reqFields)) style="accent-color:#0f5132;">
                <span>স্থায়ী পূর্ণ ঠিকানা (Address Line)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="perm_area_type" class="admission-field-chk" @checked(in_array('perm_area_type', $reqFields)) style="accent-color:#0f5132;">
                <span>পল্লী / শহর এলাকা (Rural/Urban)</span>
              </label>
            </div>
          </div>

          {{-- Group 3: Present Address & Demographics --}}
          <div style="background:#fff;border:1px solid #e2e8f0;border-radius:6px;padding:12px;">
            <strong style="color:#0f5132;font-size:13.5px;display:block;margin-bottom:10px;border-bottom:1px solid #e2e8f0;padding-bottom:6px;">
              ৪. বর্তমান ঠিকানা ও ডেমোগ্রাফিক
            </strong>
            <div style="display:flex;flex-direction:column;gap:8px;">
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="pres_division" class="admission-field-chk" @checked(in_array('pres_division', $reqFields)) style="accent-color:#0f5132;">
                <span>বর্তমান বিভাগ (Present Division)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="pres_district" class="admission-field-chk" @checked(in_array('pres_district', $reqFields)) style="accent-color:#0f5132;">
                <span>বর্তমান জেলা (Present District)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="pres_upazilla" class="admission-field-chk" @checked(in_array('pres_upazilla', $reqFields)) style="accent-color:#0f5132;">
                <span>বর্তমান উপজেলা/থানা (Upazilla)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="pres_address" class="admission-field-chk" @checked(in_array('pres_address', $reqFields)) style="accent-color:#0f5132;">
                <span>বর্তমান পূর্ণ ঠিকানা (Address Line)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="religion" class="admission-field-chk" @checked(in_array('religion', $reqFields)) style="accent-color:#0f5132;">
                <span>ধর্ম (Religion)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="ethnicity" class="admission-field-chk" @checked(in_array('ethnicity', $reqFields)) style="accent-color:#0f5132;">
                <span>নৃগোষ্ঠী (Ethnicity)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="education_level" class="admission-field-chk" @checked(in_array('education_level', $reqFields)) style="accent-color:#0f5132;">
                <span>শিক্ষাগত যোগ্যতা (Education Level)</span>
              </label>
            </div>
          </div>

          {{-- Group 4: Household & Employment --}}
          <div style="background:#fff;border:1px solid #e2e8f0;border-radius:6px;padding:12px;">
            <strong style="color:#0f5132;font-size:13.5px;display:block;margin-bottom:10px;border-bottom:1px solid #e2e8f0;padding-bottom:6px;">
              ৫. পারিবারিক ও কর্মসংস্থান
            </strong>
            <div style="display:flex;flex-direction:column;gap:8px;">
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="father_name_en" class="admission-field-chk" @checked(in_array('father_name_en', $reqFields)) style="accent-color:#0f5132;">
                <span>পিতার নাম ইংরেজিতে (Father's Name EN)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="father_name_bn" class="admission-field-chk" @checked(in_array('father_name_bn', $reqFields)) style="accent-color:#0f5132;">
                <span>পিতার নাম বাংলায় (Father's Name BN)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="mother_name_en" class="admission-field-chk" @checked(in_array('mother_name_en', $reqFields)) style="accent-color:#0f5132;">
                <span>মাতার নাম ইংরেজিতে (Mother's Name EN)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="mother_name_bn" class="admission-field-chk" @checked(in_array('mother_name_bn', $reqFields)) style="accent-color:#0f5132;">
                <span>মাতার নাম বাংলায় (Mother's Name BN)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="emergency_contact" class="admission-field-chk" @checked(in_array('emergency_contact', $reqFields)) style="accent-color:#0f5132;">
                <span>জরুরি যোগাযোগ (Emergency Contact)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="household_members" class="admission-field-chk" @checked(in_array('household_members', $reqFields)) style="accent-color:#0f5132;">
                <span>পরিবারের সদস্য সংখ্যা (Members)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="income_source" class="admission-field-chk" @checked(in_array('income_source', $reqFields)) style="accent-color:#0f5132;">
                <span>আয়ের উৎস (Income Source)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="monthly_income" class="admission-field-chk" @checked(in_array('monthly_income', $reqFields)) style="accent-color:#0f5132;">
                <span>মাসিক পারিবারিক আয় (Monthly Income)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="has_bank_account" class="admission-field-chk" @checked(in_array('has_bank_account', $reqFields)) style="accent-color:#0f5132;">
                <span>ব্যাংক অ্যাকাউন্ট আছে কি না (Bank)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="past_training" class="admission-field-chk" @checked(in_array('past_training', $reqFields)) style="accent-color:#0f5132;">
                <span>পূর্বে প্রশিক্ষণ নেওয়া হয়েছে কি না</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="employment_status" class="admission-field-chk" @checked(in_array('employment_status', $reqFields)) style="accent-color:#0f5132;">
                <span>কর্মসংস্থান অবস্থা (Employment)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="monthly_cash_income" class="admission-field-chk" @checked(in_array('monthly_cash_income', $reqFields)) style="accent-color:#0f5132;">
                <span>মাসিক নগদ আয় (Cash Income)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;font-size:12.5px;cursor:pointer;">
                <input type="checkbox" name="admission_required_fields[]" value="training_media_source" class="admission-field-chk" @checked(in_array('training_media_source', $reqFields)) style="accent-color:#0f5132;">
                <span>কীভাবে জানতে পেরেছেন (Media Source)</span>
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
function setAllAdmissionFields(checked) {
  document.querySelectorAll('.admission-field-chk').forEach(chk => {
    chk.checked = checked;
  });
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
}
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
