<?php
declare(strict_types=1);
require_once __DIR__.'/home.php';

function home_button(string $label, string $url, string $class='btn btn-primary'): string
{
    if ($label==='' || $url==='' || !home_safe_url($url)) return '';
    return '<a data-home-link href="'.home_escape($url).'" class="'.home_escape($class).'">'.home_escape($label).'</a>';
}

function home_heading(array $s, string $class, bool $button=false): void
{ ?>
  <div class="<?= home_escape($class) ?> reveal"><div>
    <span class="eyebrow"><?= home_escape($s['eyebrow']) ?></span>
    <h2 class="section-title mt-16"><?= home_escape($s['title']) ?></h2>
    <p class="section-lead"><?= home_escape($s['description']) ?></p>
  </div><?php if ($button) echo home_button($s['button_label'],$s['button_url']); ?></div>
<?php }

function home_render(array $content): string
{
    ob_start();
    foreach ($content as $key=>$s):
    if ($key==='hero'): ?>
<section class="hero" id="home-hero" data-home-section="hero">
  <div class="hero-grid-bg"></div><div class="container hero-inner">
    <div class="hero-copy reveal">
      <div class="hero-status"><span class="badge badge-orange"><span class="dot"></span><?= home_escape($s['badge']) ?></span><span class="hero-region"><?= home_escape($s['region']) ?></span></div>
      <h1><?= home_escape($s['title']) ?> <span class="accent"><?= home_escape($s['highlight']) ?></span></h1>
      <p class="hero-sub"><?= home_escape($s['description']) ?></p>
      <div class="hero-proof"><?php foreach ($s['proofs'] as $proof): ?><span><?= home_escape($proof['text']) ?></span><?php endforeach; ?></div>
      <div class="hero-cta"><?= home_button($s['primary_label'],$s['primary_url'],'btn btn-primary btn-lg') ?><?= home_button($s['secondary_label'],$s['secondary_url'],'btn btn-ghost') ?></div>
    </div>
    <div class="hero-visual reveal"><div class="gate-ledger" aria-label="<?= home_escape($s['ledger_title']) ?>">
      <div class="gl-head"><div><span class="gl-tag"><?= home_escape($s['ledger_tag']) ?></span><h2><?= home_escape($s['ledger_title']) ?></h2></div><span class="gl-lock"><?= home_icon('shield') ?><?= home_escape($s['ledger_status']) ?></span></div>
      <p class="gl-summary"><?= home_escape($s['ledger_description']) ?></p>
      <ol class="gl-gates"><?php foreach ($s['gates'] as $i=>$gate): ?>
        <li class="gl-gate <?= home_escape($gate['style']) ?>"><span class="gl-idx"><?= sprintf('%02d',$i+1) ?></span><div><b><?= home_escape($gate['title']) ?></b><span class="d"><?= home_escape($gate['description']) ?></span></div><span class="gl-state <?= $gate['style']==='waiting'?'gl-state-waiting':'' ?>"><?= home_escape($gate['status']) ?></span></li>
      <?php endforeach; ?></ol>
      <div class="gl-meter"><span class="gl-mlabel"><?= home_escape($s['meter_label']) ?></span><span class="gl-mval"><?= home_escape($s['meter_status']) ?></span><div class="gl-track" role="progressbar" aria-label="<?= home_escape($s['meter_label']) ?>" aria-valuenow="<?= (int)$s['progress'] ?>" aria-valuemin="0" aria-valuemax="100"><i style="width:<?= (int)$s['progress'] ?>%"></i></div></div>
    </div></div>
  </div>
</section>
<?php elseif ($key==='stats'): ?>
<section class="stat-band section-sm" id="home-stats" data-home-section="stats" aria-label="أرقام الفريق والخبرة"><div class="container"><div class="stat-row">
  <?php foreach ($s['items'] as $item): ?><div class="stat reveal"><span class="stat-ico"><?= home_icon($item['icon']) ?></span><b class="mono" dir="ltr"><?= home_escape($item['value']) ?></b><span class="stat-label"><?= home_escape($item['label']) ?></span></div><?php endforeach; ?>
</div></div></section>
<?php elseif ($key==='why'): ?>
<section class="section methodology-section" id="home-why" data-home-section="why"><div class="container">
  <?php home_heading($s,'method-head'); ?><div class="method-grid mt-40">
    <?php foreach ($s['items'] as $i=>$item): ?><div class="method-card <?= $item['featured']?'featured':'' ?> reveal"><div class="method-card-top"><div class="method-ico"><?= home_icon($item['icon']) ?></div><span class="method-num"><?= sprintf('%02d',$i+1) ?></span></div><h3><?= home_escape($item['title']) ?></h3><p><?= home_escape($item['description']) ?></p></div><?php endforeach; ?>
  </div>
</div></section>
<?php elseif ($key==='sectors'): ?>
<section class="section sectors-section" id="home-sectors" data-home-section="sectors"><div class="container">
  <?php home_heading($s,'sectors-head',true); ?><div class="sectors-grid mt-40">
    <?php foreach ($s['items'] as $i=>$item): ?><a data-home-link href="<?= home_escape($item['url']?:'sectors.php') ?>" class="sector-tile reveal"><div class="sector-tile-top"><div class="sector-ico"><?= home_icon($item['icon']) ?></div><span class="sector-rank"><?= sprintf('%02d',$i+1) ?></span></div><h3><?= home_escape($item['title']) ?></h3><p><?= home_escape($item['description']) ?></p><span class="sector-more"><?= home_escape($item['button_label']) ?> ←</span></a><?php endforeach; ?>
  </div>
</div></section>
<?php elseif ($key==='paths'): ?>
<section class="section paths-section" id="home-paths" data-home-section="paths"><div class="container">
  <?php home_heading($s,'paths-head center'); ?><div class="paths-grid mt-40">
    <?php foreach ($s['items'] as $item): ?><div class="path-card <?= home_escape($item['style']) ?> reveal"><div class="path-top"><span class="path-badge"><?= home_escape($item['badge']) ?></span><div class="path-ico"><?= home_icon($item['icon']) ?></div></div><h3><?= home_escape($item['title']) ?></h3><p><?= home_escape($item['description']) ?></p><ul class="path-list"><?php foreach ($item['features'] as $feature): ?><li><span aria-hidden="true">✓</span><span><?= home_escape($feature['text']) ?></span></li><?php endforeach; ?></ul><?= home_button($item['button_label'],$item['url'],'btn '.($item['style']==='investor'?'btn-primary':'btn-dark').' btn-block') ?></div><?php endforeach; ?>
  </div>
</div></section>
<?php elseif ($key==='stories'): ?>
<section class="section stories-section" id="home-stories" data-home-section="stories"><div class="container">
  <?php home_heading($s,'stories-head',true); ?><div class="stories-showcase mt-40">
    <?php foreach ($s['items'] as $i=>$item): ?>
      <button class="case-card reveal" type="button" data-home-story="home-story-<?= $i ?>" aria-haspopup="dialog"><span class="case-glow" aria-hidden="true"></span><div class="case-top"><div><span class="case-sector"><?= home_escape($item['category']) ?></span><h3><?= home_escape($item['title']) ?></h3></div><span class="case-action"><?= home_escape($s['preview_label']) ?></span></div><p><?= home_escape($item['description']) ?></p><div class="case-metrics"><?php foreach ($item['metrics'] as $metric): ?><div class="case-metric <?= home_escape($metric['style']) ?>"><b dir="auto"><?= home_escape($metric['value']) ?></b><span><?= home_escape($metric['label']) ?></span></div><?php endforeach; ?></div></button>
      <template id="home-story-<?= $i ?>"><h3 data-dialog-title><?= home_escape($item['title']) ?></h3><div class="home-modal-badges"><span class="badge badge-orange"><?= home_escape($item['category']) ?></span><span class="badge badge-success"><?= home_escape($s['modal_badge']) ?></span></div><p><?= home_escape($item['modal_description']?:$item['description']) ?></p><div class="modal-stat-grid"><?php foreach ($item['metrics'] as $metric): ?><div><b dir="auto"><?= home_escape($metric['value']) ?></b><span><?= home_escape($metric['label']) ?></span></div><?php endforeach; ?></div><div class="modal-note"><?= home_escape($s['modal_note']) ?></div><?= home_button($s['modal_button_label'],$s['modal_button_url'],'btn btn-primary btn-block mt-24') ?></template>
    <?php endforeach; ?>
  </div><div class="stories-note reveal"><span aria-hidden="true">ⓘ</span><span><?= home_escape($s['note']) ?></span></div>
</div></section>
<?php elseif ($key==='news'): ?>
<section class="section news-section" id="home-news" data-home-section="news"><div class="container">
  <?php home_heading($s,'news-head',true); ?><div class="news-layout mt-40">
    <?php foreach ($s['items'] as $item): ?><a data-home-link href="<?= home_escape($item['url']?:'news-events.php') ?>" class="news-item <?= $item['featured']?'featured':'' ?> reveal"><div class="news-meta"><span class="news-type <?= home_escape($item['style']) ?>"><?= home_escape($item['category']) ?></span><time datetime="<?= home_escape($item['date']) ?>"><?= home_escape($item['date_label']?:$item['date']) ?></time></div><h3><?= home_escape($item['title']) ?></h3><p><?= home_escape($item['description']) ?></p><span class="news-more"><?= home_escape($item['button_label']) ?> ←</span></a><?php endforeach; ?>
  </div>
</div></section>
<?php elseif ($key==='cta'): ?>
<section class="section final-cta-section" id="home-cta" data-home-section="cta"><div class="container"><div class="cta-band reveal"><div class="cta-content"><span class="cta-kicker"><?= home_escape($s['eyebrow']) ?></span><h2><?= home_escape($s['title']) ?></h2><p><?= home_escape($s['description']) ?></p><div class="cta-actions"><?= home_button($s['primary_label'],$s['primary_url'],'btn btn-primary btn-lg') ?><?= home_button($s['secondary_label'],$s['secondary_url'],'btn btn-ghost btn-lg') ?></div></div></div></div></section>
<?php endif;
    endforeach;
    return ob_get_clean();
}
