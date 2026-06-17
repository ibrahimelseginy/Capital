@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'تفاصيل المشروع' : 'Project Details')

@section('content')
<style>
  /* Premium Modal CSS */
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

  .modal-overlay.active .modal-content-premium {
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

  .invest-input-wrapper {
    position: relative;
    margin: var(--space-4) 0;
  }

  .invest-input {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    padding-left: var(--space-8);
    border-radius: var(--radius-lg);
    border: 2px solid var(--border-default);
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 1.25rem;
    font-weight: var(--weight-bold);
    transition: all 0.2s ease;
  }
  
  [dir="rtl"] .invest-input {
    padding-left: var(--space-4);
    padding-right: var(--space-8);
  }

  .invest-input:focus {
    border-color: var(--color-primary);
    background: var(--bg-surface);
    outline: none;
    box-shadow: var(--shadow-focus);
  }

  .currency-indicator {
    position: absolute;
    left: var(--space-4);
    top: 50%;
    transform: translateY(-50%);
    font-weight: var(--weight-bold);
    color: var(--text-secondary);
    pointer-events: none;
  }
  
  [dir="rtl"] .currency-indicator {
    left: auto;
    right: var(--space-4);
  }

  /* Confetti Success Effect */
  @keyframes successCheck {
    0% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
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
    animation: successCheck 0.5s var(--ease-bounce) forwards;
  }

  /* Prospectus Table */
  .prospectus-table {
    width: 100%;
    border-collapse: collapse;
    margin: var(--space-4) 0;
  }

  .prospectus-table td {
    padding: var(--space-2) var(--space-3);
    border-bottom: 1px solid var(--border-subtle);
    font-size: var(--text-body-sm);
  }

  .prospectus-table tr:last-child td {
    border-bottom: none;
  }

  .prospectus-table td.label-td {
    color: var(--text-secondary);
    font-weight: var(--weight-medium);
  }

  .prospectus-table td.value-td {
    text-align: end;
    font-weight: var(--weight-bold);
    color: var(--text-primary);
  }
  
  [dir="rtl"] .prospectus-table td.value-td {
    text-align: start;
  }
  [dir="rtl"] .prospectus-table td.label-td {
    text-align: end;
  }

  /* Global Toast Alert */
  .toast-container {
    position: fixed;
    top: var(--space-6);
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

  /* Button Spinner */
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

<!-- Top Action Bar -->
<div class="d-flex justify-between items-center mb-6">
  <div class="d-flex items-center gap-4">
    <a href="{{ url('/dashboard/projects') }}" class="btn btn-ghost" style="width:40px;height:40px;padding:0;border-radius:50%;display:flex;align-items:center;justify-content:center;background:var(--bg-secondary)">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <h2 class="text-h4" style="font-weight:var(--weight-bold)">{{ $project->title ?? 'Project' }} — {{ app()->getLocale() == 'ar' ? 'تفاصيل المشروع' : 'Project Details' }}</h2>
  </div>
  <div class="d-flex gap-3">
    <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg); color:var(--text-secondary)" onclick="openRequestModal('{{ $project->id }}', '{{ $project->title }}', 'project', 'edit')">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-inline-end:6px"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
      {{ app()->getLocale() == 'ar' ? 'طلب تعديل' : 'Request Edit' }}
    </button>
    <button class="btn btn-secondary btn-sm" style="border-radius:var(--radius-lg)" onclick="openProspectusModal()">{{ app()->getLocale() == 'ar' ? 'تحميل النشرة' : 'Download Prospectus' }}</button>
    <button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)" onclick="openInvestModal()">{{ app()->getLocale() == 'ar' ? 'استثمر الآن' : 'Invest Now' }}</button>
  </div>
</div>

