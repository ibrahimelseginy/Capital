<?php $base = isset($base) ? $base : ''; $active = isset($active) ? $active : ''; ?>
<a href="#main" class="skip-link">تخطّي إلى المحتوى الرئيسي</a>
<div class="site-topbar" aria-label="معلومات التواصل والإعدادات">
  <div class="container site-topbar-inner">
    <div class="topbar-contact">
      <a href="mailto:hello@seventech.capital">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
        hello@seventech.capital
      </a>
      <a href="tel:+966539555889">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.32 1.77.59 2.61a2 2 0 0 1-.45 2.11L8 9.7a16 16 0 0 0 6.3 6.3l1.26-1.25a2 2 0 0 1 2.11-.45c.84.27 1.71.47 2.61.59A2 2 0 0 1 22 16.92z"/></svg>
        <span class="ltr-number">+966539555889</span>
      </a>
    </div>

    <div class="topbar-actions">
      <div class="topbar-social" aria-label="روابط التواصل الاجتماعي">
        <a href="#" onclick="demoAction(event,'رابط LinkedIn يُفعّل في النسخة الحية.')" aria-label="LinkedIn">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.94 8.98H3.46v11.09h3.48V8.98ZM5.2 3.48a2.02 2.02 0 1 0 0 4.04 2.02 2.02 0 0 0 0-4.04Zm14.88 10.23c0-3.04-1.62-4.46-3.78-4.46a3.27 3.27 0 0 0-2.95 1.62h-.05V8.98H9.97v11.09h3.48v-5.49c0-1.45.27-2.85 2.07-2.85 1.77 0 1.79 1.66 1.79 2.94v5.4h3.48v-6.36h-.71Z"/></svg>
        </a>
        <a href="#" onclick="demoAction(event,'رابط X يُفعّل في النسخة الحية.')" aria-label="X">
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.82 10.77 20.85 2.6h-1.67l-6.1 7.1-4.88-7.1H2.58l7.37 10.73-7.37 8.57h1.67l6.44-7.49 5.14 7.49h5.62l-7.63-11.13Zm-2.28 2.65-.75-1.07L4.86 3.86h2.54l4.8 6.86.75 1.07 6.23 8.92h-2.54l-5.1-7.29Z"/></svg>
        </a>
        <a href="#" onclick="demoAction(event,'رابط Instagram يُفعّل في النسخة الحية.')" aria-label="Instagram">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="3.4"/><circle cx="17.2" cy="6.8" r="1"/></svg>
        </a>
      </div>
      <div class="dropdown">
        <button class="topbar-tool" data-dropdown title="اللغة" aria-label="اختيار اللغة" aria-haspopup="true" aria-expanded="false" aria-controls="langMenu">
          <span data-lang-label>AR</span>
        </button>
        <div class="dropdown-menu" id="langMenu" role="menu">
          <button data-lang="ar" role="menuitemradio" aria-checked="true"  onclick="setLang('ar')"><span class="lang-code">AR</span> العربية</button>
          <button data-lang="en" role="menuitemradio" aria-checked="false" onclick="setLang('en')"><span class="lang-code">EN</span> English</button>
          <button data-lang="fr" role="menuitemradio" aria-checked="false" onclick="setLang('fr')"><span class="lang-code">FR</span> Français</button>
        </div>
      </div>
      <button class="theme-switch" onclick="toggleTheme()" title="الوضع الفاتح/الداكن" aria-label="تبديل الوضع الفاتح والداكن" aria-pressed="false">
        <span class="theme-switch-track" aria-hidden="true"><span class="theme-switch-thumb"><span data-theme-icon></span></span></span>
      </button>
    </div>
  </div>
