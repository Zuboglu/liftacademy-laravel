@extends('layouts.auth')
@section('title', 'Giriş – LiftAcademy')
@section('content')

<div class="min-h-screen bg-[#F5F0E8] flex">
  {{-- Sol Panel --}}
  <div class="hidden lg:flex lg:w-2/5 xl:w-1/2 bg-[#0A0A0A] flex-col justify-between p-12 relative overflow-hidden">
    <div class="relative z-10">
      <a href="{{ route('home') }}" class="flex items-center gap-3 mb-16">
        <div class="w-10 h-10 bg-[#FFE000] border-[3px] border-[#FFE000] flex items-center justify-center font-black text-[#0A0A0A] text-lg">C</div>
        <span class="font-black text-xl text-[#F5F0E8] uppercase">LiftAcademy</span>
      </a>
      <div class="mb-12">
        <h1 class="text-5xl font-black text-[#F5F0E8] uppercase leading-none tracking-tight mb-4">
          TEKRAR<br><span class="text-[#FFE000]">HOŞGELDİN</span>
        </h1>
        <p class="text-[#888] font-medium leading-relaxed">Eğitimine kaldığın yerden devam et.</p>
      </div>
      <div class="grid grid-cols-2 gap-4">
        @foreach([['1.2K+','Kayıtlı Operatör','bg-[#FFE000]','text-[#0A0A0A]'],['%97','Geçme Oranı','border-[2px] border-[#444]','text-[#F5F0E8]'],['135+','Aktif Kurs','border-[2px] border-[#444]','text-[#F5F0E8]'],['3.4K+','Sertifika','bg-[#CCFF00]','text-[#0A0A0A]']] as $s)
        <div class="{{ $s[2] }} {{ $s[3] }} p-4">
          <p class="text-2xl font-black leading-none">{{ $s[0] }}</p>
          <p class="text-xs font-bold uppercase tracking-widest opacity-60 mt-1">{{ $s[1] }}</p>
        </div>
        @endforeach
      </div>
    </div>
    <div class="relative z-10">
      <div class="flex flex-wrap gap-2">
        @foreach(['VİNÇ EĞİTİMİ','İSG','SERTİFİKA','SİMÜLASYON','OPERATİF SAHA'] as $t)
        <span class="text-[10px] font-bold uppercase tracking-widest text-[#444] border border-[#222] px-3 py-1">{{ $t }}</span>
        @endforeach
      </div>
    </div>
    <div class="absolute inset-0 opacity-5" style="background-image:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2240%22 height=%2240%22><rect x=%220%22 y=%220%22 width=%2240%22 height=%2240%22 fill=%22none%22 stroke=%22white%22 stroke-width=%220.5%22/></svg>')"></div>
  </div>

  {{-- Sağ Panel --}}
  <div class="flex-1 flex flex-col justify-center px-8 md:px-16 xl:px-24 py-12">
    <div class="w-full max-w-md mx-auto">
      <div class="lg:hidden flex items-center gap-3 mb-10">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
          <div class="w-9 h-9 bg-[#FFE000] border-[3px] border-[#0A0A0A] flex items-center justify-center font-black text-[#0A0A0A]">C</div>
          <span class="font-black text-lg text-[#0A0A0A] uppercase">LiftAcademy</span>
        </a>
      </div>

      <div class="mb-8">
        <span class="tag-black mb-4 inline-block">GİRİŞ YAP</span>
        <h2 class="text-3xl font-black uppercase tracking-tight text-[#0A0A0A]">Hesabına giriş yap</h2>
        <p class="text-sm text-[#888] mt-2 font-medium">Demo için aşağıdaki bilgileri kullanabilirsin.</p>
      </div>

      {{-- Demo Kartlar --}}
      <div class="grid grid-cols-2 gap-3 mb-8">
        @foreach([['ÖĞRENCİ','student@liftacademy.com','student123','bg-[#0047FF]','text-white'],['ADMIN','admin@liftacademy.com','admin123','bg-[#FFE000]','text-[#0A0A0A]']] as $d)
        <button type="button" onclick="fillDemo('{{ $d[1] }}','{{ $d[2] }}')"
          class="{{ $d[3] }} {{ $d[4] }} border-[3px] border-[#0A0A0A] p-3 text-left hover-lift-sm">
          <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">{{ $d[0] }}</p>
          <p class="text-xs font-bold truncate">{{ $d[1] }}</p>
          <p class="text-[10px] font-mono opacity-60">{{ $d[2] }}</p>
        </button>
        @endforeach
      </div>

      @if(session('error'))
      <div class="mb-6 p-4 bg-[#FF2D2D] text-white border-[3px] border-[#0A0A0A] font-bold text-sm">
        {{ session('error') }}
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
          <label for="email" class="text-mono-sm mb-2 block">E-POSTA</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
            placeholder="ornek@firma.com" class="input-brut @error('email') border-[#FF2D2D] @enderror">
          @error('email')<p class="text-[#FF2D2D] text-xs font-bold mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <div class="flex items-center justify-between mb-2">
            <label for="password" class="text-mono-sm">ŞİFRE</label>
          </div>
          <input id="password" type="password" name="password" required placeholder="••••••••" class="input-brut @error('password') border-[#FF2D2D] @enderror">
          @error('password')<p class="text-[#FF2D2D] text-xs font-bold mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-2">
          <input type="checkbox" id="remember" name="remember" class="w-4 h-4 border-[2px] border-[#0A0A0A] appearance-none checked:bg-[#FFE000] cursor-pointer">
          <label for="remember" class="text-sm font-bold cursor-pointer">Beni hatırla</label>
        </div>
        <button type="submit" class="btn-brut-dark w-full justify-center py-4 text-sm">
          Giriş Yap ↗
        </button>
        <p class="text-center text-sm font-medium text-[#888]">
          Hesabın yok mu?
          <a href="{{ route('register') }}" class="text-[#0A0A0A] font-bold underline hover:text-[#0047FF]">Kayıt ol</a>
        </p>
      </form>
    </div>
  </div>
</div>

<script>
function fillDemo(email, password) {
  document.getElementById('email').value = email;
  document.getElementById('password').value = password;
}
</script>
@endsection
