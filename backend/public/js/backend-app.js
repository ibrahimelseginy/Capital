/**
 * SEVEN TECH CAPITAL — Backend UI Logic (Laravel)
 * This script initializes UI interactions without SPA routing.
 */

import ThemeManager from './theme.js';

class BackendApp {
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
    this.setupThemeToggle();
    this.setupLangToggle();
    this.setupMobileMenu();
    this.setupSearch();
    this.initReveals();
    this.initCounters();
    this.initAccordions();
    this.initTabs();
    this.updateLogos();
    this.setupNotifications();

    window.addEventListener('themechange', () => {
      this.updateLogos();
      this.updateThemeIcons(ThemeManager.get());
    });

    // Remove transition block after init
    requestAnimationFrame(() => {
      document.body.classList.remove('no-transition');
    });
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

  setupLangToggle() {
    document.querySelectorAll('[data-action="toggle-lang"]').forEach(btn => {
      btn.onclick = () => {
        const currentLang = document.documentElement.dir === 'rtl' ? 'ar' : 'en';
        const newLang = currentLang === 'ar' ? 'en' : 'ar';
        // Send request to set language in session
        window.location.href = window.appBaseUrl + '/lang/' + newLang;
      };
    });
  }

  updateLogos() {
    const logoSrc = ThemeManager.getLogoSrc();
    // For Laravel layout, we have 'header-logo', 'sidebar-logo'
    ['header-logo', 'sidebar-logo', 'footer-logo'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.src = logoSrc;
    });
  }

  setupMobileMenu() {
    const toggle = document.querySelector('.dashboard-menu-toggle');
    const sidebar = document.querySelector('.dashboard-sidebar');
    if (!toggle || !sidebar) return;

    toggle.onclick = (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('mobile-open');
    };
    
    // Close sidebar when clicking outside
    document.addEventListener('click', (e) => {
        if (sidebar.classList.contains('mobile-open') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('mobile-open');
        }
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
    
    const isAr = document.documentElement.dir === 'rtl';
    if(input) input.placeholder = isAr ? 'ابحث هنا...' : 'Search...';

    const openSearch = () => {
      modal.style.display = 'flex';
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
    if(closeBtn) closeBtn.addEventListener('click', closeSearch);
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeSearch();
    });
    
    if(input) {
        input.addEventListener('input', (e) => {
        const q = e.target.value.trim().toLowerCase();
        
        if (!q) {
            results.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="1" style="margin-bottom:var(--space-4);opacity:0.5;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <p class="text-body text-secondary" style="text-align:center;">${isAr ? 'ابدأ الكتابة للبحث...' : 'Type to start searching...'}</p>
            `;
            return;
        }
        
        results.innerHTML = `
            <div style="width:100%;text-align:${isAr ? 'right' : 'left'};display:flex;flex-direction:column;gap:var(--space-4);">
            <div style="padding:var(--space-4);background:var(--bg-secondary);border-radius:var(--radius-md);border:1px solid var(--border-default);">
                <h4 class="text-h4" style="margin-bottom:8px;color:var(--text-primary);font-weight:var(--weight-bold);">${isAr ? 'نتائج البحث عن:' : 'Search results for:'} <span style="color:var(--action-primary)">${q}</span></h4>
            </div>
            </div>
        `;
        });
    }
  }

  initReveals() {
    const elements = document.querySelectorAll('.reveal:not(.visible)');
    if (!elements.length) return;
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    elements.forEach(el => observer.observe(el));
  }

  initCounters() {
    const counters = document.querySelectorAll('[data-counter]:not(.counted)');
    if (!counters.length) return;
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const el = entry.target;
          el.classList.add('counted');
          const target = parseInt(el.getAttribute('data-counter'), 10);
          const suffix = el.getAttribute('data-suffix') || '';
          const prefix = el.getAttribute('data-prefix') || '';
          const duration = 800;
          const start = performance.now();
          const update = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3);
            el.textContent = prefix + Math.round(target * ease).toLocaleString() + suffix;
            if (progress < 1) requestAnimationFrame(update);
          };
          requestAnimationFrame(update);
          observer.unobserve(el);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(el => observer.observe(el));
  }

  initAccordions() {
    document.querySelectorAll('.accordion-trigger').forEach(trigger => {
      trigger.onclick = () => {
        const item = trigger.closest('.accordion-item');
        const wasOpen = item.classList.contains('open');
        item.parentElement.querySelectorAll('.accordion-item').forEach(s => s.classList.remove('open'));
        if (!wasOpen) item.classList.add('open');
      };
    });
  }

  initTabs() {
    document.querySelectorAll('[data-tab]').forEach(tab => {
      tab.onclick = () => {
        const group = tab.closest('.tabs');
        const target = tab.getAttribute('data-tab');
        group.querySelectorAll('[data-tab]').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        const container = tab.closest('section') || tab.closest('.tab-container');
        if (container) {
          container.querySelectorAll('[data-tab-content]').forEach(c => {
            c.style.display = c.getAttribute('data-tab-content') === target ? '' : 'none';
          });
        }
      };
    });
  }

  setupNotifications() {
    const trigger = document.querySelector('.notification-trigger');
    const dropdown = document.querySelector('.notification-dropdown-premium');
    const badge = document.querySelector('.notification-badge');
    const clearBtn = document.querySelector('.clear-notifications-btn');
    const unreadItems = document.querySelectorAll('.notification-item.unread');

    if (!trigger || !dropdown) return;

    trigger.onclick = (e) => {
      e.stopPropagation();
      const isOpen = dropdown.style.display === 'block';
      dropdown.style.display = isOpen ? 'none' : 'block';
    };

    // Close when clicking outside
    document.addEventListener('click', (e) => {
      if (!dropdown.contains(e.target) && !trigger.contains(e.target)) {
        dropdown.style.display = 'none';
      }
    });

    if (clearBtn) {
      clearBtn.onclick = (e) => {
        e.stopPropagation();
        unreadItems.forEach(item => {
          item.classList.remove('unread');
          item.style.backgroundColor = 'transparent';
        });
        if (badge) badge.style.display = 'none';
      };
    }
  }
}

new BackendApp();
