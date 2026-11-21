@extends('admin.layout')
@section('title', 'Job Applications · MCTSC Admin')
@section('crumb', 'Job Applications')

@section('content')
<div class="page-head">
  <div>
    <h1>Job Applications (চাকরির আবেদনসমূহ)</h1>
    <p>Review online career applications, download CVs, filter by post & district, shortlist candidates, and export to Excel</p>
  </div>
  <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
    <a id="btnExportExcel" class="btn" href="{{ route('admin.job-applications.export') }}" style="background:#0f5132;color:#fff;display:inline-flex;align-items:center;gap:6px;font-weight:600;" title="Export currently filtered candidates to Excel (CSV)">
      <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
      Export to Excel
    </a>
    <a class="btn light" href="{{ route('admin.content.index', 'career') }}">Manage Job Circulars →</a>
  </div>
</div>

<style>
.excel-th {
  position: relative;
  user-select: none;
}
.excel-header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 6px;
}
.excel-filter-btn {
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  width: 22px;
  height: 22px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #64748b;
  position: relative;
  padding: 0;
  transition: all 0.15s ease;
  flex-shrink: 0;
}
.excel-filter-btn:hover {
  background: #e2e8f0;
  color: #0f172a;
  border-color: #94a3b8;
}
.excel-filter-btn.is-active {
  background: #ecfdf5 !important;
  border-color: #0f5132 !important;
  color: #0f5132 !important;
  box-shadow: 0 0 0 2px rgba(15, 81, 50, 0.15);
}
.excel-filter-btn.is-active::after {
  content: '';
  position: absolute;
  top: -2px;
  right: -2px;
  width: 7px;
  height: 7px;
  background: #10b981;
  border: 1.5px solid #fff;
  border-radius: 50%;
}
.excel-popover {
  display: none;
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  z-index: 1050;
  min-width: 220px;
  max-width: 280px;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
  padding: 10px;
  text-align: left;
  font-weight: normal;
  text-transform: none;
  color: #1e293b;
}
.popover-search input {
  width: 100%;
  box-sizing: border-box;
  padding: 5px 8px;
  font-size: 12px;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  margin-bottom: 8px;
  outline: none;
}
.popover-search input:focus {
  border-color: #0f5132;
}
.popover-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 11px;
  padding-bottom: 6px;
  border-bottom: 1px solid #f1f5f9;
  margin-bottom: 6px;
}
.popover-actions button {
  background: none;
  border: none;
  color: #0369a1;
  font-weight: 600;
  cursor: pointer;
  padding: 0;
  font-size: 11px;
}
.popover-actions button:hover {
  text-decoration: underline;
}
.popover-list {
  max-height: 180px;
  overflow-y: auto;
  margin-bottom: 8px;
}
.popover-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 4px 6px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  margin-bottom: 2px;
}
.popover-item:hover {
  background: #f8fafc;
}
.popover-item input[type="checkbox"] {
  cursor: pointer;
  margin: 0;
}
.popover-footer {
  display: flex;
  justify-content: flex-end;
  gap: 6px;
  padding-top: 6px;
  border-top: 1px solid #f1f5f9;
}
.popover-apply-btn {
  background: #0f5132;
  color: #fff;
  border: none;
  padding: 4px 10px;
  border-radius: 4px;
  font-size: 11.5px;
  font-weight: 600;
  cursor: pointer;
}
.popover-reset-btn {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #cbd5e1;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 11.5px;
  cursor: pointer;
}
</style>

