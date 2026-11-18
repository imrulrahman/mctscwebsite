@php
  $isBn = $isBn ?? (session('locale', 'en') === 'bn');
@endphp
{{-- Admission Choice Modal (Regular vs NHRDF Project Form) --}}
<style>
.acm-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.72);
  backdrop-filter: blur(4px);
  z-index: 999999;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 16px;
  animation: acmFadeIn 0.2s ease-out;
}
@keyframes acmFadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
.acm-box {
  background: #ffffff;
  border-radius: 16px;
  max-width: 660px;
  width: 100%;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.28);
  overflow: hidden;
  border: 1px solid #e2e8f0;
  animation: acmSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes acmSlideUp {
  from { opacity: 0; transform: translateY(18px) scale(0.97); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}
.acm-header {
  padding: 22px 24px 18px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 14px;
}
.acm-header-left {
  display: flex;
  align-items: center;
  gap: 14px;
}
.acm-icon-wrap {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: #ecfdf5;
  color: #047857;
  border: 1.5px solid #a7f3d0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  flex-shrink: 0;
}
.acm-title {
  margin: 0 0 4px 0;
  font-size: 19px;
  font-weight: 700;
  color: #0f172a;
  font-family: var(--serif, 'Libre Baskerville', Georgia, serif);
}
.acm-subtitle {
  margin: 0;
  font-size: 13px;
  color: #64748b;
  line-height: 1.45;
}
.acm-close {
  background: #ffffff;
  border: 1px solid #cbd5e1;
  width: 34px;
  height: 34px;
  border-radius: 50%;
  color: #64748b;
  font-size: 18px;
  font-weight: 700;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.15s ease;
  line-height: 1;
}
.acm-close:hover {
  background: #fee2e2;
  color: #dc2626;
  border-color: #fca5a5;
}
.acm-body {
  padding: 24px;
}
.acm-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 18px;
}
@media (max-width: 640px) {
  .acm-grid {
    grid-template-columns: 1fr;
  }
}
.acm-card-item {
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  padding: 20px 18px;
  text-decoration: none;
  color: inherit;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.2s ease;
  position: relative;
  background: #ffffff;
}
.acm-card-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 22px rgba(0, 0, 0, 0.08);
}
.acm-card-item.regular:hover {
  border-color: #0f5132;
  background: #fbfdfc;
}
.acm-card-item.nhrdf {
  border-color: #10b981;
  background: linear-gradient(to bottom, #ffffff, #f0fdf4);
}
.acm-card-item.nhrdf:hover {
  border-color: #047857;
  box-shadow: 0 10px 22px rgba(16, 185, 129, 0.15);
}
.acm-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  font-size: 10.5px;
  font-weight: 700;
  text-transform: uppercase;
  padding: 2px 8px;
  border-radius: 12px;
}
.badge-reg {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #cbd5e1;
}
.badge-nhr {
  background: #ecfdf5;
  color: #047857;
  border: 1px solid #10b981;
}
.acm-card-icon {
  font-size: 26px;
  margin-bottom: 10px;
}
.acm-card-heading {
  font-size: 16px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 6px 0;
}
.acm-card-text {
  font-size: 12.5px;
  color: #64748b;
  line-height: 1.5;
  margin: 0 0 14px 0;
}
.acm-trades-mini {
  background: #ffffff;
  border: 1px solid #a7f3d0;
  border-radius: 6px;
  padding: 8px 10px;
  font-size: 11.5px;
  color: #166534;
  font-weight: 600;
  margin-bottom: 14px;
}
.acm-trades-mini div {
  margin-bottom: 3px;
}
.acm-trades-mini div:last-child {
  margin-bottom: 0;
}
.acm-btn {
  display: block;
  text-align: center;
  padding: 9px 14px;
  border-radius: 6px;
  font-weight: 700;
  font-size: 13px;
  transition: background 0.15s ease;
}
.btn-reg {
  background: #0f5132;
  color: #ffffff;
}
.acm-card-item.regular:hover .btn-reg {
  background: #093722;
}
.btn-nhr {
  background: #059669;
  color: #ffffff;
}
.acm-card-item.nhrdf:hover .btn-nhr {
  background: #047857;
}
.acm-foot {
  padding: 12px 24px;
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
  font-size: 12.5px;
  color: #64748b;
  text-align: center;
}
</style>

