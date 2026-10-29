@extends('site.layout')

@section('title', $isBn ? 'ক্যারিয়ার ও নিয়োগ বিজ্ঞপ্তি · মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজ' : 'Career Opportunities · Mahmudunnabi Chowdhury Technical School and College')
@section('page_title', $isBn ? 'ক্যারিয়ার ও নিয়োগ বিজ্ঞপ্তি' : 'Career Opportunities')
@section('page_subtitle', $isBn ? 'মাহমুদুন্নবী চৌধুরী টেকনিক্যাল স্কুল এন্ড কলেজে দক্ষ, অভিজ্ঞ ও পেশাদার প্রশিক্ষক/কর্মকর্তা হিসেবে যোগ দিন।' : 'Join our dedicated team of educators, technical instructors, and TVET professionals.')

@section('content')
<style>
.career-container {
  max-width: 1050px;
  margin: 0 auto;
  padding: 36px 16px 60px;
}
.career-card {
  background: #ffffff;
  border: 1px solid #dbe6e0;
  border-left: 5px solid var(--green, #0f5132);
  border-radius: 10px;
  padding: 24px 28px;
  margin-bottom: 22px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.career-card:hover {
  box-shadow: 0 8px 24px rgba(15, 81, 50, 0.08);
}
.career-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  border-bottom: 1px dashed #e2e8f0;
  padding-bottom: 16px;
  margin-bottom: 16px;
}
.career-title {
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
  font-size: 20px;
  color: #14532d;
  margin: 0 0 6px 0;
  font-weight: 700;
}
.career-badges {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}
.career-badge {
  font-size: 11.5px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 4px;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}
.badge-category {
  background: #ecfdf5;
  color: #065f46;
  border: 1px solid #a7f3d0;
}
.badge-deadline {
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}
.badge-active {
  background: #eff6ff;
  color: #1e40af;
  border: 1px solid #bfdbfe;
}
.career-meta-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
  margin: 14px 0 16px 0;
  background: #f8fafc;
  padding: 12px 16px;
  border-radius: 6px;
  border: 1px solid #edf2f7;
  font-size: 13px;
  color: #334155;
}
.career-meta-item {
  display: flex;
  align-items: center;
  gap: 8px;
}
.career-meta-item strong {
  color: #0f5132;
}
.career-body {
  font-size: 14.5px;
  line-height: 1.7;
  color: #334155;
  margin-bottom: 22px;
}
.career-body h2,
.career-body h3,
.career-body h4 {
  color: var(--green, #0f5132);
  font-size: 16px;
  font-weight: 700;
  margin: 18px 0 8px 0;
  display: flex;
  align-items: center;
  gap: 7px;
}
.career-body h4::before,
.career-body h3::before {
  content: "▪";
  color: #bd9635;
  font-size: 16px;
  line-height: 1;
}
.career-body p {
  margin: 0 0 10px 0;
  line-height: 1.65;
}
.career-body ul,
.career-body ol {
  margin: 6px 0 14px 20px;
  padding-left: 6px;
}
.career-body li {
  margin-bottom: 6px;
  line-height: 1.55;
}
.career-body strong,
.career-body b {
  color: #0f172a;
  font-weight: 600;
}
.career-body a {
  color: var(--green, #0f5132);
  font-weight: 600;
  text-decoration: underline;
}
.career-body a:hover {
  color: #bd9635;
}
.career-actions {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
  padding-top: 14px;
  border-top: 1px solid #f1f5f9;
}
.btn-apply {
  background: var(--green, #0f5132);
  color: #ffffff !important;
  font-weight: 700;
  font-size: 14px;
  padding: 10px 22px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  box-shadow: 0 2px 6px rgba(15, 81, 50, 0.2);
  transition: background 0.2s ease, transform 0.2s ease;
}
.btn-apply:hover {
  background: var(--green-deep, #093722);
  transform: translateY(-1px);
}
.btn-details {
  background: #f1f5f9;
  color: #1e293b !important;
  font-weight: 600;
  font-size: 13.5px;
  padding: 9px 18px;
  border-radius: 6px;
  border: 1px solid #cbd5e1;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  text-decoration: none;
}
.btn-details:hover {
  background: #e2e8f0;
}
.career-empty-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 50px 30px;
  text-align: center;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
  max-width: 680px;
  margin: 30px auto 0;
}
.empty-icon {
  font-size: 52px;
  margin-bottom: 16px;
  display: inline-block;
  opacity: 0.85;
}
.empty-title {
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
  font-size: 22px;
  color: #1e293b;
  margin: 0 0 10px 0;
  font-weight: 700;
}
.empty-desc {
  font-size: 14.5px;
  color: #64748b;
  line-height: 1.6;
  max-width: 520px;
  margin: 0 auto 20px;
}
.empty-contact-pill {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  padding: 10px 20px;
  border-radius: 30px;
  font-size: 13.5px;
  color: #1e293b;
}
.empty-contact-pill a {
  color: var(--green, #0f5132);
  font-weight: 700;
  text-decoration: none;
}
.empty-contact-pill a:hover {
  text-decoration: underline;
}

/* Modal styling */
.apply-modal {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  z-index: 99999;
  align-items: center;
  justify-content: center;
  padding: 20px;
  backdrop-filter: blur(3px);
}
.apply-modal.active {
  display: flex;
}
.apply-modal-box {
  background: #ffffff;
  border-radius: 10px;
  width: 100%;
  max-width: 580px;
  max-height: 90vh;
  overflow-y: auto;
  padding: 26px 30px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
  position: relative;
}
.modal-close {
  position: absolute;
  top: 18px;
  right: 20px;
  background: #f1f5f9;
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  font-size: 18px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #64748b;
}
.modal-close:hover {
  background: #e2e8f0;
  color: #0f172a;
}
.apply-field {
  margin-bottom: 14px;
}
.apply-field label {
  display: block;
  font-weight: 700;
  font-size: 13px;
  color: #1e293b;
  margin-bottom: 5px;
}
.apply-field input,
.apply-field textarea {
  width: 100%;
  padding: 9px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 14px;
  box-sizing: border-box;
}
.apply-field input:focus,
.apply-field textarea:focus {
  border-color: var(--green, #0f5132);
  outline: none;
  box-shadow: 0 0 0 3px rgba(15, 81, 50, 0.12);
}
</style>

<div class="career-container">
  @if(session('status'))
    <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 14px 18px; border-radius: 8px; margin-bottom: 22px; font-weight: 600; font-size: 14px;">
      ✓ {{ session('status') }}
    </div>
  @endif

  @if($circulars->isEmpty())
    <!-- EMPTY STATE WHEN NO RECRUITMENT CIRCULAR -->
    <div class="career-empty-card">
      <div class="empty-icon">💼</div>
      <h3 class="empty-title">{{ $isBn ? 'বর্তমানে কোনো নিয়োগ বিজ্ঞপ্তি নেই' : 'No recruitment circular is available' }}</h3>
      <p class="empty-desc">
        {{ $isBn 
          ? 'এই মুহূর্তে প্রতিষ্ঠানে কোনো উন্মুক্ত নিয়োগ বিজ্ঞপ্তি প্রকাশিত নেই। নতুন কোনো পদের সার্কুলার প্রকাশিত হলে এখানে তা দেখতে পাবেন। স্বতঃপ্রণোদিত সিভি পাঠাতে নিচে দেওয়া ইমেইলে যোগাযোগ করতে পারেন।'
          : 'There are currently no active job vacancies or recruitment circulars at this moment. Please check back later or send your spontaneous CV for future opportunities.' }}
      </p>
      <div class="empty-contact-pill">
        <span>📧 {{ $isBn ? 'সিভি পাঠাতে:' : 'Direct CV submission:' }}</span>
        <a href="mailto:career@mctsc.edu.bd">career@mctsc.edu.bd</a>
      </div>
    </div>
  @else
    <!-- LIST OF ACTIVE JOB CIRCULARS -->
    @foreach($circulars as $job)
      <article class="career-card" id="job-{{ $job->id }}">
        <div class="career-header">
          <div>
            <h2 class="career-title" data-live-edit data-live-content-id="{{ $job->id }}" data-live-content-field="title" data-live-type="text" data-live-title="Job: {{ $job->title }}" data-live-location="Career">{{ $job->title }}</h2>
            <div class="career-badges">
              @if($job->category)
                <span class="career-badge badge-category">{{ $job->category }}</span>
              @endif
              @if($job->ends_at)
                <span class="career-badge badge-deadline">
                  ⏰ {{ $isBn ? 'শেষ তারিখ:' : 'Deadline:' }} {{ $job->ends_at->format('d M, Y') }}
                </span>
              @else
                <span class="career-badge badge-active">{{ $isBn ? 'চলমান' : 'Open' }}</span>
              @endif
            </div>
          </div>
        </div>

        <div class="career-meta-grid">
          <div class="career-meta-item">
            <span>📍</span>
            <div><strong>{{ $isBn ? 'কর্মস্থল:' : 'Location:' }}</strong> {{ $isBn ? 'সাগরিকা, পাহাড়তলী, চট্টগ্রাম' : 'Sagorika, Pahartali, Chattogram' }}</div>
          </div>
          <div class="career-meta-item">
            <span>📅</span>
            <div><strong>{{ $isBn ? 'প্রকাশের তারিখ:' : 'Published:' }}</strong> {{ $job->starts_at ? $job->starts_at->format('d M, Y') : $job->created_at->format('d M, Y') }}</div>
          </div>
          @if($job->ends_at)
            <div class="career-meta-item">
              <span>⏳</span>
              <div><strong>{{ $isBn ? 'আবেদনের শেষ সময়:' : 'Deadline:' }}</strong> {{ $job->ends_at->format('d M, Y (h:i A)') }}</div>
            </div>
          @endif
        </div>

        @if($job->excerpt)
          <div data-live-edit data-live-content-id="{{ $job->id }}" data-live-content-field="excerpt" data-live-type="textarea" data-live-title="Job Summary: {{ $job->title }}" data-live-location="Career" style="font-weight: 600; color: #1e293b; font-size: 14.5px; margin-bottom: 14px; line-height: 1.55;">
            {{ $job->excerpt }}
          </div>
        @endif

        @if($job->body)
          <div class="career-body" data-live-edit data-live-content-id="{{ $job->id }}" data-live-content-field="body" data-live-type="rich" data-live-title="Circular Body: {{ $job->title }}" data-live-location="Career">
            @if(strip_tags($job->body) !== $job->body)
              {!! $job->body !!}
            @else
              {!! nl2br(e($job->body)) !!}
            @endif
          </div>
        @endif

        <div class="career-actions">
          <button type="button" class="btn-apply" onclick="openApplyModal('{{ addslashes($job->title) }}')">
            <span>📝</span> {{ $isBn ? 'আবেদন করুন (Apply Now)' : 'Apply Now' }}
          </button>
          <a href="mailto:career@mctsc.edu.bd?subject={{ urlencode('Application for ' . $job->title) }}" class="btn-details">
            <span>✉️</span> {{ $isBn ? 'ইমেইলে সিভি পাঠান' : 'Apply via Email' }}
          </a>
        </div>
      </article>
    @endforeach
  @endif
</div>

<!-- APPLY MODAL -->
<div class="apply-modal" id="applyModal" onclick="if(event.target === this) closeApplyModal()">
  <div class="apply-modal-box">
    <button type="button" class="modal-close" onclick="closeApplyModal()">&times;</button>
    <h3 style="font-family: var(--serif, 'Libre Baskerville', Georgia, serif); font-size: 20px; color: var(--green, #0f5132); margin: 0 0 6px 0;">
      {{ $isBn ? 'অনলাইন চাকরির আবেদন' : 'Online Job Application' }}
    </h3>
    <p style="font-size: 13px; color: #64748b; margin: 0 0 18px 0;" id="modalJobSubtitle">
      {{ $isBn ? 'আবেদনের পদ:' : 'Position:' }} <strong id="modalJobTitleText"></strong>
    </p>

    <form method="post" action="{{ route('contact.store') }}">
      @csrf
      <input type="hidden" name="subject" id="modalSubject" value="Job Application">
      
      <div class="apply-field">
        <label>{{ $isBn ? 'আপনার পূর্ণ নাম' : 'Full Name' }} <span style="color: #dc2626;">*</span></label>
        <input type="text" name="name" required placeholder="{{ $isBn ? 'যেমন: মোহাম্মদ রহিম' : 'e.g. John Doe' }}">
      </div>

      <div class="apply-field">
        <label>{{ $isBn ? 'ইমেইল অ্যাড্রেস' : 'Email Address' }} <span style="color: #dc2626;">*</span></label>
        <input type="email" name="email" required placeholder="name@example.com">
      </div>

      <div class="apply-field">
        <label>{{ $isBn ? 'মোবাইল / হোয়াটসঅ্যাপ নম্বর' : 'Phone / WhatsApp' }} <span style="color: #dc2626;">*</span></label>
        <input type="tel" name="phone" required placeholder="01XXXXXXXXX">
      </div>

      <div class="apply-field">
        <label>{{ $isBn ? 'যোগ্যতা ও অভিজ্ঞতা সংক্ষেপ / কভার নোট' : 'Qualifications, Experience & CV Details' }} <span style="color: #dc2626;">*</span></label>
        <textarea name="message" rows="4" required placeholder="{{ $isBn ? 'আপনার শিক্ষাগত যোগ্যতা, ডিপ্লোমা/সার্টিফিকেশন, অভিজ্ঞতা এবং সিভি / পোর্টফোলিও লিংক লিখুন...' : 'Briefly describe your relevant education, TVET diploma/certificates, years of experience, and Google Drive CV link...' }}"></textarea>
      </div>

      <div style="background: #f8fafc; border: 1px dashed #cbd5e1; padding: 10px 14px; border-radius: 6px; font-size: 12px; color: #64748b; margin-bottom: 18px;">
        💡 {{ $isBn ? 'সিভির গুগল ড্রাইভ / ড্রপবক্স লিংক উপরে উল্লেখ করতে পারেন অথবা আবেদন করার পর career@mctsc.edu.bd ঠিকানায় সরাসরি সিভি এটাচ করে পাঠাতে পারেন।' : 'You may include your Google Drive / Dropbox CV link in the box above, or email your resume directly to career@mctsc.edu.bd.' }}
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="btn-details" onclick="closeApplyModal()">{{ $isBn ? 'বাতিল' : 'Cancel' }}</button>
        <button type="submit" class="btn-apply">{{ $isBn ? 'আবেদন জমা দিন' : 'Submit Application' }}</button>
      </div>
    </form>
  </div>
</div>

<script>
function openApplyModal(jobTitle) {
  document.getElementById('modalJobTitleText').textContent = jobTitle;
  document.getElementById('modalSubject').value = 'Job Application: ' + jobTitle;
  document.getElementById('applyModal').classList.add('active');
}
function closeApplyModal() {
  document.getElementById('applyModal').classList.remove('active');
}
</script>
@endsection
