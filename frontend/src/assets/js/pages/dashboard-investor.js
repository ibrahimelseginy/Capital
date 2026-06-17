/**
 * SEVEN TECH CAPITAL — Investor Dashboard (Premium UX)
 * Overview, Projects, Reports, Documents, NDAs (investor signs), Exit Requests/Records, Consultations, Events, Profile
 */
import LangManager from '../language.js';
import { dashboardLayout } from './dashboard-layout.js';

// ─── Shared helpers ───
function investorMetricCard(value, label, iconSvg, color = 'var(--action-primary)', change = '') {
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

const isAr = () => typeof LangManager !== 'undefined' && LangManager.currentLang === 'ar';
const t = (en, ar) => isAr() ? ar : en;

function overview() {
  return `
    <!-- Welcome Banner -->
    <div class="card mb-8 reveal" style="padding:var(--space-8) var(--space-10);background:linear-gradient(135deg, #0f0f0f 0%, #1a1510 40%, #1a1714 60%, #0f0f0f 100%);border:1px solid rgba(255,90,0,0.15);position:relative;overflow:hidden;border-radius:var(--radius-xl)">
      <div style="position:absolute;top:-60%;right:-15%;width:500px;height:500px;background:radial-gradient(circle,rgba(255,90,0,0.12) 0%,transparent 65%);pointer-events:none"></div>
      <div style="position:absolute;bottom:-40%;left:-5%;width:350px;height:350px;background:radial-gradient(circle,rgba(198,161,91,0.08) 0%,transparent 65%);pointer-events:none"></div>
      <div style="position:relative;z-index:2">
        <div class="text-caption mb-3" style="color:var(--action-primary);font-weight:var(--weight-bold);text-transform:uppercase;letter-spacing:3px;font-size:11px">${t('Investor Dashboard', 'لوحة المستثمر')}</div>
        <h2 class="text-h2 mb-3" style="color:#fff;font-weight:var(--weight-bold);letter-spacing:-0.5px;line-height:1.2">${t('Welcome back, Khalid', 'مرحباً بك، خالد')} 👋</h2>
        <p class="text-body" style="color:rgba(255,255,255,0.55);max-width:500px;line-height:1.6">${t("Here's your portfolio performance and pending actions at a glance.", 'إليك نظرة سريعة على أداء محفظتك والإجراءات المعلقة.')}</p>
      </div>
    </div>

    <div class="grid-4 mb-8">
      ${investorMetricCard('$2.4M', t('Total Invested', 'إجمالي الاستثمار'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>', 'var(--action-primary)', '↑ 12% this quarter')}
      ${investorMetricCard('5', t('Active Projects', 'مشاريع نشطة'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg>', '#3b82f6')}
      ${investorMetricCard('3.2x', t('Portfolio Return', 'عائد المحفظة'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/></svg>', 'var(--color-success)', '↑ 0.4x from Q4')}
      ${investorMetricCard('3', t('Pending NDAs', 'اتفاقيات معلقة'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>', 'var(--color-warning)')}
    </div>

    <div class="grid-12" style="gap:var(--space-6)">
      <div style="grid-column:span 8">
        <!-- Portfolio Chart -->
        <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <div class="d-flex justify-between items-center mb-5">
            <h3 class="text-h5" style="font-weight:var(--weight-bold)">${t('Portfolio Overview', 'نظرة على المحفظة')}</h3>
            <div class="d-flex gap-2">
              <button class="chip active" style="border-radius:var(--radius-full)">${t('1Y', 'سنة')}</button>
              <button class="chip" style="border-radius:var(--radius-full)">${t('All', 'الكل')}</button>
            </div>
          </div>
          <div style="height:240px;background:linear-gradient(to bottom, var(--bg-secondary), var(--bg-surface));border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
            <div style="position:absolute;bottom:0;left:0;right:0;height:60%;background:linear-gradient(to top, rgba(255,90,0,0.05), transparent)"></div>
            <svg viewBox="0 0 400 120" style="width:100%;height:80%;padding:0 20px" preserveAspectRatio="none">
              <polyline fill="none" stroke="var(--action-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="0,100 40,85 80,90 120,60 160,65 200,40 240,45 280,25 320,30 360,15 400,10"/>
              <polyline fill="url(#chartGrad)" stroke="none" points="0,100 40,85 80,90 120,60 160,65 200,40 240,45 280,25 320,30 360,15 400,10 400,120 0,120"/>
              <defs><linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="var(--action-primary)" stop-opacity="0.15"/><stop offset="100%" stop-color="var(--action-primary)" stop-opacity="0"/></linearGradient></defs>
            </svg>
          </div>
        </div>
        <!-- Recent Activity -->
        <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <div class="d-flex justify-between items-center mb-5" style="padding-bottom:var(--space-4);border-bottom:1px solid var(--border-subtle)">
            <h3 class="text-h5" style="font-weight:var(--weight-bold)">${t('Recent Activity', 'النشاط الأخير')}</h3>
          </div>
          ${[
            [t('New quarterly report available for FinFlow','تقرير ربع سنوي جديد لـ FinFlow'), t('2h ago','منذ ساعتين'), '#3b82f6'],
            [t('DataPulse milestone: 10K users reached','DataPulse: تم الوصول إلى 10 آلاف مستخدم'), t('1d ago','منذ يوم'), 'var(--color-success)'],
            [t('Exit opportunity notification for BuildOS','إشعار فرصة خروج لـ BuildOS'), t('2d ago','منذ يومين'), 'var(--action-primary)'],
            [t('NDA signed for Project Alpha','تم توقيع NDA لمشروع ألفا'), t('5d ago','منذ 5 أيام'), 'var(--accent-gold)'],
          ].map(([msg, time, color]) => `
          <div class="d-flex gap-4 py-3 activity-row" style="padding-inline:var(--space-3);border-radius:var(--radius-md);cursor:pointer;transition:all 0.2s" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
            <div style="width:10px;height:10px;border-radius:50%;background:${color};margin-top:6px;flex-shrink:0;box-shadow:0 0 0 3px ${color}20"></div>
            <div class="flex-1 text-body-sm">${msg}</div>
            <div class="text-caption text-tertiary" style="background:var(--bg-secondary);padding:2px 10px;border-radius:var(--radius-full);align-self:center;white-space:nowrap">${time}</div>
          </div>`).join('')}
        </div>
      </div>
      <div style="grid-column:span 4">
        <!-- Allocation -->
        <div class="card mb-5" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h4 class="text-label mb-5" style="font-weight:var(--weight-bold)">${t('Investment Allocation', 'توزيع الاستثمارات')}</h4>
          <div style="height:160px;display:flex;align-items:center;justify-content:center;position:relative">
            <svg viewBox="0 0 120 120" style="width:140px;height:140px;transform:rotate(-90deg)">
              <circle cx="60" cy="60" r="50" fill="none" stroke="var(--bg-secondary)" stroke-width="12"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke="var(--action-primary)" stroke-width="12" stroke-dasharray="141.37 314.16" stroke-linecap="round"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke="var(--accent-gold)" stroke-width="12" stroke-dasharray="94.25 314.16" stroke-dashoffset="-141.37" stroke-linecap="round"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke="var(--color-success)" stroke-width="12" stroke-dasharray="78.54 314.16" stroke-dashoffset="-235.62" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="d-flex flex-col gap-3 mt-4">
            ${[['FinTech','45%','var(--action-primary)'],['AI & Data','30%','var(--accent-gold)'],['PropTech','25%','var(--color-success)']].map(([name, pct, color]) => `
            <div class="d-flex justify-between items-center">
              <div class="d-flex gap-2 items-center"><div style="width:10px;height:10px;border-radius:50%;background:${color}"></div><span class="text-caption" style="font-weight:var(--weight-medium)">${name}</span></div>
              <span class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">${pct}</span>
            </div>`).join('')}
          </div>
        </div>
        <!-- Upcoming -->
        <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h4 class="text-label mb-4" style="font-weight:var(--weight-bold)">${t('Upcoming', 'القادم')}</h4>
          <div class="d-flex flex-col gap-3">
            ${[['15', t('Jul · Demo Day', 'يوليو · يوم العروض')],['28', t('Jul · Q2 Briefing', 'يوليو · إحاطة ق2')]].map(([day, desc]) => `
            <div class="d-flex gap-3 items-center" style="padding:var(--space-3);border-radius:var(--radius-lg);transition:all 0.2s;cursor:pointer" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
              <div style="min-width:40px;text-align:center;padding:var(--space-2);background:var(--color-primary-lighter);border-radius:var(--radius-md)">
                <div class="text-h5" style="color:var(--action-primary);font-weight:var(--weight-bold)">${day}</div>
              </div>
              <div class="text-caption" style="font-weight:var(--weight-medium)">${desc}</div>
            </div>`).join('')}
          </div>
        </div>
      </div>
    </div>`;
}

function projects() {
  return `
    <div class="d-flex justify-between items-center mb-6"><h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Portfolio Projects', 'مشاريع المحفظة')}</h2><div class="d-flex gap-3"><button class="chip active" style="border-radius:var(--radius-full)">${t('All','الكل')}</button><button class="chip" style="border-radius:var(--radius-full)">${t('Active','نشط')}</button><button class="chip" style="border-radius:var(--radius-full)">${t('Exited','خروج')}</button></div></div>
    <div class="grid-2" style="gap:var(--space-6)">
      ${[
        ['FinFlow','FinTech',t('Active','نشط'),'$800K','3.5x','Series A'],
        ['DataPulse','AI & Data',t('Scaling','توسع'),'$600K','2.8x','Seed+'],
        ['BuildOS','PropTech',t('Building','بناء'),'$400K','1.2x','Seed'],
        ['HealthBridge','HealthTech',t('Active','نشط'),'$350K','2.1x','Pre-Series A'],
        ['LogiFlow','Logistics',t('Exited','خروج'),'$250K','4.2x',t('Acquired','مُستحوذ')],
      ].map(([name, cat, status, invested, returns, round]) => `
      <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl);transition:all 0.3s ease" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
        <div class="d-flex justify-between items-start mb-4">
          <div class="d-flex gap-3 items-center"><div style="width:48px;height:48px;border-radius:var(--radius-lg);background:var(--color-primary-lighter);display:flex;align-items:center;justify-content:center;color:var(--action-primary);font-weight:700;font-size:1.1rem">${name[0]}</div><div><h3 class="text-h5" style="font-weight:var(--weight-bold)">${name}</h3><div class="text-caption text-secondary">${cat} · ${round}</div></div></div>
          <span class="badge ${status===t('Exited','خروج')?'badge-gold':status===t('Active','نشط')?'badge-success':'badge-primary'} badge-dot" style="border-radius:var(--radius-full)">${status}</span>
        </div>
        <div class="grid-3 mt-4" style="gap:var(--space-3)">
          <div><div class="text-caption text-secondary">${t('Invested','استثمار')}</div><div class="text-label" style="font-weight:var(--weight-bold)">${invested}</div></div>
          <div><div class="text-caption text-secondary">${t('Return','عائد')}</div><div class="text-label" style="color:var(--color-success);font-weight:var(--weight-bold)">${returns}</div></div>
          <div><div class="text-caption text-secondary">${t('Status','الحالة')}</div><div class="text-label">${status}</div></div>
        </div>
        <div class="d-flex gap-2 mt-5">
          <button class="btn btn-secondary btn-sm flex-1" style="border-radius:var(--radius-lg)" onclick="if(!this.dataset.signed){ window.openInvestorNDA('NDA — ${name}'); this.dataset.signed='true'; this.innerHTML='<span style=\\'color:var(--color-success)\\'>✓</span> '+this.innerText; } else { window.location.hash = '/dashboard/investor/project/' + '${name}'.toLowerCase().replace(/\\s+/g,'-'); }">${t('View Details','التفاصيل')}</button>
          <button class="btn btn-ghost btn-sm flex-1" style="border-radius:var(--radius-lg)">${t('Reports','التقارير')}</button>
        </div>
      </div>`).join('')}
    </div>`;
}

