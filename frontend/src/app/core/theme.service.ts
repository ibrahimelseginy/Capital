import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ThemeService {
  private themeSubject = new BehaviorSubject<string>('light');
  public theme$ = this.themeSubject.asObservable();

  constructor() {
    this.initTheme();
  }

  private initTheme() {
    let theme = localStorage.getItem('stc-theme');
    if (!theme) {
      theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    this.setTheme(theme);
  }

  public setTheme(theme: string) {
    localStorage.setItem('stc-theme', theme);
    document.documentElement.setAttribute('data-theme', theme);
    this.themeSubject.next(theme);
    this.updateLogos(theme);
  }

  public toggleTheme() {
    const newTheme = this.themeSubject.value === 'light' ? 'dark' : 'light';
    this.setTheme(newTheme);
  }

  private updateLogos(theme: string) {
    const logoSrc = theme === 'dark' ? 'assets/images/Group 102.png' : 'assets/images/Group 97.png';
    ['drawer-logo', 'auth-logo', 'auth-logo-mobile', 'reg-logo', 'header-logo', 'footer-logo', 'sidebar-logo', 'onboarding-logo'].forEach(id => {
      const el = document.getElementById(id) as HTMLImageElement;
      if (el) el.src = logoSrc;
    });
  }
}
