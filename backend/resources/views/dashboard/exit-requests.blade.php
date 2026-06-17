@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'طلبات الخروج' : 'Exit Requests')

@section('content')
<style>
  /* Premium Exit Requests Styles */
  .fade-in {
    animation: fadeInUp var(--duration-normal) var(--ease-out) forwards;
  }
  
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(16px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Stats Grid */
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

  .stat-card-premium::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--color-primary);
    opacity: 0;
    transition: opacity 0.3s ease;
  }
  
  [dir="rtl"] .stat-card-premium::before {
    left: auto;
    right: 0;
  }

  .stat-card-premium:hover::before {
    opacity: 1;
  }

  .stat-icon-container {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
  }

  .stat-card-premium:hover .stat-icon-container {
    transform: scale(1.1) rotate(5deg);
  }

  /* Controls Bar */
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

  .search-wrapper {
    position: relative;
    flex: 1;
    max-width: 380px;
  }

  .search-wrapper svg {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: var(--space-3);
    color: var(--text-tertiary);
    pointer-events: none;
    transition: color 0.2s ease;
  }

  [dir="rtl"] .search-wrapper svg {
    left: auto;
    right: var(--space-3);
  }

  .search-input-premium {
    width: 100%;
    padding: var(--space-2) var(--space-4);
    padding-left: var(--space-10);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-default);
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: var(--text-body-sm);
    transition: all 0.2s ease;
  }

  [dir="rtl"] .search-input-premium {
    padding-left: var(--space-4);
    padding-right: var(--space-10);
  }

  .search-input-premium:focus {
    border-color: var(--color-primary);
    background: var(--bg-surface);
    box-shadow: var(--shadow-focus);
    outline: none;
  }

  .search-input-premium:focus + svg {
    color: var(--color-primary);
  }

  /* Table Style */
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
    letter-spacing: var(--tracking-wider);
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

  .requests-table tr:last-child td {
    border-bottom: none;
  }

  .requests-table tr {
    transition: background-color 0.2s ease;
  }

  .requests-table tr:hover {
    background-color: var(--action-ghost-hover);
  }

  /* Pulsing badge dot */
  .badge-pulse {
    position: relative;
  }
  
  .badge-pulse::after {
    content: '';
    position: absolute;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    top: 50%;
    transform: translateY(-50%);
    left: -10px;
    animation: pulseGlow 1.8s infinite;
  }

  [dir="rtl"] .badge-pulse::after {
    left: auto;
    right: -10px;
  }

  /* Modal styling */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: var(--z-modal);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity var(--duration-normal) var(--ease-default);
  }

  .modal-overlay.active {
    opacity: 1;
    pointer-events: auto;
  }

  .modal-content-premium {
    background: var(--bg-surface);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-xl);
    width: 90%;
    max-width: 520px;
    padding: var(--space-6);
    box-shadow: var(--shadow-xl);
    transform: scale(0.9) translateY(-20px);
    transition: transform var(--duration-normal) var(--ease-bounce), opacity var(--duration-normal) ease;
    opacity: 0;
    position: relative;
  }

  .modal-overlay.active .modal-content-premium, .modal-overlay.open .modal-content-premium {
    transform: scale(1) translateY(0);
    opacity: 1;
  }

  .modal-close-btn {
    position: absolute;
    top: var(--space-4);
    right: var(--space-4);
    background: var(--bg-secondary);
    border: 1px solid var(--border-default);
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--text-secondary);
    transition: all 0.2s ease;
  }
  
  [dir="rtl"] .modal-close-btn {
    right: auto;
    left: var(--space-4);
  }

  .modal-close-btn:hover {
    color: var(--text-primary);
    background: var(--action-ghost-hover);
    transform: rotate(90deg);
  }

  .form-group-premium {
    margin-bottom: var(--space-4);
  }

  .form-label-premium {
    display: block;
    font-size: var(--text-caption);
    font-weight: var(--weight-semibold);
    color: var(--text-secondary);
    margin-bottom: var(--space-1);
  }

  .form-input-premium {
    width: 100%;
    padding: var(--space-2) var(--space-3);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-default);
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: var(--text-body-sm);
    transition: all 0.2s ease;
  }

  .form-input-premium:focus {
    border-color: var(--color-primary);
    background: var(--bg-surface);
    outline: none;
    box-shadow: var(--shadow-focus);
  }

  .success-checkmark {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: var(--color-success-bg);
    color: var(--color-success);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-4) auto;
  }

  /* Empty state */
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

  /* Responsiveness */
  @media (max-width: 1024px) {
    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .stats-grid {
      grid-template-columns: 1fr;
    }
    .controls-bar {
      flex-direction: column;
      align-items: stretch;
    }
    .search-wrapper {
      max-width: 100%;
    }
  }
