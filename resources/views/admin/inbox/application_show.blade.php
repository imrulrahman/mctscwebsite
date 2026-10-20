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
    @if($application->status !== 'accepted')
      <!-- SECTION 1: APPROVAL & PROJECT ASSIGNMENT -->
      <section class="panel" id="approval-section" style="margin-bottom: 20px; border: 2px solid #0f5132; background: #fbfdfc;">
        <div style="display:flex;justify-content:space-between;align-items:center;border-bottom: 2px solid #b8dfd7; padding-bottom: 10px; margin-bottom: 14px;">
          <div>
            <h2 style="font-size: 17px; color: #0f5132; margin: 0; font-weight: 800;">
              ⚡ আবেদন অনুমোদন ও প্রজেক্ট নির্ধারণ (Approve & Assign Project)
            </h2>
            <small style="color: #64748b; font-size: 12.5px;">প্রজেক্ট ও ফি নির্ধারণ করে আবেদনটি অনুমোদন করুন</small>
          </div>
          <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 4px; font-weight: 700; font-size: 12px; border: 1px solid #fde68a;">
            ⏳ অনুমোদন অপেক্ষমাণ (Pending Approval)
          </span>
        </div>

        <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px 16px; margin-bottom: 16px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px;">
          <div><strong>আবেদনকারী:</strong> {{ $application->trainee_name_bn ?: $application->name }} ({{ strtoupper($application->trainee_name_en ?: $application->name) }})</div>
          <div><strong>পছন্দের কোর্স:</strong> <span style="color: #0f5132; font-weight: 700;">{{ $application->course }}</span></div>
          <div><strong>পছন্দের শিফট:</strong> {{ $application->preferred_shift ?: 'যেকোনো শিফট' }}</div>
          <div><strong>মোবাইল নম্বর:</strong> <a href="tel:{{ $application->contact_no }}" style="color: #0f5132; font-weight: bold;">{{ $application->contact_no }}</a></div>
        </div>

        <form method="POST" action="{{ route('admin.applications.approve', $application) }}" id="approvalForm">
          @csrf
          <div style="margin-bottom: 14px;">
            <label style="font-weight: 700; color: #0f172a; display: block; margin-bottom: 8px; font-size: 13.5px;">
              * প্রজেক্ট / ব্যাচ নির্বাচন করুন (Select Project / Batch):
            </label>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
              <label class="approve-proj-radio" style="display: inline-flex; align-items: center; gap: 8px; background: #eef7f2; border: 1.5px solid #0f5132; padding: 9px 18px; border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                <input type="radio" name="project_name" value="Paid Batch" data-fee="6000" @checked(old('project_name', $application->project_name ?: 'Paid Batch') === 'Paid Batch') onchange="calcApproveFee()" style="accent-color: #0f5132; transform: scale(1.2);">
                <span style="font-weight: 700; font-size: 14px; color: #0f5132;">Paid Batch</span>
              </label>

              @foreach($projects as $p)
                @php
                  $pFee = (float)($p->admission_fee ?? 0);
                  if (strtoupper(trim($p->title)) === 'ECHO' && $pFee == 0) $pFee = 500;
                @endphp
                <label class="approve-proj-radio" style="display: inline-flex; align-items: center; gap: 8px; background: #fff; border: 1px solid #cbd5e1; padding: 9px 18px; border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                  <input type="radio" name="project_name" value="{{ $p->title }}" data-fee="{{ $pFee }}" @checked(old('project_name', $application->project_name) === $p->title) onchange="calcApproveFee()" style="accent-color: #0f5132; transform: scale(1.2);">
                  <span style="font-weight: 700; font-size: 14px; color: #1e293b;">{{ $p->title }}</span>
                </label>
              @endforeach
            </div>
          </div>

          {{-- Amount Section --}}
          <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 14px; margin-bottom: 16px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 12px;">
              <div>
                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">নির্ধারিত কোর্স ফি (৳):</label>
                <input type="number" name="course_fee" id="approveCourseFee" value="{{ old('course_fee', $application->course_fee ?: 6000) }}" oninput="calcApproveFee()" required style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: 700; color: #0f5132; font-size: 14px;">
              </div>
              <div>
                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">বিশেষ ছাড় / ডিস্কাউন্ট (৳):</label>
                <input type="number" name="discount" id="approveDiscount" value="{{ old('discount', $application->discount ?: 0) }}" oninput="calcApproveFee()" min="0" placeholder="0" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: 700; font-size: 14px;">
              </div>
              <div>
                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">পরিশোধযোগ্য মোট (৳):</label>
                <input type="text" id="approvePayable" readonly style="width: 100%; padding: 8px 10px; border: 1px solid #fecaca; background: #fef2f2; border-radius: 4px; font-weight: 800; color: #b91c1c; font-size: 14px;" value="৳ 6,000">
              </div>
              <div>
                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">পরিশোধিত অর্থ / জমা (৳):</label>
                <input type="number" name="paid_amount" id="approvePaidAmount" value="{{ old('paid_amount', $application->paid_amount ?: 0) }}" min="0" placeholder="0" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: 700; color: #0f5132; font-size: 14px;">
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <div>
                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">পেমেন্ট মেথড (Payment Method):</label>
                <select name="payment_method" style="width: 100%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 13px;">
                  <option value="Cash" @selected(old('payment_method', $application->payment_method) === 'Cash')>Cash (নগদ)</option>
                  <option value="bKash" @selected(old('payment_method', $application->payment_method) === 'bKash')>bKash</option>
                  <option value="Nagad" @selected(old('payment_method', $application->payment_method) === 'Nagad')>Nagad</option>
                  <option value="Rocket" @selected(old('payment_method', $application->payment_method) === 'Rocket')>Rocket</option>
                  <option value="Bank" @selected(old('payment_method', $application->payment_method) === 'Bank')>Bank Deposit</option>
                </select>
              </div>
              <div>
                <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 4px;">TrxID / রসিদ নম্বর (যদি থাকে):</label>
                <input type="text" name="payment_trx_id" value="{{ old('payment_trx_id', $application->payment_trx_id) }}" placeholder="e.g. 9X29A... বা মানি রিসিট নং" style="width: 100%; padding: 7px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 13px;">
              </div>
            </div>
          </div>

          <button type="submit" style="background: #0f5132; color: #fff; border: none; padding: 11px 26px; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            ✔ আবেদন অনুমোদন ও প্রজেক্ট নিশ্চিত করুন (Approve Application)
          </button>
        </form>
      </section>
    @else
      <!-- SECTION 1: COURSE & PROJECT (APPROVED) -->
      <section class="panel" style="margin-bottom: 20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 12px;">
          <h2 style="font-size: 16px; color: var(--green); margin: 0;">
            ১. প্রজেক্ট, কোর্স ও ফি সংক্রান্ত তথ্য (Project, Course & Fee)
          </h2>
          <span style="background:#d1fae5;color:#065f46;padding:4px 12px;border-radius:4px;font-weight:700;font-size:12px;">
            ✔ অনুমোদিত (Approved)
          </span>
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
    @endif

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
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;font-weight:600;">Project / Batch:</label>
          <select name="project_name" id="sidebarProjectSelect" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: 600;" onchange="updateSidebarProjectFee()">
            <option value="Paid Batch" data-fee="6000" @selected(($application->project_name ?: 'Paid Batch') === 'Paid Batch')>Paid Batch</option>
            @foreach($projects as $p)
              @php
                $pFee = (float)($p->admission_fee ?? 0);
                if (strtoupper(trim($p->title)) === 'ECHO' && $pFee == 0) $pFee = 500;
              @endphp
              <option value="{{ $p->title }}" data-fee="{{ $pFee }}" @selected($application->project_name === $p->title)>{{ $p->title }}</option>
            @endforeach
          </select>
        </div>
        <div style="margin-bottom: 10px;">
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;font-weight:600;">Course Fee (৳):</label>
          <input type="number" name="course_fee" id="sidebarCourseFee" value="{{ old('course_fee', $application->course_fee ?: 6000) }}" min="0" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: 600; color: #0f5132;">
        </div>
        <div style="margin-bottom: 10px;">
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;font-weight:600;">Discount (ছাড় ৳):</label>
          <input type="number" name="discount" min="0" value="{{ old('discount', $application->discount) }}" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: 600;">
        </div>
        <div style="margin-bottom: 10px;">
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;font-weight:600;">Paid Amount (পরিশোধিত ৳):</label>
          <input type="number" name="paid_amount" min="0" value="{{ old('paid_amount', $application->paid_amount) }}" required style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: 700; color: #0f5132;">
        </div>
        <div style="margin-bottom: 10px;">
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;font-weight:600;">Payment Method:</label>
          <select name="payment_method" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
            <option value="Cash" @selected($application->payment_method === 'Cash')>Cash (নগদ)</option>
            <option value="bKash" @selected($application->payment_method === 'bKash')>bKash</option>
            <option value="Nagad" @selected($application->payment_method === 'Nagad')>Nagad</option>
            <option value="Rocket" @selected($application->payment_method === 'Rocket')>Rocket</option>
            <option value="Bank" @selected($application->payment_method === 'Bank')>Bank Deposit</option>
          </select>
        </div>
        <div style="margin-bottom: 12px;">
          <label style="font-size:12px;display:block;margin-bottom:4px;color:#475569;font-weight:600;">TrxID / Receipt No:</label>
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

