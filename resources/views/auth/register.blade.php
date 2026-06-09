@extends('layouts.auth')
@section('title', 'Kayıt Ol – LiftAcademy')
@section('content')

<div class="min-h-screen bg-[#F5F0E8] flex">
  {{-- Sol Panel --}}
  <div class="hidden lg:flex lg:w-2/5 xl:w-1/2 bg-[#0A0A0A] flex-col justify-between p-12 relative overflow-hidden">
    <div class="relative z-10">
      <a href="{{ route('home') }}" class="flex items-center gap-3 mb-16">
        <div class="w-10 h-10 bg-[#FFE000] border-[3px] border-[#FFE000] flex items-center justify-center font-black text-[#0A0A0A] text-lg">C</div>
        <span class="font-black text-xl text-[#F5F0E8] uppercase">LiftAcademy</span>
      </a>
      <div class="mb-10">
        <h1 class="text-5xl font-black text-[#F5F0E8] uppercase leading-none tracking-tight mb-4">
          SERTİFİKA<br><span class="text-[#CCFF00]">YOLCULUĞU</span><br>BAŞLIYOR
        </h1>
        <p class="text-[#888] font-medium leading-relaxed">Junior'dan Trainer'a 5 kademe.</p>
      </div>
      <div class="space-y-0 border-[3px] border-[#444]">
        @foreach([
          ['01','Junior Operatör','4 kurs','bg-[#CCFF00]','text-[#0A0A0A]'],
          ['02','Operatör','7 kurs','border-b border-[#222]','text-[#F5F0E8]'],
          ['03','Senior Operatör','10 kurs','border-b border-[#222]','text-[#F5F0E8]'],
          ['04','Supervisor','14 kurs','border-b border-[#222]','text-[#F5F0E8]'],
          ['05','Trainer','18 kurs','bg-[#FFE000]','text-[#0A0A0A]'],
        ] as $i => $lvl)
        <div class="{{ $lvl[3] }} {{ $lvl[4] }} px-5 py-3 flex items-center justify-between {{ $i < 4 ? 'border-b-[2px] border-[#222]' : '' }}">
          <div class="flex items-center gap-3">
            <span class="text-[10px] font-mono opacity-40">{{ $lvl[0] }}</span>
            <span class="font-black uppercase tracking-tight text-sm">{{ $lvl[1] }}</span>
          </div>
          <span class="text-[10px] font-mono opacity-50">{{ $lvl[2] }}</span>
        </div>
        @endforeach
      </div>
    </div>
    <div class="relative z-10">
      <p class="text-[#444] text-xs font-mono uppercase tracking-widest">Uluslararası Standartlarda Sertifikasyon</p>
    </div>
    <div class="absolute inset-0 opacity-5" style="background-image:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2240%22 height=%2240%22><rect x=%220%22 y=%220%22 width=%2240%22 height=%2240%22 fill=%22none%22 stroke=%22white%22 stroke-width=%220.5%22/></svg>')"></div>
  </div>

  {{-- Sağ Panel --}}
  <div class="flex-1 flex flex-col justify-center px-8 md:px-16 xl:px-24 py-12 overflow-y-auto">
    <div class="w-full max-w-md mx-auto">
      <div class="lg:hidden flex items-center gap-3 mb-10">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
          <div class="w-9 h-9 bg-[#FFE000] border-[3px] border-[#0A0A0A] flex items-center justify-center font-black text-[#0A0A0A]">C</div>
          <span class="font-black text-lg text-[#0A0A0A] uppercase">LiftAcademy</span>
        </a>
      </div>

      <div class="mb-8">
        <span class="tag-black mb-4 inline-block">KAYIT OL</span>
        <h2 class="text-3xl font-black uppercase tracking-tight text-[#0A0A0A]">Hesap oluştur</h2>
        <p class="text-sm text-[#888] mt-2 font-medium">Ücretsiz başla, sertifika kazan.</p>
      </div>

      @if($errors->any())
      <div class="mb-6 p-4 bg-[#FF2D2D] text-white border-[3px] border-[#0A0A0A] font-bold text-sm">
        <ul class="list-disc pl-4 space-y-1">
          @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
      </div>
      @endif

      <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm">
        @csrf
        <div>
          <label for="name" class="text-mono-sm mb-2 block">AD SOYAD</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" required
            placeholder="Adınız Soyadınız" class="input-brut">
        </div>
        <div>
          <label for="email" class="text-mono-sm mb-2 block">E-POSTA</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required
            placeholder="ornek@firma.com" class="input-brut">
        </div>
        <div>
          <label for="password" class="text-mono-sm mb-2 block">ŞİFRE</label>
          <input id="password" type="password" name="password" required
            placeholder="En az 8 karakter" class="input-brut" oninput="checkStrength(this.value)">
          <div class="mt-2 h-1.5 border border-[#ccc] overflow-hidden">
            <div id="strengthBar" class="h-full w-0 transition-all duration-300 bg-[#FF2D2D]"></div>
          </div>
          <p id="strengthText" class="text-[10px] font-mono uppercase tracking-wider text-[#888] mt-1"></p>
        </div>
        <div>
          <label for="password_confirmation" class="text-mono-sm mb-2 block">ŞİFRE TEKRAR</label>
          <input id="password_confirmation" type="password" name="password_confirmation" required
            placeholder="Şifreyi tekrarlayın" class="input-brut">
        </div>
        <div>
          <label class="text-mono-sm mb-2 block">ROL</label>
          <select name="role" class="input-brut">
            <option value="STUDENT" {{ old('role','STUDENT')==='STUDENT'?'selected':'' }}>Öğrenci</option>
            <option value="INSTRUCTOR" {{ old('role')==='INSTRUCTOR'?'selected':'' }}>Eğitmen</option>
          </select>
        </div>
        <div class="flex items-start gap-2">
          <input type="checkbox" id="terms" name="terms" required class="mt-1 w-4 h-4 border-[2px] border-[#0A0A0A] shrink-0">
          <label for="terms" class="text-xs font-medium text-[#555] cursor-pointer">
            <span class="underline font-bold text-[#0A0A0A]">Kullanım Koşulları</span> ve <span class="underline font-bold text-[#0A0A0A]">Gizlilik Politikası</span>'nı kabul ediyorum
          </label>
        </div>
        <button type="submit" class="btn-brut-dark w-full justify-center py-4 text-sm">
          Hesap Oluştur ↗
        </button>
        <p class="text-center text-sm font-medium text-[#888]">
          Zaten hesabın var mı?
          <a href="{{ route('login') }}" class="text-[#0A0A0A] font-bold underline hover:text-[#0047FF]">Giriş yap</a>
        </p>
      </form>
    </div>
  </div>
</div>

<script>
function checkStrength(v) {
  const bar = document.getElementById('strengthBar');
  const txt = document.getElementById('strengthText');
  let score = 0;
  if (v.length >= 8) score++;
  if (/[A-Z]/.test(v)) score++;
  if (/[0-9]/.test(v)) score++;
  if (/[^A-Za-z0-9]/.test(v)) score++;
  const levels = [['0%','',''],['25%','Zayıf','#FF2D2D'],['50%','Orta','#FF9500'],['75%','İyi','#FFE000'],['100%','Güçlü','#CCFF00']];
  const [w,l,c] = levels[score];
  bar.style.width = w;
  bar.style.background = c;
  txt.textContent = l;
  txt.style.color = c || '#888';
}
</script>
@endsection
