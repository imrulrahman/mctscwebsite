@extends('site.layout')

@section('title', $isBn ? 'প্রার্থী লগইন · মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Candidate Portal Login · Mahmudunnabi Chowdhury Technical School and College')
@section('page_title', $isBn ? 'প্রার্থী লগইন ও অ্যাপ্লিকেশন পোর্টাল' : 'Candidate Login & Portal')
@section('page_subtitle', $isBn ? 'আবেদনের স্ট্যাটাস দেখতে এবং হালনাগাদ সিভি আপলোড করতে লগইন করুন।' : 'Login with your Application Tracking ID and Mobile Number to track status and update your CV.')

@section('content')
<style>
.cand-login-wrap {
  max-width: 520px;
  margin: 40px auto 70px;
  padding: 0 16px;
}
.cand-login-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #dbe6e0;
  box-shadow: 0 10px 30px rgba(15, 81, 50, 0.08);
  overflow: hidden;
}
.cand-login-head {
  background: linear-gradient(135deg, #0f5132 0%, #093722 100%);
  color: #ffffff;
  padding: 24px 28px;
  text-align: center;
}
.cand-login-head h2 {
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
  font-size: 21px;
  margin: 0 0 6px 0;
  color: #ffffff;
}
.cand-login-head p {
  margin: 0;
  font-size: 13.5px;
  color: #d1fae5;
  line-height: 1.5;
}
.cand-login-body {
  padding: 28px;
}
.cand-form-group {
  margin-bottom: 18px;
}
.cand-form-group label {
  display: block;
  font-weight: 700;
  font-size: 13.5px;
  color: #1e293b;
  margin-bottom: 6px;
}
.cand-form-group label span {
  color: #dc2626;
}
.cand-input {
  width: 100%;
  padding: 11px 14px;
  border: 1.5px solid #cbd5e1;
  border-radius: 8px;
  font-size: 14.5px;
  color: #0f172a;
  background: #f8fafc;
  transition: all 0.2s ease;
  box-sizing: border-box;
}
.cand-input:focus {
  border-color: #0f5132;
  background: #ffffff;
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 81, 50, 0.15);
}
.cand-btn-submit {
  width: 100%;
  background: #0f5132;
  color: #ffffff;
  border: none;
  padding: 12px 20px;
  font-size: 15px;
  font-weight: 700;
  border-radius: 8px;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(15, 81, 50, 0.25);
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.cand-btn-submit:hover {
  background: #093722;
  transform: translateY(-1px);
}
.cand-alert-error {
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #991b1b;
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 13.5px;
  margin-bottom: 18px;
  line-height: 1.5;
}
.cand-info-box {
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  border-radius: 8px;
  padding: 14px 16px;
  margin-top: 22px;
  font-size: 13px;
  color: #166534;
  line-height: 1.6;
}
.cand-info-box strong {
  display: block;
  margin-bottom: 4px;
}
</style>

<div class="cand-login-wrap">
  <div class="cand-login-card">
    <div class="cand-login-head">
      <div style="font-size: 32px; margin-bottom: 6px;">💼</div>
      <h2>{{ $isBn ? 'প্রার্থী পোর্টাল লগইন' : 'Candidate Portal Login' }}</h2>
      <p>{{ $isBn ? 'আপনার চাকরির আবেদনের ট্র্যাকিং নম্বর ও মোবাইল নম্বর দিয়ে লগইন করুন' : 'Sign in using your Application Tracking ID & Mobile Number' }}</p>
    </div>

    <div class="cand-login-body">
      @if($errors->has('login_error'))
        <div class="cand-alert-error">
          ⚠️ {{ $errors->first('login_error') }}
        </div>
      @endif

      @if(session('info'))
        <div style="background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af; padding:12px 16px; border-radius:8px; font-size:13.5px; margin-bottom:18px;">
          ℹ️ {{ session('info') }}
        </div>
      @endif

      <form method="POST" action="{{ route('candidate.login.submit') }}">
        @csrf

        <div class="cand-form-group">
          <label for="tracking_no">
            {{ $isBn ? 'আবেদনের ট্র্যাকিং নম্বর (Tracking ID)' : 'Application Tracking ID' }} <span>*</span>
          </label>
          <input type="text" id="tracking_no" name="tracking_no" class="cand-input" required 
                 placeholder="e.g. JOB-2026-XXXXXX" value="{{ old('tracking_no', request('tracking_no')) }}" autofocus>
          @error('tracking_no')
            <small style="color:#dc2626; font-size:12px; display:block; margin-top:4px;">{{ $message }}</small>
          @enderror
        </div>

        <div class="cand-form-group">
          <label for="mobile_no">
            {{ $isBn ? 'আবেদনে প্রদত্ত মোবাইল নম্বর (Mobile Number)' : 'Registered Mobile Number' }} <span>*</span>
          </label>
          <input type="tel" id="mobile_no" name="mobile_no" class="cand-input" required 
                 placeholder="e.g. 018XXXXXXXX" value="{{ old('mobile_no', request('mobile')) }}">
          @error('mobile_no')
            <small style="color:#dc2626; font-size:12px; display:block; margin-top:4px;">{{ $message }}</small>
          @enderror
        </div>

        <button type="submit" class="cand-btn-submit">
          <span>🔒 {{ $isBn ? 'ড্যাশবোর্ডে প্রবেশ করুন' : 'Access Candidate Portal' }}</span> →
        </button>
      </form>

      <div class="cand-info-box">
        <strong>💡 {{ $isBn ? 'সহায়তা ও তথ্য:' : 'Help & Information:' }}</strong>
        {{ $isBn 
          ? 'অনলাইনে আবেদন সাবমিট করার পর পাওয়া ট্র্যাকিং নম্বরটি ব্যবহার করুন। লগইন করার পর আপনি সকল আবেদনের স্ট্যাটাস দেখতে পারবেন এবং যেকোনো সময় আপনার সর্বশেষ হালনাগাদ সিভি (CV) আপলোড করতে পারবেন।'
          : 'Use the Tracking ID provided after submitting your online application. Once logged in, you can track real-time application status and upload your updated CV.' }}
      </div>

      <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('career') }}" style="color: #0f5132; font-size: 13.5px; font-weight: 600; text-decoration: none;">
          ← {{ $isBn ? 'ক্যারিয়ার নিয়োগ বিজ্ঞপ্তি পেজে ফিরুন' : 'Back to Career Circulars' }}
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
