@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'الاستشارات الاستثمارية' : 'Investment Consultations')

@section('content')
<style>
  /* Premium Consultations Styles */
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

  /* Consultation items list */
  .consultations-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
  }

  .consultation-card-premium {
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

  .consultation-card-premium:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--border-strong);
  }

  .consult-icon-box {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .icon-scheduled {
    background: var(--color-success-bg);
    color: var(--color-success);
  }

  .icon-pending {
    background: var(--color-warning-bg);
    color: var(--color-warning);
  }

  .icon-completed {
    background: var(--bg-secondary);
    color: var(--text-secondary);
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
    max-width: 500px;
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

  /* Full Screen Video Call Overlay */
  .video-call-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: #0f0f12;
    z-index: 10000;
    display: none;
    flex-direction: column;
    color: white;
    font-family: sans-serif;
    opacity: 0;
    transition: opacity 0.4s ease;
  }
  .video-call-overlay.active {
    display: flex;
    opacity: 1;
  }
  .video-grid {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-4);
    padding: var(--space-6);
    position: relative;
  }
  @media(max-width: 768px) {
    .video-grid {
      grid-template-columns: 1fr;
    }
  }
  .video-feed {
    background: #1a1a24;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: var(--radius-xl);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .video-feed-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--action-primary) 0%, #cc4700 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
    box-shadow: var(--shadow-lg);
  }
  .feed-label {
    position: absolute;
    bottom: var(--space-4);
    left: var(--space-4);
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    padding: 6px 12px;
    border-radius: var(--radius-md);
    font-size: 12px;
    font-weight: 600;
  }
  [dir="rtl"] .feed-label {
    left: auto;
    right: var(--space-4);
  }
  .call-controls {
    height: 84px;
    background: #141419;
    border-top: 1px solid rgba(255,255,255,0.06);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 var(--space-6);
  }
  .control-group {
    display: flex;
    gap: var(--space-3);
    align-items: center;
  }
  .control-btn {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
  }
  .control-btn:hover {
    background: rgba(255,255,255,0.18);
    transform: scale(1.05);
  }
  .control-btn.btn-hangup {
    background: var(--color-error);
  }
  .control-btn.btn-hangup:hover {
    background: #e74c3c;
  }
  .control-btn.active {
    background: rgba(255, 255, 255, 0.9) !important;
    color: #141419;
  }
  .chat-panel {
    width: 320px;
    background: #141419;
    border-left: 1px solid rgba(255,255,255,0.06);
    display: none;
    flex-direction: column;
  }
  [dir="rtl"] .chat-panel {
    border-left: none;
    border-right: 1px solid rgba(255,255,255,0.06);
  }
  .chat-panel.active {
    display: flex;
  }
  .chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: var(--space-4);
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
  }
  .chat-bubble {
    background: rgba(255,255,255,0.06);
    padding: 10px 14px;
    border-radius: var(--radius-lg);
    font-size: 13px;
    max-width: 85%;
    line-height: 1.4;
  }
  .chat-bubble.mine {
    background: var(--action-primary);
    align-self: flex-end;
  }
  .chat-input-wrapper {
    padding: var(--space-3);
    border-top: 1px solid rgba(255,255,255,0.06);
    display: flex;
    gap: var(--space-2);
  }

  /* Paper Document Viewer for Report */
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
  
  /* Timeline Trackers */
  .timeline-container {
    display: flex;
    flex-direction: column;
    gap: var(--space-6);
    position: relative;
    margin-bottom: var(--space-6);
  }
  .timeline-container::before {
    content: '';
    position: absolute;
    top: 10px;
    bottom: 10px;
    left: 17px;
    width: 2px;
    background: var(--border-default);
  }
  [dir="rtl"] .timeline-container::before {
    left: auto;
    right: 17px;
  }
  .timeline-row {
    display: flex;
    gap: var(--space-4);
    position: relative;
    align-items: flex-start;
  }
  .timeline-dot {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--bg-secondary);
    border: 2px solid var(--border-default);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-tertiary);
    z-index: 1;
    font-size: 14px;
  }
  .timeline-row.completed .timeline-dot {
    background: var(--color-success-bg);
    border-color: var(--color-success);
    color: var(--color-success);
  }
  .timeline-row.active .timeline-dot {
    background: var(--color-primary-light);
    border-color: var(--action-primary);
    color: var(--action-primary);
    box-shadow: 0 0 0 4px rgba(255,90,0,0.15);
  }
</style>

