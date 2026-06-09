@extends('layouts.app')
@section('title', 'Öğrenci Paneli – LiftAcademy')
@section('content')

<div class="bg-[#F5F0E8] min-h-screen">
  <div class="max-w-[1400px] mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-6 mb-10">
      <div>
        <span class="tag-black mb-4 inline-block">ÖĞRENCİ PANELİ</span>
        <h1 class="text-4xl font-black uppercase leading-none tracking-tight">
          HOŞGELDİN,<br><span class="text-[#0047FF]">{{ strtoupper(auth()->user()->name) }}</span>
        </h1>
      </div>
      <a href="{{ route('courses.index') }}" class="btn-brut text-sm px-6 py-3">Yeni Kurs Bul ↗</a>
    </div>

    {{-- İstatistik Kartları --}}
    <div class="grid grid-cols-2 md:grid-cols-4 border-[3px] border-[#0A0A0A] mb-10" style="box-shadow:6px 6px 0 #0A0A0A">
      @foreach([
        [$stats['enrollments'],'KAYITLI KURS','📚','bg-[#FFE000]','text-[#0A0A0A]'],
        [$stats['completed'],'TAMAMLANAN','✅','bg-[#CCFF00]','text-[#0A0A0A]'],
        [$stats['certificates'],'SERTİFİKA','🏆','bg-[#0A0A0A]','text-[#FFE000]'],
        [$stats['progress'].'%','ORT. İLERLEME','📊','bg-[#F5F0E8]','text-[#0A0A0A]'],
      ] as $i => $s)
      <div class="{{ $s[3] }} {{ $s[4] }} p-6 {{ $i < 3 ? 'border-r-[3px] border-[#0A0A0A]' : '' }}">
        <span class="text-2xl mb-2 block">{{ $s[2] }}</span>
        <p class="text-3xl font-black leading-none mb-1">{{ $s[0] }}</p>
        <p class="text-mono-sm opacity-60">{{ $s[1] }}</p>
      </div>
      @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8">

      {{-- Kurslarım --}}
      <div>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-black uppercase tracking-tight">KURSLARIM</h2>
          <a href="{{ route('courses.index') }}" class="text-mono-sm text-[#888] hover:text-[#0047FF]">Tümünü gör →</a>
        </div>

        @forelse($enrollments as $enrollment)
        @php $course = $enrollment->course; $prog = $enrollment->progress_percent ?? 0; @endphp
        <div class="border-[3px] border-[#0A0A0A] mb-4 hover-lift-sm">
          <div class="flex items-start gap-5 p-5">
            <div class="w-14 h-14 bg-[#0A0A0A] border-[3px] border-[#0A0A0A] flex items-center justify-center shrink-0 text-2xl text-[#FFE000]">
              🏗️
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-start gap-2 mb-2">
                <h3 class="font-black text-sm uppercase tracking-tight text-[#0A0A0A] leading-snug">{{ $course->title }}</h3>
                @if($enrollment->completed_at)
                <span class="tag-lime text-[10px]">TAMAMLANDI</span>
                @endif
              </div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-mono-sm text-[#888]">{{ $prog }}% TAMAMLANDI</span>
                <span class="text-mono-sm text-[#888]">{{ $enrollment->lessons_completed ?? 0 }}/{{ $course->sections->sum(fn($s) => $s->lessons->count()) }} DERS</span>
              </div>
              <div class="h-2.5 border-[2px] border-[#0A0A0A] bg-[#e0e0e0]">
                <div class="h-full {{ $prog >= 100 ? 'bg-[#CCFF00]' : ($prog > 0 ? 'bg-[#FFE000]' : 'bg-[#e0e0e0]') }} transition-all"
                  style="width:{{ $prog }}%"></div>
              </div>
            </div>
          </div>
          <div class="border-t-[3px] border-[#0A0A0A] px-5 py-3 flex items-center justify-between bg-[#fafaf7]">
            <span class="text-mono-sm text-[#888]">SON: {{ $enrollment->updated_at->diffForHumans() }}</span>
            <a href="{{ route('courses.learn', $course->slug) }}" class="btn-brut text-[10px] py-2 px-4">
              {{ $prog > 0 ? 'Devam Et →' : 'Başla ↗' }}
            </a>
          </div>
        </div>
        @empty
        <div class="text-center py-16 border-[3px] border-dashed border-[#ccc]">
          <p class="text-4xl mb-4">📚</p>
          <p class="font-black text-xl uppercase tracking-tight mb-2">Henüz kurs yok</p>
          <p class="text-[#888] font-medium mb-6">İlk kursuna kaydol!</p>
          <a href="{{ route('courses.index') }}" class="btn-brut">Kurslara Bak ↗</a>
        </div>
        @endforelse
      </div>

      {{-- Sertifikalar Sidebar --}}
      <div>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-black uppercase tracking-tight">SERTİFİKALARIM</h2>
          <a href="{{ route('certificates.index') }}" class="text-mono-sm text-[#888] hover:text-[#0047FF]">Tümü →</a>
        </div>
        @forelse($certificates as $cert)
        <div class="border-[3px] border-[#0A0A0A] mb-3 hover-lift-sm bg-[#0A0A0A]">
          <div class="p-4">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 bg-[#FFE000] border-[2px] border-[#FFE000] flex items-center justify-center font-black text-xl">🏆</div>
              <div class="min-w-0 flex-1">
                <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-tight leading-snug truncate">{{ $cert->course->title ?? 'Sertifika' }}</p>
                <p class="text-mono-sm text-[#444]">{{ $cert->issued_at->format('d.m.Y') }}</p>
              </div>
            </div>
            <p class="text-[10px] font-mono text-[#444] mb-3">{{ $cert->certificate_number }}</p>
            <a href="{{ route('certificates.show', $cert->id) }}" class="btn-brut text-[10px] py-2 px-4 w-full justify-center">Görüntüle ↗</a>
          </div>
        </div>
        @empty
        <div class="border-[3px] border-dashed border-[#ccc] p-8 text-center">
          <p class="text-3xl mb-3">🏆</p>
          <p class="font-black text-sm uppercase tracking-tight mb-1">Sertifika yok</p>
          <p class="text-[#888] text-xs font-medium">Kurs tamamla, sertifika kazan.</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
