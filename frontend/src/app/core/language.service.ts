import { Injectable, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class LanguageService {
  private STORAGE_KEY = 'stc-lang';
  private langSubject = new BehaviorSubject<string>('en');
  public lang$ = this.langSubject.asObservable();
  private isBrowser: boolean;

  private strings: any = {
    en: {
      // Nav
      nav_home: 'Home',
      nav_partners: 'Partners',
      nav_investors: 'Investors',
      nav_events: 'Events',
      nav_blogs: 'Blogs',
      nav_jobs: 'Jobs',
      nav_contact: 'Contact',
      nav_signin: 'Sign In',
      nav_create: 'Create Account',
      nav_search: 'Search',
      lang_toggle: 'عربي',
      
      // Hero
      hero_overline: 'A Venture Studio',
      hero_title_1: 'We build technology',
      hero_title_2: 'companies designed',
      hero_title_3: 'to lead.',
      hero_subtitle: 'SEVEN TECH CAPITAL combines capital, strategy, product, technology, and execution to build scalable ventures.',
      hero_cta_primary: 'Apply as Entrepreneur',
      hero_cta_secondary: 'Become an Investor',
      hero_explore: 'Explore the Studio',

      // Audience Cards
      audience_general_title: 'Explore SEVEN TECH CAPITAL',
      audience_general_desc: 'Discover how we build technology companies from the ground up. Browse our portfolio, events, and resources.',
      audience_general_link: 'Start Exploring',
      audience_investor_title: 'Invest in Scalable Ventures',
      audience_investor_desc: 'Access curated investment opportunities in technology ventures built with institutional-grade operations.',
      audience_investor_link: 'Learn About Investing',
      audience_entrepreneur_title: 'Build Your Venture With Us',
      audience_entrepreneur_desc: 'Bring your vision to our venture studio. We provide capital, team, technology, and go-to-market execution.',
      audience_entrepreneur_link: 'Apply Now',

      // How it Works
      how_title: 'How SEVEN TECH CAPITAL Works',
      how_subtitle: 'From concept to market-leading company, we combine strategic capital with hands-on execution.',
      how_step1_title: 'Source & Validate',
      how_step1_desc: 'We identify market opportunities and validate ideas with rigorous research and founder partnerships.',
      how_step2_title: 'Build & Design',
      how_step2_desc: 'Our product and engineering teams build institutional-grade technology from day one.',
      how_step3_title: 'Launch & Grow',
      how_step3_desc: 'We deploy go-to-market strategy, growth operations, and market access to accelerate traction.',
      how_step4_title: 'Scale & Exit',
      how_step4_desc: 'Strategic governance, investor relations, and exit planning drive long-term value creation.',

      // Featured Projects
      projects_title: 'Featured Ventures',
      projects_subtitle: 'Companies built through our venture studio model, from concept to scale.',
      projects_view_all: 'View All Ventures',

      // Partners
      partners_title: 'Our Partners',
      partners_subtitle: 'Strategic alliances that strengthen our venture ecosystem.',

      // Investor CTA
      investor_cta_title: 'Invest in Tomorrow\'s Technology Leaders',
      investor_cta_desc: 'Join a select group of investors accessing institutional-grade venture opportunities with transparent governance and professional reporting.',
      investor_cta_btn: 'Become an Investor',
      investor_cta_link: 'Learn More About Our Model',

      // Events
      events_title: 'Upcoming Events',
      events_subtitle: 'Connect with founders, investors, and the venture community.',
      events_view_all: 'View All Events',

      // Blogs
      blogs_title: 'Latest Insights',
      blogs_subtitle: 'Thought leadership on venture building, investment, and technology.',
      blogs_view_all: 'Read All Articles',
      blogs_read_time: 'min read',

      // Content
      content_title: 'Featured Content',
      content_subtitle: 'Reports, guides, and resources from our venture ecosystem.',
      content_view_all: 'Browse Content Library',

      // Jobs
      jobs_title: 'Join Our Team',
      jobs_subtitle: 'Build the next generation of technology companies.',
      jobs_view_all: 'View All Openings',
      job_1_title: 'Senior Product Designer',
      job_1_loc: 'Riyadh, KSA',
      job_1_type: 'Full-time',
      job_1_dept: 'Design',
      job_1_apply: 'Apply',
      job_2_title: 'Full-Stack Engineer',
      job_2_loc: 'Remote',
      job_2_type: 'Full-time',
      job_2_dept: 'Engineering',
      job_2_apply: 'Apply',
      job_3_title: 'Growth Lead',
      job_3_loc: 'Dubai, UAE',
      job_3_type: 'Full-time',
      job_3_dept: 'Marketing',
      job_3_apply: 'Apply',

      // Metrics
      metrics_title: 'Impact in Numbers',
      metrics_subtitle: 'Our venture studio by the numbers.',
      metric_ventures: 'Ventures Built',
      metric_capital: 'Capital Deployed',
      metric_team: 'Team Members',
      metric_markets: 'Markets Reached',

      // Testimonials
      testimonials_title: 'Founder Stories',
      testimonials_subtitle: 'What founders and investors say about working with us.',
      testimonials_view_all: 'View All Stories',
      testimonial_1_quote: "SEVEN TECH CAPITAL didn't just invest — they became our co-founders. Their product team built our MVP in 12 weeks, and their market access opened doors we couldn't have opened alone.",
      testimonial_1_name: 'Sarah Al-Tamimi',
      testimonial_1_role: 'CEO, FinFlow',
      testimonial_2_quote: 'The transparency and governance we see from SEVEN TECH CAPITAL is institutional-grade. Monthly reports, clear metrics, and a dedicated account manager who understands our goals.',
      testimonial_2_name: 'Khalid Al-Dosari',
      testimonial_2_role: 'Lead Investor',

      // Newsletter
      newsletter_title: 'Stay Informed',
      newsletter_subtitle: 'Get insights on venture building, investment opportunities, and ecosystem updates.',
      newsletter_placeholder: 'Enter your email',
      newsletter_btn: 'Subscribe',
      newsletter_consent: 'By subscribing, you agree to our privacy policy.',

      // Final CTA
      final_cta_title: 'Ready to Build Something Great?',
      final_cta_desc: 'Whether you\'re a founder with a vision or an investor seeking opportunities, we\'re ready to work with you.',

      // Footer
      footer_tagline: 'Building technology companies designed to lead.',
      footer_company: 'Company',
      footer_about: 'About',
      footer_how: 'How We Build',
      footer_team: 'Team',
      footer_portfolio: 'Portfolio',
      footer_careers: 'Careers',
      footer_resources: 'Resources',
      footer_contact: 'Contact',
      footer_legal: 'Legal',
      footer_privacy: 'Privacy Policy',
      footer_terms: 'Terms of Service',
      footer_cookies: 'Cookie Policy',
      footer_connect: 'Connect',
      footer_apply: 'Apply',
      footer_newsletter: 'Newsletter',
      footer_rights: '© 2026 SEVEN TECH CAPITAL. All rights reserved.',
    },
    ar: {
      // Nav
      nav_home: 'الرئيسية',
      nav_partners: 'الشركاء',
      nav_investors: 'المستثمرون',
      nav_events: 'الفعاليات',
      nav_blogs: 'المدونة',
      nav_jobs: 'الوظائف',
      nav_contact: 'تواصل معنا',
      nav_signin: 'تسجيل الدخول',
      nav_create: 'إنشاء حساب',
      nav_search: 'بحث',
      lang_toggle: 'English',

      // Hero
      hero_overline: 'استوديو ريادي',
      hero_title_1: 'نبني شركات تقنية',
      hero_title_2: 'صُمّمت',
      hero_title_3: 'لتقود.',
      hero_subtitle: 'سفن تك كابيتال تجمع بين رأس المال والاستراتيجية والمنتج والتقنية والتنفيذ لبناء مشاريع قابلة للتوسع.',
      hero_cta_primary: 'انضم كرائد أعمال',
      hero_cta_secondary: 'انضم كمستثمر',
      hero_explore: 'استكشف الاستوديو',

      // Audience Cards
      audience_general_title: 'استكشف سفن تك كابيتال',
      audience_general_desc: 'اكتشف كيف نبني شركات تقنية من الصفر. تصفّح محفظتنا وفعالياتنا ومواردنا.',
      audience_general_link: 'ابدأ الاستكشاف',
      audience_investor_title: 'استثمر في مشاريع قابلة للتوسع',
      audience_investor_desc: 'وصول إلى فرص استثمارية مختارة في مشاريع تقنية مبنية بمعايير مؤسسية.',
      audience_investor_link: 'تعرّف على الاستثمار',
      audience_entrepreneur_title: 'ابنِ مشروعك معنا',
      audience_entrepreneur_desc: 'أحضر رؤيتك إلى استوديونا الريادي. نقدم رأس المال والفريق والتقنية والتنفيذ.',
      audience_entrepreneur_link: 'انضم الآن',

      // How it Works
      how_title: 'كيف تعمل سفن تك كابيتال',
      how_subtitle: 'من الفكرة إلى شركة رائدة في السوق، نجمع بين رأس المال الاستراتيجي والتنفيذ العملي.',
      how_step1_title: 'الاستكشاف والتحقق',
      how_step1_desc: 'نحدد فرص السوق ونتحقق من الأفكار ببحث دقيق وشراكات مع المؤسسين.',
      how_step2_title: 'البناء والتصميم',
      how_step2_desc: 'فرق المنتج والهندسة لدينا تبني تقنية بمعايير مؤسسية من اليوم الأول.',
      how_step3_title: 'الإطلاق والنمو',
      how_step3_desc: 'ننشر استراتيجية الوصول للسوق وعمليات النمو والوصول للأسواق لتسريع الجذب.',
      how_step4_title: 'التوسع والتخارج',
      how_step4_desc: 'الحوكمة الاستراتيجية وعلاقات المستثمرين وتخطيط التخارج لخلق قيمة طويلة المدى.',

      // Featured Projects
      projects_title: 'المشاريع المميزة',
      projects_subtitle: 'شركات بُنيت من خلال نموذج الاستوديو الريادي، من الفكرة إلى التوسع.',
      projects_view_all: 'عرض جميع المشاريع',

      // Partners
      partners_title: 'شركاؤنا',
      partners_subtitle: 'تحالفات استراتيجية تعزز منظومتنا الريادية.',

      // Investor CTA
      investor_cta_title: 'استثمر في قادة التقنية المستقبليين',
      investor_cta_desc: 'انضم لمجموعة مختارة من المستثمرين للوصول إلى فرص ريادية بمعايير مؤسسية مع حوكمة شفافة وتقارير احترافية.',
      investor_cta_btn: 'انضم كمستثمر',
      investor_cta_link: 'تعرف على نموذجنا',

      // Events
      events_title: 'الفعاليات القادمة',
      events_subtitle: 'تواصل مع المؤسسين والمستثمرين ومجتمع الريادة.',
      events_view_all: 'عرض جميع الفعاليات',

      // Blogs
      blogs_title: 'أحدث المقالات',
      blogs_subtitle: 'قيادة فكرية في بناء المشاريع والاستثمار والتقنية.',
      blogs_view_all: 'قراءة جميع المقالات',
      blogs_read_time: 'دقيقة قراءة',

      // Content
      content_title: 'محتوى مميز',
      content_subtitle: 'تقارير وأدلة وموارد من منظومتنا الريادية.',
      content_view_all: 'تصفح مكتبة المحتوى',

      // Jobs
      jobs_title: 'انضم لفريقنا',
      jobs_subtitle: 'ابنِ الجيل القادم من شركات التقنية.',
      jobs_view_all: 'عرض جميع الوظائف',
      job_1_title: 'مصمم منتجات أول',
      job_1_loc: 'الرياض، السعودية',
      job_1_type: 'دوام كامل',
      job_1_dept: 'التصميم',
      job_1_apply: 'انضم',
      job_2_title: 'مهندس برمجيات متكامل',
      job_2_loc: 'عن بُعد',
      job_2_type: 'دوام كامل',
      job_2_dept: 'الهندسة',
      job_2_apply: 'انضم',
      job_3_title: 'قائد النمو والتسويق',
      job_3_loc: 'دبي، الإمارات',
      job_3_type: 'دوام كامل',
      job_3_dept: 'التسويق',
      job_3_apply: 'انضم',

      // Metrics
      metrics_title: 'الأثر بالأرقام',
      metrics_subtitle: 'الاستوديو الريادي بالأرقام.',
      metric_ventures: 'مشروع تم بناؤه',
      metric_capital: 'رأس مال مستثمر',
      metric_team: 'عضو فريق',
      metric_markets: 'سوق تم الوصول إليه',

      // Testimonials
      testimonials_title: 'قصص المؤسسين',
      testimonials_subtitle: 'ما يقوله المؤسسون والمستثمرون عن العمل معنا.',
      testimonials_view_all: 'عرض جميع القصص',
      testimonial_1_quote: 'سفن تك كابيتال لم تكتفِ بالاستثمار — بل أصبحوا شركاءنا المؤسسين. فريق المنتج لديهم بنى النسخة الأولى خلال 12 أسبوعاً، ووصولهم للسوق فتح لنا أبواباً لم نكن لنفتحها بمفردنا.',
      testimonial_1_name: 'سارة التميمي',
      testimonial_1_role: 'الرئيس التنفيذي، فن فلو',
      testimonial_2_quote: 'الشفافية والحوكمة التي نراها من سفن تك كابيتال هي بمستوى مؤسسي. تقارير شهرية، مقاييس واضحة، ومدير حساب مخصص يفهم أهدافنا.',
      testimonial_2_name: 'خالد الدوسري',
      testimonial_2_role: 'مستثمر رئيسي',

      // Newsletter
      newsletter_title: 'ابقَ على اطلاع',
      newsletter_subtitle: 'احصل على رؤى حول بناء المشاريع وفرص الاستثمار وتحديثات المنظومة.',
      newsletter_placeholder: 'أدخل بريدك الإلكتروني',
      newsletter_btn: 'اشترك',
      newsletter_consent: 'بالاشتراك، أنت توافق على سياسة الخصوصية.',

      // Final CTA
      final_cta_title: 'مستعد لبناء شيء عظيم؟',
      final_cta_desc: 'سواء كنت مؤسسًا يحمل رؤية أو مستثمرًا يبحث عن فرص، نحن جاهزون للعمل معك.',

      // Footer
      footer_tagline: 'نبني شركات تقنية صُمّمت لتقود.',
      footer_company: 'الشركة',
      footer_about: 'من نحن',
      footer_how: 'كيف نبني',
      footer_team: 'الفريق',
      footer_portfolio: 'المحفظة',
      footer_careers: 'الوظائف',
      footer_resources: 'الموارد',
      footer_contact: 'تواصل معنا',
      footer_legal: 'قانوني',
      footer_privacy: 'سياسة الخصوصية',
      footer_terms: 'شروط الخدمة',
      footer_cookies: 'سياسة الكوكيز',
      footer_connect: 'تواصل',
      footer_apply: 'انضم الآن',
      footer_newsletter: 'النشرة البريدية',
      footer_rights: 'جميع الحقوق محفوظة © Seven Tech Capital A Venture studio',
    }
  };

  constructor(@Inject(PLATFORM_ID) platformId: Object) {
    this.isBrowser = isPlatformBrowser(platformId);
    if (this.isBrowser) {
      this.initLang();
      
      // Expose for legacy scripts
      (window as any).LangManager = this;
      
      // We must re-apply the translations whenever Angular navigates
      // because Angular renders new templates that might need translation
      window.addEventListener('popstate', () => {
        setTimeout(() => this.applyDOM(this.langSubject.value), 50);
      });
    }
  }

  get currentLang(): string {
    return this.langSubject.value;
  }

  public t(key: string): string {
    const lang = this.currentLang;
    return (this.strings[lang] && this.strings[lang][key]) || key;
  }

  private initLang() {
    const lang = localStorage.getItem(this.STORAGE_KEY) || 'en';
    this.setLang(lang);
  }

  public setLang(lang: string) {
    if (this.isBrowser) {
      localStorage.setItem(this.STORAGE_KEY, lang);
      document.documentElement.lang = lang;
      document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
      
      if (lang === 'ar') {
        document.body.style.fontFamily = 'var(--font-ar)';
      } else {
        document.body.style.fontFamily = 'var(--font-en)';
      }

      this.applyDOM(lang);
    }
    this.langSubject.next(lang);
  }

  public toggleLang() {
    const newLang = this.langSubject.value === 'en' ? 'ar' : 'en';
    this.setLang(newLang);
  }

  public applyDOM(lang: string) {
    if (!this.isBrowser) return;
    
    // Update all text elements with data-i18n
    document.querySelectorAll('[data-i18n]').forEach(el => {
      const key = el.getAttribute('data-i18n');
      if (key && this.strings[lang] && this.strings[lang][key]) {
        el.textContent = this.strings[lang][key];
      }
    });

    // Update placeholders
    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
      const key = el.getAttribute('data-i18n-placeholder');
      if (key && this.strings[lang] && this.strings[lang][key]) {
        el.setAttribute('placeholder', this.strings[lang][key]);
      }
    });
  }
}