<div class="fade-in">
  <!-- Top Action Bar -->
  <div class="mb-6 d-flex justify-between items-center flex-wrap gap-4">
    <div>
      <h2 class="text-h3" style="font-weight:var(--weight-bold); letter-spacing:-0.5px">
        {{ app()->getLocale() == 'ar' ? 'الاستشارات والاجتماعات' : 'Consultations & Meetings' }}
      </h2>
      <p class="text-secondary mt-1">
        {{ app()->getLocale() == 'ar' ? 'احجز استشارات مع خبراء الاستثمار ومديري حسابات الصناديق وتابع مواعيدها.' : 'Book consultations with investment experts or account managers and track status.' }}
      </p>
    </div>
    <button class="btn btn-primary" style="border-radius:var(--radius-lg); height: 42px" onclick="openRequestModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-inline-end: 6px"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
      <span>{{ app()->getLocale() == 'ar' ? 'طلب استشارة' : 'Request Consultation' }}</span>
    </button>
  </div>

  @php
    $totalCount = count($consultations);
    $scheduledCount = $consultations->where('status', 'Scheduled')->count();
    $pendingCount = $consultations->where('status', 'Pending Response')->count();
    $completedCount = $consultations->where('status', 'Completed')->count();
  @endphp

  <!-- Stats Grid -->
  <div class="stats-grid">
    <!-- Stat 1 -->
    <div class="stat-card-premium">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'إجمالي الاستشارات' : 'Total Consultations' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $totalCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'جلسات في الأرشيف والجدول' : 'Requested expert reviews' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-primary-light); color:var(--color-primary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </div>
    </div>

    <!-- Stat 2 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-success)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'لقاءات مجدولة' : 'Scheduled Calls' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)" id="stat-scheduled-count">
          {{ $scheduledCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'جلسات قادمة مؤكدة' : 'Confirmed upcoming slots' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-success-bg); color:var(--color-success)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
      </div>
    </div>

    <!-- Stat 3 -->
    <div class="stat-card-premium" style="--color-primary: var(--color-warning)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'في انتظار الرد' : 'Pending Response' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)" id="stat-pending-count">
          {{ $pendingCount }}
        </div>
        <div class="text-caption mt-2" style="color:var(--color-warning); font-weight:var(--weight-medium); display:flex; align-items:center; gap:4px">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'بانتظار تحديد موعد' : 'Awaiting date allocation' }}</span>
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--color-warning-bg); color:var(--color-warning)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
      </div>
    </div>

    <!-- Stat 4 -->
    <div class="stat-card-premium" style="--color-primary: var(--text-secondary)">
      <div>
        <div class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">
          {{ app()->getLocale() == 'ar' ? 'استشارات مكتملة' : 'Completed Sessions' }}
        </div>
        <div class="text-h4 mt-1" style="font-weight:var(--weight-bold); color:var(--text-primary)">
          {{ $completedCount }}
        </div>
        <div class="text-caption mt-2 text-secondary" style="font-weight:var(--weight-medium)">
          {{ app()->getLocale() == 'ar' ? 'تمت بنجاح وتوثيق ملاحظاتها' : 'Minutes of meeting filed' }}
        </div>
      </div>
      <div class="stat-icon-container" style="background:var(--bg-secondary); color:var(--text-secondary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
    </div>
  </div>

  <!-- Controls Bar -->
  <div class="controls-bar">
    <!-- Live Search -->
    <div class="search-wrapper">
      <input type="text" id="consult-search" class="search-input-premium" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث عن استشارة...' : 'Search consultations...' }}" onkeyup="filterConsultations()">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>

    <!-- Status Filters -->
    <div class="filter-chips-wrapper" id="consult-filter-chips">
      <button class="chip-premium active" onclick="filterConsultStatus('all', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</span>
        <span class="chip-count">{{ $totalCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterConsultStatus('Scheduled', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'مجدولة' : 'Scheduled' }}</span>
        <span class="chip-count">{{ $scheduledCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterConsultStatus('Pending Response', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'في انتظار الرد' : 'Pending' }}</span>
        <span class="chip-count">{{ $pendingCount }}</span>
      </button>
      <button class="chip-premium" onclick="filterConsultStatus('Completed', this)">
        <span>{{ app()->getLocale() == 'ar' ? 'مكتملة' : 'Completed' }}</span>
        <span class="chip-count">{{ $completedCount }}</span>
      </button>
    </div>
  </div>

  <!-- Empty State -->
  <div class="empty-state-wrapper" id="empty-state">
    <div class="empty-state-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
    </div>
    <h3 class="text-h5" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'لا توجد استشارات مطابقة لبحثك' : 'No meetings found' }}</h3>
    <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'يرجى مراجعة خيارات التصفية أو تغيير البحث.' : 'Please try adjusting your search terms or filter selection.' }}</p>
  </div>

  <!-- List Wrapper -->
  <div class="consultations-list" id="consult-list">
    @foreach($consultations as $c)
      @php
        // Map status tags & color mix styles
        $isScheduled = ($c->status == 'Scheduled');
        $isPending = ($c->status == 'Pending Response');
        
        $iconClass = 'icon-completed';
        $badgeClass = 'badge-neutral';
        $statusStyle = '';
        if ($isScheduled) {
            $iconClass = 'icon-scheduled';
            $badgeClass = 'badge-success';
            $statusStyle = 'color: var(--color-success); border-color: rgba(46,204,113,0.2); background: rgba(46,204,113,0.06)';
        } elseif ($isPending) {
            $iconClass = 'icon-pending';
            $badgeClass = 'badge-warning';
            $statusStyle = 'color: var(--color-warning); border-color: rgba(241,196,15,0.2); background: rgba(241,196,15,0.06)';
        }
        
        // Translate Title
        $translatedTitle = $c->title;
        if(app()->getLocale() == 'ar') {
            if($c->title == 'Portfolio Strategy Review') $translatedTitle = 'مراجعة استراتيجية المحفظة الاستثمارية';
            elseif($c->title == 'Exit Planning Discussion') $translatedTitle = 'جلسة مناقشة خطط ومواعيد التخارج';
            elseif($c->title == 'Q2 Performance Deep Dive') $translatedTitle = 'تحليل معمق لأداء المشاريع في الربع الثاني';
        }
        
        // Date strings
        $dateStr = '';
        if ($c->scheduled_at) {
            $dateStr = $c->scheduled_at;
            if ($c->title == 'Portfolio Strategy Review') $dateStr = '15 Jun, 2026 · 14:00';
            elseif ($c->title == 'Q2 Performance Deep Dive') $dateStr = '28 May, 2026';
        } else {
            $dateStr = 'Requested Jun 08';
        }
      @endphp
      <div class="consultation-card-premium card" data-status="{{ $c->status }}" data-title="{{ $translatedTitle }}" data-consultant="{{ $c->with_name }}">
        <div class="d-flex gap-4 items-center flex-1">
          <!-- Icon box -->
          <div class="consult-icon-box {{ $iconClass }}">
            @if($isScheduled)
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.6 11.6L12 8V3M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            @elseif($isPending)
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            @else
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            @endif
          </div>
          <div>
            <h4 class="text-label" style="font-weight:var(--weight-bold); color:var(--text-primary)">{{ $translatedTitle }}</h4>
            <div class="text-caption text-secondary mt-1">
              {{ $dateStr }} · {{ app()->getLocale() == 'ar' ? 'مع' : 'with' }} 
              <span style="font-weight: var(--weight-semibold); color: var(--text-primary)">
                @if(app()->getLocale() == 'ar')
                  @if($c->with_name == 'Ahmad Al-Rashid') أحمد الرشيد
                  @elseif($c->with_name == 'Investment Committee') لجنة الاستثمار
                  @else مدير علاقات المستثمرين
                  @endif
                @else
                  {{ $c->with_name }}
                @endif
              </span>
            </div>
          </div>
        </div>
        
        <!-- Badge & Action Buttons -->
        <div class="d-flex gap-4 items-center">
          <span class="badge {{ $badgeClass }} @if($isScheduled) badge-pulse @endif" style="border-radius:var(--radius-full); {{ $statusStyle }}">
            @if(app()->getLocale() == 'ar')
              @if($c->status == 'Scheduled') مجدولة
              @elseif($c->status == 'Completed') مكتملة
              @else في انتظار الرد
              @endif
            @else
              {{ $c->status }}
            @endif
          </span>
          
          <div>
            @if($isScheduled)
              <button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg); display: inline-flex; align-items: center; gap: 6px" onclick="joinCall('{{ $translatedTitle }}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 7a2 2 0 0 0-2.45-1.45L16 7V5a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2l4.55 1.45A2 2 0 0 0 23 17V7Z"/></svg>
                <span>{{ app()->getLocale() == 'ar' ? 'انضمام' : 'Join' }}</span>
              </button>
            @elseif($isPending)
              <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)" onclick="viewRequestDetails('{{ $translatedTitle }}')">
                <span>{{ app()->getLocale() == 'ar' ? 'عرض الطلب' : 'View' }}</span>
              </button>
            @else
              <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)" onclick="viewMinutes('{{ $translatedTitle }}')">
                <span>{{ app()->getLocale() == 'ar' ? 'عرض التقرير' : 'View Notes' }}</span>
              </button>
            @endif
          </div>
        </div>

      </div>
    @endforeach
  </div>
