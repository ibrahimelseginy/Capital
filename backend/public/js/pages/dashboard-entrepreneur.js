/**
 * SEVEN TECH CAPITAL — Entrepreneur Dashboard (Premium UX)
 * Overview, My Projects, Applications, Progress, Reports, Documents, NDAs (Auto-signed), Meetings, Exit Records, Profile
 */
import LangManager from '../language.js';
import { dashboardLayout } from './dashboard-layout.js';

const isAr = () => typeof LangManager !== 'undefined' && LangManager.currentLang === 'ar';
const t = (en, ar) => isAr() ? ar : en;

// ─── Shared helpers ───
function entMetricCard(value, label, iconSvg, color = 'var(--action-primary)', change = '') {
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

function overview() {
  return `
    <!-- Welcome Banner -->
    <div class="card mb-8 reveal" style="padding:var(--space-8) var(--space-10);background:linear-gradient(135deg, #0f0f0f 0%, #1a1510 40%, #1a1714 60%, #0f0f0f 100%);border:1px solid rgba(255,90,0,0.15);position:relative;overflow:hidden;border-radius:var(--radius-xl)">
      <div style="position:absolute;top:-60%;right:-15%;width:500px;height:500px;background:radial-gradient(circle,rgba(255,90,0,0.12) 0%,transparent 65%);pointer-events:none"></div>
      <div style="position:absolute;bottom:-40%;left:-5%;width:350px;height:350px;background:radial-gradient(circle,rgba(198,161,91,0.08) 0%,transparent 65%);pointer-events:none"></div>
      <div style="position:relative;z-index:2">
        <div class="text-caption mb-3" style="color:var(--action-primary);font-weight:var(--weight-bold);text-transform:uppercase;letter-spacing:3px;font-size:11px">${t('Founder Dashboard', 'لوحة المؤسس')}</div>
        <h2 class="text-h2 mb-3" style="color:#fff;font-weight:var(--weight-bold);letter-spacing:-0.5px;line-height:1.2">${t('Welcome back, Sarah', 'مرحباً بكِ، سارة')} 👋</h2>
        <p class="text-body" style="color:rgba(255,255,255,0.55);max-width:500px;line-height:1.6">${t("Here's your ventures progress and upcoming milestones.", 'إليك تقدم مشاريعك والمراحل القادمة.')}</p>
      </div>
    </div>

    <div class="grid-4 mb-8">
      ${entMetricCard('2', t('Active Projects', 'مشاريع نشطة'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg>', 'var(--action-primary)')}
      ${entMetricCard('78%', t('Milestone Progress', 'تقدم المراحل'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/></svg>', 'var(--color-success)', '↑ 12% this month')}
      ${entMetricCard('$1.2M', t('Funding Raised', 'التمويل المجموع'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>', '#3b82f6')}
      ${entMetricCard('4', t('Pending Actions', 'إجراءات معلقة'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>', 'var(--color-warning)')}
    </div>

    <div class="grid-12" style="gap:var(--space-6)">
      <div style="grid-column:span 8">
        <!-- Project Progress -->
        <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h3 class="text-h5 mb-5" style="font-weight:var(--weight-bold)">${t('Project Progress', 'تقدم المشاريع')}</h3>
          <div class="d-flex flex-col gap-5">
            ${[['FinFlow','78%',t('On Track','في المسار الصحيح')],['DataPulse','45%',t('Needs Attention','يحتاج اهتمام')]].map(([name, pct, status]) => `
            <div>
              <div class="d-flex justify-between items-center mb-2">
                <span class="text-label" style="font-weight:var(--weight-bold)">${name}</span>
                <span class="badge ${status===t('On Track','في المسار الصحيح')?'badge-success':'badge-warning'} badge-dot" style="border-radius:var(--radius-full)">${status}</span>
              </div>
              <div style="height:8px;background:var(--bg-secondary);border-radius:var(--radius-full);overflow:hidden">
                <div style="height:100%;width:${pct};background:${status===t('On Track','في المسار الصحيح')?'var(--color-success)':'var(--color-warning)'};border-radius:var(--radius-full);transition:width 0.6s ease;box-shadow:0 0 10px ${status===t('On Track','في المسار الصحيح')?'var(--color-success)':'var(--color-warning)'}"></div>
              </div>
              <div class="text-caption text-secondary mt-2">${pct} ${t('complete','مكتمل')}</div>
            </div>`).join('')}
          </div>
        </div>
        <!-- Recent Activity -->
        <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">${t('Recent Activity', 'النشاط الأخير')}</h3>
          ${[
            [t('Milestone "Beta Launch" completed for FinFlow','اكتمال مرحلة "الإطلاق التجريبي" لـ FinFlow'), t('2h ago','منذ ساعتين'), 'var(--color-success)'],
            [t('Meeting scheduled with investor committee','تم جدولة اجتماع مع لجنة المستثمرين'), t('1d ago','منذ يوم'), '#3b82f6'],
            [t('Document "Business Plan v3" uploaded','تم رفع مستند "خطة العمل الإصدار 3"'), t('2d ago','منذ يومين'), 'var(--accent-gold)'],
            [t('Application for DataPulse Phase 2 submitted','تم تقديم طلب لمرحلة 2 من DataPulse'), t('5d ago','منذ 5 أيام'), 'var(--action-primary)'],
          ].map(([msg, time, color]) => `
          <div class="d-flex gap-4 py-3 activity-row" style="padding-inline:var(--space-3);border-radius:var(--radius-md);cursor:pointer;transition:all 0.2s" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
            <div style="width:10px;height:10px;border-radius:50%;background:${color};margin-top:6px;flex-shrink:0;box-shadow:0 0 0 3px ${color}20"></div>
            <div class="flex-1 text-body-sm">${msg}</div>
            <div class="text-caption text-tertiary" style="background:var(--bg-secondary);padding:2px 10px;border-radius:var(--radius-full);align-self:center;white-space:nowrap">${time}</div>
          </div>`).join('')}
        </div>
      </div>
      <div style="grid-column:span 4">
        <!-- Deadlines -->
        <div class="card mb-5" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h4 class="text-label mb-5" style="font-weight:var(--weight-bold)">${t('Upcoming Deadlines', 'المواعيد النهائية القادمة')}</h4>
          <div class="d-flex flex-col gap-3">
            ${[[t('Monthly Report Due','تسليم التقرير الشهري'),'Jun 15',t('3 days','3 أيام'),'var(--color-error)'],[t('Investor Meeting','اجتماع المستثمرين'),'Jun 18',t('6 days','6 أيام'),'var(--color-warning)'],[t('Milestone Review','مراجعة المراحل'),'Jun 25',t('13 days','13 يوم'),'var(--color-success)']].map(([name, date, days, color]) => `
            <div class="d-flex justify-between items-center" style="padding:var(--space-3);border-radius:var(--radius-lg);transition:all 0.2s" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
              <div><div class="text-body-sm" style="font-weight:var(--weight-semibold)">${name}</div><div class="text-caption text-secondary mt-1">${date}</div></div>
              <span class="badge" style="background:${color}15;color:${color};border-radius:var(--radius-full);font-weight:var(--weight-bold)">${days}</span>
            </div>`).join('')}
          </div>
        </div>
        <!-- Quick Actions -->
        <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h4 class="text-label mb-4" style="font-weight:var(--weight-bold)">${t('Quick Actions', 'إجراءات سريعة')}</h4>
          <div class="d-flex flex-col gap-2">
            ${[
              [t('Submit Report','تقديم تقرير'),'#3b82f6','<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/>'],
              [t('Upload Document','رفع مستند'),'#10b981','<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/>'],
              [t('Request Meeting','طلب اجتماع'),'var(--action-primary)','<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
            ].map(([label, color, icon]) => `
            <button class="btn btn-ghost w-full justify-start gap-3" style="padding:var(--space-3) var(--space-4);border-radius:var(--radius-lg);transition:all 0.2s" onmouseover="this.style.background='${color}10';this.style.transform='translateX(${isAr()?-4:4}px)'" onmouseout="this.style.background='transparent';this.style.transform='translateX(0)'">
              <div style="width:32px;height:32px;border-radius:var(--radius-md);background:${color}15;color:${color};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">${icon}</svg>
              </div>
              <span class="text-body-sm" style="font-weight:var(--weight-semibold)">${label}</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="2" style="margin-inline-start:auto"><path d="m9 18 6-6-6-6"/></svg>
            </button>`).join('')}
          </div>
        </div>
      </div>
    </div>`;
}

function myProjects() {
  return `
    <h2 class="text-h4 mb-6" style="font-weight:var(--weight-bold)">${t('My Projects', 'مشاريعي')}</h2>
    <div class="d-flex flex-col gap-6">
      ${[
        ['FinFlow','FinTech',t('Active','نشط'),'78%','$800K','Series A','Jan 2024'],
        ['DataPulse','AI & Data',t('Building','بناء'),'45%','$400K','Seed','Jun 2025'],
      ].map(([name, cat, status, progress, funding, round, since]) => `
      <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl);transition:all 0.3s ease" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
        <div class="d-flex justify-between items-start mb-5">
          <div class="d-flex gap-4 items-center">
            <div style="width:64px;height:64px;border-radius:var(--radius-xl);background:var(--color-primary-lighter);display:flex;align-items:center;justify-content:center;color:var(--action-primary);font-weight:700;font-size:1.5rem;box-shadow:0 4px 12px rgba(255,90,0,0.1)">${name[0]}</div>
            <div>
              <h3 class="text-h4 mb-1" style="font-weight:var(--weight-bold)">${name}</h3>
              <div class="text-body-sm text-secondary">${cat} <span style="opacity:0.5">•</span> ${round} <span style="opacity:0.5">•</span> ${t('Since','منذ')} ${since}</div>
            </div>
          </div>
          <span class="badge ${status===t('Active','نشط')?'badge-success':'badge-primary'} badge-dot" style="border-radius:var(--radius-full);padding:6px 12px">${status}</span>
        </div>
        <div class="grid-4 mb-5" style="gap:var(--space-4);background:var(--bg-secondary);padding:var(--space-5);border-radius:var(--radius-lg)">
          <div><div class="text-caption text-secondary">${t('Progress','التقدم')}</div><div class="text-label" style="font-weight:var(--weight-bold);font-size:1.1rem">${progress}</div></div>
          <div><div class="text-caption text-secondary">${t('Funding','التمويل')}</div><div class="text-label" style="font-weight:var(--weight-bold);font-size:1.1rem">${funding}</div></div>
          <div><div class="text-caption text-secondary">${t('Round','الجولة')}</div><div class="text-label" style="font-weight:var(--weight-bold);font-size:1.1rem">${round}</div></div>
          <div><div class="text-caption text-secondary">${t('Team','الفريق')}</div><div class="text-label" style="font-weight:var(--weight-bold);font-size:1.1rem">${name==='FinFlow'?'12':'6'} ${t('members','أعضاء')}</div></div>
        </div>
        <div style="height:10px;background:var(--bg-secondary);border-radius:var(--radius-full);overflow:hidden;margin-bottom:var(--space-5)">
          <div style="height:100%;width:${progress};background:var(--color-success);border-radius:var(--radius-full);box-shadow:0 0 10px var(--color-success)"></div>
        </div>
        <div class="d-flex gap-3">
          <button class="btn btn-secondary btn-sm" style="border-radius:var(--radius-lg)">${t('Milestones','المراحل')}</button>
          <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)">${t('Reports','التقارير')}</button>
          <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)">${t('Team','الفريق')}</button>
          <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)">${t('Documents','المستندات')}</button>
        </div>
      </div>`).join('')}
    </div>`;
}

