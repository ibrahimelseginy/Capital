@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'لوحة تحكم المسؤول · طلبات التعديل والحذف' : 'Admin Panel · Edit & Delete Requests')

@section('content')
<style>
  .fade-in {
    animation: fadeInUp var(--duration-normal) var(--ease-out) forwards;
  }
  
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--space-5);
    margin-bottom: var(--space-8);
  }

  .stat-card-premium {
    background: var(--bg-surface);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s var(--ease-default);
    box-shadow: var(--shadow-sm);
  }

  .stat-card-premium:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--color-primary-light);
  }

  .stat-icon-container {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .controls-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--space-4);
    margin-bottom: var(--space-6);
    background: var(--bg-surface);
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-default);
  }

  .filter-chips-wrapper {
    display: flex;
    gap: var(--space-2);
    align-items: center;
  }

  .chip-premium {
    border: 1px solid var(--border-default);
    background: transparent;
    color: var(--text-secondary);
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-full);
    font-size: var(--text-caption);
    font-weight: var(--weight-medium);
    cursor: pointer;
    transition: all 0.2s var(--ease-default);
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
  }

  .chip-premium:hover {
    background: var(--action-ghost-hover);
    color: var(--text-primary);
    border-color: var(--border-strong);
  }

  .chip-premium.active {
    background: var(--color-primary);
    color: var(--text-on-primary);
    border-color: var(--color-primary);
    box-shadow: 0 4px 12px rgba(255, 90, 0, 0.25);
  }

  .chip-count {
    background: rgba(16, 16, 16, 0.08);
    color: var(--text-secondary);
    font-size: 10px;
    padding: 1px 6px;
    border-radius: var(--radius-full);
    font-weight: var(--weight-bold);
  }

  .chip-premium.active .chip-count {
    background: rgba(255, 255, 255, 0.25);
    color: var(--text-on-primary);
  }

  .requests-table-container {
    background: var(--bg-surface);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-default);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }

  .requests-table {
    width: 100%;
    border-collapse: collapse;
    text-align: start;
  }

  .requests-table th {
    background: var(--bg-secondary);
    padding: var(--space-4) var(--space-5);
    font-size: var(--text-table-header);
    font-weight: var(--weight-bold);
    color: var(--text-secondary);
    text-transform: uppercase;
    border-bottom: 1px solid var(--border-default);
    text-align: start;
  }

  .requests-table td {
    padding: var(--space-4) var(--space-5);
    font-size: var(--text-body-sm);
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-subtle);
    vertical-align: middle;
    text-align: start;
  }

  .requests-table tr:hover {
    background-color: var(--action-ghost-hover);
  }

  .empty-state-wrapper {
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: var(--space-16) var(--space-8);
    background: var(--bg-surface);
    border-radius: var(--radius-xl);
    border: 1px dashed var(--border-default);
    text-align: center;
    animation: fadeIn var(--duration-normal) ease forwards;
  }

  .empty-state-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: var(--color-primary-light);
    color: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--space-4);
  }

  /* Toast styling */
  .toast-container {
    position: fixed;
    bottom: var(--space-6);
    right: var(--space-6);
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
    pointer-events: none;
  }
  [dir="rtl"] .toast-container {
    right: auto;
    left: var(--space-6);
  }
  .toast-alert {
    background: var(--bg-surface);
    border: 1px solid var(--border-default);
    border-left: 4px solid var(--color-success);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    padding: var(--space-4) var(--space-5);
    display: flex;
    align-items: center;
    gap: var(--space-3);
    pointer-events: auto;
    transform: translateX(120%);
    opacity: 0;
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  [dir="rtl"] .toast-alert {
    border-left: 1px solid var(--border-default);
    border-right: 4px solid var(--color-success);
    transform: translateX(-120%);
  }
  .toast-alert.show {
    transform: translateX(0);
    opacity: 1;
  }

  .action-icon-btn { background: transparent; border: none; padding: 0.5rem; border-radius: var(--radius-full); color: var(--text-secondary); cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
  .action-icon-btn:hover { background: var(--bg-secondary); color: var(--action-primary); }
  .action-icon-btn.approve:hover { background: rgba(16, 185, 129, 0.1); color: #10b981; }
  .action-icon-btn.reject:hover { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
  
  .search-container { position: relative; max-width: 300px; width: 100%; }
  .search-container input { width: 100%; padding: 0.6rem 1rem 0.6rem 2.5rem; border-radius: var(--radius-full); border: 1px solid var(--border-default); background: var(--bg-surface); color: var(--text-primary); font-size: 0.9rem; transition: all 0.3s; }
  html[dir="rtl"] .search-container input { padding: 0.6rem 2.5rem 0.6rem 1rem; }
  .search-container input:focus { outline: none; border-color: var(--action-primary); box-shadow: 0 0 0 3px rgba(196, 164, 119, 0.1); }
  .search-icon { position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--text-secondary); }
  html[dir="rtl"] .search-icon { left: auto; right: 1rem; }
</style>

<div class="fade-in">
  <!-- Top Intro -->
  <div class="mb-6">
    <h2 class="text-h3" style="font-weight:var(--weight-bold); letter-spacing:-0.5px">
      {{ app()->getLocale() == 'ar' ? 'طلبات التعديل والحذف (المسؤول)' : 'Edit & Delete Requests (Admin)' }}
    </h2>
    <p class="text-secondary mt-1">
      {{ app()->getLocale() == 'ar' ? 'إدارة ومراجعة طلبات التعديل والحذف المقدمة من المستثمرين على المشاريع والمستندات والتقارير.' : 'Manage and review edit/delete requests submitted by investors on projects, documents, and reports.' }}
    </p>
  </div>

  <!-- Stats Grid -->
  <div class="stats-grid">
    <!-- Stat 1 -->
    <div class="stat-card-premium">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'إجمالي الطلبات' : 'Total Requests' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold)" id="stat-total">0</div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-primary-light); color:var(--color-primary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
    </div>
    <!-- Stat 2 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-warning)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'قيد الانتظار' : 'Pending' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold)" id="stat-pending">0</div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-warning-bg); color:var(--color-warning)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
    </div>
    <!-- Stat 3 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-success)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'مقبولة' : 'Approved' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold)" id="stat-approved">0</div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-success-bg); color:var(--color-success)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
    </div>
    <!-- Stat 4 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-error)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'مرفوضة' : 'Rejected' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold)" id="stat-rejected">0</div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-error-bg); color:var(--color-error)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
      </div>
    </div>
  </div>

  <!-- Controls Bar -->
  <div class="controls-bar" style="flex-wrap: wrap;">
    <div class="filter-chips-wrapper" id="admin-chips">
      <button class="chip-premium active" onclick="filterRequests('all', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</span>
        <span class="chip-count" id="chip-all-count">0</span>
      </button>
      <button class="chip-premium" onclick="filterRequests('Pending', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'قيد الانتظار' : 'Pending' }}</span>
        <span class="chip-count" id="chip-pending-count">0</span>
      </button>
      <button class="chip-premium" onclick="filterRequests('Approved', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'مقبولة' : 'Approved' }}</span>
        <span class="chip-count" id="chip-approved-count">0</span>
      </button>
      <button class="chip-premium" onclick="filterRequests('Rejected', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'مرفوضة' : 'Rejected' }}</span>
        <span class="chip-count" id="chip-rejected-count">0</span>
      </button>
    </div>
    <div class="search-container">
      <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input type="text" id="requestSearch" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث في الطلبات...' : 'Search requests...' }}" onkeyup="renderTable()">
    </div>
  </div>

  <!-- Empty State -->
  <div class="empty-state-wrapper" id="empty-state" style="display:flex">
    <div class="empty-state-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <h3 class="text-h5" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'لا توجد طلبات واردة حالياً' : 'No requests received yet' }}</h3>
    <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'سيتم سرد طلبات التعديل والحذف التي يقدمها المستثمر هنا.' : 'Edit/delete requests submitted by the investor will appear here.' }}</p>
  </div>

  <!-- Table Container -->
  <div class="requests-table-container" id="table-wrapper" style="display:none">
    <table class="requests-table">
      <thead>
        <tr>
          <th>{{ app()->getLocale() == 'ar' ? 'المستثمر' : 'Investor' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'العنصر المستهدف' : 'Target Item' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'نوع الطلب' : 'Request Type' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'السبب والبيانات' : 'Reason & Details' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الإجراءات' : 'Actions' }}</th>
        </tr>
      </thead>
      <tbody id="requests-list-body">
      </tbody>
    </table>
  </div>
</div>

<div class="toast-container" id="admin-toast-container">
  @if(session('success'))
  <div class="toast-alert show" style="transform:translateX(0); opacity:1; margin-bottom:1rem;">
    <div class="toast-alert-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--color-success)" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div style="font-size:13px; font-weight:var(--weight-medium); color:var(--text-primary)">{{ session('success') }}</div>
  </div>
  @endif
</div>

<script>
  let currentFilter = 'all';
  const serverRequests = @json($requests);

  function renderTable() {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    
    document.getElementById('stat-total').innerText = serverRequests.length;
    document.getElementById('stat-pending').innerText = serverRequests.filter(r => r.status === 'Pending').length;
    document.getElementById('stat-approved').innerText = serverRequests.filter(r => r.status === 'Approved').length;
    document.getElementById('stat-rejected').innerText = serverRequests.filter(r => r.status === 'Rejected').length;

    document.getElementById('chip-all-count').innerText = serverRequests.length;
    document.getElementById('chip-pending-count').innerText = serverRequests.filter(r => r.status === 'Pending').length;
    document.getElementById('chip-approved-count').innerText = serverRequests.filter(r => r.status === 'Approved').length;
    document.getElementById('chip-rejected-count').innerText = serverRequests.filter(r => r.status === 'Rejected').length;

    const tbody = document.getElementById('requests-list-body');
    tbody.innerHTML = '';
    
    const query = document.getElementById('requestSearch') ? document.getElementById('requestSearch').value.toLowerCase() : '';

    const filtered = serverRequests.filter(r => {
        const matchesStatus = currentFilter === 'all' || r.status === currentFilter;
        const matchesQuery = query === '' || 
                             r.user_name.toLowerCase().includes(query) || 
                             r.item_title.toLowerCase().includes(query) || 
                             r.reason.toLowerCase().includes(query);
        return matchesStatus && matchesQuery;
    });

    if (filtered.length === 0) {
      document.getElementById('empty-state').style.display = 'flex';
      document.getElementById('table-wrapper').style.display = 'none';
      if (query !== '') {
          document.querySelector('#empty-state h3').innerText = isAr ? 'لا توجد نتائج مطابقة للبحث' : 'No matching results found';
          document.querySelector('#empty-state p').innerText = '';
      } else {
          document.querySelector('#empty-state h3').innerText = isAr ? 'لا توجد طلبات واردة حالياً' : 'No requests received yet';
          document.querySelector('#empty-state p').innerText = isAr ? 'سيتم سرد طلبات التعديل والحذف التي يقدمها المستثمر هنا.' : 'Edit/delete requests submitted by the investor will appear here.';
      }
      return;
    }

    document.getElementById('empty-state').style.display = 'none';
    document.getElementById('table-wrapper').style.display = 'block';

    filtered.forEach(r => {
      let typeBadge = `<span class="badge badge-primary" style="color:var(--action-primary); border-color:rgba(255,90,0,0.2); background:rgba(255,90,0,0.06)">${r.request_type}</span>`;
      if(r.item_type === 'exit') {
        typeBadge = `<span class="badge badge-error" style="color:var(--color-error); border-color:rgba(217,48,37,0.2); background:rgba(217,48,37,0.06)">${r.request_type}</span>`;
      }

      let statusBadge = '';
      if (r.status === 'Pending') {
        statusBadge = `<span class="badge badge-warning badge-pulse" style="color:var(--color-warning); border-color:rgba(241,196,15,0.2); background:rgba(241,196,15,0.06)">${isAr ? 'معلق' : 'Pending'}</span>`;
      } else if (r.status === 'Approved') {
        statusBadge = `<span class="badge badge-success" style="color:var(--color-success); border-color:rgba(46,204,113,0.2); background:rgba(46,204,113,0.06)">${isAr ? 'مقبول' : 'Approved'}</span>`;
      } else {
        statusBadge = `<span class="badge badge-error" style="color:var(--color-error); border-color:rgba(217,48,37,0.2); background:rgba(217,48,37,0.06)">${isAr ? 'مرفوض' : 'Rejected'}</span>`;
      }

      let actionButtons = '-';
      if (r.status === 'Pending' && r.update_url) {
        actionButtons = `
          <div class="d-flex gap-2 justify-center">
            <form action="${r.update_url}" method="POST" style="margin:0;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="status" value="${r.approve_val}">
                <button type="submit" class="action-icon-btn approve" title="${isAr ? 'قبول' : 'Approve'}">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </button>
            </form>
            <form action="${r.update_url}" method="POST" style="margin:0;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="status" value="${r.reject_val}">
                <button type="submit" class="action-icon-btn reject" title="${isAr ? 'رفض' : 'Reject'}">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </form>
          </div>
        `;
      }

      let detailsHtml = `<div><strong>${isAr ? 'السبب:' : 'Details:'}</strong> ${r.reason}</div>`;

      const tr = document.createElement('tr');
      tr.className = 'data-row';
      tr.innerHTML = `
        <td>
          <div class="d-flex gap-3 items-center">
            <div style="width:32px; height:32px; border-radius:50%; background:var(--bg-secondary); display:flex; align-items:center; justify-content:center; font-weight:bold; color:var(--text-secondary)">
              ${r.user_name.substring(0,2).toUpperCase()}
            </div>
            <div>
              <div style="font-weight:600">${r.user_name}</div>
              <div class="text-caption text-secondary" style="font-size:10px">${r.user_role}</div>
            </div>
          </div>
        </td>
        <td>
          <div style="font-weight:600">${r.item_title}</div>
          <div class="text-caption text-secondary" style="font-size:10px">${r.item_type.toUpperCase()}</div>
        </td>
        <td>${typeBadge}</td>
        <td style="max-width:280px; white-space:normal; line-height:1.4">${detailsHtml}</td>
        <td class="text-secondary">${r.created_at}</td>
        <td>${statusBadge}</td>
        <td style="text-align:center">${actionButtons}</td>
      `;
      tbody.appendChild(tr);
    });
  }

  function filterRequests(status, element) {
    if (status !== null) {
        currentFilter = status;
        document.querySelectorAll('#admin-chips .chip-premium').forEach(c => c.classList.remove('active'));
        if (element) element.classList.add('active');
    }
    renderTable();
  }

  document.addEventListener('DOMContentLoaded', () => {
    renderTable();
    setTimeout(() => {
        const toast = document.querySelector('.toast-alert');
        if(toast) {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }
    }, 4000);
  });
</script>
@endsection
