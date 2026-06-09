@extends('layouts.app')
@section('title', 'Kurslar – LiftAcademy')
@section('content')

<div class="min-h-screen bg-[#F5F0E8]">

  {{-- Hero banner --}}
  <div class="bg-[#0A0A0A] border-b-[3px] border-[#FFE000] px-6 py-12">
    <div class="max-w-[1400px] mx-auto">

      {{-- Marka badge --}}
      <div class="flex items-center gap-3 mb-8">
        <div class="flex items-center gap-0 border-[3px] border-[#FFE000]" style="box-shadow:3px 3px 0 #FFE000">
          <div class="w-12 h-12 bg-[#FFE000] flex items-center justify-center font-black text-[#0A0A0A] text-xl">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
              <path d="M4 3 L11 16 L18 3" stroke="#0A0A0A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
              <circle cx="11" cy="19" r="2" fill="#0A0A0A"/>
              <path d="M11 16 L11 17" stroke="#0A0A0A" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="px-4 py-2 border-l-[3px] border-[#FFE000]">
            <p class="font-black text-[10px] uppercase tracking-widest text-[#888]">LiftAcademy</p>
            <p class="font-black text-sm uppercase tracking-tight text-[#F5F0E8] leading-tight">Operatör Eğitimi</p>
          </div>
        </div>
        <span class="tag-yellow text-[10px]">{{ $total }} AKTİF KURS</span>
      </div>

      <h1 class="font-black text-[#F5F0E8] uppercase tracking-tight leading-none mb-6"
        style="font-size:clamp(2.5rem,5vw,5rem);letter-spacing:-0.04em">
        TÜM<br><span class="text-[#FFE000]">KURSLAR</span>
      </h1>

      {{-- Arama --}}
      <form method="GET" action="{{ route('courses.index') }}" class="flex max-w-lg">
        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
        <div class="relative flex-1">
          <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#888] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
          </svg>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Kurs veya eğitmen ara..."
            class="w-full pl-11 pr-4 py-3 text-sm font-medium text-[#0A0A0A] bg-[#F5F0E8] border-[3px] border-[#F5F0E8] outline-none placeholder:text-[#888] focus:border-[#FFE000] transition-colors">
        </div>
        <button type="submit" class="btn-brut text-xs py-0 px-5 border-l-0">ARA</button>
      </form>
    </div>
  </div>

  <div class="max-w-[1400px] mx-auto px-6 py-10">

    {{-- Kategori filtreleri --}}
    <div class="flex flex-wrap gap-2 mb-10">
      @foreach($categories as $slug => $label)
      @php $isActive = request('category') === $slug || ($slug === 'all' && !request('category')); @endphp
      <a href="{{ $slug === 'all' ? route('courses.index', request()->only('q')) : route('courses.index', array_merge(request()->only('q'), ['category' => $slug])) }}"
        class="flex items-center gap-1.5 px-4 py-2 font-black text-xs uppercase tracking-widest border-[3px] border-[#0A0A0A] transition-all duration-100 {{ $isActive ? 'bg-[#FFE000] text-[#0A0A0A]' : 'bg-white text-[#555] hover:bg-[#FFE000] hover:text-[#0A0A0A]' }}"
        style="{{ $isActive ? 'box-shadow:3px 3px 0 #0A0A0A' : '' }}">
        {{ $label }}
      </a>
      @endforeach
    </div>

    {{-- Sonuçlar --}}
    @if($courses->isEmpty())
    <div class="text-center py-20 border-[3px] border-[#0A0A0A]" style="box-shadow:6px 6px 0 #0A0A0A">
      <p class="font-black text-xl uppercase tracking-tight text-[#0A0A0A] mb-2">Kurs bulunamadı</p>
      <p class="text-mono-sm text-[#888] mb-6">
        @if(request('q'))"{{ request('q') }}" için sonuç yok.@else Bu kategoride henüz yayında kurs yok.@endif
      </p>
      <a href="{{ route('courses.index') }}" class="btn-brut-dark text-xs py-2.5">Tüm Kurslara Bak ↗</a>
    </div>
    @else
    @php
      $categoryColors = [
        'SAFETY'        => ['bg-[#FF2D2D]', 'text-white',      '🦺'],
        'CRANE_TYPE'    => ['bg-[#0047FF]', 'text-white',      '🏗️'],
        'OPERATION'     => ['bg-[#FFE000]', 'text-[#0A0A0A]', '⚙️'],
        'TECHNICAL'     => ['bg-[#0047FF]', 'text-white',      '🔧'],
        'RISK'          => ['bg-[#CCFF00]', 'text-[#0A0A0A]', '⚠️'],
        'CERTIFICATION' => ['bg-[#0A0A0A]', 'text-[#FFE000]', '🪪'],
        'COMPANY'       => ['bg-[#FF3CAC]', 'text-white',      '📋'],
      ];
      $fallbackBg   = ['bg-[#FF2D2D]','bg-[#0047FF]','bg-[#FFE000]','bg-[#CCFF00]','bg-[#FF3CAC]','bg-[#0A0A0A]'];
      $fallbackText = ['text-white','text-white','text-[#0A0A0A]','text-[#0A0A0A]','text-white','text-[#FFE000]'];
      $levelTag   = ['BEGINNER'=>'tag-lime','INTERMEDIATE'=>'tag-yellow','ADVANCED'=>'tag-red','ALL_LEVELS'=>'tag-blue'];
      $levelLabel = ['BEGINNER'=>'Başlangıç','INTERMEDIATE'=>'Orta','ADVANCED'=>'İleri','ALL_LEVELS'=>'Her Seviye'];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      @foreach($courses as $i => $c)
      @php
        $cat = $c->category ?? '';
        if (isset($categoryColors[$cat])) {
          [$bg, $tx, $emoji] = $categoryColors[$cat];
        } else {
          $bg    = $fallbackBg[$i % count($fallbackBg)];
          $tx    = $fallbackText[$i % count($fallbackText)];
          $emoji = '📚';
        }
        $ltag   = $levelTag[$c->level]   ?? 'tag-black';
        $llabel = $levelLabel[$c->level] ?? $c->level;
      @endphp
      <a href="{{ route('courses.show', $c->slug) }}"
        class="group border-[3px] border-[#0A0A0A] bg-[#F5F0E8] flex flex-col overflow-hidden hover-lift-sm">

        {{-- Thumbnail --}}
        <div class="{{ $bg }} {{ $tx }} h-32 flex items-center justify-between p-5 border-b-[3px] border-[#0A0A0A]">
          <span class="text-4xl">{{ $emoji }}</span>
          <span class="text-mono-sm opacity-60">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</span>
        </div>

        {{-- İçerik --}}
        <div class="p-4 flex-1 flex flex-col gap-3">
          <div class="flex flex-wrap gap-1.5">
            @if($c->is_mandatory)<span class="tag-red text-[10px]">ZORUNLU</span>@endif
            <span class="{{ $ltag }} text-[10px]">{{ $llabel }}</span>
          </div>
          <h3 class="font-black text-xs uppercase tracking-tight text-[#0A0A0A] leading-snug group-hover:text-[#0047FF] transition-colors">
            {{ $c->title }}
          </h3>
          <p class="text-mono-sm text-[#888]">{{ $c->instructor->name ?? '—' }}</p>
          <div class="flex items-center justify-between mt-auto border-t-[2px] border-[#eee] pt-3">
            <span class="text-mono-sm text-[#888]">{{ $c->sections_count ?? $c->sections->count() }} modül</span>
            <span class="text-mono-sm text-[#888]">{{ $c->enrollments_count ?? 0 }} öğrenci</span>
          </div>
        </div>
      </a>
      @endforeach
    </div>
    <div class="mt-10">{{ $courses->links() }}</div>
    @endif
  </div>
</div>
@endsection
