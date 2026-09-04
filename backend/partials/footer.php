<?php $base = isset($base) ? $base : ''; ?>
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand-col">
        <div class="brand">
          <?php include __DIR__ . '/logo.php'; ?>
        </div>
        <p>
          صندوق استثماري مدعوم بذراع تقني بخبرة تمتد إلى 20 عامًا. نبني المشروع ونُجهّزه للتشغيل قبل تفعيل رأس المال.
        </p>
      </div>
      <nav class="footer-links-col" aria-label="روابط المنصة">
        <h5>المنصة</h5>
        <a href="<?= $base ?>investors.php">للمستثمرين</a>
        <a href="<?= $base ?>entrepreneurs.php">لرواد الأعمال</a>
        <a href="<?= $base ?>sectors.php">القطاعات المستهدفة</a>
        <a href="<?= $base ?>success-stories.php">قصص النجاح</a>
        <a href="<?= $base ?>login.php">تسجيل الدخول</a>
      </nav>
      <nav class="footer-links-col" aria-label="روابط الشركة">
        <h5>الشركة</h5>
        <a href="<?= $base ?>about.php">من نحن</a>
        <a href="<?= $base ?>seven-tech.php">الذراع التقني</a>
        <a href="<?= $base ?>news-events.php">الأخبار والفعاليات</a>
        <a href="<?= $base ?>contact.php">تواصل معنا</a>
      </nav>
      <div class="footer-contact-col">
        <h5>تواصل سريع</h5>
        <a href="mailto:hello@seventech.capital" class="footer-contact-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
          hello@seventech.capital
        </a>
        <a href="tel:+966539555889" class="footer-contact-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.32 1.77.59 2.61a2 2 0 0 1-.45 2.11L8 9.7a16 16 0 0 0 6.3 6.3l1.26-1.25a2 2 0 0 1 2.11-.45c.84.27 1.71.47 2.61.59A2 2 0 0 1 22 16.92z"/></svg>
          <span class="ltr-number">+966539555889</span>
        </a>
        <div class="footer-contact-link footer-address">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
          <span>القاهرة، مصر · نطاق العمل MENA والخليج</span>
        </div>
        <div class="footer-social" aria-label="روابط التواصل الاجتماعي">
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
      </div>
      <div class="footer-legal-col">
        <h5>قانوني</h5>
        <button type="button" class="footer-legal" onclick="demoAction(event)">الشروط والأحكام</button>
        <button type="button" class="footer-legal" onclick="demoAction(event)">سياسة الخصوصية</button>
        <button type="button" class="footer-legal" onclick="demoAction(event)">إخلاء المسؤولية</button>
        <button type="button" class="footer-legal" onclick="demoAction(event)">سياسة KYC/AML</button>
        <button type="button" class="footer-legal" onclick="demoAction(event)">ملفات تعريف الارتباط</button>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Seven Tech Capital — جميع الحقوق محفوظة.</span>
      <a href="<?= $base ?>contact.php">تواصل مع الفريق</a>
    </div>
  </div>
</footer>
<script src="<?= $base ?>assets/js/app.js"></script>
</body>
</html>
