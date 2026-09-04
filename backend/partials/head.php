<?php
  if (str_contains((string) ($_SERVER['SCRIPT_NAME'] ?? ''), '/dashboard/')) {
    require_once __DIR__ . '/../lib/auth.php';
    auth_protect_dashboard();
  }
  $base   = isset($base) ? $base : '';
  $title  = isset($title) ? $title : 'Seven Tech Capital';
  $active = isset($active) ? $active : '';
  $assetRoot = __DIR__ . '/../assets/css/';
  $assetVersion = static function (string $file) use ($assetRoot): string {
    $modified = @filemtime($assetRoot . $file);
    return $modified ? (string) $modified : '1';
  };
?><!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Set theme + mark JS before first paint: kills the light/dark flash (FOUC)
       and lets the reveal animations degrade gracefully when JS is unavailable. -->
  <script>
    (function () {
      var d = document.documentElement;
      d.classList.add('js');
      try {
        var t = localStorage.getItem('stc-theme');
        if (t !== 'dark' && t !== 'light') {
          t = window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        d.setAttribute('data-theme', t);
      } catch (e) {}
    })();
  </script>
  <meta name="description" content="Seven Tech Capital — صندوق استثماري مدعوم بذراع تقني بخبرة 20 عامًا. نبني المشروع قبل أن نُفعّل الاستثمار.">
  <meta name="theme-color" content="#FAF7F3" media="(prefers-color-scheme: light)">
  <meta name="theme-color" content="#0D0D0D" media="(prefers-color-scheme: dark)">
  <title><?= htmlspecialchars($title) ?> · Seven Tech Capital</title>
  <link rel="icon" type="image/svg+xml" href="<?= $base ?>assets/img/favicon.svg">
  <link rel="apple-touch-icon" href="<?= $base ?>assets/img/icon.png">
  <!-- Perf: preconnect to font CDNs before the CSS @import kicks in -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="<?= $base ?>assets/css/design-system.css?v=<?= $assetVersion('design-system.css') ?>">
  <link rel="stylesheet" href="<?= $base ?>assets/css/site.css?v=<?= $assetVersion('site.css') ?>">
  <link rel="stylesheet" href="<?= $base ?>assets/css/responsive.css?v=<?= $assetVersion('responsive.css') ?>">
</head>
<body class="page-<?= htmlspecialchars($active) ?>">
