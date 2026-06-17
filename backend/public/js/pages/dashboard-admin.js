/**
 * SEVEN TECH CAPITAL — Admin Dashboard
 * System management: Users, Projects, NDAs, Content
 */
import LangManager from '../language.js';
import { dashboardLayout } from './dashboard-layout.js';

// ─── Shared helpers ───
function adminMetricCard(value, label, iconSvg, color = 'var(--action-primary)', change = '') {
  const changeHtml = change ? `<div class="metric-card-change positive" style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:${change.startsWith('↓') ? 'var(--color-error-bg)' : 'var(--color-success-bg)'};color:${change.startsWith('↓') ? 'var(--color-error)' : 'var(--color-success)'}">${change}</div>` : '';
  return `
    <div class="metric-card" style="padding:var(--space-6);border-radius:var(--radius-xl);border:1px solid var(--border-default);background:var(--bg-surface);position:relative;overflow:hidden;transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);cursor:pointer" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 40px rgba(0,0,0,0.1)';this.style.borderColor='${color}'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';this.style.borderColor='var(--border-default)'">
      <div style="position:absolute;top:0;right:0;width:120px;height:120px;background:radial-gradient(circle, ${color}10 0%, transparent 70%);pointer-events:none"></div>
      <div class="d-flex justify-between items-start" style="position:relative;z-index:2">
        <div>
          <div class="text-caption text-secondary mb-2" style="font-weight:var(--weight-semibold);text-transform:uppercase;letter-spacing:1.2px;font-size:11px">${label}</div>
          <div class="metric-card-value" style="font-size:2rem;line-height:1;letter-spacing:-1px">${value}</div>
          ${changeHtml}
        </div>
        <div style="width:52px;height:52px;border-radius:var(--radius-lg);background:${color}12;display:flex;align-items:center;justify-content:center;color:${color}">
          ${iconSvg}
        </div>
      </div>
    </div>`;
}

function t(en, ar) { return LangManager.currentLang === 'ar' ? ar : en; }

// ─── TABS ───

function overview() {
  return `
    <div class="d-flex justify-between items-center mb-6">
      <h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('System Overview', 'نظرة عامة على النظام')}</h2>
      <div class="d-flex gap-3">
        <button class="btn btn-secondary btn-sm" style="border-radius:var(--radius-lg)">${t('Generate Report', 'استخراج تقرير')}</button>
      </div>
    </div>

    <!-- Top Metrics -->
    <div class="grid-4 mb-8" style="gap:var(--space-6)">
      ${adminMetricCard('1,248', t('Total Users', 'إجمالي المستخدمين'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', 'var(--action-primary)', '↑ 12%')}
      ${adminMetricCard('34', t('Pending Apps', 'طلبات معلقة'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>', '#3b82f6')}
      ${adminMetricCard('$45.2M', t('Managed Funds', 'الأموال المدارة'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>', 'var(--color-success)', '↑ $2.1M')}
      ${adminMetricCard('128', t('Active NDAs', 'اتفاقيات نشطة'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>', 'var(--accent-gold)')}
    </div>

    <!-- Recent Activity -->
    <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl);border:1px solid var(--border-default)">
      <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">${t('Recent Platform Activity', 'أحدث نشاطات المنصة')}</h3>
      <div class="d-flex flex-col">
        ${[
          ['New Investor Registered', 'مستثمر جديد مسجل', 'Khaled Al-Faisal', '10 mins ago', 'var(--action-primary)'],
          ['Project Application Submitted', 'تقديم طلب مشروع جديد', 'TechVision AI', '2 hours ago', '#3b82f6'],
          ['NDA Signed', 'تم توقيع اتفاقية', 'Sarah Al-Tamimi', '5 hours ago', 'var(--color-success)'],
          ['Exit Request Initiated', 'طلب تخارج جديد', 'FinFlow Project', '1 day ago', 'var(--accent-gold)']
        ].map(([en, ar, sub, time, color]) => `
        <div class="d-flex gap-4 py-3 activity-row" style="padding-inline:var(--space-3);border-radius:var(--radius-md);cursor:pointer;transition:background 0.2s" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
          <div style="width:10px;height:10px;border-radius:50%;background:${color};margin-top:6px;flex-shrink:0"></div>
          <div style="flex:1">
            <div class="text-label">${t(en,ar)}</div>
            <div class="text-caption text-secondary mt-1">${sub}</div>
          </div>
          <div class="text-caption text-tertiary" style="background:var(--bg-secondary);padding:2px 10px;border-radius:var(--radius-full);align-self:center;white-space:nowrap">${time}</div>
        </div>`).join('')}
      </div>
    </div>`;
}

