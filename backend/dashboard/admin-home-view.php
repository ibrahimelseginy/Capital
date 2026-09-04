<?php
require_once __DIR__.'/content-editor-fields.php';
try { $homeSections=home_read(); } catch (Throwable $error) { error_log('Home editor: '.$error->getMessage()); echo '<div class="auth-message auth-message-error">تعذر تحميل المحتوى. شغّل MySQL في MAMP ثم أعد تحميل الصفحة.</div>'; return; }
?>
<link rel="stylesheet" href="../assets/css/home-editor.css?v=<?= filemtime(__DIR__.'/../assets/css/home-editor.css') ?>">
<div class="home-manager" data-home-manager>
  <div class="panel home-manager-intro"><div><h3>التحكم في الصفحة الرئيسية</h3><p>عدّل الأقسام والبطاقات ثم اضغط «حفظ ونشر القسم». الإظهار والترتيب مستقلان لكل قسم وعنصر. حذف بطاقة هنا لا يحذف الخبر أو القطاع الأصلي.</p><p class="hint">التعديلات هنا تخص عرض الصفحة الرئيسية. استخدم صفحات الأخبار والقطاعات لتعديل التفاصيل الأصلية.</p></div><a href="../index.php" target="_blank" rel="noopener" class="btn btn-ghost">معاينة الرئيسية ↗</a></div>
  <nav class="home-editor-nav" aria-label="أقسام الصفحة الرئيسية"><?php foreach (home_schema() as $key=>$schema): ?><a href="#edit-<?= $key ?>" class="btn btn-soft btn-sm"><?= home_escape($schema['label']) ?></a><?php endforeach; ?></nav>
  <noscript><div class="auth-message auth-message-error">فعّل JavaScript لتعديل الصفحة وحفظها.</div></noscript>
  <div class="home-editor-sections">
  <?php foreach (home_schema() as $key=>$schema): $section=$homeSections[$key]; ?>
    <details class="home-editor-section" id="edit-<?= $key ?>"><summary><span><?= home_escape($schema['label']) ?></span><span class="badge" data-section-state><?= $section['content']['is_active']?'ظاهر':'مخفي' ?></span></summary>
      <form data-home-form data-section="<?= $key ?>" data-revision="<?= (int)$section['revision'] ?>" class="home-editor-form">
        <input type="hidden" name="csrf" value="<?= home_escape(auth_csrf_token()) ?>">
        <?php home_editor_fields($schema['fields'],$section['content']); ?>
        <div class="home-save-row"><span role="status" aria-live="polite" data-home-result>المحتوى المحفوظ حاليًا</span><button type="submit" class="btn btn-primary">حفظ ونشر القسم</button></div>
      </form>
    </details>
  <?php endforeach; ?>
  </div>
</div>
<script src="../assets/js/admin-home.js?v=<?= filemtime(__DIR__.'/../assets/js/admin-home.js') ?>" defer></script>
