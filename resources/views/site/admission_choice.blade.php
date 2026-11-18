@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
@extends('site.layout')

@section('title', $isBn ? 'ভর্তি আবেদন ফরম নির্বাচন করুন · মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Select Admission Category · Mahmudunnabi Chowdhury Technical School and College')
@section('page_title', $isBn ? 'ভর্তি আবেদন ফরম নির্বাচন' : 'Admission Application Form Selection')
@section('page_subtitle', $isBn ? 'আপনার প্রয়োজন অনুযায়ী নিয়মিত বা বিশেষ প্রজেক্ট ভর্তি ফরম নির্বাচন করুন।' : 'Choose your desired admission category to start your online application.')

@section('content')
<style>
.choice-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 40px 16px 70px;
}
.choice-head {
  text-align: center;
  margin-bottom: 35px;
}
.choice-head h2 {
  font-size: 26px;
  font-weight: 700;
  color: #14532d;
  margin: 0 0 10px 0;
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
}
.choice-head p {
  font-size: 15px;
  color: #64748b;
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.6;
}
.choice-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 26px;
  margin-bottom: 35px;
}
.choice-card {
  background: #ffffff;
  border: 2px solid #e2e8f0;
  border-radius: 14px;
  padding: 30px 26px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  text-decoration: none;
  color: inherit;
  transition: all 0.25s ease;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}
.choice-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
}
.choice-card.card-regular:hover {
  border-color: #0f5132;
}
.choice-card.card-nhrdf {
  border-color: #059669;
  background: linear-gradient(to bottom, #ffffff, #f0fdf4);
}
.choice-card.card-nhrdf:hover {
  border-color: #047857;
  box-shadow: 0 12px 28px rgba(5, 150, 105, 0.15);
}
.choice-pill {
  position: absolute;
  top: 16px;
  right: 18px;
  font-size: 11.5px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 3px 10px;
  border-radius: 20px;
}
.pill-regular {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #cbd5e1;
}
.pill-nhrdf {
  background: #ecfdf5;
  color: #047857;
  border: 1.5px solid #10b981;
}
.choice-icon {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  margin-bottom: 18px;
}
.icon-regular {
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
}
.icon-nhrdf {
  background: #ecfdf5;
  border: 1.5px solid #86efac;
}
.choice-card h3 {
  font-size: 20px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 8px 0;
}
.choice-card p {
  font-size: 14px;
  color: #64748b;
  line-height: 1.6;
  margin: 0 0 18px 0;
}
.trades-badge-box {
  background: #ffffff;
  border: 1px solid #a7f3d0;
  border-radius: 8px;
  padding: 12px 14px;
  margin-bottom: 22px;
}
.trades-badge-title {
  font-size: 12px;
  font-weight: 700;
  color: #047857;
  text-transform: uppercase;
  letter-spacing: 0.4px;
  margin-bottom: 6px;
  display: block;
}
.trades-badge-box ul {
  margin: 0;
  padding-left: 18px;
  font-size: 13.5px;
  color: #1e293b;
  font-weight: 600;
}
.trades-badge-box li {
  margin-bottom: 4px;
}
.choice-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 12px 20px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 14.5px;
  transition: all 0.2s ease;
}
.btn-regular {
  background: #0f5132;
  color: #ffffff;
}
.choice-card.card-regular:hover .btn-regular {
  background: #093722;
}
.btn-nhrdf {
  background: #059669;
  color: #ffffff;
}
.choice-card.card-nhrdf:hover .btn-nhrdf {
  background: #047857;
}
.help-box {
  text-align: center;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 16px 20px;
  font-size: 14px;
  color: #475569;
}
</style>

<div class="choice-container">
  <div class="choice-head">
    <h2>{{ $isBn ? 'ভর্তি আবেদন ফরম নির্বাচন করুন' : 'Select Admission Form' }}</h2>
    <p>{{ $isBn ? 'আপনার প্রয়োজন অনুযায়ী রেগুলার ভর্তি ফরম অথবা NHRDF বিশেষ প্রজেক্ট ভর্তি ফরম নির্বাচন করুন:' : 'Please choose between the regular technical admission or the NHRDF subsidized project form below:' }}</p>
  </div>

  <div class="choice-grid">
    {{-- Card 1: Regular Form --}}
    <a href="{{ route('apply') }}" class="choice-card card-regular">
      <div>
        <span class="choice-pill pill-regular">{{ $isBn ? 'সাধারণ ভর্তি' : 'Regular' }}</span>
        <div class="choice-icon icon-regular">🎓</div>
        <h3>{{ $isBn ? 'রেগুলার ভর্তি আবেদন ফরম' : 'Regular Admission Form' }}</h3>
        <p>
          {{ $isBn 
            ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজের নিয়মিত বিভিন্ন কারিগরি ট্রেড ও পেশাদার শর্ট কোর্সে সাধারণ ভর্তির আবেদন।' 
            : 'General admission for regular technical trades, vocational diplomas, and institutional short courses.' }}
        </p>
      </div>
      <div>
        <span class="choice-btn btn-regular">
          {{ $isBn ? 'রেগুলার ভর্তি ফরমে যান' : 'Go to Regular Form' }} ➔
        </span>
      </div>
    </a>

    {{-- Card 2: NHRDF Project Form --}}
    <a href="{{ route('apply.nhrdf') }}" class="choice-card card-nhrdf">
      <div>
        <span class="choice-pill pill-nhrdf">{{ $isBn ? 'বিশেষ প্রজেক্ট' : 'Special Project' }}</span>
        <div class="choice-icon icon-nhrdf">🏛️</div>
        <h3 style="color:#047857;">{{ $isBn ? 'NHRDF প্রজেক্ট ভর্তি ফরম' : 'NHRDF Project Form' }}</h3>
        <p>
          {{ $isBn 
            ? 'জাতীয় মানবসম্পদ উন্নয়ন তহবিল (NHRDF) বিশেষ প্রকল্পের অধীনে সম্পূর্ণ সরকারি খরচে ও বৃত্তিসহ ৩টি নির্দিষ্ট ট্রেডে ভর্তির আবেদন:' 
            : 'National Human Resource Development Fund (NHRDF) subsidized vocational training project with scholarship.' }}
        </p>
        <div class="trades-badge-box">
          <span class="trades-badge-title">{{ $isBn ? 'উপলব্ধ ট্রেডসমূহ (Available Trades):' : 'Available Trades:' }}</span>
          <ul>
            <li>Plumbing, Level-02</li>
            <li>Tailoring & Dress Making, Level-02</li>
            <li>Driving, Level-03</li>
          </ul>
        </div>
      </div>
      <div>
        <span class="choice-btn btn-nhrdf">
          {{ $isBn ? 'NHRDF ফরমে যান' : 'Go to NHRDF Form' }} ➔
        </span>
      </div>
    </a>
  </div>

  <div class="help-box">
    📞 {{ $isBn ? 'ভর্তি সংক্রান্ত যে কোনো তথ্যের জন্য আমাদের হেল্পলাইনে যোগাযোগ করুন:' : 'For admission inquiries or technical assistance, call:' }}
    <strong style="color:#0f5132; margin-left:6px;">01880919275</strong>
  </div>
</div>
@endsection
