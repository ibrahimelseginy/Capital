/**
 * SEVEN TECH CAPITAL — Theme Manager
 * Light/dark theme with system preference detection and persistence
 */

const ThemeManager = {
  STORAGE_KEY: 'stc-theme',
  
  init() {
    // Prevent flash — read preference before paint
    const saved = localStorage.getItem(this.STORAGE_KEY);
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = saved || (prefersDark ? 'dark' : 'light');
    
    document.documentElement.setAttribute('data-theme', theme);
    
    // Listen for system preference changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
      if (!localStorage.getItem(this.STORAGE_KEY)) {
        this.set(e.matches ? 'dark' : 'light', false);
      }
    });
  },

  get() {
    return document.documentElement.getAttribute('data-theme') || 'light';
  },

  set(theme, persist = true) {
    document.documentElement.setAttribute('data-theme', theme);
    if (persist) {
      localStorage.setItem(this.STORAGE_KEY, theme);
    }
    // Dispatch event for other components
    window.dispatchEvent(new CustomEvent('themechange', { detail: { theme } }));
  },

  toggle() {
    const current = this.get();
    const next = current === 'dark' ? 'light' : 'dark';
    this.set(next);
    return next;
  },

  // Get appropriate logo path based on current theme
  getLogoSrc() {
    const theme = this.get();
    return theme === 'dark' 
      ? 'Group 102.png'  // White text logo for dark backgrounds
      : 'Group 97.png';  // Dark text logo for light backgrounds
  },

  getIconSrc() {
    const theme = this.get();
    return theme === 'dark'
      ? 'Group 99.png'   // White icon
      : 'Group 98.png';  // Orange icon
  }
};

// Initialize immediately
ThemeManager.init();

export default ThemeManager;