<script>
function calcApproveFee() {
  const selectedRadio = document.querySelector('input[name="project_name"]:checked');
  const feeInput = document.getElementById('approveCourseFee');
  if (selectedRadio && window.event && window.event.type === 'change') {
    feeInput.value = selectedRadio.dataset.fee || 6000;
  }
  const fee = parseFloat(feeInput.value || 0);
  const discountInput = document.getElementById('approveDiscount');
  const discount = Math.max(0, parseFloat(discountInput.value || 0));
  const payable = Math.max(0, fee - discount);
  document.getElementById('approvePayable').value = '৳ ' + payable.toLocaleString();

  // If free project, set paid amount to 0
  const paidInput = document.getElementById('approvePaidAmount');
  if (payable === 0 && (!paidInput.value || paidInput.value == 0)) {
    paidInput.value = 0;
  }

  // Visual card highlight
  document.querySelectorAll('.approve-proj-radio').forEach(card => {
    const r = card.querySelector('input[type="radio"]');
    if (r && r.checked) {
      card.style.borderColor = '#0f5132';
      card.style.background = '#eef7f2';
      const sp = card.querySelector('span');
      if (sp) sp.style.color = '#0f5132';
    } else {
      card.style.borderColor = '#cbd5e1';
      card.style.background = '#ffffff';
      const sp = card.querySelector('span');
      if (sp) sp.style.color = '#1e293b';
    }
  });
}

function updateSidebarProjectFee() {
  const select = document.getElementById('sidebarProjectSelect');
  const selectedOpt = select.options[select.selectedIndex];
  const feeInput = document.getElementById('sidebarCourseFee');
  if (selectedOpt && selectedOpt.dataset.fee !== undefined) {
    feeInput.value = selectedOpt.dataset.fee;
  }
}

document.addEventListener('DOMContentLoaded', function() {
  if (document.getElementById('approvalForm')) {
    calcApproveFee();
  }
});
</script>
@endsection
