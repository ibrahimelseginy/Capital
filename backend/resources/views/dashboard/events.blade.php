@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'فعاليات المستثمرين' : 'Investor Events')

@section('content')
<style>
  /* Premium Events Styles */
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

  /* Event cards list */
  .events-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
  }

  .event-card-premium {
    background: var(--bg-surface);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-4);
    transition: all 0.3s ease;
  }

  .event-card-premium:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--border-strong);
  }

  .date-badge-container {
    min-width: 56px;
    text-align: center;
    padding: var(--space-2);
    background: var(--color-primary-lighter);
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-primary-light);
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
    .event-card-premium {
      flex-direction: column;
      align-items: stretch;
      gap: var(--space-4);
    }
    .event-card-premium .d-flex {
      justify-content: space-between;
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

  /* Modals */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: var(--bg-overlay);
    backdrop-filter: blur(8px);
    z-index: var(--z-modal);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
  }
  .modal-overlay.show {
    opacity: 1;
    pointer-events: auto;
  }
  .modal-box {
    background: var(--bg-surface);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-xl);
    width: 100%;
    max-width: 440px;
    padding: var(--space-6);
    box-shadow: var(--shadow-xl);
    transform: scale(0.95) translateY(10px);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  .modal-overlay.show .modal-box {
    transform: scale(1) translateY(0);
  }

  /* Ticket Card Design */
  .ticket-card {
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-surface) 100%);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-lg);
    padding: var(--space-5);
    position: relative;
    overflow: hidden;
    margin-top: var(--space-4);
    box-shadow: var(--shadow-sm);
  }
  .ticket-card-header {
    border-bottom: 2px dashed var(--border-subtle);
    padding-bottom: var(--space-4);
    margin-bottom: var(--space-4);
    position: relative;
  }
  .ticket-card-header::before, .ticket-card-header::after {
    content: '';
    position: absolute;
    bottom: -11px;
    width: 20px;
    height: 20px;
    background: var(--bg-surface);
    border-radius: 50%;
    border: 1px solid var(--border-default);
  }
  .ticket-card-header::before {
    left: -27px;
  }
  .ticket-card-header::after {
    right: -27px;
  }
  .ticket-card-body {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .btn-spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-inline-end: var(--space-2);
    vertical-align: middle;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
</style>

<div class="fade-in">
  <!-- Top Greeting & Intro -->
  <div class="mb-6">
    <h2 class="text-h3" style="font-weight:var(--weight-bold); letter-spacing:-0.5px">
      {{ app()->getLocale() == 'ar' ? 'فعاليات المستثمرين' : 'Investor Events' }}
    </h2>
    <p class="text-secondary mt-1">
      {{ app()->getLocale() == 'ar' ? 'انضم للإحاطات الربع سنوية، عروض الشركات الناشئة، والقمم الاستثمارية الحصرية.' : 'Join quarterly briefs, venture demo days, and exclusive investor summits.' }}
    </p>
  </div>

  @php
    $totalCount = count($events);
    $registeredCount = $events->where('status', 'Registered')->count();
    $comingCount = $events->where('status', 'Coming Soon')->count();
    $exclusiveCount = $events->where('access_type', 'Exclusive')->count();
  @endphp

  <!-- Stats Grid -->
  <div class="stats-grid">
    <!-- Stat 1 -->
    <div class="stat-card-premium">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'إجمالي الفعاليات' : 'Total Events' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $totalCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'فعاليات مجدولة وقادمة' : 'Scheduled briefings' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-primary-light); color:var(--color-primary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
      </div>
    </div>

    <!-- Stat 2 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-success)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'الفعاليات المسجلة' : 'Registered' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)" id="stat-registered-count">
          {{ $registeredCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'تم تأكيد حضورك بها' : 'Your seat is confirmed' }}
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
          {{ app()->getLocale() == 'ar' ? 'فعاليات حصرية' : 'Exclusive Access' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $exclusiveCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'مخصصة للمستثمرين فقط' : 'Limited to fund members' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-gold-light); color:var(--accent-gold)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
    </div>

    <!-- Stat 4 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-info)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'موقع الفعاليات' : 'Locations' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          2
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'الرياض وبث حي مباشر' : 'Riyadh & Online streams' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-info-bg); color:var(--color-info)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
      </div>
    </div>
  </div>

  <!-- Controls Bar -->
  <div class="controls-bar">
    <!-- Live Search -->
    <div class="search-wrapper">
      <input type="text" id="event-search" class="search-input-premium" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث عن فعالية...' : 'Search events...' }}" onkeyup="filterEvents()">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>

    <!-- Status Filters -->
    <div class="filter-chips-wrapper" id="event-filter-chips">
      <button class="chip-premium active" onclick="filterEventStatus('all', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</span>
        <span class="chip-count">{{ $totalCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterEventStatus('Registered', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'مسجل' : 'Registered' }}</span>
        <span class="chip-count">{{ $registeredCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterEventStatus('Coming Soon', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'قريباً' : 'Coming Soon' }}</span>
        <span class="chip-count">{{ $comingCount }}</span>
      </button>
    </div>
  </div>

  <!-- Empty State -->
  <div class="empty-state-wrapper" id="empty-state">
    <div class="empty-state-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>
    <h3 class="text-h5" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'لا توجد فعاليات مطابقة لبحثك' : 'No events found' }}</h3>
    <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'يرجى مراجعة كلمات البحث أو خيارات التصفية.' : 'Please try adjusting your search terms or filter selection.' }}</p>
  </div>

  <!-- Events List -->
  <div class="events-list" id="events-list">
    @foreach($events as $event)
      @php
        // Date parsing using Carbon
        $carbonDate = \Carbon\Carbon::parse($event->event_date);
        $dayNum = $carbonDate->format('d');
        
        // Month translation
        $monthsAr = [
            'Jan' => 'يناير', 'Feb' => 'فبراير', 'Mar' => 'مارس', 'Apr' => 'أبريل',
            'May' => 'مايو', 'Jun' => 'يونيو', 'Jul' => 'يوليو', 'Aug' => 'أغسطس',
            'Sep' => 'سبتمبر', 'Oct' => 'أكتوبر', 'Nov' => 'نوفمبر', 'Dec' => 'ديسمبر'
        ];
        $monthStr = app()->getLocale() == 'ar' ? ($monthsAr[$carbonDate->format('M')] ?? $carbonDate->format('M')) : $carbonDate->format('M');
        if ($event->title == 'Annual Investor Summit') {
            // Check if it's Nov 2026 without specific day
            $dayNum = '2026';
            $monthStr = app()->getLocale() == 'ar' ? 'نوفمبر' : 'Nov';
        }

        // Access Badge color mapping
        $accessStyle = '';
        $accessBadgeClass = 'badge-neutral';
        if ($event->access_type == 'Exclusive') {
            $accessBadgeClass = 'badge-gold';
            $accessStyle = 'color: var(--color-gold); border-color: rgba(198,161,91,0.2); background: rgba(198,161,91,0.06)';
        }

        // Status Badge color mapping
        $statusStyle = '';
        $statusBadgeClass = 'badge-success';
        if ($event->status == 'Registered') {
            $statusBadgeClass = 'badge-success';
            $statusStyle = 'color: var(--color-success); border-color: rgba(46,204,113,0.2); background: rgba(46,204,113,0.06)';
        } else {
            $statusBadgeClass = 'badge-warning';
            $statusStyle = 'color: var(--color-warning); border-color: rgba(241,196,15,0.2); background: rgba(241,196,15,0.06)';
        }
        
        // Title translation
        $translatedTitle = $event->title;
        if(app()->getLocale() == 'ar') {
            if($event->title == 'Investor Briefing: Q2 Update') $translatedTitle = 'إحاطة المستثمرين: تحديث الربع الثاني';
            elseif($event->title == 'Venture Demo Day 2026') $translatedTitle = 'يوم عروض المشاريع الريادية 2026';
            elseif($event->title == 'Annual Investor Summit') $translatedTitle = 'قمة المستثمرين السنوية';
        }

        // Location translation
        $translatedLoc = $event->location;
        if(app()->getLocale() == 'ar') {
            if($event->location == 'Online') $translatedLoc = 'بث مباشر عبر الإنترنت';
            elseif($event->location == 'Riyadh') $translatedLoc = 'الرياض، المملكة العربية السعودية';
        }
      @endphp
      <div class="event-card-premium card d-flex flex-col" data-status="{{ $event->status }}" data-title="{{ $translatedTitle }}" data-loc="{{ $translatedLoc }}" id="event-card-{{ $event->id }}" style="align-items: stretch; cursor: pointer;" onclick="const details = this.querySelector('.event-details'); const btn = this.querySelector('.details-btn span'); if(details.style.display==='none'){details.style.display='block';btn.textContent='{{ app()->getLocale() == 'ar' ? 'إخفاء' : 'Hide' }}';}else{details.style.display='none';btn.textContent='{{ app()->getLocale() == 'ar' ? 'التفاصيل' : 'Details' }}';}">
        
        <div class="d-flex justify-between items-center w-full" style="flex-wrap: wrap; gap: var(--space-4)">
          <!-- Left: Date Calendar Block & Info -->
          <div class="d-flex gap-4 items-center flex-1">
            <!-- Calendar Block -->
            <div class="date-badge-container">
              <div class="text-h5" style="color:var(--action-primary); font-weight:var(--weight-bold); line-height: 1.1">
                {{ $dayNum }}
              </div>
              <div class="text-caption text-secondary" style="font-weight: var(--weight-semibold); text-transform: uppercase; margin-top: 2px">
                {{ $monthStr }}
              </div>
            </div>
            
            <div>
              <h4 class="text-label" style="font-weight:var(--weight-bold); color:var(--text-primary); margin-bottom: 4px;">
                {{ $translatedTitle }}
              </h4>
              
              <div class="text-caption text-secondary mt-1 d-flex items-center gap-3 flex-wrap">
                @if($event->category)
                  <span class="badge badge-primary" style="padding: 2px 8px; font-size: 10px;">{{ app()->getLocale() == 'ar' ? ($event->category == 'Webinar' ? 'ندوة' : ($event->category == 'Demo Day' ? 'يوم عروض' : 'مؤتمر')) : $event->category }}</span>
                @endif
                <span class="d-flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  {{ $translatedLoc }}
                </span>
                @if($event->time)
                <span class="d-flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  {{ app()->getLocale() == 'ar' ? str_replace(['AM', 'PM', 'AST'], ['ص', 'م', 'بتوقيت السعودية'], $event->time) : $event->time }}
                </span>
                @endif
                @if($event->attendees_count > 0)
                <span class="d-flex items-center gap-1" style="color:var(--color-success)">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                  {{ $event->attendees_count }} {{ app()->getLocale() == 'ar' ? 'مشارك' : 'Attendees' }}
                </span>
                @endif
              </div>

              @if($event->description)
              <p class="text-body-sm text-secondary mt-3 mb-0" style="max-width: 550px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ app()->getLocale() == 'ar' ? ($event->title == 'Investor Briefing: Q2 Update' ? 'تحديث حصري لمستثمرينا حول أداء الربع الثاني وأبرز الإنجازات.' : ($event->title == 'Venture Demo Day 2026' ? 'شاهد أحدث مشاريعنا وهي تُعرض أمام نخبة مختارة من المستثمرين والشركاء.' : 'الحدث الأهم للمؤسسين والمستثمرين الرائدين لاستكشاف الابتكارات المستقبلية.')) : $event->description }}
              </p>
              @endif
            </div>
          </div>
          
          <!-- Right: Badges & Call to Actions -->
          <div class="d-flex gap-4 items-center justify-between">
            <span class="badge {{ $accessBadgeClass }}" style="border-radius:var(--radius-full); {{ $accessStyle }}">
              {{ app()->getLocale() == 'ar' ? ($event->access_type == 'Exclusive' ? 'حصري' : ($event->access_type == 'VIP Access' ? 'دخول VIP' : 'مفتوح')) : $event->access_type }}
            </span>

            <span class="badge {{ $statusBadgeClass }} @if($event->status == 'Registered') badge-pulse @endif event-status-badge" style="border-radius:var(--radius-full); {{ $statusStyle }}">
              {{ app()->getLocale() == 'ar' ? ($event->status == 'Registered' ? 'مسجّل' : 'قريباً') : $event->status }}
            </span>
            
            <div class="event-action-container d-flex items-center gap-2">
              <button class="btn btn-ghost btn-sm details-btn text-accent" style="border-radius:var(--radius-lg); padding: var(--space-2) var(--space-4);" onclick="event.stopPropagation(); this.closest('.card').click()">
                <span>{{ app()->getLocale() == 'ar' ? 'التفاصيل' : 'Details' }}</span>
              </button>
              
              <!-- More Actions Dropdown -->
              <div class="dropdown-actions-wrapper" style="position:relative">
                <button class="btn btn-ghost btn-sm btn-icon dropdown-trigger" style="border-radius:var(--radius-lg); height: 36px; width:36px; min-width:36px; padding:0; display:flex; align-items:center; justify-content:center; border:1px solid var(--border-default)" onclick="toggleDropdown(event)">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                </button>
                <div class="dropdown-menu-premium" style="display:none; position:absolute; top:100%; right:0; margin-top:8px; background:var(--bg-surface); border:1px solid var(--border-default); border-radius:var(--radius-lg); box-shadow:var(--shadow-lg); z-index:100; min-width:140px">
                  <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $event->id }}', '{{ $translatedTitle }}', 'event', 'edit')" style="display:block; padding:10px 16px; font-size:12px; color:var(--text-primary); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب تعديل' : 'Request Edit' }}</a>
                  <a href="javascript:void(0)" class="dropdown-item-premium" onclick="openRequestModal('{{ $event->id }}', '{{ $translatedTitle }}', 'event', 'delete')" style="display:block; padding:10px 16px; font-size:12px; color:var(--color-error); text-decoration:none">{{ app()->getLocale() == 'ar' ? 'طلب حذف' : 'Request Delete' }}</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Hidden Details Section -->
        <div class="event-details" style="display:none; padding-bottom:var(--space-6); margin-top:var(--space-4); border-top:1px dashed var(--border-default); animation:fadeIn 0.3s ease;">
          @php
             $speakers = json_decode($event->speakers, true) ?? [];
             $program = json_decode($event->program, true) ?? [];
          @endphp
          
          <div class="grid-2" style="gap:var(--space-6)">
            <!-- Speakers -->
            @if(count($speakers) > 0)
            <div>
              <h5 class="text-body mb-4" style="font-weight:var(--weight-bold); color:var(--text-primary)">{{ app()->getLocale() == 'ar' ? 'المتحدثون' : 'Speakers' }}</h5>
              <div class="d-flex flex-col gap-4">
                @foreach($speakers as $speaker)
                <div class="d-flex items-center gap-3 p-3" style="background:var(--bg-primary); border-radius:var(--radius-lg); border:1px solid var(--border-subtle)">
                  <div style="width:48px;height:48px;border-radius:50%;background:var(--bg-secondary);background-image:url('{{ $speaker['image'] }}');background-size:cover;border:2px solid var(--action-primary);flex-shrink:0;"></div>
                  <div>
                    <div class="text-body-sm" style="font-weight:var(--weight-semibold); color:var(--text-primary)">{{ app()->getLocale() == 'ar' ? $speaker['name_ar'] : $speaker['name'] }}</div>
                    <div class="text-caption text-secondary" style="margin-top:2px">{{ app()->getLocale() == 'ar' ? $speaker['role_ar'] : $speaker['role'] }}</div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endif

            <!-- Program -->
            @if(count($program) > 0)
            <div>
              <h5 class="text-body mb-4" style="font-weight:var(--weight-bold); color:var(--text-primary)">{{ app()->getLocale() == 'ar' ? 'برنامج الفعالية' : 'Event Program' }}</h5>
              <ul style="margin:0;padding:0;list-style:none;display:flex;flex-direction:column;gap:var(--space-4)">
                @foreach($program as $index => $item)
                <li class="d-flex gap-4 items-center">
                  <div class="text-secondary" style="font-size:13px;width:75px;text-align:{{ app()->getLocale() == 'ar' ? 'right' : 'right' }}">{{ app()->getLocale() == 'ar' ? str_replace(['AM','PM'], ['ص','م'], $item['time']) : $item['time'] }}</div>
                  <div style="position:relative; width:12px; height:12px; border-radius:50%; background:{{ $index == 0 ? 'var(--action-primary)' : 'var(--border-strong)' }}; box-shadow:{{ $index == 0 ? '0 0 0 3px rgba(255,90,0,0.15)' : 'none' }}">
                     @if($index < count($program) - 1)
                     <div style="position:absolute; top:12px; left:5px; width:2px; height:30px; background:var(--border-subtle)"></div>
                     @endif
                  </div>
                  <div class="text-body-sm" style="font-weight:var(--weight-medium); color:var(--text-primary)">{{ app()->getLocale() == 'ar' ? $item['title_ar'] : $item['title'] }}</div>
                </li>
                @endforeach
              </ul>
            </div>
            @endif
          </div>

          <!-- Entry Ticket / Generation Unit & QR -->
          @if($event->status == 'Registered')
          <div class="d-flex justify-between items-center mt-6" style="background:var(--bg-secondary);padding:var(--space-5);border-radius:var(--radius-xl);border:1px solid var(--border-default)">
            <div class="d-flex items-center gap-4">
              <div style="width:56px;height:56px;border-radius:var(--radius-lg);background:var(--bg-surface);display:flex;align-items:center;justify-content:center;color:var(--action-primary);box-shadow:var(--shadow-sm);border:1px dashed var(--color-primary-light)">
                <!-- QR Code SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><rect x="7" y="7" width="3" height="3"/><rect x="14" y="7" width="3" height="3"/><rect x="7" y="14" width="3" height="3"/><rect x="14" y="14" width="3" height="3"/></svg>
              </div>
              <div>
                <h4 class="text-body mb-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">{{ app()->getLocale() == 'ar' ? 'بطاقة الدعوة (Invitation Card & QR)' : 'Invitation Card & QR Code' }}</h4>
                <p class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'استخدم وحدة التوليد هذه لإصدار تذكرتك و QR الخاص بك' : 'Use this generation unit to issue your ticket and QR code' }}</p>
              </div>
            </div>
            <button class="btn btn-primary" style="border-radius:var(--radius-lg); font-weight:var(--weight-semibold); padding:var(--space-3) var(--space-6)" onclick="event.stopPropagation(); generateTicket(this, '{!! addslashes($translatedTitle) !!}', '{!! addslashes($translatedLoc) !!}')">
              {{ app()->getLocale() == 'ar' ? 'توليد البطاقة' : 'Generate Ticket' }}
            </button>
          </div>
          @endif

        </div>
      </div>
    @endforeach
