<?php $base=''; $title='Seven Tech'; $active='seventech'; include 'partials/head.php'; include 'partials/nav.php'; ?>

<main id="main">
<section class="page-hero seven-tech-hero">
  <div class="hero-dots"></div>
  <div class="container">
    <div class="seven-tech-hero-grid">
      <div>
        <div class="seven-tech-brand reveal"><?php include 'partials/logo.php'; ?><span>Seven Tech<small>الذراع التقني</small></span></div>
        <h1 class="reveal mt-16">الذراع التقني الذي يبني ويُشغّل</h1>
        <p class="reveal">شركة تقنية بخبرة 15 عامًا وأكثر من 500 عميل، مسؤولة عن بناء المنتجات والأنظمة ودعم التشغيل، وهي المحرّك خلف منهجية تقليل المخاطر في Seven Tech Capital.</p>
      </div>
      <aside class="seven-tech-hero-card reveal">
        <span>الذراع التقني</span>
        <b>نبني قبل رأس المال</b>
        <small>منتج، أنظمة، تكاملات، وتشغيل مستمر قبل وبعد الاستثمار.</small>
      </aside>
    </div>
  </div>
</section>

<!-- Services -->
<section class="section-sm seven-tech-services">
  <div class="container">
    <div class="seven-tech-section-head reveal">
      <span class="eyebrow">الخدمات</span>
      <h2 class="section-title mt-16">ما نبنيه</h2>
      <p class="section-lead">قدرات تقنية وتشغيلية تغطي دورة المنتج من أول نسخة قابلة للاختبار حتى البنية السحابية والدعم المستمر.</p>
    </div>
    <div class="seven-tech-service-grid mt-40">
      <?php $srv=[
        ['بناء المنتجات','MVP، تطبيقات ويب وموبايل، ومنتجات قابلة للتوسع.','<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>'],
        ['الأنظمة والتكاملات','بنى خلفية، APIs، تكاملات، وأتمتة العمليات.','<path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>'],
        ['الذكاء الاصطناعي','نماذج تنبؤ وتحليلات ومساعدون أذكياء.','<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/>'],
        ['التحول الرقمي','رقمنة العمليات ولوحات القرار.','<path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 5-6"/>'],
        ['دعم التشغيل','مراقبة، صيانة، وتطوير مستمر.','<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83"/>'],
        ['البنية السحابية','AWS، أمان، نسخ احتياطي، وقابلية توسع.','<path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/>'],
      ];
      foreach($srv as $i=>$s): ?>
      <article class="seven-tech-service-card reveal">
        <div class="service-card-top">
          <div class="service-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $s[2] ?></svg></div>
          <span><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></span>
        </div>
        <h3><?= $s[0] ?></h3>
        <p><?= $s[1] ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Role -->
<section class="section seven-tech-role">
  <div class="container">
    <div class="seven-tech-role-grid">
      <div class="reveal">
        <span class="eyebrow">الدور في المنظومة</span>
        <h2 class="section-title mt-16">كيف يقلّل Seven Tech المخاطر</h2>
        <p class="section-lead">قبل تفعيل أي تمويل، يبني الذراع التقني المنتج ويُجهّز المشروع للتشغيل — فتصل الفرص للمستثمرين أكثر جاهزية.</p>
        <div class="risk-list mt-24">
          <?php foreach(['نبني المنتج ونختبره في السوق قبل الجولة','نُجهّز البنية التقنية والتشغيلية للنمو','ندعم التنفيذ بعد الاستثمار لضمان الاستمرارية'] as $li): ?>
          <div class="risk-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg><span><?= $li ?></span></div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="seven-tech-stats reveal">
        <div><b>15</b><span>عامًا خبرة</span></div>
        <div><b>500+</b><span>عميل</span></div>
        <div><b>10+</b><span>مشروعات</span></div>
        <div><b class="money-value"><span class="currency">$</span><span>50</span><span class="suffix">M</span></b><span>قيمة مشروعات</span></div>
      </div>
    </div>
    <div class="seven-tech-note reveal mt-40">صفحة تعريفية بالذراع التقني دون نموذج طلب خدمات في هذا الإصدار.</div>
  </div>
</section>
</main>

<?php include 'partials/footer.php'; ?>
