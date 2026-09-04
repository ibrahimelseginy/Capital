<?php $base=''; $title='القطاعات'; $active='sectors'; include 'partials/head.php'; include 'partials/nav.php'; ?>

<main id="main">
<section class="page-hero sectors-page-hero">
  <div class="hero-dots"></div>
  <div class="container">
    <div class="sectors-hero-grid">
      <div>
        <span class="eyebrow reveal">القطاعات المستهدفة</span>
        <h1 class="reveal mt-16">نستثمر في ما نفهمه ونبنيه</h1>
        <p class="reveal">ثمانية قطاعات نمتلك فيها خبرة تقنية وتشغيلية عميقة، مع عرض الفرص المتاحة التي تنشرها الإدارة مباشرة.</p>
      </div>
      <aside class="sectors-hero-note reveal">
        <b data-sector-map-count>8 قطاعات</b>
        <span>فرص تقنية قابلة للبناء والتوسع في MENA والخليج.</span>
      </aside>
    </div>
  </div>
</section>

<section class="section-sm sectors-page-section">
  <div class="container">
    <div class="sectors-page-head reveal" data-sector-map-intro>
      <span class="eyebrow" data-sector-map-eyebrow>جارٍ التحميل</span>
      <h2 class="section-title mt-16" data-sector-map-title>خريطة الفرص</h2>
      <p class="section-lead" data-sector-map-description>يتم تحميل القطاعات من لوحة الإدارة.</p>
    </div>

    <div class="sectors-page-grid mt-40" data-sector-map data-api="api/sectors.php" aria-live="polite" aria-busy="true">
      <div class="sector-map-skeleton"></div><div class="sector-map-skeleton"></div><div class="sector-map-skeleton"></div><div class="sector-map-skeleton"></div>
    </div>

    <section class="public-opportunities reveal mt-40" aria-labelledby="public-opportunities-title">
      <div class="public-opportunities-heading">
        <div>
          <span class="eyebrow">متصلة بلوحة الإدارة</span>
          <h2 id="public-opportunities-title">الفرص الاستثمارية المتاحة</h2>
          <p>تُحدّث القائمة تلقائيًا من الفرص المنشورة في لوحة الإدارة.</p>
        </div>
        <div class="public-opportunities-meta"><b data-public-opportunities-count>—</b><span data-public-opportunities-updated>جارٍ التحميل</span></div>
      </div>
      <div class="public-opportunities-grid" data-public-opportunities data-api="api/opportunities.php" aria-live="polite" aria-busy="true">
        <div class="public-opportunity-skeleton"></div><div class="public-opportunity-skeleton"></div>
      </div>
    </section>

    <div class="protected-opportunities reveal mt-40">
      <div class="protected-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-5"/></svg>
      </div>
      <div>
        <b>تفاصيل الفرص محمية</b>
        <p>تظهر البيانات العامة هنا، أما المستندات والتفاصيل الحساسة فتتاح للمستثمرين المعتمدين.</p>
      </div>
      <div class="protected-actions">
        <a href="login.php?tab=register" class="btn btn-primary btn-sm">سجّل كمستثمر</a>
      </div>
    </div>
  </div>
</section>
</main>

<script src="assets/js/sectors-map.js?v=<?= (string) (@filemtime(__DIR__ . '/assets/js/sectors-map.js') ?: 1) ?>" defer></script>
<script src="assets/js/sectors-opportunities.js?v=<?= (string) (@filemtime(__DIR__ . '/assets/js/sectors-opportunities.js') ?: 1) ?>" defer></script>
<?php include 'partials/footer.php'; ?>
