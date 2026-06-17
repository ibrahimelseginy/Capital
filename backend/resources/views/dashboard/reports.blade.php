@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'التقارير الدورية' : 'Periodic Reports')

@section('content')
<style>
  /* Premium Reports Styles */
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
    max-width: 300px;
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

  .filter-select-premium {
    padding: var(--space-2) var(--space-8) var(--space-2) var(--space-4);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-default);
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: var(--text-body-sm);
    cursor: pointer;
    min-width: 180px;
    transition: all 0.2s ease;
  }

  [dir="rtl"] .filter-select-premium {
    padding: var(--space-2) var(--space-4) var(--space-2) var(--space-8);
  }

  .filter-select-premium:focus {
    border-color: var(--color-primary);
    box-shadow: var(--shadow-focus);
    outline: none;
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

  /* Table design */
  .reports-table-container {
    background: var(--bg-surface);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-default);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }

  .reports-table {
    width: 100%;
    border-collapse: collapse;
    text-align: start;
  }

  .reports-table th {
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

  .reports-table td {
    padding: var(--space-4) var(--space-5);
    font-size: var(--text-body-sm);
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-subtle);
    vertical-align: middle;
    text-align: start;
  }

  .reports-table tr:last-child td {
    border-bottom: none;
  }

  .reports-table tr {
    transition: background-color 0.2s ease;
  }

  .reports-table tr:hover {
    background-color: var(--action-ghost-hover);
  }

  /* PDF Icon Container */
  .pdf-icon-container {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-lg);
    background: rgba(217, 48, 37, 0.1);
    color: #d93025;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Badge styling */
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

  /* Empty State */
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
    .search-wrapper, .filter-select-premium {
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
</style>

<div class="fade-in">
  <!-- Top Greeting & Intro -->
  <div class="mb-6 d-flex justify-between items-start flex-wrap gap-4">
    <div>
      <h2 class="text-h3" style="font-weight:var(--weight-bold); letter-spacing:-0.5px">
        {{ app()->getLocale() == 'ar' ? 'التقارير الاستثمارية' : 'Investment Reports' }}
      </h2>
      <p class="text-secondary mt-1">
        {{ app()->getLocale() == 'ar' ? 'استعرض التقارير الدورية والربع سنوية وتقارير العناية الواجبة الخاصة بمشاريعك.' : 'View periodic, quarterly, and due diligence reports for your invested ventures.' }}
      </p>
    </div>
  </div>

  @php
    $totalCount = count($reports);
    $quarterlyCount = $reports->where('type', 'Quarterly')->count();
    $monthlyCount = $reports->where('type', 'Monthly')->count();
    $ndaCount = $reports->where('status', 'NDA Required')->count();
  @endphp

  <!-- Stats Grid -->
  <div class="stats-grid">
    <!-- Stat 1 -->
    <div class="stat-card-premium">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'إجمالي التقارير' : 'Total Reports' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $totalCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'تقارير متوفرة للمحفظة' : 'Available portfolio files' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-primary-light); color:var(--color-primary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
    </div>

    <!-- Stat 2 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-info)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'تقارير ربع سنوية' : 'Quarterly Reports' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $quarterlyCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'أداء تفصيلي ربع سنوي' : 'Quarterly performance' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-info-bg); color:var(--color-info)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="18" y1="20" y2="10"/><line x1="12" x2="12" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
      </div>
    </div>

    <!-- Stat 3 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-success)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'تحديثات شهرية' : 'Monthly Updates' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $monthlyCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'موجز أداء شهري مبسط' : 'Brief monthly updates' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-success-bg); color:var(--color-success)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
      </div>
    </div>

    <!-- Stat 4 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-warning)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'يتطلب NDA' : 'NDA Required' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $ndaCount }}
        </div>
        <div class="text-caption mt-2" style="color:var(--color-warning); font-weight:var(--weight-medium); display:flex; align-items:center; gap:4px">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'توقيع اتفاقية السرية' : 'Requires NDA signature' }}</span>
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-warning-bg); color:var(--color-warning)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </div>
    </div>
  </div>

  <!-- Controls Bar (Filters & Project Dropdown) -->
  <div class="controls-bar">
    <!-- Live Search -->
    <div class="search-wrapper">
      <input type="text" id="report-search" class="search-input-premium" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث في التقارير...' : 'Search reports...' }}" onkeyup="filterReportsData()">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>

    <!-- Project Selector (Dynamic Project Filter) -->
    <div>
      <select id="project-filter" class="filter-select-premium form-select" onchange="filterByProject(this.value)">
        <option value="">{{ app()->getLocale() == 'ar' ? 'كل المشاريع' : 'All Projects' }}</option>
        @foreach($projects as $p)
          <option value="{{ $p->id }}" @if($projectId == $p->id) selected @endif>{{ $p->title }}</option>
        @endforeach
      </select>
    </div>

    <!-- Type Filters -->
    <div class="filter-chips-wrapper" id="reports-type-chips">
      <button class="chip-premium active" onclick="filterType('all', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</span>
      </button>
      <button class="chip-premium" onclick="filterType('Quarterly', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'ربع سنوي' : 'Quarterly' }}</span>
      </button>
      <button class="chip-premium" onclick="filterType('Monthly', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'شهري' : 'Monthly' }}</span>
      </button>
      <button class="chip-premium" onclick="filterType('Due Diligence', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'عناية واجبة' : 'Due Diligence' }}</span>
      </button>
    </div>
  </div>

  <!-- Empty State -->
  <div class="empty-state-wrapper" id="empty-state">
    <div class="empty-state-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>
    <h3 class="text-h5" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'لا توجد تقارير مطابقة لبحثك' : 'No reports found' }}</h3>
    <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'يرجى مراجعة خيارات الفرز أو معايير البحث.' : 'Please try adjusting your filter selectors or search criteria.' }}</p>
  </div>

  <!-- Table Content -->
  <div class="reports-table-container" id="reports-table-wrapper">
    <table class="reports-table">
      <thead>
        <tr>
          <th>{{ app()->getLocale() == 'ar' ? 'التقرير' : 'Report' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'المشروع' : 'Project' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الفترة' : 'Period' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'النوع' : 'Type' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الإجراءات' : 'Actions' }}</th>
        </tr>
      </thead>
      <tbody id="reports-list-body">
        @foreach($reports as $report)
          @php
            // Type badge color mapping
            $typeBadgeClass = 'badge-neutral';
            if ($report->type == 'Quarterly') {
                $typeBadgeClass = 'badge-neutral';
            } elseif ($report->type == 'Monthly') {
                $typeBadgeClass = 'badge-neutral';
            } elseif ($report->type == 'Due Diligence') {
                $typeBadgeClass = 'badge-primary';
            }

            // Status Badge
            $statusBadgeClass = 'badge-success';
            $statusStyle = '';
            if ($report->status == 'Published') {
                $statusBadgeClass = 'badge-success';
                $statusStyle = 'color: var(--color-success); border-color: rgba(46,204,113,0.2); background: rgba(46,204,113,0.06)';
            } else {
                $statusBadgeClass = 'badge-warning';
                $statusStyle = 'color: var(--color-warning); border-color: rgba(241,196,15,0.2); background: rgba(241,196,15,0.06)';
            }
          @endphp
          <tr class="report-row" data-type="{{ $report->type }}" data-title="{{ $report->title }}" data-project="{{ $report->project->title ?? '' }}">
            <!-- Title with PDF Icon -->
            <td>
              <div class="d-flex gap-3 items-center">
                <div class="pdf-icon-container">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div style="font-weight: var(--weight-semibold); color: var(--text-primary)">
                  {{ app()->getLocale() == 'ar' && $report->title == 'Q1 2026 Performance Report' ? 'تقرير أداء الربع الأول 2026' : (app()->getLocale() == 'ar' && $report->title == 'Monthly Update — May 2026' ? 'التحديث الشهري — مايو 2026' : (app()->getLocale() == 'ar' && $report->title == 'Due Diligence Report' ? 'تقرير العناية الواجبة للمشروع' : $report->title)) }}
                </div>
              </div>
            </td>
            <!-- Project Title -->
            <td>
              <span style="font-weight: var(--weight-medium)">{{ $report->project->title ?? '-' }}</span>
            </td>
            <!-- Period -->
            <td class="text-secondary">
              @if(app()->getLocale() == 'ar')
                @if($report->period == 'Jan-Mar 2026') يناير - مارس 2026 
                @elseif($report->period == 'May 2026') مايو 2026 
                @elseif($report->period == 'Mar 2026') مارس 2026 
                @else {{ $report->period }} 
                @endif
              @else
                {{ $report->period }}
              @endif
            </td>
            <!-- Type Badge -->
            <td>
              <span class="badge {{ $typeBadgeClass }}" style="border-radius: var(--radius-full)">
                {{ app()->getLocale() == 'ar' ? ($report->type == 'Quarterly' ? 'ربع سنوي' : ($report->type == 'Monthly' ? 'شهري' : 'عناية واجبة')) : $report->type }}
              </span>
            </td>
            <!-- Status Badge -->
            <td>
              <span class="badge {{ $statusBadgeClass }} @if($report->status != 'Published') badge-pulse @endif" style="border-radius: var(--radius-full); {{ $statusStyle }}">
                {{ app()->getLocale() == 'ar' ? ($report->status == 'Published' ? 'منشور' : 'يتطلب NDA') : $report->status }}
              </span>
            </td>
            <!-- Actions -->
            <td>
              <div class="d-flex gap-2 items-center">
                @if($report->status == 'Published')
                  <button class="btn btn-ghost btn-sm" style="color:var(--action-primary); border-radius:var(--radius-lg); font-weight:var(--weight-semibold); display:inline-flex; align-items:center; gap:4px" onclick="openReport('{{ $report->title }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <span>{{ app()->getLocale() == 'ar' ? 'عرض' : 'View' }}</span>
                  </button>
                @else
                  <button class="btn btn-secondary btn-sm" style="border-radius:var(--radius-lg); font-weight:var(--weight-semibold); display:inline-flex; align-items:center; gap:4px" onclick="window.location.href='{{ url('/dashboard/ndas') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>{{ app()->getLocale() == 'ar' ? 'توقيع NDA' : 'Sign NDA' }}</span>
                  </button>
                @endif

                <!-- More Actions Dropdown -->
                <div class="dropdown-actions-wrapper" style="position:relative">
                  <button class="btn btn-ghost btn-sm btn-icon dropdown-trigger" style="border-radius:var(--radius-lg); height: 32px; width:32px; min-width:32px; padding:0; display:flex; align-items:center; justify-content:center" onclick="toggleDropdown(event)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                  </button>
                  <div class="dropdown-menu-premium" style="display:none; position:absolute; top:100%; right:0; margin-top:8px; background:var(--bg-surface); border:1px solid var(--border-default); border-radius:var(--radius-lg); box-shadow:var(--shadow-lg); z-index:100; min-width:140px">
                    <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $report->id }}', '{{ $report->title }}', 'report', 'edit')" style="display:block; padding:10px 16px; font-size:12px; color:var(--text-primary); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب تعديل' : 'Request Edit' }}</a>
                    <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $report->id }}', '{{ $report->title }}', 'report', 'delete')" style="display:block; padding:10px 16px; font-size:12px; color:var(--color-error); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب حذف' : 'Request Delete' }}</a>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  <!-- Submitted Requests Log -->
  <div class="card mt-8" id="submitted-requests-section" style="display:none; padding:var(--space-6); border-radius:var(--radius-xl)">
    <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'طلبات التعديل والحذف المعلقة' : 'Pending Edit & Delete Requests' }}</h3>
    <div class="requests-table-container">
      <table class="requests-table" style="width:100%; border-collapse:collapse; text-align:start">
        <thead>
          <tr style="background:var(--bg-secondary)">
            <th style="padding:var(--space-3) var(--space-4); border-bottom:1px solid var(--border-default); text-transform:uppercase; font-size:11px; font-weight:bold; color:var(--text-secondary)">{{ app()->getLocale() == 'ar' ? 'التقرير' : 'Report' }}</th>
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

