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
      /* Geniş ekran: kancalar tam görünür, içeriklerin önünde */
      #hook-left, #hook-right { transition: opacity .3s; }

      /* Tablet: içeriklerle örtüşme başlar, arkaya al ve soldur */
      @media (max-width: 1280px) {
        #hook-left, #hook-right {
          opacity: 0.18 !important;
          z-index: 0 !important;
        }
      }

      /* Mobile: neredeyse görünmez, tamamen içeriğin arkasında */
      @media (max-width: 768px) {
        #hook-left, #hook-right {
          opacity: 0.07 !important;
          z-index: 0 !important;
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
