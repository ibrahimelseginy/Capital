<?php $base=''; $title='قصص النجاح'; $active='stories'; include 'partials/head.php'; include 'partials/nav.php'; ?>

<main id="main">
<section class="page-hero page-hero-simple stories-page-hero">
  <div class="hero-dots"></div>
  <div class="container">
    <span class="eyebrow reveal">دراسات حالة</span>
    <h1 class="reveal mt-16">نتائج موثقة، دون أسماء العملاء</h1>
    <p class="reveal">مشروعات سابقة بناها وشغّلها الفريق. كل دراسة تعرض القطاع، المشكلة، الدور، المدة، الحل، والنتائج — بعد التحقق والمراجعة.</p>
  </div>
</section>

<section class="section-sm story-filters-section">
  <div class="container">
    <div class="row gap-8 flex-wrap reveal visible" role="radiogroup" aria-label="تصفية حسب القطاع" data-story-filters aria-busy="true">
      <span class="story-filter-loading">جارٍ تحميل التصنيفات...</span>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="stories-grid stories-page-grid" data-success-stories data-api="api/success-stories.php" aria-live="polite" aria-busy="true">
      <?php for($i=0;$i<3;$i++): ?><div class="card story-card story-card-skeleton" aria-hidden="true"><span></span><span></span><span></span><span></span></div><?php endfor; ?>
    </div>
    <p class="hint center mt-40">تُعرض فقط قصص النجاح المنشورة والمعتمدة من الإدارة، مع إخفاء بيانات العملاء الحساسة.</p>
  </div>
</section>
</main>

<script src="assets/js/success-stories.js?v=20260804" defer></script>
<?php include 'partials/footer.php'; ?>