<!-- Report Viewer Modal -->
<div class="modal-overlay" id="report-viewer-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:var(--bg-overlay); backdrop-filter:blur(8px); z-index:9999; align-items:center; justify-content:center; padding:var(--space-4); opacity:0; transition:opacity 0.3s ease;">
  <div class="modal-box" style="width:100%; max-width:800px; background:var(--bg-surface); border:1px solid var(--border-default); border-radius:var(--radius-xl); box-shadow:var(--shadow-xl); overflow:hidden; display:flex; flex-direction:column; max-height:90vh; transform:translateY(20px); transition:transform 0.3s ease;">
    <!-- Modal Header -->
    <div style="padding:var(--space-4) var(--space-6); border-bottom:1px solid var(--border-default); display:flex; justify-content:space-between; align-items:center; background:var(--bg-secondary)">
      <div class="d-flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="var(--action-primary)" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
        <h4 class="text-h6" id="modal-report-title" style="margin:0; font-weight:var(--weight-bold); color:var(--text-primary)">Report Document</h4>
      </div>
      <button id="close-report-modal" style="background:transparent; border:none; cursor:pointer; color:var(--text-secondary); display:flex; align-items:center; justify-content:center; padding:var(--space-1)">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>

    <!-- Modal Scrollable Body (The Paper Sheet) -->
    <div style="flex:1; overflow-y:auto; padding:var(--space-8); background:#1e1e1e" id="report-paper-container">
      <!-- Paper Sheet -->
      <div class="report-paper" id="printable-report-area" style="background:white; color:#1a1a1a; padding:var(--space-10) var(--space-8); border-radius:var(--radius-sm); box-shadow:0 4px 20px rgba(0,0,0,0.15); max-width:680px; margin:0 auto; font-family:sans-serif; position:relative; min-height:800px; display:flex; flex-direction:column; justify-content:space-between">
        
        <!-- Watermark -->
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%) rotate(-30deg); font-size:4rem; font-weight:800; color:rgba(255,90,0,0.03); text-transform:uppercase; letter-spacing:8px; pointer-events:none; white-space:nowrap; user-select:none">
          SEVEN TECH CAPITAL
        </div>

        <div>
          <!-- Paper Header -->
          <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #FF5A00; padding-bottom:var(--space-4); margin-bottom:var(--space-6)">
            <div>
              <div style="font-size:16px; font-weight:800; color:#FF5A00; letter-spacing:1px">SEVEN TECH</div>
              <div style="font-size:10px; font-weight:600; color:#666; letter-spacing:2px">C A P I T A L</div>
            </div>
            <div style="text-align:right">
              <div style="font-size:10px; color:#666; font-weight:600" id="report-paper-date">Date: 2026-06-15</div>
              <div style="font-size:10px; color:#666; font-weight:600" id="report-paper-id">REF: STC-REP-9082</div>
            </div>
          </div>

          <!-- Project & Report Meta -->
          <div style="margin-bottom:var(--space-6)">
            <h2 style="font-size:22px; font-weight:800; color:#111; margin:0 0 var(--space-2) 0" id="report-paper-title">Q1 2026 Performance Report</h2>
            <div style="display:flex; gap:16px; font-size:12px; color:#555">
              <span><strong>Project:</strong> <span id="report-paper-project">FinFlow</span></span>
              <span>•</span>
              <span><strong>Period:</strong> <span id="report-paper-period">Jan-Mar 2026</span></span>
              <span>•</span>
              <span><strong>Status:</strong> <span style="color:#2ecc71; font-weight:bold">Official Audit</span></span>
            </div>
          </div>

          <!-- Summary Section -->
          <div style="margin-bottom:var(--space-6)">
            <h3 style="font-size:14px; font-weight:700; text-transform:uppercase; color:#FF5A00; border-bottom:1px solid #eee; padding-bottom:6px; margin:0 0 var(--space-3) 0">Executive Summary</h3>
            <p style="font-size:13px; color:#333; line-height:1.6; margin:0" id="report-paper-summary">
              This periodic report outlines the financial parameters, operational milestones, and investment yields for the project. Audits indicate significant user onboarding traction and sustainable revenue flows matching target estimations.
            </p>
          </div>

          <!-- KPI Metrics Grid -->
          <div style="margin-bottom:var(--space-8)">
            <h3 style="font-size:14px; font-weight:700; text-transform:uppercase; color:#FF5A00; border-bottom:1px solid #eee; padding-bottom:6px; margin:0 0 var(--space-3) 0">Financial Performance KPIs</h3>
            
            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-top:12px" id="report-paper-kpi-grid">
              <div style="background:#f9f9f9; padding:12px; border-radius:6px; border:1px solid #eee">
                <div style="font-size:10px; color:#666; font-weight:600; text-transform:uppercase">Net Revenue</div>
                <div style="font-size:18px; font-weight:800; color:#111; margin-top:4px" id="kpi-val-1">$1.24M</div>
                <div style="font-size:10px; color:#2ecc71; font-weight:600; margin-top:2px">↑ +42% QoQ</div>
              </div>
              <div style="background:#f9f9f9; padding:12px; border-radius:6px; border:1px solid #eee">
                <div style="font-size:10px; color:#666; font-weight:600; text-transform:uppercase">User Engagement</div>
                <div style="font-size:18px; font-weight:800; color:#111; margin-top:4px" id="kpi-val-2">250,000+</div>
                <div style="font-size:10px; color:#2ecc71; font-weight:600; margin-top:2px">↑ +18% Monthly</div>
              </div>
              <div style="background:#f9f9f9; padding:12px; border-radius:6px; border:1px solid #eee">
                <div style="font-size:10px; color:#666; font-weight:600; text-transform:uppercase">EBITDA Margin</div>
                <div style="font-size:18px; font-weight:800; color:#111; margin-top:4px" id="kpi-val-3">18.5%</div>
                <div style="font-size:10px; color:#2ecc71; font-weight:600; margin-top:2px">Stabilized</div>
              </div>
            </div>
          </div>

          <!-- Dynamic SVG Chart -->
          <div style="margin-bottom:var(--space-8)">
            <h3 style="font-size:14px; font-weight:700; text-transform:uppercase; color:#FF5A00; border-bottom:1px solid #eee; padding-bottom:6px; margin:0 0 16px 0">Performance Growth Trend</h3>
            <div style="text-align:center; background:#fafafa; border:1px solid #eee; border-radius:6px; padding:16px">
              <!-- Beautiful SVG Line Chart -->
              <svg width="100%" height="150" viewBox="0 0 500 150" style="overflow:visible">
                <!-- Grid Lines -->
                <line x1="50" y1="20" x2="450" y2="20" stroke="#f0f0f0" stroke-width="1"/>
                <line x1="50" y1="70" x2="450" y2="70" stroke="#f0f0f0" stroke-width="1"/>
                <line x1="50" y1="120" x2="450" y2="120" stroke="#f0f0f0" stroke-width="1"/>
                
                <!-- Chart Labels -->
                <text x="45" y="125" font-family="sans-serif" font-size="10" fill="#888" text-anchor="end">$0</text>
                <text x="45" y="75" font-family="sans-serif" font-size="10" fill="#888" text-anchor="end">$500K</text>
                <text x="45" y="25" font-family="sans-serif" font-size="10" fill="#888" text-anchor="end">$1M+</text>
                
                <text x="100" y="142" font-family="sans-serif" font-size="10" fill="#888" text-anchor="middle" id="chart-lbl-1">Month 1</text>
                <text x="250" y="142" font-family="sans-serif" font-size="10" fill="#888" text-anchor="middle" id="chart-lbl-2">Month 2</text>
                <text x="400" y="142" font-family="sans-serif" font-size="10" fill="#888" text-anchor="middle" id="chart-lbl-3">Month 3</text>
                
                <!-- Smooth Curve Area -->
                <path d="M 100 120 Q 250 80 400 30 L 400 120 Z" fill="rgba(255, 90, 0, 0.08)" stroke="none"/>
                <!-- Smooth Line -->
                <path d="M 100 120 Q 250 80 400 30" fill="none" stroke="#FF5A00" stroke-width="3" stroke-linecap="round"/>
                
                <!-- Dot Markers -->
                <circle cx="100" cy="120" r="5" fill="#FF5A00" stroke="white" stroke-width="2"/>
                <circle cx="250" cy="80" r="5" fill="#FF5A00" stroke="white" stroke-width="2"/>
                <circle cx="400" cy="30" r="5" fill="#FF5A00" stroke="white" stroke-width="2"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- Paper Footer -->
        <div style="border-top:1px solid #eee; padding-top:var(--space-4); display:flex; justify-content:space-between; align-items:center">
          <div>
            <div style="font-size:10px; font-weight:bold; color:#111">AUDITED BY</div>
            <div style="font-size:11px; color:#555; margin-top:2px">STC Investment Operations</div>
          </div>
          <div style="text-align:right">
            <div style="display:inline-flex; align-items:center; gap:6px; background:rgba(46,204,113,0.1); border:1px solid rgba(46,204,113,0.2); border-radius:30px; padding:4px 12px; color:#27ae60; font-size:10px; font-weight:800">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              <span>STC VERIFIED</span>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal Actions Footer -->
    <div style="padding:var(--space-4) var(--space-6); border-top:1px solid var(--border-default); display:flex; justify-content:end; gap:var(--space-3); background:var(--bg-secondary)">
      <button id="print-report-btn" class="btn btn-secondary btn-sm" style="border-radius:var(--radius-lg); padding:var(--space-2) var(--space-4); display:inline-flex; align-items:center; gap:6px">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        <span>{{ app()->getLocale() == 'ar' ? 'طباعة' : 'Print' }}</span>
      </button>
      <button id="download-report-pdf-btn" class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg); padding:var(--space-2) var(--space-4); display:inline-flex; align-items:center; gap:6px">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
        <span>{{ app()->getLocale() == 'ar' ? 'تحميل PDF' : 'Download PDF' }}</span>
      </button>
    </div>
  </div>
