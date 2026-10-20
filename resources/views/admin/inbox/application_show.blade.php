@extends('admin.layout')
@section('title', 'Application Details · ' . ($application->tracking_no ?: ('#' . $application->id)))
@section('crumb')
  <a href="{{ route('admin.applications') }}" style="color: inherit; text-decoration: none;">Admissions</a> / Details
@endsection

@section('content')
<div class="page-head">
  <div>
    <h1>Trainee Application: {{ $application->trainee_name_en ?: $application->name }}</h1>
    <p>Tracking ID: <strong style="color: var(--green);">{{ $application->tracking_no ?: ('#' . $application->id) }}</strong> · Applied on {{ $application->created_at->format('d F, Y (h:i A)') }}</p>
  </div>
  <div class="top-actions">
    <a href="{{ route('admin.applications.print', $application) }}" target="_blank" style="background: var(--green); color: #fff; padding: 9px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
      🖨️ Print Application Copy
    </a>
  </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
  <!-- MAIN DETAILS -->
  <div>
    <!-- SECTION 1: COURSE & PROJECT -->
    <section class="panel" style="margin-bottom: 20px;">
      <div style="display:flex;justify-content:space-between;align-items:center;border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 12px;">
        <h2 style="font-size: 16px; color: var(--green); margin: 0;">
          ১. প্রজেক্ট, কোর্স ও ফি সংক্রান্ত তথ্য (Project, Course & Fee)
        </h2>
        @if($application->status !== 'accepted')
          <form method="POST" action="{{ route('admin.applications.approve', $application) }}" style="margin:0;">
            @csrf
            <button type="submit" style="background:#0f5132;color:#fff;border:none;padding:7px 16px;border-radius:5px;font-weight:700;font-size:12.5px;cursor:pointer;">
              ✔ আবেদন অনুমোদন করুন (Approve)
            </button>
          </form>
        @else
          <span style="background:#d1fae5;color:#065f46;padding:4px 12px;border-radius:4px;font-weight:700;font-size:12px;">
            ✔ অনুমোদিত (Approved)
          </span>
        @endif
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; font-size: 13.5px;">
        <div><strong>Project / Batch:</strong> <span style="font-weight: 700; color: #0f5132; background:#eef7f2; padding:2px 8px; border-radius:4px;">{{ $application->project_name ?: 'Paid Batch' }}</span></div>
        <div><strong>Applied Course:</strong> <span style="font-weight: 600; color: #0f172a;">{{ $application->course }}</span></div>
        <div><strong>Preferred Shift:</strong> {{ $application->preferred_shift ?: 'Any' }}</div>
        <div><strong>Payment Status:</strong> 
          @if($application->isPaid())
            <span style="color:#059669;font-weight:700;">● Paid / Free</span>
          @else
            <span style="color:#dc2626;font-weight:700;">○ Unpaid (বকেয়া)</span>
          @endif
        </div>
        <div><strong>Course Fee:</strong> ৳ {{ number_format($application->course_fee, 0) }}</div>
        <div><strong>Discount / ছাড়:</strong> ৳ {{ number_format($application->discount, 0) }}</div>
        <div><strong>Net Payable (প্রদেয়):</strong> <strong style="color: #b91c1c; font-size:15px;">৳ {{ number_format($application->payable_amount, 0) }}</strong></div>
        <div><strong>Paid Amount (পরিশোধিত):</strong> <strong style="color: #0f5132; font-size:15px;">৳ {{ number_format($application->paid_amount, 0) }}</strong></div>
        @if($application->payment_trx_id)
          <div><strong>TrxID / Receipt:</strong> <code style="font-size:13px;">{{ $application->payment_trx_id }}</code> ({{ $application->payment_method }})</div>
        @endif
        <div><strong>Voucher No:</strong> <code style="font-size:13px;">{{ $application->voucher_no ?: $application->tracking_no }}</code></div>
      </div>
    </section>

    <!-- SECTION 2: PERSONAL DATA -->
    <section class="panel" style="margin-bottom: 20px;">
      <h2 style="font-size: 16px; color: var(--green); margin: 0 0 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
        ২. আবেদনকারীর ব্যক্তিগত তথ্য (Personal Details)
      </h2>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
        <div><strong>Trainee Name (English):</strong> {{ $application->trainee_name_en ?: $application->name }}</div>
        <div><strong>Trainee Name (Bangla):</strong> {{ $application->trainee_name_bn ?: '—' }}</div>
        <div><strong>Date of Birth:</strong> {{ $application->dob ? $application->dob->format('d M, Y') : '—' }}</div>
        <div><strong>Sex:</strong> {{ $application->sex ?: '—' }}</div>
        <div><strong>Identification Type:</strong> {{ $application->ident_type ?: 'NID' }}</div>
        <div><strong>ID / Registration No:</strong> <strong style="color: #0f5132;">{{ $application->ident_no ?: '—' }}</strong></div>
        <div><strong>PWD (প্রতিবন্ধিতা):</strong> {{ $application->pwd ?: 'No' }}</div>
        <div><strong>Contact Number:</strong> <a href="tel:{{ $application->contact_no ?: $application->phone }}" style="color: #0f5132; font-weight: 600;">{{ $application->contact_no ?: $application->phone }}</a></div>
        <div><strong>Email Address:</strong> {{ $application->email ?: '—' }}</div>
        <div><strong>Religion:</strong> {{ $application->religion ?: '—' }}</div>
        <div><strong>Ethnicity (নৃগোষ্ঠী):</strong> {{ $application->ethnicity ?: '—' }}</div>
        <div><strong>Educational Qualification:</strong> {{ $application->education_level ?: $application->education }}</div>
        <div><strong>Marital Status:</strong> {{ $application->marital_status ?: '—' }}</div>
      </div>
    </section>

    <!-- SECTION 3: ADDRESSES -->
    <section class="panel" style="margin-bottom: 20px;">
      <h2 style="font-size: 16px; color: var(--green); margin: 0 0 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
        ৩. ঠিকানা (Permanent & Present Address)
      </h2>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 13px;">
        <div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
          <strong style="color: #0f5132;">স্থায়ী ঠিকানা (Permanent Address):</strong>
          <p style="margin: 6px 0 0; line-height: 1.5;">
            {{ $application->perm_address ?: '—' }}<br>
            উপজেলা/থানা: {{ $application->perm_upazilla ?: '—' }}, জেলা: {{ $application->perm_district ?: '—' }}<br>
            বিভাগ: {{ $application->perm_division ?: '—' }} ({{ $application->perm_area_type ?: 'Rural' }})
          </p>
        </div>
        <div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
          <strong style="color: #0f5132;">বর্তমান ঠিকানা (Present Address):</strong>
          <p style="margin: 6px 0 0; line-height: 1.5;">
            {{ $application->pres_address ?: '—' }}<br>
            উপজেলা/থানা: {{ $application->pres_upazilla ?: '—' }}, জেলা: {{ $application->pres_district ?: '—' }}<br>
            বিভাগ: {{ $application->pres_division ?: '—' }}
          </p>
        </div>
      </div>
    </section>

    <!-- SECTION 4: HOUSEHOLD & SKILLS -->
    <section class="panel">
      <h2 style="font-size: 16px; color: var(--green); margin: 0 0 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
        ৪. পারিবারিক ও কর্মসংস্থান তথ্য (Household & Employment)
      </h2>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
        <div><strong>Father's Name (EN/BN):</strong> {{ $application->father_name_en }} / {{ $application->father_name_bn }}</div>
        <div><strong>Mother's Name (EN/BN):</strong> {{ $application->mother_name_en }} / {{ $application->mother_name_bn }}</div>
        <div><strong>Emergency Contact:</strong> {{ $application->emergency_contact ?: '—' }}</div>
        <div><strong>Household Members:</strong> {{ $application->household_members ?: '—' }}</div>
        <div><strong>Income Source:</strong> {{ $application->income_source ?: '—' }}</div>
        <div><strong>Monthly Household Income:</strong> {{ $application->monthly_income ? number_format($application->monthly_income, 2) . ' BDT' : '—' }}</div>
        <div><strong>Annual Household Income:</strong> <strong style="color: #0f5132;">{{ $application->yearly_income ? number_format($application->yearly_income, 2) . ' BDT' : ($application->monthly_income ? number_format($application->monthly_income * 12, 2) . ' BDT' : '—') }}</strong></div>
        <div><strong>Has Bank Account:</strong> {{ $application->has_bank_account ?: 'No' }}</div>
        <div><strong>Past Skills Training:</strong> {{ $application->past_training ?: 'No' }}</div>
        <div><strong>Employment Before Training:</strong> {{ $application->employment_status ?: '—' }}</div>
        <div><strong>Monthly Cash Income:</strong> {{ $application->monthly_cash_income ? number_format($application->monthly_cash_income, 2) . ' BDT' : '0.00 BDT' }}</div>
        <div><strong>Heard About Training Via:</strong> {{ $application->training_media_source ?: '—' }}</div>
      </div>
    </section>
  </div>

  <!-- SIDEBAR: PHOTOS, DOCUMENTS & STATUS -->
  <div>
    <!-- STATUS CARD -->
    <section class="panel" style="margin-bottom: 20px;">
      <h3 style="font-size: 15px; margin: 0 0 12px; color: #1e293b;">Application Status</h3>
      <form method="POST" action="{{ route('admin.applications.update', $application) }}">
        @csrf
        @method('PATCH')
        <div style="margin-bottom: 12px;">
          <select name="status" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
            <option value="new" @selected($application->status === 'new')>New (নতুন)</option>
            <option value="reviewing" @selected($application->status === 'reviewing')>Reviewing (বিবেচনাধীন)</option>
            <option value="accepted" @selected($application->status === 'accepted')>Accepted (ভর্তি অনুমোদিত)</option>
            <option value="rejected" @selected($application->status === 'rejected')>Rejected (বাতিল)</option>
          </select>
        </div>
        <button type="submit" style="width: 100%; background: var(--green); color: #fff; padding: 9px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
          Update Status
        </button>
      </form>
    </section>

    <!-- PAYMENT & DISCOUNT CARD -->
    <section class="panel" style="margin-bottom: 20px;">
      <h3 style="font-size: 15px; margin: 0 0 12px; color: #1e293b;">Fee & Payment Details</h3>
      <form method="POST" action="{{ route('admin.applications.payment', $application) }}">
        @csrf
        <div style="margin-bottom: 10px;">
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;">Course Fee: <strong>৳ {{ number_format($application->course_fee, 0) }}</strong></label>
        </div>
        <div style="margin-bottom: 10px;">
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;">Discount (ছাড় ৳):</label>
          <input type="number" name="discount" min="0" value="{{ old('discount', $application->discount) }}" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: 600;">
        </div>
        <div style="margin-bottom: 10px;">
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;">Paid Amount (পরিশোধিত ৳):</label>
          <input type="number" name="paid_amount" min="0" value="{{ old('paid_amount', $application->paid_amount) }}" required style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: 700; color: #0f5132;">
        </div>
        <div style="margin-bottom: 10px;">
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;">Payment Method:</label>
          <select name="payment_method" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
            <option value="Cash" @selected($application->payment_method === 'Cash')>Cash (নগদ)</option>
            <option value="bKash" @selected($application->payment_method === 'bKash')>bKash</option>
            <option value="Nagad" @selected($application->payment_method === 'Nagad')>Nagad</option>
            <option value="Rocket" @selected($application->payment_method === 'Rocket')>Rocket</option>
            <option value="Bank" @selected($application->payment_method === 'Bank')>Bank Deposit</option>
          </select>
        </div>
        <div style="margin-bottom: 12px;">
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;">TrxID / Receipt No:</label>
          <input type="text" name="payment_trx_id" value="{{ old('payment_trx_id', $application->payment_trx_id) }}" placeholder="e.g. 9X29A... বা রশিদ নং" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>
        <button type="submit" style="width: 100%; background: #0f5132; color: #fff; padding: 8px; border: none; border-radius: 5px; font-weight: 600; cursor: pointer; font-size: 13px;">
          Save Payment & Discount
        </button>
      </form>
    </section>

    <!-- PHOTO & SIGNATURE PREVIEWS -->
    <section class="panel" style="margin-bottom: 20px; text-align: center;">
      <h3 style="font-size: 15px; margin: 0 0 14px; text-align: left; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
        Photos & Signature
      </h3>

      <div style="margin-bottom: 18px;">
        <span style="font-size: 12px; font-weight: 600; color: #64748b; display: block; margin-bottom: 6px;">Trainee Photo</span>
        @if($application->photo)
          <img src="{{ storage_asset($application->photo) }}" alt="Trainee Photo" style="width: 140px; height: 160px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
          <div style="margin-top: 6px;">
            <a href="{{ storage_asset($application->photo) }}" target="_blank" style="font-size: 12px; color: #0f5132; text-decoration: none;">View Original ↗</a>
          </div>
        @else
          <div style="width: 140px; height: 160px; background: #f1f5f9; border: 1px dashed #cbd5e1; border-radius: 6px; margin: 0 auto; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #94a3b8;">No Photo</div>
        @endif
      </div>

      <div style="margin-bottom: 18px;">
        <span style="font-size: 12px; font-weight: 600; color: #64748b; display: block; margin-bottom: 6px;">Signature</span>
        @if($application->signature_photo)
          <img src="{{ storage_asset($application->signature_photo) }}" alt="Signature" style="max-width: 180px; max-height: 65px; object-fit: contain; border: 1px solid #cbd5e1; padding: 4px; background: #fff; border-radius: 4px;">
        @else
          <span style="color: #94a3b8; font-size: 12px;">No signature</span>
        @endif
      </div>

      @if($application->ident_doc)
        <div style="padding-top: 10px; border-top: 1px dashed #e2e8f0;">
          <a href="{{ storage_asset($application->ident_doc) }}" target="_blank" style="display: inline-block; background: #f8fafc; border: 1px solid #cbd5e1; padding: 7px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; color: #1e293b; text-decoration: none;">
            📄 View ID Document (NID/Birth) ↗
          </a>
        </div>
      @endif
    </section>

    <div style="text-align: center;">
      <a href="{{ route('admin.applications') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">← Back to Applications List</a>
    </div>
  </div>
</div>
@endsection