function reports() {
  return `
    <div class="d-flex justify-between items-center mb-6"><h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Reports','التقارير')}</h2><div class="d-flex gap-3"><select class="form-input form-select" style="max-width:160px;border-radius:var(--radius-lg)"><option>${t('All Projects','كل المشاريع')}</option><option>FinFlow</option><option>DataPulse</option></select><select class="form-input form-select" style="max-width:140px;border-radius:var(--radius-lg)"><option>2026</option><option>2025</option></select></div></div>
    <div class="table-wrapper" style="border-radius:var(--radius-xl)">
      <table class="table"><thead><tr><th>${t('Report','التقرير')}</th><th>${t('Project','المشروع')}</th><th>${t('Period','الفترة')}</th><th>${t('Type','النوع')}</th><th>${t('Status','الحالة')}</th><th></th></tr></thead>
        <tbody>${[
          [t('Q1 2026 Performance Report','تقرير أداء ق1 2026'),'FinFlow','Jan-Mar 2026',t('Quarterly','ربع سنوي'),t('Published','منشور')],
          [t('Monthly Update — May 2026','تحديث شهري — مايو 2026'),'DataPulse','May 2026',t('Monthly','شهري'),t('Published','منشور')],
          [t('Due Diligence Report','تقرير العناية الواجبة'),'BuildOS','Mar 2026',t('Due Diligence','عناية واجبة'),t('NDA Required','يتطلب NDA')],
        ].map(([name, project, period, type, status]) => `
        <tr style="transition:all 0.2s" onmouseover="this.style.background='var(--action-ghost-hover)'" onmouseout="this.style.background=''"><td class="text-label" style="font-weight:var(--weight-semibold)">${name}</td><td>${project}</td><td class="text-secondary">${period}</td><td><span class="badge badge-neutral" style="border-radius:var(--radius-full)">${type}</span></td><td><span class="badge ${status.includes('NDA')?'badge-warning':'badge-success'} badge-dot" style="border-radius:var(--radius-full)">${status}</span></td><td><button class="btn btn-ghost btn-sm" style="color:var(--action-primary);border-radius:var(--radius-full)">${status.includes('NDA')?t('Sign NDA','وقّع NDA'):t('View','عرض')}</button></td></tr>`).join('')}
        </tbody></table>
    </div>`;
}