function applications() {
  return `
    <div class="d-flex justify-between items-center mb-6"><h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Applications', 'الطلبات')}</h2><button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">${t('New Application', 'طلب جديد')}</button></div>
    <div class="d-flex flex-col gap-3">
      ${[
        ['DataPulse — Phase 2 Expansion', t('Submitted Jun 5','قُدِّم في 5 يونيو'), t('Under Review','قيد المراجعة'), t('Expansion','توسع')],
        ['FinFlow — Additional Funding', t('Submitted Apr 10','قُدِّم في 10 أبريل'), t('Approved','موافق عليه'), t('Funding','تمويل')],
      ].map(([name, date, status, type]) => `
      <div class="card" style="padding:var(--space-5);display:flex;align-items:center;justify-content:space-between;border-radius:var(--radius-xl);transition:all 0.2s" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
        <div><div class="text-label" style="font-weight:var(--weight-bold)">${name}</div><div class="text-caption text-secondary mt-1">${date} <span style="opacity:0.5">•</span> ${type}</div></div>
        <div class="d-flex gap-3 items-center">
          <span class="badge ${status===t('Approved','موافق عليه')?'badge-success':status===t('Rejected','مرفوض')?'badge-error':'badge-warning'} badge-dot" style="border-radius:var(--radius-full)">${status}</span>
          <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)">${t('View','عرض')}</button>
        </div>
      </div>`).join('')}
    </div>`;
}