</div>

<!-- Modal: New Consultation Request Simulator -->
<div class="modal-overlay" id="consult-modal">
  <div class="modal-content-premium">
    <button class="modal-close-btn" onclick="closeRequestModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    
    <!-- State 1: Form -->
    <div id="consult-form-state">
      <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold); color:var(--text-primary)">
        {{ app()->getLocale() == 'ar' ? 'طلب استشارة استثمارية' : 'Request Consultation' }}
      </h3>
      <p class="text-secondary text-body-sm mb-4">
        {{ app()->getLocale() == 'ar' ? 'اختر موضوع الاستشارة والمستشار المناسب لحجز موعد جلسة اتصال مرئية.' : 'Select the review topic and expert to schedule a videoconference review session.' }}
      </p>

      <!-- Topic Selector -->
      <div class="form-group-premium">
        <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'موضوع الاستشارة' : 'Consultation Topic' }}</label>
        <select id="consult-topic-select" class="form-input-premium form-select">
          <option value="Portfolio Strategy Review">{{ app()->getLocale() == 'ar' ? 'مراجعة استراتيجية المحفظة' : 'Portfolio Strategy Review' }}</option>
          <option value="Exit Planning advisory">{{ app()->getLocale() == 'ar' ? 'استشارة تخطيط المخارج والسيولة' : 'Exit Planning advisory' }}</option>
          <option value="Tax & Regulatory consulting">{{ app()->getLocale() == 'ar' ? 'الاستشارات الضريبية والتنظيمية' : 'Tax & Regulatory consulting' }}</option>
          <option value="New Venture opportunity pitch">{{ app()->getLocale() == 'ar' ? 'عرض فرصة استثمارية جديدة' : 'New Venture opportunity pitch' }}</option>
        </select>
      </div>

      <!-- Consultant Selector -->
      <div class="form-group-premium">
        <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'المستشار المطلوب' : 'Preferred Expert / Consultant' }}</label>
        <select id="consult-with-select" class="form-input-premium form-select">
          <option value="Ahmad Al-Rashid">{{ app()->getLocale() == 'ar' ? 'أحمد الرشيد (خبير استراتيجي)' : 'Ahmad Al-Rashid (Strategy Lead)' }}</option>
          <option value="Investment Committee">{{ app()->getLocale() == 'ar' ? 'لجنة الاستثمار التابعة للصندوق' : 'Investment Committee' }}</option>
          <option value="Account Manager">{{ app()->getLocale() == 'ar' ? 'فهد آل سعود (مدير حسابك)' : 'Fahad Al-Saud (Account Manager)' }}</option>
        </select>
      </div>

      <!-- Date Input -->
      <div class="form-group-premium">
        <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'التاريخ والوقت المقترح' : 'Preferred Date & Time' }}</label>
        <input type="datetime-local" id="consult-time-input" class="form-input-premium" value="2026-06-20T10:00">
      </div>

      <div style="display:flex; gap:12px; margin-bottom:var(--space-6)">
        <button class="btn btn-ghost flex-1" style="border-radius:var(--radius-lg)" onclick="closeRequestModal()">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
        <button class="btn btn-primary flex-1" style="border-radius:var(--radius-lg)" onclick="submitConsultRequest()">{{ app()->getLocale() == 'ar' ? 'تقديم الطلب' : 'Submit Request' }}</button>
      </div>
    </div>

    <!-- State 2: Processing -->
    <div id="consult-loading-state" style="display:none; text-align:center; padding:var(--space-8) 0">
      <div style="width:48px; height:48px; border:3px solid var(--border-default); border-top-color:var(--color-primary); border-radius:50%; animation:spin 1s linear infinite; margin:0 auto var(--space-4) auto"></div>
      <h4 class="text-h5" style="font-weight:var(--weight-semibold)">
        {{ app()->getLocale() == 'ar' ? 'جاري إرسال طلب الحجز...' : 'Sending request...' }}
      </h4>
      <p class="text-secondary text-caption mt-1">{{ app()->getLocale() == 'ar' ? 'الرجاء عدم إغلاق هذه النافذة' : 'Please wait while we reserve your slot' }}</p>
    </div>

    <!-- State 3: Success -->
    <div id="consult-success-state" style="display:none; text-align:center; padding:var(--space-6) 0">
      <div class="success-checkmark">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h3 class="text-h4" style="font-weight:var(--weight-bold); color:var(--text-primary)">
        {{ app()->getLocale() == 'ar' ? 'تم تقديم الطلب بنجاح!' : 'Request Sent!' }}
      </h3>
      <p class="text-secondary text-body-sm mt-2 mb-6" id="consult-success-desc">
        {{ app()->getLocale() == 'ar' ? 'تم إرسال طلب الاستشارة بنجاح. سيقوم المستشار المعني بالرد وتثبيت الموعد خلال 24 ساعة.' : 'Your request has been submitted. The allocated expert will confirm your slot within 24 hours.' }}
      </p>

      <button class="btn btn-primary" style="border-radius:var(--radius-lg); width:100%" onclick="closeRequestModal()">
        {{ app()->getLocale() == 'ar' ? 'العودة للاستشارات' : 'Return to Consultations' }}
      </button>
    </div>
  </div>
