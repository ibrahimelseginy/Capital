/**
 * SEVEN TECH CAPITAL — General User Dashboard (Premium UX)
 * Overview, Saved, Events, Invitations, Downloads, Notifications, Profile
 */
import LangManager from '../language.js';
import { dashboardLayout } from './dashboard-layout.js';

// ─── Shared components ───
function metricCard(value, label, iconSvg, color = 'var(--action-primary)', gradient = 'linear-gradient(135deg, rgba(196,164,119,0.1) 0%, transparent 100%)', link = '', change = '', changeType = 'positive') {
  const tag = link ? 'a' : 'div';
  const href = link ? ` href="${link}"` : '';
  const changeHtml = change ? `
    <div style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:${changeType === 'positive' ? 'var(--color-success-bg)' : 'var(--color-error-bg)'};color:${changeType === 'positive' ? 'var(--color-success)' : 'var(--color-error)'}">
      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="${changeType === 'positive' ? 'M7 17l5-5 5 5' : 'M7 7l5 5 5-5'}"/></svg>
      ${change}
    </div>` : '';
  return `
    <${tag}${href} class="card metric-card reveal" style="padding:var(--space-6); background: var(--bg-surface); border: 1px solid var(--border-default); position:relative; overflow:hidden; transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); box-shadow: 0 2px 8px rgba(0,0,0,0.02); cursor:pointer; text-decoration:none; display:block; color:inherit; border-radius:var(--radius-xl);" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 40px rgba(0,0,0,0.1)';this.style.borderColor='${color}'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)';this.style.borderColor='var(--border-default)'">
      <div style="position:absolute; top:0; right:0; left:0; bottom:0; background:${gradient}; opacity:0.6; pointer-events:none;"></div>
      <div class="d-flex justify-between items-start" style="position:relative; z-index:2">
        <div>
          <div class="text-caption text-secondary mb-2" style="font-weight:var(--weight-semibold);text-transform:uppercase;letter-spacing:1.2px;font-size:11px">${label}</div>
          <div class="text-display-sm" style="font-weight:var(--weight-bold);color:var(--text-primary);letter-spacing:-1px;font-size:2rem;line-height:1">${value}</div>
          ${changeHtml}
        </div>
        <div style="width:52px;height:52px;border-radius:var(--radius-lg);background:${color}12;display:flex;align-items:center;justify-content:center;color:${color};transition:transform 0.3s ease,background 0.3s ease">
          ${iconSvg}
        </div>
      </div>
    </${tag}>
  `;
}

function activityItem(title, desc, time, iconColor = 'var(--action-primary)') {
  return `
    <div class="d-flex gap-4 py-4 activity-row" style="transition:all 0.25s ease; padding-inline:var(--space-3); border-radius:var(--radius-md); cursor:pointer; position:relative" onmouseover="this.style.background='var(--bg-secondary)';this.style.paddingInline='var(--space-4)'" onmouseout="this.style.background='transparent';this.style.paddingInline='var(--space-3)'">
      <div style="width:42px;height:42px;border-radius:var(--radius-md);background:${iconColor}10;display:flex;align-items:center;justify-content:center;color:${iconColor};flex-shrink:0;transition:transform 0.2s ease">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-body-sm" style="font-weight:var(--weight-semibold);color:var(--text-primary);line-height:1.4">${title}</div>
        <div class="text-caption text-secondary" style="margin-top:3px">${desc}</div>
      </div>
      <div class="text-caption text-tertiary flex-shrink-0" style="font-weight:var(--weight-medium);background:var(--bg-secondary);padding:4px 12px;border-radius:var(--radius-full);align-self:center;white-space:nowrap">${time}</div>
    </div>
  `;
}

