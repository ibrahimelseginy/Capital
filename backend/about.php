<?php $base=''; $title='من نحن'; $active='about'; include 'partials/head.php'; include 'partials/nav.php'; ?>
<main id="main" data-about-page aria-busy="true">

<section class="page-hero about-hero" data-about-section="hero">
  <div class="hero-dots"></div>
  <div class="container"><div class="about-hero-grid" data-about-hero></div></div>
</section>

<section class="section-sm about-brands" data-about-section="brand">
  <div class="container"><div class="about-brand-grid" data-about-brands></div></div>
</section>

<section class="section about-vmm" data-about-section="vmm">
  <div class="container"><div class="about-vmm-grid" data-about-vmm></div></div>
</section>

<section class="stat-band section-sm" aria-label="أرقامنا" data-about-section="stat">
  <div class="container"><div class="stat-row" data-about-stats></div></div>
</section>

<section class="section" data-about-section="team">
  <div class="container">
    <div class="about-section-head center" data-about-team-head></div>
    <div class="about-team-grid mt-40" data-about-team></div>
  </div>
</section>

<section class="section about-geo" data-about-section="geo">
  <div class="container">
    <div class="about-section-head center" data-about-geo-head></div>
    <div class="about-geo-grid mt-40" data-about-geo></div>
  </div>
</section>

<section class="section about-cta" data-about-section="cta">
  <div class="container"><div class="cta-band" data-about-cta></div></div>
</section>

</main>
<script src="assets/js/api-config.js?v=1"></script>
<script src="assets/js/about.js?v=2" defer></script>
<?php include 'partials/footer.php'; ?>
