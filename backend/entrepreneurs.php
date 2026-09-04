<?php $base=''; $title='رواد الأعمال'; $active='entrepreneurs'; include 'partials/head.php'; include 'partials/nav.php'; ?>

<main id="main" data-entrepreneurs-page data-api="api/entrepreneurs.php" aria-busy="true">
<section class="page-hero entrepreneur-hero" data-entrepreneurs-section="hero">
  <div class="hero-dots"></div>
  <div class="container">
    <div class="entrepreneur-hero-grid" data-entrepreneurs-hero></div>
  </div>
</section>

<!-- Support stages -->
<section class="section-sm entrepreneur-stages" id="stages" data-entrepreneurs-section="stages">
  <div class="container">
    <div class="entrepreneur-section-head" data-stages-head></div>
    <div class="support-stage-grid mt-40" data-support-stages></div>
  </div>
</section>

<!-- Evaluation criteria -->
<section class="section entrepreneur-evaluation" data-entrepreneurs-section="evaluation">
  <div class="container">
    <div class="entrepreneur-eval-grid"><div><div data-evaluation-head></div><div class="criteria-grid mt-24" data-evaluation-criteria></div></div><div class="review-card" data-review-card></div></div>
  </div>
</section>

<!-- Application form (multi-step preview) -->
<section class="section entrepreneur-apply" id="apply" data-entrepreneurs-section="apply">
  <div class="container">
    <div class="entrepreneur-apply-head" data-apply-head></div>

    <div class="application-shell reveal mt-40" id="wizard-card">
      <!-- Steps indicator -->
      <div class="application-steps">
        <?php $steps=['الدعم','المشروع','الفريق','الملفات'];
        foreach($steps as $i=>$s): ?>
        <div class="application-step <?= $i===0?'active':'' ?>" id="step-indicator-<?= $i+1 ?>">
          <div class="step-num"><?= '0'.($i+1) ?></div>
          <span class="step-label"><?= $s ?></span>
          <?php if($i<3): ?><div class="progress"><span class="step-bar" style="width:<?= $i===0?'100':'0' ?>%"></span></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <form id="wizard-form" onsubmit="event.preventDefault(); window.nextStep();">
        <!-- Step 1 -->
        <div class="wizard-step" id="step-content-1">
          <fieldset style="border:0;padding:0;margin:0;min-inline-size:0">
            <legend class="label" id="support-label" style="margin-bottom:12px;padding:0">اختر الدعم <span class="req">*</span></legend>
            <div class="support-options" role="radiogroup" data-support-options></div>
          </fieldset>

          <div class="form-grid mt-24">
            <div class="field"><label class="label" for="p-name">اسم المشروع <span class="req">*</span></label><input class="input" id="p-name" name="project" autocomplete="organization" placeholder="مثال: منصة X" required></div>
            <div class="field"><label class="label" for="p-sector">القطاع <span class="req">*</span></label><select class="select" id="p-sector" name="sector" required><option>البرمجيات و SaaS</option><option>التقنية المالية</option><option>الذكاء الاصطناعي</option><option>الصحة الرقمية</option><option>التعليم التقني</option><option>اللوجستيات</option></select></div>
          </div>
          <div class="field"><label class="label" for="p-desc">ملخص الفكرة <span class="req">*</span></label><textarea class="textarea" id="p-desc" name="description" placeholder="المشكلة، الحل، والقيمة المضافة..." required></textarea></div>
        </div>

        <!-- Step 2 -->
        <div class="wizard-step hide" id="step-content-2">
          <h4 class="form-section-title" id="step-2-title">المرحلة والاحتياج التمويلي</h4>
          <div class="form-grid">
            <div class="field"><label class="label" for="p-stage">المرحلة الحالية</label><select class="select" id="p-stage" name="stage"><option>فكرة</option><option>نموذج أولي</option><option>MVP</option><option>إطلاق</option><option>نمو</option></select></div>
            <div class="field"><label class="label" for="p-fund">التمويل المطلوب ($)</label><input class="input" id="p-fund" name="funding" type="text" inputmode="numeric" placeholder="$ 250,000"></div>
          </div>
          <div class="field"><label class="label" for="p-market">السوق المستهدف والعملاء</label><input class="input" id="p-market" placeholder="مثال: قطاع التجزئة والمقاهي في السعودية ومصر"></div>
          <div class="field"><label class="label" for="p-diff">الميزة التنافسية</label><textarea class="textarea" id="p-diff" placeholder="ما الذي يميّز حلّك عن المنافسين؟"></textarea></div>
        </div>

        <!-- Step 3 -->
        <div class="wizard-step hide" id="step-content-3">
          <h4 class="form-section-title" id="step-3-title">الفريق الحالي والهيكل</h4>
          <div class="form-grid">
            <div class="field"><label class="label" for="p-founders">عدد المؤسسين</label><input class="input" id="p-founders" type="number" value="2" min="1"></div>
            <div class="field"><label class="label" for="p-cto">هل يوجد شريك تقني (CTO)؟</label><select class="select" id="p-cto"><option>نعم — شريك مؤسس</option><option>لا — بحاجة لذراع تقني</option><option>قيد التوظيف</option></select></div>
          </div>
          <div class="field"><label class="label" for="p-experience">خبرات الفريق السابقة</label><textarea class="textarea" id="p-experience" placeholder="نبذة عن خبرات وإنجازات فريق العمل..."></textarea></div>
        </div>

        <!-- Step 4 -->
        <div class="wizard-step hide" id="step-content-4">
          <h4 class="form-section-title" id="step-4-title">المرفقات وطلب الدعم</h4>
          <div class="field">
            <label class="label" id="upload-label">عرض المشروع (Pitch Deck / Business Plan)</label>
            <div class="upload-box" onclick="toast('تم اختيار الملف بنجاح (معاينة).')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
              <b id="upload-title">اسحب وأسقط عرض المشروع هنا أو انقر للتصفح</b>
              <span class="hint" id="upload-hint" style="margin-top:4px;display:block">يدعم PDF, PPTX, DOCX (بحد أقصى 25MB)</span>
            </div>
          </div>
          <div class="field mt-16">
            <label class="label"><input type="checkbox" required checked> <span id="consent-label">أوافق على شروط السرية وسياسة معالجة البيانات</span></label>
          </div>
        </div>

        <div class="draft-note"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> <span id="draft-note-text">حفظ تلقائي للمسودة</span></div>

        <div class="wizard-actions">
          <button class="btn btn-ghost btn-lg hide" id="btn-prev" type="button" onclick="window.prevStep()">← الانتقال إلى الخطوة السابقة</button>
          <div>
            <button class="btn btn-ghost btn-lg" id="btn-draft" type="button" onclick="toast('حُفظت المسودة بنجاح.')">حفظ كمسودة</button>
            <button class="btn btn-primary btn-lg" id="btn-next" type="submit">الانتقال إلى الخطوة التالية →</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
