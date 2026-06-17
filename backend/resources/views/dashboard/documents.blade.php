@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'مركز المستندات' : 'Document Center')

@section('content')
<style>
  /* Premium Document Center Styles */
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

  /* Table Style */
  .documents-table-container {
    background: var(--bg-surface);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-default);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }

  .documents-table {
    width: 100%;
    border-collapse: collapse;
    text-align: start;
  }

  .documents-table th {
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

  .documents-table td {
    padding: var(--space-4) var(--space-5);
    font-size: var(--text-body-sm);
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-subtle);
    vertical-align: middle;
    text-align: start;
  }

  .documents-table tr:last-child td {
    border-bottom: none;
  }

  .documents-table tr {
    transition: background-color 0.2s ease;
  }

  .documents-table tr:hover {
    background-color: var(--action-ghost-hover);
  }

  /* Custom Type Icons */
  .doc-icon-container {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .icon-legal {
    background: rgba(198, 161, 91, 0.1);
    color: var(--color-gold);
  }

  .icon-financial {
    background: rgba(26, 115, 232, 0.1);
    color: var(--color-info);
  }

  .icon-nda {
    background: rgba(255, 90, 0, 0.1);
    color: var(--color-primary);
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
  <div class="mb-6">
    <h2 class="text-h3" style="font-weight:var(--weight-bold); letter-spacing:-0.5px">
      {{ app()->getLocale() == 'ar' ? 'مركز المستندات' : 'Document Center' }}
    </h2>
    <p class="text-secondary mt-1">
      {{ app()->getLocale() == 'ar' ? 'استعرض ووقع وحمل المستندات القانونية والمالية واتفاقيات السرية الخاصة بمشاريعك.' : 'View, sign, and download legal papers, share certificates, and NDA agreements.' }}
    </p>
  </div>

  @php
    $totalCount = count($documents);
    $legalCount = $documents->where('type', 'Legal')->count();
    $financialCount = $documents->where('type', 'Financial')->count();
    $ndaCount = $documents->where('type', 'NDA')->count();
  @endphp

  <!-- Stats Grid -->
  <div class="stats-grid">
    <!-- Stat 1 -->
    <div class="stat-card-premium">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'إجمالي المستندات' : 'Total Documents' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $totalCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'ملفات في أرشيفك' : 'Files in your archive' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-primary-light); color:var(--color-primary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
    </div>

    <!-- Stat 2 -->
    <div class="stat-card-premium" style="--color-primary: var(--accent-gold)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'مستندات قانونية' : 'Legal Documents' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $legalCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'اتفاقيات وقرارات مجلس' : 'Agreements & resolutions' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-gold-light); color:var(--accent-gold)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
    </div>

    <!-- Stat 3 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-info)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'مستندات مالية' : 'Financial Documents' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $financialCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'شهادات الأسهم والحصص' : 'Share certificates' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-info-bg); color:var(--color-info)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
      </div>
    </div>

    <!-- Stat 4 -->
    <div class="stat-card-premium">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'اتفاقيات سرية NDA' : 'NDAs Center' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $ndaCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'اتفاقيات عدم الإفصاح' : 'Confidentiality papers' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-primary-light); color:var(--color-primary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </div>
    </div>
  </div>

  <!-- Controls Bar (Filters & Search) -->
  <div class="controls-bar">
    <!-- Live Search -->
    <div class="search-wrapper">
      <input type="text" id="doc-search" class="search-input-premium" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث في المستندات...' : 'Search for a document...' }}" onkeyup="filterDocs()">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>

    <!-- Type Filter Chips -->
    <div class="filter-chips-wrapper" id="docs-filter-chips">
      <button class="chip-premium active" onclick="filterType('all', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</span>
        <span class="chip-count">{{ $totalCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterType('Legal', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'قانوني' : 'Legal' }}</span>
        <span class="chip-count">{{ $legalCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterType('Financial', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'مالي' : 'Financial' }}</span>
        <span class="chip-count">{{ $financialCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterType('NDA', this)">
        <span>NDA</span>
        <span class="chip-count">{{ $ndaCount }}</span>
      </button>
    </div>
  </div>

  <!-- Empty State -->
  <div class="empty-state-wrapper" id="empty-state">
    <div class="empty-state-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>
    <h3 class="text-h5" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'لا توجد مستندات مطابقة لبحثك' : 'No documents found' }}</h3>
    <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'يرجى تغيير الكلمات المفتاحية أو خيارات التصفية.' : 'Please try adjusting your search terms or filter selection.' }}</p>
  </div>

  <!-- Table Wrapper -->
  <div class="documents-table-container" id="docs-table-wrapper">
    <table class="documents-table">
      <thead>
        <tr>
          <th>{{ app()->getLocale() == 'ar' ? 'المستند' : 'Document' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'المشروع' : 'Project' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'النوع' : 'Type' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'الإجراءات' : 'Actions' }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($documents as $doc)
          @php
            // Type icon & style
            $iconClass = 'icon-legal';
            if ($doc->type == 'Legal') $iconClass = 'icon-legal';
            elseif ($doc->type == 'Financial') $iconClass = 'icon-financial';
            elseif ($doc->type == 'NDA') $iconClass = 'icon-nda';

            // Status style
            $statusStyle = '';
            $statusBadgeClass = 'badge-success';
            if ($doc->status == 'Signed' || $doc->status == 'Active') {
                $statusBadgeClass = 'badge-success';
                $statusStyle = 'color: var(--color-success); border-color: rgba(46,204,113,0.2); background: rgba(46,204,113,0.06)';
            } else {
                $statusBadgeClass = 'badge-warning';
                $statusStyle = 'color: var(--color-warning); border-color: rgba(241,196,15,0.2); background: rgba(241,196,15,0.06)';
            }
          @endphp
          <tr class="doc-row" data-type="{{ $doc->type }}" data-title="{{ $doc->title }}" data-project="{{ $doc->project->title ?? 'General' }}">
            <!-- Name & Icon -->
            <td>
              <div class="d-flex gap-3 items-center">
                <div class="doc-icon-container {{ $iconClass }}">
                  @if($doc->type == 'Legal')
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                  @elseif($doc->type == 'Financial')
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
                  @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                  @endif
                </div>
                <span class="text-label" style="font-weight: var(--weight-semibold); color: var(--text-primary)">
                  {{ app()->getLocale() == 'ar' && $doc->title == 'Investment Agreement — FinFlow' ? 'اتفاقية استثمار — FinFlow' : (app()->getLocale() == 'ar' && $doc->title == 'Share Certificate — DataPulse' ? 'شهادة أسهم — DataPulse' : (app()->getLocale() == 'ar' && $doc->title == 'Board Resolution — BuildOS' ? 'قرار مجلس إدارة — BuildOS' : $doc->title)) }}
                </span>
              </div>
            </td>
            <!-- Project -->
            <td>
              <span style="font-weight: var(--weight-medium)">{{ $doc->project->title ?? (app()->getLocale() == 'ar' ? 'عام' : 'General') }}</span>
            </td>
            <!-- Type Badge -->
            <td>
              <span class="badge badge-neutral" style="border-radius: var(--radius-full)">
                {{ app()->getLocale() == 'ar' ? ($doc->type == 'Legal' ? 'قانوني' : ($doc->type == 'Financial' ? 'مالي' : 'اتفاقية سرية')) : $doc->type }}
              </span>
            </td>
            <!-- Date Created -->
            <td class="text-secondary">
              @if(app()->getLocale() == 'ar')
                @if($doc->created_at)
                  {{ $doc->created_at->format('M Y') }}
                @else
                  @if($doc->title == 'Investment Agreement — FinFlow') يناير 2024
                  @elseif($doc->title == 'Share Certificate — DataPulse') مارس 2024
                  @elseif($doc->title == 'Board Resolution — BuildOS') مايو 2026
                  @else يونيو 2026
                  @endif
                @endif
              @else
                @if($doc->created_at)
                  {{ $doc->created_at->format('M Y') }}
                @else
                  @if($doc->title == 'Investment Agreement — FinFlow') Jan 2024
                  @elseif($doc->title == 'Share Certificate — DataPulse') Mar 2024
                  @elseif($doc->title == 'Board Resolution — BuildOS') May 2026
                  @else Jun 2026
                  @endif
                @endif
              @endif
            </td>
            <!-- Access / Status -->
            <td>
              <span class="badge {{ $statusBadgeClass }} @if($doc->status == 'Pending') badge-pulse @endif" style="border-radius: var(--radius-full); {{ $statusStyle }}">
                {{ app()->getLocale() == 'ar' ? ($doc->status == 'Signed' ? 'موقّع' : ($doc->status == 'Active' ? 'نشط' : 'معلق')) : $doc->status }}
              </span>
            </td>
            <!-- Actions -->
            <td>
              <div class="d-flex gap-2 items-center">
                @if($doc->status == 'Signed' || $doc->status == 'Active')
                  <button class="btn btn-ghost btn-sm" style="color: var(--action-primary); border-radius: var(--radius-lg); font-weight:var(--weight-semibold); display:inline-flex; align-items:center; gap:4px" onclick="downloadDoc('{{ $doc->title }}', '{{ $doc->project->title ?? (app()->getLocale() == 'ar' ? 'عام' : 'General') }}', '{{ $doc->type }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    <span>{{ app()->getLocale() == 'ar' ? 'تحميل' : 'Download' }}</span>
                  </button>
                @else
                  <button class="btn btn-secondary btn-sm" style="border-radius: var(--radius-lg); font-weight:var(--weight-semibold); display:inline-flex; align-items:center; gap:4px" onclick="window.location.href='{{ url('/dashboard/ndas') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>{{ app()->getLocale() == 'ar' ? 'مراجعة وتوقيع' : 'Review & Sign' }}</span>
                  </button>
                @endif

                <!-- More Actions Dropdown -->
                <div class="dropdown-actions-wrapper" style="position:relative">
                  <button class="btn btn-ghost btn-sm btn-icon dropdown-trigger" style="border-radius:var(--radius-lg); height: 32px; width:32px; min-width:32px; padding:0; display:flex; align-items:center; justify-content:center" onclick="toggleDropdown(event)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                  </button>
                  <div class="dropdown-menu-premium" style="display:none; position:absolute; top:100%; right:0; margin-top:8px; background:var(--bg-surface); border:1px solid var(--border-default); border-radius:var(--radius-lg); box-shadow:var(--shadow-lg); z-index:100; min-width:140px">
                    <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $doc->id }}', '{{ $doc->title }}', 'document', 'edit')" style="display:block; padding:10px 16px; font-size:12px; color:var(--text-primary); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب تعديل' : 'Request Edit' }}</a>
                    <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $doc->id }}', '{{ $doc->title }}', 'document', 'delete')" style="display:block; padding:10px 16px; font-size:12px; color:var(--color-error); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب حذف' : 'Request Delete' }}</a>
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
            <th style="padding:var(--space-3) var(--space-4); border-bottom:1px solid var(--border-default); text-transform:uppercase; font-size:11px; font-weight:bold; color:var(--text-secondary)">{{ app()->getLocale() == 'ar' ? 'المستند' : 'Document' }}</th>
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

