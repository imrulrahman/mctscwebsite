@extends('admin.layout')
@section('title', 'Application ' . $application->tracking_no)
@section('crumb', 'Job Application Details')

@section('content')
<div class="page-head">
  <div>
    <h1>{{ $application->applicant_name }}</h1>
    <p>Tracking ID: <strong>{{ $application->tracking_no }}</strong> · Applied Post: <strong>{{ $application->post_name }}</strong></p>
  </div>
  <div style="display:flex;gap:10px;">
    <a class="btn light" href="{{ route('admin.job-applications.index') }}">← Back to List</a>
    <a class="btn light" href="{{ route('career.apply.confirmation', $application->tracking_no) }}" target="_blank">🖨️ Printable Applicant's Copy</a>
  </div>
</div>

<div class="dash-grid">
  <section class="panel" style="grid-column: 1 / -1;">
    <div style="padding:20px;">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:20px;margin-bottom:20px;border-bottom:1px solid #e2e8f0;padding-bottom:16px;">
        <div style="display:flex;gap:18px;align-items:center;">
          @if($application->photo_path)
            <img src="{{ storage_asset($application->photo_path) }}" alt="Photo" style="width:96px;height:96px;object-fit:cover;border-radius:6px;border:1px solid #cbd5e1;background:#f8fafc;" onerror="this.onerror=null; this.src='{{ asset('assets/images/user-placeholder.png') }}';">
          @endif
          <div>
            <h2 style="margin:0 0 6px 0;">{{ $application->applicant_name }}</h2>
            <p style="margin:0;color:#475569;">📞 {{ $application->mobile_no }} · ✉️ {{ $application->email }}</p>
            <p style="margin:4px 0 0;color:#64748b;font-size:13px;">Father: {{ $application->father_name }} · Mother: {{ $application->mother_name }}</p>
            @if($application->cv_path)
              <a href="{{ storage_asset($application->cv_path) }}" target="_blank" class="btn gold" style="margin-top:10px;display:inline-block;padding:6px 14px;font-size:12.5px;">📄 Download Attached CV / Resume</a>
            @endif
          </div>
        </div>
        @if($application->signature_path)
          <div style="text-align:center;">
            <img src="{{ storage_asset($application->signature_path) }}" alt="Signature" style="max-width:170px;max-height:52px;border:1px solid #e2e8f0;padding:4px;border-radius:4px;background:#fff;" onerror="this.onerror=null; this.style.display='none';">
            <div style="font-size:11px;color:#64748b;margin-top:3px;">Applicant's Signature</div>
          </div>
        @endif
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:16px;margin-bottom:20px;">
        <div style="background:#f8fafc;padding:14px;border-radius:6px;border:1px solid #e2e8f0;">
          <h3 style="margin:0 0 8px 0;font-size:13.5px;color:#0f5132;">Personal Details</h3>
          <div style="font-size:13px;line-height:1.7;">
            <div><strong>DOB:</strong> {{ $application->dob?->format('d M Y') }}</div>
            <div><strong>Gender:</strong> {{ $application->gender }} | <strong>Religion:</strong> {{ $application->religion }}</div>
            <div><strong>Nationality:</strong> {{ $application->nationality }}</div>
            <div><strong>Marital Status:</strong> {{ $application->marital_status }} {{ $application->spouse_name ? '(' . $application->spouse_name . ')' : '' }}</div>
            <div><strong>NID:</strong> {{ $application->nid_no ?: '—' }} | <strong>BRN:</strong> {{ $application->birth_reg_no ?: '—' }}</div>
          </div>
        </div>
        <div style="background:#f8fafc;padding:14px;border-radius:6px;border:1px solid #e2e8f0;">
          <h3 style="margin:0 0 8px 0;font-size:13.5px;color:#0f5132;">Present Address</h3>
          <div style="font-size:13px;line-height:1.7;">
            C/O: {{ $application->present_address['care_of'] ?? '—' }}<br>
            {{ $application->present_address['village'] ?? '' }}<br>
            PO: {{ $application->present_address['post_office'] ?? '' }} ({{ $application->present_address['post_code'] ?? '' }})<br>
            Upazila: {{ $application->present_address['upazila'] ?? '' }}, District: <strong>{{ $application->present_address['district'] ?? '' }}</strong>
          </div>
        </div>
        <div style="background:#f8fafc;padding:14px;border-radius:6px;border:1px solid #e2e8f0;">
          <h3 style="margin:0 0 8px 0;font-size:13.5px;color:#0f5132;">Permanent Address</h3>
          <div style="font-size:13px;line-height:1.7;">
            C/O: {{ $application->permanent_address['care_of'] ?? '—' }}<br>
            {{ $application->permanent_address['village'] ?? '' }}<br>
            PO: {{ $application->permanent_address['post_office'] ?? '' }} ({{ $application->permanent_address['post_code'] ?? '' }})<br>
            Upazila: {{ $application->permanent_address['upazila'] ?? '' }}, District: <strong>{{ $application->permanent_address['district'] ?? '' }}</strong>
          </div>
        </div>
      </div>

      <h3 style="font-size:14px;color:#0f5132;margin-bottom:8px;">Educational Qualifications</h3>
      <div class="table-wrap" style="margin-bottom:20px;">
        <table>
          <thead>
            <tr>
              <th>Exam</th>
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
      </div>

      @if(!empty($application->experiences))
        <h3 style="font-size:14px;color:#0f5132;margin-bottom:8px;">Professional Experience</h3>
        <div class="table-wrap" style="margin-bottom:20px;">
          <table>
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
        </div>
      @endif

      @if($application->other_qualifications)
        <p style="font-size:13px;background:#f8fafc;padding:12px;border-radius:6px;border:1px solid #e2e8f0;">
          <strong>Other Qualifications / Skills:</strong> {{ $application->other_qualifications }}
        </p>
      @endif

      <form method="post" action="{{ route('admin.job-applications.update', $application) }}" style="margin-top:20px;background:#f0fdf4;border:1px solid #bbf7d0;padding:16px;border-radius:8px;">
        @csrf
        @method('PATCH')
        <h3 style="margin:0 0 12px 0;font-size:14px;color:#166534;">Review & Update Application Status</h3>
        <div class="field-grid">
          <label>
            Status
            <select name="status">
              <option value="new" @selected($application->status === 'new')>New</option>
              <option value="shortlisted" @selected($application->status === 'shortlisted')>Shortlisted</option>
              <option value="interview" @selected($application->status === 'interview')>Called for Interview</option>
              <option value="hired" @selected($application->status === 'hired')>Hired</option>
              <option value="rejected" @selected($application->status === 'rejected')>Rejected</option>
            </select>
          </label>
          <label>
            Admin Notes / Interview Remarks
            <input type="text" name="admin_notes" value="{{ old('admin_notes', $application->admin_notes) }}" placeholder="Optional internal notes...">
          </label>
        </div>
        <button type="submit" class="btn gold" style="margin-top:12px;">Update Status</button>
      </form>
    </div>
  </section>
</div>
@endsection
