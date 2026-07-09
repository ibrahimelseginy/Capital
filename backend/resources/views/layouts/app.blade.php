<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>@yield('title', 'SEVEN TECH CAPITAL — Dashboard')</title>
  
  <link rel="icon" type="image/png" href="{{ asset('Group 98.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('Group 98.png') }}">
  
  <!-- App Config -->
  <script>window.appBaseUrl = "{{ url('/') }}";</script>
  
  <!-- Prevent theme flash -->
  <script>
    (function(){
      var t = localStorage.getItem('stc-theme');
      if (!t) t = window.matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', t);
      window.__stcLogo = t === 'dark' ? window.appBaseUrl + '/Group 102.png' : window.appBaseUrl + '/Group 97.png';
      document.addEventListener('DOMContentLoaded', function(){
        ['header-logo','sidebar-logo'].forEach(function(id){
          var el = document.getElementById(id);
          if (el) el.src = window.__stcLogo;
        });
      });
    })();
  </script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  
  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/tokens.css') }}">
  <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
  <link rel="stylesheet" href="{{ asset('css/typography.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}">
</head>
<body class="no-transition">
  
  <div class="dashboard-layout">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="dashboard-main">
      <!-- Header -->
      @include('components.header')

      <!-- Content -->
      <div class="dashboard-content">
        @yield('content')
      </div>
    </main>
  </div>

  <!-- Global Search Modal -->
  <div class="search-modal" id="global-search-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);backdrop-filter:blur(10px);z-index:9999;align-items:flex-start;justify-content:center;padding-top:10vh;opacity:0;transition:opacity 0.3s ease;">
    <div style="width:100%;max-width:640px;margin:0 1rem;background:var(--bg-primary);border-radius:var(--radius-xl);box-shadow:0 20px 60px rgba(0,0,0,0.4);border:1px solid var(--border-default);overflow:hidden;transform:translateY(-20px);transition:transform 0.3s ease;" id="search-modal-content">
      <div style="padding:1.5rem;border-bottom:1px solid var(--border-default);display:flex;align-items:center;gap:1rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--action-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="global-search-input" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث...' : 'Search...' }}" style="flex:1;background:transparent;border:none;outline:none;font-size:1.25rem;color:var(--text-primary);" autocomplete="off">
        <button id="close-search-modal" style="background:var(--bg-secondary);border:1px solid var(--border-default);border-radius:var(--radius-md);cursor:pointer;color:var(--text-secondary);padding:0.5rem;display:flex;align-items:center;justify-content:center;transition:all 0.2s;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
      <div style="padding:3rem 2rem;min-height:240px;display:flex;flex-direction:column;align-items:center;justify-content:center;" id="search-results-container">
      </div>
    </div>
  </div>

  <!-- Cookie Consent -->
  <div class="cookie-banner" id="cookie-banner">
    <p class="text-body-sm">{{ app()->getLocale() == 'ar' ? 'نحن نستخدم ملفات تعريف الارتباط لتحسين تجربتك.' : 'We use cookies to improve your experience.' }}</p>
    <div class="d-flex gap-3 flex-shrink-0">
      <button class="btn btn-primary btn-sm" id="cookie-accept">{{ app()->getLocale() == 'ar' ? 'قبول' : 'Accept' }}</button>
      <button class="btn btn-ghost btn-sm" onclick="document.getElementById('cookie-banner').classList.remove('visible')">{{ app()->getLocale() == 'ar' ? 'رفض' : 'Decline' }}</button>
    </div>
  </div>

  <!-- App Script -->
  <script type="module" src="{{ asset('js/backend-app.js') }}"></script>
</body>
</html>
