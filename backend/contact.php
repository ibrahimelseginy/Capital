<?php $base=''; $title='تواصل معنا'; $active='contact'; include 'partials/head.php'; include 'partials/nav.php'; ?>
<main id="main">

<section class="page-hero page-hero-simple">
  <div class="hero-dots"></div>
  <div class="container">
    <span class="eyebrow reveal">نحن هنا</span>
    <h1 class="reveal mt-16">تواصل مع فريق Seven Tech Capital</h1>
    <p class="reveal">استفسارات المستثمرين ورواد الأعمال والشراكات — نجيبك في أقرب وقت.</p>
  </div>
</section>

<section class="section contact-section">
  <div class="container">
    <div class="contact-layout">
      <!-- Form -->
      <div class="card card-pad-lg contact-form-card reveal">
        <div class="content-card-head">
          <span class="content-card-kicker">راسلنا مباشرة</span>
          <h2>أرسل رسالة</h2>
          <p>شاركنا تفاصيل استفسارك وسيوجّه الفريق رسالتك إلى الشخص المناسب.</p>
        </div>
        <form class="mt-24" onsubmit="demoAction(event,'شكرًا! تم استلام رسالتك (نموذج) — سيتواصل معك الفريق.')">
          <div class="form-grid">
            <div class="field"><label class="label" for="c-name">الاسم <span class="req">*</span></label><input class="input" id="c-name" name="name" autocomplete="name" required placeholder="اسمك…"></div>
            <div class="field"><label class="label" for="c-email">البريد <span class="req">*</span></label><input class="input" id="c-email" name="email" type="email" inputmode="email" autocomplete="email" spellcheck="false" required placeholder="you@example.com…"></div>
          </div>
          <div class="form-grid">
            <div class="field"><label class="label" for="c-tel">رقم واتساب</label><input class="input ltr-input" id="c-tel" name="whatsapp" type="tel" inputmode="tel" autocomplete="tel" spellcheck="false" placeholder="+966539555889"></div>
            <div class="field"><label class="label" for="c-type">نوع الاستفسار</label><select class="select" id="c-type" name="topic"><option>استفسار مستثمر</option><option>تقديم مشروع</option><option>شراكة استراتيجية</option><option>إعلامي / صحفي</option><option>عام</option></select></div>
          </div>
          <div class="field"><label class="label" for="c-msg">الرسالة <span class="req">*</span></label><textarea class="textarea" id="c-msg" name="message" required placeholder="كيف يمكننا مساعدتك؟"></textarea></div>
          <button class="btn btn-primary btn-lg btn-block" type="submit">إرسال الرسالة</button>
        </form>
      </div>

      <!-- Info -->
      <aside class="contact-sidebar reveal" aria-label="بيانات التواصل">
        <div class="card contact-info-card">
          <div class="row gap-12"><div class="sector-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="M22 6l-10 7L2 6"/></svg></div><div><b>البريد الإلكتروني</b><a class="text-2 contact-value" href="mailto:hello@seventech.capital">hello@seventech.capital</a></div></div>
        </div>
        <div class="card contact-info-card">
          <div class="row gap-12"><div class="sector-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></div><div><b>واتساب</b><a class="text-2 ltr-number contact-value" href="https://wa.me/966539555889">+966539555889</a></div></div>
        </div>
        <div class="card contact-offices">
          <b>المكاتب</b>
          <div class="office-list">
            <div class="office-row"><span class="badge badge-orange">القاهرة</span><span class="text-2">مصر — المقر الرئيسي</span></div>
            <div class="office-row"><span class="badge badge-info">دبي</span><span class="text-2">الإمارات — قيد التوسع</span></div>
            <div class="office-row"><span class="badge badge-success">الرياض</span><span class="text-2">السعودية — مستهدف</span></div>
          </div>
        </div>
        <div class="contact-notice">
          <p>كيان قيد التأسيس واستكمال التراخيص — لا يُستقبل تمويل قبل الاعتماد القانوني.</p>
        </div>
      </aside>
    </div>
  </div>
</section>

</main>
<?php include 'partials/footer.php'; ?>
