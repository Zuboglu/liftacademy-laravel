@extends('layouts.app')
@section('title', __('ui.quiz_result_title') . ' – LiftAcademy')
@section('content')

<div class="min-h-screen bg-[#F5F0E8]">
  <div class="max-w-[800px] mx-auto px-6 py-12">

    {{-- Sonuç banner --}}
    <div class="border-[3px] border-[#0A0A0A] mb-8 overflow-hidden" style="box-shadow:8px 8px 0 #0A0A0A">
      <div class="{{ $attempt->passed ? 'bg-[#CCFF00]' : 'bg-[#FF2D2D]' }} px-8 py-10 text-center">
        <p class="font-mono text-[10px] uppercase tracking-widest {{ $attempt->passed ? 'text-[#0A0A0A]' : 'text-white' }} opacity-60 mb-3">
          {{ $attempt->quiz->title }}
        </p>
        <div class="text-8xl font-black {{ $attempt->passed ? 'text-[#0A0A0A]' : 'text-white' }} leading-none mb-2">
          %{{ $attempt->score }}
        </div>
        <p class="font-black text-xl uppercase tracking-tight {{ $attempt->passed ? 'text-[#0A0A0A]' : 'text-white' }}">
          {{ $attempt->passed ? __('ui.success') : __('ui.fail') }}
        </p>
      </div>
      <div class="bg-[#0A0A0A] px-8 py-5 grid grid-cols-3 gap-4 text-center">
        <div>
          <p class="font-black text-2xl text-[#CCFF00]">{{ $attempt->correct_count }}</p>
          <p class="font-mono text-[9px] text-[#555] uppercase tracking-widest mt-1">{{ __('ui.result_correct') }}</p>
        </div>
        <div>
          <p class="font-black text-2xl text-[#FF2D2D]">{{ $attempt->total_questions - $attempt->correct_count }}</p>
          <p class="font-mono text-[9px] text-[#555] uppercase tracking-widest mt-1">{{ __('ui.result_wrong') }}</p>
        </div>
        <div>
          <p class="font-black text-2xl text-[#FFE000]">{{ $attempt->total_questions }}</p>
          <p class="font-mono text-[9px] text-[#555] uppercase tracking-widest mt-1">{{ __('ui.result_total') }}</p>
        </div>
      </div>
    </div>

    {{-- Sertifika oluşturuldu --}}
    @if($certificate)
    <div class="border-[3px] border-[#FFE000] bg-[#FFE000] p-6 mb-8" style="box-shadow:6px 6px 0 #0A0A0A">
      <p class="font-black text-lg uppercase tracking-tight text-[#0A0A0A] mb-1">🏆 {{ __('ui.cert_created') }}</p>
      <p class="text-sm font-medium text-[#0A0A0A] opacity-70 mb-3">
        {{ __('ui.cert_no') }}: <strong>{{ $certificate->cert_number }}</strong>
      </p>
      <a href="{{ route('certificates.show', $certificate->id) }}" class="btn-brut-dark text-xs py-2.5 px-5 inline-flex">
        {{ __('ui.view_cert') }} ↗
      </a>
    </div>
    @endif

    {{-- Soru bazlı sonuç --}}
    <div class="border-[3px] border-[#0A0A0A] mb-8" style="box-shadow:4px 4px 0 #0A0A0A">
      <div class="bg-[#0A0A0A] px-5 py-3">
        <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">{{ __('ui.question_analysis') }}</p>
      </div>
      @foreach($attempt->quiz->questions as $qi => $q)
      @php
        $given = $attempt->answers[$q->id] ?? null;
        $correct = (int)$q->correct_answer;
        $isCorrect = !is_null($given) && (int)$given === $correct;
      @endphp
      <div class="px-5 py-4 border-b border-[#eee] last:border-b-0">
        <div class="flex items-start gap-3 mb-3">
          <span class="shrink-0 w-6 h-6 flex items-center justify-center font-black text-[10px] {{ $isCorrect ? 'bg-[#CCFF00] text-[#0A0A0A]' : 'bg-[#FF2D2D] text-white' }}">
            {{ $isCorrect ? '✓' : '✕' }}
          </span>
          <p class="text-sm font-bold text-[#0A0A0A] leading-snug">{{ $q->question }}</p>
        </div>
        <div class="ml-9 space-y-1.5">
          @foreach($q->options as $oi => $opt)
          @php
            $isGiven   = !is_null($given) && (int)$given === $oi;
            $isCorrectOpt = $oi === $correct;
          @endphp
          <div class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium
            {{ $isCorrectOpt ? 'bg-[#CCFF00] text-[#0A0A0A] border-[2px] border-[#0A0A0A]' : '' }}
            {{ $isGiven && !$isCorrectOpt ? 'bg-[#FF2D2D] text-white border-[2px] border-[#FF2D2D]' : '' }}
            {{ !$isCorrectOpt && !$isGiven ? 'text-[#888]' : '' }}">
            <span class="font-mono text-[10px] shrink-0">{{ chr(65+$oi) }}</span>
            <span>{{ $opt }}</span>
            @if($isCorrectOpt)<span class="ml-auto font-black text-[10px]">{{ __('ui.correct_answer') }}</span>@endif
            @if($isGiven && !$isCorrectOpt)<span class="ml-auto font-black text-[10px]">{{ __('ui.selected_answer') }}</span>@endif
          </div>
          @endforeach
          @if($q->explanation)
          <p class="mt-2 text-xs text-[#555] font-medium bg-[#f0f0e8] border-l-4 border-[#FFE000] px-3 py-2">
            💡 {{ $q->explanation }}
          </p>
          @endif
        </div>
      </div>
      @endforeach
    </div>

    {{-- Butonlar --}}
    <div class="flex flex-wrap gap-3">
      <a href="{{ route('quizzes.show', $attempt->quiz->id) }}" class="btn-brut-dark text-xs py-3 px-6">{{ __('ui.back_to_quiz') }}</a>
      <a href="{{ route('quizzes.index') }}" class="btn-brut text-xs py-3 px-6">{{ __('ui.all_quizzes') }}</a>
      @if($attempt->quiz->course)
      <a href="{{ route('courses.show', $attempt->quiz->course->slug) }}" class="border-[3px] border-[#0A0A0A] bg-transparent text-[#0A0A0A] font-black text-xs uppercase tracking-widest py-3 px-6 hover:bg-[#0A0A0A] hover:text-[#F5F0E8] transition-colors">
        {{ __('ui.go_course') }} ↗
      </a>
      @endif
    </div>

  </div>
</div>
@endsection
