import { Component, OnInit, OnDestroy } from '@angular/core';
import { ThemeService } from '../../core/theme.service';
import { LanguageService } from '../../core/language.service';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit, OnDestroy {
  isMobileMenuOpen = false;
  currentLang = 'en';
  currentTheme = 'light';
  private subs: Subscription = new Subscription();

  constructor(
    private themeService: ThemeService,
    private languageService: LanguageService
  ) { }

  ngOnInit(): void {
    this.subs.add(this.languageService.lang$.subscribe(lang => {
      this.currentLang = lang;
    }));
    this.subs.add(this.themeService.theme$.subscribe(theme => {
      this.currentTheme = theme;
    }));
  }

  ngOnDestroy(): void {
    this.subs.unsubscribe();
  }

  toggleMobileMenu(): void {
    this.isMobileMenuOpen = !this.isMobileMenuOpen;
  }

  toggleSearch(): void {
    const modal = document.getElementById('global-search-modal');
    if (modal) {
      modal.style.display = 'flex';
      void modal.offsetWidth;
      modal.style.opacity = '1';
      const content = document.getElementById('search-modal-content');
      if (content) content.style.transform = 'translateY(0)';
      const input = document.getElementById('global-search-input');
      if (input) input.focus();
    }
  }

  toggleLanguage(): void {
    this.languageService.toggleLang();
  }

  toggleTheme(): void {
    this.themeService.toggleTheme();
  }

  goToBackend(path: string): void {
    window.location.href = `http://127.0.0.1:8000${path}?lang=${this.currentLang}`;
  }
}
