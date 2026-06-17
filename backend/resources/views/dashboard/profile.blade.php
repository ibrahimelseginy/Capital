@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إعدادات الملف الشخصي والأمان' : 'Profile & Security Settings')

@section('content')
<!-- Custom Styles -->
<style>
  .fade-in {
    animation: fadeInUp var(--duration-normal) var(--ease-out) forwards;
  }
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(16px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Profile Completeness and Banner */
  .profile-banner {
    background: linear-gradient(135deg, var(--bg-surface) 0%, rgba(255, 90, 0, 0.04) 50%, rgba(198, 161, 91, 0.04) 100%);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
  }
  [data-theme="dark"] .profile-banner {
    background: linear-gradient(135deg, var(--bg-surface) 0%, rgba(255, 90, 0, 0.08) 50%, rgba(198, 161, 91, 0.08) 100%);
  }
  .profile-banner-left {
    display: flex;
    align-items: center;
    gap: var(--space-6);
    z-index: 2;
  }
  .profile-avatar-container {
    position: relative;
    width: 90px;
    height: 90px;
  }
  .profile-avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--bg-surface);
    box-shadow: var(--shadow-md);
    background: linear-gradient(135deg, var(--action-primary), #cc4700);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: 700;
    transition: all 0.3s ease;
  }
  .profile-avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 10px;
    opacity: 0;
    cursor: pointer;
    transition: opacity 0.3s ease;
    border: 3px solid transparent;
  }
  .profile-avatar-container:hover .profile-avatar-overlay {
    opacity: 1;
  }
  
  /* Circular Progress Ring */
  .completeness-container {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    z-index: 2;
    background: var(--bg-surface);
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-default);
    box-shadow: var(--shadow-sm);
  }
  .completeness-wrapper {
    position: relative;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .progress-ring {
    transform: rotate(-90deg);
  }
  .progress-ring__circle {
    transition: stroke-dashoffset 1s var(--ease-out);
    stroke-linecap: round;
  }
  .completeness-num {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 13px;
    font-weight: var(--weight-bold);
    color: var(--text-primary);
    line-height: 1;
  }
  .completeness-info h4 {
    margin: 0;
    font-size: var(--text-body-sm);
    font-weight: var(--weight-bold);
  }
  .completeness-info p {
    margin: 2px 0 0 0;
    font-size: var(--text-caption);
    color: var(--text-secondary);
  }

  /* Settings Navigation Tabs */
  .settings-tabs {
    display: flex;
    gap: var(--space-2);
    border-bottom: 1px solid var(--border-default);
    margin-bottom: var(--space-6);
    padding-bottom: var(--space-1);
    overflow-x: auto;
  }
  .settings-tab-btn {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    font-size: var(--text-body-sm);
    font-weight: var(--weight-medium);
    padding: var(--space-3) var(--space-4);
    cursor: pointer;
    border-radius: var(--radius-lg);
    transition: all 0.2s var(--ease-default);
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: var(--space-2);
  }
  .settings-tab-btn:hover {
    color: var(--text-primary);
    background: var(--bg-secondary);
  }
  .settings-tab-btn.active {
    color: var(--action-primary);
    background: var(--color-primary-light);
  }
  [data-theme="dark"] .settings-tab-btn.active {
    background: rgba(255, 90, 0, 0.15);
  }

  /* Form Elements */
  .form-group-premium {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
    margin-bottom: var(--space-5);
  }
  .form-label-premium {
    font-size: var(--text-body-sm);
    font-weight: var(--weight-semibold);
    color: var(--text-secondary);
  }
  .form-input-premium {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    background: var(--bg-secondary);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-lg);
    color: var(--text-primary);
    font-size: var(--text-body-sm);
    font-family: inherit;
    transition: all 0.2s var(--ease-default);
  }
  .form-input-premium:focus {
    outline: none;
    border-color: var(--border-focus);
    background: var(--bg-surface);
    box-shadow: 0 0 0 3px rgba(255, 90, 0, 0.15);
  }
  .form-input-premium:disabled {
    background: var(--bg-primary);
    opacity: 0.7;
    cursor: not-allowed;
  }
  .password-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }
  .password-wrapper .form-input-premium {
    padding-inline-end: 44px;
  }
  .eye-toggle-btn {
    position: absolute;
    right: 12px;
    background: transparent;
    border: none;
    color: var(--text-tertiary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-1);
    transition: color 0.2s;
  }
  [dir="rtl"] .eye-toggle-btn {
    right: auto;
    left: 12px;
  }
  .eye-toggle-btn:hover {
    color: var(--text-primary);
  }

  /* Tab Panels */
  .tab-panel {
    display: none;
    opacity: 0;
    transform: translateY(12px);
    transition: opacity 0.3s var(--ease-out), transform 0.3s var(--ease-out);
  }
  .tab-panel.active {
    display: block;
    opacity: 1;
    transform: translateY(0);
  }

  /* Chips and Selectors */
  .sector-chips-grid {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-2);
  }
  .sector-chip {
    padding: var(--space-2) var(--space-4);
    background: var(--bg-secondary);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-full);
    color: var(--text-secondary);
    font-size: var(--text-body-sm);
    cursor: pointer;
    transition: all 0.2s var(--ease-default);
  }
  .sector-chip:hover {
    border-color: var(--action-primary);
    color: var(--text-primary);
  }
  .sector-chip.active {
    background: var(--color-primary-light);
    border-color: var(--action-primary);
    color: var(--action-primary);
    font-weight: var(--weight-medium);
  }
  [data-theme="dark"] .sector-chip.active {
    background: rgba(255, 90, 0, 0.15);
  }

  /* Risk Cards */
  .risk-cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--space-4);
  }
  .risk-card {
    border: 1px solid var(--border-default);
    background: var(--bg-secondary);
    border-radius: var(--radius-lg);
    padding: var(--space-4);
    cursor: pointer;
    transition: all 0.2s var(--ease-default);
    position: relative;
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
  }
  .risk-card:hover {
    border-color: var(--border-strong);
    background: var(--bg-surface);
  }
  .risk-card.active {
    border-color: var(--action-primary);
    background: var(--bg-surface);
    box-shadow: 0 0 0 3px rgba(255, 90, 0, 0.1);
  }
  .risk-card-title {
    font-size: var(--text-body-sm);
    font-weight: var(--weight-bold);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: var(--space-2);
  }
  .risk-card-desc {
    font-size: var(--text-caption);
    color: var(--text-secondary);
    line-height: var(--leading-normal);
  }
  .risk-card .checked-icon {
    position: absolute;
    top: 12px;
    right: 12px;
    color: var(--action-primary);
    display: none;
  }
  [dir="rtl"] .risk-card .checked-icon {
    right: auto;
    left: 12px;
  }
  .risk-card.active .checked-icon {
    display: block;
  }

  /* Ticket size */
  .ticket-size-container {
    background: var(--bg-secondary);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-lg);
    padding: var(--space-4) var(--space-5);
  }
  .ticket-size-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-3);
  }
  .ticket-size-range {
    -webkit-appearance: none;
    width: 100%;
    height: 6px;
    border-radius: var(--radius-full);
    background: var(--border-default);
    outline: none;
    margin: var(--space-3) 0;
  }
  .ticket-size-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--action-primary);
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(255,90,0,0.3);
    transition: transform 0.1s;
  }
  .ticket-size-range::-webkit-slider-thumb:hover {
    transform: scale(1.2);
  }

  /* KYC Checklist */
  .kyc-checklist {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
  }
  .kyc-item {
    background: var(--bg-secondary);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-lg);
    padding: var(--space-4);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .kyc-item-left {
    display: flex;
    align-items: center;
    gap: var(--space-3);
  }
  .kyc-item-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-md);
    background: var(--bg-surface);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    border: 1px solid var(--border-default);
  }
  .kyc-item.verified .kyc-item-icon {
    color: var(--color-success);
    background: var(--color-success-bg);
    border-color: transparent;
  }
  .kyc-item-info h5 {
    margin: 0;
    font-size: var(--text-body-sm);
    font-weight: var(--weight-bold);
  }
  .kyc-item-info p {
    margin: 2px 0 0 0;
    font-size: var(--text-caption);
    color: var(--text-secondary);
  }
  .kyc-badge {
    padding: 4px 10px;
    border-radius: var(--radius-full);
    font-size: 11px;
    font-weight: var(--weight-semibold);
  }
  .kyc-badge.verified {
    background: var(--color-success-bg);
    color: var(--color-success);
  }
  .kyc-badge.pending {
    background: var(--color-warning-bg);
    color: var(--color-warning);
  }
  .kyc-badge.unloaded {
    background: var(--border-default);
    color: var(--text-secondary);
  }

  /* Switch */
  .switch-container {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
  }
  .switch-container input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  .switch-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--border-default);
    transition: .3s;
    border-radius: 24px;
  }
  .switch-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
  }
  .switch-container input:checked + .switch-slider {
    background-color: var(--color-success);
  }
  .switch-container input:checked + .switch-slider:before {
    transform: translateX(20px);
  }
  [dir="rtl"] .switch-slider:before {
    left: auto;
    right: 3px;
  }
  [dir="rtl"] .switch-container input:checked + .switch-slider:before {
    transform: translateX(-20px);
  }

  /* Activity Logs Table */
  .logs-table-container {
    overflow-x: auto;
  }
  .logs-table {
    width: 100%;
    border-collapse: collapse;
    font-size: var(--text-body-sm);
  }
  .logs-table th {
    text-align: start;
    padding: var(--space-3) var(--space-4);
    border-bottom: 2px solid var(--border-default);
    color: var(--text-secondary);
    font-weight: var(--weight-bold);
  }
  .logs-table td {
    padding: var(--space-4);
    border-bottom: 1px solid var(--border-subtle);
    color: var(--text-primary);
  }
  .logs-table tr:last-child td {
    border-bottom: none;
  }

  /* Manager Card & Scheduler */
  .manager-contact-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--space-2);
    margin-bottom: var(--space-5);
  }
  .manager-contact-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    padding: var(--space-3) var(--space-2);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-lg);
    background: var(--bg-surface);
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 11px;
    text-decoration: none;
    transition: all 0.2s var(--ease-default);
  }
  .manager-contact-btn:hover {
    border-color: var(--action-primary);
    color: var(--action-primary);
    background: var(--color-primary-light);
    transform: translateY(-2px);
  }
  [data-theme="dark"] .manager-contact-btn:hover {
    background: rgba(255, 90, 0, 0.15);
  }
  
  .time-slots-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-2);
    margin-top: var(--space-2);
  }
  .time-slot-btn {
    padding: var(--space-2) var(--space-3);
    background: var(--bg-secondary);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-md);
    font-size: var(--text-caption);
    color: var(--text-secondary);
    cursor: pointer;
    text-align: center;
    transition: all 0.2s;
  }
  .time-slot-btn:hover {
    border-color: var(--border-strong);
    color: var(--text-primary);
  }
  .time-slot-btn.active {
    background: var(--color-primary-light);
    border-color: var(--action-primary);
    color: var(--action-primary);
    font-weight: var(--weight-bold);
  }
  [data-theme="dark"] .time-slot-btn.active {
    background: rgba(255, 90, 0, 0.15);
  }

  /* Booking Success Overlay State */
  .booking-success-state {
    text-align: center;
    padding: var(--space-6) var(--space-4);
    animation: scaleUp 0.3s var(--ease-bounce) forwards;
  }
  @keyframes scaleUp {
    from { opacity:0; transform: scale(0.9); }
    to { opacity:1; transform: scale(1); }
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

  /* Two Factor Verification Modal */
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
    transition: opacity 0.3s;
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

  /* Button Spinner */
  .btn-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
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

  /* Responsive Grid column adjustments */
  @media(max-width: 992px) {
    .grid-12 > div {
      grid-column: span 12 !important;
    }
  }
</style>

<div class="grid-12 fade-in" style="gap:var(--space-6)">
  
  <!-- Left Column (span 8) -->
  <div style="grid-column: span 8; display: flex; flex-direction: column; gap: var(--space-6)">
    
    <!-- Top Profile Banner & Completeness Indicator -->
    <div class="profile-banner">
      <div class="profile-banner-left">
        <div class="profile-avatar-container">
          <!-- Avatar container, defaults to initials representation unless uploaded -->
          <div class="profile-avatar-img" id="main-avatar-display">KA</div>
          <div class="profile-avatar-overlay" id="change-photo-trigger">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom:4px"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            <span>{{ app()->getLocale() == 'ar' ? 'تغيير الصورة' : 'Change Photo' }}</span>
          </div>
          <input type="file" id="avatar-upload-input" style="display:none" accept="image/*">
        </div>
        <div>
          <h3 class="text-h4 mb-1" style="font-weight:var(--weight-bold)" id="user-display-name">
            {{ app()->getLocale() == 'ar' ? 'خالد الدوسري' : 'Khalid Al-Dosari' }}
          </h3>
          <p class="text-body-sm text-secondary d-flex items-center gap-2" style="margin:0">
            <span class="badge badge-primary" style="border-radius:var(--radius-full); font-size: 11px; padding: 2px 8px">{{ app()->getLocale() == 'ar' ? 'مستثمر' : 'Investor' }}</span>
            <span style="opacity:0.4">•</span>
            <span>{{ app()->getLocale() == 'ar' ? 'عضو منذ يناير 2024' : 'Member since Jan 2024' }}</span>
          </p>
        </div>
      </div>

      <!-- Completeness Ring Container -->
      <div class="completeness-container">
        <div class="completeness-wrapper">
          <svg class="progress-ring" width="60" height="60">
            <circle stroke="var(--border-subtle)" stroke-width="5" fill="transparent" r="25" cx="30" cy="30"/>
            <circle class="progress-ring__circle" id="completeness-circle" stroke="var(--action-primary)" stroke-width="5" fill="transparent" r="25" cx="30" cy="30" stroke-dasharray="157.1" stroke-dashoffset="157.1"/>
          </svg>
          <span class="completeness-num" id="completeness-val">85%</span>
        </div>
        <div class="completeness-info">
          <h4>{{ app()->getLocale() == 'ar' ? 'اكتمال الملف' : 'Profile Strength' }}</h4>
          <p>{{ app()->getLocale() == 'ar' ? 'متبقي خطوة واحدة فقط!' : 'Only one step remaining!' }}</p>
        </div>
      </div>
    </div>

    <!-- Main Card containing Settings Content -->
    <div class="card" style="padding:var(--space-6); border-radius:var(--radius-xl); box-shadow:var(--shadow-sm)">
      
      <!-- Nav Tabs -->
      <div class="settings-tabs">
        <button class="settings-tab-btn active" data-tab="panel-personal">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'المعلومات الشخصية' : 'Personal Details' }}</span>
        </button>
        <button class="settings-tab-btn" data-tab="panel-investment">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'الملف الاستثماري' : 'Investment Profile' }}</span>
        </button>
        <button class="settings-tab-btn" data-tab="panel-security">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'الأمان والحماية' : 'Security' }}</span>
        </button>
      </div>

      <!-- Tab Content Panels -->
      <div class="profile-panel-container">
        
        <!-- Panel 1: Personal Info -->
        <div class="tab-panel active" id="panel-personal">
          <h4 class="text-h5 mb-2" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'المعلومات الشخصية' : 'Personal Details' }}</h4>
          <p class="text-body-sm text-secondary mb-6">{{ app()->getLocale() == 'ar' ? 'تعديل بيانات الاتصال الأساسية للملف التعريفي الخاص بك.' : 'Manage your primary contact information and personal details.' }}</p>
          
          <form id="personal-info-form">
            <div class="grid-2" style="gap:var(--space-4)">
              <div class="form-group-premium">
                <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'الاسم الأول' : 'First Name' }}</label>
                <input type="text" class="form-input-premium" id="first-name-input" value="{{ app()->getLocale() == 'ar' ? 'خالد' : 'Khalid' }}" required>
              </div>
              <div class="form-group-premium">
                <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'اسم العائلة' : 'Last Name' }}</label>
                <input type="text" class="form-input-premium" id="last-name-input" value="{{ app()->getLocale() == 'ar' ? 'الدوسري' : 'Al-Dosari' }}" required>
              </div>
            </div>
            
            <div class="grid-2" style="gap:var(--space-4)">
              <div class="form-group-premium">
                <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                <div style="position:relative; display:flex; align-items:center">
                  <input type="email" class="form-input-premium" value="khalid@example.com" disabled style="padding-inline-end: 36px">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="var(--text-tertiary)" stroke-width="2" style="position:absolute; right:12px"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
              </div>
              <div class="form-group-premium">
                <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'رقم الجوال' : 'Phone Number' }}</label>
                <input type="tel" class="form-input-premium" id="phone-input" value="+966 50 123 4567" required>
              </div>
            </div>

            <div class="grid-2" style="gap:var(--space-4)">
              <div class="form-group-premium">
                <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'اللغة المفضلة' : 'Language Preference' }}</label>
                <select class="form-input-premium" id="lang-pref-select" style="cursor:pointer">
                  <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>العربية (Arabic)</option>
                  <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English (الإنجليزية)</option>
                </select>
              </div>
              <div class="form-group-premium">
                <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'المنطقة الزمنية' : 'Timezone' }}</label>
                <input type="text" class="form-input-premium" value="Riyadh, Saudi Arabia (GMT+3)" disabled>
              </div>
            </div>

            <button type="submit" class="btn btn-primary" id="personal-submit-btn" style="margin-top:var(--space-4); border-radius:var(--radius-lg); padding:var(--space-3) var(--space-6)">
              {{ app()->getLocale() == 'ar' ? 'حفظ التغييرات' : 'Save Changes' }}
            </button>
          </form>
        </div>

        <!-- Panel 2: Investment Profile / KYC -->
        <div class="tab-panel" id="panel-investment">
          <h4 class="text-h5 mb-2" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'تفضيلات الملف الاستثماري والتوثيق' : 'Investment Profile & KYC' }}</h4>
          <p class="text-body-sm text-secondary mb-6">{{ app()->getLocale() == 'ar' ? 'إدارة القطاعات المفضلة لديك وحجم التذاكر الاستثمارية ورفع مستندات إثبات الهوية.' : 'Manage target investment sectors, ticket sizes, and verify credentials.' }}</p>

          <div class="d-flex flex-col gap-6">
            
            <!-- Target Sectors -->
            <div class="form-group-premium">
              <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'القطاعات الاستثمارية المستهدفة' : 'Target Investment Sectors' }}</label>
              <div class="sector-chips-grid">
                <div class="sector-chip active" data-sector="FinTech">{{ app()->getLocale() == 'ar' ? 'التقنية المالية (FinTech)' : 'FinTech' }}</div>
                <div class="sector-chip active" data-sector="PropTech">{{ app()->getLocale() == 'ar' ? 'التقنية العقارية (PropTech)' : 'PropTech' }}</div>
                <div class="sector-chip active" data-sector="AI">{{ app()->getLocale() == 'ar' ? 'الذكاء الاصطناعي (AI)' : 'Artificial Intelligence' }}</div>
                <div class="sector-chip" data-sector="HealthTech">{{ app()->getLocale() == 'ar' ? 'التقنية الصحية (HealthTech)' : 'HealthTech' }}</div>
                <div class="sector-chip" data-sector="EdTech">{{ app()->getLocale() == 'ar' ? 'تقنيات التعليم (EdTech)' : 'EdTech' }}</div>
                <div class="sector-chip" data-sector="Logistics">{{ app()->getLocale() == 'ar' ? 'الخدمات اللوجستية (Logistics)' : 'Logistics' }}</div>
                <div class="sector-chip" data-sector="CleanTech">{{ app()->getLocale() == 'ar' ? 'التقنية النظيفة (CleanTech)' : 'CleanTech' }}</div>
                <div class="sector-chip" data-sector="Web3">Web3 & Blockchain</div>
              </div>
            </div>

            <!-- Risk Tolerance Grid -->
            <div class="form-group-premium">
              <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'مستوى تحمل المخاطر للمحفظة' : 'Risk Tolerance Profile' }}</label>
              <div class="risk-cards-grid">
                <div class="risk-card" data-risk="low">
                  <span class="checked-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                  <div class="risk-card-title">{{ app()->getLocale() == 'ar' ? 'منخفض (Conservative)' : 'Conservative' }}</div>
                  <div class="risk-card-desc">{{ app()->getLocale() == 'ar' ? 'التركيز التام على حفظ رأس المال بعوائد هادئة.' : 'Focus on capital preservation and lower risk.' }}</div>
                </div>
                <div class="risk-card active" data-risk="moderate">
                  <span class="checked-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                  <div class="risk-card-title">{{ app()->getLocale() == 'ar' ? 'متوسط (Balanced)' : 'Moderate' }}</div>
                  <div class="risk-card-desc">{{ app()->getLocale() == 'ar' ? 'نمو متوازن للمحفظة مع مخاطر مقبولة.' : 'Balanced growth path with medium volatility.' }}</div>
                </div>
                <div class="risk-card" data-risk="high">
                  <span class="checked-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                  <div class="risk-card-title">{{ app()->getLocale() == 'ar' ? 'مرتفع (Aggressive)' : 'Aggressive' }}</div>
                  <div class="risk-card-desc">{{ app()->getLocale() == 'ar' ? 'استهداف العوائد الكبيرة جداً مع تحمل تقلبات عالية.' : 'Seeking maximum growth with high risk limits.' }}</div>
                </div>
              </div>
            </div>

            <!-- Investment Ticket Size Slider -->
            <div class="form-group-premium">
              <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'حجم الصفقة الاستثمارية المستهدفة' : 'Target Ticket Size Range' }}</label>
              <div class="ticket-size-container">
                <div class="ticket-size-header">
                  <span class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'نطاق الاستثمار للمشروع الواحد' : 'Per project allocation range' }}</span>
                  <span class="text-body-sm" id="ticket-value-display" style="font-weight:var(--weight-bold); color:var(--action-primary)">
                    {{ app()->getLocale() == 'ar' ? '250 ألف دولار' : '$250K' }}
                  </span>
                </div>
                <input type="range" class="ticket-size-range" id="ticket-size-range" min="10" max="1000" step="10" value="250">
                <div class="d-flex justify-between text-caption text-secondary">
                  <span>$10K</span>
                  <span>$500K</span>
                  <span>$1M+</span>
                </div>
              </div>
            </div>

            <hr style="border:none; border-top: 1px solid var(--border-default); margin:var(--space-2) 0">

            <!-- KYC Documents Checklist -->
            <div class="form-group-premium">
              <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'حالة التوثيق والمستندات (KYC)' : 'KYC verification & Documents' }}</label>
              <div class="kyc-checklist">
                <!-- Doc 1: Verified -->
                <div class="kyc-item verified">
                  <div class="kyc-item-left">
                    <div class="kyc-item-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div class="kyc-item-info">
                      <h5>{{ app()->getLocale() == 'ar' ? 'الهوية الوطنية / جواز السفر' : 'National ID / Passport' }}</h5>
                      <p class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'تم التحقق التلقائي · تنتهي في يناير 2028' : 'Verified automatically · Expires Jan 2028' }}</p>
                    </div>
                  </div>
                  <span class="kyc-badge verified">{{ app()->getLocale() == 'ar' ? 'موثق' : 'Verified' }}</span>
                </div>
                
                <!-- Doc 2: Verified -->
                <div class="kyc-item verified">
                  <div class="kyc-item-left">
                    <div class="kyc-item-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="kyc-item-info">
                      <h5>{{ app()->getLocale() == 'ar' ? 'إثبات العنوان السكني' : 'Proof of Address' }}</h5>
                      <p class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'فاتورة خدمات / إثبات العنوان الوطني موثق' : 'Utility Bill / National Address verified' }}</p>
                    </div>
                  </div>
                  <span class="kyc-badge verified">{{ app()->getLocale() == 'ar' ? 'موثق' : 'Verified' }}</span>
                </div>

                <!-- Doc 3: Not Uploaded (Interactive) -->
                <div class="kyc-item" id="kyc-doc-item">
                  <div class="kyc-item-left">
                    <div class="kyc-item-icon" id="kyc-doc-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div class="kyc-item-info">
                      <h5>{{ app()->getLocale() == 'ar' ? 'تصريح المستثمر المؤهل' : 'Accredited Investor Declaration' }}</h5>
                      <p class="text-caption text-secondary" id="kyc-doc-subtitle">{{ app()->getLocale() == 'ar' ? 'مطلوب للمشاركة في الصفقات الخاصة' : 'Required to participate in private placements' }}</p>
                    </div>
                  </div>
                  <div class="d-flex items-center gap-3">
                    <span class="kyc-badge unloaded" id="kyc-doc-badge">{{ app()->getLocale() == 'ar' ? 'غير مرفوع' : 'Not Uploaded' }}</span>
                    <button type="button" class="btn btn-sm" id="kyc-upload-trigger" style="background:var(--bg-surface); border:1px solid var(--border-default); border-radius:var(--radius-md); padding:var(--space-2) var(--space-3)">
                      {{ app()->getLocale() == 'ar' ? 'رفع الملف' : 'Upload File' }}
                    </button>
                    <input type="file" id="kyc-file-input" style="display:none" accept=".pdf,.png,.jpg,.jpeg">
                  </div>
                </div>

              </div>
            </div>

            <button type="button" class="btn btn-primary" id="kyc-save-btn" style="align-self:flex-start; margin-top:var(--space-2); border-radius:var(--radius-lg); padding:var(--space-3) var(--space-6)">
              {{ app()->getLocale() == 'ar' ? 'حفظ تفضيلات الاستثمار' : 'Save Investment Profile' }}
            </button>
          </div>
        </div>

        <!-- Panel 3: Security & Credentials -->
        <div class="tab-panel" id="panel-security">
          <h4 class="text-h5 mb-2" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'الأمان وبيانات الدخول' : 'Security Settings' }}</h4>
          <p class="text-body-sm text-secondary mb-6">{{ app()->getLocale() == 'ar' ? 'تحديث كلمة المرور وإدارة آليات الأمان الثنائية لحماية حسابك.' : 'Update your password and manage login authentication preferences.' }}</p>

          <form id="security-form" class="d-flex flex-col gap-5">
            <div class="form-group-premium">
              <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'كلمة المرور الحالية' : 'Current Password' }}</label>
              <div class="password-wrapper">
                <input type="password" class="form-input-premium" placeholder="••••••••" required>
                <button type="button" class="eye-toggle-btn">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="var(--text-tertiary)" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>
            
            <div class="grid-2" style="gap:var(--space-4); margin:0">
              <div class="form-group-premium">
                <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'كلمة المرور الجديدة' : 'New Password' }}</label>
                <div class="password-wrapper">
                  <input type="password" class="form-input-premium" id="new-pwd-input" placeholder="••••••••" required>
                  <button type="button" class="eye-toggle-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="var(--text-tertiary)" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
              </div>
              <div class="form-group-premium">
                <label class="form-label-premium">{{ app()->getLocale() == 'ar' ? 'تأكيد كلمة المرور الجديدة' : 'Confirm New Password' }}</label>
                <div class="password-wrapper">
                  <input type="password" class="form-input-premium" id="confirm-pwd-input" placeholder="••••••••" required>
                  <button type="button" class="eye-toggle-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="var(--text-tertiary)" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary" id="security-submit-btn" style="align-self:flex-start; border-radius:var(--radius-lg); padding:var(--space-3) var(--space-6)">
              {{ app()->getLocale() == 'ar' ? 'تحديث كلمة المرور' : 'Update Password' }}
            </button>
          </form>

          <hr style="border:none; border-top: 1px solid var(--border-default); margin:var(--space-6) 0">

          <!-- 2FA Block -->
          <div class="d-flex items-center justify-between" style="background:var(--bg-secondary); padding:var(--space-4) var(--space-5); border-radius:var(--radius-lg); border:1px solid var(--border-default)">
            <div>
              <h5 class="text-body-sm" style="font-weight:var(--weight-bold); margin:0">{{ app()->getLocale() == 'ar' ? 'المصادقة الثنائية (2FA)' : 'Two-Factor Authentication (2FA)' }}</h5>
              <p class="text-caption text-secondary" style="margin:2px 0 0 0" id="tfa-status-desc">
                {{ app()->getLocale() == 'ar' ? 'المصادقة الثنائية غير مفعلة حالياً على هذا الحساب.' : 'Two-factor authentication is currently disabled.' }}
              </p>
            </div>
            <div class="d-flex items-center gap-3">
              <span class="kyc-badge unloaded" id="tfa-badge">{{ app()->getLocale() == 'ar' ? 'غير مفعل' : 'Disabled' }}</span>
              <label class="switch-container">
                <input type="checkbox" id="two-factor-toggle">
                <span class="switch-slider"></span>
              </label>
            </div>
          </div>
        </div>



      </div>
    </div>
  </div>

  <!-- Right Column (span 4) -->
  <div style="grid-column: span 4; display: flex; flex-direction: column; gap: var(--space-6)">
    
    <!-- Account Manager Details Widget -->
    <div class="card" style="padding:var(--space-6); border-radius:var(--radius-xl); background:linear-gradient(135deg, var(--bg-surface) 0%, rgba(255,90,0,0.02) 100%); box-shadow:var(--shadow-sm); border:1px solid var(--border-default)">
      
      <div class="d-flex items-center gap-3 mb-6" style="border-bottom:1px solid var(--border-subtle); padding-bottom:var(--space-4)">
        <div style="width:36px; height:36px; border-radius:var(--radius-md); background:var(--color-primary-light); color:var(--action-primary); display:flex; align-items:center; justify-content:center">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h4 class="text-h6" style="font-weight:var(--weight-bold); margin:0">{{ app()->getLocale() == 'ar' ? 'مدير حسابك' : 'Your Account Manager' }}</h4>
      </div>

      <!-- Manager Headshot -->
      <div class="text-center mb-6">
        <div style="width:90px; height:90px; border-radius:50%; background:url('https://i.pravatar.cc/150?img=11') center/cover; margin:0 auto 16px; border:3px solid var(--bg-surface); box-shadow:0 8px 20px rgba(0,0,0,0.1)"></div>
        <h5 class="text-h5 mb-1" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'فهد آل سعود' : 'Fahad Al-Saud' }}</h5>
        <p class="text-caption text-secondary" style="margin:0">{{ app()->getLocale() == 'ar' ? 'مدير أول علاقات المستثمرين' : 'Senior Investor Relations Manager' }}</p>
      </div>

      <!-- Quick Contact Grid -->
      <div class="manager-contact-grid">
        <a href="mailto:fahad@seventech.com" class="manager-contact-btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'إيميل' : 'Email' }}</span>
        </a>
        <a href="tel:+966501234567" class="manager-contact-btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'اتصال' : 'Call' }}</span>
        </a>
        <a href="https://wa.me/966501234567" target="_blank" class="manager-contact-btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.455 5.703 1.458h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413"/></svg>
          <span>WhatsApp</span>
        </a>
      </div>

      <!-- Quick Scheduler Area -->
      <div style="background:var(--bg-secondary); border-radius:var(--radius-lg); padding:var(--space-4); border:1px solid var(--border-default)" id="scheduler-widget-container">
        <h5 class="text-body-sm mb-3" style="font-weight:var(--weight-bold); margin-top:0">{{ app()->getLocale() == 'ar' ? 'جدولة استشارة سريعة' : 'Quick Consultation Scheduler' }}</h5>
        
        <form id="quick-booking-form">
          <div class="form-group-premium" style="margin-bottom:var(--space-3)">
            <label class="form-label-premium" style="font-size:11px">{{ app()->getLocale() == 'ar' ? 'تاريخ الاستشارة' : 'Preferred Date' }}</label>
            <input type="date" id="booking-date-input" class="form-input-premium" style="padding:var(--space-2) var(--space-3); font-size:12px; background:var(--bg-surface)" required>
          </div>

          <div class="form-group-premium" style="margin-bottom:var(--space-4)">
            <label class="form-label-premium" style="font-size:11px">{{ app()->getLocale() == 'ar' ? 'الوقت المتاح' : 'Available Time Slots' }}</label>
            <div class="time-slots-grid">
              <button type="button" class="time-slot-btn" data-time="10:00 AM">10:00 AM</button>
              <button type="button" class="time-slot-btn" data-time="11:30 AM">11:30 AM</button>
              <button type="button" class="time-slot-btn" data-time="02:00 PM">02:00 PM</button>
              <button type="button" class="time-slot-btn" data-time="03:30 PM">03:30 PM</button>
            </div>
            <input type="hidden" id="booking-time-input" required>
          </div>

          <button type="submit" class="btn btn-primary w-full" id="book-meeting-submit" style="padding:var(--space-2) var(--space-4); font-size:12px; border-radius:var(--radius-md)">
            {{ app()->getLocale() == 'ar' ? 'تأكيد وحجز اللقاء' : 'Confirm & Schedule' }}
          </button>
        </form>
      </div>

    </div>

    <!-- Extra Promo/Security info card -->
    <div class="card" style="padding:var(--space-5); border-radius:var(--radius-xl); border:1px solid var(--border-default); box-shadow:var(--shadow-sm); display:flex; flex-direction:column; gap:var(--space-2)">
      <h5 class="text-body-sm" style="font-weight:var(--weight-bold); margin:0; display:flex; align-items:center; gap:var(--space-2)">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="var(--action-primary)" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        <span>{{ app()->getLocale() == 'ar' ? 'اتصال آمن ومشفر' : 'Secure Connection' }}</span>
      </h5>
      <p class="text-caption text-secondary" style="margin:0; line-height:1.4">
        {{ app()->getLocale() == 'ar' ? 'بياناتك الشخصية والمالية محمية بموجب بروتوكولات التشفير عالية الأمان ومعايير الحوكمة المالية.' : 'Your personal and financial profile parameters are strictly protected under advanced encryption protocols.' }}
      </p>
    </div>

  </div>