</div>
<header class="nav">
  <div class="container nav-inner">
    <a href="<?= $base ?>index.php" class="brand" aria-label="Seven Tech Capital — الرئيسية">
      <?php include __DIR__ . '/logo.php'; ?>
    </a>

    <nav class="nav-links">
      <a href="<?= $base ?>index.php"            class="<?= $active==='home'?'active':'' ?>"<?= $active==='home'?' aria-current="page"':'' ?>>الرئيسية</a>
      <a href="<?= $base ?>about.php"            class="<?= $active==='about'?'active':'' ?>"<?= $active==='about'?' aria-current="page"':'' ?>>من نحن</a>
      <a href="<?= $base ?>investors.php"        class="<?= $active==='investors'?'active':'' ?>"<?= $active==='investors'?' aria-current="page"':'' ?>>المستثمرون</a>
      <a href="<?= $base ?>entrepreneurs.php"    class="<?= $active==='entrepreneurs'?'active':'' ?>"<?= $active==='entrepreneurs'?' aria-current="page"':'' ?>>رواد الأعمال</a>
      <a href="<?= $base ?>sectors.php"          class="<?= $active==='sectors'?'active':'' ?>"<?= $active==='sectors'?' aria-current="page"':'' ?>>القطاعات</a>
      <a href="<?= $base ?>success-stories.php"  class="<?= $active==='stories'?'active':'' ?>"<?= $active==='stories'?' aria-current="page"':'' ?>>قصص النجاح</a>
      <a href="<?= $base ?>news-events.php"      class="<?= $active==='news'?'active':'' ?>"<?= $active==='news'?' aria-current="page"':'' ?>>الأخبار</a>
      <a href="<?= $base ?>seven-tech.php"       class="<?= $active==='seventech'?'active':'' ?>"<?= $active==='seventech'?' aria-current="page"':'' ?>>Seven Tech</a>
      <a href="<?= $base ?>contact.php"          class="<?= $active==='contact'?'active':'' ?>"<?= $active==='contact'?' aria-current="page"':'' ?>>تواصل</a>
    </nav>

    <div class="nav-actions">
      <!-- Login -->
      <a href="<?= $base ?>login.php" class="btn btn-ghost btn-sm hide-mobile">دخول</a>
      <a href="<?= $base ?>login.php?tab=register" class="btn btn-primary btn-sm hide-mobile">سجّل كمستثمر</a>
      <!-- Mobile toggle -->
      <button class="icon-btn menu-toggle" onclick="toggleMobileMenu()" aria-label="القائمة" aria-expanded="false" aria-controls="mobileMenu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
    </div>
  </div>
</header>

<!-- Mobile menu -->
<div class="scrim" onclick="closeOverlays()"></div>
<div class="mobile-menu" id="mobileMenu" role="dialog" aria-modal="true" aria-label="قائمة التنقّل">
  <div class="row" style="justify-content:space-between;margin-bottom:20px;">
    <span class="brand" style="font-size:16px;">Seven Tech Capital</span>
    <button class="icon-btn" onclick="toggleMobileMenu()" aria-label="إغلاق القائمة"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
  </div>
  <div class="mobile-tools" aria-label="إعدادات سريعة">
    <button class="mobile-tool" type="button" onclick="setLang('ar')" data-lang="ar" aria-label="العربية">AR</button>
    <button class="mobile-tool" type="button" onclick="setLang('en')" data-lang="en" aria-label="English">EN</button>
    <button class="mobile-tool" type="button" onclick="setLang('fr')" data-lang="fr" aria-label="Français">FR</button>
    <button class="mobile-tool" type="button" onclick="toggleTheme()" aria-label="تبديل الوضع الفاتح والداكن" aria-pressed="false"><span data-theme-icon></span></button>
  </div>
  <nav aria-label="القائمة الرئيسية" style="display:contents;">
    <a href="<?= $base ?>index.php">الرئيسية</a>
    <a href="<?= $base ?>about.php">من نحن</a>
    <a href="<?= $base ?>investors.php">المستثمرون</a>
    <a href="<?= $base ?>entrepreneurs.php">رواد الأعمال</a>
    <a href="<?= $base ?>sectors.php">القطاعات</a>
    <a href="<?= $base ?>success-stories.php">قصص النجاح</a>
    <a href="<?= $base ?>news-events.php">الأخبار والفعاليات</a>
    <a href="<?= $base ?>seven-tech.php">Seven Tech</a>
    <a href="<?= $base ?>contact.php">تواصل</a>
    <div class="row gap-12 mt-24">
      <a href="<?= $base ?>login.php" class="btn btn-ghost btn-block">دخول</a>
      <a href="<?= $base ?>login.php?tab=register" class="btn btn-primary btn-block">سجّل</a>
    </div>
  </nav>
</div>