</div>

<!-- Global Toast Container -->
<div class="toast-container" id="reports-toast-container" style="position:fixed; bottom:var(--space-6); right:var(--space-6); z-index:99999; display:flex; flex-direction:column; gap:var(--space-3); pointer-events:none"></div>

<!-- Modal overlay css style injection -->
<style>
  .modal-overlay.show {
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
  }
  .modal-overlay.show .modal-box {
    transform: translateY(0) !important;
  }
  .btn-spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    vertical-align: middle;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
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
    transform: translateX(0) !important;
    opacity: 1 !important;
  }
  @media print {
    body * {
      visibility: hidden;
    }
    #printable-report-area, #printable-report-area * {
      visibility: visible;
    }
    #printable-report-area {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      box-shadow: none !important;
      padding: 0 !important;
    }
  }
</style>

<script>


  let currentTypeFilter = 'all';
  let activeReportTitle = '';

  // Toast Alert Manager
  function showToast(message, type = 'success') {
    const container = document.getElementById('reports-toast-container');
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
    
    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 50);
    
    // Auto remove
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 400);
    }, 3500);
  }

  // Open Report Modal
  function openReport(title) {
    activeReportTitle = title;
    
    // Find matching report details
    let project = 'FinFlow';
    let period = 'Jan-Mar 2026';
    let summary = '';
    let kpi1 = '$1.24M';
    let kpi2 = '250,000+';
    let kpi3 = '18.5%';
    let ref = 'STC-REP-1024';
    
    const isAr = "{{ app()->getLocale() == 'ar' }}";

    if (title.includes('Q1 2026')) {
      project = 'FinFlow';
      period = isAr ? 'يناير - مارس 2026' : 'Jan-Mar 2026';
      ref = 'STC-REP-1024';
      kpi1 = '$1.24M';
      kpi2 = '250,000+';
      kpi3 = '18.5%';
      summary = isAr 
        ? "أكملت SEVEN TECH CAPITAL تدقيق ومراجعة المؤشرات المالية والتشغيلية لمشروع FinFlow عن الربع الأول من عام 2026. أظهر المشروع نمواً قوياً بنسبة 42% مقارنة بالربع السابق، مع زيادة مطردة في كفاءة الخدمات التشغيلية وتقليل النفقات العامة."
        : "SEVEN TECH CAPITAL has audited the financial statements and operational milestones of FinFlow for Q1 2026. The venture has demonstrated strong product-market fit with net revenue expanding 42% quarter-over-quarter and stable cash flow reserves.";
    } else if (title.includes('May 2026')) {
      project = 'DataPulse';
      period = isAr ? 'مايو 2026' : 'May 2026';
      ref = 'STC-REP-3904';
      kpi1 = '$640K';
      kpi2 = '112,000+';
      kpi3 = '14.2%';
      summary = isAr
        ? "يسرنا تقديم التقرير الشهري لأداء مشروع DataPulse لشهر مايو 2026. حققت المنصة نمواً لافتاً في استخدام أدوات الذكاء الاصطناعي مع زيادة في الاشتراكات النشطة وارتفاع الإيرادات المتكررة بفضل الميزات السحابية الجديدة."
        : "We are pleased to present the monthly update for DataPulse for May 2026. The platform experienced double-digit growth in active subscribers, driven by new enterprise AI features and infrastructure optimization.";
    } else {
      project = 'BuildOS';
      period = isAr ? 'مارس 2026' : 'Mar 2026';
      ref = 'STC-REP-8812';
      kpi1 = '$420K';
      kpi2 = '45 Partners';
      kpi3 = '22.0%';
      summary = isAr
        ? "تقرير تقييم العناية الواجبة وتفاصيل الجاهزية التشغيلية لمشروع BuildOS العقاري. يوضح التقرير ملاءة المحفظة العقارية وتراخيص العمل بالإضافة إلى عوائد المشاريع المطورة والتحقق القانوني الكامل."
        : "Due diligence evaluation report for BuildOS. This document presents verified operational metrics, legal compliance verification, developer licensing statuses, and projected ROI margins for upcoming real estate developments.";
    }
    
    // Populate Modal
    document.getElementById('modal-report-title').textContent = title;
    document.getElementById('report-paper-title').textContent = isAr && title.includes('Q1 2026') ? 'تقرير أداء الربع الأول 2026' : (isAr && title.includes('May 2026') ? 'التحديث الشهري — مايو 2026' : (isAr && title.includes('Due Diligence') ? 'تقرير العناية الواجبة للمشروع' : title));
    document.getElementById('report-paper-project').textContent = project;
    document.getElementById('report-paper-period').textContent = period;
    document.getElementById('report-paper-summary').textContent = summary;
    document.getElementById('report-paper-id').textContent = 'REF: ' + ref;
    document.getElementById('report-paper-date').textContent = 'Date: ' + new Date().toISOString().split('T')[0];
    
    document.getElementById('kpi-val-1').textContent = kpi1;
    document.getElementById('kpi-val-2').textContent = kpi2;
    document.getElementById('kpi-val-3').textContent = kpi3;

    // Show Modal
    const modal = document.getElementById('report-viewer-modal');
    modal.style.display = 'flex';
    setTimeout(() => {
      modal.classList.add('show');
    }, 50);
  }

  // Close Modal
  document.getElementById('close-report-modal').addEventListener('click', closeReportModal);
  function closeReportModal() {
    const modal = document.getElementById('report-viewer-modal');
    modal.classList.remove('show');
    setTimeout(() => {
      modal.style.display = 'none';
    }, 300);
  }

  // Close when clicking overlay
  document.getElementById('report-viewer-modal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('report-viewer-modal')) {
      closeReportModal();
    }
  });

  // Print Report
  document.getElementById('print-report-btn').addEventListener('click', () => {
    const printContent = document.getElementById('printable-report-area').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
      <html>
        <head>
          <title>${activeReportTitle}</title>
          <style>
            body { font-family: sans-serif; padding: 40px; color: #1a1a1a; background: white; }
          </style>
        </head>
        <body onload="window.print(); window.close();">
          <div style="max-width: 700px; margin: 0 auto;">
            ${printContent}
          </div>
        </body>
      </html>
    `);
    printWindow.document.close();
  });

  // Download PDF (Browser Native Print for RTL support)
  document.getElementById('download-report-pdf-btn').addEventListener('click', () => {
    const btn = document.getElementById('download-report-pdf-btn');
    const originalText = btn.innerHTML;
    const isAr = "{{ app()->getLocale() == 'ar' }}";
    
    btn.disabled = true;
    btn.innerHTML = `<span class="btn-spinner" style="border-top-color:white; margin:0"></span> ${isAr ? 'جاري التجهيز...' : 'Preparing...'}`;
    
    showToast(isAr ? 'جاري تجهيز المستند للطباعة/الحفظ بصيغة PDF...' : 'Preparing document for Print/PDF...', 'success');
    
    const paper = document.getElementById('printable-report-area');
    
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
          font-family: sans-serif;
        }
        @media print {
          @page { margin: 15mm; }
          body { padding: 0; }
        }
        #printable-report-area { 
          box-shadow: none !important; 
          border: none !important; 
          border-radius: 0 !important; 
          max-width: 100% !important; 
          margin: 0 !important; 
          padding: 0 !important; 
        }
        * { color: black !important; }
      </style>
    `;
    
    const htmlContent = `
      <!DOCTYPE html>
      <html dir="${document.documentElement.dir || 'ltr'}">
        <head>
          <title>${activeReportTitle}_Report</title>
          ${stylesHtml}
        </head>
        <body>
          <div style="direction: ${document.documentElement.dir || 'ltr'}; text-align: ${document.documentElement.dir === 'rtl' ? 'right' : 'left'};">
            ${paper.outerHTML}
          </div>
          <script>
            window.onload = function() {
              setTimeout(function() {
                window.focus();
                window.print();
              }, 500);
            };
          <\\/script>
        </body>
      </html>
    `;
    
    doc.open();
    doc.write(htmlContent);
    doc.close();

    // Re-enable button
    setTimeout(() => {
      btn.disabled = false;
      btn.innerHTML = originalText;
    }, 1500);
    
    // Clean up iframe after printing
    setTimeout(() => {
      document.body.removeChild(iframe);
    }, 60000);
  });

  // Handle Project Dropdown redirect
  function filterByProject(projectId) {
    if (projectId) {
      window.location.href = "{{ url('/dashboard/reports') }}?project_id=" + projectId;
    } else {
      window.location.href = "{{ url('/dashboard/reports') }}";
    }
  }

  // Handle Type Filter Chips click
  function filterType(type, btn) {
    currentTypeFilter = type;
    
    // Toggle active state for chips
    document.querySelectorAll('#reports-type-chips .chip-premium').forEach(chip => {
      chip.classList.remove('active');
    });
    btn.classList.add('active');
    
    filterReportsData();
  }

  // Filter reports data locally on keyup / chip selection
  function filterReportsData() {
    const searchVal = document.getElementById('report-search').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.report-row');
    
    let visibleCount = 0;

    rows.forEach(row => {
      const title = row.getAttribute('data-title').toLowerCase();
      const project = row.getAttribute('data-project').toLowerCase();
      const type = row.getAttribute('data-type');

      const matchesSearch = title.includes(searchVal) || project.includes(searchVal);
      const matchesType = (currentTypeFilter === 'all') || (type === currentTypeFilter);

      if (matchesSearch && matchesType) {
        row.style.display = 'table-row';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    // Toggle Empty State Visibility
    const emptyState = document.getElementById('empty-state');
    const tableWrapper = document.getElementById('reports-table-wrapper');
    
    if (visibleCount === 0) {
      emptyState.style.display = 'flex';
      tableWrapper.style.display = 'none';
    } else {
      emptyState.style.display = 'none'; // Fix: show emptyState none when not empty
      tableWrapper.style.display = 'block';
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
        ? `تم إرسال طلب ${action === 'edit' ? 'التعديل' : 'الحذف'} للتقرير "${activeRequestItemName}" بنجاح إلى المسؤول.`
        : `Your request to ${action} report "${activeRequestItemName}" has been successfully sent to the admin.`;

      // Trigger toast (use global showToast from report.blade.php)
      showToast(isAr ? 'تم إرسال الطلب للمسؤول بنجاح' : 'Request submitted successfully');
      renderReportsRequests();
    }, 1200);
  }

  function renderReportsRequests() {
    const requests = JSON.parse(localStorage.getItem('stc_asset_requests')) || [];
    const repRequests = requests.filter(r => r.item_type === 'report');
    const tbody = document.getElementById('submitted-requests-tbody');
    const isAr = "{{ app()->getLocale() == 'ar' }}";

    if (repRequests.length === 0) {
      document.getElementById('submitted-requests-section').style.display = 'none';
      return;
    }

    document.getElementById('submitted-requests-section').style.display = 'block';
    tbody.innerHTML = '';

    repRequests.forEach(r => {
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
    renderReportsRequests();
  });
</script>
@endsection