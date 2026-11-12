@extends('site.layout')

@section('title', 'Applicant\'s Copy · ' . $application->tracking_no)
@section('page_title', $isBn ? 'চাকরির আবেদন কপি (Applicant\'s Copy)' : 'Applicant\'s Copy')
@section('page_subtitle', 'Tracking ID: ' . $application->tracking_no)

@section('content')
<style>
.ac-wrap {
  max-width: 900px;
  margin: 0 auto;
  padding: 28px 14px 60px;
}
.ac-card {
  background: #fff;
  border: 2px solid #0f5132;
  border-radius: 8px;
  padding: 28px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
}
.ac-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 20px;
  border-bottom: 2px solid #0f5132;
  padding-bottom: 16px;
  margin-bottom: 20px;
}
.ac-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 18px;
  font-size: 13px;
}
.ac-table th, .ac-table td {
  border: 1px solid #cbd5e1;
  padding: 8px 10px;
  text-align: left;
  vertical-align: top;
}
.ac-table th {
  background: #f1f5f9;
  color: #334155;
  font-weight: 700;
  width: 22%;
}
.ac-sec-title {
  background: #0f5132;
  color: #fff;
  padding: 7px 12px;
  font-weight: 700;
  font-size: 13.5px;
  border-radius: 4px 4px 0 0;
  margin-top: 16px;
}
@media print {
  header, footer, .page-hero, .no-print {
    display: none !important;
  }
  .ac-wrap {
    padding: 0;
    max-width: 100%;
  }
  .ac-card {
    box-shadow: none;
    border: 1px solid #000;
  }
}
</style>

