@extends('layouts.app')
@section('title', 'Sınavlar – LiftAcademy')
@section('content')

<div class="min-h-screen bg-[#F5F0E8]">

  {{-- Hero --}}
  <div class="bg-[#0A0A0A] border-b-[3px] border-[#FFE000] px-6 py-12">
    <div class="max-w-[1400px] mx-auto">
      <div class="flex items-center gap-3 mb-8">
        <span class="tag-yellow text-[10px]">{{ $quizzes->total() }} SINAV</span>
        <span class="tag-lime text-[10px]">100 PUAN SİSTEMİ</span>
      </div>
      <h1 class="font-black text-[#F5F0E8] uppercase tracking-tight leading-none mb-4"
        style="font-size:clamp(2.5rem,5vw,5rem);letter-spacing:-0.04em">
        YETKİNLİK<br><span class="text-[#FFE000]">SINAVLARI</span>
      </h1>
      <p class="text-[#555] font-medium max-w-xl text-sm leading-relaxed">
        Kursu tamamladıktan sonra sınava giriş hakkı açılır. Soruların tümü çoktan seçmeli, puanlama 100 üzerinden yapılır.
        Geçer notla bitirince sertifikanız otomatik oluşturulur.
      </p>
    </div>
  </div>

  <div class="max-w-[1400px] mx-auto px-6 py-10">

    @if(session('success'))
    <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-4 mb-6 font-bold text-sm uppercase tracking-wide">
      ✓ {{ session('success') }}
    </div>
    @endif

    @if($quizzes->isEmpty())
    <div class="text-center py-20 border-[3px] border-[#0A0A0A]" style="box-shadow:6px 6px 0 #0A0A0A">
      <p class="font-black text-xl uppercase tracking-tight text-[#0A0A0A] mb-2">Henüz sınav yok</p>
      <a href="{{ route('courses.index') }}" class="btn-brut-dark text-xs py-2.5">Kurslara Bak ↗</a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($quizzes as $quiz)
      @php
        $attempt = null;
        $bestScore = null;
        $passed = false;
        if(auth()->check()) {
          $attempts = \App\Models\QuizAttempt::where('user_id', auth()->id())->where('quiz_id', $quiz->id)->get();
          $bestScore = $attempts->max('score');
          $passed = $attempts->where('passed', true)->count() > 0;
        }
      @endphp
      <a href="{{ route('quizzes.show', $quiz->id) }}"
        class="group border-[3px] border-[#0A0A0A] bg-[#F5F0E8] flex flex-col overflow-hidden hover-lift-sm">
        {{-- Header --}}
        <div class="{{ $passed ? 'bg-[#CCFF00] text-[#0A0A0A]' : 'bg-[#0A0A0A] text-[#F5F0E8]' }} px-5 py-4 border-b-[3px] border-[#0A0A0A] flex items-center justify-between">
          <span class="font-mono text-[10px] uppercase tracking-widest opacity-60">
            {{ $quiz->course->title ?? 'GENEL' }}
          </span>
          @if($passed)
          <span class="font-black text-[10px] uppercase tracking-widest">✓ GEÇTİ</span>
          @elseif($bestScore !== null)
          <span class="font-black text-[10px] text-[#FFE000] uppercase">%{{ $bestScore }}</span>
          @else
          <span class="font-mono text-[10px] opacity-40">YENİ</span>
          @endif
        </div>

        {{-- İçerik --}}
        <div class="p-5 flex-1 flex flex-col gap-3">
          <h3 class="font-black text-sm uppercase tracking-tight text-[#0A0A0A] leading-snug group-hover:text-[#0047FF] transition-colors">
            {{ $quiz->title }}
          </h3>
          @if($quiz->description)
          <p class="text-mono-sm text-[#888] leading-relaxed line-clamp-2">{{ $quiz->description }}</p>
          @endif
          <div class="flex flex-wrap gap-3 mt-auto pt-3 border-t-[2px] border-[#eee]">
            <div class="flex items-center gap-1.5">
              <span class="text-mono-sm text-[#888]">{{ $quiz->questions_count }} soru</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="text-mono-sm text-[#888]">Geçer: %{{ $quiz->passing_score }}</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="text-mono-sm text-[#888]">{{ $quiz->attempts }} hak</span>
            </div>
            @if($quiz->time_limit)
            <div class="flex items-center gap-1.5">
              <span class="text-mono-sm text-[#888]">{{ $quiz->time_limit }} dak.</span>
            </div>
            @endif
          </div>
        </div>
      </a>
      @endforeach
    </div>
    <div class="mt-10">{{ $quizzes->links() }}</div>
    @endif
  </div>
</div>
@endsection
