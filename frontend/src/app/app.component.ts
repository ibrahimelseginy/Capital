import { Component, OnInit } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { filter } from 'rxjs/operators';
import { ThemeService } from './core/theme.service';
import { LanguageService } from './core/language.service';
import { PreloadService } from './core/preload.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  title = 'capital-angular';
  showHeaderFooter = true;

  constructor(
    private router: Router,
    private themeService: ThemeService,
    private languageService: LanguageService,
    private preloadService: PreloadService
  ) {}

  ngOnInit() {
    this.preloadService.preloadAllPagesContent();
    
    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe((event: any) => {
      const path = event.urlAfterRedirects;
      const isDashboard = path.startsWith('/dashboard');
      const isAuth = path.startsWith('/login') || path.startsWith('/register') || path.startsWith('/auth');
      const isOnboarding = path.startsWith('/onboarding');
      
      this.showHeaderFooter = !(isDashboard || isAuth || isOnboarding);

      // Re-initialize UI interactive elements after Angular renders the view
      setTimeout(() => {
        if ((window as any).STC_UI) {
          (window as any).STC_UI.initReveals();
          (window as any).STC_UI.initCounters();
          (window as any).STC_UI.initAccordions();
          (window as any).STC_UI.initTabs();
        }
      }, 50);
    });
  }
}
