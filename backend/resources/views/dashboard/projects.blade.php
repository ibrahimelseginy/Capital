@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'مشاريع المحفظة' : 'Portfolio Projects')

@section('content')
<style>
  /* Premium Dashboard Styles */
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

  .layout-switcher {
    display: flex;
    background: var(--bg-primary);
    padding: 2px;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-default);
  }

  .layout-btn {
    border: none;
    background: transparent;
    color: var(--text-secondary);
    width: 36px;
    height: 36px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .layout-btn:hover {
    color: var(--text-primary);
  }

  .layout-btn.active {
    background: var(--bg-surface);
    color: var(--color-primary);
    box-shadow: var(--shadow-sm);
  }

  /* Grid View Premium Cards */
  .projects-grid-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--space-6);
  }

  .project-card-premium {
    background: var(--bg-surface);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    box-shadow: var(--shadow-sm);
    transition: all 0.3s var(--ease-bounce);
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: visible;
  }

  .project-card-premium:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-xl);
    border-color: var(--border-strong);
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

  /* Progress bar styling */
  .progress-wrapper {
    margin: var(--space-4) 0;
  }

  .progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: var(--text-caption);
    color: var(--text-secondary);
    margin-bottom: var(--space-1);
  }

  .progress-track {
    width: 100%;
    height: 6px;
    background: var(--bg-primary);
    border-radius: var(--radius-full);
    overflow: hidden;
    position: relative;
  }

  .progress-bar {
    height: 100%;
    border-radius: var(--radius-full);
    transition: width 0.6s ease;
  }

  /* List View Style */
  .projects-list-container {
    display: none;
    background: var(--bg-surface);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-default);
    box-shadow: var(--shadow-sm);
    overflow: visible;
  }

  .list-table {
    width: 100%;
    border-collapse: collapse;
    text-align: start;
  }

  .list-table th {
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

  .list-table th:first-child {
    border-top-left-radius: var(--radius-xl);
  }
  .list-table th:last-child {
    border-top-right-radius: var(--radius-xl);
  }
  [dir="rtl"] .list-table th:first-child {
    border-top-left-radius: 0;
    border-top-right-radius: var(--radius-xl);
  }
  [dir="rtl"] .list-table th:last-child {
    border-top-right-radius: 0;
    border-top-left-radius: var(--radius-xl);
  }

  .list-table td {
    padding: var(--space-4) var(--space-5);
    font-size: var(--text-body-sm);
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-subtle);
    vertical-align: middle;
    text-align: start;
  }

  .list-table tr:last-child td {
    border-bottom: none;
  }

  .list-table tr {
    transition: background-color 0.2s ease;
  }

  .list-table tr:hover {
    background-color: var(--action-ghost-hover);
  }

  /* Card gradient logos */
  .logo-grad-1 { background: linear-gradient(135deg, #C2452D 0%, #DE634B 100%); }
  .logo-grad-2 { background: linear-gradient(135deg, #9b51e0 0%, #bb6bd9 100%); }
  .logo-grad-3 { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); }
  .logo-grad-4 { background: linear-gradient(135deg, #2f80ed 0%, #56ccf2 100%); }
  .logo-grad-5 { background: linear-gradient(135deg, #c6a15b 0%, #d7bc7d 100%); }

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
    .projects-grid-container {
      grid-template-columns: repeat(2, 1fr);
    }
    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .projects-grid-container {
      grid-template-columns: 1fr;
    }
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

  /* Actions Dropdown styling */
  .dropdown-menu-premium {
    animation: fadeIn var(--duration-fast) var(--ease-out) forwards;
  }
  .dropdown-item-premium {
    transition: background 0.2s ease;
  }
  .dropdown-item-premium:hover {
    background: var(--action-ghost-hover);
  }
  [dir="rtl"] .dropdown-menu-premium {
    right: auto;
    left: 0;
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
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-6);
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
</style>

<div class="fade-in">
  <!-- Top Greeting & Intro -->
  <div class="mb-6">
    <h2 class="text-h3" style="font-weight:var(--weight-bold); letter-spacing:-0.5px">
      {{ app()->getLocale() == 'ar' ? 'مشاريع المحفظة' : 'Portfolio Projects' }}
    </h2>
    <p class="text-secondary mt-1">
      {{ app()->getLocale() == 'ar' ? 'تابع استثماراتك، العوائد المستهدفة، والتقارير المالية الخاصة بكل مشروع في مكان واحد.' : 'Track your venture investments, targeted returns, and project financial updates in one place.' }}
    </p>
  </div>

  @php
    $allCount = count($projects);
    $activeCount = $projects->where('status', 'Active')->count();
    $scalingCount = $projects->whereIn('status', ['Scaling', 'Building'])->count();
    $exitedCount = $projects->where('status', 'Exited')->count();
    $totalInvested = $projects->sum('budget');
  @endphp

  <!-- Stats Summaries -->
  <div class="stats-grid">
    <!-- Stat 1 -->
    <div class="stat-card-premium">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'إجمالي الاستثمار' : 'Total Invested' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          ${{ number_format($totalInvested / 1000000, 1) }}M
        </div>
        <div class="text-caption mt-2" style="color:var(--color-success); font-weight:var(--weight-medium); display:flex; align-items:center; gap:4px">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
          <span>12% {{ app()->getLocale() == 'ar' ? 'الربع الحالي' : 'this quarter' }}</span>
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-primary-light); color:var(--color-primary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
    </div>

    <!-- Stat 2 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-success)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'مشاريع نشطة' : 'Active Projects' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $activeCount + $scalingCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'قيد التشغيل والتطوير' : 'Ongoing operations' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-success-bg); color:var(--color-success)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
    </div>

    <!-- Stat 3 -->
    <div class="stat-card-premium" style="--color-primary: var(--accent-gold)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'عمليات التخارج' : 'Exited Ventures' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $exitedCount }}
        </div>
        <div class="text-caption mt-2" style="color:var(--accent-gold); font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'تمت بنجاح' : 'Successfully exited' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-gold-light); color:var(--accent-gold)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
    </div>

    <!-- Stat 4 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-info)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'مضاعف محفظة الاستثمار' : 'Portfolio Multiple' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          3.2x
        </div>
        <div class="text-caption mt-2" style="color:var(--color-info); font-weight:var(--weight-medium); display:flex; align-items:center; gap:4px">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
          <span>+0.4x {{ app()->getLocale() == 'ar' ? 'من الربع الأخير' : 'from Q4' }}</span>
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-info-bg); color:var(--color-info)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="18" y1="20" y2="10"/><line x1="12" x2="12" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
      </div>
    </div>
  </div>

  <!-- Controls Bar (Filters, Search, View Switcher) -->
  <div class="controls-bar">
    <!-- Live Search -->
    <div class="search-wrapper">
      <input type="text" id="project-search" class="search-input-premium" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث عن مشروع...' : 'Search for a project...' }}" onkeyup="filterProjectsData()">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>

    <!-- Status Filters -->
    <div class="filter-chips-wrapper" id="projects-filter-chips">
      <button class="chip-premium active" onclick="filterStatus('all', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</span>
        <span class="chip-count">{{ $allCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterStatus('Active', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'نشط' : 'Active' }}</span>
        <span class="chip-count">{{ $activeCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterStatus('Scaling', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'توسع' : 'Scaling' }}</span>
        <span class="chip-count">{{ $scalingCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterStatus('Exited', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'خروج' : 'Exited' }}</span>
        <span class="chip-count">{{ $exitedCount }}</span>
      </button>
    </div>

    <!-- Layout Toggle Buttons -->
    <div class="layout-switcher">
      <button class="layout-btn active" id="btn-grid" onclick="setLayout('grid')" title="{{ app()->getLocale() == 'ar' ? 'عرض شبكي' : 'Grid View' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      </button>
      <button class="layout-btn" id="btn-list" onclick="setLayout('list')" title="{{ app()->getLocale() == 'ar' ? 'عرض قائمة' : 'List View' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
      </button>
    </div>
  </div>

  <!-- Empty State -->
  <div class="empty-state-wrapper" id="empty-state">
    <div class="empty-state-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>
    <h3 class="text-h5" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'لا توجد مشاريع مطابقة لبحثك' : 'No projects match your criteria' }}</h3>
    <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'يرجى مراجعة كلمات البحث أو تبديل خيار التصفية.' : 'Please try adjusting your search terms or filter chip selection.' }}</p>
  </div>

  <!-- Grid View Container -->
  <div class="projects-grid-container" id="projects-grid">
    @foreach($projects as $index => $project)
      @php
        // Distribute gradient logo backgrounds
        $gradClass = 'logo-grad-' . (($index % 5) + 1);
        
        // Progress percent mapped by status
        $progress = 85;
        if ($project->status == 'Exited') $progress = 100;
        elseif ($project->status == 'Building' || $project->status == 'Seed') $progress = 40;
        elseif ($project->status == 'Scaling') $progress = 70;
        
        // Status color configuration
        $badgeClass = 'badge-primary';
        $statusStyle = '';
        if ($project->status == 'Active') {
            $badgeClass = 'badge-success';
            $statusStyle = 'color: var(--color-success); border-color: rgba(46,204,113,0.2); background: rgba(46,204,113,0.06)';
        } elseif ($project->status == 'Exited') {
            $badgeClass = 'badge-gold';
            $statusStyle = 'color: var(--color-gold); border-color: rgba(198,161,91,0.2); background: rgba(198,161,91,0.06)';
        } elseif ($project->status == 'Scaling' || $project->status == 'Building') {
            $badgeClass = 'badge-primary';
            $statusStyle = 'color: var(--action-primary); border-color: rgba(255,90,0,0.2); background: rgba(255,90,0,0.06)';
        }
      @endphp
      <div class="project-card-premium card" data-status="{{ $project->status }}" data-title="{{ $project->title }}" data-desc="{{ $project->description }}">
        <!-- Card Header -->
        <div class="d-flex justify-between items-start mb-4">
          <div class="d-flex gap-3 items-center">
            <!-- Icon/Gradient Squircle -->
            <div class="stat-icon-container {{ $gradClass }}" style="width: 48px; height: 48px; border-radius: var(--radius-lg); font-weight: var(--weight-bold); font-size: 1.15rem; color: var(--color-white)">
              {{ $project->image ?? substr($project->title, 0, 1) }}
            </div>
            <div>
              <h3 class="text-h5" style="font-weight:var(--weight-bold); line-height: var(--leading-snug)">{{ $project->title }}</h3>
              <div class="text-caption text-secondary" style="font-weight:var(--weight-medium)">{{ $project->description }}</div>
            </div>
          </div>
          <!-- Status Badge -->
          <span class="badge {{ $badgeClass }} @if($project->status != 'Exited') badge-pulse @endif" style="border-radius:var(--radius-full); padding: var(--space-1) var(--space-3); font-size: var(--text-caption); {{ $statusStyle }}">
            {{ app()->getLocale() == 'ar' ? ($project->status == 'Active' ? 'نشط' : ($project->status == 'Exited' ? 'خروج' : 'قيد النمو')) : $project->status }}
          </span>
        </div>

        <!-- Progress Indicator -->
        <div class="progress-wrapper">
          <div class="progress-header">
            <span>{{ app()->getLocale() == 'ar' ? 'تقدم التمويل' : 'Funding Progress' }}</span>
            <span style="font-weight: var(--weight-semibold)">{{ $progress }}%</span>
          </div>
          <div class="progress-track">
            <div class="progress-bar" style="width: {{ $progress }}%; background: @if($project->status == 'Exited') var(--color-gold) @elseif($project->status == 'Active') var(--color-success) @else var(--color-primary) @endif"></div>
          </div>
        </div>

        <!-- Statistics Layout -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-4); margin: var(--space-3) 0 var(--space-4) 0; padding: var(--space-3); background: var(--bg-secondary); border-radius: var(--radius-lg)">
          <div>
            <div class="text-caption text-secondary" style="font-weight: var(--weight-medium)">
              {{ app()->getLocale() == 'ar' ? 'الاستثمار' : 'Invested' }}
            </div>
            <div class="text-label" style="font-weight: var(--weight-bold); color: var(--text-primary); margin-top: 2px">
              ${{ number_format($project->budget / 1000) }}K
            </div>
          </div>
          <div>
            <div class="text-caption text-secondary" style="font-weight: var(--weight-medium)">
              {{ app()->getLocale() == 'ar' ? 'العائد' : 'Target Return' }}
            </div>
            <div class="text-label" style="font-weight: var(--weight-bold); color: @if($project->status == 'Exited') var(--color-gold) @else var(--color-success) @endif; margin-top: 2px">
              {{ $project->status == 'Exited' ? '4.2x' : '2.1x' }}
            </div>
          </div>
        </div>

        <!-- Card Footer Actions -->
        <div class="d-flex gap-3 mt-auto pt-2" style="border-top: 1px solid var(--border-subtle); position: relative;">
          <button class="btn btn-secondary btn-sm flex-1" style="border-radius:var(--radius-lg); height: 38px; display: inline-flex; align-items: center; justify-content: center; gap: var(--space-2)" 
            onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
            <span>{{ app()->getLocale() == 'ar' ? 'التفاصيل' : 'View Details' }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s ease" class="arrow-icon"><line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>
          
          <button class="btn btn-ghost btn-sm flex-1" style="border-radius:var(--radius-lg); height: 38px; display: inline-flex; align-items: center; justify-content: center; gap: var(--space-2); color: var(--action-primary); position: relative" onclick="window.location.href='{{ url('/dashboard/reports') }}?project_id={{ $project->id }}'">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
            <span>{{ app()->getLocale() == 'ar' ? 'التقارير' : 'Reports' }}</span>
            @if($project->reports_count > 0)
              <span style="background: var(--color-primary); color: white; font-size: 9px; padding: 2px 6px; border-radius: var(--radius-full); font-weight: bold; margin-inline-start: 4px">
                {{ $project->reports_count }}
              </span>
            @endif
          </button>

          <!-- More Actions Dropdown -->
          <div class="dropdown-actions-wrapper" style="position:relative">
            <button class="btn btn-ghost btn-sm btn-icon dropdown-trigger" style="border-radius:var(--radius-lg); height: 38px; width:38px; min-width:38px; border:1px solid var(--border-default)" onclick="toggleDropdown(event)">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
            </button>
            <div class="dropdown-menu-premium" style="display:none; position:absolute; top:100%; right:0; margin-top:8px; background:var(--bg-surface); border:1px solid var(--border-default); border-radius:var(--radius-lg); box-shadow:var(--shadow-lg); z-index:100; min-width:140px">
              <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $project->id }}', '{{ $project->title }}', 'project', 'edit')" style="display:block; padding:10px 16px; font-size:12px; color:var(--text-primary); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب تعديل' : 'Request Edit' }}</a>
              <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $project->id }}', '{{ $project->title }}', 'project', 'delete')" style="display:block; padding:10px 16px; font-size:12px; color:var(--color-error); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب حذف' : 'Request Delete' }}</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <!-- List View Container -->
  <div class="projects-list-container" id="projects-list">
    <table class="list-table">
      <thead>
        <tr>
          <th>{{ app()->getLocale() == 'ar' ? 'المشروع' : 'Project' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'رأس المال المستثمر' : 'Capital Invested' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'العائد المتوقع' : 'Target Return' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'التقارير المتوفرة' : 'Reports Available' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الإجراءات' : 'Actions' }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($projects as $index => $project)
          @php
            $gradClass = 'logo-grad-' . (($index % 5) + 1);
            $badgeClass = 'badge-primary';
            $statusStyle = '';
            if ($project->status == 'Active') {
                $badgeClass = 'badge-success';
                $statusStyle = 'color: var(--color-success); border-color: rgba(46,204,113,0.2); background: rgba(46,204,113,0.06)';
            } elseif ($project->status == 'Exited') {
                $badgeClass = 'badge-gold';
                $statusStyle = 'color: var(--color-gold); border-color: rgba(198,161,91,0.2); background: rgba(198,161,91,0.06)';
            } elseif ($project->status == 'Scaling' || $project->status == 'Building') {
                $badgeClass = 'badge-primary';
                $statusStyle = 'color: var(--action-primary); border-color: rgba(255,90,0,0.2); background: rgba(255,90,0,0.06)';
            }
          @endphp
          <tr class="project-list-row" data-status="{{ $project->status }}" data-title="{{ $project->title }}" data-desc="{{ $project->description }}">
            <td>
              <div class="d-flex gap-3 items-center">
                <div class="stat-icon-container {{ $gradClass }}" style="width: 38px; height: 38px; border-radius: var(--radius-md); font-weight: var(--weight-bold); font-size: 0.95rem; color: var(--color-white)">
                  {{ $project->image ?? substr($project->title, 0, 1) }}
                </div>
                <div>
                  <div style="font-weight: var(--weight-bold); color: var(--text-primary)">{{ $project->title }}</div>
                  <div class="text-caption text-secondary" style="font-weight: var(--weight-medium)">{{ $project->description }}</div>
                </div>
              </div>
            </td>
            <td>
              <span class="badge {{ $badgeClass }} @if($project->status != 'Exited') badge-pulse @endif" style="border-radius:var(--radius-full); padding: var(--space-1) var(--space-3); font-size: var(--text-caption); {{ $statusStyle }}">
                {{ app()->getLocale() == 'ar' ? ($project->status == 'Active' ? 'نشط' : ($project->status == 'Exited' ? 'خروج' : 'قيد النمو')) : $project->status }}
              </span>
            </td>
            <td>
              <span style="font-weight: var(--weight-semibold)">${{ number_format($project->budget / 1000) }}K</span>
            </td>
            <td>
              <span style="color: @if($project->status == 'Exited') var(--color-gold) @else var(--color-success) @endif; font-weight: var(--weight-bold)">
                {{ $project->status == 'Exited' ? '4.2x' : '2.1x' }}
              </span>
            </td>
            <td>
              @if($project->reports_count > 0)
                <span class="badge badge-neutral" style="border-radius: var(--radius-full); font-weight: var(--weight-semibold)">
                  {{ $project->reports_count }} {{ app()->getLocale() == 'ar' ? 'تقارير' : 'reports' }}
                </span>
              @else
                <span class="text-secondary">-</span>
              @endif
            </td>
            <td>
              <div class="d-flex gap-2 items-center">
                <button class="btn btn-secondary btn-sm" style="border-radius:var(--radius-lg); padding: var(--space-1) var(--space-3); display: inline-flex; align-items: center; gap: 4px" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                  <span>{{ app()->getLocale() == 'ar' ? 'عرض' : 'View' }}</span>
                </button>
                <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg); padding: var(--space-1) var(--space-3); color: var(--action-primary)" onclick="window.location.href='{{ url('/dashboard/reports') }}?project_id={{ $project->id }}'">
                  <span>{{ app()->getLocale() == 'ar' ? 'التقارير' : 'Reports' }}</span>
                </button>

                <!-- More Actions Dropdown -->
                <div class="dropdown-actions-wrapper" style="position:relative">
                  <button class="btn btn-ghost btn-sm btn-icon dropdown-trigger" style="border-radius:var(--radius-lg); height: 32px; width:32px; min-width:32px; padding:0; display:flex; align-items:center; justify-content:center" onclick="toggleDropdown(event)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                  </button>
                  <div class="dropdown-menu-premium" style="display:none; position:absolute; top:100%; right:0; margin-top:8px; background:var(--bg-surface); border:1px solid var(--border-default); border-radius:var(--radius-lg); box-shadow:var(--shadow-lg); z-index:100; min-width:140px">
                    <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $project->id }}', '{{ $project->title }}', 'project', 'edit')" style="display:block; padding:10px 16px; font-size:12px; color:var(--text-primary); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب تعديل' : 'Request Edit' }}</a>
                    <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $project->id }}', '{{ $project->title }}', 'project', 'delete')" style="display:block; padding:10px 16px; font-size:12px; color:var(--color-error); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب حذف' : 'Request Delete' }}</a>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Submitted Requests Log -->
  <div class="card mt-8" id="submitted-requests-section" style="display:none; padding:var(--space-6); border-radius:var(--radius-xl)">
    <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'طلبات التعديل والحذف المعلقة' : 'Pending Edit & Delete Requests' }}</h3>
    <div class="requests-table-container">
      <table class="requests-table" style="width:100%; border-collapse:collapse; text-align:start">
        <thead>
          <tr style="background:var(--bg-secondary)">
            <th style="padding:var(--space-3) var(--space-4); border-bottom:1px solid var(--border-default); text-transform:uppercase; font-size:11px; font-weight:bold; color:var(--text-secondary)">{{ app()->getLocale() == 'ar' ? 'العنصر' : 'Item' }}</th>
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

