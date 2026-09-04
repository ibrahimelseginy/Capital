<?php $base=''; $title='المستثمرون'; $active='investors'; include 'partials/head.php'; include 'partials/nav.php'; ?>
<main id="main" data-investors-page data-api="api/investors.php" aria-busy="true">

<section class="page-hero investor-hero" data-investors-section="hero"><div class="hero-dots"></div><div class="container"><div class="investor-hero-grid" data-investors-hero></div></div></section>

<section class="section-sm investor-types-section" data-investors-section="investor_type"><div class="container"><div class="investor-type-grid" data-investor-types></div></div></section>

<section class="section investor-benefits" data-investors-section="benefit"><div class="container"><div class="investor-section-head center" data-benefits-head></div><div class="investor-benefit-grid mt-40" data-investor-benefits></div></div></section>

<section class="section" id="journey" data-investors-section="journey"><div class="container"><div class="investor-journey-grid"><div class="investor-journey-copy" data-journey-head></div><div class="journey" data-investor-journey><div class="journey-line"></div></div></div></div></section>

<section class="section investor-faq" data-investors-section="faq"><div class="container investor-faq-container"><div class="center" data-faq-head></div><div class="mt-40" data-investor-faq></div></div></section>

<section class="section investor-cta" data-investors-section="cta"><div class="container"><div class="cta-band" data-investors-cta></div></div></section>
</main>
<script src="assets/js/api-config.js?v=1"></script>
<script src="assets/js/investors-page.js?v=20260831-sections" defer></script>
<?php include 'partials/footer.php'; ?>