// ─── Overview ───
function overview() {
  const isAr = LangManager.currentLang === 'ar';
  
  const savedItemsTitle = isAr ? 'العناصر المحفوظة' : 'Saved Items';
  const upcomingEventsTitle = isAr ? 'الفعاليات القادمة' : 'Upcoming Events';
  const invitationsTitle = isAr ? 'الدعوات' : 'Invitations';
  const downloadsTitle = isAr ? 'التنزيلات' : 'Downloads';
  const recentActivityTitle = isAr ? 'النشاط الأخير' : 'Recent Activity';
  const quickActionsTitle = isAr ? 'إجراءات سريعة' : 'Quick Actions';

  const greeting = (() => {
    const h = new Date().getHours();
    if (h < 12) return isAr ? 'صباح الخير' : 'Good Morning';
    if (h < 17) return isAr ? 'مساء الخير' : 'Good Afternoon';
    return isAr ? 'مساء الخير' : 'Good Evening';
  })();

  const bookmarkIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>';
  const calendarIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>';
  const mailIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>';
  const downloadIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>';
  
  return `
    <!-- Welcome Banner -->
    <div class="card mb-8 reveal" style="padding:var(--space-8) var(--space-10);background:linear-gradient(135deg, #0f0f0f 0%, #1a1510 40%, #1a1714 60%, #0f0f0f 100%);border:1px solid rgba(255,90,0,0.15);position:relative;overflow:hidden;border-radius:var(--radius-xl)">
      <div style="position:absolute;top:-60%;right:-15%;width:500px;height:500px;background:radial-gradient(circle,rgba(255,90,0,0.12) 0%,transparent 65%);pointer-events:none;animation:floatGlow 6s ease-in-out infinite alternate"></div>
      <div style="position:absolute;bottom:-40%;left:-5%;width:350px;height:350px;background:radial-gradient(circle,rgba(198,161,91,0.08) 0%,transparent 65%);pointer-events:none"></div>
      <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><defs><pattern id=%22p%22 width=%2260%22 height=%2260%22 patternUnits=%22userSpaceOnUse%22><path d=%22M0 60L60 0%22 stroke=%22rgba(255,255,255,0.02)%22 stroke-width=%221%22/></pattern></defs><rect width=%22100%25%22 height=%22100%25%22 fill=%22url(%23p)%22/></svg>');pointer-events:none"></div>
      <div style="position:relative;z-index:2;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:var(--space-6)">
        <div>
          <div class="text-caption mb-3" style="color:var(--action-primary);font-weight:var(--weight-bold);text-transform:uppercase;letter-spacing:3px;font-size:11px">${greeting}</div>
          <h2 class="text-h2 mb-3" style="color:#fff;font-weight:var(--weight-bold);letter-spacing:-0.5px;line-height:1.2">John Doe 👋</h2>
          <p class="text-body" style="color:rgba(255,255,255,0.55);max-width:500px;line-height:1.6">${isAr ? 'إليك نظرة سريعة على آخر المستجدات في حسابك اليوم.' : "Here's a quick look at what's happening with your account today."}</p>
        </div>
        <div style="display:flex;gap:var(--space-3)">
          <a href="#/events" class="btn btn-primary" style="border-radius:var(--radius-full);padding:var(--space-3) var(--space-6);font-size:13px;box-shadow:0 4px 16px rgba(255,90,0,0.3)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/></svg>
            ${isAr ? 'الفعاليات' : 'Events'}
          </a>
          <a href="#/content" class="btn btn-secondary" style="border-radius:var(--radius-full);padding:var(--space-3) var(--space-6);font-size:13px;border-color:rgba(255,255,255,0.2);color:rgba(255,255,255,0.8)" onmouseover="this.style.borderColor='var(--action-primary)';this.style.color='#fff'" onmouseout="this.style.borderColor='rgba(255,255,255,0.2)';this.style.color='rgba(255,255,255,0.8)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
            ${isAr ? 'المكتبة' : 'Library'}
          </a>
        </div>
      </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid-4 mb-8">
      ${metricCard('5', savedItemsTitle, bookmarkIcon, 'var(--action-primary)', 'linear-gradient(135deg, rgba(255,90,0,0.06) 0%, transparent 100%)', '#/dashboard/saved', '+2 ${isAr ? "هذا الأسبوع" : "this week"}', 'positive')}
      ${metricCard('3', upcomingEventsTitle, calendarIcon, '#3b82f6', 'linear-gradient(135deg, rgba(59,130,246,0.06) 0%, transparent 100%)', '#/dashboard/my-events', isAr ? 'القادمة' : 'upcoming', 'positive')}
      ${metricCard('2', invitationsTitle, mailIcon, '#8b5cf6', 'linear-gradient(135deg, rgba(139,92,246,0.06) 0%, transparent 100%)', '#/dashboard/invitations', isAr ? 'جديدة' : 'new', 'positive')}
      ${metricCard('7', downloadsTitle, downloadIcon, '#10b981', 'linear-gradient(135deg, rgba(16,185,129,0.06) 0%, transparent 100%)', '#/dashboard/downloads', '1.2 GB', 'positive')}
    </div>

    <!-- Content Grid -->
    <div class="grid-12" style="gap:var(--space-6)">
      <!-- Recent Activity -->
      <div style="grid-column:span 8">
        <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <div class="d-flex justify-between items-center mb-6" style="padding-bottom:var(--space-4);border-bottom:1px solid var(--border-subtle)">
            <div class="d-flex items-center gap-3">
              <div style="width:36px;height:36px;border-radius:var(--radius-md);background:var(--color-primary-lighter);display:flex;align-items:center;justify-content:center;color:var(--action-primary)">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
              </div>
              <h3 class="text-h5" style="font-weight:var(--weight-bold)">${recentActivityTitle}</h3>
            </div>
            <a href="#/dashboard/notifications" class="btn btn-ghost btn-sm" style="color:var(--action-primary);border-radius:var(--radius-full);padding:var(--space-2) var(--space-4)">${isAr ? 'عرض الكل' : 'View All'} →</a>
          </div>
          ${activityItem(isAr ? 'حفظ "FinFlow" في المجموعة' : 'Saved "FinFlow" to collection', isAr ? 'تمت الإضافة إلى المشاريع المحفوظة' : 'Added to your saved projects', isAr ? 'منذ ساعتين' : '2h ago', 'var(--action-primary)')}
          ${activityItem(isAr ? 'تم التسجيل في يوم العروض 2026' : 'Registered for Demo Day 2026', isAr ? 'فعالية في 15 يوليو، الرياض' : 'Event on Jul 15, Riyadh', isAr ? 'منذ 5 ساعات' : '5h ago', '#3b82f6')}
          ${activityItem(isAr ? 'تحميل دليل الابتكار' : 'Downloaded Venture Playbook', isAr ? 'مستند PDF - 2.4 ميغابايت' : 'PDF document - 2.4 MB', isAr ? 'منذ يوم' : '1d ago', '#10b981')}
          ${activityItem(isAr ? 'مشاهدة مشروع "DataPulse"' : 'Viewed "DataPulse" project', isAr ? 'صفحة تفاصيل المشروع' : 'Project detail page', isAr ? 'منذ يومين' : '2d ago', '#8b5cf6')}
          ${activityItem(isAr ? 'تحديث معلومات الملف الشخصي' : 'Updated profile information', isAr ? 'تم تحديث البريد الإلكتروني ورقم الهاتف' : 'Email and phone number updated', isAr ? 'منذ 3 أيام' : '3d ago', 'var(--accent-gold)')}
        </div>
      </div>

      <!-- Right Column -->
      <div style="grid-column:span 4">
        <!-- Quick Actions -->
        <div class="card mb-5" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <div class="d-flex items-center gap-3 mb-5">
            <div style="width:36px;height:36px;border-radius:var(--radius-md);background:var(--color-primary-lighter);display:flex;align-items:center;justify-content:center;color:var(--action-primary)">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg>
            </div>
            <h3 class="text-h5" style="font-weight:var(--weight-bold)">${quickActionsTitle}</h3>
          </div>
          <div class="d-flex flex-col gap-1">
            ${[
              { href: '#/events', icon: '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>', label: isAr ? 'تصفح الفعاليات' : 'Browse Events', color: '#3b82f6' },
              { href: '#/blogs', icon: '<path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>', label: isAr ? 'اقرأ المقالات' : 'Read Articles', color: '#10b981' },
              { href: '#/jobs', icon: '<rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>', label: isAr ? 'عرض الوظائف' : 'View Jobs', color: '#8b5cf6' },
              { href: '#/content', icon: '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>', label: isAr ? 'مكتبة المحتوى' : 'Content Library', color: 'var(--action-primary)' },
            ].map(a => `
            <a href="${a.href}" class="btn btn-ghost w-full justify-start gap-3" style="padding:var(--space-3) var(--space-4);border-radius:var(--radius-lg);transition:all 0.2s ease;text-decoration:none;color:var(--text-primary)" onmouseover="this.style.background='${a.color}08';this.style.transform='translateX(4px)'" onmouseout="this.style.background='transparent';this.style.transform='translateX(0)'">
              <div style="width:32px;height:32px;border-radius:var(--radius-md);background:${a.color}10;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="${a.color}" stroke-width="2">${a.icon}</svg>
              </div>
              <span style="font-weight:var(--weight-medium);font-size:13px">${a.label}</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="2" style="margin-inline-start:auto;opacity:0.5"><path d="m9 18 6-6-6-6"/></svg>
            </a>`).join('')}
          </div>
        </div>

        <!-- Upgrade Card -->
        <div class="card" style="padding:var(--space-6);border:1px solid rgba(255,90,0,0.2);background:linear-gradient(135deg, rgba(255,90,0,0.04) 0%, transparent 60%);position:relative;overflow:hidden;border-radius:var(--radius-xl)">
          <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;background:radial-gradient(circle,rgba(255,90,0,0.1) 0%,transparent 65%);pointer-events:none"></div>
          <div style="position:relative;z-index:2">
            <div class="d-flex items-center gap-3 mb-4">
              <div style="width:40px;height:40px;border-radius:var(--radius-lg);background:linear-gradient(135deg, rgba(255,90,0,0.15) 0%, rgba(198,161,91,0.15) 100%);display:flex;align-items:center;justify-content:center;color:var(--action-primary)">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"/></svg>
              </div>
              <div>
                <h4 class="text-label" style="font-weight:var(--weight-bold);line-height:1.2">${isAr ? 'ترقية حسابك' : 'Upgrade Your Account'}</h4>
                <p class="text-caption text-tertiary" style="margin-top:2px">${isAr ? 'ميزات حصرية' : 'Exclusive features'}</p>
              </div>
            </div>
            <p class="text-caption text-secondary mb-5" style="line-height:1.6">${isAr ? 'انضم كمستثمر أو رائد أعمال للوصول إلى ميزات حصرية ومحتوى متقدم.' : 'Apply as an Investor or Entrepreneur to access exclusive features and premium content.'}</p>
            <a href="#/onboarding/investor" class="btn btn-primary btn-sm w-full" style="box-shadow:0 4px 16px rgba(255,90,0,0.25);border-radius:var(--radius-lg);padding:var(--space-3)">${isAr ? 'انضم الآن' : 'Apply Now'} →</a>
          </div>
        </div>
      </div>
    </div>`;
}