function documents() {
  return `
    <div class="d-flex justify-between items-center mb-6"><h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Document Center','مركز المستندات')}</h2><div class="d-flex gap-3"><button class="chip active" style="border-radius:var(--radius-full)">${t('All','الكل')}</button><button class="chip" style="border-radius:var(--radius-full)">${t('Legal','قانوني')}</button><button class="chip" style="border-radius:var(--radius-full)">${t('Financial','مالي')}</button><button class="chip" style="border-radius:var(--radius-full)">NDA</button></div></div>
    <div class="table-wrapper" style="border-radius:var(--radius-xl)">
      <table class="table"><thead><tr><th>${t('Document','المستند')}</th><th>${t('Type','النوع')}</th><th>${t('Date','التاريخ')}</th><th>${t('Access','الوصول')}</th><th></th></tr></thead>
        <tbody>${[
          [t('Investment Agreement — FinFlow','اتفاقية استثمار — FinFlow'),t('Legal','قانوني'),'Jan 2024',t('Signed','موقّع')],
          [t('Share Certificate — DataPulse','شهادة أسهم — DataPulse'),t('Financial','مالي'),'Mar 2024',t('Active','نشط')],
          ['NDA — Project Alpha','NDA','Jun 2026',t('Pending','معلق')],
          [t('Board Resolution — BuildOS','قرار مجلس — BuildOS'),t('Legal','قانوني'),'May 2026',t('Signed','موقّع')],
        ].map(([name, type, date, status]) => `
        <tr><td class="d-flex gap-2 items-center"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/></svg><span class="text-label">${name}</span></td><td><span class="badge badge-neutral" style="border-radius:var(--radius-full)">${type}</span></td><td class="text-secondary">${date}</td><td><span class="badge ${status===t('Pending','معلق')?'badge-warning':'badge-success'} badge-dot" style="border-radius:var(--radius-full)">${status}</span></td><td><button class="btn btn-ghost btn-sm" style="color:var(--action-primary);border-radius:var(--radius-full)">${status===t('Pending','معلق')?t('Review','مراجعة'):t('Download','تحميل')}</button></td></tr>`).join('')}
        </tbody></table>
    </div>`;
}

