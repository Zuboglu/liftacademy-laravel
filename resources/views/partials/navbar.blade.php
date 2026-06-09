@php
$currentLocale = app()->getLocale();
$langs = [
    'tr' => ['flag' => '🇹🇷', 'name' => 'Türkçe'],
    'en' => ['flag' => '🇬🇧', 'name' => 'English'],
    'de' => ['flag' => '🇩🇪', 'name' => 'Deutsch'],
    'zh' => ['flag' => '🇨🇳', 'name' => '中文'],
    'az' => ['flag' => '🇦🇿', 'name' => 'Azərbaycan'],
    'ru' => ['flag' => '🇷🇺', 'name' => 'Русский'],
    'ar' => ['flag' => '🇸🇦', 'name' => 'العربية'],
    'ka' => ['flag' => '🇬🇪', 'name' => 'ქართული'],
];
$isRtl = $currentLocale === 'ar';
@endphp

<header class="sticky top-0 z-50 bg-[#F5F0E8] border-b-[3px] border-[#0A0A0A]" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <div class="max-w-[1400px] mx-auto px-6">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-[#FFE000] border-[3px] border-[#0A0A0A] flex items-center justify-center font-black text-base" style="box-shadow:3px 3px 0 #0A0A0A">C</div>
                <span class="font-black text-lg uppercase tracking-tight text-[#0A0A0A]">LiftAcademy</span>
            </a>

            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('courses.index') }}" class="font-bold text-sm uppercase tracking-wider text-[#0A0A0A] px-4 py-2 hover:bg-[#FFE000] border-[2px] border-transparent hover:border-[#0A0A0A] transition-all duration-100 {{ request()->routeIs('courses.*') ? 'bg-[#FFE000] !border-[#0A0A0A]' : '' }}">{{ __('ui.courses') }}</a>
                <a href="{{ route('quizzes.index') }}" class="font-bold text-sm uppercase tracking-wider text-[#0A0A0A] px-4 py-2 hover:bg-[#FFE000] border-[2px] border-transparent hover:border-[#0A0A0A] transition-all duration-100 {{ request()->routeIs('quizzes.*') ? 'bg-[#FFE000] !border-[#0A0A0A]' : '' }}">{{ __('ui.quizzes') }}</a>
                <a href="{{ route('certificates.index') }}" class="font-bold text-sm uppercase tracking-wider text-[#0A0A0A] px-4 py-2 hover:bg-[#FFE000] border-[2px] border-transparent hover:border-[#0A0A0A] transition-all duration-100 {{ request()->routeIs('certificates.*') ? 'bg-[#FFE000] !border-[#0A0A0A]' : '' }}">{{ __('ui.certificates') }}</a>
                @auth
                <a href="{{ route('dashboard') }}" class="font-bold text-sm uppercase tracking-wider text-[#0A0A0A] px-4 py-2 hover:bg-[#FFE000] border-[2px] border-transparent hover:border-[#0A0A0A] transition-all duration-100 {{ request()->routeIs('dashboard') ? 'bg-[#FFE000] !border-[#0A0A0A]' : '' }}">{{ __('ui.dashboard') }}</a>
                @endauth
            </nav>

            <div class="hidden md:flex items-center gap-2">

                {{-- Dil Seçici --}}
                <div class="relative" id="lang-dropdown-wrap">
                    <button id="lang-btn" onclick="toggleLangMenu()"
                        class="flex items-center gap-1.5 px-3 py-2 border-[2px] border-[#0A0A0A] bg-[#F5F0E8] hover:bg-[#FFE000] transition-all duration-100 font-bold text-sm"
                        style="box-shadow:2px 2px 0 #0A0A0A">
                        <span class="text-base">{{ $langs[$currentLocale]['flag'] }}</span>
                        <span class="font-mono text-[10px] uppercase tracking-widest hidden lg:inline">{{ strtoupper($currentLocale) }}</span>
                        <svg class="w-3 h-3" id="lang-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="lang-menu" class="hidden absolute right-0 top-full mt-1 bg-[#F5F0E8] border-[3px] border-[#0A0A0A] z-50 min-w-[160px]" style="box-shadow:4px 4px 0 #0A0A0A">
                        @foreach($langs as $code => $lang)
                        <a href="{{ route('lang.switch', $code) }}"
                            class="flex items-center gap-2.5 px-4 py-2.5 font-bold text-xs uppercase tracking-wider hover:bg-[#FFE000] transition-colors {{ $currentLocale === $code ? 'bg-[#FFE000]' : '' }}">
                            <span class="text-base">{{ $lang['flag'] }}</span>
                            <span>{{ $lang['name'] }}</span>
                            @if($currentLocale === $code)<span class="ml-auto text-[#0A0A0A]">✓</span>@endif
                        </a>
                        @endforeach
                    </div>
                </div>

                @auth
                <div class="relative" id="user-dropdown-wrap">
                    <button id="user-btn" onclick="toggleUserMenu()" class="flex items-center gap-2.5 px-3 py-2 border-[3px] border-[#0A0A0A] bg-[#F5F0E8] hover:bg-[#FFE000] transition-all duration-100 font-bold text-sm uppercase tracking-wider" style="box-shadow:3px 3px 0 #0A0A0A">
                        <div class="w-6 h-6 bg-[#0A0A0A] flex items-center justify-center text-[10px] font-black text-[#FFE000]">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <span class="hidden lg:inline max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                        <svg class="w-3.5 h-3.5 transition-transform" id="user-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="user-menu" class="hidden absolute right-0 top-full mt-2 w-64 bg-[#F5F0E8] border-[3px] border-[#0A0A0A] z-50" style="box-shadow:6px 6px 0 #0A0A0A">
                        <div class="px-4 py-4 border-b-[3px] border-[#0A0A0A] bg-[#0A0A0A]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#FFE000] flex items-center justify-center font-black text-sm text-[#0A0A0A]">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}</div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-black text-sm uppercase tracking-tight text-[#F5F0E8] truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] font-mono text-[#666] truncate">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                @php
                                    $roleColors = ['ADMIN'=>'#FFE000','INSTRUCTOR'=>'#CCFF00','STUDENT'=>'#0047FF'];
                                    $roleLabels = ['ADMIN'=>'YÖNETİCİ','INSTRUCTOR'=>'EĞİTMEN','STUDENT'=>'ÖĞRENCİ','SUPERVISOR'=>'SÜPERVIZÖR'];
                                    $rc = $roleColors[auth()->user()->role] ?? '#0047FF';
                                    $rl = $roleLabels[auth()->user()->role] ?? auth()->user()->role;
                                @endphp
                                <span class="inline-flex items-center text-[10px] font-black uppercase tracking-widest px-2.5 py-1 border-[2px] text-[#0A0A0A]" style="background:{{ $rc }};border-color:{{ $rc }}">{{ $rl }}</span>
                            </div>
                        </div>
                        <div class="py-2">
                            @if(in_array(auth()->user()->role, ['ADMIN','INSTRUCTOR']))
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 font-bold text-xs uppercase tracking-wider hover:bg-[#FFE000] transition-colors">🛡 {{ __('ui.admin_panel') }}</a>
                            @endif
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 font-bold text-xs uppercase tracking-wider hover:bg-[#FFE000] transition-colors">👤 {{ __('ui.student_panel') }}</a>
                        </div>
                        <div class="border-t-[3px] border-[#0A0A0A] p-3">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#FF2D2D] text-white font-black text-xs uppercase tracking-widest border-[3px] border-[#0A0A0A]" style="box-shadow:3px 3px 0 #0A0A0A">
                                    {{ __('ui.logout_button') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="font-bold text-sm uppercase tracking-wider text-[#0A0A0A] px-4 py-2 border-[2px] border-transparent hover:border-[#0A0A0A] transition-all">{{ __('ui.login_button') }}</a>
                <a href="{{ route('register') }}" class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-5 py-2.5 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors" style="box-shadow:3px 3px 0 #0A0A0A">{{ __('ui.start') }} ↗</a>
                @endauth
            </div>

            <button class="md:hidden p-2 border-[2px] border-[#0A0A0A]" id="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
        </div>
    </div>

    {{-- Mobil menü --}}
    <div id="mobile-menu" class="hidden md:hidden border-t-[3px] border-[#0A0A0A] bg-[#F5F0E8]">
        <a href="{{ route('courses.index') }}" class="block px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-[2px] border-[#0A0A0A] hover:bg-[#FFE000]">{{ __('ui.courses') }}</a>
        <a href="{{ route('quizzes.index') }}" class="block px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-[2px] border-[#0A0A0A] hover:bg-[#FFE000]">{{ __('ui.quizzes') }}</a>
        <a href="{{ route('certificates.index') }}" class="block px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-[2px] border-[#0A0A0A] hover:bg-[#FFE000]">{{ __('ui.certificates') }}</a>
        @auth
        <a href="{{ route('dashboard') }}" class="block px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-[2px] border-[#0A0A0A] hover:bg-[#FFE000]">{{ __('ui.dashboard') }}</a>

        {{-- Mobil dil seçici --}}
        <div class="px-6 py-3 border-b-[2px] border-[#0A0A0A]">
          <p class="font-mono text-[9px] uppercase tracking-widest text-[#888] mb-2">DİL / LANGUAGE</p>
          <div class="flex flex-wrap gap-2">
            @foreach($langs as $code => $lang)
            <a href="{{ route('lang.switch', $code) }}"
              class="flex items-center gap-1 px-2 py-1 border-[2px] border-[#0A0A0A] font-bold text-xs {{ $currentLocale === $code ? 'bg-[#FFE000]' : 'bg-white hover:bg-[#FFE000]' }} transition-colors">
              <span>{{ $lang['flag'] }}</span>
              <span class="uppercase font-mono text-[9px]">{{ strtoupper($code) }}</span>
            </a>
            @endforeach
          </div>
        </div>

        <div class="p-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-[#FF2D2D] text-white font-black text-xs uppercase tracking-widest border-[3px] border-[#0A0A0A]">{{ __('ui.logout_button') }}</button>
            </form>
        </div>
        @else
        <div class="px-6 py-3 border-b-[2px] border-[#0A0A0A]">
          <div class="flex flex-wrap gap-2">
            @foreach($langs as $code => $lang)
            <a href="{{ route('lang.switch', $code) }}"
              class="flex items-center gap-1 px-2 py-1 border-[2px] border-[#0A0A0A] font-bold text-xs {{ $currentLocale === $code ? 'bg-[#FFE000]' : 'bg-white hover:bg-[#FFE000]' }} transition-colors">
              <span>{{ $lang['flag'] }}</span>
              <span class="uppercase font-mono text-[9px]">{{ strtoupper($code) }}</span>
            </a>
            @endforeach
          </div>
        </div>
        <div class="p-4 flex gap-3">
            <a href="{{ route('login') }}" class="flex-1 text-center py-2.5 font-bold text-sm uppercase border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#F5F0E8] transition-colors">{{ __('ui.login_button') }}</a>
            <a href="{{ route('register') }}" class="flex-1 text-center bg-[#FFE000] font-black text-xs uppercase tracking-widest border-[3px] border-[#0A0A0A] py-2.5 hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors">{{ __('ui.start') }}</a>
        </div>
        @endauth
    </div>
</header>

<script>
function toggleLangMenu() {
    const menu = document.getElementById('lang-menu');
    const chevron = document.getElementById('lang-chevron');
    const isOpen = !menu.classList.contains('hidden');
    closeAllDropdowns();
    if (!isOpen) {
        menu.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    }
}
function toggleUserMenu() {
    const menu = document.getElementById('user-menu');
    const chevron = document.getElementById('user-chevron');
    if (!menu) return;
    const isOpen = !menu.classList.contains('hidden');
    closeAllDropdowns();
    if (!isOpen) {
        menu.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    }
}
function toggleMobileMenu() {
    document.getElementById('mobile-menu').classList.toggle('hidden');
}
function closeAllDropdowns() {
    ['lang-menu','user-menu'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    });
    ['lang-chevron','user-chevron'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.transform = '';
    });
}
document.addEventListener('click', function(e) {
    if (!document.getElementById('lang-dropdown-wrap')?.contains(e.target) &&
        !document.getElementById('user-dropdown-wrap')?.contains(e.target)) {
        closeAllDropdowns();
    }
});
</script>
