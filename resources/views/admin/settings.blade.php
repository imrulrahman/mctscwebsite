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
            $currentLogo = $settings->get('site_logo') 
              ? (str_starts_with($settings->get('site_logo'), 'assets/') ? asset($settings->get('site_logo')) : \Illuminate\Support\Facades\Storage::url($settings->get('site_logo'))) 
              : asset('assets/MC Logo.png');
          @endphp
          <div style="margin-top: 10px; display: flex; align-items: center; gap: 14px;">
            <img src="{{ $currentLogo }}" alt="Current Logo" style="height: 60px; max-width: 150px; object-fit: contain; background: #fff; padding: 4px; border: 1px solid #ccc; border-radius: 4px;">
            <span style="font-size: 12px; color: #555;">বর্তমান লোগো প্রিভিউ</span>
          </div>
        </label>
      </div>
    </div>

    <!-- 2. CONTACT & SOCIAL -->
    <div style="border-bottom: 2px solid #e2ebe6; padding-bottom: 16px; margin-bottom: 20px;">
      <h2 style="font-size: 18px; color: var(--green); margin: 0 0 6px;">2. যোগাযোগ ও ঠিকানা (Contact & Social Info)</h2>
      <p style="color: var(--muted); margin: 0 0 15px; font-size: 13px;">ওয়েবসাইটের ফুটার এবং Contact সেকশনে এই তথ্যগুলো থাকবে।</p>
      
      <div class="field-grid">
        <label>
          ফোন নম্বর (Phone Number)
          <input name="phone" value="{{ old('phone', $settings->get('phone', '+880 1XXX-XXXXXX')) }}">
        </label>
        <label>
          অফিশিয়াল ইমেইল (Email Address)
          <input name="email" type="email" value="{{ old('email', $settings->get('email', 'info@mctsc.edu.bd')) }}">
        </label>
      </div>

      <label style="margin-top: 12px;">
        ক্যাম্পাসের ঠিকানা (Address)
        <textarea name="address" rows="2">{{ old('address', $settings->get('address', 'Chattogram, Bangladesh')) }}</textarea>
      </label>

      <div class="field-grid" style="margin-top: 12px;">
        <label>
          ফেসবুক পেজ লিঙ্ক (Facebook URL)
          <input name="social_facebook" value="{{ old('social_facebook', $settings->get('social_facebook')) }}" placeholder="https://facebook.com/yourpage">
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
            $currentHero = $settings->get('hero_banner') 
              ? (str_starts_with($settings->get('hero_banner'), 'assets/') ? asset($settings->get('hero_banner')) : \Illuminate\Support\Facades\Storage::url($settings->get('hero_banner'))) 
              : asset('assets/mc Banner.jpg');
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
            $currentAbout = $settings->get('about_image') 
              ? (str_starts_with($settings->get('about_image'), 'assets/') ? asset($settings->get('about_image')) : \Illuminate\Support\Facades\Storage::url($settings->get('about_image'))) 
              : asset('assets/MC Building Photo.jpg');
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
          $f1 = $settings->get('facility_1_image') ? (str_starts_with($settings->get('facility_1_image'), 'assets/') ? asset($settings->get('facility_1_image')) : \Illuminate\Support\Facades\Storage::url($settings->get('facility_1_image'))) : asset('assets/Computer Lab r.jpg');
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
          $f2 = $settings->get('facility_2_image') ? (str_starts_with($settings->get('facility_2_image'), 'assets/') ? asset($settings->get('facility_2_image')) : \Illuminate\Support\Facades\Storage::url($settings->get('facility_2_image'))) : asset('assets/RAC Lab.jpg');
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
          $f3 = $settings->get('facility_3_image') ? (str_starts_with($settings->get('facility_3_image'), 'assets/') ? asset($settings->get('facility_3_image')) : \Illuminate\Support\Facades\Storage::url($settings->get('facility_3_image'))) : asset('assets/Plumbing Class.jpg');
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
          $f4 = $settings->get('facility_4_image') ? (str_starts_with($settings->get('facility_4_image'), 'assets/') ? asset($settings->get('facility_4_image')) : \Illuminate\Support\Facades\Storage::url($settings->get('facility_4_image'))) : asset('assets/SMO Lab.jpg');
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
@endsection
