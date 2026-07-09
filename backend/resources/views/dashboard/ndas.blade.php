@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'مركز اتفاقيات السرية' : 'NDA Center')

@section('content')
<style>
  /* Premium NDA Center Styles */
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

  /* List cards */
  .nda-cards-wrapper {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
  }

  .nda-card-premium {
    padding: var(--space-5);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-default);
    background: var(--bg-surface);
    transition: all 0.3s ease;
  }

  .nda-card-premium:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
  }

  .nda-card-pending {
    border-color: rgba(255,90,0,0.15);
    background: linear-gradient(135deg, var(--bg-surface) 0%, rgba(255,90,0,0.02) 100%);
  }

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
    visibility: hidden;
    pointer-events: none;
    transition: all var(--duration-normal) var(--ease-default);
  }

  .modal-overlay.active, .modal-overlay.open {
    opacity: 1;
    visibility: visible !important;
    pointer-events: auto;
  }

  .modal-content-premium {
    background: var(--bg-surface);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-xl);
    width: 90%;
    max-width: 580px;
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

  .signature-pad {
    border: 2px dashed var(--border-strong);
    border-radius: var(--radius-lg);
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background: var(--bg-primary);
    color: var(--text-tertiary);
    font-size: var(--text-body-sm);
    transition: all 0.2s ease;
    user-select: none;
  }

  .signature-pad:hover {
    border-color: var(--color-primary);
    color: var(--color-primary);
  }

  .signature-signed {
    border-style: solid;
    border-color: var(--color-success);
    background: var(--color-success-bg);
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
    .filter-chips-wrapper {
      overflow-x: auto;
      padding-bottom: var(--space-2);
      scrollbar-width: none;
    }
    .filter-chips-wrapper::-webkit-scrollbar {
      display: none;
    }
  }

  /* Global Toast Alert */
  .toast-container {
    position: fixed;
    bottom: var(--space-6);
    right: var(--space-6);
    z-index: var(--z-toast);
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
  .toast-alert.toast-error {
    border-left-color: var(--color-error);
  }
  [dir="rtl"] .toast-alert.toast-error {
    border-right-color: var(--color-error);
  }
  .toast-alert-icon {
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Paper Document Viewer for NDA */
  .report-paper {
    background: white !important;
    color: #1a1a1a !important;
    border-radius: var(--radius-sm);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    max-width: 680px;
    margin: 0 auto;
    font-family: sans-serif;
    position: relative;
    padding: var(--space-10) var(--space-8);
  }
  [data-theme="dark"] .report-paper {
    background: white !important;
    color: #1a1a1a !important;
  }
  .signature-stamp {
    border: 2px solid #2ecc71;
    border-radius: 4px;
    padding: 6px 12px;
    color: #2ecc71;
    font-family: 'Brush Script MT', cursive, sans-serif;
    font-size: 20px;
    transform: rotate(-3deg);
    display: inline-block;
    font-weight: 700;
  }
  .btn-spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  .requests-table th, .requests-table td {
    text-align: start;
  }
</style>

<div class="fade-in">
  <!-- Top Greeting & Intro -->
  <div class="mb-6">
    <h2 class="text-h3" style="font-weight:var(--weight-bold); letter-spacing:-0.5px">
      {{ app()->getLocale() == 'ar' ? 'مركز اتفاقيات السرية' : 'NDA Center' }}
    </h2>
    <p class="text-secondary mt-1">
      {{ app()->getLocale() == 'ar' ? 'كمستثمر، يجب عليك توقيع اتفاقية السرية لفتح مستندات وتفاصيل المشاريع المغلقة.' : 'As an investor, you must execute non-disclosure papers to view closed project information.' }}
    </p>
  </div>

  @php
    // Merge DB records and standard static ones to ensure a complete, beautiful list
    // DB has 2 items: FinFlow (Active), BuildOS (Active)
    // We will add DataPulse, Project Alpha, Market Research as placeholders if not already in DB
    $dbTitles = $ndas->pluck('project.title')->toArray();
    
    // We will construct a dynamic-friendly array
    $ndaItems = [];
    
    // Add DB items
    foreach($ndas as $n) {
      $ndaItems[] = [
        'id' => $n->id,
        'title' => 'NDA — ' . ($n->project->title ?? 'General'),
        'project' => $n->project->title ?? 'General',
        'date' => $n->created_at ? $n->created_at->format('M d, Y') : 'Jan 10, 2026',
        'priority' => 'Medium',
        'status' => $n->status, // Active, Pending, Expired
      ];
    }
    
    // Add placeholders if not in database to match original beautiful prototype
    if (!in_array('Project Alpha', $dbTitles)) {
      $ndaItems[] = [
        'id' => 'alpha',
        'title' => 'NDA — Project Alpha',
        'project' => 'Project Alpha',
        'date' => 'Jun 10, 2026',
        'priority' => 'High',
        'status' => 'Pending Signature',
      ];
    }
    if (!in_array('DataPulse', $dbTitles)) {
      $ndaItems[] = [
        'id' => 'datapulse',
        'title' => 'NDA — DataPulse Extension',
        'project' => 'DataPulse',
        'date' => 'Jun 08, 2026',
        'priority' => 'Medium',
        'status' => 'Pending Review',
      ];
    }
    if (!in_array('Market Research 2026', $dbTitles)) {
      $ndaItems[] = [
        'id' => 'market',
        'title' => 'NDA — Market Research 2026',
        'project' => 'Market Research',
        'date' => 'Jun 05, 2026',
        'priority' => 'Low',
        'status' => 'Pending Signature',
      ];
    }
    if (!in_array('LogiFlow', $dbTitles)) {
      $ndaItems[] = [
        'id' => 'logiflow',
        'title' => 'NDA — LogiFlow',
        'project' => 'LogiFlow',
        'date' => 'Dec 15, 2025',
        'priority' => 'Low',
        'status' => 'Expired',
      ];
    }

    $totalCount = count($ndaItems);
    $activeCount = collect($ndaItems)->where('status', 'Active')->count();
    $pendingCount = collect($ndaItems)->filter(function($i) { return str_contains($i['status'], 'Pending'); })->count();
    $expiredCount = collect($ndaItems)->where('status', 'Expired')->count();
  @endphp

  <!-- Stats Grid -->
  <div class="stats-grid">
    <!-- Stat 1 -->
    <div class="stat-card-premium">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'إجمالي الاتفاقيات' : 'Total NDAs' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $totalCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'ملفات عدم الإفصاح' : 'Confidentiality agreements' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-primary-light); color:var(--color-primary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
    </div>

    <!-- Stat 2 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-warning)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'بانتظار التوقيع' : 'Pending Action' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)" id="pending-ndas-count">
          {{ $pendingCount }}
        </div>
        <div class="text-caption mt-2" style="color:var(--color-warning); font-weight:var(--weight-medium); display:flex; align-items:center; gap:4px">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'تتطلب مراجعة وتوقيع' : 'Require signature' }}</span>
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-warning-bg); color:var(--color-warning)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </div>
    </div>

    <!-- Stat 3 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-success)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'الاتفاقيات النشطة' : 'Active NDAs' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)" id="active-ndas-count">
          {{ $activeCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'تمنحك صلاحية الوصول' : 'Provide access permission' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-success-bg); color:var(--color-success)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
    </div>

    <!-- Stat 4 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-error)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'الاتفاقيات المنتهية' : 'Expired NDAs' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $expiredCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'صلاحيات منتهية' : 'Expired authorizations' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-error-bg); color:var(--color-error)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" x2="15" y1="9" y2="15"/><line x1="15" x2="9" y1="9" y2="15"/></svg>
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
        {{ app()->getLocale() == 'ar' ? 'كمستثمر، يجب عليك توقيع اتفاقيات عدم الإفصاح قبل الوصول إلى معلومات المشاريع السرية. يرجى مراجعة كل اتفاقية بعناية قبل التوقيع.' : 'As an investor, you are required to sign NDAs before accessing confidential project information. Please review each agreement carefully before signing.' }}
      </p>
    </div>
  </div>

  <!-- Controls Bar -->
  <div class="controls-bar">
    <!-- Live Search -->
    <div class="search-wrapper">
      <input type="text" id="nda-search" class="search-input-premium" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث في الاتفاقيات...' : 'Search for NDA...' }}" onkeyup="filterNDAs()">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>

    <!-- Status Filters -->
    <div class="filter-chips-wrapper" id="ndas-filter-chips">
      <button class="chip-premium active" onclick="filterNdaStatus('all', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</span>
        <span class="chip-count">{{ $totalCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterNdaStatus('Pending', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'معلقة' : 'Pending' }}</span>
        <span class="chip-count">{{ $pendingCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterNdaStatus('Active', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'نشطة' : 'Active' }}</span>
        <span class="chip-count">{{ $activeCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterNdaStatus('Expired', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'منتهية' : 'Expired' }}</span>
        <span class="chip-count">{{ $expiredCount }}</span>
      </button>
    </div>
  </div>

  <!-- Empty State -->
  <div class="empty-state-wrapper" id="empty-state">
    <div class="empty-state-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>
    <h3 class="text-h5" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'لا توجد اتفاقيات مطابقة لبحثك' : 'No agreements found' }}</h3>
    <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'يرجى مراجعة خيارات التصفية أو البحث.' : 'Please try adjusting your search terms or filter selection.' }}</p>
  </div>

  <!-- NDA List Cards -->
  <div class="nda-cards-wrapper" id="ndas-list">
    @foreach($ndaItems as $item)
      @php
        $isPending = str_contains($item['status'], 'Pending');
        $cardClass = $isPending ? 'nda-card-pending' : '';
        
        // Setup status variables
        $statusStyle = '';
        $badgeClass = 'badge-warning';
        if ($item['status'] == 'Active') {
            $badgeClass = 'badge-success';
            $statusStyle = 'color: var(--color-success); border-color: rgba(46,204,113,0.2); background: rgba(46,204,113,0.06)';
        } elseif ($item['status'] == 'Expired') {
            $badgeClass = 'badge-error';
            $statusStyle = 'color: var(--color-error); border-color: rgba(217,48,37,0.2); background: rgba(217,48,37,0.06)';
        } else {
            $badgeClass = 'badge-warning';
            $statusStyle = 'color: var(--color-warning); border-color: rgba(241,196,15,0.2); background: rgba(241,196,15,0.06)';
        }
      @endphp
      <div class="nda-card-premium card {{ $cardClass }}" id="nda-card-{{ $item['id'] }}" data-status="{{ $item['status'] }}" data-title="{{ $item['title'] }}" data-project="{{ $item['project'] }}">
        <div class="d-flex items-center justify-between gap-4 flex-wrap">
          
          <div class="d-flex gap-4 items-center">
            <!-- Icon Squircle -->
            <div style="width:44px; height:44px; border-radius:var(--radius-lg); background:@if($isPending) rgba(255,90,0,0.08) @else var(--color-success-bg) @endif; display:flex; align-items:center; justify-content:center; color:@if($isPending) var(--action-primary) @else var(--color-success) @endif" class="nda-icon-box">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
              <div class="text-label" style="font-weight:var(--weight-bold); color: var(--text-primary)">{{ $item['title'] }}</div>
              <div class="text-caption text-secondary">
                {{ $item['date'] }} @if($isPending) · {{ app()->getLocale() == 'ar' ? 'الأولوية' : 'Priority' }}: <span style="color:@if($item['priority']=='High') var(--color-error) @else var(--color-warning) @endif; font-weight:bold">{{ app()->getLocale() == 'ar' ? ($item['priority']=='High'?'عالية':'متوسطة') : $item['priority'] }}</span> @endif
              </div>
            </div>
          </div>

          <div class="d-flex gap-3 items-center">
            <!-- Status Badge -->
            <span class="badge {{ $badgeClass }} @if($isPending) badge-pulse @endif nda-status-badge" style="border-radius:var(--radius-full); {{ $statusStyle }}">
              @if(app()->getLocale() == 'ar')
                @if($item['status'] == 'Active') نشطة
                @elseif($item['status'] == 'Expired') منتهية
                @elseif($item['status'] == 'Pending Signature') في انتظار التوقيع
                @else في انتظار المراجعة
                @endif
              @else
                {{ $item['status'] }}
              @endif
            </span>
            
            <!-- Action Button & More Actions Dropdown -->
            <div class="d-flex gap-2 items-center">
              <div class="nda-action-btn-container">
                @if($isPending)
                  <button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg); box-shadow:0 4px 12px rgba(255,90,0,0.2)" onclick="openNdaSignatureModal('{{ $item['id'] }}', '{{ $item['title'] }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-inline-end:4px"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                    <span>{{ app()->getLocale() == 'ar' ? 'راجع ووقّع' : 'Review & Sign' }}</span>
                  </button>
                @else
                  <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)" onclick="viewSignedNda('{{ $item['title'] }}')">
                    <span>{{ app()->getLocale() == 'ar' ? 'عرض' : 'View' }}</span>
                  </button>
                @endif
              </div>

              <!-- More Actions Dropdown -->
              <div class="dropdown-actions-wrapper" style="position:relative">
                <button class="btn btn-ghost btn-sm btn-icon dropdown-trigger" style="border-radius:var(--radius-lg); height: 32px; width:32px; min-width:32px; padding:0; display:flex; align-items:center; justify-content:center" onclick="toggleDropdown(event)">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                </button>
                <div class="dropdown-menu-premium" style="display:none; position:absolute; top:100%; right:0; margin-top:8px; background:var(--bg-surface); border:1px solid var(--border-default); border-radius:var(--radius-lg); box-shadow:var(--shadow-lg); z-index:100; min-width:140px">
                  <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $item['id'] }}', '{{ $item['title'] }}', 'nda', 'edit')" style="display:block; padding:10px 16px; font-size:12px; color:var(--text-primary); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب تعديل' : 'Request Edit' }}</a>
                  <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $item['id'] }}', '{{ $item['title'] }}', 'nda', 'delete')" style="display:block; padding:10px 16px; font-size:12px; color:var(--color-error); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب حذف' : 'Request Delete' }}</a>
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>
    @endforeach
  <!-- Submitted Requests Log -->
  <div class="card mt-8" id="submitted-requests-section" style="display:none; padding:var(--space-6); border-radius:var(--radius-xl)">
    <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'طلبات التعديل والحذف المعلقة' : 'Pending Edit & Delete Requests' }}</h3>
    <div class="requests-table-container">
      <table class="requests-table" style="width:100%; border-collapse:collapse; text-align:start">
        <thead>
          <tr style="background:var(--bg-secondary)">
            <th style="padding:var(--space-3) var(--space-4); border-bottom:1px solid var(--border-default); text-transform:uppercase; font-size:11px; font-weight:bold; color:var(--text-secondary)">{{ app()->getLocale() == 'ar' ? 'الاتفاقية' : 'NDA' }}</th>
            <th style="padding:var(--space-3) var(--space-4); border-bottom:1px solid var(--border-default); text-transform:uppercase; font-size:11px; font-weight:bold; color:var(--text-secondary)">{{ app()->getLocale() == 'ar' ? 'نوع الطلب' : 'Request Type' }}</th>
            <th style="padding:var(--space-3) var(--space-4); border-bottom:1px solid var(--border-default); text-transform:uppercase; font-size:11px; font-weight:bold; color:var(--text-secondary)">{{ app()->getLocale() == 'ar' ? 'السبب والبيانات' : 'Reason & Details' }}</th>
            <th style="padding:var(--space-3) var(--space-4); border-bottom:1px solid var(--border-default); text-transform:uppercase; font-size:11px; font-weight:bold; color:var(--text-secondary)">{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</th>
            <th style="padding:var(--space-3) var(--space-4); border-bottom:1px solid var(--border-default); text-transform:uppercase; font-size:11px; font-weight:bold; color:var(--text-secondary)">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
          </tr>
        </thead>
        <tbody id="submitted-requests-tbody">
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Asset Action Request Modal -->
<div class="modal-overlay" id="asset-request-modal">
  <div class="modal-content-premium">
    <button class="modal-close-btn" onclick="closeRequestModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    
    <!-- State 1: Form -->
    <div id="request-form-state">
      <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold); color:var(--text-primary)" id="request-modal-title">
        {{ app()->getLocale() == 'ar' ? 'تقديم طلب تعديل أو حذف' : 'Submit Modification Request' }}
      </h3>
      <p class="text-secondary text-body-sm mb-4">
        {{ app()->getLocale() == 'ar' ? 'سيتم إرسال طلبك مباشرة للمسؤول (الآدمن) لمراجعته واتخاذ الإجراء.' : 'Your request will be sent directly to the admin for review.' }}
      </p>

      <div class="form-group-premium">
        <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'العنصر المستهدف' : 'Target Item' }}</label>
        <input type="text" id="request-item-name" class="form-input-premium" readonly style="opacity:0.7">
      </div>

      <div class="form-group-premium">
        <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'نوع الطلب' : 'Request Type' }}</label>
        <select id="request-action-type" class="form-input-premium form-select" onchange="toggleChangesField(this.value)">
          <option value="edit">{{ app()->getLocale() == 'ar' ? 'طلب تعديل البيانات' : 'Request Edit' }}</option>
          <option value="delete">{{ app()->getLocale() == 'ar' ? 'طلب حذف العنصر' : 'Request Delete' }}</option>
        </select>
      </div>

      <div class="form-group-premium">
        <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'السبب بالتفصيل' : 'Detailed Reason' }}</label>
        <textarea id="request-reason" class="form-input-premium" rows="3" placeholder="{{ app()->getLocale() == 'ar' ? 'يرجى كتابة أسباب طلب الإجراء...' : 'Please write your reasons...' }}" required></textarea>
      </div>

      <div class="form-group-premium" id="proposed-changes-group">
        <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'التعديلات المقترحة' : 'Proposed Modifications' }}</label>
        <textarea id="request-proposed" class="form-input-premium" rows="3" placeholder="{{ app()->getLocale() == 'ar' ? 'ما هي البيانات التي تود تعديلها؟' : 'What values do you want to change?' }}"></textarea>
      </div>

      <div style="display:flex; gap:12px; margin-bottom:var(--space-6)">
        <button class="btn btn-ghost flex-1" style="border-radius:var(--radius-lg)" onclick="closeRequestModal()">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
        <button class="btn btn-primary flex-1" style="border-radius:var(--radius-lg)" onclick="submitAssetRequest()">{{ app()->getLocale() == 'ar' ? 'إرسال الطلب' : 'Submit Request' }}</button>
      </div>
    </div>

    <!-- State 2: Processing -->
    <div id="request-loading-state" style="display:none; text-align:center; padding:var(--space-8) 0">
      <div class="btn-spinner" style="width:48px; height:48px; border-width:3px; border-top-color:var(--color-primary); margin:0 auto var(--space-4) auto"></div>
      <h4 class="text-h5" style="font-weight:var(--weight-semibold)">
        {{ app()->getLocale() == 'ar' ? 'جاري إرسال الطلب...' : 'Submitting request...' }}
      </h4>
    </div>

    <!-- State 3: Success -->
    <div id="request-success-state" style="display:none; text-align:center; padding:var(--space-6) 0">
      <div style="width:64px; height:64px; border-radius:50%; background:var(--color-success-bg); color:var(--color-success); display:flex; align-items:center; justify-content:center; margin:0 auto var(--space-4) auto">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h3 class="text-h4" style="font-weight:var(--weight-bold); color:var(--text-primary)">
        {{ app()->getLocale() == 'ar' ? 'تم إرسال الطلب!' : 'Request Sent!' }}
      </h3>
      <p class="text-secondary text-body-sm mt-2 mb-6" id="request-success-desc">
        {{ app()->getLocale() == 'ar' ? 'تم إرسال طلب التعديل/الحذف للمسؤول بنجاح.' : 'Your request was successfully submitted to the admin.' }}
      </p>
      <button class="btn btn-primary" style="border-radius:var(--radius-lg); width:100%" onclick="closeRequestModal()">
        {{ app()->getLocale() == 'ar' ? 'العودة' : 'Back' }}
      </button>
    </div>
  </div>
