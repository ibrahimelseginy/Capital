/**
 * SEVEN TECH CAPITAL — Frontend UI Interactivity
 * This script provides UI interactions that Angular components can trigger.
 * It removes the Vanilla Router to avoid conflicting with Angular Router.
 */

window.STC_UI = {
  init() {
    this.initReveals();
    this.initCounters();
    this.initAccordions();
    this.initTabs();
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
  },
  
  setupSearch() {
    // Search is handled by Angular HeaderComponent now, but if we need vanilla JS modal:
    const input = document.getElementById('global-search-input');
    const results = document.getElementById('search-results-container');
    
    if(input) {
      input.addEventListener('input', (e) => {
        const q = e.target.value.trim().toLowerCase();
        const isAr = document.documentElement.dir === 'rtl';
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
};

document.addEventListener('DOMContentLoaded', () => {
  window.STC_UI.setupSearch();
  // Remove transition block after init
  setTimeout(() => {
    document.body.classList.remove('no-transition');
  }, 100);
});
