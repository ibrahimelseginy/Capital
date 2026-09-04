<?php $base=''; $title='الأخبار والفعاليات'; $active='news'; include 'partials/head.php'; include 'partials/nav.php'; ?>

<main id="main" data-news-events data-api="api/news-events.php">
<section class="page-hero knowledge-hero">
  <div class="hero-dots"></div>
  <div class="container">
    <div class="knowledge-hero-grid">
      <div>
        <span class="eyebrow reveal">مركز المعرفة</span>
        <h1 class="reveal mt-16">الأخبار والمقالات والفعاليات</h1>
        <p class="reveal">تحديثات الصندوق، مقالات استثمارية، وفعاليات مجانية هجينة بتسجيل وقائمة انتظار.</p>
      </div>
      <aside class="knowledge-hero-note reveal">
        <b>تحديثات موثقة</b>
        <span>محتوى مختصر يساعد المستثمر ورائد الأعمال على متابعة الصورة الكبيرة.</span>
      </aside>
    </div>
  </div>
</section>

<section class="section-sm knowledge-tabs-section">
  <div class="container">
    <div class="tabs knowledge-tabs reveal" data-tabgroup="news" role="tablist" aria-label="نوع المحتوى">
      <button class="tab active" role="tab" data-target="tab-news" onclick="switchTab(this,'news')" aria-selected="true">الأخبار والمقالات</button>
      <button class="tab" role="tab" data-target="tab-events" onclick="switchTab(this,'news')" aria-selected="false">الفعاليات</button>
    </div>
  </div>
</section>

<section class="section knowledge-news-section" id="tab-news" data-tabpanel="news" role="tabpanel" aria-label="الأخبار والمقالات">
  <div class="container">
    <div data-featured-content aria-live="polite" aria-busy="true">
      <div class="featured-article knowledge-skeleton" aria-hidden="true"></div>
    </div>
    <div class="knowledge-grid mt-24" data-knowledge-grid aria-live="polite" aria-busy="true">
      <?php for($i=0;$i<3;$i++): ?><div class="knowledge-card knowledge-card-skeleton" aria-hidden="true"></div><?php endfor; ?>
    </div>
  </div>
</section>

<section class="section hide knowledge-events-section" id="tab-events" data-tabpanel="news" role="tabpanel" aria-label="الفعاليات">
  <div class="container">
    <div class="events-list" data-events-list aria-live="polite" aria-busy="true">
      <div class="event-card knowledge-event-skeleton" aria-hidden="true"></div>
    </div>
    <div class="event-note reveal mt-24">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
      <span>تظهر روابط التسجيل والتقويم فقط عند إضافتها واعتمادها من الإدارة.</span>
    </div>
  </div>
</section>
</main>

<script src="assets/js/news-events.js?v=20260804" defer></script>
<?php include 'partials/footer.php'; ?>
