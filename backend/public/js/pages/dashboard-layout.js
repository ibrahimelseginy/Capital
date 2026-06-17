/**
 * SEVEN TECH CAPITAL — Dashboard Layout & Shared Components
 */

import LangManager from '../language.js';

export function dashboardLayout(title, role, activeTab, content) {
  const isAr = LangManager.currentLang === 'ar';

  // No general tabs — only investor and entrepreneur

  const investorTabs = [
    ['overview', isAr ? 'نظرة عامة' : 'Overview', '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>'],
    ['projects', isAr ? 'مشاريع المحفظة' : 'Portfolio Projects', '<rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/>'],
    ['reports', isAr ? 'التقارير' : 'Reports', '<line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/>'],
    ['documents', isAr ? 'المستندات' : 'Documents', '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>'],
    ['ndas', isAr ? 'مركز NDA' : 'NDA Center', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
    ['exit-requests', isAr ? 'طلبات الخروج' : 'Exit Requests', '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>'],
    ['exit-records', isAr ? 'سجلات الخروج' : 'Exit Records', '<path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/>'],
    ['consultations', isAr ? 'الاستشارات' : 'Consultations', '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
    ['events', isAr ? 'الفعاليات' : 'Events', '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/>'],
    ['profile', isAr ? 'الملف الشخصي' : 'Profile & Security', '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
  ];

  const entrepreneurTabs = [
    ['overview', isAr ? 'نظرة عامة' : 'Overview', '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>'],
    ['my-projects', isAr ? 'مشاريعي' : 'My Projects', '<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>'],
    ['applications', isAr ? 'الطلبات' : 'Applications', '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>'],
    ['progress', isAr ? 'تتبع التقدم' : 'Progress Tracking', '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/>'],
    ['reports', isAr ? 'التقارير' : 'Reports', '<line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/>'],
    ['documents', isAr ? 'المستندات' : 'Documents', '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>'],
    ['ndas', isAr ? 'مركز NDA' : 'NDA Center', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
    ['meetings', isAr ? 'الاجتماعات' : 'Meetings', '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
    ['exit-records', isAr ? 'سجلات الخروج' : 'Exit Records', '<rect width="20" height="14" x="2" y="6" rx="2"/>'],
    ['profile', isAr ? 'الملف الشخصي' : 'Profile & Security', '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
  ];

  const adminTabs = [
    ['overview', isAr ? 'نظرة عامة' : 'Overview', '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>'],
    ['users', isAr ? 'المستخدمين' : 'Users Management', '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
    ['projects', isAr ? 'المشاريع والطلبات' : 'Projects & Apps', '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>'],
    ['ndas', isAr ? 'اتفاقيات السرية' : 'NDA Tracking', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
    ['content', isAr ? 'إدارة المحتوى' : 'Content & Blogs', '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>'],
    ['profile', isAr ? 'إعدادات النظام' : 'System Settings', '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
  ];

  const tabs = role === 'admin' ? adminTabs : (role === 'entrepreneur' ? entrepreneurTabs : investorTabs);
  const basePath = role === 'admin' ? '/dashboard/admin' : (role === 'entrepreneur' ? '/dashboard/entrepreneur' : '/dashboard/investor');

  const userName = role === 'admin' ? (isAr ? 'مدير النظام' : 'System Admin') : (role === 'entrepreneur' ? (isAr ? 'سارة التميمي' : 'Sarah Al-Tamimi') : (isAr ? 'خالد الدوسري' : 'Khalid Al-Dosari'));
  const userInitials = role === 'admin' ? 'AD' : (role === 'entrepreneur' ? 'SA' : 'KA');
  const roleLabel = role === 'admin' ? (isAr ? 'مسؤول' : 'Administrator') : (role === 'entrepreneur' ? (isAr ? 'رائد أعمال' : 'Entrepreneur') : (isAr ? 'مستثمر' : 'Investor'));

  // Today's date formatted
  const now = new Date();
  const dateStr = isAr 
    ? now.toLocaleDateString('ar-SA', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
    : now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

  return `
  <div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
      <div class="sidebar-header">
        <a href="#/"><img src="Group 97.png" alt="STC" height="28" id="sidebar-logo"></a>
      </div>
      <div class="sidebar-user" onclick="window.location.hash='${basePath}/profile'">
        <div class="sidebar-avatar">${userInitials}</div>
        <div class="sidebar-user-info">
          <div class="text-label">${userName}</div>
          <div class="text-caption text-secondary" style="text-transform:capitalize">${roleLabel}</div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="2" style="margin-inline-start:auto;flex-shrink:0"><path d="m9 18 6-6-6-6"/></svg>
      </div>
      <nav class="sidebar-nav">
        ${tabs.map(([id, label, iconPath]) => `
        <a href="#${basePath}/${id}" class="sidebar-link ${activeTab === id ? 'active' : ''}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${iconPath}</svg>
          <span>${label}</span>
          ${id === 'invitations' ? `<span style="margin-inline-start:auto;background:var(--action-primary);color:#fff;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;flex-shrink:0">2</span>` : ''}
        </a>`).join('')}
      </nav>
      <div class="sidebar-footer">
        <a href="#/" class="sidebar-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
          <span>${isAr ? 'تسجيل الخروج' : 'Sign Out'}</span>
        </a>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">
      <header class="dashboard-topbar">
        <div class="d-flex items-center gap-4">
          <button class="dashboard-menu-toggle" onclick="document.querySelector('.dashboard-sidebar').classList.toggle('mobile-open')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
          </button>
          <div>
            <h1 class="text-h4" style="margin:0;line-height:1.2">${title}</h1>
            <p class="text-caption text-tertiary" style="margin:0;margin-top:2px">${dateStr}</p>
          </div>
        </div>
        <div class="d-flex items-center gap-3">
          <!-- Search -->
          <div style="position:relative;display:none" class="topbar-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="2" style="position:absolute;top:50%;transform:translateY(-50%);left:12px;pointer-events:none"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" placeholder="${isAr ? 'بحث...' : 'Search...'}" style="width:200px;padding:8px 12px 8px 36px;background:var(--bg-secondary);border:1px solid var(--border-default);border-radius:var(--radius-full);font-size:13px;color:var(--text-primary);outline:none;transition:all 0.2s ease" onfocus="this.style.width='260px';this.style.borderColor='var(--action-primary)';this.style.boxShadow='0 0 0 3px rgba(255,90,0,0.1)'" onblur="this.style.width='200px';this.style.borderColor='var(--border-default)';this.style.boxShadow='none'">
          </div>

          <a href="#${basePath}/notifications" class="header-action-btn" aria-label="Notifications" style="position:relative;display:flex;align-items:center;justify-content:center;color:inherit;text-decoration:none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            <span style="position:absolute;top:6px;right:6px;width:8px;height:8px;background:var(--color-error);border-radius:50%;border:2px solid var(--bg-surface)"></span>
          </a>
          <button class="header-action-btn" data-action="toggle-theme" aria-label="Toggle theme">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
          </button>
          <button class="lang-toggle" data-action="toggle-lang">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
            <span class="lang-label">عربي</span>
          </button>
          <div class="sidebar-avatar" style="width:36px;height:36px;font-size:12px;cursor:pointer" onclick="window.location.hash='${basePath}/profile'">${userInitials}</div>
        </div>
      </header>
      <div class="dashboard-content">
        ${content}
      </div>
    </main>
  </div>`;
}