</style>

<div class="fade-in">
  <!-- Top Greeting & Intro -->
  <div class="mb-6 d-flex justify-between items-center flex-wrap gap-4">
    <div>
      <h2 class="text-h3" style="font-weight:var(--weight-bold); letter-spacing:-0.5px">
        {{ app()->getLocale() == 'ar' ? 'طلبات الخروج' : 'Exit Requests' }}
      </h2>
      <p class="text-secondary mt-1">
        {{ app()->getLocale() == 'ar' ? 'قدم طلبات تخارج أو بيع أسهمك الاستثمارية، وتابع حالة المراجعة مباشرة.' : 'Submit exit requests or sell your shares, and track their review status live.' }}
      </p>
    </div>
    <button class="btn btn-primary" style="border-radius:var(--radius-lg); height: 42px" onclick="openNewExitModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-inline-end: 6px"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
      <span>{{ app()->getLocale() == 'ar' ? 'طلب خروج جديد' : 'New Exit Request' }}</span>
    </button>
  </div>

  @php
    $totalRequestedVal = $requests->sum('amount');
    $underReviewCount = $requests->where('status', 'Under Review')->count();
    $completedCount = $requests->where('status', 'Completed')->count();
    $allRequestsCount = count($requests);
  @endphp

  <!-- Stats Grid -->
  <div class="stats-grid">
    <!-- Stat 1 -->
    <div class="stat-card-premium">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'إجمالي الطلبات' : 'Total Exit Requested' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)" id="stat-total-val">
          ${{ number_format($totalRequestedVal / 1000) }}K
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'رأس مال قيد طلب التخارج' : 'Capital locked in exit requests' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-primary-light); color:var(--color-primary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
    </div>

    <!-- Stat 2 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-warning)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'طلبات قيد المراجعة' : 'Under Review' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)" id="stat-review-count">
          {{ $underReviewCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'تراجع من قبل لجنة الاستثمار' : 'Reviewed by committee' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-warning-bg); color:var(--color-warning)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
      </div>
    </div>

    <!-- Stat 3 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-success)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'الطلبات المكتملة' : 'Completed Exits' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $completedCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'تم تحويل السيولة للحساب' : 'Liquidity transferred' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-success-bg); color:var(--color-success)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
    </div>

    <!-- Stat 4 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-info)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'متوسط فترة المراجعة' : 'Est. Review Period' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          5d
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'خلال 5 أيام عمل' : 'Within 5 business days' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-info-bg); color:var(--color-info)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
      </div>
    </div>
  </div>

  <!-- Info Banner -->
  <div class="card mb-6" style="padding:var(--space-4); border:1px solid rgba(59,130,246,0.3); background:rgba(59,130,246,0.04); border-radius:var(--radius-xl)">
    <div class="d-flex gap-3 items-center">
      <div style="width:36px; height:36px; border-radius:var(--radius-lg); background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center; color:#3b82f6; flex-shrink:0">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
      </div>
      <p class="text-body-sm text-secondary" style="line-height: 1.5">
        {{ app()->getLocale() == 'ar' ? 'يتم مراجعة طلبات التخارج وبيع الحصص الرأسمالية من قبل لجنة الاستثمار التابعة للصندوق خلال 5 أيام عمل بحد أقصى للتحقق من الشروط القانونية والسيولة.' : 'Exit requests are reviewed by the venture fund investment committee within 5 business days for verification of legal agreements and liquidity reserves.' }}
      </p>
    </div>
  </div>

  <!-- Controls Bar -->
  <div class="controls-bar">
    <!-- Live Search -->
    <div class="search-wrapper">
      <input type="text" id="request-search" class="search-input-premium" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث عن طلب تخارج...' : 'Search exit requests...' }}" onkeyup="filterRequests()">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>
  </div>

  <!-- Empty State -->
  <div class="empty-state-wrapper" id="empty-state">
    <div class="empty-state-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>
    <h3 class="text-h5" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'لا توجد طلبات تخارج مطابقة' : 'No exit requests found' }}</h3>
    <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'يرجى مراجعة الاسم أو البحث من جديد.' : 'Please try adjusting your search terms.' }}</p>
  </div>

  <!-- Requests Table Container -->
  <div class="requests-table-container" id="requests-table-wrapper">
    <table class="requests-table">
      <thead>
        <tr>
          <th>{{ app()->getLocale() == 'ar' ? 'المشروع' : 'Project' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'تاريخ الطلب' : 'Request Date' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'النوع' : 'Type' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'المبلغ' : 'Amount' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الإجراءات' : 'Actions' }}</th>
        </tr>
      </thead>
      <tbody id="requests-list-body">
        @foreach($requests as $req)
          @php
            $isUnderReview = ($req->status == 'Under Review' || $req->status == 'قيد المراجعة');
            
            // Status style
            $statusStyle = '';
            $statusBadgeClass = 'badge-warning';
            if ($req->status == 'Completed' || $req->status == 'مكتمل') {
                $statusBadgeClass = 'badge-success';
                $statusStyle = 'color: var(--color-success); border-color: rgba(46,204,113,0.2); background: rgba(46,204,113,0.06)';
            } else {
                $statusBadgeClass = 'badge-warning';
                $statusStyle = 'color: var(--color-warning); border-color: rgba(241,196,15,0.2); background: rgba(241,196,15,0.06)';
            }
          @endphp
          <tr class="request-row" data-project="{{ $req->project->title ?? '' }}" data-type="{{ $req->type }}" data-status="{{ $req->status }}">
            <!-- Project Name -->
            <td>
              <div class="d-flex gap-3 items-center">
                <div style="width:36px; height:36px; border-radius:var(--radius-md); background:var(--color-primary-lighter); display:flex; align-items:center; justify-content:center; color:var(--action-primary); font-weight:700">
                  {{ $req->project->title[0] ?? 'P' }}
                </div>
                <span class="text-label" style="font-weight: var(--weight-bold); color: var(--text-primary)">
                  {{ $req->project->title ?? '-' }}
                </span>
              </div>
            </td>
            <!-- Request Date -->
            <td class="text-secondary">
              @if(app()->getLocale() == 'ar')
                @if($req->created_at)
                  {{ $req->created_at->format('M d, Y') }}
                @else
                  @if($req->request_date == '2026-06-01') 01 يونيو 2026
                  @else أكتوبر 2025
                  @endif
                @endif
              @else
                {{ $req->request_date ?? ($req->created_at ? $req->created_at->format('M d, Y') : '-') }}
              @endif
            </td>
            <!-- Exit Type -->
            <td>
              <span class="badge badge-neutral" style="border-radius: var(--radius-full)">
                {{ app()->getLocale() == 'ar' ? ($req->type == 'Partial Exit' ? 'خروج جزئي' : 'خروج كامل') : $req->type }}
              </span>
            </td>
            <!-- Amount -->
            <td style="font-weight: var(--weight-semibold)">
              ${{ number_format($req->amount) }}
            </td>
            <!-- Status -->
            <td>
              <span class="badge {{ $statusBadgeClass }} @if($isUnderReview) badge-pulse @endif" style="border-radius: var(--radius-full); {{ $statusStyle }}">
                {{ app()->getLocale() == 'ar' ? (($req->status == 'Under Review' || $req->status == 'قيد المراجعة') ? 'قيد المراجعة' : 'مكتمل') : $req->status }}
              </span>
            </td>
            <!-- Actions -->
            <td>
              @if($isUnderReview)
                <button class="btn btn-ghost btn-sm" style="color:var(--action-primary); border-radius:var(--radius-lg); font-weight:var(--weight-semibold)" onclick="trackRequest('{!! addslashes($req->project->title ?? '') !!}')">
                  <span>{{ app()->getLocale() == 'ar' ? 'تتبع الطلب' : 'Track Request' }}</span>
                </button>
              @else
                <span class="text-secondary">-</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: New Exit Request Simulator -->
<div class="modal-overlay" id="exit-modal">
  <div class="modal-content-premium">
    <button class="modal-close-btn" onclick="closeNewExitModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    
    <!-- State 1: Form -->
    <div id="exit-form-state">
      <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold); color:var(--text-primary)">
        {{ app()->getLocale() == 'ar' ? 'تقديم طلب خروج جديد' : 'New Exit Request' }}
      </h3>
      <p class="text-secondary text-body-sm mb-4">
        {{ app()->getLocale() == 'ar' ? 'املأ البيانات التالية لطلب تسييل محفظتك أو بيع حصتك الاستثمارية بمشروع معين.' : 'Fill the following form to request liquidation or sell your invested shares.' }}
      </p>

      <!-- Validation Warning Banner -->
      <div id="exit-error-banner" style="display:none; background:rgba(231,76,60,0.08); border:1px solid rgba(231,76,60,0.2); border-radius:var(--radius-lg); padding:var(--space-3) var(--space-4); margin-bottom:var(--space-4); color:var(--color-error); font-size:12px; font-weight:var(--weight-semibold)">
      </div>

      <!-- Project Selector -->
      <div class="form-group-premium">
        <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'المشروع الاستثماري' : 'Investment Project' }}</label>
        <select id="exit-project-select" class="form-input-premium form-select">
          <option value="FinFlow">FinFlow</option>
          <option value="DataPulse">DataPulse</option>
          <option value="BuildOS">BuildOS</option>
          <option value="HealthBridge">HealthBridge</option>
        </select>
      </div>

      <!-- Exit Type -->
      <div class="form-group-premium">
        <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'نوع التخارج' : 'Exit Type' }}</label>
        <select id="exit-type-select" class="form-input-premium form-select">
          <option value="Partial Exit">{{ app()->getLocale() == 'ar' ? 'خروج جزئي' : 'Partial Exit' }}</option>
          <option value="Full Exit">{{ app()->getLocale() == 'ar' ? 'خروج كامل' : 'Full Exit' }}</option>
        </select>
      </div>

      <!-- Amount -->
      <div class="form-group-premium">
        <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'المبلغ المراد خروجه (USD)' : 'Exit Amount (USD)' }}</label>
        <input type="number" id="exit-amount-input" class="form-input-premium" value="50000" min="10000" step="5000">
      </div>

      <div style="display:flex; gap:12px; margin-bottom:var(--space-6)">
        <button class="btn btn-ghost flex-1" style="border-radius:var(--radius-lg)" onclick="closeNewExitModal()">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
        <button class="btn btn-primary flex-1" style="border-radius:var(--radius-lg)" onclick="submitExitRequest()">{{ app()->getLocale() == 'ar' ? 'تأكيد تقديم الطلب' : 'Confirm Submission' }}</button>
      </div>
    </div>

    <!-- State 2: Processing -->
    <div id="exit-loading-state" style="display:none; text-align:center; padding:var(--space-8) 0">
      <div style="width:48px; height:48px; border:3px solid var(--border-default); border-top-color:var(--color-primary); border-radius:50%; animation:spin 1s linear infinite; margin:0 auto var(--space-4) auto"></div>
      <h4 class="text-h5" style="font-weight:var(--weight-semibold)">
        {{ app()->getLocale() == 'ar' ? 'جاري إرسال الطلب...' : 'Submitting request...' }}
      </h4>
      <p class="text-secondary text-caption mt-1">{{ app()->getLocale() == 'ar' ? 'الرجاء عدم إغلاق هذه الصفحة' : 'Please do not close this window' }}</p>
    </div>

    <!-- State 3: Success -->
    <div id="exit-success-state" style="display:none; text-align:center; padding:var(--space-6) 0">
      <div class="success-checkmark">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h3 class="text-h4" style="font-weight:var(--weight-bold); color:var(--text-primary)">
        {{ app()->getLocale() == 'ar' ? 'تم تقديم الطلب بنجاح!' : 'Request Submitted!' }}
      </h3>
      <p class="text-secondary text-body-sm mt-2 mb-6" id="exit-success-desc">
        {{ app()->getLocale() == 'ar' ? 'تم استلام طلب التخارج الخاص بك وهو قيد المراجعة حالياً من قبل الصندوق.' : 'Your exit request has been received and is currently under review by our investment committee.' }}
      </p>

      <button class="btn btn-primary" style="border-radius:var(--radius-lg); width:100%" onclick="closeNewExitModal()">
        {{ app()->getLocale() == 'ar' ? 'العودة لطلبات الخروج' : 'Return to Exit Requests' }}
      </button>
    </div>
  </div>
