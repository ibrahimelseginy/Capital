<?php $base=''; $title='الرئيسية'; $active='home'; include 'partials/head.php'; include 'partials/nav.php'; ?>
<main id="main">

<!-- ===================== HERO ===================== -->
<section class="hero">
  <div class="hero-grid-bg"></div>
  <div class="container hero-inner">
    <div class="hero-copy reveal">
      <div class="hero-status">
        <span class="badge badge-orange"><span class="dot"></span> صندوق قيد التأسيس</span>
        <span class="hero-region">نطاق إقليمي للنمو</span>
      </div>
      <h1>نَبني المشروع قبل أن نُفعّل <span class="accent">الاستثمار.</span></h1>
      <p class="hero-sub">
        Seven Tech Capital صندوق استثماري مدعوم بذراع تقني بخبرة تمتد إلى 20 عامًا في تأسيس وتشغيل الشركات التقنية.
        نختبر الفكرة، نبني المنتج، ونُجهّز المشروع للتشغيل قبل تفعيل رأس المال — لتقليل مخاطر التنفيذ وصناعة فرص أكثر جاهزية للنمو.
      </p>
      <div class="hero-proof" aria-label="مزايا التجربة الاستثمارية">
        <span>بوابات مراجعة قبل التمويل</span>
        <span>ذراع تقني للتنفيذ</span>
        <span>متابعة شفافة للمحفظة</span>
      </div>
      <div class="hero-cta">
        <a href="login.php?tab=register" class="btn btn-primary btn-lg">
          سجّل كمستثمر
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7" style="transform:scaleX(-1);transform-origin:center"/></svg>
        </a>
        <a href="entrepreneurs.php" class="btn btn-ghost">قدّم مشروعك الآن</a>
      </div>
    </div>

    <!-- SIGNATURE: Capital Gate Ledger — capital stays locked until the project is operate-ready -->
    <div class="hero-visual reveal">
      <div class="gate-ledger" role="img" aria-label="سجلّ بوابات رأس المال: يبقى رأس المال مُقفَلًا عبر بوابات البناء ويُفعَّل عند بوابة التشغيل.">
        <div class="gl-head">
          <div>
            <span class="gl-tag">CAPITAL · STATUS</span>
            <h2>سجل جاهزية رأس المال</h2>
          </div>
          <span class="gl-lock"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>مُقفَل</span>
        </div>
        <p class="gl-summary">لا يتم تحرير التمويل إلا بعد مرور المشروع ببوابات التحقق والبناء والتشغيل.</p>

        <ol class="gl-gates">
          <li class="gl-gate done">
            <span class="gl-idx">01</span>
            <div><b>الفكرة والتحقق</b><span class="d">بحث المشكلة والسوق واختبار الفرضيات</span></div>
            <span class="gl-state">مُجتاز</span>
          </li>
          <li class="gl-gate done">
            <span class="gl-idx">02</span>
            <div><b>الدراسة والتأسيس</b><span class="d">النموذج المالي والقانوني وخطة العمل</span></div>
            <span class="gl-state">مُجتاز</span>
          </li>
          <li class="gl-gate active">
            <span class="gl-idx">03</span>
            <div><b>البناء التقني</b><span class="d">MVP · ويب · موبايل · ذكاء اصطناعي وتكاملات</span></div>
            <span class="gl-state">جارٍ</span>
          </li>
          <li class="gl-gate">
            <span class="gl-idx">04</span>
            <div><b>التمويل والتوظيف</b><span class="d">هيكلة الجولة وبناء الفريق الأساسي</span></div>
            <span class="gl-state gl-state-waiting">بانتظار</span>
          </li>
          <li class="gl-gate unlock">
            <span class="gl-idx">05</span>
            <div><b>التشغيل — يُفعَّل رأس المال</b><span class="d">عند الجاهزية للتشغيل يُفتح القفل ويُحرَّر التمويل</span></div>
            <span class="gl-state">الإفراج</span>
          </li>
        </ol>

        <div class="gl-meter">
          <span class="gl-mlabel">تفعيل رأس المال</span>
          <span class="gl-mval">قيد الإعداد</span>
          <div class="gl-track"><i></i></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== STAT BAND ===================== -->
