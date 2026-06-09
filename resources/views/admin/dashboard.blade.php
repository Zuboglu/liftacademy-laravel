@extends('layouts.app')
@section('title', 'Yönetim Paneli – LiftAcademy')
@section('content')

<div class="bg-[#F5F0E8] min-h-screen">
  <div class="max-w-[1400px] mx-auto px-6 py-10">

    <div class="mb-10">
      <span class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-[9px] uppercase tracking-widest px-3 py-1.5 inline-block mb-4">YÖNETİM PANELİ</span>
      <h1 class="text-4xl font-black uppercase leading-none tracking-tight">ADMIN<br><span class="text-[#FF2D2D]">DASHBOARD</span></h1>
    </div>

    {{-- İstatistikler --}}
    <div class="grid grid-cols-2 md:grid-cols-5 border-[3px] border-[#0A0A0A] mb-10" style="box-shadow:6px 6px 0 #0A0A0A">
      @php
        $stats = [
          [\App\Models\User::count(),        'KULLANICI',   '👤', 'bg-[#F5F0E8]'],
          [\App\Models\Course::count(),      'KURS',        '📚', 'bg-[#FFE000]'],
          [\App\Models\Quiz::count(),        'SINAV',       '📝', 'bg-[#CCFF00]'],
          [\App\Models\Enrollment::count(),  'KAYIT',       '✅', 'bg-[#F5F0E8]'],
          [\App\Models\Certificate::count(), 'SERTİFİKA',   '🏆', 'bg-[#0A0A0A]'],
        ];
      @endphp
      @foreach($stats as $i => $s)
      <div class="{{ $s[3] }} {{ $s[3]==='bg-[#0A0A0A]' ? 'text-[#FFE000]' : 'text-[#0A0A0A]' }} p-6 {{ $i < 4 ? 'border-r-[3px] border-[#0A0A0A]' : '' }}">
        <span class="text-2xl mb-2 block">{{ $s[2] }}</span>
        <p class="text-3xl font-black leading-none mb-1">{{ $s[0] }}</p>
        <p class="font-mono text-[9px] uppercase tracking-widest opacity-60 mt-1">{{ $s[1] }}</p>
      </div>
      @endforeach
    </div>

    {{-- Modül kartları --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
      @foreach([
        ['Kurs Yönetimi',    'Kurs ekle, bölüm ve ders yönetimi. Video yükleme.',          '📚', 'bg-[#FFE000]',  'admin.courses.index',       false],
        ['Sınav Yönetimi',   'Sınav oluştur, soru ekle, deneme hakkı ve süre ayarla.',     '📝', 'bg-[#CCFF00]',  'admin.quizzes.index',       false],
        ['Sertifikalar',     'Sertifika oluştur, düzenle ve görsel önizleme yap.',         '🏆', 'bg-[#0A0A0A]',  'admin.certificates.index',  false],
        ['Kullanıcılar',     'Kullanıcı listesi, rol yönetimi ve hesap düzenleme.',        '👤', 'bg-[#F5F0E8]',  'admin.users.index',         false],
        ['İlerleme Takibi',  'Öğrenci kayıtlarını ve ders tamamlama durumlarını izle.',   '📊', 'bg-[#FF3CAC]',  'admin.users.progress',      false],
        ['Raporlar',         'Platform kullanım istatistikleri ve başarı oranları.',       '📈', 'bg-[#0047FF]',  'admin.dashboard',            true],
      ] as $card)
      <a href="{{ route($card[4]) }}"
        class="border-[3px] border-[#0A0A0A] p-8 {{ in_array($card[3],['bg-[#0A0A0A]','bg-[#0047FF]']) ? 'bg-[#0A0A0A] text-[#FFE000]' : $card[3].' text-[#0A0A0A]' }} hover:scale-[1.02] transition-transform group relative overflow-hidden"
        style="box-shadow:5px 5px 0 #0A0A0A">
        @if($card[5])
        <div class="absolute top-3 right-3 bg-[#888] text-white font-mono text-[9px] uppercase px-2 py-0.5 tracking-widest">YAKINDA</div>
        @endif
        <span class="text-4xl block mb-4">{{ $card[0] }}</span>
        <p class="font-black text-xl uppercase tracking-tight mb-2">{{ $card[0] }}</p>
        <p class="text-sm font-medium opacity-70">{{ $card[1] }}</p>
        <p class="mt-4 font-black text-xs uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">Yönet ↗</p>
      </a>
      @endforeach
    </div>

    {{-- Son aktivite --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:4px 4px 0 #0A0A0A">
        <div class="bg-[#0A0A0A] px-5 py-3 flex items-center justify-between">
          <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">Son Kurslar</p>
          <a href="{{ route('admin.courses.index') }}" class="font-mono text-[10px] text-[#555] hover:text-[#FFE000] uppercase tracking-widest">Tümü →</a>
        </div>
        @foreach(\App\Models\Course::latest()->take(5)->get() as $c)
        <div class="flex items-center justify-between px-5 py-3 border-b border-[#eee] last:border-b-0 hover:bg-[#fafaf5]">
          <div>
            <p class="font-bold text-sm text-[#0A0A0A]">{{ $c->title }}</p>
            <p class="font-mono text-[10px] text-[#888]">{{ $c->category }}</p>
          </div>
          <div class="flex items-center gap-2">
            @if($c->published)<span class="bg-[#CCFF00] text-[#0A0A0A] font-black text-[9px] uppercase px-2 py-0.5 border border-[#0A0A0A]">YAYINDA</span>
            @else<span class="bg-[#FF2D2D] text-white font-black text-[9px] uppercase px-2 py-0.5">TASLAK</span>@endif
            <a href="{{ route('admin.courses.show', $c->id) }}" class="font-mono text-[10px] text-[#0047FF] hover:underline">Yönet</a>
          </div>
        </div>
        @endforeach
      </div>

      <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:4px 4px 0 #0A0A0A">
        <div class="bg-[#0A0A0A] px-5 py-3 flex items-center justify-between">
          <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">Son Sertifikalar</p>
          <a href="{{ route('admin.certificates.index') }}" class="font-mono text-[10px] text-[#555] hover:text-[#FFE000] uppercase tracking-widest">Tümü →</a>
        </div>
        @foreach(\App\Models\Certificate::with(['user','course'])->latest()->take(5)->get() as $cert)
        @php $sc = match($cert->status){ 'ACTIVE'=>'#CCFF00','EXPIRED'=>'#FF2D2D', default=>'#888' }; @endphp
        <div class="flex items-center justify-between px-5 py-3 border-b border-[#eee] last:border-b-0 hover:bg-[#fafaf5]">
          <div>
            <p class="font-bold text-sm text-[#0A0A0A]">{{ $cert->recipient_name }}</p>
            <p class="font-mono text-[10px] text-[#888]">{{ $cert->cert_number }} · {{ $cert->level }}</p>
          </div>
          <div class="flex items-center gap-2">
            <span class="font-black text-[9px] uppercase px-2 py-0.5 border text-[#0A0A0A]"
              style="background:{{ $sc }};border-color:{{ $sc }}">{{ $cert->status }}</span>
            <a href="{{ route('admin.certificates.show', $cert->id) }}" class="font-mono text-[10px] text-[#0047FF] hover:underline">Görüntüle</a>
          </div>
        </div>
        @endforeach
        @if(\App\Models\Certificate::count() === 0)
        <div class="px-5 py-6 text-center">
          <p class="font-mono text-[10px] text-[#888] uppercase">Henüz sertifika yok</p>
        </div>
        @endif
      </div>
    </div>

  </div>
</div>
@endsection
