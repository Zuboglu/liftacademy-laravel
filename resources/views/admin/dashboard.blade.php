@extends('layouts.app')
@section('title', 'Yönetim Paneli – LiftAcademy')
@section('content')

<div class="bg-[#F5F0E8] min-h-screen">
  <div class="max-w-[1400px] mx-auto px-6 py-10">

    <div class="mb-10">
      <span class="tag-black mb-4 inline-block">YÖNETİM PANELİ</span>
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
        <p class="text-mono-sm opacity-60">{{ $s[1] }}</p>
      </div>
      @endforeach
    </div>

    {{-- Modül kartları --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach([
        ['Kurs Yönetimi',   'Kurs ekle, bölüm ve ders yönetimi. Video yükleme.',     '📚', 'bg-[#FFE000]',  'admin.courses.index'],
        ['Sınav Yönetimi',  'Sınav oluştur, soru ekle, deneme hakkı ve süre ayarla.','📝', 'bg-[#CCFF00]',  'admin.quizzes.index'],
        ['Sertifikalar',    'Otomatik ve manuel sertifikaları görüntüle ve yönet.',   '🏆', 'bg-[#0A0A0A]',  'certificates.index'],
        ['Kullanıcılar',    'Kullanıcı listesi, rol yönetimi ve hesap durumu.',       '👤', 'bg-[#F5F0E8]',  'admin.dashboard'],
        ['Kayıtlar',        'Öğrenci kayıtlarını ve ilerleme durumlarını izle.',      '✅', 'bg-[#FF3CAC]',  'admin.dashboard'],
        ['Raporlar',        'Platform kullanım istatistikleri ve başarı oranları.',   '📊', 'bg-[#0047FF]',  'admin.dashboard'],
      ] as $card)
      <a href="{{ route($card[4]) }}"
        class="border-[3px] border-[#0A0A0A] p-8 {{ $card[2]==='🏆' ? 'bg-[#0A0A0A] text-[#FFE000]' : $card[3].' text-[#0A0A0A]' }} hover-lift group">
        <span class="text-4xl block mb-4">{{ $card[2] }}</span>
        <p class="font-black text-xl uppercase tracking-tight mb-2">{{ $card[0] }}</p>
        <p class="text-sm font-medium opacity-70">{{ $card[1] }}</p>
        <p class="mt-4 font-black text-xs uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">Yönet ↗</p>
      </a>
      @endforeach
    </div>

    {{-- Son aktivite --}}
    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:4px 4px 0 #0A0A0A">
        <div class="bg-[#0A0A0A] px-5 py-3"><p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">Son Kurslar</p></div>
        @foreach(\App\Models\Course::latest()->take(5)->get() as $c)
        <div class="flex items-center justify-between px-5 py-3 border-b border-[#eee] last:border-b-0">
          <div>
            <p class="font-bold text-sm text-[#0A0A0A]">{{ $c->title }}</p>
            <p class="font-mono text-[10px] text-[#888]">{{ $c->category }}</p>
          </div>
          <div class="flex items-center gap-2">
            @if($c->published)<span class="tag-lime text-[9px]">YAYINDA</span>@else<span class="tag-red text-[9px]">TASLAK</span>@endif
            <a href="{{ route('admin.courses.show', $c->id) }}" class="font-mono text-[10px] text-[#0047FF] hover:underline">Yönet</a>
          </div>
        </div>
        @endforeach
      </div>

      <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:4px 4px 0 #0A0A0A">
        <div class="bg-[#0A0A0A] px-5 py-3"><p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">Son Sınavlar</p></div>
        @foreach(\App\Models\Quiz::with('course')->latest()->take(5)->get() as $q)
        <div class="flex items-center justify-between px-5 py-3 border-b border-[#eee] last:border-b-0">
          <div>
            <p class="font-bold text-sm text-[#0A0A0A]">{{ $q->title }}</p>
            <p class="font-mono text-[10px] text-[#888]">{{ $q->course->title ?? 'Genel' }} · %{{ $q->passing_score }} geçer</p>
          </div>
          <a href="{{ route('admin.quizzes.show', $q->id) }}" class="font-mono text-[10px] text-[#0047FF] hover:underline">Yönet</a>
        </div>
        @endforeach
      </div>
    </div>

  </div>
</div>
@endsection
