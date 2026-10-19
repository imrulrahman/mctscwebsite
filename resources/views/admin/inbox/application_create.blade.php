@extends('admin.layout')
@section('title', 'Add Student Application · MCTSC Admin')
@section('crumb', 'Add Student Application')

@section('content')
<div class="page-head">
  <div>
    <h1>Add Student Admission Application</h1>
    <p>Register offline or walk-in candidate for technical training admission</p>
  </div>
  <div>
    <a class="btn light" href="{{ route('admin.applications') }}">← Back to Applications</a>
  </div>
</div>

<div class="panel form-panel" style="max-width:960px;">
  <form method="post" action="{{ route('admin.applications.store') }}" enctype="multipart/form-data">
    @csrf

    <div style="background:#f9fbf9;border:1px solid #e2ebe6;border-radius:6px;padding:16px;margin-bottom:18px;">
      <h3 style="margin:0 0 12px;font-size:15px;color:#18362b;">1. Course & Admission Details</h3>
      <div class="field-grid">
        <label>
          Course / Trade <span>*</span>
          <select name="course" required>
            <option value="">— Select Trade —</option>
            @foreach($courses as $c)
              <option value="{{ $c->title }}" {{ old('course') == $c->title ? 'selected' : '' }}>{{ $c->title }}</option>
            @endforeach
          </select>
          @error('course')<div class="field-error">{{ $message }}</div>@enderror
        </label>

        <label>
          Preferred Shift
          <select name="preferred_shift">
            <option value="Morning" {{ old('preferred_shift') === 'Morning' ? 'selected' : '' }}>Morning</option>
            <option value="Afternoon" {{ old('preferred_shift') === 'Afternoon' ? 'selected' : '' }}>Afternoon</option>
            <option value="Evening" {{ old('preferred_shift') === 'Evening' ? 'selected' : '' }}>Evening</option>
          </select>
          @error('preferred_shift')<div class="field-error">{{ $message }}</div>@enderror
        </label>
      </div>

      <div class="field-grid" style="margin-top:10px;">
        <label>
          Initial Application Status <span>*</span>
          <select name="status" required>
            <option value="accepted" {{ old('status', 'accepted') === 'accepted' ? 'selected' : '' }}>Accepted</option>
            <option value="new" {{ old('status') === 'new' ? 'selected' : '' }}>New</option>
            <option value="reviewing" {{ old('status') === 'reviewing' ? 'selected' : '' }}>Reviewing</option>
            <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
          </select>
          @error('status')<div class="field-error">{{ $message }}</div>@enderror
        </label>

        <label>
          Student Passport Photo
          <input type="file" name="photo" accept="image/*">
          @error('photo')<div class="field-error">{{ $message }}</div>@enderror
        </label>
      </div>
    </div>

    <div style="background:#f9fbf9;border:1px solid #e2ebe6;border-radius:6px;padding:16px;margin-bottom:18px;">
      <h3 style="margin:0 0 12px;font-size:15px;color:#18362b;">2. Personal Details</h3>
      <div class="field-grid">
        <label>
          Student Full Name (English) <span>*</span>
          <input type="text" name="trainee_name_en" value="{{ old('trainee_name_en') }}" required placeholder="FULL NAME IN CAPITAL">
          @error('trainee_name_en')<div class="field-error">{{ $message }}</div>@enderror
        </label>

        <label>
          Student Full Name (Bangla)
          <input type="text" name="trainee_name_bn" value="{{ old('trainee_name_bn') }}" placeholder="নাম (বাংলায়)">
          @error('trainee_name_bn')<div class="field-error">{{ $message }}</div>@enderror
        </label>
      </div>

      <div class="field-grid" style="margin-top:10px;">
        <label>
          Father's Name
          <input type="text" name="father_name_en" value="{{ old('father_name_en') }}">
        </label>
        <label>
          Mother's Name
          <input type="text" name="mother_name_en" value="{{ old('mother_name_en') }}">
        </label>
      </div>

      <div class="field-grid" style="margin-top:10px;">
        <label>
          Mobile Number <span>*</span>
          <input type="text" name="contact_no" value="{{ old('contact_no') }}" required placeholder="018XXXXXXXX">
          @error('contact_no')<div class="field-error">{{ $message }}</div>@enderror
        </label>
        <label>
          Email Address
          <input type="email" name="email" value="{{ old('email') }}" placeholder="applicant@example.com">
        </label>
      </div>

      <div class="field-grid" style="margin-top:10px;">
        <label>
          Date of Birth
          <input type="date" name="dob" value="{{ old('dob') }}">
        </label>
        <label>
          Gender
          <select name="sex">
            <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ old('sex') === 'Other' ? 'selected' : '' }}>Other</option>
          </select>
        </label>
      </div>

      <div class="field-grid" style="margin-top:10px;">
        <label>
          Identification Document
          <select name="ident_type">
            <option value="NID" {{ old('ident_type') === 'NID' ? 'selected' : '' }}>National ID (NID)</option>
            <option value="Birth Certificate" {{ old('ident_type') === 'Birth Certificate' ? 'selected' : '' }}>Birth Certificate</option>
          </select>
        </label>
        <label>
          ID Number
          <input type="text" name="ident_no" value="{{ old('ident_no') }}" placeholder="NID or Birth Registration No">
        </label>
      </div>

      <div style="margin-top:10px;">
        <label>
          Educational Qualification
          <input type="text" name="education_level" value="{{ old('education_level', 'SSC / Equivalent') }}" placeholder="e.g. JSC, SSC, HSC">
        </label>
      </div>
    </div>

    <div style="background:#f9fbf9;border:1px solid #e2ebe6;border-radius:6px;padding:16px;margin-bottom:18px;">
      <h3 style="margin:0 0 12px;font-size:15px;color:#18362b;">3. Address Information</h3>
      <div class="field-grid">
        <label>
          Present Address
          <textarea name="pres_address" rows="2" placeholder="House, Road, Area, Thana, District">{{ old('pres_address') }}</textarea>
        </label>
        <label>
          Permanent Address
          <textarea name="perm_address" rows="2" placeholder="Village, Post Office, Thana, District">{{ old('perm_address') }}</textarea>
        </label>
      </div>
    </div>

    <div class="form-actions" style="margin-top:20px;">
      <button type="submit" class="btn gold" style="padding:11px 26px;font-size:13px;font-weight:600;">
        Save Student Application
      </button>
      <a class="btn light" href="{{ route('admin.applications') }}" style="font-size:13px;padding:9px 16px;">Cancel</a>
    </div>
  </form>
</div>
@endsection
