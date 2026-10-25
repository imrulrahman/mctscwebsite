@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
@extends('site.layout')

@section('title', $settings['contact_tab_title'] ?? ($isBn ? 'যোগাযোগ' : 'Contact Us'))
@section('page_title', $settings['contact_page_title'] ?? ($isBn ? 'আমাদের সাথে যোগাযোগ' : 'Contact Our Campus'))
@section('page_subtitle', $settings['contact_page_subtitle'] ?? ($isBn ? 'ভর্তি তথ্য, কোর্স বিবরণ অথবা যেকোনো প্রয়োজনে সরাসরি আমাদের ক্যাম্পাসে আসুন অথবা বার্তা পাঠান।' : 'Get in touch with our admissions office for course inquiries, campus visits, or general assistance.'))

@section('content')
<section class="section" style="padding: 40px 0 60px 0;">
  <div class="container">
    @if(session('success'))
      <div style="background: #f0fdf4; border: 1px solid #86efac; color: #166534; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px; font-weight: 600;">
        ✓ {{ session('success') }}
      </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 40px; align-items: start;">
      <!-- CONTACT DETAILS & ADDRESS -->
      <div>
        <span class="section-kicker">{{ $isBn ? 'ক্যাম্পাস ইনফরমেশন' : 'CAMPUS DIRECTORY' }}</span>
        <h2 style="font-family: var(--serif); font-size: 26px; color: var(--green); margin: 6px 0 16px 0;">
          {{ $settings['institution_name'] ?? 'Mahmudunnabi Chowdhury Technical School and College' }}
        </h2>
        <p style="font-size: 14.5px; color: #475569; line-height: 1.6; margin-bottom: 24px;">
          {{ $isBn ? 'যেকোনো ট্রেডে ভর্তি সংক্রান্ত পরামর্শের জন্য আমাদের এডমিশন কাউন্সিলিং সেলে সরাসরি যোগাযোগ করতে পারেন।' : 'Our counselors are available to guide you in choosing the most suitable technical career path.' }}
        </p>

        <div style="display: flex; flex-direction: column; gap: 18px; margin-bottom: 30px;">
          <div style="display: flex; align-items: flex-start; gap: 14px; background: #ffffff; border: 1px solid #e2ebe6; padding: 16px; border-radius: 8px;">
            <div style="width: 42px; height: 42px; border-radius: 8px; background: #e8f5ed; color: var(--green); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
              📍
            </div>
            <div>
              <strong style="display: block; font-size: 14px; color: #1e293b; margin-bottom: 3px;">{{ $isBn ? 'ইনস্টিটিউট ক্যাম্পাস ঠিকানা' : 'Campus Address' }}</strong>
              <span style="font-size: 13.5px; color: #475569; line-height: 1.5;">
                {{ $settings['address'] ?? '273/A/520, Chodu Chowdhury Road, Sagorika, Pahartali, Chattogram' }}
              </span>
            </div>
          </div>

          <div style="display: flex; align-items: flex-start; gap: 14px; background: #ffffff; border: 1px solid #e2ebe6; padding: 16px; border-radius: 8px;">
            <div style="width: 42px; height: 42px; border-radius: 8px; background: #e8f5ed; color: var(--green); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
              📞
            </div>
            <div>
              <strong style="display: block; font-size: 14px; color: #1e293b; margin-bottom: 3px;">{{ $isBn ? 'হেল্পলাইন ও ফোন নম্বর' : 'Admissions Helpline' }}</strong>
              <span style="font-size: 13.5px; color: #475569;">
                {{ $settings['phone'] ?? '+8801880919275' }}
              </span>
            </div>
          </div>

          <div style="display: flex; align-items: flex-start; gap: 14px; background: #ffffff; border: 1px solid #e2ebe6; padding: 16px; border-radius: 8px;">
            <div style="width: 42px; height: 42px; border-radius: 8px; background: #e8f5ed; color: var(--green); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
              ✉️
            </div>
            <div>
              <strong style="display: block; font-size: 14px; color: #1e293b; margin-bottom: 3px;">{{ $isBn ? 'ইমেইল এড্রেস' : 'Email Address' }}</strong>
              <span style="font-size: 13.5px; color: #475569;">
                {{ $settings['email'] ?? 'mnctsc@gmail.com' }}
              </span>
            </div>
          </div>

          <div style="display: flex; align-items: flex-start; gap: 14px; background: #ffffff; border: 1px solid #e2ebe6; padding: 16px; border-radius: 8px;">
            <div style="width: 42px; height: 42px; border-radius: 8px; background: #e8f5ed; color: var(--green); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
              ⏰
            </div>
            <div>
              <strong style="display: block; font-size: 14px; color: #1e293b; margin-bottom: 3px;">{{ $isBn ? 'অফিস সময়সূচী' : 'Office Hours' }}</strong>
              <span style="font-size: 13.5px; color: #475569;">
                {{ $isBn ? 'শনিবার হতে বৃহস্পতিবার: সকাল ৮:৩০ – বিকাল ৪:৩০ (শুক্রবার বন্ধ)' : 'Saturday – Thursday: 8:30 AM – 4:30 PM (Friday Closed)' }}
              </span>
            </div>
          </div>
        </div>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
          <strong style="display: block; font-size: 14px; color: #1e293b; margin-bottom: 6px;">
            {{ $isBn ? 'অনলাইনে সরাসরি ভর্তি হতে চান?' : 'Looking to Enroll Today?' }}
          </strong>
          <p style="font-size: 13px; color: #64748b; margin-bottom: 12px; line-height: 1.5;">
            {{ $isBn ? 'ক্যাম্পাসে না এসেও আপনি ঘরে বসেই অনলাইন ফরমের মাধ্যমে এডমিশন আবেদন সম্পন্ন করতে পারেন।' : 'Complete your trainee application online directly and download your printable admission copy.' }}
          </p>
          <a href="{{ route('apply') }}" class="button button-gold" style="display: block; text-align: center; font-size: 13px; font-weight: 700; text-decoration: none; border-radius: 6px; padding: 10px 14px;">
            {{ $isBn ? 'অনলাইন ভর্তি ফরম পূরণ করুন ➔' : 'Open Admission Form ➔' }}
          </a>
        </div>
      </div>

      <!-- INTERACTIVE CONTACT FORM -->
      <div style="background: #ffffff; border: 1px solid #d8e4dc; border-top: 4px solid var(--green); border-radius: 12px; padding: 32px; box-shadow: 0 4px 18px rgba(0,0,0,0.06);">
        <span class="section-kicker">{{ $isBn ? 'বার্তা পাঠান' : 'SEND A MESSAGE' }}</span>
        <h3 style="font-family: var(--serif); font-size: 22px; color: var(--green); margin: 6px 0 18px 0;">
          {{ $isBn ? 'আমাদের বার্তা লিখে পাঠান' : 'Leave Us a Message' }}
        </h3>

        <form method="post" action="{{ route('contact.store') }}" style="display: flex; flex-direction: column; gap: 16px;">
          @csrf
          <div>
            <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
              {{ $isBn ? 'আপনার পূর্ণ নাম' : 'Your Full Name' }} <span style="color: #dc2626;">*</span>
            </label>
            <input type="text" name="name" required maxlength="150" placeholder="{{ $isBn ? 'যেমন: মোহাম্মদ আরিফুল ইসলাম' : 'e.g. John Doe' }}" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
              <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                {{ $isBn ? 'ইমেইল এড্রেস' : 'Email Address' }} <span style="color: #dc2626;">*</span>
              </label>
              <input type="email" name="email" required placeholder="name@example.com" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>
            <div>
              <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                {{ $isBn ? 'মোবাইল নম্বর (ঐচ্ছিক)' : 'Phone Number (Optional)' }}
              </label>
              <input type="tel" name="phone" placeholder="018XXXXXXXX" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>
          </div>

          <div>
            <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
              {{ $isBn ? 'বিষয়' : 'Subject' }}
            </label>
            <input type="text" name="subject" maxlength="180" placeholder="{{ $isBn ? 'যেমন: নতুন সেশনে ভর্তি তথ্য' : 'e.g. Admission Inquiry' }}" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
              {{ $isBn ? 'আপনার বার্তা / প্রশ্ন' : 'Your Message' }} <span style="color: #dc2626;">*</span>
            </label>
            <textarea name="message" rows="5" required maxlength="3000" placeholder="{{ $isBn ? 'বিস্তারিত লিখুন...' : 'Write your inquiry or question...' }}" style="width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box; resize: vertical;"></textarea>
          </div>

          <button type="submit" class="button button-gold" style="width: 100%; padding: 13px; font-size: 15px; font-weight: 700; cursor: pointer; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); text-align: center; border: 0;">
            {{ $isBn ? 'বার্তা দাখিল করুন ➔' : 'Submit Message ➔' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