<section class="stat-band section-sm" aria-label="أرقام الفريق والخبرة">
  <div class="container">
    <div class="stat-row">
      <div class="stat reveal">
        <span class="stat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg></span>
        <b class="mono" data-count="20" data-suffix="+">0</b>
        <span class="stat-label">عامًا خبرة تراكمية للفريق</span>
      </div>
      <div class="stat reveal">
        <span class="stat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/><path d="M9 7h.5M9 11h.5M9 15h.5M14 7h.5M14 11h.5M14 15h.5"/></svg></span>
        <b class="mono" data-count="15">0</b>
        <span class="stat-label">عامًا عمر شركة Seven Tech</span>
      </div>
      <div class="stat reveal">
        <span class="stat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg></span>
        <b class="mono" data-count="50" data-prefix="$" data-suffix="M">0</b>
        <span class="stat-label">قيمة مشروعات شارك بها الفريق</span>
      </div>
      <div class="stat reveal">
        <span class="stat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
        <b class="mono" data-count="500" data-suffix="+">0</b>
        <span class="stat-label">عميل في مصر والدول العربية</span>
      </div>
    </div>
  </div>
</section>

<!-- ===================== METHODOLOGY / VALUE ===================== -->
<section class="section methodology-section">
  <div class="container">
    <div class="method-head reveal">
      <span class="eyebrow">لماذا Seven Tech Capital</span>
      <h2 class="section-title mt-16">استثمار يمر ببوابات مراجعة وجاهزية قبل تحرير التمويل</h2>
      <p class="section-lead">نحن لا نكتفي بتمويل الفكرة، بل نبنيها ونُجهّزها ونُشغّلها — بخبرة تقنية وتشغيلية عميقة ومجلس استشاري قوي.</p>
    </div>

    <div class="method-grid mt-40">
      <?php
      $vals = [
        ['آمن ومدروس','إظهار أن الاستثمار يمر ببوابات مراجعة وجاهزية وتشغيل قبل تحرير التمويل.','<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>','primary'],
        ['تقني ومستقبلي','تجربة رقمية حديثة، حركة هادئة، وبيانات واضحة — ليست مجرد مؤثرات بصرية.','<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h6v6H9z"/>',''],
        ['جريء وواثق','رسائل مباشرة ومساحات واسعة وتباين قوي، مع البرتقالي كإشارة قرار.','<path d="M13 2L3 14h9l-1 8 10-12h-9z"/>',''],
        ['مؤسسي وشفاف','عرض المراحل والحالات والتقارير والمستندات وسجل الإجراءات بوضوح.','<path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 5-6"/>',''],
      ];
      foreach($vals as $i=>$v): ?>
      <div class="method-card <?= $v[3]==='primary'?'featured':'' ?> reveal">
        <div class="method-card-top">
          <div class="method-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $v[2] ?></svg></div>
          <span class="method-num">0<?= $i+1 ?></span>
        </div>
        <h3><?= $v[0] ?></h3>
        <p><?= $v[1] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===================== SECTORS ===================== -->
<section class="section sectors-section">
  <div class="container">
    <div class="sectors-head reveal">
      <div>
        <span class="eyebrow">القطاعات المستهدفة</span>
        <h2 class="section-title mt-16">نستثمر في القطاعات التي نفهمها ونبنيها</h2>
        <p class="section-lead">نركّز على قطاعات قابلة للبناء والقياس، حيث يمكن للذراع التقني تحويل الفكرة إلى منتج وتشغيل فعلي.</p>
      </div>
      <a href="sectors.php" class="btn btn-primary">استعرض كل القطاعات</a>
    </div>

    <div class="sectors-grid mt-40">
      <?php
      $sectors = [
        ['البرمجيات و SaaS','منصات ومنتجات برمجية قابلة للتوسع.','<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>'],
        ['التقنية المالية','حلول مالية وأسواق مال ومدفوعات.','<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/>'],
        ['الذكاء الاصطناعي','التنبؤ والتحليلات والأتمتة.','<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>'],
        ['الصحة الرقمية','منصات رعاية وخدمات صحية.','<path d="M12 21s-8-5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 6-8 11-8 11z"/><path d="M8 12h2l1-2 2 4 1-2h2" stroke-width="1.5"/>'],
        ['التعليم التقني','منصات تعلم ومهارات رقمية.','<path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/>'],
        ['إنترنت الأشياء','أجهزة وشبكات ذكية متصلة.','<circle cx="12" cy="12" r="2"/><path d="M4.9 4.9a10 10 0 0 0 0 14.2M19.1 4.9a10 10 0 0 1 0 14.2M7.8 7.8a6 6 0 0 0 0 8.4M16.2 7.8a6 6 0 0 1 0 8.4"/>'],
        ['التوصيل واللوجستيات','سلاسل إمداد وتوصيل ذكية.','<rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7z"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/>'],
        ['التحول الرقمي','إدارة وتشغيل وأتمتة العمليات.','<path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/>'],
      ];
      foreach($sectors as $i=>$s): ?>
      <a href="sectors.php" class="sector-tile reveal" aria-label="أمثلة فرص قطاع <?= $s[0] ?>">
        <div class="sector-tile-top">
          <div class="sector-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $s[2] ?></svg></div>
          <span class="sector-rank">0<?= $i+1 ?></span>
        </div>
        <h3><?= $s[0] ?></h3>
        <p><?= $s[1] ?></p>
        <span class="sector-more">أمثلة الفرص <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===================== TWO PATHS ===================== -->