// ─── NDA Center — Investor signs NDA themselves ───
window.openInvestorNDA = function(name) {
  const modal = document.createElement('div');
  modal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;animation:fadeIn 0.2s ease';
  
  const isAr = LangManager.currentLang === 'ar';
  const tTitle = isAr ? 'مراجعة وتوقيع NDA' : 'Review & Sign NDA';
  const tHeader = isAr ? 'اتفاقية عدم الإفصاح' : 'NON-DISCLOSURE AGREEMENT';
  const tP1 = isAr ? 'يتم إبرام اتفاقية عدم الإفصاح هذه بين سفن تك كابيتال ("الشركة") والمستثمر الموقع أدناه ("الطرف المتلقي").' : 'This Non-Disclosure Agreement is entered into by and between SEVEN TECH CAPITAL ("Company") and the undersigned Investor ("Receiving Party").';
  const tP2 = isAr ? 'يوافق الطرف المتلقي على الحفاظ على جميع المعلومات السرية بصرامة وعدم إفصاحها لأي طرف ثالث دون موافقة خطية مسبقة.' : 'The Receiving Party agrees to keep all confidential information strictly confidential and not disclose it to any third party without prior written consent.';
  const tP3 = isAr ? 'تكون هذه الاتفاقية سارية لمدة سنتين من تاريخ التوقيع.' : 'This agreement shall be effective for a period of 2 years from the date of signing.';
  const tSig = isAr ? 'توقيعك الرقمي' : 'Your Digital Signature';
  const tClick = isAr ? 'انقر للتوقيع' : 'Click to sign';
  const tSignBtn = isAr ? 'وقّع وأرسل' : 'Sign & Submit';
  const tCancel = isAr ? 'إلغاء' : 'Cancel';
  const tSuccess = isAr ? '✓ تم التوقيع بنجاح' : '✓ Signed Successfully';

  modal.innerHTML = `
    <div style="background:var(--bg-surface);border-radius:var(--radius-xl);max-width:600px;width:100%;max-height:80vh;overflow-y:auto;padding:var(--space-8);box-shadow:0 24px 56px rgba(0,0,0,0.3)">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-6)">
        <h3 style="font-size:1.25rem;font-weight:700">${tTitle}</h3>
        <button id="nda-close-top" style="width:36px;height:36px;border-radius:50%;border:1px solid var(--border-default);background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-primary)">✕</button>
      </div>
      <div style="background:var(--bg-secondary);border-radius:var(--radius-lg);padding:var(--space-6);margin-bottom:var(--space-6);font-size:14px;line-height:1.8;color:var(--text-secondary)">
        <p style="font-weight:600;color:var(--text-primary);margin-bottom:12px">${tHeader}</p>
        <p>${tP1}</p>
        <p style="margin-top:12px">${tP2}</p>
        <p style="margin-top:12px">${tP3}</p>
      </div>
      <div style="margin-bottom:var(--space-6)">
        <label style="display:block;font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:8px">${tSig}</label>
        <div id="nda-sig-box" style="border:2px dashed var(--border-default);border-radius:var(--radius-lg);height:80px;display:flex;align-items:center;justify-content:center;cursor:pointer;background:var(--bg-primary);color:var(--text-tertiary);font-size:13px;transition:all 0.2s">
          ${tClick}
        </div>
      </div>
      <div style="display:flex;gap:12px">
        <button id="nda-submit-btn" style="flex:1;padding:14px;background:var(--action-primary);color:white;border:none;border-radius:var(--radius-lg);font-weight:600;cursor:pointer;font-size:14px;box-shadow:0 4px 16px rgba(255,90,0,0.3)">${tSignBtn}</button>
        <button id="nda-cancel-btn" style="padding:14px 24px;background:transparent;color:var(--text-secondary);border:1px solid var(--border-default);border-radius:var(--radius-lg);cursor:pointer;font-weight:500;font-size:14px">${tCancel}</button>
      </div>
    </div>`;

  document.body.appendChild(modal);

  // Attach event listeners cleanly
  const closeTop = modal.querySelector('#nda-close-top');
  const cancelBtn = modal.querySelector('#nda-cancel-btn');
  const submitBtn = modal.querySelector('#nda-submit-btn');
  const sigBox = modal.querySelector('#nda-sig-box');

  const closeModal = () => modal.remove();
  
  closeTop.onclick = closeModal;
  cancelBtn.onclick = closeModal;
  modal.onclick = (e) => { if (e.target === modal) closeModal(); };

  sigBox.onmouseover = () => {
    if (sigBox.dataset.signed) return;
    sigBox.style.borderColor = 'var(--action-primary)';
    sigBox.style.color = 'var(--action-primary)';
  };
  sigBox.onmouseout = () => {
    if (sigBox.dataset.signed) return;
    sigBox.style.borderColor = 'var(--border-default)';
    sigBox.style.color = 'var(--text-tertiary)';
  };
  sigBox.onclick = () => {
    sigBox.dataset.signed = 'true';
    sigBox.innerHTML = '<span style="font-family:cursive;font-size:24px;color:var(--text-primary)">Khalid Al-Dosari</span>';
    sigBox.style.borderStyle = 'solid';
    sigBox.style.borderColor = 'var(--color-success)';
    sigBox.style.background = 'var(--color-success-bg)';
  };

  submitBtn.onclick = () => {
    submitBtn.textContent = tSuccess;
    submitBtn.style.background = 'var(--color-success)';
    submitBtn.disabled = true;
    setTimeout(closeModal, 1500);
  };
};