<div class="panel">
  <div class="panel-head" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;padding:14px 20px;">
    <div style="display:flex;align-items:center;gap:12px;">
      <h2 style="margin:0;font-size:16.5px;font-weight:700;">
        Received Applications (<span id="visibleAppCount">{{ $applications->total() }}</span>)
      </h2>
      <button type="button" id="clearAllFiltersBtn" onclick="resetAllExcelFilters()" style="display:none;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:3px 10px;border-radius:14px;font-size:11.5px;font-weight:600;cursor:pointer;">
        ✕ Clear Filters
      </button>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
      {{-- Compact 1.5-inch search box in the corner --}}
      <div style="position:relative;width:150px;">
        <input type="text" id="tableQuickSearch" oninput="applyAllExcelFilters()" placeholder="Search..." style="width:100%;box-sizing:border-box;padding:6px 10px 6px 28px;font-size:12.5px;border:1px solid #cbd5e1;border-radius:6px;outline:none;background:#fff;" title="Quick search applications">
        <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:12px;color:#94a3b8;pointer-events:none;">🔍</span>
      </div>
    </div>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:45px;text-align:center;">SL</th>
          <th style="min-width:180px;">APPLICANT</th>
          <th class="excel-th" style="min-width:190px;">
            <div class="excel-header-content">
              <span>APPLIED POST</span>
              <button type="button" class="excel-filter-btn" id="btnFilterPost" onclick="toggleFilterMenu('menuPost', event)" title="Filter by Applied Post">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/></svg>
              </button>
            </div>
            <!-- Excel Popover for Post -->
            <div class="excel-popover" id="menuPost" onclick="event.stopPropagation()">
              <div class="popover-search">
                <input type="text" onkeyup="filterPopoverList(this, 'listPost')" placeholder="Search posts...">
              </div>
              <div class="popover-actions">
                <button type="button" onclick="setAllCheckboxes('listPost', true)">Select All</button>
                <span>·</span>
                <button type="button" onclick="setAllCheckboxes('listPost', false)">Clear</button>
              </div>
              <div class="popover-list" id="listPost"></div>
              <div class="popover-footer">
                <button type="button" class="popover-apply-btn" onclick="applyAllExcelFilters(); closeAllFilterMenus();">Apply</button>
                <button type="button" class="popover-reset-btn" onclick="resetColumnFilter('post'); closeAllFilterMenus();">Reset</button>
              </div>
            </div>
          </th>
          <th>CONTACT</th>
          <th class="excel-th" style="min-width:140px;">
            <div class="excel-header-content">
              <span>DISTRICT</span>
              <button type="button" class="excel-filter-btn" id="btnFilterDistrict" onclick="toggleFilterMenu('menuDistrict', event)" title="Filter by District">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/></svg>
              </button>
            </div>
            <!-- Excel Popover for District -->
            <div class="excel-popover" id="menuDistrict" onclick="event.stopPropagation()">
              <div class="popover-search">
                <input type="text" onkeyup="filterPopoverList(this, 'listDistrict')" placeholder="Search districts...">
              </div>
              <div class="popover-actions">
                <button type="button" onclick="setAllCheckboxes('listDistrict', true)">Select All</button>
                <span>·</span>
                <button type="button" onclick="setAllCheckboxes('listDistrict', false)">Clear</button>
              </div>
              <div class="popover-list" id="listDistrict"></div>
              <div class="popover-footer">
                <button type="button" class="popover-apply-btn" onclick="applyAllExcelFilters(); closeAllFilterMenus();">Apply</button>
                <button type="button" class="popover-reset-btn" onclick="resetColumnFilter('district'); closeAllFilterMenus();">Reset</button>
              </div>
            </div>
          </th>
          <th class="excel-th" style="min-width:125px;">
            <div class="excel-header-content">
              <span>ATTACHED CV</span>
              <button type="button" class="excel-filter-btn" id="btnFilterCv" onclick="toggleFilterMenu('menuCv', event)" title="Filter by CV">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/></svg>
              </button>
            </div>
            <!-- Excel Popover for CV -->
            <div class="excel-popover" id="menuCv" onclick="event.stopPropagation()">
              <div class="popover-list" id="listCv">
                <label class="popover-item"><input type="checkbox" value="yes" checked onchange="applyAllExcelFilters()"> <span>📎 Attached CV</span></label>
                <label class="popover-item"><input type="checkbox" value="no" checked onchange="applyAllExcelFilters()"> <span>— No CV</span></label>
              </div>
              <div class="popover-footer">
                <button type="button" class="popover-apply-btn" onclick="applyAllExcelFilters(); closeAllFilterMenus();">Apply</button>
                <button type="button" class="popover-reset-btn" onclick="resetColumnFilter('cv'); closeAllFilterMenus();">Reset</button>
              </div>
            </div>
          </th>
          <th>APPLIED ON</th>
          <th style="text-align:right;">ACTIONS</th>
        </tr>
      </thead>
      <tbody id="applicationsTableBody">
        @forelse($applications as $app)
          <tr class="app-row" 
              data-post="{{ $app->post_name }}" 
              data-district="{{ $app->present_address['district'] ?? '—' }}" 
              data-has-cv="{{ $app->cv_path ? 'yes' : 'no' }}" 
              data-search="{{ strtolower($app->applicant_name . ' ' . $app->post_name . ' ' . $app->mobile_no . ' ' . $app->email . ' ' . ($app->present_address['district'] ?? '') . ' ' . $app->tracking_no) }}">
            <td class="row-sl" style="text-align:center;font-weight:700;color:#64748b;">
              {{ $applications->firstItem() ? ($applications->firstItem() + $loop->index) : $loop->iteration }}
            </td>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                @if($app->photo_path)
                  <img src="{{ storage_asset($app->photo_path) }}" alt="" style="width:36px;height:36px;border-radius:4px;object-fit:cover;border:1px solid #cbd5e1;">
                @endif
                <div>
                  <strong style="display:block;font-size:13.5px;">{{ $app->applicant_name }}</strong>
                  <small style="color:#64748b;">{{ $app->gender }} · {{ $app->dob?->age }} yrs</small>
                  @php
                    $edu = is_array($app->education) ? $app->education : [];
                    $highest = null;
                    if (!empty($edu['masters']['exam'])) {
                      $highest = '🎓 ' . $edu['masters']['exam'];
                    } elseif (!empty($edu['graduation']['exam'])) {
                      $highest = '🎓 ' . $edu['graduation']['exam'];
                    } elseif (!empty($edu['hsc']['exam'])) {
                      $highest = '📜 ' . $edu['hsc']['exam'];
                    } elseif (!empty($edu['ssc']['exam'])) {
                      $highest = '📖 ' . $edu['ssc']['exam'];
                    }
                  @endphp
                  @if($highest)
                    <div style="font-size:11px;color:#0369a1;font-weight:600;margin-top:2px;">{{ $highest }}</div>
                  @endif
                </div>
              </div>
            </td>
            <td><strong>{{ $app->post_name }}</strong></td>
            <td>
              <div>{{ $app->mobile_no }}</div>
              <small style="color:#64748b;">{{ $app->email }}</small>
            </td>
            <td>
              <div>{{ $app->present_address['district'] ?? '—' }}</div>
            </td>
            <td>
              @if($app->cv_path)
                <div style="display:inline-flex;align-items:center;gap:6px;">
                  <button type="button" 
                          onclick="openCvModal('{{ storage_asset($app->cv_path) }}', '{{ e($app->applicant_name) }} — {{ e($app->post_name) }} (CV Preview)', '{{ route('admin.job-applications.download-cv', $app) }}')" 
                          style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;padding:4px 9px;border-radius:4px;font-size:12px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:4px;"
                          title="পপআপে সিভি প্রিভিউ দেখুন (View CV)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span>View</span>
                  </button>
                  <a href="{{ route('admin.job-applications.download-cv', $app) }}" 
                     style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:26px;background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;border-radius:4px;text-decoration:none;" 
                     title="সিভি ডাউনলোড করুন ({{ $app->getDownloadCvFilename() }})">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                  </a>
                </div>
              @else
                <span style="color:#94a3b8;font-size:12px;">— No CV</span>
              @endif
            </td>
            <td style="white-space:nowrap;font-size:12px;color:#64748b;">
              {{ $app->created_at->format('d/m/Y') }}<br>
              {{ $app->created_at->format('h:i A') }}
            </td>
            <td style="text-align:right;">
              <div class="actions" style="justify-content:flex-end;gap:8px;flex-wrap:wrap;">
                <a class="edit-link" href="{{ route('admin.job-applications.show', $app) }}">View Details</a>
                <form method="post" action="{{ route('admin.job-applications.destroy', $app) }}" onsubmit="return confirm('Delete this job application?');">
                  @csrf
                  @method('delete')
                  <button class="delete-link">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="empty">No job applications received yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($applications->hasPages())
    <div class="pagination" style="padding:16px;">{{ $applications->links() }}</div>
  @endif
