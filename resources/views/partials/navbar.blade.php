<header class="sticky top-0 z-50 bg-[#F5F0E8] border-b-[3px] border-[#0A0A0A]">
    <div class="max-w-[1400px] mx-auto px-6">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-[#FFE000] border-[3px] border-[#0A0A0A] flex items-center justify-center font-black text-base" style="box-shadow:3px 3px 0 #0A0A0A">C</div>
                <span class="font-black text-lg uppercase tracking-tight text-[#0A0A0A]">LiftAcademy</span>
            </a>

            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('courses.index') }}" class="font-bold text-sm uppercase tracking-wider text-[#0A0A0A] px-4 py-2 hover:bg-[#FFE000] border-[2px] border-transparent hover:border-[#0A0A0A] transition-all duration-100 {{ request()->routeIs('courses.*') ? 'bg-[#FFE000] !border-[#0A0A0A]' : '' }}">Kurslar</a>
                <a href="{{ route('quizzes.index') }}" class="font-bold text-sm uppercase tracking-wider text-[#0A0A0A] px-4 py-2 hover:bg-[#FFE000] border-[2px] border-transparent hover:border-[#0A0A0A] transition-all duration-100 {{ request()->routeIs('quizzes.*') ? 'bg-[#FFE000] !border-[#0A0A0A]' : '' }}">Sınavlar</a>
                <a href="{{ route('certificates.index') }}" class="font-bold text-sm uppercase tracking-wider text-[#0A0A0A] px-4 py-2 hover:bg-[#FFE000] border-[2px] border-transparent hover:border-[#0A0A0A] transition-all duration-100 {{ request()->routeIs('certificates.*') ? 'bg-[#FFE000] !border-[#0A0A0A]' : '' }}">Sertifikalar</a>
                @auth
                <a href="{{ route('dashboard') }}" class="font-bold text-sm uppercase tracking-wider text-[#0A0A0A] px-4 py-2 hover:bg-[#FFE000] border-[2px] border-transparent hover:border-[#0A0A0A] transition-all duration-100 {{ request()->routeIs('dashboard') ? 'bg-[#FFE000] !border-[#0A0A0A]' : '' }}">Panel</a>
                @endauth
            </nav>

            <div class="hidden md:flex items-center gap-3">
                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2.5 px-3 py-2 border-[3px] border-[#0A0A0A] bg-[#F5F0E8] hover:bg-[#FFE000] transition-all duration-100 font-bold text-sm uppercase tracking-wider" style="box-shadow:3px 3px 0 #0A0A0A">
                        <div class="w-6 h-6 bg-[#0A0A0A] flex items-center justify-center text-[10px] font-black text-[#FFE000]">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <span class="hidden lg:inline max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                        <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 top-full mt-2 w-64 bg-[#F5F0E8] border-[3px] border-[#0A0A0A] z-50" style="box-shadow:6px 6px 0 #0A0A0A">
                        <div class="px-4 py-4 border-b-[3px] border-[#0A0A0A] bg-[#0A0A0A]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#FFE000] flex items-center justify-center font-black text-sm text-[#0A0A0A]">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
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
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 font-bold text-xs uppercase tracking-wider hover:bg-[#FFE000] transition-colors">🛡 Yönetim Paneli</a>
                            @endif
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 font-bold text-xs uppercase tracking-wider hover:bg-[#FFE000] transition-colors">👤 Öğrenci Paneli</a>
                        </div>
                        <div class="border-t-[3px] border-[#0A0A0A] p-3">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#FF2D2D] text-white font-black text-xs uppercase tracking-widest border-[3px] border-[#0A0A0A]" style="box-shadow:3px 3px 0 #0A0A0A">
                                    Çıkış Yap
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="font-bold text-sm uppercase tracking-wider text-[#0A0A0A] px-4 py-2 border-[2px] border-transparent hover:border-[#0A0A0A] transition-all">Giriş</a>
                <a href="{{ route('register') }}" class="btn-brut text-xs py-2.5 px-5">Başla ↗</a>
                @endauth
            </div>

            <button class="md:hidden p-2 border-[2px] border-[#0A0A0A]" onclick="this.nextElementSibling.classList.toggle('hidden')">☰</button>
        </div>
    </div>

    <div class="hidden md:hidden border-t-[3px] border-[#0A0A0A] bg-[#F5F0E8]">
        <a href="{{ route('courses.index') }}" class="block px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-[2px] border-[#0A0A0A] hover:bg-[#FFE000]">Kurslar</a>
        <a href="{{ route('quizzes.index') }}" class="block px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-[2px] border-[#0A0A0A] hover:bg-[#FFE000]">Sınavlar</a>
        <a href="{{ route('certificates.index') }}" class="block px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-[2px] border-[#0A0A0A] hover:bg-[#FFE000]">Sertifikalar</a>
        @auth
        <a href="{{ route('dashboard') }}" class="block px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-[2px] border-[#0A0A0A] hover:bg-[#FFE000]">Panel</a>
        <div class="p-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-[#FF2D2D] text-white font-black text-xs uppercase tracking-widest border-[3px] border-[#0A0A0A]">Çıkış Yap</button>
            </form>
        </div>
        @else
        <div class="p-4 flex gap-3">
            <a href="{{ route('login') }}" class="flex-1 text-center py-2.5 font-bold text-sm uppercase border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#F5F0E8] transition-colors">Giriş</a>
            <a href="{{ route('register') }}" class="flex-1 btn-brut justify-center text-xs py-2.5">Başla</a>
        </div>
        @endauth
    </div>
</header>

<script>
// Alpine.js yoksa basit dropdown
if (typeof Alpine === 'undefined') {
    document.querySelectorAll('[x-data]').forEach(el => {
        const btn = el.querySelector('button');
        const menu = el.querySelector('[x-show]');
        if (btn && menu) {
            menu.style.display = 'none';
            btn.addEventListener('click', e => {
                e.stopPropagation();
                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
            });
            document.addEventListener('click', () => { menu.style.display = 'none'; });
        }
    });
}
</script>