</div>

<!-- NDA Signature Modal -->
<div class="modal-overlay" id="nda-modal">
  <div class="modal-content-premium">
    <button class="modal-close-btn" onclick="closeNdaModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    
    <!-- State 1: Form & Sign -->
    <div id="nda-form-state">
      <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold); color: var(--text-primary)" id="nda-modal-title">
        {{ app()->getLocale() == 'ar' ? 'مراجعة وتوقيع اتفاقية السرية' : 'Review & Sign NDA' }}
      </h3>
      <p class="text-secondary text-body-sm mb-4">
        {{ app()->getLocale() == 'ar' ? 'يرجى قراءة شروط اتفاقية عدم الإفصاح الموضحة أدناه والنقر على مربع التوقيع للموافقة.' : 'Please read the terms of the Non-Disclosure Agreement below and click the signature box to sign.' }}
      </p>

      <div style="background:var(--bg-secondary); border-radius:var(--radius-lg); padding:var(--space-4); margin-bottom:var(--space-4); font-size:13px; line-height:1.6; color:var(--text-secondary); max-height: 180px; overflow-y: auto; border: 1px solid var(--border-default)">
        <p style="font-weight:bold; color:var(--text-primary); margin-bottom:8px">
          {{ app()->getLocale() == 'ar' ? 'اتفاقية عدم الإفصاح والسرية (NDA)' : 'NON-DISCLOSURE & CONFIDENTIALITY AGREEMENT' }}
        </p>
        <p>
          {{ app()->getLocale() == 'ar' 
            ? 'يتم إبرام هذه الاتفاقية بين شركة سفن تك كابيتال (المصدر) والمستثمر خالد الدوسري (المتلقي). يلتزم الطرف المتلقي بالحفاظ على سرية المعلومات التقنية والمالية المقدمة إليه.' 
            : 'This Agreement is made between SEVEN TECH CAPITAL (Discloser) and the Investor Khalid Al-Dosari (Recipient). Recipient agrees to hold all technical and financial data in strict confidence.' }}
        </p>
        <p style="margin-top: 8px">
          {{ app()->getLocale() == 'ar'
            ? 'لا يحق للطرف المتلقي تسريب هذه المعلومات لأي طرف ثالث دون موافقة كتابية مسبقة. يسري هذا الالتزام لمدة سنتين من تاريخ التوقيع.'
            : 'Recipient shall not disclose the confidential info to any third party without prior written consent. This obligation persists for 2 years from date of execution.' }}
        </p>
      </div>

      <div style="margin-bottom:var(--space-5)">
        <label class="text-caption text-secondary" style="display:block; font-weight:var(--weight-semibold); margin-bottom:var(--space-2)">
          {{ app()->getLocale() == 'ar' ? 'التوقيع الرقمي للمستثمر' : 'Investor Digital Signature' }}
        </label>
        <div id="nda-sig-pad" class="signature-pad" style="padding:0; overflow:hidden; position:relative; height: 150px; background: var(--bg-primary);">
          <canvas id="signature-canvas" style="width: 100%; height: 100%; display: block; cursor: crosshair; touch-action: none;"></canvas>
          <div id="nda-sig-prompt" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); pointer-events:none; color:var(--text-tertiary);">
            {{ app()->getLocale() == 'ar' ? 'قم بالتوقيع هنا' : 'Draw your signature here' }}
          </div>
        </div>
        <div style="text-align: right; margin-top: 8px;">
            <button class="btn btn-ghost btn-sm" onclick="clearSignature()">{{ app()->getLocale() == 'ar' ? 'مسح التوقيع' : 'Clear Signature' }}</button>
        </div>
      </div>

      <div style="display:flex; gap:12px">
        <button class="btn btn-ghost flex-1" style="border-radius:var(--radius-lg)" onclick="closeNdaModal()">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
        <button class="btn btn-primary flex-1" style="border-radius:var(--radius-lg); opacity: 0.6; cursor: not-allowed" id="nda-submit-btn" disabled onclick="submitNdaSignature()">
          {{ app()->getLocale() == 'ar' ? 'وقّع وأرسل' : 'Sign & Submit' }}
        </button>
      </div>
    </div>

    <!-- State 2: Success -->
    <div id="nda-success-state" style="display:none; text-align:center; padding:var(--space-6) 0">
      <div class="success-checkmark">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h3 class="text-h4" style="font-weight:var(--weight-bold); color:var(--text-primary)">
        {{ app()->getLocale() == 'ar' ? 'تم التوقيع بنجاح!' : 'Signed Successfully!' }}
      </h3>
      <p class="text-secondary text-body-sm mt-2 mb-6" id="nda-success-desc">
        {{ app()->getLocale() == 'ar' ? 'لقد تم تسجيل توقيعك بنظام العقود الذكية لـ ' : 'Your digital signature has been recorded in our smart contracts for ' }}
      </p>

      <button class="btn btn-primary" style="border-radius:var(--radius-lg); width:100%" onclick="closeNdaModal()">
        {{ app()->getLocale() == 'ar' ? 'العودة لمركز NDA' : 'Return to NDA Center' }}
      </button>
    </div>
  </div>