<div class="ac-wrap">
  <div class="no-print" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
    <a href="{{ route('career') }}" style="color:#0f5132;font-weight:700;text-decoration:none;">← {{ $isBn ? 'ক্যারিয়ার পেইজে ফিরে যান' : 'Back to Career' }}</a>
    <button type="button" onclick="window.print()" style="background:#0f5132;color:#fff;border:none;padding:9px 20px;border-radius:6px;font-weight:700;cursor:pointer;">
      🖨️ {{ $isBn ? 'আবেদন কপি প্রিন্ট / ডাউনলোড করুন' : 'Print / Save Applicant\'s Copy' }}
    </button>
  </div>

  @if(session('success'))
    <div class="no-print" style="background:#ecfdf5;border:1px solid #10b981;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:18px;font-weight:600;">
      ✅ {{ session('success') }}
    </div>
  @endif

  <div class="ac-card">
    <div class="ac-top">
      <div>
        <div style="font-size:11.5px;font-weight:700;color:#0f5132;text-transform:uppercase;letter-spacing:0.6px;">Applicant's Copy (চাকরির আবেদন কপি)</div>
        <h2 style="margin:4px 0;font-size:20px;color:#0f172a;">{{ $settings->get('institution_name', 'Mahmudunnabi Chowdhury Technical School & College') }}</h2>
        <div style="font-size:13.5px;color:#334155;margin-top:6px;">
          <strong>Name of the Post:</strong> <span style="color:#0f5132;font-weight:700;">{{ $application->post_name }}</span>
        </div>
        <div style="font-size:13px;color:#334155;margin-top:4px;">
          <strong>Tracking ID:</strong> <span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:4px;font-weight:700;">{{ $application->tracking_no }}</span>
          &nbsp;|&nbsp;
          <strong>Submitted:</strong> {{ $application->created_at->format('d M Y, h:i A') }}
        </div>
      </div>
      <div style="text-align:center;flex-shrink:0;">
        @if($application->photo_path)
          <img src="{{ storage_asset($application->photo_path) }}" alt="Photo" style="width:110px;height:110px;object-fit:cover;border:1px solid #94a3b8;border-radius:4px;">
        @endif
      </div>
    </div>

    <div class="ac-sec-title">1. Personal Information</div>
    <table class="ac-table">
      <tr>
        <th>Applicant's Name</th>
        <td><strong>{{ $application->applicant_name }}</strong></td>
        <th>Date of Birth</th>
        <td>{{ $application->dob?->format('d M Y') }}</td>
      </tr>
      <tr>
        <th>Father's Name</th>
        <td>{{ $application->father_name }}</td>
        <th>Mother's Name</th>
        <td>{{ $application->mother_name }}</td>
      </tr>
      <tr>
        <th>Gender</th>
        <td>{{ $application->gender }}</td>
        <th>Religion</th>
        <td>{{ $application->religion }}</td>
      </tr>
      <tr>
        <th>Nationality</th>
        <td>{{ $application->nationality }}</td>
        <th>Marital Status</th>
        <td>{{ $application->marital_status }} {{ $application->spouse_name ? '(' . $application->spouse_name . ')' : '' }}</td>
      </tr>
      <tr>
        <th>NID / Birth Reg / Passport</th>
        <td colspan="3">
          {{ $application->nid_no ? 'NID: ' . $application->nid_no : '' }}
          {{ $application->birth_reg_no ? 'BRN: ' . $application->birth_reg_no : '' }}
          {{ $application->passport_no ? 'PP: ' . $application->passport_no : '' }}
          @if(!$application->nid_no && !$application->birth_reg_no && !$application->passport_no) — @endif
        </td>
      </tr>
      <tr>
        <th>Mobile Number</th>
        <td><strong>{{ $application->mobile_no }}</strong></td>
        <th>Email</th>
        <td>{{ $application->email }}</td>
      </tr>
    </table>

    <div class="ac-sec-title">2. Address Details</div>
    <table class="ac-table">
      <tr>
        <th>Present Address</th>
        <td>
          C/O: {{ $application->present_address['care_of'] ?? '—' }}<br>
          {{ $application->present_address['village'] ?? '' }}<br>
          Post: {{ $application->present_address['post_office'] ?? '' }} ({{ $application->present_address['post_code'] ?? '' }}),
          Upazila: {{ $application->present_address['upazila'] ?? '' }},
          District: {{ $application->present_address['district'] ?? '' }}
        </td>
        <th>Permanent Address</th>
        <td>
          C/O: {{ $application->permanent_address['care_of'] ?? '—' }}<br>
          {{ $application->permanent_address['village'] ?? '' }}<br>
          Post: {{ $application->permanent_address['post_office'] ?? '' }} ({{ $application->permanent_address['post_code'] ?? '' }}),
          Upazila: {{ $application->permanent_address['upazila'] ?? '' }},
          District: {{ $application->permanent_address['district'] ?? '' }}
        </td>
      </tr>
    </table>

    <div class="ac-sec-title">3. Educational Qualifications</div>
    <table class="ac-table">
      <thead>
        <tr>
          <th>Examination</th>
          <th>Board / University</th>
          <th>Roll / Duration</th>
          <th>Group / Subject</th>
          <th>Result</th>
          <th>Year</th>
        </tr>
      </thead>
      <tbody>
        @foreach(['ssc' => 'SSC / Equivalent', 'hsc' => 'HSC / Diploma', 'graduation' => 'Graduation', 'masters' => 'Masters'] as $key => $label)
          @php $edu = $application->education[$key] ?? null; @endphp
          @if(!empty($edu['exam']) || !empty($edu['board']) || !empty($edu['result']))
            <tr>
              <td><strong>{{ $edu['exam'] ?: $label }}</strong></td>
              <td>{{ $edu['board'] ?? '—' }}</td>
              <td>{{ $edu['roll'] ?? ($edu['duration'] ?? '—') }}</td>
              <td>{{ $edu['group'] ?? '—' }}</td>
              <td><strong>{{ $edu['result'] ?? '—' }}</strong></td>
              <td>{{ $edu['passing_year'] ?? '—' }}</td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>

    @if(!empty($application->experiences))
      <div class="ac-sec-title">4. Professional Experience</div>
      <table class="ac-table">
        <thead>
          <tr>
            <th>Designation</th>
            <th>Organization</th>
            <th>Duration</th>
            <th>Responsibilities</th>
          </tr>
        </thead>
        <tbody>
          @foreach($application->experiences as $exp)
            <tr>
              <td><strong>{{ $exp['designation'] ?? '—' }}</strong></td>
              <td>{{ $exp['organization'] ?? '—' }}</td>
              <td>{{ $exp['from_date'] ?? '—' }} to {{ !empty($exp['to_date']) ? $exp['to_date'] : 'Present' }}</td>
              <td>{{ $exp['responsibilities'] ?? '—' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif

    @if($application->other_qualifications)
      <div style="font-size:13px;margin-top:10px;padding:10px;background:#f8fafc;border:1px solid #cbd5e1;border-radius:4px;">
        <strong>Other Qualifications / Skills:</strong> {{ $application->other_qualifications }}
      </div>
    @endif

    <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-top:26px;padding-top:14px;border-top:1px dashed #cbd5e1;">
      <div style="font-size:12px;color:#475569;max-width:65%;">
        I hereby declare that all information provided above is true and accurate to the best of my knowledge.
      </div>
      <div style="text-align:center;">
        @if($application->signature_path)
          <img src="{{ storage_asset($application->signature_path) }}" alt="Signature" style="max-width:180px;max-height:55px;object-fit:contain;display:block;margin:0 auto 4px;">
        @endif
        <div style="border-top:1px solid #334155;font-size:11.5px;font-weight:700;padding-top:3px;">Applicant's Signature</div>
      </div>
    </div>
  </div>
</div>
@endsection