</div>

{{-- CV Document Popup Preview Modal --}}
<div id="cvPreviewModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.75);backdrop-filter:blur(4px);z-index:999999;align-items:center;justify-content:center;padding:16px;" onclick="if(event.target===this) closeCvModal()">
  <div style="background:#fff;border-radius:12px;width:100%;max-width:960px;height:88vh;display:flex;flex-direction:column;box-shadow:0 25px 50px -12px rgba(0,0,0,0.35);overflow:hidden;border:1px solid #cbd5e1;">
    <div style="padding:12px 18px;background:#f8fafc;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;gap:12px;">
      <div style="display:flex;align-items:center;gap:10px;min-width:0;">
        <span style="font-size:20px;">📄</span>
        <h3 id="cvModalTitle" style="margin:0;font-size:15px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Candidate CV Preview</h3>
      </div>
      <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
        <a id="cvModalDownloadBtn" href="#" download class="btn" style="background:#0f5132;color:#fff;padding:6px 12px;font-size:12px;display:inline-flex;align-items:center;gap:5px;text-decoration:none;border-radius:6px;font-weight:600;">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
          Download
        </a>
        <a id="cvModalNewTabBtn" href="#" target="_blank" class="btn light" style="padding:6px 10px;font-size:12px;text-decoration:none;border:1px solid #cbd5e1;border-radius:6px;color:#475569;" title="Open in New Tab">
          New Tab ↗
        </a>
        <button type="button" onclick="closeCvModal()" style="background:#fee2e2;border:1px solid #fca5a5;color:#dc2626;width:28px;height:28px;border-radius:50%;font-size:16px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;" title="Close">&times;</button>
      </div>
    </div>
    <div style="flex:1;background:#525659;position:relative;">
      <iframe id="cvModalIframe" src="" style="width:100%;height:100%;border:none;"></iframe>
    </div>
  </div>