function users() {
  return `
    <div class="d-flex justify-between items-center mb-6">
      <h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Users Management', 'إدارة المستخدمين')}</h2>
      <div class="d-flex gap-3">
        <div style="position:relative">
          <input type="text" placeholder="${t('Search users...','ابحث عن مستخدمين...')}" style="padding:8px 16px;padding-inline-start:36px;border-radius:var(--radius-full);border:1px solid var(--border-default);background:var(--bg-secondary);outline:none;font-size:13px;width:240px">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="2" style="position:absolute;top:50%;transform:translateY(-50%);${LangManager.currentLang==='ar'?'right:12px':'left:12px'};pointer-events:none"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
      </div>
    </div>
    
    <div class="table-wrapper" style="border-radius:var(--radius-xl)">
      <table class="table">
        <thead>
          <tr>
            <th>${t('Name','الاسم')}</th>
            <th>${t('Email','البريد الإلكتروني')}</th>
            <th>${t('Role','الدور')}</th>
            <th>${t('Status','الحالة')}</th>
            <th>${t('Joined','تاريخ الانضمام')}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          ${[
            ['Khalid Al-Dosari', 'khalid@example.com', t('Investor','مستثمر'), t('Active','نشط'), 'Jan 2024'],
            ['Sarah Al-Tamimi', 'sarah@example.com', t('Entrepreneur','رائد أعمال'), t('Active','نشط'), 'Feb 2024'],
            ['Ahmed Zaki', 'ahmed.z@example.com', t('Investor','مستثمر'), t('Pending KYC','بانتظار KYC'), 'Jun 2026'],
            ['Omar Hassan', 'omar@techvision.io', t('Entrepreneur','رائد أعمال'), t('Suspended','موقوف'), 'Dec 2025']
          ].map(([name, email, role, status, joined]) => `
          <tr style="transition:all 0.2s" onmouseover="this.style.background='var(--action-ghost-hover)'" onmouseout="this.style.background=''">
            <td class="text-label" style="font-weight:var(--weight-semibold)">
              <div class="d-flex items-center gap-3">
                <div style="width:32px;height:32px;border-radius:50%;background:var(--action-primary);color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:bold">${name.charAt(0)}</div>
                ${name}
              </div>
            </td>
            <td class="text-secondary">${email}</td>
            <td><span class="badge badge-neutral" style="border-radius:var(--radius-full)">${role}</span></td>
            <td><span class="badge ${status===t('Active','نشط')?'badge-success':status===t('Suspended','موقوف')?'badge-error':'badge-warning'} badge-dot" style="border-radius:var(--radius-full)">${status}</span></td>
            <td class="text-secondary">${joined}</td>
            <td>
              <button class="btn btn-ghost btn-sm" style="color:var(--text-primary);border-radius:var(--radius-full)">${t('Manage','إدارة')}</button>
            </td>
          </tr>`).join('')}
        </tbody>
      </table>
    </div>`;
}