// ─── Saved Items ───
function saved() {
  const isAr = typeof LangManager !== 'undefined' && LangManager.currentLang === 'ar';
  const t = (en, ar) => isAr ? ar : en;
  
  const title = t('Saved Items', 'العناصر المحفوظة');
  const tabAll = t('All', 'الكل');
  const tabProjects = t('Projects', 'مشاريع');
  const tabArticles = t('Articles', 'مقالات');
  const tabEvents = t('Events', 'فعاليات');
  const savedOn = t('Saved on Jun 5, 2026', 'تم الحفظ في 5 يونيو 2026');

  const items = isAr 
    ? ['FinFlow|التقنية المالية|نشط|https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=400&q=80','DataPulse|الذكاء الاصطناعي والبيانات|قيد التوسع|https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=400&q=80','BuildOS|التقنية العقارية|قيد البناء|https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80']
    : ['FinFlow|FinTech|Active|https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=400&q=80','DataPulse|AI & Data|Scaling|https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=400&q=80','BuildOS|PropTech|Building|https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80'];

  return `
    <div class="d-flex justify-between items-center mb-8">
      <h2 class="text-h4" style="font-weight:var(--weight-bold);letter-spacing:-0.5px">${title}</h2>
      <div class="d-flex gap-3">
        <button class="chip active" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-full)">${tabAll}</button>
        <button class="chip" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-full)">${tabProjects}</button>
        <button class="chip" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-full)">${tabArticles}</button>
        <button class="chip" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-full)">${tabEvents}</button>
      </div>
    </div>
    <div class="grid-3 reveal">
      ${items.map(item => {
        const [name, cat, status, img] = item.split('|');
        return `<div class="card card-hover" style="overflow:hidden;border:1px solid var(--border-default);background:var(--bg-surface);transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);padding:0;border-radius:var(--radius-xl)" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 40px rgba(0,0,0,0.1)';this.style.borderColor='var(--action-primary)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='var(--shadow-md)';this.style.borderColor='var(--border-default)'">
          <div style="height:170px;position:relative;overflow:hidden;">
            <img src="${img}" alt="${name}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s ease" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" />
            <div style="position:absolute;top:var(--space-3);right:var(--space-3)">
              <button style="width:32px;height:32px;border-radius:50%;background:rgba(0,0,0,0.5);backdrop-filter:blur(8px);border:none;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s" onmouseover="this.style.background='var(--action-primary)'" onmouseout="this.style.background='rgba(0,0,0,0.5)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
              </button>
            </div>
          </div>
          <div class="card-body" style="padding:var(--space-5)">
            <div class="d-flex justify-between items-center mb-3">
              <span class="badge badge-primary" style="font-weight:var(--weight-semibold);border-radius:var(--radius-full)">${cat}</span>
              <span class="badge badge-success badge-dot" style="background:transparent;border:1px solid rgba(46,204,113,0.2);border-radius:var(--radius-full)">${status}</span>
            </div>
            <h3 class="text-h5 mb-2" style="color:var(--text-primary);font-weight:var(--weight-bold)">${name}</h3>
            <p class="text-caption text-secondary">${savedOn}</p>
          </div>
        </div>`;
      }).join('')}
    </div>`;
}