function progress() {
  return `
    <h2 class="text-h4 mb-2" style="font-weight:var(--weight-bold)">${t('Progress Tracking', 'تتبع التقدم')}</h2>
    <p class="text-body-sm text-secondary mb-6">${t('Track milestones and KPIs across your ventures.', 'تتبع المراحل ومؤشرات الأداء عبر مشاريعك.')}</p>
    <div class="tabs mb-6" style="border-bottom:1px solid var(--border-default)">
      <button class="tab active" data-tab="finflow" style="margin-bottom:-1px">FinFlow</button>
      <button class="tab" data-tab="datapulse" style="margin-bottom:-1px">DataPulse</button>
    </div>
    <div data-tab-content="finflow">
      <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
        <h3 class="text-h5 mb-5" style="font-weight:var(--weight-bold)">${t('Milestones', 'المراحل')}</h3>
        <div class="timeline" style="padding-inline-start:var(--space-4)">
          ${[
            [t('MVP Launch','إطلاق MVP'),t('Completed','مكتمل'),'Jan 2024',true],
            [t('1000 Users','1000 مستخدم'),t('Completed','مكتمل'),'Apr 2024',true],
            [t('Beta Launch','إطلاق تجريبي'),t('Completed','مكتمل'),'Jun 2024',true],
            ['Series A',t('Active','نشط'),t('In Progress','قيد التنفيذ'),false],
            [t('10K Users','10 آلاف مستخدم'),t('Upcoming','قادم'),'Q3 2026',false],
          ].map(([name, status, date, completed], i, arr) => `
          <div class="timeline-item" style="padding-bottom:${i<arr.length-1?'var(--space-6)':'0'}">
            <div class="timeline-marker">
              <div class="timeline-dot ${completed?'completed':status===t('Active','نشط')?'active':''}" style="width:16px;height:16px;border:2px solid ${completed?'var(--color-success)':status===t('Active','نشط')?'var(--action-primary)':'var(--border-strong)'};background:${completed?'var(--color-success)':'var(--bg-surface)'}"></div>
              ${i<arr.length-1?`<div class="timeline-line ${completed?'completed':''}" style="background:${completed?'var(--color-success)':'var(--border-default)'};width:2px;transform:translateX(-50%)"></div>`:''}
            </div>
            <div class="timeline-content" style="padding-inline-start:var(--space-5)">
              <div class="d-flex justify-between items-center mb-1">
                <div class="text-label" style="font-weight:var(--weight-bold);color:${completed||status===t('Active','نشط')?'var(--text-primary)':'var(--text-secondary)'}">${name}</div>
                <span class="badge ${completed?'badge-success':status===t('Active','نشط')?'badge-primary':'badge-neutral'} badge-dot" style="border-radius:var(--radius-full)">${status}</span>
              </div>
              <div class="text-caption text-secondary">${date}</div>
            </div>
          </div>`).join('')}
        </div>
      </div>
    </div>`;
}