let currentStep = 1;
window.nextStep = function() {
  if (currentStep < 4) {
    document.getElementById('step-content-' + currentStep).classList.add('hide');
    currentStep++;
    document.getElementById('step-content-' + currentStep).classList.remove('hide');
    updateStepUI();
    toast('تم الانتقال إلى الخطوة ' + currentStep + ' من 4');
  } else {
    toast('🎉 تم تقديم مشروعك بنجاح! سيقوم الفريق بمراجعته وإشعارك بالقرار.');
  }
};
window.prevStep = function() {
  if (currentStep > 1) {
    document.getElementById('step-content-' + currentStep).classList.add('hide');
    currentStep--;
    document.getElementById('step-content-' + currentStep).classList.remove('hide');
    updateStepUI();
  }
};
function updateStepUI() {
  const copy=(window.entrepreneurFormCopy||{})['step_'+currentStep]||{};
  document.getElementById('btn-prev').classList.toggle('hide', currentStep === 1);
  document.getElementById('btn-prev').textContent=copy.previous_label||'← الانتقال إلى الخطوة السابقة';
  document.getElementById('btn-next').textContent = currentStep === 4 ? (copy.submit_label||'إرسال الطلب النهائي ✔') : (copy.next_label||'الانتقال إلى الخطوة التالية →');
  document.getElementById('btn-draft').textContent=copy.save_label||'حفظ كمسودة';
  document.getElementById('draft-note-text').textContent=copy.draft_label||'حفظ تلقائي للمسودة';
  for (let i = 1; i <= 4; i++) {
    const ind = document.getElementById('step-indicator-' + i);
    if (!ind) continue;
    const num = ind.querySelector('.step-num');
    const label = ind.querySelector('.step-label');
    const bar = ind.querySelector('.step-bar');
    if (i <= currentStep) {
      ind.classList.add('active');
      num.style.background = 'var(--orange)';
      num.style.color = '#fff';
      if (label) { label.style.color = 'var(--text)'; label.style.fontWeight = '700'; }
      if (bar) bar.style.width = '100%';
    } else {
      ind.classList.remove('active');
      num.style.background = 'var(--surface-3)';
      num.style.color = 'var(--text-3)';
      if (label) { label.style.color = 'var(--text-3)'; label.style.fontWeight = '700'; }
      if (bar) bar.style.width = '0';
    }
  }
}
window.updateEntrepreneurStepUI=updateStepUI;
</script>

</main>

<script src="assets/js/api-config.js?v=1"></script>
<script src="assets/js/entrepreneurs-page.js?v=20260901-form" defer></script>
<?php include 'partials/footer.php'; ?>