<section class="section paths-section">
  <div class="container">
    <div class="paths-head center reveal">
      <span class="eyebrow">مسارات المنصة</span>
      <h2 class="section-title mt-16">مساران، منصة واحدة، سجل تدقيق كامل</h2>
      <p class="section-lead">اختر المسار المناسب لك. كل رحلة لها متطلبات واضحة، حالات متابعة، وخطوة تالية ظاهرة داخل المنصة.</p>
    </div>
    <div class="paths-grid mt-40">
      <!-- Investor -->
      <div class="path-card investor reveal">
        <div class="path-top">
          <span class="path-badge">للمستثمرين</span>
          <div class="path-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19V5"/><path d="M4 19h16"/><path d="m7 15 3.5-3.5 3 3L20 8"/><path d="M16 8h4v4"/></svg></div>
        </div>
        <h3>استثمر في فرص أكثر جاهزية</h3>
        <p>تأهيل يدوي، اتفاقيات سرية، فرص محمية، ومتابعة كاملة للمحفظة عبر لوحة تحكم مخصصة.</p>
        <ul class="path-list">
          <?php foreach(['تأهيل KYC/AML يدوي محكم خلال 3–5 أيام','فرص لا تُعرض إلا بعد الاعتماد وتوقيع NDA','خيارات: الصندوق · فرصة محددة · مسار هجين','متابعة المحفظة والتقارير والاجتماعات'] as $li): ?>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg><span><?= $li ?></span></li>
          <?php endforeach; ?>
        </ul>
        <a href="investors.php" class="btn btn-primary btn-block">ابدأ كمستثمر</a>
      </div>
      <!-- Entrepreneur -->
      <div class="path-card entrepreneur reveal">
        <div class="path-top">
          <span class="path-badge">لرواد الأعمال</span>
          <div class="path-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"/><path d="m14.5 5.5 4 4"/></svg></div>
        </div>
        <h3>من الفكرة إلى التشغيل والتوسع</h3>
        <p>اختر نوع الدعم المناسب، قدّم مشروعك، وتابع مراحل التقييم والاجتماعات والمهام من لوحة واحدة.</p>
        <ul class="path-list">
          <?php foreach(['نموذج تقديم متعدد الخطوات مع حفظ تلقائي','رفع Pitch Deck ودراسة جدوى وملفات','خط مراجعة شفاف: جديد ← تقييم ← قرار ← تعاقد','دعم يشمل البناء والتمويل والتشغيل'] as $li): ?>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg><span><?= $li ?></span></li>
          <?php endforeach; ?>
        </ul>
        <a href="entrepreneurs.php" class="btn btn-dark btn-block">قدّم مشروعك</a>
      </div>
    </div>
  </div>
</section>

