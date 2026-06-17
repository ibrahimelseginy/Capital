import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../core/language.service';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-auth',
  templateUrl: './auth.component.html',
  styleUrls: ['./auth.component.css']
})
export class AuthComponent implements OnInit, OnDestroy {
  isLogin = true;
  isAr = false;
  currentLang = 'en';
  accountType = 'investor';
  private langSub!: Subscription;

  constructor(private router: Router, private langService: LanguageService) {}

  ngOnInit(): void {
    const path = this.router.url;
    this.isLogin = !path.includes('register');
    
    this.langSub = this.langService.lang$.subscribe(lang => {
      this.isAr = lang === 'ar';
      this.currentLang = lang;
    });

    // Initialize UI scripts for reveal elements
    setTimeout(() => {
      if ((window as any).STC_UI) {
        (window as any).STC_UI.init();
      }
    }, 100);
  }

  ngOnDestroy(): void {
    if (this.langSub) {
      this.langSub.unsubscribe();
    }
  }

  setAccountType(type: string): void {
    this.accountType = type;
  }

  submitLogin(event: Event): void {
    event.preventDefault();
    this.router.navigate(['/dashboard']);
  }

  submitRegister(event: Event): void {
    event.preventDefault();
    this.router.navigate(['/dashboard']);
  }
}