// ─── NDA Center — Investor signs NDA themselves ───
function ndas() {
  return `
    <div class="d-flex justify-between items-center mb-6">
      <div>
        <h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('NDA Center', 'مركز اتفاقيات السرية')}</h2>
        <p class="text-body-sm text-secondary mt-1">${t('Review and sign Non-Disclosure Agreements', 'راجع ووقّع اتفاقيات عدم الإفصاح')}</p>
      </div>
      <span class="badge badge-warning badge-dot" style="border-radius:var(--radius-full);font-size:13px;padding:6px 14px">3 ${t('Pending', 'معلقة')}</span>
    </div>

    <!-- Info Banner -->
    <div class="card mb-6" style="padding:var(--space-5);border:1px solid rgba(59,130,246,0.3);background:rgba(59,130,246,0.04);border-radius:var(--radius-xl)">
      <div class="d-flex gap-3 items-center">
        <div style="width:40px;height:40px;border-radius:var(--radius-lg);background:rgba(59,130,246,0.1);display:flex;align-items:center;justify-content:center;color:#3b82f6;flex-shrink:0">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
        </div>
        <p class="text-body-sm">${t('As an investor, you are required to sign NDAs before accessing confidential project information. Please review each agreement carefully before signing.', 'كمستثمر، يجب عليك توقيع اتفاقيات عدم الإفصاح قبل الوصول إلى معلومات المشاريع السرية. يرجى مراجعة كل اتفاقية بعناية قبل التوقيع.')}</p>
      </div>
    </div>

    <div class="d-flex flex-col gap-4">
      ${[
        ['NDA — Project Alpha', t('Pending Signature','في انتظار التوقيع'), 'Jun 10, 2026', t('High','عالية'), true],
        ['NDA — DataPulse Extension', t('Pending Review','في انتظار المراجعة'), 'Jun 8, 2026', t('Medium','متوسطة'), true],
        ['NDA — Market Research 2026', t('Pending Signature','في انتظار التوقيع'), 'Jun 5, 2026', t('Low','منخفضة'), true],
        ['NDA — FinFlow', t('Active','نشطة'), 'Jan 2024', '—', false],
        ['NDA — BuildOS', t('Active','نشطة'), 'Mar 2024', '—', false],
        ['NDA — LogiFlow', t('Expired','منتهية'), 'Dec 2025', '—', false],
      ].map(([name, status, date, priority, pending]) => `
      <div class="card" style="padding:var(--space-5);border-radius:var(--radius-xl);border:1px solid ${pending ? 'rgba(255,90,0,0.15)' : 'var(--border-default)'};transition:all 0.3s ease;${pending ? 'background:linear-gradient(135deg, var(--bg-surface) 0%, rgba(255,90,0,0.02) 100%)' : ''}" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
        <div class="d-flex items-center justify-between gap-4">
          <div class="d-flex gap-4 items-center">
            <div style="width:44px;height:44px;border-radius:var(--radius-lg);background:${pending?'rgba(255,90,0,0.08)':'var(--color-success-bg)'};display:flex;align-items:center;justify-content:center;color:${pending?'var(--action-primary)':'var(--color-success)'}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
              <div class="text-label" style="font-weight:var(--weight-bold)">${name}</div>
              <div class="text-caption text-secondary">${date}${priority!=='—'?` · ${t('Priority','الأولوية')}: ${priority}`:''}</div>
            </div>
          </div>
          <div class="d-flex gap-3 items-center">
            <span class="badge ${status===t('Active','نشطة')?'badge-success':status===t('Expired','منتهية')?'badge-error':'badge-warning'} badge-dot" style="border-radius:var(--radius-full)">${status}</span>
            ${pending ? `
            <button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg);box-shadow:0 4px 12px rgba(255,90,0,0.2)" onclick="event.stopPropagation(); window.openInvestorNDA('${name}')">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-inline-end:4px"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
              ${t('Review & Sign', 'راجع ووقّع')}
            </button>` : `<button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)">${t('View','عرض')}</button>`}
          </div>
        </div>
      </div>`).join('')}
    </div>`;
}

function exitRequests() {
  return `
    <div class="d-flex justify-between items-center mb-6"><h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Exit Requests','طلبات الخروج')}</h2><button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">${t('New Exit Request','طلب خروج جديد')}</button></div>
    <div class="card mb-6" style="padding:var(--space-5);border-color:rgba(59,130,246,0.3);background:rgba(59,130,246,0.04);border-radius:var(--radius-xl)">
      <div class="d-flex gap-3 items-center"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg><p class="text-body-sm">${t('Exit requests are reviewed by our investment committee within 5 business days.','يتم مراجعة طلبات الخروج من قبل لجنة الاستثمار خلال 5 أيام عمل.')}</p></div>
    </div>
    <div class="table-wrapper" style="border-radius:var(--radius-xl)">
      <table class="table"><thead><tr><th>${t('Project','المشروع')}</th><th>${t('Request Date','تاريخ الطلب')}</th><th>${t('Type','النوع')}</th><th>${t('Amount','المبلغ')}</th><th>${t('Status','الحالة')}</th><th></th></tr></thead>
        <tbody>${[
          ['FinFlow','Jun 1, 2026',t('Partial Exit','خروج جزئي'),'$200K',t('Under Review','قيد المراجعة')],
          ['LogiFlow','Oct 2025',t('Full Exit','خروج كامل'),'$250K',t('Completed','مكتمل')],
        ].map(([project, date, type, amount, status]) => `
        <tr><td class="text-label" style="font-weight:var(--weight-semibold)">${project}</td><td class="text-secondary">${date}</td><td><span class="badge badge-neutral" style="border-radius:var(--radius-full)">${type}</span></td><td style="font-weight:var(--weight-semibold)">${amount}</td><td><span class="badge ${status===t('Completed','مكتمل')?'badge-success':'badge-warning'} badge-dot" style="border-radius:var(--radius-full)">${status}</span></td><td>${status===t('Under Review','قيد المراجعة')?`<button class="btn btn-ghost btn-sm" style="color:var(--action-primary);border-radius:var(--radius-full)">${t('Track','تتبع')}</button>`:''}</td></tr>`).join('')}
        </tbody></table>
    </div>`;
}

function exitRecords() {
  return `
    <h2 class="text-h4 mb-6" style="font-weight:var(--weight-bold)">${t('Exit Records','سجلات الخروج')}</h2>
    <div class="grid-2 mb-8" style="gap:var(--space-6)">
      ${investorMetricCard('$1.05M', t('Total Exit Value','إجمالي قيمة الخروج'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>', 'var(--color-success)')}
      ${investorMetricCard('4.2x', t('Average Return Multiple','متوسط مضاعف العائد'), '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/></svg>', 'var(--accent-gold)')}
    </div>
    <div class="table-wrapper" style="border-radius:var(--radius-xl)">
      <table class="table"><thead><tr><th>${t('Project','المشروع')}</th><th>${t('Entry','الدخول')}</th><th>${t('Exit','الخروج')}</th><th>${t('Invested','استثمار')}</th><th>${t('Returned','العائد')}</th><th>${t('Multiple','مضاعف')}</th><th>${t('Method','الطريقة')}</th></tr></thead>
        <tbody><tr><td class="text-label" style="font-weight:var(--weight-semibold)">LogiFlow</td><td>Mar 2023</td><td>Oct 2025</td><td>$250K</td><td style="color:var(--color-success);font-weight:var(--weight-bold)">$1.05M</td><td style="color:var(--color-success);font-weight:var(--weight-bold)">4.2x</td><td><span class="badge badge-gold" style="border-radius:var(--radius-full)">${t('Acquisition','استحواذ')}</span></td></tr></tbody></table>
    </div>`;
}