</div>

<!-- Video Call Overlay -->
<div class="video-call-overlay" id="video-call-overlay">
  <!-- Grid -->
  <div class="video-grid">
    <!-- feed 1: consultant -->
    <div class="video-feed">
      <div class="video-feed-avatar" id="consultant-video-avatar">F</div>
      <div class="feed-label" id="consultant-feed-label">Fahad Al-Saud (Manager)</div>
    </div>
    <!-- feed 2: investor -->
    <div class="video-feed" id="local-video-feed">
      <div class="video-feed-avatar" style="background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%)">KA</div>
      <div class="feed-label">{{ app()->getLocale() == 'ar' ? 'أنت (خالد الدوسري)' : 'You (Khalid Al-Dosari)' }}</div>
    </div>
  </div>
  
  <!-- Right Chat Panel -->
  <div class="chat-panel" id="call-chat-panel">
    <div style="padding: var(--space-4); border-bottom: 1px solid rgba(255,255,255,0.06); font-weight: 600; font-size:14px">
      {{ app()->getLocale() == 'ar' ? 'المحادثة الفورية' : 'Live Chat' }}
    </div>
    <div class="chat-messages" id="call-chat-messages">
      <div class="chat-bubble">
        <strong>Fahad:</strong> Welcome to the portfolio strategy session, Khalid. Let me know if you can hear me well.
      </div>
    </div>
    <div class="chat-input-wrapper">
      <input type="text" id="call-chat-input" placeholder="{{ app()->getLocale() == 'ar' ? 'اكتب رسالة...' : 'Type a message...' }}" style="flex:1; padding: 6px 12px; border-radius: var(--radius-md); background: rgba(255,255,255,0.06); border: none; color: white; font-size: 13px" onkeydown="handleChatKeyDown(event)">
      <button type="button" class="btn btn-primary" onclick="sendCallChatMessage()" style="padding: 6px 12px; border-radius: var(--radius-md); font-size:12px">
        {{ app()->getLocale() == 'ar' ? 'إرسال' : 'Send' }}
      </button>
    </div>
  </div>

  <!-- Controls Bar -->
  <div class="call-controls">
    <div class="control-group">
      <span style="font-size:14px; font-weight: 600; color: #a0a0b0; display: flex; align-items: center; gap: 8px">
        <span style="width: 8px; height: 8px; border-radius:50%; background: var(--color-success); display:inline-block"></span>
        <span id="call-timer">00:00</span>
      </span>
    </div>
    
    <div class="control-group">
      <!-- Mic toggle -->
      <button type="button" class="control-btn" id="btn-toggle-mic" onclick="toggleCallMic()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v1a7 7 0 0 1-14 0v-1M12 19v3M8 22h8"/></svg>
      </button>
      <!-- Video toggle -->
      <button type="button" class="control-btn" id="btn-toggle-video" onclick="toggleCallVideo()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 7a2 2 0 0 0-2.45-1.45L16 7V5a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2l4.55 1.45A2 2 0 0 0 23 17V7Z"/></svg>
      </button>
      <!-- Screen share -->
      <button type="button" class="control-btn" id="btn-share-screen" onclick="toggleScreenShare()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><path d="M8 21h8M12 17v4"/></svg>
      </button>
      <!-- Chat toggle -->
      <button type="button" class="control-btn" id="btn-toggle-chat" onclick="toggleCallChat()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </button>
    </div>

    <div class="control-group">
      <button type="button" class="control-btn btn-hangup" onclick="leaveVideoCall()" title="{{ app()->getLocale() == 'ar' ? 'إنهاء الاجتماع' : 'Leave Meeting' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.68 22.18a19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81 7A2 2 0 0 1 22 16.92z"/></svg>
      </button>
    </div>
  </div>
