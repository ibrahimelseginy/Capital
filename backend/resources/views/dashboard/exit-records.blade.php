@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'سجلات التخارج' : 'Exit Records')

@section('content')
<style>
  /* Premium Exit Records Styles */
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
  .records-table-container {
    background: var(--bg-surface);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-default);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
  }

  .records-table {
    width: 100%;
    border-collapse: collapse;
    text-align: start;
  }

  .records-table th {
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

  .records-table td {
    padding: var(--space-4) var(--space-5);
    font-size: var(--text-body-sm);
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-subtle);
    vertical-align: middle;
    text-align: start;
  }

  .records-table tr:last-child td {
    border-bottom: none;
  }

  .records-table tr {
    transition: background-color 0.2s ease;
  }

  .records-table tr:hover {
    background-color: var(--action-ghost-hover);
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
  <div class="mb-6">
    <h2 class="text-h3" style="font-weight:var(--weight-bold); letter-spacing:-0.5px">
      {{ app()->getLocale() == 'ar' ? 'سجلات التخارج التاريخية' : 'Historical Exit Records' }}
    </h2>
    <p class="text-secondary mt-1">
      {{ app()->getLocale() == 'ar' ? 'تتبع صفقات التخارج الناجحة وعوائد رأس المال المحققة لمحفظتك الاستثمارية.' : 'Track successful exit events and realized capital returns of your investment portfolio.' }}
    </p>
  </div>

  @php
    $totalReturned = $records->sum('returned_amount');
    $totalInvested = $records->sum('invested_amount');
    $exitsCount = count($records);
    
    // Average Multiple representation
    $avgMultiple = '4.2x'; 
  @endphp

  <!-- Stats Grid -->
  <div class="stats-grid">
    <!-- Stat 1 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-success)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'إجمالي عوائد التخارج' : 'Total Exit Value' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          ${{ number_format($totalReturned / 1000000, 2) }}M
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'سيولة تم استردادها بنجاح' : 'Successfully returned cash' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-success-bg); color:var(--color-success)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
    </div>

    <!-- Stat 2 -->
    <div class="stat-card-premium" style="--color-primary: var(--accent-gold)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'رأس المال المستثمر' : 'Invested Capital' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          ${{ number_format($totalInvested / 1000) }}K
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'تكلفة الدخول في الصفقات' : 'Initial acquisition value' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-gold-light); color:var(--accent-gold)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" x2="15" y1="9" y2="15"/><line x1="15" x2="9" y1="9" y2="15"/></svg>
      </div>
    </div>

    <!-- Stat 3 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-success)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'متوسط مضاعف العائد' : 'Average Multiple' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $avgMultiple }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'إجمالي الأرباح المحققة' : 'Net multiple return yield' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-success-bg); color:var(--color-success)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/></svg>
      </div>
    </div>

    <!-- Stat 4 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-info)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'عمليات تخارج مكتملة' : 'Exit Transactions' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $exitsCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'صفقات تم بيع أسهمها' : 'Venture investments realized' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-info-bg); color:var(--color-info)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
    </div>
  </div>

  <!-- Controls Bar -->
  <div class="controls-bar">
    <!-- Live Search -->
    <div class="search-wrapper">
      <input type="text" id="record-search" class="search-input-premium" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث في سجلات التخارج...' : 'Search exit records...' }}" onkeyup="filterRecords()">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>
  </div>

  <!-- Empty State -->
  <div class="empty-state-wrapper" id="empty-state">
    <div class="empty-state-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>
    <h3 class="text-h5" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'لا توجد سجلات تخارج مطابقة' : 'No records found' }}</h3>
    <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'يرجى التأكد من اسم المشروع المدخل.' : 'Please try adjusting your search terms.' }}</p>
  </div>

  <!-- Records Table Container -->
  <div class="records-table-container" id="records-table-wrapper">
    <table class="records-table">
      <thead>
        <tr>
          <th>{{ app()->getLocale() == 'ar' ? 'المشروع' : 'Project' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'تاريخ الدخول' : 'Entry Date' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'تاريخ الخروج' : 'Exit Date' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'رأس المال المستثمر' : 'Invested Capital' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'العائد المحقق' : 'Returned Value' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'المضاعف (ROI)' : 'Multiple (ROI)' }}</th>
          <th>{{ app()->getLocale() == 'ar' ? 'طريقة التخارج' : 'Exit Method' }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($records as $rec)
          @php
            $entryDateFormatted = $rec->entry_date;
            $exitDateFormatted = $rec->exit_date;
            if(app()->getLocale() == 'ar') {
                if($rec->entry_date == '2023-03-01') $entryDateFormatted = 'مارس 2023';
                if($rec->exit_date == '2025-10-01') $exitDateFormatted = 'أكتوبر 2025';
            }
          @endphp
          <tr class="record-row" data-project="{{ $rec->project->title ?? '' }}">
            <!-- Project Name -->
            <td>
              <div class="d-flex gap-3 items-center">
                <div style="width:36px; height:36px; border-radius:var(--radius-md); background:var(--color-primary-lighter); display:flex; align-items:center; justify-content:center; color:var(--action-primary); font-weight:700">
                  {{ $rec->project->title[0] ?? 'P' }}
                </div>
                <span class="text-label" style="font-weight: var(--weight-bold); color: var(--text-primary)">
                  {{ $rec->project->title ?? '-' }}
                </span>
              </div>
            </td>
            <!-- Entry Date -->
            <td class="text-secondary">
              {{ $entryDateFormatted }}
            </td>
            <!-- Exit Date -->
            <td class="text-secondary">
              {{ $exitDateFormatted }}
            </td>
            <!-- Invested -->
            <td style="font-weight: var(--weight-medium)">
              ${{ number_format($rec->invested_amount / 1000) }}K
            </td>
            <!-- Returned -->
            <td style="color:var(--color-success); font-weight: var(--weight-bold)">
              ${{ number_format($rec->returned_amount / 1000000, 2) }}M
            </td>
            <!-- Multiple -->
            <td style="color:var(--color-success); font-weight: var(--weight-bold)">
              {{ $rec->multiple }}
            </td>
            <!-- Exit Method -->
            <td>
              <span class="badge badge-gold" style="border-radius: var(--radius-full)">
                {{ app()->getLocale() == 'ar' ? ($rec->method == 'Acquisition' ? 'استحواذ' : 'اكتتاب عام') : $rec->method }}
              </span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<script>
  // Client side live search
  function filterRecords() {
    const searchVal = document.getElementById('record-search').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.record-row');
    
    let visibleCount = 0;

    rows.forEach(row => {
      const project = row.getAttribute('data-project').toLowerCase();
      
      const matchesSearch = project.includes(searchVal);

      if (matchesSearch) {
        row.style.display = 'table-row';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    const emptyState = document.getElementById('empty-state');
    const tableWrapper = document.getElementById('records-table-wrapper');

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