<!-- Header & Hero -->
<div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl);display:flex;gap:var(--space-8);align-items:center;flex-wrap:wrap">
  <div style="width:160px;height:160px;border-radius:var(--radius-xl);background:url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=400') center/cover;box-shadow:0 8px 24px rgba(0,0,0,0.1)"></div>
  <div class="flex-1" style="min-width:300px">
    <div class="d-flex gap-3 mb-2 items-center">
      <span class="badge badge-success badge-dot">{{ app()->getLocale() == 'ar' ? 'نشط وينمو' : 'Active & Growing' }}</span>
      <span class="badge badge-neutral">{{ app()->getLocale() == 'ar' ? 'قطاع التقنية المالية' : 'FinTech Sector' }}</span>
    </div>
    <h1 class="text-h2 mb-1" style="font-weight:var(--weight-bold)">{{ $project->title ?? 'Project' }}</h1>
    <p class="text-h6 text-secondary mb-4">{{ app()->getLocale() == 'ar' ? 'المشروع الفرعي' : 'Sub-project' }}: {{ $project->title ?? 'Project' }} Pay Gateway</p>
    <div class="d-flex gap-6 flex-wrap">
      <div><div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'رأس المال' : 'Capital' }}</div><div class="text-label" style="font-weight:var(--weight-bold)">${{ number_format(($project->budget ?? 500000) * 10 / 1000000, 1) }}M</div></div>
      <div><div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'التمويل المطلوب' : 'Funding Ask' }}</div><div class="text-label" style="font-weight:var(--weight-bold)">${{ number_format(($project->budget ?? 500000) * 2 / 1000000, 1) }}M</div></div>
      <div><div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'عدد الأسهم' : 'Total Shares' }}</div><div class="text-label" style="font-weight:var(--weight-bold)">1,000,000</div></div>
      <div><div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'عدد المساهمين' : 'Shareholders' }}</div><div class="text-label" style="font-weight:var(--weight-bold)">24</div></div>
    </div>
  </div>
</div>

