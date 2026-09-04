<?php
require_once __DIR__.'/../lib/about.php';
require_once __DIR__.'/content-editor-fields.php';
try { $aboutSections=about_read()['sections']; }
catch (Throwable $error) { error_log('About editor: '.$error->getMessage()); echo '<div class="auth-message auth-message-error">تعذر تحميل المحتوى. شغّل MySQL في MAMP ثم أعد تحميل الصفحة.</div>'; return; }
?>
<link rel="stylesheet" href="../assets/css/home-editor.css?v=<?= filemtime(__DIR__.'/../assets/css/home-editor.css') ?>">
<div class="home-manager" data-home-manager data-endpoint="../api/admin-about-sections.php">
  <div class="panel home-manager-intro"><div><h3>التحكم في صفحة من نحن</h3><p>افتح القسم المطلوب وعدّل محتواه ثم اضغط «حفظ ونشر القسم». يمكنك ترتيب الأقسام والبطاقات وإظهارها أو إخفاؤها بشكل مستقل.</p><p class="hint">بطاقات الفريق وعناوينه في قسم واحد، وكذلك مناطق التوسع ومقدمتها. التغييرات تُعرض على صفحة من نحن في الموقع.</p></div><a href="../about.php" target="_blank" rel="noopener" class="btn btn-ghost">معاينة من نحن ↗</a></div>
  <nav class="home-editor-nav" aria-label="أقسام صفحة من نحن"><?php foreach (about_schema() as $key=>$schema): ?><a href="#edit-<?= $key ?>" class="btn btn-soft btn-sm"><?= home_escape($schema['label']) ?></a><?php endforeach; ?></nav>
  <noscript><div class="auth-message auth-message-error">فعّل JavaScript لتعديل الصفحة وحفظها.</div></noscript>
  <div class="home-editor-sections">
    <?php foreach (about_schema() as $key=>$schema): $section=$aboutSections[$key]; ?>
    <details class="home-editor-section" id="edit-<?= $key ?>"><summary><span><?= home_escape($schema['label']) ?></span><span class="badge" data-section-state><?= $section['content']['is_active']?'ظاهر':'مخفي' ?></span></summary>
      <form data-home-form data-section="<?= $key ?>" data-revision="<?= home_escape($section['revision']) ?>" class="home-editor-form">
        <input type="hidden" name="csrf" value="<?= home_escape(auth_csrf_token()) ?>">
        <?php home_editor_fields($schema['fields'],$section['content']); ?>
        <div class="home-save-row"><span role="status" aria-live="polite" data-home-result>المحتوى المحفوظ حاليًا</span><button type="submit" class="btn btn-primary">حفظ ونشر القسم</button></div>
      </form>
    </details>
    <?php endforeach; ?>
  </div>
</div>
<script src="../assets/js/admin-home.js?v=<?= filemtime(__DIR__.'/../assets/js/admin-home.js') ?>" defer></script>
