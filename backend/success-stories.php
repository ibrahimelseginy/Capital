<?php $base=''; $title='قصص النجاح'; $active='stories'; include 'partials/head.php'; include 'partials/nav.php'; ?>

<main id="main" data-success-stories-page aria-busy="true">
<section class="page-hero page-hero-simple stories-page-hero" data-stories-section="hero">
  <div class="hero-dots"></div>
  <div class="container">
    <span class="eyebrow reveal" data-stories-hero-eyebrow>دراسات حالة</span>
    <h1 class="reveal mt-16" data-stories-hero-title>نتائج موثقة، دون أسماء العملاء</h1>
    <p class="reveal" data-stories-hero-description>مشروعات سابقة بناها وشغّلها الفريق. كل دراسة تعرض القطاع، المشكلة، الدور، المدة، الحل، والنتائج — بعد التحقق والمراجعة.</p>
  </div>
</section>

<section class="section story-filters-section" data-stories-section="cases">
  <div class="container">
    <div class="row gap-8 flex-wrap reveal visible" role="radiogroup" aria-label="تصفية حسب القطاع" data-story-filters aria-busy="true">
      <span class="story-filter-loading">جارٍ تحميل التصنيفات...</span>
    </div>
    <div class="stories-grid stories-page-grid mt-40" data-success-stories data-api="api/success-stories.php" aria-live="polite" aria-busy="true">
      <?php for($i=0;$i<3;$i++): ?><div class="card story-card story-card-skeleton" aria-hidden="true"><span></span><span></span><span></span><span></span></div><?php endfor; ?>
    </div>
  </div>
</section>
</main>

<script src="assets/js/success-stories.js?v=20260901-sections" defer></script>
<?php include 'partials/footer.php'; ?>