// ─── My Events ───
function myEvents() {
  const isAr = LangManager.currentLang === 'ar';
  
  const title = isAr ? 'فعالياتي' : 'My Events';
  const tabUpcoming = isAr ? 'القادمة' : 'Upcoming';
  const tabPast = isAr ? 'السابقة' : 'Past';
  const attendeesLabel = isAr ? 'حاضر' : 'Attendees';
  const detailsBtn = isAr ? 'التفاصيل ←' : 'Details →';

  return `
    <div class="d-flex justify-between items-center mb-8"><h2 class="text-h4" style="font-weight:var(--weight-bold);letter-spacing:-0.5px">${title}</h2><div class="d-flex gap-3"><button class="chip active" style="border-radius:var(--radius-full)">${tabUpcoming}</button><button class="chip" style="border-radius:var(--radius-full)">${tabPast}</button></div></div>
    <div class="grid-2 reveal">
      ${[
        [isAr?'يوم عروض المشاريع 2026':'Venture Demo Day 2026', isAr?'يوليو 15, 2026':'Jul 15, 2026', isAr?'الرياض، السعودية':'Riyadh, KSA', isAr?'مسجل':'Registered', isAr?'يوم عروض':'Demo Day', '150'],
        [isAr?'لقاء المستثمرين: تحديث الربع الثاني':'Investor Briefing Q2', isAr?'يوليو 28, 2026':'Jul 28, 2026', isAr?'عبر الإنترنت':'Online', isAr?'مسجل':'Registered', isAr?'ندوة':'Webinar', '45'],
        [isAr?'ورشة عمل المؤسسين':'Founder Workshop', isAr?'أغسطس 10, 2026':'Aug 10, 2026', isAr?'دبي، الإمارات':'Dubai, UAE', isAr?'قيد الانتظار':'Pending', isAr?'ورشة عمل':'Workshop', '20'],
      ].map(([name, date, loc, status, cat, attendees]) => `
      <div class="card d-flex flex-col" style="overflow:hidden;padding:0;background:var(--bg-surface);border:1px solid var(--border-default);transition:all 0.3s ease;cursor:pointer;border-radius:var(--radius-xl)" onclick="const details = this.querySelector('.event-details'); const btn = this.querySelector('.details-btn'); if(details.style.display==='none'){details.style.display='block';btn.textContent='${isAr ? 'إخفاء' : 'Hide'}';}else{details.style.display='none';btn.textContent='${detailsBtn}';}" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='var(--shadow-md)'">
        <div class="d-flex w-full" style="position:relative">
          <div style="width:5px;background:linear-gradient(to bottom, var(--action-primary), var(--accent-gold));flex-shrink:0;border-radius:4px 0 0 0"></div>
          <div style="padding:var(--space-6);flex:1;display:flex;align-items:center;justify-content:space-between;gap:var(--space-5)">
            <div class="d-flex gap-5 items-center">
              <div style="min-width:72px;text-align:center;padding:var(--space-3);background:linear-gradient(to bottom, rgba(255,90,0,0.08), transparent);border:1px solid rgba(255,90,0,0.15);border-radius:var(--radius-lg)">
                <div class="text-caption text-secondary" style="text-transform:uppercase;letter-spacing:1px;font-weight:var(--weight-semibold)">${date.split(' ')[0]}</div>
                <div class="text-h3 text-primary" style="color:var(--action-primary)">${date.split(' ')[1].replace(',','')}</div>
              </div>
              <div>
                <div class="text-h5 mb-1" style="font-weight:var(--weight-bold)">${name}</div>
                <div class="text-body-sm text-secondary d-flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                  ${loc} <span style="opacity:0.5">•</span> ${cat}
                </div>
                <div class="text-caption text-tertiary mt-2 d-flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                  ${attendees} ${attendeesLabel}
                </div>
              </div>
            </div>
            <div class="d-flex flex-col items-end gap-3">
              <span class="badge ${status==='Registered'||status==='مسجل'?'badge-success':'badge-warning'} badge-dot" style="font-weight:var(--weight-medium);background:transparent;border:1px solid currentColor;border-radius:var(--radius-full)">${status}</span>
              <button class="btn btn-ghost btn-sm text-accent details-btn" style="padding:var(--space-2) var(--space-4);border-radius:var(--radius-full)" onclick="event.stopPropagation(); this.closest('.card').click()">${detailsBtn}</button>
            </div>
          </div>
        </div>
        <div class="event-details" style="display:none;padding:var(--space-6);border-top:1px solid var(--border-default);background:var(--bg-primary);animation:fadeIn 0.3s ease">
          <div class="grid-2" style="gap:var(--space-5)">
            <div>
              <p class="text-caption text-secondary mb-1">${isAr?'المنظم':'Organizer'}</p>
              <p class="text-body-sm" style="font-weight:var(--weight-semibold)">SEVEN TECH CAPITAL</p>
            </div>
            <div>
              <p class="text-caption text-secondary mb-1">${isAr?'الوقت':'Time'}</p>
              <p class="text-body-sm" style="font-weight:var(--weight-semibold)">10:00 AM - 02:00 PM (AST)</p>
            </div>
            <div style="grid-column:1/-1">
              <p class="text-caption text-secondary mb-1">${isAr?'الوصف':'Description'}</p>
              <p class="text-body-sm" style="line-height:1.6">${isAr?'انضم إلينا في هذه الفعالية الحصرية التي تربط كبار المؤسسين مع المستثمرين البارزين والشركاء الاستراتيجيين. اكتشف أحدث الابتكارات التي ترسم ملامح المستقبل.':'Join us for an exclusive event connecting top-tier founders with leading venture capitalists and strategic partners. Discover the latest innovations shaping the future.'}</p>
            </div>
            
            <div style="grid-column:1/-1; margin-top: var(--space-4); padding-top: var(--space-4); border-top: 1px dashed var(--border-default);">
              <h4 class="text-body mb-4" style="font-weight:var(--weight-bold); color:var(--text-primary)">${isAr?'المتحدثون':'Speakers'}</h4>
              <div class="d-flex gap-4 flex-wrap">
                <div class="d-flex items-center gap-3">
                  <div style="width:44px;height:44px;border-radius:50%;background:var(--bg-secondary);background-image:url('https://images.unsplash.com/photo-1560250097-0b93528c311a?w=100&h=100&fit=crop');background-size:cover;border:2px solid var(--action-primary)"></div>
                  <div><div class="text-body-sm" style="font-weight:var(--weight-semibold)">${isAr?'د. أحمد عبدالله':'Dr. Ahmed Abdullah'}</div><div class="text-caption text-secondary">CEO, STC</div></div>
                </div>
                <div class="d-flex items-center gap-3">
                  <div style="width:44px;height:44px;border-radius:50%;background:var(--bg-secondary);background-image:url('https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&h=100&fit=crop');background-size:cover;border:2px solid var(--action-primary)"></div>
                  <div><div class="text-body-sm" style="font-weight:var(--weight-semibold)">${isAr?'سارة التميمي':'Sarah Al-Tamimi'}</div><div class="text-caption text-secondary">Partner, STC</div></div>
                </div>
              </div>
            </div>

            <div style="grid-column:1/-1; margin-top: var(--space-4); padding-top: var(--space-4); border-top: 1px dashed var(--border-default);">
              <h4 class="text-body mb-4" style="font-weight:var(--weight-bold); color:var(--text-primary)">${isAr?'برنامج الفعالية':'Event Program'}</h4>
              <ul style="margin:0;padding:0;list-style:none;display:flex;flex-direction:column;gap:var(--space-3)">
                <li class="d-flex gap-4 items-center">
                  <div class="text-secondary" style="font-size:13px;width:75px;text-align:right">10:00 AM</div>
                  <div style="width:10px;height:10px;border-radius:50%;background:var(--action-primary);box-shadow:0 0 0 3px rgba(255,90,0,0.15)"></div>
                  <div class="text-body-sm" style="font-weight:var(--weight-medium)">${isAr?'الكلمة الافتتاحية':'Keynote Speech'}</div>
                </li>
                <li class="d-flex gap-4 items-center">
                  <div class="text-secondary" style="font-size:13px;width:75px;text-align:right">11:30 AM</div>
                  <div style="width:10px;height:10px;border-radius:50%;background:var(--border-strong)"></div>
                  <div class="text-body-sm" style="font-weight:var(--weight-medium)">${isAr?'حلقة نقاش المستثمرين':'Investor Panel'}</div>
                </li>
                <li class="d-flex gap-4 items-center">
                  <div class="text-secondary" style="font-size:13px;width:75px;text-align:right">01:00 PM</div>
                  <div style="width:10px;height:10px;border-radius:50%;background:var(--border-strong)"></div>
                  <div class="text-body-sm" style="font-weight:var(--weight-medium)">${isAr?'تواصل الغداء':'Networking Lunch'}</div>
                </li>
              </ul>
            </div>

            <div style="grid-column:1/-1; margin-top: var(--space-4); padding-top: var(--space-4); border-top: 1px dashed var(--border-default);">
              <div class="d-flex justify-between items-center" style="background:var(--bg-secondary);padding:var(--space-4);border-radius:var(--radius-xl);border:1px solid var(--border-default)">
                <div class="d-flex items-center gap-4">
                  <div style="width:48px;height:48px;border-radius:var(--radius-lg);background:var(--bg-surface);display:flex;align-items:center;justify-content:center;color:var(--action-primary);box-shadow:var(--shadow-sm)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="8" x2="8" y1="3" y2="21"/><line x1="16" x2="16" y1="3" y2="21"/></svg>
                  </div>
                  <div>
                    <h4 class="text-body mb-1" style="font-weight:var(--weight-bold)">${isAr?'بطاقة الدخول':'Entry Ticket'}</h4>
                    <p class="text-caption text-secondary">${isAr?'استخدم هذه البطاقة للدخول إلى الفعالية':'Use this card for event entry'}</p>
                  </div>
                </div>
                <button class="btn btn-primary" style="border-radius:var(--radius-lg)" onclick="event.stopPropagation(); const btn=this; btn.innerHTML='${isAr?'تم التوليد ✓':'Generated ✓'}'; btn.style.background='var(--color-success)'; setTimeout(()=>alert('${isAr?'تم إنشاء بطاقة الدخول وتحميلها!':'Entry ticket generated and downloaded!'}'),300);">
                  ${isAr?'توليد البطاقة':'Generate Card'}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>`).join('')}
    </div>`;
}