</div>

<!-- Minutes / Report Viewer Modal -->
<div class="modal-overlay" id="minutes-viewer-modal" style="z-index: 9999;">
  <div class="modal-content-premium" style="max-width: 760px; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;">
    <button class="modal-close-btn" onclick="closeMinutesModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    
    <div style="padding-bottom:var(--space-3); border-bottom:1px solid var(--border-default); display:flex; justify-content:space-between; align-items:center">
      <h4 class="text-h6" id="minutes-modal-title" style="margin:0; font-weight:var(--weight-bold); color:var(--text-primary)">-</h4>
    </div>

    <!-- Paper Container -->
    <div style="flex:1; overflow-y:auto; padding:var(--space-6) var(--space-4); background:#1e1e1e; border-radius: var(--radius-lg); margin: var(--space-4) 0">
      <div class="report-paper" id="minutes-paper-area">
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
            <div style="font-size:10px; color:#666; font-weight:600" id="minutes-paper-date">Date: 2026-06-15</div>
            <div style="font-size:10px; color:#666; font-weight:600" id="minutes-paper-ref">REF: STC-MIN-8271</div>
          </div>
        </div>

        <div>
          <h2 style="font-size:20px; font-weight:800; color:#111; margin:0 0 8px 0" id="minutes-paper-title">-</h2>
          <div style="font-size:12px; color:#555; margin-bottom:20px">
            <strong>{{ app()->getLocale() == 'ar' ? 'المستشار:' : 'Consultant:' }}</strong> <span id="minutes-paper-consultant">-</span>
          </div>

          <div style="border: 1px solid #ddd; padding: 14px; border-radius: 6px; background: #fafafa; margin-bottom: 20px">
            <h4 style="margin:0 0 6px 0; font-size:13px; color:#111">{{ app()->getLocale() == 'ar' ? 'ملخص ومخرجات الجلسة:' : 'Session Summary & Outputs:' }}</h4>
            <p style="margin:0; font-size:12px; line-height:1.5; color:#444" id="minutes-paper-content">-</p>
          </div>

          <div style="margin-bottom: 20px">
            <h4 style="margin:0 0 6px 0; font-size:13px; color:#111">{{ app()->getLocale() == 'ar' ? 'التوصيات الرئيسية:' : 'Key Recommendations:' }}</h4>
            <ul style="margin:0; padding-inline-start: 20px; font-size:12px; line-height:1.6; color:#444" id="minutes-paper-recs">
              <li>-</li>
            </ul>
          </div>
        </div>

        <div style="border-top:1px solid #eee; padding-top:15px; margin-top:30px; display:flex; justify-content:space-between; align-items:center; font-size:10px; color:#888">
          <div>© 2026 Seven Tech Capital. All rights reserved.</div>
          <div>{{ app()->getLocale() == 'ar' ? 'وثيقة مستندات الصندوق الرسمية' : 'Official Portal Documentation' }}</div>
        </div>
      </div>
    </div>

    <div style="display:flex; gap:12px">
      <button class="btn btn-ghost flex-1" style="border-radius:var(--radius-lg)" onclick="closeMinutesModal()">{{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}</button>
      <button class="btn btn-primary flex-1" style="border-radius:var(--radius-lg); display:inline-flex; align-items:center; justify-content:center; gap:6px" onclick="downloadMinutesFile()">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
        <span>{{ app()->getLocale() == 'ar' ? 'تحميل التقرير' : 'Download Minutes' }}</span>
      </button>
    </div>
  </div>
</div>

<!-- Request Details Timeline Modal -->
<div class="modal-overlay" id="request-details-modal">
  <div class="modal-content-premium" style="max-width: 480px">
    <button class="modal-close-btn" onclick="closeRequestDetailsModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    
    <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold); color:var(--text-primary)" id="req-details-title">
      -
    </h3>
    <p class="text-secondary text-body-sm mb-4" id="req-details-subtitle">
      -
    </p>

    <!-- Timeline Grid -->
    <div class="timeline-container">
      <div class="timeline-row completed">
        <div class="timeline-dot">✓</div>
        <div>
          <h5 class="text-body-sm mb-1" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'تم تقديم الطلب' : 'Request Submitted' }}</h5>
          <p class="text-caption text-secondary" style="margin:0">{{ app()->getLocale() == 'ar' ? 'خالد الدوسري · بانتظار استجابة المستشار وتثبيت الساعة المقترحة.' : 'Khalid Al-Dosari · Request sent to portfolio manager.' }}</p>
        </div>
      </div>
      
      <div class="timeline-row active">
        <div class="timeline-dot">⏱</div>
        <div>
          <h5 class="text-body-sm mb-1" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'بانتظار موافقة المستشار' : 'Awaiting Expert Review' }}</h5>
          <p class="text-caption text-secondary" style="margin:0">{{ app()->getLocale() == 'ar' ? 'جاري مراجعة الموعد المقترح وتوزيع الموارد لغرفة الفيديو.' : 'Allocating secure WebRTC meeting room details.' }}</p>
        </div>
      </div>

      <div class="timeline-row">
        <div class="timeline-dot">○</div>
        <div>
          <h5 class="text-body-sm mb-1" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'تثبيت اللقاء وغرفة الفيديو' : 'Room Launch & Confirmation' }}</h5>
          <p class="text-caption text-secondary" style="margin:0">{{ app()->getLocale() == 'ar' ? 'الحالة: لم يتم تثبيته بعد.' : 'Status: Pending calendar booking.' }}</p>
        </div>
      </div>
    </div>

    <button class="btn btn-primary mt-6" style="border-radius:var(--radius-lg); width:100%" onclick="closeRequestDetailsModal()">
      {{ app()->getLocale() == 'ar' ? 'فهمت' : 'Close' }}
    </button>
  </div>
