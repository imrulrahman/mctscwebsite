@if(auth()->check() && (auth()->user()->is_admin || auth()->user()->can('settings.manage') || auth()->user()->can('projects.manage')))
{{-- =========================================================================
     MCTSC FRONT-END VISUAL LIVE IN-PAGE EDITOR
     Only active and visible for authenticated Administrators
     ========================================================================= --}}
<div id="mctscLiveEditorShell" class="live-editor-root" style="display: block;">
  {{-- 1. Floating Bottom Dock Bar --}}
  <div id="liveEditorDock" class="live-dock-bar">
    <div class="dock-left">
      <span class="dock-badge">🛡️ Admin</span>
      <span class="dock-label">Live Edit Mode</span>
      <label class="dock-switch" title="Toggle On-Page Visual Edit Mode">
        <input type="checkbox" id="liveEditToggle">
        <span class="dock-slider"></span>
      </label>
      <span id="dockStatusText" class="dock-status-off">OFF</span>
    </div>
    <div class="dock-actions">
      <a href="{{ route('admin.dashboard') }}" class="dock-btn-link" target="_blank" title="Go to Admin Dashboard">
        <span>⚙️</span> Dashboard
      </a>
      <button type="button" id="dockMinimizeBtn" class="dock-icon-btn" title="Minimize Toolbar">
        <span>−</span >
      </button>
    </div>
  </div>

  {{-- 2. Floating Minimized Trigger (When collapsed) --}}
  <button type="button" id="dockRestoreBtn" class="dock-restore-btn" style="display: none;" title="Open Live Edit Toolbar">
    <span>✏️</span>
    <small>Live Edit</small>
  </button>

  {{-- 3. Live Edit Modal Popup --}}
  <div id="liveEditModal" class="live-modal-backdrop" style="display: none;">
    <div class="live-modal-box">
      <div class="live-modal-head">
        <div>
          <div id="liveModalLocation" class="live-modal-loc">📍 হোমপেজ</div>
          <h3 id="liveModalTitle" class="live-modal-title">টেক্সট সম্পাদনা</h3>
        </div>
        <button type="button" id="liveModalClose" class="live-modal-close" title="Close (Esc)">✕</button>
      </div>

      <div class="live-modal-body">
        <p id="liveModalDesc" class="live-modal-hint">নিচের বক্সে আপনার কাঙ্ক্ষিত পরিবর্তন লিখুন:</p>
        
        {{-- Text Input Container --}}
        <div id="liveInputWrap" class="live-field-wrap">
          <input type="text" id="liveTextInput" class="live-control" placeholder="এখানে লিখুন...">
        </div>

        {{-- Textarea Container --}}
        <div id="liveTextareaWrap" class="live-field-wrap" style="display: none;">
          <textarea id="liveTextareaInput" class="live-control live-textarea" rows="6" placeholder="এখানে বিস্তারিত বিবরণ লিখুন..."></textarea>
        </div>

        {{-- Image Upload Container --}}
        <div id="liveImageWrap" class="live-field-wrap" style="display: none;">
          <div style="display: flex; gap: 14px; align-items: center; margin-bottom: 12px;">
            <img id="liveImgPreview" src="" alt="Preview" style="height: 70px; max-width: 140px; object-fit: contain; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px; background: #fff;">
            <div style="font-size: 12.5px; color: #475569;">
              বর্তমান ছবি পরিবর্তন করতে নতুন ফাইল নির্বাচন করুন (JPG, PNG, WEBP, SVG)
            </div>
          </div>
          <input type="file" id="liveFileInput" accept="image/*,.svg" class="live-control">
        </div>

        <div id="liveModalAlert" class="live-alert" style="display: none;"></div>
      </div>

      <div class="live-modal-foot">
        <a id="liveDeepLink" href="{{ route('admin.settings') }}" target="_blank" class="live-deep-link">
          এডমিন সেটিংসে বিস্তারিত দেখুন ↗
        </a>
        <div style="display: flex; gap: 10px;">
          <button type="button" id="liveModalCancel" class="live-btn-cancel">বাতিল</button>
          <button type="button" id="liveModalSave" class="live-btn-save">
            <span id="liveSaveSpinner" style="display: none;">⏳ </span>
            <span id="liveSaveText">💾 Save & Apply</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- 4. Toast Notification --}}
  <div id="liveToast" class="live-toast" style="display: none;">
    <span class="live-toast-icon">✓</span>
    <span id="liveToastMsg">সফলভাবে আপডেট হয়েছে!</span>
  </div>
