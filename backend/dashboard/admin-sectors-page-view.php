<?php
require_once __DIR__.'/../lib/sectors-sections.php';
require_once __DIR__.'/content-editor-fields.php';
try { $sectorsSections=sectors_read(); }
catch (Throwable $error) { error_log('Sectors editor: '.$error->getMessage());echo '<div class="auth-message auth-message-error">تعذر تحميل المحتوى. شغّل MySQL في MAMP ثم أعد تحميل الصفحة.</div>';return; }
?>
<link rel="stylesheet" href="../assets/css/home-editor.css?v=<?= filemtime(__DIR__.'/../assets/css/home-editor.css') ?>">
<div class="home-manager" data-home-manager data-endpoint="../api/admin-sectors-sections.php">
  <div class="panel home-manager-intro"><div><h3>التحكم في صفحة القطاعات</h3><p>افتح القسم المطلوب وعدّل محتواه ثم اضغط «حفظ ونشر القسم».</p><p class="hint">أربعة أقسام بالترتيب المطلوب. يمكنك إضافة بطاقات قطاعات وترتيبها وإظهارها أو إخفاؤها. عدد الفرص ووقت آخر تحديث يأتيان تلقائيًا من الفرص المنشورة.</p></div><a href="../sectors.php" target="_blank" rel="noopener" class="btn btn-ghost">معاينة القطاعات ↗</a></div>
  <nav class="home-editor-nav" aria-label="أقسام صفحة القطاعات"><?php foreach (sectors_schema() as $key=>$schema): ?><a href="#edit-<?= $key ?>" class="btn btn-soft btn-sm"><?= home_escape($schema['label']) ?></a><?php endforeach; ?></nav>
  <noscript><div class="auth-message auth-message-error">فعّل JavaScript لتعديل الصفحة وحفظها.</div></noscript>
  <div class="home-editor-sections">
    <?php foreach (sectors_schema() as $key=>$schema): $section=$sectorsSections[$key]; ?>
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