function consultations() {
  return `
    <div class="d-flex justify-between items-center mb-6"><h2 class="text-h4" style="font-weight:var(--weight-bold)">${t('Consultations','الاستشارات')}</h2><button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">${t('Request Consultation','طلب استشارة')}</button></div>
    <div class="d-flex flex-col gap-3">
      ${[
        [t('Portfolio Strategy Review','مراجعة استراتيجية المحفظة'),t('Scheduled','مجدولة'),'Jun 15, 2026 · 14:00','Ahmad Al-Rashid'],
        [t('Exit Planning Discussion','مناقشة خطة الخروج'),t('Pending Response','في انتظار الرد'),'Requested Jun 8',t('Investment Committee','لجنة الاستثمار')],
        [t('Q2 Performance Deep Dive','تحليل معمق لأداء ق2'),t('Completed','مكتملة'),'May 28, 2026',t('Account Manager','مدير الحساب')],
      ].map(([title, status, date, with_]) => `
      <div class="card" style="padding:var(--space-5);display:flex;align-items:center;justify-content:space-between;gap:var(--space-4);border-radius:var(--radius-xl);transition:all 0.2s" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
        <div><div class="text-label" style="font-weight:var(--weight-bold)">${title}</div><div class="text-caption text-secondary">${date} · ${t('with','مع')} ${with_}</div></div>
        <div class="d-flex gap-2 items-center">
          <span class="badge ${status===t('Scheduled','مجدولة')?'badge-success':status===t('Completed','مكتملة')?'badge-neutral':'badge-warning'} badge-dot" style="border-radius:var(--radius-full)">${status}</span>
          ${status===t('Scheduled','مجدولة')?`<button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">${t('Join','انضم')}</button>`:`<button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg)">${t('View','عرض')}</button>`}
        </div>
      </div>`).join('')}
    </div>`;
}

function events() {
  return `
    <h2 class="text-h4 mb-6" style="font-weight:var(--weight-bold)">${t('Investor Events','فعاليات المستثمرين')}</h2>
    <div class="d-flex flex-col gap-3">
      ${[
        [t('Investor Briefing: Q2 Update','إحاطة المستثمرين: تحديث ق2'),'Jul 28, 2026',t('Online','عبر الإنترنت'),t('Registered','مسجل'),t('Exclusive','حصري')],
        [t('Venture Demo Day 2026','يوم عروض 2026'),'Jul 15, 2026',t('Riyadh','الرياض'),t('VIP Access','دخول VIP'),t('Open','مفتوح')],
        [t('Annual Investor Summit','قمة المستثمرين السنوية'),'Nov 2026',t('Riyadh','الرياض'),t('Coming Soon','قريباً'),t('Exclusive','حصري')],
      ].map(([name, date, loc, status, access]) => `
      <div class="card" style="padding:var(--space-5);display:flex;align-items:center;justify-content:space-between;border-radius:var(--radius-xl);transition:all 0.2s" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
        <div class="d-flex gap-4 items-center">
          <div style="min-width:52px;text-align:center;padding:var(--space-2);background:var(--color-primary-lighter);border-radius:var(--radius-lg)"><div class="text-h5" style="color:var(--action-primary);font-weight:var(--weight-bold)">${date.split(' ')[1].replace(',','')}</div><div class="text-caption text-secondary">${date.split(' ')[0]}</div></div>
          <div><div class="text-label" style="font-weight:var(--weight-bold)">${name}</div><div class="text-caption text-secondary">${loc}</div></div>
        </div>
        <div class="d-flex gap-2 items-center"><span class="badge ${access===t('Exclusive','حصري')?'badge-gold':'badge-neutral'}" style="border-radius:var(--radius-full)">${access}</span><span class="badge badge-success badge-dot" style="border-radius:var(--radius-full)">${status}</span></div>
      </div>`).join('')}
    </div>`;
}