<!-- Main Content Grid -->
<div class="grid-12" style="gap:var(--space-6)">
  <!-- Left Column -->
  <div style="grid-column:span 8">
    
    <!-- Growth Rates Chart -->
    <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
      <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'معدلات النمو' : 'Growth Rates' }}</h3>
      <div style="height:200px;background:linear-gradient(to bottom, rgba(59,130,246,0.1), transparent);border-radius:var(--radius-lg);position:relative">
        <svg viewBox="0 0 400 100" style="width:100%;height:100%;padding:10px" preserveAspectRatio="none">
          <polyline fill="none" stroke="#3b82f6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" points="0,90 80,70 160,80 240,40 320,45 400,10"/>
        </svg>
      </div>
      <div class="d-flex justify-between mt-3 text-caption text-secondary">
        <span>Q1 2025</span><span>Q2 2025</span><span>Q3 2025</span><span>Q4 2025</span><span>Q1 2026</span><span>Q2 2026</span>
      </div>
    </div>

    <!-- Project Portfolio (Subsidiaries/Products) -->
    <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
      <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'المحفظة الخاصة بالمشروع (المنتجات)' : 'Project Portfolio (Products)' }}</h3>
      <div class="grid-2" style="gap:var(--space-4)">
        <div style="padding:var(--space-4);border:1px solid var(--border-default);border-radius:var(--radius-lg)">
          <h4 class="text-label" style="font-weight:var(--weight-bold)">{{ $project->title ?? 'Project' }} B2B API</h4>
          <p class="text-caption text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'بوابة دفع مخصصة للشركات' : 'Enterprise payment integration' }}</p>
        </div>
        <div style="padding:var(--space-4);border:1px solid var(--border-default);border-radius:var(--radius-lg)">
          <h4 class="text-label" style="font-weight:var(--weight-bold)">{{ $project->title ?? 'Project' }} Wallet App</h4>
          <p class="text-caption text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'محفظة رقمية للمستهلكين' : 'Consumer digital wallet' }}</p>
        </div>
      </div>
    </div>

    <!-- Reports -->
    <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl)">
      <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'التقارير المالية والتدقيق' : 'Financial & Audit Reports' }}</h3>
      <div class="d-flex flex-col gap-3">
        <div class="d-flex justify-between items-center" style="padding:var(--space-4);background:var(--bg-secondary);border-radius:var(--radius-md)">
          <div class="d-flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="var(--action-primary)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span class="text-body-sm" style="font-weight:var(--weight-medium)">{{ app()->getLocale() == 'ar' ? 'البيان المالي للربع الأول 2026' : 'Q1 2026 Financial Statement' }}</span>
          </div>
          <button class="btn btn-ghost btn-sm" onclick="openReportMock('{{ $project->title ?? '' }} Q1 2026')">{{ app()->getLocale() == 'ar' ? 'تحميل' : 'Download' }}</button>
        </div>
        <div class="d-flex justify-between items-center" style="padding:var(--space-4);background:var(--bg-secondary);border-radius:var(--radius-md)">
          <div class="d-flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="var(--action-primary)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span class="text-body-sm" style="font-weight:var(--weight-medium)">{{ app()->getLocale() == 'ar' ? 'التدقيق السنوي لعام 2025' : 'Annual Audit 2025' }}</span>
          </div>
          <button class="btn btn-ghost btn-sm" onclick="openReportMock('{{ $project->title ?? '' }} Annual Audit 2025')">{{ app()->getLocale() == 'ar' ? 'تحميل' : 'Download' }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column -->
  <div style="grid-column:span 4">
    <!-- Key Personnel -->
    <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
      <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'الإدارة الرئيسية' : 'Key Personnel' }}</h3>
      <div class="d-flex flex-col gap-4">
        <div class="d-flex items-center gap-3">
          <div style="width:40px;height:40px;border-radius:50%;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;font-size:20px">👨‍💼</div>
          <div><div class="text-body-sm" style="font-weight:var(--weight-bold)">Ahmad Nasser</div><div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'الرئيس التنفيذي (CEO)' : 'CEO' }}</div></div>
        </div>
        <div class="d-flex items-center gap-3">
          <div style="width:40px;height:40px;border-radius:50%;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;font-size:20px">👨‍💻</div>
          <div><div class="text-body-sm" style="font-weight:var(--weight-bold)">Faisal Omar</div><div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'مدير المشروع' : 'Project Manager' }}</div></div>
        </div>
        <div class="d-flex items-center gap-3">
          <div style="width:40px;height:40px;border-radius:50%;background:var(--color-primary-lighter);color:var(--action-primary);display:flex;align-items:center;justify-content:center;font-size:20px">📞</div>
          <div><div class="text-body-sm" style="font-weight:var(--weight-bold)">Fahad Al-Saud</div><div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'مدير الحساب الخاص بك' : 'Account Manager' }}</div></div>
        </div>
      </div>
    </div>

    <!-- Consultants -->
    <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
      <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'استشاريو المشروع' : 'Project Consultants' }}</h3>
      <div class="d-flex flex-col gap-3">
        <div class="d-flex justify-between items-center"><span class="text-body-sm">McKinsey & Co.</span><span class="badge badge-neutral">{{ app()->getLocale() == 'ar' ? 'استراتيجية' : 'Strategy' }}</span></div>
        <div class="d-flex justify-between items-center"><span class="text-body-sm">PwC</span><span class="badge badge-neutral">{{ app()->getLocale() == 'ar' ? 'مالي' : 'Financial' }}</span></div>
      </div>
    </div>

    <!-- Exit Requests -->
    <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl);background:linear-gradient(135deg, var(--bg-surface) 0%, rgba(255,90,0,0.05) 100%)">
      <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'طلبات التخارج' : 'Exit Requests' }}</h3>
      <p class="text-body-sm text-secondary mb-4">{{ app()->getLocale() == 'ar' ? 'المستثمرون الذين يطلبون التخارج أو بيع أسهمهم في هذا المشروع.' : 'Investors looking to exit or sell shares in this project.' }}</p>
      <div class="d-flex justify-between items-center p-3" style="background:var(--bg-surface);border-radius:var(--radius-md);border:1px solid var(--border-default)">
        <span class="text-caption" style="font-weight:var(--weight-medium)">2 {{ app()->getLocale() == 'ar' ? 'طلبات معلقة' : 'Pending Requests' }}</span>
        <button class="btn btn-ghost btn-sm" style="color:var(--action-primary)" onclick="window.location.href='{{ url('/dashboard/exit-requests') }}'">{{ app()->getLocale() == 'ar' ? 'عرض العروض' : 'View Offers' }}</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal 1: Investment Simulator -->
