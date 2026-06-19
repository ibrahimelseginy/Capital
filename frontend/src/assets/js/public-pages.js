
(function() {
  const LangManager = {
    get currentLang() { return window.LangManager ? window.LangManager.currentLang : 'en'; },
    t: function(k) { return window.LangManager ? window.LangManager.t(k) : k; }
  };
  const t = (k) => LangManager.t(k);

/**
 * SEVEN TECH CAPITAL — All Public Pages
 * Partners, Investors, Events, Blogs, Content, Jobs, Branches, Speakers, Search
 */



window.filterEvents = function(btn, filterGroup, value) {
  // Update active state
  if (filterGroup === 'time') {
    document.querySelectorAll('.filters-time .chip').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
  } else if (filterGroup === 'cat') {
    if (btn.classList.contains('active')) {
       btn.classList.remove('active'); // toggle off
    } else {
       document.querySelectorAll('.filters-cat .chip').forEach(c => c.classList.remove('active'));
       btn.classList.add('active');
    }
  }

  // Get current filters
  const activeTimeBtn = document.querySelector('.filters-time .chip.active');
  const activeCatBtn = document.querySelector('.filters-cat .chip.active');
  
  const timeVal = activeTimeBtn ? activeTimeBtn.dataset.val : 'All';
  const catVal = activeCatBtn ? activeCatBtn.dataset.val : null;

  document.querySelectorAll('.event-card').forEach(el => {
    const elTime = el.dataset.time;
    const elCat = el.dataset.cat;
    
    let matchTime = true;
    if (!timeVal.includes('All') && !timeVal.includes('الكل')) {
       matchTime = (timeVal.includes('Upcoming') || timeVal.includes('القادمة')) ? elTime === 'upcoming' : elTime === 'past';
    }
    
    let matchCat = true;
    if (catVal && !catVal.includes('All') && !catVal.includes('الكل')) {
       matchCat = elCat === catVal;
    }
    
    el.style.display = (matchTime && matchCat) ? 'flex' : 'none';
  });
};

window.filterJobs = function() {
  const dept = document.getElementById('filter-dept').value;
  const loc = document.getElementById('filter-loc').value;
  const type = document.getElementById('filter-type').value;
  document.querySelectorAll('.job-preview-item').forEach(el => {
    const matchDept = dept.includes('All') || dept.includes('جميع') || el.dataset.dept.includes(dept) || dept.includes(el.dataset.dept);
    const matchLoc = loc.includes('All') || loc.includes('جميع') || el.dataset.loc.includes(loc) || loc.includes(el.dataset.loc);
    const matchType = type.includes('All') || type.includes('جميع') || el.dataset.type.includes(type) || type.includes(el.dataset.type);
    el.style.display = (matchDept && matchLoc && matchType) ? 'flex' : 'none';
  });
};


// ════════════════════════════════════════
// PARTNERS PAGE
// ════════════════════════════════════════
function partnersPage() {
  const isAr = LangManager.currentLang === 'ar';
  
  const filters = isAr ? 
    ['الكل', 'استراتيجي', 'تقنية', 'استثمار', 'مالي', 'قانوني', 'إعلام', 'حكومي'] : 
    ['All', 'Strategic', 'Technology', 'Investment', 'Financial', 'Legal', 'Media', 'Government'];

  const searchPlaceholder = isAr ? "ابحث عن الشركاء..." : "Search partners...";

  const featured = isAr ? [
    { name: 'أرامكو', cat: 'استراتيجي', desc: 'شريك رائد في مجال الطاقة يقود النمو الاستراتيجي عبر المنطقة.', loc: 'الرياض، السعودية', img: 'partner-aramco', enName: 'aramco' },
    { name: 'إس تي سي', cat: 'تقنية', desc: 'شريك اتصالات يدفع الابتكار والوصول للسوق.', loc: 'الرياض، السعودية', img: 'partner-stc', enName: 'stc' },
    { name: 'نيوم', cat: 'استراتيجي', desc: 'مدينة المستقبل ومركز التقنية المتقدمة والاستدامة.', loc: 'نيوم، السعودية', img: 'partner-neom', enName: 'neom' }
  ] : [
    { name: 'Aramco', cat: 'Strategic', desc: 'Leading energy partner driving strategic growth across the region.', loc: 'Riyadh, KSA', img: 'partner-aramco', enName: 'aramco' },
    { name: 'STC', cat: 'Technology', desc: 'Telecommunications partner driving innovation and market access.', loc: 'Riyadh, KSA', img: 'partner-stc', enName: 'stc' },
    { name: 'NEOM', cat: 'Strategic', desc: 'The city of the future and center for advanced technology.', loc: 'NEOM, KSA', img: 'partner-neom', enName: 'neom' }
  ];

  const allPartners = isAr ? [
    { name: 'سابك', cat: 'استراتيجي', img: 'partner-sabic', enName: 'sabic' },
    { name: 'وزارة الاتصالات وتقنية المعلومات', cat: 'حكومي', img: 'partner-mcit', enName: 'mcit' },
    { name: 'هب 71', cat: 'استثمار', img: 'partner-hub71', enName: 'hub71' },
    { name: 'مسك', cat: 'استراتيجي', img: 'partner-misk', enName: 'misk' }
  ] : [
    { name: 'SABIC', cat: 'Strategic', img: 'partner-sabic', enName: 'sabic' },
    { name: 'MCIT', cat: 'Government', img: 'partner-mcit', enName: 'mcit' },
    { name: 'Hub71', cat: 'Investment', img: 'partner-hub71', enName: 'hub71' },
    { name: 'Misk', cat: 'Strategic', img: 'partner-misk', enName: 'misk' }
  ];

  const featuredTitle = isAr ? "شركاء مميزون" : "Featured Partners";
  const allTitle = isAr ? "جميع الشركاء" : "All Partners";
  const activeText = isAr ? "نشط" : "Active";

  const filterScript = `document.querySelectorAll('#partners-filters .chip').forEach(c=>c.classList.remove('active')); this.classList.add('active'); const cat=this.innerText; document.querySelectorAll('.partner-item').forEach(i => i.style.display = (cat==='All'||cat==='الكل'||i.dataset.category===cat) ? 'block' : 'none');`;
  const searchScript = `const q=this.value.toLowerCase(); document.querySelectorAll('.partner-item').forEach(i => i.style.display = i.dataset.name.toLowerCase().includes(q) ? 'block' : 'none');`;

  return `
  <section class="section partners-section" style="padding-top:calc(var(--header-height) + var(--space-8)); position: relative; overflow: hidden;">
    <div class="page-header-glow"></div>
    <div class="container-content" style="position: relative; z-index: 1;">
      <div class="section-header reveal">
        <div class="gold-line"></div>
        <h1 class="text-h1">${t('nav_partners')}</h1>
        <p class="text-body-lg text-secondary">${t('partners_subtitle')}</p>
      </div>

      <!-- Control Bar -->
      <div class="partners-control-bar reveal">
        <div class="partners-search-wrapper">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="partners-search-icon"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" class="form-input search-input" placeholder="${searchPlaceholder}" onkeyup="${searchScript}">
        </div>
        <div class="partners-filters-wrapper" id="partners-filters">
          ${filters.map((f, i) => `<button class="chip ${i === 0 ? 'active' : ''}" onclick="${filterScript}">${f}</button>`).join('')}
        </div>
      </div>

      <!-- Featured Partners -->
      <div class="mb-16 reveal">
        <h2 class="text-h3 mb-8 featured-section-title">${featuredTitle}</h2>
        <div class="grid-3">
          ${featured.map(p => `
          <a href="#/partner/${p.enName}" class="card card-hover partner-item partner-featured-card" data-category="${p.cat}" data-name="${p.name}">
            <div class="partner-logo-container">
              <img src="assets/images/${p.img}.png" class="partner-logo-img" alt="${p.name}">
            </div>
            <div class="card-body">
              <div class="d-flex gap-2 mb-3 items-center">
                <span class="badge badge-primary">${p.cat}</span>
                <span class="badge badge-success badge-dot">${activeText}</span>
              </div>
              <h3 class="text-h5 mb-2">${p.name}</h3>
              <p class="text-body-sm text-secondary">${p.desc}</p>
              <div class="d-flex items-center gap-2 mt-4 text-caption text-tertiary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                ${p.loc}
              </div>
            </div>
          </a>`).join('')}
        </div>
      </div>

      <!-- All Partners Grid -->
      <h2 class="text-h3 mb-8 all-section-title reveal">${allTitle}</h2>
      <div class="grid-4 reveal">
        ${allPartners.map(p => `
        <a href="#/partner/${p.enName}" class="card card-hover partner-item partner-general-card" data-category="${p.cat}" data-name="${p.name}">
          <div class="partner-general-logo-container">
            <img src="assets/images/${p.img}.png" class="partner-logo-img" alt="${p.name}">
          </div>
          <div class="card-body" style="padding:var(--space-5)">
            <span class="badge badge-neutral" style="font-size:10px;">${p.cat}</span>
            <h4 class="text-label mt-2">${p.name}</h4>
          </div>
        </a>`).join('')}
      </div>
    </div>
  </section>`;
}

// ════════════════════════════════════════
// PARTNER DETAIL
// ════════════════════════════════════════
function partnerDetailPage(params) {
  const name = (params.id || 'partner').replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
  return `
  <section class="section" style="padding-top:calc(var(--header-height) + var(--space-8))">
    <div class="container-content">
      <div class="breadcrumbs mb-6">
        <a href="#/partners">Partners</a>
        <span class="breadcrumb-separator">›</span>
        <span class="current">${name}</span>
      </div>
      <div class="grid-12" style="gap:var(--space-10)">
        <div style="grid-column:span 8">
          <div class="d-flex items-center gap-4 mb-6">
            <div style="width:80px;height:80px;background:var(--bg-secondary);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center" class="text-h4 text-tertiary">${name.charAt(0)}</div>
            <div>
              <h1 class="text-h2">${name}</h1>
              <div class="d-flex gap-2 mt-2">
                <span class="badge badge-primary">Strategic Partner</span>
                <span class="badge badge-success badge-dot">Active</span>
              </div>
            </div>
          </div>
          <div class="gold-line mb-6"></div>
          <h2 class="text-h4 mb-4">Partnership Overview</h2>
          <p class="text-body text-secondary mb-6" style="line-height:1.8">
            ${name} is a strategic partner of SEVEN TECH CAPITAL, providing critical expertise and market access across the technology ecosystem. Our partnership focuses on collaborative innovation, shared resources, and mutual growth opportunities.
          </p>
          <h3 class="text-h5 mb-3">Shared Objectives</h3>
          <ul style="list-style:disc;padding-inline-start:var(--space-6);color:var(--text-secondary)" class="mb-8">
            <li class="mb-2">Accelerate technology adoption across shared markets</li>
            <li class="mb-2">Co-develop innovative solutions for enterprise clients</li>
            <li class="mb-2">Provide portfolio companies with strategic market access</li>
            <li class="mb-2">Share expertise and resources across the venture ecosystem</li>
          </ul>
          <h3 class="text-h5 mb-4">Related Projects</h3>
          <div class="d-flex flex-col gap-3 mb-8">
            ${['FinFlow','DataPulse'].map(p => `
            <div class="card card-hover" style="padding:var(--space-4);display:flex;align-items:center;justify-content:space-between">
              <div class="d-flex items-center gap-3">
                <div style="width:40px;height:40px;background:var(--color-primary-lighter);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;color:var(--action-primary);font-weight:700">${p[0]}</div>
                <div>
                  <div class="text-label">${p}</div>
                  <div class="text-caption text-secondary">Active Project</div>
                </div>
              </div>
              <span class="badge badge-success badge-dot">Active</span>
            </div>`).join('')}
          </div>
        </div>
        <aside style="grid-column:span 4">
          <div class="card" style="padding:var(--space-6)">
            <h4 class="text-label mb-4">Partner Information</h4>
            <div class="d-flex flex-col gap-4">
              <div><div class="text-caption text-secondary mb-1">Category</div><div class="text-body-sm">Strategic Partner</div></div>
              <div><div class="text-caption text-secondary mb-1">Country</div><div class="text-body-sm">Saudi Arabia</div></div>
              <div><div class="text-caption text-secondary mb-1">City</div><div class="text-body-sm">Riyadh</div></div>
              <div><div class="text-caption text-secondary mb-1">Partnership Since</div><div class="text-body-sm">January 2024</div></div>
              <div><div class="text-caption text-secondary mb-1">Status</div><span class="badge badge-success badge-dot">Active</span></div>
              <a href="#" class="btn btn-secondary w-full mt-2">Visit Website</a>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>`;
}

// ════════════════════════════════════════
// PUBLIC INVESTORS PAGE
// ════════════════════════════════════════
function investorsPublicPage() {
  const isAr = LangManager.currentLang === 'ar';
  
  const heroTitle = isAr ? "استثمر في مستقبل التقنية" : "Invest in the Future of Technology";
  const heroDesc = isAr ? "انضم لمجموعة مختارة من المستثمرين للوصول إلى فرص ريادية بمعايير مؤسسية مع حوكمة شفافة وتقارير احترافية." : "Join a select group of investors accessing institutional-grade venture opportunities with transparent governance and professional reporting.";
  const btnInvest = isAr ? "انضم كمستثمر" : "Become an Investor";
  const btnLogin = isAr ? "تسجيل دخول المستثمرين" : "Investor Sign In";
  
  const modelTitle = isAr ? "نموذجنا الاستثماري" : "Our Investment Model";
  const modelDesc = isAr ? "تعمل سفن تك كابيتال كاستوديو ريادي — نحن لا نستثمر فقط، بل نبني. يشارك مستثمرونا في محفظة من المشاريع التقنية التي يتم تصميمها وبناؤها وتوسيعها بواسطة فريقنا التشغيلي." : "SEVEN TECH CAPITAL operates as a venture studio — we don't just invest, we build. Our investors participate in a portfolio of technology ventures that are designed, built, and scaled by our operational team.";
  
  const modelFeatures = isAr ? [
    ['بناء عملي مباشر','نبني الشركات من الصفر بفرق مخصصة للمنتج والهندسة والنمو.'],
    ['حوكمة شفافة','تقارير دورية، مقاييس واضحة، وإدارة حسابات مخصصة لكل مستثمر.'],
    ['عمليات مؤسسية','إدارة مالية احترافية، امتثال قانوني، وتخطيط منظم للتخارج.']
  ] : [
    ['Hands-on Building','We build companies from the ground up with dedicated product, engineering, and growth teams.'],
    ['Transparent Governance','Regular reporting, clear metrics, and dedicated account management for every investor.'],
    ['Institutional Operations','Professional financial management, legal compliance, and structured exit planning.']
  ];

  const whyTitle = isAr ? "لماذا تستثمر معنا" : "Why Invest With Us";
  const whyStats = isAr ? [
    ['+12','مشروع تم بناؤه وتوسعته'],
    ['45 مليون دولار','رأس مال مستثمر حتى الآن'],
    ['3.2x','متوسط عائد المحفظة'],
    ['24/7','مدير حساب مخصص']
  ] : [
    ['12+','Ventures built and scaled'],
    ['$45M','Capital deployed to date'],
    ['3.2x','Average portfolio return'],
    ['24/7','Dedicated account manager']
  ];

  const sectorsTitle = isAr ? "قطاعات الاستثمار" : "Investment Sectors";
  const sectorsDesc = isAr ? "تغطي محفظتنا قطاعات التقنية عالية النمو في منطقة الشرق الأوسط وشمال إفريقيا." : "Our portfolio spans high-growth technology sectors across MENA.";
  const sectorsList = isAr ? 
    ['التقنية المالية','الذكاء الاصطناعي والبيانات','التقنية العقارية','التقنية الصحية','التقنية التعليمية','الخدمات اللوجستية','التجارة الإلكترونية','البرمجيات كخدمة'] : 
    ['FinTech','AI & Data','PropTech','HealthTech','EdTech','Logistics','E-Commerce','SaaS'];

  const ndaTitle = isAr ? "متطلبات اتفاقية السرية (لمدة 5 سنوات)" : "NDA Requirement (5 Years)";
  const ndaDesc = isAr ? "يتطلب الوصول إلى معلومات المشاريع التفصيلية والتقارير المالية والفرص الاستثمارية توقيع اتفاقية عدم إفشاء (NDA) سارية لمدة 5 سنوات. هذا يحمي شركات محفظتنا ومستثمرينا." : "Access to detailed project information, financial reports, and investment opportunities requires signing a Non-Disclosure Agreement valid for 5 years. This protects both our portfolio companies and our investors.";

  const faqTitle = isAr ? "الأسئلة الشائعة" : "Frequently Asked Questions";
  const faqList = isAr ? [
    ['ما هو الحد الأدنى للاستثمار؟','الحد الأدنى للاستثمار هو 500,000 (نصف مليون).'],
    ['كيف أتلقى تحديثات حول استثماراتي؟','يتلقى المستثمرون المعتمدون تقارير شهرية ومراجعات ربع سنوية وتحديثات في الوقت الفعلي من خلال لوحة التحكم الخاصة بهم.'],
    ['ما هو الأفق الزمني المعتاد للاستثمار؟','تم تصميم مشاريعنا لأفق زمني يمتد من 3 إلى 7 سنوات، مع التخطيط المنظم للتخارج منذ اليوم الأول.'],
    ['هل يمكنني الاستثمار في مشاريع محددة؟','نعم. بعد الانتهاء من إجراءات اتفاقية عدم الإفشاء (NDA)، يمكن للمستثمرين اختيار المشاركة في مشاريع محددة أو الاستثمار في كامل المحفظة.'],
    ['كيف تتم هيكلة الحوكمة؟','كل مشروع لديه مدير مشروع مخصص ومدير تنفيذي ومدير حساب لضمان حوكمة بمعايير مؤسسية.']
  ] : [
    ['What is the minimum investment?','The minimum investment is 500,000 (Half a million).'],
    ['How do I receive updates on my investments?','Approved investors receive monthly reports, quarterly reviews, and real-time updates through their dedicated dashboard.'],
    ['What is the typical investment horizon?','Our ventures are designed for 3-7 year horizons, with structured exit planning beginning from day one.'],
    ['Can I invest in specific projects?','Yes. After completing the NDA process, investors can choose to participate in specific ventures or invest across the portfolio.'],
    ['How is governance structured?','Each venture has a dedicated project manager, executive manager, and account manager ensuring institutional-grade governance.']
  ];

  const finalTitle = isAr ? "مستعد للاستثمار؟" : "Ready to Invest?";
  const finalDesc = isAr ? "ابدأ رحلتك كمستثمر اليوم. سيقوم فريقنا بإرشادك خلال العملية." : "Start your investor journey today. Our team will guide you through the process.";
  const finalBtn = isAr ? "بدء طلب المستثمر" : "Begin Investor Application";

  return `
  <section class="section investors-section" style="padding-top:calc(var(--header-height) + var(--space-8)); position: relative; overflow: hidden;">
    <div class="page-header-glow"></div>
    <div class="container-content" style="position: relative; z-index: 1;">
      <!-- Hero -->
      <div class="text-center mb-20 reveal" style="max-width:800px;margin-inline:auto">
        <div class="gold-line mx-auto mb-4"></div>
        <h1 class="text-display-xl mb-4 investors-hero-title">${heroTitle}</h1>
        <p class="text-body-lg text-secondary">${heroDesc}</p>
        <div class="d-flex gap-4 justify-center mt-10 flex-wrap">
          <a href="#/onboarding/investor" class="btn btn-primary btn-lg">${btnInvest}</a>
          <a href="#/login" class="btn btn-secondary btn-lg">${btnLogin}</a>
        </div>
      </div>

      <!-- Investment Model -->
      <div class="grid-2 mb-20" style="gap:var(--space-12)">
        <div class="reveal">
          <h2 class="text-h2 mb-4">${modelTitle}</h2>
          <p class="text-body text-secondary mb-8" style="line-height:1.8">${modelDesc}</p>
          <div class="d-flex flex-col gap-6">
            ${modelFeatures.map(([title, desc]) => `
            <div class="d-flex gap-4 items-start">
              <div class="model-bullet"></div>
              <div><div class="text-label mb-1" style="font-weight:var(--weight-bold);">${title}</div><div class="text-body-sm text-secondary" style="line-height:1.6;">${desc}</div></div>
            </div>`).join('')}
          </div>
        </div>
        <div class="reveal">
          <div class="card investors-stats-card" style="padding:var(--space-8);">
            <h3 class="text-h4 mb-8" style="font-weight:var(--weight-bold);">${whyTitle}</h3>
            <div class="d-flex flex-col gap-6">
              ${whyStats.map(([val, label]) => `
              <div class="d-flex items-center gap-6 stat-row">
                <div class="text-h2 stat-value" style="min-width:100px;font-variant-numeric:tabular-nums">${val}</div>
                <div class="text-body-sm text-secondary" style="font-weight:var(--weight-medium);">${label}</div>
              </div>`).join('')}
            </div>
          </div>
        </div>
      </div>

      <!-- Project Categories -->
      <div class="mb-20 reveal">
        <div class="section-header center"><div class="gold-line"></div><h2>${sectorsTitle}</h2><p class="text-body-lg text-secondary">${sectorsDesc}</p></div>
        <div class="grid-4 mt-10">
          ${sectorsList.map(s => `
          <div class="card card-hover text-center sector-card" style="padding:var(--space-8)">
            <div class="sector-icon-container">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg>
            </div>
            <div class="text-label" style="font-weight:var(--weight-bold);">${s}</div>
          </div>`).join('')}
        </div>
      </div>

      <!-- NDA Requirement -->
      <div class="card reveal investors-nda-card" style="padding:var(--space-8);">
        <div class="d-flex gap-5 items-start flex-wrap">
          <div class="nda-icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <div style="flex:1; min-width:280px;">
            <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold);">${ndaTitle}</h3>
            <p class="text-body-sm text-secondary" style="line-height:1.6;">${ndaDesc}</p>
          </div>
        </div>
      </div>

      <!-- FAQ -->
      <div class="mt-20 reveal">
        <div class="section-header center"><div class="gold-line"></div><h2>${faqTitle}</h2></div>
        <div class="container-narrow mt-10">
          ${faqList.map(([q, a]) => `
          <div class="accordion-item investors-faq-item">
            <button class="accordion-trigger" style="font-weight:var(--weight-bold);">${q}<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg></button>
            <div class="accordion-content"><div class="accordion-body" style="line-height:1.7;">${a}</div></div>
          </div>`).join('')}
        </div>
      </div>

      <!-- Final CTA -->
      <div class="text-center mt-20 py-16 reveal">
        <h2 class="text-h2 mb-4" style="font-weight:var(--weight-bold);">${finalTitle}</h2>
        <p class="text-body-lg text-secondary mb-10 mx-auto" style="max-width:540px">${finalDesc}</p>
        <a href="#/onboarding/investor" class="btn btn-primary btn-lg">${finalBtn}</a>
      </div>
    </div>
  </section>`;
}

// ════════════════════════════════════════
// BLOGS DATA & HELPERS
// ════════════════════════════════════════
const getArticles = (isAr) => isAr ? [
  { id: 'future-of-venture-studios', title: 'لماذا تعتبر استوديوهات المشاريع هي مستقبل بناء الشركات', cat: 'بناء المشاريع', author: 'أحمد الراشد', date: '5 يونيو 2026', time: '8', img: 'blog-venture-studios', tags: ['#تقنية', '#استوديوهات_مشاريع', '#بناء_شركات'], content: '<p>النموذج التقليدي للشركات الناشئة لم يعد فعالاً. توفر استوديوهات المشاريع رأس المال والفريق والبنية التحتية التشغيلية التي يحتاجها المؤسسون للنجاح من اليوم الأول.</p><p>هنا نقوم ببناء المستقبل. ##استثمار ##مستقبل</p>', media: [ { type: 'image', url: 'assets/images/blog-venture-studios.png' }, { type: 'youtube', url: 'https://www.youtube.com/embed/dQw4w9WgXcQ' } ] },
  { id: 'investment-returns-data', title: 'فهم عوائد استوديوهات المشاريع: تحليل مبني على البيانات', cat: 'الاستثمار', author: 'سارة التميمي', date: '28 مايو 2026', time: '6', img: 'blog-investment-returns', tags: ['#استثمار', '#بيانات', '#عائد'], content: '<p>تحليل عميق لكيفية تحقيق عوائد استثنائية من خلال منهجية الاستوديو.</p>', media: [ { type: 'gallery', urls: ['assets/images/blog-investment-returns.png', 'assets/images/blog-building-scale.png'] }, { type: 'file', url: '#', name: 'تقرير العوائد.pdf' } ] },
  { id: 'building-for-scale', title: 'البناء للتوسع: قرارات البنية التحتية المهمة', cat: 'التقنية', author: 'محمد ك.', date: '20 مايو 2026', time: '5', img: 'blog-building-scale', tags: ['#تقنية', '#توسع', '#بنية_تحتية'], content: '<p>قرارات تقنية مصيرية لتوسع المشاريع.</p>', media: [ { type: 'video', url: 'https://www.w3schools.com/html/mov_bbb.mp4' } ] }
] : [
  { id: 'future-of-venture-studios', title: 'Why Venture Studios Are the Future of Company Building', cat: 'Venture Building', author: 'Ahmad Al-Rashid', date: 'Jun 5, 2026', time: '8', img: 'blog-venture-studios', tags: ['#Tech', '#VentureStudios', '#CompanyBuilding'], content: '<p>The traditional startup model is broken. Venture studios provide the capital, team, and operational infrastructure that founders need to succeed from day one.</p><p>We are building the future here. ##Investment ##Future</p>', media: [ { type: 'image', url: 'assets/images/blog-venture-studios.png' }, { type: 'youtube', url: 'https://www.youtube.com/embed/dQw4w9WgXcQ' } ] },
  { id: 'investment-returns-data', title: 'Understanding Venture Studio Returns: A Data-Driven Analysis', cat: 'Investment', author: 'Sarah Al-Tamimi', date: 'May 28, 2026', time: '6', img: 'blog-investment-returns', tags: ['#Investment', '#Data', '#Returns'], content: '<p>Deep dive into how we achieve exceptional returns through the studio methodology.</p>', media: [ { type: 'gallery', urls: ['assets/images/blog-investment-returns.png', 'assets/images/blog-building-scale.png'] }, { type: 'file', url: '#', name: 'Returns_Report.pdf' } ] },
  { id: 'building-for-scale', title: 'Building for Scale: Architecture Decisions That Matter', cat: 'Technology', author: 'Mohammed K.', date: 'May 20, 2026', time: '5', img: 'blog-building-scale', tags: ['#Tech', '#Scaling', '#Architecture'], content: '<p>Critical tech decisions for scaling ventures.</p>', media: [ { type: 'video', url: 'https://www.w3schools.com/html/mov_bbb.mp4' } ] }
];

window.filterBlogsByTag = function(tag) {
  const q = tag.toLowerCase().trim();
  const els = document.querySelectorAll('.blog-item-wrapper');
  let hasVisible = false;
  els.forEach(el => {
    const tags = el.dataset.tags ? el.dataset.tags.toLowerCase() : '';
    if (tags.includes(q)) {
      el.style.display = 'block';
      hasVisible = true;
    } else {
      el.style.display = 'none';
    }
  });
  // Highlight active tag
  document.querySelectorAll('.blog-tag-filter').forEach(b => {
    if (b.innerText.trim().toLowerCase() === q) b.classList.add('active', 'badge-primary');
    else b.classList.remove('active', 'badge-primary');
  });
};

window.filterBlogsByCat = function(btn, cat) {
  document.querySelectorAll('.blog-cat-filter').forEach(c => c.classList.remove('active'));
  btn.classList.add('active');
  const els = document.querySelectorAll('.blog-item-wrapper');
  els.forEach(el => {
    if (cat.includes('All') || cat.includes('الكل') || el.dataset.cat === cat) el.style.display = 'block';
    else el.style.display = 'none';
  });
  // Clear tag filters
  document.querySelectorAll('.blog-tag-filter').forEach(b => b.classList.remove('active', 'badge-primary'));
};

window.searchBlogsByInput = function(val) {
  const q = val.toLowerCase().trim();
  const els = document.querySelectorAll('.blog-item-wrapper');
  els.forEach(el => {
    // If the search starts with #, search in tags
    if (q.startsWith('#')) {
      const tags = el.dataset.tags ? el.dataset.tags.toLowerCase() : '';
      if (tags.includes(q) || q === '#') el.style.display = 'block';
      else el.style.display = 'none';
    } else {
      // Normal search by title or content
      const title = el.querySelector('.blog-card-title').innerText.toLowerCase();
      if (title.includes(q)) el.style.display = 'block';
      else el.style.display = 'none';
    }
  });
};

// ════════════════════════════════════════
// BLOGS PAGE
// ════════════════════════════════════════
function blogsPage() {
  const isAr = LangManager.currentLang === 'ar';
  const titleText = isAr ? "قيادة فكرية في بناء المشاريع، الاستثمار، والتقنية." : "Thought leadership on venture building, investment, and technology.";
  const categories = isAr ? ['الكل','بناء المشاريع','الاستثمار','التقنية'] : ['All','Venture Building','Investment','Technology'];
  const articles = getArticles(isAr);
  
  // Extract all unique tags
  const allTags = [...new Set(articles.flatMap(a => a.tags || []))];

  return `
  <section class="section events-section" style="padding-top:calc(var(--header-height) + var(--space-8)); position: relative; overflow: hidden;">
    <div class="page-header-glow"></div>
    <div class="container-content" style="position: relative; z-index: 1;">
      <div class="section-header reveal">
        <div class="gold-line"></div>
        <h1 class="text-h1">${t('nav_blogs')}</h1>
        <p class="text-body-lg text-secondary">${titleText}</p>
      </div>

      <!-- Search & Categories & HashTags -->
      <div class="d-flex justify-between items-center flex-wrap gap-4 mb-6 reveal">
        <div class="d-flex gap-3 flex-wrap" style="overflow-x:auto">
          ${categories.map((c, i) => `<button class="chip blog-cat-filter ${i === 0 ? 'active' : ''}" onclick="window.filterBlogsByCat(this, '${c}')">${c}</button>`).join('')}
        </div>
        <div style="position:relative;flex:1;min-width:260px;max-width:320px">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;top:50%;transform:translateY(-50%);${isAr?'right:16px':'left:16px'};color:var(--text-tertiary)"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" placeholder="${isAr ? 'ابحث باستخدام الهاشتاج (مثال: #استثمار)...' : 'Search by hashtag (e.g., #tech)...'}" style="width:100%;padding:12px 16px;padding-${isAr?'right':'left'}:44px;border-radius:var(--radius-full);border:1px solid var(--border-default);background:var(--bg-surface);color:var(--text-primary);outline:none;font-size:14px;transition:all 0.2s" onfocus="this.style.borderColor='var(--action-primary)'" onblur="this.style.borderColor='var(--border-default)'" onkeyup="window.searchBlogsByInput(this.value)">
        </div>
      </div>
      <div class="d-flex gap-2 flex-wrap mb-8 reveal" style="overflow-x:auto">
        ${allTags.map(tag => `<button class="badge badge-neutral blog-tag-filter" style="cursor:pointer" onclick="window.filterBlogsByTag('${tag}')">${tag}</button>`).join('')}
      </div>

      <!-- Articles Grid -->
      <div class="grid-3 reveal">
        ${articles.map((a) => `
        <div class="blog-item-wrapper" data-cat="${a.cat}" data-tags="${(a.tags||[]).join(',')}">
          <a href="#/blog/${a.id}" class="blog-card-small" style="height:100%">
            <div class="blog-card-small-image" style="background-image:url('${a.img.includes('assets/images/') ? a.img : 'assets/images/'+a.img+'.png'}');background-size:cover;background-position:center;"></div>
            <div class="blog-card-small-body d-flex flex-col">
              <span class="blog-card-category">${a.cat}</span>
              <h3 class="blog-card-title mt-2 mb-3" style="font-size:var(--text-h5)">${a.title}</h3>
              <div class="d-flex gap-2 flex-wrap mt-auto mb-4">
                ${(a.tags||[]).map(t => `<span class="text-caption text-action">${t}</span>`).join('')}
              </div>
              <div class="blog-card-author pt-3" style="border-top:1px solid var(--border-subtle)">
                <div class="blog-card-author-avatar" style="background-color:var(--action-primary);width:28px;height:28px;color:#fff;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:bold">${a.author[0]}</div>
                <div>
                  <div class="text-caption" style="font-weight:var(--weight-bold);">${a.author}</div>
                  <div class="text-caption text-tertiary">${a.date} · ${a.time} ${isAr ? 'دقائق' : 'min'}</div>
                </div>
              </div>
            </div>
          </a>
        </div>`).join('')}
      </div>
    </div>
  </section>`;
}

function blogDetailPage(params) {
  const isAr = LangManager.currentLang === 'ar';
  const articles = getArticles(isAr);
  const article = articles.find(a => a.id === params.id) || articles[0]; // fallback
  
  // Parse ##keywords in content
  let parsedContent = article.content.replace(/##([\w\u0600-\u06FF]+)/g, '<a href="#/blogs" onclick="setTimeout(()=>window.filterBlogsByTag(\'#$1\'),100)" style="color:var(--action-primary);font-weight:600;text-decoration:none">#$1</a>');

  // Generate Media Blocks
  const mediaHtml = (article.media || []).map(m => {
    switch (m.type) {
      case 'image':
        return `<img src="${m.url}" alt="Media" style="width:100%;border-radius:var(--radius-xl);margin-bottom:var(--space-6);box-shadow:var(--shadow-md)">`;
      case 'gallery':
        return `<div class="grid-2 mb-6" style="gap:var(--space-4)">${m.urls.map(u => `<div style="height:200px;background:url('${u}') center/cover;border-radius:var(--radius-lg);box-shadow:var(--shadow-sm)"></div>`).join('')}</div>`;
      case 'video':
        return `<video controls style="width:100%;border-radius:var(--radius-xl);margin-bottom:var(--space-6);background:#000;box-shadow:var(--shadow-md)"><source src="${m.url}" type="video/mp4"></video>`;
      case 'youtube':
        return `<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:var(--radius-xl);margin-bottom:var(--space-6);box-shadow:var(--shadow-md)"><iframe src="${m.url}" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0" allowfullscreen></iframe></div>`;
      case 'file':
        return `<a href="${m.url}" download class="card card-hover mb-6" style="padding:var(--space-4);display:flex;align-items:center;gap:var(--space-4);border-radius:var(--radius-lg);text-decoration:none">
          <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--color-primary-lighter);color:var(--action-primary);display:flex;align-items:center;justify-content:center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg></div>
          <div><div class="text-label" style="font-weight:var(--weight-bold);">${isAr?'تحميل الملف المرفق':'Download Attached File'}</div><div class="text-caption text-secondary">${m.name}</div></div>
        </a>`;
      default: return '';
    }
  }).join('');

  return `
  <article class="section" style="padding-top:calc(var(--header-height) + var(--space-8))">
    <div class="container-narrow">
      <div class="breadcrumbs mb-6"><a href="#/blogs">${isAr?'المقالات':'Blogs'}</a><span class="breadcrumb-separator">›</span><span class="current">${article.title}</span></div>
      
      <div class="d-flex gap-2 mb-4 flex-wrap">
        <span class="badge badge-primary">${article.cat}</span>
        ${(article.tags||[]).map(t => `<a href="#/blogs" onclick="setTimeout(()=>window.filterBlogsByTag('${t}'),100)" class="badge badge-neutral" style="text-decoration:none">${t}</a>`).join('')}
      </div>
      
      <h1 class="text-display-sm mt-2 mb-6" style="font-weight:var(--weight-bold);line-height:1.3">${article.title}</h1>
      
      <div class="blog-card-author mb-8 pb-8" style="border-bottom:1px solid var(--border-subtle)">
        <div class="blog-card-author-avatar" style="background:var(--action-primary);width:48px;height:48px;color:white;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:bold">${article.author[0]}</div>
        <div>
          <div class="text-label" style="font-weight:var(--weight-bold)">${article.author}</div>
          <div class="text-caption text-secondary">${article.date} · ${article.time} ${isAr?'دقائق قراءة':'min read'}</div>
        </div>
      </div>
      
      <!-- Media Section -->
      ${mediaHtml}

      <div class="prose text-body" style="line-height:1.9;color:var(--text-secondary)">
        ${parsedContent}
      </div>
      
      <div class="divider mt-12 mb-8"></div>
      
      <!-- Newsletter -->
      <div class="newsletter-section" style="padding:var(--space-8);background:var(--bg-secondary);border-radius:var(--radius-xl);text-align:center">
        <h3 class="text-h4 mb-2" style="font-weight:var(--weight-bold);">${isAr?'هل أعجبك المقال؟':'Enjoyed this article?'}</h3>
        <p class="text-body-sm text-secondary mb-6">${isAr?'اشترك للحصول على المزيد من الرؤى.':'Subscribe for more insights.'}</p>
        <form class="newsletter-form mx-auto" style="max-width:400px;display:flex;gap:var(--space-2)" onsubmit="return false">
          <input type="email" class="form-input flex-1" placeholder="${isAr?'البريد الإلكتروني':'Email address'}" style="border-radius:var(--radius-lg)">
          <button class="btn btn-primary" style="border-radius:var(--radius-lg); font-weight:var(--weight-semibold);">${isAr?'اشتراك':'Subscribe'}</button>
        </form>
      </div>
    </div>
  </article>`;
}

// ════════════════════════════════════════
// EVENTS PAGE
// ════════════════════════════════════════
function eventsPage() {
  const isAr = LangManager.currentLang === 'ar';
  
  const titleText = isAr ? "تواصل مع المؤسسين والمستثمرين ومجتمع ريادة الأعمال." : "Connect with founders, investors, and the venture community.";
  
  const events = isAr ? [
    { title:'يوم عروض المشاريع 2026', date:'يوليو 15', loc:'الرياض، السعودية', cat:'يوم عروض', status:'التسجيل مفتوح', type:'حضوري', img:'event-demo-day' },
    { title:'لقاء المستثمرين: تحديث الربع الثاني', date:'يوليو 28', loc:'عبر الإنترنت', cat:'ندوة', status:'دعوة فقط', type:'عن بُعد', img:'event-investor-briefing' },
    { title:'ورشة عمل ملاءمة المنتج للسوق', date:'أغسطس 10', loc:'دبي، الإمارات', cat:'ورشة عمل', status:'ممتلئ', type:'حضوري', img:'event-workshop' },
    { title:'قمة التقنية للشرق الأوسط', date:'سبتمبر 05', loc:'الرياض، السعودية', cat:'مؤتمر', status:'قريباً', type:'حضوري', img:'blog-building-scale' },
    { title:'غداء عمل رواد الأعمال', date:'سبتمبر 12', loc:'الرياض، السعودية', cat:'تعارف', status:'التسجيل مفتوح', type:'حضوري', img:'blog-mena-market' },
  ] : [
    { title:'Venture Demo Day 2026', date:'Jul 15', loc:'Riyadh, KSA', cat:'Demo Day', status:'Registration Open', type:'In-Person', img:'event-demo-day' },
    { title:'Investor Briefing: Q2 Update', date:'Jul 28', loc:'Online', cat:'Webinar', status:'Invitation Only', type:'Remote', img:'event-investor-briefing' },
    { title:'Product-Market Fit Workshop', date:'Aug 10', loc:'Dubai, UAE', cat:'Workshop', status:'At Capacity', type:'In-Person', img:'event-workshop' },
    { title:'MENA Tech Summit 2026', date:'Sep 05', loc:'Riyadh, KSA', cat:'Conference', status:'Coming Soon', type:'In-Person', img:'blog-building-scale' },
    { title:'Founders Networking Lunch', date:'Sep 12', loc:'Riyadh, KSA', cat:'Networking', status:'Registration Open', type:'In-Person', img:'blog-mena-market' },
  ];

  const filtersAll = isAr ? ['الكل', 'القادمة', 'السابقة'] : ['All', 'Upcoming', 'Past'];
  const filtersCats = isAr ? ['يوم عروض', 'ندوة', 'ورشة عمل', 'مؤتمر', 'تعارف'] : ['Demo Day', 'Webinar', 'Workshop', 'Conference', 'Networking'];

  return `
  <section class="section events-section" style="padding-top:calc(var(--header-height) + var(--space-8)); position: relative; overflow: hidden;">
    <div class="page-header-glow"></div>
    <div class="container-content" style="position: relative; z-index: 1;">
      <div class="section-header reveal">
        <div class="gold-line"></div>
        <h1 class="text-h1">${t('nav_events')}</h1>
        <p class="text-body-lg text-secondary">${titleText}</p>
      </div>

      <!-- Events Control Bar -->
      <div class="events-control-bar reveal">
        <div class="filters-time-wrapper filters-time">
          ${filtersAll.map((f, i) => `<button class="chip ${i===0?'active':''}" data-val="${f}" onclick="window.filterEvents(this, 'time', '${f}')">${f}</button>`).join('')}
        </div>
        <div class="filters-cats-wrapper filters-cat">
          ${filtersCats.map(f => `<button class="chip" data-val="${f}" onclick="window.filterEvents(this, 'cat', '${f}')">${f}</button>`).join('')}
        </div>
      </div>

      <!-- Events Grid -->
      <div class="grid-3 reveal">
        ${events.map((e, i) => `
        <a href="#/event/${e.title.toLowerCase().replace(/[^a-z0-9؀-ۿ]+/g,'-')}" class="event-card" data-cat="${e.cat}" data-time="${e.status.includes('Past') || e.status.includes('السابقة') ? 'past' : 'upcoming'}">
          <div class="event-card-image" style="background:url('assets/images/${e.img}.png') center/cover;position:relative">
            <div class="event-card-date-badge"><div class="day">${e.date.split(' ')[1]}</div><div class="month">${e.date.split(' ')[0]}</div></div>
          </div>
          <div class="event-card-body">
            <div class="event-card-meta">
              <span class="badge ${e.status==='Invitation Only' || e.status==='دعوة فقط'?'badge-gold':'badge-primary'}">${e.cat}</span>
              <span class="event-loc"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>${e.loc}</span>
            </div>
            <h3>${e.title}</h3>
            <div class="d-flex gap-2 mt-4"><span class="badge badge-neutral">${e.type}</span><span class="badge ${e.status.includes('Open') || e.status.includes('مفتوح')?'badge-success':'badge-neutral'} badge-dot">${e.status}</span></div>
          </div>
        </a>`).join('')}
      </div>
    </div>
  </section>`;
}

function eventDetailPage(params) {
  const isAr = LangManager.currentLang === 'ar';
  
  const navEvents = isAr ? "الفعاليات" : "Events";
  const evTitle = isAr ? "يوم عروض المشاريع 2026" : "Venture Demo Day 2026";
  const evCat = isAr ? "يوم عروض" : "Demo Day";
  const evStatus = isAr ? "التسجيل مفتوح" : "Registration Open";
  const evType = isAr ? "حضوري" : "In-Person";
  const evDesc = isAr 
    ? "شاهد أحدث مشاريعنا وهي تُعرض أمام نخبة مختارة من المستثمرين، الشركاء، وقادة الصناعة. ست شركات، اثنتا عشرة دقيقة لكل منها، إمكانيات لا محدودة."
    : "Watch our latest ventures present to a curated audience of investors, partners, and industry leaders. Six companies, twelve minutes each, unlimited potential.";
    
  const agendaTitle = isAr ? "جدول الفعالية" : "Event Agenda";
  const day1Tab = isAr ? "اليوم الأول — ١٥ يوليو" : "Day 1 — Jul 15";
  
  const agendaItems = isAr ? [
    ['09:00','09:30','التسجيل والتعارف','—'],
    ['09:30','10:00','الكلمة الافتتاحية','الرئيس التنفيذي، سفن تك كابيتال'],
    ['10:00','10:20','عرض FinFlow','سارة التميمي'],
    ['10:20','10:40','عرض DataPulse','محمد ك.'],
    ['10:40','11:00','استراحة القهوة','—'],
    ['11:00','11:20','عرض BuildOS','عمر بشير'],
    ['11:20','12:00','جلسة أسئلة المستثمرين','متحدثون متعددون'],
    ['12:00','13:00','الغداء والتعارف','—'],
  ] : [
    ['09:00','09:30','Registration & Networking','—'],
    ['09:30','10:00','Opening Keynote','CEO, SEVEN TECH CAPITAL'],
    ['10:00','10:20','FinFlow Demo','Sarah Al-Tamimi'],
    ['10:20','10:40','DataPulse Demo','Mohammed K.'],
    ['10:40','11:00','Coffee Break','—'],
    ['11:00','11:20','BuildOS Demo','Omar Bashir'],
    ['11:20','12:00','Investor Q&A Panel','Multiple Speakers'],
    ['12:00','13:00','Lunch & Networking','—'],
  ];

  const speakersTitle = isAr ? "المتحدثون" : "Speakers";
  const speakers = isAr ? 
    ['أحمد الراشد|الرئيس التنفيذي|سفن تك كابيتال','سارة التميمي|الرئيس التنفيذي|FinFlow','محمد ك.|الرئيس التقني|DataPulse'] :
    ['Ahmad Al-Rashid|CEO|SEVEN TECH CAPITAL','Sarah Al-Tamimi|CEO|FinFlow','Mohammed K.|CTO|DataPulse'];

  const detailsTitle = isAr ? "تفاصيل الفعالية" : "Event Details";
  const dateStr = isAr ? "١٥ يوليو ٢٠٢٦" : "July 15, 2026";
  const timeStr = isAr ? "09:00 – 13:00 بتوقيت السعودية" : "09:00 – 13:00 AST";
  const locStr = isAr ? "الرياض، المملكة العربية السعودية" : "Riyadh, Kingdom of Saudi Arabia";
  const venueStr = isAr ? "مركز الملك عبدالله المالي" : "King Abdullah Financial District";
  const seatsStr = isAr ? "١٥٠ مقعد" : "150 seats";
  const remStr = isAr ? "٤٢ متبقية" : "42 remaining";
  
  const btnReg = isAr ? "سجل الآن" : "Register Now";
  const btnCal = isAr ? "إضافة للتقويم" : "Add to Calendar";
  const sep = isAr ? "، " : ", ";

  return `
  <section class="section event-detail-hero" style="padding-top:calc(var(--header-height) + var(--space-8))">
    <div class="event-detail-hero-glow"></div>
    <div class="container-content" style="position:relative; z-index:1;">
      <div class="breadcrumbs mb-6"><a href="#/events">${navEvents}</a><span class="breadcrumb-separator">›</span><span class="current">${evTitle}</span></div>
      
      <div class="event-detail-grid">
        <div>
          <div style="height:380px;background:url('assets/images/event-demo-day.png') center/cover;border-radius:var(--radius-2xl);margin-bottom:var(--space-8);box-shadow:var(--shadow-md)"></div>
          <div class="d-flex gap-2 mb-4 flex-wrap"><span class="badge badge-primary">${evCat}</span><span class="badge badge-success badge-dot">${evStatus}</span><span class="badge badge-neutral">${evType}</span></div>
          <h1 class="text-display-sm mb-4" style="font-weight:var(--weight-bold);">${evTitle}</h1>
          <p class="text-body-lg text-secondary mb-10" style="line-height:1.8">${evDesc}</p>
          
          <!-- Agenda -->
          <h2 class="text-h3 mb-6" style="font-weight:var(--weight-bold);">${agendaTitle}</h2>
          <div class="tabs mb-6"><button class="tab active" data-tab="day1">${day1Tab}</button></div>
          <div data-tab-content="day1" class="timeline-container">
            ${agendaItems.map(([start, end, title, speaker]) => `
            <div class="timeline-item">
              <div class="timeline-dot"></div>
              <div class="timeline-time">${start} – ${end}</div>
              <div class="timeline-content-card">
                <div class="text-label" style="font-weight:var(--weight-bold);">${title}</div>
                ${speaker !== '—' ? `<div class="text-caption text-secondary mt-1">${speaker}</div>` : ''}
              </div>
            </div>`).join('')}
          </div>

          <!-- Speakers -->
          <h2 class="text-h3 mt-12 mb-8" style="font-weight:var(--weight-bold);">${speakersTitle}</h2>
          <div class="grid-3 gap-6">
            ${speakers.map(s => {
              const [name, role, org] = s.split('|');
              return `
              <div class="card text-center speaker-card" style="padding:var(--space-6); border-radius:var(--radius-xl)">
                <div class="speaker-avatar-circle mx-auto">${name[0]}</div>
                <div class="text-label" style="font-weight:var(--weight-bold);">${name}</div>
                <div class="text-caption text-secondary mt-1">${role}${sep}${org}</div>
              </div>`;
            }).join('')}
          </div>
        </div>

        <aside>
          <div class="event-meta-sidebar-card">
            <h3 class="text-h4 mb-6" style="font-weight:var(--weight-bold);">${detailsTitle}</h3>
            <div class="d-flex flex-col gap-6 mb-8">
              <div class="d-flex gap-4 items-start">
                <div style="color:var(--action-primary); padding-top:2px;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 2v4"/><path d="M16 2v4"/></svg></div>
                <div><div class="text-body-sm" style="font-weight:var(--weight-semibold);">${dateStr}</div><div class="text-caption text-secondary mt-1">${timeStr}</div></div>
              </div>
              <div class="d-flex gap-4 items-start">
                <div style="color:var(--action-primary); padding-top:2px;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>
                <div><div class="text-body-sm" style="font-weight:var(--weight-semibold);">${locStr}</div><div class="text-caption text-secondary mt-1">${venueStr}</div></div>
              </div>
              <div class="d-flex gap-4 items-start">
                <div style="color:var(--action-primary); padding-top:2px;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                <div><div class="text-body-sm" style="font-weight:var(--weight-semibold);">${seatsStr}</div><div class="text-caption text-secondary mt-1">${remStr}</div></div>
              </div>
            </div>
            <a href="#/register-event" class="btn btn-primary w-full mb-3" style="border-radius:var(--radius-lg); padding: 1rem; font-weight: var(--weight-bold); text-align:center; display:block;">${btnReg}</a>
            <button class="btn btn-secondary w-full" style="border-radius:var(--radius-lg); padding: 1rem;">${btnCal}</button>
          </div>
        </aside>
      </div>
    </div>
  </section>`;
}

// ════════════════════════════════════════
// CONTENT LIBRARY
// ════════════════════════════════════════
function contentPage() {
  const isAr = LangManager.currentLang === 'ar';
  
  const types = isAr ? 
    ['الكل','أخبار','مستندات','تقارير','أدلة','فيديو','تنزيلات','إعلانات'] : 
    ['All','News','Documents','Reports','Guides','Videos','Downloads','Announcements'];
    
  const audiences = isAr ? ['جميع الجماهير', 'عام', 'المستثمرون', 'رواد الأعمال'] : ['All Audiences', 'Public', 'Investors', 'Entrepreneurs'];
  const searchPlaceholder = isAr ? "ابحث في المحتوى..." : "Search content...";
  const titleText = isAr ? "تقارير، أدلة، وموارد من منظومة مشاريعنا." : "Reports, guides, playbooks, and resources from our venture ecosystem.";
  
  const items = isAr ? [
    { title:'تقرير المشاريع للربع الأول 2026', type:'تقرير', audience:'المستثمرون', date:'أبريل 2026', icon:'chart', origAudience:'Investors' },
    { title:'دليل استوديو المشاريع', type:'دليل', audience:'عام', date:'مارس 2026', icon:'file', origAudience:'Public' },
    { title:'لقاء مع الرئيس التنفيذي: البناء في المنطقة', type:'فيديو', audience:'عام', date:'مارس 2026', icon:'video', origAudience:'Public' },
    { title:'دليل المستثمر 2026', type:'مستند', audience:'عام', date:'فبراير 2026', icon:'file', origAudience:'Public' },
    { title:'إعلان إطلاق FinFlow', type:'أخبار', audience:'عام', date:'يناير 2026', icon:'news', origAudience:'Public' },
    { title:'التقرير السنوي 2025', type:'تقرير', audience:'المستثمرون', date:'ديسمبر 2025', icon:'chart', origAudience:'Investors' },
    { title:'إرشادات تصميم المنتج', type:'دليل', audience:'رواد الأعمال', date:'نوفمبر 2025', icon:'file', origAudience:'Entrepreneurs' },
    { title:'دراسة حالة DataPulse', type:'مستند', audience:'عام', date:'أكتوبر 2025', icon:'file', origAudience:'Public' },
  ] : [
    { title:'Q1 2026 Venture Report', type:'Report', audience:'Investors', date:'Apr 2026', icon:'chart', origAudience:'Investors' },
    { title:'Venture Studio Playbook', type:'Guide', audience:'Public', date:'Mar 2026', icon:'file', origAudience:'Public' },
    { title:'CEO Fireside Chat: Building in MENA', type:'Video', audience:'Public', date:'Mar 2026', icon:'video', origAudience:'Public' },
    { title:'Investor Guide 2026', type:'Document', audience:'Public', date:'Feb 2026', icon:'file', origAudience:'Public' },
    { title:'FinFlow Launch Announcement', type:'News', audience:'Public', date:'Jan 2026', icon:'news', origAudience:'Public' },
    { title:'Annual Report 2025', type:'Report', audience:'Investors', date:'Dec 2025', icon:'chart', origAudience:'Investors' },
    { title:'Product Design Guidelines', type:'Guide', audience:'Entrepreneurs', date:'Nov 2025', icon:'file', origAudience:'Entrepreneurs' },
    { title:'DataPulse Case Study', type:'Document', audience:'Public', date:'Oct 2025', icon:'file', origAudience:'Public' },
  ];
  
  const ndaReq = isAr ? "مطلوب اتفاقية سرية" : "NDA Required";
  const btnView = isAr ? "عرض" : "View";

  return `
  <section class="section" style="padding-top:calc(var(--header-height) + var(--space-8))">
    <div class="container-content">
      <div class="section-header reveal"><div class="gold-line"></div><h1 class="text-h1">${t('nav_content')}</h1><p class="text-body-lg text-secondary">${titleText}</p></div>
      <div class="d-flex gap-3 flex-wrap mb-4 reveal">${types.map((t, i) => `<button class="chip ${i===0?'active':''}">${t}</button>`).join('')}</div>
      <div class="d-flex gap-3 flex-wrap mb-8 reveal">
        <select class="form-input form-select" style="max-width:200px">${audiences.map(a => `<option>${a}</option>`).join('')}</select>
        <input type="text" class="form-input" placeholder="${searchPlaceholder}" style="max-width:300px">
      </div>
      <div class="d-flex flex-col gap-3 reveal">
        ${items.map(item => `
        <div class="content-card">
          <div class="content-card-icon" ${item.origAudience==='Investors'?'style="color:var(--accent-gold);background:var(--color-gold-light)"':''}>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">${item.icon==='chart'?'<line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/>':item.icon==='video'?'<path d="m16 13 5.223 3.482a.5.5 0 0 0 .777-.416V7.87a.5.5 0 0 0-.752-.432L16 10.5"/><rect x="2" y="6" width="14" height="12" rx="2"/>':'<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>'}</svg>
          </div>
          <div class="flex-1"><h4 class="text-label">${item.title}</h4><p class="text-caption text-secondary">${item.type} · ${item.date}</p></div>
          <div class="d-flex gap-2 items-center">
            <span class="badge ${item.origAudience==='Investors'?'badge-gold':item.origAudience==='Entrepreneurs'?'badge-primary':'badge-neutral'}">${item.audience}</span>
            ${item.origAudience==='Investors'?`<span class="badge badge-warning badge-dot">${ndaReq}</span>`:`<button class="btn btn-ghost btn-sm">${btnView}</button>`}
          </div>
        </div>`).join('')}
      </div>
    </div>
  </section>`;
}

// ════════════════════════════════════════
// JOBS PAGE
// ════════════════════════════════════════
function jobsPage() {
  const isAr = LangManager.currentLang === 'ar';

  const subtitle = isAr ? "ابنِ الجيل القادم من شركات التقنية معنا." : "Build the next generation of technology companies with us.";
  const whyTitle = isAr ? "لماذا العمل في سفن تك كابيتال؟" : "Why Work at SEVEN TECH CAPITAL?";
  const whyDesc = isAr ? "انضم إلى فريق يبني الشركات من الصفر. اعمل عبر مشاريع متعددة، وحل مشاكل حقيقية، واصنع أثراً ملموساً في المنظومة التقنية." : "Join a team that builds companies from scratch. Work across multiple ventures, solve real problems, and make meaningful impact in the technology ecosystem.";
  
  const perks = isAr ? [
    ['🚀','ابنِ من الصفر','اعمل على المشاريع من مرحلة الفكرة إلى التوسع'],
    ['🌍','أثر إقليمي','شكّل المشهد التقني في الشرق الأوسط وشمال إفريقيا'],
    ['📈','نمو سريع','خبرة عبر وظائف متعددة في مختلف المشاريع']
  ] : [
    ['🚀','Build from Zero','Work on ventures from concept to scale'],
    ['🌍','Regional Impact','Shape MENA\'s technology landscape'],
    ['📈','Grow Fast','Cross-functional experience across ventures']
  ];

  const filterDepts = isAr ? ['جميع الأقسام','التصميم','الهندسة','التسرييق','العمليات','البيانات'] : ['All Departments','Design','Engineering','Marketing','Operations','Data'];
  const filterLocs = isAr ? ['جميع المواقع','الرياض','دبي','عن بُعد'] : ['All Locations','Riyadh','Dubai','Remote'];
  const filterTypes = isAr ? ['جميع الأنواع','دوام كامل','عقد'] : ['All Types','Full-time','Contract'];
  
  const btnView = isAr ? "عرض وتقديم" : "View & Apply";

  const jobs = isAr ? [
    { title:'مصمم منتجات أول', dept:'التصميم', loc:'الرياض، السعودية', type:'دوام كامل', exp:'متقدم' },
    { title:'مهندس برمجيات متكامل (Laravel)', dept:'الهندسة', loc:'عن بُعد', type:'دوام كامل', exp:'متوسط-متقدم' },
    { title:'قائد النمو والتسويق', dept:'التسويق', loc:'دبي، الإمارات', type:'دوام كامل', exp:'متقدم' },
    { title:'محلل بيانات', dept:'البيانات', loc:'الرياض، السعودية', type:'دوام كامل', exp:'متوسط' },
    { title:'مساعد مشاريع', dept:'العمليات', loc:'الرياض، السعودية', type:'دوام كامل', exp:'مبتدئ-متوسط' },
    { title:'باحث تجربة مستخدم', dept:'التصميم', loc:'عن بُعد', type:'عقد', exp:'متوسط' },
    { title:'مهندس عمليات تطوير (DevOps)', dept:'الهندسة', loc:'الرياض، السعودية', type:'دوام كامل', exp:'متقدم' },
    { title:'خبير استراتيجية محتوى', dept:'التسويق', loc:'دبي، الإمارات', type:'دوام كامل', exp:'متوسط' },
  ] : [
    { title:'Senior Product Designer', dept:'Design', loc:'Riyadh, KSA', type:'Full-time', exp:'Senior' },
    { title:'Full-Stack Engineer (Laravel)', dept:'Engineering', loc:'Remote', type:'Full-time', exp:'Mid-Senior' },
    { title:'Growth & Marketing Lead', dept:'Marketing', loc:'Dubai, UAE', type:'Full-time', exp:'Senior' },
    { title:'Data Analyst', dept:'Data', loc:'Riyadh, KSA', type:'Full-time', exp:'Mid' },
    { title:'Venture Associate', dept:'Operations', loc:'Riyadh, KSA', type:'Full-time', exp:'Junior-Mid' },
    { title:'UX Researcher', dept:'Design', loc:'Remote', type:'Contract', exp:'Mid' },
    { title:'DevOps Engineer', dept:'Engineering', loc:'Riyadh, KSA', type:'Full-time', exp:'Senior' },
    { title:'Content Strategist', dept:'Marketing', loc:'Dubai, UAE', type:'Full-time', exp:'Mid' },
  ];

  return `
  <section class="section events-section" style="padding-top:calc(var(--header-height) + var(--space-8)); position: relative; overflow: hidden;">
    <div class="page-header-glow"></div>
    <div class="container-content" style="position: relative; z-index: 1;">
      <div class="section-header reveal"><div class="gold-line"></div><h1 class="text-h1">${t('nav_jobs')}</h1><p class="text-body-lg text-secondary">${subtitle}</p></div>
      
      <!-- Culture -->
      <div class="culture-panel mb-12 reveal text-center" style="padding:var(--space-12) var(--space-8);color:white;">
        <div class="gold-line mx-auto mb-6"></div>
        <h2 class="text-h2 mb-4" style="color:white; font-weight:var(--weight-bold);">${whyTitle}</h2>
        <p class="text-body-lg mx-auto" style="color:rgba(255,255,255,0.7);max-width:600px;line-height:1.8">${whyDesc}</p>
        
        <div class="grid-3 mt-12" style="gap:var(--space-8)">
          ${perks.map(([emoji,title,desc]) => `
          <div class="perk-card d-flex flex-col items-center text-center">
            <div style="width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:center;font-size:2.5rem;margin-bottom:var(--space-4);box-shadow:inset 0 0 20px rgba(255,255,255,0.02)">
              ${emoji}
            </div>
            <h3 class="text-h5 mb-2" style="color:white; font-weight:var(--weight-bold);">${title}</h3>
            <p class="text-body-sm" style="color:rgba(255,255,255,0.6);line-height:1.6">${desc}</p>
          </div>`).join('')}
        </div>
      </div>

      <!-- Filters -->
      <div class="d-flex gap-3 flex-wrap mb-8 reveal">
        <select id="filter-dept" class="form-input form-select" style="max-width:200px" onchange="window.filterJobs()">${filterDepts.map(f => `<option value="${f}">${f}</option>`).join('')}</select>
        <select id="filter-loc" class="form-input form-select" style="max-width:200px" onchange="window.filterJobs()">${filterLocs.map(f => `<option value="${f}">${f}</option>`).join('')}</select>
        <select id="filter-type" class="form-input form-select" style="max-width:200px" onchange="window.filterJobs()">${filterTypes.map(f => `<option value="${f}">${f}</option>`).join('')}</select>
      </div>

      <div class="d-flex flex-col gap-4 reveal">
        ${jobs.map(j => `
        <a href="#/job/${j.title.toLowerCase().replace(/[^a-z0-9\u0600-\u06FF]+/g,'-')}" class="job-preview-item" data-dept="${j.dept}" data-loc="${j.loc}" data-type="${j.type}">
          <div class="job-preview-info">
            <h4>${j.title}</h4>
            <div class="job-preview-meta">
              <span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>${j.loc}</span>
              <span>•</span>
              <span>${j.type}</span>
              <span>•</span>
              <span>${j.dept}</span>
              <span class="badge badge-neutral">${j.exp}</span>
            </div>
          </div>
          <span class="btn btn-secondary btn-sm" style="border-radius: var(--radius-lg);">${btnView}</span>
        </a>`).join('')}
      </div>
    </div>
  </section>`;
}

function jobDetailPage(params) {
  const isAr = LangManager.currentLang === 'ar';

  const navJobs = isAr ? "الوظائف" : "Jobs";
  const jobTitle = isAr ? "مصمم منتجات أول" : "Senior Product Designer";
  const jobMeta = isAr ? "التصميم · الرياض، السعودية · دوام كامل · متقدم" : "Design · Riyadh, KSA · Full-time · Senior";
  
  const aboutTitle = isAr ? "عن الوظيفة" : "About the Role";
  const aboutDesc = isAr ? "نحن نبحث عن مصمم منتجات أول لقيادة تصميم منتجات محفظة مشاريعنا. ستعمل عبر مشاريع متعددة في نفس الوقت، من تصميم منتجات من الصفر إلى توسيع نطاق المنصات الحالية." : "We're looking for a Senior Product Designer to lead the design of our venture portfolio products. You'll work across multiple ventures simultaneously, designing everything from 0-to-1 products to scaling existing platforms.";
  
  const respTitle = isAr ? "المسؤوليات" : "Responsibilities";
  const respList = isAr ? [
    "قيادة تصميم المنتج بالكامل عبر مشاريع متعددة",
    "إنشاء نماذج أولية عالية الدقة وأنظمة تصميم",
    "إجراء أبحاث المستخدم واختبارات قابلية الاستخدام",
    "التعاون مع فرق الهندسة والمنتج والأعمال",
    "توجيه المصممين المبتدئين ووضع معايير التصميم"
  ] : [
    "Lead end-to-end product design across multiple ventures",
    "Create high-fidelity prototypes and design systems",
    "Conduct user research and usability testing",
    "Collaborate with engineering, product, and business teams",
    "Mentor junior designers and establish design standards"
  ];

  const reqTitle = isAr ? "المتطلبات" : "Requirements";
  const reqList = isAr ? [
    "5+ سنوات من الخبرة في تصميم المنتجات",
    "معرض أعمال قوي يوضح تصميم المنتج بالكامل",
    "خبرة في أنظمة التصميم واسعة النطاق",
    "إتقان استخدام Figma وأدوات التصميم الحديثة",
    "الخبرة في التصميم ثنائي اللغة (عربي/إنجليزي) ميزة إضافية"
  ] : [
    "5+ years of product design experience",
    "Strong portfolio demonstrating end-to-end product design",
    "Experience with design systems at scale",
    "Proficiency in Figma and modern design tools",
    "Experience with bilingual (AR/EN) design is a plus"
  ];

  const applyTitle = isAr ? "تقدم لهذه الوظيفة" : "Apply for this Position";
  const lblName = isAr ? "الاسم الكامل" : "Full Name";
  const lblEmail = isAr ? "البريد الإلكتروني" : "Email";
  const lblPhone = isAr ? "رقم الهاتف" : "Phone";
  const lblCountry = isAr ? "البلد / المدينة" : "Country / City";
  const lblLinkedIn = isAr ? "رابط لينكد إن" : "LinkedIn Profile";
  const lblPortfolio = isAr ? "رابط معرض الأعمال" : "Portfolio URL";
  const lblCV = isAr ? "السيرة الذاتية" : "CV / Resume";
  const txtDrop = isAr ? "اسحب سيرتك الذاتية هنا أو اضغط للرفع" : "Drop your CV here or click to upload";
  const txtSize = isAr ? "PDF، DOC بحد أقصى 10 ميجابايت" : "PDF, DOC up to 10MB";
  const lblCover = isAr ? "رسالة التغطية" : "Cover Message";
  const txtCoverPlaceholder = isAr ? "أخبرنا لماذا أنت مهتم بهذا الدور..." : "Tell us why you're interested in this role...";
  const txtConsent = isAr ? "أوافق على قيام شركة SEVEN TECH CAPITAL بمعالجة بياناتي الشخصية لأغراض التوظيف." : "I consent to SEVEN TECH CAPITAL processing my personal data for recruitment purposes.";
  const btnSubmit = isAr ? "إرسال الطلب" : "Submit Application";
  const msgSuccess = isAr ? "✓ تم إرسال الطلب" : "✓ Application Submitted";

  const infoTitle = isAr ? "معلومات الوظيفة" : "Job Information";
  const lblDept = isAr ? "القسم" : "Department";
  const valDept = isAr ? "التصميم" : "Design";
  const lblLoc = isAr ? "الموقع" : "Location";
  const valLoc = isAr ? "الرياض، السعودية" : "Riyadh, KSA";
  const lblType = isAr ? "نوع العمل" : "Work Type";
  const valType = isAr ? "حضوري مع مرونة" : "On-site with flexibility";
  const lblEmp = isAr ? "التوظيف" : "Employment";
  const valEmp = isAr ? "دوام كامل" : "Full-time";
  const lblExp = isAr ? "الخبرة" : "Experience";
  const valExp = isAr ? "5+ سنوات" : "5+ years";
  const lblDeadline = isAr ? "الموعد النهائي" : "Deadline";
  const valDeadline = isAr ? "30 أغسطس 2026" : "August 30, 2026";
  const btnApplyNow = isAr ? "تقدم الآن" : "Apply Now";

  return `
  <section class="section events-section" style="padding-top:calc(var(--header-height) + var(--space-8)); position: relative; overflow: hidden;">
    <div class="page-header-glow"></div>
    <div class="container-content" style="position: relative; z-index: 1;">
      <div class="breadcrumbs mb-6"><a href="#/jobs">${navJobs}</a><span class="breadcrumb-separator">›</span><span class="current">${jobTitle}</span></div>
      
      <div class="job-detail-grid">
        <div>
          <h1 class="text-display-sm mb-3" style="font-weight:var(--weight-bold);">${jobTitle}</h1>
          <div class="d-flex gap-3 flex-wrap mb-6 text-body-sm text-secondary">
            <span>${jobMeta}</span>
          </div>
          <div class="gold-line mb-8"></div>
          
          <h2 class="text-h4 mb-4" style="font-weight:var(--weight-bold);">${aboutTitle}</h2>
          <p class="text-body text-secondary mb-8" style="line-height:1.8">${aboutDesc}</p>
          
          <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold);">${respTitle}</h3>
          <ul style="list-style:disc;padding-inline-start:var(--space-6);color:var(--text-secondary)" class="mb-8">
            ${respList.map(item => `<li class="mb-2" style="line-height:1.6;">${item}</li>`).join('')}
          </ul>
          
          <h3 class="text-h5 mb-4" style="font-weight:var(--weight-bold);">${reqTitle}</h3>
          <ul style="list-style:disc;padding-inline-start:var(--space-6);color:var(--text-secondary)" class="mb-8">
            ${reqList.map(item => `<li class="mb-2" style="line-height:1.6;">${item}</li>`).join('')}
          </ul>

          <!-- Application Form -->
          <div id="apply-section" class="card mt-12" style="padding:var(--space-8); border-radius: var(--radius-2xl);">
            <h2 class="text-h3 mb-6" style="font-weight:var(--weight-bold);">${applyTitle}</h2>
            <form class="d-flex flex-col gap-6" onsubmit="event.preventDefault();this.querySelector('.btn-primary').textContent='${msgSuccess}';this.querySelector('.btn-primary').style.background='var(--color-success)'">
              <div class="grid-2 gap-4">
                <div class="form-group"><label class="form-label">${lblName} <span class="required">*</span></label><input type="text" class="form-input" style="border-radius: var(--radius-lg);" required></div>
                <div class="form-group"><label class="form-label">${lblEmail} <span class="required">*</span></label><input type="email" class="form-input" style="border-radius: var(--radius-lg);" required></div>
              </div>
              <div class="grid-2 gap-4">
                <div class="form-group"><label class="form-label">${lblPhone}</label><input type="tel" class="form-input" style="border-radius: var(--radius-lg);"></div>
                <div class="form-group"><label class="form-label">${lblCountry}</label><input type="text" class="form-input" style="border-radius: var(--radius-lg);"></div>
              </div>
              <div class="form-group"><label class="form-label">${lblLinkedIn}</label><input type="url" class="form-input" style="border-radius: var(--radius-lg);" placeholder="https://linkedin.com/in/..."></div>
              <div class="form-group"><label class="form-label">${lblPortfolio}</label><input type="url" class="form-input" style="border-radius: var(--radius-lg);" placeholder="https://..."></div>
              <div class="form-group"><label class="form-label">${lblCV} <span class="required">*</span></label><div class="file-upload" style="border-radius: var(--radius-xl);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg><span class="text-body-sm">${txtDrop}</span><span class="text-caption text-tertiary">${txtSize}</span></div></div>
              <div class="form-group"><label class="form-label">${lblCover}</label><textarea class="form-input" style="border-radius: var(--radius-lg);" rows="4" placeholder="${txtCoverPlaceholder}"></textarea></div>
              <div class="form-check"><input type="checkbox" class="form-check-input" required><span class="text-body-sm text-secondary">${txtConsent}</span></div>
              <button type="submit" class="btn btn-primary btn-lg" style="align-self:flex-start; border-radius: var(--radius-lg); font-weight: var(--weight-bold);">${btnSubmit}</button>
            </form>
          </div>
        </div>
        
        <aside>
          <div class="job-apply-card">
            <h4 class="text-h4 mb-6" style="font-weight:var(--weight-bold);">${infoTitle}</h4>
            <div class="d-flex flex-col gap-5 mb-8">
              <div class="d-flex justify-between items-center py-2 border-b border-subtle">
                <span class="text-caption text-secondary">${lblDept}</span>
                <span class="text-body-sm" style="font-weight:var(--weight-semibold);">${valDept}</span>
              </div>
              <div class="d-flex justify-between items-center py-2 border-b border-subtle">
                <span class="text-caption text-secondary">${lblLoc}</span>
                <span class="text-body-sm" style="font-weight:var(--weight-semibold);">${valLoc}</span>
              </div>
              <div class="d-flex justify-between items-center py-2 border-b border-subtle">
                <span class="text-caption text-secondary">${lblType}</span>
                <span class="text-body-sm" style="font-weight:var(--weight-semibold);">${valType}</span>
              </div>
              <div class="d-flex justify-between items-center py-2 border-b border-subtle">
                <span class="text-caption text-secondary">${lblEmp}</span>
                <span class="text-body-sm" style="font-weight:var(--weight-semibold);">${valEmp}</span>
              </div>
              <div class="d-flex justify-between items-center py-2 border-b border-subtle">
                <span class="text-caption text-secondary">${lblExp}</span>
                <span class="text-body-sm" style="font-weight:var(--weight-semibold);">${valExp}</span>
              </div>
              <div class="d-flex justify-between items-center py-2 border-b border-subtle">
                <span class="text-caption text-secondary">${lblDeadline}</span>
                <span class="text-body-sm" style="font-weight:var(--weight-semibold);">${valDeadline}</span>
              </div>
            </div>
            <a href="#apply-section" class="btn btn-primary w-full" style="border-radius: var(--radius-lg); padding:1rem; font-weight:var(--weight-bold); text-align:center; display:block;">${btnApplyNow}</a>
          </div>
        </aside>
      </div>
    </div>
  </section>`;
}

// ════════════════════════════════════════
// BRANCHES PAGE
// ════════════════════════════════════════
function branchesPage() {
  const isAr = LangManager.currentLang === 'ar';
  
  const titleText = isAr ? "مكاتبنا العالمية" : "Our Global Offices";
  const subtitleText = isAr ? "تواجدنا في أبرز المدن لدعم وبناء مشاريع التقنية الرائدة." : "Our presence in key cities to support and build leading technology ventures.";
  const btnDir = isAr ? "الاتجاهات" : "Directions";
  const btnCall = isAr ? "اتصال" : "Call";
  const btnEmail = isAr ? "مراسلة" : "Email";

  const branches = isAr ? [
    { 
      name: 'المقر الرئيسي بالرياض', country: 'السعودية', city: 'الرياض', 
      addr: 'برج الفيصلية، طريق الملك فهد، حي العليا، الرياض 12212', 
      phone: '+966 11 400 0000', email: 'riyadh@seventechcapital.com',
      status: 'نشط', hours: 'الأحد - الخميس 9:00 ص - 6:00 م', origStatus: 'Active',
      gradient: 'linear-gradient(135deg, #10B981 0%, #047857 100%)'
    },
    { 
      name: 'مركز الابتكار بدبي', country: 'الإمارات', city: 'دبي', 
      addr: 'مدينة دبي للإنترنت، المبنى 3، الطابق 4، دبي', 
      phone: '+971 4 300 0000', email: 'dubai@seventechcapital.com',
      status: 'نشط', hours: 'الإثنين - الجمعة 9:00 ص - 6:00 م', origStatus: 'Active',
      gradient: 'linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%)'
    },
    { 
      name: 'مكتب الإسكندرية', country: 'مصر', city: 'الإسكندرية', 
      addr: 'سان ستيفانو جراند بلازا، الإسكندرية', 
      phone: '+20 3 500 0000', email: 'alexandria@seventechcapital.com',
      status: 'نشط', hours: 'الأحد - الخميس 9:00 ص - 6:00 م', origStatus: 'Active',
      gradient: 'linear-gradient(135deg, #F59E0B 0%, #B45309 100%)'
    },
  ] : [
    { 
      name: 'Riyadh HQ', country: 'Saudi Arabia', city: 'Riyadh', 
      addr: 'Al Faisaliah Tower, King Fahd Rd, Olaya District, Riyadh 12212', 
      phone: '+966 11 400 0000', email: 'riyadh@seventechcapital.com',
      status: 'Active', hours: 'Sun-Thu 9:00 AM - 6:00 PM', origStatus: 'Active',
      gradient: 'linear-gradient(135deg, #10B981 0%, #047857 100%)'
    },
    { 
      name: 'Dubai Innovation Hub', country: 'UAE', city: 'Dubai', 
      addr: 'Dubai Internet City, Building 3, Floor 4, Dubai', 
      phone: '+971 4 300 0000', email: 'dubai@seventechcapital.com',
      status: 'Active', hours: 'Mon-Fri 9:00 AM - 6:00 PM', origStatus: 'Active',
      gradient: 'linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%)'
    },
    { 
      name: 'Alexandria Office', country: 'Egypt', city: 'Alexandria', 
      addr: 'San Stefano Grand Plaza, Alexandria', 
      phone: '+20 3 500 0000', email: 'alexandria@seventechcapital.com',
      status: 'Active', hours: 'Sun-Thu 9:00 AM - 6:00 PM', origStatus: 'Active',
      gradient: 'linear-gradient(135deg, #F59E0B 0%, #B45309 100%)'
    },
  ];

  return `
  <section class="section events-section" style="padding-top:calc(var(--header-height) + var(--space-8)); position: relative; overflow: hidden;">
    <!-- Abstract Background glow -->
    <div style="position:absolute; top: -10%; left: 50%; transform: translateX(-50%); width: 100vw; height: 600px; background: radial-gradient(ellipse at top, var(--action-primary) 0%, transparent 60%); opacity: 0.06; filter: blur(80px); pointer-events: none;"></div>
    
    <div class="container-content" style="position: relative; z-index: 2;">
      <!-- Hero -->
      <div class="text-center reveal mb-16">
        <div class="gold-line mx-auto mb-6"></div>
        <h1 class="text-display-lg mb-4" style="font-weight: var(--weight-bold); letter-spacing: -1px;">${titleText}</h1>
        <p class="text-body-lg text-secondary" style="max-width:600px; margin:0 auto; line-height: 1.8;">${subtitleText}</p>
      </div>

      <!-- Branches Layout -->
      <div class="grid-12 gap-10 mb-24 reveal">
        <!-- Interactive Map Visual Side (Hidden on mobile) -->
        <div class="hidden lg:block" style="grid-column: span 5; position: relative;">
          <div style="position: sticky; top: calc(var(--header-height) + var(--space-10)); height: 680px; border-radius: var(--radius-2xl); background: var(--bg-secondary); border: 1px solid var(--border-default); overflow: hidden; display: flex; align-items: center; justify-content: center; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            <!-- Map Container -->
            <svg viewBox="0 0 800 600" style="width: 160%; height: 160%; opacity: 0.9; fill: var(--text-tertiary);">
              <defs>
                <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
                  <path d="M 60 0 L 0 0 0 60" fill="none" stroke="currentColor" stroke-width="0.5" stroke-opacity="0.15"/>
                </pattern>
                <filter id="glow"><feGaussianBlur stdDeviation="8" result="coloredBlur"/><feMerge><feMergeNode in="coloredBlur"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
              </defs>
              <rect width="100%" height="100%" fill="url(#grid)" />
              
              <!-- Map Connections -->
              <path d="M 200 220 Q 380 200 480 380" fill="none" stroke="var(--action-primary)" stroke-width="2" stroke-dasharray="8,8" opacity="0.6">
                <animate attributeName="stroke-dashoffset" values="16;0" dur="1s" repeatCount="indefinite" />
              </path>
              <path d="M 480 380 Q 560 300 650 280" fill="none" stroke="var(--action-primary)" stroke-width="2" stroke-dasharray="8,8" opacity="0.6">
                <animate attributeName="stroke-dashoffset" values="16;0" dur="1s" repeatCount="indefinite" />
              </path>

              <!-- Riyadh Pin -->
              <g transform="translate(480, 380)">
                <circle cx="0" cy="0" r="45" fill="var(--action-primary)" opacity="0.1"><animate attributeName="r" values="20;55;20" dur="3s" repeatCount="indefinite"/></circle>
                <circle cx="0" cy="0" r="10" fill="var(--action-primary)" filter="url(#glow)"/>
                <circle cx="0" cy="0" r="4" fill="#FFF"/>
                <text x="-30" y="30" font-size="20" font-weight="bold" fill="var(--text-primary)" font-family="inherit">Riyadh</text>
              </g>
              
              <!-- Dubai Pin -->
              <g transform="translate(650, 280)">
                <circle cx="0" cy="0" r="30" fill="var(--action-primary)" opacity="0.1"><animate attributeName="r" values="15;35;15" dur="4s" repeatCount="indefinite"/></circle>
                <circle cx="0" cy="0" r="8" fill="var(--action-primary)"/>
                <circle cx="0" cy="0" r="3" fill="#FFF"/>
                <text x="18" y="6" font-size="18" fill="var(--text-secondary)" font-family="inherit">Dubai</text>
              </g>

              <!-- Alexandria Pin -->
              <g transform="translate(200, 220)">
                <circle cx="0" cy="0" r="30" fill="var(--action-primary)" opacity="0.1"><animate attributeName="r" values="15;35;15" dur="4s" repeatCount="indefinite"/></circle>
                <circle cx="0" cy="0" r="8" fill="var(--action-primary)"/>
                <circle cx="0" cy="0" r="3" fill="#FFF"/>
                <text x="-105" y="6" font-size="18" fill="var(--text-secondary)" font-family="inherit">Alexandria</text>
              </g>
            </svg>
            
            <!-- Map Overlay Info -->
            <div style="position: absolute; bottom: var(--space-8); left: var(--space-8); right: var(--space-8); padding: var(--space-8); background: var(--bg-primary); border: 1px solid var(--border-default); border-radius: var(--radius-xl); box-shadow: 0 16px 40px rgba(0,0,0,0.15); backdrop-filter: blur(10px); text-align: ${isAr ? 'right' : 'left'};">
              <div class="d-flex items-center gap-3 mb-3">
                <div style="width: 14px; height: 14px; border-radius: 50%; background: var(--action-primary); box-shadow: 0 0 12px var(--action-primary); flex-shrink: 0;"></div>
                <h4 class="text-h4" style="margin: 0; font-weight: var(--weight-bold);">${isAr ? 'المركز الإقليمي' : 'Regional Hub'}</h4>
              </div>
              <p class="text-body-lg text-secondary" style="line-height: 1.8; margin: 0;">${isAr ? 'مكاتبنا مصممة لتكون مراكز ابتكار تخدم جميع أنحاء الشرق الأوسط وشمال إفريقيا بسرعة فائقة وخبرة محلية متميزة.' : 'Strategically positioned innovation centers serving the entire MENA region with exceptional speed and local expertise.'}</p>
            </div>
          </div>
        </div>

        <!-- Scrollable Cards list -->
        <div class="branch-list-container">
          ${branches.map((b, i) => `
          <div class="branch-card-premium" style="animation: branchSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; animation-delay: ${i * 0.15}s; opacity: 0;">
            <div class="branch-map-bg"></div>
            
            <!-- Header -->
            <div class="d-flex justify-between items-start mb-6">
              <div class="d-flex gap-5 items-center">
                <div style="width: 64px; height: 64px; border-radius: var(--radius-xl); background: ${b.gradient}; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 10px 24px rgba(0,0,0,0.15);">
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                  <h3 class="text-h2" style="font-size: 1.5rem; margin-bottom: 0.25rem;">${b.city}</h3>
                  <div class="text-caption text-secondary" style="letter-spacing: 1px; text-transform: uppercase;">${b.name}</div>
                </div>
              </div>
              <span class="badge ${b.origStatus==='Active'?'badge-success':'badge-warning'} badge-dot" style="backdrop-filter: blur(4px); padding: 0.5rem 1rem; font-size: 0.8rem;">${b.status}</span>
            </div>
            
            <div class="divider mb-6" style="opacity: 0.4;"></div>
            
            <!-- Details -->
            <div class="d-flex flex-col gap-5 mb-8">
              <div class="d-flex gap-4 items-start">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--bg-primary); border: 1px solid var(--border-default); display: flex; align-items: center; justify-content: center; color: var(--text-tertiary); flex-shrink: 0; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 8h20"/><path d="M6 4v4"/></svg>
                </div>
                <div class="text-body text-secondary" style="line-height: 1.6; padding-top: 6px;">${b.addr}<br/><strong style="color: var(--text-primary);">${b.country}</strong></div>
              </div>
              
              <div class="grid-2 gap-4">
                <div class="d-flex gap-4 items-center p-4" style="background: var(--bg-primary); border-radius: var(--radius-lg); border: 1px solid var(--border-default); transition: all 0.3s;" onmouseover="this.style.borderColor='var(--action-primary)';this.style.boxShadow='0 4px 12px rgba(196,164,119,0.1)'" onmouseout="this.style.borderColor='var(--border-default)';this.style.boxShadow='none'">
                  <div style="color: var(--action-primary);"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
                  <a href="tel:${b.phone.replace(/[^0-9+]/g,'')}" class="text-body-sm text-primary hover-text-action transition-colors" dir="ltr" style="text-decoration:none; font-weight: 500;">${b.phone}</a>
                </div>
                
                <div class="d-flex gap-4 items-center p-4" style="background: var(--bg-primary); border-radius: var(--radius-lg); border: 1px solid var(--border-default); transition: all 0.3s;" onmouseover="this.style.borderColor='var(--action-primary)';this.style.boxShadow='0 4px 12px rgba(196,164,119,0.1)'" onmouseout="this.style.borderColor='var(--border-default)';this.style.boxShadow='none'">
                  <div style="color: var(--action-primary);"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></div>
                  <a href="mailto:${b.email}" class="text-body-sm text-primary hover-text-action transition-colors" style="text-decoration:none; font-weight: 500;">${btnEmail}</a>
                </div>
              </div>
              
              <div class="d-flex gap-3 items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <div class="text-caption text-secondary" style="font-size: 0.9rem;">${b.hours}</div>
              </div>
            </div>
            
            <!-- Actions -->
            <div class="d-flex gap-4 mt-auto">
              ${b.origStatus==='Active' ? `
                <a href="#" class="btn btn-primary" style="flex: 1.5; border-radius: var(--radius-lg); padding: 1.1rem; font-size: 1.05rem; box-shadow: 0 8px 24px rgba(196,164,119,0.25);"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg> ${btnDir}</a>
                <a href="tel:${b.phone.replace(/[^0-9+]/g,'')}" class="btn btn-secondary" style="flex: 1; border-radius: var(--radius-lg);"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></a>
              ` : `
                <button class="btn btn-secondary w-full" style="border-radius: var(--radius-lg); padding: 1.1rem; font-size: 1.05rem; opacity: 0.6; cursor: not-allowed;" disabled>${b.status}</button>
              `}
            </div>
          </div>`).join('')}
        </div>
      </div>
      
      <!-- Premium Contact Section -->
      <div class="contact-glass reveal" style="padding: var(--space-12); position: relative; overflow: hidden; margin-top: var(--space-16);">
        <!-- Glowing orb behind contact -->
        <div style="position: absolute; top: -50%; right: -20%; width: 60%; height: 200%; background: radial-gradient(circle, var(--accent-gold) 0%, transparent 60%); opacity: 0.08; filter: blur(80px); pointer-events: none;"></div>
        
        <div class="grid-2 gap-16 items-center" style="position: relative; z-index: 2;">
          <div style="padding-${isAr?'left':'right'}: var(--space-8);">
            <div class="gold-line mb-6"></div>
            <h2 class="text-display-md mb-6" style="font-weight: var(--weight-bold); text-align: ${isAr ? 'right' : 'left'};">${isAr ? 'تواصل معنا مباشرة' : 'Get in Touch Directly'}</h2>
            <p class="text-body-lg text-secondary mb-10" style="line-height: 1.8; text-align: ${isAr ? 'right' : 'left'};">
              ${isAr ? 'سواء كنت تتطلع لاستكشاف فرص استثمارية أو ترغب في الانضمام إلى منظومتنا، فريق الخبراء لدينا جاهز للرد على جميع استفساراتك وتقديم التوجيه اللازم.' : 'Whether you are looking to explore investment opportunities or join our ecosystem, our team of experts is ready to answer all your inquiries.'}
            </p>
            
            <div class="d-flex flex-col gap-6">
              <div class="d-flex gap-4 items-center p-5" style="background: var(--bg-primary); border-radius: var(--radius-xl); border: 1px solid var(--border-default); box-shadow: 0 10px 20px rgba(0,0,0,0.05); transition: transform 0.3s;" onmouseover="this.style.transform='translateX(${isAr?'-8px':'8px'})'" onmouseout="this.style.transform='translateX(0)'">
                <div style="width: 56px; height: 56px; border-radius: var(--radius-lg); background: rgba(196,164,119,0.1); display: flex; align-items: center; justify-content: center; color: var(--action-primary); flex-shrink: 0;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div style="text-align: ${isAr ? 'right' : 'left'};">
                  <div class="text-caption text-secondary mb-1" style="font-size: 0.9rem;">${isAr ? 'الدعم العالمي' : 'Global Support'}</div>
                  <div class="text-body-lg" style="font-weight: var(--weight-bold); font-size: 1.4rem; font-variant-numeric: tabular-nums;" dir="ltr">+966 920 000 000</div>
                </div>
              </div>
              <div class="d-flex gap-4 items-center p-5" style="background: var(--bg-primary); border-radius: var(--radius-xl); border: 1px solid var(--border-default); box-shadow: 0 10px 20px rgba(0,0,0,0.05); transition: transform 0.3s;" onmouseover="this.style.transform='translateX(${isAr?'-8px':'8px'})'" onmouseout="this.style.transform='translateX(0)'">
                <div style="width: 56px; height: 56px; border-radius: var(--radius-lg); background: rgba(196,164,119,0.1); display: flex; align-items: center; justify-content: center; color: var(--action-primary); flex-shrink: 0;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                </div>
                <div style="text-align: ${isAr ? 'right' : 'left'};">
                  <div class="text-caption text-secondary mb-1" style="font-size: 0.9rem;">${isAr ? 'استفسارات عامة' : 'General Inquiries'}</div>
                  <div class="text-body-lg" style="font-weight: var(--weight-bold); font-size: 1.2rem;">hello@seventechcapital.com</div>
                </div>
              </div>
            </div>
          </div>
          
          <div>
            <form class="p-6" style="background: var(--bg-primary); border-radius: var(--radius-2xl); border: 1px solid var(--border-default); box-shadow: 0 30px 60px rgba(0,0,0,0.2);" onsubmit="event.preventDefault();">
              <h3 class="text-h5 mb-5" style="font-weight: var(--weight-bold); text-align: ${isAr ? 'right' : 'left'};">${isAr ? 'أرسل رسالة' : 'Send a Message'}</h3>
              <div class="d-flex flex-col gap-4">
                <div class="form-group" style="position: relative;">
                  <label class="form-label text-caption" style="color: var(--text-primary); margin-bottom: 0.5rem; font-weight: var(--weight-medium); display: block;">${isAr ? 'الاسم بالكامل' : 'Full Name'}</label>
                  <div style="position: relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; top: 50%; transform: translateY(-50%); ${isAr ? 'right' : 'left'}: 12px; color: var(--text-tertiary); pointer-events: none;"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input type="text" class="form-input contact-input" style="width: 100%; padding: 0.8rem; padding-${isAr ? 'right' : 'left'}: 2.5rem; border-radius: var(--radius-lg); font-size: 0.85rem;" placeholder="${isAr ? 'أدخل اسمك هنا' : 'Enter your name'}" required>
                  </div>
                </div>
                
                <div class="form-group" style="position: relative;">
                  <label class="form-label text-caption" style="color: var(--text-primary); margin-bottom: 0.5rem; font-weight: var(--weight-medium); display: block;">${isAr ? 'البريد الإلكتروني' : 'Email Address'}</label>
                  <div style="position: relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; top: 50%; transform: translateY(-50%); ${isAr ? 'right' : 'left'}: 12px; color: var(--text-tertiary); pointer-events: none;"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    <input type="email" class="form-input contact-input" style="width: 100%; padding: 0.8rem; padding-${isAr ? 'right' : 'left'}: 2.5rem; border-radius: var(--radius-lg); font-size: 0.85rem;" placeholder="you@example.com" required dir="auto">
                  </div>
                </div>
                
                <div class="form-group" style="position: relative;">
                  <label class="form-label text-caption" style="color: var(--text-primary); margin-bottom: 0.5rem; font-weight: var(--weight-medium); display: block;">${isAr ? 'الرسالة' : 'Message'}</label>
                  <div style="position: relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; top: 0.9rem; ${isAr ? 'right' : 'left'}: 12px; color: var(--text-tertiary); pointer-events: none;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <textarea class="form-input contact-input" rows="3" style="width: 100%; padding: 0.8rem; padding-${isAr ? 'right' : 'left'}: 2.5rem; border-radius: var(--radius-lg); resize: vertical; font-size: 0.85rem;" placeholder="${isAr ? 'كيف يمكننا مساعدتك؟' : 'How can we help you?'}" required></textarea>
                  </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-full mt-2 d-flex items-center justify-center" style="padding: 0.9rem; font-size: 0.95rem; border-radius: var(--radius-lg); box-shadow: 0 10px 30px rgba(196,164,119,0.3); transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 15px 40px rgba(196,164,119,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 10px 30px rgba(196,164,119,0.3)'">
                  ${isAr ? 'إرسال الرسالة الآن' : 'Send Message Now'}
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-${isAr ? 'right' : 'left'}: 10px; transform: rotate(${isAr ? '180deg' : '0deg'})"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
    </div>
  </section>`;
}

// ════════════════════════════════════════
// SPEAKERS PAGE
// ════════════════════════════════════════
function speakersPage() {
  const speakers = [
    { name:'Ahmad Al-Rashid', title:'CEO', org:'SEVEN TECH CAPITAL', expertise:['Venture Building','Strategy'] },
    { name:'Sarah Al-Tamimi', title:'CEO', org:'FinFlow', expertise:['FinTech','Product'] },
    { name:'Mohammed K.', title:'CTO', org:'DataPulse', expertise:['AI','Engineering'] },
    { name:'Layla Hassan', title:'VP Growth', org:'SEVEN TECH CAPITAL', expertise:['Growth','Marketing'] },
    { name:'Omar Bashir', title:'Head of Design', org:'SEVEN TECH CAPITAL', expertise:['Design','UX'] },
    { name:'Khalid Al-Dosari', title:'Managing Partner', org:'Alpha Ventures', expertise:['Investment','Governance'] },
  ];
  return `
  <section class="section" style="padding-top:calc(var(--header-height) + var(--space-8))">
    <div class="container-content">
      <div class="section-header reveal"><div class="gold-line"></div><h1 class="text-h1">Speakers</h1><p class="text-body-lg text-secondary">Industry leaders and domain experts from our ecosystem.</p></div>
      <div class="d-flex gap-3 flex-wrap mb-8 reveal">
        <button class="chip active">All</button><button class="chip">Venture Building</button><button class="chip">Investment</button><button class="chip">Technology</button><button class="chip">Design</button><button class="chip">Growth</button>
      </div>
      <div class="grid-3 reveal">
        ${speakers.map(s => `
        <div class="card card-hover text-center" style="padding:var(--space-8)">
          <div style="width:80px;height:80px;border-radius:50%;background:var(--action-primary);margin:0 auto var(--space-4)"></div>
          <h3 class="text-h5">${s.name}</h3>
          <div class="text-body-sm text-secondary">${s.title}, ${s.org}</div>
          <div class="d-flex gap-2 justify-center flex-wrap mt-3">${s.expertise.map(e => `<span class="tag">${e}</span>`).join('')}</div>
        </div>`).join('')}
      </div>
    </div>
  </section>`;
}

/**
 * SEVEN TECH CAPITAL — Homepage Module
 * Returns the homepage HTML for the SPA router
 */

function homePage() {
  return `
    <!-- 1. HERO -->
    <section class="hero" id="hero">
      <div class="hero-bg"><div class="hero-glow-1"></div><div class="hero-glow-2"></div><div class="hero-grid-pattern"></div><div class="hero-arc"></div><div class="hero-arc-2"></div><div class="hero-dot"></div></div>
      <div class="container">
        <div class="hero-content">
          <div class="hero-overline reveal"><span class="hero-overline-dot"></span><span data-i18n="hero_overline">A Venture Studio</span></div>
          <h1 class="hero-title reveal reveal-delay-1"><span data-i18n="hero_title_1" style="display: block; margin-bottom: 20px;">We build technology</span><span data-i18n="hero_title_2">companies designed</span> <span class="accent" data-i18n="hero_title_3">to lead.</span></h1>
          <p class="hero-subtitle reveal reveal-delay-2" data-i18n="hero_subtitle">SEVEN TECH CAPITAL combines capital, strategy, product, technology, and execution to build scalable ventures.</p>
          <div class="hero-actions reveal reveal-delay-3">
            <a href="#/login" class="btn btn-primary btn-lg" data-i18n="hero_cta_primary">Apply as Entrepreneur</a>
            <a href="#/login" class="btn btn-secondary btn-lg" data-i18n="hero_cta_secondary">Become an Investor</a>
          </div>
          <a href="#how-it-works" class="hero-explore reveal reveal-delay-4"><span data-i18n="hero_explore">Explore the Studio</span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg></a>
        </div>
      </div>
    </section>

    <!-- 2. POSITIONING -->
    <section class="section" id="positioning">
      <div class="container-content">
        <div class="section-header center reveal"><div class="gold-line"></div><h2 data-i18n="how_title">How SEVEN TECH CAPITAL Works</h2><p data-i18n="how_subtitle">From concept to market-leading company, we combine strategic capital with hands-on execution.</p></div>
      </div>
    </section>

    <!-- 3. AUDIENCE CARDS -->
    <section class="section-sm" id="audience-paths">
      <div class="container-content">
        <div class="audience-cards">
          <div class="audience-card reveal">
            <div class="audience-card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></div>
            <h3 data-i18n="audience_general_title">Explore SEVEN TECH CAPITAL</h3>
            <p data-i18n="audience_general_desc">Discover how we build technology companies from the ground up. Browse our portfolio, events, and resources.</p>
            <a href="#/partners" class="audience-card-link"><span data-i18n="audience_general_link">Start Exploring</span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
          </div>
          <div class="audience-card reveal reveal-delay-1">
            <div class="audience-card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg></div>
            <h3 data-i18n="audience_investor_title">Invest in Scalable Ventures</h3>
            <p data-i18n="audience_investor_desc">Access curated investment opportunities in technology ventures built with institutional-grade operations.</p>
            <a href="#/investors" class="audience-card-link"><span data-i18n="audience_investor_link">Learn About Investing</span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
          </div>
          <div class="audience-card reveal reveal-delay-2">
            <div class="audience-card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg></div>
            <h3 data-i18n="audience_entrepreneur_title">Build Your Venture With Us</h3>
            <p data-i18n="audience_entrepreneur_desc">Bring your vision to our venture studio. We provide capital, team, technology, and go-to-market execution.</p>
            <a href="#/login" class="audience-card-link"><span data-i18n="audience_entrepreneur_link">Apply Now</span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. HOW IT WORKS -->
    <section class="section" id="how-it-works">
      <div class="container-content">
        <div class="process-steps">
          ${[['1','how_step1_title','Source & Validate','how_step1_desc','We identify market opportunities and validate ideas with rigorous research and founder partnerships.'],['2','how_step2_title','Build & Design','how_step2_desc','Our product and engineering teams build institutional-grade technology from day one.'],['3','how_step3_title','Launch & Grow','how_step3_desc','We deploy go-to-market strategy, growth operations, and market access to accelerate traction.'],['4','how_step4_title','Scale & Exit','how_step4_desc','Strategic governance, investor relations, and exit planning drive long-term value creation.']].map(([n,tk,td,dk,dd],i) => `
          <div class="process-step reveal ${i>0?'reveal-delay-'+i:''}"><div class="process-step-number">${n}</div><h4 data-i18n="${tk}">${td}</h4><p data-i18n="${dk}">${dd}</p></div>`).join('')}
        </div>
      </div>
    </section>


    <!-- FEATURED PROJECTS — Removed from public. Available only in Investor/Entrepreneur dashboards -->


    <!-- 6. PARTNERS MARQUEE -->
    <section class="section-sm" id="partners-preview">
      <div class="container-content">
        <div class="section-header center reveal"><div class="gold-line"></div><h2 data-i18n="partners_title">Our Partners</h2><p data-i18n="partners_subtitle">Strategic alliances that strengthen our venture ecosystem.</p></div>
        <div class="partners-marquee reveal"><div class="partners-track">${['partner-stc','partner-neom','partner-aramco','partner-sabic','partner-mcit','partner-hub71','partner-misk','partner-stc','partner-neom','partner-aramco','partner-sabic','partner-mcit','partner-hub71','partner-misk'].map(name => `<img src="assets/images/${name}.png" alt="${name}" class="partner-logo-img">`).join('')}</div></div>
      </div>
    </section>

    <!-- 7. INVESTOR CTA -->
    <section class="section" id="investor-cta">
      <div class="container-content">
        <div class="investor-cta-section reveal"><div class="investor-cta-content"><div class="gold-line mb-6" style="background:var(--accent-gold)"></div><h2 data-i18n="investor_cta_title">Invest in Tomorrow's Technology Leaders</h2><p data-i18n="investor_cta_desc">Join a select group of investors accessing institutional-grade venture opportunities with transparent governance and professional reporting.</p><div class="d-flex gap-4 flex-wrap"><a href="#/login" class="btn btn-primary btn-lg" data-i18n="investor_cta_btn">Become an Investor</a><a href="#/investors" class="btn btn-ghost btn-lg" style="color:rgba(255,255,255,0.7);border:1px solid rgba(255,255,255,0.2)" data-i18n="investor_cta_link">Learn More About Our Model</a></div></div></div>
      </div>
    </section>

    <!-- 8. EVENTS -->
    <section class="section" id="events-preview">
      <div class="container-content">
        <div class="d-flex justify-between items-end mb-12 flex-wrap gap-4 reveal"><div class="section-header" style="margin-bottom:0"><div class="gold-line"></div><h2 data-i18n="events_title">Upcoming Events</h2><p data-i18n="events_subtitle">Connect with founders, investors, and the venture community.</p></div><a href="#/events" class="btn btn-secondary" data-i18n="events_view_all">View All Events</a></div>
        <div class="grid-3 reveal">
          ${[['15','Jul','Riyadh, KSA','Demo Day','Venture Demo Day 2026','Watch our latest ventures present.','assets/images/event-demo-day.png'],['28','Jul','Online','Webinar','Investor Briefing: Q2','Quarterly portfolio update.','assets/images/event-investor-briefing.png'],['10','Aug','Dubai, UAE','Workshop','Product-Market Fit Workshop','Intensive validation workshop.','assets/images/event-workshop.png']].map(([day,month,loc,cat,title,desc,img]) => `
          <a href="#/events" class="event-card"><div class="event-card-image" style="background:url('${img}') center/cover;position:relative"><div class="event-card-date-badge"><div class="day">${day}</div><div class="month">${month}</div></div></div><div class="event-card-body"><div class="event-card-meta"><span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>${loc}</span><span class="badge badge-primary">${cat}</span></div><h3>${title}</h3><p>${desc}</p></div></a>`).join('')}
        </div>
      </div>
    </section>

    <!-- 9. BLOGS -->
    <section class="section" id="blogs-preview">
      <div class="container-content">
        <div class="d-flex justify-between items-end mb-12 flex-wrap gap-4 reveal"><div class="section-header" style="margin-bottom:0"><div class="gold-line"></div><h2 data-i18n="blogs_title">Latest Insights</h2><p data-i18n="blogs_subtitle">Thought leadership on venture building, investment, and technology.</p></div><a href="#/blogs" class="btn btn-secondary" data-i18n="blogs_view_all">Read All Articles</a></div>
        <a href="#/blog/venture-studios-future" class="blog-card-editorial reveal mb-8"><div class="blog-card-editorial-image" style="background:url('assets/images/blog-venture-studios.png') center/cover"></div><div><span class="blog-card-category">Venture Building</span><h3 class="blog-card-title" style="font-size:var(--text-h3)">Why Venture Studios Are the Future</h3><p class="blog-card-excerpt">The traditional startup model is broken. Venture studios provide the infrastructure founders need.</p><div class="blog-card-author"><div class="blog-card-author-avatar" style="background:url('assets/images/founder-sarah.png') center/cover"></div><div><div class="blog-card-author-name">Ahmad Al-Rashid</div><div class="blog-card-author-meta">Jun 5, 2026 · 8 min read</div></div></div></div></a>
        <div class="grid-3 reveal">
          ${[['Understanding Returns','Investment','assets/images/blog-investment-returns.png','May 28'],['Building for Scale','Technology','assets/images/blog-building-scale.png','May 20'],['MENA Market Entry','Growth','assets/images/blog-mena-market.png','May 12']].map(([title,cat,img,date]) => `
          <a href="#/blogs" class="blog-card-small"><div class="blog-card-small-image" style="background:url('${img}') center/cover"></div><div class="blog-card-small-body"><span class="blog-card-category">${cat}</span><h4 class="blog-card-title" style="font-size:var(--text-h5)">${title}</h4><div class="blog-card-author-meta mt-3">${date}, 2026 · 6 min</div></div></a>`).join('')}
        </div>
      </div>
    </section>

    <!-- 10. CONTENT -->
    <section class="section-sm" id="content-preview">
      <div class="container-content">
        <div class="d-flex justify-between items-end mb-12 flex-wrap gap-4 reveal"><div class="section-header" style="margin-bottom:0"><div class="gold-line"></div><h2 data-i18n="content_title">Featured Content</h2><p data-i18n="content_subtitle">Reports, guides, and resources.</p></div><a href="#/content" class="btn btn-secondary" data-i18n="content_view_all">Browse Content Library</a></div>
        <div class="grid-2 reveal" style="gap:var(--space-4)">
          ${[['Q1 2026 Report','chart','Comprehensive portfolio analysis.'],['Venture Playbook','file','Our methodology from ideation to scale.'],['CEO Fireside Chat','video','Building in MENA discussion.'],['Investor Guide 2026','download','Everything investors need to know.']].map(([title,icon,desc]) => `
          <div class="content-card"><div class="content-card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">${icon==='chart'?'<line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/>':icon==='video'?'<path d="m16 13 5.223 3.482a.5.5 0 0 0 .777-.416V7.87a.5.5 0 0 0-.752-.432L16 10.5"/><rect x="2" y="6" width="14" height="12" rx="2"/>':'<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>'}</svg></div><div><h4>${title}</h4><p class="text-caption text-secondary">${desc}</p></div></div>`).join('')}
        </div>
      </div>
    </section>

    <!-- 11. JOBS -->
    <section class="section" id="jobs-preview">
      <div class="container-content">
        <div class="d-flex justify-between items-end mb-12 flex-wrap gap-4 reveal"><div class="section-header" style="margin-bottom:0"><div class="gold-line"></div><h2 data-i18n="jobs_title">Join Our Team</h2><p data-i18n="jobs_subtitle">Build the next generation of technology companies.</p></div><a href="#/jobs" class="btn btn-secondary" data-i18n="jobs_view_all">View All Openings</a></div>
        <div class="d-flex flex-col gap-3 reveal">
          <a href="#/jobs" class="job-preview-item"><div class="job-preview-info"><h4 data-i18n="job_1_title">Senior Product Designer</h4><div class="job-preview-meta"><span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg><span data-i18n="job_1_loc">Riyadh, KSA</span></span><span data-i18n="job_1_type">Full-time</span><span data-i18n="job_1_dept">Design</span></div></div><span class="btn btn-secondary btn-sm" data-i18n="job_1_apply">Apply</span></a>
          <a href="#/jobs" class="job-preview-item"><div class="job-preview-info"><h4 data-i18n="job_2_title">Full-Stack Engineer</h4><div class="job-preview-meta"><span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg><span data-i18n="job_2_loc">Remote</span></span><span data-i18n="job_2_type">Full-time</span><span data-i18n="job_2_dept">Engineering</span></div></div><span class="btn btn-secondary btn-sm" data-i18n="job_2_apply">Apply</span></a>
          <a href="#/jobs" class="job-preview-item"><div class="job-preview-info"><h4 data-i18n="job_3_title">Growth Lead</h4><div class="job-preview-meta"><span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg><span data-i18n="job_3_loc">Dubai, UAE</span></span><span data-i18n="job_3_type">Full-time</span><span data-i18n="job_3_dept">Marketing</span></div></div><span class="btn btn-secondary btn-sm" data-i18n="job_3_apply">Apply</span></a>
        </div>
      </div>
    </section>

    <!-- 12. METRICS -->
    <section class="section" id="metrics" style="background:var(--bg-secondary)">
      <div class="container-content">
        <div class="section-header center reveal"><div class="gold-line"></div><h2 data-i18n="metrics_title">Impact in Numbers</h2></div>
        <div class="metrics-grid reveal">
          <div class="metric-item"><div class="metric-number" data-counter="12" data-suffix="+">0</div><div class="metric-label" data-i18n="metric_ventures">Ventures Built</div></div>
          <div class="metric-item"><div class="metric-number" data-counter="45" data-prefix="$" data-suffix="M">$0</div><div class="metric-label" data-i18n="metric_capital">Capital Deployed</div></div>
          <div class="metric-item"><div class="metric-number" data-counter="120" data-suffix="+">0</div><div class="metric-label" data-i18n="metric_team">Team Members</div></div>
          <div class="metric-item"><div class="metric-number" data-counter="8">0</div><div class="metric-label" data-i18n="metric_markets">Markets Reached</div></div>
        </div>
      </div>
    </section>

    <!-- 13. TESTIMONIALS -->
    <section class="section" id="testimonials">
      <div class="container-content">
        <div class="d-flex justify-between items-end mb-12 flex-wrap gap-4 reveal"><div class="section-header" style="margin-bottom:0"><div class="gold-line"></div><h2 data-i18n="testimonials_title">Founder Stories</h2><p data-i18n="testimonials_subtitle">What founders and investors say about working with us.</p></div><a href="#/blogs" class="btn btn-secondary" data-i18n="testimonials_view_all">View All Stories</a></div>
        <div class="grid-2 reveal">
          <div class="testimonial-card"><div class="testimonial-quote" data-i18n="testimonial_1_quote">SEVEN TECH CAPITAL didn't just invest — they became our co-founders. Their product team built our MVP in 12 weeks, and their market access opened doors we couldn't have opened alone.</div><div class="testimonial-author"><div class="testimonial-author-avatar" style="background:url('assets/images/founder-sarah.png') center/cover"></div><div><div class="testimonial-author-name" data-i18n="testimonial_1_name">Sarah Al-Tamimi</div><div class="testimonial-author-role" data-i18n="testimonial_1_role">CEO, FinFlow</div></div></div></div>
          <div class="testimonial-card"><div class="testimonial-quote" data-i18n="testimonial_2_quote">The transparency and governance we see from SEVEN TECH CAPITAL is institutional-grade. Monthly reports, clear metrics, and a dedicated account manager who understands our goals.</div><div class="testimonial-author"><div class="testimonial-author-avatar" style="background:url('assets/images/founder-khalid.png') center/cover"></div><div><div class="testimonial-author-name" data-i18n="testimonial_2_name">Khalid Al-Dosari</div><div class="testimonial-author-role" data-i18n="testimonial_2_role">Lead Investor</div></div></div></div>
        </div>
      </div>
    </section>

    <!-- 14. NEWSLETTER -->
    <section class="section-sm" id="newsletter">
      <div class="container-content">
        <div class="newsletter-section reveal"><h2 data-i18n="newsletter_title">Stay Informed</h2><p data-i18n="newsletter_subtitle">Get insights on venture building, investment opportunities, and ecosystem updates.</p><form class="newsletter-form" onsubmit="return false"><input type="email" class="form-input" data-i18n-placeholder="newsletter_placeholder" placeholder="Enter your email"><button type="submit" class="btn btn-primary" data-i18n="newsletter_btn">Subscribe</button></form></div>
      </div>
    </section>

    <!-- 15. FINAL CTA -->
    <section class="section" id="final-cta">
      <div class="container-content">
        <div class="final-cta reveal"><h2 data-i18n="final_cta_title">Ready to Build Something Great?</h2><p data-i18n="final_cta_desc">Whether you're a founder with a vision or an investor seeking opportunities, we're ready to work with you.</p><div class="final-cta-actions"><a href="#/login" class="btn btn-primary btn-lg" data-i18n="hero_cta_primary">Apply as Entrepreneur</a><a href="#/login" class="btn btn-dark btn-lg" data-i18n="hero_cta_secondary">Become an Investor</a></div></div>
      </div>
    </section>`;
}


  window.STC_PAGES = {
    homePage,
    partnersPage,
    partnerDetailPage,
    investorsPublicPage,
    blogsPage,
    blogDetailPage,
    eventsPage,
    eventDetailPage,
    jobsPage,
    jobDetailPage,
    branchesPage
  };
})();
