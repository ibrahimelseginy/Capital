<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>@yield('title', 'SEVEN TECH CAPITAL')</title>
  
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
    })();
  </script>

  <!-- Language Sync from URL -->
  <script>
    (function(){
      var params = new URLSearchParams(window.location.search);
      var lang = params.get('lang');
      var serverLang = "{{ app()->getLocale() }}";
      if (lang && lang !== serverLang && (lang === 'ar' || lang === 'en')) {
          window.location.href = "{{ url('/lang') }}/" + lang;
      }
    })();
  </script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/tokens.css') }}">
  <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
  <link rel="stylesheet" href="{{ asset('css/typography.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
</head>
<body class="no-transition">
  
  @yield('content')

</body>
</html>
