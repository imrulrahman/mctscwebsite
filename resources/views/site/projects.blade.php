@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
@extends('site.layout')

@section('title', $settings['projects_tab_title'] ?? ($isBn ? 'প্রজেক্টস ও পার্টনারশিপ' : 'Projects & Training Initiatives'))
@section('page_title', $settings['projects_page_title'] ?? ($isBn ? 'প্রজেক্টস ও উন্নয়নমূলক প্রশিক্ষণ কর্মসূচি' : 'Projects & Development Initiatives'))
@section('page_subtitle', $settings['projects_page_subtitle'] ?? ($isBn ? 'সরকারি ও আন্তর্জাতিক উন্নয়ন সহযোগীদের অর্থায়নে বিনামূল্যে কর্মমুখী কারিগরি প্রশিক্ষণ ও বৃত্তি কার্যক্রম।' : 'Collaborative training programs supported by government ministries and international development partners.'))

@section('content')
<section class="section" style="padding: 40px 0 60px 0;">
  <div class="container">
    <div style="margin-bottom: 36px; text-align: center;">
      <span class="section-kicker">{{ $isBn ? 'বিশেষ স্কলারশিপ ইনিশিয়েটিভ' : 'SPECIAL SCHOLARSHIPS & INITIATIVES' }}</span>
      <h2 style="font-family: var(--serif); font-size: 28px; color: var(--green); margin: 6px 0 12px 0;">
        {{ $isBn ? 'চলমান প্রজেক্ট ও পার্টনারশিপ কর্মসূচি' : 'Active Projects & Partnership Programs' }}
      </h2>
      <p style="font-size: 15px; color: #64748b; max-width: 700px; margin: 0 auto; line-height: 1.6;">
        {{ $isBn 
          ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ বিভিন্ন জাতীয় ও আন্তর্জাতিক প্রকল্পের আওতায় সুবিধাবঞ্চিত যুব ও নারীদের দক্ষ জনশক্তিতে রূপান্তরে প্রশিক্ষণ পরিচালনা করছে।' 
          : 'MCTSC partners with leading national development agencies to provide funded skills training, empowering youth with high-demand vocational trade certifications.' }}
      </p>
    </div>

    <!-- PROJECTS GRID -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 50px;">
      @forelse($projects as $project)
        @php
          $pImg = $project->image ? storage_asset($project->image) : null;
        @endphp
        <div style="background: #ffffff; border: 1px solid #d8e4dc; border-top: 4px solid var(--green); border-radius: 10px; padding: 26px; box-shadow: 0 4px 14px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
              @if($pImg)
                <img src="{{ $pImg }}" alt="{{ $project->title }}" style="width: 52px; height: 52px; object-fit: contain; background: #f8fafc; padding: 4px; border-radius: 8px; border: 1px solid #e2e8f0; flex-shrink: 0;">
              @else
                <div style="width: 52px; height: 52px; border-radius: 8px; background: #e8f5ed; color: var(--green); display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700; flex-shrink: 0;">
                  ⌁
                </div>
              @endif
              <div>
                <h3 style="font-family: var(--serif); font-size: 19px; color: #1e3d35; margin: 0 0 4px 0;">
                  {{ $project->title }}
                </h3>
                <span style="font-size: 12px; color: #64748b; font-weight: 600;">
                  {{ $isBn ? 'সরকারি / উন্নয়ন সহযোগী প্রজেক্ট' : 'Govt / Development Partner Project' }}
                </span>
              </div>
            </div>

            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 18px 0;">
              {{ $project->excerpt ?: ($isBn ? 'এই প্রজেক্টের আওতায় উপযুক্ত ট্রেডে সম্পূর্ণ বিনামূল্যে প্রশিক্ষণ, মাসিক বৃত্তি এবং সনদ প্রদান করা হয়।' : 'Under this initiative, trainees receive free skills training, official stipends, and nationally recognized accreditation.') }}
            </p>

            <div style="background: #f8faf9; border: 1px solid #e8f0ec; border-radius: 6px; padding: 12px; margin-bottom: 16px; font-size: 12.5px; color: #334155; display: flex; flex-direction: column; gap: 6px;">
              <div>✓ <strong>{{ $isBn ? 'কোর্স ফি:' : 'Course Fee:' }}</strong> {{ $isBn ? 'সম্পূর্ণ বিনামূল্যে (১০০% স্কলারশিপ)' : '100% Free Scholarship' }}</div>
              <div>✓ <strong>{{ $isBn ? 'দৈনিক ভাতা/বৃত্তি:' : 'Stipend:' }}</strong> {{ $isBn ? 'প্রজেক্ট নিয়মাবলী অনুযায়ী প্রযোজ্য' : 'Applicable as per project guidelines' }}</div>
              <div>✓ <strong>{{ $isBn ? 'সনদপত্র:' : 'Certificate:' }}</strong> {{ $isBn ? 'BTEB / NSDA স্বীকৃত সনদ' : 'BTEB / NSDA Recognized' }}</div>
            </div>
          </div>

          <a href="{{ route('apply') }}" class="button button-gold" style="display: block; text-align: center; font-size: 13px; font-weight: 700; text-decoration: none; border-radius: 6px; padding: 10px 14px;">
            {{ $isBn ? 'প্রজেক্টে ভর্তির জন্য আবেদন করুন ➔' : 'Apply for this Project ➔' }}
          </a>
        </div>
      @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1;">
          <p style="color: #64748b; font-size: 16px;">{{ $isBn ? 'বর্তমানে কোনো প্রজেক্ট তালিকাভুক্ত নেই।' : 'No active projects listed at this time.' }}</p>
        </div>
      @endforelse
    </div>

    <!-- HIGHLIGHT INFORMATION BOX -->
    <div style="background: linear-gradient(135deg, var(--green-deep), var(--green)); color: #fff; padding: 36px 32px; border-radius: 10px; display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; align-items: center; box-shadow: 0 6px 20px rgba(15,81,50,0.18);">
      <div>
        <h3 style="font-family: var(--serif); font-size: 22px; font-weight: 700; margin: 0 0 10px 0;">
          {{ $isBn ? 'প্রজেক্ট ও স্কলারশিপে ভর্তি প্রক্রিয়া' : 'How Project Admissions Work' }}
        </h3>
        <p style="font-size: 14px; color: rgba(255,255,255,0.88); line-height: 1.6; margin: 0 0 12px 0;">
          {{ $isBn 
            ? 'অনলাইনে আবেদন করার পর ইনস্টিটিউটের এডমিশন সেল আবেদনকারীর তথ্য যাচাই-বাছাই করবে। প্রজেক্ট কোটা অনুযায়ী প্রজেক্টের সুবিধাসমূহ প্রদান করা হয়। আগ্রহী প্রার্থীরা অনলাইনে বা সরাসরি কলেজে এসে আবেদন করতে পারেন।'
            : 'After online submission, our admission office verifies the applicant credentials and confirms project eligibility. Scholarships are granted on a first-come, merit-assessed basis.' }}
        </p>
        <span style="font-size: 13px; color: var(--gold); font-weight: 700;">
          {{ $isBn ? 'যোগাযোগ ও তথ্য সহায়তা: ' . ($settings['phone'] ?? '+8801880919275') : 'Admissions Helpline: ' . ($settings['phone'] ?? '+8801880919275') }}
        </span>
      </div>
      <div style="text-align: right;">
        <a href="{{ route('apply') }}" class="button button-gold" style="font-size: 15px; font-weight: 700; padding: 14px 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
          {{ $isBn ? 'অনলাইন ভর্তি ফরম পূরণ করুন ➔' : 'Proceed to Application Form ➔' }}
        </a>
      </div>
    </div>
  </div>
</section>
@endsection
