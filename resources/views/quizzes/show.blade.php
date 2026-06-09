@extends('layouts.app')
@section('title', $quiz->title . ' – LiftAcademy')
@section('content')

<div class="min-h-screen bg-[#F5F0E8]">
  <div class="max-w-[900px] mx-auto px-6 py-12">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 mb-8 text-mono-sm text-[#888]">
      <a href="{{ route('quizzes.index') }}" class="hover:text-[#0A0A0A] transition-colors">Sınavlar</a>
      <span>→</span>
      <span class="text-[#0A0A0A]">{{ $quiz->title }}</span>
    </div>

    @if(session('error'))
    <div class="border-[3px] border-[#FF2D2D] bg-[#FF2D2D] text-white p-4 mb-6 font-bold text-sm uppercase tracking-wide">
      ✕ {{ session('error') }}
    </div>
    @endif

    {{-- Sınav Başlık Kartı --}}
    <div class="border-[3px] border-[#0A0A0A] mb-6" style="box-shadow:6px 6px 0 #0A0A0A">
      <div class="bg-[#0A0A0A] px-6 py-5 flex items-start justify-between gap-4">
        <div>
          @if($quiz->course)
          <p class="font-mono text-[10px] text-[#555] uppercase tracking-widest mb-2">{{ $quiz->course->title }}</p>
          @endif
          <h1 class="font-black text-[#F5F0E8] uppercase tracking-tight text-2xl leading-tight">{{ $quiz->title }}</h1>
        </div>
        @if($passed)
        <span class="shrink-0 bg-[#CCFF00] text-[#0A0A0A] font-black text-[10px] uppercase tracking-widest px-3 py-1.5 border-[2px] border-[#CCFF00]">✓ GEÇTİ</span>
        @endif
      </div>
      <div class="bg-[#F5F0E8] px-6 py-5">
        @if($quiz->description)
        <p class="text-sm text-[#555] font-medium leading-relaxed mb-5">{{ $quiz->description }}</p>
        @endif
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          @foreach([
            [$quiz->questions->count(),'SORU SAYISI'],
            [$quiz->passing_score.'%','GEÇER NOT'],
            [$quiz->attempts,'DENEME HAKKI'],
            [$quiz->time_limit ? $quiz->time_limit.' dak.' : 'Süresiz','SÜRE'],
          ] as $stat)
          <div class="border-[2px] border-[#0A0A0A] p-3 text-center">
            <p class="font-black text-xl leading-none text-[#0A0A0A]">{{ $stat[0] }}</p>
            <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest mt-1">{{ $stat[1] }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Video kilidi uyarısı --}}
    @if(!$videoCompleted)
    <div class="border-[3px] border-[#FF2D2D] bg-[#FF2D2D] text-white p-5 mb-6">
      <p class="font-black text-sm uppercase tracking-wide mb-1">🔒 Sınav Kilitli</p>
      <p class="text-sm font-medium opacity-90">Sınava girebilmek için kursa ait tüm videoları izlemeniz gerekiyor.</p>
      @if($quiz->course)
      <a href="{{ route('courses.learn', $quiz->course->slug) }}" class="inline-block mt-3 bg-white text-[#FF2D2D] font-black text-xs uppercase tracking-widest px-4 py-2 border-[2px] border-white">
        Kursa Git ↗
      </a>
      @endif
    </div>
    @endif

    {{-- CTA --}}
    @auth
      @if($videoCompleted && $canTake)
      <div class="flex gap-3 mb-8">
        <a href="{{ route('quizzes.take', $quiz->id) }}"
          class="btn-brut text-sm px-8 py-4">
          Sınava Başla ↗
        </a>
      </div>
      @elseif(!$canTake && !$passed)
      <div class="border-[3px] border-[#FFE000] bg-[#FFE000] p-4 mb-8 font-bold text-sm uppercase tracking-wide">
        ⚠ Deneme hakkınız doldu. En yüksek puanınız: %{{ $bestScore ?? 0 }}
      </div>
      @endif
    @else
    <div class="border-[3px] border-[#0A0A0A] p-5 mb-8 flex items-center justify-between gap-4">
      <p class="font-bold text-sm text-[#0A0A0A]">Sınava girebilmek için giriş yapmalısınız.</p>
      <a href="{{ route('login') }}" class="btn-brut text-xs py-2.5 px-5">Giriş Yap ↗</a>
    </div>
    @endauth

    {{-- Geçmiş denemeler --}}
    @if(count($userAttempts) > 0)
    <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:4px 4px 0 #0A0A0A">
      <div class="bg-[#0A0A0A] px-5 py-3 flex items-center justify-between">
        <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">Geçmiş Denemeler</p>
        <span class="font-mono text-[10px] text-[#555]">{{ count($userAttempts) }} / {{ $quiz->attempts }}</span>
      </div>
      @foreach($userAttempts as $i => $att)
      <div class="flex items-center justify-between px-5 py-3 border-b border-[#eee] last:border-b-0">
        <div class="flex items-center gap-4">
          <span class="font-mono text-[10px] text-[#888]">#{{ $i+1 }}</span>
          <span class="font-mono text-[10px] text-[#888]">{{ $att->finished_at?->format('d.m.Y H:i') }}</span>
          <span class="font-mono text-[10px] text-[#888]">{{ $att->correct_count }}/{{ $att->total_questions }} doğru</span>
        </div>
        <div class="flex items-center gap-3">
          <span class="font-black text-sm {{ $att->passed ? 'text-[#00a000]' : 'text-[#FF2D2D]' }}">
            %{{ $att->score }}
          </span>
          <span class="font-black text-[10px] uppercase tracking-widest {{ $att->passed ? 'tag-lime' : 'tag-red' }}">
            {{ $att->passed ? 'GEÇTI' : 'KALDI' }}
          </span>
          <a href="{{ route('quizzes.result', $att->id) }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] border-b border-dashed border-[#ccc]">Detay</a>
        </div>
      </div>
      @endforeach
    </div>
    @endif

  </div>
</div>
@endsection