</div>

<style>
/* =========================================================================
   LIVE EDITOR STYLING
   ========================================================================= */
.live-dock-bar {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: rgba(15, 23, 42, 0.92);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  color: #f8fafc;
  padding: 8px 16px;
  border-radius: 30px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
  border: 1px solid rgba(255, 255, 255, 0.15);
  z-index: 999999;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  font-size: 13.5px;
  transition: transform 0.25s ease, opacity 0.25s ease;
}
.dock-left {
  display: flex;
  align-items: center;
  gap: 10px;
}
.dock-badge {
  background: #bd9635;
  color: #fff;
  font-size: 11px;
  font-weight: 700;
  padding: 2px 7px;
  border-radius: 12px;
  letter-spacing: 0.3px;
}
.dock-label {
  font-weight: 600;
  color: #f1f5f9;
}
.dock-switch {
  position: relative;
  display: inline-block;
  width: 42px;
  height: 22px;
  margin: 0;
}
.dock-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}
.dock-slider {
  position: absolute;
  cursor: pointer;
  inset: 0;
  background-color: #475569;
  transition: .3s;
  border-radius: 24px;
}
.dock-slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .3s;
  border-radius: 50%;
}
.dock-switch input:checked + .dock-slider {
  background-color: #10b981;
}
.dock-switch input:checked + .dock-slider:before {
  transform: translateX(20px);
}
.dock-status-off {
  font-size: 11.5px;
  font-weight: 700;
  color: #94a3b8;
}
.dock-status-on {
  font-size: 11.5px;
  font-weight: 700;
  color: #34d399;
}
.dock-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  border-left: 1px solid rgba(255, 255, 255, 0.2);
  padding-left: 12px;
}
.dock-btn-link {
  color: #cbd5e1;
  text-decoration: none;
  font-size: 12.5px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.1);
  display: inline-flex;
  align-items: center;
  gap: 4px;
  transition: all 0.2s;
}
.dock-btn-link:hover {
  background: rgba(255, 255, 255, 0.2);
  color: #ffffff;
}
.dock-icon-btn {
  background: none;
  border: none;
  color: #94a3b8;
  cursor: pointer;
  font-size: 18px;
  line-height: 1;
  padding: 2px 6px;
  border-radius: 4px;
}
.dock-icon-btn:hover {
  color: #fff;
  background: rgba(255, 255, 255, 0.15);
}

.dock-restore-btn {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #0f5132;
  color: #ffffff;
  border: 2px solid #bd9635;
  border-radius: 30px;
  padding: 8px 16px;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  z-index: 999999;
  font-weight: 700;
  font-size: 13px;
  transition: transform 0.2s;
}
.dock-restore-btn:hover {
  transform: translateY(-2px);
  background: #093722;
}

/* =========================================================================
   LIVE EDIT HIGHLIGHT STYLES (WHEN TOGGLE IS ON)
   ========================================================================= */
body.live-edit-mode-active [data-live-edit] {
  position: relative !important;
  cursor: pointer !important;
  transition: outline 0.18s ease, background-color 0.18s ease !important;
  outline: 2px dashed rgba(189, 150, 53, 0.65) !important;
  outline-offset: 3px !important;
  border-radius: 4px !important;
}
body.live-edit-mode-active [data-live-edit]:hover {
  outline: 2.5px solid #0f5132 !important;
  background-color: rgba(15, 81, 50, 0.08) !important;
}
body.live-edit-mode-active [data-live-edit]::after {
  content: "✎ Edit";
  position: absolute;
  top: -12px;
  right: 8px;
  background: #0f5132;
  color: #ffffff;
  font-family: -apple-system, BlinkMacSystemFont, sans-serif;
  font-size: 10.5px;
  font-weight: 700;
  padding: 2px 7px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.25);
  pointer-events: none;
  opacity: 0;
  transform: translateY(3px);
  transition: opacity 0.15s ease, transform 0.15s ease;
  z-index: 10000;
  white-space: nowrap;
}
body.live-edit-mode-active [data-live-edit]:hover::after {
  opacity: 1;
  transform: translateY(0);
}