<div class="modal-overlay" id="invest-modal">
  <div class="modal-content-premium">
    <button class="modal-close-btn" onclick="closeInvestModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    
    <!-- State 1: Form -->
    <div id="invest-form-state">
      <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold)">
        {{ app()->getLocale() == 'ar' ? 'الاستثمار في المشروع' : 'Invest in Project' }}
      </h3>
      <p class="text-secondary text-body-sm mb-4">
        {{ app()->getLocale() == 'ar' ? 'أدخل قيمة المبلغ الذي ترغب في استثماره في مشروع ' : 'Specify the amount you wish to allocate for ' }} <strong>{{ $project->title }}</strong>.
      </p>

      <!-- Validation Warning Banner -->
      <div id="invest-error-banner" style="display:none; background:rgba(231,76,60,0.08); border:1px solid rgba(231,76,60,0.2); border-radius:var(--radius-lg); padding:var(--space-3) var(--space-4); margin-bottom:var(--space-4); color:var(--color-error); font-size:12px; font-weight:var(--weight-semibold)">
      </div>

      <div class="invest-input-wrapper">
        <input type="number" id="invest-amount-input" class="invest-input" value="10000" min="5000" step="1000" oninput="calculateShares(this.value)">
        <span class="currency-indicator">$</span>
      </div>

      <div style="background:var(--bg-secondary); border-radius:var(--radius-lg); padding:var(--space-4); margin-bottom:var(--space-4)">
        <div style="display:flex; justify-between; font-size:var(--text-body-sm); margin-bottom:var(--space-2)">
          <span class="text-secondary">{{ app()->getLocale() == 'ar' ? 'سعر السهم المقدر' : 'Est. Share Price' }}</span>
          <span style="font-weight:var(--weight-bold); color:var(--text-primary)">$10.00</span>
        </div>
        <div style="display:flex; justify-between; font-size:var(--text-body-sm); margin-bottom:var(--space-2)">
          <span class="text-secondary">{{ app()->getLocale() == 'ar' ? 'الأسهم المستحقة المقدرة' : 'Estimated Allocated Shares' }}</span>
          <span style="font-weight:var(--weight-bold); color:var(--color-primary)" id="allocated-shares-txt">1,000</span>
        </div>
        <div style="display:flex; justify-between; font-size:var(--text-body-sm)">
          <span class="text-secondary">{{ app()->getLocale() == 'ar' ? 'العائد المتوقع سنوياً' : 'Target Dividend Yield' }}</span>
          <span style="font-weight:var(--weight-bold); color:var(--color-success)">12% - 15%</span>
        </div>
      </div>

      <div class="mb-4">
        <label class="text-caption text-secondary" style="display:flex; gap:8px; align-items:flex-start; cursor:pointer">
          <input type="checkbox" id="terms-check" style="margin-top:3px" checked>
          <span>{{ app()->getLocale() == 'ar' ? 'أوافق على الشروط والأحكام ووثيقة الإفصاح عن المخاطر الخاصة بنشرة الإصدار.' : 'I agree to the terms, conditions, and risk disclosure statement associated with this prospectus.' }}</span>
        </label>
      </div>

      <div style="display:flex; gap:12px; margin-top:var(--space-4)">
        <button class="btn btn-ghost flex-1" style="border-radius:var(--radius-lg)" onclick="closeInvestModal()">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
        <button class="btn btn-primary flex-1" style="border-radius:var(--radius-lg)" id="confirm-invest-btn" onclick="submitInvestment()">{{ app()->getLocale() == 'ar' ? 'تأكيد الاستثمار' : 'Confirm Investment' }}</button>
      </div>
    </div>

    <!-- State 2: Processing (Hidden) -->
    <div id="invest-loading-state" style="display:none; text-align:center; padding:var(--space-8) 0">
      <div style="width:48px; height:48px; border:3px solid var(--border-default); border-top-color:var(--color-primary); border-radius:50%; animation:spin 1s linear infinite; margin:0 auto var(--space-4) auto"></div>
      <h4 class="text-h5" style="font-weight:var(--weight-semibold)" id="loading-txt">
        {{ app()->getLocale() == 'ar' ? 'جاري معالجة الاستثمار...' : 'Processing investment...' }}
      </h4>
      <p class="text-secondary text-caption mt-1">{{ app()->getLocale() == 'ar' ? 'الرجاء عدم إغلاق هذه النافذة' : 'Please do not close this window' }}</p>
    </div>

    <!-- State 3: Success (Hidden) -->
    <div id="invest-success-state" style="display:none; text-align:center; padding:var(--space-6) 0">
      <div class="success-checkmark">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h3 class="text-h4" style="font-weight:var(--weight-bold); color:var(--text-primary)">
        {{ app()->getLocale() == 'ar' ? 'تم الاستثمار بنجاح!' : 'Investment Successful!' }}
      </h3>
      <p class="text-secondary text-body-sm mt-2 mb-6" id="success-desc-txt">
        {{ app()->getLocale() == 'ar' ? 'لقد قمت بنجاح باستثمار 10,000$ في مشروع ' : 'You have successfully allocated $10,000 to ' }} <strong>{{ $project->title }}</strong>.
      </p>

      <button class="btn btn-primary" style="border-radius:var(--radius-lg); width:100%" onclick="closeInvestModal()">
        {{ app()->getLocale() == 'ar' ? 'العودة للمشروع' : 'Return to Project' }}
      </button>
    </div>
  </div>
