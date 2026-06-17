import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LanguageService } from '../../core/language.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  constructor(private router: Router, private languageService: LanguageService) { }

  ngOnInit(): void {
  }
  
  get currentLang(): string {
    return this.languageService.currentLang || 'en';
  }
}