<!-- Global Toast Container & Spinner Overlay -->
<div class="toast-container" id="docs-toast-container" style="position:fixed; bottom:var(--space-6); right:var(--space-6); z-index:99999; display:flex; flex-direction:column; gap:var(--space-3); pointer-events:none"></div>
<div class="modal-overlay" id="download-progress-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:var(--bg-overlay); backdrop-filter:blur(4px); z-index:99999; align-items:center; justify-content:center; opacity:0; transition:opacity 0.25s;">
  <div style="background:var(--bg-surface); padding:var(--space-6); border-radius:var(--radius-xl); border:1px solid var(--border-default); box-shadow:var(--shadow-xl); text-align:center; max-width:320px; width:90%">
    <div class="btn-spinner" style="width:36px; height:36px; border-width:3px; border-top-color:var(--action-primary); margin-bottom:var(--space-4)"></div>
    <h5 class="text-body" style="font-weight:var(--weight-bold); margin:0 0 8px 0" id="download-progress-title">{{ app()->getLocale() == 'ar' ? 'جاري تحضير الملف...' : 'Preparing document...' }}</h5>
    <p class="text-caption text-secondary" style="margin:0">{{ app()->getLocale() == 'ar' ? 'يرجى الانتظار لحين تشفير وتنزيل المستند.' : 'Securing and generating file for transfer.' }}</p>
  </div>
