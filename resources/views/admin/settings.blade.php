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
    <div style="margin-bottom: 25px;">
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

    <div class="form-actions" style="position: sticky; bottom: 0; background: #fff; padding: 16px 0; border-top: 1px solid #ddd; z-index: 2;">
      <button class="btn gold" type="submit" style="font-size: 15px; padding: 12px 28px;">Save All Website Settings</button>
    </div>
  </form>
</section>

<script>
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