</div>

<!-- Modal 2: Prospectus Viewer -->
<div class="modal-overlay" id="prospectus-modal">
  <div class="modal-content-premium" style="max-width:580px">
    <button class="modal-close-btn" onclick="closeProspectusModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    
    <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold)">
      {{ app()->getLocale() == 'ar' ? 'نشرة إصدار المشروع' : 'Project Prospectus' }}
    </h3>
    <p class="text-secondary text-body-sm mb-4">
      {{ app()->getLocale() == 'ar' ? 'ملخص نشرة الإصدار والشروط الأساسية للاستثمار في مشروع ' : 'Investment prospectus summary and parameters for ' }} <strong>{{ $project->title }}</strong>.
    </p>

    <table class="prospectus-table">
      <tr>
        <td class="label-td">{{ app()->getLocale() == 'ar' ? 'الشركة المصدرة' : 'Issuing entity' }}</td>
        <td class="value-td">{{ $project->title }} Capital Ltd.</td>
      </tr>
      <tr>
        <td class="label-td">{{ app()->getLocale() == 'ar' ? 'التقييم المستهدف' : 'Target Valuation' }}</td>
        <td class="value-td">${{ number_format(($project->budget ?? 500000) * 10) }}</td>
      </tr>
      <tr>
        <td class="label-td">{{ app()->getLocale() == 'ar' ? 'سعر السهم الاكتتابي' : 'Offering Share Price' }}</td>
        <td class="value-td">$10.00</td>
      </tr>
      <tr>
        <td class="label-td">{{ app()->getLocale() == 'ar' ? 'الحد الأدنى للاستثمار' : 'Minimum Investment' }}</td>
        <td class="value-td">$5,000</td>
      </tr>
      <tr>
        <td class="label-td">{{ app()->getLocale() == 'ar' ? 'فترة الحظر (Lock-up)' : 'Lock-up Period' }}</td>
        <td class="value-td">12 {{ app()->getLocale() == 'ar' ? 'شهراً' : 'months' }}</td>
      </tr>
      <tr>
        <td class="label-td">{{ app()->getLocale() == 'ar' ? 'توزيعات الأرباح المستهدفة' : 'Target dividend' }}</td>
        <td class="value-td">12% - 15% {{ app()->getLocale() == 'ar' ? 'سنوياً' : 'p.a.' }}</td>
      </tr>
    </table>

    <div style="display:flex; gap:12px; margin-top:var(--space-6)">
      <button class="btn btn-ghost flex-1" style="border-radius:var(--radius-lg)" onclick="closeProspectusModal()">{{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}</button>
      <button class="btn btn-primary flex-1" style="border-radius:var(--radius-lg); display:inline-flex; align-items:center; justify-content:center; gap:8px" onclick="triggerProspectusDownload()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
        <span>{{ app()->getLocale() == 'ar' ? 'تحميل كملف PDF' : 'Download PDF' }}</span>
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

  // Handle Prospectus Modal
  function openProspectusModal() {
    document.getElementById('prospectus-modal').classList.add('active');
  }

  function closeProspectusModal() {
    document.getElementById('prospectus-modal').classList.remove('active');
  }

  // --- Toast Notification Manager ---
  function showToast(message, type = 'success') {
    const container = document.getElementById('project-toast-container');
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

  function triggerProspectusDownload() {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    showToast(isAr ? 'جاري إعداد وتحميل النشرة الاستثمارية الكاملة...' : 'Preparing prospectus download...');
    closeProspectusModal();
    
    setTimeout(() => {
      const textContent = `==================================================\n` +
                          `             INVESTMENT PROSPECTUS DOCUMENT       \n` +
                          `==================================================\n` +
                          `PROJECT:     {{ $project->title }}\n` +
                          `TARGET:      $${(500000).toLocaleString()}\n` +
                          `OFFERING:    10.00 USD / Share\n` +
                          `DATE:        2026-06-15\n` +
                          `--------------------------------------------------\n` +
                          `Official Prospectus details filed for audit.\n` +
                          `==================================================\n`;
                          
      const blob = new Blob([textContent], { type: "text/plain;charset=utf-8" });
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = `Prospectus_{{ str_replace(' ', '_', $project->title) }}.txt`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      
      showToast(isAr ? 'تم تحميل النشرة الاستثمارية بنجاح!' : 'Prospectus downloaded successfully!');
    }, 1200);
  }

  // Handle Investment Modal
  function openInvestModal() {
    // Reset modal states
    document.getElementById('invest-form-state').style.display = 'block';
    document.getElementById('invest-loading-state').style.display = 'none';
    document.getElementById('invest-success-state').style.display = 'none';
    
    // Hide error banner
    const errBanner = document.getElementById('invest-error-banner');
    if (errBanner) {
      errBanner.style.display = 'none';
      errBanner.innerText = '';
    }
    
    // Set default values
    document.getElementById('invest-amount-input').value = 10000;
    calculateShares(10000);
    document.getElementById('terms-check').checked = true;

    document.getElementById('invest-modal').classList.add('active');
  }

  function closeInvestModal() {
    document.getElementById('invest-modal').classList.remove('active');
  }

  function calculateShares(amount) {
    const shares = Math.floor(amount / 10);
    document.getElementById('allocated-shares-txt').innerText = shares.toLocaleString();
  }

  function submitInvestment() {
    const amount = document.getElementById('invest-amount-input').value;
    const isChecked = document.getElementById('terms-check').checked;
    const errBanner = document.getElementById('invest-error-banner');

    if (errBanner) {
      errBanner.style.display = 'none';
      errBanner.innerText = '';
    }

    if (!amount || amount < 5000) {
      const errMsg = "{{ app()->getLocale() == 'ar' ? 'الحد الأدنى للاستثمار هو $5,000' : 'Minimum investment is $5,000' }}";
      if (errBanner) {
        errBanner.innerText = errMsg;
        errBanner.style.display = 'block';
      } else {
        showToast(errMsg, 'error');
      }
      return;
    }

    if (!isChecked) {
      const errMsg = "{{ app()->getLocale() == 'ar' ? 'يرجى الموافقة على الشروط والأحكام أولاً' : 'Please agree to the terms and conditions first' }}";
      if (errBanner) {
        errBanner.innerText = errMsg;
        errBanner.style.display = 'block';
      } else {
        showToast(errMsg, 'error');
      }
      return;
    }

    // Toggle Loading State
    document.getElementById('invest-form-state').style.display = 'none';
    document.getElementById('invest-loading-state').style.display = 'block';

    setTimeout(() => {
      // Toggle Success State
      document.getElementById('invest-loading-state').style.display = 'none';
      document.getElementById('invest-success-state').style.display = 'block';

      // Update success message text
      const currencyVal = parseFloat(amount).toLocaleString();
      const projectTitle = "{{ $project->title }}";
      
      const successMsgAr = `لقد قمت بنجاح باستثمار $${currencyVal} في مشروع <strong>${projectTitle}</strong>. تم حجز حصتك من الأسهم بنجاح.`;
      const successMsgEn = `You have successfully allocated $${currencyVal} to <strong>${projectTitle}</strong>. Your shares have been allocated.`;

      document.getElementById('success-desc-txt').innerHTML = "{{ app()->getLocale() == 'ar' }}" === "1" || "{{ app()->getLocale() == 'ar' }}" === "true" ? successMsgAr : successMsgEn;
    }, 1800);
  }

  // Reports mockup downloader
  function openReportMock(title) {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    showToast(isAr ? 'جاري تحميل ملف التقرير...' : 'Downloading report file...');
    
    setTimeout(() => {
      const textContent = `==================================================\n` +
                          `                  PROJECT REPORT                  \n` +
                          `==================================================\n` +
                          `TITLE:       ${title}\n` +
                          `PROJECT:     {{ $project->title }}\n` +
                          `DATE:        2026-06-15\n` +
                          `==================================================\n`;
                          
      const blob = new Blob([textContent], { type: "text/plain;charset=utf-8" });
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = `Report_${title.replace(/\s+/g, "_")}.txt`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      
      showToast(isAr ? 'تم تحميل الملف بنجاح!' : 'File downloaded successfully!');
    }, 1000);
  }
</script>
<div class="toast-container" id="project-toast-container"></div>
@include('components.request-modal')
@endsection