/* =========================================================================
   MODAL POPUP STYLING
   ========================================================================= */
.live-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.65);
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
  z-index: 1000000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}
.live-modal-box {
  background: #ffffff;
  width: 100%;
  max-width: 580px;
  border-radius: 12px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
  border: 1px solid #e2e8f0;
  overflow: hidden;
  animation: liveModalIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
@keyframes liveModalIn {
  from { opacity: 0; transform: scale(0.95) translateY(8px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}
.live-modal-head {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 18px 22px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}
.live-modal-loc {
  font-size: 11.5px;
  color: #0f5132;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.4px;
  margin-bottom: 3px;
}
.live-modal-title {
  margin: 0;
  font-size: 17px;
  color: #0f172a;
  font-weight: 700;
}
.live-modal-close {
  background: none;
  border: none;
  font-size: 18px;
  color: #64748b;
  cursor: pointer;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.15s;
}
.live-modal-close:hover {
  background: #e2e8f0;
  color: #0f172a;
}
.live-modal-body {
  padding: 22px;
}
.live-modal-hint {
  font-size: 13px;
  color: #64748b;
  margin: 0 0 12px 0;
}
.live-control {
  width: 100%;
  box-sizing: border-box;
  padding: 10px 14px;
  border: 1.5px solid #cbd5e1;
  border-radius: 6px;
  font-size: 14.5px;
  font-family: inherit;
  color: #0f172a;
  background: #ffffff;
  transition: border-color 0.15s, box-shadow 0.15s;
}
.live-control:focus {
  outline: none;
  border-color: #0f5132;
  box-shadow: 0 0 0 3px rgba(15, 81, 50, 0.15);
}
.live-textarea {
  resize: vertical;
  line-height: 1.6;
}
.live-alert {
  margin-top: 12px;
  padding: 10px 14px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
}
.live-alert.error {
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}
.live-modal-foot {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 22px;
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
}
.live-deep-link {
  color: #0f5132;
  font-size: 12px;
  font-weight: 600;
  text-decoration: underline;
}
.live-deep-link:hover {
  color: #bd9635;
}
.live-btn-cancel {
  background: #e2e8f0;
  color: #334155;
  border: none;
  padding: 9px 18px;
  border-radius: 6px;
  font-weight: 600;
  font-size: 13.5px;
  cursor: pointer;
}
.live-btn-cancel:hover {
  background: #cbd5e1;
}
.live-btn-save {
  background: #0f5132;
  color: #ffffff;
  border: none;
  padding: 9px 20px;
  border-radius: 6px;
  font-weight: 700;
  font-size: 13.5px;
  cursor: pointer;
  box-shadow: 0 2px 6px rgba(15, 81, 50, 0.25);
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.live-btn-save:hover {
  background: #093722;
}

/* =========================================================================
   TOAST NOTIFICATION
   ========================================================================= */
.live-toast {
  position: fixed;
  bottom: 80px;
  right: 24px;
  background: #065f46;
  color: #ffffff;
  padding: 12px 20px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  font-weight: 600;
  box-shadow: 0 10px 25px rgba(0,0,0,0.25);
  border: 1px solid #34d399;
  z-index: 1000001;
  animation: liveToastIn 0.25s ease;
}
@keyframes liveToastIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
.live-toast-icon {
  background: #10b981;
  width: 22px;
  height: 22px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
}
</style>

<script>
(function() {
  var STORAGE_KEY = 'mnctsc_live_edit_state';
  var toggle = document.getElementById('liveEditToggle');
  var statusText = document.getElementById('dockStatusText');
  var dock = document.getElementById('liveEditorDock');
  var restoreBtn = document.getElementById('dockRestoreBtn');
  var minimizeBtn = document.getElementById('dockMinimizeBtn');

  var modal = document.getElementById('liveEditModal');
  var modalTitle = document.getElementById('liveModalTitle');
  var modalLoc = document.getElementById('liveModalLocation');
  var modalClose = document.getElementById('liveModalClose');
  var modalCancel = document.getElementById('liveModalCancel');
  var modalSave = document.getElementById('liveModalSave');
  var saveSpinner = document.getElementById('liveSaveSpinner');
  var saveText = document.getElementById('liveSaveText');
  var modalAlert = document.getElementById('liveModalAlert');
  var deepLink = document.getElementById('liveDeepLink');

  var inputWrap = document.getElementById('liveInputWrap');
  var textInput = document.getElementById('liveTextInput');
  var textareaWrap = document.getElementById('liveTextareaWrap');
  var textareaInput = document.getElementById('liveTextareaInput');
  var imageWrap = document.getElementById('liveImageWrap');
  var fileInput = document.getElementById('liveFileInput');
  var imgPreview = document.getElementById('liveImgPreview');

  var toast = document.getElementById('liveToast');
  var toastMsg = document.getElementById('liveToastMsg');

  var currentTarget = null;
  var currentType = 'text';
  var currentKey = '';
  var isContent = false;
  var contentId = null;
  var contentField = null;

  // 1. Initialize Toggle State
  var savedState = localStorage.getItem(STORAGE_KEY);
  if (savedState === 'active') {
    enableLiveEdit(false);
  }

  toggle.addEventListener('change', function() {
    if (this.checked) {
      enableLiveEdit(true);
    } else {
      disableLiveEdit(true);
    }
  });

  function enableLiveEdit(showFeedback) {
    document.body.classList.add('live-edit-mode-active');
    toggle.checked = true;
    statusText.textContent = 'ON';
    statusText.className = 'dock-status-on';
    localStorage.setItem(STORAGE_KEY, 'active');
    if (showFeedback) showToast('Live Edit Mode চালু হয়েছে! ক্লিক করে যেকোনো অংশ পরিবর্তন করুন।');
  }

  function disableLiveEdit(showFeedback) {
    document.body.classList.remove('live-edit-mode-active');
    toggle.checked = false;
    statusText.textContent = 'OFF';
    statusText.className = 'dock-status-off';
    localStorage.setItem(STORAGE_KEY, 'inactive');
    if (showFeedback) showToast('Live Edit Mode বন্ধ করা হয়েছে।');
  }

  // Minimize / Restore Dock
  minimizeBtn.addEventListener('click', function() {
    dock.style.display = 'none';
    restoreBtn.style.display = 'flex';
  });
  restoreBtn.addEventListener('click', function() {
    restoreBtn.style.display = 'none';
    dock.style.display = 'flex';
  });

  // 2. Delegate Click on [data-live-edit] elements
  document.addEventListener('click', function(e) {
    if (!document.body.classList.contains('live-edit-mode-active')) return;

    var target = e.target.closest('[data-live-edit]');
    if (!target) return;

    // Stop normal navigation or parent events
    e.preventDefault();
    e.stopPropagation();

    openLiveModal(target);
  }, true);

  function openLiveModal(elem) {
    currentTarget = elem;
    currentKey = elem.getAttribute('data-live-key') || '';
    currentType = elem.getAttribute('data-live-type') || 'text';
    var title = elem.getAttribute('data-live-title') || 'তথ্য সম্পাদনা';
    var loc = elem.getAttribute('data-live-location') || 'ওয়েবসাইট পেইজ';

    // Content specific
    contentId = elem.getAttribute('data-live-content-id');
    contentField = elem.getAttribute('data-live-content-field');
    isContent = (contentId && contentField);

    modalTitle.textContent = title;
    modalLoc.textContent = '📍 ' + loc;
    modalAlert.style.display = 'none';
    modalAlert.textContent = '';

    // Hide all input wraps first
    inputWrap.style.display = 'none';
    textareaWrap.style.display = 'none';
    imageWrap.style.display = 'none';

    if (currentType === 'image') {
      imageWrap.style.display = 'block';
      fileInput.value = '';
      imgPreview.src = elem.tagName === 'IMG' ? elem.src : (elem.getAttribute('data-live-preview') || '');
    } else if (currentType === 'textarea' || currentType === 'rich') {
      textareaWrap.style.display = 'block';
      var rawVal = elem.getAttribute('data-live-raw') || elem.innerText.trim();
      textareaInput.value = rawVal;
      setTimeout(function() { textareaInput.focus(); }, 100);
    } else {
      inputWrap.style.display = 'block';
      var rawVal = elem.getAttribute('data-live-raw') || elem.innerText.trim();
      textInput.value = rawVal;
      setTimeout(function() { textInput.focus(); textInput.select(); }, 100);
    }

    modal.style.display = 'flex';
  }

  function closeLiveModal() {
    modal.style.display = 'none';
    currentTarget = null;
    modalAlert.style.display = 'none';
  }

  modalClose.addEventListener('click', closeLiveModal);
  modalCancel.addEventListener('click', closeLiveModal);
  modal.addEventListener('click', function(e) {
    if (e.target === modal) closeLiveModal();
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.style.display === 'flex') {
      closeLiveModal();
    }
  });

  // 3. Save Handler
  modalSave.addEventListener('click', function() {
    if (!currentTarget) return;

    var endpoint = isContent 
      ? '{{ route("admin.live-edit.content") }}' 
      : '{{ route("admin.live-edit.setting") }}';

    var formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');

    if (isContent) {
      formData.append('id', contentId);
      formData.append('field', contentField);
    } else {
      formData.append('key', currentKey);
    }

    var newValue = '';
    if (currentType === 'image') {
      if (!fileInput.files || fileInput.files.length === 0) {
        showModalError('অনুগ্রহ করে একটি ছবি ফাইল নির্বাচন করুন।');
        return;
      }
      formData.append('image', fileInput.files[0]);
    } else if (currentType === 'textarea' || currentType === 'rich') {
      newValue = textareaInput.value;
      formData.append('value', newValue);
    } else {
      newValue = textInput.value;
      formData.append('value', newValue);
    }

    // Set saving state
    setSaving(true);

    fetch(endpoint, {
      method: 'POST',
      body: formData,
      headers: {
        'Accept': 'application/json'
      }
    })
    .then(function(res) {
      return res.json().then(function(data) {
        return { ok: res.ok, data: data };
      });
    })
    .then(function(result) {
      setSaving(false);
      if (!result.ok || !result.data.success) {
        showModalError(result.data.message || 'সংরক্ষণ ব্যর্থ হয়েছে।');
        return;
      }

      // 4. Update the DOM on the active page immediately
      if (currentType === 'image' && result.data.url) {
        if (currentTarget.tagName === 'IMG') {
          currentTarget.src = result.data.url;
        } else {
          currentTarget.style.backgroundImage = 'url(' + result.data.url + ')';
        }
      } else {
        currentTarget.setAttribute('data-live-raw', newValue);
        if (currentType === 'rich') {
          currentTarget.innerHTML = newValue;
        } else {
          currentTarget.textContent = newValue;
        }
      }

      closeLiveModal();
      showToast('✓ সফলভাবে সংরক্ষিত ও আপডেট হয়েছে!');
    })
    .catch(function(err) {
      setSaving(false);
      showModalError('সার্ভারে যোগাযোগ করা যায়নি। পুনরায় চেষ্টা করুন।');
    });
  });

  function setSaving(isSaving) {
    if (isSaving) {
      modalSave.disabled = true;
      saveSpinner.style.display = 'inline';
      saveText.textContent = 'সংরক্ষণ হচ্ছে...';
    } else {
      modalSave.disabled = false;
      saveSpinner.style.display = 'none';
      saveText.textContent = '💾 Save & Apply';
    }
  }

  function showModalError(msg) {
    modalAlert.className = 'live-alert error';
    modalAlert.textContent = msg;
    modalAlert.style.display = 'block';
  }

  function showToast(msg) {
    toastMsg.textContent = msg;
    toast.style.display = 'flex';
    setTimeout(function() {
      toast.style.display = 'none';
    }, 3500);
  }
})();
</script>
@endif
