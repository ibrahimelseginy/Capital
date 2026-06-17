import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../core/language.service';
import { WebsiteService } from '../../core/website.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  events: any[] = [];
  articles: any[] = [];
  jobs: any[] = [];
  metrics: any[] = [];
  testimonials: any[] = [];
  backendUrl: string = '';

  constructor(
    private router: Router, 
    private languageService: LanguageService,
    private websiteService: WebsiteService
  ) { 
    this.backendUrl = this.websiteService.backendUrl;
  }

  ngOnInit(): void {
    this.websiteService.getHomeContent().subscribe({
      next: (res) => {
        if (res.status === 'success') {
          this.events = res.data.events;
          this.articles = res.data.articles;
          this.jobs = res.data.jobs;
          this.metrics = res.data.metrics;
          this.testimonials = res.data.testimonials;
        }
      },
      error: (err) => {
        console.error('Error fetching website content', err);
      }
    });
  }
  
  get currentLang(): string {
    return this.languageService.currentLang || 'en';
  }
}