function reportsTab() {
  return `
    <h2 class="text-h4 mb-6" style="font-weight:var(--weight-bold)">${t('Reports', 'التقارير')}</h2>
    <div class="d-flex justify-between items-center mb-6">
      <select class="form-input form-select" style="max-width:200px;border-radius:var(--radius-lg)"><option>${t('All Projects','كل المشاريع')}</option><option>FinFlow</option></select>
      <button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">${t('Submit New Report', 'تقديم تقرير جديد')}</button>
    </div>
    <div class="table-wrapper" style="border-radius:var(--radius-xl)">
      <table class="table"><thead><tr><th>${t('Report','التقرير')}</th><th>${t('Project','المشروع')}</th><th>${t('Period','الفترة')}</th><th>${t('Status','الحالة')}</th><th></th></tr></thead>
      <tbody>${[
        [t('Monthly Report — May','تقرير شهري — مايو'),'FinFlow','May 2026',t('Submitted','مُقدم')],
        [t('Monthly Report — Apr','تقرير شهري — أبريل'),'FinFlow','Apr 2026',t('Approved','موافق عليه')],
        [t('Initial Progress Report','تقرير التقدم الأولي'),'DataPulse','Jun 2025',t('Draft','مسودة')],
      ].map(([name, project, period, status]) => `
      <tr style="transition:all 0.2s" onmouseover="this.style.background='var(--action-ghost-hover)'" onmouseout="this.style.background=''"><td class="text-label" style="font-weight:var(--weight-semibold)">${name}</td><td>${project}</td><td class="text-secondary">${period}</td><td><span class="badge ${status===t('Approved','موافق عليه')?'badge-success':status===t('Draft','مسودة')?'badge-neutral':'badge-primary'} badge-dot" style="border-radius:var(--radius-full)">${status}</span></td><td><button class="btn btn-ghost btn-sm" style="color:var(--action-primary);border-radius:var(--radius-full)">${status===t('Draft','مسودة')?t('Edit','تعديل'):t('View','عرض')}</button></td></tr>`).join('')}
      </tbody></table>
    </div>`;
}

