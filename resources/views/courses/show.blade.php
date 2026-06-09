@extends('layouts.app')
@section('title', '{{ $course->title }} – LiftAcademy')
@section('content')

@php
  $categoryColors = [
    'SAFETY'        => ['bg-[#FF2D2D]', 'text-white',      '🦺'],
    'CRANE_TYPE'    => ['bg-[#0047FF]', 'text-white',      '🏗️'],
    'OPERATION'     => ['bg-[#FFE000]', 'text-[#0A0A0A]', '⚙️'],
    'TECHNICAL'     => ['bg-[#0A0A0A]', 'text-[#F5F0E8]', '🔧'],
    'RISK'          => ['bg-[#CCFF00]', 'text-[#0A0A0A]', '⚠️'],
    'CERTIFICATION' => ['bg-[#0A0A0A]', 'text-[#FFE000]', '🪪'],
    'COMPANY'       => ['bg-[#FF3CAC]', 'text-white',      '📋'],
  ];
  $categoryLabels = [
    'SAFETY'=>'İSG & Güvenlik','CRANE_TYPE'=>'Vinç Türleri','OPERATION'=>'Operasyon',
    'TECHNICAL'=>'Teknik','RISK'=>'Risk Yönetimi','CERTIFICATION'=>'Sertifikasyon','COMPANY'=>'Firma İçi',
  ];
  $levelTag   = ['BEGINNER'=>'tag-lime','INTERMEDIATE'=>'tag-yellow','ADVANCED'=>'tag-red','ALL_LEVELS'=>'tag-blue'];
  $levelLabel = ['BEGINNER'=>'Başlangıç','INTERMEDIATE'=>'Orta','ADVANCED'=>'İleri','ALL_LEVELS'=>'Her Seviye'];

  $cat      = $course->category ?? '';
  $heroBg   = $categoryColors[$cat][0] ?? 'bg-[#0A0A0A]';
  $heroText = $categoryColors[$cat][1] ?? 'text-[#F5F0E8]';
  $emoji    = $categoryColors[$cat][2] ?? '📚';
  $catLabel = $categoryLabels[$cat] ?? $cat;
  $ltag     = $levelTag[$course->level]   ?? 'tag-black';
  $llabel   = $levelLabel[$course->level] ?? $course->level;

  $totalLessons = $course->sections->sum(fn($s) => $s->lessons->count());
  $totalSecs    = $course->sections->flatMap->lessons->sum('duration');

  // Süre formatlama
  $fmtDuration = function($secs) {
    if (!$secs) return '—';
    $m = floor($secs / 60);
    return $m >= 60 ? floor($m/60).'s '.($m%60).'dk' : $m.' dk';
  };
@endphp