<!-- ===================== SUCCESS STORIES ===================== -->
<section class="section stories-section">
  <div class="container">
    <div class="stories-head reveal">
      <div>
        <span class="eyebrow">قصص النجاح</span>
        <h2 class="section-title mt-16">دراسات حالة موثقة — دون أسماء العملاء</h2>
        <p class="section-lead">نستخدم قصصًا مجهّلة لعرض أثر البناء التقني والتشغيلي دون كشف بيانات العملاء أو الفرص الحساسة.</p>
      </div>
      <a href="success-stories.php" class="btn btn-primary">استعرض كل القصص</a>
    </div>
    <div class="stories-showcase mt-40">
      <?php
      $stories = [
        ['التقنية المالية','منصة مدفوعات B2B','خفض زمن التسوية وتحسين تجربة التاجر عبر بنية حديثة.',[['-64%','زمن العملية'],['3.5x','نمو المعاملات'],['9 أسابيع','للإطلاق']]],
        ['الصحة الرقمية','منصة حجوزات ورعاية','توحيد تجربة المريض ورقمنة العمليات التشغيلية.',[['+180%','مستخدمون'],['-38%','التكلفة'],['12 أسبوع','للإطلاق']]],
        ['اللوجستيات','نظام توصيل ذكي','تحسين المسارات والتتبع اللحظي وأتمتة الأسطول.',[['+42%','كفاءة'],['-27%','زمن التسليم'],['8 أسابيع','للإطلاق']]],
      ];
      foreach($stories as $st):
        $jsonStats = htmlspecialchars(json_encode($st[3]), ENT_QUOTES, 'UTF-8');
      ?>
      <button class="case-card reveal" type="button" onclick="showStoryModal('<?= $st[1] ?>', '<?= $st[0] ?>', '<?= $st[2] ?>', '<?= $jsonStats ?>')">
        <span class="case-glow" aria-hidden="true"></span>
        <div class="case-top">
          <div>
            <span class="case-sector"><?= $st[0] ?></span>
            <h3><?= $st[1] ?></h3>
          </div>
          <span class="case-action">معاينة</span>
        </div>
        <p><?= $st[2] ?></p>
        <div class="case-metrics">
          <?php foreach($st[3] as $m):
            $metricClass = str_starts_with($m[0], '+') || str_contains($m[0], 'x') ? 'is-positive' : (str_starts_with($m[0], '-') ? 'is-reduction' : 'is-time');
          ?>
          <div class="case-metric <?= $metricClass ?>"><b><?= $m[0] ?></b><span><?= $m[1] ?></span></div>
          <?php endforeach; ?>
        </div>
      </button>
      <?php endforeach; ?>
    </div>
    <div class="stories-note reveal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
      <span>تُعرض المؤشرات بعد التحقق والمراجعة القانونية والتسويقية. الأرقام أعلاه تمثيلية في هذا النموذج.</span>
    </div>
  </div>
</section>

<!-- ===================== NEWS ===================== -->
<section class="section news-section">
  <div class="container">
    <div class="news-head reveal">
      <div>
        <span class="eyebrow">الأخبار والفعاليات</span>
        <h2 class="section-title mt-16">آخر المستجدات</h2>
        <p class="section-lead">تحديثات المنصة، منهجية الاستثمار، والشراكات المؤسسية في مكان واحد.</p>
      </div>
      <a href="news-events.php" class="btn btn-primary">عرض كل المستجدات</a>
    </div>
    <div class="news-layout mt-40">
      <?php
      $news = [
        ['فعالية','إطلاق الإصدار التشغيلي الأول للمنصة','17 يوليو 2026','نستعرض بوابتَي المستثمر ورائد الأعمال ولوحة الإدارة.'],
        ['مقال','منهجية تقليل مخاطر التنفيذ في الاستثمار الجريء','10 يوليو 2026','كيف نُجهّز المشروع للتشغيل قبل تفعيل رأس المال.'],
        ['شراكة','توسع مؤسسي مستهدف نحو السعودية والإمارات','2 يوليو 2026','خطة التوسع الجغرافي مع فصل بيانات كل دولة.'],
      ];
      foreach($news as $i=>$n): ?>
      <a href="news-events.php" class="news-item <?= $i===0?'featured':'' ?> reveal">
        <div class="news-meta">
          <span class="news-type <?= $n[0]==='فعالية'?'is-event':($n[0]==='شراكة'?'is-partner':'is-article') ?>"><?= $n[0] ?></span>
          <time datetime="2026-07-<?= $i===0?'17':($i===1?'10':'02') ?>"><?= $n[2] ?></time>
        </div>
        <h3><?= $n[1] ?></h3>
        <p><?= $n[3] ?></p>
        <span class="news-more">اقرأ المزيد <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="section final-cta-section">
  <div class="container">
    <div class="cta-band reveal">
      <div class="cta-content">
        <span class="cta-kicker">ابدأ الرحلة المناسبة لك</span>
        <h2>جاهز لتبدأ رحلتك الاستثمارية؟</h2>
        <p>سجّل كمستثمر معتمد أو قدّم مشروعك اليوم. تأهيل يدوي محكم، سرية كاملة، وتجربة رقمية شفافة بثلاث لغات.</p>
        <div class="cta-actions">
          <a href="login.php?tab=register" class="btn btn-primary btn-lg">سجّل كمستثمر</a>
          <a href="entrepreneurs.php" class="btn btn-ghost btn-lg">قدّم مشروعك الآن</a>
        </div>
      </div>
    </div>
  </div>
</section>

</main>
<?php include 'partials/footer.php'; ?>