// ─── Invitations ───
function invitations() {
  const isAr = LangManager.currentLang === 'ar';
  
  const title = isAr ? 'بطاقات الدعوة' : 'Invitation Cards';
  const tabs = isAr ? ['نشطة','مستخدمة','منتهية'] : ['Active','Used','Expired'];
  
  return `
    <div class="d-flex justify-between items-center mb-8"><h2 class="text-h4" style="font-weight:var(--weight-bold);letter-spacing:-0.5px">${title}</h2><div class="d-flex gap-3"><button class="chip active" style="border-radius:var(--radius-full)">${tabs[0]}</button><button class="chip" style="border-radius:var(--radius-full)">${tabs[1]}</button><button class="chip" style="border-radius:var(--radius-full)">${tabs[2]}</button></div></div>
    <div class="grid-2 reveal">
      ${[
        [isAr?'دعوة VIP — يوم عروض 2026':'VIP Invitation — Demo Day 2026', isAr?'دخول 1 · 15 يوليو 2026 · الرياض':'Admit 1 · Jul 15, 2026 · Riyadh', isAr?'نشط':'Active', 'var(--action-primary)', 'SEVEN-VIP-2026'],
        [isAr?'أمسية التعارف':'Networking Evening', isAr?'دخول 1 · 20 سبتمبر 2026 · جدة':'Admit 1 · Sep 20, 2026 · Jeddah', isAr?'قيد الانتظار':'Pending', 'var(--text-secondary)', 'SEVEN-NET-2026'],
      ].map(([invTitle, meta, status, color, qrData]) => `
      <div class="card" style="padding:var(--space-6);background:var(--bg-surface);border:1px solid var(--border-default);border-top:4px solid ${color};transition:all 0.3s ease;border-radius:var(--radius-xl)" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='var(--shadow-md)'">
        <div class="d-flex justify-between items-start mb-6">
          <div>
            <h3 class="text-h4 mb-2" style="color:var(--text-primary);font-weight:var(--weight-bold)">${invTitle}</h3>
            <div class="text-body-sm text-secondary d-flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
              ${meta}
            </div>
          </div>
          <span class="badge ${status==='Active'||status==='نشط'?'badge-success':'badge-warning'} badge-dot" style="background:transparent;border:1px solid currentColor;border-radius:var(--radius-full)">${status}</span>
        </div>
        <div style="width:160px;height:160px;background:#fff;border:1px solid #ddd;border-radius:var(--radius-xl);display:flex;align-items:center;justify-content:center;margin:var(--space-6) auto;box-shadow:0 8px 24px rgba(0,0,0,0.08);position:relative;overflow:hidden;padding:10px">
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=${qrData}" alt="QR Code" width="140" height="140" style="display:block;filter:grayscale(1) contrast(1.2)">
        </div>
        <div class="d-flex gap-3 mt-6">
          <button class="btn btn-primary flex-1" style="padding:var(--space-3);border-radius:var(--radius-lg)">${isAr ? 'عرض رمز الاستجابة' : 'Show QR'}</button>
          <button class="btn btn-secondary flex-1" style="padding:var(--space-3);border-radius:var(--radius-lg)">${isAr ? 'تنزيل PDF' : 'Download PDF'}</button>
        </div>
      </div>`).join('')}
    </div>`;
}