</div>

<script>
// --- CV Preview Modal Functions ---
function openCvModal(url, title, downloadUrl) {
  const modal = document.getElementById('cvPreviewModal');
  const iframe = document.getElementById('cvModalIframe');
  const titleEl = document.getElementById('cvModalTitle');
  const dlBtn = document.getElementById('cvModalDownloadBtn');
  const tabBtn = document.getElementById('cvModalNewTabBtn');
  if (modal && iframe) {
    if (titleEl) titleEl.textContent = title || 'Candidate CV Preview';
    iframe.src = url;
    if (dlBtn) dlBtn.href = downloadUrl || url;
    if (tabBtn) tabBtn.href = url;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
}

function closeCvModal() {
  const modal = document.getElementById('cvPreviewModal');
  const iframe = document.getElementById('cvModalIframe');
  if (modal) {
    modal.style.display = 'none';
    if (iframe) iframe.src = '';
    document.body.style.overflow = '';
  }
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeCvModal();
    closeAllFilterMenus();
  }
});

// --- Excel-Style Table Filtering System ---
function toggleFilterMenu(menuId, event) {
  if (event) event.stopPropagation();
  const targetMenu = document.getElementById(menuId);
  if (!targetMenu) return;

  const isCurrentlyOpen = targetMenu.style.display === 'block';
  closeAllFilterMenus();

  if (!isCurrentlyOpen) {
    targetMenu.style.display = 'block';
    const searchInput = targetMenu.querySelector('input[type="text"]');
    if (searchInput) {
      searchInput.value = '';
      setTimeout(() => searchInput.focus(), 50);
    }
  }
}

function closeAllFilterMenus() {
  document.querySelectorAll('.excel-popover').forEach(el => {
    el.style.display = 'none';
  });
}

document.addEventListener('click', function(e) {
  if (!e.target.closest('.excel-th')) {
    closeAllFilterMenus();
  }
});

function setAllCheckboxes(listId, state) {
  const container = document.getElementById(listId);
  if (!container) return;
  container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
    cb.checked = state;
  });
  applyAllExcelFilters();
}

function filterPopoverList(input, listId) {
  const q = (input.value || '').toLowerCase().trim();
  const container = document.getElementById(listId);
  if (!container) return;
  container.querySelectorAll('.popover-item').forEach(item => {
    const text = item.textContent.toLowerCase();
    item.style.display = text.includes(q) ? 'flex' : 'none';
  });
}

function resetColumnFilter(col) {
  if (col === 'post') setAllCheckboxes('listPost', true);
  if (col === 'district') setAllCheckboxes('listDistrict', true);
  if (col === 'cv') setAllCheckboxes('listCv', true);
}

function resetAllExcelFilters() {
  const quickInput = document.getElementById('tableQuickSearch');
  if (quickInput) quickInput.value = '';
  setAllCheckboxes('listPost', true);
  setAllCheckboxes('listDistrict', true);
  setAllCheckboxes('listCv', true);
  applyAllExcelFilters();
}