function projects() {
  return `
    <div class="d-flex justify-between items-center mb-6">
      <h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Projects & Applications', 'المشاريع والطلبات')}</h2>
    </div>
    
    <div class="table-wrapper" style="border-radius:var(--radius-xl)">
      <table class="table">
        <thead>
          <tr>
            <th>${t('Project Name','اسم المشروع')}</th>
            <th>${t('Founder','المؤسس')}</th>
            <th>${t('Sector','القطاع')}</th>
            <th>${t('Funding Ask','التمويل المطلوب')}</th>
            <th>${t('Status','الحالة')}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          ${[
            ['FinFlow Analytics', 'Sarah Al-Tamimi', 'FinTech', '$1.2M', t('Approved','معتمد')],
            ['HealthSync AI', 'Nasser Ali', 'HealthTech', '$500K', t('Under Review','قيد المراجعة')],
            ['LogiFlow Platform', 'Yousef H.', 'Logistics', '$800K', t('Approved','معتمد')],
            ['EcoBuild Materials', 'Fatima A.', 'CleanTech', '$2.5M', t('Rejected','مرفوض')]
          ].map(([name, founder, sector, ask, status]) => `
          <tr style="transition:all 0.2s" onmouseover="this.style.background='var(--action-ghost-hover)'" onmouseout="this.style.background=''">
            <td class="text-label" style="font-weight:var(--weight-semibold)">${name}</td>
            <td class="text-secondary">${founder}</td>
            <td><span class="badge badge-neutral" style="border-radius:var(--radius-full)">${sector}</span></td>
            <td style="font-weight:var(--weight-bold)">${ask}</td>
            <td><span class="badge ${status===t('Approved','معتمد')?'badge-success':status===t('Rejected','مرفوض')?'badge-error':'badge-warning'} badge-dot" style="border-radius:var(--radius-full)">${status}</span></td>
            <td>
              <button class="btn btn-ghost btn-sm" style="color:var(--action-primary);border-radius:var(--radius-full)">${t('Review','مراجعة')}</button>
            </td>
          </tr>`).join('')}
        </tbody>
      </table>
    </div>`;
}

function ndas() {
  return `
    <div class="d-flex justify-between items-center mb-6">
      <h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('NDA Tracking System', 'نظام تتبع اتفاقيات السرية')}</h2>
      <span class="badge badge-primary badge-dot" style="border-radius:var(--radius-full)">128 ${t('Active NDAs','اتفاقية نشطة')}</span>
    </div>
    
    <div class="card mb-6" style="padding:var(--space-5);border-radius:var(--radius-xl);background:var(--bg-secondary)">
      <p class="text-body-sm text-secondary">${t('Monitor all non-disclosure agreements signed across the platform by investors and auto-signed for entrepreneurs.','راقب جميع اتفاقيات السرية الموقعة عبر المنصة من قبل المستثمرين أو الموقعة تلقائياً لرواد الأعمال.')}</p>
    </div>

    <div class="table-wrapper" style="border-radius:var(--radius-xl)">
      <table class="table">
        <thead>
          <tr>
            <th>${t('Document','المستند')}</th>
            <th>${t('User','المستخدم')}</th>
            <th>${t('Type','النوع')}</th>
            <th>${t('Date Signed','تاريخ التوقيع')}</th>
            <th>${t('Status','الحالة')}</th>
          </tr>
        </thead>
        <tbody>
          ${[
            ['NDA — Project Alpha', 'Khalid Al-Dosari', t('Investor','مستثمر'), 'Jun 10, 2026', t('Active','نشط')],
            ['NDA — FinFlow Auto-Sign', 'Sarah Al-Tamimi', t('Entrepreneur','رائد أعمال'), 'Jan 15, 2024', t('Active','نشط')],
            ['NDA — DataPulse', 'Ahmed Zaki', t('Investor','مستثمر'), 'Jun 8, 2026', t('Active','نشط')],
            ['NDA — LogiFlow', 'Khalid Al-Dosari', t('Investor','مستثمر'), 'Dec 2023', t('Expired','منتهي')]
          ].map(([doc, user, type, date, status]) => `
          <tr>
            <td class="text-label" style="font-weight:var(--weight-semibold)">
              <div class="d-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--action-primary)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                ${doc}
              </div>
            </td>
            <td>${user}</td>
            <td class="text-secondary">${type}</td>
            <td class="text-secondary">${date}</td>
            <td><span class="badge ${status===t('Active','نشط')?'badge-success':'badge-error'} badge-dot" style="border-radius:var(--radius-full)">${status}</span></td>
          </tr>`).join('')}
        </tbody>
      </table>
    </div>`;
}

