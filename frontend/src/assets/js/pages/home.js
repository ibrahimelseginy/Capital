/**
 * SEVEN TECH CAPITAL — Homepage Module
 * Returns the homepage HTML for the SPA router
 */

export function homePage() {
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
        <div class="partners-marquee reveal"><div class="partners-track">${['partner-stc','partner-neom','partner-aramco','partner-sabic','partner-mcit','partner-hub71','partner-misk','partner-stc','partner-neom','partner-aramco','partner-sabic','partner-mcit','partner-hub71','partner-misk'].map(name => `<img src="images/${name}.png" alt="${name}" class="partner-logo-img">`).join('')}</div></div>
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
          ${[['15','Jul','Riyadh, KSA','Demo Day','Venture Demo Day 2026','Watch our latest ventures present.','images/event-demo-day.png'],['28','Jul','Online','Webinar','Investor Briefing: Q2','Quarterly portfolio update.','images/event-investor-briefing.png'],['10','Aug','Dubai, UAE','Workshop','Product-Market Fit Workshop','Intensive validation workshop.','images/event-workshop.png']].map(([day,month,loc,cat,title,desc,img]) => `
          <a href="#/events" class="event-card"><div class="event-card-image" style="background:url('${img}') center/cover;position:relative"><div class="event-card-date-badge"><div class="day">${day}</div><div class="month">${month}</div></div></div><div class="event-card-body"><div class="event-card-meta"><span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>${loc}</span><span class="badge badge-primary">${cat}</span></div><h3>${title}</h3><p>${desc}</p></div></a>`).join('')}
        </div>
      </div>
    </section>

    <!-- 9. BLOGS -->
    <section class="section" id="blogs-preview">
      <div class="container-content">
        <div class="d-flex justify-between items-end mb-12 flex-wrap gap-4 reveal"><div class="section-header" style="margin-bottom:0"><div class="gold-line"></div><h2 data-i18n="blogs_title">Latest Insights</h2><p data-i18n="blogs_subtitle">Thought leadership on venture building, investment, and technology.</p></div><a href="#/blogs" class="btn btn-secondary" data-i18n="blogs_view_all">Read All Articles</a></div>
        <a href="#/blog/venture-studios-future" class="blog-card-editorial reveal mb-8"><div class="blog-card-editorial-image" style="background:url('images/blog-venture-studios.png') center/cover"></div><div><span class="blog-card-category">Venture Building</span><h3 class="blog-card-title" style="font-size:var(--text-h3)">Why Venture Studios Are the Future</h3><p class="blog-card-excerpt">The traditional startup model is broken. Venture studios provide the infrastructure founders need.</p><div class="blog-card-author"><div class="blog-card-author-avatar" style="background:url('images/founder-sarah.png') center/cover"></div><div><div class="blog-card-author-name">Ahmad Al-Rashid</div><div class="blog-card-author-meta">Jun 5, 2026 · 8 min read</div></div></div></div></a>
        <div class="grid-3 reveal">
          ${[['Understanding Returns','Investment','images/blog-investment-returns.png','May 28'],['Building for Scale','Technology','images/blog-building-scale.png','May 20'],['MENA Market Entry','Growth','images/blog-mena-market.png','May 12']].map(([title,cat,img,date]) => `
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
          <div class="testimonial-card"><div class="testimonial-quote" data-i18n="testimonial_1_quote">SEVEN TECH CAPITAL didn't just invest — they became our co-founders. Their product team built our MVP in 12 weeks, and their market access opened doors we couldn't have opened alone.</div><div class="testimonial-author"><div class="testimonial-author-avatar" style="background:url('images/founder-sarah.png') center/cover"></div><div><div class="testimonial-author-name" data-i18n="testimonial_1_name">Sarah Al-Tamimi</div><div class="testimonial-author-role" data-i18n="testimonial_1_role">CEO, FinFlow</div></div></div></div>
          <div class="testimonial-card"><div class="testimonial-quote" data-i18n="testimonial_2_quote">The transparency and governance we see from SEVEN TECH CAPITAL is institutional-grade. Monthly reports, clear metrics, and a dedicated account manager who understands our goals.</div><div class="testimonial-author"><div class="testimonial-author-avatar" style="background:url('images/founder-khalid.png') center/cover"></div><div><div class="testimonial-author-name" data-i18n="testimonial_2_name">Khalid Al-Dosari</div><div class="testimonial-author-role" data-i18n="testimonial_2_role">Lead Investor</div></div></div></div>
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