// ─── Downloads ───
function downloads() {
  const isAr = LangManager.currentLang === 'ar';
  
  const title = isAr ? 'التنزيلات' : 'Downloads';
  const th1 = isAr ? 'اسم الملف' : 'File Name';
  const th2 = isAr ? 'النوع' : 'Type';
  const th3 = isAr ? 'الحجم' : 'Size';
  const th4 = isAr ? 'تاريخ التنزيل' : 'Downloaded';
  const btn = isAr ? 'إعادة تنزيل' : 'Re-download';
  
  return `
    <div class="d-flex justify-between items-center mb-8">
      <h2 class="text-h4" style="font-weight:var(--weight-bold);letter-spacing:-0.5px">${title}</h2>
      <div class="text-body-sm text-secondary d-flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
        ${isAr ? '5 ملفات · 15.9 ميغابايت' : '5 files · 15.9 MB total'}
      </div>
    </div>
    <div class="table-wrapper reveal" style="border-radius:var(--radius-xl)">
      <table class="table" style="width:100%;border-collapse:separate;border-spacing:0 8px;padding:var(--space-3)">
        <thead>
          <tr>
            <th style="text-align:start;color:var(--text-secondary);font-weight:var(--weight-medium);padding-bottom:var(--space-4);border-bottom:1px solid var(--border-default)">${th1}</th>
            <th style="text-align:start;color:var(--text-secondary);font-weight:var(--weight-medium);padding-bottom:var(--space-4);border-bottom:1px solid var(--border-default)">${th2}</th>
            <th style="text-align:start;color:var(--text-secondary);font-weight:var(--weight-medium);padding-bottom:var(--space-4);border-bottom:1px solid var(--border-default)">${th3}</th>
            <th style="text-align:start;color:var(--text-secondary);font-weight:var(--weight-medium);padding-bottom:var(--space-4);border-bottom:1px solid var(--border-default)">${th4}</th>
            <th style="text-align:end;padding-bottom:var(--space-4);border-bottom:1px solid var(--border-default)"></th>
          </tr>
        </thead>
        <tbody>
          ${[
            [isAr?'دليل استوديو المشاريع':'Venture Studio Playbook','PDF','2.4 MB',isAr?'5 يونيو 2026':'Jun 5, 2026','#e74c3c'],
            [isAr?'ملخص تقرير الربع الأول 2026':'Q1 2026 Report Summary','PDF','1.8 MB',isAr?'28 مايو 2026':'May 28, 2026','#e74c3c'],
            [isAr?'دليل المستثمر 2026':'Investor Guide 2026','PDF','3.2 MB',isAr?'15 مايو 2026':'May 15, 2026','#e74c3c'],
            [isAr?'أجندة يوم العروض':'Demo Day Agenda','PDF','450 KB',isAr?'10 مايو 2026':'May 10, 2026','#e74c3c'],
            [isAr?'إرشادات العلامة التجارية':'Brand Guidelines','ZIP','8.1 MB',isAr?'20 أبريل 2026':'Apr 20, 2026','#f39c12'],
          ].map(([name, type, size, date, color]) => `
          <tr style="background:var(--bg-surface);box-shadow:var(--shadow-sm);transition:all 0.2s ease;cursor:pointer;border-radius:var(--radius-lg)" onmouseover="this.style.transform='translateX(${isAr?'-':''}4px)';this.style.boxShadow='0 4px 16px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='translateX(0)';this.style.boxShadow='var(--shadow-sm)'">
            <td style="padding:var(--space-4);border-radius:${isAr?'0 var(--radius-lg) var(--radius-lg) 0':'var(--radius-lg) 0 0 var(--radius-lg)'}">
              <div class="d-flex gap-4 items-center">
                <div style="width:42px;height:42px;background:${type === 'ZIP' ? 'rgba(243,156,18,0.1)' : 'rgba(231,76,60,0.1)'};color:${type === 'ZIP' ? '#f39c12' : '#e74c3c'};display:flex;align-items:center;justify-content:center;border-radius:var(--radius-md);font-size:11px;font-weight:700;letter-spacing:0.5px">
                  ${type}
                </div>
                <div style="font-weight:var(--weight-semibold);color:var(--text-primary)">${name}</div>
              </div>
            </td>
            <td style="padding:var(--space-4);text-align:start"><span class="badge" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:var(--radius-full)">${type}</span></td>
            <td style="padding:var(--space-4);color:var(--text-secondary);text-align:start;font-weight:var(--weight-medium)">${size}</td>
            <td style="padding:var(--space-4);color:var(--text-secondary);text-align:start">${date}</td>
            <td style="padding:var(--space-4);border-radius:${isAr?'var(--radius-lg) 0 0 var(--radius-lg)':'0 var(--radius-lg) var(--radius-lg) 0'};text-align:end">
              <button class="btn btn-ghost btn-sm" style="color:var(--action-primary);background:rgba(255,90,0,0.05);border-radius:var(--radius-full);padding:var(--space-2) var(--space-4)" onmouseover="this.style.background='rgba(255,90,0,0.12)'" onmouseout="this.style.background='rgba(255,90,0,0.05)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-inline-end:4px"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                ${btn}
              </button>
            </td>
          </tr>`).join('')}
        </tbody>
      </table>
    </div>`;
}