function applyAllExcelFilters() {
  const rows = Array.from(document.querySelectorAll('.app-row'));
  if (rows.length === 0) return;

  const postBoxes = Array.from(document.querySelectorAll('#listPost input[type="checkbox"]'));
  const checkedPosts = postBoxes.filter(b => b.checked).map(b => b.value);
  const isPostFiltered = postBoxes.length > 0 && checkedPosts.length < postBoxes.length;

  const distBoxes = Array.from(document.querySelectorAll('#listDistrict input[type="checkbox"]'));
  const checkedDistricts = distBoxes.filter(b => b.checked).map(b => b.value);
  const isDistFiltered = distBoxes.length > 0 && checkedDistricts.length < distBoxes.length;

  const cvBoxes = Array.from(document.querySelectorAll('#listCv input[type="checkbox"]'));
  const checkedCv = cvBoxes.filter(b => b.checked).map(b => b.value);
  const isCvFiltered = cvBoxes.length > 0 && checkedCv.length < cvBoxes.length;

  const searchQ = (document.getElementById('tableQuickSearch')?.value || '').toLowerCase().trim();

  let visibleCount = 0;
  const totalCount = rows.length;

  rows.forEach(row => {
    const postVal = row.dataset.post || '';
    const distVal = row.dataset.district || '—';
    const cvVal = row.dataset.hasCv || 'no';
    const searchVal = row.dataset.search || '';

    const matchPost = !isPostFiltered || checkedPosts.includes(postVal);
    const matchDist = !isDistFiltered || checkedDistricts.includes(distVal);
    const matchCv = !isCvFiltered || checkedCv.includes(cvVal);
    const matchSearch = !searchQ || searchVal.includes(searchQ);

    if (matchPost && matchDist && matchCv && matchSearch) {
      row.style.display = '';
      visibleCount++;
      const slEl = row.querySelector('.row-sl');
      if (slEl) slEl.textContent = visibleCount;
    } else {
      row.style.display = 'none';
    }
  });

  // Update counter
  const countEl = document.getElementById('visibleAppCount');
  if (countEl) {
    countEl.textContent = (visibleCount === totalCount) ? totalCount : `${visibleCount} of ${totalCount}`;
  }

  // Update filter button highlight states
  document.getElementById('btnFilterPost')?.classList.toggle('is-active', isPostFiltered);
  document.getElementById('btnFilterDistrict')?.classList.toggle('is-active', isDistFiltered);
  document.getElementById('btnFilterCv')?.classList.toggle('is-active', isCvFiltered);

  // Show or hide clear all button
  const clearBtn = document.getElementById('clearAllFiltersBtn');
  if (clearBtn) {
    clearBtn.style.display = (isPostFiltered || isDistFiltered || isCvFiltered || searchQ !== '') ? 'inline-block' : 'none';
  }

  // Sync Export to Excel link parameters
  const exportBtn = document.getElementById('btnExportExcel');
  if (exportBtn) {
    const params = new URLSearchParams();
    if (isPostFiltered && checkedPosts.length === 1) params.set('post', checkedPosts[0]);
    if (isDistFiltered && checkedDistricts.length === 1) params.set('district', checkedDistricts[0]);
    if (searchQ) params.set('q', searchQ);
    exportBtn.href = "{{ route('admin.job-applications.export') }}" + (params.toString() ? ('?' + params.toString()) : '');
  }
}

// Populate Excel checkbox lists from existing table rows on load
document.addEventListener('DOMContentLoaded', function() {
  const rows = Array.from(document.querySelectorAll('.app-row'));
  const posts = {};
  const districts = {};

  rows.forEach(r => {
    const p = r.dataset.post || 'Unknown';
    const d = r.dataset.district || '—';
    posts[p] = (posts[p] || 0) + 1;
    districts[d] = (districts[d] || 0) + 1;
  });

  const listPost = document.getElementById('listPost');
  if (listPost) {
    const postKeys = Object.keys(posts).sort();
    listPost.innerHTML = postKeys.map(p => `
      <label class="popover-item">
        <input type="checkbox" value="${p}" checked onchange="applyAllExcelFilters()">
        <span>${p} <small style="color:#64748b;font-weight:600;">(${posts[p]})</small></span>
      </label>
    `).join('') || '<div style="font-size:11px;color:#94a3b8;padding:4px;">No posts found</div>';
  }

  const listDistrict = document.getElementById('listDistrict');
  if (listDistrict) {
    const distKeys = Object.keys(districts).sort();
    listDistrict.innerHTML = distKeys.map(d => `
      <label class="popover-item">
        <input type="checkbox" value="${d}" checked onchange="applyAllExcelFilters()">
        <span>${d} <small style="color:#64748b;font-weight:600;">(${districts[d]})</small></span>
      </label>
    `).join('') || '<div style="font-size:11px;color:#94a3b8;padding:4px;">No districts found</div>';
  }
});
</script>
@endsection