</div>

<div class="toast-container" id="consultations-toast-container"></div>

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

  let currentConsultStatusFilter = 'all';
  let callTimerInterval = null;
  let activeReportTitle = '';
  let activeReportConsultant = '';

  // --- Toast Notification Manager ---
  function showToast(message, type = 'success') {
    const container = document.getElementById('consultations-toast-container');
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

  // Join Call Simulation
  function joinCall(title) {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    showToast(isAr ? 'جاري الاتصال والولوج لغرفة الاجتماع المرئي...' : 'Launching secure WebRTC connection...');
    
    // Set avatars and labels based on consultant name
    const avatarFeed = document.getElementById('consultant-video-avatar');
    const labelFeed = document.getElementById('consultant-feed-label');
    
    if (title.includes('Committee') || title.includes('لجنة')) {
      avatarFeed.innerText = 'IC';
      avatarFeed.style.background = 'linear-gradient(135deg, var(--accent-gold) 0%, #d4af37 100%)';
      labelFeed.innerText = isAr ? 'لجنة الاستثمار (صندوق الاستثمار)' : 'Investment Committee (Seven Tech)';
    } else if (title.includes('Rashid') || title.includes('الرشيد')) {
      avatarFeed.innerText = 'AR';
      avatarFeed.style.background = 'linear-gradient(135deg, #3498db 0%, #2980b9 100%)';
      labelFeed.innerText = isAr ? 'أحمد الرشيد (خبير المحفظة)' : 'Ahmad Al-Rashid (Portfolio Lead)';
    } else {
      avatarFeed.innerText = 'F';
      avatarFeed.style.background = 'linear-gradient(135deg, var(--action-primary) 0%, #cc4700 100%)';
      labelFeed.innerText = isAr ? 'فهد آل سعود (مدير علاقات المستثمرين)' : 'Fahad Al-Saud (Investor Relations)';
    }

    setTimeout(() => {
      const overlay = document.getElementById('video-call-overlay');
      overlay.classList.add('active');
      
      // Start call timer
      let seconds = 0;
      const timerEl = document.getElementById('call-timer');
      timerEl.innerText = "00:00";
      
      if (callTimerInterval) clearInterval(callTimerInterval);
      callTimerInterval = setInterval(() => {
        seconds++;
        const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
        const secs = String(seconds % 60).padStart(2, '0');
        timerEl.innerText = `${mins}:${secs}`;
      }, 1000);

      // Populate mock welcome messages
      const msgsContainer = document.getElementById('call-chat-messages');
      msgsContainer.innerHTML = `
        <div class="chat-bubble">
          <strong>${avatarFeed.innerText === 'IC' ? 'Committee' : (avatarFeed.innerText === 'AR' ? 'Ahmad' : 'Fahad')}:</strong> 
          ${isAr ? 'مرحباً بك في جلسة الاستشارة والتقييم المشفرة، خالد. كيف يمكنني مساعدتك اليوم؟' : "Welcome to the encrypted consulting session, Khalid. How can we assist you with your strategy today?"}
        </div>
      `;
    }, 1000);
  }

  function leaveVideoCall() {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    if (callTimerInterval) clearInterval(callTimerInterval);
    document.getElementById('video-call-overlay').classList.remove('active');
    showToast(isAr ? 'تم إنهاء المكالمة ومغادرة الغرفة.' : 'Call ended and room cleared.');
  }

  function sendCallChatMessage() {
    const input = document.getElementById('call-chat-input');
    const msg = input.value.trim();
    if (!msg) return;

    const msgsContainer = document.getElementById('call-chat-messages');
    const bubble = document.createElement('div');
    bubble.className = 'chat-bubble mine';
    bubble.innerHTML = `<strong>You:</strong> ${msg}`;
    msgsContainer.appendChild(bubble);
    input.value = '';
    msgsContainer.scrollTop = msgsContainer.scrollHeight;

    // Simulate reply after 1.5 seconds
    setTimeout(() => {
      const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
      const reply = document.createElement('div');
      reply.className = 'chat-bubble';
      reply.innerHTML = `<strong>System:</strong> ${isAr ? 'تم استلام الرسالة، جاري معالجة استفسارك.' : 'Message received, compiling answer...'}`;
      msgsContainer.appendChild(reply);
      msgsContainer.scrollTop = msgsContainer.scrollHeight;
    }, 1500);
  }

  function handleChatKeyDown(e) {
    if (e.key === 'Enter') {
      sendCallChatMessage();
    }
  }

  function toggleCallMic() {
    const btn = document.getElementById('btn-toggle-mic');
    btn.classList.toggle('active');
    const isMuted = !btn.classList.contains('active');
    showToast(isMuted ? 'Microphone Muted' : 'Microphone Unmuted', isMuted ? 'error' : 'success');
  }

  function toggleCallVideo() {
    const btn = document.getElementById('btn-toggle-video');
    btn.classList.toggle('active');
    const isOff = !btn.classList.contains('active');
    showToast(isOff ? 'Camera Off' : 'Camera On', isOff ? 'error' : 'success');
  }

  function toggleScreenShare() {
    const btn = document.getElementById('btn-share-screen');
    btn.classList.toggle('active');
    const isSharing = btn.classList.contains('active');
    showToast(isSharing ? 'Screen sharing active' : 'Screen sharing stopped');
  }

  function toggleCallChat() {
    const chat = document.getElementById('call-chat-panel');
    chat.classList.toggle('active');
  }

  // View Pending Request Details timeline modal
  function viewRequestDetails(title) {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    document.getElementById('req-details-title').innerText = title;
    document.getElementById('req-details-subtitle').innerText = isAr 
      ? 'تفاصيل ومسار معالجة طلب الاستشارة الاستثمارية المقدم.'
      : 'Consultation booking timeline and status parameters.';
      
    document.getElementById('request-details-modal').classList.add('active');
  }

  function closeRequestDetailsModal() {
    document.getElementById('request-details-modal').classList.remove('active');
  }

  // View Completed Minutes report viewer modal
  function viewMinutes(title) {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    activeReportTitle = title;
    
    // Map topics to contents
    let content = "";
    let recs = [];
    let consultant = "";
    let ref = "";
    
    if (title.includes('Strategy') || title.includes('استراتيجية')) {
      consultant = isAr ? "أحمد الرشيد" : "Ahmad Al-Rashid";
      ref = "STC-MIN-8271";
      content = isAr 
        ? "تمت مراجعة الهيكل الاستثماري الحالي للمحفظة مع المستثمر. تم الاتفاق على زيادة نسبة تملك قطاع التقنية المالية (FinTech) من 15% إلى 25% مع إعادة توجيه جزء من الأرباح المحتجزة لتغطية الفروع الجديدة."
        : "Reviewed the current investment allocation. Agreed to increase the FinTech exposure from 15% to 25% by reinvesting retained earnings.";
      recs = isAr 
        ? ["شراء أسهم إضافية في جولة التمويل القادمة لـ FinFlow.", "تنويع المحفظة في قطاع التقنيات النظيفة بنسبة 5%.", "متابعة تطورات العوائد في نهاية الربع الحالي."]
        : ["Execute add-on allocation in FinFlow's Series B.", "Rebalance clean-tech segment exposure to 5%.", "Conduct next valuation check at end of Q2."];
    } else {
      consultant = isAr ? "لجنة الاستثمار" : "Investment Committee";
      ref = "STC-MIN-9082";
      content = isAr 
        ? "مراجعة شاملة لخيارات ومسارات التخارج والسيولة المتاحة للمستثمر في المشروعات القائمة. تم مناقشة آفاق الاكتتاب العام الأولي والتسهيلات المقدمة للمستثمرين الأوائل."
        : "In-depth briefing of liquidity pathways and exit frameworks. Discussed secondary market placement opportunities and listing schedules.";
      recs = isAr 
        ? ["تقديم طلب تخارج جزئي لمشروع Alpha عند إتمام الاستحواذ.", "مراجعة شروط اتفاقية حق الشفعة.", "التنسيق مع المستشار المالي لتحديد السعر المستهدف."]
        : ["File partial exit request for Project Alpha post-acquisition.", "Review Right of First Refusal clauses.", "Align with financial advisor on floor valuation limits."];
    }

    activeReportConsultant = consultant;

    document.getElementById('minutes-modal-title').innerText = isAr ? 'محضر الجلسة والتقرير' : 'Session Minutes & Report';
    document.getElementById('minutes-paper-title').innerText = title;
    document.getElementById('minutes-paper-consultant').innerText = consultant;
    document.getElementById('minutes-paper-content').innerText = content;
    document.getElementById('minutes-paper-ref').innerText = `REF: ${ref}`;
    document.getElementById('minutes-paper-date').innerText = `Date: ${new Date().toISOString().split('T')[0]}`;
    
    const recsUl = document.getElementById('minutes-paper-recs');
    recsUl.innerHTML = recs.map(r => `<li>${r}</li>`).join('');

    document.getElementById('minutes-viewer-modal').classList.add('active');
  }

  function closeMinutesModal() {
    document.getElementById('minutes-viewer-modal').classList.remove('active');
  }

  // Simulated download of minutes text file
  function downloadMinutesFile() {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    showToast(isAr ? 'جاري تصدير وتحميل تقرير الجلسة...' : 'Exporting meeting report...');
    
    setTimeout(() => {
      const textContent = `==================================================\n` +
                          `             SEVEN TECH CAPITAL REPORT            \n` +
                          `==================================================\n` +
                          `REPORT:      ${activeReportTitle}\n` +
                          `CONSULTANT:  ${activeReportConsultant}\n` +
                          `DATE:        ${new Date().toISOString().split('T')[0]}\n` +
                          `ATTENDEE:    Khalid Al-Dosari\n` +
                          `--------------------------------------------------\n` +
                          `SUMMARY:\n` +
                          `Document containing secure strategy minutes.\n` +
                          `==================================================\n`;
                          
      const blob = new Blob([textContent], { type: "text/plain;charset=utf-8" });
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = `Session_Report_${activeReportTitle.replace(/\s+/g, "_")}.txt`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      
      showToast(isAr ? 'تم تحميل التقرير بنجاح!' : 'Report downloaded successfully!');
    }, 1000);
  }

  // Handle Modals
  function openRequestModal() {
    document.getElementById('consult-form-state').style.display = 'block';
    document.getElementById('consult-loading-state').style.display = 'none';
    document.getElementById('consult-success-state').style.display = 'none';

    document.getElementById('consult-modal').classList.add('active');
  }

  function closeRequestModal() {
    document.getElementById('consult-modal').classList.remove('active');
  }

  // Submit Request
  function submitConsultRequest() {
    const topic = document.getElementById('consult-topic-select').value;
    const withName = document.getElementById('consult-with-select').value;
    const dateTime = document.getElementById('consult-time-input').value;

    if (!dateTime) {
      showToast("{{ app()->getLocale() == 'ar' ? 'يرجى اختيار تاريخ ووقت مقترح' : 'Please select a preferred date and time' }}", 'error');
      return;
    }

    // Toggle Loading State
    document.getElementById('consult-form-state').style.display = 'none';
    document.getElementById('consult-loading-state').style.display = 'block';

    setTimeout(() => {
      // Toggle Success State
      document.getElementById('consult-loading-state').style.display = 'none';
      document.getElementById('consult-success-state').style.display = 'block';

      // Insert new card dynamically into the list!
      const list = document.getElementById('consult-list');
      
      const newCard = document.createElement('div');
      newCard.className = 'consultation-card-premium card';
      newCard.setAttribute('data-status', 'Pending Response');
      
      // Format topics & withName for display
      let displayTopic = topic;
      let displayWithName = withName;
      if ("{{ app()->getLocale() == 'ar' }}" === "1") {
        if (topic === 'Portfolio Strategy Review') displayTopic = 'مراجعة استراتيجية المحفظة الاستثمارية';
        else if (topic === 'Exit Planning advisory') displayTopic = 'استشارة تخطيط المخارج والسيولة';
        else if (topic === 'Tax & Regulatory consulting') displayTopic = 'الاستشارات الضريبية والتنظيمية';
        else displayTopic = 'عرض فرصة استثمارية جديدة';

        if (withName === 'Ahmad Al-Rashid') displayWithName = 'أحمد الرشيد';
        else if (withName === 'Investment Committee') displayWithName = 'لجنة الاستثمار';
        else displayWithName = 'فهد آل سعود';
      }

      newCard.setAttribute('data-title', displayTopic);
      newCard.setAttribute('data-consultant', displayWithName);

      const parsedDate = new Date(dateTime);
      const formattedDate = parsedDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' · ' + parsedDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
      const formattedDateAr = parsedDate.toLocaleDateString('ar-EG', { month: 'short', day: 'numeric', year: 'numeric' }) + ' · ' + parsedDate.toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit', hour12: false });
      const displayDate = "{{ app()->getLocale() == 'ar' }}" === "1" ? formattedDateAr : formattedDate;

      newCard.innerHTML = `
        <div class="d-flex gap-4 items-center flex-1">
          <div class="consult-icon-box icon-pending">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          </div>
          <div>
            <h4 class="text-label" style="font-weight:var(--weight-bold); color:var(--text-primary)">${displayTopic}</h4>
            <div class="text-caption text-secondary mt-1">
              ${displayDate} · ${"{{ app()->getLocale() == 'ar' }}" === "1" ? 'مع' : 'with'} 
              <span style="font-weight: var(--weight-semibold); color: var(--text-primary)">${displayWithName}</span>
            </div>
          </div>
        </div>
        
        <div class="d-flex gap-4 items-center">
          <span class="badge badge-warning badge-pulse" style="border-radius:var(--radius-full); color: var(--color-warning); border-color: rgba(241,196,15,0.2); background: rgba(241,196,15,0.06)">
            ${"{{ app()->getLocale() == 'ar' }}" === "1" ? 'في انتظار الرد' : 'Pending Response'}
          </span>
          <div>
            <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)" onclick="viewRequestDetails('${displayTopic}')">
              <span>${"{{ app()->getLocale() == 'ar' }}" === "1" ? 'عرض الطلب' : 'View'}</span>
            </button>
          </div>
        </div>
      `;

      // Prepend to list
      if (list.firstChild) {
        list.insertBefore(newCard, list.firstChild);
      } else {
        list.appendChild(newCard);
      }

      // Re-calculate statistics counts in the UI!
      const pendingTextEl = document.getElementById('stat-pending-count');
      const currentPendingCount = parseInt(pendingTextEl.innerText, 10);
      pendingTextEl.innerText = currentPendingCount + 1;

    }, 1500);
  }

  // Handle status filter chips click
  function filterConsultStatus(status, btn) {
    currentConsultStatusFilter = status;

    // Toggle active state for chips
    document.querySelectorAll('#consult-filter-chips .chip-premium').forEach(chip => {
      chip.classList.remove('active');
    });
    btn.classList.add('active');

    filterConsultations();
  }

  // Local live filtering
  function filterConsultations() {
    const searchVal = document.getElementById('consult-search').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.consultation-card-premium');

    let visibleCount = 0;

    cards.forEach(card => {
      const title = card.getAttribute('data-title').toLowerCase();
      const consultant = card.getAttribute('data-consultant').toLowerCase();
      const status = card.getAttribute('data-status');

      const matchesSearch = title.includes(searchVal) || consultant.includes(searchVal);
      
      let matchesStatus = false;
      if (currentConsultStatusFilter === 'all') {
        matchesStatus = true;
      } else {
        matchesStatus = (status === currentConsultStatusFilter);
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