// ─── Notifications ───
function notifications() {
  const isAr = typeof LangManager !== 'undefined' && LangManager.currentLang === 'ar';
  
  const title = isAr ? 'الإشعارات' : 'Notifications';
  const markRead = isAr ? 'تحديد الكل كمقروء' : 'Mark All Read';
  
  const getIcon = (type) => {
    if (type === 'events') return `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>`;
    if (type === 'success') return `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`;
    return `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`;
  };
  
  const getColor = (type) => {
    if (type === 'events') return 'var(--action-primary)';
    if (type === 'success') return '#10b981';
    return '#3b82f6';
  };

  return `
    <div class="d-flex justify-between items-center mb-8">
      <h2 class="text-h4" style="font-weight:var(--weight-bold);letter-spacing:-0.5px">${title}</h2>
      <button class="btn btn-ghost btn-sm d-flex items-center gap-2" style="color:var(--action-primary); border: 1px solid rgba(255,90,0,0.2); border-radius: var(--radius-full); padding: 6px 16px; background: rgba(255,90,0,0.04); transition: all 0.3s;" onmouseover="this.style.background='rgba(255,90,0,0.12)'" onmouseout="this.style.background='rgba(255,90,0,0.04)'">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        ${markRead}
      </button>
    </div>
    <div class="d-flex flex-col gap-3 reveal">
      ${[
        [isAr?'تمت إضافة فعالية جديدة: قمة التقنية':'New event added: MENA Tech Summit','events',isAr?'منذ ساعتين':'2h ago', true],
        [isAr?'تم تأكيد تسجيلك في الفعالية':'Your event registration confirmed','success',isAr?'منذ 5 ساعات':'5h ago', true],
        [isAr?'مقال جديد: البناء للتوسع':'New article: Building for Scale','info',isAr?'منذ يوم':'1d ago', false],
        [isAr?'تم تحديث الملف الشخصي بنجاح':'Profile update successful','success',isAr?'منذ يومين':'2d ago', false],
        [isAr?'محتوى جديد متاح: تقرير الربع الثاني':'New content available: Q2 Report','info',isAr?'منذ 3 أيام':'3d ago', false],
      ].map(([msg, type, time, unread]) => {
        const color = getColor(type);
        return `
        <div class="card d-flex gap-4" style="padding:var(--space-4) var(--space-5);background:${unread ? 'var(--bg-surface)' : 'var(--bg-secondary)'};border:1px solid ${unread ? 'var(--border-default)' : 'transparent'};border-inline-start:4px solid ${unread ? color : 'transparent'};border-radius:var(--radius-xl);transition:all 0.25s ease;cursor:pointer;position:relative;overflow:hidden" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.borderColor='${color}'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='${unread ? 'var(--shadow-sm)' : 'none'}';this.style.borderColor='${unread ? 'var(--border-default)' : 'transparent'}'">
          ${unread ? `<div style="position:absolute;top:-20px;right:-20px;width:100px;height:100px;background:radial-gradient(circle, ${color}10 0%, transparent 70%);pointer-events:none"></div>` : ''}
          <div style="width:40px;height:40px;border-radius:var(--radius-lg);background:${color}12;color:${color};display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:2">
            ${getIcon(type)}
          </div>
          <div class="flex-1 min-w-0 d-flex flex-col justify-center" style="position:relative;z-index:2">
            <div class="text-body" style="font-weight:${unread ? 'var(--weight-bold)' : 'var(--weight-medium)'};color:${unread ? 'var(--text-primary)' : 'var(--text-secondary)'};font-size:0.95rem;margin-bottom:4px">${msg}</div>
            <div class="text-caption d-flex items-center gap-2" style="color:var(--text-tertiary);font-weight:var(--weight-medium)">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              ${time}
            </div>
          </div>
          ${unread ? `<div style="width:8px;height:8px;border-radius:50%;background:${color};align-self:center;box-shadow:0 0 8px ${color}60;position:relative;z-index:2;flex-shrink:0;margin-inline-start:var(--space-3)"></div>` : ''}
        </div>`;
      }).join('')}
    </div>`;
}