</div>

<!-- Exit Tracking Modal -->
<div class="modal-overlay" id="exit-tracking-modal">
  <div class="modal-content-premium">
    <button class="modal-close-btn" onclick="closeTrackingModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold); color: var(--text-primary)">
      {{ app()->getLocale() == 'ar' ? 'تتبع طلب التخارج' : 'Track Exit Request' }}
    </h3>
    <p class="text-secondary text-body-sm mb-6" id="tracking-project-subtitle">
      Project: -
    </p>

    <!-- Timeline Wrapper -->
    <div style="display:flex; flex-direction:column; gap:24px; position:relative; padding-inline-start:32px; text-align:start" id="tracking-timeline-box">
      <!-- Vertical line -->
      <div id="timeline-line-indicator" style="position:absolute; top:8px; bottom:8px; left:9px; width:2px; background:var(--border-default)"></div>
      
      <!-- Step 1 -->
      <div style="position:relative; display:flex; flex-direction:column; gap:4px">
        <div style="position:absolute; left:-32px; top:2px; width:20px; height:20px; border-radius:50%; background:var(--color-success); border:4px solid var(--bg-surface); display:flex; align-items:center; justify-content:center; color:white" class="timeline-circle">
          <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div style="font-weight:var(--weight-bold); font-size:13px; color:var(--text-primary)">{{ app()->getLocale() == 'ar' ? 'تم تقديم الطلب' : 'Request Submitted' }}</div>
        <div style="font-size:11px; color:var(--text-secondary)" id="tracking-step-1-date">June 01, 2026</div>
      </div>

      <!-- Step 2 -->
      <div style="position:relative; display:flex; flex-direction:column; gap:4px">
        <div style="position:absolute; left:-32px; top:2px; width:20px; height:20px; border-radius:50%; background:var(--color-success); border:4px solid var(--bg-surface); display:flex; align-items:center; justify-content:center; color:white" class="timeline-circle">
          <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div style="font-weight:var(--weight-bold); font-size:13px; color:var(--text-primary)">{{ app()->getLocale() == 'ar' ? 'تحت الدراسة والمراجعة' : 'Under Review' }}</div>
        <div style="font-size:11px; color:var(--text-secondary)" id="tracking-step-2-date">June 03, 2026</div>
      </div>

      <!-- Step 3 -->
      <div style="position:relative; display:flex; flex-direction:column; gap:4px" id="tracking-step-3-container">
        <div style="position:absolute; left:-32px; top:2px; width:20px; height:20px; border-radius:50%; background:var(--color-warning); border:4px solid var(--bg-surface); box-shadow:0 0 0 4px rgba(212, 160, 23, 0.2)" class="timeline-circle"></div>
        <div style="font-weight:var(--weight-bold); font-size:13px; color:var(--text-primary)">{{ app()->getLocale() == 'ar' ? 'المراجعة القانونية من لجنة الاستثمار' : 'Legal Validation' }}</div>
        <div style="font-size:11px; color:var(--text-secondary)">{{ app()->getLocale() == 'ar' ? 'جاري مطابقة حصص الملكية وسجل الشركاء' : 'Verifying share ledger and owner registries.' }}</div>
      </div>

      <!-- Step 4 -->
      <div style="position:relative; display:flex; flex-direction:column; gap:4px; opacity:0.5" id="tracking-step-4-container">
        <div style="position:absolute; left:-32px; top:2px; width:20px; height:20px; border-radius:50%; background:var(--border-default); border:4px solid var(--bg-surface)" class="timeline-circle"></div>
        <div style="font-weight:var(--weight-bold); font-size:13px; color:var(--text-primary)">{{ app()->getLocale() == 'ar' ? 'تحويل وصرف السيولة' : 'Funds Disbursed' }}</div>
        <div style="font-size:11px; color:var(--text-secondary)">{{ app()->getLocale() == 'ar' ? 'تحويل المبلغ للحساب البنكي المعتمد للمستثمر' : 'Processing transfer to investor bank accounts.' }}</div>
      </div>
    </div>

    <!-- Actions -->
    <div style="margin-bottom:var(--space-6); display:flex; gap:12px">
      <button class="btn btn-primary" style="border-radius:var(--radius-lg); width:100%" onclick="closeTrackingModal()">
        {{ app()->getLocale() == 'ar' ? 'إغلاق نافذة التتبع' : 'Close' }}
      </button>
    </div>
  </div>