<div class="min-h-screen bg-[#F5F0E8]">

  {{-- Hero --}}
  <div class="{{ $heroBg }} {{ $heroText }} border-b-[3px] border-[#0A0A0A]">
    <div class="max-w-[1400px] mx-auto px-6 py-10 grid md:grid-cols-3 gap-10">
      <div class="md:col-span-2">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-mono-sm opacity-60 mb-4">
          <a href="{{ route('courses.index') }}" class="hover:opacity-100 transition-opacity">Kurslar</a>
          <span>/</span>
          <span>{{ $catLabel }}</span>
        </div>

        {{-- Badges --}}
        <div class="flex flex-wrap gap-2 mb-4">
          @if($course->is_mandatory)<span class="tag-black text-[10px]">⚠ ZORUNLU EĞİTİM</span>@endif
          <span class="{{ $ltag }} text-[10px]">{{ $llabel }}</span>
          <span class="tag-black text-[10px]">{{ $catLabel }}</span>
        </div>

        <div class="flex items-start gap-4 mb-4">
          <span class="text-5xl">{{ $emoji }}</span>
          <h1 class="font-black uppercase tracking-tight leading-tight"
            style="font-size:clamp(1.8rem,4vw,3.5rem);letter-spacing:-0.03em">
            {{ $course->title }}
          </h1>
        </div>

        @if($course->description)
        <p class="opacity-80 mb-6 leading-relaxed max-w-xl">{{ $course->description }}</p>
        @endif

        <div class="flex flex-wrap items-center gap-5 text-mono-sm opacity-70">
          <span class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            {{ number_format($course->enrollments_count ?? 0) }} öğrenci
          </span>
          <span class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            {{ $fmtDuration($totalSecs) }}
          </span>
          <span class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            {{ $totalLessons }} ders
          </span>
        </div>
        <p class="text-mono-sm opacity-50 mt-3">
          Eğitmen: <span class="opacity-100 font-bold">{{ $course->instructor->name ?? '—' }}</span>
        </p>
      </div>

      {{-- Kayıt Kartı --}}
      <div class="bg-[#F5F0E8] text-[#0A0A0A] border-[3px] border-[#0A0A0A] overflow-hidden" style="box-shadow:8px 8px 0 #0A0A0A">
        {{-- Renkli emoji banner --}}
        <div class="{{ $heroBg }} {{ $heroText }} h-28 flex items-center justify-center text-6xl border-b-[3px] border-[#0A0A0A]">
          {{ $emoji }}
        </div>
        <div class="p-5">
          <p class="font-black text-[#0A0A0A] mb-1">
            <span class="tag-lime text-sm px-3 py-1">KURUMSAL EĞİTİM</span>
          </p>
          @if($course->is_mandatory)
          <p class="text-mono-sm text-[#FF2D2D] font-bold mb-4 mt-2">⚠ BU EĞİTİM ZORUNLUDUR</p>
          @endif

          @if($isEnrolled)
          <a href="{{ route('courses.learn', $course->slug) }}" class="btn-brut-dark w-full justify-center py-4 text-sm mb-2 mt-3">
            Derse Devam Et →
          </a>
          <div class="mt-3 mb-2">
            <div class="flex items-center justify-between mb-1">
              <span class="text-mono-sm">İLERLEME</span>
              <span class="font-black text-sm">{{ $progress }}%</span>
            </div>
            <div class="h-3 bg-[#e0e0e0] border-[2px] border-[#0A0A0A]">
              <div class="h-full bg-[#CCFF00] border-r-[2px] border-[#0A0A0A] transition-all" style="width:{{ $progress }}%"></div>
            </div>
          </div>
          @else
          <form action="{{ route('courses.enroll', $course->slug) }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn-brut-dark w-full justify-center py-4 text-sm mb-2">
              Kursa Kayıt Ol ↗
            </button>
          </form>
          <a href="{{ route('login') }}" class="block text-center text-mono-sm text-[#888] hover:text-[#0A0A0A] transition-colors py-2">
            Zaten hesabın var mı? Giriş yap
          </a>
          @endif

          <div class="border-t-[3px] border-[#0A0A0A] mt-4 pt-4 space-y-2">
            @foreach([
              ['✓','Ömür boyu erişim'],
              ['✓','Sertifika (min. %'.($course->passing_score ?? 70).')'],
              ['✓',$totalLessons.' ders · '.$course->sections->count().' modül'],
              ['✓','İSG uyumlu müfredat'],
            ] as $f)
            <div class="flex items-center gap-2 text-xs font-medium text-[#0A0A0A]">
              <span class="w-4 h-4 bg-[#CCFF00] border-[2px] border-[#0A0A0A] flex items-center justify-center text-[10px] font-black shrink-0">{{ $f[0] }}</span>
              {{ $f[1] }}
            </div>
            @endforeach
          </div>

          @if($course->quizzes->count())
          @php $quiz = $course->quizzes->first(); @endphp
          <div class="border-t-[3px] border-[#0A0A0A] mt-4 pt-4">
            <p class="text-mono-sm mb-2">DEĞERLENDİRME</p>
            <div class="space-y-1.5 text-xs font-medium text-[#555]">
              <div class="flex justify-between"><span>Soru sayısı</span><span class="font-black text-[#0A0A0A]">{{ $quiz->questions->count() }}</span></div>
              <div class="flex justify-between"><span>Süre</span><span class="font-black text-[#0A0A0A]">{{ $quiz->time_limit ? $quiz->time_limit.' dk' : '—' }}</span></div>
              <div class="flex justify-between"><span>Geçme notu</span><span class="font-black text-[#0A0A0A]">%{{ $quiz->passing_score }}</span></div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Body --}}
  <div class="max-w-[1400px] mx-auto px-6 py-10 grid md:grid-cols-3 gap-10">
    <div class="md:col-span-2 space-y-8">

      {{-- Simülasyon --}}
      @if($course->simulations->count())
      @php $sim = $course->simulations->first(); @endphp
      <div class="bg-[#0A0A0A] border-[3px] border-[#0A0A0A] p-6" style="box-shadow:5px 5px 0 #FF3CAC">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-[#FF3CAC] text-xl">🛡</span>
          <h2 class="font-black text-lg uppercase tracking-tight text-[#F5F0E8]">Senaryo Simülasyonu</h2>
          <span class="tag-pink text-[10px]">Zorluk: {{ $sim->difficulty }}</span>
        </div>
        <p class="text-[#555] font-medium leading-relaxed">{{ $sim->scenario }}</p>
      </div>
      @endif

      {{-- Müfredat --}}
      <div>
        <div class="flex items-center justify-between mb-5">
          <h2 class="font-black text-lg uppercase tracking-tight text-[#0A0A0A]">Kurs İçeriği</h2>
          <span class="text-mono-sm text-[#888]">{{ $course->sections->count() }} modül · {{ $totalLessons }} ders · {{ $fmtDuration($totalSecs) }}</span>
        </div>
        <div class="space-y-3">
          @forelse($course->sections->sortBy('order') as $si => $section)
          @php
            $isQuizSection = $section->lessons->contains(fn($l) => in_array($l->type, ['QUIZ','SIMULATION']));
          @endphp
          <div class="border-[3px] border-[#0A0A0A] overflow-hidden" style="box-shadow:3px 3px 0 #0A0A0A">
            <div class="px-5 py-3.5 border-b-[3px] border-[#0A0A0A] flex items-center justify-between {{ $isQuizSection ? 'bg-[#0047FF] text-white' : 'bg-[#0A0A0A] text-[#F5F0E8]' }}">
              <div class="flex items-center gap-2">
                <span>{{ $isQuizSection ? '📊' : '▶' }}</span>
                <h3 class="font-black text-sm uppercase tracking-tight">{{ $section->title }}</h3>
              </div>
              <span class="text-mono-sm opacity-60">{{ $section->lessons->count() }} öğe</span>
            </div>
            <ul class="divide-y divide-[#eee]">
              @foreach($section->lessons->sortBy('order') as $lesson)
              <li class="px-5 py-3 flex items-center justify-between hover:bg-[#FFE000]/10 transition-colors">
                <div class="flex items-center gap-3">
                  @if($lesson->type==='VIDEO')<span class="text-[#888] text-sm">▶</span>
                  @elseif($lesson->type==='QUIZ')<span class="text-[#0047FF] text-sm">📊</span>
                  @elseif($lesson->type==='SIMULATION')<span class="text-[#FF3CAC] text-sm">🛡</span>
                  @else<span class="text-[#FFE000] text-sm">📄</span>@endif
                  <span class="text-sm font-medium text-[#0A0A0A]">{{ $lesson->title }}</span>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                  <span class="text-mono-sm text-[#888]">{{ $fmtDuration($lesson->duration) }}</span>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
          @empty
          <p class="text-[#888] font-medium">Henüz ders eklenmemiş.</p>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Sidebar --}}
    <div class="hidden md:block">
      <div class="sticky top-24 space-y-4">

        {{-- Sertifika bilgisi --}}
        <div class="border-[3px] border-[#0A0A0A] p-5" style="box-shadow:4px 4px 0 #FFE000">
          <h3 class="font-black text-sm uppercase tracking-tight text-[#0A0A0A] mb-4 flex items-center gap-2">
            🏆 Sertifika Bilgisi
          </h3>
          <p class="text-xs font-medium text-[#555] leading-relaxed mb-4">
            Kursu tamamlayan ve %{{ $course->passing_score ?? 70 }} üzeri puan alan kursiyerlere otomatik dijital sertifika verilir.
          </p>
          <div class="space-y-2">
            @foreach([['Geçme notu','%'.($course->passing_score ?? 70)],['Geçerlilik','2 yıl'],['Doğrulanabilir','Evet']] as [$label,$value])
            <div class="flex justify-between text-xs border-b-[2px] border-[#eee] pb-2">
              <span class="font-medium text-[#555]">{{ $label }}</span>
              <span class="font-black text-[#0A0A0A]">{{ $value }}</span>
            </div>
            @endforeach
          </div>
        </div>

        {{-- Zorunlu kurslar --}}
        @if($mandatoryCourses->count())
        <div class="bg-[#0A0A0A] border-[3px] border-[#0A0A0A] p-5">
          <h3 class="font-black text-sm uppercase tracking-tight text-[#FFE000] mb-4">Zorunlu Kurslar</h3>
          @foreach($mandatoryCourses as $mc)
          <a href="{{ route('courses.show', $mc->slug) }}"
            class="flex items-center gap-2 py-2 text-mono-sm {{ $mc->slug === $course->slug ? 'text-[#FFE000] font-bold' : 'text-[#444] hover:text-[#FFE000]' }} transition-colors">
            <span class="text-[#FFE000] shrink-0">⚠</span>
            <span>{{ $mc->title }}</span>
          </a>
          @endforeach
        </div>
        @endif

        {{-- Eğitmen --}}
        <div class="border-[3px] border-[#0A0A0A] p-5">
          <h3 class="font-black text-sm uppercase tracking-tight text-[#0A0A0A] mb-3">Eğitmen</h3>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#0A0A0A] flex items-center justify-center font-black text-[#FFE000] text-sm">
              {{ strtoupper(substr($course->instructor->name ?? '?', 0, 2)) }}
            </div>
            <div>
              <p class="font-black text-xs uppercase tracking-tight">{{ $course->instructor->name ?? '—' }}</p>
              <p class="text-mono-sm text-[#888]">Eğitmen</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