function documentsTab() {
  return `
    <div class="d-flex justify-between items-center mb-6"><h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Documents', 'المستندات')}</h2><button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">${t('Upload Document', 'رفع مستند')}</button></div>
    <div class="table-wrapper" style="border-radius:var(--radius-xl)">
      <table class="table"><thead><tr><th>${t('Document','المستند')}</th><th>${t('Project','المشروع')}</th><th>${t('Type','النوع')}</th><th>${t('Uploaded','تاريخ الرفع')}</th><th></th></tr></thead>
      <tbody>${[
        [t('Business Plan v3','خطة العمل الإصدار 3'),'FinFlow',t('Business Plan','خطة عمل'),'Jun 5, 2026'],
        [t('Technical Architecture','البنية التقنية'),'FinFlow',t('Technical','تقني'),'May 2026'],
      ].map(([name, project, type, date]) => `
      <tr style="transition:all 0.2s" onmouseover="this.style.background='var(--action-ghost-hover)'" onmouseout="this.style.background=''"><td class="d-flex gap-2 items-center"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/></svg><span class="text-label" style="font-weight:var(--weight-semibold)">${name}</span></td><td>${project}</td><td><span class="badge badge-neutral" style="border-radius:var(--radius-full)">${type}</span></td><td class="text-secondary">${date}</td><td><button class="btn btn-ghost btn-sm" style="color:var(--action-primary);border-radius:var(--radius-full)">${t('Download','تحميل')}</button></td></tr>`).join('')}
      </tbody></table>
    </div>`;
}