</div>

<script>
  // Spin Animation Keyframe injection
  if (!document.getElementById('spin-keyframes')) {
    const style = document.createElement('style');
    style.id = 'spin-keyframes';
    style.innerHTML = `
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(style);
  }

  // Track Exit Request
  function trackRequest(proj) {
    // Look up status from DOM
    let status = 'Under Review';
    const rows = document.querySelectorAll('.request-row');
    rows.forEach(row => {
      const rowProj = row.querySelector('.text-label');
      if (rowProj && rowProj.textContent.trim() === proj) {
        status = row.getAttribute('data-status') || 'Under Review';
      }
    });

    const isAr = "{{ app()->getLocale() == 'ar' }}";
    document.getElementById('tracking-project-subtitle').innerHTML = (isAr ? 'المشروع: ' : 'Project: ') + `<strong>${proj}</strong>`;
    
    const timelineBox = document.getElementById('tracking-timeline-box');
    const step3 = document.getElementById('tracking-step-3-container');
    const step4 = document.getElementById('tracking-step-4-container');
    const step1Date = document.getElementById('tracking-step-1-date');
    const step2Date = document.getElementById('tracking-step-2-date');
    
    if (proj.includes('FinFlow')) {
      step1Date.textContent = isAr ? '1 يونيو 2026' : 'June 01, 2026';
      step2Date.textContent = isAr ? '3 يونيو 2026' : 'June 03, 2026';
    } else {
      step1Date.textContent = isAr ? 'قبل يوم' : '1 day ago';
      step2Date.textContent = isAr ? 'قبل يوم' : '1 day ago';
    }

    if (status === 'Completed') {
      step3.style.opacity = '1';
      step3.innerHTML = `
        <div style="position:absolute; left:-32px; top:2px; width:20px; height:20px; border-radius:50%; background:var(--color-success); border:4px solid var(--bg-surface); display:flex; align-items:center; justify-content:center; color:white" class="timeline-circle">
          <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div style="font-weight:var(--weight-bold); font-size:13px; color:var(--text-primary)">${isAr ? 'المراجعة القانونية' : 'Legal Validation'}</div>
        <div style="font-size:11px; color:var(--text-secondary)">${isAr ? 'تم التحقق وتوقيع الأوراق' : 'Verified and papers signed.'}</div>
      `;
      step4.style.opacity = '1';
      step4.innerHTML = `
        <div style="position:absolute; left:-32px; top:2px; width:20px; height:20px; border-radius:50%; background:var(--color-success); border:4px solid var(--bg-surface); display:flex; align-items:center; justify-content:center; color:white" class="timeline-circle">
          <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div style="font-weight:var(--weight-bold); font-size:13px; color:var(--text-primary)">${isAr ? 'تم صرف السيولة' : 'Funds Disbursed'}</div>
        <div style="font-size:11px; color:var(--text-secondary)">${isAr ? 'تم تحويل النقد لحساب المستثمر' : 'Cash transferred to investor account.'}</div>
      `;
    } else {
      step3.style.opacity = '1';
      step3.innerHTML = `
        <div style="position:absolute; left:-32px; top:2px; width:20px; height:20px; border-radius:50%; background:var(--color-warning); border:4px solid var(--bg-surface); box-shadow:0 0 0 4px rgba(212, 160, 23, 0.2)" class="timeline-circle"></div>
        <div style="font-weight:var(--weight-bold); font-size:13px; color:var(--text-primary)">${isAr ? 'المراجعة القانونية من لجنة الاستثمار' : 'Legal Validation'}</div>
        <div style="font-size:11px; color:var(--text-secondary)">${isAr ? 'جاري مطابقة حصص الملكية وسجل الشركاء' : 'Verifying share ledger and owner registries.'}</div>
      `;
      step4.style.opacity = '0.5';
      step4.innerHTML = `
        <div style="position:absolute; left:-32px; top:2px; width:20px; height:20px; border-radius:50%; background:var(--border-default); border:4px solid var(--bg-surface)" class="timeline-circle"></div>
        <div style="font-weight:var(--weight-bold); font-size:13px; color:var(--text-primary)">${isAr ? 'تحويل وصرف السيولة' : 'Funds Disbursed'}</div>
        <div style="font-size:11px; color:var(--text-secondary)">${isAr ? 'تحويل المبلغ للحساب البنكي المعتمد للمستثمر' : 'Processing transfer to investor bank accounts.'}</div>
      `;
    }

    // Mirror RTL layout support
    if (document.documentElement.dir === 'rtl') {
      timelineBox.style.paddingInlineStart = '0';
      timelineBox.style.paddingInlineEnd = '32px';
      
      const lineIndicator = document.getElementById('timeline-line-indicator');
      if (lineIndicator) {
        lineIndicator.style.left = 'auto';
        lineIndicator.style.right = '9px';
      }
      
      setTimeout(() => {
        timelineBox.querySelectorAll('.timeline-circle').forEach(c => {
          c.style.left = 'auto';
          c.style.right = '-32px';
        });
      }, 10);
    } else {
      timelineBox.style.paddingInlineStart = '32px';
      timelineBox.style.paddingInlineEnd = '0';
      
      const lineIndicator = document.getElementById('timeline-line-indicator');
      if (lineIndicator) {
        lineIndicator.style.right = 'auto';
        lineIndicator.style.left = '9px';
      }
      
      setTimeout(() => {
        timelineBox.querySelectorAll('.timeline-circle').forEach(c => {
          c.style.right = 'auto';
          c.style.left = '-32px';
        });
      }, 10);
    }

    document.getElementById('exit-tracking-modal').classList.add('active');
  }

  function closeTrackingModal() {
    document.getElementById('exit-tracking-modal').classList.remove('active');
  }


  function openNewExitModal() {
    // Reset modal states
    document.getElementById('exit-form-state').style.display = 'block';
    document.getElementById('exit-loading-state').style.display = 'none';
    document.getElementById('exit-success-state').style.display = 'none';
    
    const errBanner = document.getElementById('exit-error-banner');
    if (errBanner) {
      errBanner.style.display = 'none';
      errBanner.innerText = '';
    }
    
    document.getElementById('exit-modal').classList.add('active');
  }

  function closeNewExitModal() {
    document.getElementById('exit-modal').classList.remove('active');
  }

  function submitExitRequest() {
    const proj = document.getElementById('exit-project-select').value;
    const type = document.getElementById('exit-type-select').value;
    const amount = document.getElementById('exit-amount-input').value;
    const errBanner = document.getElementById('exit-error-banner');

    if (errBanner) {
      errBanner.style.display = 'none';
      errBanner.innerText = '';
    }

    if (!amount || amount <= 0) {
      const errMsg = "{{ app()->getLocale() == 'ar' ? 'يرجى إدخال مبلغ صحيح' : 'Please input a valid amount' }}";
      if (errBanner) {
        errBanner.innerText = errMsg;
        errBanner.style.display = 'block';
      } else {
        alert(errMsg);
      }
      return;
    }

    // Show processing
    document.getElementById('exit-form-state').style.display = 'none';
    document.getElementById('exit-loading-state').style.display = 'block';

    setTimeout(() => {
      // Toggle Success State
      document.getElementById('exit-loading-state').style.display = 'none';
      document.getElementById('exit-success-state').style.display = 'block';

      // Insert new request row dynamically into the DOM!
      const tbody = document.getElementById('requests-list-body');
      
      const newRow = document.createElement('tr');
      newRow.className = 'request-row';
      newRow.setAttribute('data-project', proj);
      newRow.setAttribute('data-type', type);
      newRow.setAttribute('data-status', 'Under Review');
      
      const today = new Date();
      const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      const formattedDate = monthNames[today.getMonth()] + ' ' + today.getDate() + ', ' + today.getFullYear();
      const formattedDateAr = today.getDate() + ' ' + (["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"][today.getMonth()]) + ' ' + today.getFullYear();
      
      const localeDate = "{{ app()->getLocale() == 'ar' }}" === "1" ? formattedDateAr : formattedDate;
      const localeType = "{{ app()->getLocale() == 'ar' }}" === "1" ? (type === 'Partial Exit' ? 'خروج جزئي' : 'خروج كامل') : type;
      const formattedAmount = parseFloat(amount).toLocaleString();

      newRow.innerHTML = `
        <td>
          <div class="d-flex gap-3 items-center">
            <div style="width:36px; height:36px; border-radius:var(--radius-md); background:var(--color-primary-lighter); display:flex; align-items:center; justify-content:center; color:var(--action-primary); font-weight:700">
              ${proj[0]}
            </div>
            <span class="text-label" style="font-weight: var(--weight-bold); color: var(--text-primary)">
              ${proj}
            </span>
          </div>
        </td>
        <td class="text-secondary">${localeDate}</td>
        <td>
          <span class="badge badge-neutral" style="border-radius: var(--radius-full)">
            ${localeType}
          </span>
        </td>
        <td style="font-weight: var(--weight-semibold)">$${formattedAmount}</td>
        <td>
          <span class="badge badge-warning badge-pulse" style="border-radius: var(--radius-full); color: var(--color-warning); border-color: rgba(241,196,15,0.2); background: rgba(241,196,15,0.06)">
            ${"{{ app()->getLocale() == 'ar' }}" === "1" ? 'قيد المراجعة' : 'Under Review'}
          </span>
        </td>
        <td>
          <button class="btn btn-ghost btn-sm" style="color:var(--action-primary); border-radius:var(--radius-lg); font-weight:var(--weight-semibold)" onclick="trackRequest('${proj}')">
            <span>${"{{ app()->getLocale() == 'ar' }}" === "1" ? 'تتبع الطلب' : 'Track Request'}</span>
          </button>
        </td>
      `;

      // Prepend the row
      if (tbody.firstChild) {
        tbody.insertBefore(newRow, tbody.firstChild);
      } else {
        tbody.appendChild(newRow);
      }

      // Re-calculate statistics cards!
      const totalValCard = document.getElementById('stat-total-val');
      const reviewCountCard = document.getElementById('stat-review-count');

      // Update values
      const currentReviewCount = parseInt(reviewCountCard.innerText, 10);
      reviewCountCard.innerText = currentReviewCount + 1;

      // Extract raw amount to increment
      const oldValText = totalValCard.innerText.replace('$', '').replace('K', '').replace(',', '');
      const oldVal = parseFloat(oldValText) * 1000;
      const newVal = oldVal + parseFloat(amount);
      totalValCard.innerText = `$${(newVal / 1000).toLocaleString()}K`;

    }, 1500);
  }

  // Client side live search
  function filterRequests() {
    const searchVal = document.getElementById('request-search').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.request-row');
    
    let visibleCount = 0;

    rows.forEach(row => {
      const project = row.getAttribute('data-project').toLowerCase();
      const type = row.getAttribute('data-type').toLowerCase();
      
      const matchesSearch = project.includes(searchVal) || type.includes(searchVal);

      if (matchesSearch) {
        row.style.display = 'table-row';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    const emptyState = document.getElementById('empty-state');
    const tableWrapper = document.getElementById('requests-table-wrapper');

    if (visibleCount === 0) {
      emptyState.style.display = 'flex';
      tableWrapper.style.display = 'none';
    } else {
      emptyState.style.display = 'none';
      tableWrapper.style.display = 'block';
    }
  }
</script>
@endsection