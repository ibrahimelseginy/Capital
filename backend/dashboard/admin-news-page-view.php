<?php
require_once __DIR__.'/../lib/news-page-sections.php';
require_once __DIR__.'/content-editor-fields.php';
try{$newsSections=news_page_read();}catch(Throwable $error){error_log('News editor: '.$error->getMessage());echo '<div class="auth-message auth-message-error">تعذر تحميل المحتوى. شغّل MySQL في MAMP ثم أعد تحميل الصفحة.</div>';return;}
?>
<link rel="stylesheet" href="../assets/css/home-editor.css?v=<?= filemtime(__DIR__.'/../assets/css/home-editor.css') ?>">
<div class="home-manager" data-home-manager data-endpoint="../api/admin-news-page-sections.php">
  <div class="panel home-manager-intro"><div><h3>التحكم في صفحة الأخبار</h3><p>افتح القسم المطلوب وعدّل محتواه ثم اضغط «حفظ ونشر القسم».</p><p class="hint">يمكنك تعديل المقدمة، وإضافة الأخبار والمقالات والفعاليات، وتحديد المقال المميّز والصورة والروابط والترتيب والإظهار.</p></div><a href="../news-events.php" target="_blank" rel="noopener" class="btn btn-ghost">معاينة صفحة الأخبار ↗</a></div>
  <nav class="home-editor-nav" aria-label="أقسام صفحة الأخبار"><?php foreach(news_page_schema() as $key=>$schema):?><a href="#edit-<?= $key ?>" class="btn btn-soft btn-sm"><?= home_escape($schema['label']) ?></a><?php endforeach;?></nav>
  <noscript><div class="auth-message auth-message-error">فعّل JavaScript لتعديل الصفحة وحفظها.</div></noscript>
  <div class="home-editor-sections">
    <?php foreach(news_page_schema() as $key=>$schema):$section=$newsSections[$key];?>
    <details class="home-editor-section" id="edit-<?= $key ?>"><summary><span><?= home_escape($schema['label']) ?></span><span class="badge" data-section-state><?= $section['content']['is_active']?'ظاهر':'مخفي' ?></span></summary>
      <form data-home-form data-section="<?= $key ?>" data-revision="<?= (int)$section['revision'] ?>" class="home-editor-form">
        <input type="hidden" name="csrf" value="<?= home_escape(auth_csrf_token()) ?>">
        <?php home_editor_fields($schema['fields'],$section['content']);?>
        <div class="home-save-row"><span role="status" aria-live="polite" data-home-result>المحتوى المحفوظ حاليًا</span><button type="submit" class="btn btn-primary">حفظ ونشر القسم</button></div>
      </form>
    </details>
    <?php endforeach;?>
  </div>
</div>
<script src="../assets/js/admin-home.js?v=<?= filemtime(__DIR__.'/../assets/js/admin-home.js') ?>" defer></script>
