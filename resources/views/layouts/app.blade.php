<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LiftAcademy')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <style>
      #hook-left, #hook-right { transition: opacity .3s; }

      /* Dar ekran: içerik alanına yaklaşınca arkaya gönder */
      @media (max-width: 1024px) {
        #hook-left, #hook-right {
          opacity: 0.25 !important;
          z-index: 0 !important;
        }
      }

      /* Mobil: gizle */
      @media (max-width: 640px) {
        #hook-left, #hook-right {
          display: none !important;
        }
      }
    </style>
</head>
<body class="bg-[#F5F0E8]">
    @include('partials.navbar')
    <main>@yield('content')</main>
    @include('partials.chat-widget')
    @stack('scripts')
</body>
</html>