// ─── Profile & Security ───
function profile() {
  const isAr = LangManager.currentLang === 'ar';
  
  const title = isAr ? 'الملف الشخصي والأمان' : 'Profile & Security';
  const tab1 = isAr ? 'المعلومات الشخصية' : 'Personal Info';
  const tab2 = isAr ? 'الأمان' : 'Security';
  const tab3 = isAr ? 'التفضيلات' : 'Preferences';
  
  const role = isAr ? 'مستخدم عام' : 'General User';
  const memberSince = isAr ? 'عضو منذ يناير 2026' : 'Member since Jan 2026';
  const fname = isAr ? 'الاسم الأول' : 'First Name';
  const lname = isAr ? 'الاسم الأخير' : 'Last Name';
  const email = isAr ? 'البريد الإلكتروني' : 'Email Address';
  const phone = isAr ? 'رقم الهاتف' : 'Phone Number';
  const country = isAr ? 'الدولة' : 'Country';
  const bio = isAr ? 'النبذة التعريفية' : 'Bio';
  const saveBtn = isAr ? 'حفظ التغييرات' : 'Save Changes';
  
  const changePass = isAr ? 'تغيير كلمة المرور' : 'Change Password';
  const passDesc = isAr ? 'تأكد من استخدام كلمة مرور طويلة وعشوائية للبقاء آمناً.' : 'Ensure your account is using a long, random password to stay secure.';
  const curPass = isAr ? 'كلمة المرور الحالية' : 'Current Password';
  const newPass = isAr ? 'كلمة المرور الجديدة' : 'New Password';
  const confPass = isAr ? 'تأكيد كلمة المرور' : 'Confirm New Password';
  const updatePass = isAr ? 'تحديث كلمة المرور' : 'Update Password';
  
  const tfa = isAr ? 'المصادقة الثنائية (2FA)' : 'Two-Factor Authentication';
  const authApp = isAr ? 'تطبيق المصادقة' : 'Authenticator App';
  const disabled = isAr ? 'معطل' : 'Disabled';
  const tfaDesc = isAr ? 'أضف طبقة أمان إضافية لحسابك باستخدام تطبيق المصادقة.' : 'Add an extra layer of security to your account using an authenticator app.';
  const enable2fa = isAr ? 'تفعيل 2FA' : 'Enable 2FA';
  
  const sessions = isAr ? 'الجلسات النشطة' : 'Active Sessions';
  const currentSession = isAr ? 'الجلسة الحالية' : 'Current session';
  const revoke = isAr ? 'إلغاء' : 'Revoke';
  
  return `
  <div class="tab-container">
    <h2 class="text-h4 mb-8" style="font-weight:var(--weight-bold);letter-spacing:-0.5px">${title}</h2>
    <div class="tabs mb-8" style="border-bottom:1px solid var(--border-default)">
      <button class="tab active" data-tab="personal" style="padding-bottom:var(--space-4);margin-bottom:-1px">${tab1}</button>
      <button class="tab" data-tab="security" style="padding-bottom:var(--space-4);margin-bottom:-1px">${tab2}</button>
    </div>
    
    <div data-tab-content="personal" class="reveal">
      <div class="card" style="padding:var(--space-8);background:var(--bg-surface);border:1px solid var(--border-default);border-radius:var(--radius-xl)">
        <div class="d-flex gap-8 items-center mb-10 pb-8" style="border-bottom:1px solid var(--border-default)">
          <div style="position:relative">
            <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg, var(--action-primary) 0%, #cc4700 100%);display:flex;align-items:center;justify-content:center;color:white;font-size:2rem;font-weight:700;box-shadow:0 8px 24px rgba(255,90,0,0.25)">JD</div>
            <button style="position:absolute;bottom:0;${isAr?'left':'right'}:0;width:32px;height:32px;border-radius:50%;background:var(--bg-primary);border:1px solid var(--border-default);display:flex;align-items:center;justify-content:center;color:var(--text-primary);cursor:pointer;box-shadow:var(--shadow-sm);transition:all 0.2s ease" onmouseover="this.style.color='var(--action-primary)';this.style.borderColor='var(--action-primary)'" onmouseout="this.style.color='var(--text-primary)';this.style.borderColor='var(--border-default)'">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
            </button>
          </div>
          <div>
            <h3 class="text-h3 mb-2" style="font-weight:var(--weight-bold)">John Doe</h3>
            <p class="text-body text-secondary d-flex items-center gap-2">
              <span class="badge badge-primary" style="border-radius:var(--radius-full)">${role}</span>
              <span style="opacity:0.5">•</span>
              ${memberSince}
            </p>
          </div>
        </div>
        
        <form class="d-flex flex-col gap-6" style="max-width:800px">
          <div class="grid-2" style="gap:var(--space-6)">
            <div class="form-group">
              <label class="form-label text-body-sm text-secondary">${fname}</label>
              <input type="text" class="form-input" value="John" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;font-size:1rem;border-radius:var(--radius-lg)">
            </div>
            <div class="form-group">
              <label class="form-label text-body-sm text-secondary">${lname}</label>
              <input type="text" class="form-input" value="Doe" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;font-size:1rem;border-radius:var(--radius-lg)">
            </div>
          </div>
          <div class="grid-2" style="gap:var(--space-6)">
            <div class="form-group">
              <label class="form-label text-body-sm text-secondary">${email}</label>
              <input type="email" class="form-input" value="john@example.com" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;font-size:1rem;border-radius:var(--radius-lg)">
            </div>
            <div class="form-group">
              <label class="form-label text-body-sm text-secondary">${phone}</label>
              <input type="tel" class="form-input" value="+966 55 xxx xxxx" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;font-size:1rem;border-radius:var(--radius-lg)" dir="ltr">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label text-body-sm text-secondary">${country}</label>
            <select class="form-input form-select" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;font-size:1rem;border-radius:var(--radius-lg)">
              <option>${isAr?'السعودية':'Saudi Arabia'}</option><option>${isAr?'الإمارات':'UAE'}</option><option>${isAr?'أخرى':'Other'}</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label text-body-sm text-secondary">${bio}</label>
            <textarea class="form-input" rows="4" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;font-size:1rem;border-radius:var(--radius-lg)">Technology enthusiast and venture explorer.</textarea>
          </div>
          <div class="mt-8 pt-8" style="border-top:1px solid var(--border-default);margin-top:var(--space-12)">
            <button type="button" class="btn btn-primary btn-lg" style="padding:var(--space-4) var(--space-8);box-shadow:0 8px 24px rgba(255,90,0,0.25);border-radius:var(--radius-lg)">${saveBtn}</button>
          </div>
        </form>
      </div>
    </div>
    
    <div data-tab-content="security" style="display:none">
      <div class="card" style="padding:var(--space-8);background:var(--bg-surface);border:1px solid var(--border-default);border-radius:var(--radius-xl)">
        <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold)">${changePass}</h3>
        <p class="text-body-sm text-secondary mb-6">${passDesc}</p>
        <form class="d-flex flex-col gap-5" style="max-width:500px">
          <div class="form-group"><label class="form-label text-secondary">${curPass}</label><input type="password" class="form-input" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;border-radius:var(--radius-lg)"></div>
          <div class="form-group"><label class="form-label text-secondary">${newPass}</label><input type="password" class="form-input" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;border-radius:var(--radius-lg)"></div>
          <div class="form-group"><label class="form-label text-secondary">${confPass}</label><input type="password" class="form-input" style="padding:var(--space-4);background:var(--bg-secondary);border-color:transparent;border-radius:var(--radius-lg)"></div>
          <button type="button" class="btn btn-primary mt-2" style="align-self:flex-start;padding:var(--space-3) var(--space-6);border-radius:var(--radius-lg)">${updatePass}</button>
        </form>
        
      </div>
    </div>
      </div>
    </div>`;
}

// ─── Page factory ───
export function generalDashboardPage(tab = 'overview') {
  const titles = { overview:'Dashboard', saved:'Saved Items', 'my-events':'My Events', invitations:'Invitations', downloads:'Downloads', notifications:'Notifications', profile:'Profile & Security' };
  const contents = { overview, saved, 'my-events': myEvents, invitations, downloads, notifications, profile };
  const content = (contents[tab] || overview)();
  return dashboardLayout(titles[tab] || 'Dashboard', 'general', tab, content);
}