</div>

<!-- Modal & Toast Container -->
<div class="modal-overlay" id="ticket-modal">
  <div class="modal-box">
    <div class="d-flex justify-between items-center mb-4" style="border-bottom:1px solid var(--border-default); padding-bottom:var(--space-2)">
      <h4 class="text-h6" id="ticket-modal-title" style="margin:0; font-weight:var(--weight-bold)">
        {{ app()->getLocale() == 'ar' ? 'تأكيد حجز التذكرة' : 'Ticket Booking Confirmed' }}
      </h4>
      <button type="button" style="background:transparent; border:none; cursor:pointer; color:var(--text-secondary)" onclick="closeTicketModal()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    
    <div class="text-center mb-4">
      <div style="width:48px; height:48px; border-radius:50%; background:var(--color-success-bg); color:var(--color-success); display:flex; align-items:center; justify-content:center; margin:0 auto var(--space-3)">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h5 class="text-body" style="font-weight:var(--weight-bold); margin:0 0 4px 0">
        {{ app()->getLocale() == 'ar' ? 'تم تأكيد حضورك بنجاح!' : 'Your Seat is Confirmed!' }}
      </h5>
      <p class="text-caption text-secondary" style="margin:0; line-height:1.4">
        {{ app()->getLocale() == 'ar' ? 'تم إصدار بطاقة الدخول للفعالية بنجاح. يمكنك إبرازها عند البوابة.' : 'Your entry ticket for this exclusive event has been generated.' }}
      </p>
    </div>

    <!-- Ticket Visual -->
    <div class="ticket-card">
      <div class="ticket-card-header">
        <div class="text-caption text-secondary" style="text-transform: uppercase; font-weight: var(--weight-bold); letter-spacing: 0.5px">
          {{ app()->getLocale() == 'ar' ? 'بطاقة دخول الفعالية' : 'EVENT PASS' }}
        </div>
        <h4 class="text-body" id="ticket-event-name" style="margin: 4px 0 0 0; font-weight: var(--weight-bold); color: var(--text-primary)">
          -
        </h4>
      </div>
      <div class="ticket-card-body">
        <div style="display:flex; flex-direction:column; gap: var(--space-2)">
          <div>
            <div class="text-caption text-secondary" style="font-size:10px">{{ app()->getLocale() == 'ar' ? 'المستثمر' : 'Attendee' }}</div>
            <div class="text-body-sm" style="font-weight: var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'خالد الدوسري' : 'Khalid Al-Dosari' }}</div>
          </div>
          <div>
            <div class="text-caption text-secondary" style="font-size:10px">{{ app()->getLocale() == 'ar' ? 'الموقع' : 'Location' }}</div>
            <div class="text-body-sm" id="ticket-event-loc" style="font-weight: var(--weight-semibold)">-</div>
          </div>
        </div>
        
        <!-- Barcode / QR Code SVG -->
        <div style="background: white; padding: var(--space-2); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-default)">
          <svg width="70" height="70" viewBox="0 0 100 100">
            <rect width="100" height="100" fill="white"/>
            <rect x="10" y="10" width="20" height="20" fill="black"/>
            <rect x="14" y="14" width="12" height="12" fill="white"/>
            <rect x="18" y="18" width="4" height="4" fill="black"/>
            
            <rect x="70" y="10" width="20" height="20" fill="black"/>
            <rect x="74" y="14" width="12" height="12" fill="white"/>
            <rect x="78" y="18" width="4" height="4" fill="black"/>

            <rect x="10" y="70" width="20" height="20" fill="black"/>
            <rect x="14" y="74" width="12" height="12" fill="white"/>
            <rect x="18" y="78" width="4" height="4" fill="black"/>

            <rect x="35" y="15" width="10" height="5" fill="black"/>
            <rect x="50" y="25" width="5" height="15" fill="black"/>
            <rect x="40" y="45" width="20" height="5" fill="black"/>
            <rect x="65" y="35" width="10" height="10" fill="black"/>

            <rect x="15" y="40" width="5" height="15" fill="black"/>
            <rect x="25" y="50" width="10" height="5" fill="black"/>
            
            <rect x="70" y="70" width="10" height="10" fill="black"/>
            <rect x="85" y="80" width="5" height="10" fill="black"/>
            <rect x="60" y="85" width="15" height="5" fill="black"/>
          </svg>
        </div>
      </div>
    </div>

    <div class="d-flex gap-3 mt-5">
      <button type="button" class="btn btn-secondary w-full" id="ticket-btn-download" style="border-radius:var(--radius-lg); font-size:12px; display:inline-flex; align-items:center; justify-content:center; gap:6px">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
        <span>{{ app()->getLocale() == 'ar' ? 'تحميل التذكرة' : 'Download Pass' }}</span>
      </button>
      <button type="button" class="btn btn-primary w-full" style="border-radius:var(--radius-lg); font-size:12px" onclick="closeTicketModal()">
        {{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}
      </button>
    </div>
  </div>
</div>

@include('components.request-modal')

<div class="toast-container" id="events-toast-container"></div>

<script>
  let currentEventStatusFilter = 'all';

  // --- Toast Notification Manager ---
  function showToast(message, type = 'success') {
    const container = document.getElementById('events-toast-container');
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

  // Add event to calendar simulator - exports real .ics file!
  function addToCalendar(title) {
    try {
      const startStr = new Date(Date.now() + 2 * 24 * 60 * 60 * 1000).toISOString().replace(/-|:|\.\d\d\d/g, ""); // 2 days later, standard format
      const endStr = new Date(Date.now() + 2 * 24 * 60 * 60 * 1000 + 2 * 60 * 60 * 1000).toISOString().replace(/-|:|\.\d\d\d/g, ""); // 2 hours duration
      const icsContent = 
        "BEGIN:VCALENDAR\n" +
        "VERSION:2.0\n" +
        "PRODID:-//Capital Investor Portal//NONSGML Event//EN\n" +
        "BEGIN:VEVENT\n" +
        "UID:" + Date.now() + "@capital.com\n" +
        "DTSTAMP:" + new Date().toISOString().replace(/-|:|\.\d\d\d/g, "") + "\n" +
        "DTSTART:" + startStr + "\n" +
        "DTEND:" + endStr + "\n" +
        "SUMMARY:" + title + "\n" +
        "DESCRIPTION:Investor Meeting - Capital Portal\n" +
        "END:VEVENT\n" +
        "END:VCALENDAR";

      const blob = new Blob([icsContent], { type: "text/calendar;charset=utf-8" });
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = `${title.replace(/\s+/g, "_")}.ics`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);

      showToast(
        "{{ app()->getLocale() == 'ar' ? 'تم تصدير وحفظ موعد الفعالية في تقويمك الخاص بنجاح' : 'Successfully exported and added to your calendar: ' }}" + title
      );
    } catch(err) {
      console.error(err);
      showToast("Error generating calendar file", "error");
    }
  }

  let activeDownloadingPassTitle = '';

  // Register for event simulator
  function registerForEvent(eventId, title) {
    const card = document.getElementById(`event-card-${eventId}`);
    if (!card) return;

    const actionContainer = card.querySelector('.event-action-container');
    
    // Set button loading state
    const button = actionContainer.querySelector('button');
    if (button) {
      button.disabled = true;
      button.innerHTML = `<span class="btn-spinner"></span><span>${"{{ app()->getLocale() == 'ar' }}" === "1" ? 'جاري الحجز...' : 'Booking...'}</span>`;
    }

    setTimeout(() => {
      // Dynamically update UI state to Registered!
      
      // Status Badge
      const badge = card.querySelector('.event-status-badge');
      badge.className = 'badge badge-success badge-pulse event-status-badge';
      badge.style.cssText = 'color: var(--color-success); border-color: rgba(46,204,113,0.2); background: rgba(46,204,113,0.06); border-radius: var(--radius-full)';
      badge.innerText = "{{ app()->getLocale() == 'ar' }}" === "1" ? 'مسجّل' : 'Registered';
      
      // Action Button
      actionContainer.innerHTML = `
        <button class="btn btn-secondary btn-sm" style="border-radius:var(--radius-lg); display:inline-flex; align-items:center; gap:6px" onclick="addToCalendar('${title}')">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
          <span>${"{{ app()->getLocale() == 'ar' }}" === "1" ? 'إضافة للتقويم' : 'Add to Calendar'}</span>
        </button>
      `;
      
      // Update data attribute
      card.setAttribute('data-status', 'Registered');

      // Update Registered Count in statistics
      const regCountEl = document.getElementById('stat-registered-count');
      if (regCountEl) {
        const currentCount = parseInt(regCountEl.innerText, 10);
        regCountEl.innerText = currentCount + 1;
      }

      // Open Ticket Modal
      const loc = card.getAttribute('data-loc');
      document.getElementById('ticket-event-name').innerText = title;
      document.getElementById('ticket-event-loc').innerText = loc;
      
      activeDownloadingPassTitle = title;

      const ticketModal = document.getElementById('ticket-modal');
      ticketModal.classList.add('show');

      showToast(
        "{{ app()->getLocale() == 'ar' ? 'تم حجز تذكرتك وتأكيد حضورك للفعالية بنجاح!' : 'Your ticket has been booked and attendance confirmed successfully!' }}"
      );
    }, 1200);
  }

  function closeTicketModal() {
    document.getElementById('ticket-modal').classList.remove('show');
  }

  function generateTicket(btn, title, loc) {
    btn.innerHTML = "{{ app()->getLocale() == 'ar' ? 'تم التوليد ✓' : 'Generated ✓' }}";
    btn.style.background = 'var(--color-success)';
    btn.style.borderColor = 'var(--color-success)';
    setTimeout(() => {
      showToast("{{ app()->getLocale() == 'ar' ? 'تم إنشاء بطاقة الدخول (QR) بنجاح!' : 'QR Entry ticket generated successfully!' }}");
      document.getElementById('ticket-event-name').innerText = title;
      document.getElementById('ticket-event-loc').innerText = loc;
      activeDownloadingPassTitle = title;
      document.getElementById('ticket-modal').classList.add('show');
    }, 300);
  }

  // Ticket download button event
  document.addEventListener('DOMContentLoaded', () => {
    const downloadBtn = document.getElementById('ticket-btn-download');
    if (downloadBtn) {
      downloadBtn.addEventListener('click', () => {
        const btnText = downloadBtn.querySelector('span');
        const originalText = btnText.innerText;
        downloadBtn.disabled = true;
        btnText.innerText = "{{ app()->getLocale() == 'ar' }}" === "1" ? 'جاري التحميل...' : 'Downloading...';
        
        setTimeout(() => {
          // Generate mock ticket download
          const textContent = `----------------------------------------------\n` +
                              `           CAPITAL EVENT ENTRY PASS           \n` +
                              `----------------------------------------------\n` +
                              `EVENT:     ${activeDownloadingPassTitle}\n` +
                              `ATTENDEE:  Khalid Al-Dosari\n` +
                              `GATE CODE: STC-EVT-99827361\n` +
                              `STATUS:    CONFIRMED\n` +
                              `----------------------------------------------\n` +
                              `Present this pass at the entrance gate.`;
                              
          const blob = new Blob([textContent], { type: "text/plain;charset=utf-8" });
          const link = document.createElement("a");
          link.href = URL.createObjectURL(blob);
          link.download = `Event_Pass_${activeDownloadingPassTitle.replace(/\s+/g, "_")}.txt`;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);

          downloadBtn.disabled = false;
          btnText.innerText = originalText;
          showToast("{{ app()->getLocale() == 'ar' ? 'تم تحميل تذكرة الدخول بنجاح.' : 'Entry pass downloaded successfully.' }}");
        }, 1000);
      });
    }
  });

  // Handle status filter chips click
  function filterEventStatus(status, btn) {
    currentEventStatusFilter = status;

    // Toggle active state for chips
    document.querySelectorAll('#event-filter-chips .chip-premium').forEach(chip => {
      chip.classList.remove('active');
    });
    btn.classList.add('active');

    filterEvents();
  }

  // Local live filtering
  function filterEvents() {
    const searchVal = document.getElementById('event-search').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.event-card-premium');

    let visibleCount = 0;

    cards.forEach(card => {
      const title = card.getAttribute('data-title').toLowerCase();
      const loc = card.getAttribute('data-loc').toLowerCase();
      const status = card.getAttribute('data-status');

      const matchesSearch = title.includes(searchVal) || loc.includes(searchVal);
      
      let matchesStatus = false;
      if (currentEventStatusFilter === 'all') {
        matchesStatus = true;
      } else {
        matchesStatus = (status === currentEventStatusFilter);
      }

      if (matchesSearch && matchesStatus) {
        card.style.display = 'flex';
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
</script>
@endsection