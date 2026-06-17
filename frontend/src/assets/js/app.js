/**
 * SEVEN TECH CAPITAL — Main Application (SPA)
 * Master controller wiring router, pages, theme, language
 */

import ThemeManager from './theme.js';
import LangManager from './language.js';
import Router from './router.js';

// Pages
import { homePage } from './pages/home.js';
import { partnersPage, partnerDetailPage, investorsPublicPage, blogsPage, blogDetailPage, eventsPage, eventDetailPage, jobsPage, jobDetailPage, branchesPage, speakersPage } from './pages/public.js';
import { loginPage, registerPage } from './pages/auth.js';
import { investorOnboardingPage, entrepreneurOnboardingPage } from './pages/onboarding.js';
// General dashboard kept for shared utilities but no longer directly routed
import { investorDashboardPage, investorProjectDetailsPage } from './pages/dashboard-investor.js';
import { entrepreneurDashboardPage } from './pages/dashboard-entrepreneur.js';
import { adminDashboardPage } from './pages/dashboard-admin.js';

class App {
  constructor() {
    this.init();
  }

  init() {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.onReady());
    } else {
      this.onReady();
    }
  }

  onReady() {
    LangManager.init();
    this.registerRoutes();
    this.setupThemeToggle();
    this.setupLanguageToggle();
    this.setupMobileMenu();
    this.setupHeader();
    this.setupCookieBanner();
    this.setupSearch();
    this.updateLogos();

    window.addEventListener('themechange', () => {
      this.updateLogos();
      this.updateThemeIcons(ThemeManager.get());
    });
    window.addEventListener('langchange', () => {
      this.updateLogos();
      Router.navigate(); // Re-render current route to update dynamic content
    });

    // Route change: re-init interactive elements
    window.addEventListener('routechange', (e) => {
      this.setupThemeToggle();
      this.setupLanguageToggle();
      this.updateThemeIcons(ThemeManager.get());
      this.updateLangButtons(LangManager.get());
      this.updateLogos();
      this.setupNewsletter();
      this.toggleHeaderFooter(e.detail.path);
    });

    // Start router
    Router.init('#main-content');

    requestAnimationFrame(() => {
      document.body.classList.remove('no-transition');
    });
  }

  registerRoutes() {
    // Public pages
    Router.register('/', homePage);
    Router.register('/partners', partnersPage);
    Router.register('/partner/:id', partnerDetailPage);
    Router.register('/investors', investorsPublicPage);
    Router.register('/blogs', blogsPage);
    Router.register('/blog/:id', blogDetailPage);
    Router.register('/events', eventsPage);
    Router.register('/event/:id', eventDetailPage);
    Router.register('/jobs', jobsPage);
    Router.register('/job/:id', jobDetailPage);
    Router.register('/branches', branchesPage);
    Router.register('/speakers', speakersPage);

    // Auth
    Router.register('/login', loginPage);
    Router.register('/register', registerPage);

    // Onboarding
    Router.register('/onboarding/investor', investorOnboardingPage);
    Router.register('/onboarding/entrepreneur', entrepreneurOnboardingPage);

    // Dashboard defaults to investor
    Router.register('/dashboard', () => { window.location.hash = '/dashboard/investor'; return '<div></div>'; });

    // Investor Dashboard
    Router.register('/dashboard/investor', () => investorDashboardPage('overview'));
    Router.register('/dashboard/investor/overview', () => investorDashboardPage('overview'));
    Router.register('/dashboard/investor/projects', () => investorDashboardPage('projects'));
    Router.register('/dashboard/investor/reports', () => investorDashboardPage('reports'));
    Router.register('/dashboard/investor/documents', () => investorDashboardPage('documents'));
    Router.register('/dashboard/investor/ndas', () => investorDashboardPage('ndas'));
    Router.register('/dashboard/investor/exit-requests', () => investorDashboardPage('exit-requests'));
    Router.register('/dashboard/investor/exit-records', () => investorDashboardPage('exit-records'));
    Router.register('/dashboard/investor/consultations', () => investorDashboardPage('consultations'));
    Router.register('/dashboard/investor/events', () => investorDashboardPage('events'));
    Router.register('/dashboard/investor/notifications', () => investorDashboardPage('notifications'));
    Router.register('/dashboard/investor/profile', () => investorDashboardPage('profile'));
    Router.register('/dashboard/investor/project/:id', (params) => investorProjectDetailsPage(params.id));

    // Entrepreneur Dashboard
    Router.register('/dashboard/entrepreneur', () => entrepreneurDashboardPage('overview'));
    Router.register('/dashboard/entrepreneur/overview', () => entrepreneurDashboardPage('overview'));
    Router.register('/dashboard/entrepreneur/my-projects', () => entrepreneurDashboardPage('my-projects'));
    Router.register('/dashboard/entrepreneur/applications', () => entrepreneurDashboardPage('applications'));
    Router.register('/dashboard/entrepreneur/progress', () => entrepreneurDashboardPage('progress'));
    Router.register('/dashboard/entrepreneur/reports', () => entrepreneurDashboardPage('reports'));
    Router.register('/dashboard/entrepreneur/documents', () => entrepreneurDashboardPage('documents'));
    Router.register('/dashboard/entrepreneur/ndas', () => entrepreneurDashboardPage('ndas'));
    Router.register('/dashboard/entrepreneur/meetings', () => entrepreneurDashboardPage('meetings'));
    Router.register('/dashboard/entrepreneur/exit-records', () => entrepreneurDashboardPage('exit-records'));
    Router.register('/dashboard/entrepreneur/notifications', () => entrepreneurDashboardPage('notifications'));
    Router.register('/dashboard/entrepreneur/profile', () => entrepreneurDashboardPage('profile'));

    // Admin Dashboard
    Router.register('/dashboard/admin', () => adminDashboardPage('overview'));
    Router.register('/dashboard/admin/overview', () => adminDashboardPage('overview'));
    Router.register('/dashboard/admin/users', () => adminDashboardPage('users'));
    Router.register('/dashboard/admin/projects', () => adminDashboardPage('projects'));
    Router.register('/dashboard/admin/ndas', () => adminDashboardPage('ndas'));
    Router.register('/dashboard/admin/content', () => adminDashboardPage('content'));
    Router.register('/dashboard/admin/profile', () => adminDashboardPage('profile'));
  }

  toggleHeaderFooter(path) {
    const header = document.getElementById('main-header');
    const footer = document.querySelector('.site-footer');
    const cookie = document.getElementById('cookie-banner');
    const isDashboard = path.startsWith('/dashboard');
    const isAuth = path === '/login' || path === '/register';
    const isOnboarding = path.startsWith('/onboarding');
    const hide = isDashboard || isAuth || isOnboarding;

    if (header) header.style.display = hide ? 'none' : '';
    if (footer) footer.style.display = hide ? 'none' : '';
    if (cookie && hide) cookie.style.display = 'none';
  }

  setupThemeToggle() {
    document.querySelectorAll('[data-action="toggle-theme"]').forEach(btn => {
      btn.onclick = () => {
        const theme = ThemeManager.toggle();
        this.updateThemeIcons(theme);
      };
    });
    this.updateThemeIcons(ThemeManager.get());
  }

  updateThemeIcons(theme) {
    document.querySelectorAll('[data-action="toggle-theme"]').forEach(btn => {
      btn.innerHTML = theme === 'dark'
        ? '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>'
        : '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>';
    });
  }

  setupLanguageToggle() {
    document.querySelectorAll('[data-action="toggle-lang"]').forEach(btn => {
      btn.onclick = () => {
        const lang = LangManager.toggle();
        this.updateLangButtons(lang);
      };
    });
    this.updateLangButtons(LangManager.get());
  }

  updateLangButtons(lang) {
    document.querySelectorAll('[data-action="toggle-lang"] .lang-label').forEach(label => {
      label.textContent = lang === 'en' ? 'عربي' : 'EN';
    });
  }

  updateLogos() {
    const logoSrc = ThemeManager.getLogoSrc();
    ['drawer-logo', 'auth-logo', 'auth-logo-mobile', 'reg-logo', 'header-logo', 'footer-logo', 'sidebar-logo', 'onboarding-logo'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.src = logoSrc;
    });
  }

  setupMobileMenu() {
    const toggle = document.getElementById('mobile-menu-toggle');
    const drawer = document.getElementById('mobile-drawer');
    const overlay = document.getElementById('mobile-overlay');
    const close = document.getElementById('mobile-drawer-close');
    if (!toggle || !drawer) return;

    const open = () => { drawer.classList.add('open'); overlay?.classList.add('open'); document.body.style.overflow = 'hidden'; };
    const closeDrawer = () => { drawer.classList.remove('open'); overlay?.classList.remove('open'); document.body.style.overflow = ''; };

    toggle.onclick = open;
    close && (close.onclick = closeDrawer);
    overlay && (overlay.onclick = closeDrawer);
    drawer.querySelectorAll('a').forEach(l => l.addEventListener('click', closeDrawer));
  }

  setupHeader() {
    const header = document.getElementById('main-header');
    if (!header) return;
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
      const s = window.scrollY;
      header.classList.toggle('scrolled', s > 80);
      if (s > lastScroll + 10 && s > 200) header.classList.add('hidden');
      else if (s < lastScroll - 10) header.classList.remove('hidden');
      lastScroll = s;
    }, { passive: true });
  }

  setupNewsletter() {
    document.querySelectorAll('.newsletter-form').forEach(form => {
      form.onsubmit = (e) => {
        e.preventDefault();
        const input = form.querySelector('input');
        const btn = form.querySelector('button');
        if (input?.value) { btn.textContent = '✓'; input.value = ''; setTimeout(() => btn.textContent = LangManager.t('newsletter_btn'), 2000); }
      };
    });
  }

  setupCookieBanner() {
    const banner = document.getElementById('cookie-banner');
    if (!banner || localStorage.getItem('stc-cookies')) return;
    setTimeout(() => banner.classList.add('visible'), 2000);
    document.getElementById('cookie-accept')?.addEventListener('click', () => {
      localStorage.setItem('stc-cookies', 'accepted');
      banner.classList.remove('visible');
    });
  }

  setupSearch() {
    const searchBtns = document.querySelectorAll('[data-action="search"]');
    const modal = document.getElementById('global-search-modal');
    if (!modal) return;
    const closeBtn = document.getElementById('close-search-modal');
    const input = document.getElementById('global-search-input');
    const content = document.getElementById('search-modal-content');
    const results = document.getElementById('search-results-container');
    
    const isAr = LangManager.currentLang === 'ar';
    input.placeholder = isAr ? 'ابحث هنا...' : 'Search...';

    const openSearch = () => {
      modal.style.display = 'flex';
      // Trigger reflow
      void modal.offsetWidth;
      modal.style.opacity = '1';
      content.style.transform = 'translateY(0)';
      input.focus();
    };

    const closeSearch = () => {
      modal.style.opacity = '0';
      content.style.transform = 'translateY(-20px)';
      setTimeout(() => { modal.style.display = 'none'; }, 300);
    };

    searchBtns.forEach(btn => btn.addEventListener('click', openSearch));
    closeBtn.addEventListener('click', closeSearch);
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeSearch();
    });
    
    input.addEventListener('input', (e) => {
      const q = e.target.value.trim().toLowerCase();
      const currentIsAr = LangManager.currentLang === 'ar';
      
      if (!q) {
        results.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="1" style="margin-bottom:var(--space-4);opacity:0.5;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <p class="text-body text-secondary" style="text-align:center;">${currentIsAr ? 'ابدأ الكتابة للبحث في الشركاء والمستثمرين...' : 'Type to start searching across partners, investors...'}</p>
        `;
        return;
      }
      
      results.innerHTML = `
        <div style="width:100%;text-align:${currentIsAr ? 'right' : 'left'};display:flex;flex-direction:column;gap:var(--space-4);">
          <div style="padding:var(--space-4);background:var(--bg-secondary);border-radius:var(--radius-md);border:1px solid var(--border-default);cursor:pointer;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--action-primary)'" onmouseout="this.style.borderColor='var(--border-default)'" onclick="window.location.hash='/partners';document.getElementById('close-search-modal').click();">
            <h4 class="text-h4" style="margin-bottom:8px;color:var(--text-primary);font-weight:var(--weight-bold);">${currentIsAr ? 'شريك تقني:' : 'Tech Partner:'} <span style="color:var(--action-primary)">${q}</span></h4>
            <p class="text-caption text-secondary">${currentIsAr ? 'موجود في دليل الشركاء' : 'Found in Partners Directory'}</p>
          </div>
          <div style="padding:var(--space-4);background:var(--bg-secondary);border-radius:var(--radius-md);border:1px solid var(--border-default);cursor:pointer;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--action-primary)'" onmouseout="this.style.borderColor='var(--border-default)'" onclick="window.location.hash='/blogs';document.getElementById('close-search-modal').click();">
            <h4 class="text-h4" style="margin-bottom:8px;color:var(--text-primary);font-weight:var(--weight-bold);">${currentIsAr ? 'مقال يناقش' : 'Article discussing'} <span style="color:var(--action-primary)">${q}</span></h4>
            <p class="text-caption text-secondary">${currentIsAr ? 'موجود في المدونة والمحتوى' : 'Found in Knowledge Base'}</p>
          </div>
        </div>
      `;
    });
  }
}

new App();