</div>

<style>
  .modal-overlay.show {
    opacity: 1 !important;
    pointer-events: auto !important;
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
</style>

<script>
  let currentTypeFilter = 'all';

  // Toast Alert Manager
  function showToast(message, type = 'success') {
    const container = document.getElementById('docs-toast-container');
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

  // Helper to generate real document PDF blob client-side
  function generateDocumentPdfBlob(title, project, type) {
    const refKey = `STC-DOC-SEC-${Math.floor(100000 + Math.random() * 900000)}`;
    const dateStr = new Date().toLocaleString();
    
    let streamContent = "";
    
    // Draw top dark banner
    streamContent += "0.08 0.08 0.08 rg\n";
    streamContent += "0 740 595 102 re\n";
    streamContent += "f\n";
    
    // Draw gold/orange accent line at bottom of banner
    streamContent += "1 0.35 0 rg\n";
    streamContent += "0 736 595 4 re\n";
    streamContent += "f\n";
    
    // Header Title
    streamContent += "BT\n/F2 18 Tf\n1 1 1 rg\n40 795 Td\n(SEVEN TECH CAPITAL) Tj\nET\n";
    
    // Header Subtitle
    streamContent += `BT\n/F1 9 Tf\n1 0.6 0.2 rg\n40 775 Td\n(OFFICIAL SECURED ${type.toUpperCase()} EXCHANGE) Tj\nET\n`;
    
    // Draw Metadata Card Container (Light grey box with border)
    streamContent += "0.97 0.97 0.97 rg\n";
    streamContent += "0.85 0.85 0.85 RG\n";
    streamContent += "1 w\n";
    streamContent += "40 550 515 150 re\n";
    streamContent += "B\n"; // Fill and Stroke
    
    // Status Badge Box (Green)
    streamContent += "0.90 0.96 0.92 rg\n";
    streamContent += "0.15 0.65 0.30 RG\n";
    streamContent += "400 650 120 26 re\n";
    streamContent += "B\n";
    
    // Status Badge Text
    streamContent += "BT\n/F2 9 Tf\n0.15 0.65 0.30 rg\n418 659 Td\n(SIGNED & ACTIVE) Tj\nET\n";
    
    // Metadata Labels & Values
    streamContent += "BT\n/F2 10 Tf\n0.2 0.2 0.2 rg\n55 670 Td\n(Document:) Tj\nET\n";
    streamContent += `BT\n/F1 10 Tf\n0.3 0.3 0.3 rg\n130 670 Td\n(${title}) Tj\nET\n`;
    
    streamContent += "BT\n/F2 10 Tf\n0.2 0.2 0.2 rg\n55 640 Td\n(Project / Asset:) Tj\nET\n";
    streamContent += `BT\n/F1 10 Tf\n0.3 0.3 0.3 rg\n130 640 Td\n(${project}) Tj\nET\n`;
    
    streamContent += "BT\n/F2 10 Tf\n0.2 0.2 0.2 rg\n55 610 Td\n(Issue Date:) Tj\nET\n";
    streamContent += `BT\n/F1 10 Tf\n0.3 0.3 0.3 rg\n130 610 Td\n(${dateStr}) Tj\nET\n`;
    
    streamContent += "BT\n/F2 10 Tf\n0.2 0.2 0.2 rg\n55 580 Td\n(Security Ref:) Tj\nET\n";
    streamContent += `BT\n/F1 10 Tf\n0.3 0.3 0.3 rg\n130 580 Td\n(${refKey}) Tj\nET\n`;
    
    // Main Section Title
    streamContent += "BT\n/F2 12 Tf\n0.08 0.08 0.08 rg\n40 500 Td\n(AUTHENTICITY CERTIFICATE & COVENANTS) Tj\nET\n";
    
    // Main Body Text
    const bodyText = [
      "This document serves as verification of asset placement and regulatory compliance",
      "executed on the SEVEN TECH CAPITAL digital portal. All transactions, disclosures,",
      "and covenants associated with this asset have been cryptographically sealed and logged.",
      "",
      "The signatures below represent binding confirmation that the client is fully vetted",
      "under KYC laws and owns the respective holdings in the named venture.",
      "",
      "--------------------------------------------------------------------------------",
      "Security Notice: This document contains proprietary financial information and is",
      "subject to active Non-Disclosure Agreement (NDA) rules. Unauthorized copying is",
      "strictly prohibited under international securities legislation."
    ];
    
    let yPos = 470;
    for (let line of bodyText) {
      if (line === "") {
        yPos -= 10;
        continue;
      }
      streamContent += `BT\n/F1 10 Tf\n0.3 0.3 0.3 rg\n40 ${yPos} Td\n(${line}) Tj\nET\n`;
      yPos -= 15;
    }
    
    // Draw Signature Box Labels
    streamContent += "BT\n/F2 10 Tf\n0.2 0.2 0.2 rg\n60 280 Td\n(INVESTOR SIGNATURE) Tj\nET\n";
    streamContent += "BT\n/F3 12 Tf\n0.1 0.1 0.1 rg\n60 258 Td\n(Khalid Al-Dosari) Tj\nET\n";
    streamContent += "BT\n/F1 8 Tf\n0.15 0.65 0.30 rg\n60 242 Td\n(Digitally Signed - STC Vetted) Tj\nET\n";
    
    streamContent += "BT\n/F2 10 Tf\n0.2 0.2 0.2 rg\n340 280 Td\n(ISSUER SIGNATURE) Tj\nET\n";
    streamContent += "BT\n/F3 12 Tf\n0.1 0.1 0.1 rg\n340 258 Td\n(Seven Tech Operations) Tj\nET\n";
    streamContent += "BT\n/F1 8 Tf\n0.15 0.65 0.30 rg\n340 242 Td\n(Digitally Signed - STC Certified) Tj\nET\n";
    
    // Draw lines under signatures
    streamContent += "0.7 0.7 0.7 RG\n";
    streamContent += "0.5 w\n";
    streamContent += "60 232 m\n220 232 l\nS\n";
    streamContent += "340 232 m\n500 232 l\nS\n";
    
    // Draw footer thin divider
    streamContent += "40 55 m\n555 55 l\nS\n";
    
    // Footer text
    streamContent += "BT\n/F1 8 Tf\n0.5 0.5 0.5 rg\n40 40 Td\n(Page 1 of 1  |  Confidential & Proprietary) Tj\nET\n";
    streamContent += "BT\n/F1 8 Tf\n0.5 0.5 0.5 rg\n355 40 Td\n(All rights reserved (c) 2026 SEVEN TECH CAPITAL) Tj\nET\n";

    const catalog = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj";
    const pages = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj";
    const page = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R /F2 6 0 R /F3 7 0 R >> >> /Contents 5 0 R >>\nendobj";
    const fontRegular = "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj";
    const fontBold = "6 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>\nendobj";
    const fontOblique = "7 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Oblique >>\nendobj";
    
    const streamLength = streamContent.length;
    const contentStream = `5 0 obj\n<< /Length ${streamLength} >>\nstream\n${streamContent}\nendstream\nendobj`;

    const objects = [catalog, pages, page, fontRegular, contentStream, fontBold, fontOblique];
    let pdf = "%PDF-1.4\n";
    const offsets = [];
    
    for (let i = 0; i < objects.length; i++) {
      offsets.push(pdf.length);
      pdf += objects[i] + "\n";
    }
    
    const xrefOffset = pdf.length;
    pdf += "xref\n0 8\n";
    pdf += "0000000000 65535 f \n";
    for (let i = 0; i < offsets.length; i++) {
      const paddedOffset = ("0000000000" + offsets[i]).slice(-10);
      pdf += `${paddedOffset} 00000 n \n`;
    }
    
    pdf += `trailer\n<< /Size 8 /Root 1 0 R >>\nstartxref\n${xrefOffset}\n%%EOF`;
    
    const charList = pdf.split('');
    const uintArray = new Uint8Array(charList.length);
    for (let i = 0; i < charList.length; i++) {
      uintArray[i] = charList[i].charCodeAt(0);
    }
    
    return new Blob([uintArray], { type: 'application/pdf' });
  }

  // Download simulation
  function downloadDoc(title, project, type) {
    const isAr = "{{ app()->getLocale() == 'ar' }}";
    const overlay = document.getElementById('download-progress-overlay');
    const progressTitle = document.getElementById('download-progress-title');
    
    progressTitle.textContent = isAr ? 'جاري تحضير المستند...' : 'Preparing document...';
    overlay.style.display = 'flex';
    setTimeout(() => overlay.classList.add('show'), 10);

    setTimeout(() => {
      progressTitle.textContent = isAr ? 'جاري التنزيل الآن...' : 'Downloading file...';
      
      setTimeout(() => {
        // Hide overlay
        overlay.classList.remove('show');
        setTimeout(() => overlay.style.display = 'none', 250);

        const blob = generateDocumentPdfBlob(title, project, type);
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${title.replace(/[\s—|]+/g, '_')}.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(link.href);

        showToast(isAr ? 'تم تحميل المستند بنجاح.' : 'Document downloaded successfully.');
      }, 1000);
    }, 1200);
  }

  // Type Filter selection
  function filterType(type, element) {
    currentTypeFilter = type;

    // Toggle active state for chips
    document.querySelectorAll('#docs-filter-chips .chip-premium').forEach(chip => {
      chip.classList.remove('active');
    });
    element.classList.add('active');

    filterDocs();
  }

  // Search and filter documents locally
  function filterDocs() {
    const searchVal = document.getElementById('doc-search').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.doc-row');

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

    const emptyState = document.getElementById('empty-state');
    const tableWrapper = document.getElementById('docs-table-wrapper');

    if (visibleCount === 0) {
      emptyState.style.display = 'flex';
      tableWrapper.style.display = 'none';
    } else {
      emptyState.style.display = 'none';
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
        ? `تم إرسال طلب ${action === 'edit' ? 'التعديل' : 'الحذف'} للمستند "${activeRequestItemName}" بنجاح إلى المسؤول.`
        : `Your request to ${action} document "${activeRequestItemName}" has been successfully sent to the admin.`;

      // Trigger toast (use global showToast from document.blade.php)
      showToast(isAr ? 'تم إرسال الطلب للمسؤول بنجاح' : 'Request submitted successfully');
      renderDocumentsRequests();
    }, 1200);
  }

  function renderDocumentsRequests() {
    const requests = JSON.parse(localStorage.getItem('stc_asset_requests')) || [];
    const docRequests = requests.filter(r => r.item_type === 'document');
    const tbody = document.getElementById('submitted-requests-tbody');
    const isAr = "{{ app()->getLocale() == 'ar' }}";

    if (docRequests.length === 0) {
      document.getElementById('submitted-requests-section').style.display = 'none';
      return;
    }

    document.getElementById('submitted-requests-section').style.display = 'block';
    tbody.innerHTML = '';

    docRequests.forEach(r => {
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
    renderDocumentsRequests();
  });
</script>
@endsection