<div id="admissionChoiceModal" class="acm-backdrop" onclick="if(event.target===this) closeAdmissionChoiceModal()">
  <div class="acm-box">
    <div class="acm-header">
      <div class="acm-header-left">
        <div class="acm-icon-wrap">🎓</div>
        <div>
          <h3 class="acm-title">{{ $isBn ? 'ভর্তি আবেদন ফরম নির্বাচন করুন' : 'Select Admission Form' }}</h3>
          <p class="acm-subtitle">{{ $isBn ? 'যে ফরমে আবেদন করতে চান সেটি নির্বাচন করুন:' : 'Choose your desired admission category:' }}</p>
        </div>
      </div>
      <button type="button" class="acm-close" onclick="closeAdmissionChoiceModal()" title="Close">&times;</button>
    </div>

    <div class="acm-body">
      <div class="acm-grid">
        {{-- 1. Regular Form --}}
        <a href="{{ route('apply') }}" class="acm-card-item regular">
          <div>
            <span class="acm-badge badge-reg">{{ $isBn ? 'সাধারণ' : 'Regular' }}</span>
            <div class="acm-card-icon">🎓</div>
            <h4 class="acm-card-heading">{{ $isBn ? 'রেগুলার ভর্তি আবেদন ফরম' : 'Regular Admission Form' }}</h4>
            <p class="acm-card-text">
              {{ $isBn ? 'প্রতিষ্ঠানের নিয়মিত বিভিন্ন কারিগরি ট্রেড ও পেশাদার কোর্সে সাধারণ ভর্তি আবেদন।' : 'General admission for regular technical trades and institutional courses.' }}
            </p>
          </div>
          <div>
            <span class="acm-btn btn-reg">{{ $isBn ? 'রেগুলার ফরম' : 'Regular Form' }} ➔</span>
          </div>
        </a>

        {{-- 2. NHRDF Project Form --}}
        <a href="{{ route('apply.nhrdf') }}" class="acm-card-item nhrdf">
          <div>
            <span class="acm-badge badge-nhr">{{ $isBn ? 'বিশেষ প্রজেক্ট' : 'Special Project' }}</span>
            <div class="acm-card-icon">🏛️</div>
            <h4 class="acm-card-heading" style="color:#047857;">{{ $isBn ? 'NHRDF প্রজেক্ট ভর্তি ফরম' : 'NHRDF Project Form' }}</h4>
            <p class="acm-card-text">
              {{ $isBn ? 'জাতীয় মানবসম্পদ উন্নয়ন তহবিল (NHRDF) বিশেষ প্রকল্পের অধীনে ৩টি নির্দিষ্ট ট্রেডে আবেদন:' : 'National Human Resource Development Fund (NHRDF) project for 3 trades:' }}
            </p>
            <div class="acm-trades-mini">
              <div>• Plumbing, Level-02</div>
              <div>• Tailoring & Dress Making, Level-02</div>
              <div>• Driving, Level-03</div>
            </div>
          </div>
          <div>
            <span class="acm-btn btn-nhr">{{ $isBn ? 'NHRDF ফরম' : 'NHRDF Form' }} ➔</span>
          </div>
        </a>
      </div>
    </div>

    <div class="acm-foot">
      📞 {{ $isBn ? 'ভর্তি বিষয়ক হেল্পলাইন:' : 'Admission Helpline:' }} <strong>01880919275</strong>
    </div>
  </div>
</div>

<script>
function openAdmissionChoiceModal(e) {
  if (e) e.preventDefault();
  const m = document.getElementById('admissionChoiceModal');
  if (m) {
    m.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
}

function closeAdmissionChoiceModal() {
  const m = document.getElementById('admissionChoiceModal');
  if (m) {
    m.style.display = 'none';
    document.body.style.overflow = '';
  }
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeAdmissionChoiceModal();
});
</script>
