import { Component, OnInit, OnDestroy, ChangeDetectorRef } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { DomSanitizer, SafeHtml } from '@angular/platform-browser';
import { Subscription } from 'rxjs';
import { LanguageService } from '../../core/language.service';

@Component({
  selector: 'app-dynamic',
  template: '<div [innerHTML]="htmlContent"></div>',
})
export class DynamicComponent implements OnInit, OnDestroy {
  htmlContent: SafeHtml = '';
  private langSub!: Subscription;
  private routeSub!: Subscription;

  constructor(
    private route: ActivatedRoute,
    private sanitizer: DomSanitizer,
    private langService: LanguageService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit() {
    this.routeSub = this.route.url.subscribe(() => {
      this.renderPage();
    });

    this.langSub = this.langService.lang$.subscribe(() => {
      this.renderPage();
    });
  }

  ngOnDestroy() {
    if (this.langSub) this.langSub.unsubscribe();
    if (this.routeSub) this.routeSub.unsubscribe();
  }

  private renderPage() {
    const path = this.route.snapshot.url[0]?.path;
    const id = this.route.snapshot.paramMap.get('id');
    
    let rawHtml = '';
    const pages: any = (window as any).STC_PAGES || {};

    if (!path) rawHtml = pages.homePage ? pages.homePage() : '';
    else if (path === 'partners') rawHtml = pages.partnersPage ? pages.partnersPage() : '';
    else if (path === 'partner' && id) rawHtml = pages.partnerDetailPage ? pages.partnerDetailPage({id}) : '';
    else if (path === 'investors') rawHtml = pages.investorsPublicPage ? pages.investorsPublicPage() : '';
    else if (path === 'events') rawHtml = pages.eventsPage ? pages.eventsPage() : '';
    else if (path === 'event' && id) rawHtml = pages.eventDetailPage ? pages.eventDetailPage({id}) : '';
    else if (path === 'blogs') rawHtml = pages.blogsPage ? pages.blogsPage() : '';
    else if (path === 'blog' && id) rawHtml = pages.blogDetailPage ? pages.blogDetailPage({id}) : '';
    else if (path === 'jobs') rawHtml = pages.jobsPage ? pages.jobsPage() : '';
    else if (path === 'job' && id) rawHtml = pages.jobDetailPage ? pages.jobDetailPage({id}) : '';
    else if (path === 'contact' || path === 'branches') rawHtml = pages.branchesPage ? pages.branchesPage() : '';
    
    this.htmlContent = this.sanitizer.bypassSecurityTrustHtml(rawHtml);
    this.cdr.detectChanges();
    
    // Apply translations
    setTimeout(() => {
      this.langService.applyDOM(this.langService.currentLang);
    }, 10);
    
    // Reinitialize UI scripts (tabs, accordions, reveals) for newly inserted HTML
    setTimeout(() => {
      if ((window as any).STC_UI) {
        (window as any).STC_UI.init();
      }
    }, 100);
  }
}