</div>

<!-- 2FA SETUP MODAL -->
<div class="modal-overlay" id="tfa-setup-modal">
  <div class="modal-box">
    <div class="d-flex justify-between items-center mb-4" style="border-bottom:1px solid var(--border-default); padding-bottom:var(--space-2)">
      <h4 class="text-h6" style="margin:0; font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'إعداد المصادقة الثنائية' : 'Setup Two-Factor Auth' }}</h4>
      <button type="button" class="btn-close-modal" id="tfa-modal-close" style="background:transparent; border:none; cursor:pointer; color:var(--text-secondary)">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    
    <p class="text-caption text-secondary mb-4" style="line-height:1.4">
      {{ app()->getLocale() == 'ar' ? 'امسح الرمز الشريطي (QR) باستخدام تطبيق المصادقة (Google Authenticator) ثم أدخل الرمز المكون من 6 أرقام للتحقق.' : 'Scan the QR code below using your authenticator app (Google Authenticator), then enter the 6-digit code to verify.' }}
    </p>

    <!-- QR Code SVG Representation -->
    <div style="text-align:center; padding:var(--space-4); background:white; border:1px solid var(--border-default); border-radius:var(--radius-lg); width:fit-content; margin:0 auto var(--space-4)">
      <!-- Animated / structured QR Code representation in CSS/SVG -->
      <svg width="140" height="140" viewBox="0 0 100 100" style="display:block">
        <rect width="100" height="100" fill="white"/>
        <!-- Corners -->
        <rect x="5" y="5" width="25" height="25" fill="black"/>
        <rect x="9" y="9" width="17" height="17" fill="white"/>
        <rect x="13" y="13" width="9" height="9" fill="black"/>

        <rect x="70" y="5" width="25" height="25" fill="black"/>
        <rect x="74" y="9" width="17" height="17" fill="white"/>
        <rect x="78" y="13" width="9" height="9" fill="black"/>

        <rect x="5" y="70" width="25" height="25" fill="black"/>
        <rect x="9" y="74" width="17" height="17" fill="white"/>
        <rect x="13" y="78" width="9" height="9" fill="black"/>
        
        <!-- Random QR code bits -->
        <rect x="40" y="5" width="5" height="10" fill="black"/>
        <rect x="50" y="10" width="10" height="5" fill="black"/>
        <rect x="45" y="20" width="5" height="15" fill="black"/>
        <rect x="60" y="15" width="5" height="10" fill="black"/>

        <rect x="35" y="45" width="15" height="5" fill="black"/>
        <rect x="55" y="40" width="5" height="15" fill="black"/>
        <rect x="45" y="55" width="10" height="10" fill="black"/>
        
        <rect x="70" y="40" width="10" height="5" fill="black"/>
        <rect x="85" y="45" width="10" height="10" fill="black"/>
        <rect x="75" y="60" width="5" height="5" fill="black"/>
        <rect x="80" y="70" width="15" height="5" fill="black"/>
        <rect x="70" y="80" width="5" height="15" fill="black"/>
        <rect x="85" y="85" width="10" height="5" fill="black"/>
      </svg>
    </div>

    <div class="text-center mb-4">
      <span class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'مفتاح الإعداد اليدوي:' : 'Secret setup key:' }}</span>
      <code style="display:block; padding:4px 8px; background:var(--bg-secondary); border-radius:var(--radius-sm); font-size:12px; margin-top:4px; font-weight:var(--weight-bold); letter-spacing:1px">STC-SECURE-KEY-2026</code>
    </div>

    <!-- 6 digit verification input -->
    <div class="form-group-premium">
      <label class="form-label-premium text-center" style="font-size:12px">{{ app()->getLocale() == 'ar' ? 'رمز التحقق (6 أرقام)' : 'Verification Code (6-Digits)' }}</label>
      <input type="text" id="tfa-verification-code" class="form-input-premium text-center" style="letter-spacing:4px; font-size:16px; font-weight:var(--weight-bold)" placeholder="000000" maxlength="6">
    </div>

    <div class="d-flex gap-3 mt-4">
      <button type="button" class="btn btn-ghost w-full" id="tfa-btn-cancel" style="border:1px solid var(--border-default); border-radius:var(--radius-lg)">
        {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
      </button>
      <button type="button" class="btn btn-primary w-full" id="tfa-btn-verify" style="border-radius:var(--radius-lg)">
        {{ app()->getLocale() == 'ar' ? 'تحقق وتفعيل' : 'Verify & Enable' }}
      </button>
    </div>
  </div>
</div>

<!-- Global Toast Container -->
<div class="toast-container" id="profile-toast-container"></div>

<!-- Custom Page Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const locale = "{{ app()->getLocale() }}";

  // --- Toast Notification Manager ---
  function showToast(message, type = 'success') {
    const container = document.getElementById('profile-toast-container');
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

  // --- Animate Completeness Circle ---
  const ring = document.getElementById('completeness-circle');
  if (ring) {
    const r = ring.r.baseVal.value;
    const circ = 2 * Math.PI * r;
    ring.style.strokeDasharray = `${circ} ${circ}`;
    
    // Set 85% strength
    setTimeout(() => {
      const offset = circ - (0.85 * circ);
      ring.style.strokeDashoffset = offset;
    }, 400);
  }

  // --- Settings Tab Navigator ---
  const tabs = document.querySelectorAll('.settings-tab-btn');
  const panels = document.querySelectorAll('.tab-panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.tab;
      
      tabs.forEach(t => t.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));
      
      tab.classList.add('active');
      const targetPanel = document.getElementById(target);
      if (targetPanel) {
        targetPanel.classList.add('active');
      }
    });
  });

  // --- Password Visibility Toggles ---
  document.querySelectorAll('.eye-toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.previousElementSibling;
      if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="var(--text-secondary)" stroke-width="2">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
            <line x1="1" y1="1" x2="23" y2="23"/>
          </svg>
        `;
      } else {
        input.type = 'password';
        btn.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="var(--text-tertiary)" stroke-width="2">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
        `;
      }
    });
  });

  // --- Avatar Image Upload Simulation ---
  const avatarTrigger = document.getElementById('change-photo-trigger');
  const avatarInput = document.getElementById('avatar-upload-input');
  const avatarDisplays = [
    document.getElementById('main-avatar-display'),
    ...document.querySelectorAll('.sidebar-avatar')
  ];

  if (avatarTrigger && avatarInput) {
    avatarTrigger.addEventListener('click', () => avatarInput.click());
    
    avatarInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        if (!file.type.startsWith('image/')) {
          showToast(locale === 'ar' ? 'يرجى اختيار ملف صورة صالح.' : 'Please select a valid image file.', 'error');
          return;
        }

        const reader = new FileReader();
        reader.onload = function(evt) {
          const base64Img = evt.target.result;
          
          // Show simulated upload spinner
          const mainDisplay = document.getElementById('main-avatar-display');
          mainDisplay.style.background = 'transparent';
          mainDisplay.innerHTML = `<span class="btn-spinner" style="border-top-color:var(--action-primary); margin:0"></span>`;
          
          setTimeout(() => {
            avatarDisplays.forEach(display => {
              if (display) {
                display.style.background = `url('${base64Img}') center/cover`;
                display.innerHTML = '';
                display.style.color = 'transparent';
              }
            });
            showToast(locale === 'ar' ? 'تم تحديث الصورة الشخصية بنجاح.' : 'Profile picture updated successfully.');
          }, 1200);
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // --- Tab 1: Save Personal Info Form ---
  const personalForm = document.getElementById('personal-info-form');
  const personalBtn = document.getElementById('personal-submit-btn');
  
  if (personalForm && personalBtn) {
    personalForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const originalText = personalBtn.textContent.trim();
      personalBtn.disabled = true;
      personalBtn.innerHTML = `<span class="btn-spinner"></span>${locale === 'ar' ? 'جاري الحفظ...' : 'Saving...'}`;

      const fName = document.getElementById('first-name-input').value;
      const lName = document.getElementById('last-name-input').value;

      setTimeout(() => {
        personalBtn.disabled = false;
        personalBtn.textContent = originalText;
        
        // Update name displays on page
        const fullName = `${fName} ${lName}`;
        const nameNode = document.getElementById('user-display-name');
        if (nameNode) nameNode.textContent = fullName;
        
        const sidebarNameNode = document.querySelector('.sidebar-user-info .text-label');
        if (sidebarNameNode) sidebarNameNode.textContent = fullName;
        
        showToast(locale === 'ar' ? 'تم حفظ التعديلات بنجاح.' : 'Changes saved successfully.');
      }, 1000);
    });
  }

  // --- Tab 2: Sector Chips Toggle ---
  document.querySelectorAll('.sector-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      chip.classList.toggle('active');
    });
  });

  // --- Tab 2: Risk Tolerance Selection ---
  const riskCards = document.querySelectorAll('.risk-card');
  riskCards.forEach(card => {
    card.addEventListener('click', () => {
      riskCards.forEach(c => c.classList.remove('active'));
      card.classList.add('active');
    });
  });

  // --- Tab 2: Ticket Size Slider ---
  const ticketSlider = document.getElementById('ticket-size-range');
  const ticketDisplay = document.getElementById('ticket-value-display');
  
  if (ticketSlider && ticketDisplay) {
    ticketSlider.addEventListener('input', (e) => {
      const val = parseInt(e.target.value);
      if (val >= 1000) {
        ticketDisplay.textContent = locale === 'ar' ? '1 مليون دولار+' : '$1M+';
      } else {
        ticketDisplay.textContent = locale === 'ar' ? `${val} ألف دولار` : `$${val}K`;
      }
    });
  }

  // --- Tab 2: KYC Accredited Declaration Upload ---
  const kycTrigger = document.getElementById('kyc-upload-trigger');
  const kycInput = document.getElementById('kyc-file-input');
  const kycBadge = document.getElementById('kyc-doc-badge');
  const kycItem = document.getElementById('kyc-doc-item');
  const kycSubtitle = document.getElementById('kyc-doc-subtitle');

  if (kycTrigger && kycInput && kycBadge) {
    kycTrigger.addEventListener('click', () => kycInput.click());
    kycInput.addEventListener('change', (e) => {
      if (e.target.files[0]) {
        kycTrigger.disabled = true;
        kycTrigger.innerHTML = `<span class="btn-spinner" style="border-top-color:var(--text-primary); margin:0"></span>`;
        
        setTimeout(() => {
          kycBadge.className = 'kyc-badge pending';
          kycBadge.textContent = locale === 'ar' ? 'قيد المراجعة' : 'Under Review';
          
          if (kycItem) kycItem.classList.add('verified'); // green-ish icon border
          if (kycSubtitle) kycSubtitle.textContent = locale === 'ar' ? 'تم الرفع وجاري مراجعة المستند حالياً' : 'File uploaded and is under verification';
          
          kycTrigger.style.display = 'none';
          showToast(locale === 'ar' ? 'تم رفع مستند التوثيق بنجاح.' : 'Verification document uploaded successfully.');
        }, 1500);
      }
    });
  }

  // --- Tab 2: KYC Preferences Save ---
  const kycSaveBtn = document.getElementById('kyc-save-btn');
  if (kycSaveBtn) {
    kycSaveBtn.addEventListener('click', () => {
      const originalText = kycSaveBtn.textContent.trim();
      kycSaveBtn.disabled = true;
      kycSaveBtn.innerHTML = `<span class="btn-spinner"></span>${locale === 'ar' ? 'جاري الحفظ...' : 'Saving...'}`;
      
      setTimeout(() => {
        kycSaveBtn.disabled = false;
        kycSaveBtn.textContent = originalText;
        showToast(locale === 'ar' ? 'تم تحديث التفضيلات الاستثمارية بنجاح.' : 'Investment preferences updated successfully.');
      }, 1000);
    });
  }

  // --- Tab 3: Security Submit Form ---
  const securityForm = document.getElementById('security-form');
  const securityBtn = document.getElementById('security-submit-btn');

  if (securityForm && securityBtn) {
    securityForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const newPwd = document.getElementById('new-pwd-input').value;
      const confirmPwd = document.getElementById('confirm-pwd-input').value;
      
      if (newPwd !== confirmPwd) {
        showToast(locale === 'ar' ? 'عذراً، كلمتا المرور الجديدتان غير متطابقتين.' : 'New passwords do not match.', 'error');
        return;
      }
      
      const originalText = securityBtn.textContent.trim();
      securityBtn.disabled = true;
      securityBtn.innerHTML = `<span class="btn-spinner"></span>${locale === 'ar' ? 'جاري التحديث...' : 'Updating...'}`;
      
      setTimeout(() => {
        securityBtn.disabled = false;
        securityBtn.textContent = originalText;
        securityForm.reset();
        showToast(locale === 'ar' ? 'تم تحديث كلمة المرور بنجاح.' : 'Password updated successfully.');
      }, 1500);
    });
  }

  // --- Tab 3: 2FA Toggle & Setup Modal ---
  const tfaToggle = document.getElementById('two-factor-toggle');
  const tfaModal = document.getElementById('tfa-setup-modal');
  const tfaCancel = document.getElementById('tfa-btn-cancel');
  const tfaVerify = document.getElementById('tfa-btn-verify');
  const tfaBadge = document.getElementById('tfa-badge');
  const tfaDesc = document.getElementById('tfa-status-desc');

  function openTfaModal() {
    tfaModal.classList.add('show');
    document.getElementById('tfa-verification-code').value = '';
  }
  function closeTfaModal() {
    tfaModal.classList.remove('show');
  }

  if (tfaToggle) {
    tfaToggle.addEventListener('change', (e) => {
      if (e.target.checked) {
        openTfaModal();
      } else {
        // Disable 2FA
        tfaBadge.className = 'kyc-badge unloaded';
        tfaBadge.textContent = locale === 'ar' ? 'غير مفعل' : 'Disabled';
        tfaDesc.textContent = locale === 'ar' ? 'المصادقة الثنائية غير مفعلة حالياً على هذا الحساب.' : 'Two-factor authentication is currently disabled.';
        showToast(locale === 'ar' ? 'تم إلغاء تفعيل المصادقة الثنائية.' : 'Two-factor authentication disabled.');
      }
    });
  }

  const tfaClose = document.getElementById('tfa-modal-close');
  if (tfaClose) tfaClose.addEventListener('click', () => {
    closeTfaModal();
    tfaToggle.checked = false;
  });
  
  if (tfaCancel) tfaCancel.addEventListener('click', () => {
    closeTfaModal();
    tfaToggle.checked = false;
  });

  if (tfaVerify) {
    tfaVerify.addEventListener('click', () => {
      const codeInput = document.getElementById('tfa-verification-code').value;
      if (codeInput.length < 6) {
        showToast(locale === 'ar' ? 'يرجى إدخال رمز صحيح من 6 أرقام.' : 'Please enter a valid 6-digit code.', 'error');
        return;
      }
      
      const originalText = tfaVerify.textContent.trim();
      tfaVerify.disabled = true;
      tfaVerify.innerHTML = `<span class="btn-spinner"></span>${locale === 'ar' ? 'جاري التحقق...' : 'Verifying...'}`;
      
      setTimeout(() => {
        tfaVerify.disabled = false;
        tfaVerify.textContent = originalText;
        
        closeTfaModal();
        tfaToggle.checked = true;
        
        // Update 2FA badges & text
        tfaBadge.className = 'kyc-badge verified';
        tfaBadge.textContent = locale === 'ar' ? 'مفعل' : 'Enabled';
        tfaDesc.textContent = locale === 'ar' ? 'المصادقة الثنائية مفعلة بنجاح، حسابك محمي برموز أمان إضافية.' : 'Two-factor authentication is active. Your account is secured.';
        
        showToast(locale === 'ar' ? 'تم تفعيل المصادقة الثنائية (2FA) بنجاح.' : 'Two-factor authentication activated successfully.');
      }, 1200);
    });
  }

  // --- Right Column: Meeting Quick Scheduler ---
  const timeBtns = document.querySelectorAll('.time-slot-btn');
  const timeInput = document.getElementById('booking-time-input');
  
  timeBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      timeBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      timeInput.value = btn.dataset.time;
    });
  });

  // Prevent selecting past dates for calendar scheduler
  const dateInput = document.getElementById('booking-date-input');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
  }

  const bookingForm = document.getElementById('quick-booking-form');
  const bookBtn = document.getElementById('book-meeting-submit');
  const widgetContainer = document.getElementById('scheduler-widget-container');

  if (bookingForm && bookBtn && widgetContainer) {
    bookingForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const bDate = dateInput.value;
      const bTime = timeInput.value;
      
      if (!bDate || !bTime) {
        showToast(locale === 'ar' ? 'يرجى تحديد التاريخ والوقت المفضليْن.' : 'Please select both date and time slot.', 'error');
        return;
      }
      
      const originalText = bookBtn.textContent.trim();
      bookBtn.disabled = true;
      bookBtn.innerHTML = `<span class="btn-spinner"></span>${locale === 'ar' ? 'جاري الحجز...' : 'Scheduling...'}`;
      
      setTimeout(() => {
        showToast(locale === 'ar' ? 'تم جدولة استشارتك بنجاح!' : 'Your consultation has been scheduled successfully!');
        
        // Transform the widget area into a success checkmark block
        const formattedDate = new Date(bDate).toLocaleDateString(locale === 'ar' ? 'ar-SA' : 'en-US', {
          weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
        
        widgetContainer.innerHTML = `
          <div class="booking-success-state">
            <div style="width:48px; height:48px; border-radius:50%; background:var(--color-success-bg); color:var(--color-success); display:flex; align-items:center; justify-content:center; margin:0 auto 12px">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h5 class="text-body" style="font-weight:var(--weight-bold); margin:0 0 8px 0">${locale === 'ar' ? 'تم الحجز بنجاح!' : 'Consultation Scheduled!'}</h5>
            <p class="text-caption text-secondary" style="margin:0 0 16px 0; line-height:1.4">
              ${locale === 'ar' ? `تم حجز موعد مع مدير حسابك فهد آل سعود في <strong>${formattedDate}</strong> الساعة <strong>${bTime}</strong>.` : `Your booking with Fahad Al-Saud is confirmed for <strong>${formattedDate}</strong> at <strong>${bTime}</strong>.`}
            </p>
            <button type="button" class="btn btn-sm btn-ghost" onclick="window.location.href='{{ url('/dashboard/consultations') }}'" style="border:1px solid var(--border-default); border-radius:var(--radius-md); font-size:11px; padding:var(--space-2) var(--space-4)">
              ${locale === 'ar' ? 'عرض قائمة الاستشارات' : 'View Consultations'}
            </button>
          </div>
        `;
  const clearLogsBtn = document.getElementById('clear-logs-btn');
  if (clearLogsBtn) {
    clearLogsBtn.addEventListener('click', () => {
      const tbody = document.querySelector('.logs-table tbody');
      if (tbody) {
        tbody.style.transition = 'opacity 0.5s ease';
        tbody.style.opacity = '0';
      }
      setTimeout(() => {
        localStorage.setItem('profile_logs_cleared', 'true');
        localStorage.setItem('profile_logs', JSON.stringify([]));
        renderLogs();
        showToast(locale === 'ar' ? 'تم مسح سجل النشاط بنجاح.' : 'Activity log cleared successfully.');
      }, tbody ? 500 : 50);
    });
  }

});
</script>
@endsection