function profile() {
  return `
    <div class="grid-12" style="gap:var(--space-6)">
      <!-- Left Column: Profile Details -->
      <div style="grid-column:span 8">
        <div class="card" style="padding:var(--space-8);border-radius:var(--radius-xl)">
          <div class="d-flex gap-8 items-center mb-8 pb-8" style="border-bottom:1px solid var(--border-default)">
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg, var(--action-primary), #cc4700);display:flex;align-items:center;justify-content:center;color:white;font-size:1.75rem;font-weight:700;box-shadow:0 8px 24px rgba(255,90,0,0.25)">KA</div>
            <div>
              <h3 class="text-h3 mb-1" style="font-weight:var(--weight-bold)">${t('Khalid Al-Dosari','خالد الدوسري')}</h3>
              <p class="text-body text-secondary d-flex items-center gap-2"><span class="badge badge-primary" style="border-radius:var(--radius-full)">${t('Investor','مستثمر')}</span><span style="opacity:0.5">•</span>${t('Member since Jan 2024','عضو منذ يناير 2024')}</p>
            </div>
          </div>
          <form class="d-flex flex-col gap-6">
            <h4 class="text-h6 mb-2" style="font-weight:var(--weight-bold)">${t('Personal Information','المعلومات الشخصية')}</h4>
            <div class="grid-2" style="gap:var(--space-6)">
              <div class="form-group"><label class="form-label text-body-sm text-secondary">${t('First Name','الاسم الأول')}</label><input type="text" class="form-input" value="${t('Khalid','خالد')}" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;border-radius:var(--radius-lg)"></div>
              <div class="form-group"><label class="form-label text-body-sm text-secondary">${t('Last Name','اسم العائلة')}</label><input type="text" class="form-input" value="${t('Al-Dosari','الدوسري')}" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;border-radius:var(--radius-lg)"></div>
            </div>
            <div class="form-group"><label class="form-label text-body-sm text-secondary">${t('Email','البريد الإلكتروني')}</label><input type="email" class="form-input" value="khalid@example.com" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;border-radius:var(--radius-lg)"></div>
            
            <hr style="border:none;border-top:1px solid var(--border-default);margin:var(--space-4) 0">
            
            <h4 class="text-h6 mb-2" style="font-weight:var(--weight-bold)">${t('Investment Profile (KYC)','الملف الاستثماري (KYC)')}</h4>
            <div class="grid-2" style="gap:var(--space-6)">
              <div class="form-group"><label class="form-label text-body-sm text-secondary">${t('Target Sectors','القطاعات المستهدفة')}</label><input type="text" class="form-input" value="FinTech, PropTech, AI" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;border-radius:var(--radius-lg)"></div>
              <div class="form-group"><label class="form-label text-body-sm text-secondary">${t('Investment Ticket Size','حجم التذكرة الاستثمارية')}</label><input type="text" class="form-input" value="$100K - $500K" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;border-radius:var(--radius-lg)"></div>
            </div>
            <div class="form-group"><label class="form-label text-body-sm text-secondary">${t('KYC Status','حالة التوثيق (KYC)')}</label><div class="d-flex items-center gap-2 mt-2"><span class="badge badge-success badge-dot">${t('Verified','موثق')}</span><span class="text-caption text-secondary">${t('Last verified: Mar 2024','آخر توثيق: مارس 2024')}</span></div></div>

            <button type="button" class="btn btn-primary" style="align-self:flex-start;border-radius:var(--radius-lg);padding:var(--space-3) var(--space-8);margin-top:var(--space-4)">${t('Save Changes','حفظ التغييرات')}</button>
          </form>
        </div>
      </div>

      <!-- Right Column: Account Manager -->
      <div style="grid-column:span 4">
        <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl);background:linear-gradient(135deg, var(--bg-surface) 0%, rgba(59,130,246,0.05) 100%)">
          <div class="d-flex items-center gap-3 mb-6">
            <div style="width:40px;height:40px;border-radius:50%;background:var(--color-primary-lighter);color:var(--action-primary);display:flex;align-items:center;justify-content:center"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
            <h4 class="text-h6" style="font-weight:var(--weight-bold)">${t('Your Account Manager','مدير حسابك')}</h4>
          </div>
          
          <div class="text-center mb-6">
            <div style="width:80px;height:80px;border-radius:50%;background:url('https://i.pravatar.cc/150?img=11') center/cover;margin:0 auto 16px;border:3px solid white;box-shadow:0 4px 12px rgba(0,0,0,0.1)"></div>
            <h5 class="text-h5 mb-1" style="font-weight:var(--weight-bold)">${t('Fahad Al-Saud','فهد آل سعود')}</h5>
            <p class="text-body-sm text-secondary">${t('Senior Investor Relations Manager','مدير أول علاقات المستثمرين')}</p>
          </div>

          <div class="d-flex flex-col gap-3 mb-6">
            <div class="d-flex items-center gap-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="var(--text-secondary)" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg><span class="text-body-sm text-secondary">fahad@seventech.com</span></div>
            <div class="d-flex items-center gap-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="var(--text-secondary)" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg><span class="text-body-sm text-secondary">+966 50 123 4567</span></div>
          </div>

          <button class="btn btn-primary w-full" style="border-radius:var(--radius-lg);box-shadow:0 4px 12px rgba(59,130,246,0.3);background:#3b82f6">${t('Schedule a Meeting','حجز اجتماع')}</button>
        </div>
      </div>
    </div>`;
}

export function investorDashboardPage(tab = 'overview') {
  const titles = { overview: t('Investor Dashboard','لوحة المستثمر'), projects: t('Portfolio Projects','مشاريع المحفظة'), reports: t('Reports','التقارير'), documents: t('Document Center','مركز المستندات'), ndas: t('NDA Center','مركز NDA'), 'exit-requests': t('Exit Requests','طلبات الخروج'), 'exit-records': t('Exit Records','سجلات الخروج'), consultations: t('Consultations','الاستشارات'), events: t('Events','الفعاليات'), profile: t('Profile & Security','الملف الشخصي') };
  const contents = { overview, projects, reports, documents, ndas, 'exit-requests': exitRequests, 'exit-records': exitRecords, consultations, events, profile };
  const content = (contents[tab] || overview)();
  return dashboardLayout(titles[tab] || t('Investor Dashboard','لوحة المستثمر'), 'investor', tab, content);
}