function content() {
  return `
    <div class="d-flex justify-between items-center mb-6">
      <h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Content & Blogs', 'إدارة المحتوى والمقالات')}</h2>
      <button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">+ ${t('New Article', 'مقال جديد')}</button>
    </div>
    
    <div class="grid-3">
      ${[
        { title: 'The Future of AI in SaaS', views: '2.4K', status: t('Published','منشور') },
        { title: 'Investment Strategies 2026', views: '1.2K', status: t('Published','منشور') },
        { title: 'Building for Scale', views: '-', status: t('Draft','مسودة') }
      ].map(a => `
      <div class="card card-hover" style="padding:var(--space-5);border-radius:var(--radius-xl)">
        <span class="badge ${a.status===t('Published','منشور')?'badge-success':'badge-neutral'} mb-3">${a.status}</span>
        <h3 class="text-h6 mb-2">${a.title}</h3>
        <p class="text-caption text-secondary mb-4">${t('Views','المشاهدات')}: ${a.views}</p>
        <div class="d-flex gap-2 mt-auto">
          <button class="btn btn-secondary btn-sm flex-1" style="border-radius:var(--radius-lg)">${t('Edit','تعديل')}</button>
          <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg);color:var(--color-error)">${t('Delete','حذف')}</button>
        </div>
      </div>`).join('')}
    </div>`;
}

function profile() {
  return `
    <div class="d-flex justify-between items-center mb-6">
      <h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('System Settings', 'إعدادات النظام')}</h2>
    </div>
    <div class="card" style="padding:var(--space-8);border-radius:var(--radius-xl)">
      <div class="grid-2" style="gap:var(--space-6)">
        <div class="form-group">
          <label class="form-label">${t('Admin Name','اسم المسؤول')}</label>
          <input type="text" class="form-input" value="System Admin" readonly style="background:var(--bg-secondary);border-radius:var(--radius-lg)">
        </div>
        <div class="form-group">
          <label class="form-label">${t('Admin Email','البريد الإلكتروني')}</label>
          <input type="email" class="form-input" value="admin@stc.com" readonly style="background:var(--bg-secondary);border-radius:var(--radius-lg)">
        </div>
      </div>
      <div class="mt-8 pt-8" style="border-top:1px solid var(--border-default)">
        <button class="btn btn-primary" style="border-radius:var(--radius-lg)">${t('Save Configuration','حفظ الإعدادات')}</button>
      </div>
    </div>`;
}

export function adminDashboardPage(tab = 'overview') {
  const titles = { overview:t('System Overview','نظرة عامة على النظام'), users:t('Users Management','إدارة المستخدمين'), projects:t('Projects & Apps','المشاريع والطلبات'), ndas:t('NDA Tracking','اتفاقيات السرية'), content:t('Content & Blogs','إدارة المحتوى'), profile:t('System Settings','إعدادات النظام') };
  const contents = { overview, users, projects, ndas, content, profile };
  
  let contentHtml = '';
  if (contents[tab]) {
    contentHtml = contents[tab]();
  } else {
    contentHtml = '<div class="text-secondary">Loading...</div>';
  }

  return dashboardLayout(titles[tab] || t('Admin Dashboard','لوحة المسؤول'), 'admin', tab, contentHtml);
}