</div>

<!-- Signed NDA Viewer Modal -->
<div class="modal-overlay" id="signed-nda-viewer-modal" style="z-index: 9999;">
  <div class="modal-content-premium" style="max-width: 760px; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;">
    <button class="modal-close-btn" onclick="closeSignedNdaModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    
    <div style="padding-bottom:var(--space-3); border-bottom:1px solid var(--border-default); display:flex; justify-content:space-between; align-items:center">
      <h4 class="text-h6" style="margin:0; font-weight:var(--weight-bold); color:var(--text-primary)">
        {{ app()->getLocale() == 'ar' ? 'اتفاقية السرية الموقعة' : 'Signed NDA Document' }}
      </h4>
    </div>

    <!-- Paper Container -->
    <div style="flex:1; overflow-y:auto; padding:var(--space-6) var(--space-4); background:#1e1e1e; border-radius: var(--radius-lg); margin: var(--space-4) 0">
      <div class="report-paper" id="nda-paper-area">
        <!-- Watermark -->
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%) rotate(-30deg); font-size:3.5rem; font-weight:800; color:rgba(194,69,45,0.03); text-transform:uppercase; letter-spacing:6px; pointer-events:none; white-space:nowrap; user-select:none">
          SEVEN TECH CAPITAL
        </div>
        
        <!-- Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #C2452D; padding-bottom:12px; margin-bottom:20px">
          <div>
            <div style="font-size:16px; font-weight:800; color:#C2452D; letter-spacing:1px">SEVEN TECH</div>
            <div style="font-size:10px; font-weight:600; color:#666; letter-spacing:2px">C A P I T A L</div>
          </div>
          <div style="text-align:right">
            <div style="font-size:10px; color:#666; font-weight:600" id="nda-paper-date">Date: 2026-06-15</div>
            <div style="font-size:10px; color:#666; font-weight:600" id="nda-paper-ref">REF: STC-NDA-9082</div>
          </div>
        </div>

        <div>
          <h2 style="font-size:20px; font-weight:800; color:#111; margin:0 0 16px 0; text-align:center" id="nda-paper-title">MUTUAL NON-DISCLOSURE AGREEMENT</h2>
          
          <div style="font-size:12px; line-height:1.6; color:#222; display:flex; flex-direction:column; gap:12px">
            <p>
              {{ app()->getLocale() == 'ar'
                ? 'اتفاقية السرية المتبادلة وعدم الإفصاح تم إبرامها وتوقيعها رقمياً من قبل الأطراف المعنية للإفصاح عن البيانات الحساسة المتعلقة بالمشاريع المستهدفة.'
                : 'This Mutual Non-Disclosure Agreement (the "Agreement") is entered into and signed digitally to secure and restrict the exchange of sensitive information.' }}
            </p>
            <p>
              <strong>{{ app()->getLocale() == 'ar' ? 'الطرف الأول:' : 'Discloser:' }}</strong> Seven Tech Capital Ltd.<br>
              <strong>{{ app()->getLocale() == 'ar' ? 'الطرف الثاني (المتلقي):' : 'Recipient:' }}</strong> Khalid Al-Dosari (Investor Member)
            </p>
            <p>
              {{ app()->getLocale() == 'ar'
                ? 'يلتزم الطرف المتلقي بعدم الإفصاح أو نقل أو نشر أي تفاصيل مالية أو فنية أو قوائم عملاء أو خطط مستقبلية للمشروعات دون إذن خطي مسبق.'
                : 'The Recipient shall hold and maintain all Confidential Information in strict confidence and shall not disclose it to third parties.' }}
            </p>
          </div>

          <div style="display:flex; justify-content:space-between; align-items:center; margin-top:40px; border-top:1px dashed #ddd; padding-top:20px">
            <div>
              <div style="font-size:10px; color:#666">{{ app()->getLocale() == 'ar' ? 'توقيع الطرف الأول:' : 'Discloser Signature:' }}</div>
              <div style="font-family:serif; font-size:16px; font-weight:bold; color:#777; margin-top:4px">Seven Tech Auth</div>
            </div>
            <div>
              <div style="font-size:10px; color:#666">{{ app()->getLocale() == 'ar' ? 'توقيع الطرف الثاني:' : 'Recipient Signature:' }}</div>
              <div class="signature-stamp" style="margin-top:4px">Khalid Al-Dosari</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div style="display:flex; gap:12px">
      <button class="btn btn-ghost flex-1" style="border-radius:var(--radius-lg)" onclick="closeSignedNdaModal()">{{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}</button>
      <button id="download-nda-pdf-btn" class="btn btn-primary flex-1" style="border-radius:var(--radius-lg); display:inline-flex; align-items:center; justify-content:center; gap:6px" onclick="downloadNdaPdf()">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
        <span>{{ app()->getLocale() == 'ar' ? 'تنزيل الاتفاقية' : 'Download NDA' }}</span>
      </button>
    </div>
  </div>
</div>

<div class="toast-container" id="ndas-toast-container"></div>

<script>


  let currentNdaStatusFilter = 'all';
  let activeSigningNdaId = null;
  let hasSigned = false;
  let activeNdaTitle = '';

  // --- Toast Notification Manager ---
  function showToast(message, type = 'success') {
    const container = document.getElementById('ndas-toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `toast-alert ${type === 'error' ? 'toast-error' : ''}`;
    
    const icon = type === 'success' 
      ? `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--color-success)" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>`
      : `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--color-error)" stroke-width="3"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
      
    toast.innerHTML = `
      <div class="toast-alert-icon">${icon}</div>
      <div style="font-size:13px; font-weight:var(--weight-medium); color:var(--text-primary)">${message}</div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 50);
    
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 400);
    }, 3500);
  }

  // View Signed NDA Details
  function viewSignedNda(title) {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    activeNdaTitle = title;
    
    document.getElementById('nda-paper-title').innerText = isAr 
      ? `اتفاقية السرية المتبادلة لـ ${title}`
      : `MUTUAL NDA - ${title.toUpperCase()}`;
      
    document.getElementById('nda-paper-ref').innerText = `REF: STC-NDA-${Math.floor(1000 + Math.random() * 9000)}`;
    document.getElementById('nda-paper-date').innerText = `Date: ${new Date().toISOString().split('T')[0]}`;
    
    document.getElementById('signed-nda-viewer-modal').classList.add('active');
  }

  function closeSignedNdaModal() {
    document.getElementById('signed-nda-viewer-modal').classList.remove('active');
  }

  function downloadNdaPdf() {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    const btn = document.getElementById('download-nda-pdf-btn');
    const originalText = btn ? btn.innerHTML : '';
    
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = `<span class="btn-spinner" style="border-top-color:white; margin:0"></span> ${isAr ? 'جاري التجهيز...' : 'Preparing...'}`;
    }

    showToast(isAr ? 'جاري تجهيز المستند للطباعة/الحفظ بصيغة PDF...' : 'Preparing document for Print/PDF...', 'success');
    
    const paper = document.getElementById('nda-paper-area');
    
    // Create an iframe to hold the print content
    const iframe = document.createElement('iframe');
    iframe.style.position = 'absolute';
    iframe.style.width = '0px';
    iframe.style.height = '0px';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);
    
    const doc = iframe.contentWindow.document;
    
    // Copy styles
    let stylesHtml = '';
    document.querySelectorAll('style, link[rel="stylesheet"]').forEach(el => {
      stylesHtml += el.outerHTML;
    });
    
    // Print-specific styles
    stylesHtml += `
      <style>
        body { 
          background: white !important; 
          margin: 0; 
          padding: 20px; 
        }
        @media print {
          @page { margin: 15mm; }
          body { padding: 0; }
        }
        .nda-paper-container { 
          box-shadow: none !important; 
          border: none !important; 
          border-radius: 0 !important; 
          max-width: 100% !important; 
          margin: 0 !important; 
          padding: 0 !important; 
        }
        * { color: black !important; }
        .text-accent, .text-primary { color: #000 !important; }
        .text-secondary { color: #333 !important; }
        .signature-signed { background: transparent !important; border-bottom: 1px solid #ccc !important; }
      </style>
    `;
    
    const htmlContent = `
      <!DOCTYPE html>
      <html dir="${document.documentElement.dir || 'ltr'}">
        <head>
          <title>${activeNdaTitle}</title>
          ${stylesHtml}
        </head>
        <body>
          <div class="nda-paper-container" style="direction: ${document.documentElement.dir || 'ltr'}; text-align: ${document.documentElement.dir === 'rtl' ? 'right' : 'left'};">
            ${paper.innerHTML}
          </div>
          <script>
            window.onload = function() {
              setTimeout(function() {
                window.focus();
                window.print();
              }, 500);
            };
          <\/script>
        </body>
      </html>
    `;
    
    doc.open();
    doc.write(htmlContent);
    doc.close();

    // Re-enable button
    setTimeout(() => {
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    }, 1500);
    
    // Clean up iframe after printing
    setTimeout(() => {
      document.body.removeChild(iframe);
    }, 60000);
  }

  let signaturePadInitialized = false;
  let isDrawing = false;
  let canvasCtx = null;
  let lastX = 0;
  let lastY = 0;

  function initSignaturePad() {
    const canvas = document.getElementById('signature-canvas');
    if(!canvas) return;
    
    // Resize canvas to match display size
    const rect = canvas.parentElement.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;
    
    canvasCtx = canvas.getContext('2d');
    canvasCtx.strokeStyle = document.documentElement.getAttribute('data-theme') === 'dark' ? '#fff' : '#000';
    canvasCtx.lineWidth = 3;
    canvasCtx.lineCap = 'round';
    canvasCtx.lineJoin = 'round';

    const drawStart = (e) => {
      isDrawing = true;
      document.getElementById('nda-sig-prompt').style.display = 'none';
      const pos = getPos(canvas, e);
      lastX = pos.x;
      lastY = pos.y;
    };

    const drawMove = (e) => {
      if(!isDrawing) return;
      e.preventDefault(); // prevent scrolling on touch
      const pos = getPos(canvas, e);
      canvasCtx.beginPath();
      canvasCtx.moveTo(lastX, lastY);
      canvasCtx.lineTo(pos.x, pos.y);
      canvasCtx.stroke();
      lastX = pos.x;
      lastY = pos.y;
      hasSigned = true;
      
      const submitBtn = document.getElementById('nda-submit-btn');
      submitBtn.disabled = false;
      submitBtn.style.opacity = '1';
      submitBtn.style.cursor = 'pointer';
    };

    const drawEnd = () => {
      isDrawing = false;
    };

    canvas.addEventListener('mousedown', drawStart);
    canvas.addEventListener('mousemove', drawMove);
    canvas.addEventListener('mouseup', drawEnd);
    canvas.addEventListener('mouseout', drawEnd);

    canvas.addEventListener('touchstart', drawStart, {passive: false});
    canvas.addEventListener('touchmove', drawMove, {passive: false});
    canvas.addEventListener('touchend', drawEnd);
  }

  function getPos(canvas, e) {
    const rect = canvas.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    return {
      x: clientX - rect.left,
      y: clientY - rect.top
    };
  }

  function clearSignature() {
    const canvas = document.getElementById('signature-canvas');
    if(!canvas || !canvasCtx) return;
    canvasCtx.clearRect(0, 0, canvas.width, canvas.height);
    hasSigned = false;
    document.getElementById('nda-sig-prompt').style.display = 'block';
    
    const submitBtn = document.getElementById('nda-submit-btn');
    submitBtn.disabled = true;
    submitBtn.style.opacity = '0.6';
    submitBtn.style.cursor = 'not-allowed';
  }

  // Open signature modal
  function openNdaSignatureModal(ndaId, ndaTitle) {
    activeSigningNdaId = ndaId;
    hasSigned = false;

    // Reset modal states
    document.getElementById('nda-form-state').style.display = 'block';
    document.getElementById('nda-success-state').style.display = 'none';

    // Clear signature pad
    clearSignature();

    // Disable submit button
    const submitBtn = document.getElementById('nda-submit-btn');
    submitBtn.disabled = true;
    submitBtn.style.opacity = '0.6';
    submitBtn.style.cursor = 'not-allowed';

    // Set modal title
    document.getElementById('nda-modal-title').innerText = ndaTitle;

    document.getElementById('nda-modal').classList.add('active');
    
    if(!signaturePadInitialized) {
      setTimeout(() => {
        initSignaturePad();
        signaturePadInitialized = true;
      }, 100);
    }
  }

  function closeNdaModal() {
    document.getElementById('nda-modal').classList.remove('active');
  }

  // Submit mock signature and update DOM
  function submitNdaSignature() {
    if (!hasSigned || !activeSigningNdaId) return;

    // Show processing / success state immediately
    document.getElementById('nda-form-state').style.display = 'none';
    document.getElementById('nda-success-state').style.display = 'block';
    
    // Update success desc
    const cardEl = document.getElementById(`nda-card-${activeSigningNdaId}`);
    const titleVal = cardEl.getAttribute('data-title');
    document.getElementById('nda-success-desc').innerHTML = ("{{ app()->getLocale() == 'ar' }}" === "1" ? 'لقد تم تسجيل توقيعك بنجاح على اتفاقية: ' : 'Your digital signature has been recorded for: ') + `<strong>${titleVal}</strong>.`;

    // Update target card DOM status pill to "Active" dynamically!
    const badge = cardEl.querySelector('.nda-status-badge');
    badge.className = 'badge badge-success nda-status-badge';
    badge.style.cssText = 'color: var(--color-success); border-color: rgba(46,204,113,0.2); background: rgba(46,204,113,0.06); border-radius: var(--radius-full)';
    badge.innerText = "{{ app()->getLocale() == 'ar' }}" === "1" ? 'نشطة' : 'Active';

    // Update card icon color
    const iconBox = cardEl.querySelector('.nda-icon-box');
    iconBox.style.background = 'var(--color-success-bg)';
    iconBox.style.color = 'var(--color-success)';

    // Update action button to "View"
    const actionContainer = cardEl.querySelector('.nda-action-btn-container');
    actionContainer.innerHTML = `<button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)" onclick="viewSignedNda('${titleVal}')"><span>${"{{ app()->getLocale() == 'ar' }}" === "1" ? 'عرض' : 'View'}</span></button>`;

    // Update attributes
    cardEl.setAttribute('data-status', 'Active');
    cardEl.classList.remove('nda-card-pending');

    // Re-calculate statistics counts in the UI!
    setTimeout(() => {
      // Decrement pending count, increment active count
      const pendingTextEl = document.getElementById('pending-ndas-count');
      const activeTextEl = document.getElementById('active-ndas-count');
      
      let pCount = parseInt(pendingTextEl.innerText, 10);
      let aCount = parseInt(activeTextEl.innerText, 10);
      
      if (pCount > 0) {
        pendingTextEl.innerText = pCount - 1;
        activeTextEl.innerText = aCount + 1;
      }
    }, 500);
  }

  // Handle status filter chips click
  function filterNdaStatus(status, btn) {
    currentNdaStatusFilter = status;
    
    // Toggle active state for chips
    document.querySelectorAll('#ndas-filter-chips .chip-premium').forEach(chip => {
      chip.classList.remove('active');
    });
    btn.classList.add('active');
    
    filterNDAs();
  }

  // Local live filtering
  function filterNDAs() {
    const searchVal = document.getElementById('nda-search').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.nda-card-premium');
    
    let visibleCount = 0;

    cards.forEach(card => {
      const title = card.getAttribute('data-title').toLowerCase();
      const project = card.getAttribute('data-project').toLowerCase();
      const status = card.getAttribute('data-status');

      const matchesSearch = title.includes(searchVal) || project.includes(searchVal);
      
      let matchesStatus = false;
      if (currentNdaStatusFilter === 'all') {
        matchesStatus = true;
      } else if (currentNdaStatusFilter === 'Pending') {
        matchesStatus = status.includes('Pending');
      } else {
        matchesStatus = (status === currentNdaStatusFilter);
      }

      if (matchesSearch && matchesStatus) {
        card.style.display = 'block';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    const emptyState = document.getElementById('empty-state');
    if (visibleCount === 0) {
      emptyState.style.display = 'flex';
    } else {
      emptyState.style.display = 'none';
    }
  }

  // --- Edit/Delete Requests JS Logic ---
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-actions-wrapper')) {
      document.querySelectorAll('.dropdown-menu-premium').forEach(d => d.style.display = 'none');
    }
  });

  function toggleDropdown(e) {
    e.stopPropagation();
    const wrapper = e.target.closest('.dropdown-actions-wrapper');
    const menu = wrapper.querySelector('.dropdown-menu-premium');
    const isVisible = menu.style.display === 'block';
    document.querySelectorAll('.dropdown-menu-premium').forEach(d => d.style.display = 'none');
    menu.style.display = isVisible ? 'none' : 'block';
  }

  let activeRequestItemId = null;
  let activeRequestItemName = "";
  let activeRequestItemType = "";
  let activeRequestAction = "";

  function openRequestModal(itemId, itemName, itemType, actionType) {
    activeRequestItemId = itemId;
    activeRequestItemName = itemName;
    activeRequestItemType = itemType;
    activeRequestAction = actionType;

    document.getElementById('request-form-state').style.display = 'block';
    document.getElementById('request-loading-state').style.display = 'none';
    document.getElementById('request-success-state').style.display = 'none';

    document.getElementById('request-item-name').value = itemName;
    document.getElementById('request-action-type').value = actionType;
    document.getElementById('request-reason').value = "";
    document.getElementById('request-proposed').value = "";

    toggleChangesField(actionType);

    document.querySelectorAll('.dropdown-menu-premium').forEach(d => d.style.display = 'none');
    document.getElementById('asset-request-modal').classList.add('open');
  }

  function closeRequestModal() {
    document.getElementById('asset-request-modal').classList.remove('open');
  }

  function toggleChangesField(val) {
    document.getElementById('proposed-changes-group').style.display = val === 'edit' ? 'block' : 'none';
  }

  function submitAssetRequest() {
    const reason = document.getElementById('request-reason').value.trim();
    const proposed = document.getElementById('request-proposed').value.trim();
    const action = document.getElementById('request-action-type').value;
    const isAr = "{{ app()->getLocale() == 'ar' }}";

    if (!reason) {
      alert(isAr ? 'يرجى كتابة السبب أولاً' : 'Please provide a reason');
      return;
    }

    document.getElementById('request-form-state').style.display = 'none';
    document.getElementById('request-loading-state').style.display = 'block';

    setTimeout(() => {
      const requests = JSON.parse(localStorage.getItem('stc_asset_requests')) || [];
      const newReq = {
        id: Date.now(),
        item_id: activeRequestItemId,
        item_title: activeRequestItemName,
        item_type: activeRequestItemType,
        request_type: action,
        reason: reason,
        proposed_changes: action === 'edit' ? proposed : '',
        status: 'Pending',
        created_at: new Date().toISOString().split('T')[0]
      };
      requests.push(newReq);
      localStorage.setItem('stc_asset_requests', JSON.stringify(requests));

      document.getElementById('request-loading-state').style.display = 'none';
      document.getElementById('request-success-state').style.display = 'block';
      document.getElementById('request-success-desc').innerText = isAr
        ? `تم إرسال طلب ${action === 'edit' ? 'التعديل' : 'الحذف'} للاتفاقية "${activeRequestItemName}" بنجاح إلى المسؤول.`
        : `Your request to ${action} NDA "${activeRequestItemName}" has been successfully sent to the admin.`;

      showToast(isAr ? 'تم إرسال الطلب للمسؤول بنجاح' : 'Request submitted successfully', 'success');
      renderNdasRequests();
    }, 1200);
  }

  function renderNdasRequests() {
    const requests = JSON.parse(localStorage.getItem('stc_asset_requests')) || [];
    const ndaRequests = requests.filter(r => r.item_type === 'nda');
    const tbody = document.getElementById('submitted-requests-tbody');
    const isAr = "{{ app()->getLocale() == 'ar' }}";

    if (ndaRequests.length === 0) {
      document.getElementById('submitted-requests-section').style.display = 'none';
      return;
    }

    document.getElementById('submitted-requests-section').style.display = 'block';
    tbody.innerHTML = '';

    ndaRequests.forEach(r => {
      const typeBadge = r.request_type === 'edit'
        ? `<span class="badge badge-primary" style="color:var(--action-primary); border-color:rgba(255,90,0,0.2); background:rgba(255,90,0,0.06)">${isAr ? 'تعديل' : 'Edit'}</span>`
        : `<span class="badge badge-error" style="color:var(--color-error); border-color:rgba(217,48,37,0.2); background:rgba(217,48,37,0.06)">${isAr ? 'حذف' : 'Delete'}</span>`;

      let statusBadge = '';
      if (r.status === 'Pending') {
        statusBadge = `<span class="badge badge-warning badge-pulse" style="color:var(--color-warning); border-color:rgba(241,196,15,0.2); background:rgba(241,196,15,0.06)">${isAr ? 'معلق' : 'Pending'}</span>`;
      } else if (r.status === 'Approved') {
        statusBadge = `<span class="badge badge-success" style="color:var(--color-success); border-color:rgba(46,204,113,0.2); background:rgba(46,204,113,0.06)">${isAr ? 'مقبول' : 'Approved'}</span>`;
      } else {
        statusBadge = `<span class="badge badge-error" style="color:var(--color-error); border-color:rgba(217,48,37,0.2); background:rgba(217,48,37,0.06)">${isAr ? 'مرفوض' : 'Rejected'}</span>`;
      }

      let details = `<div><strong>${isAr ? 'السبب:' : 'Reason:'}</strong> ${r.reason}</div>`;
      if (r.request_type === 'edit' && r.proposed_changes) {
        details += `<div style="font-size:11px" class="text-secondary"><strong>${isAr ? 'التعديلات:' : 'Changes:'}</strong> ${r.proposed_changes}</div>`;
      }

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td style="padding:12px 16px; border-bottom:1px solid var(--border-subtle)"><strong>${r.item_title}</strong></td>
        <td style="padding:12px 16px; border-bottom:1px solid var(--border-subtle)">${typeBadge}</td>
        <td style="padding:12px 16px; border-bottom:1px solid var(--border-subtle); font-size:12px">${details}</td>
        <td style="padding:12px 16px; border-bottom:1px solid var(--border-subtle)" class="text-secondary">${r.created_at}</td>
        <td style="padding:12px 16px; border-bottom:1px solid var(--border-subtle)">${statusBadge}</td>
      `;
      tbody.appendChild(tr);
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    renderNdasRequests();
  });
</script>
@endsection