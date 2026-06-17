/**
 * SEVEN TECH CAPITAL — SPA Router
 * Hash-based client-side routing for all pages
 */

import ThemeManager from './theme.js';
import LangManager from './language.js';

const Router = {
  routes: {},
  currentRoute: null,
  mainEl: null,

  init(mainSelector = '#main-content') {
    this.mainEl = document.querySelector(mainSelector);
    window.addEventListener('hashchange', () => this.navigate());
    // Handle initial route
    this.navigate();
  },

  register(path, handler) {
    this.routes[path] = handler;
  },

  navigate() {
    const hash = window.location.hash.slice(1) || '/';
    const path = hash.split('?')[0];
    
    // Find matching route
    let handler = this.routes[path];
    let params = {};

    if (!handler) {
      // Try dynamic routes like /partner/:id
      for (const [pattern, h] of Object.entries(this.routes)) {
        const regex = new RegExp('^' + pattern.replace(/:\w+/g, '([^/]+)') + '$');
        const match = path.match(regex);
        if (match) {
          handler = h;
          const paramNames = pattern.match(/:\w+/g) || [];
          paramNames.forEach((name, i) => {
            params[name.slice(1)] = match[i + 1];
          });
          break;
        }
      }
    }

    if (!handler) {
      handler = this.routes['/'] || (() => '<div class="state-empty"><h3>Page Not Found</h3></div>');
    }

    this.currentRoute = path;
    
    // Determine layout type
    const isDashboard = path.startsWith('/dashboard');
    const isAuth = path.startsWith('/login') || path.startsWith('/register');
    const isOnboarding = path.startsWith('/onboarding');

    // Render
    if (this.mainEl) {
      this.mainEl.innerHTML = handler(params);
      window.scrollTo(0, 0);
      
      // Re-apply language
      LangManager.apply(LangManager.currentLang);

      // Re-init reveals
      this.initReveals();

      // Re-init counters
      this.initCounters();

      // Re-init interactive elements
      this.initAccordions();
      this.initTabs();

      // Update active nav
      this.updateNav(path);

      // Dispatch route event
      window.dispatchEvent(new CustomEvent('routechange', { detail: { path, params } }));
    }
  },

  updateNav(path) {
    document.querySelectorAll('.header-nav-link, .mobile-nav-link').forEach(link => {
      const href = link.getAttribute('href') || '';
      const linkPath = href.startsWith('#') ? href.slice(1) : href;
      link.classList.toggle('active', linkPath === path || (path === '/' && linkPath === '#'));
    });
  },

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
  },

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
  },

  initAccordions() {
    document.querySelectorAll('.accordion-trigger').forEach(trigger => {
      trigger.onclick = () => {
        const item = trigger.closest('.accordion-item');
        const wasOpen = item.classList.contains('open');
        item.parentElement.querySelectorAll('.accordion-item').forEach(s => s.classList.remove('open'));
        if (!wasOpen) item.classList.add('open');
      };
    });
  },

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
};

export default Router;
