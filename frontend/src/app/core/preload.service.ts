import { Injectable, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';

@Injectable({
  providedIn: 'root'
})
export class PreloadService {
  private isBrowser: boolean;
  private hasPreloaded = false;

  constructor(@Inject(PLATFORM_ID) platformId: Object) {
    this.isBrowser = isPlatformBrowser(platformId);
  }

  public preloadAllPagesContent(): void {
    if (!this.isBrowser || this.hasPreloaded) return;
    
    // Ensure we run this after initial load so it doesn't block the home page
    setTimeout(() => {
      this.hasPreloaded = true;
      const pages = (window as any).STC_PAGES;
      if (!pages) return;

      try {
        let allHtml = '';
        
        // Accumulate HTML from all generated public pages
        if (pages.homePage) allHtml += pages.homePage();
        if (pages.partnersPage) allHtml += pages.partnersPage();
        if (pages.investorsPublicPage) allHtml += pages.investorsPublicPage();
        if (pages.eventsPage) allHtml += pages.eventsPage();
        if (pages.blogsPage) allHtml += pages.blogsPage();
        if (pages.jobsPage) allHtml += pages.jobsPage();
        if (pages.branchesPage) allHtml += pages.branchesPage();
        if (pages.loginPage) allHtml += pages.loginPage();
        if (pages.registerPage) allHtml += pages.registerPage();

        // We use a Set to avoid preloading the same image multiple times
        const urlsToPreload = new Set<string>();

        // 1. Extract standard <img src="..."> 
        const imgRegex = /<img[^>]+src=["']([^"']+)["']/g;
        let match;
        while ((match = imgRegex.exec(allHtml)) !== null) {
          const src = match[1];
          if (src && !src.startsWith('data:')) {
            urlsToPreload.add(src);
          }
        }

        // 2. Extract CSS background-image url('...')
        const bgRegex = /url\(["']?([^"'\)]+)["']?\)/g;
        while ((match = bgRegex.exec(allHtml)) !== null) {
          const src = match[1];
          if (src && !src.startsWith('data:')) {
            urlsToPreload.add(src);
          }
        }

        // Preload all extracted URLs
        urlsToPreload.forEach(url => {
          const img = new Image();
          img.src = url;
        });

        console.log(`[PreloadService] Successfully preloaded ${urlsToPreload.size} assets into browser cache for faster navigation.`);
      } catch (err) {
        console.warn('[PreloadService] Could not preload all pages:', err);
      }
    }, 2000); // 2 second delay to prioritize the main home page rendering
  }
}