@include('components.request-modal')

<div class="toast-container" id="projects-toast-container"></div>

<script>
  // Active state status filter
  let currentFilter = 'all';
  
  // Layout state
  let currentLayout = localStorage.getItem('stc-projects-layout') || 'grid';

  // Initialize Layout on Load
  document.addEventListener('DOMContentLoaded', () => {
    setLayout(currentLayout);
  });

  // Toggle layout between grid and list
  function setLayout(layout) {
    currentLayout = layout;
    localStorage.setItem('stc-projects-layout', layout);

    const gridContainer = document.getElementById('projects-grid');
    const listContainer = document.getElementById('projects-list');
    const btnGrid = document.getElementById('btn-grid');
    const btnList = document.getElementById('btn-list');

    if (layout === 'grid') {
      gridContainer.style.display = 'grid';
      listContainer.style.display = 'none';
      btnGrid.classList.add('active');
      btnList.classList.remove('active');
    } else {
      gridContainer.style.display = 'none';
      listContainer.style.display = 'block';
      btnGrid.classList.remove('active');
      btnList.classList.add('active');
    }
    
    // Refresh item visibilities based on current filter & search
    filterProjectsData();
  }

  // Handle status filter chips click
  function filterStatus(status, element) {
    currentFilter = status;
    
    // Toggle active state for chips
    document.querySelectorAll('#projects-filter-chips .chip-premium').forEach(chip => {
      chip.classList.remove('active');
    });
    element.classList.add('active');
    
    filterProjectsData();
  }

  // Perform dynamic filtering based on search query and status filter
  function filterProjectsData() {
    const searchVal = document.getElementById('project-search').value.toLowerCase().trim();
    
    let visibleCount = 0;
    
    // Filter cards (Grid)
    const cards = document.querySelectorAll('.project-card-premium');
    cards.forEach(card => {
      const title = card.getAttribute('data-title').toLowerCase();
      const desc = card.getAttribute('data-desc').toLowerCase();
      const status = card.getAttribute('data-status');
      
      const matchesSearch = title.includes(searchVal) || desc.includes(searchVal);
      
      let matchesStatus = false;
      if (currentFilter === 'all') {
        matchesStatus = true;
      } else if (currentFilter === 'Scaling') {
        matchesStatus = (status === 'Scaling' || status === 'Building');
      } else {
        matchesStatus = (status === currentFilter);
      }
      
      if (matchesSearch && matchesStatus) {
        card.style.display = 'flex';
        if (currentLayout === 'grid') visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    // Filter rows (List)
    const rows = document.querySelectorAll('.project-list-row');
    rows.forEach(row => {
      const title = row.getAttribute('data-title').toLowerCase();
      const desc = row.getAttribute('data-desc').toLowerCase();
      const status = row.getAttribute('data-status');
      
      const matchesSearch = title.includes(searchVal) || desc.includes(searchVal);
      
      let matchesStatus = false;
      if (currentFilter === 'all') {
        matchesStatus = true;
      } else if (currentFilter === 'Scaling') {
        matchesStatus = (status === 'Scaling' || status === 'Building');
      } else {
        matchesStatus = (status === currentFilter);
      }
      
      if (matchesSearch && matchesStatus) {
        row.style.display = 'table-row';
        if (currentLayout === 'list') visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    // Toggle Empty State Visibility
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
      document.querySelectorAll('.project-card-premium, .project-list-row').forEach(c => c.style.zIndex = '1');
    }
  });

  function toggleDropdown(e) {
    e.stopPropagation();
    const wrapper = e.target.closest('.dropdown-actions-wrapper');
    const menu = wrapper.querySelector('.dropdown-menu-premium');
    const isVisible = menu.style.display === 'block';
    
    document.querySelectorAll('.dropdown-menu-premium').forEach(d => d.style.display = 'none');
    document.querySelectorAll('.project-card-premium, .project-list-row').forEach(c => c.style.zIndex = '1');
    
    if (!isVisible) {
      menu.style.display = 'block';
      const card = wrapper.closest('.project-card-premium') || wrapper.closest('.project-list-row');
      if (card) card.style.zIndex = '100';
    }
  }

  // Request Modal logic is now handled by components/request-modal

  function showToastAlert(message) {
    const container = document.getElementById('projects-toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast-alert';
    toast.innerHTML = `
      <div class="toast-alert-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--color-success)" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div style="font-size:13px; font-weight:var(--weight-medium); color:var(--text-primary)">${message}</div>
    `;
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 50);
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 400);
    }, 3500);
  }

  function renderProjectsRequests() {
    const requests = JSON.parse(localStorage.getItem('stc_asset_requests')) || [];
    const projRequests = requests.filter(r => r.item_type === 'project');
    const tbody = document.getElementById('submitted-requests-tbody');
    const isAr = "{{ app()->getLocale() == 'ar' }}";

    if (projRequests.length === 0) {
      document.getElementById('submitted-requests-section').style.display = 'none';
      return;
    }

    document.getElementById('submitted-requests-section').style.display = 'block';
    tbody.innerHTML = '';

    projRequests.forEach(r => {
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

  // Render on load
  document.addEventListener('DOMContentLoaded', () => {
    renderProjectsRequests();
  });
</script>
@endsection