export function investorProjectDetailsPage(id) {
  const projName = id.charAt(0).toUpperCase() + id.slice(1);
  
  const content = `
    <!-- Top Action Bar -->
    <div class="d-flex justify-between items-center mb-6">
      <div class="d-flex items-center gap-4">
        <a href="#/dashboard/investor/projects" class="btn btn-ghost" style="width:40px;height:40px;padding:0;border-radius:50%;display:flex;align-items:center;justify-content:center;background:var(--bg-secondary)">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-h4" style="font-weight:var(--weight-bold)">${projName} — ${t('Project Details','تفاصيل المشروع')}</h2>
      </div>
      <div class="d-flex gap-3">
        <button class="btn btn-secondary btn-sm" style="border-radius:var(--radius-lg)">${t('Download Prospectus','تحميل النشرة')}</button>
        <button class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">${t('Invest Now','استثمر الآن')}</button>
      </div>
    </div>

    <!-- Header & Hero -->
    <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl);display:flex;gap:var(--space-8);align-items:center">
      <div style="width:160px;height:160px;border-radius:var(--radius-xl);background:url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=400') center/cover;box-shadow:0 8px 24px rgba(0,0,0,0.1)"></div>
      <div class="flex-1">
        <div class="d-flex gap-3 mb-2 items-center">
          <span class="badge badge-success badge-dot">${t('Active & Growing','نشط وينمو')}</span>
          <span class="badge badge-neutral">${t('FinTech Sector','قطاع التقنية المالية')}</span>
        </div>
        <h1 class="text-h2 mb-1" style="font-weight:var(--weight-bold)">${projName}</h1>
        <p class="text-h6 text-secondary mb-4">${t('Sub-project','المشروع الفرعي')}: ${projName} Pay Gateway</p>
        <div class="d-flex gap-6">
          <div><div class="text-caption text-secondary">${t('Capital','رأس المال')}</div><div class="text-label" style="font-weight:var(--weight-bold)">$5.2M</div></div>
          <div><div class="text-caption text-secondary">${t('Funding Ask','التمويل المطلوب')}</div><div class="text-label" style="font-weight:var(--weight-bold)">$1.5M</div></div>
          <div><div class="text-caption text-secondary">${t('Total Shares','عدد الأسهم')}</div><div class="text-label" style="font-weight:var(--weight-bold)">1,000,000</div></div>
          <div><div class="text-caption text-secondary">${t('Shareholders','عدد المساهمين')}</div><div class="text-label" style="font-weight:var(--weight-bold)">24</div></div>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid-12" style="gap:var(--space-6)">
      <!-- Left Column: Reports, Growth, Portfolio -->
      <div style="grid-column:span 8">
        
        <!-- Growth Rates Chart -->
        <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">${t('Growth Rates','معدلات النمو')}</h3>
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
          <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">${t('Project Portfolio (Products)','المحفظة الخاصة بالمشروع (المنتجات)')}</h3>
          <div class="grid-2" style="gap:var(--space-4)">
            <div style="padding:var(--space-4);border:1px solid var(--border-default);border-radius:var(--radius-lg)">
              <h4 class="text-label" style="font-weight:var(--weight-bold)">${projName} B2B API</h4>
              <p class="text-caption text-secondary mt-1">${t('Enterprise payment integration','بوابة دفع مخصصة للشركات')}</p>
            </div>
            <div style="padding:var(--space-4);border:1px solid var(--border-default);border-radius:var(--radius-lg)">
              <h4 class="text-label" style="font-weight:var(--weight-bold)">${projName} Wallet App</h4>
              <p class="text-caption text-secondary mt-1">${t('Consumer digital wallet','محفظة رقمية للمستهلكين')}</p>
            </div>
          </div>
        </div>

        <!-- Reports -->
        <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">${t('Financial & Audit Reports','التقارير المالية والتدقيق')}</h3>
          <div class="d-flex flex-col gap-3">
            ${[t('Q1 2026 Financial Statement','البيان المالي للربع الأول 2026'), t('Annual Audit 2025','التدقيق السنوي لعام 2025')].map(r => `
            <div class="d-flex justify-between items-center" style="padding:var(--space-4);background:var(--bg-secondary);border-radius:var(--radius-md)">
              <div class="d-flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="var(--action-primary)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span class="text-body-sm" style="font-weight:var(--weight-medium)">${r}</span>
              </div>
              <button class="btn btn-ghost btn-sm">${t('Download','تحميل')}</button>
            </div>`).join('')}
          </div>
        </div>
      </div>

      <!-- Right Column: Team, Consultants, Exit -->
      <div style="grid-column:span 4">
        <!-- Key Personnel -->
        <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">${t('Key Personnel','الإدارة الرئيسية')}</h3>
          <div class="d-flex flex-col gap-4">
            <div class="d-flex items-center gap-3">
              <div style="width:40px;height:40px;border-radius:50%;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;font-size:20px">👨‍💼</div>
              <div><div class="text-body-sm" style="font-weight:var(--weight-bold)">Ahmad Nasser</div><div class="text-caption text-secondary">${t('CEO','الرئيس التنفيذي (CEO)')}</div></div>
            </div>
            <div class="d-flex items-center gap-3">
              <div style="width:40px;height:40px;border-radius:50%;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;font-size:20px">👨‍💻</div>
              <div><div class="text-body-sm" style="font-weight:var(--weight-bold)">Faisal Omar</div><div class="text-caption text-secondary">${t('Project Manager','مدير المشروع')}</div></div>
            </div>
            <div class="d-flex items-center gap-3">
              <div style="width:40px;height:40px;border-radius:50%;background:var(--color-primary-lighter);color:var(--action-primary);display:flex;align-items:center;justify-content:center;font-size:20px">📞</div>
              <div><div class="text-body-sm" style="font-weight:var(--weight-bold)">Fahad Al-Saud</div><div class="text-caption text-secondary">${t('Account Manager','مدير الحساب الخاص بك')}</div></div>
            </div>
          </div>
        </div>

        <!-- Consultants -->
        <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">${t('Project Consultants','استشاريو المشروع')}</h3>
          <div class="d-flex flex-col gap-3">
            <div class="d-flex justify-between items-center"><span class="text-body-sm">McKinsey & Co.</span><span class="badge badge-neutral">${t('Strategy','استراتيجية')}</span></div>
            <div class="d-flex justify-between items-center"><span class="text-body-sm">PwC</span><span class="badge badge-neutral">${t('Financial','مالي')}</span></div>
          </div>
        </div>

        <!-- Exit Requests -->
        <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl);background:linear-gradient(135deg, var(--bg-surface) 0%, rgba(255,90,0,0.05) 100%)">
          <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold)">${t('Exit Requests','طلبات التخارج')}</h3>
          <p class="text-body-sm text-secondary mb-4">${t('Investors looking to exit or sell shares in this project.','المستثمرون الذين يطلبون التخارج أو بيع أسهمهم في هذا المشروع.')}</p>
          <div class="d-flex justify-between items-center p-3" style="background:var(--bg-surface);border-radius:var(--radius-md);border:1px solid var(--border-default)">
            <span class="text-caption" style="font-weight:var(--weight-medium)">2 ${t('Pending Requests','طلبات معلقة')}</span>
            <button class="btn btn-ghost btn-sm" style="color:var(--action-primary)">${t('View Offers','عرض العروض')}</button>
          </div>
        </div>
      </div>
    </div>
  `;

  return dashboardLayout(projName, 'investor', 'projects', content);
}