// ─── NDA Center — Auto-signed by Company ───
function ndasTab() {
  return `
    <div class="d-flex justify-between items-center mb-6">
      <div>
        <h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('NDA Center', 'مركز اتفاقيات السرية')}</h2>
        <p class="text-body-sm text-secondary mt-1">${t('Non-Disclosure Agreements for your ventures', 'اتفاقيات عدم الإفصاح لمشاريعك')}</p>
      </div>
    </div>

    <!-- Info Banner for Entrepreneur -->
    <div class="card mb-6" style="padding:var(--space-5);border:1px solid rgba(46,204,113,0.3);background:rgba(46,204,113,0.04);border-radius:var(--radius-xl)">
      <div class="d-flex gap-3 items-center">
        <div style="width:40px;height:40px;border-radius:var(--radius-lg);background:rgba(46,204,113,0.1);display:flex;align-items:center;justify-content:center;color:var(--color-success);flex-shrink:0">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <p class="text-body-sm">${t('For your protection, SEVEN TECH CAPITAL automatically signs an NDA for your projects upon submission. Your intellectual property is protected.', 'لحمايتك، تقوم شركة سفن تك كابيتال تلقائياً بتوقيع اتفاقية سرية (NDA) لمشاريعك عند تقديمها. حقوقك الفكرية محفوظة.')}</p>
      </div>
    </div>

    <div class="d-flex flex-col gap-4">
      ${[
        ['NDA — DataPulse Partner Agreement', t('Auto-Signed by Capital', 'موقّعة تلقائياً من الشركة'), 'Jun 5, 2026'],
        ['NDA — Investor Access for FinFlow', t('Auto-Signed by Capital', 'موقّعة تلقائياً من الشركة'), 'Jan 2024'],
        ['NDA — STC Solutions Partnership', t('Auto-Signed by Capital', 'موقّعة تلقائياً من الشركة'), 'Mar 2025']
      ].map(([name, status, date]) => `
      <div class="card" style="padding:var(--space-5);border-radius:var(--radius-xl);border:1px solid var(--border-default);transition:all 0.3s ease" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
        <div class="d-flex items-center justify-between gap-4">
          <div class="d-flex gap-4 items-center">
            <div style="width:44px;height:44px;border-radius:var(--radius-lg);background:var(--color-success-bg);display:flex;align-items:center;justify-content:center;color:var(--color-success)">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
              <div class="text-label" style="font-weight:var(--weight-bold)">${name}</div>
              <div class="text-caption text-secondary mt-1">${date}</div>
            </div>
          </div>
          <div class="d-flex gap-3 items-center">
            <span class="badge badge-success" style="border-radius:var(--radius-full);background:rgba(46,204,113,0.1);color:var(--color-success)">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-inline-end:4px"><polyline points="20 6 9 17 4 12"/></svg>
              ${status}
            </span>
            <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg);color:var(--action-primary)" onclick="
              event.stopPropagation();
              const modal = document.createElement('div');
              modal.style.cssText='position:fixed;inset:0;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;animation:fadeIn 0.2s ease';
              modal.innerHTML='<div style=\\'background:var(--bg-surface);border-radius:var(--radius-xl);max-width:600px;width:100%;max-height:80vh;overflow-y:auto;padding:var(--space-8);box-shadow:0 24px 56px rgba(0,0,0,0.3)\\'>'+
                '<div style=\\'display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-6)\\'>'+
                  '<h3 style=\\'font-size:1.25rem;font-weight:700\\'>${t('Agreement Preview','معاينة الاتفاقية')}</h3>'+
                  '<button onclick=\\'this.closest(\\\"div[style*=fixed]\\\").remove()\\' style=\\'width:36px;height:36px;border-radius:50%;border:1px solid var(--border-default);background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-primary)\\'>✕</button>'+
                '</div>'+
                '<div style=\\'background:var(--bg-secondary);border-radius:var(--radius-lg);padding:var(--space-6);margin-bottom:var(--space-6);font-size:14px;line-height:1.8;color:var(--text-secondary);position:relative\\'>'+
                  '<div style=\\'position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) rotate(-15deg);opacity:0.05;font-size:80px;font-weight:900;color:var(--color-success);white-space:nowrap;pointer-events:none\\'>AUTO SIGNED</div>'+
                  '<p style=\\'font-weight:600;color:var(--text-primary);margin-bottom:12px\\'>${name}</p>'+
                  '<p>${t('This Non-Disclosure Agreement is entered into by SEVEN TECH CAPITAL (&quot;Company&quot;) in favor of the Entrepreneur.','يتم إبرام اتفاقية عدم الإفصاح هذه من قبل سفن تك كابيتال (\"الشركة\") لصالح رائد الأعمال.')}</p>'+
                  '<p style=\\'margin-top:12px\\'>${t('The Company agrees to keep all intellectual property, business plans, and data strictly confidential.','توافق الشركة على الحفاظ على جميع حقوق الملكية الفكرية وخطط العمل والبيانات بسرية تامة.')}</p>'+
                  '<div style=\\'margin-top:32px;padding-top:24px;border-top:1px dashed var(--border-default);display:flex;justify-content:space-between;align-items:flex-end\\'>'+
                    '<div>'+
                      '<div style=\\'font-family:cursive;font-size:28px;color:var(--action-primary);margin-bottom:8px\\'>Seven Tech Capital</div>'+
                      '<div style=\\'font-size:12px;font-weight:600;color:var(--text-tertiary);text-transform:uppercase\\'>${t('Company Signature','توقيع الشركة')}</div>'+
                    '</div>'+
                    '<div style=\\'width:80px;height:80px;border:3px solid var(--action-primary);border-radius:50%;display:flex;align-items:center;justify-content:center;transform:rotate(-15deg);opacity:0.8\\'>'+
                      '<div style=\\'text-align:center;color:var(--action-primary);font-size:10px;font-weight:bold;text-transform:uppercase;line-height:1.2\\'>STC<br>OFFICIAL<br>SEAL</div>'+
                    '</div>'+
                  '</div>'+
                '</div>'+
                '<button onclick=\\'this.closest(\"div[style*=fixed]\").remove()\\' style=\\'width:100%;padding:14px;background:var(--bg-secondary);color:var(--text-primary);border:1px solid var(--border-default);border-radius:var(--radius-lg);font-weight:600;cursor:pointer;font-size:14px\\'>${t('Download PDF','تحميل كملف PDF')}</button>'+
              '</div>';
              document.body.appendChild(modal);
              modal.onclick=function(e){if(e.target===modal)modal.remove()};
            ">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-inline-end:4px"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
              ${t('View & Download', 'عرض وتحميل')}
            </button>
          </div>
        </div>
      </div>`).join('')}
    </div>`;
}

function meetings() {
  return `
    <div class="d-flex justify-between items-center mb-6"><h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Meetings', 'الاجتماعات')}</h2><button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">${t('Request Meeting', 'طلب اجتماع')}</button></div>
    <div class="d-flex flex-col gap-3">
      ${[
        [t('Weekly Standup — FinFlow','اجتماع أسبوعي — FinFlow'),'Jun 12, 2026 · 10:00',t('Project Team','فريق المشروع'),t('Scheduled','مجدول')],
        [t('Sprint Retrospective','مراجعة مرحلة التطوير'),'Jun 6, 2026 · 10:00',t('Engineering Team','الفريق الهندسي'),t('Completed','مكتمل')],
      ].map(([name, date, with_, status]) => `
      <div class="card" style="padding:var(--space-5);display:flex;align-items:center;justify-content:space-between;border-radius:var(--radius-xl);transition:all 0.2s" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
        <div><div class="text-label" style="font-weight:var(--weight-bold)">${name}</div><div class="text-caption text-secondary mt-1">${date} · ${with_}</div></div>
        <div class="d-flex gap-3 items-center">
          <span class="badge ${status===t('Completed','مكتمل')?'badge-neutral':status===t('Scheduled','مجدول')?'badge-success':'badge-primary'} badge-dot" style="border-radius:var(--radius-full)">${status}</span>
          ${status===t('Scheduled','مجدول')?`<button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">${t('Join','انضمام')}</button>`:`<button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)">${t('Notes','الملاحظات')}</button>`}
        </div>
      </div>`).join('')}
    </div>`;
}

function exitRecords() {
  return `
    <h2 class="text-h4 mb-6" style="font-weight:var(--weight-bold)">${t('Exit Records', 'سجلات الخروج')}</h2>
    <div class="state-empty" style="background:var(--bg-surface);border-radius:var(--radius-xl);padding:var(--space-12);text-align:center;border:1px dashed var(--border-strong)">
      <div style="width:64px;height:64px;background:var(--color-primary-lighter);color:var(--action-primary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-4)">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="6" rx="2"/><path d="M12 6V2"/></svg>
      </div>
      <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold)">${t('No Exits Yet', 'لا توجد تخارجات حتى الآن')}</h3>
      <p class="text-secondary" style="max-width:400px;margin:0 auto">${t('Exit records will appear here once any of your ventures complete an exit process.', 'ستظهر سجلات التخارج هنا بمجرد اكتمال عملية التخارج لأي من مشاريعك.')}</p>
    </div>`;
}

// Reuse general profile
import { generalDashboardPage } from './dashboard-general.js?v=2';

export function entrepreneurDashboardPage(tab = 'overview') {
  const titles = { overview:t('Founder Dashboard','لوحة المؤسس'), 'my-projects':t('My Projects','مشاريعي'), applications:t('Applications','الطلبات'), progress:t('Progress Tracking','تتبع التقدم'), reports:t('Reports','التقارير'), documents:t('Documents','المستندات'), ndas:t('NDA Center','مركز NDA'), meetings:t('Meetings','الاجتماعات'), 'exit-records':t('Exit Records','سجلات الخروج'), notifications:t('Notifications','الإشعارات'), profile:t('Profile & Security','الملف الشخصي') };
  const contents = { overview, 'my-projects': myProjects, applications, progress, reports: reportsTab, documents: documentsTab, ndas: ndasTab, meetings, 'exit-records': exitRecords };
  
  let content = '';
  if (tab === 'notifications' || tab === 'profile') {
    // Generate the content directly from general dashboard functions we imported indirectly
    // For simplicity, we just trigger the layout with the appropriate tab
    content = '<div class="text-secondary">Loading...</div>';
    setTimeout(() => {
      // Re-trigger router internally to fetch general layout which has profile
      window.location.hash = `/dashboard/${tab}`;
    }, 0);
  } else {
    content = contents[tab] ? contents[tab]() : overview();
  }
  
  return dashboardLayout(titles[tab] || t('Founder Dashboard','لوحة المؤسس'), 'entrepreneur', tab, content);
}
